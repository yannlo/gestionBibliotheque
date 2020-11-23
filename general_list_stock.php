<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php'); 
if(isset($_SESSION['url_ok'])){
    unset($_SESSION['url_ok']);
}
$_SESSION['url_ok'] = 26;

$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$list_oeuvre = $bdd->query('SELECT * FROM liste_oeuvre ');

if(isset($_GET["affiche"])){
    foreach($_SESSION['oeuvre'] as $key => $value){
        if($key != $_GET["affiche"]){
            unset($_SESSION['oeuvre'][$key]);
        }
    }
    print_r($_SESSION['oeuvre']);
    header("Location:affiche_doc_page_complet.php");
    exit();
}

if(isset($_POST['nombre_element_page'])){
    $_SESSION['nombre_element_page'] = $_POST['nombre_element_page'];
}
if(!isset($_SESSION['nombre_element_page'])){
    $_SESSION['nombre_element_page'] = 20;
}


$compteur = $list_oeuvre -> rowCount();

?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Gestion de stock de livre - Gestionnaire </title>
    <link rel="stylesheet" href="style6.css" />
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="stock_book/gestion_livre_style.css" />
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
            <section id="list_element">
                <form action="general_list_stock.php" method="POST">
                    <p>
                        <label for="nombre_element_page">Indiquer le nombre d'element a afficher sur la page:</label><br/>
                        <input type="number" name="nombre_element_page" id="nombre_element_page" min="5" max="<?php echo $compteur;?>" value ='20'  />
                        <input type="submit" valeur="valider"/>
                    </p>
                </form>
                <h2>Liste des oeuvres</h2>
                <table>

					<tr>
                        <th>N°</th>
						<th>Nom</th>
						<th>Type</th>
						<th>Categorie</th>
						<th>Auteur</th>
                        <th>Quantité</th>
                        <th>Detail</th>
                    </tr>
<?php

$_SESSION['oeuvre'] = array();

if ($compteur != 0){

    $current_page_search = (int) ($_GET['page'] ?? 1) ?: 1;
    if ($current_page_search < 0) {
        $current_page_search = 1;
    }


    $per_search_page = $_SESSION['nombre_element_page'];
    $all_pages_search = ceil($compteur / $per_search_page);
    if ($current_page_search > $all_pages_search) {
        $current_page_search = $all_pages_search;
    }
    
    $offset = $per_search_page * ($current_page_search - 1);
    
    $list_oeuvre = $bdd->query("SELECT * FROM liste_oeuvre ORDER BY nom LIMIT $per_search_page OFFSET $offset ");

    if(!isset($_SESSION['increment']) OR !isset($_GET['page']) OR $_GET['page'] == 1 ){ 
        $_SESSION['increment'] = 0;                    

    }
            
            while ($oeuvre = $list_oeuvre ->fetch()){
                $_SESSION['oeuvre'][$oeuvre['id']] =  $oeuvre['nom'];
                $saisie_auteur = $bdd -> prepare('SELECT * FROM autheur_livre WHERE id = :id ');
                $saisie_auteur -> execute(array(
                    'id'=>$oeuvre['id_auteur']
                ));
                $saisie_category = $bdd -> prepare('SELECT * FROM categorie_livre WHERE id = :id');
                $saisie_category ->execute(array('id'=> $oeuvre['id_categorie']));
                $saisie_type = $bdd -> prepare('SELECT * FROM type_oeuvre WHERE id = :id');
                $saisie_type ->execute(array('id'=> $oeuvre['id_type']));
                while ($auteur = $saisie_auteur ->fetch() AND $categorie = $saisie_category -> fetch() AND $type = $saisie_type -> fetch()){
?>     
					<tr <?php if($_SESSION['increment'] %2 != 0){echo "class='select'";} ?> >
                        <td><?php  echo  ($_SESSION['increment'] + 1) ;?></td>    
                        <td><?php  echo  $oeuvre['nom'] ;?></td>
						<td><?php  echo $type['nom'] ;?></td>
						<td><?php  echo $categorie['nom'] ;?></td>
                        <td><?php echo $auteur['nom'] ;?></td>
                        <td><?php  echo  $oeuvre['stock_exemplaire'] ;?></td>
						<td><a href="general_list_stock.php?affiche=<?php  echo  $oeuvre['id'] ;?>">affiches plus...</a></td>
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
        </table>
    
    
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

    <script>
        const search_book_zone = document.getElementById('search_book_zone');
        const list_oeuvre = document.getElementById('list_oeuvre');
    </script>


</body>

</html>