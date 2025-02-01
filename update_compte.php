<?php
session_start();
$serverName = "DESKTOP-H147H0H\\SQLEXPRESS";
$database = "projetsn";
$username = "";
$password = "";
$conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $adresse = $_POST['adresse'];

    $stmt = $conn->prepare("UPDATE clients SET nom = ?, email = ?, telephone = ?, adresse = ? WHERE id = ?");
    if ($stmt->execute([$nom, $email, $telephone, $adresse, $id])) {
        echo "Informations mises à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour.";
    }
}
?>