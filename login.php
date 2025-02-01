<?php
session_start(); // Démarrage de la session

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
        $passwordInput = $_POST['password'];

        // Exécution de la procédure stockée pour récupérer le mot de passe haché
        $stmt = $conn->prepare("EXEC VerifierClient :nom");
        $stmt->bindParam(':nom', $nom);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $hashedPassword = $result['mot_de_passe'];

            // Vérification du mot de passe
            if (password_verify($passwordInput, $hashedPassword)) {
                $_SESSION['nom'] = $nom; // Stocker le nom en session
                echo "<script>alert('Connexion réussie !'); window.location.href='pageaccueil.php';</script>";
            } else {
                echo "<script>alert('Nom ou mot de passe incorrect.');window.location.href='login.html';</script>";
            }
        } else {
            echo "<script>alert('Nom ou mot de passe incorrect.');window.location.href='login.html';</script>";
        }

    } catch (PDOException $e) {
        echo "<script>alert('Erreur : " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>