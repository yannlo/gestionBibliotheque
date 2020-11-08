<?php
include('../../../../function/verified_session.php');
$_SESSION['type']='admin';
include('../../../../function/acces_admin_verification.php');
if(isset($_POST['nom_oeuvre']) AND isset($_POST['type_oeuvre']) AND isset($_POST['categorie_oeuvre']) AND isset($_POST['stock_exemplaire']) AND isset($_POST['description_oeuvre'])){

    $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
    $request =  $bdd -> prepare('INSERT INTO liste_oeuvre (nom, id_type, id_categorie, description_oeuvre) VALUES (:nom, :id_type, :id_categorie, :description_oeuvre)');

    $search = $bdd->query('SELECT nom FROM liste_oeuvre');


    while ($donnee = $search->fetch() ){
        if(preg_match('#'.$donnee['nom'].'#i',$_POST['nom_oeuvre'])){
            echo'<script> alert("cette oeuvre existe deja"); </script>';
            header('Location: add_book.php');
            exit();
        }
    }


    $request -> execute(array(
        'nom' => htmlspecialchars($_POST['nom_oeuvre']),
        'id_type' => $_POST['type_oeuvre'],
        'id_categorie' => $_POST['categorie_oeuvre'],
        'description_oeuvre' => htmlspecialchars($_POST['description_oeuvre'])
    ));
    $_SESSION['exemplaire'] = array();
    $search = $bdd->prepare('SELECT id FROM liste_oeuvre WHERE nom = :nom');
    $search -> execute(array(
        'nom' => $_POST['nom'],
    ));
    $_SESSION['exemplaire']['nom_oeuvre'] = $_POST['nom_oeuvre'];


    $_SESSION['exemplaire']['nombre'] = 1;
    

    $_SESSION['exemplaire']['nombre_finale'] = $_POST['stock_exemplaire'];

    header('Location: add_exemplaire_2.php');

    exit();




}else if(isset($_POST['nom_oeuvre']) AND isset($_POST['etat_oeuvre']) AND isset($_POST['editeur_exemplaire'])){
    $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
    $request =  $bdd -> prepare('INSERT INTO liste_exemplaire (id_oeuvre, id_etat, editeur) VALUES (:id_oeuvre, :id_etat, :editeur)');
    $compteur_ajout =  $bdd -> prepare('INSERT INTO liste_exemplaire (stock_exemplaire) VALUES (:stock_exemplaire)');
    $request -> execute(array(
        'id_oeuvre' => $_POST['nom_oeuvre'],
        'id_etat' => $_POST['etat_oeuvre'],
        'editeur' => htmlspecialchars($_POST['editeur_exemplaire'])
    ));
    $compteur_ajout-> execute(array('stock_exemplaire' => $compteur));
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
    <link rel="stylesheet" href="../../../../style4.css" />
    <link rel="stylesheet" href="../../../../general-style-element.css" />
    <link rel="stylesheet" href="style.css" />

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <title>Ajout de livre - Gestionnaire</title>
</head>
<body>

    <div id="container">
  
        <head>
            <?php include('../../../../headerAndFooter/menu.php'); ?>
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

                <form method="POST"action="add_book.php">

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
        
        <?php include('../../../../headerAndFooter/footer.php'); ?>
        
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


</body>
</html>