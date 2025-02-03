<?php
session_start();

if (!isset($_SESSION["panier"]) || empty($_SESSION["panier"])) {
  echo "Votre panier est vide.";
  exit;
}
?>

<h2>Informations de livraison</h2>
<form action="valider_commande.php" method="POST">
    <label>Nom :</label>
    <input type="text" name="nom" required><br>

    <label>Adresse :</label>
    <input type="text" name="adresse" required><br>

    <label>Téléphone :</label>
    <input type="text" name="telephone" required><br>

    <button type="submit">Valider la commande</button>
</form>