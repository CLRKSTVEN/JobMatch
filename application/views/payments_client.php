<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= html_escape($page_title ?? 'Payments (Spend History)') ?> • JobMatch</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?v=1.0.9') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />

  <style>
    :root {
      --ink: #0f172a;
      --muted: #6b7280;
      --line: #e5e7eb;
      --card: #fff;
      --indigo-200: #c7d2fe;
      --indigo-300: #a5b4fc;
      --indigo-400: #818cf8;
      --indigo-500: #6366f1;
      --shadow-1: 0 6px 18px rgba(2, 6, 23, .06), 0 1px 0 rgba(2, 6, 23, .04);
      --shadow-2: 0 16px 36px rgba(2, 6, 23, .12), 0 3px 10px rgba(2, 6, 23, .08)
    }

    body {
      background: #f6f7fb;
      color: var(--ink);
      font-family: "Inter", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial
    }

    .content-wrapper {
      padding-top: 1rem
    }

    .app {
      max-width: 1120px;
      margin: 0 auto;
      padding: 0 16px
    }

    .eyebrow {
      font-size: .85rem;
      color: var(--muted);
      font-weight: 600;
      letter-spacing: .3px
    }

    .card-flat {
      background: var(--card);
      border: 1px solid var(--indigo-200);
      border-radius: 14px;
      box-shadow: var(--shadow-1)
    }

    .pgrid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 18px
    }

    .p-card {
      overflow: hidden;
      display: flex;
      flex-direction: column
    }

    .section {
      padding: 16px
    }

    .badge-soft {
      display: inline-flex;
      align-items: center;
      border-radius: 9999px;
      padding: .2rem .55rem;
      font-weight: 600;
      font-size: .8rem;
      border: 1px solid var(--indigo-200);
      background: #eef2ff;
      color: #3730a3
    }

    .row-top {
      display: flex;
      align-items: center;
      gap: 10px
    }

    .avatar {
      width: 52px;
      height: 52px;
      border-radius: 9999px;
      object-fit: cover;
      border: 2px solid #e5e7eb
    }

    .title {
      font-weight: 700
    }

    .muted {
      color: #6b7280
    }

    .meta {
      color: #94a3b8;
      font-size: .88rem
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      padding: .5rem .9rem;
      border-radius: 10px;
      font-weight: 600;
      font-size: .9rem
    }

    .btn-light {
      background: #fff;
      border: 1px solid var(--line);
      color: var(--ink)
    }

    .btn-brand {
      background: #6366f1;
      border: 1px solid #6366f1;
      color: #fff
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <?php $this->load->view('includes/nav'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php $this->load->view('includes/nav-top'); ?>
      <div class="main-panel">
        <div class="content-wrapper pb-0">
          <div class="app">

            <div class="mb-3">
              <div class="eyebrow"><?= html_escape($page_title ?? 'Payments (Spend History)') ?></div>
            </div>

            <div class="card-flat section mb-3" style="display:flex;gap:12px;align-items:center;justify-content:space-between">
              <div class="d-flex align-items-center gap-3">
                <span class="badge-soft"><i class="mdi mdi-cash-multiple"></i> Total Spend</span>
                <div class="h5 m-0">₱<?= number_format((float)($spend_total ?? 0), 2) ?></div>
              </div>
              <div class="text-muted small"><?= (int)($total_rows ?? 0) ?> payment<?= (int)($total_rows ?? 0) === 1 ? '' : 's' ?></div>
            </div>

            <?php if (empty($items)): ?>
              <div class="card-flat section text-center">
                <i class="mdi mdi-receipt-text-outline" style="font-size:42px;color:#94a3b8"></i>
                <p class="mb-2">No payments yet.</p>
                <a class="btn btn-light" href="<?= site_url('projects/active') ?>"><i class="mdi mdi-briefcase-outline"></i> Post/Manage Projects</a>
              </div>
            <?php else: ?>

              <div class="pgrid">
                <?php foreach ($items as $r): ?>
                  <?php
                  $wname = trim(($r->w_first ?? '') . ' ' . ($r->w_last ?? ''));
                  $seed  = $wname !== '' ? $wname : ($r->w_email ?? 'Worker');
                  $avatar = !empty($r->w_avatar) ? base_url($r->w_avatar)
                    : 'https://api.dicebear.com/9.x/initials/svg?seed=' . rawurlencode($seed);
                  $proj  = $r->project_title ?: ('#' . $r->projectID);
                  $amt   = (float)($r->rate_agreed ?? 0);
                  $unit  = $r->rate_unit ? (' / ' . html_escape($r->rate_unit)) : '';
                  ?>
                  <div class="card-flat p-card">
                    <div class="section">
                      <div class="row-top mb-2">
                        <img class="avatar" src="<?= html_escape($avatar) ?>" alt="Avatar">
                        <div>
                          <div class="title"><?= html_escape($wname ?: ($r->w_email ?? 'Worker #' . $r->workerID)) ?></div>
                          <div class="muted"><i class="mdi mdi-briefcase-outline me-1"></i><?= html_escape($proj) ?></div>
                        </div>
                        <span class="ms-auto badge-soft"><i class="mdi mdi-check-decagram-outline"></i> Paid</span>
                      </div>

                      <div class="d-flex flex-wrap gap-3 small">
                        <span><i class="mdi mdi-cash me-1"></i><strong>₱<?= number_format($amt, 2) ?></strong><?= $unit ?></span>
                        <?php if (!empty($r->paid_at)): ?>
                          <span class="meta"><i class="mdi mdi-calendar-blank me-1"></i><?= date('M d, Y h:i A', strtotime($r->paid_at)) ?></span>
                        <?php endif; ?>
                      </div>

                      <div class="d-flex justify-content-end gap-2 mt-2">
                        <a class="btn btn-light" href="<?= site_url('profile/worker/' . (int)$r->workerID) ?>"><i class="mdi mdi-account"></i> Worker</a>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>

              <?php if (!empty($total_pages) && $total_pages > 1): ?>
                <div class="d-flex justify-content-between align-items-center mt-3">
                  <a class="btn btn-light <?= empty($prev_url) ? 'disabled' : '' ?>" href="<?= $prev_url ?? '#' ?>"><i class="mdi mdi-chevron-left"></i> Prev</a>
                  <div class="text-muted small">Page <?= (int)$page ?> of <?= (int)$total_pages ?></div>
                  <a class="btn btn-light <?= empty($next_url) ? 'disabled' : '' ?>" href="<?= $next_url ?? '#' ?>">Next <i class="mdi mdi-chevron-right"></i></a>
                </div>
              <?php endif; ?>

            <?php endif; ?>

          </div>
        </div>
        <?php $this->load->view('includes/footer'); ?>
      </div>
    </div>
  </div>

  <script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js') ?>"></script>
  <script src="<?= base_url('assets/js/off-canvas.js') ?>"></script>
  <script src="<?= base_url('assets/js/hoverable-collapse.js') ?>"></script>
  <script src="<?= base_url('assets/js/misc.js') ?>"></script>
</body>

</html>