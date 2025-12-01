<?php
// controller/book.php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/../db.php';

// Ensure user is logged in
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php?next=' . urlencode('/booking.php'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../services.php');
    exit;
}

$service_key = $_POST['service_key'] ?? '';
$date = $_POST['date'] ?? '';
$time = $_POST['time'] ?? '';
$notes = $_POST['notes'] ?? '';
$price = $_POST['price'] ?? '';

if (empty($service_key) || empty($date) || empty($time)) {
    header('Location: ../booking.php?service=' . urlencode($service_key) . '&error=' . urlencode('Please select date and time'));
    exit;
}

// Normalize keys (accept both real-estate and realestate)
$norm_key = str_replace('-', '', strtolower($service_key));

// Define the same services list as booking.php so we can get the service name/price
$services = [
  'wedding' => ['name'=>'Wedding Photography','price'=>60000,'duration'=>480],
  'portrait' => ['name'=>'Portrait Session','price'=>2500,'duration'=>120],
  'event' => ['name'=>'Event Photography','price'=>10000,'duration'=>240],
  'commercial' => ['name'=>'Product Photography','price'=>5000,'duration'=>180],
  'realestate' => ['name'=>'Real Estate Photography','price'=>4000,'duration'=>150],
  'maternity' => ['name'=>'Maternity Photoshoot','price'=>3500,'duration'=>90],
];

$svc = $services[$norm_key] ?? $services['wedding'];
$service_name = $svc['name'];
$service_price = $svc['price'];

// Combine date and time into datetime
$scheduled = $date . ' ' . $time . ':00';

try {
    // Find service_id in DB by name
    $stmt = $pdo->prepare('SELECT service_id FROM services WHERE name = :name LIMIT 1');
    $stmt->execute(['name' => $service_name]);
    $row = $stmt->fetch();
    if ($row && !empty($row['service_id'])) {
        $service_id = (int)$row['service_id'];
    } else {
        // insert service record so FK constraints are satisfied
        $ins = $pdo->prepare('INSERT INTO services (name, description, price, duration, is_active) VALUES (:name, :desc, :price, :duration, 1)');
        $ins->execute([
            'name' => $service_name,
            'desc' => $_POST['notes'] ?? '',
            'price' => $service_price,
            'duration' => $svc['duration'] ?? 60,
        ]);
        $service_id = (int)$pdo->lastInsertId();
    }

    // Insert reservation
    $r = $pdo->prepare('INSERT INTO reservations (user_id, service_id, scheduled_date, status, created_at) VALUES (:uid, :sid, :sched, :status, NOW())');
    $r->execute([
        'uid' => $_SESSION['user_id'],
        'sid' => $service_id,
        'sched' => $scheduled,
        'status' => 'pending',
    ]);

    // Create notification for user
    require_once __DIR__ . '/../includes/notifications_helper.php';
    $userName = $_SESSION['name'] ?? 'Customer';
    createNotification($pdo, $_SESSION['user_id'], "Your booking for {$service_name} on " . date('M j, Y', strtotime($date)) . " has been submitted and is pending confirmation.", 'booking');
    
    // Notify admins about new booking
    notifyAdmins($pdo, "New booking: {$userName} booked {$service_name} for " . date('M j, Y', strtotime($date)), 'booking');

    // Redirect to dashboard with success flag
    header('Location: ../dashboard.php?booked=1');
    exit;

} catch (Exception $e) {
    header('Location: ../booking.php?service=' . urlencode($service_key) . '&error=' . urlencode('Unable to create booking'));
    exit;
}
