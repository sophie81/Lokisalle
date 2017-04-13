<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

$resultat = $pdo -> query("SELECT * FROM produit");

$produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);

//debug($produits);

require_once('../inc/header.inc.php');

?>

<h1>Gestion des produits</h1>
<?php if(!empty($produits)): ?>
	<table border="1">
		<tr>
			<?php for($i = 0; $i < $resultat -> columnCount(); $i++): ?>
				<?php $colonne = $resultat -> getColumnMeta($i); ?>
				<th><?= $colonne['name']; ?></th>
			<?php endfor; ?>
			<th colspan="2">Actions</th>
		</tr>
		
		<?php foreach ($produits as $indice => $valeur): ?>
			<tr>
				<?php foreach($valeur as $indice2 => $valeur2): ?>
					<?php if ($indice2 == 'photo'): ?>
						<td><img src="<?= RACINE_SITE . 'photo/' . $valeur2; ?>"" height="80"></td>
					<?php else: ?>
						<td><?= $valeur2 ?></td>
					<?php endif; ?>
				<?php endforeach; ?>
				<td><a href="formulaire_produit.php?id=<?= $valeur['id_produit']; ?>"><img src="<?= RACINE_SITE . 'img/edit.png' ?>"></a></td>
				<td><a href="supprimer_salle.php?id=<?= $valeur['id_produit']; ?>"><img src="<?= RACINE_SITE . 'img/delete.png' ?>"></a></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p>Aucun produit</p>
<?php endif; ?>

		<?= $msg ?>
		<form action="" method="post">
			<div class="col-md-6">
				<label>Date d'arrivée : </label><br/>
				<input type="datetime-local" name="date_arrive"/><br/><br/>

				<label>Date de départ : </label><br/>
				<input type="datetime-local" name="date_depart"/><br/><br/>
			</div>
			<div class="col-md-6">
				<label>Salle : </label><br/>
				<select name="salle">
					<option value=""></option>
					<option value=""></option>
				</select><br/><br/>
				
				<label>Tarif : </label><br/>
				<input type="text" name="prix" value="" placeholder="Prix en euros" /><br/><br/>
				
				<input type="submit" value="Enregistrer" class="btn btn-primary" />	
			</div>
		</form>


<?php require_once('../inc/footer.inc.php'); ?>