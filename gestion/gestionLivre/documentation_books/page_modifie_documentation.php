<?php
include('../../../function/verified_session.php');
$_SESSION['type']= 'admin';
include('../../../function/acces_admin_verification.php');
include('../../../function/geturl.php');
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if(isset($_POST['nom_oeuvre_mod']) AND isset($_POST['type_oeuvre_mod']) AND isset($_POST['categorie_oeuvre_mod']) AND isset($_POST['auteur_oeuvre_mod']) AND isset($_POST['description_oeuvre_mod']) ){
    $remplace = $bdd -> prepare('INSERT TO liste_oeuvre (nom, id_type, id_categorie, id_auteur, description_oeuvre, id_photo_couverture) VALUES(:nom, :id_type, :id_categorie, :id_auteur, :description_oeuvre, :id_photo_couverture)');
    
    function transfert(){
        $ret        = false;
        $img_bdd   = '';
        $img_taille = 0;
        $img_type   = '';
        $img_nom    = '';
        $taille_max = 250000;
        $ret        = is_uploaded_file($_FILES['photo_oeuvre_mod']['tmp_name']);
        
        if (!$ret) {
            echo "<script>alert('Problème de transfert'); </script>";
            return false;
        } else {
            // Le fichier a bien été reçu
            $img_taille = $_FILES['fic']['size'];
            
            if ($img_taille > $taille_max) {
                echo "<script>alert('fichier trop volumineux'); </script>";
                return false;
            }

            $img_type = $_FILES['photo_oeuvre_mod']['type'];
            $img_nom  = $_FILES['photo_oeuvre_mod']['name'];
            $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $img_bdd = file_get_contents ($_FILES['photo_oeuvre_mod']['tmp_name']);
            include ("connexion.php");
            $img_blob = file_get_contents ($_FILES['photo_oeuvre_mod']['tmp_name']);
            $req = "INSERT INTO all_photos_and_image (" .
                                "img_name, img_size, img_type, img_blob " .
                                ") VALUES (" .
                                "'" . $img_nom . "', " .
                                "'" . $img_taille . "', " .
                                "'" . $img_type . "', " .
                                "'" . addslashes ($img_blob) . "') ";
            $ret = $bdd -> query ($req);
            return true;

        }
    }

    transfert();

}

if (isset($_SESSION['oeuvre'])) {
    $oeuvre_choose = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id = :id AND nom = :nom');
    foreach($_SESSION['oeuvre'] as $key => $val) {
    
         $oeuvre_choose -> execute(array(
            'id' => htmlspecialchars($key),
            'nom' => htmlspecialchars($val)
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
        <link rel="stylesheet" href="../../../style4.css"/>
        <link rel="stylesheet" href="style_document3.css"/>
        <link rel="stylesheet" href="../../../general-style-element.css"/>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>

	<body>
		
		<div class="container">
			<header>
				<?php  include("../../../headerAndFooter/menu.php") ?>
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
            
                // AND  AND 
?>
                        <p>
                            <label for="photo_oeuvre_mod">Modifier le la photo</label>
  <?php 
  if($oeuvre['id_photo_couverture'] !== NULL){
    $id_image = $oeuvre['id_photo_couverture'];
    $req = "SELECT img_id, img_type, img_blob " . 
           "FROM images WHERE img_id =  "
?>
                            

<?Php
  }
  ?>                      
  
                            <input type="hidden" name="max_file_photo_oeuvre_mod" id="photo_oeuvre_mod" value='250000'/>
                            <input type="file" name="photo_oeuvre_mod" id="photo_oeuvre_mod" size="50" required="required"/>
                        </p>

                        <p>
                            <label for="nom_oeuvre_mod">Modifier le nom de l'oeuvre:</label>
                            <input type="text" name="nom_oeuvre_mod" id="nom_oeuvre_mod" value="<?php echo $oeuvre['nom']; ?>" required="required"/>
                        </p>
                        <p>
                            <label for="type_oeuvre_mod">Modifier le type d'oeuvre:</label>
                            <select type="text" name="type_oeuvree_mod" id="type_oeuvree_mod"  required>
<?php 
while ($type = $saisie_type ->fetch()){
if($oeuvre['id_type'] == $saisie_type['id'] ){
        echo '<option value="'. $saisie_type['id'] .'" selected ="selected" >' . $saisie_type['nom'] . '</option>';
    }else{
        echo '<option value="'. $saisie_type['id'] .'" >' . $saisie_type['nom'] . '</option>';
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
if($oeuvre['id_categorie'] ==  $saisie_category['id'] ){
        echo '<option value="'.  $saisie_category['id'] .'" selected ="selected" >' . $saisie_category['nom'] . '</option>';
    }else{
        echo '<option value="'.  $saisie_category['id'] .'" >' .  $saisie_category['nom'] . '</option>';
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
if($oeuvre['id_auteur'] ==  $saisie_auteur['id'] ){
        echo '<option value="'.  $saisie_auteur['id'] .'" selected ="selected" >' . $saisie_auteur['nom'] . '</option>';
    }else{
        echo '<option value="'.  $saisie_auteur['id'] .'" >' .  $saisie_auteur['nom'] . '</option>';
    }
}


?>
                            </select>
                        </p>
                        <p>
                            <label for="description_oeuvre_mod">Modifier la description de l'oeuvre:</label>
                            <textarea type="text" name="description_oeuvre_mod" id="description_oeuvre_mod"  required="required">bla bla bla</textarea>
                        </p>


<?php
            
        }

?> 

<input type="submit" name="comfirmer" id="confirmer" value="confirmer"/>

</form>


</section>

</div>

<?php include('../../../headerAndFooter/footer.php'); ?>

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

</body>
</html>


<?php

    }
   

}else{

    header("Location:page_documentation_book.php");
    exit();
}

?>

