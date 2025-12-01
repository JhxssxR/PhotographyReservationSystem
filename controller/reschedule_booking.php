<?php
// Reschedule booking controller
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../includes/notifications_helper.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../dashboard.php');
    exit;
}

// Check if user is logged in
if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$reservationId = intval($_POST['reservation_id'] ?? 0);
$newDate = $_POST['new_date'] ?? '';
$newTime = $_POST['new_time'] ?? '';

// Validate inputs
if (!$reservationId || empty($newDate) || empty($newTime)) {
    header('Location: ../dashboard.php?error=' . urlencode('Invalid reschedule request'));
    exit;
}

// Validate date is in the future
if (strtotime($newDate) < strtotime(date('Y-m-d'))) {
    header('Location: ../dashboard.php?error=' . urlencode('Please select a future date'));
    exit;
}

try {
    // Verify the reservation belongs to the user and is in a reschedulable status
    $stmt = $pdo->prepare("
        SELECT r.reservation_id, r.status, s.name as service_name 
        FROM reservations r 
        JOIN services s ON r.service_id = s.service_id
        WHERE r.reservation_id = :rid AND r.user_id = :uid
    ");
    $stmt->execute(['rid' => $reservationId, 'uid' => $userId]);
    $reservation = $stmt->fetch();

    if (!$reservation) {
        header('Location: ../dashboard.php?error=' . urlencode('Reservation not found'));
        exit;
    }

    // Only allow rescheduling for pending or approved bookings
    if (!in_array($reservation['status'], ['pending', 'approved'])) {
        header('Location: ../dashboard.php?error=' . urlencode('This booking cannot be rescheduled'));
        exit;
    }

    // Update the reservation with new date and time
    $updateStmt = $pdo->prepare("
        UPDATE reservations 
        SET reservation_date = :date, reservation_time = :time, status = 'pending'
        WHERE reservation_id = :rid
    ");
    $updateStmt->execute([
        'date' => $newDate,
        'time' => $newTime,
        'rid' => $reservationId
    ]);

    // Notify user
    createNotification(
        $pdo,
        $userId,
        "Your booking for {$reservation['service_name']} has been rescheduled to {$newDate} at {$newTime}. Awaiting admin approval.",
        'booking'
    );

    // Notify admins
    $userName = $_SESSION['name'] ?? 'A user';
    notifyAdmins(
        $pdo,
        "{$userName} rescheduled their {$reservation['service_name']} booking to {$newDate} at {$newTime}",
        'booking'
    );

    header('Location: ../dashboard.php?rescheduled=1');
    exit;

} catch (PDOException $e) {
    header('Location: ../dashboard.php?error=' . urlencode('An error occurred. Please try again.'));
    exit;
}
