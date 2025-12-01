<?php
// Reset password controller - updates user's password
session_start();
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../reset_password.php');
    exit;
}

// Check if user is allowed to reset password
if (empty($_SESSION['reset_user_id']) || empty($_SESSION['reset_email'])) {
    header('Location: ../forgot_password.php');
    exit;
}

$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

// Validate password
if (empty($password)) {
    header('Location: ../reset_password.php?error=' . urlencode('Please enter a password'));
    exit;
}

if (strlen($password) < 6) {
    header('Location: ../reset_password.php?error=' . urlencode('Password must be at least 6 characters long'));
    exit;
}

if ($password !== $confirmPassword) {
    header('Location: ../reset_password.php?error=' . urlencode('Passwords do not match'));
    exit;
}

try {
    $userId = $_SESSION['reset_user_id'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the password in database
    $updateStmt = $pdo->prepare("UPDATE users SET password_hash = :password WHERE user_id = :uid");
    $updateStmt->execute([
        'password' => $hashedPassword,
        'uid' => $userId
    ]);

    // Clear reset session data
    unset($_SESSION['reset_user_id']);
    unset($_SESSION['reset_email']);
    
    // Set success flag
    $_SESSION['password_reset_success'] = true;
    
    header('Location: ../reset_success.php');
    exit;

} catch (PDOException $e) {
    header('Location: ../reset_password.php?error=' . urlencode('An error occurred. Please try again.'));
    exit;
}
