<?php
require 'db/database.php';

header('Content-Type: text/html; charset=utf-8');

// Récupérer les données du bus si ID fourni
$bus = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM bus WHERE id = ?");
    $stmt->execute([$id]);
    $bus = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['submit'])) {
    $nom_bus = $_POST['nom_bus'];
    $point_arret = $_POST['point_arret'];
    $conducteur_nom = $_POST['conducteur_nom'];
    $conducteur_postnom = $_POST['conducteur_postnom'];
    $conducteur_prenom = $_POST['conducteur_prenom'];
    $conducteur_tel = $_POST['conducteur_tel'];
    $id = $_POST['id'];

    if (strlen($conducteur_tel) != 10) {
        $error = "Le numéro du conducteur doit contenir 10 chiffres";
    } else {
        $stmt = $pdo->prepare("UPDATE bus SET nom_bus = ?, point_arret = ?, conducteur_nom = ?, conducteur_postnom = ?, conducteur_prenom = ?, conducteur_tel = ? WHERE id = ?");
        $stmt->execute([$nom_bus, $point_arret, $conducteur_nom, $conducteur_postnom, $conducteur_prenom, $conducteur_tel, $id]);
        $success = "Bus modifié avec succès !";
        // Recharger les données après mise à jour
        $stmt = $pdo->prepare("SELECT * FROM bus WHERE id = ?");
        $stmt->execute([$id]);
        $bus = $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Modifier un Bus</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ================== SIDEBAR ================== -->
<?php include 'sidebar.php'; ?>

<!-- ================== CONTENU PRINCIPAL ================== -->
<div class="main-content">
    <header>Modifier un Bus</header>

    <div class="container">
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

        <?php if ($bus): ?>
        <form method="POST">
            <input type="hidden" name="id" value="<?= $bus['id'] ?>">
            <input type="text" name="nom_bus" placeholder="Nom du Bus *" value="<?= htmlspecialchars($bus['nom_bus']) ?>" required>
            <select name="point_arret">
                <option value="Kamutsha" <?= $bus['point_arret'] == 'Kamutsha' ? 'selected' : '' ?>>Kamutsha</option>
                <option value="Kilidja" <?= $bus['point_arret'] == 'Kilidja' ? 'selected' : '' ?>>Kilidja</option>
                <option value="Garage" <?= $bus['point_arret'] == 'Garage' ? 'selected' : '' ?>>Garage</option>
                <option value="Igota Nzambi" <?= $bus['point_arret'] == 'Igota Nzambi' ? 'selected' : '' ?>>Igota Nzambi</option>
                <option value="Musudja" <?= $bus['point_arret'] == 'Musudja' ? 'selected' : '' ?>>Musudja</option>
                <option value="Station Salongo" <?= $bus['point_arret'] == 'Station Salongo' ? 'selected' : '' ?>>Station Salongo</option>
                <option value="Route ByPass" <?= $bus['point_arret'] == 'Route ByPass' ? 'selected' : '' ?>>Route ByPass</option>
                <option value="Route Biangana" <?= $bus['point_arret'] == 'Route Biangana' ? 'selected' : '' ?>>Route Biangana</option>
            </select>
            <input type="text" name="conducteur_nom" placeholder="Nom du Conducteur *" value="<?= htmlspecialchars($bus['conducteur_nom']) ?>" required>
            <input type="text" name="conducteur_postnom" placeholder="Postnom du Conducteur" value="<?= htmlspecialchars($bus['conducteur_postnom']) ?>">
            <input type="text" name="conducteur_prenom" placeholder="Prénom du Conducteur *" value="<?= htmlspecialchars($bus['conducteur_prenom']) ?>" required>
            <input type="text" name="conducteur_tel" placeholder="Téléphone du Conducteur *" value="<?= htmlspecialchars($bus['conducteur_tel']) ?>" required>

            <button type="submit" name="submit">Modifier</button>
        </form>
        <?php else: ?>
        <p>Bus non trouvé.</p>
        <?php endif; ?>
    </div>
</div>

<script src="js/script.js"></script>
</body>
</html>
