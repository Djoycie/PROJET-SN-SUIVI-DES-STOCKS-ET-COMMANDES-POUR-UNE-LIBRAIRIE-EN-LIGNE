<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prenom = trim($_POST["prenom"]);
    $nom = trim($_POST["nom"]);
    $message = trim($_POST["message"]);

    if (!empty($prenom) && !empty($nom) && !empty($message)) {
        try {
            $conn = new PDO("sqlsrv:server= DESKTOP-H147H0H\SQLEXPRESS;database=projetsn", "", "");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql = "{CALL InsertCommentaires(:prenom, :nom, :message)}";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":prenom", $prenom);
            $stmt->bindParam(":nom", $nom);
            $stmt->bindParam(":message", $message);

            if ($stmt->execute()) {
                echo "<script>alert('Message envoyé avec succès');window.location.href='pageaccueil.php';</script>";
            } else {
                echo "<script>alert('Erreur lors de l\'envoi du message');</script>";
            }
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    } else {
        echo "<script>alert('Tous les champs sont obligatoires');</script>";
    }
}
?>