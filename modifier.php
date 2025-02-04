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
    <style>
        /* Styles globaux */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc; /* Bleu clair */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        /* Conteneur principal */
        .container {
            background-color: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            width: 420px;
            text-align: center;
        }

        h2 {
            color: #3498db;
            margin-bottom: 20px;
        }

        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input, select {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #3498db;
            box-shadow: 0px 0px 5px rgba(52, 152, 219, 0.5);
        }

        /* Image du livre */
        .book-image {
            display: flex;
            justify-content: center;
            margin-bottom: 15px;
        }

        .book-image img {
            max-width: 100px;
            border-radius: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Bouton */
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }

        button:hover {
            background-color: #217dbb;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Modifier un Livre</h2>

        <?php if (isset($livre)): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $livre['id']; ?>">
            <input type="hidden" name="ancienne_photo" value="<?= $livre['photo']; ?>">

            <label>Nom du livre :</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($livre['nom']); ?>" required>

            <label>Prix :</label>
            <input type="number" name="prix" value="<?= $livre['prix']; ?>" step="0.01" required>

            <label>Catégorie :</label>
            <input type="text" name="categorie" value="<?= htmlspecialchars($livre['categorie']); ?>" required>

            <label>Quantité :</label>
            <input type="number" name="quantite" value="<?= $livre['quantite']; ?>" required>

            <label>Photo actuelle :</label>
            <div class="book-image">
                <img src="<?= $livre['photo']; ?>" alt="Photo du livre">
            </div>

            <label>Nouvelle photo :</label>
            <input type="file" name="photo">

            <button type="submit" name="modifier">Valider les modifications</button>
        </form>
        <?php else: ?>
            <p>Livre introuvable.</p>
        <?php endif; ?>
    </div>
</body>
</html>