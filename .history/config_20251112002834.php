<?php
$host = '127.0.0.1';
$db   = 'ong_transport'; // ton nom de base MySQL
$user = 'root';
$pass = ''; // mot de passe vide pour XAMPP
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    die('Échec connexion DB: ' . $e->getMessage());
}
