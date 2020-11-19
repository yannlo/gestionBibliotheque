<?php

function getMatricule($id){
    return date('Y', time()).'-'.sprintf("%05d", $id).'-M';
}

?>