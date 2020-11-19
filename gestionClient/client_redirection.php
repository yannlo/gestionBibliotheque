<?php
include('../function/verified_session.php');
include('../function/acces_admin_verification.php');
include('../function/geturl.php');

if(isset($_POST['check_formulaire'])){
    // echo $_POST['check_formulaire'];
    if($_POST['check_formulaire'] == 'ensemble_client'){
        header('Location: ../client_list.php');
        exit();

    }else if ($_POST['check_formulaire'] == 'recherche_client' ){
        header('Location: ../search_client.php');
        exit();

    }else{
        header('Location: ../client_doc_index.php');
        exit();
    }

}
else{
    header('Location: ../client_doc_index.php');
    exit();
}
