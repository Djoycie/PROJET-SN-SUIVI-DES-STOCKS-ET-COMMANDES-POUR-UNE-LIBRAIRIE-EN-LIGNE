<?php
session_start();
$serverName = "DESKTOP-H147H0H\\SQLEXPRESS";
$database = "projetsn";
$username = "";
$password = "";

try {
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $search = isset($_POST['search']) ? trim($_POST['search']) : '';
    $category = isset($_POST['category']) ? trim($_POST['category']) : 'tous';

    // Début de la requête SQL
    $query = "SELECT * FROM Livres WHERE quantite > 0";
    $params = [];

    // Si une recherche est effectuée
    if (!empty($search)) {
        $query .= " AND (nom LIKE ? OR categorie LIKE ?)";
        $params[] = '%' . $search . '%';
        $params[] = '%' . $search . '%';
    }

    // Si une catégorie spécifique est sélectionnée
    if ($category !== 'tous') {
        $query .= " AND categorie = ?";
        $params[] = $category;
    }

    // Préparation de la requête
    $stmt = $conn->prepare($query);

    // Exécution avec les paramètres
    $stmt->execute($params);
    $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($livres) > 0) {
        foreach ($livres as $livre) {
            echo "<tr>
                <td><img src='" . htmlspecialchars($livre['photo']) . "' width='50'></td>
                <td>" . htmlspecialchars($livre['nom']) . "</td>
                <td>" . htmlspecialchars($livre['categorie']) . "</td>
                <td>" . number_format($livre['prix'], 2) . "fcfa</td>
                <td>
                    <input type='number' min='1' max='" . $livre['quantite'] . "' value='1' id='quantite-" . $livre['id'] . "'>
                </td>
                <td>
                    <button class='add-to-cart' 
                        data-id='" . $livre['id'] . "' 
                        data-nom='" . $livre['nom'] . "' 
                        data-prix='" . $livre['prix'] . "' 
                        data-photo='" . $livre['photo'] . "' 
                        data-stock='" . $livre['quantite'] . "'>
                        Ajouter au panier
                    </button>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>Aucun livre trouvé.</td></tr>";
    }

} catch (PDOException $e) {
    echo "Erreur SQL : " . $e->getMessage();
}
?>