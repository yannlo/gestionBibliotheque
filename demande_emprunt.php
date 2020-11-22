<?php
include('function/verified_session.php');
include('function/acces_user_verification.php');
include('function/geturl.php'); 
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if(isset($_POST['check_envoie_demande'])){


    if($_POST['check_envoie_demande'] == 'demande_confirmation'){
       
        $take_id_oeuvre = 0 ;

        foreach($_SESSION['oeuvre'] as $key => $val) {

            $take_id_oeuvre = (int) $key;

        }


        $verified_demande = $bdd -> prepare('SELECT * FROM demande_emprunt WHERE id_oeuvre = :id_oeuvre   AND id_demandeur = :id_demandeur');
        $verified_demande -> execute(array(
            'id_oeuvre' => $take_id_oeuvre,
            'id_demandeur' =>  $_SESSION['id_user']
        ));
        $compte_element = 0;
        while ($demande = $verified_demande->fetch()){
            if($demande['confirmation'] == NULL){
                $compte_element++;
            }
        }
       if ($compte_element == 0){

        $emprunt_list = $bdd -> prepare('SELECT * FROM liste_emprunt WHERE id_user = :id_demandeur');
        $emprunt_list -> execute(array(
             'id_demandeur' => $_SESSION['id_user']
         ));
         $incr = 0;
         while ($emprunt_search = $emprunt_list -> fetch()){
             if ($emprunt_search['date_retour_effectif'] == NULL AND $emprunt_search['id_oeuvre'] == $take_id_oeuvre ) {
                 $incr++;
             }
         }
         if ( $incr == 0){
             $envoie_demande = $bdd -> prepare("INSERT INTO demande_emprunt( id_demandeur, id_oeuvre, date_demande) VALUES ( :id_demandeur , :id_oeuvre, CURDATE() )");
             $envoie_demande -> execute(array(
                 'id_demandeur' => $_SESSION['id_user'],
                 'id_oeuvre' => $take_id_oeuvre
             ));
             $message = ' La demande d\'emprunt a bien été envoyée.';

         }else{ 
            $message = 'Une demande a deja été envoyé ou vous avez deja un emprunt encour avec ce livre. ';

         }
       }
        else{
            $message = 'Une demande a deja été envoyé. ';

        }
 
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Demande d'emprunt - client </title>
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

            <h1>Demande d'emprunt</h1>

            <section id="exemplaire_liste">

                <h2>Message de confirmation d'envoie de demande</h2>

                <p class='p_end'>
                   <?php echo $message; ?>
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


    }else if($_POST['check_envoie_demande'] == 'demande_annulation'){
        header('Location: affiche_doc_page.php');
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
    <title>Demande d'emprunt - Client </title>
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

            <h1>Demande d'emprunt</h1>

            <section id="confirm_sup_oeuvre">

                <h2>Confirmation de demande d'emprunt</h2>

                <form action="demande_emprunt.php" method="POST">
                <p >
                    Souhaitez vous envoyez une demande d'emprunt pour cette oeuvre??<br />
                    <div class='radio-style'>
                        <label for="check_oeuvre">
                            <input type="radio" name="check_envoie_demande" value ="demande_confirmation" id="check_oeuvre" />
                            Oui, envoyer une demande.
                        </label>

                        <label for="check_exemplaire">
                            <input type="radio" name="check_envoie_demande" value ="demande_annulation" id="check_exemplaire" />
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