<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Welcome to JobMatch DavOr</title>
  <style>
    body {
      background: #f3f5f7;
      margin: 0;
      padding: 24px;
      font-family: Arial, Helvetica, sans-serif;
      color: #111;
    }

    .card {
      max-width: 620px;
      margin: 0 auto;
      background: #fff;
      border: 1px solid #eee;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(16, 24, 40, .08);
    }

    .pad {
      padding: 24px;
    }

    .muted {
      color: #6b7280;
      font-size: 13px
    }

    .bar {
      height: 3px;
      background: #ffd200;
      width: 56px;
      border-radius: 2px;
      margin: 6px 0 16px
    }
  </style>
</head>

<body>
  <div class="card">
    <?php if (!empty($bannerSrc)): ?>
      <div style="height:112px;background: #0b61ff url('<?= htmlspecialchars($bannerSrc) ?>') no-repeat center/280px auto;border-radius:12px 12px 0 0;"></div>
    <?php endif; ?>
    <div class="pad">
      <p style="margin:0 0 6px;font-weight:bold;font-size:18px">
        <span style="color:#0b61ff">Traba</span><span style="color:#ffd200">Who?</span>
      </p>
      <div class="bar"></div>

      <h2 style="margin:0 0 12px;">Welcome, <?= htmlspecialchars($full_name ?? 'User') ?>!</h2>
      <p style="margin:0 0 16px;">Your account has been created with the role <strong><?= htmlspecialchars($role ?? 'worker') ?></strong>.</p>

      <div style="background:#fafafa;border:1px solid #eee;border-radius:8px;padding:12px 14px;margin:16px 0;">
        <p style="margin:0 0 6px;"><strong>Login Email:</strong> <?= htmlspecialchars($email ?? '') ?></p>
        <p style="margin:0;"><strong>Password:</strong> <?= htmlspecialchars($password ?? '') ?></p>
      </div>

      <p class="muted">Tip: change your password after first login.</p>

      <?php if (!empty($logoSrc)): ?>
        <div style="margin-top:18px;display:flex;align-items:center;gap:8px;">
          <img src="<?= htmlspecialchars($logoSrc) ?>" width="28" height="28" style="border-radius:6px;display:block" alt="">
          <span class="muted">City of Mati • JobMatch DavOr</span>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>