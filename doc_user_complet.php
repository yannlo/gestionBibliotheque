<?php

include('function/verified_session.php');
include('function/acces_admin_verification.php');
// $_SESSION['url_ok_good'] = 10;

// echo $_SESSION['url_ok_good'];
if(isset($_SESSION['url_valeur'])){
    unset($_SESSION['url_valeur']);
}
$_SESSION['url_valeur'] = 43;


$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$list_user = $bdd->query('SELECT * FROM all_comptes WHERE id_type_compte = "2"');

if(isset($_GET["affiche"])){

    foreach($_SESSION['emprunt'] as $key => $value){
        if($key != $_GET["affiche"]){
            unset($_SESSION['emprunt'][$key]);
        }
    }

    header("Location:detail_emprunt.php");
    exit();
}
if (isset($_SESSION['user'])) {
    $user_choose = $bdd -> prepare('SELECT * FROM all_comptes WHERE id = :id ');
    foreach($_SESSION['user'] as $key => $val) {

        $user_choose -> execute(array(
            'id' => htmlspecialchars($key)
        ));
    }
    $compteur = $user_choose -> rowCount();
    if ($compteur == 0 OR $compteur > 1 ){
        header('Location: index.php');
        exit();
    }else{

    while ($user = $user_choose ->fetch()){
        $sexe_user = $bdd -> prepare('SELECT * FROM sexe_compte WHERE id = :id ');
        $sexe_user -> execute(array(
            'id'=> $user['id_sexe_compte']
        ));
        $user_information = $bdd -> prepare('SELECT * FROM users WHERE id = :id ');
        $user_information -> execute(array('id'=> $user['id_other_information']));

        while ($sexe = $sexe_user ->fetch() AND $user_inf = $user_information ->fetch()){
            $user_type = $bdd -> prepare('SELECT * FROM type_users WHERE id = :id');
            $user_type ->execute(array('id'=> $user_inf['id_type_user']));
            $date = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$user['birth_date']);
            $create_date = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$user['creation_date']);
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
            <title>Gestion de stock de livre - Gestionnaire </title>
            <link rel="stylesheet" href="documentation_books/style_document4.css"/>
            <link rel="stylesheet" href="style5.css"/>
            <link rel="stylesheet" href="gestionClient/client_style_3.css"/>
            <link rel="stylesheet" href="general-style-element.css"/>
            <link rel="stylesheet" href="stock_book/gestion_livre_style.css"/>
            <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        </head>
    
        <body>
            
            <div class="container">
    
                <header>
                    <?php  include("headerAndFooter/menu.php") ?>
                </header>
    
                <div class="center">
    
                    <h1 class="h1_doc_liv">Documentation de client </h1>
    
                    <section id="information_user">
                        <div class="parti1">
<?php                         
if( $user_inf['nom_photo_user'] == NULL){
?>
<img src="imageAndLogo/uncknown_user.png" alt="icon absence de photo de converture" />           
<?Php
  }else{
   ?>
<img src="imageAndLogo/photo_user/<?php echo $user_inf['nom_photo_user'] ;?>" alt="photo de profile" <?php echo $user_inf['nom_photo_user'] ;?>" />
   <?php   
  }
  ?> 
                            <div class="parti2">
                                <h2><strong>Matricule:</strong> <?php echo $user_inf['matricule'] ?></h2>
                                <h2><strong>Nom:</strong> <?php echo $user['first_name'] ?></h2>
                                <h2><strong>Prenom:</strong> <?php  echo $user['last_name'] ;?></h2>
                                <h2><strong>Sexe:</strong> <?php  echo $sexe['nom'] ;?></h2>
                                <h2><strong>Date de naissance:</strong> <?php echo $date ;?></h2>
                            </div>
                        </div>
                        <div class="parti3">
                            <h2><strong>Email:</strong> <?php echo $user['email'] ?></h2>
                            <h2><strong>Contact 1:</strong> <?php  echo $user['contact1'] ;?></h2>
        <?php 
            if($user['contact2'] != NULL) {

                ?>
                            <h2><strong>Contact 2:</strong> <?php  echo $user['contact2'] ;?></h2>
                            
             <?php
                        }
        ?>
                            <h2><strong>Date de creation:</strong> <?php echo $create_date ;?></h2>
 
                        </div>

                        <div class="parti4">
                            <h2 style="color : #3399FF; font-size:1.8em; margin-top:40px;"><strong>Historique des emprunts</strong></h2>



                            <table>
                                <tr>
                                    <th>nom de l'oeuvre</th>
                                    <th>date d'emprunt</th>
                                    <th>etat initial</th>
                                    <th>date de retour presumé</th>
                                    <th>date de retour effective</th>
                                    <th>etat post emprunt</th>
                                    <th>Detail</th>
                                    
                                </tr>

<?php
$list_user_emprunt = $bdd -> query("SELECT * FROM liste_emprunt WHERE id_user = '".$user['id']."' ORDER BY date_emprunt DESC ") ;

$compte = $list_user_emprunt -> rowCount();

if ($compte == 0) {


?>

                                <tr class='select'>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            
                            </table>
<?php }
else{
    $_SESSION['emprunt'] = array();

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
    $_SESSION['increment'] = 0;

    while ($emprunt = $list_user_emprunt ->fetch()){
        $select_oeuvre = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id = :id');
        $select_oeuvre->execute(array(
            'id' => $emprunt['id_oeuvre']
        ));
        $select_etat = $bdd -> prepare('SELECT * FROM etat_books WHERE id = :id');
        $select_etat->execute(array(
            'id' => $emprunt['id_oeuvre']
        ));
        $date1 = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$emprunt['date_emprunt']);
        $date = date('Y-m-d');
        $date_val1 =  new DateTime($date);
        $date_val2 = new DateTime($emprunt['date_retour_supposer']);
        $date_diff = $date_val1 -> diff($date_val2);
        $date_actu = $date_diff->format('%a');
        $date2 = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$emprunt['date_retour_supposer']);
        $select_user = $bdd -> prepare('SELECT * FROM all_comptes WHERE id = :id');
        $select_user->execute(array(
            'id' => $emprunt['id_user']
        ));
        $select_exemplaire = $bdd -> prepare('SELECT * FROM liste_exemplaire WHERE id = :id');
        $select_exemplaire->execute(array(
            'id' => $emprunt['id_exemplaire']
        ));
        while($oeuvre_choose = $select_oeuvre ->fetch() AND $etat = $select_etat ->fetch() AND $user = $select_user ->fetch() AND $exemplaire = $select_exemplaire ->fetch()){
            $_SESSION['emprunt'][$emprunt['id']] =  $emprunt['id_exemplaire'] ;
?>     
					<tr <?php if($_SESSION['increment'] %2 != 0){echo "class='select'";} ?> >
                        <td><?php  echo  $oeuvre_choose['nom'] ;?></td>    
                        <td><?php  echo  $date1 ;?></td>
						<td><?php  echo $etat['nom_etat'] ;?></td>
						<td><?php  echo $date2 ;?></td>
                        <td><?php echo 'none' ;?></td>
                        <td><?php  echo 'none' ;?></td>
						<td><a class='afficher' href="doc_user_complet.php?affiche=<?php  echo  $emprunt['id'] ;?>">affiches plus...</a></td>
                    </tr>
                    
<?php 
        }
                $_SESSION['increment'] ++;
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
                                    
                                </div>



<?php                         
}
?>
                        <div class="bottom_link">


                            <a href="supp_client.php">suppression du compte client</a>
    <?php 

    ?>
                            <a href=" <?php if($_SESSION['url_ok_good'] === 53 ){ echo 'client_list.php';}else if($_SESSION['url_ok_good'] === 10 ){ echo 'search_client.php';} ?>">Retour</a>
                            
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
 
    }

else{
    header('Location: index.php');
    exit();
}


?>
