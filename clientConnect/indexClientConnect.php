    <div class='center'>
    <?php
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
        <section id='list_oeuvre'>
            <h2>Nos nouveautés</h2>

            <div class='containt'>
            <?php

            include('function/connexion_bdd.php');


            $_SESSION['oeuvre'] = array();

            $found_search = $bdd -> query('SELECT * FROM liste_oeuvre ORDER BY id DESC LIMIT 3');
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
                    <a href="page_documentation_book.php?session=<?php  echo  $oeuvre['id'] ;?>"> 
                        <div class="oeuvre">
                            <div class='v1'>
                            <?php 
  if( $oeuvre['nom_photo_couverture'] == NULL){
?>
<img src="imageAndLogo/uncknown_book.png" alt="icon absence de photo de converture"   id="output"/>
           
<?Php
  }else{
   ?>
<img src="imageAndLogo/image_book/<?php echo $oeuvre['nom_photo_couverture'] ;?>" alt="couverture de l'oeuvre <?php echo $oeuvre['nom_photo_couverture'] ;?>" id="output" />
   <?php   
  }
  ?>                             </div>
                            <div class='v2'>
                                <h3><strong>Titre:</strong> <?php  echo  $oeuvre['nom'] ;?></h3>
                                    <h3><strong>Auteur:</strong> <?php echo $auteur['nom'] ;?></h3>
                                    <h3><strong>Catégorie:</strong> <?php  echo $categorie['nom'] ;?></h3>
                                    <h3><strong>type:</strong> <?php  echo $type['nom'] ;?></h3>
                            </div>
                        </div>
                    </a>
                    
                    <?php 
                    } 
                }
                ?>

            </div>

        </section>
    </div>


    <script>
        function on_next(){
           increment =0;
           class_element = document.getElementsByClassName(element_car)[increment]
        }
     </script>
