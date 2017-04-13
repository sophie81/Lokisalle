<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

$recup_membre = $pdo -> query("SELECT id_membre, pseudo, nom, prenom, email, civilite, statut, DATE_FORMAT(date_enregistrement, '%d/%m/%Y %H:%m') as date_enregistrement FROM membre");

$membre = $recup_membre -> fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
	//debug($_POST);

	$msg = '';

	$required = array('pseudo', 'nom', 'prenom', 'email', 'civilite');
	$champ_vide = false;

	foreach ($required as $field) {
		if (empty($_POST[$field])) {
			$champ_vide = true;
			var_dump($field);
		}
	}

	if (!isset($_GET['id'])) {
		if (empty($_POST['mdp'])) {
			$champ_vide = true;
		} else {
			checkLength('mdp');
			$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['mdp']); 

			if(!$verif_caractere){ // $verif_caractere == TRUE
				$msg .= '<div class="erreur">Mot de passe : Caractères acceptés : de A à Z, de 0 à 9, et les "-", "_", "."</div>';
			}
		}
	}
	
	if ($_POST['statut'] == null) {
		$champ_vide = true;
	}

	if ($champ_vide) { 
		$msg .= '<div class="erreur">Veuillez renseigner tout les champs !</div>';
	} else {
		checkLength('pseudo');
		checkLength('nom');
		checkLength('prenom');

		$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']); 

		if(!$verif_caractere){ // $verif_caractere == TRUE
			$msg .= '<div class="erreur">Pseudo : Caractères acceptés : de A à Z, de 0 à 9, et les "-", "_", "."</div>';
		}

		$verif_nom = preg_match('#^[a-zA-Z-]+$#', $_POST['nom']); 
		$verif_prenom = preg_match('#^[a-zA-Z-]+$#', $_POST['prenom']); 

		if(!$verif_nom || !$verif_prenom){ // $verif_caractere == TRUE
			$msg .= '<div class="erreur">Champs nom et prénom : Caractères acceptés : de A à Z, "-"</div>';
		}

		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
		    $msg .= '<div class="erreur">Email non valide</div>';
		}
	}

	if(empty($msg)){
		if (isset($_GET['id'])) {
			$resultat = $pdo -> prepare("UPDATE membre SET pseudo = :pseudo, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, statut = :statut WHERE id_membre = :id_membre");

			$resultat -> bindParam(':id_membre', $_GET['id'], PDO::PARAM_INT);
		} else {

			$resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, NOW())");

			$mdp_crypte = md5($_POST['mdp']);
			$resultat -> bindParam(':mdp', $mdp_crypte, PDO::PARAM_STR);
		}

		$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
		$resultat -> bindParam(':nom', $_POST['nom'], PDO::PARAM_STR);
		$resultat -> bindParam(':prenom', $_POST['prenom'], PDO::PARAM_STR); 
		$resultat -> bindParam(':email', $_POST['email'], PDO::PARAM_STR); 
		$resultat -> bindParam(':civilite', $_POST['civilite'], PDO::PARAM_STR); 
		$resultat -> bindParam(':statut', $_POST['statut'], PDO::PARAM_STR); 

		if ($resultat -> execute()) {
			header('location:gestion_membre.php');
		}
	}
}

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if ($resultat -> rowCount() > 0) {
		$membre_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);	
	} 
}

if (isset($membre_actuel)) {
	$pseudo = $membre_actuel['pseudo'];
	$nom = $membre_actuel['nom'];
	$prenom = $membre_actuel['prenom'];
	$email = $membre_actuel['email'];
	$civilite = $membre_actuel['civilite'];
	$statut = $membre_actuel['statut'];
} elseif (!empty($msg)) {
	$pseudo = $_POST['pseudo'];
	$nom = $_POST['nom'];
	$mdp = '';
	$prenom = $_POST['prenom'];
	$email = $_POST['email'];
	$civilite = (isset($_POST['civilite'])) ? $_POST['civilite'] : '';
	$statut = (isset($_POST['statut'])) ? $_POST['statut'] : '';
} else {
	$pseudo = '';
	$mdp = '';
	$nom = '';
	$prenom = '';
	$email = '';
	$civilite = '';
	$statut = '';
}

$action = (isset($membre_actuel)) ?'Modifier ' : 'Ajouter ';

$disabled = '';
if (isset($_GET['action']) && !empty($_GET['action']) && $_GET['action'] == 'details') {
	$disabled = 'disabled';
	$action = 'Détails d\'';
}

require_once('../inc/header.inc.php');

?>

<h1>Gestion des membres</h1>
<?php if(!empty($membre)): ?>
<table border="1" class="table table-striped table-bordered table-hover">
	<tr>
		<?php for($i = 0; $i < $recup_membre -> columnCount(); $i++): ?>
			<?php $colonne = $recup_membre -> getColumnMeta($i); ?>
			<th><?= $colonne['name']; ?></th>
		<?php endfor; ?>
		<th colspan="3">Actions</th>
	</tr>
	
	<?php foreach ($membre as $indice => $valeur): ?>
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
			<td><a href="gestion_membre.php?id=<?= $valeur['id_membre']; ?>&action=details"><i class="fa fa-search" aria-hidden="true"></i></a></td>
			<td><a href="gestion_membre.php?id=<?= $valeur['id_membre']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
			<td><a href="supprimer_membre.php?id=<?= $valeur['id_membre']; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
		</tr>
	<?php endforeach; ?>
	
</table>
<?php else: ?>
	<p>Aucun membre</p>
<?php endif; ?>

<h2><?= $action; ?>un membre</h2>
<?= $msg; ?>
<form action="" method="post" enctype="multipart/form-data">
	<div class="col-lg-6">
		<label>Pseudo : </label><br/>
		<input type="text" name="pseudo" value="<?= $pseudo;  ?>" <?= $disabled; ?> placeholder="Pseudo"  /> <br/><br/>

		<?php if (!isset($_GET['id'])): ?>
		<label>Mot de passe : </label><br/>
		<input type="password" name="mdp" value="<?= $mdp;  ?>" <?= $disabled; ?> placeholder="Mot de passe" /><br/><br/>
		<?php endif; ?>

		<label>Nom : </label><br/>
		<input type="text" name="nom" value="<?= $nom;  ?>" <?= $disabled; ?> placeholder="Nom" /><br/><br/>

		<label>Prénom : </label><br/>
		<input type="text" name="prenom" value="<?= $prenom;  ?>" <?= $disabled; ?> placeholder="Prénom" /><br/><br/>

		<label>Email : </label><br/>
		<input type="email" name="email" value="<?= $email;  ?>" <?= $disabled; ?> placeholder="Email" /><br/><br/>
	</div>
	<div class="col-lg-6">
		<label>Civilite : </label><br/>
		<select name="civilite">
			<option selected="true" disabled>-- Selectionnez --</option>
			<option value="Homme" <?= ($civilite == 'Homme') ? 'selected' : '' ?> <?= $disabled; ?> >Homme</option>
			<option value="Femme" <?= ($civilite == 'Femme') ? 'selected' : '' ?> <?= $disabled; ?> >Femme</option>
		</select><br/><br/>

		<label>Statut : </label><br/>
		<select name="statut">
			<option selected="true" disabled>-- Selectionnez --</option>
			<option value="1" <?= ($statut == '1') ? 'selected' : '' ?> <?= $disabled; ?> >Admin</option>
			<option value="0" <?= ($statut == '0') ? 'selected' : '' ?> <?= $disabled; ?> >Membre</option>
		</select><br/><br/>

		<?php if(empty($disabled)): ?>
		<input type="submit" class="btn btn-primary" value="<?= $action; ?>">
		<?php endif; ?>
		
		<?php if($action == 'Modifier ' || !empty($disabled)): ?>
		<a href="<?= RACINE_SITE ?>backoffice/gestion_membre.php" class="btn btn-danger">Retour</a>
		<?php endif; ?>
	</div>
</form>

<?php require_once('../inc/footer.inc.php'); ?>