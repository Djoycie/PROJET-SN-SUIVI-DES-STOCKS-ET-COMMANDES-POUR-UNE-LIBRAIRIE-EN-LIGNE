<?php
session_start();
session_destroy(); // Détruit la session
header("Location: authentification.php"); // Redirige vers la page de connexion
exit();
?>