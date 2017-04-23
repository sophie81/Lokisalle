<!Doctype html>
<html>
    <head>
        <title>LOKISALLE</title>
        <link rel="stylesheet" href="<?= RACINE_SITE ?>css/bootstrap.min.css"/>
        <link rel="stylesheet" href="<?= RACINE_SITE ?>css/style.css"/>
        <link rel="stylesheet" href="<?= RACINE_SITE ?>font-awesome/css/font-awesome.min.css">
    </head>
    <body>    
        <header>
				<nav class="navbar navbar-default navbar-static-top" style="margin-bottom: 0">
		            <div class="navbar-header">
		                <a class="navbar-brand" href="<?= RACINE_SITE ?>index.php">Lokisalle</a>
		                <?php if(!userAdmin()): ?>
		                	<ul class="nav navbar-top-links navbar-left">
		                		<li><a href="<?= RACINE_SITE ?>qui-somme-nous.php">Qui sommes nous</a></li>
			                	<li><a href="<?= RACINE_SITE ?>contact.php">Contact</a></li>
		                	</ul>
			            <?php endif; ?>
		            </div>
		            <!-- /.navbar-header -->

		            <ul class="nav navbar-top-links navbar-right">
			            <?php if(userAdmin()) : ?>
			            	<li class="dropdown">
			            		<div class="dropdown-toggle" data-toggle="dropdown">
			                        <i class="fa fa-cog" aria-hidden="true"></i>&nbsp;Gestion <i class="fa fa-caret-down"></i>
			                    </div>
			                    <ul class="dropdown-menu dropdown-user">
				            		<li>
			                            <a href="<?= RACINE_SITE ?>backoffice/dashboard.php">Dashboard</a>
			                        </li>
			                        <li class="divider"></li>
			                        <li>
			                            <a href="<?= RACINE_SITE ?>backoffice/gestion_salle.php">Gestion des salles</a>
			                        </li>
			                        <li>
			                            <a href="<?= RACINE_SITE ?>backoffice/gestion_produit.php">Gestion des produits</a>
			                        </li>
			                        <li>
			                            <a href="<?= RACINE_SITE ?>backoffice/gestion_membre.php">Gestions des membres</a>
			                        </li>
			                        <li>
			                            <a href="<?= RACINE_SITE ?>backoffice/gestion_avis.php">Gestions des avis</a>
			                        </li>
			                        <li>
			                            <a href="<?= RACINE_SITE ?>backoffice/gestion_commande.php">Gestion des commandes</a>
			                        </li>
			                    </ul>
			                <?php endif; ?>
		            	</li>
						<li class="dropdown">
		                    <div class="dropdown-toggle" data-toggle="dropdown">
		                        <i class="fa fa-user fa-fw"></i>Espace Membre <i class="fa fa-caret-down"></i>
		                    </div>
		                    <ul class="dropdown-menu dropdown-user">
		                        <?php if(userConnecte()) : ?>
									<li><a href="<?= RACINE_SITE ?>profil.php"><i class="fa fa-user fa-fw"></i> Profil</a></li>
									<?php if(!userAdmin()) : ?>
										<li class="divider"></li>
										<li><a href="<?= RACINE_SITE ?>index.php""><i class="fa fa-home" aria-hidden="true"></i> Accueil</a></li>
										<li class="divider"></li>
										<li><a href="historique_commandes.php"><i class="fa fa-list" aria-hidden="true"></i> Historique des commandes</a></li>
									<?php endif; ?>
									<li class="divider"></li>
									<li><a href="<?= RACINE_SITE ?>deconnexion.php"><i class="fa fa-sign-out fa-fw"></i> Déconnexion</a></li>
								<?php else : ?>
									<li><a href="<?= RACINE_SITE ?>inscription.php"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Inscription</a></li>
									<li><a href="<?= RACINE_SITE ?>connexion.php"><i class="fa fa-sign-in" aria-hidden="true"></i> Connexion</a></li>
								<?php endif; ?>
		                    </ul>
		                    <!-- /.dropdown-user -->
		                </li>
		            </ul>
		        </nav>
        </header>
        <section>
			<div id="page-wrapper"  class="<?php if (userAdmin()): ?>bo<?php else: ?>front<?php endif; ?> container"  > 

				