<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php'); 
include('function/connexion_bdd.php');

if(isset($_POST['check_sup_conf'])){


    if($_POST['check_sup_conf'] == 'sup_confirmation'){
       
        $take_id_oeuvre = 0 ;

        foreach($_SESSION['oeuvre'] as $key => $val) {

            $take_id_oeuvre = (int) $key;

        }

        $sup_cover = $bdd -> query("SELECT nom_photo_couverture FROM liste_oeuvre WHERE id = '". $take_id_oeuvre . "' ");
        $cover_name ='';
        while ($sup_cover_ops = $sup_cover->fetch()) {
            $cover_name = $sup_cover_ops['nom_photo_couverture'];
        }
        
        if($cover_name != NULL) {
            unlink("imageAndLogo/image_book/$cover_name");
        }

        $sup_oeuvre_request = $bdd -> query("DELETE FROM liste_oeuvre WHERE id = '". $take_id_oeuvre . "' " );

        $sup_exemplaire_request = $bdd -> query("DELETE FROM liste_exemplaire WHERE id_oeuvre = '". $take_id_oeuvre . "' " );
 
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Gestion de stock de livre - Gestionnaire </title>
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

            <h1>Gestion des stocks de livre</h1>

            <section id="exemplaire_liste">

                <h2>Message de confirmation de suppression</h2>

                <p class='p_end'>
                    L'oeuvre a bien été suprimé.
                </p>

                <a href="gestion_livre_index.php">Retour a la page principale</a>


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


    }else if($_POST['check_sup_conf'] == 'sup_annulation'){
        header('Location: affiche_doc_page_complet.php');
        exit();
    }




}
else{

?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Gestion de stock de livre - Gestionnaire </title>
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="style6.css" />
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

            <h1>Gestion des stocks de livre</h1>

            <section id="confirm_sup_oeuvre">

                <h2>Confirmation de suppresion</h2>

                <form action="sup_oeuvre.php" method="POST">
                <p >
                    Souhaitez vous supprimer vraiment supprimer cette oeuvre??<br />
                    Gardez en tete que cette action est definitive
                    <div class='radio-style'>
                        <label for="check_oeuvre">
                            <input type="radio" name="check_sup_conf" value ="sup_confirmation" id="check_oeuvre" />
                            Oui, supprimer cette oeuvre
                        </label>

                        <label for="check_exemplaire">
                            <input type="radio" name="check_sup_conf" value ="sup_annulation" id="check_exemplaire" />
                            Non, revenir a la documentation de l'oeuvre
                        </label>
                        </div>
                    </p>
                    <input type="submit" value="confirmer" />
                </form>


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
}

?>