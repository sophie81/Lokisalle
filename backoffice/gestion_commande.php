<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

$recup_commande = $pdo -> query('SELECT c.id_commande, c.id_membre, c.id_produit, p.prix, DATE_FORMAT(c.date_enregistrement, "%d/%m/%Y %H:%m") as date_enregistrement  FROM produit p, commande c WHERE c.id_produit = p.id_produit');

$commande = $recup_commande -> fetchAll(PDO::FETCH_ASSOC);

require_once('../inc/header.inc.php');

?>

<h1>Gestion des commandes</h1>
<?php if(!empty($commande)): ?>
	<table border="1" class="table table-striped table-bordered table-hover">
		<tr>
			<?php for($i = 0; $i < $recup_commande -> columnCount(); $i++): ?>
				<?php $colonne = $recup_commande -> getColumnMeta($i); ?>
				<th><?= $colonne['name']; ?></th>
			<?php endfor; ?>
			<th colspan="2">Actions</th>
		</tr>
		
		
		<?php foreach ($commande as $indice => $valeur): ?>
			<tr>
				<?php foreach($valeur as $indice2 => $valeur2): ?>
					<?php if ($indice2 == 'statut'): ?>
						<?php if($valeur2 == 0): ?>
							<td>Membre</td>
						<?php else: ?>
							<td>Admin</td>
						<?php endif; ?>
					<?php else: ?>
						<td><?= $valeur2 ?></td>
					<?php endif; ?>
				<?php endforeach; ?>
				<td><a href="gestion_avis.php?id=<?= $valeur['id_membre']; ?>"><i class="fa fa-search" aria-hidden="true"></i></a></td>
				<td><a href="gestion_avis.php?id=<?= $valeur['id_membre']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
				<td><a href="supprimer_avis.php?id=<?= $valeur['id_membre']; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
			</tr>
		<?php endforeach; ?>
		
	</table>
<?php else: ?>
	<p>Aucune commande</p>
<?php endif; ?>

<?php require_once('../inc/footer.inc.php'); ?>