<?php
// Shared footer include
$footerBase = '/PhotographyReservationSystem/';
?>
<footer class="site-footer">
  <div class="footer-container">
    <!-- Brand Section -->
    <div class="footer-section footer-brand-section">
      <a href="<?= $footerBase ?>index.php" class="footer-logo">
        <div class="footer-logo-icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
        </div>
        <span class="footer-logo-text">PhotoReserve</span>
      </a>
      <p class="footer-tagline">Capturing moments that last forever. Professional photography services for all your special occasions.</p>
      <div class="footer-social">
        <a href="#" class="social-link" title="Facebook">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
        <a href="#" class="social-link" title="Instagram">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
        </a>
        <a href="#" class="social-link" title="Twitter">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
      </div>
    </div>

    <!-- Quick Links -->
    <div class="footer-section">
      <h4 class="footer-title">Quick Links</h4>
      <ul class="footer-links">
        <li><a href="<?= $footerBase ?>index.php">Home</a></li>
        <li><a href="<?= $footerBase ?>services.php">Our Services</a></li>
        <li><a href="<?= $footerBase ?>booking.php">Book a Session</a></li>
        <li><a href="<?= $footerBase ?>login.php">Login</a></li>
        <li><a href="<?= $footerBase ?>register.php">Register</a></li>
      </ul>
    </div>

    <!-- Business Hours -->
    <div class="footer-section">
      <h4 class="footer-title">Business Hours</h4>
      <ul class="footer-hours">
        <li>
          <span class="day">Monday - Friday</span>
          <span class="time">9:00 AM - 6:00 PM</span>
        </li>
        <li>
          <span class="day">Saturday</span>
          <span class="time">10:00 AM - 4:00 PM</span>
        </li>
        <li>
          <span class="day">Sunday</span>
          <span class="time">By Appointment</span>
        </li>
      </ul>
    </div>

    <!-- Contact Info -->
    <div class="footer-section">
      <h4 class="footer-title">Contact Us</h4>
      <ul class="footer-contact">
        <li>
          <div class="contact-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </div>
          <div class="contact-text">
            <span class="contact-label">Phone</span>
            <a href="tel:09000005676">0900 000 5676</a>
          </div>
        </li>
        <li>
          <div class="contact-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          </div>
          <div class="contact-text">
            <span class="contact-label">Email</span>
            <a href="mailto:photoreserve@gmail.com">photoreserve@gmail.com</a>
          </div>
        </li>
        <li>
          <div class="contact-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
          </div>
          <div class="contact-text">
            <span class="contact-label">Location</span>
            <span>Meadows, Mintal, Davao City</span>
          </div>
        </li>
      </ul>
    </div>
  </div>

  <!-- Footer Bottom -->
  <div class="footer-bottom">
    <div class="footer-bottom-content">
      <p>&copy; 2025 <strong>PhotoReserve</strong>. All rights reserved.</p>
      <div class="footer-bottom-links">
        <a href="#">Privacy Policy</a>
        <span class="divider">•</span>
        <a href="#">Terms of Service</a>
      </div>
    </div>
  </div>
</footer>

<style>
.site-footer {
  background: linear-gradient(180deg, #3d2b1f 0%, #2a1f16 100%);
  color: #e8ddd4;
  position: relative;
  z-index: 100;
}

.footer-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 30px 20px 25px;
  display: grid;
  grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
  gap: 30px;
}

/* Brand Section */
.footer-brand-section {
  padding-right: 20px;
}

.footer-logo {
  display: flex;
  align-items: center;
  gap: 12px;
  text-decoration: none;
  margin-bottom: 16px;
}

.footer-logo-icon {
  width: 36px;
  height: 36px;
  background: linear-gradient(135deg, #c9a57a 0%, #d4b896 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
}

.footer-logo-text {
  font-size: 18px;
  font-weight: 700;
  color: #fff;
}

.footer-tagline {
  font-size: 13px;
  line-height: 1.5;
  color: #b8a99a;
  margin-bottom: 14px;
}

.footer-social {
  display: flex;
  gap: 12px;
}

.social-link {
  width: 32px;
  height: 32px;
  background: rgba(255,255,255,0.08);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #c9a57a;
  transition: all 0.3s ease;
}

.social-link:hover {
  background: #c9a57a;
  color: #fff;
  transform: translateY(-3px);
}

/* Footer Titles */
.footer-title {
  font-size: 14px;
  font-weight: 600;
  color: #fff;
  margin-bottom: 14px;
  padding-bottom: 8px;
  border-bottom: 2px solid #c9a57a;
  display: inline-block;
}

/* Quick Links */
.footer-links {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-links li {
  margin-bottom: 8px;
}

.footer-links a {
  color: #b8a99a;
  text-decoration: none;
  font-size: 14px;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 6px;
}

.footer-links a:hover {
  color: #c9a57a;
  padding-left: 6px;
}

.footer-links a::before {
  content: "›";
  opacity: 0;
  transition: opacity 0.2s;
}

.footer-links a:hover::before {
  opacity: 1;
}

/* Business Hours */
.footer-hours {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-hours li {
  display: flex;
  flex-direction: column;
  gap: 2px;
  padding: 6px 0;
  border-bottom: 1px solid rgba(255,255,255,0.08);
}

.footer-hours li:last-child {
  border-bottom: none;
}

.footer-hours .day {
  font-size: 14px;
  font-weight: 500;
  color: #fff;
}

.footer-hours .time {
  font-size: 13px;
  color: #c9a57a;
}

/* Contact Info */
.footer-contact {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-contact li {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  margin-bottom: 12px;
}

.contact-icon {
  width: 32px;
  height: 32px;
  background: rgba(201, 165, 122, 0.15);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #c9a57a;
  flex-shrink: 0;
}

.contact-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.contact-label {
  font-size: 12px;
  color: #8a7b6c;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.contact-text a,
.contact-text span:not(.contact-label) {
  font-size: 14px;
  color: #e8ddd4;
  text-decoration: none;
  transition: color 0.2s;
}

.contact-text a:hover {
  color: #c9a57a;
}

/* Footer Bottom */
.footer-bottom {
  background: rgba(0,0,0,0.2);
  padding: 14px 20px;
}

.footer-bottom-content {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
}

.footer-bottom p {
  font-size: 14px;
  color: #8a7b6c;
  margin: 0;
}

.footer-bottom strong {
  color: #c9a57a;
}

.footer-bottom-links {
  display: flex;
  align-items: center;
  gap: 8px;
}

.footer-bottom-links a {
  color: #8a7b6c;
  text-decoration: none;
  font-size: 13px;
  transition: color 0.2s;
}

.footer-bottom-links a:hover {
  color: #c9a57a;
}

.footer-bottom-links .divider {
  color: #5a4d42;
}

/* Responsive */
@media (max-width: 900px) {
  .footer-container {
    grid-template-columns: 1fr 1fr;
  }
  
  .footer-brand-section {
    grid-column: span 2;
  }
}

@media (max-width: 600px) {
  .footer-container {
    grid-template-columns: 1fr;
    text-align: center;
  }
  
  .footer-brand-section {
    grid-column: span 1;
  }
  
  .footer-logo {
    justify-content: center;
  }
  
  .footer-social {
    justify-content: center;
  }
  
  .footer-title {
    display: block;
    text-align: center;
  }
  
  .footer-contact li {
    justify-content: center;
  }
  
  .footer-hours li {
    align-items: center;
  }
  
  .footer-bottom-content {
    flex-direction: column;
    text-align: center;
  }
}
</style>
