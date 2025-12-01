document.addEventListener('DOMContentLoaded', function () {
  const tabs = document.querySelectorAll('.dashboard-tabs .tab');
  const panels = {
    'My Bookings': document.getElementById('tab-bookings'),
    'Profile': document.getElementById('tab-profile'),
    'Payments': document.getElementById('tab-payments')
  };

  function activate(tabEl) {
    tabs.forEach(t => t.classList.remove('active'));
    tabEl.classList.add('active');
    // hide all panels
    Object.values(panels).forEach(p => p && (p.style.display = 'none'));
    const key = tabEl.textContent.trim();
    const panel = panels[key];
    if (panel) panel.style.display = '';
  }

  tabs.forEach(t => {
    t.addEventListener('click', function () { activate(this); });
    t.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); activate(this); } });
  });
});
