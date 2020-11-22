<?php
include('function/verified_session.php');
include('function/acces_admin_verification.php');

$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$list_user = $bdd->query('SELECT * FROM all_comptes WHERE id_type_compte = "2"');

if(isset($_POST['check_formulaire'])){
    if($_POST['check_formulaire'] == 0){
        $demande_choose = $bdd -> prepare('UPDATE demande_emprunt SET confirmation = :confirmation WHERE id = :id ');
        $id_demande = 0;
        foreach($_SESSION['demande'] as $key => $val) {
            $id_demande =$key;
        }    
        $demande_choose -> execute(array(
            'confirmation' =>  $_POST['check_formulaire'],
            'id' => htmlspecialchars($id_demande)
        ));


?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Detail de demande - Gestionnaire </title>
    <link rel="stylesheet" href="style6.css" />
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="stock_book/gestion_livre_style.css" />
    <link rel="stylesheet" href="stock_book/gestion_sup2.css" />
    <link rel="stylesheet" href="general-style-element.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>

    <div class="container">
        <header>
            <?php  include("headerAndFooter/menu.php") ?>
        </header>
        
            <section id="exemplaire_liste">
                

                <h2>Message de confirmation de demande</h2>

                <p class='p_end'>
                    La demande a bien été refuser.
                </p>

                <a href="list_all_demande.php">Retour a la page principale</a>


            </section>


        </div>

        <?php include('headerAndFooter/footer.php'); ?>

    </div>
    

    <script>
        const identifation_page = 'connect-book';
        actived_link_page(identifation_page);
    </script>


</body>

</html>

<?php    

    

        
    }else if($_POST['check_formulaire'] == 1){
        $demande_choose = $bdd -> prepare('UPDATE demande_emprunt SET confirmation = :confirmation WHERE id = :id ');
        $id_demande = 0;
        foreach($_SESSION['demande'] as $key => $val) {
            $id_demande =$key;
        }    
        $demande_choose -> execute(array(
            'confirmation' =>  $_POST['check_formulaire'],
            'id' => htmlspecialchars($id_demande)
        ));
        $demande_choose = $bdd -> prepare('SELECT * FROM demande_emprunt WHERE id = :id ');
        $demande_choose -> execute(array(
            'id' => htmlspecialchars($id_demande)
        ));
        
        while ($info_demande = $demande_choose->fetch()){

            $create_emprunt = $bdd -> prepare('INSERT INTO liste_emprunt (id_user,id_oeuvre) VALUE (:id_user, :id_oeuvre) ');
            $create_emprunt -> execute(array(
                'id_user' => $info_demande['id_demandeur'],
                'id_oeuvre' =>$info_demande['id_oeuvre']
            ));
        }
        ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd" xml:lang="fr">

<head>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <title>Detail de demande - Gestionnaire </title>
    <link rel="stylesheet" href="style6.css" />
    <link rel="shortcut icon" href="imageAndLogo/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="stock_book/gestion_livre_style.css" />
    <link rel="stylesheet" href="stock_book/gestion_sup2.css" />
    <link rel="stylesheet" href="general-style-element.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>

<body>

    <div class="container">
        <header>
            <?php  include("headerAndFooter/menu.php") ?>
        </header>



        <div class="center">

            <h1>Detail de demande de client</h1>

        <section id="exemplaire_liste">

                <h2>Message de confirmation de demande</h2>

                <p class='p_end'>
                    La demande a bien été accepté.<br/>
                    veuillez vous rendre sur la page d'emprunt pour completer les informations de cet emprunt
                </p>

                <a href="list_all_demande.php">Retour a la page principale</a>
                <a href="list_emprunt.php">liste des emprunts</a>

        </div>

        <?php include('headerAndFooter/footer.php'); ?>

    </div>
    

    <script>
        const identifation_page = 'connect-book';
        actived_link_page(identifation_page);
    </script>


</body>

</html>

<?php    


    }

}else if (isset($_SESSION['demande'])) {
    $demande_choose = $bdd -> prepare('SELECT * FROM demande_emprunt WHERE id = :id ');
    foreach($_SESSION['demande'] as $key => $val) {

        $demande_choose -> execute(array(
            'id' => htmlspecialchars($key)
        ));
    }
    $compteur = $demande_choose -> rowCount();
    if ($compteur == 0 OR $compteur > 1 ){
        echo $compteur;
        // header('Location: index.php');
        exit();
    }else{

    while ($demande = $demande_choose ->fetch()){

        $select_oeuvre = $bdd -> prepare('SELECT * FROM liste_oeuvre WHERE id = :id');
        $select_oeuvre->execute(array(
            'id' => $demande['id_oeuvre']
        ));
        $select_user = $bdd -> prepare('SELECT * FROM all_comptes WHERE id = :id');
        $select_user->execute(array(
            'id' => $demande['id_demandeur']
        ));

        $date1 = preg_replace('#([0-9]{4})-([0-9]{2})-([0-9]{2})#',"$3/$2/$1",$demande['date_demande']);

        while($oeuvre_choose = $select_oeuvre ->fetch() AND $user = $select_user ->fetch()){

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
            <title>Detail de demande - Gestionnaire </title>
            <link rel="stylesheet" href="style6.css"/>
            <link rel="stylesheet" href="documentation_books/style_document4.css"/>
            <link rel="stylesheet" href="documentation_books/information_comp.css"/>
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
    
                    <h1 class="h1_doc_liv">Detail de la demande d'emprunt </h1>
    
                    <section id="information_oeuvre">

                        <div class="parti2">
                            <h2><strong>Nom du demandeur:</strong> <?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h2>
                            <h2><strong>Nom de l'oeuvre:</strong> <?php echo $oeuvre_choose['nom']; ?></h2>
                            <h2><strong>Date de l'emprunt:</strong> <?php  echo $date1 ;?></h2>
                        </div>
                        <div class="parti2">
                        <h2><strong>Etat de la demande:</strong></h2>
                            <form Method="POST" action="detail_demande.php">
                                <p>
                                    Souhaitez vous autoriser ou l'emprunt la demande d'emprunt: <br />
                                    <div class='radio-style'>
                                        
                                        <?php 
                                                $liste_exemplaire = $bdd -> prepare('SELECT * FROM liste_exemplaire WHERE id_oeuvre = :id_oeuvre AND etat_emprunt = "0"');
                                                $liste_exemplaire ->execute(array(
                                                    'id_oeuvre' => $oeuvre_choose['id']
                                                ));
                                                $compte_exemplaire =$liste_exemplaire -> rowCount();
                                                if($compte_exemplaire >= 1){
?>
                                        <label for="check_accepte">
                                            <input type="radio" name="check_formulaire" value ="1" id="check_accepte" />
                                            Autoriser
                                        </label>
                                        
                                        
                                        <label for="check_refus">
                                            <input type="radio" name="check_formulaire" value ="0" id="check_refus" />
                                            Refuser
                                        </label>
                                    </div>
                                        <p class="information_comp"><span>NB:</span> <br/>
                                    Une fois la demande accepté ou refusé le prossessus est irreversible.
                                </p> 

<?php
                                                }else{
                                                    ?>
                                                    
                                                    
                                                    <label for="check_refus">
                                                        <input type="radio" name="check_formulaire" value ="0" id="check_refus" />
                                                        Refuser
                                                    </label>
                                </div>
                                                    <p class="information_comp"><span>NB:</span> <br/>
                                    Plus aucun exemplaire de ce livvre est disponible a l'emprunt.
                                </p> 
            
            <?php   
                                                }
                                        ?>

 
  
                                <input type="submit" value="Confirmer" />

                            </form>
                        </div>

                    
                        <a href="list_all_demande.php">Retour</a>


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
