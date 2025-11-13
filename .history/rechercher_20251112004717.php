<?php
require 'db/database.php';
$eleves = [];

if(isset($_GET['search'])){
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM eleves WHERE nom LIKE ? OR postnom LIKE ? OR prenom LIKE ? OR classe LIKE ?");
    $stmt->execute(["%$search%","%$search%","%$search%","%$search%"]);
    $eleves = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rechercher un élève</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>Rechercher un élève</header>
<div class="container">
    <form method="GET">
        <input type="text" name="search" placeholder="Nom, Postnom, Prénom ou Classe">
        <button type="submit">Rechercher</button>
    </form>

    <?php if($eleves): ?>
    <h3>Résultats</h3>
    <table>
        <tr>
            <th>Nom</th>
            <th>Postnom</th>
            <th>Prénom</th>
            <th>Classe</th>
            <th>Point d'arrêt</th>
            <th>Parent</th>
            <th>Téléphone</th>
        </tr>
        <?php foreach($eleves as $e): ?>
        <tr>
            <td><?= $e['nom'] ?></td>
            <td><?= $e['postnom'] ?></td>
            <td><?= $e['prenom'] ?></td>
            <td><?= $e['classe'] ?></td>
            <td><?= $e['point_arret'] ?></td>
            <td><?= $e['parent_nom'] ?></td>
            <td><?= $e['parent_tel'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
</div>
</body>
</html>
