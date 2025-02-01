<?php
// Configuration de la connexion à la base de données
$serverName = "DESKTOP-H147H0H\SQLEXPRESS";
$database = "projetsn"; 
$username = "";
$password = "";
$conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST["nom"];
    $prix = $_POST["prix"];
    $categorie = $_POST["categorie"];
    $quantite = $_POST["quantite"];

    // Gestion de l’upload de l’image
    $targetDir = "uploads/"; // Dossier où stocker les images
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true); // Crée le dossier si nécessaire
    }

    // Nom unique pour éviter les conflits
    $targetFile = $targetDir . uniqid() . "_" . basename($_FILES["photo"]["name"]);
    
    // Déplacement du fichier téléchargé vers le dossier de destination
    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
        echo "L'image a été téléchargée avec succès : " . $targetFile;
    } else {
        echo "Erreur lors de l'upload de l'image.";
    }

    // Appel de la procédure stockée pour ajouter le livre
    $stmt = $conn->prepare("EXEC sp_AjouterLivre ?, ?, ?, ?, ?");
    $stmt->execute([$targetFile, $nom, $prix, $categorie, $quantite]);

    // Rediriger vers la page de liste des livres après l'ajout
    header("Location: testadmin.php"); // Redirection vers admin.php pour voir la liste des livres
    exit(); // Toujours faire un exit après une redirection
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Livre</title>
</head>
<body>
    <h2>Ajouter un Livre</h2>
    <form method="POST" enctype="multipart/form-data">
        <label>Photo :</label>
        <input type="file" name="photo" required><br><br>
        <label>Nom du livre :</label>
        <input type="text" name="nom" required><br><br>
        <label>Prix :</label>
        <input type="number" name="prix" step="0.01" required><br><br>
        <label>Catégorie :</label>
        <input type="text" name="categorie" required><br><br>
        <label>Quantité :</label>
        <input type="number" name="quantite" required><br><br>
        <button type="submit">Ajouter</button>
    </form>
</body>
</html>