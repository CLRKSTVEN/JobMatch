<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($page_title ?? 'Reset Password • JobMatch DavOr', ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">

  <style>
    :root {
      --blue-900: #1e3a8a;
      --blue-700: #1d4ed8;
      --blue-600: #2563eb;
      --blue-500: #2563eb;
      --silver-300: #d9dee7;
      --silver-100: #f6f8fc;
      --radius: 16px;
      --shadow: 0 10px 22px rgba(2, 6, 23, .10);
    }

    * {
      box-sizing: border-box
    }

    html,
    body {
      height: 100%
    }

    body {
      margin: 0;
      font-family: "Poppins", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      background: linear-gradient(180deg, var(--silver-100), #eef2f7);
      color: #0f172a;
    }

    .wrap {
      min-height: 100%;
      display: grid;
      place-items: center;
      padding: 22px
    }

    .card {
      width: 100%;
      max-width: 720px;
      background: #fff;
      border: 1px solid var(--silver-300);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden
    }

    .card-head {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 18px 22px;
      background: linear-gradient(90deg, var(--blue-700), var(--blue-500));
      color: #fff
    }

    .card-head .icon {
      width: 42px;
      height: 42px;
      border-radius: 12px;
      background: rgba(255, 255, 255, .15);
      display: grid;
      place-items: center
    }

    .card-head h1 {
      font-size: 22px;
      margin: 0;
      font-weight: 700
    }

    .card-head p {
      margin: 2px 0 0;
      font-size: 13px;
      opacity: .9
    }

    .card-body {
      padding: 24px 22px 26px
    }

    label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: #374151;
      margin-bottom: 6px
    }

    .field {
      position: relative
    }

    .input {
      width: 100%;
      padding: 12px 44px 12px 14px;
      border: 1px solid var(--silver-300);
      border-radius: 12px;
      font-size: 14px;
      outline: none;
      transition: border .15s ease;
    }

    .input:focus {
      border-color: var(--blue-600);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, .12)
    }

    .toggle {
      position: absolute;
      right: 10px;
      top: 50%;
      transform: translateY(-50%);
      background: transparent;
      border: none;
      padding: 4px;
      color: #6b7280;
      cursor: pointer
    }

    .btn {
      width: 100%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      background: linear-gradient(180deg, #2563eb, #2563eb);
      color: #fff;
      font-weight: 700;
      border: none;
      border-radius: 10px;
      padding: 12px 14px;
      cursor: pointer;
    }

    .btn:hover {
      filter: brightness(1.03)
    }

    .back {
      display: inline-block;
      margin-top: 12px;
      color: #2563eb;
      font-weight: 600;
      text-decoration: none
    }

    .alert {
      border-radius: 10px;
      padding: 10px 12px;
      margin-bottom: 12px;
      font-size: 13px;
      font-weight: 600
    }

    .alert--err {
      background: #fef2f2;
      border: 1px solid #fecaca;
      color: #991b1b
    }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="card">
      <div class="card-head">
        <div class="icon"><i class="mdi mdi-lock-reset" style="font-size:22px"></i></div>
        <div>
          <h1>Reset Password</h1>
          <p>Create a strong new password</p>
        </div>
      </div>

      <div class="card-body">
        <?php if ($this->session->flashdata('error')): ?>
          <div class="alert alert--err"><?= htmlspecialchars($this->session->flashdata('error'), ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>
        <?php if (validation_errors()): ?>
          <div class="alert alert--err"><?= validation_errors(); ?></div>
        <?php endif; ?>

        <?= form_open('auth/reset'); ?>
        <?php if (isset($this->security)): ?>
          <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        <?php endif; ?>
        <input type="hidden" name="selector" value="<?= html_escape($selector) ?>">
        <input type="hidden" name="validator" value="<?= html_escape($validator) ?>">

        <div style="margin-bottom:14px">
          <label for="password">New Password</label>
          <div class="field">
            <input id="password" class="input" type="password" name="password" minlength="8" required>
            <button type="button" class="toggle" data-toggle="#password" aria-label="Show password">
              <i class="mdi mdi-eye-outline"></i>
            </button>
          </div>
          <?= form_error('password', '<div class="alert alert--err" style="margin-top:8px">', '</div>'); ?>
        </div>

        <div style="margin-bottom:18px">
          <label for="password2">Confirm Password</label>
          <div class="field">
            <input id="password2" class="input" type="password" name="password2" required>
            <button type="button" class="toggle" data-toggle="#password2" aria-label="Show password">
              <i class="mdi mdi-eye-outline"></i>
            </button>
          </div>
          <?= form_error('password2', '<div class="alert alert--err" style="margin-top:8px">', '</div>'); ?>
        </div>

        <button class="btn" type="submit">
          <i class="mdi mdi-check-circle-outline"></i> Update Password
        </button>
        <?= form_close(); ?>

        <div class="text-center">
          <a class="back" href="<?= site_url('auth/login'); ?>">â† Back to login</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    // eye toggles
    document.querySelectorAll('.toggle').forEach(btn => {
      btn.addEventListener('click', () => {
        const sel = btn.getAttribute('data-toggle');
        const input = document.querySelector(sel);
        if (!input) return;
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.innerHTML = show ?
          '<i class="mdi mdi-eye-off-outline"></i>' :
          '<i class="mdi mdi-eye-outline"></i>';
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
      });
    });
  </script>
</body>

</html>