<?php
require 'db/database.php';

$eleves = [];
$success = '';
$prix_mois = 25; // Prix d'un mois

// --- Recherche d'élèves ---
if (isset($_GET['search'])) {
    $search = $_GET['search'];
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

// --- Ajout ou prolongation d'abonnement ---
if (isset($_POST['submit'])) {
    $eleve_id = $_POST['eleve_id'];
    $montant = floatval($_POST['montant']);
    $date_paiement = $_POST['date_paiement'];

    $mois_paye = floor($montant / $prix_mois);
    $reste = $montant - ($mois_paye * $prix_mois);
    $dette = 0;

    if ($reste < $prix_mois && $reste > 0) {
        $mois_paye += 1;
        $dette = $prix_mois - $reste;
    } elseif ($reste < 0) {
        $dette = abs($reste);
    }

    // Vérifie si l'élève a déjà un abonnement
    $stmt = $pdo->prepare("SELECT * FROM abonnements WHERE eleve_id=? ORDER BY date_expiration DESC LIMIT 1");
    $stmt->execute([$eleve_id]);
    $abonnement = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($abonnement) {
        // Prolonger l'abonnement existant
        $nouvelle_expiration = date('Y-m-d', strtotime("+$mois_paye months", strtotime($abonnement['date_expiration'])));
        $nouveau_montant = $abonnement['montant'] + $montant;
        $nouvelle_dette = $abonnement['dette'] + $dette;

        $update = $pdo->prepare("UPDATE abonnements SET montant=?, date_paiement=?, date_expiration=?, dette=? WHERE id=?");
        $update->execute([$nouveau_montant, $date_paiement, $nouvelle_expiration, $nouvelle_dette, $abonnement['id']]);
        $success = "✅ Abonnement prolongé avec succès !";
    } else {
        // Créer un nouvel abonnement
        $date_expiration = date('Y-m-d', strtotime("+$mois_paye months", strtotime($date_paiement)));
        $insert = $pdo->prepare("INSERT INTO abonnements (eleve_id, montant, date_paiement, date_expiration, dette) VALUES (?,?,?,?,?)");
        $insert->execute([$eleve_id, $montant, $date_paiement, $date_expiration, $dette]);
        $success = "✅ Nouvel abonnement ajouté avec succès !";
    }

    // Rafraîchir la liste après ajout
    header("Location: rechercher.php?search=");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Rechercher / Gérer abonnements</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f6fa; margin:0; }
        .container { width: 90%; margin: 30px auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px 12px; text-align: left; }
        th { background: #3498db; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        input[type="text"], input[type="number"], input[type="date"] {
            padding: 8px; border: 1px solid #ccc; border-radius: 5px;
        }
        button {
            padding: 8px 15px; background: #3498db; color: white;
            border: none; border-radius: 5px; cursor: pointer;
        }
        button:hover { background: #2980b9; }
        form { margin-top: 15px; }
        .success { color: green; font-weight: bold; }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <header><h2>Rechercher / Gérer les abonnements</h2></header>

    <div class="container">
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>

        <form method="GET">
            <input type="text" name="search" placeholder="Nom, Prénom ou Classe"
                   value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
            <button type="submit">🔍 Rechercher</button>
        </form>

        <?php if (!empty($eleves)): ?>
            <h3>Résultats de recherche</h3>
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
                    <td><?= htmlspecialchars($e['montant'] ?? '0') ?></td>
                    <td><?= htmlspecialchars($e['dette'] ?? '0') ?></td>
                    <td><?= htmlspecialchars($e['date_paiement'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($e['date_expiration'] ?? '-') ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="eleve_id" value="<?= $e['id'] ?>">
                            <input type="number" name="montant" placeholder="Montant $" step="0.01" required>
                            <input type="date" name="date_paiement" required>
                            <button type="submit" name="submit">+ Ajouter / Prolonger</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif (isset($search)): ?>
            <p>Aucun élève trouvé pour "<strong><?= htmlspecialchars($search) ?></strong>".</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
