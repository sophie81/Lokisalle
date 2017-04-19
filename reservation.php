<?php 

require_once('inc/init.inc.php');

if($_POST){

	$resultat = $pdo -> prepare("INSERT INTO commande (id_membre, id_produit, date_enregistrement) VALUES (:id_membre, :id_produit, NOW())");

	$resultat1 = $pdo -> prepare("UPDATE produit SET etat = 'reservation' WHERE id_produit = :id_produit");
	$resultat1 -> bindParam(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
	$resultat1 -> execute();

	//STR
	$resultat -> bindParam(':id_membre', $_POST['id_membre'], PDO::PARAM_INT);
	$resultat -> bindParam(':id_produit', $_POST['id_produit'], PDO::PARAM_INT);
	
	if($resultat -> execute()){
		header('location:index.php');
	}

}

 ?>