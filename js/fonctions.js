function showFilter()
{
    var categorie = document.getElementById('categorie').value;
    var ville = document.getElementById('ville').value;
    var capacite = document.getElementById('capacite').value;
    var prix = document.getElementById('prix').value;

    document.getElementById('valuePrix').innerHTML = prix;

    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp=new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
    {
        if (xmlhttp.readyState==4 && xmlhttp.status==200)
        {
            document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
        }
    }
    xmlhttp.open("GET","getFilter.php?categorie="+categorie+"&ville="+ville+"&capacite="+capacite+"&prix="+prix,true);
    xmlhttp.send();
}

function ConfirmSupprSalle(salle) {
    if (confirm("Voulez-vous supprimer cette salle ? Cela entrainera la suppression des produits non commandé ainsi que des commentaires.")) { // Clic sur OK
        document.location.href="supprimer_salle.php?id="+salle;
    }
}

function ConfirmSupprCommande(commande) {
    if (confirm("Voulez-vous supprimer cette commande ?")) { // Clic sur OK
        document.location.href="supprimer_commande.php?id="+commande;
    }
}

function ConfirmSupprProduit(produit) {
    if (confirm("Voulez-vous supprimer ce produit ?")) { // Clic sur OK
        document.location.href="supprimer_produit.php?id="+produit;
    }
}

function ConfirmSupprMembre(membre) {
    if (confirm("Voulez-vous supprimer ce membre ? Cela entrainera la suppression des avis qu'il a rédigé.")) { // Clic sur OK
        document.location.href="supprimer_membre.php?id="+membre;
    }
}

function ConfirmSupprAvis(avis) {
    if (confirm("Voulez-vous supprimer cet avis ?")) { // Clic sur OK
        document.location.href="supprimer_avis.php?id="+avis;
    }
}

function InfoMessage(message) {
    alert(message);
}