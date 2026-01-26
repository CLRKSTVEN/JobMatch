<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($page_title ?? 'Hotlines', ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?v=1.0.9') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />

  <style>
    :root {
      --silver-300: #d9dee7;
      --blue-900: #c1272d;
      --shadow-1: 0 6px 16px rgba(2, 6, 23, .08)
    }

    .app {
      max-width: 1000px;
      margin: 0 auto;
      padding: 0 12px
    }

    .eyebrow {
      font-size: 12px;
      color: #64748b;
      font-weight: 600;
      letter-spacing: .2px;
      margin: 4px 0 8px
    }

    .panel {
      background: #fff;
      border: 1px solid var(--silver-300);
      border-radius: 12px;
      box-shadow: var(--shadow-1);
      padding: 12px
    }

    .panel-head {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px
    }

    .panel-head h6 {
      margin: 0;
      font-size: 13px;
      font-weight: 800;
      color: var(--blue-900)
    }

    .h-card {
      border: 1px solid var(--silver-300);
      border-radius: 12px;
      padding: 12px;
      box-shadow: var(--shadow-1);
      background: #fff;
      height: 100%
    }

    .h-title {
      font-weight: 800;
      color: var(--blue-900);
      margin: 0
    }

    .h-sub {
      color: #6b7280;
      font-size: 12px
    }

    .h-phone code {
      font-size: 14px
    }
  </style>
</head>

<body>
  <?php $this->load->view('partials_translate_banner'); ?>
  <div class="container-scroller">
    <?php $this->load->view('includes_nav'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php $this->load->view('includes_nav_top'); ?>
      <div class="main-panel">
        <div class="content-wrapper pb-0">
          <div class="app">

            <div class="eyebrow"><?= htmlspecialchars($page_title ?? 'Hotlines', ENT_QUOTES) ?></div>

            <section class="panel">
              <div class="panel-head">
                <i class="mdi mdi-lifebuoy" style="color:#a7afba;font-size:18px"></i>
                <h6>Hotline Numbers</h6>
                <div class="ms-auto text-muted" style="font-size:12px">
                  Showing for <strong><?= htmlspecialchars($audience, ENT_QUOTES) ?></strong> + <strong>All</strong>
                </div>
              </div>

              <?php if (empty($rows)): ?>
                <div class="empty">No hotlines available.</div>
              <?php else: ?>
                <div class="row g-2">
                  <?php foreach ($rows as $r): ?>
                    <div class="col-md-6">
                      <div class="h-card">
                        <div class="d-flex justify-content-between flex-wrap gap-2">
                          <div>
                            <h6 class="h-title"><?= htmlspecialchars($r->title, ENT_QUOTES) ?></h6>
                            <?php if (!empty($r->agency)): ?>
                              <div class="h-sub"><?= htmlspecialchars($r->agency, ENT_QUOTES) ?></div>
                            <?php endif; ?>
                          </div>
                          <div class="h-phone"><code><?= htmlspecialchars($r->phone, ENT_QUOTES) ?></code></div>
                        </div>
                        <?php if (!empty($r->notes)): ?>
                          <div class="mt-2"><?= nl2br(htmlspecialchars($r->notes, ENT_QUOTES)) ?></div>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </section>

          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js') ?>"></script>
  <script src="<?= base_url('assets/js/off-canvas.js') ?>"></script>
  <script src="<?= base_url('assets/js/hoverable-collapse.js') ?>"></script>
  <script src="<?= base_url('assets/js/misc.js') ?>"></script>
</body>

</html>