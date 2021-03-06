<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
if(isset($_SESSION['url_val'])){
    unset($_SESSION['url_val']);
}
$_SESSION['url_val'] = 50;
include('function/connexion_bdd.php');
if (isset($_SESSION['oeuvre'])) {
    $oeuvre_choose = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id = :id ');
    foreach($_SESSION['oeuvre'] as $key => $val) {

        $oeuvre_choose -> execute(array(
            'id' => htmlspecialchars($key)
        ));
    }
    $compteur = $oeuvre_choose -> rowCount();
    if ($compteur == 0 OR $compteur > 1 ){
        header('Location: index.php');
        exit();
    }else{
    while ($oeuvre = $oeuvre_choose ->fetch()){
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
            <link rel="stylesheet" href="style6.css"/>
            <link rel="stylesheet" href="documentation_books/style_document5.css"/>
            <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
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
    
                    <h1 class="h1_doc_liv">Documentation de livre complete</h1>
    
                    <section id="information_oeuvre">
                        <div class="parti1">
<?php                         
if( $oeuvre['nom_photo_couverture'] == NULL){
?>
<img src="imageAndLogo/uncknown_book.png" alt="icon absence de photo de converture" />           
<?Php
  }else{
   ?>
<img src="imageAndLogo/image_book/<?php echo $oeuvre['nom_photo_couverture'] ;?>" alt="couverture de l'oeuvre <?php echo $oeuvre['nom_photo_couverture'] ;?>" />
   <?php   
  }
  ?> 
                            <div class="parti2">
                                <h2><strong>Titre:</strong> <?php echo $oeuvre['nom'] ?></h2>
                                <h2><strong>Type d'oeuvre:</strong> <?php  echo $type['nom'] ;?></h2>
                                <h2><strong>Categorie d'oeuvre:</strong> <?php  echo $categorie['nom'] ;?></h2>
                                <h2><strong>Auteur:</strong> <?php echo $auteur['nom'] ;?></h2>
                            </div>
                        </div>
                        <div class="parti3">
    
                            <h2><strong>Description:</strong> </h2>
                            <p>
                                <?php echo $oeuvre['description_oeuvre']; ?>
                            </p>
    
                            <h2 class ='moyen'><strong>Nombre d'exemplaire : </strong> <?php if ($oeuvre['stock_exemplaire'] > 1){echo $oeuvre['stock_exemplaire']. ' exemplaires';}else{echo $oeuvre['stock_exemplaire']. ' exemplaire';} ?>  </h2>  
                        </div>

                        <div class="parti4">
                            <h2 style="color : #3399FF; font-size:1.8em; margin-top:40px;"><strong>Tableau des etats</strong></h2>
                            <table>
                                <tr>
                                    <th>etat</th>
                                    <th>quantité</th>
                                </tr>
                                <tr>
                                <?php 
$request_count_etat = $bdd -> prepare("SELECT etat_books.id, liste_exemplaire.id_etat FROM etat_books INNER JOIN liste_exemplaire ON liste_exemplaire.id_etat = etat_books.id WHERE etat_books.id = 1  AND liste_exemplaire.id_oeuvre = :id_oeuvre ");
foreach($_SESSION['oeuvre'] as $key => $val) {

    $request_count_etat -> execute(array(
        'id_oeuvre' => htmlspecialchars($key)
    ));
}
$count_etat = $request_count_etat -> rowCount();
?>
                                    <td>Neuf</td>
                                    <td><?php echo $count_etat;?></td>
                                </tr>
                                <tr>
                                <?php 
$request_count_etat = $bdd -> prepare("SELECT etat_books.id, liste_exemplaire.id_etat FROM etat_books INNER JOIN liste_exemplaire ON liste_exemplaire.id_etat = etat_books.id WHERE etat_books.id = 2 AND liste_exemplaire.id_oeuvre = :id_oeuvre ");
foreach($_SESSION['oeuvre'] as $key => $val) {

    $request_count_etat -> execute(array(
        'id_oeuvre' => htmlspecialchars($key)
    ));
}
$count_etat = $request_count_etat -> rowCount();
?>
                                    <td>Bon</td>
                                    <td><?php echo $count_etat;?></td>
                                </tr>
                                <tr>
                                <?php 
$request_count_etat = $bdd -> prepare("SELECT etat_books.id, liste_exemplaire.id_etat FROM etat_books INNER JOIN liste_exemplaire ON liste_exemplaire.id_etat = etat_books.id WHERE etat_books.id = 3 AND liste_exemplaire.id_oeuvre = :id_oeuvre ");
foreach($_SESSION['oeuvre'] as $key => $val) {

    $request_count_etat -> execute(array(
        'id_oeuvre' => htmlspecialchars($key)
    ));
}
$count_etat = $request_count_etat -> rowCount();
?>
                                    <td>Endommagé</td>
                                    <td><?php echo $count_etat;?></td>
                                </tr>
                                <tr>
                                <?php 
$request_count_etat = $bdd -> prepare("SELECT etat_books.id, liste_exemplaire.id_etat FROM etat_books INNER JOIN liste_exemplaire ON liste_exemplaire.id_etat = etat_books.id WHERE etat_books.id = 4 AND liste_exemplaire.id_oeuvre = :id_oeuvre ");
foreach($_SESSION['oeuvre'] as $key => $val) {

    $request_count_etat -> execute(array(
        'id_oeuvre' => htmlspecialchars($key)
    ));
}
$count_etat = $request_count_etat -> rowCount();
?>
                                    <td>Irrecuperable</td>
                                    <td><?php echo $count_etat;?></td>
                                </tr>




                            </table>
                        </div>

                        <div class="bottom_link">
<?php 

$request_count_exemplaire = $bdd -> prepare("SELECT liste_oeuvre.id, liste_exemplaire.id_oeuvre FROM liste_oeuvre INNER JOIN liste_exemplaire ON liste_exemplaire.id_oeuvre = liste_oeuvre.id WHERE  liste_oeuvre.id = :id ");

foreach($_SESSION['oeuvre'] as $key => $val) {

    $request_count_exemplaire -> execute(array(
        'id' => htmlspecialchars($key)
    ));
}
$count_exemplaire = $request_count_exemplaire -> rowCount();
if($count_exemplaire == 0) {
?>


                            <a href="page_modifie_documentation.php">Modifier oeuvre</a>
                            <a href="sup_oeuvre.php">suppression oeuvre</a>
<?php } else { ?>


                            <a href="gestion_livre_mod.php">Modifier oeuvre / exemplaire</a>
                            <a href="gestion_livre_sup.php">suppression oeuvre / exemplaire</a>
<?php } ?>
                            <a href=" <?php if($_SESSION['url_ok'] == 26 ){ echo 'general_list_stock.php';}else if($_SESSION['url_ok'] == 10 ){ echo 'general_stock_page.php';} ; ?>">Retour</a>
                             
                        </div>
                    </section>
    
    
                </div>
    
                <?php include('headerAndFooter/footer.php'); ?>
            </div>
    
                
        </body>
    </html>
    
    <?php
            }

        }
 
    }
}else{
    header('Location: index.php');
    exit();
}


?>

