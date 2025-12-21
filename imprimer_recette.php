<?php
require 'db/database.php';
require_once 'vendor/tecnickcom/tcpdf/tcpdf.php'; // Charger TCPDF directement

// Récupérer les paiements groupés par mois et élève
$stmt = $pdo->query("
    SELECT
        YEAR(a.date_paiement) AS annee,
        MONTH(a.date_paiement) AS mois,
        CONCAT(e.nom, ' ', e.prenom) AS eleve_nom,
        a.montant,
        a.date_paiement
    FROM abonnements a
    JOIN eleves e ON a.eleve_id = e.id
    ORDER BY a.date_paiement DESC, e.nom ASC
");
$paiements = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Grouper par mois
$paiements_par_mois = [];
foreach ($paiements as $p) {
    $cle = $p['annee'] . '-' . str_pad($p['mois'], 2, '0', STR_PAD_LEFT);
    if (!isset($paiements_par_mois[$cle])) {
        $paiements_par_mois[$cle] = [];
    }
    $paiements_par_mois[$cle][] = $p;
}

// Créer le PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('ONG Transport');
$pdf->SetTitle('Recettes Mensuelles');
$pdf->SetSubject('Rapport des paiements mensuels');
$pdf->SetKeywords('ONG, Transport, Recettes, Mensuelles');

// Ajouter une page
$pdf->AddPage();

// Titre
$pdf->SetFont('helvetica', 'B', 16);
$pdf->Cell(0, 10, 'Recettes Mensuelles - ONG Transport', 0, 1, 'C');
$pdf->Ln(10);

// Contenu
$pdf->SetFont('helvetica', '', 12);

if (!empty($paiements_par_mois)) {
    foreach ($paiements_par_mois as $mois => $paiements_mois) {
        // Calculer le total pour ce mois
        $total_mois = array_sum(array_column($paiements_mois, 'montant'));

        // Titre du mois
        $pdf->SetFont('helvetica', 'B', 14);
        $pdf->Cell(0, 10, 'Mois: ' . date('F Y', strtotime($mois . '-01')) . ' - Total: ' . $total_mois . ' $', 0, 1);
        $pdf->Ln(5);

        // Tableau
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(60, 7, 'Élève', 1);
        $pdf->Cell(40, 7, 'Montant payé ($)', 1);
        $pdf->Cell(40, 7, 'Date de paiement', 1);
        $pdf->Ln();

        $pdf->SetFont('helvetica', '', 10);
        foreach ($paiements_mois as $p) {
            $pdf->Cell(60, 6, $p['eleve_nom'], 1);
            $pdf->Cell(40, 6, $p['montant'] . ' $', 1);
            $pdf->Cell(40, 6, $p['date_paiement'], 1);
            $pdf->Ln();
        }

        // Total du mois
        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->Cell(100, 6, 'Total du mois', 1);
        $pdf->Cell(40, 6, $total_mois . ' $', 1);
        $pdf->Ln(10);
    }
} else {
    $pdf->Cell(0, 10, 'Aucun paiement enregistré pour le moment.', 0, 1);
}

// Sortie du PDF
$pdf->Output('recettes_mensuelles.pdf', 'D'); // Télécharger le fichier
?>
