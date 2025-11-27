<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login - PhotographyReservationSystem </title>
  <link rel="stylesheet" href="cssfile/login.css">
</head>
<body>
  <header class="topbar">
    <div class="wrap">
      <div class="brand">
        <div class="logo">📷</div>
        <span>PhotoReserve</span>
      </div>
      <div class="center-nav"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="vertical-align:middle;margin-right:6px"><path d="M3 11.5L12 4l9 7.5" stroke="#94a3b8" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/><path d="M5 11.5v6a2 2 0 002 2h10a2 2 0 002-2v-6" stroke="#94a3b8" stroke-width="1.3" stroke-linecap="round" stroke-linejoin="round"/></svg>Home</div>
      <nav class="nav">
        <a href="login.php">Login</a>
        <a href="register.php" class="register">Register</a>
      </nav>
    </div>
  </header>

  <main class="center">
    <div class="card">
      <div class="card-avatar"> 
        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="6" width="18" height="12" rx="2" fill="#fff"/><path d="M8 8h8" stroke="#8b5cf6" stroke-width="1.2" stroke-linecap="round"/><circle cx="12" cy="12" r="2" fill="#fff"/></svg>
      </div>
      <h1>Welcome Back</h1>
      <p class="lead">Sign in to your account to continue</p>

      <form class="login-form">
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

        <button type="button" class="btn-primary"><span>Sign In</span>
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M13 6l6 6-6 6" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </form>

      <p class="muted">Don't have an account?<a href="register.php">Create one</a></p>
    </div>
  </main>
</body>
</html>
<?php
