<?php
require 'db/database.php';

header('Content-Type: text/html; charset=utf-8');

if (isset($_POST['submit'])) {
    $nom_bus = $_POST['nom_bus'];
    $point_arret = $_POST['point_arret'];
    $conducteur_nom = $_POST['conducteur_nom'];
    $conducteur_postnom = $_POST['conducteur_postnom'];
    $conducteur_prenom = $_POST['conducteur_prenom'];
    $conducteur_tel = $_POST['conducteur_tel'];

    if (strlen($conducteur_tel) != 10) {
        $error = "Le numéro du conducteur doit contenir 10 chiffres";
    } else {
        $stmt = $pdo->prepare("INSERT INTO bus (nom_bus, point_arret, conducteur_nom, conducteur_postnom, conducteur_prenom, conducteur_tel)
                               VALUES (?,?,?,?,?,?)");
        $stmt->execute([$nom_bus, $point_arret, $conducteur_nom, $conducteur_postnom, $conducteur_prenom, $conducteur_tel]);
        $success = "Bus ajouté avec succès !";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ajouter un Bus</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ================== SIDEBAR ================== -->
<?php include 'sidebar.php'; ?>

<!-- ================== CONTENU PRINCIPAL ================== -->
<div class="main-content">
    <header>Ajouter un Bus</header>

    <div class="container">
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

        <form method="POST">
            <input type="text" name="nom_bus" placeholder="Nom du Bus *" required>
            <select name="point_arret">
                <option value="Kamutsha">Kamutsha</option>
                <option value="Kilidja">Kilidja</option>
                <option value="Garage">Garage</option>
                <option value="Igota Nzambi">Igota Nzambi</option>
                <option value="Musudja">Musudja</option>
                <option value="Station Salongo">Station Salongo</option>
                <option value="Route ByPass">Route ByPass</option>
                <option value="Route Biangana">Route Biangana</option>
            </select>
            <input type="text" name="conducteur_nom" placeholder="Nom du Conducteur *" required>
            <input type="text" name="conducteur_postnom" placeholder="Postnom du Conducteur">
            <input type="text" name="conducteur_prenom" placeholder="Prénom du Conducteur *" required>
            <input type="text" name="conducteur_tel" placeholder="Téléphone du Conducteur *" required>

            <button type="submit" name="submit">Ajouter</button>
        </form>
    </div>
</div>

<script src="js/script.js"></script>
</body>
</html>
