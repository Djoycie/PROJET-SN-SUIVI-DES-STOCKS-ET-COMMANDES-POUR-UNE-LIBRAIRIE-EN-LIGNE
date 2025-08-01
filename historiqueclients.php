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

    // Requête pour récupérer toutes les données des clients
    $stmt = $conn->prepare("SELECT * FROM client");
    $stmt->execute();
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Liste des Clients</title>
    <style>
        /* Style global de la page */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f7fc;
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

        /* Conteneur principal centré */
        .container {
            width: 90%;
            max-width: 1200px;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            overflow: hidden;
        }

        h1 {
            color: #3498db;
            margin-bottom: 20px;
        }

        /* Table style */
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
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

            h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>

    <!-- Image de fond floutée -->
    <div class="background"></div>

    <div class="container">
        <h1>Liste des Clients</h1>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Adresse</th>
                    <th>Date d'inscription</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['id']); ?></td>
                        <td><?= htmlspecialchars($client['nom']); ?></td>
                        <td><?= htmlspecialchars($client['prenom']); ?></td>
                        <td><?= htmlspecialchars($client['email']); ?></td>
                        <td><?= htmlspecialchars($client['telephone']); ?></td>
                        <td><?= htmlspecialchars($client['adresse']); ?></td>
                        <td><?= htmlspecialchars($client['date_inscription']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>