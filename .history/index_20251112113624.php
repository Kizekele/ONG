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

</head>
<body>

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- CONTENU PRINCIPAL -->
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
