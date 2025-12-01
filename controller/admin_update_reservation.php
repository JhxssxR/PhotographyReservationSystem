<?php
// Admin action to confirm or cancel reservations
if (session_status() === PHP_SESSION_NONE) session_start();

// Check admin
if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}

require_once __DIR__ . '/../db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../adminpage/admin_dashboard.php');
    exit;
}

$reservation_id = intval($_POST['reservation_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($reservation_id <= 0 || !in_array($action, ['confirm', 'cancel'])) {
    header('Location: ../adminpage/admin_dashboard.php?error=invalid');
    exit;
}

$newStatus = ($action === 'confirm') ? 'confirmed' : 'cancelled';

try {
    // Get reservation details first
    $detailStmt = $pdo->prepare('SELECT r.user_id, s.name AS service_name FROM reservations r LEFT JOIN services s ON s.service_id = r.service_id WHERE r.reservation_id = :rid');
    $detailStmt->execute(['rid' => $reservation_id]);
    $resDetail = $detailStmt->fetch();

    $stmt = $pdo->prepare('UPDATE reservations SET status = :status WHERE reservation_id = :rid');
    $stmt->execute(['status' => $newStatus, 'rid' => $reservation_id]);
    
    // Notify the user about their booking status
    if ($resDetail && $resDetail['user_id']) {
        require_once __DIR__ . '/../includes/notifications_helper.php';
        $serviceName = $resDetail['service_name'] ?? 'your booking';
        if ($action === 'confirm') {
            createNotification($pdo, $resDetail['user_id'], "Great news! Your booking for {$serviceName} has been confirmed. Thank you!", 'booking');
        } else {
            createNotification($pdo, $resDetail['user_id'], "Your booking for {$serviceName} has been cancelled.", 'booking');
        }
    }

    header('Location: ../adminpage/admin_dashboard.php?updated=1');
    exit;
} catch (Exception $e) {
    header('Location: ../adminpage/admin_dashboard.php?error=db');
    exit;
}
