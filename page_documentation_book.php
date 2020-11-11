<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php');
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if(isset($_GET["session"])){
    foreach($_SESSION['oeuvre'] as $key => $value){
        if($key != $_GET["session"]){
            unset($_SESSION['oeuvre'][$key]);
        }
    }
    print_r($_SESSION['oeuvre']);
    header("Location:affiche_doc_page.php");
    exit();
}
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
		<title>documentation de livre - Gestionnaire </title>
        <link rel="stylesheet" href="style5.css"/>
        <link rel="stylesheet" href="documentation_books/style_document4.css"/>
        <link rel="stylesheet" href="general-style-element.css"/>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>

	<body>
		
		<div class="container">
			<header>
				<?php  include("headerAndFooter/menu.php") ?>
            </header>

            <div class="center">
                <h1>Documentation de livre</h1>
                <section id="search_book_zone">
                    <h2>Zone de recherche de livre</h2>
                    <form method="GET" action="page_documentation_book.php#val">
                        <p>
                            <label for="search_nom_oeuvre">Entrer le nom du livre: </label> <br />
                            <input type="search" name="search_nom_oeuvre" id="search_nom_oeuvre" placeholder="nom" required="required" />
                        </p>

                        <p>
                        <label for="type_oeuvre">Selectionner le type de l'oeuvre:</label>
                        <select  name="type_oeuvre" id="type_oeuvre" required="required" >
                        <option value='0' default='default'>none</option>
                        <?php 
                        $oeuvre_list = $bdd->query('SELECT * FROM type_oeuvre ORDER BY nom');
                        while($donnee = $oeuvre_list->fetch() ){
                            echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';
                        }
                        ?>
                        </select>
                    </p>

                    <p>
                        <label for="categorie_oeuvre">Selectionner la categorie de l'oeuvre:</label>
                        <select  name="categorie_oeuvre" id="categorie_oeuvre" required="required">
                        <option value='0' default='default'>none</option>

                        <?php 
                        $categorie_list = $bdd->query('SELECT * FROM categorie_livre  ORDER BY nom');
                        while($donnee = $categorie_list->fetch() ){
                            echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';

                        }

                        ?>

                        </select>
                    </p>

                    <p>
                        <label for="auteur_oeuvre">Entrer le non de l'auteur de l'oeuvre:</label>
                        <select  name="auteur_oeuvre" id="auteur_oeuvre" required="required" >
                        <option value='0' default='default'>none</option>
                        <?php 
                       
                        $auteur_livre = $bdd->query('SELECT * FROM autheur_livre  ORDER BY nom');
                        while($donnee = $auteur_livre->fetch() ){
                            echo '<option  value='.$donnee['id'].'>'. $donnee['nom'].'</option>';
                        }

                        ?>

                        </select>
                    </p>

                        <input type="submit" name="valider"  value="rechercher" />
                    </form>
                    <span id="val"></span>
                </section>

<?php
if(isset($_GET['search_nom_oeuvre']) AND isset($_GET['type_oeuvre']) AND isset($_GET['auteur_oeuvre']) AND isset($_GET['type_oeuvre'])){
    $result = htmlspecialchars($_GET['search_nom_oeuvre']);
    $decomp_result = explode(' ', $result);
    $sql_request = 'SELECT * FROM liste_oeuvre ';
    $increment = 0;
    $_SESSION['oeuvre'] = array();
    foreach ($decomp_result as $element){
        if(strlen($element) > 0 ){
            if($increment == 0){
                $sql_request .= ' WHERE ';
            }
            else{
                $sql_request .= ' OR ';
            }
            $sql_request .= ' nom LIKE \'%'. $element . '%\' ';
            $increment++;

        }
    }
    if($increment != 0){

        if( $_GET['type_oeuvre'] != 0 ){
            $sql_request .= ' AND id_type = \''. $_GET['type_oeuvre'] .'\' ';
        }

        if( $_GET['categorie_oeuvre'] != 0 ){
            $sql_request .= ' AND id_categorie = \''. $_GET['categorie_oeuvre'] .'\' ';


        }

        if( $_GET['auteur_oeuvre']  != 0 ){
            $sql_request .= ' AND  id_auteur  = \''. $_GET['auteur_oeuvre'] .'\' ';

        }

        $sql_request .=' ORDER BY nom';
        $found_search = $bdd -> query($sql_request);
        $compteur = $found_search -> rowCount();

        if ($compteur != 0){

            $current_page_search = (int) ($_GET['page'] ?? 1) ?: 1;
            if ($current_page_search < 0) {
                $current_page_search = 1;
            }
    
            $per_search_page = 3;
            $all_pages_search = ceil($compteur / $per_search_page);
            if ($current_page_search > $all_pages_search) {
                $current_page_search = $all_pages_search;
            }
            
            $offset = $per_search_page * ($current_page_search - 1);
            
    
            $sql_request .=" LIMIT $per_search_page OFFSET $offset ";
            $found_search = $bdd -> query($sql_request);
            
            
            
    
            ?>
            <section id="list_oeuvre">
                
                <h2>Resultat de la recherche: (<?php if($compteur >=2){echo $compteur . ' resultats';}else{echo $compteur . ' resultat';} ?> )</h2>
        <?php 
            while ($oeuvre = $found_search ->fetch()){
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
                            <a href="page_documentation_book.php?session=<?php  echo  $oeuvre['id'] ;?>?>"> 
                            <div class="oeuvre">
                                <h3><strong>Titre:</strong> <?php  echo  $oeuvre['nom'] ;?></h3>
                                <h3><strong>Auteur:</strong> <?php echo $auteur['nom'] ;?></h3>
                                <h3><strong>Catégorie:</strong> <?php  echo $categorie['nom'] ;?></h3>
                                <h3><strong>type:</strong> <?php  echo $type['nom'] ;?></h3>
                                <p><strong>Description:</strong>
                                <?php echo $oeuvre['description_oeuvre'] ;?>
                            </p>
                        </div>
                    </a>
                    
                    <?php 
                    } 
                }
    
    ?> 
    <div class="links_search_page">
        <?php if ($current_page_search > 1) : ?>
            <a href="<?php echo geturl() . '&page=' . ((int) $current_page_search - 1) ; ?>#val" class="previous"><i class="fa fa-angle-left"></i> Page précédente</a>
         <?php endif; ?>
        <p> <?php  echo $current_page_search .' / ' . $all_pages_search ; ?> <?php if ($current_page_search <=1 ){ echo 'page';}else{ echo 'pages';} ?>  </p>
        <?php if ($current_page_search < $all_pages_search) : ?>
            <a href="<?php  echo geturl() . '&page=' . ((int) $current_page_search + 1) ?>#val" class="next">Page suivante <i class="fa fa-angle-right"></i></a>
         <?php endif; ?>
    </div>
    
    <?php        
        }else{

            ?>

<section   id="list_oeuvre">
    <h2>Resultat de la recherche: ( 0 resultat )</h2>
    
    <p>
        Cette oeuvre n'est pas enregistré;
    </p>
    
    
    <?php


}
        }else{
            ?>
            <section id="list_oeuvre">
            
            <h2>Resultat de la recherche: ( 0 resultat )</h2>

            <p>
                Enter un mot de plus de 3 caracteres
            </p>
            <?php
             } 
            
             ?>
            </section>
            
 
        </div>
<script> const search_activate = true; </script>
<?php
}
?>

<script> const search_activate = false; </script>
<?php


?>        
  
            <?php include('headerAndFooter/footer.php'); ?>
        
        </div>
            
    
    <script>
		const identifation_page ='connect-book';
           actived_link_page(identifation_page);
    </script>

    <script>
        
        const search_book_zone = document.getElementById('search_book_zone');
        const list_oeuvre = document.getElementById('list_oeuvre');

        if(search_activate == false){
            search_book_zone.style.height = '90vh';
            console.log('ok css');
        }else if(search_activate == true){
            search_book_zone.style.height = '100%';
            console.log('ok css');

        }
    </script>

</body>
</html>

