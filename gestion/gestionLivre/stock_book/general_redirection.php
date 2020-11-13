<?php
include('../../../function/verified_session.php');
include('../../../function/acces_admin_verification.php');
include('../../../function/geturl.php');

if(isset($_POST['check_formulaire'])){
    // echo $_POST['check_formulaire'];
    if($_POST['check_formulaire'] == 'ensemble_oeuvre'){
        header('Location: general_list_stock.php');
        exit();

    }else if ($_POST['check_formulaire'] == 'recherche_oeuvre' ){
        header('Location: general_stock_page.php');
        exit();

    }else{
        header('Location: general_list_stock.php');
        exit();
    }

}
else if(isset($_POST['check_mod'])){
    // echo $_POST['check_formulaire'];
    if($_POST['check_mod'] == 'mod_oeuvre'){
        header('Location: page_modifie_documentation.php');
        exit();

    }else if ($_POST['check_mod'] == 'mod_exemplaire' ){
        header('Location: mod_exemplaire.php');
        exit();

    }else{
        header('Location: affiche_doc_page_complet.php');
        exit();
    }

}
else if(isset($_POST['check_sup'])){
    // echo $_POST['check_formulaire'];
    if($_POST['check_sup'] == 'sup_oeuvre'){
        header('Location: sup_oeuvre.php');
        exit();

    }else if ($_POST['check_sup'] == 'sup_exemplaire' ){
        header('Location: sup_exemplaire.php');
        exit();

    }else{
        header('Location: affiche_doc_page_complet.php');
        exit();
    }

}
else{
    header('Location: gestion_livre_index.php');
    exit();
}
