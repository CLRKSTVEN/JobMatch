<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1, viewport-fit=cover, interactive-widget=overlays-content">
  <title>JobMatch DavOr</title>

  <!-- ✅ POPPINS -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/universal.css') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/css/login.css') ?>">

</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="auth-center">

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