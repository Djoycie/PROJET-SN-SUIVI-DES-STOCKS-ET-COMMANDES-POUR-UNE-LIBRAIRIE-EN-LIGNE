<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur</title>
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>
<body>

    <h2>Connexion Administrateur</h2>
    <form method="POST">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit" name="login">Se connecter</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Identifiants corrects
        $admin_username = "admin";
        $admin_password = "password";

        if ($username === $admin_username && $password === $admin_password) {
            echo "<script>showAlert('Authentification réussie !');window.location.href = 'testadmin.php';
            </script>";
            
        } else {
            echo "<script>showAlert('Identifiants incorrects.');</script>";
        }
    }
    ?>

</body>
</html>