<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php'); 
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if(isset($_POST['check_sup_conf'])){


    if($_POST['check_sup_conf'] == 'sup_confirmation'){
       
        $take_id_user = 0 ;

        foreach($_SESSION['user'] as $key => $val) {

            $take_id_user = (int) $key;

        }

        $select_user = $bdd -> query("SELECT * FROM all_comptes WHERE id = '". $take_id_user . "' ");
        $inf_comp ='';
        while ($user = $select_user->fetch()) {
            $inf_comp = $user['id_other_information'];
        }



        $select_inf = $bdd -> query("SELECT * FROM  users WHERE id = '". $inf_comp . "' ");
        $cover_name ='';
        while ($inf = $select_inf->fetch()) {
            $cover_name = $inf['nom_photo_user'];
        }

        if($cover_name != NULL) {
            unlink("imageAndLogo/photo_user/$cover_name");
        }
        $sup_user_request = $bdd -> query("DELETE FROM all_comptes WHERE id = '". $take_id_user . "' " );

        $sup_inf_request = $bdd -> query("DELETE FROM users WHERE id = '". $inf_comp . "' " );
 
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>documentation du client - Gestionnaire </title>
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

            <h1>Documentation de client</h1>

            <section id="exemplaire_liste">

                <h2>Message de confirmation de suppression</h2>

                <p class='p_end'>
                    Le client a bien été suprimé.
                </p>

                <a href="client_doc_index.php">Retour a la page principale</a>


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
        header('Location: doc_user_complet.php');
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
    <title>Documentation client - Gestionnaire </title>
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

            <h1> Documentation de client</h1>

            <section id="confirm_sup_oeuvre">

                <h2>Confirmation de suppresion</h2>

                <form action="supp_client.php" method="POST">
                <p >
                    Souhaitez vous supprimer vraiment supprimer cet utilisateur ??<br />
                    Gardez en tete que cette action est definitive
                    <div class='radio-style'>
                        <label for="check_oeuvre">
                            <input type="radio" name="check_sup_conf" value ="sup_confirmation" id="check_oeuvre" />
                            Oui, supprimer cet client
                        </label>

                        <label for="check_exemplaire">
                            <input type="radio" name="check_sup_conf" value ="sup_annulation" id="check_exemplaire" />
                            Non, revenir a la documentation du client
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