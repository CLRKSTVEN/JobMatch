<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <?php $page_title = $page_title ?? 'School Admin Dashboard'; ?>
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?v=5.1.1') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard-school-admin.css?v=1.0.0') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="container-scroller">
    <?php $this->load->view('includes/nav'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php $this->load->view('includes/nav-top'); ?>
      <div class="main-panel">
        <div class="content-wrapper pb-0">
          <div class="px-4 md:px-8 max-w-7xl mx-auto">
            <div class="admin-header">
              <div class="py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                  <h1 class="text-2xl md:text-3xl font-bold"><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></h1>
                  <p class="text-sm muted mt-1">Quick overview of users you manage.</p>
                </div>
                <div class="flex items-center gap-2">
                  <a class="btn btn-primary" href="<?= site_url('school-admin/create') ?>"><i class="mdi mdi-account-plus-outline"></i> Create</a>
                  <a class="btn btn-ghost" href="<?= site_url('school-admin/workers') ?>"><i class="mdi mdi-account-group-outline"></i> Manage</a>
                  <a class="btn btn-ghost" href="<?= site_url('school-admin/bulk') ?>"><i class="mdi mdi-upload"></i> Bulk Upload</a>
                </div>
              </div>
            </div>

            <?php
            $total   = (int)($stats['total'] ?? 0);
            $activeC = (int)($stats['byActive']['1'] ?? 0);
            $inactC  = (int)($stats['byActive']['0'] ?? 0);
            ?>

            <section class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
              <div class="card p-5 kpi">
                <div class="ico gold"><i class="mdi mdi-account-group"></i></div>
                <div>
                  <div class="text-sm muted">Total Users</div>
                  <div class="val"><?= number_format($total) ?></div>
                </div>
              </div>
              <div class="card p-5 kpi">
                <div class="ico"><i class="mdi mdi-check-circle-outline"></i></div>
                <div>
                  <div class="text-sm muted">Active</div>
                  <div class="val"><?= number_format($activeC) ?></div>
                </div>
              </div>
              <div class="card p-5 kpi">
                <div class="ico silver"><i class="mdi mdi-minus-circle-outline"></i></div>
                <div>
                  <div class="text-sm muted">Inactive</div>
                  <div class="val"><?= number_format($inactC) ?></div>
                </div>
              </div>
            </section>

            <section class="mt-6">
              <div class="card p-5">
                <div class="flex items-center justify-between mb-4">
                  <div>
                    <h3 class="text-lg font-semibold">Recent Users</h3>
                    <p class="text-xs muted">Latest added accounts</p>
                  </div>
                  <a class="btn btn-ghost" href="<?= site_url('school-admin/workers') ?>"><i class="mdi mdi-arrow-right"></i> View All</a>
                </div>
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Email</th>
                        <th>Name</th>
                        <th>Role</th>
                        <th>Active</th>
                        <th>Created</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($recent)): foreach ($recent as $u):
                          $r = (string)($u->role ?? '');
                          if (in_array($r, ['admin', 'tesda_admin', 'school admin', 'peso'], true)) {
                            continue;
                          }
                          $fn = (string)($u->first_name ?? '');
                          $ln = (string)($u->last_name ?? '');
                          $name = ($fn !== '' && $ln !== '') ? ($ln . ', ' . $fn) : ($ln !== '' ? $ln : ($fn !== '' ? $fn : ''));
                      ?>
                          <tr>
                            <td><?= htmlspecialchars((string)$u->email) ?></td>
                            <td><?= htmlspecialchars($name) ?></td>
                            <td><?= htmlspecialchars($r) ?></td>
                            <td><?= ((int)($u->is_active ?? 0) === 1) ? 'Yes' : 'No' ?></td>
                            <td><?= !empty($u->created_at) ? date('M d, Y', strtotime($u->created_at)) : '' ?></td>
                          </tr>
                        <?php endforeach;
                      else: ?>
                        <tr>
                          <td colspan="5" class="text-center muted">No data.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </section>

            <div class="my-8" style="height:1px;background:var(--line)"></div>
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
