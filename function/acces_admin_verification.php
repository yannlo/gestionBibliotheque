<?php 
if ($_SESSION['type'] !== 'admin'){
    header('location: index.php');
    exit();
}
?>