<?php
session_start();

// Connexion à la base de données
$serverName = "DESKTOP-H147H0H\\SQLEXPRESS";
$database = "projetsn";
$username = "";
$password = "";
$conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$userId = $_SESSION['user_id']; // Assurez-vous que l'ID utilisateur est stocké dans la session

// Récupérer les commandes de l'utilisateur
$stmt = $conn->prepare("SELECT * FROM Commands WHERE client_id = :user_id");
$stmt->bindParam(':user_id', $userId);
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Affichage des commandes
if (count($commandes) > 0) {
    echo "<table border='1' style='border-collapse:collapse; width:100%;'>";
    echo "<tr><th>ID Commande</th><th>Date</th><th>Total</th><th>Statut</th><th>Actions</th></tr>";

    foreach ($commandes as $commande) {
        // Affichage des informations de chaque commande
        echo "<tr>";
        echo "<td>" . $commande['id_commande'] . "</td>";
        echo "<td>" . $commande['date_commande'] . "</td>";
        echo "<td>" . $commande['total'] . " fcfa</td>";
        echo "<td>" . $commande['statut'] . "</td>";

        // Affichage du bouton d'annulation si la commande est en statut "en cours"
        if ($commande['statut'] == "en cours") {
            echo "<td><button onclick='annulerCommande(" . $commande['id_commande'] . ")'>Annuler</button></td>";
        } else {
            echo "<td>Aucune action possible</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "Vous n'avez pas de commandes.";
}
?>