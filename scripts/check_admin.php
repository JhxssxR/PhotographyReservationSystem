<?php
require_once __DIR__ . '/../db.php';

$email = 'admin@gmail.com';
$stmt = $pdo->prepare('SELECT password_hash FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$hash = $stmt->fetchColumn();

echo "email: {$email}\n";
echo "hash: " . ($hash ?: '<none>') . "\n";
echo "password_verify('admin123', hash): ";
var_export(password_verify('admin123', $hash));
echo PHP_EOL;
