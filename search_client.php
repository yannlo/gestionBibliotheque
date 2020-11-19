<?php

include('function/verified_session.php');
include('function/acces_admin_verification.php');
include('function/geturl.php');


if(isset($_SESSION['url_ok_good'])){
    unset($_SESSION['url_ok_good']);
}
$_SESSION['url_ok_good'] = 10;
if(isset($_GET["affiche"])){
    foreach($_SESSION['user'] as $key => $value){
        if($key != $_GET["affiche"]){
            unset($_SESSION['user'][$key]);
        }
    }
        $_SESSION['url_ok_good'] = 10;
    print_r($_SESSION['oeuvre']);
    header("Location:doc_user_complet.php");
    exit();
}
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
if(isset($_GET["session"])){
    foreach($_SESSION['oeuvre'] as $key => $value){
        if($key != $_GET["session"]){
            unset($_SESSION['oeuvre'][$key]);
        }
    }
    print_r($_SESSION['oeuvre']);
    header("Location: affiche_doc_page_complet.php");
    exit();
}


if(isset($_POST['nombre_element_page'])){
    $_SESSION['nombre_element_page'] = $_POST['nombre_element_page'];
}

if(!isset($_SESSION['nombre_element_page'])){
    $_SESSION['nombre_element_page'] = 20;
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
		<title>documentation de client - Gestionnaire </title>
        <link rel="stylesheet" href="style5.css"/>
        <link rel="stylesheet" href="stock_book/gestion_livre_style.css" />
        <link rel="stylesheet" href="add_book/style_add_parti2.css"/>
        <link rel="stylesheet" href="general-style-element.css"/>
        <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    </head>

	<body>
		
		<div class="container">
			<header>
				<?php  include("headerAndFooter/menu.php") ?>
            </header>

            <div class="center">
                
                <h1>Gestion des stocks de livre</h1>

                <section id="search_book_zone">
                    <h2>Zone de recherche de client</h2>
                    <form method="GET" action="search_client.php#val">        
                        <p>
                            <label for="search_nom_client">Entrer le nom du client: </label> <br />
                            <input type="search" name="search_nom_client" id="search_nom_client" placeholder="nom" required="required" />
                        </p>

                        <p>
                        <label for="sexe_client">Selectionner le sexe:</label>
                        <select  name="sexe_client" id="sexe_client" required="required" >
                        <option value='0' default='default'>none</option>
                        <?php 
                        $oeuvre_list = $bdd->query('SELECT * FROM sexe_compte ORDER BY nom');
                        while($donnee = $oeuvre_list->fetch() ){
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
if(isset($_GET['search_nom_client']) AND isset($_GET['sexe_client'])){
    $result = htmlspecialchars($_GET['search_nom_client']);
    $decomp_result = explode(' ', $result);
    $sql_request = 'SELECT * FROM all_comptes WHERE id_type_compte = "2" ';
    $increment = 0;
    $_SESSION['user'] = array();
    foreach ($decomp_result as $element){
        if(strlen($element) > 0 ){
            if($increment == 0){
                $sql_request .= ' AND ';
            }
            else{
                $sql_request .= ' OR ';
            }
            $sql_request .= " first_name LIKE '%". $element . "%' OR last_name LIKE '%". $element . "%' ";
            $increment++;

        }
    }
    if($increment != 0){

        if( $_GET['sexe_client'] != 0 ){
            $sql_request .= ' AND id_sexe_compte = \''. $_GET['sexe_client'] .'\' ';
        }

        $sql_request .=' ORDER BY first_name';
        $found_search = $bdd -> query($sql_request);
        $compteur = $found_search -> rowCount();

        if ($compteur != 0){

            $current_page_search = (int) ($_GET['page'] ?? 1) ?: 1;
            if ($current_page_search < 0) {
                $current_page_search = 1;
            }
    
            $per_search_page = 5;
            $all_pages_search = ceil($compteur / $per_search_page);
            if ($current_page_search > $all_pages_search) {
                $current_page_search = $all_pages_search;
            }
            
            $offset = $per_search_page * ($current_page_search - 1);
            
    
            $sql_request .=" LIMIT $per_search_page OFFSET $offset ";
            $found_search = $bdd -> query($sql_request);
            
            
            
    
            ?>
            <section id="list_oeuvre">
                
                <h2>Resultat de la recherche: </h2>
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



    if(!isset($_SESSION['increment']) OR !isset($_GET['page']) OR $_GET['page'] == 1 ){ 
        $_SESSION['increment'] = 0;                    

    }
            
            while ($user = $found_search ->fetch()){
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
                    
<?php                  $_SESSION['increment'] ++;
                    }
                }  
            }          
                                    ?> 
                                
        </table>

    
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
		const identifation_page ='client';
           actived_link_page(identifation_page);
    </script>

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
<?php 
$_SESSION['url_ok_good'] = 10;
?>