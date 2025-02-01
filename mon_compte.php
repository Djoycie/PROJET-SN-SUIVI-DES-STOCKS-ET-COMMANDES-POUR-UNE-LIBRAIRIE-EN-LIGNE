<?php
session_start();
$serverName = "DESKTOP-H147H0H\\SQLEXPRESS";
$database = "projetsn";
$username = "";
$password = "";
$conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION['id_client'])) {
    header("Location: login.html");
    exit();
}

$id_client = $_SESSION['id_client'];

// Récupérer les informations du client
$stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");
$stmt->execute([$id_client]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Compte</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Informations du compte</h2>
    <form id="formCompte">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>

        <label>Email :</label>
        <input type="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>

        <label>Téléphone :</label>
        <input type="text" name="telephone" value="<?= htmlspecialchars($client['telephone']) ?>" required>

        <label>Adresse :</label>
        <input type="text" name="adresse" value="<?= htmlspecialchars($client['adresse']) ?>" required>

        <input type="hidden" name="id" value="<?= $id_client ?>">

        <button type="button" id="modifier">Modifier</button>
        <button type="submit" id="valider" style="display:none;">Valider les modifications</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $("#modifier").click(function() {
            $("input").prop("disabled", false);
            $("#valider").show();
            $(this).hide();
        });

        $("#formCompte").submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "update_compte.php",
                data: $(this).serialize(),
                success: function(response) {
                    alert(response);
                    location.reload();
                }
            });
        });
    </script>
</body>
</html>