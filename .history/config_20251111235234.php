<?php
// config.php
$host = '127.0.0.1';
$db   = 'ong_transport';
$user = 'root';
$pass = ''; // XAMPP par défaut
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die('DB Connexion failed: '.$e->getMessage());
}
