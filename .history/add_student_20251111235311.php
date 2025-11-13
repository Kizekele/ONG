<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // récupération et sanitation simple
    $nom = trim($_POST['nom']);
    $postnom = trim($_POST['postnom']);
    $prenom = trim($_POST['prenom']);
    $avenue = trim($_POST['avenue']);
    $quartier = $_POST['quartier'];
    $commune = $_POST['commune'];
    $ecole = trim($_POST['ecole']);
    $cycle = $_POST['cycle'];
    $classe = $_POST['classe'];
    $age = intval($_POST['age']);
    $sexe = $_POST['sexe'];
    $point_arret = $_POST['point_arret'];
    $parent_nom = trim($_POST['parent_nom']);
    $parent_phone = trim($_POST['parent_phone']);

    // Validation serveur
    $errors = [];
    if (strlen($parent_phone) != 10 || !ctype_digit($parent_phone)) {
        $errors[] = "Le numéro du parent doit contenir exactement 10 chiffres.";
    }
    if (empty($nom) || empty($prenom)) {
        $errors[] = "Nom et prénom obligatoires.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO students (nom, postnom, prenom, avenue, quartier, commune, ecole, cycle, classe, age, sexe, point_arret, parent_nom, parent_phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $postnom, $prenom, $avenue, $quartier, $commune, $ecole, $cycle, $classe, $age, $sexe, $point_arret, $parent_nom, $parent_phone]);

        $student_id = $pdo->lastInsertId();
        // Créer entry subscription par défaut
        $pdo->prepare("INSERT INTO subscriptions (student_id, expiration_date, debt) VALUES (?, NULL, 0)")->execute([$student_id]);

        header("Location: students_list.php?added=1");
        exit;
    } else {
        // afficher erreurs
    }
}
?>
