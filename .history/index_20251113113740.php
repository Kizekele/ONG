<?php
require 'db/database.php';

// Récupérer tous les élèves avec info sur abonnements
$stmt = $pdo->query("
    SELECT e.*, 
           a.montant, a.date_paiement, a.date_expiration, a.dette
    FROM eleves e
    LEFT JOIN abonnements a ON e.id = a.eleve_id
    ORDER BY e.nom ASC, e.postnom ASC, e.prenom ASC
");
$eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Menu principal</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Styles de base pour les boutons */
        .btn {
            display: inline-block;
            font-size: 14px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-right: 5px;
            text-align: center;
            min-width: 80px;
        }

        /* Bouton bleu pour modification */
        .btn.bleu {
            background-color: #007bff;
            color: #fff;
            border: 1px solid #007bff;
        }

        .btn.bleu:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
        }

        /* Bouton rouge pour abonnement */
        .btn.rouge {
            background-color: #FF2600;
            color: #fff;
            border: 1px solid #FF2600;
        }

        .btn.rouge:hover {
            background-color: #cc1f00;
            border-color: #cc1f00;
            transform: translateY(-2px);
        }

        /* Styles du tableau */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th, table td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: #0E0D0DFF;
            color: #fff;
            font-weight: 600;
        }

        table tr:hover {
            background-color: #f1f7ff;
        }

        .main-content {
            padding: 20px;
            margin-left: 250px; /* espace pour la sidebar */
        }

        header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        h2 {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- CONTENU PRINCIPAL -->
    <div class="main-content">
        <header>Menu principal</header>

        <h2>Liste des élèves</h2>

        <table>
            <tr>
                <th>Nom</th>
                <th>Postnom</th>
                <th>Prénom</th>
                <th>Classe</th>
                <th>Montant payé</th>
                <th>Dette ($)</th>
                <th>Date paiement</th>
                <th>Expiration</th>
                <th>Actions</th>
            </tr>

            <?php if (!empty($eleves)): ?>
                <?php foreach($eleves as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><?= htmlspecialchars($e['postnom']) ?></td>
                    <td><?= htmlspecialchars($e['prenom']) ?></td>
                    <td><?= htmlspecialchars($e['classe']) ?></td>
                    <td><?= $e['montant'] ?? 0 ?></td>
                    <td><?= $e['dette'] ?? 0 ?></td>
                    <td><?= $e['date_paiement'] ?? '-' ?></td>
                    <td><?= $e['date_expiration'] ?? '-' ?></td>
                    <td>
                        <a class="btn bleu" href="ajouter_eleve.php?id=<?= $e['id'] ?>">Modifier</a>
                        <a class="btn rouge" href="gerer_abonnement.php?eleve_id=<?= $e['id'] ?>">Abonnement</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9">Aucun élève trouvé.</td></tr>
            <?php endif; ?>
        </table>
    </div>

</body>
</html>
