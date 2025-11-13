<?php
require 'db/database.php';
include 'header.php';

if(isset($_GET['eleve_id'])){
    $eleve_id = $_GET['eleve_id'];

    // Récupérer l'élève
    $stmt = $pdo->prepare("SELECT * FROM eleves WHERE id=?");
    $stmt->execute([$eleve_id]);
    $eleve = $stmt->fetch(PDO::FETCH_ASSOC);
}

if(isset($_POST['submit'])){
    $eleve_id = $_POST['eleve_id'];
    $montant = $_POST['montant'];
    $date_paiement = $_POST['date_paiement'];
    $duree_mois = $_POST['duree'];
    $date_expiration = date('Y-m-d', strtotime("+$duree_mois months", strtotime($date_paiement)));
    $dette = $_POST['dette'];

    $stmt = $pdo->prepare("INSERT INTO abonnements (eleve_id, montant, date_paiement, date_expiration, dette) VALUES (?,?,?,?,?)");
    $stmt->execute([$eleve_id, $montant, $date_paiement, $date_expiration, $dette]);
    $success = "Abonnement ajouté avec succès !";
}

// Récupérer tous les abonnements
$abonnements = $pdo->query("
    SELECT a.*, e.nom, e.prenom 
    FROM abonnements a 
    JOIN eleves e ON a.eleve_id = e.id 
    ORDER BY a.date_paiement DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gérer les abonnements</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- ================== SIDEBAR ================== -->
    <php include 'sidebar.php'; ?>

<header>Gestion des abonnements</header>
<div class="container">

<?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

<?php if(isset($eleve)): ?>
<h3>Ajouter un abonnement pour <?= $eleve['nom'].' '.$eleve['prenom'] ?></h3>
<form method="POST">
    <input type="hidden" name="eleve_id" value="<?= $eleve['id'] ?>">
    <input type="number" name="montant" placeholder="Montant payé *" required>
    <input type="date" name="date_paiement" required>
    <input type="number" name="duree" placeholder="Durée (mois)" value="1" required>
    <input type="number" name="dette" placeholder="Dette (si existe)" value="0" required>
    <button type="submit" name="submit">Ajouter</button>
</form>
<?php endif; ?>

<h3>Liste des abonnements</h3>
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
    <td><?= $a['nom'].' '.$a['prenom'] ?></td>
    <td><?= $a['montant'] ?></td>
    <td><?= $a['dette'] ?></td>
    <td><?= $a['date_paiement'] ?></td>
    <td><?= $a['date_expiration'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
