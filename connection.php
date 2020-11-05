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
            <form action="#">
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