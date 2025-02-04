<?php
session_start(); // Démarrage de la session

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['nom'])) {
    header("Location: login.html");
    exit();
}

$serverName = "DESKTOP-H147H0H\SQLEXPRESS";
$database = "projetsn";
$username = "";
$password = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['commande_id'])) {
    $commande_id = $_POST['commande_id'];

    try {
        // Connexion à la base de données
        $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Appel de la procédure stockée pour annuler la commande
        $stmt = $conn->prepare("EXEC AnnulerCommande :commande_id");
        $stmt->bindParam(':commande_id', $commande_id);
        $stmt->execute();

        echo "<script>alert('Commande annulée avec succès.');window.location.href='pageaccueil.php';</script>";

    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Aucune commande spécifiée.";
}
?>