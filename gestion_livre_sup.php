<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php'); 

?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Gestion de stock de livre - Gestionnaire </title>
    <link rel="stylesheet" href="style5.css" />
    <link rel="stylesheet" href="stock_book/gestion_livre_style.css" />
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
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
            <section id="choose_search">
            <h2>Selectionner l'element a suprimer</h2>

          <form action="stock_book/general_redirection.php" method="POST">
                <p>
                    Souhaitez vous supprimer l'oeuvre  ou un exemplaire de l'oeuvre: <br />
                    <div class='radio-style'>
                        <label for="check_oeuvre">
                            <input type="radio" name="check_sup" value ="sup_oeuvre" id="check_oeuvre" />
                            supprimer l'oeuvre
                        </label>

                        <label for="check_exemplaire">
                            <input type="radio" name="check_sup" value ="sup_exemplaire" id="check_exemplaire" />
                            suprimer un exemplaire
                        </label>
                        </div>
                    </p>
                    <input type="submit" value="Selectionner" />
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