<?php
require_once('inc/init.inc.php');

$recup_salles = $pdo -> query("SELECT * FROM salle");
$salles = $recup_salles -> fetchAll(PDO::FETCH_ASSOC);

require_once('inc/header.inc.php');

?>
<h1 class="text-center">Accueil</h1>

<div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group">
                	<p>Categorie</p>
                    <?php foreach ($salles as $indice => $valeur): ?>
	                    <a href="#" class="list-group-item"><?= $valeur['categorie']; ?></a>
	                <?php endforeach; ?>
                </div>
                 <div class="list-group">
                 	<p>Ville</p>
                 	<?php foreach ($salles as $indice => $valeur): ?>
	                    <a href="#" class="list-group-item"><?= $valeur['ville']; ?></a>
	                <?php endforeach; ?>
                </div>
                 <div class="list-group">
                 	<p>Capacite</p>
                    <input type="text">
                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
	                <?php foreach ($salles as $indice => $valeur): ?>
	                    <div class="col-sm-4 col-lg-4 col-md-4">
	                        <div class="thumbnail">
	                            <img src="http://placehold.it/320x150" alt="">
	                            <div class="caption">
	                                <h4 class="pull-right">$24.99</h4>
	                                <h4><a href="#"><?= $valeur['titre']; ?></a>
	                                </h4>
	                                <p>See more snippets like this online store item at <a target="_blank" href="http://www.bootsnipp.com">Bootsnipp - http://bootsnipp.com</a>.</p>
	                            </div>
	                            <div class="ratings">
	                                <p class="pull-right">15 reviews</p>
	                                <p>
	                                    <span class="glyphicon glyphicon-star"></span>
	                                    <span class="glyphicon glyphicon-star"></span>
	                                    <span class="glyphicon glyphicon-star"></span>
	                                    <span class="glyphicon glyphicon-star"></span>
	                                    <span class="glyphicon glyphicon-star"></span>
	                                </p>
	                            </div>
	                        </div>
	                    </div>
	                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

<?php
require_once('inc/footer.inc.php');
?>