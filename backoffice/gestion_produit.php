<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

$resultat = $pdo -> query("SELECT * FROM produit");
$produits = $resultat -> fetchAll(PDO::FETCH_ASSOC);

$resultatSalle = $pdo -> query("SELECT * FROM salle");
$salles = $resultatSalle -> fetchAll(PDO::FETCH_ASSOC);

if($_POST) {
	//debug($_POST);
	//debug($_FILES);

	$msg = '';

	$required = array('id_salle', 'date_arrivee', 'date_depart', 'prix');
	$champ_vide = false;

	foreach ($required as $field) {
		if (empty($_POST[$field])) {
			$champ_vide = true;
		}
	}

	if ($champ_vide) {
		$msg .= '<div class="erreur">Veuillez renseigner tout les champs !</div>';
	} else {
		if(!is_numeric($_POST['prix'])) {
			$msg .= '<div class="erreur">Le prix n\'est pas correct, veuillez entrer un nombre entier.</div>';
		}
		/*$format ="Y-m-d H:i";
		if(DateTime::createFromFormat($format,$_POST['date_arrivee']) === false || DateTime::createFromFormat($format,$_POST['date_depart']) === false){
			$msg .= '<div class="erreur">Le format de la date doit être jj/mm/aaaa hh:mm</div>';
		} elseif(strtotime(str_replace('/', '-', $_POST['date_arrivee'])) > strtotime(str_replace('/', '-', $_POST['date_depart']))){
			$msg .= '<div class="erreur">La date d\'arrivé doit être inférieure à la date de départ.</div>';
		}*/
	}

	if (empty($msg)) {
		if (isset($_GET['id'])) {
			$resultat = $pdo->prepare("UPDATE produit SET id_salle = :id_salle, date_arrivee = :date_arrivee, date_depart = :date_depart, prix = :prix WHERE id_produit = :id_produit");
			$resultat->bindParam(':id_produit', $_GET['id'], PDO::PARAM_INT);
		} else {
			$resultat = $pdo->prepare("INSERT INTO produit (id_salle, date_arrivee, date_depart, prix, etat) VALUES (:id_salle, :date_arrivee, :date_depart, :prix, 'libre')");
		}

		$resultat->bindParam(':id_salle', $_POST['id_salle'], PDO::PARAM_INT);
		$resultat->bindParam(':date_arrivee', $_POST['date_arrivee'], PDO::PARAM_STR);
		$resultat->bindParam(':date_depart', $_POST['date_depart'], PDO::PARAM_STR);
		$resultat->bindParam(':prix', $_POST['prix'], PDO::PARAM_INT);

		if ($resultat->execute()) {
			header('location:gestion_produit.php');
		}
	}
}



if(isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])){
	$resultat = $pdo -> prepare("SELECT * FROM produit WHERE id_produit = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if($resultat -> rowCount() > 0){
		$produit_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
	}
}

if (isset($produit_actuel)) {
	$id_salle = $produit_actuel['id_salle'];
	$date_arrivee = str_replace(' ', 'T', $produit_actuel['date_arrivee']);
	$date_depart = str_replace(' ', 'T', $produit_actuel['date_depart']);
	$prix = $produit_actuel['prix'];
} elseif (!empty($msg)) {
	$id_salle = (isset($_POST['id_salle'])) ? $_POST['id_salle'] : '';
	$date_arrivee = (isset($_POST['date_arrivee'])) ? $_POST['date_arrivee'] : '';
	$date_depart = (isset($_POST['date_depart'])) ? $_POST['date_depart'] : '';
	$prix = $_POST['prix'];
} else {
	$id_salle = '';
	$date_arrivee = '';
	$date_depart = '';
	$prix = '';
}

$action = (isset($produit_actuel)) ?'Modifier ' : 'Ajouter ';

$disabled = '';
if (isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'details') {
	$disabled = 'disabled';
	$action = 'Détails d\'';
}

require_once('../inc/header.inc.php');

?>

<h1>Gestion des produits</h1>
<?php if(!empty($produits)): ?>
	<table border="1" class="table table-striped table-bordered table-hover">
		<tr>
			<?php for($i = 0; $i < $resultat -> columnCount(); $i++): ?>
				<?php $colonne = $resultat -> getColumnMeta($i); ?>
				<th><?= $colonne['name']; ?></th>
			<?php endfor; ?>
			<th colspan="3">Actions</th>
		</tr>
		
		<?php foreach ($produits as $indice => $valeur): ?>
			<tr>
				<?php foreach($valeur as $indice2 => $valeur2): ?>
					<?php if($indice2 == 'id_salle'): ?>
						<?php $salle_val = getSalle($valeur2); ?>
						<td><?= $salle_val['id_salle']; ?> - <?= $salle_val['titre']; ?><br> <img src="<?= RACINE_SITE . 'photo/' . $salle_val['photo']; ?>"" height="80"></td>
					<?php elseif ($indice2 == 'prix'): ?>
						<td><?= $valeur2 ?> €</td>
					<?php else: ?>
						<td><?= $valeur2 ?></td>
					<?php endif; ?>
				<?php endforeach; ?>
				<td><a href="gestion_produit.php?id=<?= $valeur['id_produit']; ?>&action=details"><i class="fa fa-search" aria-hidden="true"></i></a></td>
				<td><a href="gestion_produit.php?id=<?= $valeur['id_produit']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
				<td><a href="#" onClick="<?php if($valeur['etat'] == "reservation"): ?>InfoMessage()<?php else: ?>ConfirmSuppr()<?php endif; ?>" ><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
			</tr>
			<script type="text/javascript">
				function ConfirmSuppr() {
					if (confirm("Voulez-vous supprimer ce produit ?")) { // Clic sur OK
						document.location.href="supprimer_poduit.php?id=<?= $valeur['id_produit']; ?>";
					}
				}

				function InfoMessage() {
					alert("Vous ne pouvez pas supprimer ce produit car il a déjà été commandé !");
				}
			</script>
		<?php endforeach; ?>
	</table>
<?php else: ?>
	<p>Aucun produit</p>
<?php endif; ?>
	<h2><?= $action; ?>un produit</h2>
		<?= $msg ?>
		<form action="" method="post">
			<div class="col-md-6">
				<label>Date d'arrivée : </label><br/>
				<input type="datetime-local" name="date_arrivee" value="<?= $date_arrivee ?>" <?= $disabled; ?>/><br/><br/>

				<label>Date de départ : </label><br/>
				<input type="datetime-local" name="date_depart" value="<?= $date_depart ?>" <?= $disabled; ?>/><br/><br/>
			</div>
			<div class="col-md-6">
				<label>Salle : </label><br/>
				<select name="id_salle">
					<?php foreach($salles as $valeur) : ?>
						<option <?= ($id_salle == $valeur['id_salle']) ? 'selected' : '' ?> value="<?= $valeur['id_salle'] ?>" <?= $disabled; ?>><?= $valeur['titre'] . " - " . $valeur['adresse'] . ", " . $valeur['cp'] . ", " . $valeur['ville'] . " - " . $valeur['capacite'] . "pers."?></option>
					<?php endforeach; ?>
				</select><br/><br/>
				
				<label>Prix : </label><br/>
				<input type="text" name="prix" value="<?= $prix ?>" placeholder="Prix en euros" <?= $disabled; ?>/><br/><br/>

				<?php if(empty($disabled)): ?>
					<input type="submit" class="btn btn-primary" value="<?= $action ?>"/>
				<?php endif; ?>

				<?php if($action == 'Modifier ' || !empty($disabled)): ?>
					<a href="<?= RACINE_SITE ?>backoffice/gestion_produit.php" class="btn btn-danger">Retour</a>
				<?php endif; ?>

			</div>
		</form>


<?php require_once('../inc/footer.inc.php'); ?>