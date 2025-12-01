<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect admin to admin dashboard
if (($_SESSION['role'] ?? '') === 'admin') {
    header('Location: adminpage/admin_dashboard.php');
    exit;
}

// Public services listing — themed beige/white gradient
$services = [
  'wedding' => [
    'name' => 'Wedding Photography',
    'price' => '₱60,000',
    'price_raw' => 60000,
    'duration' => '480 minutes',
    'image' => 'assets/couple-holding-wedding-bouquet-scaled_460x@2x.png',
    'desc' => 'Complete wedding day coverage with professional editing',
  ],
  'portrait' => [
    'name' => 'Portrait Session',
    'price' => '₱2,500',
    'price_raw' => 2500,
    'duration' => '120 minutes',
    'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=1200&q=80&auto=format&fit=crop',
    'desc' => 'Individual or family portrait session',
  ],
  'event' => [
    'name' => 'Event Photography',
    'price' => '₱10,000',
    'price_raw' => 10000,
    'duration' => '240 minutes',
    'image' => 'assets/concert-photographer.png',
    'desc' => 'Corporate events, parties, and celebrations',
  ],
  'product' => [
    'name' => 'Product Photography',
    'price' => '₱5,000',
    'price_raw' => 5000,
    'duration' => '180 minutes',
    'image' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=1200&q=80&auto=format&fit=crop',
    'desc' => 'Professional product shots for e-commerce',
  ],
  'realestate' => [
    'name' => 'Real Estate Photography',
    'price' => '₱3,500',
    'price_raw' => 3500,
    'duration' => '150 minutes',
    'image' => 'https://th.bing.com/th/id/R.f3fbbc913f36e1414f37c25d2524a393?rik=24TvHl63huoVuA&riu=http%3a%2f%2fwww.philsavoryphotography.com.au%2fwp-content%2fuploads%2f2020%2f02%2fBrisbane-real-estate-dusk-photography-services.jpg&ehk=7hmM6eZGatNVwy6TiveGpqfeU%2bwGeW%2bt0wAqOrn%2feIA%3d&risl=&pid=ImgRaw&r=0',
    'desc' => 'Interior and exterior property photography',
  ],
  'maternity' => [
    'name' => 'Maternity Photoshoot',
    'price' => '₱2,800',
    'price_raw' => 2800,
    'duration' => '90 minutes',
    'image' => 'https://tse1.explicit.bing.net/th/id/OIP.hn4QExeej2hsoecw2tgL0gHaE9?w=1896&h=1268&rs=1&pid=ImgDetMain&o=7&rm=3',
    'desc' => 'Beautiful maternity photography session',
  ],
];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Services - PhotoReserve</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="cssfile/index.css">
  <link rel="stylesheet" href="cssfile/services.css">
  <style>
    :root{
      --bg1: #f7efe6; /* soft beige */
      --bg2: #ffffff;
      --card: #ffffff;
      --muted: #6b6b6b;
      --accent: linear-gradient(135deg,#d6bfa6 0%,#f2e9df 100%);
      --brown: #7b4f36;
      --btn-start: #c9a57a;
      --btn-end: #e9d9c8;
    }
    body{background:#f7efe6;font-family:system-ui,-apple-system,Segoe UI,Roboto,'Helvetica Neue',Arial;color:#222;margin:0;padding:0}
    main{max-width:1200px;margin:28px auto;padding:0 20px}
    .hero{display:flex;align-items:center;justify-content:center;flex-direction:column;padding:36px 12px 18px;text-align:center}
    .hero h1{font-size:40px;margin:0;color:var(--brown);letter-spacing:-0.5px}
    .hero h1 .highlight{color:var(--brown);font-weight:800}
    .hero p{color:var(--muted);margin-top:8px}

    .controls{display:flex;gap:10px;justify-content:center;margin:18px 0}
    .controls .pill{background:rgba(255,255,255,0.7);border-radius:999px;padding:8px 12px;font-weight:600;border:1px solid rgba(16,24,40,0.04)}

    .services-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;margin-top:20px}
    .svc-card{background:var(--card);border-radius:14px;padding:14px;border:1px solid rgba(16,24,40,0.04);box-shadow:0 6px 18px rgba(17,24,39,0.06);overflow:hidden;display:flex;flex-direction:column}
    .svc-img{width:100%;height:180px;object-fit:cover;border-radius:10px}
    .svc-body{padding:10px 6px;flex:1;display:flex;flex-direction:column}
    .svc-title{font-size:18px;margin:6px 0;color:#2b2b2b}
    .svc-desc{color:var(--muted);font-size:14px;flex:1}
    .svc-meta{display:flex;align-items:center;justify-content:space-between;margin-top:12px}

    .price{font-weight:800;color:var(--brown)}
    .btn-book{display:inline-block;padding:8px 14px;border-radius:10px;color:#2b2b2b;text-decoration:none;font-weight:700;background:var(--accent);border:none;box-shadow:inset 0 -2px 0 rgba(0,0,0,0.04)}

    @media (max-width:600px){.hero h1{font-size:28px}.svc-img{height:160px}}
  </style>
</head>
<body>
  <?php include __DIR__ . '/includes/header.php'; ?>

  <main>
    <section class="hero">
      <h1>Our Photography Services</h1>
      <p>Choose the perfect package for your special moments</p>
      <div class="controls">
        <div class="filter-left">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" style="vertical-align:middle;margin-right:6px"><path d="M4 6h16M7 12h10M10 18h4" stroke="#6b7280" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
          <span style="color:var(--muted);margin-right:8px">Filter:</span>
        </div>
        <div class="filter-pills">
          <button class="pill active" data-filter="all">All</button>
          <button class="pill" data-filter="wedding">Wedding</button>
          <button class="pill" data-filter="portrait">Portrait</button>
          <button class="pill" data-filter="event">Event</button>
          <button class="pill" data-filter="commercial">Commercial</button>
          <button class="pill" data-filter="realestate">Real Estate</button>
        </div>
      </div>
    </section>

    <section class="services-grid" id="servicesGrid">
      <?php foreach ($services as $key => $s): ?>
        <article class="svc-card">
          <div style="position:relative">
            <img class="svc-img" src="<?= htmlspecialchars($s['image']) ?>" alt="<?= htmlspecialchars($s['name']) ?>">
            <?php $tag = $s['tag'] ?? '';?>
            <?php if(empty($tag)):
              // infer tag by key rough mapping
              if ($key === 'wedding') $tag = 'Wedding';
              if ($key === 'portrait') $tag = 'Portrait';
              if ($key === 'event') $tag = 'Event';
              if ($key === 'product') $tag = 'Commercial';
              if ($key === 'realestate') $tag = 'Real Estate';
              if ($key === 'maternity') $tag = 'Portrait';
            endif; ?>
            <?php if(!empty($tag)): ?>
              <span class="svc-badge"><?= htmlspecialchars($tag) ?></span>
            <?php endif; ?>
          </div>
          <div class="svc-body">
            <div>
              <div class="svc-title"><?= htmlspecialchars($s['name']) ?></div>
              <div class="svc-desc"><?= htmlspecialchars($s['desc']) ?></div>
            </div>
            <div class="svc-meta">
              <div>
                <div class="meta-row"><span class="meta-item"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg><?= htmlspecialchars($s['duration']) ?></span></div>
                <div class="meta-row"><span class="price"><?= htmlspecialchars($s['price']) ?></span></div>
                <div class="meta-row" style="color:var(--muted);font-size:12px;margin-top:6px">0 bookings</div>
              </div>
              <button class="btn-book" data-service="<?= urlencode($key) ?>">Book Now</button>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </section>
  <script src="scripts/services.js" defer></script>
  <style>
    /* Prototype-style grid: 3 columns on wide screens, equal height cards */
    .services-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:28px;margin-top:28px}
    .svc-card{height:100%;display:flex;flex-direction:column;background:var(--card);border-radius:14px;padding:0;border:1px solid rgba(16,24,40,0.04);box-shadow:0 6px 18px rgba(17,24,39,0.04);overflow:hidden}
    .svc-img{width:100%;height:180px;object-fit:cover;border-top-left-radius:10px;border-top-right-radius:10px}
    .svc-badge{position:absolute;top:12px;right:12px;background:#fff;padding:6px 10px;border-radius:999px;border:1px solid rgba(16,24,40,0.06);font-size:12px;color:var(--muted)}
    .svc-body{padding:18px;display:flex;flex-direction:column;flex:1}
    .svc-title{font-size:18px;margin:6px 0;color:#111}
    .svc-desc{color:var(--muted);font-size:14px;flex:1}
    .svc-meta{display:flex;align-items:center;justify-content:space-between;margin-top:12px}
    .btn-book{background:linear-gradient(135deg,#c9a57a 0%,#e9d9c8 100%);color:#5c4033;border:none;padding:10px 16px;border-radius:10px;cursor:pointer;font-weight:700;box-shadow:0 2px 6px rgba(0,0,0,0.08)}
    .btn-book:hover{filter:brightness(0.97);box-shadow:0 4px 12px rgba(0,0,0,0.12)}
    .pill{background:#fff;border-radius:999px;padding:8px 12px;border:1px solid rgba(16,24,40,0.06);cursor:pointer}
    .pill.active{background:linear-gradient(135deg,#c9a57a 0%,#e9d9c8 100%);color:#5c4033}

    @media (max-width:1000px){.services-grid{grid-template-columns:repeat(2,1fr)}}
    @media (max-width:640px){.services-grid{grid-template-columns:repeat(1,1fr)}.hero h1{font-size:28px}}
  </style>
  </main>

  <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
