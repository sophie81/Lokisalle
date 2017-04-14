<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
	$resultat = $pdo -> prepare("SELECT * FROM commande WHERE id_commande = :id");
	$resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
	$resultat -> execute();

	if ($resultat -> rowCount() > 0) {
		$commande = $resultat -> fetch(PDO::FETCH_ASSOC);

		$resultat = $pdo -> exec("DELETE FROM commande WHERE id_commande = $commande[id_commande]");

		if ($resultat != FALSE) {
			header('location:gestion_commande.php');
		}
		
	} else {
		header("location:gestion_commande.php");
	}

} else {
	header("location:gestion_commande.php");
}