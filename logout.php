<?php
session_start();
session_destroy(); // Détruit la session
header("Location: login.html"); // Redirige vers la page de connexion
exit();
?>