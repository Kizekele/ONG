<?php
require 'db/database.php';

// Récupérer tous les bus
$stmt = $pdo->query("SELECT * FROM bus ORDER BY id DESC");
$bus = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Bus & Chauffeurs - ONG Transport</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .main-content {
            margin-left: 220px; /* pour laisser la place à la sidebar */
            padding: 20px;
            background: #f5f6fa;
            min-height: 100vh;
        }

        header {
            font-size: 24px;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 98%;
            margin: 10px auto;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
            white-space: nowrap;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #eaf2ff;
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- CONTENU PRINCIPAL -->
    <div class="main-content">
        <header>🚌 Liste des Bus & Chauffeurs</header>

        <h2>Informations sur les bus enregistrés</h2>

        <?php if (!empty($bus)): ?>
        <table>
            <tr>
                <th>Nom du Bus</th>
                <th>Point d’arrêt</th>
                <th>Nom du Conducteur</th>
                <th>Postnom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
            </tr>

            <?php foreach ($bus as $b): ?>
            <tr>
                <td><?= htmlspecialchars($b['nom_bus']) ?></td>
                <td><?= htmlspecialchars($b['point_arret']) ?></td>
                <td><?= htmlspecialchars($b['conducteur_nom']) ?></td>
                <td><?= htmlspecialchars($b['conducteur_postnom']) ?></td>
                <td><?= htmlspecialchars($b['conducteur_prenom']) ?></td>
                <td><?= htmlspecialchars($b['conducteur_tel']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p>Aucun bus enregistré dans la base de données.</p>
        <?php endif; ?>
    </div>

</body>
</html>
