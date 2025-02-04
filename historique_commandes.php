<?php
session_start();

// Connexion à la base de données
$serverName = "DESKTOP-H147H0H\SQLEXPRESS";
$database = "projetsn";
$username = "";
$password = "";

try {
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Appel de la procédure stockée pour récupérer toutes les commandes
    $stmt = $conn->prepare("EXEC GetAllCommandes");
    $stmt->execute();
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<script>alert('Erreur : " . addslashes($e->getMessage()) . "');</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Commandes</title>
    <style>
        /* Style global de la page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f7fc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            overflow-x: hidden;
        }

        /* Image de fond floutée */
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('librairie.jpg'); /* Remplacez par le chemin de votre image */
            background-size: cover;
            background-position: center;
            filter: blur(5px);
            z-index: -1;
        }

        /* Conteneur principal */
        .container {
            width: 90%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: #3498db;
            margin-bottom: 20px;
        }

        /* Table style */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #2980b9;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f4f7fc;
        }

        tr:hover {
            background-color: #e1e6f0;
        }

        /* Style responsive */
        @media (max-width: 768px) {
            th, td {
                font-size: 12px;
                padding: 8px 10px;
            }

            h2 {
                font-size: 1.5em;
            }
        }

        img{
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Image de fond floutée -->
    <div class="background">
      
    </div>

    <div class="container">
        <h2>Liste des Commandes</h2>

        <?php if (count($commandes) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Commande ID</th>
                        <th>Nom Client</th>
                        <th>Adresse</th>
                        <th>Téléphone</th>
                        <th>Date de Commande</th>
                        <th>Nom du Livre</th>
                        <th>Quantité</th>
                        <th>Prix Unitaire</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                        <tr>
                            <td><?= htmlspecialchars($commande['commande_id']); ?></td>
                            <td><?= htmlspecialchars($commande['nom']); ?></td>
                            <td><?= htmlspecialchars($commande['adresse']); ?></td>
                            <td><?= htmlspecialchars($commande['telephone']); ?></td>
                            <td><?= htmlspecialchars($commande['date_commande']); ?></td>
                            <td><?= htmlspecialchars($commande['livre_nom']); ?></td>
                            <td><?= htmlspecialchars($commande['quantite']); ?></td>
                            <td><?= htmlspecialchars($commande['prix']); ?> fcfa</td>
                            <td><?= number_format($commande['total_article'], 2); ?> fcfa</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune commande trouvée.</p>
        <?php endif; ?>
    </div>

</body>
</html>