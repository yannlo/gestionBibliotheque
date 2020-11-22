<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php'); 
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if(isset($_SESSION['url_valeur'])){
    unset($_SESSION['url_valeur']);
}
$_SESSION['url_valeur'] = 99;

$list_emprunt = $bdd->query('SELECT * FROM liste_emprunt ');

if(isset($_GET["affiche"])){
    foreach($_SESSION['emprunt'] as $key => $value){
        if($key != $_GET["affiche"]){
            unset($_SESSION['emprunt'][$key]);
        }
    }
    print_r($_SESSION['emprunt']);
    header("Location: detail_emprunt.php");
    exit();
}

if(isset($_POST['nombre_element_page'])){
    $_SESSION['nombre_element_page'] = $_POST['nombre_element_page'];
}
if(!isset($_SESSION['nombre_element_page'])){
    $_SESSION['nombre_element_page'] = 20;
}


$compteur = $list_emprunt -> rowCount();

?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title> Listes des emprunts - Gestionnaire </title>
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
            <h1>Listes des emprunts</h1>
            <section id="list_element">
                <form action="client_list.php" method="POST">
                    <p>
                        <label for="nombre_element_page">Indiquer le nombre d'element a afficher sur la page:</label><br/>
                        <input type="number" name="nombre_element_page" id="nombre_element_page" min="5" max="<?php echo $compteur;?>" value ='20'  />
                        <input type="submit" valeur="valider"/>
                    </p>
                </form>
                <div>
                <h2>Liste d'emprunt en cour</h2>
                <table>

					<tr>
                        <th>N°</th>
						<th>Nom du demandeur</th>
						<th>Nom du livre</th>
						<th>Date d'emprunt</th>
                        <th>etat initial</th>
                        <th>Date de retour prevue</th>
                        <th>Retard de restitustion</th>
                        <th>Detail</th>
                    </tr>
 
                    <?php

$_SESSION['emprunt'] = array();


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
    
    $list_emprunt = $bdd->query(" SELECT * FROM liste_emprunt ORDER BY date_emprunt DESC LIMIT $per_search_page OFFSET $offset ");

    if(!isset($_SESSION['increment']) OR !isset($_GET['page']) OR $_GET['page'] == 1 ){ 
        $_SESSION['increment'] = 0;                    

    }
            
            while ($emprunt = $list_emprunt -> fetch()){
                if($emprunt['date_emprunt'] != NULL){
                                

                $select_oeuvre = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id = :id');
                $select_oeuvre->execute(array(
                    'id' => $emprunt['id_oeuvre']
                ));
                $select_etat = $bdd -> prepare('SELECT * FROM etat_books WHERE id = :id');
                $select_etat->execute(array(
                 'id' => $emprunt['id_etat_initial']
                ));
                $date1 = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$emprunt['date_emprunt']);
                $date2 = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$emprunt['date_retour_supposer']);
                $date = date('Y-m-d');
                $date_val1 =  new DateTime($date);
                $date_val2 = new DateTime($emprunt['date_retour_supposer']);
                $date_diff = $date_val1 -> diff($date_val2);
                $date_actu = $date_diff->format('%a');
                while($oeuvre_choose = $select_oeuvre ->fetch()){
                    
                    $_SESSION['emprunt'][$emprunt['id']] =  $emprunt['id_exemplaire'] ;
                    $select_user = $bdd -> prepare('SELECT * FROM all_comptes WHERE id = :id');
                    $select_user->execute(array(
                        'id' => $emprunt['id_user']
                    ));

?>     
					<tr <?php if($_SESSION['increment'] %2 != 0){echo "class='select'";} ?> >
                        <td><?php  echo  ($_SESSION['increment'] + 1) ;?></td>    
                        <td><?php while($user = $select_user ->fetch()){  echo  $user['first_name'].' '. $user['last_name'] ;}?></td>
						<td><?php  echo $oeuvre_choose['nom'] ;?></td>
						<td><?php if(!empty($date1)){ echo $date1 ;} else{echo 'none';}?></td>
						<td><?php if(($compteur = $select_etat->rowCount()) != 0){while($etat = $select_etat ->fetch()){ echo $etat['nom_etat'];}}else{ echo 'none' ;} ?></td>
						<td><?php if($emprunt['date_retour_supposer'] == NULL){ echo 'none' ;}else{ echo $date2;} ?></td>
						<td><?php  if($date < $date2){echo $date_actu;}else{echo 'none';}?></td>
						<td><a href="list_emprunt.php?affiche=<?php  echo  $emprunt['id'] ;?>">affiches plus...</a></td>
                    </tr>
                    
<?php 
                    } $_SESSION['increment'] ++; 
                }
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
                                <tr class='select'>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>            
        </table>

    
    
    <?php
    } 
    ?> 
                </div>
                <div>
                <h2>Liste d'emprunt  a initialiser</h2>
                <table>

					<tr>
                        <th>N°</th>
						<th>Nom du demandeur</th>
						<th>Nom du livre</th>
						<th>Date d'emprunt</th>
                        <th>etat initial</th>
                        <th>Date de retour prevue</th>
                        <th>Retard de restitustion</th>
                        <th>Detail</th>
                    </tr>
 
                    <?php


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
    
    $list_emprunt = $bdd->query(" SELECT * FROM liste_emprunt ORDER BY date_emprunt DESC LIMIT $per_search_page OFFSET $offset ");

    if(!isset($_SESSION['increment']) OR !isset($_GET['page']) OR $_GET['page'] == 1 ){ 
        $_SESSION['increment'] = 0;                    

    }
            
            while ($emprunt1 = $list_emprunt -> fetch()){
                if($emprunt1['date_emprunt'] == NULL){
                                

                $select_oeuvre = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id = :id');
                $select_oeuvre->execute(array(
                    'id' => $emprunt1['id_oeuvre']
                ));
                $select_etat = $bdd -> prepare('SELECT * FROM etat_books WHERE id = :id');
                $select_etat->execute(array(
                 'id' => $emprunt1['id_etat_initial']
                ));
                $date1 = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$emprunt1['date_emprunt']);
                $date2 = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$emprunt1['date_retour_supposer']);
                $date = date('Y-m-d');
                $date_val1 =  new DateTime($date);
                $date_val2 = new DateTime($emprunt1['date_retour_supposer']);
                $date_diff = $date_val1 -> diff($date_val2);
                $date_actu = $date_diff->format('%a');
                while($oeuvre_choose = $select_oeuvre ->fetch()){
                    
                    $_SESSION['emprunt'][$emprunt1['id']] =  $emprunt1['id_exemplaire'] ;
                    $select_user = $bdd -> prepare('SELECT * FROM all_comptes WHERE id = :id');
                    $select_user->execute(array(
                        'id' => $emprunt1['id_user']
                    ));

?>     
					<tr <?php if($_SESSION['increment'] %2 != 0){echo "class='select'";} ?> >
                        <td><?php  echo  ($_SESSION['increment'] + 1) ;?></td>    
                        <td><?php while($user = $select_user ->fetch()){  echo  $user['first_name'].' '. $user['last_name'] ;}?></td>
						<td><?php  echo $oeuvre_choose['nom'] ;?></td>
						<td><?php if(!empty($date1)){ echo $date1 ;} else{echo 'none';}?></td>
						<td><?php if(($compteur = $select_etat->rowCount()) != 0){while($etat = $select_etat ->fetch()){ echo $etat['nom_etat'];}}else{ echo 'none' ;} ?></td>
						<td><?php if($emprunt1['date_retour_supposer'] == NULL){ echo 'none' ;}else{ echo $date2;} ?></td>
						<td><?php  if($date < $date2){echo $date_actu;}else{echo 'none';}?></td>
						<td><a href="list_emprunt.php?affiche=<?php  echo  $emprunt1['id'] ;?>">affiches plus...</a></td>
                    </tr>
                    
<?php 
                    } $_SESSION['increment'] ++; 
                }
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
                                <tr class='select'>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>            
        </table>

    
    
    <?php
    } 
    ?> 
                </div>

                        

                            
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