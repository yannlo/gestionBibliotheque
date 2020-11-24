<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php'); 
include('function/connexion_bdd.php');
$list_demande = $bdd->query('SELECT * FROM demande_emprunt   ');

if(isset($_GET["affiche"])){
    foreach($_SESSION['demande'] as $key => $value){
        if($key != $_GET["affiche"]){
            unset($_SESSION['demande'][$key]);
        }
    }
    print_r($_SESSION['demande']);
    header("Location: detail_demande.php");
    exit();
}
if(isset($_POST['nombre_element_page'])){
    $_SESSION['nombre_element_page'] = $_POST['nombre_element_page'];
}
if(!isset($_SESSION['nombre_element_page'])){
    $_SESSION['nombre_element_page'] = 20;
}


$compteur = $list_demande -> rowCount();

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
    <link rel="stylesheet" href="documentation_books/information_comp.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>

    <div class="container">
        <header>
            <?php  include("headerAndFooter/menu.php") ?>
        </header>



        <div class="center">
            <h1>Listes des demandes d'emprunts</h1>
            <section id="list_element">
                <form action="client_list.php" method="POST">
                    <p>
                        <label for="nombre_element_page">Indiquer le nombre d'element a afficher sur la page:</label><br/>
                        <input type="number" name="nombre_element_page" id="nombre_element_page" min="5" max="<?php echo $compteur;?>" value ='20'  />
                        <input type="submit" valeur="valider"/>
                    </p>
                </form>
                <h2>Liste des demandes d'emprunts</h2>
                <table>

					<tr>
                        <th>N°</th>
                        <th>Nom du client</th>
						<th>Nom du livre</th>
						<th>Date de la demande</th>
                        <th>Etat de la demande</th>
                        
                    </tr>
 
                    <?php

$_SESSION['demande'] = array();
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
    
include('function/connexion_bdd.php');
    
    $list_demande = $bdd->query(" SELECT * FROM  demande_emprunt ORDER BY date_demande LIMIT $per_search_page OFFSET $offset ");
    if(!isset($_SESSION['increment']) OR !isset($_GET['page']) OR $_GET['page'] == 1 ){ 
        $_SESSION['increment'] = 0;                    

    }
            
            while ($demande = $list_demande -> fetch()){

                if($demande['confirmation'] == NULL ){

                    $_SESSION['demande'][$demande['id']] =  $demande['id_oeuvre'] ;
                    $select_oeuvre = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id = :id');
                    $select_oeuvre->execute(array(
                        'id' => $demande['id_oeuvre']
                    ));
                    $select_user = $bdd -> prepare('SELECT * FROM all_comptes WHERE id = :id');
                    $select_user->execute(array(
                        'id' => $demande['id_demandeur']
                    ));
    
                    $date1 = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$demande['date_demande']);
    
    
                    while($oeuvre_choose = $select_oeuvre ->fetch() AND $user_choose = $select_user ->fetch()){
                        
    
    ?>     
                        <tr <?php if($_SESSION['increment'] %2 != 0){echo "class='select'";} ?> >
                            <td><?php  echo  ($_SESSION['increment'] + 1) ;?></td>
                            <td><?php  echo $user_choose['first_name']. ' '. $user_choose['last_name'];?></td>
                            <td><?php  echo $oeuvre_choose['nom'] ;?></td>
                            <td><?php   echo  $date1 ;?></td>
                            <td><a href="list_all_demande.php?affiche=<?php  echo  $demande['id'] ;?>">Repondre</a></td>
                            
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
                                </tr>            
        </table>

    
    
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