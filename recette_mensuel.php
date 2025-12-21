<?php
require 'db/database.php';

// Récupérer les paiements groupés par mois et élève
$stmt = $pdo->query("
    SELECT
        YEAR(a.date_paiement) AS annee,
        MONTH(a.date_paiement) AS mois,
        CONCAT(e.nom, ' ', e.prenom) AS eleve_nom,
        a.montant,
        a.date_paiement,
        a.date_expiration
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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recettes Mensuelles - ONG Transport</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background: #007bff;
            color: #fff;
        }

        tr:nth-child(even) {
            background: #f8f8f8;
        }

        header, h2 {
            margin-left: 10px;
        }

        .main-content {
            padding: 20px;
            margin-left: 250px;
        }

        .mois-section {
            margin-bottom: 30px;
        }

        .mois-title {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
            color: #007bff;
        }

        /* Actions bar (title + print button) */
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .print-btn {
            display: inline-block;
            background-color: #28a745;
            color: #fff;
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(0,0,0,0.12);
        }

        .print-btn:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }
    </style> 
</head>
<body>

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- CONTENU PRINCIPAL -->
    <div class="main-content">
        <header>Recettes Mensuelles</header>

        <div class="actions">
            <h2 style="margin:0;">Paiements par mois</h2>
            <a href="imprimer_recette.php" target="_blank" rel="noopener" class="print-btn">Imprimer</a>
        </div>

        <?php if (!empty($paiements_par_mois)): ?>
            <?php foreach ($paiements_par_mois as $mois => $paiements_mois): ?>
                <?php
                // Calculer le total pour ce mois
                $total_mois = array_sum(array_column($paiements_mois, 'montant'));
                ?>
                <div class="mois-section">
                    <div class="mois-title">Mois: <?= date('F Y', strtotime($mois . '-01')) ?> - Total: <?= htmlspecialchars($total_mois) ?> $</div>
                    <div class="table-container">
                        <table>
                            <tr>
                                <th>Élève</th>
                                <th>Montant payé ($)</th>
                                <th>Date de paiement / Expiration</th>
                            </tr>
                            <?php foreach ($paiements_mois as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['eleve_nom']) ?></td>
                                <td><?= htmlspecialchars($p['montant']) ?> $</td>
                                <td><?= htmlspecialchars($p['date_paiement']) ?> / <?= htmlspecialchars($p['date_expiration']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr style="font-weight: bold; background-color: #f0f0f0;">
                                <td colspan="2">Total du mois</td>
                                <td><?= htmlspecialchars($total_mois) ?> $</td>
                            </tr>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun paiement enregistré pour le moment.</p>
        <?php endif; ?>
    </div>

</body>
</html>
