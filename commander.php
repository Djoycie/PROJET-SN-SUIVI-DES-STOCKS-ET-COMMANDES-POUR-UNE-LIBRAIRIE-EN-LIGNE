<?php
session_start(); // Démarrage de la session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['nom'])) {
    header("Location: login.html"); // Redirection vers la page de connexion
    exit();
}

$nom = $_SESSION['nom']; // Récupération du nom du client
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Passer une commande</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 700px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #3498db;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 95%;
        }

        input[type="text"]:focus {
            border-color: #3498db;
            outline: none;
        }

        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            
        }

        button:hover {
            background-color: #2980b9;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group:last-child {
            margin-bottom: 0;
        }

        .button-container{
            text-align:center;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Passer une commande</h2>
    <form action="passer_commande.php" method="POST">
        <div class="form-group">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" value="<?= $_SESSION['nom']; ?>" readonly>
        </div>

        <div class="form-group">
            <label for="adresse">Adresse :</label>
            <input type="text" name="adresse" required>
        </div>

        <div class="form-group">
            <label for="telephone">Téléphone :</label>
            <input type="text" name="telephone" required>
        </div>

        <div class="button-container">
            <button type="submit">Confirmer la commande</button>
        </div>
    </form>
</div>

</body>
</html>