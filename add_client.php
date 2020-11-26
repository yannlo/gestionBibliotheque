<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php'); 
include('function/get_matricule.php');
include('function/connexion_bdd.php');

if(isset($_POST['firstName']) AND (isset($_POST['lastName'])) AND (isset($_POST['Birthdate'])) AND (isset($_POST['type_user'])) AND (isset($_POST['mail'])) AND (isset($_POST['password'])) AND (isset($_POST['contact'])) AND (isset($_POST['sexe_user']))){

    $add_user_request= $bdd -> prepare('INSERT INTO all_comptes ( id_type_compte, first_name, last_name, email, id_sexe_compte,  pass_word, birth_date, contact1 , creation_date)
                                        VALUES ( :id_type_compte, :first_name, :last_name, :email, :id_sexe_compte , :pass_word, :birth_date, :contact1, CURDATE()) ');

    $pass_hashed = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);

    $convert_birth_date = preg_replace('#^([0-9]{2})/([0-9]{2})/([0-9]{4})$#', '$1-$2-$3', $_POST['Birthdate']);
    
    $add_user = $bdd -> prepare('INSERT INTO users (matricule, id_type_user ) VALUE (:matricule, :id_type_user)');

    $verif_email = $bdd -> prepare('SELECT * FROM all_comptes WHERE email = :email AND id_type_compte = 2');
    $verif_email  -> execute(array(
        'email' => $_POST['mail']
    ));
    $compte = $verif_email -> rowCount();
    if($compte != 0) {
      header("Location: add_client.php");
      $_SESSION['error'] = 1;
      exit();
    }


    $add_user_request -> execute(array(
        'id_type_compte' => '2',
        'first_name' => htmlspecialchars($_POST['firstName']),
        'last_name' => htmlspecialchars($_POST['lastName']),
        'email' => htmlspecialchars($_POST['mail']),
        'id_sexe_compte' => htmlspecialchars($_POST['sexe_user']),
        'pass_word' => $pass_hashed,
        'birth_date'=> $convert_birth_date,
        'contact1' => htmlspecialchars($_POST['contact'])
    ));

    $take_id = $bdd -> prepare('SELECT id FROM all_comptes WHERE email = :email');
    $take_id -> execute(array(
        'email' =>  htmlspecialchars($_POST['mail'])
    ));
    $id_user = 0;
    while ($id_user_list = $take_id->fetch()){
        $id_user = (int) $id_user_list['id'];
    }

    $matricule = getMatricule($id_user);

    $add_user -> execute(array(
        'matricule' =>  $matricule,
        'id_type_user' => $_POST['type_user']      

    ));   

    $get_user_in_user_list = $bdd -> query("SELECT id FROM users ORDER BY id DESC LIMIT 1");

    $id_user_in_user = 0;

    while ($id_user_list = $get_user_in_user_list->fetch()){

        $id_user_in_user = $id_user_list['id'];
    }
    

    $update_user = $bdd -> query("UPDATE all_comptes SET id_other_information = $id_user_in_user  WHERE id = $id_user ");    
    

    if(isset($_POST['contact2']) AND !empty($_POST['contact2'])){
        $update_user = $bdd -> prepare("UPDATE all_comptes SET contact2 = :contact  WHERE id = $id_user ");
        $update_user -> execute(array(
            'contact' => htmlspecialchars($_POST['contact2'])
        ));
    }



    if (!empty($_FILES)){
        
        $image = $_FILES['photo_user'];
        

        if($image['error'] == 0 ){
            
            $ext_image = strtolower(substr($image['name'], -3));
            
            $allow_ext = array('jpg', 'gif', 'png');
            
            if (in_array($ext_image, $allow_ext)) {
                
                // $nom_oeuvre = htmlspecialchars($_POST['nom_oeuvre']);
                
                $fichier_partiel_nom = str_replace(' ','_',$id_user);
                
                $fichier_final_nom = (string)($fichier_partiel_nom.".".$ext_image);
                
                move_uploaded_file($image['tmp_name'], "imageAndLogo/photo_user/".$fichier_final_nom);
                
                $add_cover_image = $bdd -> prepare("UPDATE users SET nom_photo_user = :nom_photo_user ORDER BY id DESC LIMIT 1");

                $add_cover_image -> execute(array(

                    'nom_photo_user'=> $fichier_final_nom
                ));

            
            }
        
        }
    }


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html 
	xmlns="http://www.w3.org/1999/xhtml"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd"
	xml:lang="fr"
>
	<head>
		<meta http-equiv="content-type" content="text/html" charset="utf-8" />
		<title>documentation de livre - Gestionnaire </title>
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
        <link rel="stylesheet" href="style6.css"/>
        <link rel="stylesheet" href="documentation_books/style_document5.css"/>
        <link rel="stylesheet" href="general-style-element.css"/>
        <link rel="stylesheet" href="gestionClient/client_style_3.css"/>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>

	<body>
		
		<div class="container">

			<header>

				<?php  include("headerAndFooter/menu.php") ?>

            </header>

            <div class="center">

                <h1>Ajout de client</h1>
                <section id='confirmation'>
                    <h2>Message de confirmation de creation de compte</h2>
                    <p>
                        Le compte du client a bien été crée.
                    </p>
                </section>


            </div>
        
        <?php include('headerAndFooter/footer.php'); ?>
        
    </div>
    <script>
        const ver1 = document.getElementById('formulaire_ajout_exemplaire');
        ver1.style.display = 'block';
        ver1.style.height= '60vh';
    </script>
    <script>
		const identifation_page ='client';
       	actived_link_page(identifation_page);
	</script>

</body>
</html>

<?php   
}else{
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html 
	xmlns="http://www.w3.org/1999/xhtml"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd"
	xml:lang="fr"
>
	<head>
		<meta http-equiv="content-type" content="text/html" charset="utf-8" />
		<title>documentation de livre - Gestionnaire </title>
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
        <link rel="stylesheet" href="style6.css"/>
        <link rel="stylesheet" href="documentation_books/style_document5.css"/>
        <link rel="stylesheet" href="general-style-element.css"/>
        <link rel="stylesheet" href="gestionClient/client_style_3.css"/>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>

	<body>
		
		<div class="container">

			<header>

				<?php  include("headerAndFooter/menu.php") ?>

            </header>

            <div class="center">

                <h1>Ajout de client</h1>

                <section id="nouveauClient">

        <h2>Enregistrer un nouveau client:</h2>
        <form method="POST" action="#" enctype="multipart/form-data">

            <fieldset id="form-parti1">
                <legend>information personnel </legend>

                <p>
                    <label for="photo_user">Ajouter la couverture de l'oeuvre</label>
                    <img src="imageAndLogo/uncknown_user.png" alt="icon absence de photo de converture"   id="output"/>
                    <input type="file" name="photo_user" id="photo_user" accept="image/*" onchange="loadFile(event)"  />
                 </p>

                <p>
                    <label for="firstName">Entrer votre nom:</label>
                    <input type="text" name="firstName" id="firstName" placeholder="Nom" required="required" />
                </p>
                <p>
                    <label for="lastName">Entrer votre prenom:</label>
                    <input type="text" name="lastName" id="lastName" placeholder="Prenom" required="required" />
                </p>
                <p>
                    <label for="Birthdate">Entrer votre date de naissance:</label>
                    <input type="date" name="Birthdate" id="Birthdate" required="" />
                </p>
                <p>
                    <label for="sexe_user">Entrer votre sexe:</label>
                    <select  name="sexe_user" id="sexe_user" required="required" >

                <?php 
                include('function/connexion_bdd.php');

                $type_users = $bdd->query('SELECT * FROM sexe_compte  ORDER BY id');
                while($donnee = $type_users->fetch() ){
                    echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';
                }

                ?>

                </select>
                </p>
                <p>
                    <label for="type_user">Entrer votre statut:</label>
                    <select  name="type_user" id="type_user" required="required" >

                <?php 
                include('function/connexion_bdd.php');

                $type_users = $bdd->query('SELECT * FROM type_users  ORDER BY nom');
                while($donnee = $type_users->fetch() ){
                    echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';
                }

                ?>

                </select>
                </p>

            </fieldset>

            <fieldset id="form-parti2">
                <legend>email et mot de passe </legend>
                <p>
                    <label for="mail">Entrer votre email:</label>
                    <input type="email" name="mail" id="mail" placeholder="mon.email@exemple.com"
                        required="required" />
                </p>
                <p>
                    <label for="password">Entrer votre mot de passe:</label>
                    <input type="password" name="password" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="le mot de passe doit contenir au moins : 1 lettre minuscule, 1, lettre majuscule, 1 chiffre, 8 caracteres" required="required" />
                    <div id="message1">
                        <h3>le mot de passe doit contenir:</h3>
                        <p id="letter" class="invalid">1 lettre <b>minuscule</b> </p>
                        <p id="capital" class="invalid">1 lettre <b>majuscule</b> </p>
                        <p id="number" class="invalid">1 <b>nombre</b></p>
                        <p id="length" class="invalid">Minimum 8 <b>caracteres</b></p>
                     </div>
                </p>
                <p>
                    <label for="passwordVerif">Entrer votre mot de passe:</label>
                    <input type="password" name="passwordVerif" id="passwordVerif" required="required" />
                    <div id="message2">
                        <h3>le mot de passe est:</h3>
                        <p id="ident"> <b>indentique</b> </p>
                        <p id="dif"> <b>different</b> </p>
                       
                     </div>
                </p>
            </fieldset>

            <fieldset id="form-parti3">
                <legend>contacts </legend>
                <p>
                    <label for="contact">Entrer votre contact 1:</label>
                    <input type="tel" name="contact" id="contact" placeholder="01 02 03 04" required="required" title='Format: XX XX XX XX' pattern="[0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}"
                    />
                </p>
                <p>
                    <label for="contact2">Entrer votre contact 2:</label>
                    <input type="tel" name="contact2" id="contact2" placeholder="01 02 03 04" title='Format: XX XX XX XX' pattern="[0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}"/>
                </p>
                <input type="submit" name="valider" id="valider" value="valider" />
            </fieldset>
        </form>

        </section>

            

            </div>


            <?php include('headerAndFooter/footer.php'); ?>



        </div>

        <?php 
        if ($_SESSION['error'] == 1){
           echo " <script> alert('Ce email est deja utiliser') </script>";
        }
        ?>

    <script>
        const identifation_page = 'client';
        actived_link_page(identifation_page);
    </script>
    <script>
      var loadFile = function(event) {
        var output = document.getElementById('output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
          URL.revokeObjectURL(output.src) // free memory
        }
      };
    </script>

<script>
var myInput = document.getElementById("password");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  document.getElementById("message1").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  document.getElementById("message1").style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {  
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }
  
  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {  
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {  
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }
  
  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }

  if(verif_input.value ==  myInput.value ) {  
    dif.style.display = "none";
    validation.style.display = "block";
    ident.style.display = "block";
  } else {
    validation.style.display = "none";
    dif.style.display = "block";
    ident.style.display = "none";
  }

}


var verif_input = document.getElementById('passwordVerif');
var dif = document.getElementById("dif");
var ident = document.getElementById("ident");
var validation = document.getElementById("valider");

verif_input.onfocus = function() {
  document.getElementById("message2").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
verif_input.onblur = function() {
  document.getElementById("message2").style.display = "none";
}

verif_input.onkeyup = function() {
  if(verif_input.value ==  myInput.value ) {  
    dif.style.display = "none";
    validation.style.display = "block";
    ident.style.display = "block";
  } else {
    validation.style.display = "none";
    dif.style.display = "block";
    ident.style.display = "none";
  }
}
</script>


</body>

</html>

<?php
$_SESSION['error'] = 0;
}
?>

