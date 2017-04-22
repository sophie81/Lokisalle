<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

$recup_avis = $pdo -> query("SELECT id_avis, id_membre, id_salle, commentaire, note, DATE_FORMAT(date_enregistrement, '%d/%m/%Y %H:%m') as date_enregistrement FROM avis");
$avis = $recup_avis -> fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
	if (!empty($_POST['commentaire']) && strlen(trim($_POST['commentaire'])) > 0) {
		if (isset($_GET['id'])) {
			$modif = $pdo->prepare("UPDATE avis SET commentaire = :commentaire WHERE id_avis = :id");
			$modif->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
		}

		$modif->bindParam(':commentaire', $_POST['commentaire'], PDO::PARAM_STR);

		if ($modif->execute()) {
			header('location:gestion_avis.php');
		}
	} else {
		$msg .= '<div class="erreur">Veuillez remplir le champs commentaire !</div>';
	}
	
	
}

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultat = $pdo -> prepare("SELECT id_avis, id_membre, id_salle, commentaire, note, DATE_FORMAT(date_enregistrement, '%d/%m/%Y %H:%m') as date_enregistrement FROM avis WHERE id_avis = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if ($resultat -> rowCount() > 0) {
		$avis_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
	} 
}

$id_avis = (isset($avis_actuel)) ? $avis_actuel['id_avis'] : '';
$id_membre = (isset($avis_actuel)) ? $avis_actuel['id_membre'] : '';
$id_salle = (isset($avis_actuel)) ? $avis_actuel['id_salle'] : '';
$commentaire = (isset($avis_actuel)) ? $avis_actuel['commentaire'] : '';
$note = (isset($avis_actuel)) ? $avis_actuel['note'] : '';
$date_enregistrement = (isset($avis_actuel)) ? $avis_actuel['date_enregistrement'] : '';

require_once('../inc/header.inc.php');

?>

<h1>Gestion des avis</h1>
<?php if(!empty($avis)): ?>

	<table class="table table-striped table-bordered table-hover">
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
							<i class="fa fa-star" aria-hidden="true"></i>
						<?php endfor; ?>
						</td>
					<?php else: ?>
						<td><?= $valeur2 ?></td>
					<?php endif; ?>
				<?php endforeach; ?>
				<td><a href="gestion_avis.php?id=<?= $valeur['id_avis']; ?>&action=details"><i class="fa fa-search" aria-hidden="true"></i></a></td>
				<td><a href="gestion_avis.php?id=<?= $valeur['id_avis']; ?>&action=modif"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
				<td><a href="#" onClick="ConfirmSupprAvis(<?= $valeur['id_avis']; ?>)"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
			</tr>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p>Aucun avis</p>
<?php endif; ?>

<?php if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && $_GET['action'] == 'details'): ?>
	<h1>Détails avis</h1>
	<div class="row">
		<div class="col-md-12">
			<ul class="list-group">
				<li class="list-group-item"><b>ID avis :</b> <span><?= $id_avis; ?></span></li>
				<li class="list-group-item"> 
					<?php foreach($avis_actuel as $indice_avis => $valeur_avis): ?>
						<?php if($indice_avis == 'id_membre'): ?>
							<?php $avis_salle = getMembre($id_membre); ?>
							<span><b>ID membre&nbsp;:&nbsp;</b> <?= $membre_val['id_membre']; ?></span> <br>
							<span><b>Email membre&nbsp;:&nbsp;</b> <?= $membre_val['email']; ?></span>
						<?php endif; ?>
					<?php endforeach; ?>
				</li>
				<li class="list-group-item">
					<?php foreach($avis_actuel as $indice_avis => $valeur_avis): ?>
						<?php if($indice_avis == 'id_salle'): ?>
							<?php $avis_salle = getSalle($id_salle); ?>
							<span><b>ID salle&nbsp;:&nbsp;</b> <?= $avis_salle['id_salle']; ?> <br>
							<b>Titre de la salle&nbsp;:&nbsp;</b> <?= $avis_salle['titre']; ?></span> <br>
						<?php endif; ?>
					<?php endforeach; ?>
				</li>
				<li class="list-group-item"><b>Commentaire&nbsp;:</b> <span><?= $commentaire; ?></span></li>
				<li class="list-group-item"><b>Note&nbsp;: </b>
					<span><?= $note; ?></span>
				</li>
				<li class="list-group-item">
					<b>Date enregistrement&nbsp;:</b>
					<span><?= $date_enregistrement; ?></span>
				</li>
			</ul>
		</div>
	</div>
<?php endif; ?>

<?php if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id']) && $_GET['action'] == 'modif'): ?>
	<h1>Modification avis <?= $_GET['id'] ?></h1>
	<?= $msg; ?>
	<form action="" method="post" enctype="multipart/form-data">
		<textarea name="commentaire" cols="30" rows="10"><?= $commentaire; ?></textarea><br></br>
		<input type="submit" class="btn btn-primary" value="Enregistrer">
	</form>
	<div class="col-xs-12">
		<a href="<?= RACINE_SITE ?>backoffice/gestion_avis.php" class="btn btn-danger">Retour</a>
	</div>
	
<?php endif; ?>

<?php require_once('../inc/footer.inc.php'); ?>