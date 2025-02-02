<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #3498db; /* Bleu ciel */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            text-align: left;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        .show-password {
            display: flex;
            align-items: center;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .show-password input {
            width: auto;
            margin-right: 5px;
        }
        button {
            background-color:#3498db;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        button:hover {
            background-color: #006f8b;
        }
        .links {
            margin-top: 15px;
            font-size: 14px;
        }
        .links a {
            color: #0082a3;
            text-decoration: none;
            font-weight: bold;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function showAlert(message) {
            alert(message);
        }
        function togglePassword() {
            var passwordField = document.getElementById("password");
            passwordField.type = passwordField.type === "password" ? "text" : "password";
        }
    </script>
</head>
<body>

    <div class="login-container">
        <h2>Connexion Administrateur</h2>
        <form method="POST">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password" required>

            <div class="show-password">
                <input type="checkbox" onclick="togglePassword()"> Afficher le mot de passe
            </div>

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
                echo "<script>showAlert('Authentification réussie !');window.location.href = 'testadmin.php';</script>";
            } else {
                echo "<script>showAlert('Identifiants incorrects.');</script>";
            }
        }
        ?>
    </div>

</body>
</html>