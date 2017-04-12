<?php
require_once('../inc/init.inc.php');

if (!userAdmin()) {
	header("location:../connexion.php");
}

require_once('../inc/header.inc.php');

?>

<h1>Gestion des salles</h1>

<?php if(!empty($produits)): ?>
<?php else: ?>
	<p>Aucune salle</p>
<?php endif; ?>


<?php require_once('../inc/footer.inc.php'); ?>