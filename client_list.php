<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php'); 
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

if(isset($_SESSION['url_ok_good'])){
    unset($_SESSION['url_ok_good']);
}
$_SESSION['url_ok_good'] = 53;

$list_user = $bdd->query('SELECT * FROM all_comptes WHERE id_type_compte = "2"');

if(isset($_GET["affiche"])){
    foreach($_SESSION['user'] as $key => $value){
        if($key != $_GET["affiche"]){
            unset($_SESSION['user'][$key]);
        }
    }
    print_r($_SESSION['user']);
    header("Location:doc_user_complet.php");
    exit();
}

if(isset($_POST['nombre_element_page'])){
    $_SESSION['nombre_element_page'] = $_POST['nombre_element_page'];
}
if(!isset($_SESSION['nombre_element_page'])){
    $_SESSION['nombre_element_page'] = 20;
}


$compteur = $list_user -> rowCount();

?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Documentation de client - Gestionnaire </title>
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="style5.css" />
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
            <h1>Documentation de client</h1>
            <section id="list_element">
                <form action="client_list.php" method="POST">
                    <p>
                        <label for="nombre_element_page">Indiquer le nombre d'element a afficher sur la page:</label><br/>
                        <input type="number" name="nombre_element_page" id="nombre_element_page" min="5" max="<?php echo $compteur;?>" value ='20'  />
                        <input type="submit" valeur="valider"/>
                    </p>
                </form>
                <h2>Liste des clients</h2>
                <table>

					<tr>
                        <th>N°</th>
						<th>Nom</th>
						<th>Prenom</th>
						<th>Email</th>
						<th>Statut</th>
                        <th>Contact 1</th>
                        <th>Detail</th>
                    </tr>
<?php

$_SESSION['user'] = array();


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
    
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque;charset=utf8','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
    $list_user = $bdd->query(" SELECT * FROM all_comptes WHERE id_type_compte = '2' ORDER BY  first_name   LIMIT $per_search_page OFFSET $offset ");

    if(!isset($_SESSION['increment']) OR !isset($_GET['page']) OR $_GET['page'] == 1 ){ 
        $_SESSION['increment'] = 0;                    

    }
            
            while ($user = $list_user ->fetch()){
                $_SESSION['user'][$user['id']] =  $user['first_name'] . ' ' . $user['last_name'];
                $select_user = $bdd -> prepare('SELECT * FROM users WHERE id = :id');
                $select_user->execute(array(
                    'id' => $user['id_other_information']
                ));

                while ($user_selected = $select_user ->fetch()){
                    
                    $saisie_type = $bdd -> prepare('SELECT * FROM type_users WHERE id = :id');
                    $saisie_type ->execute(array('id'=> $user_selected['id_type_user']));
                    while ($type = $saisie_type ->fetch()){
?>     
					<tr <?php if($_SESSION['increment'] %2 != 0){echo "class='select'";} ?> >
                        <td><?php  echo  ($_SESSION['increment'] + 1) ;?></td>    
                        <td><?php  echo  $user['first_name'] ;?></td>
						<td><?php  echo $user['last_name'] ;?></td>
						<td><?php  echo $user['email'] ;?></td>
                        <td><?php echo $type['nom'] ;?></td>
                        <td><?php  echo  $user['contact1'] ;?></td>
						<td><a href="client_list.php?affiche=<?php  echo  $user['id'] ;?>">affiches plus...</a></td>
                    </tr>
                    
<?php 
                    }
                } $_SESSION['increment'] ++; 
            }
?> 
            
        </table>
        
                <div class="links_search_page">
                    <?php if ($current_page_search > 1) :?>  
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
            Aucun client enregister.
        </p>
    
    
    <?php
    } 
    ?>     
                
    </section>
        </div>


        <?php include('headerAndFooter/footer.php'); ?>



    </div>
    
    <script>
        const identifation_page = 'client';
        actived_link_page(identifation_page);
    </script>

    <script>
        const search_book_zone = document.getElementById('search_book_zone');
        const list_oeuvre = document.getElementById('list_oeuvre');
    </script>


</body>

</html>