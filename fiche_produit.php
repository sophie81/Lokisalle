<?php 
require_once('inc/init.inc.php');

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if ($resultat -> rowCount() > 0) {
		$produit = $resultat -> fetch(PDO::FETCH_ASSOC);
		extract($produit);
	} else {
		header('location:boutique.php');
	}

} else {
	header('location:boutique.php');
}

require_once('inc/header.inc.php');

?>
<h1>Produit</h1>
<img src="<?= RACINE_SITE . 'photo/' . $photo ?>" alt="">
<p>Détails du produit :</p>
<ul>
	<li>Réf : <?= $reference; ?></li>
	<li>Catégorie : <?= $categorie; ?></li>
	<li>Taille : <?= $taille; ?></li>
	<li>Public : <?php if($public == 'm') { echo "Homme"; } elseif($public == "m") { echo "Femme"; } else { echo "Mixte"; } ; ?></li>
	<li>Prix : <?= $prix; ?>€</li>
</ul>
<br>
<p>Description du produit :</p>
<em><?= $description; ?></em>
<br>
<br>
<fieldset>
	<legend>Acheter ce produit</legend>
	<form action="" method="post">
		<select name="quantite" style="max-width: 100px; display: inline-block;">
			<option value="">Quantité</option>
			<option value="">1</option>
			<option value="">2</option>
			<option value="">3</option>
			<option value="">4</option>
			<option value="">5</option>
		</select>
		<input type="submit" value="Ajouter au panier" style="display: inline-block;">
	</form>
</fieldset>
<br>
<br>
<fieldset>
	<legend>Suggestion de produit</legend>
	
</fieldset>

 <?php require_once('inc/footer.inc.php'); ?>