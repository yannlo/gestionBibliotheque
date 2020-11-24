<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/count_stock_element.php');
include('function/connexion_bdd.php');
 
if(isset($_POST['nom_oeuvre']) AND isset($_POST['etat_oeuvre']) AND isset($_POST['editeur_exemplaire'])){
    
    
    $request =  $bdd -> prepare('INSERT INTO liste_exemplaire (id_oeuvre, id_etat, editeur) VALUES (:id_oeuvre, :id_etat, :editeur)');

    $ident_exemplaire = $bdd -> prepare('SELECT * FROM liste_exemplaire WHERE id_oeuvre = :id_oeuvre'); 
    $ident_exemplaire-> execute(array(
        'id_oeuvre' => $_POST['nom_oeuvre']
    ));
    $compteur = $ident_exemplaire -> rowCount();

    $request -> execute(array(
        'id_oeuvre' => $_POST['nom_oeuvre'],
        'id_etat' => $_POST['etat_oeuvre'],
        'editeur' => htmlspecialchars($_POST['editeur_exemplaire'])
    ));

    update_stock($_POST['nom_oeuvre']);




    
    if($_SESSION['exemplaire']['nombre'] >= $_SESSION['exemplaire']['nombre_finale']){
        $_SESSION['exemplaire']=NULL;
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
                        
                        
                        <h2>Message de confirmation d'ajout</h2>
                        
                        <p class='p_end'>
                            L'oeuvre a bien été ajouté.
                        </p>
                        
                        <a href="add_book.php">Retour a la d'ajout de livre</a>
                        
                        
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
        header('Location:  add_book.php');
    }else{
        $_SESSION['exemplaire']['nombre']++;
    }
       

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href=" style6.css" />
    <link rel="stylesheet" href=" general-style-element.css" />
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

            <h1>Ajout de livre (<?php echo $_SESSION['exemplaire']['nombre'].'/'. $_SESSION['exemplaire']['nombre_finale'] ; ?> ):</h1>

            <section id="formulaire_ajout_exemplaire">

                <h2>Formulaire d'ajout d'exemplaire</h2>

                    <form method="POST"action="add_exemplaire_2.php">
                        <p>
                            <label for="nom_oeuvre">Entrer le nom de l'oeuvre:</label>
                            <select  name="nom_oeuvre" id="nom_oeuvre" required="required" >
                            <?php 

include('function/connexion_bdd.php');

$oeuvre_search = $bdd->prepare('SELECT id, nom FROM liste_oeuvre WHERE nom = :nom');
$oeuvre_search -> execute(array(
    'nom' => $_SESSION['exemplaire']['nom_oeuvre']
));
while($donnee = $oeuvre_search->fetch() ){
    echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';
}
?>
                            </select>
                        </p>
                        <p>
                            <label for="etat_oeuvre">Selectionner l'etat de l'exemplaire:</label>
                            <select  name="etat_oeuvre" id="etat_oeuvre" required="required" >
                            <?php 
                            include('function/connexion_bdd.php');  
                            $oeuvre_list = $bdd->query('SELECT * FROM etat_books ORDER BY id');
                            while($donnee = $oeuvre_list->fetch() ){
                                echo '<option  value='.$donnee['id'].'>'. $donnee['nom_etat'].'</option>';
                            }
                            ?>
                            </select>
                        </p>
                        <p>
                            <label for="editeur_exemplaire">Entrer le nom de l'editeur de l'exemplaire:</label>
                            <input type="text" name="editeur_exemplaire" id="editeur_exemplaire" placeholder="nom de l'editeur" required="required"/>
                        </p>

                        <input type="submit" name="valider" value="valider" />
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

</body>
</html>



<?php    
}

?> 