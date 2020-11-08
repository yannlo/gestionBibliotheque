<?php
function update_stock($id_stock){
        $bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
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