<?php
require_once('inc/init.inc.php');

$categorie = $_GET["categorie"];
//var_dump($categorie);

$capacite = $_GET["capacite"];

$ville = $_GET['ville'];
//var_dump($ville);

$prix = $_GET['prix'];


/**** PAGINATION ****/
$resultat_page = $pdo -> query('SELECT COUNT(id_produit) as nbProduit FROM produit WHERE etat = "libre" AND date_arrivee > CURRENT_DATE');
$data = $resultat_page -> fetch(PDO::FETCH_ASSOC);
$nbProduit = $data['nbProduit'];
$perPage = 9;
$nbPage = ceil($nbProduit/$perPage);
if (isset($_GET['p']) && $_GET['p'] > 0 && $_GET['p'] <= $nbProduit) {
    $cPage = $_GET['p'];
} else {
    $cPage = 1;
}
/**** /PAGINATION ****/
$vide = "";
$where = "";
if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {
    $where .= "AND s.categorie = :cat ";
}

if (isset($_GET['ville']) && !empty($_GET['ville'])) {
    $where .= "AND s.ville = :ville ";
}

if (isset($_GET['capacite']) && !empty($_GET['capacite'])) {
    if($_GET['capacite'] == 1){
        $where .= "AND s.capacite <= 50 ";
    } elseif($_GET['capacite'] == 2){
        $where .= "AND s.capacite > 50 AND s.capacite <= 100 ";
    } elseif($_GET['capacite'] == 3){
        $where .= "AND s.capacite > 100 ";
    }
}

if (isset($_GET['prix']) && !empty($_GET['prix'])) {
    $where .= "AND p.prix < :prix ";
}

$recup_produit = $pdo -> prepare("SELECT p.id_produit, p.id_salle, DATE_FORMAT(p.date_arrivee, '%d/%m/%Y') as date_arrivee, DATE_FORMAT(p.date_depart, '%d/%m/%Y') as date_depart, p.prix, p.etat FROM produit p, salle s WHERE p.id_salle=s.id_salle AND p.etat = 'libre' AND date_arrivee > CURRENT_DATE " . $where . "LIMIT " . (($cPage-1)*$perPage) . ", $perPage");
if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {
    $recup_produit -> bindParam(':cat', $_GET['categorie'], PDO::PARAM_STR );
}
if (isset($_GET['ville']) && !empty($_GET['ville'])) {
    $recup_produit -> bindParam(':ville', $_GET['ville'], PDO::PARAM_STR );
}
if (isset($_GET['prix']) && !empty($_GET['prix'])) {
    $recup_produit -> bindParam(':prix', $_GET['prix'], PDO::PARAM_INT );
}
$recup_produit -> execute();

//var_dump($recup_produit);

if ($recup_produit -> rowCount() > 0) {
    $produit = $recup_produit->fetchAll(PDO::FETCH_ASSOC);
} else {
    $vide = "Aucun produit trouvé pour cette recherche !";
}

echo "<div class=\"row\">";
if(!empty($vide)){
    echo "<p>" . $vide . "</p>";
}else{
    foreach ($produit as $indice => $valeur){
        $salle = getSalle($valeur['id_salle']);
        $description = strlen($salle['description']);
        echo "<div class=\"col-sm-4 col-lg-4 col-md-4\">";
        echo "<div class=\"thumbnail\">
                <img src=\"" . RACINE_SITE . "photo/" . $salle['photo'] . "\">";
        echo "<div class=\"caption\">
                  <h4 class=\"pull-right\">" . $valeur['prix'] . "€</h4>
                  <h4><a href=\"fiche_produit.php?id=" . $valeur['id_produit'] . "\">" . $salle['titre'] . "</a>
                  </h4>
                  <p>" . substr($salle['description'], 0, 40);
        if($description > 40){
            echo "...";
        }
        echo "</p>
                  <p><i class=\"fa fa-calendar\" aria-hidden=\"true\"></i>" . $valeur['date_arrivee'] . " au " . $valeur['date_depart'] . "</p>
                </div>";
        echo "<div class=\"ratings\">
                  <p class=\"pull-right\"><a href=\"fiche_produit.php?id=" . $valeur['id_produit'] . "\">
                    <i class=\"fa fa-search\" aria-hidden=\"true\"></i> Voir</a>
                  </p>
                  <p>Note</p>
                </div>";
        echo "</div>";
        echo "</div>";
    }
}
echo "</div>";
echo "<div class=\"row\">";
if ($nbPage > 1) {
    echo "<ul class=\"pagination\">";
    for ($i=1; $i <= $nbPage; $i++) {
        echo "<li><a href=\"index.php?p=". $i . "\">" . $i . "</a></li>";
    }
    echo "</ul>";
}
echo "</div>";

