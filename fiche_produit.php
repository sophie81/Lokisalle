<?php 
require_once('inc/init.inc.php');

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultat = $pdo -> prepare('SELECT id_produit, id_salle, DATE_FORMAT(date_arrivee, "%d/%m/%Y") as date_arrivee, DATE_FORMAT(date_depart, "%d/%m/%Y") as date_depart, prix, etat FROM produit WHERE id_produit = :id');
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if ($resultat -> rowCount() > 0) {
		$produit = $resultat -> fetch(PDO::FETCH_ASSOC);
		extract($produit);
	} else {
		header('location:index.php');
	}

} else {
	header('location:index.php');
}
if (userConnecte()) {
	extract($_SESSION['membre']);
}

$resultatAvis = $pdo -> query("SELECT id_avis, id_membre, id_salle, commentaire, note, DATE_FORMAT(date_enregistrement, '%d/%m/%Y') as date_enregistrement FROM avis WHERE id_salle = $id_salle");
$avis = $resultatAvis -> fetchAll(PDO::FETCH_ASSOC);

$salle = getSalle($produit['id_salle']);

//$resultat = $pdo -> query("SELECT * FROM produit WHERE categorie != '$categorie' ORDER BY prix DESC LIMIT 0,5");
$resultats = $pdo -> query("SELECT p.id_produit, s.id_salle, s.photo FROM produit p, salle s WHERE s.id_salle = p.id_salle AND id_produit != $id_produit AND p.etat = 'libre' AND p.date_arrivee > CURRENT_DATE LIMIT 4");
$suggestions = $resultats -> fetchAll(PDO::FETCH_ASSOC);

$commentaire = (isset($_POST['commentaire'])) ? $_POST['commentaire'] : '';

require_once('inc/header.inc.php');

?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
            	<?= $salle['titre']; ?>
                <small>Item Subheading</small>
            </h1>
            <?php if(userConnecte()) : ?>
		       	<form action="reservation.php" method="post">
					<input type="hidden"  name="id_membre" value="<?= $id_membre ?>"/>
					
					<input type="hidden"  name="id_produit" value="<?= $id_produit ?>"/>
					
					<input type="submit" value="Réserver" class="btn btn-success" />	
		       	</form>
		    <?php else: ?>
		    	<p style="font-size: 18px;"><a href="connexion.php">Connectez-vous</a> ou <a href="inscription.php">Inscrivez-vous</a> pour réserver cette salle.</p>
			<?php endif; ?>
        </div>
    </div>

    <div class="row">

        <div class="col-md-8">
            <img class="img-responsive" src="<?= RACINE_SITE . 'photo/' . $salle['photo'] ?>" alt="Lokisalle bureau">
        </div>

        <div class="col-md-4">
            <h3>Description</h3>
            <p><?= $salle['description']; ?></p>
        </div>

    </div>
    <!-- /.row -->

    <div class="row">
    	<h3>Informations complémantaires</h3>
    	<div class="col-lg-4">
            <p>
            	<i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;
            	Arrivée&nbsp;:&nbsp;<?= $date_arrivee; ?>
            </p>
            <p>
            	<i class="fa fa-calendar" aria-hidden="true"></i>&nbsp;
            	Départ&nbsp;:&nbsp;<?= $date_depart; ?>
            </p>
    	</div>
    	<div class="col-lg-4">
    		<p>
	    		<i class="fa fa-user" aria-hidden="true"></i>&nbsp;
	    		Capacite&nbsp;:&nbsp;<?= $salle['capacite']; ?>
    		</p>
    		<p>
    			<i class="fa fa-inbox" aria-hidden="true"></i>&nbsp;
    			Catégorie&nbsp;:&nbsp;<?= $salle['categorie']; ?>		
    		</p>
    	</div>
    	<div class="col-lg-4">
    		<p>
    			<i class="fa fa-map-marker" aria-hidden="true"></i>&nbsp;
    			Adresse&nbsp;:&nbsp; <?= $salle['adresse']; ?>, <?= $salle['cp']; ?>, <?= $salle['ville']; ?>
    		</p>
    		<p>
    			<i class="fa fa-eur" aria-hidden="true"></i>&nbsp;
    			Tarif&nbsp;:&nbsp; <?= $prix; ?> €
    		</p>
    	</div>
    </div>

    <!-- Related Projects Row -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-12">
            <h3 class="page-header">Suggestion de produit</h3>
        </div>
        <?php foreach($suggestions as $valeur) : ?>
	        <?php $salle = getSalle($valeur['id_salle']); ?>
			<div class="col-sm-3 col-xs-6">
				<a href="fiche_produit.php?id=<?= $valeur['id_produit']; ?>">
					<img class="img-responsive portfolio-item" src="<?= RACINE_SITE . 'photo/' . $salle['photo'] ?>" alt="">
				</a>
			</div>
		<?php endforeach; ?>
    </div>

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-12">
            <h3 class="page-header">Commentaire</h3>
        </div>
        <div class="col-lg-12">
        	<ul class="list-group">
            <?php if($avis): ?>
        		<?php foreach($avis as $avis_val) : ?>
        			<?php $membre = getMembre($avis_val['id_membre']); ?>
			        <li class="list-group-item">
			        	<p><i class="fa fa-user fa-fw"></i><b><?= $membre['pseudo']; ?></b></p>
			        	<p style="font-size: 15px;">
							<?php for($i=0; $i < $avis_val['note']; $i++):?>
								<i class="fa fa-star" aria-hidden="true" style="color: #FFD700;"></i>
							<?php endfor; ?> |
							<?= $avis_val['commentaire']; ?>
			        	</p>
			        	<p><i><?= $avis_val['date_enregistrement']; ?></i></p>
			        </li>
				<?php endforeach; ?>
            <?php else: ?>
                <p>Aucun commentaire.</p>
            <?php endif; ?>
        	</ul>
        </div>
        <?php if(userConnecte()) : ?>
        	<?= $msg; ?>
	       	<form action="comment.php" method="post">
				<input type="hidden"  name="id_membre" value="<?= $id_membre ?>"/>
				
				<input type="hidden"  name="id_salle" value="<?= $id_salle ?>"/>

				<p>Note&nbsp;:&nbsp;</p>
				<div class="acidjs-rating-stars">
					<input type="radio" value="5" name="radio[]" id="radio-1" /><label for="radio-1"></label>
					<input type="radio" value="4" name="radio[]" id="radio-2" /><label for="radio-2"></label>
					<input type="radio" value="3" name="radio[]" id="radio-3" /><label for="radio-3"></label>
					<input type="radio" value="2" name="radio[]" id="radio-4" /><label for="radio-4"></label>
					<input type="radio" value="1" name="radio[]" id="radio-5" /><label for="radio-5"></label>
				</div>	
			
				<textarea name="commentaire" cols="30" rows="10" placeholder="Votre commentaire"><?= $commentaire; ?></textarea><br></br>
				
				<input type="submit" value="Envoyer" class="btn btn-primary" />	
	       	</form>
	    <?php else: ?>
	    	<div class="col-lg-12">
	    		<p style="font-size: 18px;"><a href="connexion.php">Connectez-vous</a> pour laisser un commentaire et une note.</p>
	    	</div>
		<?php endif; ?>
    </div>
    <div class="row">
    	<div class="col-lg-12 text-center">
    		<a href="index.php">Retour vers le catalogue.</a>
    	</div>
    </div>
</div>
 <?php require_once('inc/footer.inc.php'); ?>