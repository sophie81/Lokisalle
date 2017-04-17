<?php
require_once('inc/init.inc.php');

if (!userConnecte()) {
    header('location:connexion.php');
}

extract($_SESSION['membre']);

$recup_commande = $pdo -> query("SELECT c.id_commande, c.id_produit, p.prix, DATE_FORMAT(c.date_enregistrement, '%d/%m/%Y %H:%m') as date_enregistrement  FROM produit p, commande c WHERE c.id_produit = p.id_produit AND c.id_membre = $id_membre");
$commande = $recup_commande -> fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
    $resultat = $pdo -> prepare('SELECT c.id_commande, c.id_membre, c.id_produit, p.prix, DATE_FORMAT(c.date_enregistrement, "%d/%m/%Y %H:%m") as date_enregistrement  FROM produit p, commande c WHERE c.id_produit = p.id_produit AND c.id_commande = :id');
    $resultat -> bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $resultat -> execute();

    if ($resultat -> rowCount() > 0) {
        $commande_actuel = $resultat -> fetch(PDO::FETCH_ASSOC);
    }
}

$id_commande = (isset($commande_actuel)) ? $commande_actuel['id_commande'] : '';
$prix = (isset($commande_actuel)) ? $commande_actuel['prix'] : '';
$date_enregistrement = (isset($commande_actuel)) ? $commande_actuel['date_enregistrement'] : '';


require_once('inc/header.inc.php');

?>

    <h1>Historique des commandes</h1>
<?php if(!empty($commande)): ?>
    <table border="1" class="table table-striped table-bordered table-hover">
        <tr>
            <?php for($i = 0; $i < $recup_commande -> columnCount(); $i++): ?>
                <?php $colonne = $recup_commande -> getColumnMeta($i); ?>
                <th><?= $colonne['name']; ?></th>
            <?php endfor; ?>
            <th>Actions</th>
        </tr>


        <?php foreach ($commande as $indice => $valeur): ?>
            <tr>
                <?php foreach($valeur as $indice2 => $valeur2): ?>
                    <?php if ($indice2 == 'id_membre'): ?>
                        <?php $membre_val = getMembre($valeur2); ?>
                        <td><?= $membre_val['id_membre']; ?> - <?= $membre_val['email']; ?></td>
                    <?php elseif ($indice2 == 'id_produit'): ?>
                        <?php $produit_val = getProduit($valeur2); $salle_val = getSalle($produit_val['id_salle']) ?>
                        <td><?= $produit_val['id_salle']; ?> - <?= $salle_val['titre']; ?><br><?= $produit_val['date_arrivee']; ?> au <?= $produit_val['date_depart']; ?></td>
                    <?php elseif ($indice2 == 'prix'): ?>
                        <td><?= $valeur2 ?> €</td>
                    <?php else: ?>
                        <td><?= $valeur2 ?></td>
                    <?php endif; ?>
                <?php endforeach; ?>
                <td><a href="historique_commandes.php?id=<?= $valeur['id_commande']; ?>"><i class="fa fa-search" aria-hidden="true"></i></a></td>
            </tr>
        <?php endforeach; ?>

    </table>
<?php else: ?>
    <p>Aucune commande</p>
<?php endif; ?>

<?php if(isset($_GET['id'])): ?>

    <h1>Détails de votre commande</h1>
    <div class="row">
        <div class="col-md-12">
            <ul class="list-group">
                <li class="list-group-item"><b>ID commande :</b> <span><?= $id_commande; ?></span></li>
                <li class="list-group-item">
                    <?php $produit_val = getProduit($commande_actuel['id_produit']); $salle_val = getSalle($produit_val['id_salle']);?>
                    <img src="<?= RACINE_SITE . 'photo/' . $salle_val['photo'] ?>" width="200" /> <br><br>
                    <p><b>ID produit&nbsp;:&nbsp;</b> <?= $salle_val['id_salle']; ?></p>
				    <p><b>Titre de la salle&nbsp;:&nbsp;</b> <?= $salle_val['titre']; ?></p>
				    <p><b>Adresse&nbsp;:&nbsp;</b> <?= $salle_val['adresse']; ?>, <?= $salle_val['ville']; ?> <?= $salle_val['cp']; ?></p>
                    <p><b>Capacité&nbsp;:&nbsp;</b> <?= $salle_val['capacite']; ?> pers.</p>
                    <span><b>Date d'arrivée&nbsp;:&nbsp;</b><?= $produit_val['date_arrivee']; ?></span> <br>
                    <span><b>Date de départ&nbsp;:&nbsp;</b><?= $produit_val['date_depart']; ?></span>
                </li>
                <li class="list-group-item"><b>Prix :</b> <span><?= $prix; ?> €</span></li>
                <li class="list-group-item"><b>Date d'enregistrement :</b> <span><?= $date_enregistrement; ?></span></li>
            </ul>
        </div>
    </div>
<?php endif; ?>

<?php require_once('inc/footer.inc.php'); ?>