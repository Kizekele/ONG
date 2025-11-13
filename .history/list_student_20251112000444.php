<?php
require 'config.php';

// Par défaut : afficher tous les élèves
$type = $_GET['filter'] ?? 'all';
$keyword = $_GET['search'] ?? '';

if ($type === 'debt') {
    $sql = "SELECT s.*, sub.debt 
            FROM students s
            JOIN subscriptions sub ON s.id = sub.student_id
            WHERE sub.debt > 0";
} elseif ($type === 'expire') {
    $sql = "SELECT s.*, sub.expiration_date 
            FROM students s
            JOIN subscriptions sub ON s.id = sub.student_id
            WHERE sub.expiration_date IS NOT NULL
              AND sub.expiration_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 10 DAY)";
} elseif (!empty($keyword)) {
    $sql = "SELECT * FROM students
            WHERE CONCAT(nom,' ',postnom,' ',prenom) LIKE :kw";
} else {
    $sql = "SELECT s.*, sub.expiration_date, sub.debt
            FROM students s
            LEFT JOIN subscriptions sub ON s.id = sub.student_id";
}

$stmt = $pdo->prepare($sql);

// Si recherche
if (!empty($keyword)) {
    $stmt->execute([':kw' => "%$keyword%"]);
} else {
    $stmt->execute();
}

$students = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Liste des élèves</title>
<style>
body { font-family: Arial, sans-serif; background: #f4f4f4; margin:0; padding:20px; }
h2 { color:#333; }
table { width:100%; border-collapse:collapse; background:#fff; margin-top:10px; }
th, td { padding:10px; border:1px solid #ddd; text-align:left; }
th { background:#3498db; color:#fff; }
tr:nth-child(even){ background:#f9f9f9; }
.filters { margin-bottom:15px; }
button, input[type=text] { padding:6px 10px; margin-right:5px; }
</style>
</head>
<body>

<h2>Liste des élèves</h2>

<div class="filters">
  <a href="?filter=all"><button>Tous</button></a>
  <a href="?filter=debt"><button>Avec dettes</button></a>
  <a href="?filter=expire"><button>Expiration ≤ 10j</button></a>
  <form method="get" style="display:inline;">
    <input type="text" name="search" placeholder="Rechercher par nom..." value="<?=htmlspecialchars($keyword)?>">
    <button type="submit">Rechercher</button>
  </form>
</div>

<table>
  <tr>
    <th>Nom</th>
    <th>Postnom</th>
    <th>Prénom</th>
    <th>Classe</th>
    <th>Cycle</th>
    <th>Dette ($)</th>
    <th>Date expiration</th>
    <th>Date ajout</th>
  </tr>
  <?php foreach ($students as $s): ?>
  <tr>
    <td><?= htmlspecialchars($s['nom']) ?></td>
    <td><?= htmlspecialchars($s['postnom']) ?></td>
    <td><?= htmlspecialchars($s['prenom']) ?></td>
    <td><?= htmlspecialchars($s['classe']) ?></td>
    <td><?= htmlspecialchars($s['cycle']) ?></td>
    <td><?= htmlspecialchars($s['debt'] ?? '-') ?></td>
    <td><?= htmlspecialchars($s['expiration_date'] ?? '-') ?></td>
    <td><?= htmlspecialchars($s['date_ajout']) ?></td>
  </tr>
  <?php endforeach; ?>
</table>

</body>
</html>
