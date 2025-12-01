<?php
// Update profile controller
session_start();
require_once __DIR__ . '/../db.php';

if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';

if (empty($name) || empty($email)) {
    header('Location: ../dashboard.php?tab=profile&error=' . urlencode('Name and email are required'));
    exit;
}

try {
    // Check if email is already taken by another user
    $checkStmt = $pdo->prepare("SELECT user_id FROM users WHERE email = :email AND user_id != :uid");
    $checkStmt->execute(['email' => $email, 'uid' => $userId]);
    if ($checkStmt->fetch()) {
        header('Location: ../dashboard.php?tab=profile&error=' . urlencode('Email is already in use'));
        exit;
    }

    // If changing password, verify current password
    if (!empty($newPassword)) {
        if (empty($currentPassword)) {
            header('Location: ../dashboard.php?tab=profile&error=' . urlencode('Current password is required to change password'));
            exit;
        }

        $pwStmt = $pdo->prepare("SELECT password_hash FROM users WHERE user_id = :uid");
        $pwStmt->execute(['uid' => $userId]);
        $user = $pwStmt->fetch();

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            header('Location: ../dashboard.php?tab=profile&error=' . urlencode('Current password is incorrect'));
            exit;
        }

        // Update with new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, phone_number = :phone, password_hash = :password WHERE user_id = :uid");
        $updateStmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $hashedPassword,
            'uid' => $userId
        ]);
    } else {
        // Update without changing password
        $updateStmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, phone_number = :phone WHERE user_id = :uid");
        $updateStmt->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'uid' => $userId
        ]);
    }

    // Update session name
    $_SESSION['name'] = $name;

    header('Location: ../dashboard.php?tab=profile&success=' . urlencode('Profile updated successfully'));
    exit;

} catch (PDOException $e) {
    header('Location: ../dashboard.php?tab=profile&error=' . urlencode('Failed to update profile'));
    exit;
}
