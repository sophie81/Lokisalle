<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
    header("location:../connexion.php");
}

$resultat = $pdo -> query("SELECT AVG(a.note) AS moy_note, s.titre FROM avis a, salle s WHERE a.id_salle = s.id_salle GROUP BY a.id_salle ORDER BY moy_note DESC LIMIT 5");
$stat1 = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo -> query("SELECT COUNT(c.id_produit) AS nb_commande, s.titre FROM produit p, commande c, salle s WHERE p.id_produit = c.id_produit AND p.id_salle = s.id_salle GROUP BY p.id_salle ORDER BY nb_commande DESC LIMIT 5");
$stat2 = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo -> query("SELECT COUNT(c.id_membre) AS nb_commande, m.nom, m.prenom FROM membre m, commande c WHERE m.id_membre = c.id_membre GROUP BY c.id_membre ORDER BY nb_commande DESC LIMIT 5");
$stat3 = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo -> query("SELECT AVG(p.prix) AS moy_prix, m.nom, m.prenom FROM membre m, commande c, produit p WHERE m.id_membre = c.id_membre AND p.id_produit = c.id_produit GROUP BY c.id_membre ORDER BY moy_prix DESC LIMIT 5");
$stat4 = $resultat -> fetchAll(PDO::FETCH_ASSOC);


require_once('../inc/header.inc.php');

?>

<h1>Dashboard</h1>
<div class="row">
    <div class="col-md-12">
        <h2>Top 5 des salles les mieux notées</h2>
        <div class="col-md-6">
            <ul class="list-group">
                <?php foreach($stat1 as $indice => $valeur): ?>
                    <li class="list-group-item"><?= $valeur['titre']?> - <?= round($valeur['moy_note'], 2)?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="col-md-12">
        <h2>Top 5 des salles les plus commandées</h2>
        <div class="col-md-6">
            <ul class="list-group">
                <?php foreach($stat2 as $indice => $valeur): ?>
                    <li class="list-group-item"><?= $valeur['titre']?> - <?= $valeur['nb_commande']?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="col-md-12">
        <h2>Top 5 des membres qui achètent le plus</h2>
        <div class="col-md-6">
            <ul class="list-group">
                <?php foreach($stat3 as $indice => $valeur): ?>
                    <li class="list-group-item"><?= $valeur['nom']?> <?= $valeur['prenom']?> - <?= $valeur['nb_commande']?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <div class="col-md-12">
        <h2>Top 5 des membres qui achètent le plus cher</h2>
        <div class="col-md-6">
            <ul class="list-group">
                <?php foreach($stat4 as $indice => $valeur): ?>
                    <li class="list-group-item"><?= $valeur['nom']?> <?= $valeur['prenom']?> - <?= intval($valeur['moy_prix']);?> €</li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>


<?php require_once('../inc/footer.inc.php'); ?>