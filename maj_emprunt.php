<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php'); 
include('function/connexion_bdd.php');
$emprunt_choose = $bdd -> prepare('SELECT * FROM liste_emprunt WHERE id = :id ');
foreach($_SESSION['emprunt'] as $key => $val) {

    $emprunt_choose -> execute(array(
        'id' => htmlspecialchars($key)
    ));
}


$compteur = $emprunt_choose -> rowCount();
if ($compteur == 0 OR $compteur > 1 ){
    echo $compteur;
    header('Location: index.php');
    exit();
}

if(isset($_POST['exemplaire']) AND isset($_POST['date_emprunt']) AND isset($_POST['date_fin_emprunt_sup'])){
    $update_exemplaire = $bdd -> prepare('UPDATE liste_exemplaire SET etat_emprunt = "1" WHERE id = :id');
    $update_exemplaire -> execute(array(
        'id' => $_POST['exemplaire']
    ));
    $select_numero_exemplaire = $bdd -> prepare('SELECT * FROM liste_exemplaire WHERE id = :id');
    $select_numero_exemplaire -> execute(array(
        'id' => $_POST['exemplaire']
    ));
    
    $numero_exemplaire = 0;
    while ($numero = $select_numero_exemplaire->fetch()){
        $numero_exemplaire = $numero['nombre_emprunt'];
    }
    
    $numero_exemplaire++ ;
    $update_exemplaire = $bdd -> prepare('UPDATE liste_exemplaire SET nombre_emprunt = :nombre_emprunt WHERE id = :id');
    $update_exemplaire -> execute(array(
        'nombre_emprunt' => $numero_exemplaire,
        'id' => $_POST['exemplaire']
    ));

    $date1 = preg_replace('#([0-9]{2})/([0-9]{2})/([0-9]{4})#',"$3-$2-$1",$_POST['date_emprunt']);
    $date2 = preg_replace('#([0-9]{2})-([0-9]{2})-([0-9]{4})#',"$3-$2-$1",$_POST['date_fin_emprunt_sup']);
    
    $update_emprunt = $bdd -> prepare (' UPDATE liste_emprunt SET date_emprunt = :date_emprunt, id_exemplaire =:id_exemplaire, id_etat_initial = :id_etat_initial, date_retour_supposer= :date_retour_supposer WHERE id = :id');
    $choose_exemplaire = $bdd-> prepare("SELECT * FROM liste_exemplaire WHERE id = :id");
    $choose_exemplaire -> execute(array(
        'id' => htmlspecialchars($_POST['exemplaire'])

    ));
    $id_etat_exemplaire ='';
    while ( $exemplaire = $choose_exemplaire -> fetch()){
       $id_etat_exemplaire = $exemplaire['id_etat'];
    }

    foreach($_SESSION['emprunt'] as $key => $val) {
        $update_emprunt -> execute(array(
            'id' => htmlspecialchars($key),
            'date_emprunt' => $date1,
            'id_exemplaire' => $_POST['exemplaire'],
            'id_etat_initial' =>$id_etat_exemplaire,
            'date_retour_supposer' => $date2
        ));
    }
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
		<title>Detail de l'emprunt - Gestionnaire </title>
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
        <link rel="stylesheet" href="style6.css"/>
        <link rel="stylesheet" href="documentation_books/style_document5.css"/>
        <link rel="stylesheet" href="general-style-element.css"/>
        <link rel="stylesheet" href="gestionClient/client_style_3.css"/>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>

	<body>
		
		<div class="container">

			<header>

				<?php  include("headerAndFooter/menu.php") ?>

            </header>

            <div class="center">

                <h1>Detail de l'emprunt</h1>
                <section id='confirmation'>
                    <h2>Message de mise a jour des données</h2>
                    <p>
                        Les informations de cette emprunt on bien été mise a jour.
                    </p>
                    <div class="bottom_link">
                    <a href="detail_emprunt.php">Revenir au detail de la demande</a>
                    <a href="maj_emprunt.php">Continuer la mise a jour</a>
                    </div>
                </section>

            </div>
        
        <?php include('headerAndFooter/footer.php'); ?>
        
    </div>
    <script>
        const ver1 = document.getElementById('formulaire_ajout_exemplaire');
        ver1.style.display = 'block';
        ver1.style.height= '60vh';
    </script>
    <script>
		const identifation_page ='client';
       	actived_link_page(identifation_page);
	</script>

</body>

<?php

}
else{

    if(isset($_POST['date_fin_emprunt_eff']) AND isset($_POST['etat_post'])){
        $date3 = preg_replace('#([0-9]{2})-([0-9]{2})-([0-9]{4})#',"$3-$2-$1",$_POST['date_fin_emprunt_eff']);
    
        $update_emprunt = $bdd -> prepare (' UPDATE liste_emprunt SET date_retour_effectif = :date_retour_effectif, id_etat_final =:id_etat_final WHERE id = :id');
        $choose_exemplaire = $bdd-> prepare("UPDATE liste_exemplaire SET id_etat =:id_etat, etat_emprunt=:etat_emprunt WHERE id = :id ");
        while ($emprunt = $emprunt_choose -> fetch()){
            $choose_exemplaire -> execute(array(
                'id' => htmlspecialchars($emprunt['id_exemplaire']),
                'id_etat'=> htmlspecialchars($_POST['etat_post']),
                'etat_emprunt'=>'0'
            ));
        }
        // $choose_exemplaire = $bdd-> prepare("SELECT * FROM liste_exemplaire WHERE id = :id ");
        // while ($emprunt = $emprunt_choose -> fetch()){
        //     $choose_exemplaire -> execute(array(
        //         'id' => htmlspecialchars($emprunt['id_exemplaire'])
        //     ));
        // }
        // while ($exemplaire = $choose_exemplaire -> fetch()){
        
        // }

        // $choose_exemplaire = $bdd-> prepare("UPDATE liste_exemplaire SET id_etat =:id_etat, etat_emprunt=:etat_emprunt WHERE id = :id ");
    
        foreach($_SESSION['emprunt'] as $key => $val) {
    
            $update_emprunt -> execute(array(
                'id' => htmlspecialchars($key),
                'date_retour_effectif' => $date3,
                'id_etat_final' =>$_POST['etat_post']
            ));
        }
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
                <title>Detail de l'emprunt - Gestionnaire </title>
            <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
                <link rel="stylesheet" href="style6.css"/>
                <link rel="stylesheet" href="documentation_books/style_document5.css"/>
                <link rel="stylesheet" href="general-style-element.css"/>
                <link rel="stylesheet" href="gestionClient/client_style_3.css"/>
                <script src="https://kit.fontawesome.com/a076d05399.js"></script>
            </head>
        
            <body>
                
                <div class="container">
        
                    <header>
        
                        <?php  include("headerAndFooter/menu.php") ?>
        
                    </header>
        
                    <div class="center">
        
                        <h1>Detail de l'emprunt</h1>
                        <section id='confirmation'>
                            <h2>Message de mise a jour des données</h2>
                            <p>
                                Les informations de cette emprunt on bien été entierement completer.
                            </p>
                            <div class="bottom_link">

                                <a href="detail_emprunt.php">Revenir au detail de la demande</a>
                            </div>
                        </section>
        
                    </div>
                
                <?php include('headerAndFooter/footer.php'); ?>
                
            </div>
            <script>
                const ver1 = document.getElementById('formulaire_ajout_exemplaire');
                ver1.style.display = 'block';
                ver1.style.height= '60vh';
            </script>
            <script>
                const identifation_page ='client';
                   actived_link_page(identifation_page);
            </script>
        
        </body>
        
        <?php

    }

   

    
    while ($emprunt = $emprunt_choose -> fetch()){
        $saisie_etat = $bdd ->  query('SELECT * FROM etat_books ');
        if($emprunt['id_exemplaire'] == 0 OR $emprunt['date_emprunt'] == NULL OR $emprunt['date_retour_supposer'] == NULL){
            ?>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Detail de l'emprunt - Gestionnaire </title>
    <link rel="stylesheet" href="style6.css" />
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="stock_book/gestion_livre_style.css" />
    <link rel="stylesheet" href="general-style-element.css" />
    <link rel="stylesheet" href="gestionClient/client_style_3.css"/>
    <link rel="stylesheet" href="documentation_books/information_comp.css" />
    <link rel="stylesheet" href="documentation_books/style_document3.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>

    <div class="container">
        
        
        <header>
            <?php  include("headerAndFooter/menu.php") ?>
        </header>
        
        
        
        <div class="center">
            <h1>Detail de l'emprunt</h1>
            
            <section id="mod_exemplaire">
                <h2>Mise a jour des données de l'emprunt (1/2)</h2>
                
                <form action="maj_emprunt.php" method="post">
                    <?php

if($emprunt['id_exemplaire'] == 0){   
    ?>               

<p>
    <label for="exemplaire">Selectionner l'identifiant de l'exemplaire:</label>
    <select  name="exemplaire" id="exemplaire"  required="required"> 
        <?php 
                        $choose_exemplaire = $bdd-> query("SELECT * FROM liste_exemplaire WHERE id_oeuvre = '".$emprunt['id_oeuvre']."' AND etat_emprunt ='0' ORDER BY id");
                        $recherche_exemplaire = $bdd -> query('SELECT * FROM liste_emprunt');
                        while ( $exemplaire = $choose_exemplaire -> fetch()){
                                        echo '<option value="'.  $exemplaire['id'] .'" >' .  $exemplaire['id'] . '</option>';
                       
                        }
?>
                        </select>
                    </p>
<?php 
}
if($emprunt['date_emprunt'] == NULL){

    ?>
                <p>
                    <label for="date_emprunt">Date de debut de l'emprunt:</label>
                    <input type="date" name="date_emprunt" id="date_emprunt"   required="required"/>
                </p>
                
                
                <?php
    
}

if($emprunt['date_retour_supposer'] == NULL){
?>
                <p>
                    <label for="date_fin_emprunt_sup">Date de fin de l'emprunt supposer:</label>
                    <input type="date" name="date_fin_emprunt_sup" id="date_fin_emprunt_sup" required="required"/>
                </p>
                
                <?php
}

?>
<?php
    
    
    

    ?> 
<p class="information_comp"><span>ATTENTION:</span> <br/>
                         Une fois confirmer les informations entrer ne seront plus modifiable!!! </span> 
                        </p>

<input type="submit" name="comfirmer" id="confirmer" value="confirmer"/>


</form> 
</section>


</div>


<?php include('headerAndFooter/footer.php'); ?>



</div>


<script>
    const identifation_page = 'client';
    actived_link_page(identifation_page);
    </script>


</body>

</html>

<?php
    }else{ 
        ?>

<!DOCTYPE html
PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Detail de l'emprunt - Gestionnaire </title>
    <link rel="stylesheet" href="style6.css" />
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="stock_book/gestion_livre_style.css" />
    <link rel="stylesheet" href="general-style-element.css" />
    <link rel="stylesheet" href="gestionClient/client_style_3.css"/>
    <link rel="stylesheet" href="documentation_books/style_document3.css" />
    <link rel="stylesheet" href="documentation_books/information_comp.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>
    
    <div class="container">
        
        
        <header>
            <?php  include("headerAndFooter/menu.php") ?>
        </header>
        
        
        
        <div class="center">
            <h1>Detail de l'emprunt</h1>
            
            <section id="mod_exemplaire">
                <h2>Mise a jour des données de l'emprunt (2/2)</h2>
                
                <form action="maj_emprunt.php" method="post">
                    
                    <p>
                        <label for="date_fin_emprunt_eff">Date de fin d'emprunt effectif:</label>
                        <input type="date" name="date_fin_emprunt_eff" id="date_fin_emprunt_eff"  required="required"/>
                    </p>
                    
                    <p>
                        <label for="etat_post">Selectionner l'identifiant de l'exemplaire:</label>
                        <select  name="etat_post" id="etat_post"  required="required"> 
                            <?php
                            $etat_besoin='0';

                                    $choose_exemplaire = $bdd-> query("SELECT * FROM liste_exemplaire WHERE id_oeuvre = '".$emprunt['id_oeuvre']."' AND etat_emprunt ='0' ORDER BY id='".$emprunt['idexemplaire']."'");
                                    $etat_besoin = 0;
                                    while ( $exemplaire = $choose_exemplaire -> fetch()){
                                        $etat_besoin =$exemplaire['id_etat'];
                                    }
                                    $etat_post_liste = $bdd-> query("SELECT * FROM etat_books WHERE id ");
                                    while ( $etat_post = $etat_post_liste -> fetch()){
                                        if($etat_post['id'] >= $etat_besoin){
                                            echo '<option value="'.  $etat_post['id'] .'" >' .  $etat_post['nom_etat'] . '</option>';                
                                        }
                                    }

                                   
                                           
        
                        ?>
                        </select>
                    </p>
                    <p class="information_comp"><span>ATTENTION:</span> <br/>
                             Une fois confirmer les informations entrer ne seront plus modifiable!!! </span> 
                            </p>
            
                    <input type="submit" name="comfirmer" id="confirmer" value="confirmer"/>
                    
                    
                </form>
                <div class="bottom_link">
                    <a href="list_emprunt.php">Retour</a>
                </div>
            </section>

            
        </div>

        
        <?php include('headerAndFooter/footer.php'); ?>
        
        
        
    </div>
    
    
    <script>
        const identifation_page = 'client';
        actived_link_page(identifation_page);
        </script>


</body>

</html>
<?php

}
}



}
?>

