<?php
// Configuration de la connexion à la base de données
$serverName = "DESKTOP-H147H0H\SQLEXPRESS";
$database = "projetsn"; 
$username = "";
$password = "";
$conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Supprimer le livre si l'ID est passé en GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Appel de la procédure stockée pour supprimer le livre
    $stmt = $conn->prepare("EXEC sp_SupprimerLivre ?");
    $stmt->execute([$id]);

    // Afficher le message de succès
    $message = "Livre supprimé avec succès!";
    echo "<script>alert('$message'); window.location.href = 'testadmin.php';</script>";
    exit; // S'assurer que la suppression est effectuée avant de rediriger
}

// Récupérer les livres de la base de données
$stmt = $conn->prepare("SELECT * FROM Livre");
$stmt->execute();
$livres = $stmt->fetchAll();

echo '<h2>Liste des Livres</h2>';
echo '<table>';
echo '<tr><th>Photo</th><th>Nom</th><th>Prix</th><th>Catégorie</th><th>Quantité</th><th>Actions</th></tr>';

foreach ($livres as $livre) {
    $id = $livre['id'];
    $photo = $livre['photo'];
    $nom = $livre['nom'];
    $prix = $livre['prix'];
    $categorie = $livre['categorie'];
    $quantite = $livre['quantite'];

    echo '<tr>';
    echo '<td><img src="' . $photo . '" alt="Photo du livre" width="100"></td>';
    echo '<td>' . $nom . '</td>';
    echo '<td>' . $prix . ' €</td>';
    echo '<td>' . $categorie . '</td>';
    echo '<td>' . $quantite . '</td>';
    echo '<td><button onclick="confirmDelete(' . $id . ')">Supprimer</button></td>';
    echo '</tr>';
}

echo '</table>';

?>

<script>
    // Fonction qui confirme la suppression du livre
    function confirmDelete(id) {
        // Demander confirmation via une alerte
        if (confirm("Êtes-vous sûr de vouloir supprimer ce livre ?")) {
            // Si l'utilisateur clique sur OK, effectuer la suppression en redirigeant vers supprimer.php
            window.location.href = 'testadmin.php?id=' + id;
        }
    }
</script>