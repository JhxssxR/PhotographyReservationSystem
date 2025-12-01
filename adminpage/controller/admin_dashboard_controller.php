<?php
// Admin Dashboard Controller
if (session_status() === PHP_SESSION_NONE) session_start();

// Check admin access
if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../login.php?error=' . urlencode('Admin access required'));
    exit;
}

require_once __DIR__ . '/../../db.php';

$adminName = $_SESSION['name'] ?? 'Admin';

// Gather KPIs
$totalRevenue = 0;
$totalBookings = 0;
$pendingApprovals = 0;
$totalCustomers = 0;
$recentBookings = [];
$popularServices = [];
$adminNotifCount = 0;
$adminNotifications = []; // Recent notifications for dropdown
$pendingPayments = []; // Payments awaiting verification

try {
    // Admin notification count
    $nStmt = $pdo->prepare('SELECT COUNT(*) FROM notifications WHERE recipient_id = :uid AND is_read = 0');
    $nStmt->execute(['uid' => $_SESSION['user_id']]);
    $adminNotifCount = $nStmt->fetchColumn() ?: 0;

    // Get recent notifications for dropdown
    $nListStmt = $pdo->prepare('SELECT * FROM notifications WHERE recipient_id = :uid ORDER BY created_at DESC LIMIT 5');
    $nListStmt->execute(['uid' => $_SESSION['user_id']]);
    $adminNotifications = $nListStmt->fetchAll();

    // Total revenue (from confirmed reservations)
    $stmt = $pdo->query("SELECT COALESCE(SUM(s.price), 0) AS total FROM reservations r JOIN services s ON s.service_id = r.service_id WHERE r.status = 'confirmed'");
    $totalRevenue = $stmt->fetchColumn() ?: 0;

    // Total bookings
    $stmt = $pdo->query("SELECT COUNT(*) FROM reservations");
    $totalBookings = $stmt->fetchColumn() ?: 0;

    // Pending approvals (payments pending verification)
    $stmt = $pdo->query("SELECT COUNT(*) FROM payments WHERE status = 'pending'");
    $pendingApprovals = $stmt->fetchColumn() ?: 0;

    // Total customers
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'");
    $totalCustomers = $stmt->fetchColumn() ?: 0;

    // Recent bookings with payment status
    $stmt = $pdo->query("SELECT r.reservation_id, r.scheduled_date, r.status, r.created_at, u.name AS customer_name, u.email, s.name AS service_name, s.price,
        (SELECT COUNT(*) FROM payments p WHERE p.reservation_id = r.reservation_id AND p.status = 'paid') AS paid_count,
        (SELECT COUNT(*) FROM payments p WHERE p.reservation_id = r.reservation_id AND p.status = 'pending') AS pending_payment_count
        FROM reservations r
        JOIN users u ON u.user_id = r.user_id
        JOIN services s ON s.service_id = r.service_id
        ORDER BY r.created_at DESC
        LIMIT 10");
    $recentBookings = $stmt->fetchAll();

    // Pending payments awaiting verification (with receipt details)
    $stmt = $pdo->query("SELECT p.payment_id, p.reservation_id, p.amount, p.method, p.status AS payment_status, 
        p.reference_number, p.receipt_image, p.paid_at,
        r.scheduled_date, r.status AS reservation_status,
        u.name AS customer_name, u.email, u.user_id,
        s.name AS service_name
        FROM payments p
        JOIN reservations r ON r.reservation_id = p.reservation_id
        JOIN users u ON u.user_id = r.user_id
        JOIN services s ON s.service_id = r.service_id
        WHERE p.status = 'pending'
        ORDER BY p.paid_at DESC");
    $pendingPayments = $stmt->fetchAll();

    // Popular services
    $stmt = $pdo->query("SELECT s.name, COUNT(r.reservation_id) AS booking_count, s.price
        FROM services s
        LEFT JOIN reservations r ON r.service_id = s.service_id
        GROUP BY s.service_id
        ORDER BY booking_count DESC
        LIMIT 5");
    $popularServices = $stmt->fetchAll();

} catch (Exception $e) {
    // Silently fail, keep defaults
}

include __DIR__ . '/../admin_dashboard_view.php';
