<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serverName = "DESKTOP-H147H0H\SQLEXPRESS";
    $database = "projetsn"; 
    $username = "";
    $password = "";

    try {
        // Connexion à SQL Server
        $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupération des données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $telephone = $_POST['telephone'];
        $adresse = $_POST['adresse'];
        $motDePasse = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hachage du mot de passe

        // Appel de la procédure stockée
        $stmt = $conn->prepare("EXEC AjouterClient :nom, :prenom, :email, :telephone, :adresse, :motDePasse");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':motDePasse', $motDePasse);

        $stmt->execute();

        // Succès
        echo "<script>alert('Inscription réussie !'); window.location.href='login.html';</script>";

    } catch (PDOException $e) {
        // Gestion des erreurs
        echo "<script>alert('Erreur : " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>