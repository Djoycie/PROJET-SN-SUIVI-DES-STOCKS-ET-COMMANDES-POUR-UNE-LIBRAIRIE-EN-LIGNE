<?php
session_start(); // Démarrage de la session

if (!isset($_SESSION['nom'])) {
    header("Location: login.html"); // Redirection vers la page de login si l'utilisateur n'est pas connecté
    exit();
}

$serverName = "DESKTOP-H147H0H\SQLEXPRESS";
$database = "projetsn";
$username = "";
$password = "";

try {
    // Connexion à la base de données
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données envoyées en POST
    $email = $_POST['email'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $passwordInput = $_POST['password'];

    // Si un mot de passe est fourni, on le hache
    if ($passwordInput) {
        $hashedPassword = password_hash($passwordInput, PASSWORD_DEFAULT);
        // Requête pour mettre à jour les informations du client
        $stmt = $conn->prepare("UPDATE client SET email = :email, adresse = :adresse, telephone = :telephone, mot_de_passe = :mot_de_passe WHERE nom = :nom");
        $stmt->bindParam(':mot_de_passe', $hashedPassword);
    } else {
        // Si aucun mot de passe n'est fourni, on ne le met pas à jour
        $stmt = $conn->prepare("UPDATE client SET email = :email, adresse = :adresse, telephone = :telephone WHERE nom = :nom");
    }

    // Lier les autres paramètres
    $stmt->bindParam(':nom', $_SESSION['nom']);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':adresse', $adresse);
    $stmt->bindParam(':telephone', $telephone);

    // Exécution de la requête
    $stmt->execute();

    // Message de succès
    echo "<script>alert('Informations mises à jour avec succès.'); window.location.href='login.html';</script>";
} catch (PDOException $e) {
    // Gestion des erreurs SQL
    echo "<script>alert('Erreur : " . addslashes($e->getMessage()) . "');</script>";
    exit();
}
?>