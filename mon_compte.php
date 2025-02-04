<?php
session_start(); // Démarrage de la session

// Vérifie si l'utilisateur est connecté
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

    // Récupérer les informations de l'utilisateur connecté
    $nom = $_SESSION['nom'];
    $stmt = $conn->prepare("SELECT * FROM client WHERE nom = :nom");
    $stmt->bindParam(':nom', $nom);
    $stmt->execute();
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        echo "Utilisateur non trouvé.";
        exit();
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="compte.css">
</head>

<body>

    
    
    <form action="update_compte.php" method="POST">
    <h1>Mon Compte</h1>
        <div  class="form-group">
            <label  for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($client['nom']); ?>" readonly>
        </div>

        <div  class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($client['email']); ?>">
        </div>

        <div  class="form-group">
            <label for="adresse">Adresse</label>
            <input type="text" id="adresse" name="adresse" value="<?= htmlspecialchars($client['adresse']); ?>">
        </div>

        <div  class="form-group">
            <label for="telephone">Téléphone</label>
            <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($client['telephone']); ?>">
        </div>

        <div  class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password">
        </div>

        <button class="btn" type="submit">Valider les modifications</button>
    </form>

</body>
</html>