<?php
require 'db/database.php';

session_start(); // Pour utiliser les sessions pour les messages

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM eleves WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Élève supprimé avec succès !";
    } else {
        $_SESSION['error'] = "Élève non trouvé ou déjà supprimé.";
    }
} else {
    $_SESSION['error'] = "ID invalide.";
}

header("Location: liste_eleves.php");
exit();
?>
