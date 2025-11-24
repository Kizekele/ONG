<?php
date_default_timezone_set('Africa/Kinshasa');

// Configuration MySQL
$host = 'localhost';
$db   = 'ong_transport';
$user = 'root';
$pass = '';

// Dossier où les backups seront sauvegardés
$backupDir = __DIR__ . '/sauvegardes';
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0777, true); // Crée le dossier s'il n'existe pas
}

// Nom du fichier de backup avec date/heure
$backupFile = $backupDir . '/backup_' . date("Y-m-d_H-i-s") . '.sql';

// Chemin vers mysqldump (vérifie que c'est correct sur ton PC)
$mysqldump = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';

// Commande pour sauvegarder la base
$command = "\"$mysqldump\" -h $host -u $user --password=\"$pass\" $db > \"$backupFile\"";

// Exécuter la commande
exec($command, $output, $result);

/***if ($result === 0) {
    echo "Sauvegarde MySQL réussie : $backupFile";
} else {
    echo "Erreur lors de la sauvegarde MySQL ! Vérifie le chemin de mysqldump et les permissions.";
}***/
