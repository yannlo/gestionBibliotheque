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
else{
    header('Location: gestion_livre_index.php');
    exit();
}
