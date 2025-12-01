<?php
// booking.php - shows booking form prefilled for a selected service
$serviceKey = $_GET['service'] ?? 'wedding';

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
  'commercial' => [
    'name' => 'Product Photography',
    'price' => '₱5,000',
    'price_raw' => 5000,
    'duration' => '180 minutes',
    'image' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=1200&q=80&auto=format&fit=crop',
    'desc' => 'Professional product shots for e-commerce',
  ],
  'real-estate' => [
    'name' => 'Real Estate Photography',
    'price' => '₱4,000',
    'price_raw' => 4000,
    'duration' => '150 minutes',
    'image' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=1200&q=80&auto=format&fit=crop',
    'desc' => 'Interior and exterior property photography',
  ],
  'maternity' => [
    'name' => 'Maternity Photoshoot',
    'price' => '₱3,500',
    'price_raw' => 3500,
    'duration' => '90 minutes',
    'image' => 'assets/dfbb7ce719b50d7e41795c98de20db80.jpg',
    'desc' => 'Beautiful maternity photography session',
  ],
];

$svc = $services[$serviceKey] ?? $services['wedding'];
// require registration/login before showing the booking form
$auth_redirect_register = true;
require_once __DIR__ . '/middleware/auth.php';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Book - <?= htmlspecialchars($svc['name']) ?></title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="cssfile/booking.css">
  <link rel="stylesheet" href="cssfile/index.css">
</head>
<body>
  <?php include __DIR__ . '/includes/header.php'; ?>

  <main class="booking-wrap">
    <div class="wrap booking-grid">
      <div class="card service-card-large">
        <img class="svc-img" src="<?= htmlspecialchars($svc['image']) ?>" alt="<?= htmlspecialchars($svc['name']) ?>">
        <div class="card-body">
          <h2><?= htmlspecialchars($svc['name']) ?></h2>
          <p class="desc"><?= htmlspecialchars($svc['desc']) ?></p>
          <div class="meta"><span class="time">⏱ <?= htmlspecialchars($svc['duration']) ?></span>
            <span class="price"><?= htmlspecialchars($svc['price']) ?></span>
          </div>
        </div>
      </div>

      <aside class="card booking-form">
        <h3>Book Your Session</h3>
        <form method="post" action="controller/book.php">
          <input type="hidden" name="service_key" value="<?= htmlspecialchars($serviceKey) ?>">
          <input type="hidden" name="price" value="<?= htmlspecialchars($svc['price_raw']) ?>">

          <label>Select Date
            <input type="date" name="date" required>
          </label>

          <label>Select Time
            <input type="time" name="time" required>
          </label>

          <label>Additional Notes (Optional)
            <textarea name="notes" rows="4" placeholder="Any special requirements or preferences..."></textarea>
          </label>

          <label>Payment Method
            <select name="payment_method" required>
              <option value="bank">Bank Transfer</option>
              <option value="cash">Cash</option>
              <option value="gcash">GCash</option>
            </select>
          </label>

          <div class="total">Total Amount: <strong><?= htmlspecialchars($svc['price']) ?></strong></div>

          <button type="submit" class="btn-primary">Confirm Booking & Pay</button>
        </form>
      </aside>
    </div>
  </main>

  <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
