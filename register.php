<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!empty($_SESSION['user_id'])) {
  $role = $_SESSION['role'] ?? '';
  if ($role === 'admin') {
    header('Location: adminpage/admin_dashboard.php');
    exit;
  }
  header('Location: dashboard.php');
  exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register - PhotoReserve (Design Only)</title>
  <link rel="icon" type="image/svg+xml" href="assets/favicon.svg">
  <link rel="stylesheet" href="cssfile/index.css">
  <link rel="stylesheet" href="cssfile/login.css">
</head>
<body>
  <?php
  // Hide Services and auth buttons on the register page header
  $hide_services = true;
  $hide_auth = true;
  $hide_home = false;
  include __DIR__ . '/includes/header.php'; ?>
<!-- class -->
  <main class="center">
    <div class="card">
      <div class="card-avatar"> 
        <svg width="34" height="34" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="6" width="18" height="12" rx="2" fill="#fff"/><path d="M8 8h8" stroke="#8b5cf6" stroke-width="1.2" stroke-linecap="round"/><circle cx="12" cy="12" r="2" fill="#fff"/></svg>
      </div>
      <h1>Create Account</h1>
      <p class="lead">Join us to book amazing photography sessions</p>

      <?php if (!empty($_GET['error'])): ?>
        <div class="form-error" style="color:#b91c1c;margin-bottom:12px"><?= htmlspecialchars($_GET['error']) ?></div>
      <?php endif; ?>
      <?php if (!empty($_GET['success'])): ?>
        <div class="form-success" style="color:#065f46;margin-bottom:12px"><?= htmlspecialchars($_GET['success']) ?></div>
      <?php endif; ?>

      <form class="login-form" method="post" action="controller/register.php">
        <?php if (!empty($_GET['next'])): ?>
          <input type="hidden" name="next" value="<?= htmlspecialchars($_GET['next']) ?>">
        <?php endif; ?>
        <label class="field">
          <span class="label-text">Full Name</span>
          <div class="input-wrap">
            <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 12a4 4 0 100-8 4 4 0 000 8z" stroke="#9CA3AF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/><path d="M4 20a8 8 0 0116 0" stroke="#9CA3AF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <input type="text" name="fullname" placeholder="John Doe" required>
          </div>
        </label>

        <label class="field">
          <span class="label-text">Email Address</span>
          <div class="input-wrap">
            <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M3 8l9 6 9-6" stroke="#9CA3AF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <input type="email" name="email" placeholder="you@example.com" required>
          </div>
        </label>

        <label class="field">
          <span class="label-text">Phone Number</span>
          <div class="input-wrap">
            <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 7c0 6 4 11 10 11 1 0 2-1 3-1l2 2c1 1 3 0 3-1 0-6-4-11-10-11S2 1 2 7z" stroke="#9CA3AF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <input type="tel" name="phone" placeholder="+1 234 567 8900" required>
          </div>
        </label>

        <label class="field">
          <span class="label-text">Password</span>
          <div class="input-wrap">
            <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="11" width="18" height="11" rx="2" stroke="#9CA3AF" stroke-width="1.2"/><path d="M7 11V7a5 5 0 0110 0v4" stroke="#9CA3AF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <input type="password" name="password" placeholder="Create a password" required>
          </div>
        </label>

        <label class="field">
          <span class="label-text">Confirm Password</span>
          <div class="input-wrap">
            <svg class="ic" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="11" width="18" height="11" rx="2" stroke="#9CA3AF" stroke-width="1.2"/><path d="M7 11V7a5 5 0 0110 0v4" stroke="#9CA3AF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <input type="password" name="password2" placeholder="Repeat password" required>
          </div>
        </label>

        <!-- Terms and Conditions -->
        <div class="terms-checkbox" style="display:flex;align-items:flex-start;gap:10px;margin:16px 0;text-align:left;">
          <input type="checkbox" id="termsCheck" name="terms" required style="width:18px;height:18px;margin-top:2px;accent-color:#c29b6b;cursor:pointer;">
          <label for="termsCheck" style="font-size:13px;color:#6b5e4a;cursor:pointer;line-height:1.4;">
            I agree to the <a href="#" id="termsLink" style="color:#c29b6b;font-weight:600;text-decoration:none;">Terms and Conditions</a> and <a href="#" id="privacyLink" style="color:#c29b6b;font-weight:600;text-decoration:none;">Privacy Policy</a>
          </label>
        </div>

        <button type="submit" class="btn-primary"><span>Create Account</span>
          <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M5 12h14" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M13 6l6 6-6 6" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </button>
      </form>
<!-- Alreaady have an account? -->
      <p class="muted">Already have an account? <a href="login.php">Sign in</a></p>
    </div>
  </main>

  <!-- Terms and Conditions Modal -->
  <div class="modal-overlay" id="termsModal">
    <div class="modal" style="max-width:600px;max-height:80vh;display:flex;flex-direction:column;">
      <div class="modal-header" style="padding:20px 24px;border-bottom:1px solid #e4e4e7;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#f7efe6 0%,#fff 100%);">
        <h3 style="margin:0;font-size:18px;font-weight:700;color:#7b4f36;">Terms and Conditions</h3>
        <button type="button" class="modal-close" onclick="closeTermsModal()" style="width:36px;height:36px;border-radius:10px;border:none;background:#f4f4f5;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#71717a;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="modal-body" style="padding:24px;overflow-y:auto;flex:1;font-size:14px;color:#374151;line-height:1.7;">
        <p style="margin-bottom:16px;"><strong>Last Updated:</strong> November 30, 2025</p>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">1. Acceptance of Terms</h4>
        <p style="margin-bottom:12px;">By creating an account and using PhotoReserve's services, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, please do not use our services.</p>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">2. Services</h4>
        <p style="margin-bottom:12px;">PhotoReserve provides professional photography booking services including but not limited to wedding photography, portrait sessions, event coverage, and other photography services as listed on our platform.</p>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">3. Booking and Payment</h4>
        <ul style="margin-bottom:12px;padding-left:20px;">
          <li style="margin-bottom:8px;">All bookings are subject to availability and confirmation.</li>
          <li style="margin-bottom:8px;">Payment must be completed through our approved payment methods (GCash, Bank Transfer, or Cash).</li>
          <li style="margin-bottom:8px;">A booking is only confirmed once payment has been verified by our team.</li>
          <li style="margin-bottom:8px;">Prices are subject to change without prior notice.</li>
        </ul>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">4. Cancellation Policy</h4>
        <ul style="margin-bottom:12px;padding-left:20px;">
          <li style="margin-bottom:8px;">Cancellations made 7 days before the scheduled date are eligible for a full refund.</li>
          <li style="margin-bottom:8px;">Cancellations made 3-6 days before are eligible for a 50% refund.</li>
          <li style="margin-bottom:8px;">Cancellations made less than 3 days before are non-refundable.</li>
          <li style="margin-bottom:8px;">Rescheduling is allowed up to 48 hours before the session, subject to availability.</li>
        </ul>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">5. User Responsibilities</h4>
        <ul style="margin-bottom:12px;padding-left:20px;">
          <li style="margin-bottom:8px;">You must provide accurate and complete information during registration.</li>
          <li style="margin-bottom:8px;">You are responsible for maintaining the confidentiality of your account.</li>
          <li style="margin-bottom:8px;">You agree not to use our services for any unlawful purposes.</li>
        </ul>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">6. Intellectual Property</h4>
        <p style="margin-bottom:12px;">All photographs taken during sessions remain the intellectual property of PhotoReserve until full payment is received. Upon payment, clients receive a license to use the images for personal, non-commercial purposes.</p>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">7. Limitation of Liability</h4>
        <p style="margin-bottom:12px;">PhotoReserve shall not be liable for any indirect, incidental, or consequential damages arising from the use of our services.</p>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">8. Contact Information</h4>
        <p style="margin-bottom:12px;">For questions about these Terms, please contact us at:</p>
        <ul style="margin-bottom:12px;padding-left:20px;">
          <li style="margin-bottom:8px;">Email: photoreserve@gmail.com</li>
          <li style="margin-bottom:8px;">Phone: 09000005676</li>
          <li style="margin-bottom:8px;">Address: Meadows, Mintal, Davao City</li>
        </ul>
      </div>
      <div style="padding:16px 24px;border-top:1px solid #e4e4e7;background:#faf9f7;">
        <button type="button" onclick="closeTermsModal()" style="width:100%;padding:12px;border-radius:10px;background:#c29b6b;color:#fff;font-weight:600;border:none;cursor:pointer;">I Understand</button>
      </div>
    </div>
  </div>

  <!-- Privacy Policy Modal -->
  <div class="modal-overlay" id="privacyModal">
    <div class="modal" style="max-width:600px;max-height:80vh;display:flex;flex-direction:column;">
      <div class="modal-header" style="padding:20px 24px;border-bottom:1px solid #e4e4e7;display:flex;justify-content:space-between;align-items:center;background:linear-gradient(135deg,#f7efe6 0%,#fff 100%);">
        <h3 style="margin:0;font-size:18px;font-weight:700;color:#7b4f36;">Privacy Policy</h3>
        <button type="button" class="modal-close" onclick="closePrivacyModal()" style="width:36px;height:36px;border-radius:10px;border:none;background:#f4f4f5;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#71717a;">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
      <div class="modal-body" style="padding:24px;overflow-y:auto;flex:1;font-size:14px;color:#374151;line-height:1.7;">
        <p style="margin-bottom:16px;"><strong>Last Updated:</strong> November 30, 2025</p>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">1. Information We Collect</h4>
        <p style="margin-bottom:12px;">We collect information you provide directly to us, including:</p>
        <ul style="margin-bottom:12px;padding-left:20px;">
          <li style="margin-bottom:8px;">Name, email address, and phone number</li>
          <li style="margin-bottom:8px;">Payment information for booking services</li>
          <li style="margin-bottom:8px;">Communication preferences</li>
          <li style="margin-bottom:8px;">Any other information you choose to provide</li>
        </ul>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">2. How We Use Your Information</h4>
        <ul style="margin-bottom:12px;padding-left:20px;">
          <li style="margin-bottom:8px;">To process and manage your bookings</li>
          <li style="margin-bottom:8px;">To communicate with you about our services</li>
          <li style="margin-bottom:8px;">To send booking confirmations and updates</li>
          <li style="margin-bottom:8px;">To improve our services and user experience</li>
        </ul>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">3. Information Sharing</h4>
        <p style="margin-bottom:12px;">We do not sell, trade, or rent your personal information to third parties. We may share your information only in the following circumstances:</p>
        <ul style="margin-bottom:12px;padding-left:20px;">
          <li style="margin-bottom:8px;">With your consent</li>
          <li style="margin-bottom:8px;">To comply with legal obligations</li>
          <li style="margin-bottom:8px;">To protect our rights and safety</li>
        </ul>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">4. Data Security</h4>
        <p style="margin-bottom:12px;">We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">5. Your Rights</h4>
        <p style="margin-bottom:12px;">You have the right to:</p>
        <ul style="margin-bottom:12px;padding-left:20px;">
          <li style="margin-bottom:8px;">Access your personal information</li>
          <li style="margin-bottom:8px;">Request correction of inaccurate data</li>
          <li style="margin-bottom:8px;">Request deletion of your account</li>
          <li style="margin-bottom:8px;">Opt-out of marketing communications</li>
        </ul>
        
        <h4 style="color:#7b4f36;margin:20px 0 12px;font-size:15px;">6. Contact Us</h4>
        <p style="margin-bottom:12px;">If you have questions about this Privacy Policy, please contact us at photoreserve@gmail.com</p>
      </div>
      <div style="padding:16px 24px;border-top:1px solid #e4e4e7;background:#faf9f7;">
        <button type="button" onclick="closePrivacyModal()" style="width:100%;padding:12px;border-radius:10px;background:#c29b6b;color:#fff;font-weight:600;border:none;cursor:pointer;">I Understand</button>
      </div>
    </div>
  </div>

  <style>
    /* Fix register page layout - make page scrollable */
    html, body {
      height: auto !important;
      min-height: 100vh;
      overflow-y: auto !important;
    }
    body {
      display: block !important;
    }
    .center {
      min-height: auto !important;
      padding: 30px 20px !important;
      align-items: flex-start !important;
      display: block !important;
      flex: none !important;
    }
    .card {
      margin: 0 auto 30px auto !important;
      max-height: none !important;
      overflow: visible !important;
    }
    .card-avatar {
      margin-top: 0 !important;
    }
    
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
      border-radius: 16px;
      width: 100%;
      box-shadow: 0 12px 40px rgba(0,0,0,0.15);
      animation: modalIn 0.25s ease;
    }
    @keyframes modalIn {
      from { opacity: 0; transform: scale(0.95) translateY(10px); }
      to { opacity: 1; transform: scale(1) translateY(0); }
    }
  </style>

  <script>
    // Terms Modal
    document.getElementById('termsLink').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('termsModal').classList.add('active');
    });
    
    function closeTermsModal() {
      document.getElementById('termsModal').classList.remove('active');
    }
    
    document.getElementById('termsModal').addEventListener('click', function(e) {
      if (e.target === this) closeTermsModal();
    });

    // Privacy Modal
    document.getElementById('privacyLink').addEventListener('click', function(e) {
      e.preventDefault();
      document.getElementById('privacyModal').classList.add('active');
    });
    
    function closePrivacyModal() {
      document.getElementById('privacyModal').classList.remove('active');
    }
    
    document.getElementById('privacyModal').addEventListener('click', function(e) {
      if (e.target === this) closePrivacyModal();
    });
  </script>

  <?php include __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
