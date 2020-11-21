<?php 


if (isset($_SESSION['type']) AND $_SESSION['type'] == 'admin'){
    header('location: index.php');
    exit();
}
?>