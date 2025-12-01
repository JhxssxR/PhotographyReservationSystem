<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Check if user is allowed to reset password
if (empty($_SESSION['reset_user_id']) || empty($_SESSION['reset_email'])) {
    header('Location: forgot_password.php');
    exit;
}

$email = $_SESSION['reset_email'];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reset Password - PhotoReserve</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="cssfile/login.css">
  <style>
    .password-requirements {
      background: rgba(201,165,122,0.1);
      border-radius: 8px;
      padding: 12px;
      margin-top: 12px;
      font-size: 13px;
      color: #6b7280;
    }
    .password-requirements ul {
      margin: 8px 0 0 20px;
      padding: 0;
    }
    .password-requirements li {
      margin: 4px 0;
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
      <div class="card-avatar"> 
        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="3" y="11" width="18" height="11" rx="2" stroke="#c9a57a" stroke-width="1.5"/>
          <path d="M7 11V7a5 5 0 0110 0v4" stroke="#c9a57a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          <circle cx="12" cy="16" r="1.5" fill="#c9a57a"/>
        </svg>
      </div>
      <h1>Create New Password</h1>
      <p class="lead">Enter a new password for <strong><?= htmlspecialchars($email) ?></strong></p>

      <?php if (!empty($_GET['error'])): ?>
        <div class="form-error" style="color:#b91c1c;margin-bottom:12px;background:rgba(239,68,68,0.1);padding:12px;border-radius:8px"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>

      <form class="login-form" method="post" action="controller/reset_password.php">
        <label class="field">
          <span class="label-text">New Password</span>
          <div class="input-wrap">
            <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="#9CA3AF" stroke-width="1.5"/><path d="M7 11V7a5 5 0 0110 0v4" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <input type="password" name="password" placeholder="Enter new password" required minlength="6">
          </div>
        </label>

        <label class="field">
          <span class="label-text">Confirm Password</span>
          <div class="input-wrap">
            <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="11" width="18" height="11" rx="2" ry="2" stroke="#9CA3AF" stroke-width="1.5"/><path d="M7 11V7a5 5 0 0110 0v4" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <input type="password" name="confirm_password" placeholder="Confirm new password" required minlength="6">
          </div>
        </label>

        <div class="password-requirements">
          <strong>Password requirements:</strong>
          <ul>
            <li>At least 6 characters long</li>
          </ul>
        </div>

        <button type="submit" class="btn-primary" style="margin-top:20px"><span>Update Password</span>
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M13 6l6 6-6 6" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </form>

      <p class="muted">Remember your password? <a href="login.php">Sign in</a></p>
    </div>
  </main>

  <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
