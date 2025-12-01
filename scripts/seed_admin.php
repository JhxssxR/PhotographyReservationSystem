<?php
// Seed script to create or update an admin user.
// Usage (from project root):
// php scripts/seed_admin.php

require_once __DIR__ . '/../db.php';

$email = 'admin@gmail.com';
$password_plain = 'admin123';
$name = 'Administrator';
$role = 'admin';

try {
    // Check if user already exists
    $stmt = $pdo->prepare('SELECT user_id AS id, email FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    $password_hash = password_hash($password_plain, PASSWORD_DEFAULT);

    if ($user) {
        // Update existing user to ensure admin role and updated password
        $update = $pdo->prepare('UPDATE users SET name = ?, password_hash = ?, role = ? WHERE user_id = ?');
        $update->execute([$name, $password_hash, $role, $user['id']]);
        echo "Updated existing user with email {$email} to role 'admin'.\n";
    } else {
        // Insert new admin user
        $insert = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $insert->execute([$name, $email, $password_hash, $role]);
        echo "Inserted new admin user with email {$email}.\n";
    }

    echo "Done. You can now log in with the admin account.\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
