<?php
// migrate_hash_passwords.php
// RUN ONCE from CLI: php migrate_hash_passwords.php

$host = '127.0.0.1';
$db   = 'vulnerable_app';
$user = 'root';
$pass = 'your_db_password';   
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $stmt = $pdo->query("SELECT id, username, password FROM users");
    $users = $stmt->fetchAll();

    foreach ($users as $u) {
        $id = $u['id'];
        $current = $u['password'];

        
        if (strlen($current) < 60) {
            $newHash = password_hash($current, PASSWORD_DEFAULT);
            $upd = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $upd->execute([$newHash, $id]);
            echo "Updated user id=$id username={$u['username']}\n";
        } else {
            echo "Skipping (already hashed) id=$id username={$u['username']}\n";
        }
    }

    echo "Migration finished.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}