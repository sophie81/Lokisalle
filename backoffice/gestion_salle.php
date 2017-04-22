<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

$recup_salles = $pdo -> query("SELECT * FROM salle");

$salles = $recup_salles -> fetchAll(PDO::FETCH_ASSOC);

if($_POST) {
	//debug($_POST);
	//debug($_FILES);

	$msg = '';

	$nom_photo = 'default.jpg';

	if (isset($_POST['photo_actuelle'])) {
		$nom_photo = $_POST['photo_actuelle'];
	}

	if (!empty($_FILES['photo']['name'])) {

		$nom_photo = $_FILES['photo']['name'];

		$chemin_photo = RACINE_SERVER . RACINE_SITE . 'photo/' . $nom_photo;

		copy($_FILES['photo']['tmp_name'], $chemin_photo);
	}


	$required = array('titre', 'description', 'adresse', 'cp', 'capacite', 'categorie');
	$champ_vide = false;

	foreach ($required as $field) {
		if (empty($_POST[$field])) {
			$champ_vide = true;
		}
	}

	if ($champ_vide) {
		$msg .= '<div class="erreur">Veuillez renseigner tout les champs !</div>';
	} else {
		checkLength('titre');
		$verif_titre = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['titre']);

		if (!$verif_titre) {
			$msg .= '<div class="erreur">Titre : Caractères acceptés : de A à Z, de 0 à 9, et les "-", "_", "."</div>';
		}
		if(!is_numeric($_POST['capacite']) || strlen($_POST['capacite']) > 3) {
			$msg .= '<div class="erreur">La capacité doit être comprise entre 1 et 999</div>';
		}
		if(!is_numeric($_POST['cp']) || strlen($_POST['cp'])!== 5){
				$msg .= '<div class="erreur">Le code postal doit contenir 5 chiffres</div>';
		}
	}

	if (empty($msg)) {
		if (isset($_GET['id'])) {
			$resultat = $pdo->prepare("UPDATE salle SET titre = :titre, description = :description, photo = :photo, pays = :pays, ville = :ville, adresse = :adresse, cp = :cp, capacite = :capacite, categorie = :categorie WHERE id_salle = :id_salle");
			$resultat->bindParam(':id_salle', $_GET['id'], PDO::PARAM_INT);
		} else {
			$resultat = $pdo->prepare("INSERT INTO salle (titre, description, photo, pays, ville, adresse, cp, capacite, categorie) VALUES (:titre, :description, :photo, :pays, :ville, :adresse, :cp, :capacite, :categorie)");
		}

		//STR
		$resultat->bindParam(':titre', $_POST['titre'], PDO::PARAM_STR);
		$resultat->bindParam(':description', $_POST['description'], PDO::PARAM_STR);
		$resultat->bindParam(':photo', $nom_photo, PDO::PARAM_STR);
		$resultat->bindParam(':pays', $_POST['pays'], PDO::PARAM_STR);
		$resultat->bindParam(':ville', $_POST['ville'], PDO::PARAM_STR);
		$resultat->bindParam(':adresse', $_POST['adresse'], PDO::PARAM_STR);
		$resultat->bindParam(':cp', $_POST['cp'], PDO::PARAM_INT);
		$resultat->bindParam(':capacite', $_POST['capacite'], PDO::PARAM_INT);
		$resultat->bindParam(':categorie', $_POST['categorie'], PDO::PARAM_STR);

		if ($resultat->execute()) {
			header('location:gestion_salle.php');
		}
	}
}



if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
	$resultat = $pdo -> prepare("SELECT * FROM salle WHERE id_salle = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if($resultat -> rowCount() > 0){
		$salle_actuelle = $resultat -> fetch(PDO::FETCH_ASSOC);
	}
}

if (isset($salle_actuelle)) {
	$titre = $salle_actuelle['titre'];
	$description = $salle_actuelle['description'];
	$photo = $salle_actuelle['photo'];
	$pays = $salle_actuelle['pays'];
	$ville = $salle_actuelle['ville'];
	$adresse = $salle_actuelle['adresse'];
	$cp = $salle_actuelle['cp'];
	$capacite = $salle_actuelle['capacite'];
	$categorie = $salle_actuelle['categorie'];
} elseif (!empty($msg)) {
	$titre = $_POST['titre'];
	$description = $_POST['description'];
	$pays = $_POST['pays'];
	$ville = $_POST['ville'];
	$adresse = $_POST['adresse'];
	$cp = $_POST['cp'];
	$capacite = $_POST['capacite'];
	$categorie = $_POST['categorie'];
} else {
	$titre = '';
	$description = '';
	$photo = '';
	$pays = '';
	$ville = '';
	$adresse = '';
	$cp = '';
	$capacite = '';
	$categorie = '';
}

$action = (isset($salle_actuelle)) ?'Modifier ' : 'Ajouter ';

$disabled = '';
if (isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'details') {
	$disabled = 'disabled';
	$action = 'Détails d\'';
}

require_once('../inc/header.inc.php');

?>

<h1>Gestion des salles</h1>

<?php if(!empty($salles)): ?>

	<table class="table table-striped table-bordered table-hover">
		<tr>
			<?php for($i = 0; $i < $recup_salles -> columnCount(); $i++): ?>
				<?php $colonne = $recup_salles -> getColumnMeta($i); ?>
				<th><?= $colonne['name']; ?></th>
			<?php endfor; ?>
			<th colspan="3">Actions</th>
		</tr>

		<?php foreach ($salles as $indice => $valeur): ?>
			<tr>
				<?php foreach($valeur as $indice2 => $valeur2): ?>
					<?php if ($indice2 == 'photo'): ?>
						<td><img src="<?= RACINE_SITE . 'photo/' . $valeur2; ?>" height="80" alt="Lokisalle bureau"></td>
					<?php elseif($indice2 == 'description' && strlen($valeur2) > 40 ): ?>
						<td><?= substr($valeur2, 0, 40) ?>...</td>
					<?php else: ?>
						<td><?= $valeur2 ?></td>
					<?php endif; ?>
				<?php endforeach; ?>
				<td><a href="gestion_salle.php?id=<?= $valeur['id_salle']; ?>&action=details"><i class="fa fa-search" aria-hidden="true"></i></a></td>
				<td><a href="gestion_salle.php?id=<?= $valeur['id_salle']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
				<td><?php $commande = haveCommande($valeur['id_salle']); ?><a href="#" onClick="<?php if($commande): ?>InfoMessage('Vous ne pouvez pas supprimer cette salle car elle a été commandée !')<?php else: ?>ConfirmSupprSalle(<?= $valeur['id_salle']; ?>)<?php endif; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
			</tr>
		<?php endforeach; ?>
	</table>

<?php else: ?>
	<p>Aucune salle</p>
<?php endif; ?>

	<h1><?= $action ?> une salle</h1>
	<?= $msg; ?>
	<form action="#" method="post" enctype="multipart/form-data">
		<div class="col-lg-6">
			<label>Titre : </label><br/>
			<input type="text" name="titre" value="<?= $titre ?>"/><br/><br/>

			<label>Description : </label><br/>
			<textarea rows="5" name="description"><?= $description ?></textarea><br/><br/>

			<?php if(isset($salle_actuelle)) : ?>
				<img src="<?= RACINE_SITE . 'photo/' . $photo ?>" width="100" /><br/><br/>
				<input type="hidden"  name="photo_actuelle" value="<?= $photo ?>"/>
			<?php endif; ?>

			<label>Photo : </label><br/>
			<input type="file" name="photo" /><br/><br/>

			<label>Capacité : </label><br/>
			<input type="text" name="capacite" maxlength="3" value="<?= $capacite ?>"><br/><br/>

			<label>Catégorie : </label><br/>
			<select name="categorie">
				<option value="reunion" <?= ($categorie == 'reunion') ? 'selected' : '' ?> <?= $disabled; ?>>Réunion</option>
				<option value="bureau" <?= ($categorie == 'bureau') ? 'selected' : '' ?> <?= $disabled; ?>>Bureau</option>
				<option value="formation" <?= ($categorie == 'formation') ? 'selected' : '' ?> <?= $disabled; ?>>Formation</option>
			</select><br/><br/>
		</div>
		<div class="col-lg-6">
			<label>Pays : </label><br/>
			<select name="pays">
				<option selected value="France">France</option>
			</select><br/><br/>

			<label>Ville : </label><br/>
			<select name="ville">
				<option value="Paris" <?= ($ville == 'Paris') ? 'selected' : '' ?> <?= $disabled; ?>>Paris</option>
				<option value="Lyon" <?= ($ville == 'Lyon') ? 'selected' : '' ?> <?= $disabled; ?>>Lyon</option>
				<option value="Marseille" <?= ($ville == 'Marseille') ? 'selected' : '' ?> <?= $disabled; ?>>Marseille</option>
			</select><br/><br/>

			<label>Adresse : </label><br/>
			<textarea rows="5" name="adresse"><?= $adresse ?></textarea><br/><br/>

			<label>Code Postal : </label><br/>
			<input type="text" name="cp" value="<?= $cp ?>" maxlength="5" /><br/><br/>

			<?php if(empty($disabled)): ?>
			<input type="submit" class="btn btn-primary" value="<?= $action ?>"/>
			<?php endif; ?>

			<?php if($action == 'Modifier ' || !empty($disabled)): ?>
				<a href="<?= RACINE_SITE ?>backoffice/gestion_salle.php" class="btn btn-danger">Retour</a>
			<?php endif; ?>
		</div>
	</form>

<?php require_once('../inc/footer.inc.php'); ?>