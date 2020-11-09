<?php
function geturl() {
    $adresse = $_SERVER['PHP_SELF'];
    $i = 0;
    foreach($_GET as $cle => $valeur){
        if($cle !=='page'){
            $adresse .= ($i == 0 ? '?' : '&').$cle.($valeur ? '='.$valeur : '');
            $i++;
        }
    }
    return $adresse;
}
?>