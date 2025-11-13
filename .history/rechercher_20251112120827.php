<?php
require 'db/database.php';

$eleves = []; // Initialisation

// Recherche si le paramètre 'search' existe
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

<!-- ================== SIDEBAR ================== -->
<?php include 'sidebar.php'; ?>

<header>Rechercher un élève</header>

<div class="container">
    <form method="GET">
        <input type="text" name="search" placeholder="Nom, Postnom, Prénom ou Classe" value="<?= isset($search) ? htmlspecialchars($search) : '' ?>">
        <button type="submit">Rechercher</button>
    </form>

    <?php if(!empty($eleves)): ?>
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
                <td><?= htmlspecialchars($e['nom']) ?></td>
                <td><?= htmlspecialchars($e['postnom']) ?></td>
                <td><?= htmlspecialchars($e['prenom']) ?></td>
                <td><?= htmlspecialchars($e['classe']) ?></td>
                <td><?= htmlspecialchars($e['point_arret']) ?></td>
                <td><?= htmlspecialchars($e['parent_nom']) ?></td>
                <td><?= htmlspecialchars($e['parent_tel']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif(isset($search)): ?>
        <p>Aucun élève trouvé pour "<?= htmlspecialchars($search) ?>"</p>
    <?php endif; ?>
</div>

</body>
</html>
