<?php
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PhotoReserve - Services</title>
  <link rel="stylesheet" href="cssfile/services.css">
</head>
<body>
  <header class="topbar">
    <div class="wrap">
      <div class="brand">
        <div class="logo">📷</div>
        <span class="brand-name">PhotoReserve</span>
      </div>
      <nav class="nav">
        <a href="index.php">Home</a>
        <a href="services.php" class="active">Book Service</a>
        <a href="dashboard.php">Dashboard</a>
      </nav>
      <div class="user">
        <div class="avatar">JD</div>
        <div class="username">John Doe<br><small class="role">Customer</small></div>
      </div>
    </div>
  </header>

  <main class="services-page">
    <div class="wrap">
      <h1 class="page-title">Our Photography Services</h1>
      <p class="subtitle">Choose the perfect package for your special moments</p>

      <div class="controls">
        <div class="filters">
          <button class="filter active">All</button>
          <button class="filter">Wedding</button>
          <button class="filter">Portrait</button>
          <button class="filter">Event</button>
          <button class="filter">Commercial</button>
          <button class="filter">Real Estate</button>
        </div>
      </div>

      <div class="grid services-grid">
        <!-- Service card -->
        <article class="service-card">
          <div class="media">
            <img src="assets/couple-holding-wedding-bouquet-scaled_460x@2x.png" alt="wedding" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1200 800\'><rect fill=\'%23f3f4f6\' width=\'100%25\' height=\'100%25\'/><text x=\'600\' y=\'400\' fill=\'%239ca3af\' font-size=\'36\' text-anchor=\'middle\' dominant-baseline=\'middle\'>Image%20unavailable</text></svg>'">
            <span class="tag">Wedding</span>
          </div>
          <div class="card-body">
            <h3>Wedding Photography</h3>
            <p class="desc">Complete wedding day coverage with professional editing</p>
            <div class="meta">
              <span>480 min</span>
              <span>•</span>
              <span>$250</span>
            </div>
            <a class="btn book" href="#">Book Now →</a>
          </div>
        </article>

        <article class="service-card">
          <div class="media">
            <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=1200&q=80&auto=format&fit=crop" alt="portrait">
            <span class="tag">Portrait</span>
          </div>
          <div class="card-body">
            <h3>Portrait Session</h3>
            <p class="desc">Individual or family portrait session</p>
            <div class="meta">
              <span>120 min</span>
              <span>•</span>
              <span>$35</span>
            </div>
            <a class="btn book" href="#">Book Now →</a>
          </div>
        </article>

        <article class="service-card">
          <div class="media">
            <img src="https://images.unsplash.com/photo-1509099836639-18ba1e6c3baf?w=1200&q=80&auto=format&fit=crop" alt="event">
            <span class="tag">Event</span>
          </div>
          <div class="card-body">
            <h3>Event Photography</h3>
            <p class="desc">Corporate events, parties, and celebrations</p>
            <div class="meta">
              <span>240 min</span>
              <span>•</span>
              <span>$80</span>
            </div>
            <a class="btn book" href="#">Book Now →</a>
          </div>
        </article>

        <article class="service-card">
          <div class="media">
            <img src="https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=1200&q=80&auto=format&fit=crop" alt="product">
            <span class="tag">Commercial</span>
          </div>
          <div class="card-body">
            <h3>Product Photography</h3>
            <p class="desc">Professional product shots for e-commerce</p>
            <div class="meta">
              <span>180 min</span>
              <span>•</span>
              <span>$50</span>
            </div>
            <a class="btn book" href="#">Book Now →</a>
          </div>
        </article>

        <article class="service-card">
          <div class="media">
            <img src="https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=1200&q=80&auto=format&fit=crop" alt="realestate">
            <span class="tag">Real Estate</span>
          </div>
          <div class="card-body">
            <h3>Real Estate Photography</h3>
            <p class="desc">Interior and exterior property photography</p>
            <div class="meta">
              <span>150 min</span>
              <span>•</span>
              <span>$45</span>
            </div>
            <a class="btn book" href="#">Book Now →</a>
          </div>
        </article>

        <article class="service-card">
          <div class="media">
              <img src="assets/maternity.jpg" alt="maternity" loading="lazy" onerror="this.onerror=null;this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1200 800\'><rect fill=\'%23f3f4f6\' width=\'100%25\' height=\'100%25\'/><text x=\'600\' y=\'400\' fill=\'%239ca3af\' font-size=\'36\' text-anchor=\'middle\' dominant-baseline=\'middle\'>Image%20unavailable</text></svg>'">
            <span class="tag">Portrait</span>
          </div>
          <div class="card-body">
            <h3>Maternity Photoshoot</h3>
            <p class="desc">Beautiful maternity photography session</p>
            <div class="meta">
              <span>90 min</span>
              <span>•</span>
              <span>$40</span>
            </div>
            <a class="btn book" href="#">Book Now →</a>
          </div>
        </article>
      </div>
    </div>
  </main>

  <footer class="site-footer">
    <div class="wrap">© <strong>PhotoReserve</strong></div>
  </footer>
</body>
</html>
