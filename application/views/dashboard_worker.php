<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($page_title ?? 'TrabaWHO', ENT_QUOTES, 'UTF-8') ?></title>

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

  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />
  <style>
    :root{
      --blue-900:#1e3a8a; --blue-700:#1d4ed8; --blue-600:#2563eb; --blue-500:#2563eb;
      --gold-700:#c89113; --gold-600:#f0b429;
      --silver-600:#a7afba; --silver-500:#c0c6d0; --silver-300:#d9dee7; --silver-200:#e7ebf2; --silver-100:#f6f8fc;

      --radius:12px;
      --pad-card:12px;
      --pad-panel:12px;
      --fs-title:20px;
      --fs-sub:12.5px;
      --fs-body:13px;
      --fs-kpi:18px; 
      --fs-kpi-label:12px;

      --shadow-1:0 6px 16px rgba(2,6,23,.08);
    }

    html, body { height:100%; }
    body{
      font-family:"Poppins",ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial;
      font-size: var(--fs-body);
      background: linear-gradient(180deg, var(--silver-100), #eef2f7 60%, #e9edf3 100%);
      color:#0f172a;
    }
    .content-wrapper{padding-top:.6rem}
    .app{max-width:1100px;margin:0 auto;padding:0 12px}
    .eyebrow{font-size:12px;color:#64748b;font-weight:600;letter-spacing:.2px;margin:4px 0 8px}
    .profile-card{position:relative;border-radius:var(--radius);overflow:hidden;background:#fff;box-shadow:var(--shadow-1);border:1px solid var(--silver-300)}
    .profile-cover{height:120px;background:#fff url('<?= base_url("assets/images/trabawhotext1.jpg") ?>') center top/contain no-repeat}
    .profile-brandbar{position:absolute;left:0;top:0;right:0;height:4px;background:linear-gradient(90deg,var(--blue-900),var(--blue-700),var(--blue-500))}
    .profile-gold{height:3px;background:linear-gradient(90deg,var(--gold-700),var(--gold-600))}
    .profile-main{display:grid;grid-template-columns:84px 1fr auto;gap:14px;align-items:center;padding:12px}
    .avatar{width:84px;height:84px;border-radius:50%;object-fit:cover;border:4px solid #fff;margin-top:-60px;box-shadow:0 8px 18px rgba(2,6,23,.14)}
    .profile-name{font-size:var(--fs-title);font-weight:800;margin:0;color:var(--blue-900)}
    .profile-sub{color:#6b7280;font-size:var(--fs-sub)}
    .meta{color:#64748b;font-size:12.5px}
    .meta .mdi{color:var(--blue-600)}
    .btn-primary-brand{
      display:inline-flex;align-items:center;gap:6px;padding:.45rem .8rem;border-radius:10px;
      border:1px solid var(--blue-600);background:#f5f8ff;font-weight:700;color:var(--blue-900);
      text-decoration:none;transition:all .25s ease;
    }
    .btn-primary-brand:hover{
      background:var(--gold-600); border-color:var(--gold-700); color:#111;
      transform:translateY(-1px);
    }
    .badge-soft{display:inline-flex;align-items:center;gap:6px;padding:.25rem .5rem;border-radius:9999px;border:1px solid var(--silver-300);background:#fff;font-weight:700;font-size:12px}
    .panel{background:#fff;border:1px solid var(--silver-300);border-radius:var(--radius);box-shadow:var(--shadow-1);padding:var(--pad-panel)}
    .panel--wide{grid-column:1/-1}
    .panel-head{display:flex;align-items:center;gap:8px;margin-bottom:8px}
    .panel-head i{font-size:18px;color:var(--silver-600)}
    .panel-head h6{margin:0;font-size:13px;font-weight:800;color:var(--blue-900)}
    .empty{color:#6b7280;border:1px dashed var(--silver-300);border-radius:10px;padding:10px;text-align:center;background:linear-gradient(180deg,#fff,#fbfcff)}
    .kpi-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
    @media (max-width:992px){.kpi-grid{grid-template-columns:1fr}}
    .kpi .label{font-size:var(--fs-kpi-label);color:#6b7280}
    .kpi .value{font-size:var(--fs-kpi);font-weight:800;line-height:1.1}
    .kpi .icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center}
    .layout{margin-top:12px;display:grid;grid-template-columns:6fr 5fr;gap:12px}
    @media (max-width:992px){.layout{grid-template-columns:1fr}}
    .chips{display:flex;flex-wrap:wrap;gap:.35rem}
    .chip{display:inline-flex;align-items:center;gap:6px;padding:.3rem .6rem;border-radius:9999px;border:1px solid var(--silver-300);background:#fff;font-size:12px;font-weight:700}
    .chip--gold{border-color:var(--gold-600);background:var(--gold-600);color:#0b1020}
    .c-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px}
    .c-card{position:relative;height:130px;border:1px solid var(--silver-300);border-radius:10px;overflow:hidden;background:#f8fafc;display:flex;align-items:center;justify-content:center;padding:6px}
    .c-card.hasimg{background-size:cover;background-position:center}
    .c-overlay{position:absolute;inset:0;display:flex;align-items:flex-end;justify-content:center;padding:8px;background:linear-gradient(to top, rgba(15,23,42,.55), rgba(15,23,42,.05) 55%, rgba(15,23,42,0))}
    .c-tag{display:inline-flex;align-items:center;gap:.4rem;padding:.35rem .6rem;border-radius:9999px;background:linear-gradient(180deg,var(--blue-700),var(--blue-500));color:#fff;font-weight:800;font-size:12px;transition:all .2s ease;text-decoration:none}
    .c-tag:hover{background:linear-gradient(180deg,var(--gold-700),var(--gold-600)); color:#111}
    .file-pill{position:absolute;top:8px;left:8px;background:#fff;border:1px solid var(--silver-300);border-radius:9999px;padding:.15rem .45rem;font-size:11px;font-weight:700;color:#334155}
    .xp{display:grid;gap:8px}
    .xp-item{border:1px solid var(--silver-300);border-radius:10px;padding:10px}
    .xp-role{font-weight:800;color:var(--blue-900);font-size:13px}
    .xp-meta{color:#6b7280;font-size:12px}
.progress-wrap{display:flex;align-items:center;gap:10px}
.progress-ring{
  --accent:#2563eb;
  --val:0;
  width:56px;height:56px;border-radius:50%;
  background:conic-gradient(var(--accent) calc(var(--val) * 1%), #e5e7eb 0);
  display:grid;place-items:center;
}
.progress-ring > span{
  width:40px;height:40px;border-radius:50%;background:#fff;
  display:grid;place-items:center;font-weight:800;font-size:12px;color:#1e3a8a;
}
.list-missing{margin-top:6px;font-size:12px;color:#6b7280;display:flex;flex-wrap:wrap;gap:.35rem;align-items:center}
.list-missing .chip{display:inline-flex;align-items:center;gap:6px;padding:.3rem .6rem;border-radius:9999px;border:1px solid #fde68a;background:#fffbeb;font-size:12px;font-weight:700}
 .panel{
  background:#fff;
  border:1px solid var(--silver-300);
  border-radius:var(--radius);
  box-shadow:var(--shadow-1);
  padding:var(--pad-panel);
  margin-bottom:14px; 
}

.table-r th,
.table-r td{
  padding-top:12px;
  padding-bottom:12px;
  vertical-align:middle;
}

.table-r td:nth-child(4){ 
  font-size:12px; 
  white-space:nowrap;
}
.table-r td:nth-child(4) .badge-soft{
  font-size:11px; padding:.2rem .45rem;
}


 </style>
 <style>
@media (max-width: 768px){
  .app{padding:0 10px}
  .content-wrapper{padding-top:.4rem}
  .profile-cover{height:88px;background-position:center; background-size:cover}
  .profile-main{
    grid-template-columns:64px 1fr;
    grid-auto-rows:auto;
    align-items:start;
    gap:10px;
    padding:10px;
  }
  .avatar{
    width:64px;height:64px;margin-top:-50px;border-width:3px;
  }
  .profile-title{gap:6px}
  .profile-name{font-size:18px}
  .profile-sub{font-size:12px}
  .meta{font-size:12px}
  .badges{grid-column:1/-1}
  .btn-primary-brand{
    width:100%;
    justify-content:center;
    padding:.5rem .9rem;
    border-radius:12px;
  }
  :root{
    --pad-panel:12px;
    --fs-body:13px;
  }
  .panel{padding:var(--pad-panel); margin-bottom:10px}
  .panel-head h6{font-size:13px}
  .empty{padding:10px}
  .kpi-grid{gap:8px}
  .kpi .value{font-size:17px}
  .layout{grid-template-columns:1fr !important; gap:10px}
  .c-grid{grid-template-columns:repeat(auto-fill,minmax(140px,1fr)); gap:8px}
  .c-card{height:120px;border-radius:10px}
  #svcMixPanel .panel-body > div[style*="grid"]{
    grid-template-columns:1fr !important;
    gap:10px !important;
  }
  #svcMixLegend{margin-top:6px}
  .chips{gap:.3rem}
  .chip, .badge-soft{font-size:11.5px;padding:.28rem .55rem}
  .table-responsive{overflow-x:visible}
  .table-r{width:100%; border-collapse:separate; border-spacing:0 8px}
  .table-r thead{display:none}
  .table-r tbody tr{
    display:block;
    padding:10px;
    border:1px solid var(--silver-300);
    border-radius:12px;
    background:#fff;
    box-shadow:var(--shadow-1);
  }
  .table-r tbody tr + tr{margin-top:8px}
  .table-r td{
    display:grid;
    grid-template-columns:110px 1fr;
    gap:8px;
    padding:6px 0 !important;
    border:0 !important;
    font-size:12.5px;
  }
  .table-r td::before{
    content:attr(data-label);
    font-weight:700;
    color:#334155;
  }
  .table-r td a.c-tag{display:inline-flex; margin-top:2px}
}

@media (max-width: 380px){
  .profile-name{font-size:17px}
  .avatar{width:56px;height:56px;margin-top:-44px}
  .kpi .value{font-size:16px}
  .progress-ring{width:52px;height:52px}
  .progress-ring > span{width:36px;height:36px;font-size:11px}
}

@media (max-width: 768px){
  .table-r td{
    align-items: start;
    word-break: break-word;
    overflow-wrap: anywhere;  
  }
  .table-r td .chip,
  .table-r td .badge-soft{ white-space: normal; } 
  .table-r td [href].c-tag{
    display: inline-flex;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap; 
  }
}

@media (max-width: 430px){
  .table-r tbody tr{ padding: 12px; }
  .table-r td{
    grid-template-columns: minmax(96px, 40%) 1fr;
    gap: 8px;
  }
}

@media (max-width: 380px){
  .table-r td{
    grid-template-columns: 1fr;
  }
  .table-r td::before{
    margin-bottom: 4px;
  }
}

</style>

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
              $full_name  = trim($last_name.', '.$first_name);
              $seed       = $full_name !== '' ? $full_name : ($this->session->userdata('first_name') ?: 'Worker');
$avatarUrl = function_exists('avatar_url')
  ? avatar_url($p->avatar ?? '')
  : (function($raw){
      $raw = trim((string)$raw);
      if ($raw !== '' && preg_match('#^https?://#i', $raw)) return $raw;          
      if ($raw !== '') return base_url(str_replace('\\','/',$raw)); 
      return base_url('uploads/avatars/avatar.png'); 
    })($p->avatar ?? '');
              $headline   = $p->headline ?? '';
              $bio        = $p->bio ?? '';
              $loc        = trim(($p->brgy ?? '').(($p->brgy ?? '') && ($p->city ?? '') ? ', ' : '').($p->city ?? '').((($p->brgy ?? '') || ($p->city ?? '')) && ($p->province ?? '') ? ', ' : '').($p->province ?? ''));
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
  function nc_status_badge($date){
    if (!$date) return '';
    try {
      $today = new DateTime(date('Y-m-d'));
      $exp   = new DateTime(substr($date, 0, 10));
      $diff  = (int)$today->diff($exp)->format('%r%a');
      if ($diff < 0)   return '<span class="badge-soft" style="border-color:#fecaca;color:#b91c1c;background:#fff1f2">Expired</span>';
      if ($diff <= 30) return '<span class="badge-soft" style="border-color:#fde68a;color:#b45309;background:#fffbeb">Expiring soon</span>';
      return '<span class="badge-soft" style="border-color:#bbf7d0;color:#065f46;background:#ecfdf5">Active</span>';
    } catch (\Throwable $e) { return ''; }
  }
}


              if (!function_exists('viewer_url_from_abs')) {
                function viewer_url_from_abs($absUrl) {
                  if (!$absUrl) return null;
                  $pathOnly = parse_url($absUrl, PHP_URL_PATH);
                  if (!$pathOnly) return null;
                  $fParam   = ltrim($pathOnly, '/');
                  $basePath = trim(parse_url(base_url(), PHP_URL_PATH), '/');
                  if ($basePath !== '' && strpos($fParam, $basePath.'/') === 0) {
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
              if (!empty($p->exp)) { $tmp = json_decode($p->exp, true); if (is_array($tmp)) $exp = $tmp; }
              $langs      = array_filter(array_map('trim', explode(',', $p->languages ?? '')));

              function is_image_path($path){
                $ext = strtolower(pathinfo(is_string($path)?$path:'', PATHINFO_EXTENSION));
                return in_array($ext, ['jpg','jpeg','png','webp','gif']);
              }
              function is_pdf_path($path){
                $ext = strtolower(pathinfo(is_string($path)?$path:'', PATHINFO_EXTENSION));
                return $ext === 'pdf';
              }
$proofByTitle = [];
if (!empty($certs)) {
  foreach ($certs as $c) {
    $path  = is_array($c) ? (string)($c['path'] ?? '') : (string)$c;
    if ($path === '') continue;

    $title = is_array($c) ? trim((string)($c['title'] ?? '')) : '';
    if ($title === '') { $title = pathinfo($path, PATHINFO_FILENAME); }

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
                  <div class="icon" style="background:rgba(251,191,36,.18)"><i class="mdi mdi-briefcase-check" style="font-size:18px;color:#b45309"></i></div>
                  <div>
                    <div class="label">Times Hired</div>
                    <div class="value"><?= (int)($times_hired ?? 0) ?></div>
                  </div>
                </div>
                
                <div class="text-muted" style="font-size:12px;margin-top:4px">Last 12 months</div>
              </div>
              <div class="panel kpi">
                <div style="display:flex;align-items:center;gap:10px">
                  <div class="icon" style="background:rgba(245,158,11,.12)"><i class="mdi mdi-star" style="font-size:18px;color:#f59e0b"></i></div>
                  <div>
                    <div class="label">Average Rating</div>
                    <div class="value"><?= number_format((float)($p->avgRating ?? 0), 2) ?></div>
                  </div>
                </div>
                
                <div class="text-muted" style="font-size:12px;margin-top:4px">Based on latest jobs</div>
              </div>
              
           <?php $pc = (int)($completion['percent'] ?? 0); $missing = (array)($completion['missing'] ?? []); ?>
<div class="panel kpi">
  <div class="progress-wrap">
    <div
      class="progress-ring"
      style="--val: <?= $pc ?>; --accent: <?= $pc >= 100 ? '#fbbf24' : '#2563eb' ?>;">
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
                <div class="panel-head"><i class="mdi mdi-information-outline"></i><h6>About</h6></div>
                <div class="panel-body">
                  <?php if ($bio): ?>
                    <div class="wrap"><?= nl2br(htmlspecialchars($bio, ENT_QUOTES, 'UTF-8')) ?></div>
                  <?php else: ?><div class="empty">No bio yet. Add a short summary about your background and strengths.</div><?php endif; ?>
                </div>
              </section>

              <section class="panel">
                <div class="panel-head"><i class="mdi mdi-lightbulb-on-outline"></i><h6>Skills</h6></div>
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
                <div class="panel-head"><i class="mdi mdi-calendar-clock"></i><h6>Availability</h6></div>
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
  <div class="panel-head"><i class="mdi mdi-school-outline"></i><h6>Education & Links</h6></div>
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
        ], function($v){ return $v !== ''; });
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
    <i class="mdi mdi-folder-outline"></i><h6>Saved Documents</h6>
  </div>
  <div class="panel-body">
    <?php
      $docs = $docs ?? [];
      $extOf = function($path){ return strtolower(pathinfo((string)$path, PATHINFO_EXTENSION)); };
      $makeFileHref = function($p){
        $rel = ltrim((string)$p, '/');
        $isAbs     = preg_match('#^https?://#i', $rel);
        $isUploads = preg_match('#^uploads/#i',  $rel);
        if ($isUploads) return site_url('media/preview').'?f='.rawurlencode($rel);
        return $isAbs ? $rel : base_url($rel);
      };
      $chip = function($text, $class=''){ return '<span class="chip '.$class.'">'.htmlspecialchars((string)$text,ENT_QUOTES,'UTF-8').'</span>'; };
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
          <i class="mdi <?= $ext==='pdf' ? 'mdi-file-pdf-box' : 'mdi-eye' ?>"></i>
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
  <div class="panel-head"><i class="mdi mdi-briefcase-outline"></i><h6>Experience</h6></div>
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
                          <div class="xp-meta"><?= htmlspecialchars(trim(($row['from'] ?? '').' - '.($row['to'] ?? '')), ENT_QUOTES, 'UTF-8') ?></div>
                          <?php if (!empty($row['desc'])): ?><div class="wrap" style="margin-top:4px"><?= nl2br(htmlspecialchars($row['desc'], ENT_QUOTES, 'UTF-8')) ?></div><?php endif; ?>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </section>

<section class="panel panel--wide" id="svcMixPanel">
  <div class="panel-head">
    <i class="mdi mdi-chart-pie"></i><h6>Types of Service</h6>
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
  <div class="panel-head"><i class="mdi mdi-comment-text-outline"></i><h6>Latest Reviews</h6></div>
  <div class="panel-body">
    <?php if (empty($latest_reviews)): ?>
      <div class="empty">No reviews yet.</div>
    <?php else: ?>
      <style>
        .clamp-2{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
      </style>

      <div class="table-responsive">
        <table class="table table-sm table-r" style="width:100%">
  <thead class="bg-light">
    <tr>
      <th>Client</th><th>Job</th><th>Rating</th><th>Comment</th><th>When</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($latest_reviews as $r): $stars=(int)($r->rating??0); $comment=trim((string)($r->comment??'')); ?>
      <tr>
        <td data-label="Client" class="fw-medium"><?= htmlspecialchars($r->client_name ?? '—', ENT_QUOTES) ?></td>
        <td data-label="Job"><?= htmlspecialchars($r->job_title ?? '—', ENT_QUOTES) ?></td>
        <td data-label="Rating">
          <?php for($i=1;$i<=5;$i++): ?>
            <i class="mdi <?= $i <= $stars ? 'mdi-star text-warning' : 'mdi-star-outline text-muted' ?>"></i>
          <?php endfor; ?>
        </td>
        <td data-label="Comment"><div class="rv-clamp-2"><?= $comment !== '' ? nl2br(htmlspecialchars($comment,ENT_QUOTES)) : '—' ?></div></td>
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
<?php
  $i18nJs = base_url(
    'assets/js/i18n.js?v='.(is_file(FCPATH.'assets/js/i18n.js') ? filemtime(FCPATH.'assets/js/i18n.js') : time())
  );
  $scanJs = base_url(
    'assets/js/i18n.autoscan.js?v='.(is_file(FCPATH.'assets/js/i18n.autoscan.js') ? filemtime(FCPATH.'assets/js/i18n.autoscan.js') : time())
  );
?>
<script src="<?= $i18nJs ?>"></script>
<script src="<?= $scanJs ?>"></script>
<script>
  document.addEventListener('DOMContentLoaded', async () => {
    const saved = localStorage.getItem('lang_pref') || 'en';
    await I18N.init({ defaultLang: saved });
    I18NAutoScan.init();
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  (function(){
    const el = document.getElementById('workerPerfChart');
    if(!el || typeof Chart === 'undefined') return;

    const labels   = <?= json_encode($labels ?? []) ?>;
    const dataVals = <?= json_encode($counts ?? []) ?>;

    new Chart(el.getContext('2d'), {
      type: 'line',
      data: {
        labels,
        datasets: [{
          label: 'Jobs',
          data: dataVals,
          tension: .35,
          borderWidth: 2,
          borderColor: 'rgba(31,79,209,1)',
          fill: true,
          backgroundColor: function(ctx){
            const g = ctx.chart.ctx.createLinearGradient(0,0,0,180);
            g.addColorStop(0, 'rgba(31,79,209,.18)');
            g.addColorStop(1, 'rgba(31,79,209,.02)');
            return g;
          }
        }]
      },
      options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{legend:{display:false}},
        scales:{
          x:{grid:{display:false}, ticks:{color:'#6B7280', font:{size:11}}},
          y:{grid:{color:'#eef2f7'}, ticks:{precision:0, color:'#6B7280', font:{size:11}}}
        }
      }
    });
  })();
</script>
<script>
(async function(){
  const canvas = document.getElementById('svcMixChart');
  if (!canvas) return;

  const pillsBox = document.getElementById('svcMixPills');
  const caption  = document.getElementById('svcMixCaption');

  async function fetchMix(status){
    const qs = status ? ('?status='+encodeURIComponent(status)) : '';
    const res = await fetch('<?= site_url('services/mix') ?>' + qs, {credentials:'same-origin'});
    const j   = await res.json().catch(()=>null);
    return (j && j.ok && j.items) ? j.items : { rows:[], total:0 };
  }

  function setCaption(kind){
    const map = {
      completed:   'Share of completed jobs by skill',
      in_progress: 'Share of in-progress jobs by skill',
      any:         'Share of all hires by skill'
    };
    caption.textContent = map[kind] || 'Share of jobs by skill';
  }

  function legend(rows, total){
    const box = document.getElementById('svcMixLegend');
    const html = rows.map(r => {
      const pct = total ? Math.round((r.count/total)*100) : 0;
      return `<div style="margin:.25rem 0"><strong>${r.title}</strong>: ${pct}% <span class="text-muted">(${r.count})</span></div>`;
    }).join('') || '<div class="text-muted">No data.</div>';
    box.innerHTML = html;
  }

  let chart;
  function drawPie(rows){
    const labels = rows.map(r => r.title);
    const values = rows.map(r => r.count);
    if (chart) chart.destroy();
    chart = new Chart(canvas.getContext('2d'), {
      type: 'pie',
      data: { labels, datasets: [{ data: values }] },
      options: {
        responsive:true, maintainAspectRatio:false,
        plugins:{
          legend:{ display:false },
          tooltip:{ callbacks:{
            label: (ctx) => {
              const c = ctx.parsed;
              const tot = values.reduce((a,b)=>a+b,0) || 1;
              const pct = Math.round((c/tot)*100);
              return `${ctx.label}: ${c} (${pct}%)`;
            }
          }}
        }
      }
    });
  }

  const [mixCompleted, mixInProg, mixAny] = await Promise.all([
    fetchMix('completed'),
    fetchMix('in_progress'),
    fetchMix('any')
  ]);

  const hasCompleted = (mixCompleted.total || 0) > 0;
  const hasInProg    = (mixInProg.total || 0) > 0;

  let current = hasCompleted ? 'completed' : (hasInProg ? 'in_progress' : 'any');
  let dataMap = { completed: mixCompleted, in_progress: mixInProg, any: mixAny };

  function makePill(kind, label, count){
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'btn btn-light btn-sm';
    btn.innerHTML = `${label}${typeof count==='number' ? ` <span class="text-muted">(${count})</span>` : ''}`;
    btn.dataset.kind = kind;
    btn.addEventListener('click', () => {
      current = kind;
      setActivePill();
      const d = dataMap[kind];
      setCaption(kind);
      drawPie(d.rows);
      legend(d.rows, d.total);
    });
    return btn;
  }
  function setActivePill(){
    pillsBox.querySelectorAll('button').forEach(b=>{
      b.classList.toggle('btn-brand', b.dataset.kind === current);
      b.classList.toggle('btn-light',  b.dataset.kind !== current);
    });
  }

  pillsBox.innerHTML = '';
  const pills = [];

  if (hasCompleted && hasInProg) {
    pills.push(makePill('completed',   'Completed',   mixCompleted.total));
    pills.push(makePill('in_progress', 'In progress', mixInProg.total));
    pills.push(makePill('any',         'All hires',   mixAny.total));
  } else if (hasCompleted || hasInProg) {
    // Only one exists → no pills shown (auto)
  } else if ((mixAny.total||0) > 0) {
    // Only "any" has data → no pills needed
  }

  if (pills.length) {
    pills.forEach(p => pillsBox.appendChild(p));
    pillsBox.style.display = 'flex';
    setActivePill();
  } else {
    pillsBox.style.display = 'none';
  }

  setCaption(current);
  const d0 = dataMap[current];
  drawPie(d0.rows);
  legend(d0.rows, d0.total);
})();
</script>


<script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js') ?>"></script>
<script src="<?= base_url('assets/js/off-canvas.js') ?>"></script>
<script src="<?= base_url('assets/js/hoverable-collapse.js') ?>"></script>
<script src="<?= base_url('assets/js/misc.js') ?>"></script>
</body>
</html>


