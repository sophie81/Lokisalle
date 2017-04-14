<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultat = $pdo -> prepare("SELECT * FROM avis WHERE id_avis = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();


	if ($resultat -> rowCount() > 0) {
		$avis = $resultat -> fetch(PDO::FETCH_ASSOC);
		$resultat = $pdo -> exec("DELETE FROM avis WHERE id_avis = $avis[id_avis]");

		if ($resultat != FALSE) {
			header('location:gestion_avis.php');
		}
		
	} else {
		header("location:gestion_avis.php");
	}

} else {
	header("location:gestion_avis.php");
}