<?php
require 'db/database.php';

header('Content-Type: text/html; charset=utf-8');

if (isset($_POST['submit'])) {
    $nom = $_POST['nom'];
    $postnom = $_POST['postnom'];
    $prenom = $_POST['prenom'];
    $avenue = $_POST['avenue'];
    $quartier = $_POST['quartier'];
    $commune = $_POST['commune'];
    $ecole = $_POST['ecole'];
    $cycle = $_POST['cycle'];
    $classe = $_POST['classe'];
    $age = $_POST['age'];
    $sexe = $_POST['sexe'];
    $point_arret = $_POST['point_arret'];
    $parent_nom = $_POST['parent_nom'];
    $parent_tel = $_POST['parent_tel'];

    if (strlen($parent_tel) != 10) {
        $error = "Le numéro du parent doit contenir 10 chiffres";
    } else {
        $stmt = $pdo->prepare("INSERT INTO eleves (nom, postnom, prenom, avenue, quartier, commune, ecole, cycle, classe, age, sexe, point_arret, parent_nom, parent_tel) 
                               VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$nom, $postnom, $prenom, $avenue, $quartier, $commune, $ecole, $cycle, $classe, $age, $sexe, $point_arret, $parent_nom, $parent_tel]);
        $success = "Élève ajouté avec succès !";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>➕ Ajouter un élève</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ================== SIDEBAR ================== -->
<div class="sidebar">
    <h2>ONG Transport</h2>
    <a href="index.php">Dashboard</a>
    <a href="ajouter_eleve.php" class="active">Ajouter Élève</a>
    <a href="gerer_abonnement.php">Gérer Abonnements</a>
    <a href="transport.php">Transport</a>
    <a href="rechercher.php">Rechercher</a>
</div>

<!-- ================== CONTENU PRINCIPAL ================== -->
<div class="main-content">
    <header>Ajouter un élève</header>

    <div class="container">
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

        <form method="POST">
            <input type="text" name="nom" placeholder="Nom *" required>
            <input type="text" name="postnom" placeholder="Postnom">
            <input type="text" name="prenom" placeholder="Prénom *" required>
            <input type="text" name="avenue" placeholder="Avenue">

            <select name="quartier">
                <option value="Salongo">Salongo</option>
                <option value="Gombele">Gombele</option>
                <option value="Madrendele">Madrendele</option>
            </select>

            <select name="commune">
                <option value="Lemba">Lemba</option>
                <option value="Matete">Matete</option>
                <option value="Ngaba">Ngaba</option>
            </select>

            <input type="text" name="ecole" placeholder="École">

            <select name="cycle" id="cycle">
                <option value="Maternelle">Maternelle</option>
                <option value="Primaire">Primaire</option>
                <option value="Secondaire">Secondaire</option>
                <option value="Humanité">Humanité</option>
            </select>

            <select name="classe" id="classe">
                <option value="">Sélectionner une classe</option>
            </select>

            <input type="number" name="age" placeholder="Âge *" required>

            <select name="sexe">
                <option value="M">M</option>
                <option value="F">F</option>
            </select>

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

            <input type="text" name="parent_nom" placeholder="Nom du parent">
            <input type="text" name="parent_tel" placeholder="Téléphone du parent *" required>

            <button type="submit" name="submit">Ajouter</button>
        </form>
    </div>
</div>

<script src="js/script.js"></script>
</body>
</html>
