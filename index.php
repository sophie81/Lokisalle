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

$recup_produit = $pdo -> prepare("SELECT id_produit, id_salle, DATE_FORMAT(date_arrivee, '%d/%m/%Y') as date_arrivee, DATE_FORMAT(date_depart, '%d/%m/%Y') as date_depart, prix, etat FROM produit WHERE etat = 'libre' AND date_arrivee > CURRENT_DATE LIMIT " . (($cPage-1)*$perPage) . ", $perPage");

$recup_produit -> execute();
if ($recup_produit -> rowCount() > 0) {
	$produit = $recup_produit->fetchAll(PDO::FETCH_ASSOC);
}

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
				<?php if(isset($_GET) && !empty($_GET)): ?>
					<label>Votre recherche :</label>
					<ul class="list-group">
						<?php foreach($_GET as $indice => $valeur): ?>
							<li class="list-group-item"><b><?= $indice ?></b> : <?= $valeur ?></li>
						<?php endforeach; ?>
					</ul>
					<a href="index.php" class="btn btn-primary mb-1">Reset</a><br>
				<?php endif; ?>
				<label>Categorie</label>
				<select class="form-control" name="categorie" id="categorie" onchange="showFilter()">
					<option value="">Toutes catégories</option>
				<?php foreach ($categories as $indice => $valeur): ?>
					<option value="<?= $valeur['categorie'] ?>"><?= $valeur['categorie']; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
			<div class="list-group">
				<label>Ville</label>
				<select class="form-control" name="ville" id="ville" onchange="showFilter()">
					<option value="">Toutes villes</option>
				<?php foreach ($villes as $indice => $valeur): ?>
					<option value="<?= $valeur['ville'] ?>"><?= $valeur['ville']; ?></option>
				<?php endforeach; ?>
				</select>
			</div>
			<div class="list-group">
				<label>Capacite</label>
				<select class="form-control" name="capacite" id="capacite" onchange="showFilter()">
					<option value="">Sélectionnez une capacité</option>
					<option value="1">Entre une et 50 personnes</option>
					<option value="2">Entre 50 et 100 personnes</option>
					<option value="3">Plus de 100 personnes</option>
				</select>
			</div>
			<div class="list-group">
				<label>Prix</label>
				<input class="form-control" type="range" name="prix" id="prix" value="10000" min="100" max="10000" step="50" onchange="showFilter()" />
				<p>Prix maximum : <span id="valuePrix">10000</span> €</p>
			</div>
			<!--<div class="list-group">
				<label>Date d'arrivée : </label><br/>
				<input type="datetime-local" name="date_arrivee" value=""/><br/><br/>

				<label>Date de départ : </label><br/>
				<input type="datetime-local" name="date_depart" value=""/><br/><br/>
			</div>-->
		</div>

		<div class="col-md-9" id="txtHint">
			<div class="row">
				<?php foreach ($produit as $indice => $valeur): ?>
					<?php $salle = getSalle($valeur['id_salle']) ?>
					<?php $description = strlen($salle['description']); ?>
					<div class="col-sm-4 col-lg-4 col-md-4">
						<div class="thumbnail">
							<img src="<?= RACINE_SITE . 'photo/' . $salle['photo']; ?>" alt="Lokisalle bureau">
							<div class="caption">
								<h4 class="pull-right"><?= $valeur['prix']; ?> €</h4>
								<h4><a href="fiche_produit.php?id=<?= $valeur['id_produit'];?>"><?= $salle['titre']; ?></a>
								</h4>

								<p><?= substr($salle['description'], 0, 40); ?><?php if($description > 40): ?>...<?php endif; ?></p>

								<p><i class="fa fa-calendar" aria-hidden="true"></i> <?= $valeur['date_arrivee']; ?> au <?= $valeur['date_depart']; ?></p>
							</div>
							<div class="ratings">
								<p class="pull-right"><a href="fiche_produit.php?id=<?= $valeur['id_produit']; ?>"><i class="fa fa-search" aria-hidden="true"></i> Voir</a></p>
								<p>
									<?php $note = getNoteBySalle($valeur['id_salle']); ?>
									<?php if($note): ?>
										<span>
											<?php for($i=0; $i < $note; $i++):?>
												<i class="fa fa-star" aria-hidden="true"></i>
											<?php endfor; ?>
											<?php for($i=$note; $i < 5; $i++):?>
												<i class="fa fa-star-o" aria-hidden="true"></i>
											<?php endfor; ?>
										</span>
									<?php else : ?>
										<span>Aucun avis</span>
									<?php endif; ?>
								</p>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="row text-center">
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
