<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Check if password was just reset
if (empty($_SESSION['password_reset_success'])) {
    header('Location: forgot_password.php');
    exit;
}

// Clear the session flag
unset($_SESSION['password_reset_success']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Password Reset - PhotoReserve</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="cssfile/login.css">
  <style>
    .success-icon {
      width: 64px;
      height: 64px;
      background: rgba(16,185,129,0.1);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 16px;
    }
  </style>
</head>
<body>
  <?php
  $hide_services = true;
  $hide_auth = true;
  include __DIR__ . '/includes/header.php'; ?>

  <main class="center">
    <div class="card">
      <div class="success-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
      </div>
      <h1>Password Reset Successful</h1>
      <p class="lead">Your password has been updated successfully. You can now sign in with your new password.</p>

      <a href="login.php" class="btn-primary" style="display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;margin-top:20px">
        <span>Sign In Now</span>
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M13 6l6 6-6 6" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </main>

  <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
