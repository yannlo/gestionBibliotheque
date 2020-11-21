<?php
include('function/verified_session.php');
include('function/mixed_acces_verification.php');
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if(isset($_SESSION['url_val'])){
    unset($_SESSION['url_val']);
}
$_SESSION['url_val'] = 20 ;
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
            <title>documentation de livre - client </title>
            <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
            <link rel="stylesheet" href="style6.css"/>
            <link rel="stylesheet" href="documentation_books/style_document4.css"/>
            <link rel="stylesheet" href="documentation_books/information_comp.css"/>
            <link rel="stylesheet" href="general-style-element.css"/>
            <script src="https://kit.fontawesome.com/a076d05399.js"></script>
        </head>
    
        <body>
            
            <div class="container">
    
                <header>
                    <?php  include("headerAndFooter/menu.php") ?>
                </header>
    
                <div class="center">
    
                    <h1 class="h1_doc_liv">Documentation de livre</h1>
    
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
                        <div class="bottom_link">
                            <?php if(isset($_SESSION['type']) AND $_SESSION['type'] == 'user' AND $oeuvre['stock_exemplaire'] != 0){
                            ?>
                            <a href="demande_emprunt.php">Faire une demande d'emprunt</a>
                            <?php
                        }else if($oeuvre['stock_exemplaire'] ==  0){
                            ?> 
                            
                            <p class="information_comp"><span>Alert:</span> <br/>
                            Cette oeuvre n'est pas disponible a l'emprunt.
                            </p>
    
                            <?php
                        }else{
                        ?> 
                        
                        <p class="information_comp"><span>NB:</span> <br/>
                          connectez-vous pour avoir la possibilité de
                        deposer une demande d'emprunt pour ce livre. 
                        </p>

                        <?php
                        }
                        ?>
                            <a href="page_documentation_book.php">Retour</a>
                        </div>
                    </section>
    
    
                </div>
    
                <?php include('headerAndFooter/footer.php'); ?>
            </div>
    
            <script>
		const identifation_page ='connect-book';
       	actived_link_page(identifation_page);
	</script>      
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

