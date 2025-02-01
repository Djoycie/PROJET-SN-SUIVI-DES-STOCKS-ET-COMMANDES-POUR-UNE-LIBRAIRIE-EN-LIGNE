<?php
$serverName = "DESKTOP-H147H0H\SQLEXPRESS"; // ou "NomDuServeur\InstanceSQL"
$connectionOptions = [
    "Database" => "projetsn",
    "TrustServerCertificate" => true,
    "UID" => "", // Laisser vide pour l'authentification Windows
    "PWD" => ""  // Laisser vide pour l'authentification Windows
];

// Connexion à SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);

if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

echo "Connexion réussie avec l'authentification Windows !";

// Fermer la connexion
sqlsrv_close($conn);
?>