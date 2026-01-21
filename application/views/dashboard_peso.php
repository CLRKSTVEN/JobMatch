<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <?php
  $page_title = $page_title ?? 'PESO Dashboard';
  $forcePublic = !empty($force_public_visibility);
  ?>
  <title><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?v=5.1.1') ?>">
  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    html {
      scrollbar-gutter: stable;
    }

    :root {
      --ink: #0f172a;
      --muted: #6b7280;
      --line: #e5e7eb;
      --ring: #dbeafe;
      --primary: #2563eb;
      --primary-600: #2563eb;
      --primary-700: #2563eb;
      --card: #fff;
      --card-br: #e5e7eb;
      --hover: rgba(2, 6, 23, .03);
      --danger: #dc2626;
    }

    body {
      font-family: "Poppins", system-ui, -apple-system, "Segoe UI", Roboto, Arial;
      background: #f8fafc;
      color: #0f172a;
    }

    .admin-header {
      position: sticky;
      top: 0;
      z-index: 40;
      background: #fff;
      border-bottom: 1px solid var(--line);
    }

    .card {
      background: var(--card);
      border: 1px solid var(--card-br);
      border-radius: 16px;
      box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: .5rem;
      border-radius: 10px;
      padding: .55rem .95rem;
      font-weight: 700;
      border: 1px solid transparent;
      transition: all .15s ease;
    }

    .btn-primary {
      background: var(--primary-600);
      border-color: var(--primary-600);
      color: #fff;
    }

    .btn-primary:hover {
      background: var(--primary-700);
      border-color: var(--primary-700);
    }

    .btn-ghost {
      background: #fff;
      border: 1px solid var(--line);
      color: #111827;
    }

    .btn-ghost:hover {
      background: var(--hover);
    }

    .btn-icon {
      padding: .45rem .6rem;
      border-radius: 10px;
      border: 1px solid var(--line);
      background: #fff;
    }

    .badge {
      border-radius: 999px;
      padding: .18rem .6rem;
      font-size: .72rem;
      font-weight: 700;
      display: inline-flex;
      align-items: center;
      gap: .35rem;
    }

    .badge-open {
      background: rgba(5, 150, 105, .12);
      color: #065f46;
    }

    .badge-closed {
      background: rgba(55, 65, 81, .12);
      color: #374151;
    }

    .badge-public {
      background: rgba(37, 99, 235, .12);
      color: #2563eb;
    }

    .badge-followers {
      background: rgba(217, 119, 6, .12);
      color: #92400e;
    }

    .table thead th {
      font-weight: 700;
      color: #374151;
      border-bottom: 1px solid var(--line) !important;
    }

    .table tbody tr:hover {
      background: var(--hover);
    }

    .muted {
      color: #64748b;
    }

    .form-control {
      border-radius: 10px;
    }

    .tw-modal-backdrop {
      position: fixed;
      inset: 0;
      background: rgba(2, 6, 23, .55);
      display: none;
      align-items: center;
      justify-content: center;
      z-index: 2000;
      padding: 1rem;
    }

    .tw-modal-backdrop.show {
      display: flex;
    }

    .tw-modal-card {
      width: 100%;
      max-width: 560px;
      background: #fff;
      border: 1px solid var(--card-br);
      border-radius: 16px;
      box-shadow: 0 10px 30px rgba(2, 6, 23, .18);
      overflow: hidden;
      transform: translateY(8px) scale(.98);
      opacity: 0;
      transition: .18s ease;
    }

    .tw-modal-backdrop.show .tw-modal-card {
      transform: translateY(0) scale(1);
      opacity: 1;
    }

    .tw-modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .75rem;
      padding: .9rem 1.1rem;
      border-bottom: 1px solid var(--line);
    }

    .tw-modal-title {
      font-weight: 700;
      font-size: 1rem;
      display: flex;
      align-items: center;
      gap: .5rem;
    }

    .tw-modal-body {
      padding: 1rem 1.1rem;
    }

    .tw-modal-footer {
      display: flex;
      justify-content: flex-end;
      gap: .5rem;
      padding: .8rem 1.1rem;
      border-top: 1px solid var(--line);
    }

    .tw-close {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 36px;
      height: 36px;
      border-radius: 10px;
      border: 1px solid var(--line);
      background: #fff;
      cursor: pointer;
    }

    .tw-close:hover {
      background: var(--hover);
    }

    .tw-grid-2 {
      display: grid;
      grid-template-columns: 1fr;
      gap: .8rem;
    }

    @media (min-width: 768px) {
      .tw-grid-2 {
        grid-template-columns: 1fr 1fr;
      }
    }

    .field-group {
      display: flex;
      flex-direction: column;
      gap: .35rem;
    }

    .field-label {
      font-weight: 600;
      font-size: .9rem;
    }

    .actions-cell {
      min-width: 160px;
      text-align: right;
    }

    .iconbar {
      display: flex;
      gap: .45rem;
      flex-wrap: wrap;
      align-items: center;
    }

    .iconbtn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: .35rem;
      min-height: 40px;
      padding: .48rem .7rem;
      border-radius: 12px;
      border: 1px solid var(--line);
      background: #fff;
      color: #111827;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      position: relative;
      overflow: hidden;
      transition: background .18s ease, border-color .18s ease, box-shadow .18s ease, transform .18s ease;
    }

    .iconbtn .mdi {
      font-size: 18px;
      line-height: 1;
      flex-shrink: 0;
    }

    .iconbtn__label {
      margin-left: 0;
      max-width: 0;
      opacity: 0;
      overflow: hidden;
      white-space: nowrap;
      pointer-events: none;
      transition: max-width .2s ease, opacity .2s ease, margin-left .2s ease;
    }

    .iconbtn:hover,
    .iconbtn:focus-visible {
      background: var(--hover);
      box-shadow: 0 4px 14px rgba(15, 23, 42, .08);
      transform: translateY(-2px);
    }

    .iconbtn:hover .iconbtn__label,
    .iconbtn:focus-visible .iconbtn__label {
      margin-left: .45rem;
      max-width: 12rem;
      opacity: 1;
    }

    .iconbtn:focus-visible {
      outline: 3px solid var(--ring);
      outline-offset: 2px;
    }

    .iconbtn--primary {
      background: #eef2ff;
      border-color: #c7d2fe;
      color: #1e1b4b;
    }

    .iconbtn--primary:hover,
    .iconbtn--primary:focus-visible {
      background: #e0e7ff;
      border-color: #c7d2fe;
    }

    .iconbtn--danger {
      background: #fee2e2;
      border-color: #fecaca;
      color: #7f1d1d;
    }

    .iconbtn--danger:hover,
    .iconbtn--danger:focus-visible {
      background: #fecaca;
      border-color: #fca5a5;
    }

    .sr-only {
      position: absolute;
      width: 1px;
      height: 1px;
      padding: 0;
      margin: -1px;
      overflow: hidden;
      clip: rect(0, 0, 0, 0);
      white-space: nowrap;
      border: 0;
    }

    .btn-sm {
      padding: .45rem .85rem;
      font-size: .85rem;
      line-height: 1.2;
    }

    .btn-ghost.-danger {
      border-color: #fecaca;
      color: #b91c1c;
    }

    .btn-ghost.-danger:hover {
      background: #fee2e2;
      color: #991b1b;
    }

    .jobs-shell {
      display: grid;
      gap: 1.8rem;
      margin-top: 1.5rem;
    }

    @media (min-width: 640px) {
      .jobs-shell {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      }
    }

    .job-card {
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
      padding: 1.6rem;
      border: 1px solid rgba(148, 163, 184, .35);
      border-radius: 22px;
      background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
      box-shadow: 0 12px 28px rgba(15, 23, 42, .08);
      transition: box-shadow .2s ease, transform .2s ease;
    }

    @media (max-width: 640px) {
      .job-card {
        padding: 1.3rem;
      }
    }

    .job-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 18px 36px rgba(15, 23, 42, .12);
    }

    @media (min-width: 1024px) {
      .job-card {
        flex-direction: row;
        align-items: stretch;
      }
    }

    .job-card__body {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 1.1rem;
    }

    .job-card__header {
      display: flex;
      flex-direction: column;
      gap: .6rem;
    }

    .job-card__title {
      font-size: 1.2rem;
      font-weight: 700;
      color: #0f172a;
      display: flex;
      align-items: center;
      gap: .6rem;
    }

    .job-card__badges {
      display: flex;
      flex-wrap: wrap;
      gap: .45rem;
    }

    .job-card__meta {
      display: flex;
      flex-wrap: wrap;
      gap: .65rem;
      font-size: .9rem;
      color: var(--muted);
    }

    .job-card__meta-item {
      display: inline-flex;
      align-items: center;
      gap: .35rem;
      padding: .22rem .6rem;
      border-radius: 999px;
      background: rgba(148, 163, 184, .18);
      color: #475569;
      font-weight: 600;
    }

    .job-card__meta-item .mdi {
      font-size: 1rem;
    }

    .job-card__desc {
      font-size: .95rem;
      line-height: 1.65;
      color: #334155;
      max-width: 62ch;
    }

    .job-card__links {
      display: flex;
      flex-wrap: wrap;
      gap: .75rem;
      font-size: .9rem;
    }

    .job-card__links .text-link {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      color: var(--primary-600);
      font-weight: 600;
    }

    .job-card__media {
      flex: 0 0 240px;
      width: 100%;
      max-width: 240px;
      border-radius: 18px;
      overflow: hidden;
      border: 1px solid rgba(148, 163, 184, .35);
      background: #f6f8fc;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    .job-card__media img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 14px;
      box-shadow: 0 8px 20px rgba(15, 23, 42, .1);
    }

    .job-card__media--pdf {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 1.25rem;
      gap: .6rem;
      text-align: center;
    }

    .job-card__media--pdf .mdi {
      font-size: 2.4rem;
      color: #dc2626;
    }

    @media (max-width: 1023px) {
      .job-card__media {
        max-width: 100%;
        flex-basis: auto;
      }
    }

    .job-card__footer {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    @media (min-width: 1024px) {
      .job-card__footer {
        min-width: 210px;
        align-items: flex-end;
        justify-content: space-between;
      }
    }

    .job-card__actions {
      display: flex;
      flex-wrap: wrap;
      gap: .55rem;
    }

    .job-card__stamp {
      font-size: .82rem;
      color: var(--muted);
    }

    .chip {
      display: inline-flex;
      align-items: center;
      gap: .35rem;
      padding: .3rem .65rem;
      border-radius: 999px;
      font-size: .78rem;
      font-weight: 600;
      background: #e0e7ff;
      color: #1e1b4b;
    }

    .chip .mdi {
      font-size: 1rem;
    }

    .card-panel {
      padding: 2rem 2.25rem;
      border-radius: 24px;
      border: 1px solid rgba(148, 163, 184, .3);
      box-shadow: 0 8px 24px rgba(15, 23, 42, .07);
      background: #fff;
    }

    @media (max-width: 640px) {
      .card-panel {
        padding: 1.4rem 1.5rem;
      }
    }

    .dashboard-header {
      display: flex;
      flex-direction: column;
      gap: 1.4rem;
    }

    @media (min-width: 768px) {
      .dashboard-header {
        flex-direction: row;
        align-items: flex-end;
        justify-content: space-between;
      }
    }

    .dashboard-header__text {
      display: flex;
      flex-direction: column;
      gap: .4rem;
    }

    .dashboard-header__title {
      font-size: 1.25rem;
      font-weight: 700;
      color: #0f172a;
    }

    .dashboard-header__sub {
      font-size: .85rem;
      color: var(--muted);
    }

    .filter-form {
      display: flex;
      flex-wrap: wrap;
      gap: .75rem;
      align-items: center;
      width: 100%;
    }

    .filter-form .form-control {
      flex: 1 1 220px;
      min-width: 220px;
    }

    @media (min-width: 768px) {
      .filter-form {
        margin-left: auto;
        width: auto;
      }
    }

    @media (max-width: 640px) {
      .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        margin-top: 1rem;
      }

      .stat-card {
        padding: 1.05rem 1.2rem;
        border-radius: 18px;
      }

      .stat-card__value {
        font-size: 1.45rem;
      }

      .admin-header .iconbar {
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: .65rem;
      }

      .admin-header .iconbtn {
        width: 100%;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: .7rem .6rem;
        min-height: 64px;
      }

      .admin-header .iconbtn .mdi {
        font-size: 22px;
      }

      .admin-header .iconbtn .iconbtn__label {
        margin-left: 0;
        margin-top: .4rem;
        max-width: 100%;
        opacity: 1;
      }

      .filter-form {
        flex-direction: column;
        align-items: stretch;
        gap: .7rem;
        width: 100%;
      }

      .filter-form .iconbtn {
        width: 100%;
        justify-content: flex-start;
        min-height: 48px;
      }

      .filter-form .iconbtn .iconbtn__label {
        margin-left: .55rem;
        max-width: 100%;
        opacity: 1;
      }

      .jobs-shell {
        gap: 1.4rem;
        margin-top: 1.3rem;
      }

      .job-card {
        padding: 1.35rem;
        border-radius: 20px;
      }

      .job-card__header {
        gap: .7rem;
      }

      .job-card__title {
        font-size: 1.18rem;
      }

      .job-card__meta {
        flex-direction: row;
        flex-wrap: wrap;
        gap: .5rem;
      }

      .job-card__meta-item {
        width: auto;
      }

      .job-card__desc {
        line-height: 1.7;
      }

      .job-card__footer {
        align-items: stretch;
      }

      .job-card__actions {
        flex-direction: column;
        align-items: stretch;
        gap: .5rem;
      }

      .job-card__actions .iconbtn {
        width: 100%;
        justify-content: flex-start;
        min-height: 46px;
      }

      .job-card__actions .iconbtn .iconbtn__label {
        margin-left: .55rem;
        max-width: 100%;
        opacity: 1;
      }

      .iconbtn:hover,
      .iconbtn:focus-visible {
        transform: none;
      }
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 1rem;
      margin-top: 1.5rem;
    }

    .stat-card {
      position: relative;
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1.1rem 1.25rem;
      border-radius: 18px;
      background: linear-gradient(135deg, #ffffff 0%, #f1f5f9 100%);
      border: 1px solid rgba(148, 163, 184, .35);
      box-shadow: 0 10px 30px rgba(15, 23, 42, .06);
    }

    .stat-card__icon {
      width: 46px;
      height: 46px;
      border-radius: 14px;
      display: grid;
      place-items: center;
      font-size: 22px;
      color: #fff;
    }

    .stat-card__meta {
      display: flex;
      flex-direction: column;
      gap: .2rem;
    }

    .stat-card__label {
      font-size: .82rem;
      text-transform: uppercase;
      letter-spacing: .04em;
      color: var(--muted);
      font-weight: 600;
    }

    .stat-card__value {
      font-size: 1.75rem;
      font-weight: 700;
      color: var(--ink);
    }

    .stat-card--open .stat-card__icon {
      background: linear-gradient(135deg, #22c55e, #15803d);
    }

    .stat-card--closed .stat-card__icon {
      background: linear-gradient(135deg, #94a3b8, #475569);
    }

    .stat-card--public .stat-card__icon {
      background: linear-gradient(135deg, #2563eb, #1d4ed8);
    }

    .chip.-followers {
      background: #fef3c7;
      color: #92400e;
    }

    .chip.-closed {
      background: #fee2e2;
      color: #7f1d1d;
    }

    .chip.-open {
      background: #dcfce7;
      color: #166534;
    }

    .chip.-attachment {
      background: #dbeafe;
      color: #1e3a8a;
    }

    .text-link {
      color: #2563eb;
      font-weight: 600;
      font-size: .85rem;
    }

    .text-link:hover {
      text-decoration: underline;
    }

    .empty-state {
      padding: 3rem;
      text-align: center;
      color: var(--muted);
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: .75rem;
      background: #f8fafc;
      border-radius: 18px;
      border: 1px dashed var(--line);
    }

    .attachment-field {
      display: flex;
      flex-direction: column;
      gap: .6rem;
    }

    .attachment-current {
      display: flex;
      flex-direction: column;
      gap: .55rem;
    }

    .file-pill {
      display: inline-flex;
      align-items: center;
      gap: .45rem;
      padding: .5rem .8rem;
      border-radius: 12px;
      border: 1px solid var(--line);
      background: #f8fafc;
      font-size: .86rem;
    }

    .file-pill .mdi {
      font-size: 1.1rem;
      color: #2563eb;
    }

    .attachment-actions {
      display: flex;
      flex-wrap: wrap;
      gap: .6rem;
      align-items: center;
    }

    .attachment-notice {
      display: flex;
      align-items: center;
      gap: .5rem;
      padding: .55rem .75rem;
      border-radius: 10px;
      background: #fef3c7;
      color: #92400e;
      font-size: .86rem;
      flex-wrap: wrap;
    }

    .attachment-selected {
      display: flex;
      align-items: center;
      gap: .6rem;
      padding: .55rem .8rem;
      border-radius: 12px;
      border: 1px dashed var(--primary);
      background: #eff6ff;
      color: #1d4ed8;
      font-size: .86rem;
    }

    .attachment-selected .mdi {
      font-size: 1.1rem;
    }

    .form-layout {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      margin-top: 1rem;
    }

    .form-card {
      background: #fff;
      border: 1px solid var(--card-br);
      border-radius: 16px;
      padding: 1.1rem 1.2rem;
      box-shadow: 0 10px 24px rgba(15, 23, 42, .08);
      display: flex;
      flex-direction: column;
      gap: .75rem;
    }

    .form-card.collapsed {
      background: #f9fbff;
    }

    .form-card-toggle {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: .75rem;
      font-size: 1rem;
      font-weight: 700;
      color: #0f172a;
      background: none;
      border: none;
      padding: 0;
      cursor: pointer;
    }

    .form-card-toggle span {
      display: flex;
      align-items: center;
      gap: .45rem;
    }

    .form-card-toggle:focus-visible {
      outline: 2px solid var(--primary);
      outline-offset: 2px;
    }

    .toggle-icon {
      font-size: 1.2rem;
      color: #64748b;
      transition: transform .18s ease;
    }

    .form-card:not(.collapsed) .toggle-icon {
      transform: rotate(180deg);
    }

    .form-card-body {
      display: flex;
      flex-direction: column;
      gap: .85rem;
    }

    .form-card.collapsed .form-card-body {
      display: none;
    }

    .field-stack {
      display: flex;
      flex-direction: column;
      gap: .85rem;
    }

    /* --- GRID MODAL UPGRADE --- */
    .tw-modal-card {
      width: 100%;
      max-width: 980px;
      /* wider modal */
      height: 90vh;
      /* fixed viewport height */
      display: flex;
      /* header/body/footer stack */
      flex-direction: column;
    }

    .tw-modal-header,
    .tw-modal-footer {
      position: sticky;
      z-index: 5;
      background: #fff;
    }

    .tw-modal-header {
      top: 0;
    }

    .tw-modal-footer {
      bottom: 0;
    }

    .tw-modal-body {
      flex: 1;
      overflow: auto;
      /* only body scrolls */
      padding: 1rem 1.1rem;
    }

    .form-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 1rem;
    }

    @media (min-width: 900px) {
      .form-grid {
        grid-template-columns: 1.15fr .85fr;
        /* left wider */
      }
    }

    .form-card {
      margin: 0;
      padding: 1rem 1rem;
    }

    /* Keep sections open by default (still clickable) */
    .form-card .form-card-body {
      display: block !important;
    }

    .form-card .toggle-icon {
      transform: none !important;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <?php $this->load->view('includes/nav'); ?>
    <div class="container-fluid page-body-wrapper">
      <?php $this->load->view('includes/nav-top'); ?>
      <div class="main-panel">
        <?php
        $__addr_rows = $this->db
          ->select('AddID, Province, City, Brgy')
          ->from('settings_address')
          ->order_by('Province, City, Brgy', 'ASC')
          ->get()->result_array();
        ?>
        <script>
          window.ADDRESS_DATA = <?= json_encode($__addr_rows, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
        </script>

        <div class="content-wrapper pb-0">
          <div class="px-4 md:px-8 max-w-7xl mx-auto">
            <div class="admin-header">
              <div class="py-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                <div>
                  <h1 class="text-2xl md:text-3xl font-bold"><?= htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') ?></h1>
                  <p class="text-sm muted mt-1">Manage your PESO job vacancies. Only <b>OPEN</b> + <b>PUBLIC</b> posts appear on the login page feed.</p>
                </div>
                <div class="flex items-center gap-2 iconbar">
                  <button
                    type="button"
                    class="iconbtn iconbtn--primary"
                    id="openCreateModalBtn"
                    title="New Vacancy"
                    aria-label="New Vacancy">
                    <i class="mdi mdi-briefcase-plus"></i>
                    <span class="iconbtn__label">New Vacancy</span>
                  </button>
                  <a
                    class="iconbtn"
                    href="<?= site_url('dashboard/peso') ?>"
                    title="Refresh"
                    aria-label="Refresh">
                    <i class="mdi mdi-refresh"></i>
                    <span class="iconbtn__label">Refresh</span>
                  </a>
                </div>
              </div>
            </div>

            <?php
            $k_open = isset($k_open) ? (int)$k_open : 0;
            $k_closed = isset($k_closed) ? (int)$k_closed : 0;
            $k_public = isset($k_public) ? (int)$k_public : 0;
            ?>

            <section class="stats-grid">
              <article class="stat-card stat-card--open">
                <div class="stat-card__icon">
                  <i class="mdi mdi-briefcase-check"></i>
                </div>
                <div class="stat-card__meta">
                  <span class="stat-card__label">Open Positions</span>
                  <span class="stat-card__value"><?= number_format($k_open) ?></span>
                </div>
              </article>
              <article class="stat-card stat-card--closed">
                <div class="stat-card__icon">
                  <i class="mdi mdi-briefcase-remove"></i>
                </div>
                <div class="stat-card__meta">
                  <span class="stat-card__label">Closed Positions</span>
                  <span class="stat-card__value"><?= number_format($k_closed) ?></span>
                </div>
              </article>
              <article class="stat-card stat-card--public">
                <div class="stat-card__icon">
                  <i class="mdi mdi-bullhorn"></i>
                </div>
                <div class="stat-card__meta">
                  <span class="stat-card__label">Public Posts</span>
                  <span class="stat-card__value"><?= number_format($k_public) ?></span>
                </div>
              </article>
            </section>

            <div class="mt-4">
              <?php if ($this->session->flashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  <?= $this->session->flashdata('success'); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
              <?php endif; ?>
              <?php if ($this->session->flashdata('danger')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  <?= $this->session->flashdata('danger'); ?>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
              <?php endif; ?>
            </div>

            <section class="mt-4">
              <div class="card card-panel">
                <div class="dashboard-header">
                  <div class="dashboard-header__text">
                    <h3 class="dashboard-header__title">My Job Vacancies</h3>
                    <p class="dashboard-header__sub">Latest first</p>
                  </div>
                  <form method="get" action="" class="filter-form">
                    <input
                      type="text"
                      name="q"
                      class="form-control"
                      placeholder="Search title or location"
                      value="<?= html_escape($this->input->get('q')) ?>"
                      aria-label="Search vacancies">
                    <button class="iconbtn" type="submit" title="Search" aria-label="Search">
                      <i class="mdi mdi-magnify"></i>
                      <span class="iconbtn__label">Search</span>
                    </button>
                  </form>
                </div>

                <div class="jobs-shell">
                  <?php if (!empty($list)): foreach ($list as $r): ?>
                      <?php
                      $id        = (int)$r['id'];
                      $title     = html_escape($r['title']);
                      $descRaw   = isset($r['description']) ? (string)$r['description'] : '';
                      $desc      = html_escape($descRaw);
                      $descDisplay = $desc !== '' ? nl2br($desc) : '';
                      $site      = isset($r['website_url']) ? trim((string)$r['website_url']) : '';
                      $siteSafe  = html_escape($site);
                      $loc       = !empty($r['location_text']) ? html_escape($r['location_text']) : '';
                      $min       = ($r['price_min'] !== null) ? (float)$r['price_min'] : null;
                      $max       = ($r['price_max'] !== null) ? (float)$r['price_max'] : null;
                      $minF      = $min !== null ? number_format($min, 2) : '';
                      $maxF      = $max !== null ? number_format($max, 2) : '';
                      $status    = strtolower((string)$r['status']) === 'open' ? 'open' : 'closed';
                      $post_type = isset($r['post_type']) ? strtolower((string)$r['post_type']) : 'hire';
                      $visRaw    = isset($r['visibility']) ? strtolower((string)$r['visibility']) : 'public';
                      $vis       = in_array($visRaw, ['public', 'followers'], true) ? $visRaw : 'public';
                      $postedRaw = isset($r['created_at']) ? (string)$r['created_at'] : '';
                      $postedTs  = $postedRaw !== '' ? @strtotime($postedRaw) : false;
                      $posted    = $postedTs ? html_escape(date('M j, Y', $postedTs)) : html_escape($postedRaw);
                      $isOpen    = ($status === 'open');
                      $toggleIcon = $isOpen ? 'mdi-toggle-switch' : 'mdi-toggle-switch-off-outline';
                      $toggleTip  = $isOpen ? 'Close' : 'Open';
                      $mediaRaw  = isset($r['media_json']) ? json_decode((string)$r['media_json'], true) : null;
                      $media     = null;
                      if (is_array($mediaRaw) && !empty($mediaRaw['rel_path'])) {
                        $mediaRel = ltrim(str_replace('\\', '/', (string)$mediaRaw['rel_path']), '/');
                        if (strpos($mediaRel, 'uploads/') === 0) {
                          $ext = strtolower(pathinfo($mediaRel, PATHINFO_EXTENSION));
                          $imageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                          $type = isset($mediaRaw['type']) && in_array($mediaRaw['type'], ['image', 'pdf'], true)
                            ? $mediaRaw['type']
                            : (in_array($ext, $imageExts, true) ? 'image' : 'pdf');
                          $viewer = site_url('media/preview?f=' . rawurlencode($mediaRel));
                          $preview = $type === 'image'
                            ? site_url('media/wm_image?f=' . rawurlencode($mediaRel))
                            : site_url('media/wm_pdf?f=' . rawurlencode($mediaRel));
                          $mediaNameRaw = isset($mediaRaw['original_name']) ? (string)$mediaRaw['original_name'] : basename($mediaRel);
                          $media = [
                            'rel'      => $mediaRel,
                            'type'     => $type,
                            'preview'  => $preview,
                            'viewer'   => $viewer,
                            'name_raw' => $mediaNameRaw,
                            'name'     => html_escape($mediaNameRaw),
                          ];
                        }
                      }
                      ?>
                      <article class="job-card">
                        <div class="job-card__body">
                          <div class="job-card__header">
                            <div class="job-card__title">
                              <span><?= $title ?></span>
                              <?php if ($post_type === 'service'): ?>
                                <span class="chip"><i class="mdi mdi-account-settings"></i> Service</span>
                              <?php endif; ?>
                            </div>
                            <div class="job-card__badges">
                              <span class="chip <?= $isOpen ? '-open' : '-closed' ?>"><i class="mdi mdi-checkbox-blank-circle"></i> <?= strtoupper($status) ?></span>
                              <span class="chip <?= $vis === 'public' ? '' : '-followers' ?>"><i class="mdi <?= $vis === 'public' ? 'mdi-earth' : 'mdi-account-multiple' ?>"></i> <?= ucfirst($vis) ?></span>
                              <?php if ($media): ?>
                                <span class="chip -attachment"><i class="mdi <?= $media['type'] === 'pdf' ? 'mdi-file-pdf-box' : 'mdi-image-outline' ?>"></i> Attachment</span>
                              <?php endif; ?>
                            </div>
                          </div>
                          <div class="job-card__meta">
                            <?php if ($loc): ?><span class="job-card__meta-item"><i class="mdi mdi-map-marker"></i><?= $loc ?></span><?php endif; ?>
                            <?php if ($minF !== '' || $maxF !== ''): ?><span class="job-card__meta-item"><i class="mdi mdi-cash"></i>&#8369; <?= $minF ?><?= ($minF !== '' && $maxF !== '') ? ' - &#8369; ' : '' ?><?= $maxF ?></span><?php endif; ?>
                            <span class="job-card__meta-item"><i class="mdi mdi-calendar-clock"></i><?= $posted ?></span>
                          </div>
                          <?php if ($descDisplay !== ''): ?>
                            <div class="job-card__desc"><?= $descDisplay ?></div>
                          <?php endif; ?>
                          <div class="job-card__links">
                            <?php if ($site): ?>
                              <a href="<?= $siteSafe ?>" target="_blank" rel="noopener" class="text-link"><i class="mdi mdi-open-in-new"></i> View external post</a>
                            <?php endif; ?>
                            <?php if ($media): ?>
                              <a href="<?= html_escape($media['viewer']) ?>" target="_blank" rel="noopener" class="text-link"><i class="mdi mdi-eye"></i> View attachment</a>
                            <?php endif; ?>
                          </div>
                        </div>
                        <?php if ($media): ?>
                          <div class="job-card__media <?= $media['type'] === 'pdf' ? 'job-card__media--pdf' : '' ?>">
                            <?php if ($media['type'] === 'image'): ?>
                              <img src="<?= html_escape($media['preview']) ?>" alt="Attachment preview for <?= $title ?>">
                            <?php else: ?>
                              <i class="mdi mdi-file-pdf-box"></i>
                              <div>
                                <div class="font-semibold">PDF attachment</div>
                                <a href="<?= html_escape($media['viewer']) ?>" target="_blank" rel="noopener" class="text-link">Open document</a>
                              </div>
                            <?php endif; ?>
                          </div>
                        <?php endif; ?>
                        <div class="job-card__footer">
                          <div class="job-card__actions iconbar">
                            <button
                              type="button"
                              class="iconbtn iconbtn--primary editVacancyBtn"
                              data-id="<?= $id ?>"
                              data-title="<?= $title ?>"
                              data-description="<?= $desc ?>"
                              data-website_url="<?= $siteSafe ?>"
                              data-post_type="<?= $post_type ?>"
                              data-visibility="<?= $vis ?>"
                              data-price_min="<?= $min !== null ? $min : '' ?>"
                              data-price_max="<?= $max !== null ? $max : '' ?>"
                              data-location_text="<?= $loc ?>"
                              title="Edit vacancy"
                              aria-label="Edit vacancy"
                              <?php if ($media): ?>
                              data-media-type="<?= $media['type'] ?>"
                              data-media-name="<?= html_escape($media['name_raw']) ?>"
                              data-media-viewer="<?= html_escape($media['viewer']) ?>"
                              <?php endif; ?>>
                              <i class="mdi mdi-pencil"></i>
                              <span class="iconbtn__label">Edit</span>
                            </button>
                            <a
                              href="<?= site_url('peso/toggle/' . $id) ?>"
                              class="iconbtn"
                              title="<?= htmlspecialchars($toggleTip, ENT_QUOTES, 'UTF-8') ?>"
                              aria-label="<?= htmlspecialchars($toggleTip, ENT_QUOTES, 'UTF-8') ?>">
                              <i class="mdi <?= $toggleIcon ?>"></i>
                              <span class="iconbtn__label"><?= htmlspecialchars($toggleTip, ENT_QUOTES, 'UTF-8') ?></span>
                            </a>
                            <a
                              href="<?= site_url('peso/delete/' . $id) ?>"
                              onclick="return confirm('Delete this posting?');"
                              class="iconbtn iconbtn--danger"
                              title="Delete"
                              aria-label="Delete">
                              <i class="mdi mdi-delete-outline"></i>
                              <span class="iconbtn__label">Delete</span>
                            </a>
                          </div>
                          <div class="job-card__stamp">Posted <?= $posted ?></div>
                        </div>
                      </article>
                    <?php endforeach;
                  else: ?>
                    <div class="empty-state">
                      <i class="mdi mdi-briefcase-search"></i>
                      <div class="mt-2 font-semibold">No postings yet.</div>
                      <p class="mt-1 text-sm muted">Publish a vacancy to have it appear here and on the landing page feed.</p>
                    </div>
                  <?php endif; ?>
                </div>

                <p class="mt-4 text-sm muted"><i class="mdi mdi-information-outline"></i> Only <strong>OPEN</strong> and <strong>PUBLIC</strong> vacancies are shown on the login screen feed.</p>
              </div>
            </section>

            <div class="my-8" style="height:1px;background:var(--line)"></div>
          </div>
        </div>

        <?php $this->load->view('includes/footer'); ?>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div id="vacancyModal" class="tw-modal-backdrop" aria-hidden="true">
    <div class="tw-modal-card" role="dialog" aria-modal="true" aria-labelledby="vacancyModalTitle">
      <div class="tw-modal-header">
        <div class="tw-modal-title" id="vacancyModalTitle">
          <i class="mdi mdi-briefcase-plus"></i>
          <span id="modalModeTitle">Create Job Vacancy</span>
        </div>
        <button type="button" class="tw-close" id="modalCloseBtn" title="Close">
          <i class="mdi mdi-close"></i>
        </button>
      </div>

      <form id="vacancyForm" method="post" action="<?= site_url('peso/store') ?>" enctype="multipart/form-data">
        <!-- NEW: Grid modal body with sticky footer/header -->
        <div class="tw-modal-body">
          <input type="hidden" name="id" id="v_id" />

          <div class="form-grid">
            <!-- LEFT COLUMN -->
            <div class="space-y-4">
              <!-- Job Basics -->
              <section class="form-card">
                <button type="button" class="form-card-toggle">
                  <span><i class="mdi mdi-clipboard-text-outline"></i> Job Basics</span>
                  <i class="mdi mdi-chevron-down toggle-icon"></i>
                </button>
                <div class="form-card-body">
                  <div class="tw-grid-2">
                    <div class="field-group" style="grid-column:1 / -1;">
                      <label class="field-label" for="v_title">Job Title *</label>
                      <input type="text" name="title" id="v_title" class="form-control" required placeholder="e.g., Junior Web Developer">
                    </div>
                    <div class="field-group">
                      <label class="field-label" for="v_website_url">Website Link</label>
                      <input type="url" name="website_url" id="v_website_url" class="form-control" placeholder="https://example.com/job/123">
                    </div>
                    <div class="field-group">
                      <label class="field-label" for="v_post_type">Post Type</label>
                      <select name="post_type" id="v_post_type" class="form-control">
                        <option value="hire">Hire</option>
                        <option value="service">Service</option>
                      </select>
                    </div>
                  </div>

                  <div class="field-group" style="margin-top:.75rem;">
                    <label class="field-label" for="v_description">Description</label>
                    <textarea name="description" id="v_description" rows="4" class="form-control" placeholder="Short role overview, qualifications, responsibilities"></textarea>
                  </div>
                </div>
              </section>

              <!-- Posting -->
              <section class="form-card">
                <button type="button" class="form-card-toggle">
                  <span><i class="mdi mdi-bullhorn-outline"></i> Posting</span>
                  <i class="mdi mdi-chevron-down toggle-icon"></i>
                </button>
                <div class="form-card-body">
                  <div class="field-group">
                    <label class="field-label" for="v_visibility">Visibility</label>
                    <select name="visibility" id="v_visibility" class="form-control" <?= $forcePublic ? 'data-force="public"' : '' ?>>
                      <option value="public">Public</option>
                      <?php if (!$forcePublic): ?>
                        <option value="followers">Followers</option>
                      <?php endif; ?>
                    </select>
                    <?php if ($forcePublic): ?>
                      <small class="muted">TESDA postings are published publicly so applicants can see them on the landing page.</small>
                    <?php endif; ?>
                  </div>
                </div>
              </section>
            </div>

            <!-- RIGHT COLUMN -->
            <div class="space-y-4">
              <!-- Supporting Media -->
              <section class="form-card">
                <button type="button" class="form-card-toggle">
                  <span><i class="mdi mdi-paperclip"></i> Supporting Media</span>
                  <i class="mdi mdi-chevron-down toggle-icon"></i>
                </button>
                <div class="form-card-body">
                  <div class="field-group">
                    <input type="file" name="attachment" id="v_attachment" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif,.pdf">
                    <small class="muted">Attach an image or PDF up to 8&nbsp;MB. Shown on the public landing page.</small>
                  </div>

                  <div id="attachmentCurrent" class="attachment-current" hidden>
                    <div class="file-pill">
                      <i id="attachmentCurrentIcon" class="mdi mdi-file-outline"></i>
                      <span id="attachmentCurrentName"></span>
                    </div>
                    <div class="attachment-actions">
                      <a id="attachmentPreviewLink" href="#" target="_blank" rel="noopener" class="text-link">Preview</a>
                      <button type="button" class="btn btn-ghost btn-sm -danger" id="attachmentRemoveBtn"><i class="mdi mdi-close-circle"></i> Remove</button>
                    </div>
                  </div>

                  <div id="attachmentSelected" class="attachment-selected" hidden>
                    <i id="attachmentSelectedIcon" class="mdi mdi-file-outline"></i>
                    <span>Selected: <strong id="attachmentSelectedName"></strong></span>
                    <button type="button" class="btn btn-ghost btn-sm" id="attachmentSelectedClear"><i class="mdi mdi-close"></i> Clear</button>
                  </div>

                  <div id="attachmentRemovalNotice" class="attachment-notice" hidden>
                    <i class="mdi mdi-alert-circle-outline"></i>
                    <span>The current attachment will be removed after saving.</span>
                    <button type="button" class="btn btn-ghost btn-sm" id="attachmentUndoRemove"><i class="mdi mdi-undo"></i> Keep file</button>
                  </div>

                  <input type="hidden" name="remove_media" id="v_remove_media" value="">
                </div>
              </section>

              <!-- Compensation -->
              <section class="form-card">
                <button type="button" class="form-card-toggle">
                  <span><i class="mdi mdi-cash-multiple"></i> Compensation</span>
                  <i class="mdi mdi-chevron-down toggle-icon"></i>
                </button>
                <div class="form-card-body">
                  <div class="tw-grid-2">
                    <div class="field-group">
                      <label class="field-label" for="v_price_min">Salary Min</label>
                      <input type="number" step="0.01" name="price_min" id="v_price_min" class="form-control" placeholder="e.g., 15000">
                    </div>
                    <div class="field-group">
                      <label class="field-label" for="v_price_max">Salary Max</label>
                      <input type="number" step="0.01" name="price_max" id="v_price_max" class="form-control" placeholder="e.g., 25000">
                    </div>
                  </div>
                </div>
              </section>

              <!-- Location -->
              <section class="form-card">
                <button type="button" class="form-card-toggle">
                  <span><i class="mdi mdi-map-marker-radius"></i> Location</span>
                  <i class="mdi mdi-chevron-down toggle-icon"></i>
                </button>
                <div class="form-card-body">
                  <div class="tw-grid-2">
                    <div class="field-group">
                      <label class="field-label" for="v_province">Province</label>
                      <select id="v_province" class="form-control">
                        <option value="">Select Province</option>
                      </select>
                    </div>
                    <div class="field-group">
                      <label class="field-label" for="v_city">City/Municipality</label>
                      <select id="v_city" class="form-control" disabled>
                        <option value="">Select City/Municipality</option>
                      </select>
                    </div>
                  </div>
                  <div class="tw-grid-2" style="margin-top:.85rem;">
                    <div class="field-group">
                      <label class="field-label" for="v_brgy">Barangay</label>
                      <select id="v_brgy" class="form-control" disabled>
                        <option value="">Select Barangay</option>
                      </select>
                    </div>
                    <div class="field-group">
                      <label class="field-label" for="v_location_text">Location (auto)</label>
                      <input type="text" name="location_text" id="v_location_text" class="form-control" placeholder="Barangay, City, Province" readonly>
                      <input type="hidden" id="v_address_id">
                    </div>
                  </div>
                </div>
              </section>
            </div>
          </div>
        </div>

        <div class="tw-modal-footer">
          <button type="button" class="btn btn-ghost" id="modalCancelBtn"><i class="mdi mdi-close"></i> Cancel</button>
          <button type="submit" class="btn btn-primary"><i class="mdi mdi-content-save"></i> <span id="modalSubmitText">Post Vacancy</span></button>
        </div>
      </form>
    </div>
  </div>

  <script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js') ?>"></script>
  <script src="<?= base_url('assets/js/off-canvas.js') ?>"></script>
  <script src="<?= base_url('assets/js/hoverable-collapse.js') ?>"></script>
  <script src="<?= base_url('assets/js/misc.js') ?>"></script>

  <script>
    (function() {
      const $backdrop = document.getElementById('vacancyModal');
      const $card = $backdrop.querySelector('.tw-modal-card');
      const $close = document.getElementById('modalCloseBtn');
      const $cancel = document.getElementById('modalCancelBtn');
      const $openCreate = document.getElementById('openCreateModalBtn');

      const form = document.getElementById('vacancyForm');
      const f_id = document.getElementById('v_id');
      const f_title = document.getElementById('v_title');
      const f_desc = document.getElementById('v_description');
      const f_site = document.getElementById('v_website_url');
      const f_type = document.getElementById('v_post_type');
      const f_vis = document.getElementById('v_visibility');
      const f_min = document.getElementById('v_price_min');
      const f_max = document.getElementById('v_price_max');
      const f_loc = document.getElementById('v_location_text');
      const f_file = document.getElementById('v_attachment');
      const f_remove = document.getElementById('v_remove_media');

      const wrapCurrent = document.getElementById('attachmentCurrent');
      const iconCurrent = document.getElementById('attachmentCurrentIcon');
      const nameCurrent = document.getElementById('attachmentCurrentName');
      const linkPreview = document.getElementById('attachmentPreviewLink');
      const btnRemoveAtt = document.getElementById('attachmentRemoveBtn');

      const wrapSelected = document.getElementById('attachmentSelected');
      const iconSelected = document.getElementById('attachmentSelectedIcon');
      const nameSelected = document.getElementById('attachmentSelectedName');
      const btnSelectedClear = document.getElementById('attachmentSelectedClear');

      const removalNotice = document.getElementById('attachmentRemovalNotice');
      const btnUndoRemove = document.getElementById('attachmentUndoRemove');
      const forcePublic = <?= $forcePublic ? 'true' : 'false' ?>;

      const $modeTitle = document.getElementById('modalModeTitle');
      const $submitText = document.getElementById('modalSubmitText');

      let currentAttachment = null;

      const iconForType = (type) => type === 'pdf' ? 'mdi-file-pdf-box' : (type === 'image' ? 'mdi-image-outline' : 'mdi-file-outline');
      const iconForFile = (file) => {
        if (!file || !file.type) return 'mdi-file-outline';
        return file.type.indexOf('pdf') !== -1 ? 'mdi-file-pdf-box' : 'mdi-image-outline';
      };

      function resetAttachmentUI() {
        if (f_file) {
          f_file.value = '';
        }
        if (f_remove) {
          f_remove.value = '';
        }
        if (wrapCurrent) {
          wrapCurrent.hidden = true;
        }
        if (wrapSelected) {
          wrapSelected.hidden = true;
        }
        if (removalNotice) {
          removalNotice.hidden = true;
        }
        if (linkPreview) {
          linkPreview.href = '#';
          linkPreview.hidden = false;
        }
      }

      function showExistingAttachment(type, name, viewer) {
        if (wrapCurrent) {
          wrapCurrent.hidden = false;
        }
        if (iconCurrent) {
          iconCurrent.className = 'mdi ' + iconForType(type);
        }
        if (nameCurrent) {
          nameCurrent.textContent = name || 'Attachment';
        }
        if (linkPreview) {
          if (viewer) {
            linkPreview.href = viewer;
            linkPreview.hidden = false;
          } else {
            linkPreview.hidden = true;
          }
        }
        if (removalNotice) {
          removalNotice.hidden = true;
        }
      }

      function markAttachmentRemoval() {
        if (wrapCurrent) {
          wrapCurrent.hidden = true;
        }
        if (wrapSelected) {
          wrapSelected.hidden = true;
        }
        if (removalNotice) {
          removalNotice.hidden = false;
        }
        if (f_remove) {
          f_remove.value = '1';
        }
        if (f_file) {
          f_file.value = '';
        }
      }

      function handleFileSelected(file) {
        if (!file) {
          if (wrapSelected) {
            wrapSelected.hidden = true;
          }
          if (currentAttachment && (!f_remove || f_remove.value === '')) {
            showExistingAttachment(currentAttachment.type, currentAttachment.name, currentAttachment.viewer);
          }
          return;
        }
        if (iconSelected) {
          iconSelected.className = 'mdi ' + iconForFile(file);
        }
        if (nameSelected) {
          nameSelected.textContent = file.name || 'Attachment';
        }
        if (wrapSelected) {
          wrapSelected.hidden = false;
        }
        if (wrapCurrent) {
          wrapCurrent.hidden = true;
        }
        if (removalNotice) {
          removalNotice.hidden = true;
        }
        if (f_remove) {
          f_remove.value = '';
        }
      }

      function openModal() {
        $backdrop.classList.add('show');
        setTimeout(() => {
          f_title && f_title.focus();
        }, 80);
        document.documentElement.style.overflow = 'hidden';
        document.body.style.overflow = 'hidden';
      }

      function closeModal() {
        $backdrop.classList.remove('show');
        document.documentElement.style.overflow = '';
        document.body.style.overflow = '';
      }

      function setModeCreate() {
        $modeTitle.textContent = 'Create Job Vacancy';
        $submitText.textContent = 'Post Vacancy';
        form.action = "<?= site_url('peso/store') ?>";
        f_id.value = '';
        f_title.value = '';
        f_desc.value = '';
        f_site.value = '';
        f_type.value = 'hire';
        f_vis.value = 'public';
        f_min.value = '';
        f_max.value = '';
        f_loc.value = '';
        currentAttachment = null;
        resetAttachmentUI();
        if (window.__loc_reset) window.__loc_reset();
      }

      function setModeEdit(d) {
        $modeTitle.textContent = 'Update Job Vacancy';
        $submitText.textContent = 'Save Changes';
        form.action = "<?= site_url('peso/update') ?>/" + (d.id || '');
        f_id.value = d.id || '';
        f_title.value = d.title || '';
        f_desc.value = d.description || '';
        f_site.value = d.website_url || '';
        f_type.value = d.post_type || 'hire';
        if (forcePublic) {
          f_vis.value = 'public';
        } else {
          f_vis.value = d.visibility || 'public';
        }
        f_min.value = d.price_min || '';
        f_max.value = d.price_max || '';
        f_loc.value = d.location_text || '';
        currentAttachment = null;
        resetAttachmentUI();
        if (d.mediaViewer) {
          currentAttachment = {
            type: d.mediaType || '',
            name: d.mediaName || 'Attachment',
            viewer: d.mediaViewer || ''
          };
          showExistingAttachment(currentAttachment.type, currentAttachment.name, currentAttachment.viewer);
        }
        if (window.__loc_loadFromText) window.__loc_loadFromText(d.location_text || '');
      }

      if ($openCreate) {
        $openCreate.addEventListener('click', function(e) {
          e.preventDefault();
          setModeCreate();
          openModal();
        });
      }

      document.querySelectorAll('.editVacancyBtn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const d = btn.dataset;
          setModeEdit(d);
          openModal();
        });
      });

      btnRemoveAtt?.addEventListener('click', function(e) {
        e.preventDefault();
        markAttachmentRemoval();
      });

      btnUndoRemove?.addEventListener('click', function(e) {
        e.preventDefault();
        if (f_remove) {
          f_remove.value = '';
        }
        if (removalNotice) {
          removalNotice.hidden = true;
        }
        if (currentAttachment) {
          showExistingAttachment(currentAttachment.type, currentAttachment.name, currentAttachment.viewer);
        }
      });

      btnSelectedClear?.addEventListener('click', function(e) {
        e.preventDefault();
        if (f_file) {
          f_file.value = '';
        }
        if (wrapSelected) {
          wrapSelected.hidden = true;
        }
        if (currentAttachment && (!f_remove || f_remove.value !== '1')) {
          showExistingAttachment(currentAttachment.type, currentAttachment.name, currentAttachment.viewer);
        }
      });

      f_file?.addEventListener('change', function() {
        const file = f_file.files && f_file.files[0] ? f_file.files[0] : null;
        handleFileSelected(file);
      });

      document.querySelectorAll('.form-card').forEach(function(card) {
        const btn = card.querySelector('.form-card-toggle');
        const body = card.querySelector('.form-card-body');
        const icon = card.querySelector('.toggle-icon');
        if (!btn || !body) return;

        let open = card.dataset.open !== '0';

        const setState = () => {
          card.classList.toggle('collapsed', !open);
          body.hidden = !open;
          if (icon) {
            icon.className = 'mdi ' + (open ? 'mdi-chevron-up' : 'mdi-chevron-down') + ' toggle-icon';
          }
        };

        setState();

        btn.addEventListener('click', function() {
          open = !open;
          setState();
        });
      });

      [$close, $cancel].forEach(function(el) {
        if (el) el.addEventListener('click', closeModal);
      });
      $backdrop.addEventListener('click', function(e) {
        if (!$card.contains(e.target)) closeModal();
      });
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && $backdrop.classList.contains('show')) closeModal();
      });
      $card.addEventListener('click', function(e) {
        e.stopPropagation();
      });

      window.setModeCreate = setModeCreate;
      window.setModeEdit = setModeEdit;
    })();
  </script>

  <script>
    (function() {
      const unique = (arr) => Array.from(new Set(arr));
      const byProv = (prov) => window.ADDRESS_DATA.filter(r => r.Province === prov);
      const byProvCity = (prov, city) => window.ADDRESS_DATA.filter(r => r.Province === prov && r.City === city);

      const selProv = document.getElementById('v_province');
      const selCity = document.getElementById('v_city');
      const selBrgy = document.getElementById('v_brgy');
      const txtLoc = document.getElementById('v_location_text');
      const hidAdd = document.getElementById('v_address_id');

      function resetCity() {
        selCity.innerHTML = '<option value="">Select City/Municipality</option>';
        selCity.disabled = true;
      }

      function resetBrgy() {
        selBrgy.innerHTML = '<option value="">Select Barangay</option>';
        selBrgy.disabled = true;
      }

      function composeLocation() {
        const p = selProv?.value || '';
        const c = selCity?.value || '';
        const b = selBrgy?.value || '';
        txtLoc.value = [b, c, p].filter(Boolean).join(', ');
      }

      function fillProvinces() {
        if (!selProv || selProv.dataset.filled === '1') return;
        const provinces = unique(window.ADDRESS_DATA.map(r => r.Province)).sort((a, b) => a.localeCompare(b));
        provinces.forEach(p => {
          const opt = document.createElement('option');
          opt.value = p;
          opt.textContent = p;
          selProv.appendChild(opt);
        });
        selProv.dataset.filled = '1';
      }

      selProv && selProv.addEventListener('change', function() {
        resetCity();
        resetBrgy();
        hidAdd.value = '';
        const prov = this.value;
        if (!prov) {
          composeLocation();
          return;
        }
        const cities = unique(byProv(prov).map(r => r.City)).sort((a, b) => a.localeCompare(b));
        cities.forEach(c => {
          const opt = document.createElement('option');
          opt.value = c;
          opt.textContent = c;
          selCity.appendChild(opt);
        });
        selCity.disabled = false;
        composeLocation();
      });

      selCity && selCity.addEventListener('change', function() {
        resetBrgy();
        hidAdd.value = '';
        const prov = selProv.value;
        const city = this.value;
        if (!prov || !city) {
          composeLocation();
          return;
        }
        const brgys = byProvCity(prov, city).map(r => ({
          name: r.Brgy,
          id: r.AddID
        }));
        brgys.forEach(obj => {
          const opt = document.createElement('option');
          opt.value = obj.name;
          opt.textContent = obj.name;
          opt.dataset.addid = obj.id;
          selBrgy.appendChild(opt);
        });
        selBrgy.disabled = false;
        composeLocation();
      });

      selBrgy && selBrgy.addEventListener('change', function() {
        const opt = this.selectedOptions[0];
        hidAdd.value = opt && opt.dataset.addid ? opt.dataset.addid : '';
        composeLocation();
      });

      window.__loc_reset = function() {
        fillProvinces();
        selProv.value = '';
        resetCity();
        resetBrgy();
        txtLoc.value = '';
        hidAdd.value = '';
      };

      window.__loc_loadFromText = function(text) {
        fillProvinces();
        const saved = (text || '').split(',').map(s => s.trim());
        const [bSaved, cSaved, pSaved] = [saved[0] || '', saved[1] || '', saved[2] || ''];
        if (pSaved && [...selProv.options].some(o => o.value === pSaved)) {
          selProv.value = pSaved;
          resetCity();
          resetBrgy();
          const cities = unique(byProv(pSaved).map(r => r.City)).sort((a, b) => a.localeCompare(b));
          cities.forEach(c => {
            const opt = document.createElement('option');
            opt.value = c;
            opt.textContent = c;
            if (c === cSaved) opt.selected = true;
            selCity.appendChild(opt);
          });
          selCity.disabled = false;
          if (cSaved) {
            const brgys = byProvCity(pSaved, cSaved).map(r => ({
              name: r.Brgy,
              id: r.AddID
            }));
            brgys.forEach(obj => {
              const opt = document.createElement('option');
              opt.value = obj.name;
              opt.textContent = obj.name;
              opt.dataset.addid = obj.id;
              if (obj.name === bSaved) opt.selected = true;
              selBrgy.appendChild(opt);
            });
            selBrgy.disabled = false;
            const selected = selBrgy.selectedOptions[0];
            hidAdd.value = selected && selected.dataset.addid ? selected.dataset.addid : '';
          }
        } else {
          selProv.value = '';
          resetCity();
          resetBrgy();
          hidAdd.value = '';
        }
        composeLocation();
      };

      document.getElementById('openCreateModalBtn')?.addEventListener('click', fillProvinces, {
        once: true
      });
    })();
  </script>
</body>

</html>