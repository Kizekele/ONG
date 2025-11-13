<?php
require 'config.php';

define('PRICE_PER_MONTH', 25.0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = intval($_POST['student_id']);
    $amount = floatval($_POST['amount']);

    // récupérer subscription actuelle
    $sub = $pdo->prepare("SELECT * FROM subscriptions WHERE student_id = ?");
    $sub->execute([$student_id]);
    $sub = $sub->fetch();

    if (!$sub) {
        // créer subscription si absent
        $pdo->prepare("INSERT INTO subscriptions (student_id, expiration_date, debt) VALUES (?, NULL, 0)")->execute([$student_id]);
        $sub = $pdo->prepare("SELECT * FROM subscriptions WHERE student_id = ?")->execute([$student_id]);
        $sub = $pdo->query("SELECT * FROM subscriptions WHERE student_id = $student_id")->fetch();
    }

    $current_debt = floatval($sub['debt']);
    $current_exp = $sub['expiration_date'] ? new DateTime($sub['expiration_date']) : null;
    $now = new DateTime();

    // 1. couvrir la dette d'abord
    $remaining = $amount;
    if ($current_debt > 0) {
        if ($remaining >= $current_debt) {
            $remaining -= $current_debt;
            $current_debt = 0;
        } else {
            $current_debt -= $remaining;
            $remaining = 0;
        }
    }

    // 2. acheter des mois
    $months = floor($remaining / PRICE_PER_MONTH);
    $spent_for_months = $months * PRICE_PER_MONTH;
    $remaining_after_months = $remaining - $spent_for_months;

    // 3. new debt = remaining_after_months (if >0 and <PRICE_PER_MONTH)
    $new_debt = $current_debt + ($remaining_after_months > 0 ? (PRICE_PER_MONTH - $remaining_after_months) : 0);
    // Note: on peut aussi décider de faire new_debt = remaining_after_months; j'ai choisi d'exiger 25$ pour un mois, donc si reste 5$, on considère 20$ manquant => dette.

    // 4. calcul expiration base
    if ($months > 0) {
        if ($current_exp && new DateTime($sub['expiration_date']) > $now) {
            $base = new DateTime($sub['expiration_date']);
        } else {
            $base = $now;
        }
        $base->modify("+{$months} months");
        $new_exp = $base->format('Y-m-d H:i:s');
    } else {
        $new_exp = $sub['expiration_date']; // inchangé
    }

    // 5. insert paiement historique
    $stmt = $pdo->prepare("INSERT INTO payments (student_id, amount, payment_date, expiration_date, note) VALUES (?, ?, NOW(), ?, ?)");
    $note = "Paiement traité: months={$months}, reste_after={$remaining_after_months}";
    $stmt->execute([$student_id, $amount, $new_exp, $note]);

    // 6. mettre à jour subscriptions
    $stmt2 = $pdo->prepare("UPDATE subscriptions SET expiration_date = ?, debt = ? WHERE student_id = ?");
    $stmt2->execute([$new_exp, $new_debt, $student_id]);

    header("Location: payments_list.php?ok=1");
    exit;
}
?>
