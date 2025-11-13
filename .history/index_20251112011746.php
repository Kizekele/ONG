<?php
require 'db/database.php';
include 'header.php';

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
<html>
    <head>
        <title>Dashboard ONG Transport</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
    <header>Dashboard ONG Transport</header>
    <div class="container">
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
            <?php foreach($eleves as $e): ?>
            <tr>
                <td><?= $e['nom'] ?></td>
                <td><?= $e['prenom'] ?></td>
                <td><?= $e['classe'] ?></td>
                <td><?= $e['montant'] ?? 0 ?></td>
                <td><?= $e['dette'] ?? 0 ?></td>
                <td><?= $e['date_paiement'] ?? '-' ?></td>
                <td><?= $e['date_expiration'] ?? '-' ?></td>
                <td>
                    <a href="ajouter_eleve.php?id=<?= $e['id'] ?>">Modifier</a> | 
                    <a href="gerer_abonnement.php?eleve_id=<?= $e['id'] ?>">Abonnement</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    </body>
</html>
