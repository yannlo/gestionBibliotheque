		<div class="center">

			<h1>Acceuil des gestionnaires</h1>

			<section id="listDemande">

				<h2>Listes des demandes recentes :</h2>
				<table>
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
    
$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque;charset=utf8','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
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
           
				</table>

			</section>

			<section id="confirmerRestitution">

				<h2>Confirmation de restitution de livre</h2>
				<form method="GET" action="search_client.php">
					<p>
						<label for="search">rechercher un emprunt en cour: </label> <br />
						<input type="search" name="search_nom_client" id="search" placeholder="Entrer nom du client" required="required" />
						<input type="hidden" name="sexe_client"  value="0" />
							
						<input type="submit" name="valider" value="valider" />
					</p>
				</form>

			</section>

			<section id="listRetardDepot">

				<h2>Listes des retard de restitution :</h2>
				<table>
					<tr>
						<th>nom du livre</th>
						<th>nom du client</th>
						<th>date de restitution presumé</th>
						<th>jour de retard</th>
					</tr>
					<tr>
						<td>les fleurs du mal</td>
						<td>kouakou frederick</td>
						<td>10/12/2020</td>
						<td>4 jours</td>
					</tr>
				</table>

			</section>

			<section id="gestionStock">

				<h2>Gestion du stock de livre</h2>
				<p>
					Souhaitez vous ajouter un nouveau livre a votre stock ou modifier le stock d'un livre deja existant?
				</p>

				<div class="bouttonBox">
					<a href="add_book.php"><button>nouveau livre</button></a>

					<a href="gestion_livre_index.php"><button>modifier Stock</button></a>
				</div>

			</section>



		</div>