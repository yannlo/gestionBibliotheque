<?php

include('function/verified_session.php');
include('function/acces_user_verification.php');
// $_SESSION['url_ok_good'] = 10;

// echo $_SESSION['url_ok_good'];
if(isset($_SESSION['url_valeur'])){
    unset($_SESSION['url_valeur']);
}
$_SESSION['url_valeur'] = 43;


$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque;charset=utf8','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$list_user = $bdd->query('SELECT * FROM all_comptes WHERE id_type_compte = "2"');


if (isset($_SESSION['id_user'])) {
    $user_choose = $bdd -> prepare('SELECT * FROM all_comptes WHERE id = :id ');
        $user_choose -> execute(array(
            'id' => $_SESSION['id_user']
        ));
    $compteur = $user_choose -> rowCount();
    if ($compteur == 0 OR $compteur > 1 ){
        // header('Location: index.php');
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
            <link rel="stylesheet" href="style6.css"/>
            <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
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

                            <h2><strong>Status:</strong> <?php  
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque;charset=utf8','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                            
                            while($type = $user_type -> fetch()){ echo $type['nom'] ;}?></h2>
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

                        

                        <div class="bottom_link">


                            <a href="mod_doc_user.php">Modifier mes informations</a>
    <?php 

    ?>                            
                        </div>
                    </section>
    
    
                </div>

                
    
                <?php include('headerAndFooter/footer.php'); ?>
            </div>
    
            <script>
        const identifation_page = 'information';
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
