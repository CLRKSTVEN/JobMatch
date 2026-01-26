<!DOCTYPE html>
<html lang="en">
<link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="LEFT4CODE">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/signup.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">
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
  <div class="jm-wrap jm-wrap--reverse">
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
              <i data-lucide="eye" class="jm-icon"></i>
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

  <script src="<?= base_url('dist/js/vendors/dom.js') ?>"></script>
  <script src="<?= base_url('dist/js/vendors/lucide.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/base/page-loader.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/base/lucide.js') ?>"></script>

  <script src="<?= base_url('assets/js/auth-common.js') ?>"></script>
</body>

</html>
