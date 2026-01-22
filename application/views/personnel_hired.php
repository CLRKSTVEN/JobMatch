<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($page_title ?? 'Hired Personnel', ENT_QUOTES, 'UTF-8') ?> • JobMatch</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?v=1.0.8') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />

  <style>
    :root {
      --bg: #f8fafc;
      --card: #fff;
      --ink: #0f172a;
      --muted: #64748b;
      --line: #e5e7eb;
      --indigo-200: #c7d2fe;
      --indigo-300: #a5b4fc;
      --indigo-400: #818cf8;
      --indigo-500: #6366f1;
      --shadow-1: 0 6px 18px rgba(2, 6, 23, .06), 0 1px 0 rgba(2, 6, 23, .04);
      --shadow-2: 0 18px 44px rgba(2, 6, 23, .12), 0 6px 18px rgba(2, 6, 23, .08);
    }

    body {
      background: var(--bg);
      color: var(--ink);
      font-family: "Poppins", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial
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
      letter-spacing: .3px;
      margin-bottom: .2rem
    }

    h4 {
      font-size: clamp(18px, 1.9vw, 22px);
      font-weight: 800;
      margin: 0
    }

    .section {
      background: #fff;
      border: 1px solid var(--indigo-200);
      border-radius: 14px;
      box-shadow: var(--shadow-1);
      padding: 16px
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 16px
    }

    .card-flat {
      background: #fff;
      border: 1px solid var(--indigo-200);
      border-radius: 14px;
      box-shadow: var(--shadow-1);
      transition: .18s
    }

    .card-flat:hover {
      border-color: var(--indigo-400);
      box-shadow: var(--shadow-2);
      transform: translateY(-2px)
    }

    .card-body {
      padding: 14px
    }

    .card-footer {
      padding: 12px 14px;
      border-top: 1px solid var(--line);
      background: #fff
    }

    .avatar {
      width: 52px;
      height: 52px;
      border-radius: 9999px;
      object-fit: cover;
      border: 2px solid var(--line)
    }

    .title {
      font-weight: 700
    }

    .muted {
      color: var(--muted)
    }

    .pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: .36rem .7rem;
      border-radius: 9999px;
      border: 1px solid var(--indigo-200);
      background: #fff;
      font-size: .82rem;
      font-weight: 600;
      color: #334155;
      text-decoration: none;
      transition: all .18s ease
    }

    .pill:hover {
      background: var(--indigo-500);
      border-color: var(--indigo-500);
      color: #fff
    }

    .badge-soft {
      display: inline-flex;
      align-items: center;
      border-radius: 9999px;
      padding: .2rem .55rem;
      font-weight: 600;
      font-size: .78rem;
      border: 1px solid var(--indigo-200);
      background: #eef2ff;
      color: #3730a3
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
              <div class="eyebrow">Personnel</div>
              <h4>Hired</h4>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
              <div class="alert alert-success d-flex align-items-center">
                <i class="mdi mdi-check-circle-outline me-2"></i><?= $this->session->flashdata('success'); ?>
              </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
              <div class="alert alert-danger d-flex align-items-center">
                <i class="mdi mdi-alert-circle-outline me-2"></i><?= $this->session->flashdata('error'); ?>
              </div>
            <?php endif; ?>

            <?php
            $list = isset($items) ? $items : (isset($rows) ? $rows : []);
            ?>

            <?php if (empty($list)): ?>
              <div class="section text-center">
                <i class="mdi mdi-account-multiple-outline" style="font-size:42px;color:#94a3b8"></i>
                <p class="mb-1">No hired personnel yet.</p>
              </div>
            <?php else: ?>
              <div class="grid">
                <?php foreach ($list as $r): ?>
                  <?php
                  $full  = trim(($r->first_name ?? '') . ' ' . ($r->last_name ?? ''));
                  $seed  = $full !== '' ? $full : ($r->email ?? 'Worker');
                  $avatar = !empty($r->w_avatar) ? base_url($r->w_avatar)
                    : 'https://api.dicebear.com/9.x/initials/svg?seed=' . rawurlencode($seed);
                  $rateStr = ($r->rate !== null) ? number_format((float)$r->rate, 2) : '—';
                  $unitStr = !empty($r->rate_unit) ? (' / ' . html_escape($r->rate_unit)) : '';
                  $when    = !empty($r->updated_at) ? $r->updated_at : ($r->created_at ?? '');
                  ?>
                  <div class="card-flat">
                    <div class="card-body">
                      <div class="d-flex align-items-center gap-3 mb-1">
                        <img class="avatar" src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>" alt="Avatar">
                        <div>
                          <div class="title"><?= html_escape($full ?: ($r->email ?? 'Worker')) ?></div>
                          <?php if (!empty($r->project_title)): ?>
                            <div class="muted small"><i class="mdi mdi-briefcase-outline me-1"></i><?= html_escape($r->project_title) ?></div>
                          <?php endif; ?>
                        </div>
                      </div>
                      <div class="small mt-2">
                        <span class="me-3"><i class="mdi mdi-cash me-1"></i><strong><?= $rateStr ?></strong><?= $unitStr ?></span>
                        <?php if ($when): ?>
                          <span class="muted"><i class="mdi mdi-calendar-blank me-1"></i><?= date('M d, Y h:i A', strtotime($when)) ?></span>
                        <?php endif; ?>
                      </div>
                    </div>
                    <div class="card-footer d-flex gap-2">
                      <a class="pill" href="<?= site_url('profile/worker/' . (int)$r->worker_id) ?>">
                        <i class="mdi mdi-account"></i> Profile
                      </a>

                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
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