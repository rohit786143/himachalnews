<?php
require_once __DIR__ . '/../config/db.php';
$pdo = getDBConnection();

$newPass = 'admin123';
$newHash = password_hash($newPass, PASSWORD_BCRYPT);

// Update all admin users to password 'admin123'
$stmt = $pdo->prepare("
    UPDATE `users` 
    SET `password` = ?, `status` = 'active', `role` = 'admin' 
    WHERE `username` = 'admin' OR `id` = 1
");
$stmt->execute([$newHash]);

echo "Admin password updated to 'admin123'!\n";

$u = $pdo->query("SELECT * FROM `users` WHERE `username` = 'admin' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
echo "User: {$u['username']} | Email: {$u['email']}\n";
echo "Password verify 'admin123': " . (password_verify('admin123', $u['password']) ? "SUCCESS (VALID)" : "FAILED") . "\n";
