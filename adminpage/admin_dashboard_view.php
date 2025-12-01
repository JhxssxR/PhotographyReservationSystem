<?php
// Admin Dashboard View — matching prototype with beige/brown theme
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard - PhotoReserve</title>
  <link rel="icon" type="image/svg+xml" href="../assets/favicon.svg">
  <style>
    :root {
      --bg: #f7efe6;
      --card: #ffffff;
      --muted: #6b7280;
      --brown: #7b4f36;
      --accent: #c9a57a;
      --accent-light: #e9d9c8;
      --accent-gradient: linear-gradient(135deg, #c9a57a 0%, #a67c52 100%);
    }
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { 
      font-family: 'Inter', system-ui, -apple-system, sans-serif; 
      background: linear-gradient(180deg, #f7efe6, #ffffff); 
      min-height: 100vh;
      color: #1f2937;
    }

    /* Header */
    .header {
      background: #fff;
      border-bottom: 1px solid #e5e7eb;
      padding: 0 32px;
      height: 64px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .header-left { display: flex; align-items: center; gap: 40px; }
    .brand { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 18px; color: #1f2937; }
    .brand-icon {
      width: 36px; height: 36px;
      background: var(--accent-gradient);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-size: 18px;
    }
    .nav { display: flex; gap: 8px; }
    .nav a {
      display: flex; align-items: center; gap: 6px;
      padding: 8px 14px; border-radius: 8px;
      text-decoration: none; color: var(--muted);
      font-weight: 500; font-size: 14px; transition: all 0.15s;
    }
    .nav a:hover { background: #f3f4f6; color: #1f2937; }
    .nav a.active { color: var(--brown); background: rgba(201,165,122,0.1); }
    .header-right { display: flex; align-items: center; gap: 16px; }
    .user-info { display: flex; align-items: center; gap: 10px; }
    .user-avatar {
      width: 36px; height: 36px; border-radius: 50%;
      background: var(--accent-gradient);
      display: flex; align-items: center; justify-content: center;
      color: #fff; font-weight: 600; font-size: 14px;
    }
    .user-details { line-height: 1.3; }
    .user-name { font-weight: 600; font-size: 14px; color: #1f2937; }
    .user-role { font-size: 12px; color: var(--muted); }
    .btn-logout {
      display: flex; align-items: center; gap: 6px;
      padding: 8px 14px; border-radius: 8px; border: none;
      background: transparent; color: var(--muted);
      font-weight: 500; font-size: 14px; cursor: pointer;
      text-decoration: none; transition: all 0.15s;
    }
    .btn-logout:hover { background: #f3f4f6; color: #1f2937; }

    /* Notification Bell */
    .notification-wrapper { position: relative; }
    .notification-bell {
      position: relative; display: flex; align-items: center; justify-content: center;
      width: 40px; height: 40px; background: rgba(201,165,122,0.1);
      border-radius: 10px; cursor: pointer; transition: all 0.2s ease; border: none;
    }
    .notification-bell:hover { background: rgba(201,165,122,0.2); transform: translateY(-1px); }
    .notification-bell svg { width: 20px; height: 20px; color: var(--accent); stroke: var(--accent); }
    .notification-badge {
      position: absolute; top: 4px; right: 4px; min-width: 18px; height: 18px;
      background: linear-gradient(135deg, #e74c3c, #c0392b); color: #fff;
      font-size: 10px; font-weight: 700; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      padding: 0 4px; box-shadow: 0 2px 6px rgba(231,76,60,0.4);
      animation: pulse 2s infinite;
    }
    @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }

    /* Notification Dropdown */
    .notif-dropdown {
      position: absolute; top: calc(100% + 8px); right: 0; width: 340px;
      background: #fffcf8; border-radius: 16px;
      box-shadow: 0 12px 40px rgba(123,79,54,0.18);
      border: 1px solid rgba(180,140,100,0.35);
      display: none; z-index: 1000; overflow: hidden;
    }
    .notif-dropdown.show { display: block; animation: dropdownFade .2s ease; }
    @keyframes dropdownFade { from { opacity: 0; transform: translateY(-8px); } to { opacity: 1; transform: translateY(0); } }
    .notif-header {
      display: flex; align-items: center; justify-content: space-between;
      padding: 16px 18px; background: linear-gradient(135deg, #e8d5be 0%, #d4c4a8 100%);
      border-bottom: 1px solid rgba(160,120,80,0.25);
    }
    .notif-title { font-weight: 700; font-size: 15px; color: #5a3d2b; }
    .notif-count-badge {
      background: linear-gradient(135deg, #a67c52 0%, #8b5a2b 100%);
      color: #fff; font-size: 11px; font-weight: 600;
      padding: 4px 10px; border-radius: 20px;
      box-shadow: 0 2px 6px rgba(138,90,43,0.3);
    }
    .notif-list { max-height: 320px; overflow-y: auto; background: #fffcf8; }
    .notif-list::-webkit-scrollbar { width: 5px; }
    .notif-list::-webkit-scrollbar-track { background: #e8d9c8; }
    .notif-list::-webkit-scrollbar-thumb { background: #b8956b; border-radius: 4px; }
    .notif-list::-webkit-scrollbar-thumb:hover { background: #a67c52; }
    .notif-empty { padding: 40px 20px; text-align: center; color: #8b7355; }
    .notif-empty svg { color: #c9a57a; margin-bottom: 12px; }
    .notif-empty p { font-size: 14px; margin: 0; }
    .notif-item {
      display: flex; align-items: flex-start; gap: 12px;
      padding: 14px 18px; border-bottom: 1px solid rgba(180,140,100,0.2);
      transition: background .15s ease; position: relative; background: #fffcf8;
    }
    .notif-item:hover { background: #f5ebe0; }
    .notif-item.unread { background: #f0e4d4; }
    .notif-item.unread:hover { background: #e8d9c8; }
    .notif-icon {
      width: 32px; height: 32px; border-radius: 8px;
      background: linear-gradient(135deg, #e8d5be 0%, #d9c9b0 100%);
      display: flex; align-items: center; justify-content: center; flex-shrink: 0;
      border: 1px solid rgba(180,140,100,0.2);
    }
    .notif-content { flex: 1; min-width: 0; }
    .notif-message {
      font-size: 13px; color: #4a3728; margin: 0 0 4px; line-height: 1.4;
      display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    }
    .notif-time { font-size: 11px; color: #8b7355; }
    .notif-dot { width: 8px; height: 8px; background: linear-gradient(135deg, #c9a57a 0%, #a67c52 100%); border-radius: 50%; flex-shrink: 0; margin-top: 4px; box-shadow: 0 1px 3px rgba(166,124,82,0.4); }
    .notif-footer {
      display: block; padding: 14px 18px; text-align: center;
      color: #5a3d2b; font-size: 13px; font-weight: 600; text-decoration: none;
      background: linear-gradient(135deg, #e8d5be 0%, #d4c4a8 100%);
      border-top: 1px solid rgba(160,120,80,0.25); transition: all .15s ease;
    }
    .notif-footer:hover { background: linear-gradient(135deg, #dcc9b0 0%, #c9b898 100%); color: #4a3728; }

    /* Main */
    .main { max-width: 1100px; margin: 0 auto; padding: 32px 24px; }
    .page-title { font-size: 28px; font-weight: 700; color: #1f2937; margin-bottom: 6px; }
    .page-subtitle { color: var(--muted); font-size: 15px; margin-bottom: 28px; }

    /* KPI Cards */
    .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 32px; }
    .kpi-card {
      background: var(--card); border-radius: 16px; padding: 20px;
      border: 1px solid #e5e7eb; position: relative; overflow: hidden;
    }
    .kpi-card.highlight {
      background: var(--accent-gradient); border: none; color: #fff;
    }
    .kpi-card.highlight .kpi-label,
    .kpi-card.highlight .kpi-value { color: #fff; }
    .kpi-card.highlight .kpi-icon { background: rgba(255,255,255,0.2); color: #fff; }
    .kpi-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 12px; }
    .kpi-icon {
      width: 40px; height: 40px; border-radius: 10px;
      background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-size: 18px;
    }
    .kpi-icon.purple { background: rgba(139,92,246,0.1); color: #8b5cf6; }
    .kpi-icon.yellow { background: rgba(245,158,11,0.1); color: #f59e0b; }
    .kpi-icon.blue { background: rgba(59,130,246,0.1); color: #3b82f6; }
    .kpi-trend { font-size: 18px; opacity: 0.8; }
    .kpi-value { font-size: 32px; font-weight: 700; color: #1f2937; margin-bottom: 4px; }
    .kpi-label { font-size: 14px; color: var(--muted); }

    /* Tabs */
    .tabs { display: flex; gap: 32px; border-bottom: 1px solid #e5e7eb; margin-bottom: 24px; }
    .tab {
      display: flex; align-items: center; gap: 8px;
      padding: 12px 0; border: none; background: transparent;
      color: var(--muted); font-weight: 500; font-size: 14px;
      cursor: pointer; border-bottom: 2px solid transparent;
      margin-bottom: -1px; transition: all 0.15s;
    }
    .tab:hover { color: #1f2937; }
    .tab.active { color: var(--brown); border-bottom-color: var(--brown); }
    .tab-icon { font-size: 16px; }

    /* Panels */
    .panel { display: none; }
    .panel.active { display: block; }

    /* Empty State */
    .empty-state {
      background: var(--card); border-radius: 16px; border: 1px solid #e5e7eb;
      padding: 60px 40px; text-align: center;
    }
    .empty-icon {
      width: 64px; height: 64px; margin: 0 auto 16px; border-radius: 50%;
      background: rgba(16,185,129,0.1); display: flex; align-items: center; justify-content: center;
      font-size: 32px; color: #10b981;
    }
    .empty-title { font-size: 18px; font-weight: 600; color: #1f2937; margin-bottom: 6px; }
    .empty-text { color: var(--muted); font-size: 14px; }

    /* Table Card */
    .table-card {
      background: var(--card); border-radius: 16px; border: 1px solid #e5e7eb;
      overflow: hidden; margin-top: 20px;
    }
    .table-header { padding: 16px 20px; border-bottom: 1px solid #e5e7eb; font-weight: 600; font-size: 16px; }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 14px 20px; text-align: left; font-size: 14px; }
    .table th {
      background: #f9fafb; color: var(--muted); font-weight: 600;
      font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;
    }
    .table tr:not(:last-child) td { border-bottom: 1px solid #e5e7eb; }
    .table tr:hover td { background: #f9fafb; }

    /* Status */
    .status {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 500;
    }
    .status-pending { background: rgba(245,158,11,0.1); color: #d97706; }
    .status-approved { background: rgba(59,130,246,0.1); color: #3b82f6; }
    .status-confirmed { background: rgba(16,185,129,0.1); color: #059669; }
    .status-cancelled { background: rgba(239,68,68,0.1); color: #dc2626; }
    .status-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }

    /* Action Buttons */
    .btn-action {
      padding: 6px 12px; border-radius: 8px; border: none;
      font-weight: 500; font-size: 13px; cursor: pointer; transition: all 0.15s;
    }
    .btn-confirm { background: rgba(16,185,129,0.1); color: #059669; }
    .btn-confirm:hover { background: rgba(16,185,129,0.2); }
    .btn-cancel { background: transparent; color: #dc2626; }
    .btn-cancel:hover { background: rgba(239,68,68,0.1); }
    .btn-view { background: rgba(201,165,122,0.1); color: var(--brown); }
    .btn-view:hover { background: rgba(201,165,122,0.2); }
    .btn-delete { background: rgba(239,68,68,0.1); color: #dc2626; }
    .btn-delete:hover { background: rgba(239,68,68,0.2); }
    .btn-clear { background: rgba(239,68,68,0.1); color: #dc2626; padding: 8px 16px; border-radius: 8px; border: none; font-weight: 500; font-size: 13px; cursor: pointer; transition: all 0.15s; display: inline-flex; align-items: center; gap: 6px; }
    .btn-clear:hover { background: rgba(239,68,68,0.2); }
    .table-header-actions { display: flex; align-items: center; justify-content: space-between; }

    /* Filter Buttons */
    .filter-bar { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .filter-label { display: flex; align-items: center; gap: 8px; color: var(--muted); font-size: 14px; font-weight: 500; }
    .filter-buttons { display: flex; gap: 8px; flex-wrap: wrap; }
    .filter-btn {
      padding: 8px 18px; border-radius: 20px; border: 1px solid #e5e7eb;
      background: #fff; color: #374151; font-size: 13px; font-weight: 500;
      cursor: pointer; transition: all 0.15s;
    }
    .filter-btn:hover { border-color: #c9a57a; color: var(--brown); }
    .filter-btn.active {
      background: linear-gradient(135deg, #c9a57a 0%, #e9d9c8 100%);
      color: #5c4033; border-color: transparent;
    }
    .filter-btn.active-pending { background: linear-gradient(135deg, #c9a57a 0%, #e9d9c8 100%); color: #5c4033; border-color: transparent; }
    .filter-btn.active-confirmed { background: linear-gradient(135deg, #c9a57a 0%, #e9d9c8 100%); color: #5c4033; border-color: transparent; }
    .filter-btn.active-completed { background: linear-gradient(135deg, #c9a57a 0%, #e9d9c8 100%); color: #5c4033; border-color: transparent; }
    .filter-btn.active-cancelled { background: linear-gradient(135deg, #c9a57a 0%, #e9d9c8 100%); color: #5c4033; border-color: transparent; }

    /* View Booking Modal */
    .modal-overlay {
      position: fixed; top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1000;
    }
    .modal-overlay.active { display: flex; }
    .modal {
      background: #fff; border-radius: 16px; width: 100%; max-width: 500px;
      box-shadow: 0 20px 40px rgba(0,0,0,0.15); overflow: hidden;
    }
    .modal-image {
      width: 100%; height: 200px; object-fit: cover; background: #f3f4f6;
    }
    .modal-body { padding: 24px; }
    .modal-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; }
    .modal-title { font-size: 20px; font-weight: 600; color: #1f2937; }
    .modal-close {
      background: none; border: none; cursor: pointer; padding: 4px;
      color: #6b7280; transition: color 0.15s;
    }
    .modal-close:hover { color: #1f2937; }
    .modal-details { display: grid; gap: 16px; }
    .detail-row { display: flex; justify-content: space-between; align-items: center; padding-bottom: 12px; border-bottom: 1px solid #f3f4f6; }
    .detail-row:last-child { border-bottom: none; padding-bottom: 0; }
    .detail-label { font-size: 13px; color: #6b7280; }
    .detail-value { font-size: 14px; font-weight: 500; color: #1f2937; }
    .detail-value.price { font-size: 18px; font-weight: 600; color: var(--brown); }
    .modal-status { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 500; }
    .modal-close-btn {
      width: 100%; margin-top: 24px; padding: 12px 20px;
      background: linear-gradient(135deg, #c9a57a 0%, #e9d9c8 100%);
      border: none; border-radius: 10px; color: #5c4033;
      font-weight: 600; font-size: 14px; cursor: pointer;
      transition: all 0.15s;
    }
    .modal-close-btn:hover { filter: brightness(0.95); }

    @media (max-width: 900px) {
      .kpi-grid { grid-template-columns: repeat(2, 1fr); }
      .nav { display: none; }
      .tabs { gap: 16px; overflow-x: auto; }
    }
    @media (max-width: 600px) {
      .kpi-grid { grid-template-columns: 1fr; }
      .header { padding: 0 16px; }
      .main { padding: 20px 16px; }
    }

    /* Toast Notification */
    .toast {
      position: fixed; bottom: 24px; right: 24px;
      background: #fff; padding: 16px 20px; border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      display: flex; align-items: center; gap: 12px;
      font-weight: 500; font-size: 14px; z-index: 1001;
      animation: slideIn 0.3s ease;
    }
    .toast.success { border-left: 4px solid #10b981; }
    .toast.error { border-left: 4px solid #ef4444; }
    .toast.hide { animation: slideOut 0.3s ease forwards; }
    @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes slideOut { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
  </style>
</head>
<body>
  <!-- Header -->
  <header class="header">
    <div class="header-left">
      <div class="brand">
        <div class="brand-icon"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg></div>
        <span>PhotoReserve</span>
      </div>
      <nav class="nav">
        <a href="../index.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg> Home</a>
        <a href="../services.php"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg> Book Service</a>
        <a href="admin_dashboard.php" class="active"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg> Dashboard</a>
      </nav>
    </div>
    <div class="header-right">
      <!-- Notification Bell with Dropdown -->
      <div class="notification-wrapper">
        <button class="notification-bell" id="adminNotifBell" title="Notifications">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          <?php if ($adminNotifCount > 0): ?><span class="notification-badge"><?= $adminNotifCount ?></span><?php endif; ?>
        </button>
        
        <div class="notif-dropdown" id="adminNotifDropdown">
          <div class="notif-header">
            <span class="notif-title">Notifications</span>
            <?php if ($adminNotifCount > 0): ?>
              <span class="notif-count-badge"><?= $adminNotifCount ?> new</span>
            <?php endif; ?>
          </div>
          <div class="notif-list">
            <?php if (empty($adminNotifications)): ?>
              <div class="notif-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                <p>No notifications yet</p>
              </div>
            <?php else: ?>
              <?php foreach ($adminNotifications as $notif): ?>
                <div class="notif-item <?= $notif['is_read'] ? '' : 'unread' ?>">
                  <div class="notif-icon">
                    <?php if (stripos($notif['message'], 'confirmed') !== false || stripos($notif['message'], 'approved') !== false): ?>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <?php elseif (stripos($notif['message'], 'cancelled') !== false || stripos($notif['message'], 'rejected') !== false): ?>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <?php elseif (stripos($notif['message'], 'payment') !== false || stripos($notif['message'], 'submitted') !== false): ?>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#c9a57a" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    <?php elseif (stripos($notif['message'], 'booking') !== false || stripos($notif['message'], 'reservation') !== false): ?>
                      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#8b5cf6" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
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
          <a href="../notifications.php" class="notif-footer">View all notifications</a>
        </div>
      </div>
      
      <div class="user-info">
        <div class="user-avatar"><?= strtoupper(substr($adminName, 0, 2)) ?></div>
        <div class="user-details">
          <div class="user-name"><?= htmlspecialchars($adminName) ?></div>
          <div class="user-role">Admin</div>
        </div>
      </div>
      <a href="../controller/logout.php" class="btn-logout"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg> Logout</a>
    </div>
  </header>

  <!-- Main -->
  <main class="main">
    <h1 class="page-title">Admin Dashboard</h1>
    <p class="page-subtitle">Manage reservations, payments, and generate reports</p>

    <!-- KPI Cards -->
    <div class="kpi-grid">
      <div class="kpi-card highlight">
        <div class="kpi-header">
          <div class="kpi-icon">₱</div>
          <div class="kpi-trend"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></div>
        </div>
        <div class="kpi-value">₱ <?= number_format($totalRevenue, 0) ?></div>
        <div class="kpi-label">Total Revenue</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-header"><div class="kpi-icon purple"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div></div>
        <div class="kpi-value"><?= number_format($totalBookings) ?></div>
        <div class="kpi-label">Total Bookings</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-header"><div class="kpi-icon yellow"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div></div>
        <div class="kpi-value"><?= number_format($pendingApprovals) ?></div>
        <div class="kpi-label">Pending Approvals</div>
      </div>
      <div class="kpi-card">
        <div class="kpi-header"><div class="kpi-icon blue"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></div></div>
        <div class="kpi-value"><?= number_format($totalCustomers) ?></div>
        <div class="kpi-label">Total Customers</div>
      </div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
      <button class="tab active" data-tab="overview"><span class="tab-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></span> Overview</button>
      <button class="tab" data-tab="reservations"><span class="tab-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg></span> Manage Reservations</button>
      <button class="tab" data-tab="payments"><span class="tab-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span> Confirm Payments</button>
      <button class="tab" data-tab="reports"><span class="tab-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg></span> Generate Reports</button>
    </div>

    <!-- Overview Panel -->
    <div id="panel-overview" class="panel active">
      <div class="table-card">
        <div class="table-header">Recent Activity</div>
        <table class="table">
          <thead><tr><th>Customer</th><th>Service</th><th>Date</th><th>Amount</th><th>Status</th></tr></thead>
          <tbody>
            <?php if (empty($recentBookings)): ?>
              <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--muted)">No recent activity</td></tr>
            <?php else: ?>
              <?php foreach (array_slice($recentBookings, 0, 5) as $b): ?>
                <tr>
                  <td style="font-weight:500"><?= htmlspecialchars($b['customer_name']) ?></td>
                  <td><?= htmlspecialchars($b['service_name']) ?></td>
                  <td><?= date('M j, Y', strtotime($b['scheduled_date'])) ?></td>
                  <td style="font-weight:600">₱<?= number_format($b['price'], 0) ?></td>
                  <td><span class="status status-<?= $b['status'] ?>"><span class="status-dot"></span><?= ucfirst($b['status']) ?></span></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Manage Reservations Panel -->
    <div id="panel-reservations" class="panel">
      <!-- Filter Bar -->
      <div class="filter-bar">
        <span class="filter-label">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
          Filter by status:
        </span>
        <div class="filter-buttons">
          <button class="filter-btn active" data-filter="all" onclick="filterReservations('all')">All</button>
          <button class="filter-btn" data-filter="pending" onclick="filterReservations('pending')">Pending</button>
          <button class="filter-btn" data-filter="confirmed" onclick="filterReservations('confirmed')">Confirmed</button>
          <button class="filter-btn" data-filter="cancelled" onclick="filterReservations('cancelled')">Cancelled</button>
        </div>
      </div>

      <div class="table-card">
        <div class="table-header table-header-actions">
          <span>All Reservations</span>
          <?php if (!empty($recentBookings)): ?>
            <form method="post" action="../controller/admin_clear_reservations.php" onsubmit="return confirm('Are you sure you want to clear all reservations? This action cannot be undone.');">
              <button type="submit" class="btn-clear"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg> Clear All</button>
            </form>
          <?php endif; ?>
        </div>
        <table class="table">
          <thead><tr><th>Customer</th><th>Service</th><th>Date & Time</th><th>Amount</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
            <?php if (empty($recentBookings)): ?>
              <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted)">No reservations found</td></tr>
            <?php else: ?>
              <?php foreach ($recentBookings as $b): 
                // Map service name to image
                $serviceImages = [
                  'Wedding Photography' => '../assets/couple-holding-wedding-bouquet-scaled_460x@2x.png',
                  'Portrait Session' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=1200&q=80&auto=format&fit=crop',
                  'Event Photography' => '../assets/concert-photographer.png',
                  'Product Photography' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=1200&q=80&auto=format&fit=crop',
                  'Real Estate Photography' => 'https://th.bing.com/th/id/R.f3fbbc913f36e1414f37c25d2524a393?rik=24TvHl63huoVuA&riu=http%3a%2f%2fwww.philsavoryphotography.com.au%2fwp-content%2fuploads%2f2020%2f02%2fBrisbane-real-estate-dusk-photography-services.jpg&ehk=7hmM6eZGatNVwy6TiveGpqfeU%2bwGeW%2bt0wAqOrn%2feIA%3d&risl=&pid=ImgRaw&r=0',
                  'Maternity Photoshoot' => 'https://tse1.explicit.bing.net/th/id/OIP.hn4QExeej2hsoecw2tgL0gHaE9?w=1896&h=1268&rs=1&pid=ImgDetMain&o=7&rm=3',
                ];
                $serviceImage = $serviceImages[$b['service_name']] ?? 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=1200&q=80&auto=format&fit=crop';
                $isPaid = intval($b['paid_count'] ?? 0) > 0;
              ?>
                <tr data-status="<?= $b['status'] ?>">
                  <td><div style="font-weight:500"><?= htmlspecialchars($b['customer_name']) ?></div><div style="font-size:12px;color:var(--muted)"><?= htmlspecialchars($b['email']) ?></div></td>
                  <td><?= htmlspecialchars($b['service_name']) ?></td>
                  <td><?= date('M j, Y', strtotime($b['scheduled_date'])) ?><br><span style="font-size:12px;color:var(--muted)"><?= date('g:i A', strtotime($b['scheduled_date'])) ?></span></td>
                  <td style="font-weight:600">₱<?= number_format($b['price'], 0) ?></td>
                  <td><span class="status status-<?= $b['status'] ?>"><span class="status-dot"></span><?= ucfirst($b['status']) ?></span></td>
                  <td>
                    <button type="button" class="btn-action btn-view" onclick="openViewModal('<?= htmlspecialchars($b['customer_name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($b['email'], ENT_QUOTES) ?>', '<?= htmlspecialchars($b['service_name'], ENT_QUOTES) ?>', '<?= date('M j, Y', strtotime($b['scheduled_date'])) ?>', '<?= date('g:i A', strtotime($b['scheduled_date'])) ?>', '₱<?= number_format($b['price'], 0) ?>', '<?= $b['status'] ?>', '<?= $isPaid ? 'Paid' : 'Unpaid' ?>', '<?= htmlspecialchars($serviceImage, ENT_QUOTES) ?>')">View</button>
                    <?php if ($b['status'] === 'pending' && $isPaid): ?>
                      <form method="post" action="../controller/admin_update_reservation.php" style="display:inline;margin-left:6px"><input type="hidden" name="reservation_id" value="<?= $b['reservation_id'] ?>"><input type="hidden" name="action" value="confirm"><button type="submit" class="btn-action btn-confirm">✓</button></form>
                    <?php endif; ?>
                    <?php if ($b['status'] === 'pending'): ?>
                      <form method="post" action="../controller/admin_update_reservation.php" style="display:inline;margin-left:6px"><input type="hidden" name="reservation_id" value="<?= $b['reservation_id'] ?>"><input type="hidden" name="action" value="cancel"><button type="submit" class="btn-action btn-cancel">✕</button></form>
                    <?php endif; ?>
                    <form method="post" action="../controller/admin_delete_reservation.php" style="display:inline;margin-left:6px" onsubmit="return confirm('Delete this reservation permanently?')"><input type="hidden" name="reservation_id" value="<?= $b['reservation_id'] ?>"><button type="submit" class="btn-action btn-delete" title="Delete">🗑</button></form>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Confirm Payments Panel -->
    <div id="panel-payments" class="panel">
      <?php
      // Pending payments awaiting admin verification
      $paymentsToVerify = $pendingPayments ?? [];
      // Unpaid bookings (no payment record at all)
      $unpaidBookings = array_filter($recentBookings, fn($b) => intval($b['paid_count'] ?? 0) === 0 && intval($b['pending_payment_count'] ?? 0) === 0 && $b['status'] === 'pending');
      $completedPayments = array_filter($recentBookings, fn($b) => $b['status'] === 'confirmed');
      ?>
      
      <!-- Payments Awaiting Verification -->
      <?php if (empty($paymentsToVerify)): ?>
        <div class="empty-state">
          <div class="empty-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></div>
          <div class="empty-title">No payments to verify</div>
          <div class="empty-text">Submitted payments will appear here for verification</div>
        </div>
      <?php else: ?>
        <div class="table-card">
          <div class="table-header">Payments Awaiting Verification (<?= count($paymentsToVerify) ?>)</div>
          <table class="table">
            <thead><tr><th>Customer</th><th>Service</th><th>Amount</th><th>Method</th><th>Reference</th><th>Receipt</th><th>Actions</th></tr></thead>
            <tbody>
              <?php foreach ($paymentsToVerify as $p): ?>
                <tr>
                  <td><div style="font-weight:500"><?= htmlspecialchars($p['customer_name']) ?></div><div style="font-size:12px;color:var(--muted)"><?= htmlspecialchars($p['email']) ?></div></td>
                  <td><?= htmlspecialchars($p['service_name']) ?></td>
                  <td style="font-weight:600">₱<?= number_format($p['amount'], 0) ?></td>
                  <td><span class="status status-approved"><span class="status-dot"></span><?= ucfirst($p['method']) ?></span></td>
                  <td><?= htmlspecialchars($p['reference_number'] ?? '-') ?></td>
                  <td>
                    <?php if (!empty($p['receipt_image'])): ?>
                      <button type="button" class="btn-action btn-view" onclick="openReceiptModal('../<?= htmlspecialchars($p['receipt_image']) ?>')">View Receipt</button>
                    <?php else: ?>
                      <span style="color:var(--muted);font-size:13px">No receipt</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <form method="post" action="../controller/admin_verify_payment.php" style="display:inline">
                      <input type="hidden" name="payment_id" value="<?= $p['payment_id'] ?>">
                      <input type="hidden" name="action" value="approve">
                      <button type="submit" class="btn-action btn-confirm">✓ Approve</button>
                    </form>
                    <form method="post" action="../controller/admin_verify_payment.php" style="display:inline;margin-left:6px">
                      <input type="hidden" name="payment_id" value="<?= $p['payment_id'] ?>">
                      <input type="hidden" name="action" value="reject">
                      <button type="submit" class="btn-action btn-cancel" onclick="return confirm('Reject this payment?')">✕ Reject</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

      <!-- Unpaid Pending Bookings -->
      <?php if (!empty($unpaidBookings)): ?>
        <div class="table-card" style="margin-top:24px">
          <div class="table-header">Unpaid Bookings - Waiting for Payment</div>
          <table class="table">
            <thead><tr><th>Customer</th><th>Service</th><th>Amount</th><th>Payment</th><th>Status</th></tr></thead>
            <tbody>
              <?php foreach ($unpaidBookings as $b): ?>
                <tr>
                  <td style="font-weight:500"><?= htmlspecialchars($b['customer_name']) ?></td>
                  <td><?= htmlspecialchars($b['service_name']) ?></td>
                  <td style="font-weight:600">₱<?= number_format($b['price'], 0) ?></td>
                  <td><span class="status status-pending"><span class="status-dot"></span>Unpaid</span></td>
                  <td><span style="color:var(--muted);font-size:13px">Awaiting payment</span></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

      <div class="table-card" style="margin-top:24px">
        <div class="table-header">Completed Payments</div>
        <table class="table">
          <thead><tr><th>Customer</th><th>Service</th><th>Date</th><th>Amount</th></tr></thead>
          <tbody>
            <?php if (empty($completedPayments)): ?>
              <tr><td colspan="4" style="text-align:center;padding:40px;color:var(--muted)">No completed payments yet</td></tr>
            <?php else: ?>
              <?php foreach ($completedPayments as $b): ?>
                <tr>
                  <td style="font-weight:500"><?= htmlspecialchars($b['customer_name']) ?></td>
                  <td><?= htmlspecialchars($b['service_name']) ?></td>
                  <td><?= date('M j, Y', strtotime($b['scheduled_date'])) ?></td>
                  <td style="font-weight:600;color:#059669">₱<?= number_format($b['price'], 0) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Generate Reports Panel -->
    <div id="panel-reports" class="panel">
      <!-- Summary Cards for Report -->
      <div class="report-summary" style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px">
        <div class="report-card" style="background:#fff;border-radius:12px;padding:20px;border:1px solid #e5e7eb;text-align:center">
          <div style="font-size:28px;font-weight:700;color:var(--brown)">₱<?= number_format($totalRevenue, 0) ?></div>
          <div style="font-size:13px;color:var(--muted);margin-top:4px">Total Revenue</div>
        </div>
        <div class="report-card" style="background:#fff;border-radius:12px;padding:20px;border:1px solid #e5e7eb;text-align:center">
          <div style="font-size:28px;font-weight:700;color:#8b5cf6"><?= number_format($totalBookings) ?></div>
          <div style="font-size:13px;color:var(--muted);margin-top:4px">Total Bookings</div>
        </div>
        <div class="report-card" style="background:#fff;border-radius:12px;padding:20px;border:1px solid #e5e7eb;text-align:center">
          <div style="font-size:28px;font-weight:700;color:#f59e0b"><?= number_format($pendingApprovals) ?></div>
          <div style="font-size:13px;color:var(--muted);margin-top:4px">Pending Approvals</div>
        </div>
        <div class="report-card" style="background:#fff;border-radius:12px;padding:20px;border:1px solid #e5e7eb;text-align:center">
          <div style="font-size:28px;font-weight:700;color:#3b82f6"><?= number_format($totalCustomers) ?></div>
          <div style="font-size:13px;color:var(--muted);margin-top:4px">Total Customers</div>
        </div>
      </div>

      <div class="empty-state">
        <div class="empty-icon"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg></div>
        <div class="empty-title">Generate Reports</div>
        <div class="empty-text">Export booking and payment reports with summary statistics</div>
        <div style="margin-top:20px;display:flex;gap:12px;justify-content:center">
          <button class="btn-action btn-view" onclick="exportPDF()"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>Export as PDF</button>
          <button class="btn-action btn-confirm" onclick="exportCSV()"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:middle;margin-right:4px"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="3" y1="15" x2="21" y2="15"/><line x1="9" y1="3" x2="9" y2="21"/><line x1="15" y1="3" x2="15" y2="21"/></svg>Export as CSV</button>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <script>
    document.querySelectorAll('.tab').forEach(tab => {
      tab.addEventListener('click', function() {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
        this.classList.add('active');
        document.getElementById('panel-' + this.dataset.tab).classList.add('active');
      });
    });
    
    function exportPDF() {
      // Create a professional PDF report
      const reportData = {
        totalRevenue: '₱<?= number_format($totalRevenue, 0) ?>',
        totalBookings: '<?= number_format($totalBookings) ?>',
        pendingApprovals: '<?= number_format($pendingApprovals) ?>',
        totalCustomers: '<?= number_format($totalCustomers) ?>',
        generatedDate: new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' }),
        generatedTime: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
      };
      
      // Get recent reservations for the report
      const recentReservations = [];
      document.querySelectorAll('#panel-reservations .table tbody tr').forEach((tr, idx) => {
        if (idx < 10) {
          const cells = tr.querySelectorAll('td');
          if (cells.length >= 5) {
            recentReservations.push({
              customer: cells[0].textContent.trim().split('\n')[0],
              service: cells[1].textContent.trim(),
              date: cells[2].textContent.trim().split('\n')[0],
              amount: cells[3].textContent.trim(),
              status: cells[4].textContent.trim()
            });
          }
        }
      });

      // SVG Icons for PDF
      const icons = {
        camera: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
        revenue: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>',
        calendar: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>',
        clock: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
        users: '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        clipboard: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/></svg>'
      };

      let reservationsHTML = '';
      if (recentReservations.length > 0) {
        reservationsHTML = `
          <div style="margin-top: 35px;">
            <div style="background: linear-gradient(135deg, #c9a57a 0%, #a67c52 100%); color: white; padding: 12px 20px; border-radius: 8px 8px 0 0; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
              ${icons.clipboard} Recent Reservations
            </div>
            <table style="width: 100%; border-collapse: collapse; font-size: 11px;">
              <thead>
                <tr style="background: #f7efe6;">
                  <th style="padding: 12px 10px; text-align: left; border-bottom: 2px solid #c9a57a; color: #7b4f36; font-weight: 600;">Customer</th>
                  <th style="padding: 12px 10px; text-align: left; border-bottom: 2px solid #c9a57a; color: #7b4f36; font-weight: 600;">Service</th>
                  <th style="padding: 12px 10px; text-align: left; border-bottom: 2px solid #c9a57a; color: #7b4f36; font-weight: 600;">Date</th>
                  <th style="padding: 12px 10px; text-align: right; border-bottom: 2px solid #c9a57a; color: #7b4f36; font-weight: 600;">Amount</th>
                  <th style="padding: 12px 10px; text-align: center; border-bottom: 2px solid #c9a57a; color: #7b4f36; font-weight: 600;">Status</th>
                </tr>
              </thead>
              <tbody>
                ${recentReservations.map((r, i) => `
                  <tr style="background: ${i % 2 === 0 ? '#ffffff' : '#faf7f4'};">
                    <td style="padding: 10px; border-bottom: 1px solid #e9d9c8; color: #374151;">${r.customer}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #e9d9c8; color: #374151;">${r.service}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #e9d9c8; color: #374151;">${r.date}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #e9d9c8; color: #374151; text-align: right; font-weight: 500;">${r.amount}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #e9d9c8; text-align: center;">
                      <span style="display: inline-block; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 600; background: ${r.status.toLowerCase().includes('confirm') ? '#dcfce7' : r.status.toLowerCase().includes('pending') ? '#fef3c7' : '#fee2e2'}; color: ${r.status.toLowerCase().includes('confirm') ? '#166534' : r.status.toLowerCase().includes('pending') ? '#92400e' : '#991b1b'};">${r.status}</span>
                    </td>
                  </tr>
                `).join('')}
              </tbody>
            </table>
          </div>
        `;
      }

      const pdfContent = document.createElement('div');
      pdfContent.innerHTML = `
        <div style="font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; padding: 0; color: #1f2937; background: #ffffff;">
          <!-- Header with gradient -->
          <div style="background: linear-gradient(135deg, #7b4f36 0%, #a67c52 50%, #c9a57a 100%); padding: 40px 35px; color: white; position: relative;">
            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 8px;">
              <div style="width: 50px; height: 50px; background: rgba(255,255,255,0.2); border-radius: 12px; display: flex; align-items: center; justify-content: center;">${icons.camera}</div>
              <div>
                <h1 style="font-size: 26px; font-weight: 700; margin: 0; letter-spacing: -0.5px;">PhotoReserve</h1>
                <p style="font-size: 13px; margin: 4px 0 0 0; opacity: 0.9;">Photography Reservation System</p>
              </div>
            </div>
            <div style="position: absolute; top: 35px; right: 35px; text-align: right;">
              <div style="background: rgba(255,255,255,0.15); padding: 12px 18px; border-radius: 10px; backdrop-filter: blur(10px);">
                <div style="font-size: 11px; opacity: 0.85; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Admin Report</div>
                <div style="font-size: 14px; font-weight: 600;">${reportData.generatedDate}</div>
                <div style="font-size: 11px; opacity: 0.8;">${reportData.generatedTime}</div>
              </div>
            </div>
          </div>
          
          <!-- Stats Section -->
          <div style="padding: 35px; background: #faf7f4;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 20px;">
              <div style="width: 4px; height: 22px; background: linear-gradient(135deg, #c9a57a 0%, #a67c52 100%); border-radius: 2px;"></div>
              <h2 style="font-size: 16px; font-weight: 700; color: #7b4f36; margin: 0;">Dashboard Overview</h2>
            </div>
            
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
              <div style="background: #ffffff; border-radius: 16px; padding: 22px 18px; text-align: center; box-shadow: 0 2px 8px rgba(123, 79, 54, 0.08); border: 1px solid #e9d9c8;">
                <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #c9a57a 0%, #a67c52 100%); border-radius: 12px; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center;">${icons.revenue}</div>
                <div style="font-size: 24px; font-weight: 800; color: #7b4f36; margin-bottom: 4px;">${reportData.totalRevenue}</div>
                <div style="font-size: 11px; color: #8b7355; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">Total Revenue</div>
              </div>
              <div style="background: #ffffff; border-radius: 16px; padding: 22px 18px; text-align: center; box-shadow: 0 2px 8px rgba(123, 79, 54, 0.08); border: 1px solid #e9d9c8;">
                <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); border-radius: 12px; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center;">${icons.calendar}</div>
                <div style="font-size: 24px; font-weight: 800; color: #7c3aed; margin-bottom: 4px;">${reportData.totalBookings}</div>
                <div style="font-size: 11px; color: #8b7355; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">Total Bookings</div>
              </div>
              <div style="background: #ffffff; border-radius: 16px; padding: 22px 18px; text-align: center; box-shadow: 0 2px 8px rgba(123, 79, 54, 0.08); border: 1px solid #e9d9c8;">
                <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 12px; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center;">${icons.clock}</div>
                <div style="font-size: 24px; font-weight: 800; color: #d97706; margin-bottom: 4px;">${reportData.pendingApprovals}</div>
                <div style="font-size: 11px; color: #8b7355; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">Pending</div>
              </div>
              <div style="background: #ffffff; border-radius: 16px; padding: 22px 18px; text-align: center; box-shadow: 0 2px 8px rgba(123, 79, 54, 0.08); border: 1px solid #e9d9c8;">
                <div style="width: 42px; height: 42px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); border-radius: 12px; margin: 0 auto 12px; display: flex; align-items: center; justify-content: center;">${icons.users}</div>
                <div style="font-size: 24px; font-weight: 800; color: #2563eb; margin-bottom: 4px;">${reportData.totalCustomers}</div>
                <div style="font-size: 11px; color: #8b7355; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 500;">Customers</div>
              </div>
            </div>
          </div>
          
          <!-- Reservations Table -->
          <div style="padding: 0 35px 35px;">
            ${reservationsHTML}
          </div>
          
          <!-- Footer -->
          <div style="background: #7b4f36; padding: 25px 35px; color: white;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <div>
                <div style="font-size: 13px; font-weight: 600; margin-bottom: 3px;">PhotoReserve Photography Reservation System</div>
                <div style="font-size: 11px; opacity: 0.75;">Automated report generated from admin dashboard</div>
              </div>
              <div style="text-align: right; font-size: 10px; opacity: 0.7;">
                <div>Report ID: RPT-${Date.now().toString(36).toUpperCase()}</div>
                <div style="margin-top: 2px;">© ${new Date().getFullYear()} PhotoReserve</div>
              </div>
            </div>
          </div>
        </div>
      `;
      
      const opt = {
        margin: 0,
        filename: 'PhotoReserve-Report-' + new Date().toISOString().split('T')[0] + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true, logging: false },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
      };
      
      html2pdf().set(opt).from(pdfContent).save();
    }
    
    function exportCSV() {
      const rows = [['Customer', 'Service', 'Date', 'Amount', 'Status']];
      document.querySelectorAll('#panel-reservations .table tbody tr').forEach(tr => {
        const cells = tr.querySelectorAll('td');
        if (cells.length >= 5) {
          rows.push([cells[0].textContent.trim().split('\n')[0], cells[1].textContent.trim(), cells[2].textContent.trim().split('\n')[0], cells[3].textContent.trim(), cells[4].textContent.trim()]);
        }
      });
      const blob = new Blob([rows.map(r => r.join(',')).join('\n')], { type: 'text/csv' });
      const a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = 'reservations-report.csv'; a.click();
    }

    // Filter Reservations Function
    function filterReservations(status) {
      // Update active button
      document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('active');
        if (btn.getAttribute('data-filter') === status) {
          btn.classList.add('active');
        }
      });
      
      // Filter table rows
      const rows = document.querySelectorAll('#panel-reservations .table tbody tr');
      rows.forEach(row => {
        const rowStatus = row.getAttribute('data-status');
        if (status === 'all' || rowStatus === status) {
          row.style.display = '';
        } else {
          row.style.display = 'none';
        }
      });
    }

    // View Booking Modal Functions
    function openViewModal(customer, email, service, date, time, price, status, payment, image) {
      document.getElementById('modal-image').src = image;
      document.getElementById('modal-service').textContent = service;
      document.getElementById('modal-customer').textContent = customer;
      document.getElementById('modal-email').textContent = email;
      document.getElementById('modal-date').textContent = date;
      document.getElementById('modal-time').textContent = time;
      document.getElementById('modal-price').textContent = price;
      document.getElementById('modal-payment').textContent = payment;
      
      const statusEl = document.getElementById('modal-status');
      statusEl.textContent = status.charAt(0).toUpperCase() + status.slice(1);
      statusEl.className = 'modal-status status-' + status;
      
      document.getElementById('viewBookingModal').classList.add('active');
    }
    
    function closeViewModal() {
      document.getElementById('viewBookingModal').classList.remove('active');
    }
    
    document.getElementById('viewBookingModal').addEventListener('click', function(e) {
      if (e.target === this) closeViewModal();
    });

    // Receipt Modal Functions
    function openReceiptModal(imageSrc) {
      document.getElementById('receipt-image').src = imageSrc;
      document.getElementById('receiptModal').classList.add('active');
    }
    
    function closeReceiptModal() {
      document.getElementById('receiptModal').classList.remove('active');
    }
    
    document.getElementById('receiptModal').addEventListener('click', function(e) {
      if (e.target === this) closeReceiptModal();
    });
  </script>

  <!-- View Booking Modal -->
  <div class="modal-overlay" id="viewBookingModal">
    <div class="modal">
      <img id="modal-image" class="modal-image" src="" alt="Service Image">
      <div class="modal-body">
        <div class="modal-header">
          <h3 class="modal-title" id="modal-service">Service Name</h3>
          <button class="modal-close" onclick="closeViewModal()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          </button>
        </div>
        <div class="modal-details">
          <div class="detail-row">
            <span class="detail-label">Customer</span>
            <span class="detail-value" id="modal-customer">-</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Email</span>
            <span class="detail-value" id="modal-email">-</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Date</span>
            <span class="detail-value" id="modal-date">-</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Time</span>
            <span class="detail-value" id="modal-time">-</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Payment</span>
            <span class="detail-value" id="modal-payment">-</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Status</span>
            <span class="modal-status" id="modal-status">-</span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Amount</span>
            <span class="detail-value price" id="modal-price">-</span>
          </div>
        </div>
        <button type="button" class="modal-close-btn" onclick="closeViewModal()">Close</button>
      </div>
    </div>
  </div>

  <!-- Receipt Modal -->
  <div class="modal-overlay" id="receiptModal">
    <div class="modal" style="max-width:500px">
      <div class="modal-body" style="padding:0">
        <div style="background:linear-gradient(135deg, #c9a57a 0%, #a67c52 100%);padding:20px 24px;border-radius:16px 16px 0 0">
          <div style="display:flex;align-items:center;justify-content:space-between">
            <div style="display:flex;align-items:center;gap:12px">
              <div style="width:40px;height:40px;background:rgba(255,255,255,0.2);border-radius:10px;display:flex;align-items:center;justify-content:center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
              </div>
              <div>
                <h3 style="margin:0;color:#fff;font-size:18px;font-weight:600">Payment Receipt</h3>
                <p style="margin:4px 0 0;color:rgba(255,255,255,0.8);font-size:13px">Uploaded by customer</p>
              </div>
            </div>
            <button class="modal-close" onclick="closeReceiptModal()" style="background:rgba(255,255,255,0.2);border:none;width:36px;height:36px;border-radius:10px;cursor:pointer;display:flex;align-items:center;justify-content:center">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
          </div>
        </div>
        <div style="padding:24px;background:#faf8f5">
          <div style="background:#fff;border-radius:12px;padding:16px;border:1px solid #e9d9c8;box-shadow:0 4px 12px rgba(201,165,122,0.1)">
            <img id="receipt-image" src="" alt="Receipt" style="width:100%;max-height:400px;object-fit:contain;border-radius:8px;display:block">
          </div>
          <div style="margin-top:16px;padding:14px 16px;background:#fff;border-radius:10px;border:1px solid #e9d9c8;display:flex;align-items:center;gap:10px">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#c9a57a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
            <span style="font-size:13px;color:#6b7280">Please verify the payment details before approving</span>
          </div>
        </div>
        <div style="padding:0 24px 24px;background:#faf8f5;border-radius:0 0 16px 16px">
          <button type="button" onclick="closeReceiptModal()" style="width:100%;padding:14px 20px;background:linear-gradient(135deg, #c9a57a 0%, #e9d9c8 100%);border:none;border-radius:10px;color:#5c4033;font-weight:600;font-size:14px;cursor:pointer;transition:all 0.15s">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Toast Notifications -->
  <?php if (!empty($_SESSION['admin_success'])): ?>
    <div id="toast" class="toast success">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      <?= htmlspecialchars($_SESSION['admin_success']) ?>
    </div>
    <script>setTimeout(()=>{const t=document.getElementById('toast');if(t){t.classList.add('hide');setTimeout(()=>t.remove(),300)}},3000);</script>
    <?php unset($_SESSION['admin_success']); ?>
  <?php endif; ?>
  <?php if (!empty($_SESSION['admin_error'])): ?>
    <div id="toast" class="toast error">
      <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
      <?= htmlspecialchars($_SESSION['admin_error']) ?>
    </div>
    <script>setTimeout(()=>{const t=document.getElementById('toast');if(t){t.classList.add('hide');setTimeout(()=>t.remove(),300)}},3000);</script>
    <?php unset($_SESSION['admin_error']); ?>
  <?php endif; ?>
  
  <!-- Notification Dropdown Script -->
  <script>
  document.addEventListener('click', function(e) {
    const notifBell = document.getElementById('adminNotifBell');
    const notifDropdown = document.getElementById('adminNotifDropdown');
    
    if (notifBell && notifDropdown) {
      if (notifBell.contains(e.target)) {
        const isOpen = notifDropdown.classList.contains('show');
        notifDropdown.classList.toggle('show');
        
        // Mark notifications as read via AJAX when opening
        if (!isOpen) {
          fetch('../controller/mark_notifications_read.php', { method: 'POST' })
            .then(() => {
              const badge = notifBell.querySelector('.notification-badge');
              if (badge) badge.style.display = 'none';
            })
            .catch(() => {});
        }
        return;
      }
      
      // Click outside -> close
      if (!notifDropdown.contains(e.target)) {
        notifDropdown.classList.remove('show');
      }
    }
  });
  </script>
</body>
</html>
