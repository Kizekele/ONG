<?php
require 'db/database.php';

$eleves = [];
$search = '';
$success = '';

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);

    $stmt = $pdo->prepare("
        SELECT 
            e.id,
            e.nom,
            e.prenom,
            e.classe,
            a.montant,
            a.dette,
            a.date_paiement,
            a.date_expiration
        FROM eleves e
        LEFT JOIN abonnements a ON e.id = a.eleve_id
        WHERE e.nom LIKE ? OR e.prenom LIKE ? OR e.classe LIKE ?
        ORDER BY e.nom ASC
    ");
    $stmt->execute(["%$search%", "%$search%", "%$search%"]);
    $eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Rechercher un élève</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f6fa;
            margin: 0;
        }
        .main-content {
            padding: 20px;
        }
        .container {
            width: 90%;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: left;
        }
        th {
            background: #3498db;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        input[type="text"] {
            padding: 8px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 8px 15px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #2980b9;
        }
        header h2 {
            margin: 0 0 20px 0;
        }
        .action-btn {
            text-decoration: none;
            background: #27ae60;
            color: white;
            padding: 6px 10px;
            border-radius: 5px;
        }
        .action-btn:hover {
            background: #219150;
        }
    </style>
</head>
<body>

<!-- ================== SIDEBAR ================== -->
<?php include 'sidebar.php'; ?>

<!-- ================== CONTENU PRINCIPAL ================== -->
<div class="main-content">
    <header><h2>🔍 Rechercher un élève</h2></header>

    <div class="container">
        <form method="GET">
            <input type="text" name="search" placeholder="Nom, Prénom ou Classe"
                   value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Rechercher</button>
        </form>

        <?php if (!empty($eleves)): ?>
            <h3>Résultats</h3>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Classe</th>
                    <th>Montant payé ($)</th>
                    <th>Dette ($)</th>
                    <th>Date paiement</th>
                    <th>Expiration</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($eleves as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><?= htmlspecialchars($e['prenom']) ?></td>
                    <td><?= htmlspecialchars($e['classe']) ?></td>
                    <td><?= number_format($e['montant'] ?? 0, 2) ?></td>
                    <td><?= number_format($e['dette'] ?? 0, 2) ?></td>
                    <td><?= htmlspecialchars($e['date_paiement'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($e['date_expiration'] ?? '-') ?></td>
                    <td>
                        <a class="action-btn" href="modifier_abonnement.php?eleve_id=<?= $e['id'] ?>">Modifier Abonnement</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif (isset($_GET['search'])): ?>
            <p>Aucun élève trouvé pour "<strong><?= htmlspecialchars($search) ?></strong>".</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
