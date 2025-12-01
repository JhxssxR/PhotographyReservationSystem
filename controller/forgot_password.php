<?php
// Forgot password controller - verifies email and allows user to set new password
session_start();
require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../forgot_password.php');
    exit;
}

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    header('Location: ../forgot_password.php?error=' . urlencode('Please enter your email address'));
    exit;
}

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT user_id, name, email FROM users WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if (!$user) {
        header('Location: ../forgot_password.php?error=' . urlencode('No account found with that email address'));
        exit;
    }

    // Store user info in session to allow password reset
    $_SESSION['reset_user_id'] = $user['user_id'];
    $_SESSION['reset_email'] = $email;
    
    header('Location: ../reset_password.php');
    exit;

} catch (PDOException $e) {
    header('Location: ../forgot_password.php?error=' . urlencode('An error occurred. Please try again.'));
    exit;
}
