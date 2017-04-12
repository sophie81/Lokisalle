<?php
require_once('inc/init.inc.php');

if (userConnecte()) {
	unset($_SESSION['membre']);
}

header('location:index.php');

?>