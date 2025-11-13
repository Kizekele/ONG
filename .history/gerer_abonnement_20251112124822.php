<?php
require 'db/database.php';

$eleve = null;
$abonnements = [];
$success = '';
$prix_mois = 25; // prix d'un mois

if(isset($_GET['eleve_id'])){
    $eleve_id = $_GET['eleve_id'];
    $stmt = $pdo->prepare("SELECT * FROM eleves WHERE id=?");
    $stmt->execute([$eleve_id]);
    $eleve = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(isset($_POST['submit'])){
    $eleve_id = $_POST['eleve_id'];
    $montant = floatval($_POST['montant']);
    $date_paiement = $_POST['date_paiement'];

    // Calcul du nombre de mois et de la dette
    $mois_paye = floor($montant / $prix_mois);
    $reste = $montant - ($mois_paye * $prix_mois);
    $dette = 0;

    if($reste < $prix_mois && $reste > 0){
        $mois_paye += 1;
        $dette = $prix_mois - $reste;
    } elseif($reste < 0){
        $dette = abs($reste);
    }

    // Vérifier si l'élève a déjà un abonnement
    $stmt = $pdo->prepare("SELECT * FROM abonnements WHERE eleve_id=? ORDER BY date_expiration DESC LIMIT 1");
    $stmt->execute([$eleve_id]);
    $abonnement = $stmt->fetch(PDO::FETCH_ASSOC);

    if($abonnement){
        // Mettre à jour l'abonnement existant
        $nouvelle_expiration = date('Y-m-d', strtotime("+$mois_paye months", strtotime($abonnement['date_expiration'])));
        $nouveau_montant = $abonnement['montant'] + $montant;
        $nouvelle_dette = $abonnement['dette'] + $dette;

        $update = $pdo->prepare("UPDATE abonnements SET montant=?, date_paiement=?, date_expiration=?, dette=? WHERE id=?");
        $update->execute([$nouveau_montant, $date_paiement, $nouvelle_expiration, $nouvelle_dette, $abonnement['id']]);
        $success = "Abonnement prolongé avec succès !";
    } else {
        // Créer un nouvel abonnement
        $date_expiration = date('Y-m-d', strtotime("+$mois_paye months", strtotime($date_paiement)));
        $insert = $pdo->prepare("INSERT INTO abonnements (eleve_id, montant, date_paiement, date_expiration, dette) VALUES (?,?,?,?,?)");
        $insert->execute([$eleve_id, $montant, $date_paiement, $date_expiration, $dette]);
        $success = "Abonnement ajouté avec succès !";
    }
}

// Récupérer tous les abonnements
$stmt = $pdo->query("
    SELECT a.*, e.nom, e.prenom, e.classe
    FROM abonnements a 
    JOIN eleves e ON a.eleve_id = e.id 
    ORDER BY a.date_paiement DESC
");
if($stmt){
    $abonnements = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Gérer les abonnements</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ================== SIDEBAR ================== -->
<?php include 'sidebar.php'; ?>

<div class="main-content">
    <header>Gestion des abonnements</header>

    <div class="container">
        <?php if(!empty($success)) echo "<p style='color:green; font-weight:bold;'>$success</p>"; ?>

        <?php if(isset($eleve)): ?>
        <h3>Ajouter/Prolonger un abonnement pour <?= htmlspecialchars($eleve['nom'].' '.$eleve['prenom']) ?></h3>
        <form method="POST">
            <input type="hidden" name="eleve_id" value="<?= $eleve['id'] ?>">
            <input type="number" name="montant" placeholder="Montant payé *" required>
            <input type="date" name="date_paiement" required>
            <button type="submit" name="submit">Ajouter / Prolonger</button>
        </form>
        <?php endif; ?>

        <h3>Liste des abonnements</h3>
        <?php if(!empty($abonnements)): ?>
        <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Classe</th>
            <th>Montant payé</th>
            <th>Dette ($)</th>
            <th>Date paiement</th>
            <th>Expiration</th>
        </tr>
        <?php foreach($abonnements as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['nom']) ?></td>
            <td><?= htmlspecialchars($a['prenom']) ?></td>
            <td><?= htmlspecialchars($a['classe']) ?></td>
            <td><?= htmlspecialchars($a['montant']) ?> $</td>
            <td><?= htmlspecialchars($a['dette']) ?> $</td>
            <td><?= htmlspecialchars($a['date_paiement']) ?></td>
            <td><?= htmlspecialchars($a['date_expiration']) ?></td>
        </tr>
        <?php endforeach; ?>
        </table>
        <?php else: ?>
        <p>Aucun abonnement enregistré.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
