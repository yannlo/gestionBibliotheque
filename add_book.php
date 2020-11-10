<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/count_stock_element.php');
if(isset($_POST['nom_oeuvre']) AND isset($_POST['type_oeuvre']) AND isset($_POST['categorie_oeuvre']) AND isset($_POST['auteur_oeuvre']) AND isset($_POST['stock_exemplaire']) AND isset($_POST['description_oeuvre'])){

    $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $request =  $bdd -> prepare('INSERT INTO liste_oeuvre (nom, id_type, id_categorie, description_oeuvre, id_auteur) VALUES (:nom, :id_type, :id_categorie, :description_oeuvre, :id_auteur)');

    $search = $bdd->query('SELECT nom FROM liste_oeuvre');
    $_SESSION['total_error']['error']= false;
    $_SESSION['total_error']['repeter_valeur'] = false;

    while ($donnee = $search->fetch() ){
        if($donnee['nom'] == $_POST['nom_oeuvre']){
            $_SESSION['total_error']['repeter_valeur']= true;
            header('Location: add_book.php');
            exit();
        }
    }

    if (!empty($_FILES)){
        print_r($_FILES);
        $image = $_FILES['cover_image'];
        
        if($image['error'] == 4 ){
            
                $request -> execute(array(
                    'nom' => htmlspecialchars($_POST['nom_oeuvre']),
                    'id_type' => $_POST['type_oeuvre'],
                    'id_categorie' => $_POST['categorie_oeuvre'],
                'description_oeuvre' => htmlspecialchars($_POST['description_oeuvre']),
                'id_auteur' => $_POST['auteur_oeuvre']
            ));
            
            $_SESSION['exemplaire'] = array();
            $search = $bdd->prepare('SELECT id FROM liste_oeuvre WHERE nom = :nom');
            $search -> execute(array(
                'nom' => $_POST['nom_oeuvre'],
            ));
            
            
            $_SESSION['exemplaire']['nom_oeuvre'] = $_POST['nom_oeuvre'];
            
            
            $_SESSION['exemplaire']['nombre'] = 1;
            
        
            $_SESSION['exemplaire']['nombre_finale'] = $_POST['stock_exemplaire'];
            
            header('Location: add_exemplaire_2.php');
            exit();
        
        }
    
        else if($image['error'] == 1) {

            $_SESSION['total_error']['error'] = true;
            header('Location: add_book.php');
            
        }
        
        else if($image['error'] == 0 ){

            $request -> execute(array(
                'nom' => htmlspecialchars($_POST['nom_oeuvre']),
                'id_type' => $_POST['type_oeuvre'],
                'id_categorie' => $_POST['categorie_oeuvre'],
                'description_oeuvre' => htmlspecialchars($_POST['description_oeuvre']),
                'id_auteur' => $_POST['auteur_oeuvre']
            ));
            
            $select_champ = $bdd -> prepare('SELECT id, nom FROM liste_oeuvre WHERE nom = :nom ');
            
            $select_champ -> execute(array(
                'nom' => htmlspecialchars($_POST['nom_oeuvre'])
            ));
            
            
            $ext_image = strtolower(substr($image['name'], -3));
            
            $allow_ext = array('jpg', 'gif', 'png');
            
            if (in_array($ext_image, $allow_ext)) {
                
                $key_val ='';
                
                while ($select = $select_champ->fetch()){
                    $key_val = $select['id'];
                }
                
                $nom_oeuvre = htmlspecialchars($_POST['nom_oeuvre']);
                
                $fichier_partiel_nom = str_replace(' ','_',$key_val);
                
                $fichier_final_nom = (string)($fichier_partiel_nom.".".$ext_image);
                
                move_uploaded_file($image['tmp_name'], "imageAndLogo/image_book/".$fichier_final_nom);
                
                if(!empty($_FILES) ){
                    $add_cover_image = $bdd -> prepare('UPDATE liste_oeuvre SET nom_photo_couverture = :nom_photo_couverture WHERE id = :id ');

                    $add_cover_image -> execute(array(
                        'id'=> $key_val ,
                        'nom_photo_couverture'=> $fichier_final_nom
                    ));
                    $_SESSION['exemplaire'] = array();

                    $search = $bdd->prepare('SELECT id , nom_photo_couverture FROM liste_oeuvre WHERE nom = :nom');
                    $search -> execute(array(
                        'nom' => $_POST['nom_oeuvre'],
                    ));
                    

                    $_SESSION['exemplaire']['nom_oeuvre'] = $_POST['nom_oeuvre'];
                    
                    
                    $_SESSION['exemplaire']['nombre'] = 1;
                    
                    
                    $_SESSION['exemplaire']['nombre_finale'] = $_POST['stock_exemplaire'];
                    
                    header('Location: add_exemplaire_2.php');
                    exit();
                
                }
            
            }
        
        }
    }else{
        print_r($_FILES);
        $request -> execute(array(
            'nom' => htmlspecialchars($_POST['nom_oeuvre']),
            'id_type' => $_POST['type_oeuvre'],
            'id_categorie' => $_POST['categorie_oeuvre'],
            'description_oeuvre' => htmlspecialchars($_POST['description_oeuvre']),
            'id_auteur' => $_POST['auteur_oeuvre']
        ));

        $_SESSION['exemplaire'] = array();
        $search = $bdd->prepare('SELECT id FROM liste_oeuvre WHERE nom = :nom');
        $search -> execute(array(
            'nom' => htmlspecialchars($_POST['nom_oeuvre']),
        ));
    
    
        $_SESSION['exemplaire']['nom_oeuvre'] = $_POST['nom_oeuvre'];
    
    
        $_SESSION['exemplaire']['nombre'] = 1;
        
    
        $_SESSION['exemplaire']['nombre_finale'] = $_POST['stock_exemplaire'];
    
        header('Location: add_exemplaire_2.php');
        exit();
    }


}

else if(isset($_POST['nom_oeuvre']) AND isset($_POST['etat_oeuvre']) AND isset($_POST['editeur_exemplaire'])){
    $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
    $request =  $bdd -> prepare('INSERT INTO liste_exemplaire (id_oeuvre, id_etat, editeur) VALUES (:id_oeuvre, :id_etat, :editeur)');

    $request -> execute(array(
        'id_oeuvre' => $_POST['nom_oeuvre'],
        'id_etat' => $_POST['etat_oeuvre'],
        'editeur' => htmlspecialchars($_POST['editeur_exemplaire'])
    ));

    update_stock($_POST['nom_oeuvre']);

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="style4.css" />
    <link rel="stylesheet" href="general-style-element.css" />
    <link rel="stylesheet" href="add_book/style_add_parti2.css" />

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <title>Ajout de livre - Gestionnaire</title>
</head>
<body>

    <div id="container">
  
        <head>
            <?php include('headerAndFooter/menu.php'); ?>
        </head>
        
        
        <div class='center'>
            <h1>Ajouter un nouveau livre:</h1>
            <section id='choose_form'>
                <h2>Choix du formulaire</h2>
                <p>
                    Souhaitez-vous ajouter une oeuvre ou un exemplaire : <br/>
                    <div class='radio-style'>
                        <label for="check_oeuvre">
                            <input type="radio" name="check_formulaire" id="check_oeuvre" onClick="change ('formulaire_ajout_oeuvre','formulaire_ajout_exemplaire');" />
                            Ajouter une oeuvre
                        </label>
                        <label for="check_exemplaire">
                            <input type="radio" name="check_formulaire" id="check_exemplaire" onClick="change ('formulaire_ajout_exemplaire','formulaire_ajout_oeuvre');" />
                            Ajouter une exemplaire
                        </label>
                    </div>
                </p>
            </section>

            <section id="formulaire_ajout_oeuvre">

                <h2>Formulaire d'ajout d'oeuvre</h2>

                <form method="POST"action="add_book.php" enctype="multipart/form-data" >
                    <p>
                        <label for="cover_image">Ajouter la couverture de l'oeuvre</label>
                        <img src="imageAndLogo/uncknown_book.png" alt="icon absence de photo de converture"   id="output"/>
                        <input type="file" name="cover_image" id="cover_image" accept="image/*" onchange="loadFile(event)"  />
                    </p>

                    <p>
                        <label for="nom_oeuvre">Entrer le nom de l'oeuvre:</label>
                        <input type="text" name="nom_oeuvre" id="nom_oeuvre" required="required" placeholder="nom de l'oeuvre" />
                    </p>

                    <p>
                        <label for="type_oeuvre">Selectionner le type de l'oeuvre:</label>
                        <select  name="type_oeuvre" id="type_oeuvre" required="required" >
                        <?php 
                        $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                        $oeuvre_list = $bdd->query('SELECT * FROM type_oeuvre ORDER BY nom');
                        while($donnee = $oeuvre_list->fetch() ){
                            echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';
                        }
                        ?>
                        </select>
                    </p>

                    <p>
                        <label for="categorie_oeuvre">Selectionner la categorie de l'oeuvre:</label>
                        <select  name="categorie_oeuvre" id="categorie_oeuvre" required="required">

                        <?php 
                        $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                        $categorie_list = $bdd->query('SELECT * FROM categorie_livre  ORDER BY nom');
                        while($donnee = $categorie_list->fetch() ){
                            echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';

                        }

                        ?>

                        </select>
                    </p>

                    <p>
                        <label for="auteur_oeuvre">Entrer le non de l'auteur de l'oeuvre:</label>
                        <select  name="auteur_oeuvre" id="auteur_oeuvre" required="required" >

                        <?php 
                        $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                        $auteur_livre = $bdd->query('SELECT * FROM autheur_livre  ORDER BY nom');
                        while($donnee = $auteur_livre->fetch() ){
                            echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';
                        }

                        ?>

                        </select>
                    </p>

                    <p>
                        <label for="stock_exemplaire">Entrer le nombre d'exemplaire de l'oeuvre:</label>
                        <input type="number" name="stock_exemplaire" id="stock_exemplaire" min="0" max="999" required="required"/>
                    </p>

                    <p>
                        <label for="description_oeuvre">Description de l'oeuvre categorie de l'oeuvre:</label>
                        <textarea name="description_oeuvre" id="description_oeuvre" required="required" ></textarea>
                    </p>

                    <input type="submit" name="valider" value="valider" />

                </form>
            </section>

            <section id="formulaire_ajout_exemplaire">
            <h2>Formulaire d'ajout d'exemplaire</h2>

                <form method="POST"action="#">

                    <p>
                        <label for="nom_oeuvre">Entrer le nom de l'oeuvre:</label>
                        <select  name="nom_oeuvre" id="nom_oeuvre" required="required" >
                        <?php 
                        $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                        $oeuvre_list = $bdd->query('SELECT id, nom FROM liste_oeuvre ORDER BY nom');
                        while($donnee = $oeuvre_list->fetch() ){
                            echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';
                        }
                        ?>
                        </select>
                    </p>
                    <p>
                        <label for="etat_oeuvre">Selectionner l'etat de l'exemplaire:</label>
                        <select  name="etat_oeuvre" id="etat_oeuvre" required="required" >
                        <?php 
                        $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                        $oeuvre_list = $bdd->query('SELECT * FROM etat_books ORDER BY id');
                        while($donnee = $oeuvre_list->fetch() ){
                            echo '<option  value='.$donnee['id'].'>'. $donnee['nom_etat'].'</option>';
                        }
                        ?>
                        </select>
                    </p>
                    <p>
                        <label for="editeur_exemplaire">Entrer le nom de l'editeur de l'exemplaire:</label>
                        <input type="text" name="editeur_exemplaire" id="editeur_exemplaire" placeholder="nom de l'editeur"/>
                    </p>

                    <input type="submit" name="valider" value="valider" />
                </form>
            </section>

        </div>
        
        <?php include('headerAndFooter/footer.php'); ?>
        
    </div>

    <script type="text/javascript">
        const ver1 = document.getElementById('formulaire_ajout_exemplaire');
        const ver2 = document.getElementById('formulaire_ajout_oeuvre');
        const ver3 = document.getElementById('choose_form');
        ver3.style.height ='60vh';
        ver1.style.display = 'none';
        ver2.style.display = 'none';
        function change (element,id_form) {
            const ver1 = document.getElementById(element);
            const ver2 = document.getElementById(id_form);
            const ver3 = document.getElementById('choose_form');
            if( ver1.style.display == 'none' ) {
                
                ver1.style.display = 'block';
                ver2.style.display = 'none';
                ver3.style.height = '100%';
            }
        }
    </script>


        <?php 
        if ( $_SESSION['total_error']['repeter_valeur'] == true) {
            echo "<script>
            alert('Cette oeuvre existe deja. veuillez en saisir une autre');
            </script>";
        }else if( $_SESSION['total_error']['error'] == 1){
            echo "<script>
            'alert(L'image envoyer est trop lourd);');
            </script>";
        }   
        ?>
    <script>
		const identifation_page ='connect-book';
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


</body>
</html>
