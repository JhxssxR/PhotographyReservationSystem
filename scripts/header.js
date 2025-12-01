// scripts/header.js
document.addEventListener('click', function (e) {
  const btn = document.getElementById('userButton');
  const menu = document.getElementById('userDropdown');
  const notifBell = document.getElementById('notifBell');
  const notifDropdown = document.getElementById('notifDropdown');

  // User dropdown toggle
  if (btn && menu) {
    if (btn.contains(e.target)) {
      const open = btn.getAttribute('aria-expanded') === 'true';
      btn.setAttribute('aria-expanded', open ? 'false' : 'true');
      menu.style.display = open ? 'none' : 'block';
      menu.setAttribute('aria-hidden', open ? 'true' : 'false');
      // Close notification dropdown when opening user menu
      if (notifDropdown) notifDropdown.classList.remove('show');
      return;
    }

    // click outside user menu -> close
    if (!menu.contains(e.target)) {
      btn.setAttribute('aria-expanded', 'false');
      menu.style.display = 'none';
      menu.setAttribute('aria-hidden', 'true');
    }
  }

  // Notification dropdown toggle
  if (notifBell && notifDropdown) {
    if (notifBell.contains(e.target)) {
      const isOpen = notifDropdown.classList.contains('show');
      notifDropdown.classList.toggle('show');
      // Close user menu when opening notifications
      if (menu && !isOpen) {
        menu.style.display = 'none';
        if (btn) btn.setAttribute('aria-expanded', 'false');
      }
      // Mark notifications as read via AJAX when opening
      if (!isOpen) {
        fetch('/PhotographyReservationSystem/controller/mark_notifications_read.php', { method: 'POST' })
          .then(() => {
            const badge = notifBell.querySelector('.notification-badge');
            if (badge) badge.style.display = 'none';
          })
          .catch(() => {});
      }
      return;
    }

    // click outside notification dropdown -> close
    if (!notifDropdown.contains(e.target)) {
      notifDropdown.classList.remove('show');
    }
  }
});
