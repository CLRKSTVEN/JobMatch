<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= htmlspecialchars($page_title ?? 'TrabaWHO — Client Dashboard', ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/universal.css') ?>">

  <link rel="stylesheet" href="<?= base_url('assets/vendors/font-awesome/css/font-awesome.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?v=1.0.9') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />

  <style>
    html {
      scrollbar-gutter: stable;
    }

    :root {
      --blue-900: #1e3a8a;
      --blue-700: #1d4ed8;
      --blue-600: #2563eb;
      --blue-500: #2563eb;
      --gold-700: #c89113;
      --gold-600: #f0b429;
      --silver-600: #a7afba;
      --silver-500: #c0c6d0;
      --silver-300: #d9dee7;
      --silver-200: #e7ebf2;
      --silver-100: #f6f8fc;

      --radius: 12px;
      --pad-card: 12px;
      --pad-panel: 12px;
      --fs-title: 20px;
      --fs-sub: 12.5px;
      --fs-body: 13px;
      --fs-kpi: 18px;
      --fs-kpi-label: 12px;

      --shadow-1: 0 6px 16px rgba(2, 6, 23, .08);
    }

    html,
    body {
      height: 100%;
    }

    body {
      font-family: "Poppins", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      font-size: var(--fs-body);
      background: linear-gradient(180deg, var(--silver-100), #eef2f7 60%, #e9edf3 100%);
      color: #0f172a;
    }

    .container-fluid.page-body-wrapper .main-panel .content-wrapper {
      padding-left: 12px !important;
      padding-right: 12px !important;
      padding-top: .6rem;
    }

    .app {
      max-width: none;
      width: 100%;
      margin: 0;
      padding: 0 12px;
    }

    .eyebrow {
      font-size: 12px;
      color: #64748b;
      font-weight: 600;
      letter-spacing: .2px;
      margin: 4px 0 8px
    }

    .profile-card {
      position: relative;
      border-radius: var(--radius);
      overflow: hidden;
      background: #fff;
      box-shadow: var(--shadow-1);
      border: 1px solid var(--silver-300)
    }

    .profile-cover {
      height: 120px;
      background: #fff url('<?= base_url("assets/images/trabawhotext1.jpg") ?>') center top/contain no-repeat
    }

    .profile-brandbar {
      position: absolute;
      left: 0;
      top: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--blue-900), var(--blue-700), var(--blue-500))
    }

    .profile-gold {
      height: 3px;
      background: linear-gradient(90deg, var(--gold-700), var(--gold-600))
    }

    .profile-main {
      display: grid;
      grid-template-columns: 84px 1fr auto;
      gap: 14px;
      align-items: center;
      padding: 12px
    }

    .avatar {
      width: 84px;
      height: 84px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #fff;
      margin-top: -60px;
      box-shadow: 0 8px 18px rgba(2, 6, 23, .14)
    }

    .profile-name {
      font-size: var(--fs-title);
      font-weight: 800;
      margin: 0;
      color: var(--blue-900)
    }

    .profile-sub {
      color: #6b7280;
      font-size: var(--fs-sub)
    }

    .meta {
      color: #64748b;
      font-size: 12.5px
    }

    .meta .mdi {
      color: var(--blue-600)
    }

    .btn-primary-brand {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: .45rem .8rem;
      border-radius: 10px;
      border: 1px solid var(--blue-600);
      background: #f5f8ff;
      font-weight: 700;
      color: var(--blue-900);
      text-decoration: none;
      transition: all .25s ease;
    }

    .btn-primary-brand:hover {
      background: var(--gold-600);
      border-color: var(--gold-700);
      color: #111;
      transform: translateY(-1px);
    }

    .badge-soft {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: .25rem .5rem;
      border-radius: 9999px;
      border: 1px solid var(--silver-300);
      background: #fff;
      font-weight: 700;
      font-size: 12px
    }

    .panel {
      background: #fff;
      border: 1px solid var(--silver-300);
      border-radius: var(--radius);
      box-shadow: var(--shadow-1);
      padding: var(--pad-panel)
    }

    .panel--wide {
      grid-column: 1/-1
    }

    .panel-head {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 8px
    }

    .panel-head i {
      font-size: 18px;
      color: var(--silver-600)
    }

    .panel-head h6 {
      margin: 0;
      font-size: 13px;
      font-weight: 800;
      color: var(--blue-900)
    }

    .empty {
      color: #6b7280;
      border: 1px dashed var(--silver-300);
      border-radius: 10px;
      padding: 10px;
      text-align: center;
      background: linear-gradient(180deg, #fff, #fbfcff)
    }

    .kpi-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px
    }

    @media (max-width:992px) {
      .kpi-grid {
        grid-template-columns: 1fr 1fr
      }
    }

    @media (max-width:600px) {
      .kpi-grid {
        grid-template-columns: 1fr
      }
    }

    .kpi .label {
      font-size: var(--fs-kpi-label);
      color: #6b7280
    }

    .kpi .value {
      font-size: var(--fs-kpi);
      font-weight: 800;
      line-height: 1.1
    }

    .kpi .icon {
      width: 36px;
      height: 36px;
      border-radius: 9px;
      display: flex;
      align-items: center;
      justify-content: center
    }

    .layout {
      margin-top: 12px;
      display: grid;
      grid-template-columns: 6fr 5fr;
      gap: 12px
    }

    @media (max-width:992px) {
      .layout {
        grid-template-columns: 1fr
      }
    }

    .c-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
      gap: 10px
    }

    .c-card {
      position: relative;
      height: 130px;
      border: 1px solid var(--silver-300);
      border-radius: 10px;
      overflow: hidden;
      background: #f8fafc;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 6px
    }

    .c-card.hasimg {
      background-size: cover;
      background-position: center
    }

    .c-overlay {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: flex-end;
      justify-content: center;
      padding: 8px;
      background: linear-gradient(to top, rgba(15, 23, 42, .55), rgba(15, 23, 42, .05) 55%, rgba(15, 23, 42, 0))
    }

    .c-tag {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      padding: .35rem .6rem;
      border-radius: 9999px;
      background: linear-gradient(180deg, var(--blue-700), var(--blue-500));
      color: #fff;
      font-weight: 800;
      font-size: 12px;
      transition: all .2s ease;
      text-decoration: none
    }

    .c-tag:hover {
      background: linear-gradient(180deg, var(--gold-700), var(--gold-600));
      color: #111
    }

    .file-pill {
      position: absolute;
      top: 8px;
      left: 8px;
      background: #fff;
      border: 1px solid var(--silver-300);
      border-radius: 9999px;
      padding: .15rem .45rem;
      font-size: 11px;
      font-weight: 700;
      color: #334155
    }

    @media (max-width: 768px) {
      :root {
        --pad-card: 14px;
        --pad-panel: 14px;
        --fs-title: 18px;
        --fs-kpi: 17px;
      }

      .container-fluid.page-body-wrapper .main-panel .content-wrapper {
        padding-left: 10px !important;
        padding-right: 10px !important;
        padding-top: .5rem;
      }

      .app {
        padding: 0 8px;
      }

      .profile-card {
        border-radius: 16px;
        overflow: hidden;
      }

      .profile-cover {
        height: 96px;
        background-size: cover !important;
      }

      .profile-main {
        grid-template-columns: 64px 1fr;
        grid-auto-rows: minmax(0, auto);
        gap: 10px 12px;
        padding: 12px;
      }

      .profile-main .badges {
        grid-column: 1 / -1;
      }

      .avatar {
        width: 64px;
        height: 64px;
        margin-top: -44px;
        border-width: 3px;
      }

      .profile-name {
        font-size: var(--fs-title);
      }

      .profile-sub {
        font-size: 12px;
      }

      .meta {
        font-size: 12px;
      }

      .btn-primary-brand {
        width: 100%;
        justify-content: center;
      }

      .panel {
        border-radius: 16px;
        padding: var(--pad-panel);
      }

      .panel+.panel {
        margin-top: 10px;
      }

      .kpi-grid {
        gap: 10px;
      }

      .kpi .icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
      }

      .c-card {
        height: 120px;
      }

      .c-overlay {
        padding: 10px;
      }

      .c-tag {
        font-size: 11.5px;
        padding: .35rem .7rem;
      }

      .table-responsive {
        overflow: visible;
      }

      .panel .table thead {
        display: none;
      }

      .panel .table {
        border-collapse: separate;
        border-spacing: 0 12px;
      }

      .panel .table tbody,
      .panel .table tr,
      .panel .table td {
        display: block;
        width: 100%;
      }

      .panel .table tbody tr {
        background: #fff;
        border: 1px solid var(--silver-300);
        border-radius: 14px;
        box-shadow: var(--shadow-1);
        padding: 10px 12px;
      }

      .panel .table tbody tr td {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 8px;
        align-items: baseline;
        padding: 8px 0;
        border-bottom: 1px dashed #e5e7eb;
      }

      .panel .table tbody tr td:last-child {
        border-bottom: 0;
        padding-bottom: 2px;
      }

      .panel .table td::before {
        content: attr(data-th);
        text-transform: uppercase;
        font: 700 10px/1 Poppins, system-ui, -apple-system, "Segoe UI", Roboto;
        color: #64748b;
        letter-spacing: .35px;
        align-self: start;
      }

      .fw-medium {
        font-weight: 600;
      }

      .text-muted {
        color: #6b7280 !important;
      }
    }

    @supports(padding:max(0px)) {
      @media (max-width:768px) {
        body {
          padding-bottom: max(12px, env(safe-area-inset-bottom));
        }
      }
    }

    @media (max-width: 768px) {
      .panel .table tbody tr td:not([data-th]) {
        display: block;
        grid-template-columns: 1fr !important;
        border-bottom: 0;
        padding-top: 10px;
        padding-bottom: 6px;
      }

      .panel .table tbody tr td:not([data-th])::before {
        content: none;
      }

      .panel .table tbody tr td:not([data-th]) .btn-primary-brand {
        width: 100%;
        justify-content: center;
        box-sizing: border-box;
      }

      .panel .table tbody tr td[data-th] {
        grid-template-columns: 92px 1fr;
      }

      .panel .table tbody tr {
        padding: 12px;
      }
    }

    @media (max-width:768px) {
      .panel .btn-primary-brand {
        width: 100%;
        justify-content: center;
      }
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

            $avatarUrl = function_exists('avatar_url')
              ? avatar_url($p->avatar ?? '')
              : (function ($raw) {
                $raw = trim((string)$raw);
                if ($raw !== '' && preg_match('#^https?://#i', $raw)) return $raw;
                if ($raw !== '') return base_url(str_replace('\\', '/', $raw));
                return base_url('uploads/avatars/avatar.png');
              })($p->avatar ?? '');

            $phoneNo    = $p->phoneNo ?? '';
            $loc        = trim(($p->brgy ?? '') . (($p->brgy ?? '') && ($p->city ?? '') ? ', ' : '') . ($p->city ?? '') . ((($p->brgy ?? '') || ($p->city ?? '')) && ($p->province ?? '') ? ', ' : '') . ($p->province ?? ''));
            $address    = $p->address ?? '';

            $company    = trim((string)($p->companyName ?? ''));
            $has_company_position_field = client_has_company_position_field();
            $company_position = ($has_company_position_field && isset($p->company_position))
              ? trim((string)$p->company_position)
              : '';
            $employer   = trim((string)($p->employer ?? ''));
            $biz_name   = trim((string)($p->business_name ?? ''));
            $biz_loc    = trim((string)($p->business_location ?? ''));
            $org_label  = client_org_label($p);
            $is_individual_employer = client_is_individual_employer($p);
            $has_business_details = ($company !== '' || ($has_company_position_field && $company_position !== '') || $employer !== '' || $biz_name !== '' || $biz_loc !== '');

            $id_image   = $p->id_image ?? '';
            $permit     = $p->business_permit ?? '';

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

            $idAbs           = !empty($id_image) ? base_url($id_image) : null;
            $permitAbs       = !empty($permit)   ? base_url($permit)   : null;
            $idViewerUrl     = $idAbs     ? (viewer_url_from_abs($idAbs)     ?: $idAbs)     : null;
            $permitViewerUrl = $permitAbs ? (viewer_url_from_abs($permitAbs) ?: $permitAbs) : null;

            $jobs_posted     = (int)($stats['jobs_posted'] ?? 0);
            $jobs_active     = (int)($stats['jobs_active'] ?? 0);
            $hires_total     = (int)($stats['hires_total'] ?? 0);
            $spend_total     = (float)($stats['spend_total'] ?? 0);

            $certs = [];
            if (!empty($p->certificates)) {
              $tmp = is_string($p->certificates) ? json_decode($p->certificates, true) : (array)$p->certificates;
              if (is_array($tmp)) $certs = array_values(array_filter($tmp));
            } elseif (!empty($p->cert_files)) {
              $tmp = is_string($p->cert_files) ? json_decode($p->cert_files, true) : (array)$p->cert_files;
              if (is_array($tmp)) $certs = array_values(array_filter($tmp));
            }

            $isVerified  = ($first_name && $last_name && !empty($id_image));

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
                  <div class="icon" style="background:rgba(37,99,235,.10)"><i class="mdi mdi-briefcase-outline" style="font-size:18px;color:#2563eb"></i></div>
                  <div>
                    <div class="label">Jobs Posted</div>
                    <div class="value"><?= $jobs_posted ?></div>
                  </div>
                </div>
                <div class="text-muted" style="font-size:12px;margin-top:4px">All-time</div>
              </div>
              <div class="panel kpi">
                <div style="display:flex;align-items:center;gap:10px">
                  <div class="icon" style="background:rgba(37,99,235,.12)"><i class="mdi mdi-briefcase-check" style="font-size:18px;color:#2563eb"></i></div>
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
                    <div class="value">â‚±<?= number_format($spend_total, 2) ?></div>
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
  <script>
    (function() {
      // Only transform the "Recent Jobs" table inside the last panel
      var tbl = document.querySelector('.panel.panel--wide table');
      if (!tbl) return;

      function applyLabels(table) {
        var heads = Array.from(table.querySelectorAll('thead th')).map(function(th) {
          return th.textContent.trim();
        });
        table.querySelectorAll('tbody tr').forEach(function(tr) {
          Array.from(tr.children).forEach(function(td, i) {
            if (!td.hasAttribute('data-th') && heads[i]) td.setAttribute('data-th', heads[i]);
          });
        });
      }

      applyLabels(tbl);

      // Re-apply if rows change (e.g., pagination/AJAX later)
      var obs = new MutationObserver(function() {
        applyLabels(tbl);
      });
      if (tbl.tBodies && tbl.tBodies[0]) {
        obs.observe(tbl.tBodies[0], {
          childList: true,
          subtree: true
        });
      }
    })();
  </script>

</body>

</html>