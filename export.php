<?php
// Vérifier si l'exportation en PDF est demandée
if (isset($_GET['export']) && $_GET['export'] === 'pdf') {

    // Charger la bibliothèque TCPDF
    require_once('vendor/autoload.php');

    // Connexion à la base de données
    $serverName = "DESKTOP-H147H0H\SQLEXPRESS";
    $database = "projetsn"; 
    $username = "";
    $password = "";
    $conn = new PDO("sqlsrv:Server=$serverName;Database=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les livres depuis la base de données
    $stmt = $conn->prepare("SELECT * FROM dbo.Livres ORDER BY nom");
    $stmt->execute();
    $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Créer un nouveau document PDF
    $pdf = new TCPDF();
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Votre Application');
    $pdf->SetTitle('Inventaire des Livres');
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();
    $pdf->SetFont('helvetica', '', 10);

    // Titre du document
    $pdf->Cell(0, 10, 'Inventaire des Livres', 0, 1, 'C');

    // Ajouter une ligne vide
    $pdf->Ln(5);

    // Construire le tableau HTML
    $html = '<table border="1" cellpadding="5">
                <thead>
                    <tr style="background-color:#ddd;">
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Catégorie</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>';

    foreach ($livres as $livre) {
        $html .= '<tr>
                    <td>' . htmlspecialchars($livre['nom']) . '</td>
                    <td>' . number_format($livre['prix'], 2) . ' €</td>
                    <td>' . htmlspecialchars($livre['categorie']) . '</td>
                    <td>' . $livre['quantite'] . '</td>
                  </tr>';
    }

    $html .= '</tbody></table>';

    // Ajouter le tableau au PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Générer le fichier PDF et le télécharger
    $pdf->Output('inventaire_livres.pdf', 'D'); // 'D' pour téléchargement direct

    // Redirection après l'exportation (retour à la page principale)
    header("Location: testadmin.php"); // Assurez-vous que l'URL correspond à la page où vous souhaitez rediriger
    exit; // Terminer l'exécution du script après la redirection
}
?>