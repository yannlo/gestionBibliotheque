<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php'); 
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if(isset($_POST['check_sup_conf'])){


    if($_POST['check_sup_conf'] == 'sup_confirmation'){

        $get_oeuvre = $bdd -> query("SELECT id_oeuvre FROM liste_exemplaire WHERE id = '" . $_SESSION['sup_exp_val'] . "'");
        $id_oeuvre = 0;
        while($get_val = $get_oeuvre->fetch()){
            $id_oeuvre = $get_val['id_oeuvre'];
        }
        $get_stock_oeuvre = $bdd -> query("SELECT stock_exemplaire FROM liste_oeuvre WHERE id = '" . $id_oeuvre . "' ");
        $new_val = 0;
        while($get_stock_val = $get_stock_oeuvre->fetch()){
            $new_val = $get_stock_val['stock_exemplaire'] - 1;
        } 

        $mod_stock_oeuvre = $bdd -> query("UPDATE  liste_oeuvre SET stock_exemplaire ='" .$new_val . "' WHERE id = '". $id_oeuvre . "' " );

        $sup_request = $bdd -> query("DELETE FROM liste_exemplaire WHERE id = '". $_SESSION['sup_exp_val'] . "' " );
 
        unset($_SESSION['sup_exp_val']);
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Gestion de stock de livre - Gestionnaire </title>
    <link rel="stylesheet" href="style5.css" />
    <link rel="stylesheet" href="stock_book/gestion_livre_style.css" />
    <link rel="stylesheet" href="stock_book/gestion_sup2.css" />
    <link rel="stylesheet" href="general-style-element.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>

    <div class="container">
        <header>
            <?php  include("headerAndFooter/menu.php") ?>
        </header>



        <div class="center">

            <h1>Gestion des stocks de livre</h1>

            <section id="exemplaire_liste">

                <h2>Message de confirmation de suppression</h2>

               
                <p class='p_end'> 
                    L'exemplaire a bien été suprimé.
                </p>

                <a href="affiche_doc_page_complet.php">Retour a la documentation de l'oeuvre</a>
                <a href="gestion_livre_index.php">Retour a la page principale</a>


            </section>

        </div>

        <?php include('headerAndFooter/footer.php'); ?>

    </div>
    

    <script>
        const identifation_page = 'connect-book';
        actived_link_page(identifation_page);
    </script>


</body>

</html>

<?php


    }else if($_POST['check_sup_conf'] == 'sup_annulation'){
        header('Location: affiche_doc_page_complet.php');
        exit();
    }




}
else if(isset($_GET['sup_exemplaire']) AND !empty($_GET['sup_exemplaire'])){
$_SESSION['sup_exp_val'] = (int) ($_GET['sup_exemplaire']);

?>

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Gestion de stock de livre - Gestionnaire </title>
    <link rel="stylesheet" href="style5.css" />
    <link rel="stylesheet" href="stock_book/gestion_livre_style.css" />
    <link rel="stylesheet" href="stock_book/gestion_sup2.css" />
    <link rel="stylesheet" href="general-style-element.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>

    <div class="container">
        <header>
            <?php  include("headerAndFooter/menu.php") ?>
        </header>



        <div class="center">

            <h1>Gestion des stocks de livre</h1>

            <section id="exemplaire_liste">

                <h2>Confirmation de suppresion</h2>

                <form action="sup_exemplaire.php" method="POST">
                <p>
                    Souhaitez vous supprimer vraiment supprimer cette exemplaire??<br />
                    Gardez en tete que cette action est definitive
                    <div class='radio-style'>
                        <label for="check_oeuvre">
                            <input type="radio" name="check_sup_conf" value ="sup_confirmation" id="check_oeuvre" />
                            Oui, supprimer ce exemplaire
                        </label>

                        <label for="check_exemplaire">
                            <input type="radio" name="check_sup_conf" value ="sup_annulation" id="check_exemplaire" />
                            Non, revenir a la liste des exemplaire
                        </label>
                        </div>
                    </p>
                    <input type="submit" value="confirmer" />
                </form>


            </section>

        </div>

        <?php include('headerAndFooter/footer.php'); ?>

    </div>
    

    <script>
        const identifation_page = 'connect-book';
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
    <title>Gestion de stock de livre - Gestionnaire </title>
    <link rel="stylesheet" href="style5.css" />
    <link rel="stylesheet" href="stock_book/gestion_livre_style.css" />
    <link rel="stylesheet" href="stock_book/gestion_sup2.css" />
    <link rel="stylesheet" href="general-style-element.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>

    <div class="container">
        <header>
            <?php  include("headerAndFooter/menu.php") ?>
        </header>



        <div class="center">
            <h1>Gestion des stocks de livre</h1>
            <section id="exemplaire_liste">
            <h2>Liste des exemplaires de l'oeuvre</h2>
                <table>

					<tr>
                        <th>N°</th>
						<th>Editeur</th>
                        <th>Etat</th>
                        <th>Nombre d'emprunt</th>
                        <th>Supprimer</th>
                    </tr>
<?php



$_SESSION['exemplaire'] = array();
$list_oeuvre = $bdd-> prepare("SELECT id, numero_exemplaire, id_etat, editeur FROM  liste_exemplaire WHERE id_oeuvre = :id_oeuvre ORDER BY numero_exemplaire ");
foreach($_SESSION['oeuvre'] as $key => $val) {
    $list_oeuvre -> execute(array(
        'id_oeuvre' => htmlspecialchars($key)
    ));

}

    $compteur = $list_oeuvre -> rowCount();

if ($compteur != 0){

    $current_page_search = (int) ($_GET['page'] ?? 1) ?: 1;
    if ($current_page_search < 0) {
        $current_page_search = 1;
    }


    $per_search_page = 7;
    $all_pages_search = ceil($compteur / $per_search_page);
    if ($current_page_search > $all_pages_search) {
        $current_page_search = $all_pages_search;
    }
    
    $offset = $per_search_page * ($current_page_search - 1);
    
    $list_oeuvre = $bdd->prepare("SELECT * FROM liste_exemplaire WHERE id_oeuvre = :id ORDER BY numero_exemplaire LIMIT $per_search_page OFFSET $offset ");
    foreach($_SESSION['oeuvre'] as $key => $val) {

        $list_oeuvre -> execute(array(
            'id' => htmlspecialchars($key)
        ));
    }
    if(!isset($_SESSION['increment']) OR !isset($_GET['page']) OR $_GET['page'] == 1 ){ 
        $_SESSION['increment'] = 0;                    

    }
            
            while ($exemplaire = $list_oeuvre ->fetch()){
                $_SESSION['exemplaire'][$exemplaire['id']] =  $exemplaire['numero_exemplaire'];
                $saisie_etat = $bdd -> prepare('SELECT * FROM etat_books WHERE id = :id');
                $saisie_etat ->execute(array('id'=> $exemplaire['id_etat']));
                while ($etat = $saisie_etat ->fetch()){
?>     
					<tr <?php if($_SESSION['increment'] %2 != 0){echo "class='select'";} ?> >
                        <td><?php  echo  $exemplaire['numero_exemplaire'] ;?></td>    
                        <td><?php  echo  $exemplaire['editeur'] ;?></td>
                        <td><?php  echo $etat['nom_etat'] ;?></td>
                        <td><?php  echo  $exemplaire['nombre_emprunt'] ;?></td>
						<td><a href="sup_exemplaire.php?sup_exemplaire=<?php  echo  $exemplaire['id'] ;?>">confirmer</a></td>
                    </tr>
                    
<?php 
                } $_SESSION['increment'] ++; 
            }
                ?> 
            
        </table>
        
                <div class="links_search_page">
                    <?php if ($current_page_search > 1) : ?>
                        <a href="<?php echo geturl() . '?page=' . ((int) $current_page_search - 1) ; ?>" class="previous"><i class="fa fa-angle-left"></i> Page précédente</a>
                     <?php endif; ?>
                    <p> <?php  echo $current_page_search .' / ' . $all_pages_search ; ?> <?php if ($current_page_search <=1 ){ echo 'page';}else{ echo 'pages';} ?>  </p>
                    <?php if ($current_page_search < $all_pages_search) : ?>
                        <a href="<?php  echo geturl() . '?page=' . ((int) $current_page_search + 1) ?>" class="next">Page suivante <i class="fa fa-angle-right"></i></a>
                     <?php endif; ?>
                </div>
    
                <?php        
        }else{

            ?>
    
        <p>
            Aucune Oeuvre enregister;
        </p>
    
    
    <?php
    } 
    ?>     
            <a href="affiche_doc_page_complete.php">Retour</a>

            </section>
        </div>


        <?php include('headerAndFooter/footer.php'); ?>



    </div>
    

    <script>
        const identifation_page = 'connect-book';
        actived_link_page(identifation_page);
    </script>


</body>

</html>

<?php   
}
?>
