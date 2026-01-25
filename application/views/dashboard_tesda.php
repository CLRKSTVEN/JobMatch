<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <?php $page_title = $page_title ?? 'TESDA - Dashboard'; ?>
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?v=1.0.9') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard-tesda.css?v=1.0.0') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />
</head>

<body>
  <div class="container-scroller">
    <?php $this->load->view('includes/nav'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php $this->load->view('includes/nav-top'); ?>

      <div class="main-panel">
        <div class="content-wrapper pb-0">
          <div class="app">

            <!-- HERO (no upload fields here) -->
            <div class="hero mb-3">
              <div class="ico"><i class="mdi mdi-shield-account-outline text-white" style="font-size:22px"></i></div>
              <div>
                <h4><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></h4>
                <div class="sub">Welcome! Manage TESDA-related onboarding of skilled workers from here.</div>
              </div>
            </div>

            <!-- KPIs (pass $stats from controller if available) -->
            <?php
            $k_total     = (int)($stats['workers_total']      ?? $stats['total_workers']     ?? 0);
            $k_new_7d    = (int)($stats['workers_new_7d']     ?? $stats['new_7d']            ?? 0);
            $k_certified = (int)($stats['workers_certified']  ?? $stats['tesda_certified']   ?? $stats['certified'] ?? 0);
            $k_near_exp  = (int)($stats['certs_expiring_30d'] ?? $stats['expiring_30d']      ?? $stats['expiring_30'] ?? 0);

            ?>
            <section class="kpi-grid">
              <div class="kpi">
                <div>
                  <div class="label">Skilled Workers</div>
                  <div class="value"><?= $k_total ?></div>
                </div>
                <div class="icon" style="background:var(--brand-blue-soft)"><i class="mdi mdi-account-hard-hat" style="font-size:20px;color:var(--brand-blue)"></i></div>
              </div>
              <div class="kpi">
                <div>
                  <div class="label">New (last 7 days)</div>
                  <div class="value"><?= $k_new_7d ?></div>
                </div>
                <div class="icon" style="background:var(--brand-blue-soft)"><i class="mdi mdi-account-plus-outline" style="font-size:20px;color:var(--brand-blue)"></i></div>
              </div>
              <div class="kpi">
                <div>
                  <div class="label">TESDA Certified</div>
                  <div class="value"><?= $k_certified ?></div>
                </div>
                <div class="icon" style="background:var(--brand-gold-soft)"><i class="mdi mdi-certificate" style="font-size:20px;color:#b45309"></i></div>
              </div>
              <div class="kpi">
                <div>
                  <div class="label">Certs expiring (30d)</div>
                  <div class="value"><?= $k_near_exp ?></div>
                </div>
                <div class="icon" style="background:var(--brand-gold-soft)"><i class="mdi mdi-clock-alert-outline" style="font-size:20px;color:#92400e"></i></div>
              </div>
            </section>

            <!-- ACTIONS (just links; real upload lives in /tesda/workers/upload) -->
            <section class="panel">
              <h6>Quick Actions</h6>
              <p class="note">Use these tools to onboard and maintain records. The upload flow is on a separate page.</p>
              <div class="actions">
                <a class="btn btn-blue" href="<?= site_url('tesda/workers/upload') ?>">
                  <i class="mdi mdi-database-import-outline"></i> Upload Workers
                </a>
                <a class="btn btn-silver" href="<?= site_url('tesda/workers/template') ?>">
                  <i class="mdi mdi-file-download-outline"></i> Download CSV Template
                </a>
              </div>
            </section>

            <!-- OPTIONAL: guidance -->
            <section class="panel" style="margin-top:12px">
              <h6>Guidelines</h6>
              <ul class="list">
                <li>For bulk onboarding, prepare a CSV using the provided template.</li>
                <li>Only include columns you have data for; others can be left blank.</li>
                <li>You can also add or edit individual workers from the Upload page after preview.</li>
              </ul>
            </section>

          </div>
        </div>
        <?php $this->load->view('includes/footer'); ?>
      </div>
    </div>
  </div>

  <!-- Vendor JS (no upload scripts here) -->
  <script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js') ?>"></script>
  <script src="<?= base_url('assets/js/off-canvas.js') ?>"></script>
  <script src="<?= base_url('assets/js/hoverable-collapse.js') ?>"></script>
  <script src="<?= base_url('assets/js/misc.js') ?>"></script>
</body>

</html>
