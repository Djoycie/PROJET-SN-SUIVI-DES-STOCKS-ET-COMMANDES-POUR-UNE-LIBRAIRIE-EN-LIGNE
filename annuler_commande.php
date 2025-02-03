<?php
$serverName = "DESKTOP-H147H0H\\SQLEXPRESS";
$database = "projetsn";
$username = "";
$password = "";

$conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$commande_id = $_POST['commande_id'];

$stmt = $conn->prepare("EXEC AnnulerCommande :commande_id");
$stmt->bindParam(':commande_id', $commande_id);
$stmt->execute();

echo "Commande annulée.";
?>