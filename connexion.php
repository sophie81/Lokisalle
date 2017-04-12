<?php 
require_once('inc/init.inc.php');

if (userConnecte()) {
	header('location:profil.php');
}

// traitements pour la connexion :
if($_POST){
	//debug($_POST);
	
	$resultat = $pdo -> prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
	$resultat -> bindParam(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
	$resultat -> execute(); 
	
	if($resultat -> rowCount() > 0){ // Cela signifie que le pseudo existe bien dans ma BDD
		$membre = $resultat -> fetch(PDO::FETCH_ASSOC);
		// Le fetch() me permet de récupérer les info du membre sous forme de tableau de données ARRAY. 
		
		if($membre['mdp'] == md5($_POST['mdp'])){ // Tout est ok, le pseudo existe et en plus le MDP tapé correspond bien au MDP dans la BDD
			
			
			//$_SESSION['membre']['pseudo'] = $membre['pseudo'];
			//$_SESSION['membre']['prenom'] = $membre['prenom'];
			
			//Plus pratique dans une boucle :
			foreach($membre as $indice => $valeur){
				$_SESSION['membre'][$indice] = $valeur;
			}
			
			//debug($_SESSION);
			header("location:profil.php");
			
		}
		else{
			$msg .= '<div class="erreur">Erreur de mot de passe !</div>';
		}	
	}
	else{
		$msg .= '<div class="erreur">Erreur de pseudo !</div>';
	}
}

require_once('inc/header.inc.php');
?>
<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<h1 class="text-center">Se connecter</h1>
			<?= $msg ?>
			<form action="" method="post">
				<input type="text" name="pseudo" placeholder="Pseudo" /><br/><br/>
				
				<input type="password" name="mdp" placeholder="Mot de passe" /><br/><br/>

				<input type="submit" value="Connexion" class="btn btn-primary" />
			</form>
		</div>
	</div>
</div>
<?php 
require_once('inc/footer.inc.php');
?>