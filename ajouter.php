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
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #3498db; /* Bleu ciel */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input {
            width: 90%;
            padding: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .show-password {
            display: flex;
            align-items: center;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .show-password input {
            width: auto;
            margin-right: 5px;
        }
        button {
            background-color:#3498db;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #006f8b;
        }
        .links {
            margin-top: 15px;
            font-size: 14px;
        }
        .links a {
            color: #0082a3;
            text-decoration: none;
            font-weight: bold;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
<body>
   
    <form method="POST" enctype="multipart/form-data">
    <h2>Ajouter un Livre</h2>
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