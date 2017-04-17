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

$salle = getSalle($produit['id_salle']);
$date_actuelle = strtotime(str_replace('/', '-', date('d/m/Y')));

//$resultat = $pdo -> query("SELECT * FROM produit WHERE categorie != '$categorie' ORDER BY prix DESC LIMIT 0,5");
$resultats = $pdo -> query("SELECT * FROM produit WHERE id_produit != $id_produit");
$suggestions = $resultats -> fetchAll(PDO::FETCH_ASSOC);

require_once('inc/header.inc.php');

?>

<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
            	<?= $salle['titre']; ?>
                <small>Item Subheading</small>
            </h1>
            <a href="">Réserver</a>
        </div>
    </div>

    <div class="row">

        <div class="col-md-8">
            <img class="img-responsive" src="<?= RACINE_SITE . 'photo/' . $salle['photo'] ?>">
        </div>

        <div class="col-md-4">
            <h3>Description</h3>
            <p><?= $salle['description']; ?></p>
            <h3>Localisation</h3>
            <ul>
                <li>Lorem Ipsum</li>
                <li>Dolor Sit Amet</li>
                <li>Consectetur</li>
                <li>Adipiscing Elit</li>
            </ul>
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
	       	<?php if ($valeur['etat'] == 'libre' && strtotime(str_replace('/', '-', $valeur['date_arrivee'])) > $date_actuelle): ?>
				<div class="col-sm-3 col-xs-6">
		            <a href="fiche_produit.php?id=<?= $valeur['id_produit']; ?>">
		                <img class="img-responsive portfolio-item" src="<?= RACINE_SITE . 'photo/' . $salle['photo'] ?>" alt="">
		            </a>
		        </div>
		    <?php endif; ?>
		<?php endforeach; ?>
    </div>

    <div class="row" style="margin-bottom: 20px;">
        <div class="col-lg-12">
            <h3 class="page-header">Commentaire</h3>
        </div>
        <?php if(userConnecte()) : ?>
	       	<form action="" method="post">
	       		<input type="text" name="pseudo" value="<?= $pseudo ?>" placeholder="Pseudo" /><br/><br/>
			
				<input type="text" name="nom" value="<?= $nom ?>" placeholder="Nom" /><br/><br/>
				
				<input type="text" name="prenom" value="<?= $prenom ?>" placeholder="Prénom" /><br/><br/>
			
				<textarea name="commentaire" cols="30" rows="10"><?= $commentaire; ?></textarea><br></br>
				
				<input type="submit" value="Envoyer" class="btn btn-primary" />	
	       	</form>
	    <?php else: ?>
	    	<p>Connectez-vous pour laisser un commentaire et une note.</p>
		<?php endif; ?>
    </div>
</div>

 <?php require_once('inc/footer.inc.php'); ?>