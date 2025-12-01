<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php');
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$next = $_POST['next'] ?? '';

if (empty($email) || empty($password)) {
    $q = http_build_query(['error' => 'Please provide email and password']);
    header('Location: ../login.php?' . $q);
    exit;
}

try {
    $stmt = $pdo->prepare('SELECT user_id, name, email, password_hash, role FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();
} catch (Exception $e) {
    $q = http_build_query(['error' => 'Database error']);
    header('Location: ../login.php?' . $q);
    exit;
}

if (!$user) {
    $q = http_build_query(['error' => 'Invalid credentials']);
    header('Location: ../login.php?' . $q);
    exit;
}

$hash = $user['password_hash'] ?? '';
// Verify password (assumes password_hash was used during registration)
if (function_exists('password_verify') && password_verify($password, $hash)) {
    // success
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['role'] = $user['role'];

    if (!empty($next)) {
        header('Location: ' . $next);
        exit;
    }

    if ($user['role'] === 'admin') {
        header('Location: ../adminpage/admin_dashboard.php');
        exit;
    }

    header('Location: ../dashboard.php');
    exit;
} else {
    // Password verify failed
    $q = http_build_query(['error' => 'Invalid credentials']);
    header('Location: ../login.php?' . $q);
    exit;
}
