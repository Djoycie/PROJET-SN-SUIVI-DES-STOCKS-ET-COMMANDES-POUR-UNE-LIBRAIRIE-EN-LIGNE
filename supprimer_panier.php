<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $id = $_POST["id"];

  // Supprime l'article du panier
  unset($_SESSION["panier"][$id]);

  // Met à jour la quantité disponible en stock
  $conn = new PDO("sqlsrv:Server=DESKTOP-H147H0H\\SQLEXPRESS;Database=projetsn", "", "");
  $stmt = $conn->prepare("UPDATE Livres SET quantite = quantite + ? WHERE id = ?");
  $stmt->execute(array($_SESSION["panier"][$id]["quantite"], $id));

  echo json_encode(array("success" => true));
}
?>

