<?php
// création d'une fonction debug pour faire les print_r() : 

// déclaration d'une fonction :
function debug($arg){
	//Traitements...		
	echo '<pre>'; 
	print_r($arg);
	echo '</pre>';
}

// Fonction pour voir si l'utilisateur est connecté :
function userConnecte()
{
	if (isset($_SESSION['membre'])) {
		return true;
	} else {
		return false;
	}
}

// Fonction pour voir si l'utilisateur est admin :
function userAdmin()
{
	if (userConnecte() && $_SESSION['membre']['statut'] == 1) {
		return true;
	} else {
		return false;
	}
}

function checkLength($input)
{
	global $msg;

	if(strlen($_POST[$input]) < 3 || strlen($_POST[$input]) > 25 ){
		if ($input == 'mdp') {
			$input = 'mot de passe';
		}

		$msg .= '<div class="erreur">Veuillez renseigner un '. $input . ' de 3 à 25 caractères</div>';
	} 
}

function getMembre($id_membre){
	global $pdo;

	$recup_membre = $pdo -> query("SELECT id_membre, email, pseudo FROM membre WHERE id_membre = $id_membre");

	$membre = $recup_membre -> fetch(PDO::FETCH_ASSOC);

	return $membre;
}

function getSalle($id_salle){
	global $pdo;
	
	$recup_salle = $pdo -> query("SELECT * FROM salle WHERE id_salle = $id_salle");

	$salle = $recup_salle -> fetch(PDO::FETCH_ASSOC);

	return $salle;
}

function getProduit($id_produit)
{
	global $pdo;

	$recup_produit = $pdo -> query("SELECT id_salle, id_produit, DATE_FORMAT(date_arrivee, '%d/%m/%Y') AS date_arrivee, DATE_FORMAT(date_depart, '%d/%m/%Y') AS date_depart FROM produit WHERE id_produit = $id_produit ");

	$produit = $recup_produit -> fetch(PDO::FETCH_ASSOC);

	return $produit;
}