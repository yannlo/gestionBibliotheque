
<?php
include ('function/verified_session.php');
include('start_session_control.php');
session_destroy();
header('location: index.php');
?>