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