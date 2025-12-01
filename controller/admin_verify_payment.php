<?php
// Admin Payment Verification Controller
session_start();
require_once __DIR__ . '/../db.php';

// Check admin access
if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php?error=' . urlencode('Admin access required'));
    exit;
}

$paymentId = $_POST['payment_id'] ?? null;
$action = $_POST['action'] ?? null;

if (!$paymentId || !$action) {
    $_SESSION['admin_error'] = 'Invalid request';
    header('Location: ../adminpage/admin_dashboard.php');
    exit;
}

try {
    // Get payment and reservation details
    $stmt = $pdo->prepare("SELECT p.*, r.reservation_id, r.user_id, s.name AS service_name, u.name AS customer_name
        FROM payments p
        JOIN reservations r ON r.reservation_id = p.reservation_id
        JOIN services s ON s.service_id = r.service_id
        JOIN users u ON u.user_id = r.user_id
        WHERE p.payment_id = :pid");
    $stmt->execute(['pid' => $paymentId]);
    $payment = $stmt->fetch();

    if (!$payment) {
        $_SESSION['admin_error'] = 'Payment not found';
        header('Location: ../adminpage/admin_dashboard.php');
        exit;
    }

    require_once __DIR__ . '/../includes/notifications_helper.php';
    $amount = number_format($payment['amount'], 2);

    if ($action === 'approve') {
        // Update payment status to paid
        $updatePayment = $pdo->prepare("UPDATE payments SET status = 'paid' WHERE payment_id = :pid");
        $updatePayment->execute(['pid' => $paymentId]);

        // Update reservation status to confirmed
        $updateReservation = $pdo->prepare("UPDATE reservations SET status = 'confirmed' WHERE reservation_id = :rid");
        $updateReservation->execute(['rid' => $payment['reservation_id']]);

        // Notify customer
        createNotification($pdo, $payment['user_id'], 
            "Your payment of ₱{$amount} for {$payment['service_name']} has been verified. Your booking is now confirmed!", 
            'payment'
        );

        $_SESSION['admin_success'] = "Payment approved and booking confirmed for {$payment['customer_name']}";
    } 
    elseif ($action === 'reject') {
        // Update payment status to failed
        $updatePayment = $pdo->prepare("UPDATE payments SET status = 'failed' WHERE payment_id = :pid");
        $updatePayment->execute(['pid' => $paymentId]);

        // Notify customer
        createNotification($pdo, $payment['user_id'], 
            "Your payment of ₱{$amount} for {$payment['service_name']} could not be verified. Please contact us or try again.", 
            'payment'
        );

        $_SESSION['admin_success'] = "Payment rejected for {$payment['customer_name']}";
    }

    header('Location: ../adminpage/admin_dashboard.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['admin_error'] = 'Database error: ' . $e->getMessage();
    header('Location: ../adminpage/admin_dashboard.php');
    exit;
}
