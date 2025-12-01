<?php
// Controller to delete a single reservation (admin only)
session_start();
require_once __DIR__ . '/../db.php';

// Check if user is logged in and is admin
if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservationId = intval($_POST['reservation_id'] ?? 0);
    
    if ($reservationId <= 0) {
        $_SESSION['admin_error'] = 'Invalid reservation ID.';
        header('Location: ../adminpage/admin_dashboard.php');
        exit;
    }
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Delete related payments first (foreign key constraint)
        $stmt = $pdo->prepare("DELETE FROM payments WHERE reservation_id = :rid");
        $stmt->execute(['rid' => $reservationId]);
        
        // Delete the reservation
        $stmt = $pdo->prepare("DELETE FROM reservations WHERE reservation_id = :rid");
        $stmt->execute(['rid' => $reservationId]);
        
        // Commit transaction
        $pdo->commit();
        
        $_SESSION['admin_success'] = 'Reservation deleted successfully.';
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['admin_error'] = 'Failed to delete reservation: ' . $e->getMessage();
    }
}

header('Location: ../adminpage/admin_dashboard.php');
exit;
