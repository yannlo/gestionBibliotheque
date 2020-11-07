<?php 
if ($_SESSION['type'] !== 'user'){
    header('location: index.php');
    exit();
}
?>