<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];

    // Vérifier si l'article existe dans le panier
    if (isset($_SESSION["panier"][$id])) {
        // Récupérer la quantité de l'article à supprimer
        $quantite = $_SESSION["panier"][$id]["quantite"];

        // Connexion à la base de données
        try {
            $conn = new PDO("sqlsrv:Server=DESKTOP-H147H0H\\SQLEXPRESS;Database=projetsn", "", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Met à jour la quantité disponible en stock
            $stmt = $conn->prepare("UPDATE Livres SET quantite = quantite + ? WHERE id = ?");
            $stmt->execute(array($quantite, $id));

            // Supprimer l'article du panier
            unset($_SESSION["panier"][$id]);

            // Retourner une réponse en JSON
            echo json_encode(array("success" => true));
        } catch (PDOException $e) {
            echo json_encode(array("error" => $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Article non trouvé dans le panier."));
    }
}
?>