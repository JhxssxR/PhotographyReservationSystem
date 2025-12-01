<?php
// middleware/admin_auth.php
if (session_status() === PHP_SESSION_NONE) session_start();

// Ensure the user is logged in and has admin role
if (empty($_SESSION['user_id']) || empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
  // redirect to login, preserve next
  $next = $_SERVER['REQUEST_URI'] ?? '/';
  header('Location: /Photography-Reservation-System/login.php?next=' . urlencode($next));
  exit;
}

?>
<?php
// middleware/admin_auth.php
if (session_status() === PHP_SESSION_NONE) session_start();
// require login first
if (empty($_SESSION['user_id'])) {
    $next = $_SERVER['REQUEST_URI'] ?? '/';
    header('Location: ../login.php?next=' . urlencode($next));
    exit;
}

// require admin role
if (empty($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // not authorized
    header('Location: ../dashboard.php?error=' . urlencode('Access denied'));
    exit;
}

?>
