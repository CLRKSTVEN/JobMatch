<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<link rel="shortcut icon" href="<?= base_url('assets/images/logo.png') ?>" />

<head>
  <meta charset="utf-8">
  <meta name="csrf-token" content="edjGgac9mtFsWPbrGHhItAsXhkBE8VClTqg62ZE4">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="LEFT4CODE">
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin="">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= base_url('assets/css/signup.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/auth.css') ?>">

  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <title>JobMatch DavOr - Signup</title>

  <!-- ✅ keep (your functions/js rely on dist assets & lucide) -->
  <link rel="stylesheet" href="<?= base_url('dist/css/app.css') ?>">
</head>

<body class="overflow-y-auto">

  <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
    <div class="loader-spinner !w-14"></div>
  </div>

  <!-- =========================================================
       ✅ NEW LAYOUT (NOT Midone)
       - Clean split layout
       - Uses your existing IDs/classes so JS + CSS still works
       ========================================================= -->
  <div class="jm-wrap">
    <div class="jm-left">
      <a class="jm-brand" href="<?= site_url('/') ?>">
        <img src="<?= base_url('assets/images/logo.png') ?>" alt="JobMatch DavOr" class="jm-logo">
      </a>

      <div class="jm-left-body">
        <div class="jm-illus">
          <img src="<?= base_url('dist/images/illustration.svg') ?>" alt="Illustration">
        </div>

        <h1 class="jm-title">
          A few more clicks to<br>
          sign up to your account.
        </h1>

        <p class="jm-sub">
          All your jobs and skilled workers—together in<br>
          one place with JobMatch DavOr
        </p>

        <div class="jm-badges">
          <span class="jm-pill">Skilled Workers</span>
          <span class="jm-pill">Employers</span>
          <span class="jm-pill">Secure</span>
        </div>
      </div>

      <div class="jm-left-foot">
        <small>© <?= date('Y') ?> JobMatch DavOr</small>
      </div>
    </div>

    <div class="jm-right">
      <div class="jm-card">
        <div class="jm-card-head">
          <h2 class="jm-card-title">Sign Up</h2>
          <div class="jm-card-note">Create your JobMatch DavOr account in seconds.</div>
        </div>

        <?= form_open('auth/signup', ['id' => 'signupForm', 'data-email-check-url' => site_url('auth/email-available')]) ?>

        <div class="mt-4">
          <?php foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info', 'msg' => 'success'] as $key => $class): ?>
            <?php if ($this->session->flashdata($key)): ?>
              <div class="alert alert-<?= $class ?> alert-dismissible fade show small mb-3" role="alert">
                <?= htmlspecialchars($this->session->flashdata($key), ENT_QUOTES, 'UTF-8') ?>
                <button type="button" class="btn-close" aria-label="Close"></button>
              </div>
            <?php endif; ?>
          <?php endforeach; ?>
        </div>

        <div class="jm-grid2">
          <div>
            <label class="jm-label" for="first_name">First Name</label>
            <input
              id="first_name"
              class="jm-input"
              type="text"
              name="first_name"
              placeholder="First Name"
              value="<?= set_value('first_name') ?>"
              autocomplete="given-name"
              required
              aria-required="true" />
            <?php if (form_error('first_name')): ?>
              <div class="text-red-600 text-sm mt-2"><?= form_error('first_name'); ?></div>
            <?php endif; ?>
          </div>

          <div>
            <label class="jm-label" for="last_name">Last Name</label>
            <input
              id="last_name"
              class="jm-input"
              type="text"
              name="last_name"
              placeholder="Last Name"
              value="<?= set_value('last_name') ?>"
              autocomplete="family-name"
              required
              aria-required="true" />
            <?php if (form_error('last_name')): ?>
              <div class="text-red-600 text-sm mt-2"><?= form_error('last_name'); ?></div>
            <?php endif; ?>
          </div>
        </div>

        <div class="mt-3">
          <label class="jm-label" for="signupEmail">Email</label>
          <input
            id="signupEmail"
            class="jm-input"
            type="email"
            name="email"
            placeholder="Email"
            value="<?= set_value('email') ?>">
          <div id="emailHint" class="alert alert-info alert-inline" role="alert" hidden></div>

          <?php if (form_error('email')): ?>
            <div class="alert alert-danger alert-inline" role="alert"><?= form_error('email'); ?></div>
          <?php endif; ?>
        </div>

        <div class="mt-3">
          <label class="jm-label" for="signupPassword">Password</label>
          <div class="jm-pass">
            <input
              id="signupPassword"
              class="jm-input jm-input-pass"
              type="password"
              name="password"
              placeholder="Password"
              autocomplete="new-password"
              required
              aria-required="true" />

            <button
              type="button"
              class="jm-eye"
              aria-label="Show password"
              data-toggle="password"
              data-target="#signupPassword">
              <i data-lucide="eye" class="jm-icon"></i>
            </button>
          </div>

          <!-- keep this strength bar block as-is for your current look -->
          <div class="box ml-px mt-4 grid h-2 w-full grid-flow-col gap-3 [--color:var(--color-foreground)]">
            <div class="active bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
            <div class="active bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
            <div class="active bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
            <div class="bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
          </div>
        </div>

        <div class="mt-4">
          <label for="role" class="jm-label">Register as</label>
          <select id="role" name="role" class="field-select" required>
            <option value="" <?= set_select('role', '', true) ?>>-- Select Role --</option>
            <option value="worker" <?= set_select('role', 'worker') ?>>Skilled Worker</option>
            <option value="client" <?= set_select('role', 'client') ?>>Individual / Employer</option>
          </select>
        </div>

        <div class="mt-4 jm-tos">
          <div class="bg-background border-foreground/70 relative size-4 rounded-sm border">
            <input
              class="peer relative z-10 size-full cursor-pointer opacity-0"
              type="checkbox"
              id="tos"
              name="accept_privacy"
              value="1"
              <?= set_checkbox('accept_privacy', '1'); ?>
              required>
            <div class="z-4 bg-foreground invisible absolute inset-0 flex items-center justify-center text-white peer-checked:visible">
              <i data-lucide="check" class="stroke-[1.5] [--color:currentColor] stroke-(--color) fill-(--color)/25 size-4"></i>
            </div>
          </div>

          <label class="jm-tos-label" for="tos">
            I agree to the JobMatch DavOr
            <a class="text-primary ml-1" href="#" id="openPrivacy">Privacy Policy</a>.
          </label>
        </div>

        <?php if (form_error('accept_privacy')): ?>
          <div class="text-red-600 text-sm mt-2"><?= form_error('accept_privacy'); ?></div>
        <?php endif; ?>

        <div class="mt-4">
          <label class="jm-label">Human verification</label>
          <div
            class="g-recaptcha"
            data-sitekey="<?= html_escape($this->config->item('recaptcha_site_key')) ?>"
            data-callback="recaptchaOk"
            data-expired-callback="recaptchaExpired"
            data-error-callback="recaptchaError"></div>
          <?php if (form_error('g-recaptcha-response')): ?>
            <div class="text-red-600 text-sm mt-2"><?= form_error('g-recaptcha-response'); ?></div>
          <?php endif; ?>
        </div>

        <div class="hp" aria-hidden="true">
          <label for="hp-website">Website</label>
          <input id="hp-website" type="text" name="website" autocomplete="off" tabindex="-1">
        </div>

        <div class="mt-5">
          <button
            id="btnRegister"
            type="submit"
            disabled
            class="cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 bg-(--color)/20 border-(--color)/60 text-(--color) hover:bg-(--color)/5 [--color:var(--color-primary)] h-10 box w-full px-4 py-5"
            aria-disabled="true"
            tabindex="-1">
            Register
          </button>

          <?= form_close() ?>

          <a href="<?= site_url('auth/login') ?>"
            data-auth-transition="1"
            class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 bg-background border-(--color)/20 h-10 box mt-4 w-full px-4 py-5">
            Login
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- =========================================================
       YOUR EXISTING MODAL + FIXED CLOSE ICON
       ========================================================= -->

  <div id="pmModal" role="dialog" aria-modal="true" aria-labelledby="pmTitle">
    <div id="pmBackdrop" data-close="1"></div>
    <div id="pmPanel">
      <div id="pmHead">
        <h3 id="pmTitle">Privacy Policy</h3>
        <button type="button" class="pm-btn pm-close" data-close="1" aria-label="Close">&times;</button>
      </div>
      <div id="pmBody">
        <p><strong>Effective date:</strong> <?= date('F j, Y') ?></p>
        <p>Welcome to <strong>JobMatch DavOr</strong>. We connect skilled workers with individuals or employers who need their services. This Privacy Policy explains what data we collect, how we use it, and the choices you have.</p>

        <h4>1) Information We Collect</h4>
        <ul>
          <li>Account data (name, email, password hash), role (worker or client), and account status/approval.</li>
          <li>Profile/portfolio content you add (photos, categories/skills, rates within admin-set ranges, descriptions, images/PDFs).</li>
          <li>Messaging content (threads/messages exchanged in the platform) and hire/transaction activity.</li>
          <li>Reviews/ratings after completed engagements.</li>
          <li>System notifications and related delivery data (email or in-app notification events).</li>
          <li>Basic usage, log, and device information for security and diagnostics.</li>
        </ul>

        <h4>2) How We Use Your Information</h4>
        <ul>
          <li>Create and maintain your account; show worker profiles/portfolios to clients.</li>
          <li>Enable search, hiring, confirmations, and in-app messaging between workers and clients.</li>
          <li>Send notifications about key transaction events; respond to password-reset requests.</li>
          <li>Display/moderate reviews; show admin dashboards/metrics (workers, clients, ongoing/completed engagements).</li>
          <li>Keep the service secure, prevent fraud/abuse, and maintain an audit trail of CRUD operations.</li>
        </ul>

        <h4>3) Legal Bases</h4>
        <p>We process your data to perform our contract with you (providing the JobMatch DavOr service), for our legitimate interests (platform safety and service improvement), and to comply with legal obligations.</p>

        <h4>4) Sharing</h4>
        <p>We share data with service providers that help us operate (ex. hosting, email delivery) under appropriate safeguards. Worker profile content may be visible to signed-in clients as part of matching. We may disclose information if required by law.</p>

        <h4>5) Data Retention</h4>
        <p>We retain account, messaging, and transaction records while your account is active and as required for legitimate business needs and legal obligations. You may request deletion; some records may be retained for compliance, dispute resolution, and security.</p>

        <h4>6) Your Choices & Rights</h4>
        <ul>
          <li>Access and update your profile information in account settings.</li>
          <li>Request deletion of your account (subject to necessary retention).</li>
          <li>Control notification preferences in-app or via email settings.</li>
        </ul>

        <h4>7) Security</h4>
        <p>We use reasonable technical and organizational measures to protect personal data. No method of transmission or storage is 100% secure.</p>

        <h4>8) Children</h4>
        <p>JobMatch DavOr is not directed to children under 16 and we do not knowingly collect personal data from children.</p>

        <h4>9) International Transfers</h4>
        <p>Your information may be processed in countries with different data-protection laws. We take steps to ensure appropriate safeguards are in place.</p>

        <h4>10) Changes</h4>
        <p>We may update this Policy. If changes are material, we’ll notify you in the app or by email.</p>
      </div>
      <div id="pmFoot">
        <button type="button" class="pm-btn" data-close="1">Close</button>
        <button type="button" class="pm-btn pm-btn-primary" id="pmAgree" data-close="1">I Understand</button>
      </div>
    </div>
  </div>

  <!-- keep your scripts (functions unchanged) -->
  <script src="<?= base_url('dist/js/vendors/dom.js') ?>"></script>
  <script src="<?= base_url('dist/js/vendors/lucide.js') ?>"></script>
  <script src="<?= base_url('dist/js/vendors/modal.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/base/page-loader.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/base/lucide.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/theme-switcher.js') ?>"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  <script src="<?= base_url('assets/js/auth-common.js') ?>"></script>
  <script src="<?= base_url('assets/js/auth-signup.js') ?>"></script>

</body>

</html>
