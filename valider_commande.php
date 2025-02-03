<?php
session_start();

$serverName = "DESKTOP-H147H0H\\SQLEXPRESS";
$database = "projetsn";
$username = "";  // Remplis ton username
$password = "";  // Remplis ton password

try {
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["panier"]) && !empty($_SESSION["panier"])) {
        $nom = $_POST["nom"];
        $adresse = $_POST["adresse"];
        $telephone = $_POST["telephone"];
        $total = array_sum(array_map(fn($article) => $article["prix"] * $article["quantite"], $_SESSION["panier"]));

        // Appel de la procédure stockée pour enregistrer la commande
        $stmt = $conn->prepare("DECLARE @CommandeID INT; EXEC PasserCommande :nom, :adresse, :telephone, :total, @CommandeID OUTPUT; SELECT @CommandeID AS CommandeID;");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':total', $total);
        $stmt->execute();

        $commande = $stmt->fetch(PDO::FETCH_ASSOC);
        $commandeID = $commande["CommandeID"];

        // Insérer les articles commandés
        foreach ($_SESSION["panier"] as $article) {
            $stmt = $conn->prepare("INSERT INTO Commande_Detail (commande_id, livre_id, quantite, prix_unitaire) VALUES (:commande_id, :livre_id, :quantite, :prix_unitaire)");
            $stmt->bindParam(':commande_id', $commandeID);
            $stmt->bindParam(':livre_id', $article["id"]);
            $stmt->bindParam(':quantite', $article["quantite"]);
            $stmt->bindParam(':prix_unitaire', $article["prix"]);
            $stmt->execute();
        }

        unset($_SESSION["panier"]);
        echo "<script>alert('Commande enregistrée avec succès. ID de commande : " . $commandeID . "');</script>";

    } else {
        echo "Votre panier est vide.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>