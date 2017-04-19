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
				<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
		            <div class="navbar-header">
		                <a class="navbar-brand" href="<?= RACINE_SITE ?>index.php"">Lokisalle</a>
		                <?php if(!userConnecte()) : ?>
		                	<ul class="nav navbar-top-links navbar-left">
		                		<li><a href="">Qui sommes nous</a></li>
			                	<li><a href="<?= RACINE_SITE ?>contact.php">Contact</a></li>
		                	</ul>
			            <?php endif; ?>
		            </div>
		            <!-- /.navbar-header -->

		            <ul class="nav navbar-top-links navbar-right">
						<li class="dropdown">
		                    <div class="dropdown-toggle" data-toggle="dropdown" href="#">
		                        <i class="fa fa-user fa-fw"></i>Espace Membre <i class="fa fa-caret-down"></i>
		                    </div>
		                    <ul class="dropdown-menu dropdown-user">
		                        <?php if(userConnecte()) : ?>
									<li><a href="<?= RACINE_SITE ?>profil.php"><i class="fa fa-user fa-fw"></i> Profil</a></li>
									<?php if(!userAdmin()) : ?>
										<li><a href="<?= RACINE_SITE ?>index.php""><i class="fa fa-list" aria-hidden="true"></i> Accueil</a></li>
										<li><a href="#"><i class="fa fa-list" aria-hidden="true"></i> Historique des commandes</a></li>
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
		            <!-- /.navbar-top-links -->
					<?php if (userAdmin()): ?>
			            <div class="navbar-default sidebar" role="navigation" style="display: block;">
			                <div class="sidebar-nav navbar-collapse">
			                    <ul class="nav" id="side-menu">
			                        <li>
			                            <a href="<?= RACINE_SITE ?>backoffice/dashboard.php">Dashboard</a>
			                        </li>
			                        <li>
			                            <a href="<?= RACINE_SITE ?>backoffice/gestion_salle.php">Gestion des salles</span></a>
			                        </li>
			                        <li>
			                            <a href="<?= RACINE_SITE ?>backoffice/gestion_produit.php">Gestion des produits</a>
			                        </li>
			                        <li>
			                            <a href="<?= RACINE_SITE ?>backoffice/gestion_membre.php">Gestions des membres</a>
			                        </li>
			                        <li>
			                            <a href="<?= RACINE_SITE ?>backoffice/gestion_avis.php">Gestions des avis</span></a>
			                        </li>
			                        <li>
			                            <a href="<?= RACINE_SITE ?>backoffice/gestion_commande.php">Gestion des commandes</span></a>
			                        </li>
			                    </ul>
			                </div>
			            <?php endif ?>
		            </div>
		        </nav>
        </header>
        <section>
			<div id="page-wrapper"  class="<?php if (userConnecte()): ?>bo<?php else: ?>front<?php endif; ?>"  > 

				