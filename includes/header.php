<?php
// Shared header include
if (session_status() === PHP_SESSION_NONE) session_start();
$isLoggedIn = !empty($_SESSION['user_id']);
$userName = $_SESSION['name'] ?? '';
$userRole = $_SESSION['role'] ?? '';

// Base path for links (root-relative to project)
$base = '/PhotographyReservationSystem/';
?>

<header class="topbar">
  <div class="wrap">
    <a href="<?= $base ?>index.php" class="brand" style="text-decoration:none;color:inherit;">
      <div class="logo"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
      <span class="brand-name">PhotoReserve</span>
    </a>
    <nav class="nav">
      <?php $cur = basename(parse_url(
        ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH)); ?>
      <?php if (empty($hide_home)): ?>
        <a href="<?= $base ?>index.php" class="nav-link <?= ($cur==='index.php')? 'active':'' ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          <span>Home</span>
        </a>
      <?php endif; ?>
      <?php if (empty($hide_services)): ?>
        <a href="<?= $base ?>services.php" class="nav-link <?= ($cur==='services.php')? 'active':'' ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
          <span>Services</span>
        </a>
      <?php endif; ?>
      <?php if ($isLoggedIn && $userRole !== 'admin'): ?>
        <a href="<?= $base ?>dashboard.php" class="nav-link <?= ($cur==='dashboard.php')? 'active':'' ?>">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
          <span>Dashboard</span>
        </a>
      <?php endif; ?>
    </nav>

    <div class="user-area">
      <?php if ($isLoggedIn): ?>
        <?php
        // Get notifications
        $notifCount = 0;
        $notifications = [];
        try {
          global $pdo;
          if (!isset($pdo)) require_once __DIR__ . '/../db.php';
          $nStmt = $pdo->prepare('SELECT COUNT(*) AS cnt FROM notifications WHERE recipient_id = :uid AND is_read = 0');
          $nStmt->execute(['uid' => $_SESSION['user_id']]);
          $nRow = $nStmt->fetch();
          $notifCount = $nRow ? intval($nRow['cnt']) : 0;
          
          // Get recent notifications
          $nListStmt = $pdo->prepare('SELECT * FROM notifications WHERE recipient_id = :uid ORDER BY created_at DESC LIMIT 5');
          $nListStmt->execute(['uid' => $_SESSION['user_id']]);
          $notifications = $nListStmt->fetchAll();
        } catch (Exception $e) {}
        ?>
        <button class="user-button" id="userButton" aria-haspopup="true" aria-expanded="false">
          <span class="avatar"><?= htmlspecialchars(strtoupper(substr($userName,0,2))) ?></span>
          <span class="welcome">Welcome, <strong><?= htmlspecialchars($userName) ?></strong></span>
          <span class="chev">▾</span>
        </button>
        
        <!-- Notification Bell with Dropdown -->
        <div class="notification-wrapper">
          <button class="notification-bell" id="notifBell" title="Notifications">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <?php if ($notifCount > 0): ?>
              <span class="notification-badge"><?= $notifCount ?></span>
            <?php endif; ?>
          </button>
          
          <div class="notif-dropdown" id="notifDropdown">
            <div class="notif-header">
              <span class="notif-title">Notifications</span>
              <?php if ($notifCount > 0): ?>
                <span class="notif-count-badge"><?= $notifCount ?> new</span>
              <?php endif; ?>
            </div>
            <div class="notif-list">
              <?php if (empty($notifications)): ?>
                <div class="notif-empty">
                  <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                  <p>No notifications yet</p>
                </div>
              <?php else: ?>
                <?php foreach ($notifications as $notif): ?>
                  <div class="notif-item <?= $notif['is_read'] ? '' : 'unread' ?>">
                    <div class="notif-icon">
                      <?php if (stripos($notif['message'], 'confirmed') !== false || stripos($notif['message'], 'approved') !== false): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                      <?php elseif (stripos($notif['message'], 'cancelled') !== false || stripos($notif['message'], 'rejected') !== false): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                      <?php elseif (stripos($notif['message'], 'payment') !== false): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a57a" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                      <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a57a" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/></svg>
                      <?php endif; ?>
                    </div>
                    <div class="notif-content">
                      <p class="notif-message"><?= htmlspecialchars($notif['message']) ?></p>
                      <span class="notif-time"><?= date('M j, g:i A', strtotime($notif['created_at'])) ?></span>
                    </div>
                    <?php if (!$notif['is_read']): ?>
                      <span class="notif-dot"></span>
                    <?php endif; ?>
                  </div>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <a href="<?= $base ?>notifications.php" class="notif-footer">View all notifications</a>
          </div>
        </div>
        
        <div class="dropdown-menu" id="userDropdown" aria-hidden="true">
          <?php if ($userRole === 'admin'): ?>
            <a href="<?= $base ?>adminpage/admin_dashboard.php">Admin Dashboard</a>
          <?php else: ?>
            <a href="<?= $base ?>dashboard.php#profile">Profile</a>
          <?php endif; ?>
          <a href="<?= $base ?>controller/logout.php">Logout</a>
        </div>
      <?php else: ?>
        <?php if (empty($hide_auth)): ?>
          <a class="btn" href="<?= $base ?>login.php">Login</a>
          <a class="btn btn-register" href="<?= $base ?>register.php">Register</a>
        <?php endif; ?>
      <?php endif; ?>
    </div>
  </div>
</header>
<script src="<?= $base ?>scripts/header.js" defer></script>
