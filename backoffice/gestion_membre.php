<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$recup_membre = $pdo -> query("SELECT id_membre, pseudo, nom, prenom, email, civilite, statut, DATE_FORMAT(date_enregistrement, '%d/%m/%Y %H:%m') as date_enregistrement FROM membre");

$membre = $recup_membre -> fetchAll(PDO::FETCH_ASSOC);

if ($_POST) {
	//debug($_POST);

	$required = array('pseudo', 'mdp', 'nom', 'prenom', 'email', 'civilite', 'statut');
	$champ_vide = false;

	foreach ($required as $field) {
		if (!empty($_POST[$field])) {
			
			$verif_caractere = preg_match('#^[a-zA-Z0-9._-]+$#', $_POST['pseudo']); 
			
			// preg_match() est une fonction qui permet de vérifier les caractères d'une chaîne de caractères. Le 1er arg c'est les caractères autorisés (REGEX, ou expression regulière), 2eme arg : la CC que l'on va vérifier. 
			// preg_match() nous retourne TRUE ou FALSE

			if(strlen($_POST[$field]) < 3 || strlen($_POST[$field]) > 25 ){
				$msg .= '<div class="erreur">Veuillez renseigner un pseudo de 3 à 25 caractères</div>';
			}
			
			if($verif_caractere){ // $verif_caractere == TRUE
				
			}
			else{
				$msg .= '<div class="erreur">Pseudo : Caractères acceptés : de A à Z, de 0 à 9, et les "-", "_", "."</div>';
			}
			
		} else{
			$champ_vide = true;
		}
	}

	if ($champ_vide) { 
		$msg .= '<div class="erreur">Veuillez renseigner tout les champs !</div>';
	}


	if(empty($msg)){

		if (isset($_GET['id'])) {
			$resultat = $pdo -> prepare("UPDATE membre SET pseudo = :pseudo, mdp = :mdp, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, statut = :statut WHERE id_membre = :id_membre");

			$resultat -> bindParam(':id_membre', $_GET['id'], PDO::PARAM_INT);
		} else {

			$resultat = $pdo -> prepare("INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, statut, date_enregistrement) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :statut, NOW())");
		}

		$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
		$mdp_crypte = md5($_POST['mdp']);
		$resultat -> bindParam(':mdp', $mdp_crypte, PDO::PARAM_STR);
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

$pseudo = (isset($membre_actuel)) ? $membre_actuel['pseudo'] : '';
$mdp = (isset($membre_actuel)) ? $membre_actuel['mdp'] : '';
$nom = (isset($membre_actuel)) ? $membre_actuel['nom'] : '';
$prenom = (isset($membre_actuel)) ? $membre_actuel['prenom'] : '';
$email = (isset($membre_actuel)) ? $membre_actuel['email'] : '';
$civilite = (isset($membre_actuel)) ? $membre_actuel['civilite'] : '';
$statut = (isset($membre_actuel)) ? $membre_actuel['statut'] : '';

$action = (isset($membre_actuel)) ?'Modifier' : 'Ajouter';

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
		<th colspan="2">Actions</th>
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
			<td><a href="gestion_membre.php?id=<?= $valeur['id_membre']; ?>"><i class="fa fa-search" aria-hidden="true"></i></a></td>
			<td><a href="gestion_membre.php?id=<?= $valeur['id_membre']; ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
			<td><a href="supprimer_membre.php?id=<?= $valeur['id_membre']; ?>"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
		</tr>
	<?php endforeach; ?>
	
</table>
<?php else: ?>
	<p>Aucun membre</p>
<?php endif; ?>

<h2><?= $action; ?> un membre</h2>
<?= $msg; ?>
<form action="" method="post" enctype="multipart/form-data">
	<div class="col-lg-6">
		<label>Pseudo : </label><br/>
		<input type="text" name="pseudo" value="<?= $pseudo;  ?>" placeholder="Pseudo"  /> <br/><br/>

		<?php if (!isset($_GET['id'])): ?>
		<label>Mot de passe : </label><br/>
		<input type="password" name="mdp" value="<?= $mdp;  ?>" placeholder="Mot de passe" /><br/><br/>
		<?php endif; ?>

		<label>Nom : </label><br/>
		<input type="text" name="nom" value="<?= $nom;  ?>" placeholder="Nom" /><br/><br/>

		<label>Prénom : </label><br/>
		<input type="text" name="prenom" value="<?= $prenom;  ?>" placeholder="Prénom" /><br/><br/>

		<label>Email : </label><br/>
		<input type="email" name="email" value="<?= $email;  ?>" placeholder="Email" /><br/><br/>
	</div>
	<div class="col-lg-6">
		<label>Civilite : </label><br/>
		<select name="civilite">
			<option selected="true" disabled="disabled">-- Selectionnez --</option>
			<option value="Homme" <?= ($civilite == 'Homme') ? 'selected' : '' ?> >Homme</option>
			<option value="Femme" <?= ($civilite == 'Femme') ? 'selected' : '' ?> >Femme</option>
		</select><br/><br/>

		<label>Statut : </label><br/>
		<select name="statut">
			<option selected="true" disabled="disabled">-- Selectionnez --</option>
			<option value="Admin" <?= ($statut == '1') ? 'selected' : '' ?> >Admin</option>
			<option value="Membre" <?= ($statut == '0') ? 'selected' : '' ?> >Membre</option>
		</select><br/><br/>

		<input type="submit" class="btn btn-primary" value="<?= $action; ?>">
		
		<?php if($action == 'Modifier'): ?>
		<a href="<?= RACINE_SITE ?>backoffice/gestion_membre.php" class="btn btn-danger">Retour</a>
		<?php endif; ?>
	</div>
</form>


<?php require_once('../inc/footer.inc.php'); ?>