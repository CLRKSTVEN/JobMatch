<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1, viewport-fit=cover, interactive-widget=overlays-content">
  <title>JobMatch DavOr</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/mdi/css/materialdesignicons.min.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/vendors/css/vendor.bundle.base.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/vertical-light/style.css'); ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/universal.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/landing.css') ?>">

  <link rel="shortcut icon" href="<?= base_url('assets/images/logo.png'); ?>" />

</head>

<body>
  <?php
  $isLoggedIn = !empty($is_logged_in);
  $roleValue = isset($role) ? $role : '';
  $roleSlug = is_string($roleValue) ? strtolower($roleValue) : '';
  $dashboardRoute = 'dashboard/user';
  if ($roleSlug === 'admin') {
    $dashboardRoute = 'dashboard/admin';
  } elseif ($roleSlug === 'worker') {
    $dashboardRoute = 'dashboard/worker';
  } elseif ($roleSlug === 'client') {
    $dashboardRoute = 'dashboard/client';
  } elseif ($roleSlug === 'tesda_admin') {
    $dashboardRoute = 'dashboard/tesda';
  }
  $roleLabelText = $roleSlug !== '' ? ucwords(str_replace('_', ' ', $roleSlug)) : 'Guest';
  $roleLabelSafe = htmlspecialchars($roleLabelText, ENT_QUOTES, 'UTF-8');
  $firstNameValue = isset($first_name) ? (string)$first_name : '';
  $firstNameSafe = $firstNameValue !== '' ? htmlspecialchars($firstNameValue, ENT_QUOTES, 'UTF-8') : '';

  $quickStats = is_array($quick_stats ?? null) ? $quick_stats : [];
  $statWorkers = max(0, (int)($quickStats['workers'] ?? 0));
  $statEmployers = max(0, (int)($quickStats['employers'] ?? 0));
  $statJobs = max(0, (int)($quickStats['open_jobs'] ?? 0));
  $statHotlines = max(0, (int)($quickStats['active_hotlines'] ?? 0));
  $statWorkersFmt = number_format($statWorkers);
  $statEmployersFmt = number_format($statEmployers);
  $statJobsFmt = number_format($statJobs);
  $statHotlinesFmt = number_format($statHotlines);

  $toolkitData = [
    'worker' => [
      'label'   => 'Workers',
      'summary' => 'Build your PESO-ready profile and get matched with local employers.',
      'links'   => [
        [
          'title' => 'Complete worker profile',
          'desc'  => 'Add skills, IDs, and certifications so PESO can endorse you faster.',
          'href'  => site_url('profile'),
          'icon'  => 'mdi-account-hard-hat',
        ],
        [
          'title' => 'Follow community feed',
          'desc'  => 'Join announcements from PESO officers and fellow workers.',
          'href'  => site_url('worker/feed'),
          'icon'  => 'mdi-message-bulleted',
        ],
        [
          'title' => 'Search open jobs',
          'desc'  => 'Filter opportunities by skill, barangay, or company.',
          'href'  => site_url('search'),
          'icon'  => 'mdi-magnify',
        ],
      ],
    ],
    'employer' => [
      'label'   => 'Employers',
      'summary' => 'Post openings, screen applicants, and coordinate interviews.',
      'links'   => [
        [
          'title' => 'Post a new job order',
          'desc'  => 'Create detailed job orders and reach verified workers in minutes.',
          'href'  => site_url('projects/create'),
          'icon'  => 'mdi-briefcase-plus-outline',
        ],
        [
          'title' => 'Review applicant pipeline',
          'desc'  => 'Track submissions, shortlist talent, and manage interviews.',
          'href'  => site_url('dashboard/client'),
          'icon'  => 'mdi-view-dashboard-outline',
        ],
        [
          'title' => 'Message PESO support',
          'desc'  => 'Coordinate caravans or job fairs directly with PESO staff.',
          'href'  => site_url('messages/start'),
          'icon'  => 'mdi-forum-outline',
        ],
      ],
    ],
    'school' => [
      'label'   => 'Schools',
      'summary' => 'Manage student immersions and collaborate on placements.',
      'links'   => [
        [
          'title' => 'Manage student placements',
          'desc'  => 'Submit OJT requests and monitor deployment updates.',
          'href'  => site_url('school-admin'),
          'icon'  => 'mdi-school-outline',
        ],
        [
          'title' => 'Upload student rosters',
          'desc'  => 'Send class lists in bulk for rapid TESDA endorsement.',
          'href'  => site_url('school-admin/bulk'),
          'icon'  => 'mdi-upload',
        ],
        [
          'title' => 'Coordinate with employers',
          'desc'  => 'Connect with partner companies for internships and immersion.',
          'href'  => site_url('messages/start'),
          'icon'  => 'mdi-account-group-outline',
        ],
      ],
    ],
    'tesda' => [
      'label'   => 'TESDA',
      'summary' => 'Align training programs with employer demand across Mati.',
      'links'   => [
        [
          'title' => 'View TESDA dashboard',
          'desc'  => 'Monitor program slots, assessments, and certifications.',
          'href'  => site_url('dashboard/tesda'),
          'icon'  => 'mdi-chart-timeline-variant',
        ],
        [
          'title' => 'Bulk upload trainees',
          'desc'  => 'Sync graduate data with the PESO system in one upload.',
          'href'  => site_url('tesda/workers/upload'),
          'icon'  => 'mdi-file-upload-outline',
        ],
        [
          'title' => 'Share training results',
          'desc'  => 'Update PESO on completers so employers find qualified talent.',
          'href'  => site_url('messages/start'),
          'icon'  => 'mdi-file-document-edit-outline',
        ],
      ],
    ],
    'peso' => [
      'label'   => 'PESO',
      'summary' => 'Keep the city-wide workforce and employers aligned every day.',
      'links'   => [
        [
          'title' => 'Manage PESO dashboard',
          'desc'  => 'Publish job orders, edit details, and control visibility.',
          'href'  => site_url('dashboard/peso'),
          'icon'  => 'mdi-clipboard-text-outline',
        ],
        [
          'title' => 'Bulk upload workers',
          'desc'  => 'Import verified worker lists directly into the database.',
          'href'  => site_url('admin/workers/upload'),
          'icon'  => 'mdi-database-import-outline',
        ],
        [
          'title' => 'Update public hotlines',
          'desc'  => 'Keep jobseekers informed with the latest assistance numbers.',
          'href'  => site_url('hotlines'),
          'icon'  => 'mdi-phone-classic',
        ],
      ],
    ],
  ];

  $toolkitDefault = 'worker';
  if ($roleSlug === 'worker') {
    $toolkitDefault = 'worker';
  } elseif ($roleSlug === 'client') {
    $toolkitDefault = 'employer';
  } elseif (strpos($roleSlug, 'school') !== false) {
    $toolkitDefault = 'school';
  } elseif (strpos($roleSlug, 'tesda') !== false) {
    $toolkitDefault = 'tesda';
  } elseif (strpos($roleSlug, 'peso') !== false || $roleSlug === 'admin') {
    $toolkitDefault = 'peso';
  }
  if (!isset($toolkitData[$toolkitDefault])) {
    $toolkitDefault = 'worker';
  }
  ?>

  <header class="landing-header" id="home">
    <div class="landing-container nav-container">
      <a href="<?= site_url(); ?>" class="brand" aria-label="JobMatch Home">
        <img src="<?= base_url('assets/images/logo-white2.png'); ?>" alt="JobMatch logo">
        <div class="brand-copy">
          <span>JobMatch</span>
          <small>City of Mati</small>
        </div>
      </a>

      <nav class="nav-links" aria-label="Primary navigation">
        <a href="#jobs-latest">Jobs</a>
        <a href="#why-choose">Why JobMatch?</a>
        <a href="#services">Services</a>
        <a href="#partners">Partners</a>
      </nav>

      <div class="nav-actions">
        <?php if ($isLoggedIn): ?>
          <a class="btn btn-ghost btn-sm" href="<?= site_url('auth/logout'); ?>">Logout</a>
          <a class="btn btn-primary btn-sm" href="<?= site_url($dashboardRoute); ?>">Go to dashboard</a>
        <?php else: ?>
          <a class="btn btn-ghost btn-sm" href="<?= site_url('auth/login'); ?>">Log in</a>
          <a class="btn btn-primary btn-sm" href="<?= site_url('auth/signup'); ?>">Register</a>
        <?php endif; ?>
      </div>
    </div>

    <div class="landing-container hero-grid">
      <div class="hero-copy">
        <?php if ($isLoggedIn): ?>
          <span class="hero-eyebrow">Welcome back<?= $firstNameSafe !== '' ? ', ' . $firstNameSafe : ''; ?></span>
          <h1 class="hero-title">Discover the newest opportunities in Mati today.</h1>
          <p class="hero-text">Track urgent vacancies, training schedules, and PESO announcements without leaving your dashboard.</p>
          <div class="hero-cta">
            <a class="btn btn-primary" href="<?= site_url($dashboardRoute); ?>">Continue to your dashboard</a>
            <a class="btn btn-outline" href="#jobs-latest">See fresh job posts</a>
          </div>
        <?php else: ?>
          <span class="hero-eyebrow">City of Mati</span>
          <h1 class="hero-title">Your bridge to meaningful work in Mati City, Davao Oriental.</h1>
          <p class="hero-text">Search job orders, connect with verified employers, and access TESDA training support in one place.</p>
          <div class="hero-cta">
            <a class="btn btn-primary" href="<?= site_url('auth/signup'); ?>">Create free account</a>
            <a class="btn btn-outline" href="<?= site_url('auth/login'); ?>">I already have an account</a>
          </div>
        <?php endif; ?>

        <ul class="hero-points">
          <li><i class="mdi mdi-check-circle-outline"></i> Daily updates direct from Mati PESO</li>
          <li><i class="mdi mdi-check-circle-outline"></i> TESDA-certified trainings and local events</li>
          <li><i class="mdi mdi-check-circle-outline"></i> Support for workers, employers, and schools</li>
        </ul>
      </div>

      <div class="hero-panel">
        <div class="hero-card">
          <h3>Quick job look-up</h3>
          <p>Filter new listings by keyword or location and save them to review later.</p>

          <form class="hero-form" action="<?= site_url('search'); ?>" method="get">
            <label for="search-keyword">Keyword</label>
            <input id="search-keyword" name="keyword" type="text" placeholder="Job title or skill">
            <label for="search-location">Location</label>
            <input id="search-location" name="location" type="text" placeholder="Barangay, city, or province">
            <button type="submit" class="btn btn-primary">Search jobs</button>
          </form>

          <?php if (!$isLoggedIn): ?>
            <div class="hero-divider"><span>Quick start</span></div>
            <div class="hero-quick-grid">
              <a class="hero-quick-link" href="#insights">
                <span>See live job insights</span>
                <i class="mdi mdi-chart-box-outline"></i>
              </a>
              <a class="hero-quick-link" href="#role-toolkit">
                <span>Browse role toolkits</span>
                <i class="mdi mdi-compass-outline"></i>
              </a>
              <a class="hero-quick-link" href="<?= site_url('hotlines'); ?>">
                <span>Contact PESO hotlines</span>
                <i class="mdi mdi-phone-in-talk-outline"></i>
              </a>
            </div>
            <p class="hero-card-footer">Need an account? <a href="<?= site_url('auth/signup'); ?>">Register in minutes</a></p>
          <?php else: ?>
            <div class="hero-note">
              <i class="mdi mdi-information-outline"></i>
              <span>Keep your availability updated to receive invites from employers and schools.</span>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>

  <main>
    <section class="landing-jobs" id="jobs-latest">
      <div class="landing-container">
        <div class="jobs-shell">
          <header class="jobs-header">
            <div>
              <h2 class="section-head">Latest job vacancies</h2>
              <p class="section-subhead">Stay up to date with fresh postings from the Mati Public Employment Service Office.</p>
            </div>
            <div class="jobs-actions">
              <?php if ($isLoggedIn): ?>
                <span class="muted small">Signed in as <?= $roleLabelSafe; ?>.</span>
                <a class="btn btn-outline btn-sm" href="<?= site_url($dashboardRoute); ?>">Manage saved jobs</a>
              <?php else: ?>
                <a class="btn btn-outline btn-sm" href="<?= site_url('auth/signup'); ?>">Save these jobs</a>
                <a class="btn btn-ghost btn-sm" href="<?= site_url('auth/login'); ?>">Login</a>
              <?php endif; ?>
            </div>
          </header>

          <div id="jobs-carousel" class="jobs-carousel" style="display:none;">
            <div class="jobs-track">
              <div class="jobs-inner" id="jobs-inner"></div>
              <div class="carousel-nav">
                <button type="button" class="nav-btn" id="prevD" aria-label="Previous">
                  <i class="mdi mdi-chevron-left"></i>
                </button>
                <button type="button" class="nav-btn" id="nextD" aria-label="Next">
                  <i class="mdi mdi-chevron-right"></i>
                </button>
              </div>
            </div>
            <div class="jobs-dots" id="jobs-dots" aria-label="Carousel pagination"></div>
          </div>
        </div>
      </div>
    </section>

    <section class="landing-insights" id="insights">
      <div class="landing-container">
        <div class="insights-shell">
          <div>
            <span class="insights-eyebrow">Live snapshot</span>
            <h2 class="section-head">PESO opportunities right now</h2>
            <p class="section-subhead">Numbers refresh as soon as city job orders go public.</p>
          </div>

          <div class="insights-grid">
            <article class="insight-card">
              <span class="insight-label">Open job orders</span>
              <div class="insight-value" id="insight-total-jobs">--</div>
              <span class="insight-note" id="insight-total-jobs-note">Waiting for updates...</span>
            </article>

            <article class="insight-card">
              <span class="insight-label">Active locations</span>
              <div class="insight-value" id="insight-total-locations">--</div>
              <span class="insight-note" id="insight-location-top">--</span>
            </article>

            <article class="insight-card">
              <span class="insight-label">Salary transparency</span>
              <div class="insight-value" id="insight-pay-count">--</div>
              <span class="insight-note" id="insight-pay-percent">--</span>
            </article>

            <article class="insight-card">
              <span class="insight-label">Newest posting</span>
              <div class="insight-value" id="insight-last-updated">--</div>
              <span class="insight-note" id="insight-last-updated-note">--</span>
            </article>
          </div>
        </div>
      </div>
    </section>

    <section class="landing-stats" id="why-choose">
      <div class="landing-container">
        <h2 class="section-head">Community impact across JobMatch</h2>
        <p class="section-subhead">Figures update from the live database so you always know how active the platform is.</p>

        <div class="stats-grid">
          <div class="stat-card">
            <strong><?= $statWorkers > 0 ? $statWorkersFmt . '+' : '--'; ?></strong>
            <span>Registered workers building their careers</span>
          </div>
          <div class="stat-card">
            <strong><?= $statEmployers > 0 ? $statEmployersFmt . '+' : '--'; ?></strong>
            <span>Verified employers and partner organizations</span>
          </div>
          <div class="stat-card">
            <strong><?= $statJobs > 0 ? $statJobsFmt : '--'; ?></strong>
            <span>Public job orders currently open</span>
          </div>
          <div class="stat-card">
            <strong><?= $statHotlines > 0 ? $statHotlinesFmt : '--'; ?></strong>
            <span>Active PESO hotlines ready to assist</span>
          </div>
        </div>
      </div>
    </section>

    <section class="landing-toolkit" id="role-toolkit">
      <div class="landing-container">
        <div class="toolkit-shell" data-default-role="<?= htmlspecialchars($toolkitDefault, ENT_QUOTES, 'UTF-8'); ?>">
          <div>
            <h2 class="section-head">Toolkits for every account type</h2>
            <p class="section-subhead">Switch tabs to discover the workflows available to you inside JobMatch.</p>
          </div>

          <div class="toolkit-tabs" role="tablist" aria-label="Role toolkits">
            <?php foreach ($toolkitData as $slug => $conf): ?>
              <?php
              $labelSafe = htmlspecialchars($conf['label'], ENT_QUOTES, 'UTF-8');
              $slugSafe = htmlspecialchars($slug, ENT_QUOTES, 'UTF-8');
              $isActive = $slug === $toolkitDefault ? ' active' : '';
              ?>
              <button type="button" class="toolkit-tab<?= $isActive; ?>" data-role="<?= $slugSafe; ?>"><?= $labelSafe; ?></button>
            <?php endforeach; ?>
          </div>

          <div class="toolkit-panels">
            <?php foreach ($toolkitData as $slug => $conf): ?>
              <?php
              $panelActive = $slug === $toolkitDefault ? ' active' : '';
              $slugSafe = htmlspecialchars($slug, ENT_QUOTES, 'UTF-8');
              $summarySafe = htmlspecialchars($conf['summary'], ENT_QUOTES, 'UTF-8');
              ?>
              <div class="toolkit-panel<?= $panelActive; ?>" data-role="<?= $slugSafe; ?>">
                <p class="toolkit-summary"><?= $summarySafe; ?></p>

                <ul class="toolkit-list">
                  <?php foreach ($conf['links'] as $item): ?>
                    <?php
                    $titleSafe = htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8');
                    $descSafe = htmlspecialchars($item['desc'], ENT_QUOTES, 'UTF-8');
                    $hrefSafe = htmlspecialchars($item['href'], ENT_QUOTES, 'UTF-8');
                    $iconSafe = htmlspecialchars($item['icon'], ENT_QUOTES, 'UTF-8');
                    ?>
                    <li>
                      <a class="toolkit-link" href="<?= $hrefSafe; ?>">
                        <span class="toolkit-icon"><i class="mdi <?= $iconSafe; ?>"></i></span>
                        <span>
                          <strong><?= $titleSafe; ?></strong>
                          <small><?= $descSafe; ?></small>
                        </span>
                      </a>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </section>

    <section class="landing-features" id="services">
      <div class="landing-container">
        <h2 class="section-head">Everything you need to land the role</h2>
        <p class="section-subhead">Whether you are a worker, employer, or school partner, JobMatch keeps everyone in sync.</p>

        <div class="feature-grid">
          <article class="feature-card">
            <span class="feature-icon"><i class="mdi mdi-briefcase-check"></i></span>
            <h3>Guided applications</h3>
            <p>Track requirements, upload documents, and monitor interview slots in one place.</p>
          </article>

          <article class="feature-card">
            <span class="feature-icon"><i class="mdi mdi-school"></i></span>
            <h3>TESDA-aligned trainings</h3>
            <p>Discover accredited programs and reserve slots for upskilling or reskilling.</p>
          </article>

          <article class="feature-card">
            <span class="feature-icon"><i class="mdi mdi-account-group"></i></span>
            <h3>Community support</h3>
            <p>Connect with local employers, PESO officers, and fellow workers for timely updates.</p>
          </article>

          <article class="feature-card">
            <span class="feature-icon"><i class="mdi mdi-chart-line"></i></span>
            <h3>Insights for employers</h3>
            <p>Manage postings, respond to applicants, and review hiring analytics from your dashboard.</p>
          </article>
        </div>
      </div>
    </section>

    <section class="landing-journey" id="how-it-works">
      <div class="landing-container">
        <h2 class="section-head">How your JobMatch journey works</h2>
        <p class="section-subhead">From profile setup to hiring, follow these simple steps to make the most of the platform.</p>

        <div class="journey-grid">
          <div class="journey-step">
            <span class="step-index">Step 01</span>
            <h3>Create or update your profile</h3>
            <p>Complete your details so PESO officers can match you with jobs or trainings faster.</p>
          </div>

          <div class="journey-step">
            <span class="step-index">Step 02</span>
            <h3>Browse active vacancies</h3>
            <p>Filter openings, save the ones you like, and receive notifications for urgent hiring.</p>
          </div>

          <div class="journey-step">
            <span class="step-index">Step 03</span>
            <h3>Connect and get hired</h3>
            <p>Attend orientations, submit documents, and coordinate with employers right inside your dashboard.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="landing-partners" id="partners">
      <div class="landing-container">
        <h2 class="section-head">Local partners backing your success</h2>
        <p class="section-subhead">We work closely with government offices, schools, and private employers to provide trusted opportunities.</p>

        <div class="partner-grid">
          <div class="partner-card">
            <span class="partner-badge">Government</span>
            <h3>City Government of Mati</h3>
            <p>Municipal offices offering roles in frontline services, administration, and community projects.</p>
          </div>

          <div class="partner-card">
            <span class="partner-badge">Training</span>
            <h3>TESDA Davao Oriental</h3>
            <p>Gain new certifications and connect with employers looking for skilled talent.</p>
          </div>

          <div class="partner-card">
            <span class="partner-badge">Education</span>
            <h3>Partner Schools &amp; Universities</h3>
            <p>Onboard students for internships, on-the-job training, and industry partnerships.</p>
          </div>

          <div class="partner-card">
            <span class="partner-badge">Industry</span>
            <h3>Regional Employers</h3>
            <p>Tourism, construction, and service companies with consistent hiring needs.</p>
          </div>
        </div>
      </div>
    </section>

    <section class="landing-community" id="community">
      <div class="landing-container community-grid">
        <article class="community-card">
          <h3>Stay in the loop</h3>
          <p>Receive caravan schedules, job fair announcements, and PESO advisories right away.</p>
          <a class="btn btn-outline" href="<?= site_url('worker/feed'); ?>">Open community feed</a>
        </article>

        <article class="community-card">
          <h3>Support for employers</h3>
          <p>Coordinate interviews, review applicants, and request assistance from PESO specialists.</p>
          <a class="btn btn-outline" href="<?= site_url('dashboard/client'); ?>">Go to employer tools</a>
        </article>

        <article class="community-card">
          <h3>Schools &amp; partners</h3>
          <p>Manage student placements, track requirements, and collaborate with the City of Mati.</p>
          <a class="btn btn-outline" href="<?= site_url('school-admin'); ?>">Visit school admin</a>
        </article>
      </div>
    </section>

    <section class="landing-cta" id="get-started">
      <div class="landing-container">
        <div class="cta-shell">
          <?php if ($isLoggedIn): ?>
            <h2>Ready to continue your hiring journey?</h2>
            <p>Review candidate profiles, update job posts, and respond to applications in seconds.</p>
            <a class="btn btn-primary" href="<?= site_url($dashboardRoute); ?>">Return to your dashboard</a>
          <?php else: ?>
            <h2>Start your next opportunity with JobMatch</h2>
            <p>Sign up for free to manage applications, follow job fairs, and receive tailored alerts from Mati PESO.</p>
            <div class="cta-actions">
              <a class="btn btn-primary" href="<?= site_url('auth/signup'); ?>">Create free account</a>
              <a class="btn btn-outline" href="<?= site_url('auth/login'); ?>">Log in</a>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </section>
  </main>

  <footer class="landing-footer">
    <div class="landing-container">
      <div class="footer-grid">
        <div>
          <div class="footer-heading">Discover jobs</div>
          <div class="footer-links">
            <a href="#jobs-latest">Latest vacancies</a>
            <a href="#services">Worker services</a>
            <a href="#community">Community updates</a>
            <a href="<?= site_url('hotlines'); ?>">Job fairs &amp; caravans</a>
          </div>
        </div>

        <div>
          <div class="footer-heading">For workers</div>
          <div class="footer-links">
            <a href="<?= site_url('auth/signup'); ?>">Create account</a>
            <a href="<?= site_url('auth/login'); ?>">Sign in</a>
            <a href="<?= site_url('profile'); ?>">Update profile</a>
          </div>
        </div>

        <div>
          <div class="footer-heading">For employers</div>
          <div class="footer-links">
            <a href="<?= site_url('users'); ?>">Manage postings</a>
            <a href="<?= site_url('dashboard/client'); ?>">Employer dashboard</a>
            <a href="<?= site_url('admin/reports'); ?>">Reports</a>
          </div>
        </div>

        <div>
          <div class="footer-heading">Support</div>
          <div class="footer-links">
            <span>Email <a href="mailto:support@trabawho.ph">support@jobmatch.ph</a></span>
            <a href="<?= site_url('visibility'); ?>">Privacy notice</a>
            <a href="<?= site_url('complaints'); ?>">Submit a complaint</a>
          </div>
        </div>
      </div>

      <div class="footer-meta">
        <span>&copy; <?= date('Y'); ?> JobMatch - City of Mati </span>
        <span><a href="#" data-twx-open="about" role="button">Terms of use</a> | <a href="<?= site_url('hotlines'); ?>">Contact us</a></span>
      </div>
    </div>
  </footer>

  <!-- Vendor JS -->
  <script src="<?= base_url('assets/vendors/js/vendor.bundle.base.js'); ?>"></script>
  <script src="<?= base_url('assets/js/off-canvas.js'); ?>"></script>
  <script src="<?= base_url('assets/js/hoverable-collapse.js'); ?>"></script>
  <script src="<?= base_url('assets/js/misc.js'); ?>"></script>
  <script src="<?= base_url('assets/js/settings.js'); ?>"></script>
  <script src="<?= base_url('assets/js/todolist.js'); ?>"></script>

  <!-- Feed + unified carousel (mobile & desktop) with DOM-based slides -->
  <script>
    (function() {
      const fmt = (s) => String(s || '')
        .replace(/[<>&]/g, '')
        .replace(/`/g, '')
        .replace(/\$\{/g, '')
        .trim();

      function sanitizeUrl(raw) {
        if (!raw) return '';
        let u = String(raw).trim();
        if (!/^https?:\/\//i.test(u)) u = 'https://' + u;
        return u;
      }

      const fallbackStats = {
        openJobs: <?= (int) $statJobs; ?>
      };

      function updateInsights(list) {
        const safeList = Array.isArray(list) ? list : [];
        const total = safeList.length;
        const fallbackTotal = Number.isFinite(fallbackStats.openJobs) ? fallbackStats.openJobs : 0;

        const totalEl = document.getElementById('insight-total-jobs');
        const totalNoteEl = document.getElementById('insight-total-jobs-note');
        if (totalEl) {
          if (total > 0) {
            totalEl.textContent = String(total);
            if (totalNoteEl) totalNoteEl.textContent = 'From the latest PESO feed';
          } else if (fallbackTotal > 0) {
            totalEl.textContent = String(fallbackTotal);
            if (totalNoteEl) totalNoteEl.textContent = 'Based on dashboard totals';
          } else {
            totalEl.textContent = '0';
            if (totalNoteEl) totalNoteEl.textContent = 'Awaiting new job orders';
          }
        } else if (totalNoteEl) {
          totalNoteEl.textContent = '';
        }

        const locationCounts = {};
        safeList.forEach(item => {
          const loc = fmt(item && item.location_text);
          if (!loc) return;
          locationCounts[loc] = (locationCounts[loc] || 0) + 1;
        });

        const locationEl = document.getElementById('insight-total-locations');
        const locationNoteEl = document.getElementById('insight-location-top');
        const uniqueLocations = Object.keys(locationCounts).length;

        if (locationEl) {
          if (uniqueLocations > 0) {
            locationEl.textContent = String(uniqueLocations);
          } else if (total > 0) {
            locationEl.textContent = '1';
          } else {
            locationEl.textContent = '--';
          }
        }
        if (locationNoteEl) {
          if (uniqueLocations > 0) {
            const [topLoc, topCount] = Object.entries(locationCounts).sort((a, b) => b[1] - a[1])[0];
            locationNoteEl.textContent = `${topLoc} - ${topCount} post${topCount > 1 ? 's' : ''}`;
          } else if (total > 0) {
            locationNoteEl.textContent = 'Listings span multiple barangays';
          } else {
            locationNoteEl.textContent = 'No public locations yet';
          }
        }

        const payCountEl = document.getElementById('insight-pay-count');
        const payPercentEl = document.getElementById('insight-pay-percent');
        const withPay = safeList.filter(item => {
          if (!item) return false;
          const hasMin = item.price_min !== null && item.price_min !== undefined && item.price_min !== '';
          const hasMax = item.price_max !== null && item.price_max !== undefined && item.price_max !== '';
          return hasMin || hasMax;
        }).length;

        if (payCountEl) {
          if (total > 0) {
            payCountEl.textContent = `${withPay}/${total}`;
          } else {
            payCountEl.textContent = fallbackTotal > 0 ? '--' : '0';
          }
        }
        if (payPercentEl) {
          if (total > 0) {
            const percent = Math.round((withPay / total) * 100);
            payPercentEl.textContent = `${percent}% include salary info`;
          } else {
            payPercentEl.textContent = 'Awaiting salary details';
          }
        }

        const lastUpdatedEl = document.getElementById('insight-last-updated');
        const lastUpdatedNoteEl = document.getElementById('insight-last-updated-note');
        if (lastUpdatedEl && lastUpdatedNoteEl) {
          if (total > 0) {
            const recent = safeList[0];
            const stampRaw = recent && recent.created_at ? String(recent.created_at) : '';
            const stamp = stampRaw ? new Date(stampRaw.replace(' ', 'T')) : null;
            if (stamp && !Number.isNaN(stamp.getTime())) {
              const diffMs = Date.now() - stamp.getTime();
              const diffHours = diffMs / (1000 * 60 * 60);
              if (diffHours < 1) {
                lastUpdatedEl.textContent = 'Just now';
                lastUpdatedNoteEl.textContent = 'Posted within the last hour';
              } else if (diffHours < 24) {
                lastUpdatedEl.textContent = Math.round(diffHours) + 'h';
                lastUpdatedNoteEl.textContent = 'Hours since the newest job order';
              } else {
                lastUpdatedEl.textContent = Math.round(diffHours / 24) + 'd';
                lastUpdatedNoteEl.textContent = 'Days since the newest job order';
              }
            } else {
              lastUpdatedEl.textContent = 'Today';
              lastUpdatedNoteEl.textContent = 'Latest timestamp unavailable';
            }
          } else {
            lastUpdatedEl.textContent = '--';
            lastUpdatedNoteEl.textContent = 'No public job feed yet';
          }
        }
      }

      const formatDate = (value) => {
        if (!value) return '';
        const str = String(value).trim();
        const normalized = str.includes('T') ? str : str.replace(' ', 'T');
        const d = new Date(normalized);
        if (!isNaN(d.getTime())) {
          return d.toLocaleDateString('en-PH', {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
          });
        }
        return str;
      };

      const formatPeso = (value) => {
        const num = Number(value);
        if (!isFinite(num)) return null;
        const hasCents = Math.abs(num % 1) > 0;
        return '\u20B1 ' + num.toLocaleString('en-PH', {
          minimumFractionDigits: hasCents ? 2 : 0,
          maximumFractionDigits: hasCents ? 2 : 0
        });
      };

      function makeSlideNode(j, isLatest) {
        const slide = document.createElement('div');
        slide.className = 'job-slide';

        const card = document.createElement('div');
        card.className = 'job-card';
        slide.appendChild(card);

        const mediaRaw = j && j.media && typeof j.media === 'object' ? j.media : null;
        const viewerUrl = mediaRaw && mediaRaw.viewer_url ? sanitizeUrl(mediaRaw.viewer_url) : '';
        const wmUrl = mediaRaw && mediaRaw.wm_url ? sanitizeUrl(mediaRaw.wm_url) : '';
        const publicUrl = mediaRaw && mediaRaw.public_url ? sanitizeUrl(mediaRaw.public_url) : '';
        const mediaType = mediaRaw && mediaRaw.type ? String(mediaRaw.type) : '';
        const mediaLabel = mediaRaw && mediaRaw.original ? String(mediaRaw.original) : 'Job attachment';
        const hasMedia = Boolean(mediaRaw && (publicUrl || viewerUrl || wmUrl));

        if (hasMedia) {
          const inlineUrl = publicUrl || viewerUrl || wmUrl;
          if (inlineUrl) {
            if (mediaType === 'image') {
              const link = document.createElement('a');
              link.className = 'job-card__media job-card__media--image';
              link.href = viewerUrl || inlineUrl;
              link.target = '_blank';
              link.rel = 'noopener';
              const img = document.createElement('img');
              img.src = inlineUrl;
              img.alt = mediaLabel;
              img.loading = 'lazy';
              img.decoding = 'async';
              link.appendChild(img);
              card.appendChild(link);
            } else if (mediaType === 'pdf') {
              const wrap = document.createElement('div');
              wrap.className = 'job-card__media job-card__media--pdf';
              const frame = document.createElement('iframe');
              frame.src = inlineUrl;
              frame.loading = 'lazy';
              frame.title = mediaLabel;
              frame.setAttribute('aria-label', mediaLabel);
              wrap.appendChild(frame);
              const viewLink = document.createElement('a');
              viewLink.href = viewerUrl || inlineUrl;
              viewLink.target = '_blank';
              viewLink.rel = 'noopener';
              viewLink.className = 'job-card__media-link';
              viewLink.innerHTML = '<i class="mdi mdi-open-in-new"></i> View document';
              wrap.appendChild(viewLink);
              card.appendChild(wrap);
            }
          }
        }

        const body = document.createElement('div');
        body.className = 'job-card__content';
        card.appendChild(body);

        if (isLatest) {
          const badge = document.createElement('span');
          badge.className = 'job-badge';
          badge.textContent = 'Newest Listing';
          body.appendChild(badge);
        }

        const title = document.createElement('div');
        title.className = 'title';
        title.textContent = fmt(j && j.title);
        body.appendChild(title);

        const small = document.createElement('div');
        small.className = 'muted small';
        small.textContent = formatDate(j && j.created_at);
        body.appendChild(small);

        const meta = document.createElement('div');
        meta.className = 'meta';
        body.appendChild(meta);

        if (j && j.location_text) {
          const spanLoc = document.createElement('span');
          spanLoc.innerHTML = '<i class="mdi mdi-map-marker"></i> ' + fmt(j.location_text);
          meta.appendChild(spanLoc);
        }

        const minText = formatPeso(j && j.price_min);
        const maxText = formatPeso(j && j.price_max);
        if (minText || maxText) {
          const spanPrice = document.createElement('span');
          const range = (minText && maxText) ? `${minText} - ${maxText}` : (minText || maxText);
          spanPrice.innerHTML = '<i class="mdi mdi-cash"></i> ' + range;
          meta.appendChild(spanPrice);
        }

        const desc = document.createElement('div');
        desc.className = 'job-card__description';
        const descriptionText = fmt(j && j.description);
        desc.textContent = descriptionText || 'No job summary provided yet.';
        body.appendChild(desc);

        const links = [];

        if (hasMedia) {
          const att = document.createElement('a');
          att.className = 'ext-link';
          att.href = viewerUrl || publicUrl || wmUrl;
          att.target = '_blank';
          att.rel = 'noopener';
          const icon = mediaType === 'pdf' ? 'mdi-file-pdf-box' : 'mdi-image-outline';
          att.innerHTML = `<i class="mdi ${icon}"></i> View attachment`;
          links.push(att);
        }

        const rawUrl = (j && (j.website_url || j.url || j.link)) ? (j.website_url || j.url || j.link) : '';
        const safe = sanitizeUrl(rawUrl);
        if (safe) {
          const a = document.createElement('a');
          a.className = 'ext-link';
          a.target = '_blank';
          a.rel = 'noopener';
          a.href = safe;
          a.innerHTML = '<i class="mdi mdi-open-in-new"></i> Apply / Website';
          links.push(a);
        }

        if (links.length) {
          const wrap = document.createElement('div');
          wrap.className = 'job-card__links';
          links.forEach(node => wrap.appendChild(node));
          body.appendChild(wrap);
        }

        return slide;
      }

      function buildDots(cnt, dotsEl) {
        if (!dotsEl) return;
        dotsEl.innerHTML = '';
        for (let i = 0; i < cnt; i++) {
          const b = document.createElement('button');
          if (i === 0) b.classList.add('active');
          b.setAttribute('aria-label', `Go to slide ${i + 1}`);
          dotsEl.appendChild(b);
        }
      }

      function startCarousel(innerEl, dotsEl, prevBtn, nextBtn, intervalMs) {
        if (!innerEl) return;
        const slides = innerEl.children.length;
        if (!slides) return;
        let idx = 0,
          timer = null;

        function go(i) {
          idx = (i + slides) % slides;
          innerEl.style.transform = `translateX(-${idx*100}%)`;
          if (dotsEl) {
            [...dotsEl.children].forEach((d, k) => d.classList.toggle('active', k === idx));
          }
        }

        function start() {
          timer = setInterval(() => go(idx + 1), intervalMs);
        }

        function stop() {
          if (timer) {
            clearInterval(timer);
            timer = null;
          }
        }

        innerEl.addEventListener('mouseenter', stop);
        innerEl.addEventListener('mouseleave', start);

        if (prevBtn) prevBtn.addEventListener('click', () => {
          stop();
          go(idx - 1);
          start();
        });

        if (nextBtn) nextBtn.addEventListener('click', () => {
          stop();
          go(idx + 1);
          start();
        });

        if (dotsEl) {
          [...dotsEl.children].forEach((dot, i) => {
            dot.addEventListener('click', () => {
              stop();
              go(i);
              start();
            });
          });
        }

        let x0 = null;
        innerEl.addEventListener('touchstart', e => {
          x0 = e.touches[0].clientX;
          stop();
        }, {
          passive: true
        });

        innerEl.addEventListener('touchend', e => {
          if (x0 === null) return;
          const dx = (e.changedTouches[0].clientX - x0);
          if (Math.abs(dx) > 40) {
            go(idx + (dx < 0 ? 1 : -1));
          }
          x0 = null;
          start();
        });

        start();
      }

      function initToolkitTabs() {
        const shell = document.querySelector('.toolkit-shell');
        if (!shell) return;
        const tabs = Array.from(shell.querySelectorAll('.toolkit-tab'));
        const panels = Array.from(shell.querySelectorAll('.toolkit-panel'));
        if (!tabs.length || !panels.length) return;

        const activate = (slug) => {
          tabs.forEach(btn => btn.classList.toggle('active', btn.dataset.role === slug));
          panels.forEach(panel => panel.classList.toggle('active', panel.dataset.role === slug));
          shell.setAttribute('data-active-role', slug);
        };

        tabs.forEach(btn => {
          btn.addEventListener('click', () => activate(btn.dataset.role));
        });

        let defaultRole = shell.getAttribute('data-default-role') || '';
        if (!tabs.some(btn => btn.dataset.role === defaultRole)) {
          defaultRole = tabs[0].dataset.role;
        }
        activate(defaultRole);
      }

      initToolkitTabs();
      updateInsights([]);

      fetch('<?= site_url('peso/feed'); ?>', {
          headers: {
            'Accept': 'application/json'
          }
        })
        .then(r => r.ok ? r.text() : Promise.reject())
        .then(t => {
          try {
            return JSON.parse(t);
          } catch (e) {
            return {
              ok: false
            };
          }
        })
        .then(json => {
          if (!json || !json.ok) {
            updateInsights([]);
            return;
          }
          const list = Array.isArray(json.data) ? json.data : [];
          updateInsights(list);

          const wrap = document.getElementById('jobs-carousel');
          const inner = document.getElementById('jobs-inner');
          const dots = document.getElementById('jobs-dots');
          const prevD = document.getElementById('prevD');
          const nextD = document.getElementById('nextD');

          if (!wrap || !inner || !list.length) {
            return;
          }

          while (inner.firstChild) inner.removeChild(inner.firstChild);
          list.forEach((item, idx) => inner.appendChild(makeSlideNode(item, idx === 0)));

          wrap.style.display = 'block';
          buildDots(list.length, dots);
          startCarousel(inner, dots, prevD, nextD, 4800);
        })
        .catch(() => {
          updateInsights([]);
        });
    })();
  </script>

  <?php $this->load->view('includes/footer', ['hide_footer_bar' => true]); ?>
</body>

</html>