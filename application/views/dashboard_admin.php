<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <?php $page_title = $page_title ?? 'Admin Dashboard'; ?>
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">

  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?v=5.1.1') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard-admin.css?v=1.0.0') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />

  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-50 text-gray-800">
  <div class="container-scroller">
    <?php $this->load->view('includes/nav'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php $this->load->view('includes/nav-top'); ?>

      <div class="main-panel">
        <div class="content-wrapper pb-0">
          <div class="px-4 md:px-8 max-w-7xl mx-auto">

            <!-- Header -->
            <div class="flex items-center justify-between mb-3 admin-header">
              <div>
                <h1 class="text-xl md:text-2xl font-bold"><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></h1>
              </div>
              <div class="flex items-center gap-2 admin-actions">
                <a class="btn-silver" href="<?= site_url('admin/workers/upload') ?>">
                  <i class="mdi mdi-database-import-outline"></i> Bulk Upload Workers
                </a>
                <a class="btn-blue" href="<?= site_url('admin/skills') ?>">
                  <i class="mdi mdi-hammer-wrench"></i> Manage Skills
                </a>
              </div>
            </div>

            <!-- KPIs -->
            <?php
            $stats = $stats ?? [];

            $total_workers   = (int)($stats['total_workers'] ?? 0);
            $total_clients   = (int)($stats['total_clients'] ?? 0);
            $active_projects = (int)($stats['active_projects'] ?? 0);
            $pending_verify  = (int)($stats['pending_verifications'] ?? 0);
            $completed       = (int)($stats['completed'] ?? 0);
            $ongoing         = (int)($stats['ongoing'] ?? 0);
            $cancelled       = (int)($stats['cancelled'] ?? 0);
            ?>
            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
              <div class="card kpi">
                <div>
                  <div class="stat-label">Total Skilled Workers</div>
                  <div class="text-2xl font-bold"><?= $total_workers ?></div>
                </div>
                <div class="ico" style="background:var(--brand-blue-soft);color:var(--brand-blue)">
                  <i class="mdi mdi-account-hard-hat"></i>
                </div>
              </div>

              <div class="card kpi">
                <div>
                  <div class="stat-label">Total Clients</div>
                  <div class="text-2xl font-bold"><?= $total_clients ?></div>
                </div>
                <div class="ico" style="background:var(--brand-gold-soft);color:var(--brand-gold-dark)">
                  <i class="mdi mdi-account-group-outline"></i>
                </div>
              </div>

              <div class="card kpi">
                <div>
                  <div class="stat-label">Active Projects</div>
                  <div class="text-2xl font-bold"><?= $active_projects ?></div>
                  <div class="flex items-center gap-2 mt-2 text-xs font-semibold text-gray-500">

                  </div>
                </div>
                <div class="ico" style="background:linear-gradient(135deg,var(--brand-gold-soft),var(--brand-silver-light));color:var(--brand-gold-dark);border:1px solid rgba(212,160,23,.4)">
                  <i class="mdi mdi-briefcase-check-outline"></i>
                </div>
              </div>

              <div class="card kpi">
                <div>
                  <div class="stat-label">Pending Verifications</div>
                  <div class="text-2xl font-bold"><?= $pending_verify ?></div>
                </div>
                <div class="ico" style="background:var(--brand-silver-light);color:#4b5563;border:1px solid rgba(197,204,214,.8)">
                  <i class="mdi mdi-shield-account-outline"></i>
                </div>
              </div>
            </section>

            <!-- Secondary KPIs -->
            <section class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
              <div class="card kpi">
                <div>
                  <div class="stat-label">Completed Engagements</div>
                  <div class="text-2xl font-bold"><?= $completed ?></div>
                </div>
                <div class="ico" style="background:rgba(16,185,129,.15);color:#059669">
                  <i class="mdi mdi-check-decagram-outline"></i>
                </div>
              </div>

              <div class="card kpi">
                <div>
                  <div class="stat-label">Ongoing Engagements</div>
                  <div class="text-2xl font-bold"><?= $ongoing ?></div>
                </div>
                <div class="ico" style="background:var(--brand-blue-soft);color:var(--brand-blue)">
                  <i class="mdi mdi-progress-clock"></i>
                </div>
              </div>

              <div class="card kpi">
                <div>
                  <div class="stat-label">Cancelled</div>
                  <div class="text-2xl font-bold"><?= $cancelled ?></div>
                </div>
                <div class="ico" style="background:rgba(239,68,68,.15);color:#dc2626">
                  <i class="mdi mdi-close-octagon-outline"></i>
                </div>
              </div>
            </section>

            <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
              <!-- Chart -->
              <div class="lg:col-span-2 card p-5">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-lg font-semibold">Hires (last 30 days)</h3>
                  <div class="text-xs text-gray-500">Auto-updated</div>
                </div>
                <div class="h-64">
                  <canvas
                    id="hiresChart"
                    data-labels="<?= htmlspecialchars(json_encode($chart_labels ?? ['Day 1', 'Day 5', 'Day 10', 'Day 15', 'Day 20', 'Day 25', 'Day 30']), ENT_QUOTES, 'UTF-8') ?>"
                    data-values="<?= htmlspecialchars(json_encode($chart_values ?? [4, 8, 6, 12, 9, 14, 17]), ENT_QUOTES, 'UTF-8') ?>"></canvas>
                </div>
              </div>

              <!-- Recent Activity -->
              <div class="card p-5">
                <h3 class="text-lg font-semibold mb-3">Recent Activity</h3>
                <ul class="space-y-3 text-sm">
                  <?php $activity = $activity ?? []; ?>
                  <?php foreach ($activity as $a): ?>
                    <li class="flex items-start gap-3">
                      <span class="w-2 h-2 mt-2 rounded-full" style="background:var(--brand-blue)"></span>
                      <div>
                        <div class="font-medium">
                          <i class="mdi <?= htmlspecialchars($a['icon']) ?>"></i>
                          <?= htmlspecialchars($a['title']) ?>
                        </div>
                        <div class="text-gray-500"><?= htmlspecialchars($a['meta']) ?></div>
                      </div>
                    </li>
                  <?php endforeach; ?>
                </ul>

              </div>
            </section>



            <div class="my-6 divider"></div>

          </div>
        </div>

        <?php $this->load->view('includes/footer'); ?>
      </div>
    </div>
  </div>

  <!-- Vendor JS -->
  <script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js') ?>"></script>
  <script src="<?= base_url('assets/js/off-canvas.js') ?>"></script>
  <script src="<?= base_url('assets/js/hoverable-collapse.js') ?>"></script>
  <script src="<?= base_url('assets/js/misc.js') ?>"></script>
  <script src="<?= base_url('assets/js/dashboard-admin.js?v=1.0.0') ?>"></script>

</body>

</html>
