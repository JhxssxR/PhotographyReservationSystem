<?php
// middleware/auth.php
// Usage: set $auth_redirect_register = true (optional) before including this file.
if (session_status() === PHP_SESSION_NONE) session_start();

$auth_redirect_register = $auth_redirect_register ?? false;
if (empty($_SESSION['user_id'])) {
  // preserve full requested URI for return after signup/login
  $next = $_SERVER['REQUEST_URI'] ?? '/';
  $next_q = urlencode($next);
  if ($auth_redirect_register) {
    header('Location: register.php?next=' . $next_q);
    exit;
  }
  header('Location: login.php?next=' . $next_q);
  exit;
}

// If the logged-in user is an admin, prevent access to non-admin pages.
// Pages that should allow admin access can set $allow_admin_access = true before including this middleware.
// No admin-only redirect: allow logged-in users to access non-admin pages as normal.

?>
