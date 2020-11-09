<?php
include('../../../function/verified_session.php');
$_SESSION['type']= 'admin';
include('../../../function/acces_admin_verification.php');
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
        <link rel="stylesheet" href="../../../style4.css"/>
        <link rel="stylesheet" href="style_document.css"/>
        <link rel="stylesheet" href="../../../general-style-element.css"/>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>

	<body>
		
		<div class="container">
			<header>
				<?php  include("../../../headerAndFooter/menu.php") ?>
            </header>

            <div class="center">
                <h1>Documentation de livre</h1>
                <section id="search_book_zone">
                    <h2>Zone de recherche de livre</h2>
                    <form method="GET" action="page_documentation_book.php">
                        <p>
                            <label for="search_nom_oeuvre">Entrer le nom du livre: </label> <br />
                            <input type="search" name="search_nom_oeuvre" id="search_nom_oeuvre" placeholder="nom" required="required" />
                            <input type="submit" name="valider" value="rechercher" />
                        </p>
                    </form>
                </section>

<?php
if(isset($_GET['search_nom_oeuvre'])){
    $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $result = htmlspecialchars($_GET['search_nom_oeuvre']);
    $decomp_result = explode(' ', $result);
    $sql_request = 'SELECT id, nom, id_categorie, id_auteur, description_oeuvre FROM liste_oeuvre ';
    $increment = 0;
    foreach ($decomp_result as $element){
        if(strlen($element) > 2){
            if($increment == 0){
                $sql_request .= ' WHERE ';
            }
            else{
                $sql_request .= ' OR ';
            }
            $sql_request .= ' nom LIKE \'%'. $element . '%\' ';
            $increment++;


    $sql_request .=' LIMIT 3';

    $found_search = $bdd -> query($sql_request);
    $compteur = $found_search -> rowCount();

?>
    <section id="list_oeuvre">
        
        <h2>Resultat de la recherche: (<?php if($compteur >=2){echo $compteur . ' resultats';}else{echo $compteur . ' resultat';} ?> )</h2>
<?php 
    while ($oeuvre = $found_search ->fetch()){
        $saisie_auteur = $bdd -> prepare('SELECT * FROM autheur_livre WHERE id = :id ');
        $saisie_auteur -> execute(array(
            'id'=>$oeuvre['id_auteur']
        ));
        $saisie_category = $bdd -> prepare('SELECT * FROM categorie_livre WHERE id = :id');
        $saisie_category ->execute(array('id'=> $oeuvre['id_categorie']));
        while ($auteur = $saisie_auteur ->fetch() AND $categorie = $saisie_category ->fetch()){
        // while (){
            //     while (){

                    ?>     
                    <a href="affiche_doc_page.php?id=<?php echo $oeuvre['id'] ;?>&nom=<?php echo $oeuvre['nom'] ;?>"> 
                    <div class="oeuvre">
                        <h3><strong>Titre:</strong> <?php  echo  $oeuvre['nom'] ;?></h3>
                        <h3><strong>Auteur:</strong> <?php echo $auteur['nom'] ;?></h3>
                        <h3><strong>Catégorie:</strong> <?php  echo $categorie['nom'] ;?></h3>
                        <p><strong>Description:</strong>
                        <?php echo $oeuvre['description_oeuvre'] ;?>
                    </p>
                </div>
            </a>
            
            <?php 
                // }
                // }
            } 
        }
    }
    ?>
            </section>
                
            </div>
<?php
        }
    }
?>        
  
            <?php include('../../../headerAndFooter/footer.php'); ?>
        
        </div>
            
    
    <script>
		const identifation_page ='connect-book';
           actived_link_page(identifation_page);
    </script>

    <script>
        const search_book_zone = document.getElementById('search_book_zone');
        const list_oeuvre = document.getElementById('list_oeuvre')
        // if(search_activate == false){
        //     list_oeuvre.style.display = 'none';
        //     search_book_zone.style.height = '90vh';
        // }else if(search_activate == true){
        //     list_oeuvre.style.display = 'block';
        //     search_book_zone.style.height = '250px';
        // }
    </script>


</body>
</html>
