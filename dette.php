<?php
require 'db/database.php';

// Récupérer les élèves ayant une dette
$stmt = $pdo->query("
    SELECT e.*, 
           a.id AS abo_id,
           a.montant, 
           a.date_paiement, 
           a.date_expiration, 
           a.dette
    FROM eleves e
    LEFT JOIN abonnements a ON e.id = a.eleve_id
    WHERE a.dette > 0
    ORDER BY a.dette DESC
");
$eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Élèves avec Dettes - ONG Transport</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        table {
            width: 98%;
            margin: 20px auto;
            border-collapse: collapse;
            font-size: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: center;
            white-space: nowrap;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .btn {
            padding: 5px 10px;
            background: #28a745;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn:hover { background: #1e7e34; }
        .dette {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <header>💰 Élèves ayant une dette</header>

    <h2>Liste des élèves en dette</h2>

    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Classe</th>
            <th>Montant payé</th>
            <th class="dette">Dette ($)</th>
            <th>Date paiement</th>
            <th>Expiration</th>
            <th>Action</th>
        </tr>

        <?php if (!empty($eleves)): ?>
            <?php foreach ($eleves as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['nom']) ?></td>
                <td><?= htmlspecialchars($e['prenom']) ?></td>
                <td><?= htmlspecialchars($e['classe']) ?></td>
                <td><?= $e['montant'] ?? 0 ?></td>
                <td class="dette"><?= $e['dette'] ?></td>
                <td><?= $e['date_paiement'] ?? '-' ?></td>
                <td><?= $e['date_expiration'] ?? '-' ?></td>
                <td>
                    <a class="btn" href="regler_dette.php?abo_id=<?= $e['abo_id'] ?>&eleve_id=<?= $e['id'] ?>">
                        Régler la dette
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8">✅ Aucun élève n’a de dette.</td></tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
