<?php 

// bloque l'acces au personne n'etaont pas admin (gestionnaire)

if ($_SESSION['type'] !== 'admin'){
    header('location: index.php');
    exit();
}
?>