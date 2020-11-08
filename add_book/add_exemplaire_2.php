<?php
include('../function/verified_session.php');
// print_r($_SESSION['exemplaire']);
include('../function/acces_admin_verification.php');
include('../function/count_stock_element.php');
 
if(isset($_POST['nom_oeuvre']) AND isset($_POST['etat_oeuvre']) AND isset($_POST['editeur_exemplaire'])){
    $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
    $request =  $bdd -> prepare('INSERT INTO liste_exemplaire (id_oeuvre, id_etat, editeur) VALUES (:id_oeuvre, :id_etat, :editeur)');

    $request -> execute(array(
        'id_oeuvre' => $_POST['nom_oeuvre'],
        'id_etat' => $_POST['etat_oeuvre'],
        'editeur' => htmlspecialchars($_POST['editeur_exemplaire'])
    ));

    update_stock($_POST['nom_oeuvre']);

    ;   
    
    if($_SESSION['exemplaire']['nombre'] >= $_SESSION['exemplaire']['nombre_finale']){
        echo "finished";
        $_SESSION['exemplaire']=NULL;
        header('Location: ../add_book.php');
    }else{
        $_SESSION['exemplaire']['nombre']++;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../style4.css" />
    <link rel="stylesheet" href="../general-style-element.css" />
    <link rel="stylesheet" href="style_add_parti.css" />

    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <title>Ajout de livre - Gestionnaire</title>
</head>
<body>

    <div id="container">
  
        <head>
            <?php include('../headerAndFooter/menu.php'); ?>
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

                            $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

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
                            <input type="text" name="editeur_exemplaire" id="editeur_exemplaire" placeholder="nom de l'editeur" required="required"/>
                        </p>

                        <input type="submit" name="valider" value="valider" />
                    </form>
                </section>

        </div>
        
        <?php include('../headerAndFooter/footer.php'); ?>
        
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


