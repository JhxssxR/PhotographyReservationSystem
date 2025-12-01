<?php
// User Dashboard - Clean design matching prototype with beige/brown theme
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/middleware/auth.php';

$userId = $_SESSION['user_id'] ?? null;
$userName = $_SESSION['name'] ?? 'User';

// Fetch data
$bookings = [];
$profile = [];
$payments_due = [];
$totalPending = 0;

if ($userId) {
  try {
    // Fetch active bookings (exclude cancelled)
    $stmt = $pdo->prepare("SELECT r.reservation_id, r.scheduled_date, r.status, s.name AS service_name, s.price,
      (SELECT COUNT(*) FROM payments p WHERE p.reservation_id = r.reservation_id AND p.status = 'paid') AS paid_count,
      (SELECT COUNT(*) FROM payments p WHERE p.reservation_id = r.reservation_id AND p.status = 'pending') AS pending_payment_count
      FROM reservations r
      LEFT JOIN services s ON s.service_id = r.service_id
      WHERE r.user_id = :uid AND r.status != 'cancelled'
      ORDER BY r.created_at DESC");
    $stmt->execute(['uid' => $userId]);
    while ($row = $stmt->fetch()) {
      $dateStr = '';
      $timeStr = '';
      if (!empty($row['scheduled_date'])) {
        $ts = strtotime($row['scheduled_date']);
        if ($ts !== false) {
          $dateStr = date('m/d/Y', $ts);
          $timeStr = date('H:i', $ts);
        }
      }
      $isPaid = intval($row['paid_count']) > 0;
      $hasPendingPayment = intval($row['pending_payment_count'] ?? 0) > 0;
      $bookings[] = [
        'id' => $row['reservation_id'],
        'service' => $row['service_name'] ?? 'Service',
        'date' => $dateStr,
        'time' => $timeStr,
        'status' => $row['status'],
        'paid' => $isPaid,
        'pending_payment' => $hasPendingPayment,
        'price' => $row['price'] ?? 0,
      ];
    }

    // Fetch user profile
    $pstmt = $pdo->prepare('SELECT * FROM users WHERE user_id = :uid LIMIT 1');
    $pstmt->execute(['uid' => $userId]);
    $profile = $pstmt->fetch() ?: [];

    // Fetch payments due (only bookings without any payment - paid or pending)
    $paystmt = $pdo->prepare("SELECT r.reservation_id, r.scheduled_date, r.status, s.name AS service_name, s.price,
      (SELECT COUNT(*) FROM payments p WHERE p.reservation_id = r.reservation_id AND p.status IN ('paid', 'pending')) AS payment_count
      FROM reservations r
      JOIN services s ON s.service_id = r.service_id
      WHERE r.user_id = :uid AND r.status != 'cancelled'
      ORDER BY r.created_at DESC");
    $paystmt->execute(['uid' => $userId]);
    while ($row = $paystmt->fetch()) {
      if (intval($row['payment_count']) === 0) {
        $dateStr = '';
        $timeStr = '';
        if (!empty($row['scheduled_date'])) {
          $ts = strtotime($row['scheduled_date']);
          if ($ts !== false) {
            $dateStr = date('m/d/Y', $ts);
            $timeStr = date('H:i', $ts);
          }
        }
        $payments_due[] = [
          'reservation_id' => $row['reservation_id'],
          'service' => $row['service_name'],
          'price' => $row['price'],
          'date' => $dateStr,
          'time' => $timeStr,
        ];
        $totalPending += $row['price'];
      }
    }
  } catch (Exception $e) {}
}

$activeTab = $_GET['tab'] ?? 'bookings';
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - PhotoReserve</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="cssfile/index.css">
  <style>
    * { box-sizing: border-box; }
    
    :root {
      --bg: #faf9f7;
      --card: #ffffff;
      --text: #1a1a1a;
      --muted: #71717a;
      --border: #e4e4e7;
      --brown: #8b5a2b;
      --accent: #c9a57a;
      --accent-hover: #b8956b;
      --accent-light: rgba(201, 165, 122, 0.1);
      --success: #22c55e;
      --success-bg: rgba(34, 197, 94, 0.1);
      --warning: #f59e0b;
      --warning-bg: rgba(245, 158, 11, 0.1);
      --danger: #ef4444;
      --info: #3b82f6;
      --info-bg: rgba(59, 130, 246, 0.1);
      --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
      --shadow: 0 4px 12px rgba(0,0,0,0.05);
      --shadow-lg: 0 12px 40px rgba(0,0,0,0.08);
      --radius: 16px;
      --radius-sm: 10px;
    }
    
    body { 
      background: var(--bg); 
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      color: var(--text);
      line-height: 1.6;
    }

    /* Notification Dropdown - Dashboard Override */
    .notif-dropdown {
      position: absolute !important;
      top: calc(100% + 8px) !important;
      right: 0 !important;
      width: 340px !important;
      background: #fffcf8 !important;
      border-radius: 16px !important;
      box-shadow: 0 12px 40px rgba(123,79,54,0.18) !important;
      border: 1px solid rgba(180,140,100,0.35) !important;
      display: none;
      z-index: 9999 !important;
      overflow: hidden;
    }
    .notif-dropdown.show { display: block !important; animation: dropdownFade .2s ease; }
    @keyframes dropdownFade { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    .notif-header {
      display: flex !important;
      align-items: center;
      justify-content: space-between;
      padding: 16px 18px !important;
      background: linear-gradient(135deg, #e8d5be 0%, #d4c4a8 100%) !important;
      border-bottom: 1px solid rgba(160,120,80,0.25) !important;
    }
    .notif-title { font-weight: 700 !important; font-size: 15px !important; color: #5a3d2b !important; }
    .notif-count-badge {
      background: linear-gradient(135deg, #a67c52 0%, #8b5a2b 100%) !important;
      color: #fff !important;
      font-size: 11px !important;
      font-weight: 600 !important;
      padding: 4px 10px !important;
      border-radius: 20px !important;
      box-shadow: 0 2px 6px rgba(138,90,43,0.3) !important;
    }
    .notif-list { max-height: 320px; overflow-y: auto; background: #fffcf8 !important; }
    .notif-list::-webkit-scrollbar { width: 5px; }
    .notif-list::-webkit-scrollbar-track { background: #e8d9c8; }
    .notif-list::-webkit-scrollbar-thumb { background: #b8956b; border-radius: 4px; }
    .notif-list::-webkit-scrollbar-thumb:hover { background: #a67c52; }
    .notif-empty { padding: 40px 20px !important; text-align: center; color: #8b7355 !important; }
    .notif-empty svg { color: #c9a57a !important; margin-bottom: 12px; }
    .notif-empty p { font-size: 14px; margin: 0; }
    .notif-item {
      display: flex !important;
      align-items: flex-start;
      gap: 12px;
      padding: 14px 18px !important;
      border-bottom: 1px solid rgba(180,140,100,0.2) !important;
      transition: background .15s ease;
      position: relative;
      background: #fffcf8 !important;
    }
    .notif-item:hover { background: #f5ebe0 !important; }
    .notif-item.unread { background: #f0e4d4 !important; }
    .notif-item.unread:hover { background: #e8d9c8 !important; }
    .notif-icon {
      width: 32px !important;
      height: 32px !important;
      border-radius: 8px !important;
      background: linear-gradient(135deg, #e8d5be 0%, #d9c9b0 100%) !important;
      display: flex !important;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      border: 1px solid rgba(180,140,100,0.2) !important;
    }
    .notif-content { flex: 1; min-width: 0; }
    .notif-message {
      font-size: 13px !important;
      color: #4a3728 !important;
      margin: 0 0 4px;
      line-height: 1.4;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }
    .notif-time { font-size: 11px !important; color: #8b7355 !important; }
    .notif-dot {
      width: 8px !important;
      height: 8px !important;
      background: linear-gradient(135deg, #c9a57a 0%, #a67c52 100%) !important;
      border-radius: 50% !important;
      flex-shrink: 0;
      margin-top: 4px;
      box-shadow: 0 1px 3px rgba(166,124,82,0.4) !important;
    }
    .notif-footer {
      display: block !important;
      padding: 14px 18px !important;
      text-align: center;
      color: #5a3d2b !important;
      font-size: 13px !important;
      font-weight: 600 !important;
      text-decoration: none;
      background: linear-gradient(135deg, #e8d5be 0%, #d4c4a8 100%) !important;
      border-top: 1px solid rgba(160,120,80,0.25) !important;
      transition: all .15s ease;
    }
    .notif-footer:hover { background: linear-gradient(135deg, #dcc9b0 0%, #c9b898 100%) !important; color: #4a3728 !important; }

    /* Notification wrapper positioning */
    .notification-wrapper { position: relative !important; }
    .notification-bell { 
      position: relative !important;
      display: flex !important;
      align-items: center;
      justify-content: center;
      width: 40px !important;
      height: 40px !important;
      background: rgba(194,155,107,0.1) !important;
      border-radius: 10px !important;
      cursor: pointer;
      transition: all .2s ease;
      text-decoration: none;
      border: none !important;
    }
    .notification-bell:hover { background: rgba(194,155,107,0.2) !important; }
    .notification-bell svg { width: 20px; height: 20px; color: #c29b6b; stroke: #c29b6b; }
    .notification-badge {
      position: absolute !important;
      top: 4px !important;
      right: 4px !important;
      min-width: 18px;
      height: 18px;
      background: linear-gradient(135deg,#e74c3c,#c0392b) !important;
      color: #fff !important;
      font-size: 10px !important;
      font-weight: 700;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0 4px;
      box-shadow: 0 2px 6px rgba(231,76,60,0.4);
    }

    /* User area styling for header */
    .user-area { display: flex !important; align-items: center !important; gap: 12px !important; }
    .topbar { position: relative; z-index: 100; }
    .topbar .wrap { position: relative; }

    .dashboard-container { 
      max-width: 960px; 
      margin: 0 auto; 
      padding: 40px 24px 60px; 
    }
    
    /* Header */
    .dashboard-header { 
      margin-bottom: 32px;
      text-align: center;
    }
    
    .dashboard-header h1 { 
      font-size: 32px; 
      font-weight: 700; 
      color: var(--text); 
      margin: 0 0 8px;
      letter-spacing: -0.5px;
    }
    
    .dashboard-header p { 
      color: var(--muted); 
      font-size: 15px; 
      margin: 0; 
    }

    /* Tabs */
    .dashboard-tabs {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin-bottom: 32px;
      background: var(--card);
      padding: 6px;
      border-radius: 14px;
      box-shadow: var(--shadow-sm);
      border: 1px solid var(--border);
      max-width: 420px;
      margin-left: auto;
      margin-right: auto;
    }
    
    .tab-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px 20px;
      border: none;
      background: transparent;
      color: var(--muted);
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: all 0.2s ease;
      border-radius: 10px;
      flex: 1;
    }
    
    .tab-btn:hover { 
      color: var(--text);
      background: var(--bg);
    }
    
    .tab-btn.active { 
      background: var(--accent);
      color: #fff;
      box-shadow: 0 2px 8px rgba(201, 165, 122, 0.3);
    }
    
    .tab-btn svg { width: 18px; height: 18px; }

    .panel { display: none; animation: fadeIn 0.3s ease; }
    .panel.active { display: block; }
    
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(8px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Stats Row */
    .stats-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-bottom: 28px;
    }

    .stat-card {
      background: var(--card);
      border-radius: var(--radius);
      padding: 20px;
      border: 1px solid var(--border);
      text-align: center;
      transition: all 0.2s ease;
    }

    .stat-card:hover {
      transform: translateY(-2px);
      box-shadow: var(--shadow);
    }

    .stat-value {
      font-size: 28px;
      font-weight: 700;
      color: var(--text);
      margin-bottom: 4px;
    }

    .stat-label {
      font-size: 13px;
      color: var(--muted);
      font-weight: 500;
    }

    /* Booking Cards */
    .booking-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .booking-card {
      background: var(--card);
      border-radius: var(--radius);
      padding: 20px 24px;
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      gap: 16px;
      transition: all 0.2s ease;
    }

    .booking-card:hover {
      box-shadow: var(--shadow);
      border-color: var(--accent);
    }

    .booking-icon {
      width: 52px;
      height: 52px;
      border-radius: 14px;
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .booking-icon svg {
      width: 24px;
      height: 24px;
      color: #fff;
    }

    .booking-info { 
      flex: 1;
      min-width: 0;
    }

    .booking-title {
      display: flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 6px;
    }
    
    .booking-service { 
      font-weight: 600; 
      font-size: 16px; 
      color: var(--text);
    }
    
    .booking-meta { 
      display: flex; 
      gap: 16px; 
      color: var(--muted); 
      font-size: 13px;
      font-weight: 500;
    }
    
    .booking-meta span { 
      display: flex; 
      align-items: center; 
      gap: 6px; 
    }

    .booking-meta svg {
      width: 14px;
      height: 14px;
      opacity: 0.7;
    }

    .booking-right {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
      gap: 10px;
    }
    
    .booking-price { 
      font-size: 22px; 
      font-weight: 700; 
      color: var(--text);
    }

    .booking-actions {
      display: flex;
      gap: 8px;
    }

    /* Status badges */
    .status-badge {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }
    
    .status-badge svg { width: 12px; height: 12px; }
    
    .status-pending { background: var(--warning-bg); color: #b45309; }
    .status-approved { background: var(--info-bg); color: #2563eb; }
    .status-confirmed { background: var(--success-bg); color: #16a34a; }
    .status-completed { background: var(--success-bg); color: #16a34a; }

    /* Buttons */
    .btn {
      padding: 10px 18px;
      border-radius: var(--radius-sm);
      font-weight: 600;
      font-size: 13px;
      border: none;
      cursor: pointer;
      transition: all 0.2s ease;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      text-decoration: none;
    }

    .btn svg { width: 16px; height: 16px; }
    
    .btn-primary { 
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
      color: #fff;
      box-shadow: 0 2px 8px rgba(201, 165, 122, 0.25);
    }
    
    .btn-primary:hover { 
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(201, 165, 122, 0.35);
    }

    .btn-secondary {
      background: var(--bg);
      color: var(--text);
      border: 1px solid var(--border);
    }

    .btn-secondary:hover {
      background: #fff;
      border-color: var(--accent);
    }
    
    .btn-ghost {
      background: transparent;
      color: var(--muted);
      padding: 8px 12px;
    }

    .btn-ghost:hover {
      background: var(--bg);
      color: var(--text);
    }

    .btn-danger {
      background: rgba(239, 68, 68, 0.1);
      color: #dc2626;
    }

    .btn-danger:hover {
      background: rgba(239, 68, 68, 0.15);
    }

    .btn-sm {
      padding: 8px 14px;
      font-size: 12px;
    }

    /* Cards */
    .card {
      background: var(--card);
      border-radius: var(--radius);
      border: 1px solid var(--border);
      overflow: hidden;
    }

    .card-header {
      padding: 18px 24px;
      border-bottom: 1px solid var(--border);
      font-weight: 600;
      font-size: 15px;
      color: var(--text);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .card-body {
      padding: 24px;
    }

    /* Profile */
    .profile-header {
      display: flex;
      align-items: center;
      gap: 20px;
      margin-bottom: 28px;
      padding-bottom: 24px;
      border-bottom: 1px solid var(--border);
    }

    .profile-avatar {
      width: 72px;
      height: 72px;
      border-radius: 18px;
      background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      font-weight: 700;
      color: #fff;
      box-shadow: 0 4px 12px rgba(201, 165, 122, 0.25);
    }

    .profile-info h3 {
      font-size: 22px;
      font-weight: 700;
      margin: 0 0 4px;
      color: var(--text);
    }

    .profile-info p {
      font-size: 14px;
      color: var(--muted);
      margin: 0;
    }

    .form-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 20px;
    }

    .form-group { margin-bottom: 0; }
    .form-group.full { grid-column: 1 / -1; }

    .form-label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 8px;
    }

    .form-input {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      font-size: 14px;
      font-family: inherit;
      transition: all 0.2s ease;
      background: var(--bg);
    }

    .form-input:focus {
      outline: none;
      border-color: var(--accent);
      background: #fff;
      box-shadow: 0 0 0 3px var(--accent-light);
    }

    .form-divider {
      grid-column: 1 / -1;
      height: 1px;
      background: var(--border);
      margin: 4px 0;
    }

    .form-section-title {
      grid-column: 1 / -1;
      font-size: 12px;
      font-weight: 600;
      color: var(--muted);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    /* Balance Card */
    .balance-card {
      background: linear-gradient(135deg, #8b5a2b 0%, #6b4423 100%);
      border-radius: var(--radius);
      padding: 28px;
      color: #fff;
      margin-bottom: 24px;
      position: relative;
      overflow: hidden;
    }

    .balance-card::before {
      content: '';
      position: absolute;
      top: -40px;
      right: -40px;
      width: 160px;
      height: 160px;
      background: rgba(255,255,255,0.08);
      border-radius: 50%;
    }

    .balance-card::after {
      content: '';
      position: absolute;
      bottom: -60px;
      left: -30px;
      width: 120px;
      height: 120px;
      background: rgba(255,255,255,0.05);
      border-radius: 50%;
    }

    .balance-label {
      font-size: 14px;
      opacity: 0.85;
      margin-bottom: 8px;
      font-weight: 500;
    }

    .balance-amount {
      font-size: 38px;
      font-weight: 700;
      position: relative;
      z-index: 1;
    }

    /* Payment Item */
    .payment-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 18px 24px;
      border-bottom: 1px solid var(--border);
      transition: background 0.15s ease;
    }

    .payment-item:last-child {
      border-bottom: none;
    }

    .payment-item:hover {
      background: var(--bg);
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
    }

    .empty-icon {
      width: 80px;
      height: 80px;
      border-radius: 20px;
      background: var(--accent-light);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
    }

    .empty-icon svg {
      width: 36px;
      height: 36px;
      color: var(--accent);
    }

    .empty-state h3 {
      font-size: 18px;
      font-weight: 600;
      color: var(--text);
      margin: 0 0 8px;
    }

    .empty-state p {
      font-size: 14px;
      color: var(--muted);
      margin: 0 0 24px;
    }

    /* Modal */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(4px);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 1000;
      padding: 20px;
    }

    .modal-overlay.active { display: flex; }

    .modal {
      background: #fff;
      border-radius: var(--radius);
      width: 100%;
      max-width: 420px;
      box-shadow: var(--shadow-lg);
      animation: modalIn 0.25s ease;
    }

    @keyframes modalIn {
      from { opacity: 0; transform: scale(0.95) translateY(10px); }
      to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .modal-header {
      padding: 20px 24px;
      border-bottom: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .modal-title {
      font-size: 18px;
      font-weight: 700;
      margin: 0;
      color: var(--text);
    }

    .modal-close {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      border: none;
      background: var(--bg);
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--muted);
      transition: all 0.15s ease;
    }

    .modal-close:hover {
      background: var(--border);
      color: var(--text);
    }

    .modal-body {
      padding: 24px;
    }

    .modal-form label {
      display: block;
      margin-bottom: 16px;
    }

    .modal-form .label-text {
      display: block;
      font-size: 13px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 8px;
    }

    .modal-actions {
      display: flex;
      gap: 12px;
      margin-top: 8px;
    }

    .modal-actions .btn {
      flex: 1;
      justify-content: center;
      padding: 12px 20px;
    }

    /* Alerts */
    .alert {
      padding: 14px 18px;
      border-radius: var(--radius-sm);
      margin-bottom: 20px;
      font-size: 14px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert-error { background: rgba(239, 68, 68, 0.1); color: #dc2626; }
    .alert-success { background: var(--success-bg); color: #16a34a; }

    /* Toast */
    .toast {
      position: fixed;
      bottom: 24px;
      right: 24px;
      z-index: 9999;
      padding: 16px 24px;
      border-radius: var(--radius-sm);
      background: var(--text);
      color: #fff;
      display: flex;
      align-items: center;
      gap: 12px;
      box-shadow: var(--shadow-lg);
      font-size: 14px;
      font-weight: 500;
      animation: toastIn 0.3s ease;
    }

    @keyframes toastIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    .toast.hide {
      animation: toastOut 0.3s ease forwards;
    }

    @keyframes toastOut {
      from { opacity: 1; transform: translateY(0); }
      to { opacity: 0; transform: translateY(20px); }
    }

    /* Responsive */
    @media (max-width: 640px) {
      .dashboard-container { padding: 24px 16px 48px; }
      .dashboard-header h1 { font-size: 26px; }
      .stats-row { grid-template-columns: 1fr; gap: 12px; }
      .form-grid { grid-template-columns: 1fr; }
      .booking-card { 
        flex-direction: column; 
        align-items: flex-start;
        gap: 16px;
      }
      .booking-right {
        width: 100%;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
      }
      .dashboard-tabs {
        max-width: 100%;
      }
      .tab-btn {
        padding: 10px 12px;
        font-size: 13px;
      }
      .tab-btn span { display: none; }
    }
  </style>
</head>
<body>
  <?php include __DIR__ . '/includes/header.php'; ?>

  <main class="dashboard-container">
    <div class="dashboard-header">
      <h1>Welcome back, <?= htmlspecialchars($userName) ?>!</h1>
      <p>Manage your photography sessions and account settings</p>
    </div>

    <div class="dashboard-tabs">
      <button class="tab-btn <?= $activeTab === 'bookings' ? 'active' : '' ?>" data-tab="bookings">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        <span>Bookings</span>
      </button>
      <button class="tab-btn <?= $activeTab === 'profile' ? 'active' : '' ?>" data-tab="profile">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span>Profile</span>
      </button>
      <button class="tab-btn <?= $activeTab === 'payments' ? 'active' : '' ?>" data-tab="payments">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
        <span>Payments</span>
      </button>
    </div>

    <!-- Bookings Panel -->
    <div id="panel-bookings" class="panel <?= $activeTab === 'bookings' ? 'active' : '' ?>">
      <?php 
        $confirmedCount = 0;
        $pendingCount = 0;
        $totalSpent = 0;
        foreach($bookings as $b) {
          if($b['status'] === 'confirmed') $confirmedCount++;
          else if($b['status'] === 'pending') $pendingCount++;
          if($b['paid']) $totalSpent += $b['price'];
        }
      ?>
      <?php if (!empty($bookings)): ?>
      <div class="stats-row">
        <div class="stat-card">
          <div class="stat-value"><?= count($bookings) ?></div>
          <div class="stat-label">Total Bookings</div>
        </div>
        <div class="stat-card">
          <div class="stat-value"><?= $confirmedCount ?></div>
          <div class="stat-label">Confirmed</div>
        </div>
        <div class="stat-card">
          <div class="stat-value"><?= $pendingCount ?></div>
          <div class="stat-label">Pending</div>
        </div>
      </div>
      <?php endif; ?>

      <?php if (empty($bookings)): ?>
        <div class="card">
          <div class="empty-state">
            <div class="empty-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
            </div>
            <h3>No bookings yet</h3>
            <p>Start your photography journey by booking a session.</p>
            <a href="services.php" class="btn btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              Browse Services
            </a>
          </div>
        </div>
      <?php else: ?>
        <div class="booking-list">
        <?php foreach($bookings as $b): ?>
          <div class="booking-card">
            <div class="booking-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
            </div>
            <div class="booking-info">
              <div class="booking-title">
                <span class="booking-service"><?= htmlspecialchars($b['service']) ?></span>
                <?php 
                  $statusClass = 'status-' . $b['status'];
                  $statusText = ucfirst($b['status']);
                  
                  // Pending payment verification
                  if ($b['status'] === 'pending' && !empty($b['pending_payment'])) {
                    $statusClass = 'status-approved';
                    $statusText = 'Verifying Payment';
                  }
                  // Confirmed = booking confirmed
                  if ($b['status'] === 'confirmed') {
                    $statusClass = 'status-completed';
                    $statusText = 'Confirmed';
                  }
                ?>
                <span class="status-badge <?= $statusClass ?>">
                  <?php if ($b['status'] === 'pending' && empty($b['pending_payment']) && !$b['paid']): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  <?php endif; ?>
                  <?= $statusText ?>
                </span>
              </div>
              <div class="booking-meta">
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                  <?= htmlspecialchars($b['date']) ?>
                </span>
                <span>
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                  <?= htmlspecialchars($b['time']) ?>
                </span>
              </div>
            </div>
            <div class="booking-right">
              <div class="booking-price">₱<?= number_format($b['price'], 0) ?></div>
              <?php if($b['status'] === 'pending' && !$b['paid'] && empty($b['pending_payment'])): ?>
              <div class="booking-actions">
                <button type="button" class="btn btn-secondary btn-sm" onclick="openRescheduleModal(<?= intval($b['id']) ?>, '<?= htmlspecialchars($b['date']) ?>', '<?= htmlspecialchars($b['time']) ?>')">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  Edit
                </button>
                <form method="post" action="controller/cancel_booking.php" style="display:inline">
                  <input type="hidden" name="reservation_id" value="<?= intval($b['id']) ?>">
                  <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to cancel this booking?')">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Cancel
                  </button>
                </form>
              </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Profile Panel -->
    <div id="panel-profile" class="panel <?= $activeTab === 'profile' ? 'active' : '' ?>">
      <div class="card">
        <div class="card-header">
          <span>Account Settings</span>
        </div>
        <div class="card-body">
          <?php if (!empty($_GET['error'])): ?>
            <div class="alert alert-error">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              <?= htmlspecialchars($_GET['error']) ?>
            </div>
          <?php endif; ?>
          <?php if (!empty($_GET['success'])): ?>
            <div class="alert alert-success">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
              <?= htmlspecialchars($_GET['success']) ?>
            </div>
          <?php endif; ?>

          <div class="profile-header">
            <div class="profile-avatar">
              <?= strtoupper(substr($profile['name'] ?? 'U', 0, 2)) ?>
            </div>
            <div class="profile-info">
              <h3><?= htmlspecialchars($profile['name'] ?? '') ?></h3>
              <p><?= htmlspecialchars($profile['email'] ?? '') ?></p>
            </div>
          </div>

          <form method="post" action="controller/update_profile.php">
            <div class="form-grid">
              <div class="form-section-title">Personal Information</div>
              
              <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-input" value="<?= htmlspecialchars($profile['name'] ?? '') ?>" required>
              </div>
              <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" required>
              </div>
              <div class="form-group full">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-input" value="<?= htmlspecialchars($profile['phone_number'] ?? '') ?>" placeholder="+63 900 000 0000">
              </div>

              <div class="form-divider"></div>
              <div class="form-section-title">Change Password</div>
              
              <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" name="current_password" class="form-input" placeholder="Enter current password">
              </div>
              <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-input" placeholder="Enter new password">
              </div>

              <div class="form-group full" style="margin-top: 8px;">
                <button type="submit" class="btn btn-primary">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                  Save Changes
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Payments Panel -->
    <div id="panel-payments" class="panel <?= $activeTab === 'payments' ? 'active' : '' ?>">
      <div class="balance-card">
        <div class="balance-label">Outstanding Balance</div>
        <div class="balance-amount">₱<?= number_format($totalPending, 2) ?></div>
      </div>

      <div class="card">
        <div class="card-header">
          <span>Pending Payments</span>
          <span style="font-size: 13px; font-weight: 500; color: var(--muted);"><?= count($payments_due) ?> items</span>
        </div>
        <?php if (empty($payments_due)): ?>
          <div class="empty-state">
            <div class="empty-icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h3>All caught up!</h3>
            <p>You have no pending payments at this time.</p>
          </div>
        <?php else: ?>
          <?php foreach ($payments_due as $p): ?>
            <div class="payment-item">
              <div class="booking-info">
                <div class="booking-service"><?= htmlspecialchars($p['service']) ?></div>
                <div class="booking-meta">
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <?= htmlspecialchars($p['date']) ?>
                  </span>
                  <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <?= htmlspecialchars($p['time']) ?>
                  </span>
                </div>
              </div>
              <div style="display:flex;align-items:center;gap:16px">
                <div class="booking-price">₱<?= number_format($p['price'], 0) ?></div>
                <button type="button" class="btn btn-primary btn-sm" onclick="openPaymentModal(<?= intval($p['reservation_id']) ?>)">
                  Pay Now
                </button>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>
  </main>

  <script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('panel-' + this.dataset.tab).classList.add('active');
      });
    });
  </script>

  <?php if (!empty($_GET['booked'])): ?>
    <div id="toast" class="toast">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      Booking confirmed successfully!
    </div>
    <script>setTimeout(()=>{const t=document.getElementById('toast');if(t){t.classList.add('hide');setTimeout(()=>t.remove(),300)}},3000);</script>
  <?php endif; ?>
  
  <?php if (!empty($_GET['cancelled'])): ?>
    <div id="toast" class="toast">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      Booking cancelled successfully
    </div>
    <script>setTimeout(()=>{const t=document.getElementById('toast');if(t){t.classList.add('hide');setTimeout(()=>t.remove(),300)}},3000);</script>
  <?php endif; ?>
  
  <?php if (!empty($_GET['paid'])): ?>
    <div id="toast" class="toast">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      Payment submitted for verification!
    </div>
    <script>setTimeout(()=>{const t=document.getElementById('toast');if(t){t.classList.add('hide');setTimeout(()=>t.remove(),300)}},3000);</script>
  <?php endif; ?>
  
  <?php if (!empty($_GET['rescheduled'])): ?>
    <div id="toast" class="toast">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      Booking rescheduled successfully!
    </div>
    <script>setTimeout(()=>{const t=document.getElementById('toast');if(t){t.classList.add('hide');setTimeout(()=>t.remove(),300)}},3000);</script>
  <?php endif; ?>

  <!-- Reschedule Modal -->
  <div class="modal-overlay" id="rescheduleModal">
    <div class="modal">
      <div class="modal-header">
        <h3 class="modal-title">Reschedule Booking</h3>
        <button class="modal-close" onclick="closeRescheduleModal()">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="modal-body">
        <form class="modal-form" method="post" action="controller/reschedule_booking.php">
          <input type="hidden" name="reservation_id" id="reschedule_reservation_id">
          <label>
            <span class="label-text">New Date</span>
            <input type="date" name="new_date" id="reschedule_date" class="form-input" required min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
          </label>
          <label>
            <span class="label-text">New Time</span>
            <input type="time" name="new_time" id="reschedule_time" class="form-input" required>
          </label>
          <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeRescheduleModal()">Cancel</button>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Payment Modal -->
  <div class="modal-overlay" id="paymentModal">
    <div class="modal">
      <div class="modal-header">
        <h3 class="modal-title">Complete Payment</h3>
        <button class="modal-close" onclick="closePaymentModal()">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="modal-body">
        <form class="modal-form" method="post" action="controller/pay.php" enctype="multipart/form-data">
          <input type="hidden" name="reservation_id" id="payment_reservation_id">
          
          <label>
            <span class="label-text">Payment Method</span>
            <select name="payment_method" id="payment_method" class="form-input" onchange="togglePaymentFields()">
              <option value="gcash">GCash</option>
              <option value="bank">Bank Transfer</option>
              <option value="cash">Cash (Pay in person)</option>
            </select>
          </label>
          
          <div id="onlinePaymentFields">
            <div style="background: var(--accent-light); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 16px;">
              <div id="gcashInfo">
                <p style="margin: 0 0 8px; font-weight: 600; color: var(--text); font-size: 14px;">GCash Number:</p>
                <p style="margin: 0; font-size: 20px; font-weight: 700; color: var(--brown);">0917 123 4567</p>
              </div>
              <div id="bankInfo" style="display: none;">
                <p style="margin: 0 0 8px; font-weight: 600; color: var(--text); font-size: 14px;">Bank Details (BDO):</p>
                <p style="margin: 0; font-size: 20px; font-weight: 700; color: var(--brown);">1234 5678 9012</p>
                <p style="margin: 4px 0 0; font-size: 13px; color: var(--muted);">Account Name: PhotoReserve Studio</p>
              </div>
            </div>
            
            <label>
              <span class="label-text">Reference Number</span>
              <input type="text" name="reference_number" id="reference_number" class="form-input" placeholder="Enter transaction reference">
            </label>
            
            <label>
              <span class="label-text">Upload Receipt</span>
              <input type="file" name="receipt_image" id="receipt_image" class="form-input" accept="image/*" style="padding: 10px;">
            </label>
          </div>
          
          <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closePaymentModal()">Cancel</button>
            <button type="submit" class="btn btn-primary">Submit Payment</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function openRescheduleModal(reservationId, currentDate, currentTime) {
      document.getElementById('reschedule_reservation_id').value = reservationId;
      document.getElementById('reschedule_date').value = currentDate;
      document.getElementById('reschedule_time').value = currentTime;
      document.getElementById('rescheduleModal').classList.add('active');
    }
    
    function closeRescheduleModal() {
      document.getElementById('rescheduleModal').classList.remove('active');
    }
    
    function openPaymentModal(reservationId) {
      document.getElementById('payment_reservation_id').value = reservationId;
      document.getElementById('paymentModal').classList.add('active');
      togglePaymentFields();
    }
    
    function closePaymentModal() {
      document.getElementById('paymentModal').classList.remove('active');
    }
    
    function togglePaymentFields() {
      const method = document.getElementById('payment_method').value;
      const onlineFields = document.getElementById('onlinePaymentFields');
      const gcashInfo = document.getElementById('gcashInfo');
      const bankInfo = document.getElementById('bankInfo');
      const refField = document.getElementById('reference_number');
      const receiptField = document.getElementById('receipt_image');
      
      if (method === 'cash') {
        onlineFields.style.display = 'none';
        refField.removeAttribute('required');
        receiptField.removeAttribute('required');
      } else {
        onlineFields.style.display = 'block';
        refField.setAttribute('required', 'required');
        receiptField.setAttribute('required', 'required');
        
        if (method === 'gcash') {
          gcashInfo.style.display = 'block';
          bankInfo.style.display = 'none';
        } else {
          gcashInfo.style.display = 'none';
          bankInfo.style.display = 'block';
        }
      }
    }
    
    // Close modals on overlay click
    document.getElementById('rescheduleModal').addEventListener('click', function(e) {
      if (e.target === this) closeRescheduleModal();
    });
    document.getElementById('paymentModal').addEventListener('click', function(e) {
      if (e.target === this) closePaymentModal();
    });

    // Notification dropdown toggle (backup for header.js)
    (function() {
      const notifBell = document.getElementById('notifBell');
      const notifDropdown = document.getElementById('notifDropdown');
      const userButton = document.getElementById('userButton');
      const userDropdown = document.getElementById('userDropdown');

      if (notifBell && notifDropdown) {
        notifBell.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();
          const isOpen = notifDropdown.classList.contains('show');
          notifDropdown.classList.toggle('show');
          
          // Close user dropdown when opening notifications
          if (userDropdown && !isOpen) {
            userDropdown.style.display = 'none';
            if (userButton) userButton.setAttribute('aria-expanded', 'false');
          }

          // Mark notifications as read
          if (!isOpen) {
            fetch('/PhotographyReservationSystem/controller/mark_notifications_read.php', { method: 'POST' })
              .then(() => {
                const badge = notifBell.querySelector('.notification-badge');
                if (badge) badge.style.display = 'none';
              })
              .catch(() => {});
          }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
          if (!notifBell.contains(e.target) && !notifDropdown.contains(e.target)) {
            notifDropdown.classList.remove('show');
          }
        });
      }

      // User dropdown toggle
      if (userButton && userDropdown) {
        userButton.addEventListener('click', function(e) {
          e.stopPropagation();
          const open = userButton.getAttribute('aria-expanded') === 'true';
          userButton.setAttribute('aria-expanded', open ? 'false' : 'true');
          userDropdown.style.display = open ? 'none' : 'block';
          // Close notification dropdown
          if (notifDropdown) notifDropdown.classList.remove('show');
        });

        document.addEventListener('click', function(e) {
          if (!userButton.contains(e.target) && !userDropdown.contains(e.target)) {
            userDropdown.style.display = 'none';
            userButton.setAttribute('aria-expanded', 'false');
          }
        });
      }
    })();
  </script>

  <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
