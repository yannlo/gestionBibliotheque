<?php

// bloque l'acces au personne n'etaont pas user (client)


if ($_SESSION['type'] !== 'user'){
    header('location: index.php');
    exit();
}
?>