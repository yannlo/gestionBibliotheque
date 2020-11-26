<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');

include('function/connexion_bdd.php');
$list_user = $bdd->query('SELECT * FROM all_comptes WHERE id_type_compte = "2"');

if (isset($_SESSION['emprunt'])) {
    $emprunt_choose = $bdd -> prepare('SELECT * FROM liste_emprunt WHERE id = :id ');
    foreach($_SESSION['emprunt'] as $key => $val) {

        $emprunt_choose -> execute(array(
            'id' => htmlspecialchars($key)
        ));
    }
    $compteur = $emprunt_choose -> rowCount();
    if ($compteur == 0 OR $compteur > 1 ){
        echo $compteur;
        header('Location: index.php');
        exit();
    }else{

    while ($emprunt = $emprunt_choose ->fetch()){
        $select_oeuvre = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id = :id');
        $select_oeuvre->execute(array(
            'id' => $emprunt['id_oeuvre']
        ));
        $select_etat = $bdd -> prepare('SELECT * FROM etat_books WHERE id = :id');
        $select_etat->execute(array(
            'id' => $emprunt['id_oeuvre']
        ));
        $date1 = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$emprunt['date_emprunt']);
        $date = date('Y-m-d');
        $date_val1 =  new DateTime($date);
        $date_val2 = new DateTime($emprunt['date_retour_supposer']);
        $date_diff = $date_val1 -> diff($date_val2);
        $date_actu = $date_diff->format('%a');
        $date2 = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$emprunt['date_retour_supposer']);
        $date3 = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$emprunt['date_retour_effectif']);
        $select_user = $bdd -> prepare('SELECT * FROM all_comptes WHERE id = :id');
        $select_user->execute(array(
            'id' => $emprunt['id_user']
        ));
        $select_exemplaire = $bdd -> prepare('SELECT * FROM liste_exemplaire WHERE id = :id');
        $select_exemplaire->execute(array(
            'id' => $emprunt['id_exemplaire']
        ));
        $select_etat = $bdd -> prepare('SELECT * FROM etat_books WHERE id = :id');
        $select_etat->execute(array(
            'id' => $emprunt['id_etat_initial']
        ));
        $select_etat2 = $bdd -> prepare('SELECT * FROM etat_books WHERE id = :id');
        $select_etat2->execute(array(
            'id' => $emprunt['id_etat_final']
        ));
        while($oeuvre_choose = $select_oeuvre ->fetch() AND $user = $select_user ->fetch()){

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
            <title>Detail de emprunt - Gestionnaire </title>
            <link rel="stylesheet" href="style6.css"/>
            <link rel="stylesheet" href="documentation_books/style_document5.css"/>
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
            <link rel="stylesheet" href="general-style-element.css"/>
            <link rel="stylesheet" href="stock_book/gestion_livre_style.css"/>
            <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        </head>
    
        <body>
            
            <div class="container">
    
                <header>
                    <?php  include("headerAndFooter/menu.php") ?>
                </header>
    
                <div class="center">
    
                    <h1 class="h1_doc_liv">Detail de l'emprunt </h1>
    
                    <section id="information_oeuvre">

                        <div class="parti2">
                            <h2><strong>Nom du demandeur:</strong> <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h2>
                            <h2><strong>Nom de l'oeuvre:</strong> <?php echo $oeuvre_choose['nom']; ?></h2>
                            <h2><strong>Identifiant de l'exemplaire:</strong> <?php if(($compteur = $select_exemplaire->rowCount()) != 0){while($exemplaire = $select_exemplaire->fetch()) {echo $exemplaire['id'] ;}}else{echo'none';}?></h2>
                            <h2><strong>Date de l'emprunt:</strong> <?php if(!empty($date1)){ echo $date1 ;} else{echo 'none';}?></h2>
                            <h2><strong>Etat initial:</strong> <?php if(($compteur = $select_etat->rowCount()) != 0){while($etat = $select_etat ->fetch()){ echo $etat['nom_etat'];}}else{ echo 'none' ;} ?></h2>
                            <h2><strong>Date de restitution supposer:</strong> <?php if(!empty($date2)){ echo $date2 ;} else{echo 'none';}?></h2>
                            <h2><strong>Date de restitution effective:</strong> <?php if($emprunt['date_retour_effectif'] == NULL){ echo 'none' ;}else{ echo $date3;} ?></h2>
                            <h2><strong>etat au retour:</strong> <?php if($emprunt['id_etat_final'] == NULL){ echo 'none' ;}else{ if(($compteur = $select_etat2->rowCount()) != 0){while($etat2 = $select_etat2 ->fetch()){ echo $etat2['nom_etat'];}}else{ echo 'none' ;}} ?></h2>
                        </div>
<?php
    $saisie_etat = $bdd ->  query('SELECT * FROM etat_books ');
    if($emprunt['id_exemplaire'] == 0 OR $emprunt['date_emprunt'] == NULL OR $emprunt['date_retour_supposer'] == NULL OR $emprunt['date_retour_effectif'] == NULL OR $emprunt['id_etat_final'] == NULL){

?>
                        <a href="maj_emprunt.php">Mise a jour</a>
 <?php
    }

 ?>                   
                        <a href="list_emprunt.php">Retour</a>


                    </section>
    

                </div>

                
    
                <?php include('headerAndFooter/footer.php'); ?>
            </div>
    
            <script>
        const identifation_page = 'client';
        actived_link_page(identifation_page);
    </script>   
        </body>
    </html>
    
    <?php
    }

            }

        }
 
    }

else{
    header('Location: index.php');
    exit();
}


?>
