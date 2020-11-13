<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php'); 
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if(isset($_POST['editeur_mod']) AND $_POST['etat_mod']){
    $choose_exemplaire = $bdd -> prepare('UPDATE liste_exemplaire SET  id_etat =:id_etat, editeur = :editeur    WHERE id = :id');
    $choose_exemplaire->execute(array(
        'id' => $_SESSION['id_exemplaire'],
        'id_etat' => $_POST['etat_mod'],
        'editeur' => $_POST['editeur_mod']
    ));
    header('Location: mod_exemplaire.php');
    
}



if(isset($_GET['mod_exemplaire']) AND !empty($_GET['mod_exemplaire'])){
    $_SESSION['id_exemplaire'] = (int) $_GET['mod_exemplaire'];
    $choose_exemplaire = $bdd -> prepare('SELECT id_etat, editeur FROM  liste_exemplaire WHERE id = :id');
    $choose_exemplaire->execute(array(
        'id' => $_SESSION['id_exemplaire']
    ));


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
    <link rel="stylesheet" href="general-style-element.css" />
    <link rel="stylesheet" href="documentation_books/style_document3.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>

    <div class="container">


        <header>
            <?php  include("headerAndFooter/menu.php") ?>
        </header>



        <div class="center">
            <h1>Gestion des stocks de livre</h1>

            <section id="mod_exemplaire">
            <h2>Modification des données de l'exemplaire</h2>

                <form action="mod_exemplaire.php" method="post">
<?php
while ($exemplaire = $choose_exemplaire -> fetch()){
    $saisie_etat = $bdd ->  query('SELECT * FROM etat_books  ');

    
?>               

                <p>
                    <label for="editeur_mod">Modifier le nom l'editeur:</label>
                    <input type="text" name="editeur_mod" id="editeur_mod" value="<?php echo $exemplaire['editeur']; ?>" required="required"/>
                </p>
                <p>
                    <label for="etat_mod">Modifier l'etat de l'exemplaire:</label>
                    <select type="text" name="etat_mod" id="etat_mod"  required>
<?php 

while ($etat = $saisie_etat ->fetch()){
if($exemplaire['id_etat'] == $etat['id'] ){
echo '<option value="'. $etat['id'] .'" selected ="selected" >' . $etat['nom_etat'] . '</option>';
}else{
echo '<option value="'. $etat['id'] .'" >' . $etat['nom_etat'] . '</option>';
}
}


?> 
                    </select>
                </p>

<?php
    
}

?> 

<input type="submit" name="comfirmer" id="confirmer" value="confirmer"/>

           
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
}
else{
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
                        <th>Modifier</th>
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
						<td><a href="mod_exemplaire.php?mod_exemplaire=<?php  echo  $exemplaire['id'] ;?>">confirmer</a></td>
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