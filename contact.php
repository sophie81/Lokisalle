<?php
require_once('inc/init.inc.php');


require_once('inc/header.inc.php');

?>

<div class="container">
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<h1 class="text-center">Contact</h1>
			<?= $msg ?>
			<form action="" method="post">
				<input type="text" name="nom" placeholder="Nom" /><br/><br/>
				
				<input type="text" name="prenom" placeholder="Prénom" /><br/><br/>
			
				<input type="text" name="email" placeholder="Email" /><br/><br/>
				
				<textarea name="message" id="" cols="30" rows="10" placeholder="Votre message"></textarea><br/><br/>
				
				<input type="submit" value="Envoyer" class="btn btn-primary" />	
			</form>
		</div>
	</div>
</div>

<?php
require_once('inc/footer.inc.php');
?>