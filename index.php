<?php
require_once('inc/init.inc.php');

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

$recup_produit = $pdo -> query("SELECT id_produit, id_salle, DATE_FORMAT(date_arrivee, '%d/%m/%Y') as date_arrivee, DATE_FORMAT(date_depart, '%d/%m/%Y') as date_depart, prix, etat FROM produit WHERE etat = 'libre' AND date_arrivee > CURRENT_DATE LIMIT " . (($cPage-1)*$perPage) . ", $perPage");
$produit = $recup_produit -> fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo -> query("SELECT DISTINCT s.categorie FROM produit p, salle s WHERE p.id_salle = s.id_salle");
$categories = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$resultat = $pdo -> query("SELECT DISTINCT s.ville FROM produit p, salle s WHERE p.id_salle = s.id_salle");
$villes = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$date_actuelle = strtotime(str_replace('/', '-', date('d/m/Y')));

require_once('inc/header.inc.php');

?>
<h1 class="text-center">Accueil</h1>

<div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                	<label>Categorie</label>
                    <?php foreach ($categories as $indice => $valeur): ?>
	                    <a href="#" class="list-group-item"><?= $valeur['categorie']; ?></a>
	                <?php endforeach; ?>
                </div>
                 <div class="list-group">
                 	<label>Ville</label>
                 	<?php foreach ($villes as $indice => $valeur): ?>
	                    <a href="#" class="list-group-item"><?= $valeur['ville']; ?></a>
	                <?php endforeach; ?>
                </div>
				<div class="list-group">
					<label>Capacite</label>
					<input type="text" name="capacite">
				</div>
				<div class="list-group">
					<label>Prix</label>
					<input type="range" name="prix" id="slider" value="0" min="0" max="1000"  />
				</div>
				<div class="list-group">
					<label>Date d'arrivée : </label><br/>
					<input type="datetime-local" name="date_arrivee" value=""/><br/><br/>

					<label>Date de départ : </label><br/>
					<input type="datetime-local" name="date_depart" value=""/><br/><br/>
				</div>
            </div>

            <div class="col-md-9">
                <div class="row">
	                <?php foreach ($produit as $indice => $valeur): ?>

	                	<?php $salle = getSalle($valeur['id_salle']) ?>
	                	<?php $description = strlen($salle['description']); ?>
		                    <div class="col-sm-4 col-lg-4 col-md-4">
		                        <div class="thumbnail">
		                            <img src="<?= RACINE_SITE . 'photo/' . $salle['photo']; ?>">
		                            <div class="caption">
		                                <h4 class="pull-right"><?= $valeur['prix']; ?> €</h4>
		                                <h4><a href="fiche_produit.php?id=<?= $valeur['id_produit'];?>"><?= $salle['titre']; ?></a>
		                                </h4>
		                     
		                                <p><?= substr($salle['description'], 0, 40); ?><?php if($description > 40): ?>...<?php endif; ?></p>
		                            	
		                                <p><i class="fa fa-calendar" aria-hidden="true"></i> <?= $valeur['date_arrivee']; ?> au <?= $valeur['date_depart']; ?></p>
		                            </div>
		                            <div class="ratings">
		                                <p class="pull-right"><a href="fiche_produit.php?id=<?= $valeur['id_produit']; ?>"><i class="fa fa-search" aria-hidden="true"></i> Voir</a></p>
		                                <p >Note</p>
		                            </div>
		                        </div>
		                    </div>
	                <?php endforeach; ?>
                </div>
                <div class="row">
                	<?php 
                	if ($nbPage > 1) { ?>
	                	<ul class="pagination">
		                	<?php for ($i=1; $i <= $nbPage; $i++) { ?>
		                		<li><a href="index.php?p=<?= $i ?>"><?= $i ?></a></li>	
		                	<?php } ?>
		                </ul>
	                <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php
require_once('inc/footer.inc.php');
?>