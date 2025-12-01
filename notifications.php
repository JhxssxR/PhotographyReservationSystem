<?php
// Notifications Page
session_start();
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/middleware/auth.php';

$userId = $_SESSION['user_id'] ?? null;
$userName = $_SESSION['name'] ?? 'User';

$notifications = [];
if ($userId) {
  try {
    $stmt = $pdo->prepare('SELECT * FROM notifications WHERE recipient_id = :uid ORDER BY created_at DESC LIMIT 50');
    $stmt->execute(['uid' => $userId]);
    $notifications = $stmt->fetchAll();
    
    // Mark all as read
    $updateStmt = $pdo->prepare('UPDATE notifications SET is_read = 1 WHERE recipient_id = :uid AND is_read = 0');
    $updateStmt->execute(['uid' => $userId]);
  } catch (Exception $e) {}
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Notifications - PhotoReserve</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="cssfile/index.css">
  <style>
    :root {
      --bg: #ffffff;
      --card: #ffffff;
      --muted: #6b7280;
      --brown: #7b4f36;
      --accent: #c9a57a;
    }
    body { background: #f8f9fa; }
    .notifications-container { max-width: 700px; margin: 0 auto; padding: 32px 24px; }
    .page-header { margin-bottom: 24px; }
    .page-header h1 { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0 0 6px; }
    .page-header p { color: var(--muted); font-size: 14px; margin: 0; }
    
    .notification-list { background: var(--card); border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; }
    .notification-item {
      display: flex; align-items: flex-start; gap: 14px;
      padding: 16px 20px; border-bottom: 1px solid #e5e7eb;
      transition: background 0.15s;
    }
    .notification-item:last-child { border-bottom: none; }
    .notification-item:hover { background: #f9fafb; }
    .notification-item.unread { background: rgba(201,165,122,0.05); }
    
    .notif-icon {
      width: 40px; height: 40px; border-radius: 10px;
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .notif-icon.booking { background: rgba(59,130,246,0.1); color: #3b82f6; }
    .notif-icon.payment { background: rgba(16,185,129,0.1); color: #10b981; }
    .notif-icon.system { background: rgba(201,165,122,0.1); color: var(--accent); }
    
    .notif-content { flex: 1; }
    .notif-message { font-size: 14px; color: #1f2937; margin-bottom: 4px; line-height: 1.4; }
    .notif-time { font-size: 12px; color: var(--muted); }
    
    .empty-state {
      text-align: center; padding: 60px 20px; color: var(--muted);
    }
    .empty-icon {
      width: 56px; height: 56px; background: #f3f4f6; border-radius: 50%;
      display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;
    }
    .empty-state h3 { color: #1f2937; margin-bottom: 6px; font-size: 16px; }
  </style>
</head>
<body>
  <?php
  // Hide home/services for admin
  $userRole = $_SESSION['role'] ?? '';
  if ($userRole === 'admin') {
    $hide_home = true;
    $hide_services = true;
  }
  include __DIR__ . '/includes/header.php';
  ?>

  <main class="notifications-container">
    <div class="page-header">
      <h1>Notifications</h1>
      <p>Stay updated on your bookings and payments</p>
    </div>

    <div class="notification-list">
      <?php if (empty($notifications)): ?>
        <div class="empty-state">
          <div class="empty-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          </div>
          <h3>No notifications yet</h3>
          <p>When you have updates, they'll appear here.</p>
        </div>
      <?php else: ?>
        <?php foreach ($notifications as $n): ?>
          <div class="notification-item <?= $n['is_read'] ? '' : 'unread' ?>">
            <div class="notif-icon <?= htmlspecialchars($n['type']) ?>">
              <?php if ($n['type'] === 'booking'): ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              <?php elseif ($n['type'] === 'payment'): ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              <?php else: ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
              <?php endif; ?>
            </div>
            <div class="notif-content">
              <div class="notif-message"><?= htmlspecialchars($n['message']) ?></div>
              <div class="notif-time"><?= date('M j, Y \a\t g:i A', strtotime($n['created_at'])) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </main>

  <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
