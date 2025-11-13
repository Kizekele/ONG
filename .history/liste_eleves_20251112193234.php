<?php
require 'db/database.php';

// Récupérer tous les élèves
$stmt = $pdo->query("
    SELECT id, nom, postnom, prenom, avenue, quartier, commune, ecole, cycle, classe, age, sexe, point_arret, parent_nom, parent_tel
    FROM eleves
    ORDER BY date_ajout DESC
");
$eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des élèves - ONG Transport</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- SIDEBAR -->
    <?php include 'sidebar.php'; ?>

    <!-- CONTENU PRINCIPAL -->
    <div class="main-content">
        <header>Liste complète des élèves</header>

        <h2>Informations détaillées des élèves</h2>

        <table>
            <tr>
                <th>Nom</th>
                <th>Postnom</th>
                <th>Prénom</th>
                <th>Avenue</th>
                <th>Quartier</th>
                <th>Commune</th>
                <th>École</th>
                <th>Cycle</th>
                <th>Classe</th>
                <th>Âge</th>
                <th>Sexe</th>
                <th>Point d’arrêt</th>
                <th>Parent</th>
                <th>Téléphone parent</th>
                <th>Actions</th>
            </tr>

            <?php if (!empty($eleves)): ?>
                <?php foreach($eleves as $e): ?>
                <tr>
                    <td><?= htmlspecialchars($e['nom']) ?></td>
                    <td><?= htmlspecialchars($e['postnom']) ?></td>
                    <td><?= htmlspecialchars($e['prenom']) ?></td>
                    <td><?= htmlspecialchars($e['avenue']) ?></td>
                    <td><?= htmlspecialchars($e['quartier']) ?></td>
                    <td><?= htmlspecialchars($e['commune']) ?></td>
                    <td><?= htmlspecialchars($e['ecole']) ?></td>
                    <td><?= htmlspecialchars($e['cycle']) ?></td>
                    <td><?= htmlspecialchars($e['classe']) ?></td>
                    <td><?= htmlspecialchars($e['age']) ?></td>
                    <td><?= htmlspecialchars($e['sexe']) ?></td>
                    <td><?= htmlspecialchars($e['point_arret']) ?></td>
                    <td><?= htmlspecialchars($e['parent_nom']) ?></td>
                    <td><?= htmlspecialchars($e['parent_tel']) ?></td>
                    <td>
                        <a class="btn" href="ajouter_eleve.php?id=<?= $e['id'] ?>">Modifier</a>
                        <a class="btn" href="supprimer_eleve.php?id=<?= $e['id'] ?>" onclick="return confirm('Supprimer cet élève ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="15">Aucun élève enregistré pour le moment.</td></tr>
            <?php endif; ?>
        </table>
    </div>

</body>
</html>
