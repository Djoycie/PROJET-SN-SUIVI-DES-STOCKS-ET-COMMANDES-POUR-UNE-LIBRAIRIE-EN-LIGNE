<?php
if (isset($_POST['categorie'])) {
    $category = $_POST['categorie'];
    $stmt = $conn->prepare("EXEC filtrer_livre_par_categorie :categorie");
    $stmt->execute(['categorie' => $category]);
    $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>