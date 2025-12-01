<?php
// Cancel booking controller - removes reservation from database
session_start();
require_once __DIR__ . '/../db.php';

if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$reservationId = $_POST['reservation_id'] ?? null;

if (!$reservationId) {
    header('Location: ../dashboard.php?error=missing_reservation');
    exit;
}

try {
    // Verify the reservation belongs to this user and delete it
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE reservation_id = :rid AND user_id = :uid AND status = 'pending'");
    $stmt->execute(['rid' => $reservationId, 'uid' => $userId]);
    
    if ($stmt->rowCount() > 0) {
        header('Location: ../dashboard.php?cancelled=1');
    } else {
        header('Location: ../dashboard.php?error=cannot_cancel');
    }
    exit;

} catch (PDOException $e) {
    header('Location: ../dashboard.php?error=' . urlencode('Failed to cancel booking'));
    exit;
}
