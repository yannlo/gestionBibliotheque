<?php

function getMatricule($id){
    # crée automatiquement un matricule a partire de l'id du user entrer en parametre

    return date('Y', time()).'-'.sprintf("%05d", $id).'-M';
}

?>