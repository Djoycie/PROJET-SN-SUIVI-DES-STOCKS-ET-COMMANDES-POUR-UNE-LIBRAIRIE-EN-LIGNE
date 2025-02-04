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
    // Connexion à la base de données
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $nom = $_SESSION['nom']; // Récupérer le nom de l'utilisateur depuis la session

    // Appel de la procédure stockée pour récupérer l'historique des commandes
    $stmt = $conn->prepare("EXEC HistoriqueCommandess :nom");
    $stmt->bindParam(':nom', $nom);
    $stmt->execute();

    // Récupérer tous les résultats
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Vérifier s'il y a des commandes
    if (count($commandes) > 0) {
        echo "<h2>Historique des commandes</h2>";
        foreach ($commandes as $commande) {
            echo "<div class='commande'>";
            echo "<p><strong>Commande ID:</strong> " . $commande['commande_id'] . "</p>";
            echo "<p><strong>Nom:</strong> " . $commande['nom'] . "</p>";
            echo "<p><strong>Adresse:</strong> " . $commande['adresse'] . "</p>";
            echo "<p><strong>Téléphone:</strong> " . $commande['telephone'] . "</p>";
            echo "<p><strong>Date de Commande:</strong> " . $commande['date_commande'] . "</p>";

            // Afficher les détails des articles
            echo "<h3>Détails des articles :</h3>";
            echo "<ul>";
            echo "<li><strong>Nom du livre:</strong> " . $commande['livre_nom'] . "</li>";
            echo "<li><strong>Quantité:</strong> " . $commande['quantite'] . "</li>";
            echo "<li><strong>Prix unitaire:</strong> " . $commande['prix'] . " €</li>";
            echo "<li><strong>Total de l'article:</strong> " . number_format($commande['total_article'], 2) . " €</li>";
            echo "</ul>";

            // Ajouter un bouton pour annuler la commande
            echo "<form method='POST' action='annuler_commande.php' class='annuler-form'>";
            echo "<input type='hidden' name='commande_id' value='" . $commande['commande_id'] . "'>";
            echo "<button type='submit' class='annuler-btn' onclick='return confirm(\"Êtes-vous sûr de vouloir annuler cette commande ?\");'>Annuler la commande</button>";
            echo "</form>";

            echo "<hr>";
            echo "</div>";
        }
    } else {
        echo "<script>alert('Aucune commande trouvée');window.location.href='pageaccueil.php';</script>";
    }

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des commandes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            color: #3498db;
            margin-bottom: 20px;
        }

        .commande {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f4f7fc;
        }

        .commande p {
            margin: 5px 0;
        }

        .commande h3 {
            margin-top: 10px;
            color: #2980b9;
        }

        .commande ul {
            padding-left: 20px;
        }

        .commande ul li {
            margin: 5px 0;
        }

        .annuler-form {
            text-align: center;
            margin-top: 20px;
        }

        .annuler-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .annuler-btn:hover {
            background-color: #c0392b;
        }

        hr {
            border: 0;
            border-top: 1px solid #ddd;
            margin-top: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .commande {
                padding: 10px;
            }

            h2 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <!-- PHP code will inject content here -->
</div>

</body>
</html>