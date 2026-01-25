<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($page_title ?? 'JobMatch — Client Dashboard', ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/universal.css') ?>">

  <link rel="stylesheet" href="<?= base_url('assets/vendors/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?v=1.0.5') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard-client.css?v=1.0.0') ?>">
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
            <div class="eyebrow"><?= htmlspecialchars($page_title ?? 'Client Dashboard', ENT_QUOTES, 'UTF-8') ?></div>

            <?php if ($this->session->flashdata('error')): ?>
              <div class="alert alert-danger" role="alert"><?= $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success')): ?>
              <div class="alert alert-success" role="alert"><?= $this->session->flashdata('success'); ?></div>
            <?php endif; ?>

            <?php
            $p = isset($profile) ? $profile : null;

            $first_name   = $p->first_name ?? ($p->fName ?? ($this->session->userdata('first_name') ?: ''));
            $last_name    = $p->last_name  ?? ($p->lName ?? '');
            $display_name = trim(($last_name ?: '') . ($first_name ? ', ' . $first_name : ''));
            $seed_name    = trim(($first_name ?: '') . ' ' . ($last_name ?: ''));
            $seed         = $seed_name !== '' ? $seed_name : ($this->session->userdata('first_name') ?: 'Client');

            $rawAvatar = trim((string)($this->session->userdata('avatar') ?: ($p->avatar ?? '')));

            // Normalize local path once
            $rawAvatarClean = ltrim(str_replace(['\\', './'], ['/', ''], $rawAvatar), '/');

            // Build absolute avatar URL
            if ($rawAvatar !== '' && preg_match('#^https?://#i', $rawAvatar)) {
              $avatarAbs = $rawAvatar;
            } elseif ($rawAvatarClean !== '') {
              $avatarAbs = base_url($rawAvatarClean);
            } else {
              $avatarAbs = base_url('uploads/avatars/avatar.png');
            }

            // If local path, confirm it exists; else fallback
            if ($rawAvatarClean !== '' && !preg_match('#^https?://#i', $rawAvatar)) {
              $absFile = FCPATH . $rawAvatarClean;
              if (!is_file($absFile)) {
                $avatarAbs = base_url('uploads/avatars/avatar.png');
              }
            }

            // Cache-bust: prefer updated_at; else filemtime if local; else 1
            $ver = 1;

            if ($rawAvatarClean !== '' && !preg_match('#^https?://#i', $rawAvatar) && is_file(FCPATH . $rawAvatarClean)) {
              $ver = filemtime(FCPATH . $rawAvatarClean) ?: 1;
            } elseif (!empty($p->updated_at)) {
              $ver = strtotime($p->updated_at) ?: 1;
            }


            $avatarUrl = $avatarAbs . (strpos($avatarAbs, '?') === false ? '?' : '&') . 'v=' . $ver;

            $phoneNo = $p->phoneNo ?? '';
            $loc     = trim(
              ($p->brgy ?? '') .
                (($p->brgy ?? '') && ($p->city ?? '') ? ', ' : '') . ($p->city ?? '') .
                ((($p->brgy ?? '') || ($p->city ?? '')) && ($p->province ?? '') ? ', ' : '') . ($p->province ?? '')
            );
            $address = $p->address ?? '';

            $company    = trim((string)($p->companyName ?? ''));
            $has_company_position_field = function_exists('client_has_company_position_field') ? client_has_company_position_field() : false;
            $company_position = ($has_company_position_field && isset($p->company_position)) ? trim((string)$p->company_position) : '';
            $employer   = trim((string)($p->employer ?? ''));
            $biz_name   = trim((string)($p->business_name ?? ''));
            $biz_loc    = trim((string)($p->business_location ?? ''));

            $org_label  = function_exists('client_org_label') ? client_org_label($p) : '';
            $is_individual_employer = function_exists('client_is_individual_employer') ? client_is_individual_employer($p) : false;

            $has_business_details = ($company !== '' || ($has_company_position_field && $company_position !== '') || $employer !== '' || $biz_name !== '' || $biz_loc !== '');

            $id_image = $p->id_image ?? '';
            $permit   = $p->business_permit ?? '';

            if (!function_exists('viewer_url_from_abs')) {
              function viewer_url_from_abs($absUrl)
              {
                if (!$absUrl) return null;
                $pathOnly = parse_url($absUrl, PHP_URL_PATH);
                if (!$pathOnly) return null;
                $fParam   = ltrim($pathOnly, '/');
                $basePath = trim(parse_url(base_url(), PHP_URL_PATH), '/');
                if ($basePath !== '' && strpos($fParam, $basePath . '/') === 0) {
                  $fParam = substr($fParam, strlen($basePath) + 1);
                }
                return site_url('media/preview') . '?f=' . rawurlencode($fParam);
              }
            }
            if (!function_exists('is_image_path')) {
              function is_image_path($path)
              {
                $ext = strtolower(pathinfo(is_string($path) ? $path : '', PATHINFO_EXTENSION));
                return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true);
              }
            }
            if (!function_exists('is_pdf_path')) {
              function is_pdf_path($path)
              {
                $ext = strtolower(pathinfo(is_string($path) ? $path : '', PATHINFO_EXTENSION));
                return $ext === 'pdf';
              }
            }

            $idAbs           = !empty($id_image) ? base_url(ltrim(str_replace('\\', '/', $id_image), '/')) : null;
            $permitAbs       = !empty($permit)   ? base_url(ltrim(str_replace('\\', '/', $permit), '/'))   : null;
            $idViewerUrl     = $idAbs     ? (viewer_url_from_abs($idAbs)     ?: $idAbs)     : null;
            $permitViewerUrl = $permitAbs ? (viewer_url_from_abs($permitAbs) ?: $permitAbs) : null;

            $jobs_posted = (int)($stats['jobs_posted'] ?? 0);
            $jobs_active = (int)($stats['jobs_active'] ?? 0);
            $hires_total = (int)($stats['hires_total'] ?? 0);
            $spend_total = (float)($stats['spend_total'] ?? 0);

            $certs = [];
            if (!empty($p->certificates)) {
              $tmp = is_string($p->certificates) ? json_decode($p->certificates, true) : (array)$p->certificates;
              if (is_array($tmp)) $certs = array_values(array_filter($tmp));
            } elseif (!empty($p->cert_files)) {
              $tmp = is_string($p->cert_files) ? json_decode($p->cert_files, true) : (array)$p->cert_files;
              if (is_array($tmp)) $certs = array_values(array_filter($tmp));
            }

            $isVerified = ($first_name && $last_name && !empty($id_image));
            ?>


            <div class="profile-card">
              <div class="profile-brandbar"></div>
              <div class="profile-cover"></div>
              <div class="profile-gold"></div>
              <div class="profile-main">
                <?php $defaultEsc = htmlspecialchars(base_url('uploads/avatars/avatar.png'), ENT_QUOTES, 'UTF-8'); ?>
                <img
                  class="avatar"
                  src="<?= htmlspecialchars($avatarUrl, ENT_QUOTES, 'UTF-8') ?>"
                  alt="Avatar"
                  onerror="this.onerror=null;this.src='<?= $defaultEsc ?>';">
                <div>
                  <div class="profile-title" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <h3 class="profile-name">
                      <?= htmlspecialchars($display_name !== '' ? $display_name : ($p->email ?? $this->session->userdata('email') ?? 'Client'), ENT_QUOTES, 'UTF-8') ?>
                    </h3>

                    <span class="badge-soft" title="Account status">
                      <i class="mdi <?= $isVerified ? 'mdi-check-decagram-outline' : 'mdi-alert-decagram-outline' ?>"></i>
                      <?= $isVerified ? 'Verified' : 'Verification Needed' ?>
                    </span>
                  </div>
                  <?php if ($org_label !== ''): ?>
                    <div class="profile-sub">
                      <?= htmlspecialchars($org_label, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                  <?php endif; ?>
                  <?php if ($has_company_position_field && $company_position !== ''): ?>
                    <div class="profile-sub text-xs text-gray-500">
                      <?= htmlspecialchars($company_position, ENT_QUOTES, 'UTF-8') ?>
                    </div>
                  <?php endif; ?>
                  <div class="meta" style="margin-top:2px">
                    <?php if ($loc): ?><span class="me-3"><i class="mdi mdi-map-marker"></i> <?= htmlspecialchars($loc, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                    <?php if ($phoneNo): ?><span class="me-3"><i class="mdi mdi-phone"></i> <?= htmlspecialchars($phoneNo, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                    <?php if ($address): ?><span class="wrap"><i class="mdi mdi-home-map-marker"></i> <?= htmlspecialchars($address, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                  </div>
                </div>
                <div class="badges" style="display:flex;gap:8px;flex-wrap:wrap">
                  <a href="<?= site_url('client/edit') ?>" class="btn-primary-brand"><i class="mdi mdi-pencil"></i>Edit Profile</a>
                  <a href="<?= site_url('projects/create') ?>" class="btn-primary-brand"><i class="mdi mdi-briefcase-plus-outline"></i>Post a Job</a>
                </div>
              </div>
            </div>

            <div class="kpi-grid" style="margin:10px 0 6px">
              <div class="panel kpi">
                <div style="display:flex;align-items:center;gap:10px">
                  <div class="icon" style="background:rgba(193,39,45,.15)"><i class="mdi mdi-briefcase-outline" style="font-size:18px;color:#c1272d"></i></div>
                  <div>
                    <div class="label">Jobs Posted</div>
                    <div class="value"><?= $jobs_posted ?></div>
                  </div>
                </div>
                <div class="text-muted" style="font-size:12px;margin-top:4px">All-time</div>
              </div>
              <div class="panel kpi">
                <div style="display:flex;align-items:center;gap:10px">
                  <div class="icon" style="background:rgba(193,39,45,.12)"><i class="mdi mdi-briefcase-check" style="font-size:18px;color:#c1272d"></i></div>
                  <div>
                    <div class="label">Active Jobs</div>
                    <div class="value"><?= $jobs_active ?></div>
                  </div>
                </div>
                <div class="text-muted" style="font-size:12px;margin-top:4px">Open right now</div>
              </div>
              <div class="panel kpi">
                <div style="display:flex;align-items:center;gap:10px">
                  <div class="icon" style="background:rgba(245,158,11,.12)"><i class="mdi mdi-account-multiple-check" style="font-size:18px;color:#f59e0b"></i></div>
                  <div>
                    <div class="label">Total Hires</div>
                    <div class="value"><?= $hires_total ?></div>
                  </div>
                </div>
                <div class="text-muted" style="font-size:12px;margin-top:4px">All-time</div>
              </div>
              <div class="panel kpi">
                <div style="display:flex;align-items:center;gap:10px">
                  <div class="icon" style="background:rgba(99,102,241,.12)"><i class="mdi mdi-cash-multiple" style="font-size:18px;color:#6366f1"></i></div>
                  <div>
                    <div class="label">Total Spend</div>
                    <div class="value"><?= number_format($spend_total, 2) ?></div>
                  </div>
                </div>
                <div class="text-muted" style="font-size:12px;margin-top:4px">All-time</div>
              </div>
            </div>

            <div class="layout">
              <section class="panel">
                <div class="panel-head"><i class="mdi mdi-briefcase-outline"></i>
                  <h6>Business / Project</h6>
                </div>
                <div class="panel-body">
                  <?php if ($is_individual_employer): ?>
                    <div class="mb-1"><strong>Employer Type:</strong> Individual Employer</div>
                  <?php endif; ?>

                  <?php if (!$has_business_details): ?>
                    <div class="empty">No business or project details yet.</div>
                  <?php else: ?>
                    <?php if ($company !== ''): ?><div class="mb-1"><strong>Company:</strong> <?= htmlspecialchars($company, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                    <?php if ($has_company_position_field && $company_position !== ''): ?><div class="mb-1"><strong>Position:</strong> <?= htmlspecialchars($company_position, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                    <?php if ($employer !== ''): ?><div class="mb-1"><strong>Employer:</strong> <?= htmlspecialchars($employer, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                    <?php if ($biz_name !== ''): ?><div class="mb-1"><strong>Project / Business Name:</strong> <?= htmlspecialchars($biz_name, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                    <?php if ($biz_loc !== ''): ?><div class="mb-1"><strong>Business Location:</strong> <?= htmlspecialchars($biz_loc, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
                  <?php endif; ?>
                </div>
              </section>

              <section class="panel">
                <div class="panel-head"><i class="mdi mdi-shield-account-outline"></i>
                  <h6>Verification & Documents</h6>
                </div>
                <div class="panel-body">
                  <div class="mb-2">
                    <strong>Government ID:</strong>
                    <?php if ($idViewerUrl): ?>
                      <a class="btn-primary-brand" href="<?= htmlspecialchars($idViewerUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                        <i class="mdi mdi-id-card"></i> View
                      </a>
                    <?php else: ?>
                      <span class="meta">Not uploaded</span>
                    <?php endif; ?>
                  </div>

                  <div class="mb-2" style="margin-top:6px">
                    <strong>Business Permit:</strong>
                    <?php if ($permitViewerUrl): ?>
                      <a class="btn-primary-brand" href="<?= htmlspecialchars($permitViewerUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                        <i class="mdi mdi-file-certificate-outline"></i> View
                      </a>
                    <?php else: ?>
                      <span class="meta">Not uploaded</span>
                    <?php endif; ?>
                  </div>

                  <?php if (!empty($certs)): ?>
                    <div style="margin-top:10px">
                      <strong>Certificates:</strong>
                      <?php
                      $items = [];
                      foreach ($certs as $c) {
                        if (is_string($c)) {
                          $path  = $c;
                          $title = pathinfo($c, PATHINFO_FILENAME);
                        } elseif (is_array($c) && !empty($c['path'])) {
                          $path  = (string)$c['path'];
                          $title = trim($c['title'] ?? pathinfo($path, PATHINFO_FILENAME));
                        } else {
                          continue;
                        }
                        $abs  = preg_match('#^https?://#i', $path) ? $path : base_url($path);
                        $view = viewer_url_from_abs($abs) ?: $abs;
                        $items[] = ['path' => $path, 'title' => $title, 'abs' => $abs, 'view' => $view];
                      }
                      ?>
                      <div class="c-grid certs-row" style="margin-top:6px">
                        <?php foreach ($items as $it):
                          $img = is_image_path($it['path']);
                          $pdf = is_pdf_path($it['path']);
                        ?>
                          <div class="c-card <?= $img ? 'hasimg' : '' ?>"
                            <?= $img ? 'style="background-image:url(\'' . htmlspecialchars($it['abs'], ENT_QUOTES) . '\')"' : '' ?>>
                            <?php if (!$img): ?>
                              <div class="text-center">
                                <i class="mdi <?= $pdf ? 'mdi-file-pdf-box' : 'mdi-file' ?>" style="font-size:40px;<?= $pdf ? 'color:#b91c1c' : 'color:#334155' ?>"></i>
                                <div style="font-size:11px;margin-top:4px;color:#334155;max-width:92%;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                  <?= htmlspecialchars($it['title'], ENT_QUOTES) ?>
                                </div>
                              </div>
                            <?php else: ?>
                              <span class="file-pill"><?= htmlspecialchars(strtoupper(pathinfo($it['path'], PATHINFO_EXTENSION)), ENT_QUOTES) ?></span>
                            <?php endif; ?>
                            <div class="c-overlay">
                              <a href="<?= htmlspecialchars($it['view'], ENT_QUOTES) ?>" target="_blank" rel="noopener" class="c-tag">
                                <i class="mdi mdi-eye"></i> View
                              </a>
                            </div>
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  <?php else: ?>
                    <div class="empty" style="margin-top:8px">No client certificates uploaded.</div>
                  <?php endif; ?>

                  <?php if (!$isVerified || empty($permit)): ?>
                    <div class="empty" style="margin-top:10px">
                      Complete your verification to build trust with workers.
                      <div style="margin-top:6px">
                        <a href="<?= site_url('client/edit') ?>" class="btn-primary-brand"><i class="mdi mdi-upload"></i> Upload Documents</a>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              </section>

              <section class="panel panel--wide">
                <div class="panel-head"><i class="mdi mdi-clipboard-text-outline"></i>
                  <h6>Recent Jobs</h6>
                </div>
                <div class="panel-body">
                  <?php if (empty($recent_jobs)): ?>
                    <div class="empty">No jobs yet. Post your first job to start hiring.</div>
                    <div style="margin-top:8px">
                      <a href="<?= site_url('projects/active') ?>" class="btn-primary-brand"><i class="mdi mdi-briefcase-plus-outline"></i> Post a Job</a>
                    </div>
                  <?php else: ?>
                    <div class="table-responsive">
                      <table class="table table-sm" style="width:100%">
                        <thead class="bg-light">
                          <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Applicants</th>
                            <th>Posted</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($recent_jobs as $j): ?>
                            <tr>
                              <td class="fw-medium"><?= htmlspecialchars($j->title ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                              <td>
                                <?php
                                $status = strtolower($j->status ?? 'open');
                                $icon   = $status === 'open' ? 'mdi-lock-open-outline' : ($status === 'hired' ? 'mdi-account-check' : 'mdi-archive-outline');
                                $label  = ucfirst($status);
                                ?>
                                <span class="badge-soft"><i class="mdi <?= $icon ?>"></i> <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                              </td>
                              <td><?= (int)($j->applicants ?? 0) ?></td>
                              <td class="text-muted"><?= htmlspecialchars($j->posted_ago ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                              <td style="white-space:nowrap">
                                <a class="btn-primary-brand" href="<?= site_url('projects/active') ?>"><i class="mdi mdi-eye-outline"></i> View</a>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  <?php endif; ?>
                </div>
              </section>
            </div>

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
  <script src="<?= base_url('assets/js/dashboard-client.js?v=1.0.0') ?>"></script>

</body>

</html>
