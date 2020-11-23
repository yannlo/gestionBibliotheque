<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/count_stock_element.php');
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if( (isset($_POST['sup_nom_auteur']) AND !empty($_POST['sup_nom_auteur'])) OR (isset($_POST['sup_nom_categorie']) AND !empty($_POST['sup_nom_categorie'])) OR (isset($_POST['sup_nom_type']) AND !empty($_POST['sup_nom_type'])) ){
    $information ='';
    if(isset($_POST['sup_nom_auteur']) AND !empty($_POST['sup_nom_auteur'])){
        $use_auteur = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id_auteur = :id_auteur ');
        $use_auteur -> execute(array(
            ':id_auteur' =>  $_POST['sup_nom_auteur']
        ));
        $compt_use = $use_auteur -> rowCount();
        if ($compt_use == 0) {
            $sup_auteur = $bdd -> prepare('DELETE FROM autheur_livre  WHERE id = :id');
            $sup_auteur -> execute(array(
                'id' =>$_POST['sup_nom_auteur']
            ));
            $message1="L'auteur";
        }else{
            $information .= '';
        }

    }



    if(isset($_POST['sup_nom_categorie']) AND !empty($_POST['sup_nom_categorie'])){
        $use_categorie = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id_categorie = :id_categorie ');
        $use_categorie -> execute(array(
            ':id_categorie' =>  $_POST['sup_nom_categorie']
        ));
        $compt_use = $use_categorie -> rowCount();
        if ($compt_use == 0) {
            $sup_categorie = $bdd -> prepare('DELETE FROM categorie_livre WHERE id = :id');
            $sup_categorie -> execute(array(
                'id' => $_POST['sup_nom_categorie']
            ));
            $message2="La categotie";
        }else{

            $information .= '';
        }
    }



    if(isset($_POST['sup_nom_type']) AND !empty($_POST['sup_nom_type'])){
        $use_type = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id_type = :id_type ');
        $use_type -> execute(array(
            ':id_type' =>  $_POST['sup_nom_type']
        ));
        $compt_use = $use_type -> rowCount();
        if ($compt_use == 0) {
            $sup_type_oeuvre = $bdd -> prepare('DELETE FROM type_oeuvre WHERE id = :id');
            $sup_type_oeuvre -> execute(array(
                'id' => $_POST['sup_nom_type']
            ));
            $message3="La type d'oeuvre";
        }else{

            $information .= '';
        }
    }


    ?>
    <!DOCTYPE html
        PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">
    
    <head>
        <meta http-equiv="content-type" content="text/html" charset="utf-8" />
        <title>Gestion de livre - Gestionnaire </title>
        <link rel="stylesheet" href="style6.css" />
        <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
        <link rel="stylesheet" href="stock_book/gestion_livre_style.css" />
        <link rel="stylesheet" href="stock_book/gestion_sup2.css" />
        <link rel="stylesheet" href="general-style-element.css" />
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>
    
    <body>
    
        <div class="container">
            <header>
                <?php  include("headerAndFooter/menu.php") ?>
            </header>
            
            <div class="center">
                <section id="exemplaire_liste">
                    
    
                    <h2>Message de confirmation de suppression</h2>
    
                    <p class='p_end'>
                    <?php if(isset($_POST['sup_nom_auteur']) AND !empty($_POST['sup_nom_auteur'])){echo 'l\'auteur, ';} ?>
                    <?php if(isset($_POST['sup_nom_categorie']) AND !empty($_POST['sup_nom_categorie'])){echo 'la categorie, ';} ?>
                    <?php if(isset($_POST['sup_nom_type']) AND !empty($_POST['sup_nom_type'])){echo 'La type d\'oeuvre ';} ?>
                    a bien été supprimer.
                    </p>
    
                    <a href="sup_information_oeuvre.php">Retour a la page d'ajout de livre</a>
    
    
                </section>
    
    
            </div>
    
    
                </section>
    
            </div>
    
            <?php include('headerAndFooter/footer.php'); ?>
    
        </div>
        
    
        <script>
            const identifation_page = 'connect-book';
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
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="style6.css" />
        <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
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
                <h1>Gestion de livre</h1>

                <section id="formulaire_ajout_information_oeuvre">
                    <h2>Formulaire d'ajout d'information d'oeuvre</h2>
    
                    <form method="POST"action="#">
    
                        <p>
                            <label for="sup_nom_auteur">Selectionner l'auteur a supprimer:</label>
                            <select  name="sup_nom_auteur" id="sup_nom_auteur">
                                <option  value=''>none</option>;
                            <?php 
                            $auteur_list = $bdd->query('SELECT * FROM autheur_livre ORDER BY nom');
                            while($donnee = $auteur_list->fetch() ){
                                echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';
                            }
                            ?>
                            </select>
                        </p>
                        <p>
                            <label for="sup_nom_categorie">Selectionner la categorie d'oeuvre a supprimer:</label>
                            <select  name="sup_nom_categorie" id="sup_nom_categorie">
                            <option  value=''>none</option>;
                            
                            <?php 
                            $categorie_list = $bdd->query('SELECT * FROM categorie_livre ORDER BY nom');
                            while($donnee = $categorie_list->fetch() ){
                                echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';
                            }
                            ?>
                            </select>
                        </p>
                        <p>
                            <label for="sup_nom_type">Selectionner le type d'oeuvre a supprimer:</label>
                            <select  name="sup_nom_type" id="sup_nom_type">
                            <option  value=''>none</option>;
                            
                            <?php 
                            $type_list = $bdd->query('SELECT * FROM type_oeuvre ORDER BY nom');
                            while($donnee = $type_list->fetch() ){
                                echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';
                            }
                            ?>
                            </select>
                        </p>
                        <input type="submit" name="valider" value="valider" />
                    </form>
                </section>
            </div>
            
            <?php include('headerAndFooter/footer.php'); ?>
            
        </div>
    
        <script type="text/javascript">
            const ver4 = document.getElementById('formulaire_ajout_information_oeuvre');
            ver4.style.height ='90vh';

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
    
    
    </body>
    </html>    
<?php
}
?>