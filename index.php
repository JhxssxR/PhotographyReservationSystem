<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect admin to admin dashboard
if (($_SESSION['role'] ?? '') === 'admin') {
    header('Location: adminpage/admin_dashboard.php');
    exit;
}

$isLoggedIn = !empty($_SESSION['user_id']);
$userName = $_SESSION['name'] ?? '';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PhotoReserve - Home</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="cssfile/index.css">
</head>
<body>
  <?php
  // Hide the Services and Home nav items on the homepage
  $hide_services = true;
  $hide_home = true;
  include __DIR__ . '/includes/header.php'; ?>

  <section class="hero">
    <div class="wrap hero-grid">
      <div class="hero-left">
        <span class="badge">Professional Photography Services</span>
        <h1>Capture Your <span class="perfect">Perfect</span> <span class="moment">Moments</span></h1>
        <p class="lead">Book professional photography sessions for weddings, portraits, events, and more. Easy scheduling, transparent pricing, and stunning results.</p>
        <div class="cta">
          <a href="register.php" class="btn btn-primary">Get Started <span class="arrow">→</span></a>
          <a href="services.php" class="btn btn-outline">View Services</a>
        </div>
      </div>
      <div class="hero-right">
        <div class="image-card">
          <img src="https://images.unsplash.com/photo-1499951360447-b19be8fe80f5?w=1200&q=80&auto=format&fit=crop" alt="camera">
        </div>
      </div>
    </div>
  </section>

  <section class="why" id="services">
    <div class="wrap">
      <h2 class="center">Why Choose Us</h2>
      <p class="sub center">Experience seamless booking and exceptional service</p>
      <div class="features">
        <div class="card feature">
          <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div>
          <h3>Easy Booking</h3>
          <p>Simple and intuitive booking process. Select your service, choose a date, and confirm in minutes.</p>
        </div>
        <div class="card feature">
          <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></div>
          <h3>Secure Payments</h3>
          <p>Safe and secure payment processing with multiple payment options available.</p>
        </div>
        <div class="card feature">
          <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
          <h3>Professional Quality</h3>
          <p>Work with experienced photographers dedicated to capturing your special moments.</p>
        </div>
      </div>
    </div>
  </section>

  <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
