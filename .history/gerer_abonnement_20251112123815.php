<?php
require 'db/database.php';

$eleve = null;
$abonnements = [];
$success = '';

// Prix d'un mois
$prix_mois = 25;

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
    $mois_paye = floor($montant / $prix_mois); // nombre de mois complets
    $reste = $montant - ($mois_paye * $prix_mois); // reste payé
    $dette = 0;

    if($reste < $prix_mois && $reste > 0){
        $mois_paye += 1; // on ajoute 1 mois même si paiement incomplet
        $dette = $prix_mois - $reste;
    } elseif($reste < 0){
        $dette = abs($reste); // montant insuffisant
    }

    // Vérifier si l'élève a déjà un abonnement actif
    $stmt = $pdo->prepare("SELECT * FROM abonnements WHERE eleve_id=? ORDER BY date_expiration DESC LIMIT 1");
    $stmt->execute([$eleve_id]);
    $dernier_abonnement = $stmt->fetch(PDO::FETCH_ASSOC);

    if($dernier_abonnement && strtotime($dernier_abonnement['date_expiration']) >= strtotime($date_paiement)){
        // L'abonnement est encore actif → prolongation
        $date_expiration = date('Y-m-d', strtotime("+$mois_paye months", strtotime($dernier_abonnement['date_expiration'])));
    } else {
        // Nouvel abonnement
        $date_expiration = date('Y-m-d', strtotime("+$mois_paye months", strtotime($date_paiement)));
    }

    // Ajouter l'abonnement
    $stmt = $pdo->prepare("INSERT INTO abonnements (eleve_id, montant, date_paiement, date_expiration, dette) VALUES (?,?,?,?,?)");
    $stmt->execute([$eleve_id, $montant, $date_paiement, $date_expiration, $dette]);
    $success = "Abonnement ajouté avec succès !";
}

// Récupérer tous les abonnements
$stmt = $pdo->query("
    SELECT a.*, e.nom, e.prenom 
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

<!-- ================== CONTENU PRINCIPAL ================== -->
<div class="main-content">
    <header>Gestion des abonnements</header>

    <div class="container">
        <?php if(!empty($success)) echo "<p style='color:green; font-weight:bold;'>$success</p>"; ?>

        <?php if(isset($eleve)): ?>
        <h3>Ajouter un abonnement pour <?= htmlspecialchars($eleve['nom'].' '.$eleve['prenom']) ?></h3>
        <form method="POST">
            <input type="hidden" name="eleve_id" value="<?= $eleve['id'] ?>">
            <input type="number" name="montant" placeholder="Montant payé *" required>
            <input type="date" name="date_paiement" required>
            <button type="submit" name="submit">Ajouter</button>
        </form>
        <?php endif; ?>

        <h3>Liste des abonnements</h3>
        <?php if(!empty($abonnements)): ?>
        <table>
        <tr>
            <th>Élève</th>
            <th>Montant payé</th>
            <th>Dette</th>
            <th>Date paiement</th>
            <th>Expiration</th>
        </tr>
        <?php foreach($abonnements as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['nom'].' '.$a['prenom']) ?></td>
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
