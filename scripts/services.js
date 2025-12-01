document.addEventListener('DOMContentLoaded', function () {
  // Filter functionality
  const pills = document.querySelectorAll('.pill');
  const cards = document.querySelectorAll('.svc-card');
  
  pills.forEach(p => p.addEventListener('click', function() {
    pills.forEach(x => x.classList.remove('active'));
    this.classList.add('active');
    const f = this.getAttribute('data-filter');
    cards.forEach(card => {
      const badge = card.querySelector('.svc-badge');
      const tag = badge ? badge.textContent.trim().toLowerCase().replace(/\s+/g, '') : '';
      if (f === 'all' || tag === f) {
        card.style.display = '';
      } else {
        card.style.display = 'none';
      }
    });
  }));

  // Book buttons functionality
  document.querySelectorAll('.btn-book').forEach(btn => {
    btn.addEventListener('click', function() {
      const svc = this.getAttribute('data-service');
      if (!svc) return;
      // Navigate to booking page with service param
      window.location.href = 'booking.php?service=' + encodeURIComponent(svc);
    });
  });

  // Legacy support for old filter class
  const filters = document.querySelectorAll('.filter');
  const serviceCards = document.querySelectorAll('.service-card');

  function applyFilter(filter) {
    if (filter === 'all') {
      serviceCards.forEach(c => c.style.display = '');
      return;
    }
    serviceCards.forEach(c => {
      const cat = (c.dataset.category || '').toLowerCase();
      c.style.display = (cat === filter ? '' : 'none');
    });
  }

  filters.forEach(btn => {
    btn.addEventListener('click', function () {
      filters.forEach(b => b.classList.remove('active'));
      this.classList.add('active');
      const filter = (this.dataset.filter || 'all').toLowerCase();
      applyFilter(filter);
    });
  
    btn.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        this.click();
      }
    });
  });
});
