<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

$recup_avis = $pdo -> query("SELECT id_avis, id_membre, id_salle, commentaire, note, DATE_FORMAT(date_enregistrement, '%d/%m/%Y %H:%m') as date_enregistrement FROM avis");

$avis = $recup_avis -> fetchAll(PDO::FETCH_ASSOC);

//$recup_salles = $pdo -> query("SELECT * FROM salle");
//$salles = $recup_salles -> fetchAll(PDO::FETCH_ASSOC);

//$recup_membres = $pdo -> query("SELECT m.id_membre, m.email, a.id_membre FROM membre m, avis a WHERE m.id_membre = a.id_membre");
//$membres = $recup_membres -> fetchAll(PDO::FETCH_ASSOC);

require_once('../inc/header.inc.php');

?>

<h1>Gestion des avis</h1>
<?php if(!empty($avis)): ?>

	<table border="1" class="table table-striped table-bordered table-hover">
		<tr>
			<?php for($i = 0; $i < $recup_avis -> columnCount(); $i++): ?>
				<?php $colonne = $recup_avis -> getColumnMeta($i); ?>
				<th><?= $colonne['name']; ?></th>
			<?php endfor; ?>
			<th colspan="3">Actions</th>
		</tr>
		
		<?php foreach ($avis as $indice => $valeur): ?>
			<tr>
				<?php foreach($valeur as $indice2 => $valeur2): ?>
					<?php if ($indice2 == 'id_membre'): ?>
						<?php $membre_val = getMembre($valeur2); ?>
						<td><?= $membre_val['id_membre']; ?> - <?= $membre_val['email']; ?></td>
					<?php elseif ($indice2 == 'id_salle'): ?>
						<?php $salle_val = getSalle($valeur2); ?>
						<td><?= $salle_val['id_salle']; ?> - <?= $salle_val['titre']; ?></td>
					<?php elseif($indice2 == 'commentaire' && strlen($valeur2) > 40 ): ?>
						<td><?= substr($valeur2, 0, 40) ?>...</td>
					<?php elseif($indice2 == 'note'): ?>
						<td>
						<?php for($i=0; $i < $valeur2; $i++):?>
							<i class="fa fa-star" aria-hidden="true">
						<?php endfor; ?>
						</td>
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
	<p>Aucun avis</p>
<?php endif; ?>

<?php require_once('../inc/footer.inc.php'); ?>