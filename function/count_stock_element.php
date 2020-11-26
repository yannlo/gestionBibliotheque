<?php
function update_stock($id_stock){

        # compte le nombre d'exemplaire d'une oeuvre precise dans le base de donnée et met la valeur
        # trouver dans le champs stock de l'oeuvre en question
        
        include('function/connexion_bdd.php');

        $compteur_ajout =  $bdd -> prepare('UPDATE  liste_oeuvre SET stock_exemplaire = :stock_exemplaire WHERE id = :id');
        $compte_element = $bdd ->prepare("SELECT liste_exemplaire.id_oeuvre, liste_oeuvre.id 
                                        FROM liste_exemplaire
                                        INNER JOIN liste_oeuvre  
                                        ON liste_oeuvre.id = liste_exemplaire.id_oeuvre 
                                        WHERE liste_exemplaire.id_oeuvre = :id ");

        $compte_element -> execute(array(
                    'id' => $id_stock
                ));
        $compteur = $compte_element -> rowCount();
        $compteur_ajout-> execute(array('stock_exemplaire' => $compteur, 'id' => $id_stock));
    }

?>