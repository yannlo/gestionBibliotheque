<?php
session_start();

$_SESSION['type']='admin';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html 
	xmlns="http://www.w3.org/1999/xhtml"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd"
	xml:lang="fr"
>
	<head>

		<meta http-equiv="content-type" content="text/html" charset="utf-8" />
		<title>Acceuil - Gestionnaire </title>
		<link rel="stylesheet" href="style.css"/>
		
	</head>

	<body>
	<?php include("headerAndFooter/header.php") ?>
	
	
		
	<?php include("headerAndFooter/footer.php") ?>
	</body>

</html>