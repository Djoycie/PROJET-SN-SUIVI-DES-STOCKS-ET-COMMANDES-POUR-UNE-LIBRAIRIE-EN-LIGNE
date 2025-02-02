<?php
// Configuration de la connexion à la base de données
$serverName = "DESKTOP-H147H0H\SQLEXPRESS";
$database = "projetsn"; 
$username = "";
$password = "";
$conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Récupérer la liste des livres
$stmt = $conn->prepare("SELECT * FROM dbo.Livres");
$stmt->execute();
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer toutes les catégories pour le filtrage
$categoriesStmt = $conn->prepare("SELECT DISTINCT categorie FROM dbo.Livres");
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// Rechercher des livres par nom
if (isset($_POST['recherche'])) {
    $searchTerm = $_POST['recherche'];
    $stmt = $conn->prepare("SELECT * FROM dbo.Livres WHERE nom LIKE :searchTerm");
    $stmt->execute(['searchTerm' => "%$searchTerm%"]);
    $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Filtrer par catégorie
if (isset($_POST['categorie']) && $_POST['categorie'] != '') {
    $category = $_POST['categorie'];
    $stmt = $conn->prepare("SELECT * FROM dbo.Livres WHERE categorie = :categorie");
    $stmt->execute(['categorie' => $category]);
    $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory System</title>
    <link rel="stylesheet" href="admin.css">
   
</head>
<body>
    <div class="sidebar">
        <h2>Systeme inventaire</h2>
        <div class="user-info">
            <div class="profile-pic"></div>
            <img src="téléchargement.png" alt="profile picture">
            <p>Admin</p>
            <span class="status">Online</span>
        </div>
        <nav>
            <ul>
                <li>Gestion du stock</li>
                <li onclick="window.location.href='historiqueclients.php'">Historique des clients</li>
                <li>Historique commandes</li>
                <li  onclick="window.location.href='historique_commentaire.php'">Rapport satisfaction Client</li>
               <li id="logoutButton">Se deconnecter</li>
            </ul>
        </nav>
    </div>
    <div class="main-content">
        <header>
            <h1>Gestion des stocks</h1>
            <div class="actions">
                <a href="ajouter.php"><button class="btn green">+ Ajouter un livre</button></a>
                <form action="export.php" method="GET" ><button type="submit" name="export" value="pdf" class="btn orange">Imprimer PDF</button></form>
            </div>
        </header>
        
        <!-- Formulaires de recherche et de filtrage -->
        <div class="filter-search-container">
            <form method="POST" class="search-form">
                <input class="re" type="text" name="recherche" placeholder="Rechercher un livre..." />
                <button type="submit" class="btn search-btn">Rechercher</button>
            </form>

            <form method="POST" class="filter-form">
                <select class="fi" name="categorie">
                    <option value="">Toutes les categories</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category['categorie']); ?>">
                            <?php echo htmlspecialchars($category['categorie']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button id='fi' type="submit" class="btn filter-btn">Filtrer</button>
            </form>
        </div>

        <section class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Nom du livre</th>
                        <th>Prix</th>
                        <th>Catégorie</th>
                        <th>Quantite disponible</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($livres as $livre): ?>
                    <tr>
                        <td><img src="<?php echo $livre['photo']; ?>" alt="Photo du livre" width="50"></td>
                        <td><?php echo htmlspecialchars($livre['nom']); ?></td>
                        <td><?php echo number_format($livre['prix'], 2); ?></td>
                        <td><?php echo htmlspecialchars($livre['categorie']); ?></td>
                        <td><?php echo $livre['quantite']; ?></td>
                        <td>
                            <a href="modifier.php?id=<?php echo $livre['id']; ?>"><button class="btn edit-btn">Modifier</button></a>
                            <a href="supprimer.php?id=<?php echo $livre['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce livre ?');"><button class="btn delete-btn">Supprimer</button></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
    <script> 
        document.getElementById("logoutButton").addEventListener("click", function() {
    window.location.href = "logoutadmin.php"; 
});

    </script>
</body>
</html>