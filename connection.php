<?php 
if (isset($_POST['mail']) AND isset($_POST['password'])){

    $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
	$recherche = $bdd->query('SELECT id, id_type_compte, id_other_information, email, pass_word, first_name, last_name
                            FROM all_comptes');

    $verifcation = $bdd -> prepare('SELECT nom FROM type_compte WHERE id = :id');

    $validation = array('valide_email'=>false,'valide_password' => false);

    $user_name = array();

	while($donnees = $recherche->fetch()){

		if(($donnees['email'] === $_POST['mail']) AND ($donnees['pass_word'] === $_POST['password'])){
            echo"<p style='background:white'> OK </p>";
            $verifcation -> execute(array(
                'id' => $donnees['id_type_compte']
            ));

            while($type_name = $verifcation->fetch()){

                if($type_name['nom'] === 'user'){

                    $validation['valide_email'] = true;
                    $validation['valide_password'] = true;
                    $user_name['first'] = $donnees['nom'];
                    $user_name['last'] = $donnees['prenom'];
                    session_start();
                    $_SESSION['id_user'] = $donnees['id'];
                    $_SESSION['type'] = $type_name['nom'] ;
                    $_SESSION['user_name'] = $user_name;
                    header('Location: index.php');
                    exit();

                }

                else if($type_name['nom'] === 'admin'){

                    $admin_lists = $bdd -> prepare('SELECT active FROM admins WHERE id = :id');

                    $admin_lists -> execute(array(
                        'id' => $donnees['id_other_information']
                    ));
                    
                    while($admin = $admin_lists->fetch()){

                        if($admin['active'] == true ){

                            $validation['valide_email'] = true;
                            $validation['valide_password'] = true;
                            $user_name['first'] = $donnees['nom'];
                            $user_name['last'] = $donnees['prenom'];
                            echo $user_name;
                            session_start();
                            $_SESSION['id_user'] = $donnees['id'];
                            $_SESSION['type'] = $type_name['nom'] ;
                            $_SESSION['user_name'] = $user_name;
                            header('Location: index.php');
                            exit();

                        }

                        else{

                            echo'<script language="Javascript">
			                    alert ("Ce gestionnaire n\'ai plus actif");
                                </script>';

                        }
                    }
                }
            }
        break;
        }
        else{
            echo'<script language="Javascript">
            alert ("email ou mot de passe erronée");
            </script>';
        }
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
            <form method="post" action="connection.php">
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