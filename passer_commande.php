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

try {
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nom = $_SESSION['nom'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $articles = $_SESSION['panier']; // Récupération des articles du panier

    // Création du XML des articles
    $xml = "<articles>";
    foreach ($articles as $article) {
        $xml .= "<article>";
        $xml .= "<nom>" . htmlspecialchars($article['nom']) . "</nom>"; // Utilisation du nom au lieu de l'id
        $xml .= "<quantite>" . intval($article['quantite']) . "</quantite>";
        $xml .= "<prix>" . floatval($article['prix']) . "</prix>";
        $xml .= "</article>";
    }
    $xml .= "</articles>";

    // Appel de la procédure stockée
    $stmt = $conn->prepare("EXEC AjouterCommanddes :nom, :adresse, :telephone, :articles");
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':adresse', $adresse);
    $stmt->bindParam(':telephone', $telephone);
    $stmt->bindParam(':articles', $xml);
    $stmt->execute();

    echo "<script>alert('Commande passee avec succes.');window.location.href='pageaccueil.php';</script>";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>