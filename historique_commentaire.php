<?php
try {
    // Connexion à SQL Server
    $conn = new PDO("sqlsrv:server=DESKTOP-H147H0H\SQLEXPRESS;database=projetsn", "", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Suppression d'un commentaire si un ID est passé en paramètre
    if (isset($_GET['delete_id'])) {
        $delete_id = intval($_GET['delete_id']);
        $sql = "{CALL DeleteCommentaire(:id)}";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            echo "<script>alert('Commentaire supprimé avec succès'); window.location.href='historique_commentaire.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de la suppression');</script>";
        }
    }

    // Récupérer les commentaires
    $sql = "SELECT id, prenom, nom, message, date_envoi FROM commentaires ORDER BY date_envoi DESC";
    $stmt = $conn->query($sql);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Commentaires</title>
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

        /* Conteneur principal */
        .container {
            width: 90%;
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            overflow: hidden;
        }

        h2 {
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

        /* Style du bouton de suppression */
        a {
            text-decoration: none;
            color: #e74c3c;
            font-weight: bold;
        }

        a:hover {
            color: #c0392b;
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
    </style>
</head>
<body>

    <!-- Image de fond floutée -->
    <div class="background"></div>

    <div class="container">
        <h2>Liste des Commentaires</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['id']); ?></td>
                        <td><?= htmlspecialchars($row['prenom']); ?></td>
                        <td><?= htmlspecialchars($row['nom']); ?></td>
                        <td><?= nl2br(htmlspecialchars($row['message'])); ?></td>
                        <td><?= htmlspecialchars($row['date_envoi']); ?></td>
                        <td>
                            <a href="historique_commentaire.php?delete_id=<?= $row['id']; ?>" 
                               onclick="return confirm('Voulez-vous vraiment supprimer ce commentaire ?')">
                                🗑 Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>