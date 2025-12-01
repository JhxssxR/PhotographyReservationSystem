<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['user_id'])) {
  header('Location: dashboard.php');
  exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password - PhotoReserve</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="cssfile/login.css">
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
      <h1>Forgot Password</h1>
      <p class="lead">Enter your email address and we'll help you reset your password</p>

      <?php if (!empty($_GET['error'])): ?>
        <div class="form-error" style="color:#b91c1c;margin-bottom:12px"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>
      <?php if (!empty($_GET['success'])): ?>
        <div class="form-success" style="color:#065f46;margin-bottom:12px;background:rgba(16,185,129,0.1);padding:12px;border-radius:8px"><?= htmlspecialchars($_GET['success']) ?></div>
      <?php endif; ?>

      <form class="login-form" method="post" action="controller/forgot_password.php">
        <label class="field">
          <span class="label-text">Email Address</span>
          <div class="input-wrap">
            <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 8l9 6 9-6" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <input type="email" name="email" placeholder="you@example.com" required>
          </div>
        </label>

        <button type="submit" class="btn-primary"><span>Reset Password</span>
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M13 6l6 6-6 6" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </form>

      <p class="muted">Remember your password? <a href="login.php">Sign in</a></p>
    </div>
  </main>

  <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
