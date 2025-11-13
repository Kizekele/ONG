<?php
require 'db/database.php';

// Récupérer tous les élèves avec info sur abonnements
$stmt = $pdo->query("
    SELECT e.*, 
           a.montant, a.date_paiement, a.date_expiration, a.dette
    FROM eleves e
    LEFT JOIN abonnements a ON e.id = a.eleve_id
    ORDER BY e.date_ajout DESC
");
$eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard ONG Transport</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            background: #f5f6fa;
        }

        /* ----- SIDEBAR ----- */
        .sidebar {
            width: 230px;
            height: 100vh;
            background: #1e3799;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 30px;
            position: fixed;
            left: 0;
            top: 0;
        }

        .sidebar h2 {
            font-size: 20px;
            margin-bottom: 30px;
            color: #f1f1f1;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            width: 80%;
            text-align: left;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .sidebar a:hover {
            background: #2e86de;
        }

        /* ----- CONTENU PRINCIPAL ----- */
        .main-content {
            margin-left: 230px; /* espace réservé à la sidebar */
            width: calc(100% - 230px);
            padding: 20px;
        }

        header {
            background: #2e86de;
            color: white;
            padding: 15px;
            border-radius: 5px;
            font-size: 22px;
            font-weight: bold;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: white;
            border-radius: 5px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background: #2e86de;
            color: white;
        }

        tr:nth-child(even) {
            background: #f2f2f2;
        }

        tr:hover {
            background: #dfe6e9;
        }

        a.btn {
            background: #2e86de;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }

        a.btn:hover {
            background: #1e3799;
        }
    </style>
</head>
<body>

    <!-- 🟦 SIDEBAR -->
    <div class="sidebar">
        <h2>🚍 ONG Transport</h2>
        <a href="index.php">🏠 Tableau de bord</a>
        <a href="ajouter_eleve.php">➕ Ajouter élève</a>
        <a href="gerer_abonnement.php">💳 Gérer abonnements</a>
        <a href="transport.php">🚌 Bus & Chauffeurs</a>
        <a href="rechercher.php">🔍 Rechercher</a>
        <a href="#">⚙️ Paramètres</a>
    </div>

    <!-- 🟨 CONTENU PRINCIPAL -->
    <div class="main-content">
        <header>Dashboard ONG Transport</header>

        <h2>Liste des élèves</h2>

        <table>
            <tr>
                <th>Nom</th>
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
                    <td><?= htmlspecialchars($e['prenom']) ?></td>
                    <td><?= htmlspecialchars($e['classe']) ?></td>
                    <td><?= $e['montant'] ?? 0 ?></td>
                    <td><?= $e['dette'] ?? 0 ?></td>
                    <td><?= $e['date_paiement'] ?? '-' ?></td>
                    <td><?= $e['date_expiration'] ?? '-' ?></td>
                    <td>
                        <a class="btn" href="ajouter_eleve.php?id=<?= $e['id'] ?>">Modifier</a>
                        <a class="btn" href="gerer_abonnement.php?eleve_id=<?= $e['id'] ?>">Abonnement</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8">Aucun élève trouvé.</td></tr>
            <?php endif; ?>
        </table>
    </div>

</body>
</html>
