<!DOCTYPE html>
<html lang="en">
<link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="LEFT4CODE">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/signup.css') ?>">
  <title>JobMatch DavOr - Login</title>

  <!-- ✅ keep (your functions/js rely on dist assets & lucide) -->
  <link rel="stylesheet" href="<?= base_url('dist/css/app.css') ?>">
</head>

<body class="overflow-y-auto">
  <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
    <div class="loader-spinner !w-14"></div>
  </div>

  <!-- =========================================================
       ✅ LOGIN LAYOUT (matches signup)
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
          Welcome back.<br>
          Let’s get you in.
        </h1>

        <p class="jm-sub">
          Sign in to manage your jobs, hires, and<br>
          messages with JobMatch DavOr.
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
          <h2 class="jm-card-title">Login</h2>
          <div class="jm-card-note">Welcome back—enter your details to continue.</div>
        </div>

        <?php foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info', 'msg' => 'success'] as $key => $class): ?>
          <?php if ($this->session->flashdata($key)): ?>
            <div class="alert alert-<?= $class ?> alert-dismissible fade show small mb-3" role="alert">
              <?= htmlspecialchars($this->session->flashdata($key), ENT_QUOTES, 'UTF-8') ?>
              <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>

        <?php if (validation_errors()): ?>
          <div class="alert alert-danger small mb-3">
            <?= validation_errors(); ?>
          </div>
        <?php endif; ?>

        <?= form_open('auth/login') ?>
        <?php if (isset($this->security)): ?>
          <input type="hidden"
            name="<?= $this->security->get_csrf_token_name(); ?>"
            value="<?= $this->security->get_csrf_hash(); ?>">
        <?php endif; ?>

        <div class="mt-3">
          <label class="jm-label" for="loginEmail">Email</label>
          <input
            id="loginEmail"
            class="jm-input"
            type="email"
            name="email"
            placeholder="you@example.com"
            value="<?= set_value('email'); ?>"
            autocomplete="username"
            required />
          <?= form_error('email', '<div class="text-red-600 text-sm mt-2">', '</div>'); ?>
        </div>

        <div class="mt-3">
          <label class="jm-label" for="loginPassword">Password</label>
          <div class="jm-pass">
            <input
              id="loginPassword"
              class="jm-input jm-input-pass"
              type="password"
              name="password"
              placeholder="••••••••"
              autocomplete="current-password"
              required />

            <button
              type="button"
              class="jm-eye"
              aria-label="Show password"
              data-toggle="password"
              data-target="#loginPassword">
              <i data-lucide="eye" style="width:20px; height:20px"></i>
            </button>
          </div>

          <div class="jm-meta">
            <a href="<?= site_url('auth/forgot'); ?>" class="jm-link">Forgot password?</a>
          </div>

          <?= form_error('password', '<div class="text-red-600 text-sm mt-2">', '</div>'); ?>
        </div>

        <div class="mt-5">
          <button
            type="submit"
            class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 box w-full px-4 py-5">
            Login
          </button>

          <?= form_close() ?>

          <a href="<?= site_url('auth/signup') ?>"
            data-auth-transition="1"
            class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 bg-background border-(--color)/20 h-10 box mt-4 w-full px-4 py-5">
            Create account
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

    .jm-meta {
      display: flex;
      justify-content: flex-end;
      margin-top: 8px;
    }

    .jm-link {
      color: var(--peso-red);
      font-weight: 800;
      text-decoration: none;
      font-size: 13px;
    }

    .jm-link:hover {
      color: var(--peso-red-dark);
      text-decoration: underline;
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

  <script src="<?= base_url('dist/js/vendors/dom.js') ?>"></script>
  <script src="<?= base_url('dist/js/vendors/lucide.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/base/page-loader.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/base/lucide.js') ?>"></script>

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
</body>

</html>
