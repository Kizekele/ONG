<?php
require 'db/database.php';

if (!isset($_GET['abo_id']) || !isset($_GET['eleve_id'])) {
    exit("Erreur : données manquantes.");
}

$abo_id = $_GET['abo_id'];
$eleve_id = $_GET['eleve_id'];

// Récupérer abonnement + élève
$stmt = $pdo->prepare("
    SELECT e.*, a.*
    FROM abonnements a
    JOIN eleves e ON e.id = a.eleve_id
    WHERE a.id = ?
");
$stmt->execute([$abo_id]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    exit("Abonnement introuvable.");
}

$dette = $data['dette'];
$message = "";

// Si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $montant_paye = floatval($_POST['montant_paye']);

    if ($montant_paye <= 0) {
        $message = "❌ Le montant doit être supérieur à zéro.";
    } else {

        if ($montant_paye == $dette) {
            // DETTE PAYÉE EXACTEMENT
            $message = "✅ Dette réglée totalement.";

            $pdo->prepare("UPDATE abonnements SET dette = 0 WHERE id = ?")
                ->execute([$abo_id]);

        } elseif ($montant_paye < $dette) {
            // DETTE PARTIELLE
            $nouvelle_dette = $dette - $montant_paye;
            $message = "⚠️ Dette payée partiellement. Nouvelle dette : $nouvelle_dette $.";

            $pdo->prepare("UPDATE abonnements SET dette = ? WHERE id = ?")
                ->execute([$nouvelle_dette, $abo_id]);

        } else {
            // SURPLUS
            $reste = $montant_paye - $dette;

            $message = "🎉 Dette totalement payée. Surplus : $reste $.<br>";
            $message .= "Un nouvel abonnement a été enregistré.";

            // 1. effacer dette
            $pdo->prepare("UPDATE abonnements SET dette = 0 WHERE id = ?")
                ->execute([$abo_id]);

            // 2. nouvel abonnement
            $date_paiement = date("Y-m-d");
            $expiration = date("Y-m-d", strtotime("+1 month"));

            $insert = $pdo->prepare("
                INSERT INTO abonnements(eleve_id, montant, date_paiement, date_expiration, dette)
                VALUES (?, ?, ?, ?, 0)
            ");
            $insert->execute([$eleve_id, $reste, $date_paiement, $expiration]);
        }

        // Re-fetch the updated data
        $stmt = $pdo->prepare("
            SELECT e.*, a.*
            FROM abonnements a
            JOIN eleves e ON e.id = a.eleve_id
            WHERE a.id = ?
        ");
        $stmt->execute([$abo_id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        $dette = $data['dette'];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Régler la dette</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .box {
            width: 80%;
            max-width: 600px;
            margin: 30px auto;
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 0 10px #ccc;
        }
        label {
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 7px;
            font-size: 16px;
        }
        button {
            margin-top: 15px;
            padding: 10px;
            width: 100%;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 7px;
            font-size: 16px;
        }
        button:hover {
            background: #1e7e34;
        }
        .msg {
            margin-top: 20px;
            padding: 12px;
            background: #e9ecef;
            border-left: 4px solid #007BFF;
            border-radius: 5px;
        }
        a.retour {
            display: inline-block;
            margin-top: 15px;
            color: #007BFF;
            text-decoration: none;
        }
        a.retour:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">

    <header>💵 Régler la dette</header>

    <div class="box">

        <h2>💰 Dette de <?= htmlspecialchars($data['nom']) ?></h2>

        <p>Dette actuelle : <b style="color:red;"><?= $dette ?> $</b></p>

        <form method="POST">
            <label>Montant payé :</label>
            <input type="number" step="0.01" name="montant_paye" required>

            <button type="submit">Valider le paiement</button>
        </form>

        <?php if (!empty($message)): ?>
            <div class="msg"><?= $message ?></div>
        <?php endif; ?>

        <a href="dette.php" class="retour">← Retour</a>
    </div>

</div>

</body>
</html>
