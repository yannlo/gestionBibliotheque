<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php');
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if(isset($_POST['nom_oeuvre_mod'])   AND isset($_POST['type_oeuvre_mod']) AND isset($_POST['categorie_oeuvre_mod'])  AND isset($_POST['auteur_oeuvre_mod']) AND isset($_POST['description_oeuvre_mod'])) {
    $_SESSION['total_error']['error']= false;
    $_SESSION['total_error']['repeter_valeur'] = false;
    $image = $_FILES['photo_oeuvre_mod'];
    $search1 = $bdd->prepare('SELECT nom FROM liste_oeuvre WHERE id NOT IN ( :id )');
    if($image['error'] == 4 ){   
        $modifie_oeuvre_request = $bdd -> prepare('UPDATE liste_oeuvre SET nom = :nom, id_type = :id_type, id_auteur = :id_auteur, id_categorie = :id_categorie, description_oeuvre = :description_oeuvre
        WHERE id = :id ');

        $key_val ='';
        $valeur_key = '';
        $nom_oeuvre_mod = htmlspecialchars($_POST['nom_oeuvre_mod']);

        foreach($_SESSION['oeuvre'] as $key => $value){
            $key_val = $key;
            $value = $nom_oeuvre_mod;
        }

        $search1 -> execute(array(
            'id' => $key_val
        ));

    
            while ($donnee1 = $search1->fetch() ){

                    if($donnee1['nom'] == $_POST['nom_oeuvre_mod']){
                        $_SESSION['total_error']['repeter_valeur']= true;
                        header('Location: page_modifie_documentation.php');
                        exit();
                    }

            }

        $id_oeuvre = 0;
        foreach($_SESSION['oeuvre'] as $key => $value){
            $id_oeuvre =(int) $key;
            $valeur_key = $value;
        }

        $take_inf_oeuvre = $bdd -> query("SELECT nom FROM liste_oeuvre WHERE id = $id_oeuvre");

            $modifie_oeuvre_request -> execute(array(
                'id' => $id_oeuvre,
                'nom'=>$nom_oeuvre_mod,
                'id_type'=>$_POST['type_oeuvre_mod'],
                'id_auteur'=>$_POST['auteur_oeuvre_mod'],
                'id_categorie'=>$_POST['categorie_oeuvre_mod'],
                'description_oeuvre'=>$_POST['description_oeuvre_mod']
            ));
            unset($_SESSION['oeuvre']);
            $take_inf_oeuvre = $bdd -> query("SELECT nom, id FROM liste_oeuvre WHERE id = $id_oeuvre");
            while ($nom = $take_inf_oeuvre->fetch() ){
                $_SESSION['oeuvre'] = array($nom['id'] => $nom['nom']);
            }
            print_r($_SESSION['oeuvre']);
            if ($_SESSION['url_val'] == 50){

                header('Location: affiche_doc_page_complet.php');
            }
            else if ($_SESSION['url_val'] == 20){

                header('Location: affiche_doc_page.php');
            }

        



                        
    }
    else if($image['error'] == 1) {

        $_SESSION['total_error']['error'] = true; //'L\'image envoyer est trop lourd';
    }
    else if($image['error'] == 0) {

        $search1 -> execute(array(
            'id' => $key_val
        ));

        while ($donnee = $search1->fetch() ){
            if($donnee['nom'] == $_POST['nom_oeuvre_mod']){
                $_SESSION['total_error']['repeter_valeur']= true;
                header('Location: page_modifie_documentation.php');
                exit();
            }
        }

        $modifie_oeuvre_request = $bdd -> prepare('UPDATE liste_oeuvre SET nom = :nom, id_type = :id_type, id_auteur = :id_auteur, id_categorie = :id_categorie,  nom_photo_couverture = :nom_photo_couverture, description_oeuvre = :description_oeuvre
        WHERE id = :id ');

        $ext_image = strtolower(substr($image['name'], -3));
        $allow_ext = array('jpg', 'gif', 'png');
        if (in_array($ext_image, $allow_ext)) {

            $key_val ='';
            $valeur_key = '';
            $nom_oeuvre_mod = htmlspecialchars($_POST['nom_oeuvre_mod']);
            
            foreach($_SESSION['oeuvre'] as $key => $value){
                $key_val = $key;
                $valeur_key = $value;
            }
            $fichier_partiel_nom = str_replace(' ','_',$key_val);
            
            $search1 -> execute(array(
                'id' => $key_val
            ));
    
            while ($donnee1 = $search1->fetch() ){
                if($donnee1['nom'] == $_POST['nom_oeuvre_mod']){
                    $_SESSION['total_error']['repeter_valeur']= true;
                    header('Location: page_modifie_documentation.php');
                    exit();
                }
            }

            $fichier_final_nom = (string)($fichier_partiel_nom.".".$ext_image);

            move_uploaded_file($image['tmp_name'], "imageAndLogo/image_book/".$fichier_final_nom);

            if(!empty($_FILES) ){

                $id_oeuvre = 0;
                foreach($_SESSION['oeuvre'] as $key => $value){
                    $id_oeuvre =(int) $key;
                }

                $take_inf_oeuvre = $bdd -> query("SELECT nom FROM liste_oeuvre WHERE id = $id_oeuvre");

                    $modifie_oeuvre_request -> execute(array(
                        'id' => $id_oeuvre,
                        'nom'=>$nom_oeuvre_mod,
                        'id_type'=>$_POST['type_oeuvre_mod'],
                        'id_auteur'=>$_POST['auteur_oeuvre_mod'],
                        'id_categorie'=>$_POST['categorie_oeuvre_mod'],
                        'description_oeuvre'=>$_POST['description_oeuvre_mod'],
                        'nom_photo_couverture'=> $fichier_final_nom
                    ));
                    unset($_SESSION['oeuvre']);
                    $take_inf_oeuvre = $bdd -> query("SELECT nom, id FROM liste_oeuvre WHERE id = $id_oeuvre");
                    while ($nom = $take_inf_oeuvre->fetch() ){
                        $_SESSION['oeuvre'] = array($nom['id'] => $nom['nom']);
                    }
                    print_r($_SESSION['oeuvre']);
                    if ($_SESSION['url_val'] == 50){

                        header('Location: affiche_doc_page_complet.php');
                    }
                    else if ($_SESSION['url_val'] == 20){
        
                        header('Location: affiche_doc_page.php');
                    }
                    
            }



        } 
        else {
            $error = 'ce fichier n\'est pas une image';
        }

    }

}




if (isset($_SESSION['oeuvre'])) {
    unset($_FILES);
    $oeuvre_choose = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id = :id ');
    foreach($_SESSION['oeuvre'] as $key => $val) {
    
         $oeuvre_choose -> execute(array(
            'id' => htmlspecialchars($key)
         ));
    }
    $compteur = $oeuvre_choose -> rowCount();
    if ($compteur == 0 OR $compteur > 1 ){
        header('Location: page_documentation_book.php');
        exit();
     }
    else{

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
        <link rel="stylesheet" href="style6.css"/>
        <link rel="stylesheet" href="documentation_books/style_document3.css"/>
        <link rel="stylesheet" href="general-style-element.css"/>
        <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />

        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>

	<body>
		
		<div class="container">
			<header>
				<?php  include("headerAndFooter/menu.php") ?>
            </header>

            <div class="center">

                <h1>Modification de la documentation de livre</h1>
                <section id="modifie_oeuvre">

                    
                    <form method="POST" enctype="multipart/form-data" action="page_modifie_documentation.php">
                        <h2>Information de l'oeuvre</h2>



<?php

        while ($oeuvre = $oeuvre_choose ->fetch()){
            $saisie_auteur = $bdd ->  query('SELECT * FROM autheur_livre  ');
            $saisie_category = $bdd ->  query('SELECT * FROM categorie_livre ');
            $saisie_type = $bdd -> query('SELECT * FROM type_oeuvre ');
            
            if(isset($error)){
                echo '<script> 
                alert("'.$error.'");
                </script>';
            }
            
?>
                        <p>
                            <label for="photo_oeuvre_mod">Modifier le la photo</label>
  <?php 
  if( $oeuvre['nom_photo_couverture'] == NULL){
?>
<img src="imageAndLogo/uncknown_book.png" alt="icon absence de photo de converture"   id="output"/>
<input type="file" name="photo_oeuvre_mod" id="photo_oeuvre_mod" required="required" accept="image/*" onchange="loadFile(event)"  />
           
<?Php
  }else{
   ?>
<img src="imageAndLogo/image_book/<?php echo $oeuvre['nom_photo_couverture'] ;?>" alt="couverture de l'oeuvre <?php echo $oeuvre['nom_photo_couverture'] ;?>" id="output" />
<input type="file" name="photo_oeuvre_mod" id="photo_oeuvre_mod" accept="image/*" onchange="loadFile(event)"/>  
   <?php   
  }
  ?>                      
  

                            
                        </p>

                        <p>
                            <label for="nom_oeuvre_mod">Modifier le nom de l'oeuvre:</label>
                            <input type="text" name="nom_oeuvre_mod" id="nom_oeuvre_mod" value="<?php echo $oeuvre['nom']; ?>" required="required"/>
                        </p>
                        <p>
                            <label for="type_oeuvre_mod">Modifier le type d'oeuvre:</label>
                            <select type="text" name="type_oeuvre_mod" id="type_oeuvree_mod"  required>
<?php 
while ($type = $saisie_type ->fetch()){
if($oeuvre['id_type'] == $type['id'] ){
        echo '<option value="'. $type['id'] .'" selected ="selected" >' . $type['nom'] . '</option>';
    }else{
        echo '<option value="'. $type['id'] .'" >' . $type['nom'] . '</option>';
    }
}


?> 
                            </select>
                        </p>
                        <p>
                            <label for="categorie_oeuvre_mod">Modifier la categorie de l'oeuvre:</label>
                            <select type="text" name="categorie_oeuvre_mod" id="categorie_oeuvre_mod"  required="required"> 
<?php 
while ($categorie = $saisie_category -> fetch()){
if($oeuvre['id_categorie'] ==  $categorie['id'] ){
        echo '<option value="'.  $categorie['id'] .'" selected ="selected" >' . $categorie['nom'] . '</option>';
    }else{
        echo '<option value="'.  $categorie['id'] .'" >' .  $categorie['nom'] . '</option>';
    }
}


?>
                            </select>
                        </p>
                        
                        <p>
                            <label for="auteur_oeuvre_mod">Modifier l'auteur de l'oeuvre:</label>
                            <select type="text" name="auteur_oeuvre_mod" id="auteur_oeuvre_mod"  required="required"> 
                            <?php 

while ( $auteur = $saisie_auteur -> fetch()){
if($oeuvre['id_auteur'] ==  $auteur['id'] ){
        echo '<option value="'.  $auteur['id'] .'" selected ="selected" >' . $auteur['nom'] . '</option>';
    }else{
        echo '<option value="'.  $auteur['id'] .'" >' .  $auteur['nom'] . '</option>';
    }
}


?>
                            </select>
                        </p>
                        <p>
                            <label for="description_oeuvre_mod">Modifier la description de l'oeuvre:</label>
                            <textarea type="text" name="description_oeuvre_mod" id="description_oeuvre_mod"  required="required"><?php echo $oeuvre['description_oeuvre'] ;?></textarea>
                        </p>


<?php
            
        }

?> 

<input type="submit" name="comfirmer" id="confirmer" value="confirmer"/>

</form>


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

</body>
</html>


<?php

    }
   

}else{

    header("Location:page_documentation_book.php");
    exit();
}

?>

