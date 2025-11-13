<?php
require 'db/database.php';

$eleves = [];
$search = '';

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);

    $stmt = $pdo->prepare("
        SELECT e.*, 
               a.montant, a.date_paiement, a.date_expiration, a.dette
        FROM eleves e
        LEFT JOIN abonnements a ON e.id = a.eleve_id
        WHERE e.nom LIKE ? OR e.prenom LIKE ? OR e.classe LIKE ?
        ORDER BY e.date_ajout DESC
    ");
    $stmt->execute(["%$search%", "%$search%", "%$search%"]);
    $eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche Élève - ONG Transport</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- CONTENU PRINCIPAL -->
    <div class="main-content">
        <header>Recherche Élève</header>

        <form method="GET" style="margin-bottom: 20px;">
            <input type="text" name="search" placeholder="Nom, Prénom ou Classe" 
                   value="<?= htmlspecialchars($search) ?>" 
                   style="padding: 8px; width: 300px; border-radius: 5px; border: 1px solid #ccc;">
            <button type="submit" class="btn">Rechercher</button>
        </form>

        <h2>Résultats de la recherche</h2>

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
            <?php elseif (isset($_GET['search'])): ?>
                <tr><td colspan="8">Aucun élève trouvé pour "<strong><?= htmlspecialchars($search) ?></strong>".</td></tr>
            <?php else: ?>
                <tr><td colspan="8">Entrez un nom, prénom ou une classe pour commencer la recherche.</td></tr>
            <?php endif; ?>
        </table>
    </div>

</body>
</html>
