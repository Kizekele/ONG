<?php
require_once "config.php"; // Inclut ta connexion PDO

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // --- Récupération sécurisée des données du formulaire ---
    $nom = $_POST['nom'] ?? '';
    $postnom = $_POST['postnom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $avenue = $_POST['avenue'] ?? '';
    $quartier = $_POST['quartier'] ?? '';
    $commune = $_POST['commune'] ?? '';
    $ecole = $_POST['ecole'] ?? '';
    $cycle = $_POST['cycle'] ?? '';
    $classe = $_POST['classe'] ?? '';
    $age = $_POST['age'] ?? null;
    $sexe = $_POST['sexe'] ?? '';
    $point_arret = $_POST['point_arret'] ?? '';
    $parent_nom = $_POST['parent_nom'] ?? '';
    $parent_phone = $_POST['parent_phone'] ?? '';

    try {
        // --- Requête préparée pour éviter les injections SQL ---
        $stmt = $pdo->prepare("INSERT INTO students 
            (nom, postnom, prenom, avenue, quartier, commune, ecole, cycle, classe, age, sexe, point_arret, parent_nom, parent_phone)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $nom, $postnom, $prenom, $avenue, $quartier, $commune, $ecole,
            $cycle, $classe, $age, $sexe, $point_arret, $parent_nom, $parent_phone
        ]);

        echo "<script>
                alert('✅ Élève ajouté avec succès !');
                window.location.href = 'add_student_form.html';
              </script>";
    } catch (PDOException $e) {
        echo "<p style='color:red;'>Erreur : " . $e->getMessage() . "</p>";
    }

} else {
    // Si la méthode n’est pas POST (ex: accès direct)
    header("HTTP/1.1 405 Method Not Allowed");
    echo "Méthode non autorisée.";
}
