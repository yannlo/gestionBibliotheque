<?php
include('../../../function/verified_session.php');
$_SESSION['type']= 'admin';
include('../../../function/acces_admin_verification.php');
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
?>