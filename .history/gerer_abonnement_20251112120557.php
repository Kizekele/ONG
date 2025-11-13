<!DOCTYPE html>
<html>
<head>
    <title>Gérer les abonnements</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>Gestion des abonnements</header>

<!-- ================== SIDEBAR ================== -->
<?php include 'sidebar.php'; ?>
<?php $abonnements = []; ?>

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
