<?php 
		if (isset($_POST['mail']) AND isset($_POST['password'])){
			$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			$reponse = $bdd->query('SELECT email , motDePasse FROM gestionnaire');
			$validation =array('valide_email'=>false,'valide_password'=>false);
			while($donnees = $reponse->fetch()){
				if($donnees['email'] == $_POST['mail']){
					$validation['valide_email'] = true;
					break;
				}
			}
			while($donnees = $reponse->fetch()){
				if($donnees['motDePasse'] == $_POST['valide_password']){
					$validation['valide_password'] = true;
					break;
				}
			}
			if($validation['valide_email'] == true AND $validation['valide_password'] == true ){
				session_start();
				$_SESSION['type'] = 'admin';
				header('Location: index.php');
				exit();
			}else{
				echo '<script language="Javascript">
						alert ("email ou mot de passe erronée");
					</script>';

			}
		}
?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>connexion - Bibliotheque</title>
    <link rel="stylesheet" href="style2.css" />
    <link rel="stylesheet" href="style-connection.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
    <header>
		<?php include("headerAndFooter/menu.php") ?>
	</header>

    <div class="center">
        <div class="container">
            <div class="text">Connectez-vous</div>
            <form method="post" action="index.php">
                <div class="data">
                    <label for="mail">Entrer votre mail:</label>
                    <input type="email" name="mail" id="mail" placeholder="mon.mail@exemple.com" required="required" />
                </div>
                <div class="data">
                    <label for="password">Entrer votre mot de passe:</label><br />
                    <input type="password" name="password" id="password" placeholder="password" required="required" />
                </div>
                <div class="forgot-pass">
                    <a href="#">Mot de passe oublié?</a></div>
                <div class="btn">
                    <div class="inner">
                    </div>
                    <button type="submit">connexion</button>
                </div>
            </form>
        </div>
    </div>
    <script>
		const identifation_page ='connect';
        actived_link_page(identifation_page);
	</script>
</body>

</html>