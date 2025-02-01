<?php
// Configuration de la connexion à la base de données
$serverName = "DESKTOP-H147H0H\\SQLEXPRESS";
$database = "projetsn"; 
$username = "";
$password = "";
$conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Vérifier si l'ID du livre est fourni
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    die("ID du livre non spécifié.");
}

// Récupérer les informations du livre
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM Livres WHERE id = ?");
    $stmt->execute([$id]);
    $livre = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    $categorie = $_POST['categorie'];
    $quantite = $_POST['quantite'];
    
    // Vérification de l'upload de la photo
    if ($_FILES['photo']['name']) {
        $photoName = basename($_FILES["photo"]["name"]);
        $photoPath = "uploads/" . $photoName; // Dossier "uploads" pour stocker les images
        
        // Déplacer le fichier uploadé
        move_uploaded_file($_FILES["photo"]["tmp_name"], $photoPath);
    } else {
        // Si aucune nouvelle photo, garder l'ancienne
        $photoPath = $_POST['ancienne_photo'];
    }

    // Requête UPDATE qui déclenchera le trigger
    $stmt = $conn->prepare("UPDATE Livres SET nom = ?, prix = ?, categorie = ?, quantite = ?, photo = ? WHERE id = ?");
    $stmt->execute([$nom, $prix, $categorie, $quantite, $photoPath, $id]);

    echo "<script>alert('Livre modifié avec succès !'); window.location.href = 'testadmin.php'; </script>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Livre</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Modifier un Livre</h2>

    <?php if (isset($livre)): ?>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $livre['id']; ?>">
        <input type="hidden" name="ancienne_photo" value="<?= $livre['photo']; ?>">
        
        <label>Nom du livre :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($livre['nom']); ?>" required><br><br>
        
        <label>Prix :</label>
        <input type="number" name="prix" value="<?= $livre['prix']; ?>" step="0.01" required><br><br>
        
        <label>Catégorie :</label>
        <input type="text" name="categorie" value="<?= htmlspecialchars($livre['categorie']); ?>" required><br><br>
        
        <label>Quantité :</label>
        <input type="number" name="quantite" value="<?= $livre['quantite']; ?>" required><br><br>

        <label>Photo actuelle :</label><br>
        <img src="<?= $livre['photo']; ?>" alt="Photo du livre" width="100"><br><br>

        <label>Nouvelle photo :</label>
        <input type="file" name="photo"><br><br>
        
        <button type="submit" name="modifier" style="background-color: blue; color: white;">Modifier</button>
    </form>
    <?php else: ?>
        <p>Livre introuvable.</p>
    <?php endif; ?>
</body>
</html>