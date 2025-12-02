<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../register.php');
    exit;
}

$fullname = trim($_POST['fullname'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$password2 = $_POST['password2'] ?? '';
$terms = isset($_POST['terms']) ? true : false;
$next = $_POST['next'] ?? '';

// Validation
if (empty($fullname) || empty($email) || empty($phone) || empty($password) || empty($password2)) {
    $q = http_build_query(['error' => 'All fields are required']);
    header('Location: ../register.php?' . $q);
    exit;
}

if (!$terms) {
    $q = http_build_query(['error' => 'You must agree to the Terms and Conditions']);
    header('Location: ../register.php?' . $q);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $q = http_build_query(['error' => 'Invalid email address']);
    header('Location: ../register.php?' . $q);
    exit;
}

if (strlen($password) < 6) {
    $q = http_build_query(['error' => 'Password must be at least 6 characters']);
    header('Location: ../register.php?' . $q);
    exit;
}

if ($password !== $password2) {
    $q = http_build_query(['error' => 'Passwords do not match']);
    header('Location: ../register.php?' . $q);
    exit;
}

// Check if email already exists
try {
    $stmt = $pdo->prepare('SELECT user_id FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $existing = $stmt->fetch();
    
    if ($existing) {
        $q = http_build_query(['error' => 'Email already registered. Please login or use a different email.']);
        header('Location: ../register.php?' . $q);
        exit;
    }
} catch (Exception $e) {
    $q = http_build_query(['error' => 'Database error. Please try again.']);
    header('Location: ../register.php?' . $q);
    exit;
}

// Create user
try {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, phone_number, role, created_at) VALUES (:name, :email, :password_hash, :phone, :role, NOW())');
    $stmt->execute([
        'name' => $fullname,
        'email' => $email,
        'password_hash' => $passwordHash,
        'phone' => $phone,
        'role' => 'customer'
    ]);
    
    $userId = $pdo->lastInsertId();
    
    // Auto-login the new user
    $_SESSION['user_id'] = $userId;
    $_SESSION['name'] = $fullname;
    $_SESSION['email'] = $email;
    $_SESSION['role'] = 'customer';
    
    // Redirect to dashboard or next page
    if (!empty($next)) {
        header('Location: ' . $next);
        exit;
    }
    
    header('Location: ../dashboard.php');
    exit;
    
} catch (Exception $e) {
    $q = http_build_query(['error' => 'Registration failed. Please try again.']);
    header('Location: ../register.php?' . $q);
    exit;
}
