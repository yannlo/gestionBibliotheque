<?php
include('function/verified_session.php');
include('function/acces_user_verification.php');
include('function/geturl.php'); 
include('function/get_matricule.php');
include('function/connexion_bdd.php');

if(isset($_POST['firstName']) AND (isset($_POST['lastName'])) AND (isset($_POST['type_user'])) AND (isset($_POST['mail'])) AND (isset($_POST['contact']))){
    if(isset($_POST['password_actu']) AND !empty($_POST['password_actu'])){
        $verif_password = $bdd -> query('SELECT pass_word FROM all_comptes WHERE id ="'. $_SESSION['id_user'].'" ');
        $verif= false;
        while($donnee = $verif_password->fetch()){
            $verif= password_verify ( $_POST['password_actu'] , $donnee['pass_word'] ) ;
        }
        if($verif == true){
            $update_password=$bdd -> prepare("UPDATE all_comptes SET pass_word = :pass_word WHERE id =' " . $_SESSION['id_user'] . "'");
            $update_password -> execute(array(
                'pass_word' => $pass_hashed = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT)
            ));
            $add_user_request= $bdd -> prepare('UPDATE all_comptes  SET first_name= :first_name, last_name =:last_name, email= :email,  contact1 = :contact1 ');
        
            $verif_email = $bdd -> prepare('SELECT * FROM all_comptes WHERE email = :email AND id = "'. $_SESSION['id_user'].'" ');
            $verif_email  -> execute(array(
                'email' => $_POST['mail']
            ));
            $compte = $verif_email -> rowCount();
            if($compte != 1) {
                $verif_email = $bdd -> prepare('SELECT * FROM all_comptes WHERE email = :email AND id_type_compte = 2');
                $compte = $verif_email -> rowCount();
                if($compte != 0) {
                    header("Location: mod_doc_user.php");
                    $_SESSION['error'] = 1;
                    exit();
                }
            }
            
            $add_user_request -> execute(array(
                'first_name' => htmlspecialchars($_POST['firstName']),
                'last_name' => htmlspecialchars($_POST['lastName']),
                'email' => htmlspecialchars($_POST['mail']),
                'contact1' => htmlspecialchars($_POST['contact'])
            ));
        }else{ 
            $_SESSION['error'] = 2;
        }
    }
    else{
        $add_user_request= $bdd -> prepare('UPDATE all_comptes  SET first_name= :first_name, last_name =:last_name, email= :email,  contact1 = :contact1 WHERE id = "'. $_SESSION['id_user'].'" ');

        $verif_email = $bdd -> prepare('SELECT * FROM all_comptes WHERE email = :email AND id = "'. $_SESSION['id_user'].'" ');
        $verif_email  -> execute(array(
            'email' => $_POST['mail']
        ));
        $compte = $verif_email -> rowCount();
        if($compte != 1) {
            $verif_email = $bdd -> prepare('SELECT * FROM all_comptes WHERE email = :email AND id_type_compte = 2');
            $compte = $verif_email -> rowCount();
            if($compte != 0) {
                header("Location: mod_doc_user.php");
                $_SESSION['error'] = 1;
                exit();
            }
        }
        
        $add_user_request -> execute(array(
            'first_name' => htmlspecialchars($_POST['firstName']),
            'last_name' => htmlspecialchars($_POST['lastName']),
            'email' => htmlspecialchars($_POST['mail']),
            'contact1' => htmlspecialchars($_POST['contact'])
        ));
        $select_user = $bdd -> query('SELECT * FROM all_comptes WHERE id = "'.$_SESSION['id_user'].'" ');
        while ($donnee = $select_user ->fetch()){

            $update_status = $bdd -> prepare("UPDATE users SET id_type_user = :id_type_user WHERE id = :id");
            
            $update_status -> execute(array(
                'id' => $donnee['id_other_information'],
                'id_type_user'=> $_POST['type_user']
            ));
        }
    }

    

    if(isset($_POST['contact2']) AND !empty($_POST['contact2'])){
        $update_user = $bdd -> prepare("UPDATE all_comptes SET contact2 = :contact  WHERE id ='".$_SESSION['id_user']."' ");
        $update_user -> execute(array(
            'contact' => htmlspecialchars($_POST['contact2'])
        ));


    }



    if (!empty($_FILES)){
        
        $image = $_FILES['photo_user_mod'];
        

        if($image['error'] == 0 ){
            
            $ext_image = strtolower(substr($image['name'], -3));
            
            $allow_ext = array('jpg', 'gif', 'png');
            
            if (in_array($ext_image, $allow_ext)) {
                
                // $nom_oeuvre = htmlspecialchars($_POST['nom_oeuvre']);
                
                $fichier_partiel_nom = str_replace(' ','_',$_SESSION['id_user']);
                
                $fichier_final_nom = (string)($fichier_partiel_nom.".".$ext_image);
                
                move_uploaded_file($image['tmp_name'], "imageAndLogo/photo_user/".$fichier_final_nom);
                
                $select_user = $bdd -> query('SELECT * FROM all_comptes WHERE id = "'.$_SESSION['id_user'].'" ');

                while ($donnee = $select_user ->fetch()){
                            
                    $add_cover_image = $bdd -> prepare("UPDATE users SET nom_photo_user = :nom_photo_user WHERE id = :id");

                    $add_cover_image -> execute(array(
                        'id' => $donnee['id_other_information'],
                        'nom_photo_user'=> $fichier_final_nom
                    ));
                }


            
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
		<title>Informations personnels - Client </title>
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
        <link rel="stylesheet" href="style6.css"/>
        <link rel="stylesheet" href="documentation_books/style_document4.css"/>
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

                <h1>Modification des informations personnels</h1>
                <section id='confirmation'>
                    <h2>Message de confirmation de creation de compte</h2>
                    <p>
                        Les informations personnels a bien modifié.
                    </p>
                    <div class="bottom_link">
                    <a href="doc_user.php">Revenir a mes informations</a>
                    </div>
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
		const identifation_page ='information';
       	actived_link_page(identifation_page);
	</script>

</body>
</html>

<?php   
}else{
    $information_user = $bdd -> query("SELECT * FROM all_comptes WHERE id ='". $_SESSION["id_user"]."' ");
    while ($informations = $information_user->fetch()){
        $date = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$informations['birth_date']);
        $other_information = $bdd-> query('SELECT * FROM users WHERE id ="'. $informations["id_other_information"].'" ');
        while ($other = $other_information->fetch()){


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
		<title>Informations personnels - Client </title>
        <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
        <link rel="stylesheet" href="style6.css"/>
        <link rel="stylesheet" href="documentation_books/style_document4.css"/>
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

                <h1>Modification des informations personnels</h1>

                <section id="nouveauClient">

        <h2>Modifier mes informations personnels:</h2>
        <form method="POST" action="#" enctype="multipart/form-data">

            <fieldset id="form-parti1">
                <legend>information personnel </legend>

                <p>
                    <label for="photo_user_mod">modifier votre photo profile:</label>
                    <?php 
                    if( $other['nom_photo_user'] == NULL){
                    ?>
                    <img src="imageAndLogo/uncknown_user.png" alt="icon absence de photo de converture"   id="output"/>
                    <input type="file" name="photo_user_mod" id="photo_user_mod" required="required" accept="image/*" onchange="loadFile(event)"  />
                            
                    <?Php
                    }else{
                    ?>
                    <img src="imageAndLogo/photo_user/<?php echo $other['nom_photo_user'] ;?>" alt="couverture de l'oeuvre <?php echo $other['nom_photo_user'] ;?>" id="output" />
                    <input type="file" name="photo_user_mod" id="photo_user_mod" accept="image/*" onchange="loadFile(event)"/>  
                    <?php   
                    }
                    ?> 
                 </p>

                <p>
                    <label for="firstName">Modifier votre nom:</label>
                    <input type="text" name="firstName" id="firstName" placeholder="Nom" required="required" value="<?php echo $informations['first_name']; ?>"/>
                </p>
                <p>
                    <label for="lastName">Modifier votre prenom:</label>
                    <input type="text" name="lastName" id="lastName" placeholder="Prenom" required="required" value="<?php echo $informations['last_name'];?>" />
                </p>
                <p>
                    <label for="type_user">Modifier votre statut:</label>
                    <select  name="type_user" id="type_user" required="required" >

                <?php 
                include('function/connexion_bdd.php');

                $type_users = $bdd->query('SELECT * FROM type_users  ORDER BY nom');
                while($donnee = $type_users->fetch() ){
                    if($donnee['id_categorie'] == $other['id_type_user'] ){
                        echo '<option value="'.  $donnee['id'] .'" selected ="selected" >' . $donnee['nom'] . '</option>';
                    }else{
                        echo '<option value="'.  $donnee['id'] .'" >' .  $donnee['nom'] . '</option>';
                    }
                }


                ?>

                </select>
                </p>

            </fieldset>

            <fieldset id="form-parti2">
                <legend>email et mot de passe </legend>
                <p>
                    <label for="mail">Entrer votre email:</label>
                    <input type="email" name="mail" id="mail" placeholder="mon.email@exemple.com" value="<?php echo $informations['email']; ?>"required="required" />
                </p>
                <p>
                    <label for="password_actu">Entrer votre mot de passe Actuel:</label>
                    <input type="password" name="password_actu" id="password_actu" />
                </p>
                <p>
                    <label for="password">Entrer votre nouveau mot de passe:</label>
                    <input type="password" name="password" id="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="le mot de passe doit contenir au moins : 1 lettre minuscule, 1, lettre majuscule, 1 chiffre, 8 caracteres"/>
                    <div id="message1">
                        <h3>le mot de passe doit contenir:</h3>
                        <p id="letter" class="invalid">1 lettre <b>minuscule</b> </p>
                        <p id="capital" class="invalid">1 lettre <b>majuscule</b> </p>
                        <p id="number" class="invalid">1 <b>nombre</b></p>
                        <p id="length" class="invalid">Minimum 8 <b>caracteres</b></p>
                     </div>
                </p>
                <p>
                    <label for="passwordVerif">verification nouveau mot de passe:</label>
                    <input type="password" name="passwordVerif" id="passwordVerif" />
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
                    <input type="tel" name="contact" id="contact" placeholder="01 02 03 04" required="required" title='Format: XX XX XX XX' pattern="[0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}"  value="<?php echo $informations['contact1'];?>"
                     required = "required"/>
                </p>
                <p>
                    <label for="contact2">Entrer votre contact 2:</label>
                    <input type="tel" name="contact2" id="contact2" placeholder="01 02 03 04" title='Format: XX XX XX XX' pattern="[0-9]{2} [0-9]{2} [0-9]{2} [0-9]{2}" value="<?php echo $informations['contact2'];?>"/>
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
        if ($_SESSION['error'] == 2){
            echo " <script> alert('le mot de passe actuel est incorect') </script>";
         }
        ?>

    <script>
        const identifation_page = 'information';
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
        }
    }
$_SESSION['error'] = 0;
}
?>

