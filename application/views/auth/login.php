<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport"
        content="width=device-width, initial-scale=1, viewport-fit=cover, interactive-widget=overlays-content">
  <title>TrabaWHO?</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/universal.css') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo-small.png'); ?>" />

  <style>
    :root{
      --blue:#0a22ff;
      --blue2:#0b2ea8;
      --yellow:#f7c600;
      --ink:#0f172a;
      --muted:#64748b;
      --ring:rgba(10,34,255,.20);
      --shadow: 0 18px 45px rgba(2, 8, 23, .18);
      --r: 18px;
    }

    html, body { height:100%; margin:0; }
    body{
      font-family:"Poppins",ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;
      overflow-y:auto!important;
      background:#f6f8ff;
    }

    .container-scroller,
    .page-body-wrapper,
    .content-wrapper,
    .auth{ min-height:100vh; padding:0!important; }

    /* ===== Background container with IMAGE + BLUE EFFECT + GLOW ===== */
    .auth-center{
      position:relative;
      width:100%;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding: clamp(16px, 3vw, 28px);
      overflow:hidden;
    }

    /* 1) Background image layer */
    .auth-center::before{
      content:"";
      position:absolute;
      inset:0;
      background: url("<?= base_url('assets/images/bg.jpg'); ?>") center/cover no-repeat;
      opacity:.18; /* bg image opacity */
      z-index:0;
    }

    /* 2) BLUE low-opacity effect overlay (requested) */
    .auth-center .bg-blue-overlay{
      position:absolute;
      inset:0;
      background:
        radial-gradient(900px 520px at 20% 20%, rgba(10,34,255,.35), transparent 60%),
        radial-gradient(900px 520px at 80% 10%, rgba(10,34,255,.22), transparent 62%),
        linear-gradient(135deg, rgba(10,34,255,.18), rgba(10,34,255,.10));
      opacity:.55; /* 👈 controls blue effect strength */
      z-index:1;
      pointer-events:none;
    }

    /* 3) Glow + soft white veil so the card stays readable */
    .auth-center::after{
      content:"";
      position:absolute;
      inset:0;
      background:
        radial-gradient(900px 550px at 15% 10%, rgba(247,198,0,.22), transparent 60%),
        radial-gradient(900px 550px at 85% 25%, rgba(10,34,255,.14), transparent 60%),
        linear-gradient(135deg, rgba(255,255,255,.78), rgba(255,255,255,.78));
      z-index:2;
      pointer-events:none;
    }

    /* Card always on top */
    .login-card{
      position:relative;
      z-index:3;
      width:min(520px, 100%);
      background: rgba(255,255,255,.94);
      border:1px solid rgba(15,23,42,.10);
      border-radius: calc(var(--r) + 6px);
      box-shadow: var(--shadow);
      overflow:hidden;
      backdrop-filter: blur(10px);
    }

    /* ===== Header ===== */
    .card-topbar{
      padding: 18px 18px 14px;
      background: linear-gradient(135deg, var(--blue) 0%, var(--blue2) 80%);
      color:#fff;
      text-align:center;
      position:relative;
    }
    .card-topbar::after{
      content:"";
      position:absolute;
      left:0; right:0; bottom:0;
      height:4px;
      background: linear-gradient(90deg, var(--yellow), rgba(247,198,0,.18));
    }

    /* App Logo (NO SHAPE) */
    .app-logo{
      display:flex;
      justify-content:center;
      margin-bottom:10px;
    }
    .app-logo img{
      width:min(260px,85%);
      height:auto;
      object-fit:contain;
      filter: drop-shadow(0 10px 18px rgba(2,6,23,.20));
      display:block;
    }

    .headline h2{
      margin:0;
      font-size:18px;
      font-weight:800;
      letter-spacing:.2px;
    }
    .headline p{
      margin:6px 0 0;
      color:rgba(255,255,255,.88);
      font-size:13px;
      font-weight:500;
    }

    .card-body{ padding:18px; }

    /* Inputs */
    .form-group label{
      font-weight:700;
      color:var(--ink);
      font-size:12.5px;
      margin-bottom:6px;
    }
    .form-field{ position:relative; }
    .field-icon{
      position:absolute;
      left:14px;
      top:50%;
      transform:translateY(-50%);
      font-size:20px;
      color:rgba(15,23,42,.45);
      pointer-events:none;
    }
    .form-field .form-control{
      border-radius:14px!important;
      border:1px solid rgba(15,23,42,.12)!important;
      padding:12px 14px 12px 44px!important;
      background:#fff!important;
      color:var(--ink)!important;
      box-shadow:none!important;
      transition:.18s ease;
    }
    .form-field .form-control:focus{
      border-color:rgba(10,34,255,.55)!important;
      box-shadow:0 0 0 4px var(--ring)!important;
    }
    .form-control::placeholder{ color: rgba(100,116,139,.9); opacity:1; }

    .toggle-eye{
      position:absolute;
      right:10px;
      top:50%;
      transform:translateY(-50%);
      width:36px; height:36px;
      display:inline-flex; align-items:center; justify-content:center;
      border:0; background:transparent;
      cursor:pointer;
      color: rgba(15,23,42,.55);
      border-radius:10px;
    }
    .toggle-eye:hover{ color: rgba(15,23,42,.9); background: rgba(15,23,42,.06); }
    .toggle-eye:focus{ outline:0; box-shadow: 0 0 0 4px rgba(10,34,255,.20); }

    .btn-login{
      border:0!important;
      border-radius:14px!important;
      padding:12px 14px!important;
      font-weight:800!important;
      letter-spacing:.2px;
      background:linear-gradient(135deg,var(--blue),var(--blue2))!important;
      box-shadow:0 12px 24px rgba(10,34,255,.18);
    }
    .btn-login i{ color:var(--yellow); font-size:18px; }

    .link{
      color: var(--blue2);
      font-weight:700;
      text-decoration:none;
    }
    .link:hover{ text-decoration:underline; }

    .helper{
      margin-top:14px;
      padding-top:14px;
      border-top:1px dashed rgba(15,23,42,.12);
      font-size:13px;
      text-align:center;
      color: var(--muted);
      font-weight:600;
    }

    /* Bottom logos (NO SHAPE) */
    .bottom-logos{
      margin-top:14px;
      padding-top:14px;
      border-top:1px dashed rgba(15,23,42,.12);
      display:flex;
      justify-content:center;
      gap:14px;
      flex-wrap:wrap;
    }
    .bottom-logos .rect{
      height:36px;
      width:140px;
      object-fit:contain;
      display:block;
      filter: drop-shadow(0 8px 14px rgba(2,6,23,.10));
    }
    .bottom-logos .circle{
      height:46px;
      width:46px;
      border-radius:50%;
      object-fit:cover;
      display:block;
      filter: drop-shadow(0 8px 14px rgba(2,6,23,.10));
    }

    @media (max-width: 420px){
      .card-body{ padding:16px; }
      .bottom-logos .rect{ width:120px; height:34px; }
      .bottom-logos .circle{ width:42px; height:42px; }
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="auth-center">
          <!-- BLUE EFFECT LAYER -->
          <div class="bg-blue-overlay" aria-hidden="true"></div>

          <div class="login-card">

            <div class="card-topbar">
              <!-- APP LOGO (1 rectangular) - NO SHAPE -->
              <div class="app-logo">
                <img src="<?= base_url('assets/images/logo-white.png'); ?>" alt="TrabaWHO? Logo">
              </div>

              <div class="headline">
                <!-- <h2>Login</h2>
                <p>Sign in to continue</p> -->
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

                  <div class="d-flex justify-content-end mt-2">
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

                <!-- OTHER 3 LOGOS AT THE BOTTOM (NO SHAPE) -->
                <div class="bottom-logos">
                  <img class="rect" src="<?= base_url('assets/images/partner1.png'); ?>" alt="Partner Logo 1">
                   <img class="circle" src="<?= base_url('assets/images/partner3.png'); ?>" alt="Partner Logo Circle">
                  <img class="rect" src="<?= base_url('assets/images/partner2.png'); ?>" alt="Partner Logo 2">
                  
                </div>

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
    (function(){
      var btn   = document.querySelector('.toggle-eye[data-target="#password"]');
      var input = document.querySelector('#password');
      if(!btn || !input) return;
      btn.addEventListener('click', function(){
        var isPwd = input.type === 'password';
        input.type = isPwd ? 'text' : 'password';
        var icon = btn.querySelector('.mdi');
        if(icon){
          icon.classList.remove(isPwd ? 'mdi-eye-outline' : 'mdi-eye-off-outline');
          icon.classList.add(isPwd ? 'mdi-eye-off-outline' : 'mdi-eye-outline');
        }
        btn.setAttribute('aria-label', isPwd ? 'Hide password' : 'Show password');
      });
    })();
  </script>

  <!-- Auto-close non-sticky alerts -->
  <script>
    setTimeout(function () {
      document.querySelectorAll('.alert.autoclose').forEach(function (el) {
        var bsAlert = bootstrap.Alert.getOrCreateInstance(el);
        bsAlert.close();
      });
    }, 4000);
  </script>

</body>
</html>
