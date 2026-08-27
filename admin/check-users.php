<?php
require_once __DIR__ . '/../config/db.php';
$pdo = getDBConnection();

$cols = $pdo->query("SHOW COLUMNS FROM `users`")->fetchAll(PDO::FETCH_ASSOC);
echo "Columns in `users`:\n";
foreach ($cols as $c) {
    echo "- {$c['Field']} ({$c['Type']})\n";
}

echo "\nRows in `users`:\n";
$users = $pdo->query("SELECT * FROM `users`")->fetchAll(PDO::FETCH_ASSOC);
foreach ($users as $u) {
    $passSnippet = substr($u['password'] ?? '', 0, 15) . '...';
    echo "ID: {$u['id']} | Username: {$u['username']} | Email: {$u['email']} | Pass: {$passSnippet} | Role: {$u['role']} | Status: " . ($u['status'] ?? 'N/A') . "\n";
    echo "Test verify 'admin123': " . (password_verify('admin123', $u['password'] ?? '') ? 'VALID' : 'INVALID') . "\n";
}
