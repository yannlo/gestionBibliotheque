<?php 

 // bloque l'acces a admin

if (isset($_SESSION['type']) AND $_SESSION['type'] == 'admin'){
    header('location: index.php');
    exit();
}
?>