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
$vide = "";
$where = "";

if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {
	$where .= "AND s.categorie = :cat ";
}

if (isset($_GET['ville']) && !empty($_GET['ville'])) {
	$where .= "AND s.ville = :ville ";
}

$recup_produit = $pdo -> prepare("SELECT p.id_produit, p.id_salle, DATE_FORMAT(p.date_arrivee, '%d/%m/%Y') as date_arrivee, DATE_FORMAT(p.date_depart, '%d/%m/%Y') as date_depart, p.prix, p.etat FROM produit p, salle s WHERE p.id_salle=s.id_salle AND p.etat = 'libre' AND date_arrivee > CURRENT_DATE " . $where . "LIMIT " . (($cPage-1)*$perPage) . ", $perPage");

if (isset($_GET['categorie']) && !empty($_GET['categorie'])) {
	$recup_produit -> bindParam(':cat', $_GET['categorie'], PDO::PARAM_STR );
}

if (isset($_GET['ville']) && !empty($_GET['ville'])) {
	$recup_produit -> bindParam(':ville', $_GET['ville'], PDO::PARAM_STR );
}

$recup_produit -> execute();

if ($recup_produit -> rowCount() > 0) {
	$produit = $recup_produit->fetchAll(PDO::FETCH_ASSOC);
} else {
	$vide = "Aucun produit trouvé pour cette recherche !";
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
                    <?php foreach ($categories as $indice => $valeur): ?>
	                    <a href="?<?= http_build_query(array_merge($_GET, array('categorie'=>$valeur['categorie']))) ?>" class="list-group-item"><?= $valeur['categorie']; ?></a>
	                <?php endforeach; ?>
                </div>
                 <div class="list-group">
                 	<label>Ville</label>
                 	<?php foreach ($villes as $indice => $valeur): ?>
	                    <a href="?<?= http_build_query(array_merge($_GET, array('ville'=>$valeur['ville']))) ?>" class="list-group-item"><?= $valeur['ville']; ?></a>
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
					<?php if(!empty($vide)): ?>
						<p><?= $vide ?></p>
					<?php else: ?>
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
					<?php endif; ?>
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