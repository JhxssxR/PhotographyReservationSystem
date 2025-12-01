<?php
// Payment controller - handles payment submission with receipt upload
session_start();
require_once __DIR__ . '/../db.php';

if (empty($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$reservationId = $_POST['reservation_id'] ?? null;
$paymentMethod = $_POST['payment_method'] ?? 'cash';
$referenceNumber = $_POST['reference_number'] ?? null;

if (!$reservationId) {
    header('Location: ../dashboard.php?error=missing_reservation');
    exit;
}

try {
    // Verify the reservation belongs to this user
    $stmt = $pdo->prepare("SELECT r.*, s.price FROM reservations r
        LEFT JOIN services s ON s.service_id = r.service_id
        WHERE r.reservation_id = :rid AND r.user_id = :uid");
    $stmt->execute(['rid' => $reservationId, 'uid' => $userId]);
    $reservation = $stmt->fetch();

    if (!$reservation) {
        header('Location: ../dashboard.php?error=invalid_reservation');
        exit;
    }

    // Check if payment already exists (pending or paid)
    $checkStmt = $pdo->prepare("SELECT payment_id FROM payments WHERE reservation_id = :rid AND status IN ('paid', 'pending')");
    $checkStmt->execute(['rid' => $reservationId]);
    if ($checkStmt->fetch()) {
        header('Location: ../dashboard.php?error=payment_exists');
        exit;
    }

    // Handle receipt image upload for online payments
    $receiptImage = null;
    if ($paymentMethod !== 'cash' && !empty($_FILES['receipt_image']['name'])) {
        $uploadDir = __DIR__ . '/../assets/receipts/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $fileName = time() . '_' . $reservationId . '_' . basename($_FILES['receipt_image']['name']);
        $targetPath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['receipt_image']['tmp_name'], $targetPath)) {
            $receiptImage = 'assets/receipts/' . $fileName;
        }
    }

    // Determine payment status based on method
    // Cash = pending (pay in person), Online = pending (awaiting admin verification)
    $paymentStatus = 'pending';
    
    // Create payment record with pending status
    $paymentStmt = $pdo->prepare("INSERT INTO payments (reservation_id, amount, method, status, reference_number, receipt_image, paid_at) 
        VALUES (:rid, :amount, :method, :status, :ref, :receipt, NOW())");
    $paymentStmt->execute([
        'rid' => $reservationId,
        'amount' => $reservation['price'],
        'method' => $paymentMethod,
        'status' => $paymentStatus,
        'ref' => $referenceNumber,
        'receipt' => $receiptImage
    ]);

    // Reservation stays as 'pending' until admin approves payment
    // Do NOT update reservation status here

    // Create notification for user
    require_once __DIR__ . '/../includes/notifications_helper.php';
    $amount = number_format($reservation['price'], 2);
    
    if ($paymentMethod === 'cash') {
        createNotification($pdo, $userId, "Payment of ₱{$amount} marked for cash. Please pay upon your session.", 'payment');
    } else {
        createNotification($pdo, $userId, "Payment of ₱{$amount} submitted for verification. You'll be notified once approved.", 'payment');
    }
    
    // Notify admins about payment submission
    $userName = $_SESSION['name'] ?? 'Customer';
    notifyAdmins($pdo, "Payment submitted: {$userName} paid ₱{$amount} via {$paymentMethod} - awaiting verification", 'payment');

    header('Location: ../dashboard.php?paid=1&tab=payments');
    exit;

} catch (PDOException $e) {
    header('Location: ../dashboard.php?error=' . urlencode('Payment failed: ' . $e->getMessage()));
    exit;
}
