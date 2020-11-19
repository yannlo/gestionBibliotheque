


<?php 

$bdd = new PDO('mysql:host=localhost;dbname=gestionbibliotheque','yannlo','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

$all_comptes = $bdd-> query("SELECT * FROM all_comptes WHERE id_type_compte = '2' ORDER BY first_name");
while ($compte = $all_comptes->fetch()){
    echo '<p>' . $compte['first_name'] . ' ' . $compte['last_name'] . '</p>';
}

?>
