<?php
require 'db/database.php';
include 'header.php';

if(isset($_POST['submit'])){
    $nom_bus = $_POST['nom_bus'];
    $point_arret = $_POST['point_arret'];
    $conducteur_nom = $_POST['conducteur_nom'];
    $conducteur_postnom = $_POST['conducteur_postnom'];
    $conducteur_prenom = $_POST['conducteur_prenom'];
    $conducteur_tel = $_POST['conducteur_tel'];

    if(strlen($conducteur_tel) != 10){
        $error = "Le numéro du conducteur doit contenir 10 chiffres";
    } else {
        $stmt = $pdo->prepare("INSERT INTO bus (nom_bus, point_arret, conducteur_nom, conducteur_postnom, conducteur_prenom, conducteur_tel) VALUES (?,?,?,?,?,?)");
        $stmt->execute([$nom_bus, $point_arret, $conducteur_nom, $conducteur_postnom, $conducteur_prenom, $conducteur_tel]);
        $success = "Bus ajouté avec succès !";
    }
}

$bus = $pdo->query("SELECT * FROM bus ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des bus</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- ================== SIDEBAR ================== -->
<?php include 'sidebar.php'; ?>

<header>Gestion des bus et chauffeurs</header>

<div class="container">

<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php if(isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

<h3>Ajouter un bus</h3>
<form method="POST">
    <input type="text" name="nom_bus" placeholder="Nom du bus *" required>
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
    <input type="text" name="conducteur_nom" placeholder="Nom du conducteur *" required>
    <input type="text" name="conducteur_postnom" placeholder="Postnom du conducteur">
    <input type="text" name="conducteur_prenom" placeholder="Prénom du conducteur *" required>
    <input type="text" name="conducteur_tel" placeholder="Téléphone conducteur *" required>
    <button type="submit" name="submit">Ajouter</button>
</form>

<h3>Liste des bus</h3>
<table>
<tr>
    <th>Bus</th>
    <th>Point d'arrêt</th>
    <th>Conducteur</th>
    <th>Téléphone</th>
</tr>
<?php foreach($bus as $b): ?>
<tr>
    <td><?= $b['nom_bus'] ?></td>
    <td><?= $b['point_arret'] ?></td>
    <td><?= $b['conducteur_nom'].' '.$b['conducteur_postnom'].' '.$b['conducteur_prenom'] ?></td>
    <td><?= $b['conducteur_tel'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
