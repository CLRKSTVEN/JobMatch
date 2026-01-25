<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />

<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="edjGgac9mtFsWPbrGHhItAsXhkBE8VClTqg62ZE4">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="LEFT4CODE">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/signup.css') ?>">

  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <title>JobMatch DavOr - Signup</title>

  <!-- ✅ keep (your functions/js rely on dist assets & lucide) -->
  <link rel="stylesheet" href="<?= base_url('dist/css/app.css') ?>">
</head>

<body class="overflow-y-auto">

  <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
    <div class="loader-spinner !w-14"></div>
  </div>

  <!-- =========================================================
       ✅ NEW LAYOUT (NOT Midone)
       - Clean split layout
       - Uses your existing IDs/classes so JS + CSS still works
       ========================================================= -->
  <div class="jm-wrap">
    <div class="jm-left">
      <a class="jm-brand" href="<?= site_url('/') ?>">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="JobMatch DavOr" class="jm-logo">
      </a>

      <div class="jm-left-body">
        <div class="jm-illus">
          <img src="<?= base_url('dist/images/illustration.svg') ?>" alt="Illustration">
        </div>

        <h1 class="jm-title">
          A few more clicks to<br>
          sign up to your account.
        </h1>

        <p class="jm-sub">
          All your jobs and skilled workers—together in<br>
          one place with JobMatch DavOr
        </p>

        <div class="jm-badges">
          <span class="jm-pill">Skilled Workers</span>
          <span class="jm-pill">Employers</span>
          <span class="jm-pill">Secure</span>
        </div>
      </div>

      <div class="jm-left-foot">
        <small>© <?= date('Y') ?> JobMatch DavOr</small>
      </div>
    </div>

    <div class="jm-right">
      <div class="jm-card">
        <div class="jm-card-head">
          <h2 class="jm-card-title">Sign Up</h2>
          <div class="jm-card-note">Create your JobMatch DavOr account in seconds.</div>
        </div>

        <?= form_open('auth/signup') ?>

        <div class="mt-4">
          <?php foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info', 'msg' => 'success'] as $key => $class): ?>
            <?php if ($this->session->flashdata($key)): ?>
              <div class="alert alert-<?= $class ?> alert-dismissible fade show small mb-3" role="alert">
                <?= htmlspecialchars($this->session->flashdata($key), ENT_QUOTES, 'UTF-8') ?>
                <button type="button" class="btn-close" aria-label="Close"></button>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>

        <div class="jm-grid2">
          <div>
            <label class="jm-label" for="first_name">First Name</label>
            <input
              id="first_name"
              class="jm-input"
              type="text"
              name="first_name"
              placeholder="First Name"
              value="<?= set_value('first_name') ?>"
              autocomplete="given-name"
              required
              aria-required="true" />
            <?php if (form_error('first_name')): ?>
              <div class="text-red-600 text-sm mt-2"><?= form_error('first_name'); ?></div>
            <?php endif; ?>
          </div>

          <div>
            <label class="jm-label" for="last_name">Last Name</label>
            <input
              id="last_name"
              class="jm-input"
              type="text"
              name="last_name"
              placeholder="Last Name"
              value="<?= set_value('last_name') ?>"
              autocomplete="family-name"
              required
              aria-required="true" />
            <?php if (form_error('last_name')): ?>
              <div class="text-red-600 text-sm mt-2"><?= form_error('last_name'); ?></div>
            <?php endif; ?>
          </div>
        </div>

        <div class="mt-3">
          <label class="jm-label" for="signupEmail">Email</label>
          <input
            id="signupEmail"
            class="jm-input"
            type="email"
            name="email"
            placeholder="Email"
            value="<?= set_value('email') ?>">
          <div id="emailHint" class="alert alert-info alert-inline" role="alert" hidden></div>

          <?php if (form_error('email')): ?>
            <div class="alert alert-danger alert-inline" role="alert"><?= form_error('email'); ?></div>
          <?php endif; ?>
        </div>

        <div class="mt-3">
          <label class="jm-label" for="signupPassword">Password</label>
          <div class="jm-pass">
            <input
              id="signupPassword"
              class="jm-input jm-input-pass"
              type="password"
              name="password"
              placeholder="Password"
              autocomplete="new-password"
              required
              aria-required="true" />

            <button
              type="button"
              class="jm-eye"
              aria-label="Show password"
              data-toggle="password"
              data-target="#signupPassword">
              <i data-lucide="eye" style="width:20px; height:20px"></i>
            </button>
          </div>

          <!-- keep this strength bar block as-is for your current look -->
          <div class="box ml-px mt-4 grid h-2 w-full grid-flow-col gap-3 [--color:var(--color-foreground)]">
            <div class="active bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
            <div class="active bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
            <div class="active bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
            <div class="bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
          </div>
        </div>

        <div class="mt-4">
          <label for="role" class="jm-label">Register as</label>
          <select id="role" name="role" class="field-select" required>
            <option value="" <?= set_select('role', '', true) ?>>-- Select Role --</option>
            <option value="worker" <?= set_select('role', 'worker') ?>>Skilled Worker</option>
            <option value="client" <?= set_select('role', 'client') ?>>Individual / Employer</option>
          </select>
        </div>

        <div class="mt-4 jm-tos">
          <div class="bg-background border-foreground/70 relative size-4 rounded-sm border">
            <input
              class="peer relative z-10 size-full cursor-pointer opacity-0"
              type="checkbox"
              id="tos"
              name="accept_privacy"
              value="1"
              <?= set_checkbox('accept_privacy', '1'); ?>
              required>
            <div class="z-4 bg-foreground invisible absolute inset-0 flex items-center justify-center text-white peer-checked:visible">
              <i data-lucide="check" class="stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-4"></i>
            </div>
          </div>

          <label class="jm-tos-label" for="tos">
            I agree to the JobMatch DavOr
            <a class="text-primary ml-1" href="#" id="openPrivacy">Privacy Policy</a>.
          </label>
        </div>

        <?php if (form_error('accept_privacy')): ?>
          <div class="text-red-600 text-sm mt-2"><?= form_error('accept_privacy'); ?></div>
        <?php endif; ?>

        <div class="mt-4">
          <label class="jm-label">Human verification</label>
          <div
            class="g-recaptcha"
            data-sitekey="<?= html_escape($this->config->item('recaptcha_site_key')) ?>"
            data-callback="recaptchaOk"
            data-expired-callback="recaptchaExpired"
            data-error-callback="recaptchaError"></div>
          <?php if (form_error('g-recaptcha-response')): ?>
            <div class="text-red-600 text-sm mt-2"><?= form_error('g-recaptcha-response'); ?></div>
          <?php endif; ?>
        </div>

        <div class="hp" aria-hidden="true">
          <label for="hp-website">Website</label>
          <input id="hp-website" type="text" name="website" autocomplete="off" tabindex="-1">
        </div>

        <style>
          .hp {
            position: absolute;
            left: -9999px;
            width: 1px;
            height: 1px;
            overflow: hidden;
          }
        </style>

        <div class="mt-5">
          <button
            id="btnRegister"
            type="submit"
            disabled
            class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 box w-full px-4 py-5"
            aria-disabled="true"
            tabindex="-1">
            Register
          </button>

          <?= form_close() ?>

          <a href="<?= site_url('auth/login') ?>"
            data-auth-transition="1"
            class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 bg-background border-(--color)/20 h-10 box mt-4 w-full px-4 py-5">
            Login
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- =========================================================
       NEW LAYOUT CSS (safe, doesn't break your existing signup.css)
       ========================================================= -->
  <style>
    .jm-wrap {
      min-height: 100vh;
      display: grid;
      grid-template-columns: 1.05fr 1fr;
      background: #fff;
      opacity: 0;
      transform: translateY(6px);
      animation: jmFadeIn .35s ease forwards;
    }

    body.jm-leave .jm-wrap {
      animation: jmFadeOut .25s ease forwards;
    }

    @keyframes jmFadeIn {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes jmFadeOut {
      to {
        opacity: 0;
        transform: translateY(-6px);
      }
    }

    .jm-left {
      position: relative;
      background: var(--peso-red);
      color: #fff;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      padding: 28px 28px;
    }

    /* soft blobs */
    .jm-left::before,
    .jm-left::after {
      content: "";
      position: absolute;
      width: 520px;
      height: 520px;
      border-radius: 50%;
      filter: blur(10px);
      opacity: .22;
      background: #fff;
    }

    .jm-left::before {
      top: -220px;
      left: -200px;
    }

    .jm-left::after {
      bottom: -260px;
      right: -240px;
      opacity: .16;
    }

    .jm-brand {
      position: relative;
      z-index: 1;
      display: inline-flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
    }

    .jm-logo {
      height: 70px;
      width: auto;
      max-width: 180px;
      object-fit: contain;
      display: block;
    }

    .jm-left-body {
      position: relative;
      z-index: 1;
      margin: auto 0;
      max-width: 560px;
      padding: 18px 0 12px;
    }

    .jm-illus img {
      width: 58%;
      max-width: 280px;
      height: auto;
      display: block;
      margin: 0 0 16px;
    }

    .jm-title {
      margin: 0;
      font-size: 44px;
      line-height: 1.1;
      font-weight: 800;
      letter-spacing: -.02em;
    }

    .jm-sub {
      margin: 14px 0 0;
      font-size: 16px;
      line-height: 1.6;
      opacity: .88;
    }

    .jm-badges {
      margin-top: 18px;
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }

    .jm-pill {
      display: inline-flex;
      align-items: center;
      padding: 8px 12px;
      border-radius: 999px;
      background: rgba(255, 255, 255, .14);
      border: 1px solid rgba(255, 255, 255, .22);
      font-weight: 700;
      font-size: 12px;
      letter-spacing: .02em;
    }

    .jm-left-foot {
      position: relative;
      z-index: 1;
      margin-top: 18px;
      opacity: .85;
    }

    .jm-right {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 28px 16px;
      background: linear-gradient(180deg, var(--peso-rose-50), #fff 45%);
    }

    .jm-card {
      width: 100%;
      max-width: 520px;
      background: #fff;
      border: 1px solid rgba(15, 23, 42, .10);
      border-radius: 18px;
      box-shadow: 0 18px 55px rgba(2, 6, 23, .10);
      padding: 22px 22px 20px;
    }

    .jm-card-head {
      margin-bottom: 6px;
    }

    .jm-card-title {
      margin: 0;
      font-size: 28px;
      font-weight: 900;
      letter-spacing: -.01em;
      color: #0f172a;
    }

    .jm-card-note {
      margin-top: 6px;
      color: rgba(15, 23, 42, .70);
      font-size: 14px;
      line-height: 1.5;
    }

    .jm-grid2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
      margin-top: 14px;
    }

    .jm-label {
      display: block;
      font-size: 13px;
      font-weight: 800;
      color: rgba(15, 23, 42, .86);
      margin: 0 0 6px;
    }

    .jm-input {
      width: 100%;
      height: 48px;
      border-radius: 12px;
      border: 1px solid rgba(15, 23, 42, .14);
      padding: 0 14px;
      background: #fff;
      color: #0f172a;
      transition: box-shadow .15s ease, border-color .15s ease;
    }

    .jm-input::placeholder {
      color: rgba(15, 23, 42, .55);
    }

    .jm-input:focus {
      outline: 0;
      border-color: rgba(201, 53, 61, .70);
      box-shadow: 0 0 0 3px var(--peso-ring);
    }

    .jm-pass {
      position: relative;
    }

    .jm-input-pass {
      padding-right: 48px;
    }

    .jm-eye {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      width: 36px;
      height: 36px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 0;
      background: transparent;
      cursor: pointer;
      color: rgba(15, 23, 42, .70);
    }

    .jm-eye:hover {
      color: rgba(15, 23, 42, 1);
    }

    .jm-tos {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .jm-tos-label {
      margin: 0;
      font-size: 13px;
      color: rgba(15, 23, 42, .70);
      font-weight: 700;
    }

    @media (max-width: 1024px) {
      .jm-wrap {
        grid-template-columns: 1fr;
      }

      .jm-left {
        display: none;
      }

      .jm-right {
        padding: 22px 14px;
      }

      .jm-card {
        max-width: 560px;
      }
    }

    @media (max-width: 540px) {
      .jm-grid2 {
        grid-template-columns: 1fr;
      }

      .jm-card {
        padding: 18px 16px;
        border-radius: 16px;
      }

      .jm-card-title {
        font-size: 24px;
      }
    }

    @media (prefers-reduced-motion: reduce) {
      .jm-wrap {
        animation: none;
        opacity: 1;
        transform: none;
      }

      body.jm-leave .jm-wrap {
        animation: none;
      }
    }
  </style>

  <!-- =========================================================
       YOUR EXISTING MODAL + FIXED CLOSE ICON
       (kept identical except “×”)
       ========================================================= -->
  <style>
    #pmModal {
      position: fixed;
      inset: 0;
      display: none;
      align-items: flex-start;
      justify-content: center;
      padding: 1rem;
      z-index: 2147483647
    }

    #pmModal.show {
      display: flex
    }

    #pmBackdrop {
      position: absolute;
      inset: 0;
      background: rgba(2, 6, 23, .62)
    }

    #pmPanel {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 900px;
      max-height: 85vh;
      border-radius: 14px;
      background: #fff;
      border: 1px solid rgba(15, 23, 42, .08);
      box-shadow: 0 24px 80px rgba(2, 6, 23, .35);
      overflow: hidden;
      display: flex;
      flex-direction: column
    }

    #pmHead {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 18px;
      border-bottom: 1px solid #e5e7eb
    }

    #pmTitle {
      font-weight: 700
    }

    #pmBody {
      flex: 1 1 auto;
      overflow: auto;
      max-height: calc(85vh - 110px);
      padding: 16px 18px;
      line-height: 1.6;
      color: #0f172a
    }

    #pmBody h4 {
      margin: 10px 0 6px;
      font-weight: 800
    }

    #pmBody p,
    #pmBody li {
      font-size: .95rem
    }

    #pmFoot {
      position: sticky;
      bottom: 0;
      background: #fff;
      border-top: 1px solid #e5e7eb;
      padding: 10px 18px;
      display: flex;
      justify-content: flex-end;
      gap: .5rem
    }

    .pm-btn {
      border-radius: 10px;
      padding: .55rem 1rem;
      font-weight: 700;
      border: 1px solid #e5e7eb;
      background: #fff;
      cursor: pointer
    }

    .pm-btn-primary {
      background: var(--peso-red);
      border-color: var(--peso-red);
      color: #fff
    }

    .pm-btn-primary:hover {
      background: var(--peso-red-dark);
      border-color: var(--peso-red-dark)
    }

    .pm-btn:hover {
      filter: brightness(.97)
    }

    .pm-close:hover {
      opacity: .85;
      transform: scale(1.02)
    }

    .pm-btn:focus,
    .pm-close:focus {
      outline: 0;
      box-shadow: 0 0 0 3px var(--peso-ring-strong)
    }

    .alert-inline {
      display: flex;
      align-items: center;
      gap: .5rem;
      padding: .5rem .75rem;
      margin-top: .5rem;
      border-radius: .5rem
    }

    .input-invalid {
      border-color: #ef4444 !important;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, .12) !important
    }

    .input-valid {
      border-color: #22c55e !important;
      box-shadow: 0 0 0 3px rgba(34, 197, 94, .12) !important
    }
  </style>

  <div id="pmModal" role="dialog" aria-modal="true" aria-labelledby="pmTitle">
    <div id="pmBackdrop" data-close="1"></div>
    <div id="pmPanel">
      <div id="pmHead">
        <h3 id="pmTitle">Privacy Policy</h3>
        <button type="button" class="pm-btn pm-close" data-close="1" aria-label="Close">&times;</button>
      </div>
      <div id="pmBody">
        <p><strong>Effective date:</strong> <?= date('F j, Y') ?></p>
        <p>Welcome to <strong>JobMatch DavOr</strong>. We connect skilled workers with individuals or employers who need their services. This Privacy Policy explains what data we collect, how we use it, and the choices you have.</p>

        <h4>1) Information We Collect</h4>
        <ul>
          <li>Account data (name, email, password hash), role (worker or client), and account status/approval.</li>
          <li>Profile/portfolio content you add (photos, categories/skills, rates within admin-set ranges, descriptions, images/PDFs).</li>
          <li>Messaging content (threads/messages exchanged in the platform) and hire/transaction activity.</li>
          <li>Reviews/ratings after completed engagements.</li>
          <li>System notifications and related delivery data (email or in-app notification events).</li>
          <li>Basic usage, log, and device information for security and diagnostics.</li>
        </ul>

        <h4>2) How We Use Your Information</h4>
        <ul>
          <li>Create and maintain your account; show worker profiles/portfolios to clients.</li>
          <li>Enable search, hiring, confirmations, and in-app messaging between workers and clients.</li>
          <li>Send notifications about key transaction events; respond to password-reset requests.</li>
          <li>Display/moderate reviews; show admin dashboards/metrics (workers, clients, ongoing/completed engagements).</li>
          <li>Keep the service secure, prevent fraud/abuse, and maintain an audit trail of CRUD operations.</li>
        </ul>

        <h4>3) Legal Bases</h4>
        <p>We process your data to perform our contract with you (providing the JobMatch DavOr service), for our legitimate interests (platform safety and service improvement), and to comply with legal obligations.</p>

        <h4>4) Sharing</h4>
        <p>We share data with service providers that help us operate (ex. hosting, email delivery) under appropriate safeguards. Worker profile content may be visible to signed-in clients as part of matching. We may disclose information if required by law.</p>

        <h4>5) Data Retention</h4>
        <p>We retain account, messaging, and transaction records while your account is active and as required for legitimate business needs and legal obligations. You may request deletion; some records may be retained for compliance, dispute resolution, and security.</p>

        <h4>6) Your Choices & Rights</h4>
        <ul>
          <li>Access and update your profile information in account settings.</li>
          <li>Request deletion of your account (subject to necessary retention).</li>
          <li>Control notification preferences in-app or via email settings.</li>
        </ul>

        <h4>7) Security</h4>
        <p>We use reasonable technical and organizational measures to protect personal data. No method of transmission or storage is 100% secure.</p>

        <h4>8) Children</h4>
        <p>JobMatch DavOr is not directed to children under 16 and we do not knowingly collect personal data from children.</p>

        <h4>9) International Transfers</h4>
        <p>Your information may be processed in countries with different data-protection laws. We take steps to ensure appropriate safeguards are in place.</p>

        <h4>10) Changes</h4>
        <p>We may update this Policy. If changes are material, we’ll notify you in the app or by email.</p>
      </div>
      <div id="pmFoot">
        <button type="button" class="pm-btn" data-close="1">Close</button>
        <button type="button" class="pm-btn pm-btn-primary" id="pmAgree" data-close="1">I Understand</button>
      </div>
    </div>
  </div>

  <!-- keep your scripts (functions unchanged) -->
  <script src="<?= base_url('dist/js/vendors/dom.js') ?>"></script>
  <script src="<?= base_url('dist/js/vendors/lucide.js') ?>"></script>
  <script src="<?= base_url('dist/js/vendors/modal.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/base/page-loader.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/base/lucide.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/theme-switcher.js') ?>"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <script>
    (function() {
      document.querySelectorAll('[data-toggle="password"]').forEach(function(btn) {
        var sel = btn.getAttribute('data-target');
        var input = document.querySelector(sel);
        if (!input) return;

        function setType(type) {
          input.type = type;
          var icon = btn.querySelector('[data-lucide]');
          if (icon) {
            icon.setAttribute('data-lucide', type === 'text' ? 'eye-off' : 'eye');
            if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();
          }
          btn.setAttribute('aria-label', type === 'text' ? 'Hide password' : 'Show password');
        }

        btn.addEventListener('click', function() {
          setType(input.type === 'password' ? 'text' : 'password');
        });
      });
    })();
  </script>

  <script>
    (function() {
      const email = document.getElementById('signupEmail');
      const hint = document.getElementById('emailHint');
      const btn = document.getElementById('btnRegister');
      const url = "<?= site_url('auth/email-available'); ?>";

      if (!email || !hint) return;

      function setInputState(state) {
        email.classList.remove('input-invalid', 'input-valid');
        if (state === 'err') email.classList.add('input-invalid');
        if (state === 'ok') email.classList.add('input-valid');
      }

      function setHint(msg, kind) {
        hint.className = 'alert alert-inline ' + (kind === 'err' ?
          'alert-danger' :
          kind === 'ok' ?
          'alert-success' :
          'alert-info');
        if (!msg) {
          hint.hidden = true;
          return;
        }
        hint.hidden = false;
        hint.textContent = msg;
      }

      function isLikelyEmail(v) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
      }

      let t = null;
      async function check() {
        const v = email.value.trim().toLowerCase();
        const tos = document.getElementById('tos');

        if (!v) {
          setHint('', 'info');
          setInputState('neutral');
          if (btn) btn.disabled = !(tos && tos.checked);
          return;
        }
        if (!isLikelyEmail(v)) {
          setHint('Enter a valid email.', 'err');
          setInputState('err');
          if (btn) btn.disabled = true;
          return;
        }

        try {
          const res = await fetch(url + '?email=' + encodeURIComponent(v), {
            credentials: 'same-origin'
          });
          const data = await res.json();
          if (!data.ok) {
            setHint(data.msg || 'Unable to verify email.', 'err');
            setInputState('err');
            if (btn) btn.disabled = true;
            return;
          }

          if (data.available) {
            setHint('Great, this email is available.', 'ok');
            setInputState('ok');
            if (btn) btn.disabled = !(tos && tos.checked);
          } else {
            setHint('This email is already registered. Please log in or use another email.', 'err');
            setInputState('err');
            if (btn) btn.disabled = true;
          }
        } catch (e) {
          setHint('Unable to verify email right now.', 'err');
          setInputState('err');
          if (btn) btn.disabled = true;
        }
      }

      email.addEventListener('input', () => {
        clearTimeout(t);
        t = setTimeout(check, 350);
      });
      email.addEventListener('blur', check);
      if (email.value) check();
    })();
  </script>

  <script>
    (function() {
      const modal = document.getElementById('pmModal');
      const openEl = document.getElementById('openPrivacy');
      const agree = document.getElementById('pmAgree');
      const tos = document.getElementById('tos');
      const btn = document.getElementById('btnRegister');
      const email = document.getElementById('signupEmail');

      if (!modal || !openEl) return;

      function openModal(e) {
        if (e) e.preventDefault();
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
      }

      function closeModal() {
        modal.classList.remove('show');
        document.body.style.overflow = '';
      }

      function emailIsOk() {
        if (!email) return true;
        const v = (email.value || '').trim();
        return v === '' || email.classList.contains('input-valid');
      }

      function syncBtn() {
        if (!btn) return;
        btn.disabled = !(tos && tos.checked && emailIsOk());
      }

      openEl.addEventListener('click', openModal);

      modal.addEventListener('click', (e) => {
        if (e.target.dataset.close === '1' || e.target.id === 'pmBackdrop') closeModal();
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('show')) closeModal();
      });

      agree && agree.addEventListener('click', function() {
        if (tos && !tos.checked) {
          tos.checked = true;
          tos.dispatchEvent(new Event('change'));
        }
        closeModal();
      });

      tos && tos.addEventListener('change', syncBtn);
      email && email.addEventListener('input', syncBtn);
      email && email.addEventListener('blur', syncBtn);

      syncBtn();
    })();
  </script>

  <script>
    (function() {
      document.querySelectorAll('.alert .btn-close').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var alert = btn.closest('.alert');
          if (!alert) return;
          alert.classList.remove('show');
          alert.addEventListener('transitionend', function() {
            alert.remove();
          }, {
            once: true
          });
        });
      });

      setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
          alert.classList.remove('show');
          alert.addEventListener('transitionend', function() {
            alert.remove();
          }, {
            once: true
          });
        });
      }, 4000);
    })();
  </script>

  <script>
    (function() {
      function shouldIgnore(e, link) {
        return e.defaultPrevented ||
          e.button !== 0 ||
          e.metaKey || e.ctrlKey || e.shiftKey || e.altKey ||
          !link || link.target || link.hasAttribute('download') ||
          link.getAttribute('href') === '#';
      }

      document.querySelectorAll('[data-auth-transition="1"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
          if (shouldIgnore(e, link)) return;
          e.preventDefault();
          document.body.classList.add('jm-leave');
          var href = link.getAttribute('href');
          setTimeout(function() {
            window.location.href = href;
          }, 220);
        });
      });
    })();
  </script>

  <script>
    (function() {
      const form = document.querySelector('form[action*="auth/signup"]');
      const btn = document.getElementById('btnRegister');
      const tos = document.getElementById('tos');
      const email = document.getElementById('signupEmail');

      function emailIsOk() {
        if (!email) return true;
        const v = (email.value || '').trim();
        return v === '' || email.classList.contains('input-valid');
      }
      let greOk = false;
      window.recaptchaOk = function() {
        greOk = true;
        syncBtn();
      };
      window.recaptchaExpired = function() {
        greOk = false;
        syncBtn();
      };
      window.recaptchaError = function() {
        greOk = false;
        syncBtn();
      };

      function applyDisabled(disabled) {
        if (!btn) return;
        btn.disabled = disabled;
        btn.setAttribute('aria-disabled', disabled ? 'true' : 'false');
        if (disabled) btn.setAttribute('tabindex', '-1');
        else btn.removeAttribute('tabindex');
      }

      function syncBtn() {
        const disabled = !(tos && tos.checked && emailIsOk() && greOk);
        applyDisabled(disabled);
      }
      btn && btn.addEventListener('click', function(e) {
        if (btn.disabled) e.preventDefault();
      });
      form && form.addEventListener('submit', function(e) {
        if (btn && btn.disabled) e.preventDefault();
      });

      tos && tos.addEventListener('change', syncBtn);
      email && email.addEventListener('input', syncBtn);
      email && email.addEventListener('blur', syncBtn);
      syncBtn();
    })();
  </script>

</body>

</html>
