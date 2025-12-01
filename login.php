<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['user_id'])) {
  $role = $_SESSION['role'] ?? '';
  if ($role === 'admin') {
    header('Location: adminpage/admin_dashboard.php');
    exit;
  }
  header('Location: dashboard.php');
  exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - PhotographyReservationSystem </title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="cssfile/index.css">
  <link rel="stylesheet" href="cssfile/login.css">
</head>
<body>
  <?php
  // Hide Services and auth buttons on the login page header (keep Home visible)
  $hide_services = true;
  $hide_auth = true;
  include __DIR__ . '/includes/header.php'; ?>

  <main class="center">
    <div class="card">
      <div class="card-avatar"> 
        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="6" width="18" height="12" rx="2" fill="#fff"/><path d="M8 8h8" stroke="#8b5cf6" stroke-width="1.2" stroke-linecap="round"/><circle cx="12" cy="12" r="2" fill="#fff"/></svg>
      </div>
      <h1>Welcome Back</h1>
      <p class="lead">Sign in to your account to continue</p>

      <?php if (!empty($_GET['error'])): ?>
        <div class="form-error" style="color:#b91c1c;margin-bottom:12px"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>
      <?php if (!empty($_GET['success'])): ?>
        <div class="form-success" style="color:#065f46;margin-bottom:12px"><?= htmlspecialchars($_GET['success']) ?></div>
      <?php endif; ?>

      <form class="login-form" method="post" action="controller/login.php">
        <?php if (!empty($_GET['next'])): ?>
          <input type="hidden" name="next" value="<?= htmlspecialchars($_GET['next']) ?>">
        <?php endif; ?>
        <label class="field">
          <span class="label-text">Email Address</span>
          <div class="input-wrap">
            <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 8l9 6 9-6" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <input type="email" name="email" placeholder="you@example.com" required>
          </div>
        </label>

        <label class="field">
          <span class="label-text">Password</span>
          <div class="input-wrap">
            <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="11" width="18" height="11" rx="2" stroke="#9CA3AF" stroke-width="1.5"/><path d="M7 11V7a5 5 0 0110 0v4" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <input type="password" name="password" placeholder="********" required>
          </div>
        </label>

        <div class="forgot-password" style="text-align:right;margin-bottom:16px">
          <a href="forgot_password.php" style="color:#7b4f36;font-size:13px;text-decoration:none;font-weight:500">Forgot Password?</a>
        </div>

        <button type="submit" class="btn-primary"><span>Sign In</span>
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M13 6l6 6-6 6" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </form>

      <p class="muted">Don't have an account?<a href="register.php">Create one</a></p>
    </div>
  </main>
  <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
