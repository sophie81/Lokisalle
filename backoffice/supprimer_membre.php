<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultat = $pdo -> prepare("SELECT * FROM membre WHERE id_membre = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if ($resultat -> rowCount() > 0) {
		$membre = $resultat -> fetch(PDO::FETCH_ASSOC);

		$resultat = $pdo -> exec("DELETE FROM membre WHERE id_membre = $membre[id_membre]");

		if ($resultat != FALSE) {
			header('location:gestion_membre.php');
		}
		
	} else {
		header("location:gestion_membre.php");
	}

} else {
	header("location:gestion_membre.php");
}