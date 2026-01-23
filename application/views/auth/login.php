<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1, viewport-fit=cover, interactive-widget=overlays-content">
  <title>JobMatch DavOr</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/universal.css') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />

  <style>
    html {
      scrollbar-gutter: stable;
    }

    :root {
      /* ===== Match the landing makeover vibe (Blue + Cyan, clean surfaces) ===== */
      --brand-blue: #1340a3;
      --brand-blue-dark: #0a2d73;
      --brand-cyan: #1d9dd8;
      --accent-gold: #f7b500;

      --surface: #ffffff;
      --surface-muted: #f3f6fc;
      --surface-alt: #eef2fb;

      --ink: #0f172a;
      --muted: #64748b;
      --border: #d6def3;

      --ring: rgba(29, 157, 216, .22);
      --shadow: 0 24px 60px rgba(15, 23, 42, .16), 0 10px 30px rgba(15, 23, 42, .10);
      --r: 20px;
    }

    html,
    body {
      height: 100%;
      margin: 0;
    }

    body {
      font-family: "Poppins", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      overflow-y: auto !important;
      color: var(--ink);
      background: linear-gradient(180deg, #fafdff 0%, #eef2fb 70%, #e9eefc 100%);
    }

    .container-scroller,
    .page-body-wrapper,
    .content-wrapper,
    .auth {
      min-height: 100vh;
      padding: 0 !important;
    }

    /* ===== Center wrapper with subtle image + modern glow ===== */
    .auth-center {
      position: relative;
      width: 100%;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: clamp(16px, 3vw, 30px);
      overflow: hidden;
    }

    /* Background image layer */
    .auth-center::before {
      content: "";
      position: absolute;
      inset: 0;
      background: url("<?= base_url('assets/images/bg.jpg'); ?>") center/cover no-repeat;
      opacity: .10;
      z-index: 0;
    }

    /* Color glow overlay */
    .auth-center .bg-glow {
      position: absolute;
      inset: 0;
      background:
        radial-gradient(900px 560px at 18% 18%, rgba(29, 157, 216, .20), transparent 60%),
        radial-gradient(980px 620px at 86% 14%, rgba(19, 64, 163, .18), transparent 62%),
        radial-gradient(860px 520px at 60% 90%, rgba(247, 181, 0, .12), transparent 60%),
        linear-gradient(135deg, rgba(255, 255, 255, .72), rgba(255, 255, 255, .72));
      z-index: 1;
      pointer-events: none;
    }

    /* ===== Card ===== */
    .login-card {
      position: relative;
      z-index: 2;
      width: min(540px, 100%);
      background: rgba(255, 255, 255, .98);
      border: 1px solid rgba(19, 64, 163, .12);
      border-radius: calc(var(--r) + 8px);
      box-shadow: var(--shadow);
      overflow: hidden;
      backdrop-filter: none !important;
      -webkit-backdrop-filter: none !important;
    }

    /* ===== Top header (clean, modern) ===== */
    .card-topbar {
      padding: 18px 18px 14px;
      background:
        radial-gradient(circle at 15% 10%, rgba(29, 157, 216, .18), transparent 55%),
        linear-gradient(135deg, var(--brand-blue) 0%, var(--brand-blue-dark) 100%);
      color: #fff;
      text-align: center;
      position: relative;
    }

    /* Accent strip */
    .card-topbar::after {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--accent-gold), var(--brand-cyan), rgba(255, 255, 255, .35));
      opacity: .95;
    }

    .app-logo {
      display: flex;
      justify-content: center;
      margin-bottom: 10px;
    }

    .app-logo img {
      width: min(270px, 88%);
      height: auto;
      object-fit: contain;
      display: block;

      /* crisp */
      image-rendering: -webkit-optimize-contrast;
      backface-visibility: hidden;
      transform: translateZ(0);
      filter: none !important;

      /* logo capsule */
      background: rgba(255, 255, 255, .92);
      padding: 10px 14px;
      border-radius: 16px;
      box-shadow:
        0 14px 28px rgba(2, 6, 23, .22),
        0 0 0 1px rgba(255, 255, 255, .35) inset;
    }

    .headline {
      margin-top: 6px;
    }

    .headline h2 {
      margin: 0;
      font-size: 18px;
      font-weight: 700;
      letter-spacing: .2px;
    }

    .headline p {
      margin: 4px 0 0;
      font-size: 13px;
      opacity: .90;
    }

    .card-body {
      padding: 20px 20px 22px;
    }

    /* Alerts: keep consistent spacing */
    .alert {
      border-radius: 14px;
      border: 1px solid rgba(15, 23, 42, .10);
    }

    /* Labels */
    .form-group label {
      font-weight: 700;
      color: var(--ink);
      font-size: 12.5px;
      margin-bottom: 6px;
    }

    .form-field {
      position: relative;
    }

    .field-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 20px;
      color: rgba(15, 23, 42, .45);
      pointer-events: none;
    }

    .form-field .form-control {
      border-radius: 16px !important;
      border: 1px solid rgba(19, 64, 163, .16) !important;
      padding: 12px 14px 12px 44px !important;
      background: #fff !important;
      color: var(--ink) !important;
      box-shadow: none !important;
      transition: .18s ease;
      font-weight: 500;
    }

    .form-field .form-control:focus {
      border-color: rgba(29, 157, 216, .85) !important;
      box-shadow: 0 0 0 4px var(--ring) !important;
    }

    .form-control::placeholder {
      color: rgba(100, 116, 139, .92);
      opacity: 1;
    }

    .toggle-eye {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      width: 38px;
      height: 38px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      border: 0;
      background: transparent;
      cursor: pointer;
      color: rgba(15, 23, 42, .55);
      border-radius: 12px;
    }

    .toggle-eye:hover {
      color: rgba(15, 23, 42, .92);
      background: rgba(15, 23, 42, .06);
    }

    .toggle-eye:focus {
      outline: 0;
      box-shadow: 0 0 0 4px var(--ring);
    }

    /* Links */
    .link {
      color: var(--brand-blue);
      font-weight: 700;
      text-decoration: none;
    }

    .link:hover {
      color: var(--brand-blue-dark);
      text-decoration: underline;
    }

    /* ===== Primary button (matches landing) ===== */
    .btn-login {
      border: 0 !important;
      border-radius: 16px !important;
      padding: 12px 14px !important;
      font-weight: 800 !important;
      letter-spacing: .2px;
      background: var(--brand-blue) !important;
      box-shadow: 0 14px 28px rgba(19, 64, 163, .22);
      transition: transform .15s ease, box-shadow .15s ease, background .15s ease;
    }

    .btn-login:hover {
      background: var(--brand-blue-dark) !important;
      transform: translateY(-1px);
      box-shadow: 0 18px 34px rgba(19, 64, 163, .26);
    }

    .btn-login i {
      color: #fff;
      font-size: 18px;
    }

    /* Divider helper row */
    .helper {
      margin-top: 14px;
      padding-top: 14px;
      border-top: 1px solid rgba(214, 222, 243, .8);
      font-size: 13px;
      text-align: center;
      color: var(--muted);
      font-weight: 600;
    }

    /* Small “forgot” row */
    .forgot-row {
      display: flex;
      justify-content: flex-end;
      margin-top: 8px;
    }

    /* Fine-tune on mobile */
    @media (max-width: 420px) {
      .card-body {
        padding: 16px 16px 18px;
      }

      .app-logo img {
        padding: 8px 12px;
        border-radius: 14px;
      }

      .headline h2 {
        font-size: 16px;
      }
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="auth-center">
          <!-- GLOW LAYER -->
          <div class="bg-glow" aria-hidden="true"></div>

          <div class="login-card">

            <div class="card-topbar">
              <div class="app-logo">
                <img src="<?= base_url('assets/images/logo-white2.png'); ?>" alt="JobMatch Logo">
              </div>

              <div class="headline">
                <h2>Sign in to JobMatch</h2>
                <p>Continue to your dashboard and manage opportunities.</p>
              </div>
            </div>

            <div class="card-body auth-form-transparent text-left">

              <?php foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info', 'msg' => 'success'] as $key => $class): ?>
                <?php if ($this->session->flashdata($key)): ?>
                  <?php $isSticky = ($key === 'msg'); ?>
                  <div class="alert alert-<?= $class ?> fade show small mb-3 <?= $isSticky ? 'alert-sticky' : 'autoclose' ?>" role="alert">
                    <?= htmlspecialchars($this->session->flashdata($key), ENT_QUOTES, 'UTF-8') ?>
                    <?php if (!$isSticky): ?>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              <?php endforeach; ?>

              <?php if (validation_errors()): ?>
                <div class="alert alert-danger small mb-3">
                  <?= validation_errors(); ?>
                </div>
              <?php endif; ?>

              <?= form_open('auth/login', ['class' => 'pt-2']); ?>
              <?php if (isset($this->security)): ?>
                <input type="hidden"
                  name="<?= $this->security->get_csrf_token_name(); ?>"
                  value="<?= $this->security->get_csrf_hash(); ?>">
              <?php endif; ?>

              <div class="form-group mb-3">
                <label for="email">Email Address</label>
                <div class="form-field">
                  <i class="mdi mdi-email-outline field-icon" aria-hidden="true"></i>
                  <input type="text"
                    class="form-control form-control-lg"
                    id="email" name="email"
                    value="<?= set_value('email'); ?>"
                    placeholder="you@example.com"
                    autocomplete="username" required>
                </div>
                <?= form_error('email', '<small class="text-danger">', '</small>'); ?>
              </div>

              <div class="form-group mb-2">
                <label for="password">Password</label>
                <div class="form-field">
                  <i class="mdi mdi-lock-outline field-icon" aria-hidden="true"></i>
                  <input type="password"
                    class="form-control form-control-lg"
                    id="password" name="password"
                    placeholder="••••••••"
                    autocomplete="current-password" required>

                  <button type="button"
                    class="toggle-eye"
                    aria-label="Show password"
                    data-target="#password">
                    <i class="mdi mdi-eye-outline" aria-hidden="true" style="font-size:20px"></i>
                  </button>
                </div>

                <div class="forgot-row">
                  <a href="<?= site_url('auth/forgot'); ?>" class="link">Forgot password?</a>
                </div>

                <?= form_error('password', '<small class="text-danger">', '</small>'); ?>
              </div>

              <div class="mt-3 d-grid">
                <button type="submit" class="btn btn-primary btn-lg btn-login">
                  <span class="d-inline-flex align-items-center justify-content-center gap-2">
                    <i class="mdi mdi-login-variant"></i> Login
                  </span>
                </button>
              </div>

              <div class="helper">
                Don't have an account?
                <a href="<?= site_url('auth/signup'); ?>" class="link">Create</a>
              </div>

              <!-- ✅ REMOVED: bottom partner logos -->
              <?= form_close(); ?>

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <!-- Vendor JS -->
  <script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js'); ?>"></script>
  <script src="<?= base_url('assets/js/off-canvas.js'); ?>"></script>
  <script src="<?= base_url('assets/js/hoverable-collapse.js'); ?>"></script>
  <script src="<?= base_url('assets/js/misc.js'); ?>"></script>
  <script src="<?= base_url('assets/js/settings.js'); ?>"></script>
  <script src="<?= base_url('assets/js/todolist.js'); ?>"></script>

  <!-- Password eye -->
  <script>
    (function() {
      var btn = document.querySelector('.toggle-eye[data-target="#password"]');
      var input = document.querySelector('#password');
      if (!btn || !input) return;

      btn.addEventListener('click', function() {
        var isPwd = input.type === 'password';
        input.type = isPwd ? 'text' : 'password';

        var icon = btn.querySelector('.mdi');
        if (icon) {
          icon.classList.remove(isPwd ? 'mdi-eye-outline' : 'mdi-eye-off-outline');
          icon.classList.add(isPwd ? 'mdi-eye-off-outline' : 'mdi-eye-outline');
        }
        btn.setAttribute('aria-label', isPwd ? 'Hide password' : 'Show password');
      });
    })();
  </script>

  <!-- Auto-close non-sticky alerts -->
  <script>
    setTimeout(function() {
      document.querySelectorAll('.alert.autoclose').forEach(function(el) {
        var bsAlert = bootstrap.Alert.getOrCreateInstance(el);
        bsAlert.close();
      });
    }, 4000);
  </script>

</body>

</html>