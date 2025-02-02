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
            echo "<script>alert('Commentaire supprimé avec succès'); window.location.href='historique:commentaire.php';</script>";
        } else {
            echo "<script>alert('Erreur lors de la suppression');</script>";
        }
    }

    // Récupérer les commentaires
    $sql = "SELECT id, prenom, nom, message, date_envoi FROM commentaires ORDER BY date_envoi DESC";
    $stmt = $conn->query($sql);

    echo "<h2>Liste des Commentaires</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Prénom</th><th>Nom</th><th>Message</th><th>Date</th><th>Action</th></tr>";

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
        echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
        echo "<td>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
        echo "<td>" . htmlspecialchars($row['date_envoi']) . "</td>";
        echo "<td><a href='historique_commentaire.php?delete_id=" . $row['id'] . "' onclick='return confirm(\"Voulez-vous vraiment supprimer ce commentaire ?\")'>🗑 Supprimer</a></td>";
        echo "</tr>";
    }

    echo "</table>";

} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>