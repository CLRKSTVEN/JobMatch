<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($page_title ?? 'JobMatch DavOr', ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?v=5.0.7') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/responsive.css?v=1.0.0') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/universal.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/dashboard-worker.css?v=1.0.0') ?>">

  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />

</head>

<body>
  <?php $this->load->view('partials/translate_banner'); ?>

  <div class="container-scroller">
    <?php $this->load->view('includes/nav'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php $this->load->view('includes/nav-top'); ?>
      <div class="main-panel">
        <div class="content-wrapper pb-0">
          <div class="app">

            <div class="eyebrow"><?= htmlspecialchars($page_title ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?></div>

            <?php if ($this->session->flashdata('error')): ?>
              <div class="alert alert-danger" role="alert"><?= $this->session->flashdata('error'); ?></div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('success')): ?>
              <div class="alert alert-success" role="alert"><?= $this->session->flashdata('success'); ?></div>
            <?php endif; ?>

            <?php
            $p = isset($profile) ? $profile : null;
            $first_name = $p->first_name ?? ($this->session->userdata('first_name') ?: '');
            $last_name  = $p->last_name  ?? '';
            $full_name  = trim($last_name . ', ' . $first_name);
            $seed       = $full_name !== '' ? $full_name : ($this->session->userdata('first_name') ?: 'Worker');
            $avatarUrl = function_exists('avatar_url')
              ? avatar_url($p->avatar ?? '')
              : (function ($raw) {
                $raw = trim((string)$raw);
                if ($raw !== '' && preg_match('#^https?://#i', $raw)) return $raw;
                if ($raw !== '') return base_url(str_replace('\\', '/', $raw));
                return base_url('uploads/avatars/avatar.png');
              })($p->avatar ?? '');
            $headline   = $p->headline ?? '';
            $bio        = $p->bio ?? '';
            $loc        = trim(($p->brgy ?? '') . (($p->brgy ?? '') && ($p->city ?? '') ? ', ' : '') . ($p->city ?? '') . ((($p->brgy ?? '') || ($p->city ?? '')) && ($p->province ?? '') ? ', ' : '') . ($p->province ?? ''));
            $phoneNo    = $p->phoneNo ?? '';
            $skills     = array_filter(array_map('trim', explode(',', $p->skills ?? '')));
            $creds      = preg_split('/\r\n|\r|\n/', $p->credentials ?? '', -1, PREG_SPLIT_NO_EMPTY);
            $avg        = (float)($p->avgRating ?? 0);
            $days       = array_filter(array_map('trim', explode(',', $p->availability_days ?? '')));
            $edu        = $p->education_level ?? '';
            $school     = $p->school ?? '';
            $yr         = $p->year_graduated ?? '';
            $course     = $p->course ?? '';
            $tesda      = $p->tesda_cert_no ?? '';
            $texp       = $p->tesda_expiry ?? '';
            $tesda_qual = trim($p->tesda_qualification ?? '');

            $ncList = [];
            if (!empty($p->tesda_certs)) {
              $tmp = is_string($p->tesda_certs) ? json_decode($p->tesda_certs, true) : (array)$p->tesda_certs;
              if (is_array($tmp)) {
                foreach ($tmp as $row) {
                  $q = trim((string)($row['qualification'] ?? ''));
                  $n = trim((string)($row['number'] ?? ''));
                  $e = trim((string)($row['expiry'] ?? ''));
                  if ($q !== '' || $n !== '' || $e !== '') {
                    $ncList[] = ['qualification' => $q, 'number' => $n, 'expiry' => $e];
                  }
                }
              }
            }
            if (empty($ncList) && ($tesda_qual || $tesda || $texp)) {
              $ncList[] = ['qualification' => $tesda_qual, 'number' => $tesda, 'expiry' => $texp];
            }

            if (!function_exists('nc_status_badge')) {
              function nc_status_badge($date)
              {
                if (!$date) return '';
                try {
                  $today = new DateTime(date('Y-m-d'));
                  $exp   = new DateTime(substr($date, 0, 10));
                  $diff  = (int)$today->diff($exp)->format('%r%a');
                  if ($diff < 0)   return '<span class="badge-soft" style="border-color:#fecaca;color:#b91c1c;background:#fff1f2">Expired</span>';
                  if ($diff <= 30) return '<span class="badge-soft" style="border-color:#fde68a;color:#b45309;background:#fffbeb">Expiring soon</span>';
                  return '<span class="badge-soft" style="border-color:#bbf7d0;color:#065f46;background:#ecfdf5">Active</span>';
                } catch (\Throwable $e) {
                  return '';
                }
              }
            }


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

            $certs = [];
            if (!empty($p->certificates)) {
              $tmp = is_string($p->certificates) ? json_decode($p->certificates, true) : (array)$p->certificates;
              if (is_array($tmp)) $certs = array_values(array_filter($tmp));
            } elseif (!empty($p->cert_files)) {
              $tmp = is_string($p->cert_files) ? json_decode($p->cert_files, true) : (array)$p->cert_files;
              if (is_array($tmp)) $certs = array_values(array_filter($tmp));
            }

            $exp        = [];
            if (!empty($p->exp)) {
              $tmp = json_decode($p->exp, true);
              if (is_array($tmp)) $exp = $tmp;
            }
            $langs      = array_filter(array_map('trim', explode(',', $p->languages ?? '')));

            function is_image_path($path)
            {
              $ext = strtolower(pathinfo(is_string($path) ? $path : '', PATHINFO_EXTENSION));
              return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
            }
            function is_pdf_path($path)
            {
              $ext = strtolower(pathinfo(is_string($path) ? $path : '', PATHINFO_EXTENSION));
              return $ext === 'pdf';
            }
            $proofByTitle = [];
            if (!empty($certs)) {
              foreach ($certs as $c) {
                $path  = is_array($c) ? (string)($c['path'] ?? '') : (string)$c;
                if ($path === '') continue;

                $title = is_array($c) ? trim((string)($c['title'] ?? '')) : '';
                if ($title === '') {
                  $title = pathinfo($path, PATHINFO_FILENAME);
                }

                $abs   = preg_match('#^https?://#i', $path) ? $path : base_url($path);
                $view  = function_exists('viewer_url_from_abs') ? (viewer_url_from_abs($abs) ?: $abs) : $abs;

                $proofByTitle[mb_strtolower($title)] = $view;
              }
            }

            $tesdaKeys = [];
            if (!empty($ncList)) {
              foreach ($ncList as $nc) {
                $q = trim((string)($nc['qualification'] ?? ''));
                $n = trim((string)($nc['number'] ?? ''));
                if ($q !== '') $tesdaKeys[] = mb_strtolower($q);
                if ($n !== '') $tesdaKeys[] = mb_strtolower($n);
              }
              $tesdaKeys = array_values(array_unique($tesdaKeys));
            }

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
                  style="object-fit:cover"
                  onerror="this.onerror=null;this.src='<?= $defaultEsc ?>';">
                <div>
                  <div class="profile-title" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
                    <h3 class="profile-name"><?= htmlspecialchars($full_name !== '' ? $full_name : ($email ?? 'Worker'), ENT_QUOTES, 'UTF-8') ?></h3>

                  </div>
                  <?php if ($headline): ?>
                    <div class="profile-sub"><?= htmlspecialchars($headline, ENT_QUOTES, 'UTF-8') ?></div>
                  <?php endif; ?>
                  <div class="meta" style="margin-top:2px">
                    <?php if ($loc): ?><span class="me-3"><i class="mdi mdi-map-marker"></i> <?= htmlspecialchars($loc, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                    <?php if ($phoneNo): ?><span><i class="mdi mdi-phone"></i> <?= htmlspecialchars($phoneNo, ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                  </div>
                </div>
                <div class="badges">
                  <a href="<?= site_url('profile/edit') ?>" class="btn-primary-brand"><i class="mdi mdi-pencil"></i>Edit Profile</a>
                </div>
              </div>
            </div>

            <div class="kpi-grid" style="margin:10px 0 6px">
              <div class="panel kpi">
                <div style="display:flex;align-items:center;gap:10px">
                  <div class="icon" style="background:rgba(193,39,45,.15)"><i class="mdi mdi-briefcase-check" style="font-size:18px;color:#c1272d"></i></div>
                  <div>
                    <div class="label">Times Hired</div>
                    <div class="value"><?= (int)($times_hired ?? 0) ?></div>
                  </div>
                </div>

                <div class="text-muted" style="font-size:12px;margin-top:4px">Last 12 months</div>
              </div>
              <div class="panel kpi">
                <div style="display:flex;align-items:center;gap:10px">
                  <div class="icon" style="background:rgba(230,48,49,.12)"><i class="mdi mdi-star" style="font-size:18px;color:#e63031"></i></div>
                  <div>
                    <div class="label">Average Rating</div>
                    <div class="value"><?= number_format((float)($p->avgRating ?? 0), 2) ?></div>
                  </div>
                </div>

                <div class="text-muted" style="font-size:12px;margin-top:4px">Based on latest jobs</div>
              </div>

              <?php $pc = (int)($completion['percent'] ?? 0);
              $missing = (array)($completion['missing'] ?? []); ?>
              <div class="panel kpi">
                <div class="progress-wrap">
                  <div
                    class="progress-ring"
                    style="--val: <?= $pc ?>; --accent: <?= $pc >= 100 ? '#34495e' : '#e74c3c' ?>;">
                    <span><?= $pc ?>%</span>
                  </div>
                  <div>
                    <div class="label">Profile Completion</div>
                    <div class="value"><?= $pc ?>%</div>
                    <div class="text-muted" style="font-size:12px;margin-top:4px">
                      <?php if ($pc >= 100): ?>
                        All set nice work!
                      <?php else: ?>
                        Missing items below.
                      <?php endif; ?>
                    </div>
                  </div>
                </div>

                <?php if ($pc < 100): ?>
                  <div class="list-missing">
                    <?php if (!empty($missing)):
                      $tops = array_slice($missing, 0, 3);
                    ?>
                      Top missing:
                      <?php foreach ($tops as $m): ?>
                        <span class="chip"><i class="mdi mdi-checkbox-blank-circle-outline"></i><?= htmlspecialchars($m, ENT_QUOTES, 'UTF-8') ?></span>
                      <?php endforeach; ?>
                      <?php if (count($missing) > 3): ?>
                        <span class="text-muted">+<?= count($missing) - 3 ?> more</span>
                      <?php endif; ?>
                      <a href="<?= site_url('profile/edit') ?>" class="c-tag" style="margin-left:6px">
                        <i class="mdi mdi-pencil"></i> Complete
                      </a>
                    <?php else: ?>
                      Almost there add a bit more info.
                    <?php endif; ?>
                  </div>
                <?php endif; ?>
              </div>

            </div>

            <div class="layout">
              <section class="panel">
                <div class="panel-head"><i class="mdi mdi-information-outline"></i>
                  <h6>About</h6>
                </div>
                <div class="panel-body">
                  <?php if ($bio): ?>
                    <div class="wrap"><?= nl2br(htmlspecialchars($bio, ENT_QUOTES, 'UTF-8')) ?></div>
                  <?php else: ?><div class="empty">No bio yet. Add a short summary about your background and strengths.</div><?php endif; ?>
                </div>
              </section>

              <section class="panel">
                <div class="panel-head"><i class="mdi mdi-lightbulb-on-outline"></i>
                  <h6>Skills</h6>
                </div>
                <div class="panel-body">
                  <?php if (!empty($skills)): ?>
                    <div class="chips">
                      <?php foreach ($skills as $s): ?>
                        <span class="chip chip--gold"><i class="mdi mdi-tag-outline"></i><?= htmlspecialchars($s, ENT_QUOTES, 'UTF-8') ?></span>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?><div class="empty">No skills yet.</div><?php endif; ?>
                </div>
              </section>

              <section class="panel">
                <div class="panel-head"><i class="mdi mdi-calendar-clock"></i>
                  <h6>Availability</h6>
                </div>
                <div class="panel-body">
                  <?php if (!empty($days)): ?>
                    <div class="chips">
                      <?php foreach ($days as $d): ?>
                        <span class="chip"><i class="mdi mdi-calendar-blank"></i><?= htmlspecialchars($d, ENT_QUOTES, 'UTF-8') ?></span>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?><div class="empty">No availability set.</div><?php endif; ?>
                </div>
              </section>

              <section class="panel">
                <div class="panel-head"><i class="mdi mdi-school-outline"></i>
                  <h6>Education & Links</h6>
                </div>
                <div class="panel-body">
                  <?php
                  $hasRight = ($edu || $school || $yr) || (!empty($p->portfolio_url) || !empty($p->facebook_url));
                  ?>
                  <?php if (!$hasRight): ?>
                    <div class="empty">No education or links yet.</div>
                  <?php else: ?>

                    <?php
                    $eduPrimary = trim((string)$course) !== '' ? trim((string)$course) : trim((string)$edu);
                    $eduParts = array_filter([
                      $eduPrimary,
                      trim((string)$school),
                      trim((string)$yr),
                    ], function ($v) {
                      return $v !== '';
                    });
                    $eduText = implode(' • ', $eduParts);
                    ?>

                    <?php if ($eduText !== ''): ?>
                      <div class="mb-1"><strong>Education:</strong> <?= htmlspecialchars($eduText, ENT_QUOTES, 'UTF-8') ?></div>
                    <?php endif; ?>

                    <?php if (!empty($p->portfolio_url) || !empty($p->facebook_url)): ?>
                      <div class="mt-2 edu-links">

                        <?php if (!empty($p->portfolio_url)): ?>
                          <div><strong>Portfolio:</strong>
                            <a href="<?= htmlspecialchars($p->portfolio_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                              <?= htmlspecialchars($p->portfolio_url, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                          </div>
                        <?php endif; ?>
                        <?php if (!empty($p->facebook_url)): ?>
                          <div><strong>Facebook:</strong>
                            <a href="<?= htmlspecialchars($p->facebook_url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                              <?= htmlspecialchars($p->facebook_url, ENT_QUOTES, 'UTF-8') ?>
                            </a>
                          </div>
                        <?php endif; ?>
                      </div>
                    <?php endif; ?>

                  <?php endif; ?>
                </div>
              </section>

            </div>

            <!-- Saved Documents (from Documents table) -->
            <section class="panel panel--wide">
              <div class="panel-head">
                <i class="mdi mdi-folder-outline"></i>
                <h6>Saved Documents</h6>
              </div>
              <div class="panel-body">
                <?php
                $docs = $docs ?? [];
                $extOf = function ($path) {
                  return strtolower(pathinfo((string)$path, PATHINFO_EXTENSION));
                };
                $makeFileHref = function ($p) {
                  $rel = ltrim((string)$p, '/');
                  $isAbs     = preg_match('#^https?://#i', $rel);
                  $isUploads = preg_match('#^uploads/#i',  $rel);
                  if ($isUploads) return site_url('media/preview') . '?f=' . rawurlencode($rel);
                  return $isAbs ? $rel : base_url($rel);
                };
                $chip = function ($text, $class = '') {
                  return '<span class="chip ' . $class . '">' . htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8') . '</span>';
                };
                ?>

                <?php if (empty($docs)): ?>
                  <div class="empty">No saved documents yet.</div>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-sm table-r" style="width:100%">
                      <thead class="bg-light">
                        <tr>
                          <th>Document</th>
                          <th>Type</th>
                          <th>Certificate</th>
                          <th>Expiry</th>
                          <th>File</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($docs as $r):
                          $name  = trim((string)($r->doc_name    ?? $r['doc_name']    ?? ''));
                          $type  = trim((string)($r->doc_type    ?? $r['doc_type']    ?? ''));
                          $skill = trim((string)($r->skill       ?? $r['skill']       ?? ''));
                          $exp   = trim((string)($r->expiry_date ?? $r['expiry_date'] ?? ''));
                          $file  = (string)($r->file_path ?? $r['file_path'] ?? '');
                          $href  = $file !== '' ? $makeFileHref($file) : '';
                          $ext   = $extOf($file);
                          $badge = ($exp !== '' && function_exists('nc_status_badge')) ? nc_status_badge($exp) : '';

                          // NEW: compute certificate label
                          $other = trim((string)($r->other_choice ?? $r['other_choice'] ?? ''));
                          $certLabel = '';
                          if ($skill !== '') {
                            $certLabel = $skill;
                          } elseif ($type !== '' && preg_match('/certificate/i', $type)) {
                            $certLabel = $type;
                          } elseif (strtolower($other) === 'certificate') {
                            $certLabel = $type !== '' ? $type : 'Certificate';
                          }
                        ?>
                          <tr>
                            <td class="fw-medium"><?= htmlspecialchars($name ?: '(Untitled)', ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= $type  ? $chip($type,  'chip--gold') : '—' ?></td>

                            <td><?= $certLabel ? $chip($certLabel) : '—' ?></td>

                            <td>
                              <?php if ($exp): ?>
                                <div><?= htmlspecialchars($exp, ENT_QUOTES, 'UTF-8') ?></div>
                                <div class="mt-1"><?= $badge ?></div>
                                <?php else: ?>—<?php endif; ?>
                            </td>
                            <td>
                              <?php if ($href): ?>
                                <a class="c-tag" style="padding:.2rem .6rem" href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                                  <i class="mdi <?= $ext === 'pdf' ? 'mdi-file-pdf-box' : 'mdi-eye' ?>"></i>
                                  <?= $ext ? strtoupper($ext) : 'View' ?>
                                </a>
                                <?php else: ?>—<?php endif; ?>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>

                    </table>
                  </div>
                <?php endif; ?>
              </div>
            </section>



            <?php
            $xp = [];
            if (!empty($p->exp)) {
              $items = json_decode($p->exp, true);
              if (is_array($items)) {
                foreach ($items as $it) {
                  $xp[] = [
                    'role'     => trim($it['role'] ?? ($it['title'] ?? '')),
                    'employer' => trim($it['employer'] ?? ''),
                    'from'     => trim($it['from'] ?? (!empty($it['created_at']) ? date('M Y', strtotime($it['created_at'])) : '')),
                    'to'       => trim($it['to'] ?? ''),
                    'desc'     => trim($it['desc'] ?? ($it['description'] ?? '')),
                  ];
                }
              }
            }
            ?>

            <section class="panel panel--wide">
              <div class="panel-head"><i class="mdi mdi-briefcase-outline"></i>
                <h6>Experience</h6>
              </div>
              <div class="panel-body">
                <?php if (empty($xp)): ?><div class="empty">No experience added.</div>
                <?php else: ?>
                  <div class="xp">
                    <?php foreach ($xp as $row): ?>
                      <div class="xp-item">
                        <div class="xp-role">
                          <?= htmlspecialchars($row['role'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                          <?php if (!empty($row['employer'])): ?><span class="xp-meta"> • <?= htmlspecialchars($row['employer'], ENT_QUOTES, 'UTF-8') ?></span><?php endif; ?>
                        </div>
                        <div class="xp-meta"><?= htmlspecialchars(trim(($row['from'] ?? '') . ' - ' . ($row['to'] ?? '')), ENT_QUOTES, 'UTF-8') ?></div>
                        <?php if (!empty($row['desc'])): ?><div class="wrap" style="margin-top:4px"><?= nl2br(htmlspecialchars($row['desc'], ENT_QUOTES, 'UTF-8')) ?></div><?php endif; ?>
                      </div>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
            </section>

            <div
              id="jmWorkerChartData"
              hidden
              data-labels="<?= htmlspecialchars(json_encode($labels ?? []), ENT_QUOTES, 'UTF-8') ?>"
              data-values="<?= htmlspecialchars(json_encode($counts ?? []), ENT_QUOTES, 'UTF-8') ?>"></div>

            <section class="panel panel--wide" id="svcMixPanel" data-mix-url="<?= site_url('services/mix') ?>">
              <div class="panel-head">
                <i class="mdi mdi-chart-pie"></i>
                <h6>Types of Service</h6>
                <div id="svcMixPills" class="d-flex gap-2" style="margin-left:auto; display:none"></div>
              </div>
              <div class="panel-body">
                <div class="muted mb-2" id="svcMixCaption">Share of jobs by skill</div>
                <div style="display:grid;grid-template-columns:2fr 1fr;gap:12px;align-items:center">
                  <div style="min-height:320px">
                    <canvas id="svcMixChart" height="300"></canvas>
                  </div>
                  <div id="svcMixLegend" style="font-size:12.5px"></div>
                </div>
              </div>
            </section>
            <div class="panel panel--wide" style="margin-top:6px">
              <div class="panel-head"><i class="mdi mdi-comment-text-outline"></i>
                <h6>Latest Reviews</h6>
              </div>
              <div class="panel-body">
                <?php if (empty($latest_reviews)): ?>
                  <div class="empty">No reviews yet.</div>
                <?php else: ?>
                  <div class="table-responsive">
                    <table class="table table-sm table-r" style="width:100%">
                      <thead class="bg-light">
                        <tr>
                          <th>Client</th>
                          <th>Job</th>
                          <th>Rating</th>
                          <th>Comment</th>
                          <th>When</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($latest_reviews as $r): $stars = (int)($r->rating ?? 0);
                          $comment = trim((string)($r->comment ?? '')); ?>
                          <tr>
                            <td data-label="Client" class="fw-medium"><?= htmlspecialchars($r->client_name ?? '—', ENT_QUOTES) ?></td>
                            <td data-label="Job"><?= htmlspecialchars($r->job_title ?? '—', ENT_QUOTES) ?></td>
                            <td data-label="Rating">
                              <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="mdi <?= $i <= $stars ? 'mdi-star text-warning' : 'mdi-star-outline text-muted' ?>"></i>
                              <?php endfor; ?>
                            </td>
                            <td data-label="Comment">
                              <div class="rv-clamp-2"><?= $comment !== '' ? nl2br(htmlspecialchars($comment, ENT_QUOTES)) : '—' ?></div>
                            </td>
                            <td data-label="When" class="text-muted"><?= htmlspecialchars($r->time_ago ?? '', ENT_QUOTES) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      </tbody>
                    </table>

                  </div>
                <?php endif; ?>
              </div>
            </div>


            <?php $this->load->view('includes/footer'); ?>
          </div>
        </div>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script src="<?= base_url('assets/js/dashboard-worker.js?v=1.0.0') ?>"></script>


      <script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js') ?>"></script>
      <script src="<?= base_url('assets/js/off-canvas.js') ?>"></script>
      <script src="<?= base_url('assets/js/hoverable-collapse.js') ?>"></script>
      <script src="<?= base_url('assets/js/misc.js') ?>"></script>
</body>

</html>
