<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST["id"];
  $nom = $_POST["nom"];
  $prix = $_POST["prix"];
  $photo = $_POST["photo"];
  $quantite = $_POST["quantite"];

  // Vérifie si l'article est déjà dans le panier
  if (isset($_SESSION["panier"][$id])) {
    // Met à jour la quantité de l'article dans le panier
    $_SESSION["panier"][$id]["quantite"] += $quantite;
  } else {
    // Ajoute l'article au panier
    $_SESSION["panier"][$id] = array(
      "nom" => $nom,
      "prix" => $prix,
      "photo" => $photo,
      "quantite" => $quantite
    );
  }

  // Met à jour la quantité disponible en stock
  $conn = new PDO("sqlsrv:Server=DESKTOP-H147H0H\\SQLEXPRESS;Database=projetsn", "", "");
  $stmt = $conn->prepare("UPDATE Livres SET quantite = quantite - ? WHERE id = ?");
  $stmt->execute(array($quantite, $id));

  $notifAdmin = "Stock du livre ID " . $idLivre . " réduit de " . $quantite;
    file_put_contents("notifications.txt", $notifAdmin . PHP_EOL, FILE_APPEND);

  echo json_encode(array("success" => true));
}
?>

