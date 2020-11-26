<?php include ('function/verified_session.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html 
	xmlns="http://www.w3.org/1999/xhtml"
	xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
	xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd"
	xml:lang="fr"
>
	<head>
		<meta http-equiv="content-type" content="text/html" charset="utf-8" />
		<title>Accueil </title>
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
		<link rel="stylesheet" href="style7.css"/>
		<link rel="stylesheet" href="general-style-element.css" />
		<script src="https://kit.fontawesome.com/a076d05399.js"></script>
		<?php 
		if(isset($_SESSION['type'])){
			if($_SESSION['type'] == 'admin'){	
				echo' <link rel="stylesheet" href="gestion/style1.css"/>';
			}else{ 
				echo '<link rel="stylesheet" href="clientConnect/style2.css" />';
			}
		}else{
    
			echo '<link rel="stylesheet" href="clientConnect/style2.css" />';
		}

		?>
		
		
	</head>

	<body>
		
		<div class="container">
		<?php 
		if(isset($_SESSION['type'])){
			if($_SESSION['type'] == 'admin'){
?>
			<header>
				<?php  include("headerAndFooter/menu.php") ?>
			</header>
<?php
					
			}else{
				?>
			<header>
				<?php  include("headerAndFooter/menu.php") ?>
				<div class=" wrapper">
					<div class="content">
						<h1>Bienvenue sur La bibliotheque</h1>
						<p class="paragraphe">
							Ici vous trouverez des oeuvres de grandes divercitées afin de parfaire votre culture général,
							mais aussi des oeuvres pour vous divertir de tout type et de toute categorie. <br/>
							Notre objectif est de vous encourager à lire et developper votre creativité.
						</p>
						
					</div>
        		</div>
			</header>
<?php

			}
		}else{
?>
			<header>
				<?php  include("headerAndFooter/menu.php") ?>
				<div class=" wrapper">
					<div class="content">
						<h1>Bienvenue sur La bibliotheque</h1>
						<p class="paragraphe">
							Ici vous trouverez des oeuvres de grandes divercitées afin de parfaire votre culture général,
							mais aussi des oeuvres pour vous divertir de tout type et de toute categorie. <br/>
							Notre objectif est de vous encourager à lire et developper votre creativité.
						</p>
						
					</div>
        		</div>
			</header>
<?php
		}

		?>
			


		<?php 
		if(isset($_SESSION['type'])){

			if($_SESSION['type'] == 'admin'){	
				include("gestion/indexGestion.php");
			}
			else if($_SESSION['type'] == 'user'){
				include("clientConnect/indexClientConnect.php");	
			}

		}
		
		else{
			include("clientConnect/indexClientConnect.php");	
		}

		?>

			<?php include("headerAndFooter/footer.php") ?>
		</div>
		<script>
			// const center = document.getElementsByClassName('center')[0];
			// center.style.padding = '100px 0px';
			const identifation_page ='index';
       		actived_link_page(identifation_page);
		</script>
	</body>


</html>