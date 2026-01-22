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

  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <title>JobMatch DavOr - Signup</title>

  <link rel="stylesheet" href="<?= base_url('dist/css/app.css') ?>">
</head>

<style>
  body {
    overflow-y: auto !important;
    font-family: "Poppins", ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
  }

  .field-select {
    height: 3rem;
    width: 100%;
    background: #fff !important;
    color: #0f172a !important;
    border: 1px solid #e5e7eb !important;
    border-radius: .5rem;
    padding: .75rem 1rem;
  }

  .field-select:focus {
    outline: 0 !important;
    border-color: #1e3a8a !important;
    box-shadow: 0 0 0 3px rgba(43, 77, 165, .15) !important;
  }

  .field-select option {
    color: #0f172a;
    background: #fff;
  }

  .alert {
    position: relative;
    padding: .75rem 2.25rem .75rem .75rem;
    border: 1px solid transparent;
    border-radius: .25rem;
    margin-bottom: 1rem;
    font-size: .875rem;
  }

  .alert-success {
    color: #0f5132;
    background: #d1e7dd;
    border-color: #badbcc;
  }

  .alert-danger {
    color: #842029;
    background: #f8d7da;
    border-color: #f5c2c7;
  }

  .alert-info {
    color: #055160;
    background: #cff4fc;
    border-color: #b6effb;
  }

  .alert-dismissible {
    padding-right: 2.25rem;
  }

  .btn-close {
    position: absolute;
    top: .5rem;
    right: .5rem;
    width: 1rem;
    height: 1rem;
    border: 0;
    background: transparent;
    opacity: .7;
    cursor: pointer;
    line-height: 1;
  }

  .btn-close:hover {
    opacity: 1;
  }

  .btn-close::before {
    content: "Ã—";
    font-size: 1rem;
  }

  .fade {
    transition: opacity .15s linear;
    opacity: 0;
  }

  .fade.show {
    opacity: 1;
  }

  .small {
    font-size: .875rem;
  }

  .mb-3 {
    margin-bottom: 1rem;
  }

  button[disabled] {
    pointer-events: none !important;
    user-select: none !important;
    outline: 0 !important;
  }
</style>

<body class="overflow-y-auto">

  <div class="page-loader bg-background fixed inset-0 z-[100] flex items-center justify-center transition-opacity">
    <div class="loader-spinner !w-14"></div>
  </div>

  <div class="relative min-h-screen lg:overflow-visible bg-primary bg-noise xl:bg-background xl:bg-none before:hidden before:xl:block before:content-[''] before:w-[57%] before:-mt-[28%] before:-mb-[16%] before:-ml-[12%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[6deg] before:bg-primary/[.95] before:bg-noise before:rounded-[35%] after:hidden after:xl:block after:content-[''] after:w-[57%] after:-mt-[28%] after:-mb-[16%] after:-ml-[12%] after:absolute after:inset-y-0 after:left-0 after:transform after:rotate-[6deg] after:border after:bg-accent after:bg-cover after:blur-xl after:rounded-[35%] after:border-[20px] after:border-primary">
    <div class="p-3 sm:px-8 relative h-full before:hidden before:xl:block before:w-[57%] before:-mt-[20%] before:-mb-[13%] before:-ml-[12%] before:absolute before:inset-y-0 before:left-0 before:transform before:rotate-[-6deg] before:bg-primary/40 before:bg-noise before:border before:border-primary/50 before:opacity-60 before:rounded-[20%]">
      <div class="container relative z-10 mx-auto sm:px-20">
        <div class="block grid-cols-2 gap-4 xl:grid">
          <div class="hidden min-h-screen flex-col xl:flex">
            <a class="flex items-center pt-10" href="">
              <img class="ml-3" src="<?= base_url('assets/images/logo-white.png') ?>" alt="Midone - Tailwind Admin Dashboard Template" style="width:280px; height:auto;">

            </a>

            <div class="my-auto">
              <img class="-mt-16 w-1/2" src="<?= base_url('dist/images/illustration.svg') ?>" alt="Midone - Tailwind Admin Dashboard Template">
              <div class="mt-10 text-4xl font-medium leading-tight text-white">
                A few more clicks to <br>
                sign up to your account.
              </div>
              <div class="mt-5 text-lg text-white opacity-70">
                All your jobs and skilled workers—together in <br> one place with JobMatch DavOr
              </div>
            </div>
          </div>


          <div class="my-10 flex py-5 xl:my-0 xl:py-0">
            <div class="box relative p-5 before:absolute before:inset-0 before:mx-3 before:-mb-3 before:border before:border-foreground/10 before:bg-background/30 before:shadow-[0px_3px_5px_#0000000b] before:z-[-1] before:rounded-xl after:absolute after:inset-0 after:border after:border-foreground/10 after:bg-background after:shadow-[0px_3px_5px_#0000000b] after:rounded-xl after:z-[-1] after:backdrop-blur-md mx-auto my-auto w-full px-5 py-8 sm:w-3/4 sm:px-8 lg:w-2/4 xl:ml-24 xl:w-auto xl:p-0 xl:before:hidden xl:after:hidden">
              <h2 class="text-center text-2xl font-semibold xl:text-left xl:text-3xl">
                Sign Up
              </h2>
              <div class="mt-2 text-center opacity-70 xl:hidden">
                All your jobs and skilled workers—together in one place with JobMatch DavOr
              </div>

              <?= form_open('auth/signup') ?>


              <div class="mt-8 flex flex-col gap-5">
                <?php foreach (['success' => 'success', 'error' => 'danger', 'info' => 'info', 'msg' => 'success'] as $key => $class): ?>
                  <?php if ($this->session->flashdata($key)): ?>
                    <div class="alert alert-<?= $class ?> alert-dismissible fade show small mb-3" role="alert">
                      <?= htmlspecialchars($this->session->flashdata($key), ENT_QUOTES, 'UTF-8') ?>
                      <button type="button" class="btn-close" aria-label="Close"></button>
                    </div>
                  <?php endif; ?>
                <?php endforeach; ?>


                <input
                  id="first_name"
                  class="h-10 w-full rounded-md border bg-background ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box block min-w-full px-5 py-6 xl:min-w-[28rem]"
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


                <input
                  id="last_name"
                  class="h-10 w-full rounded-md border bg-background ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box block min-w-full px-5 py-6 xl:min-w-[28rem]"
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

                <input
                  id="signupEmail"
                  class="h-10 w-full rounded-md border bg-background ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box block min-w-full px-5 py-6 xl:min-w-[28rem]"
                  type="email"
                  name="email"
                  placeholder="Email"
                  value="<?= set_value('email') ?>">
                <div id="emailHint" class="alert alert-info alert-inline" role="alert" hidden></div>

                <?php if (form_error('email')): ?>
                  <div class="alert alert-danger alert-inline" role="alert"><?= form_error('email'); ?></div>
                <?php endif; ?>
                <div class="relative" style="position:relative">
                  <input
                    id="signupPassword"
                    class="w-full rounded-md border bg-background ring-offset-background file:border-0 file:bg-transparent file:font-medium file:text-foreground placeholder:text-foreground/70 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-foreground/5 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 box block min-w-full xl:min-w-[28rem]"
                    type="password"
                    name="password"
                    placeholder="Password"
                    autocomplete="new-password"
                    required
                    aria-required="true"
                    style="height:48px; padding:0 3rem 0 1rem;" />

                  <button
                    type="button"
                    aria-label="Show password"
                    data-toggle="password"
                    data-target="#signupPassword"
                    style="
      position:absolute;
      top:50%;
      right:12px;
      transform:translateY(-50%);
      width:36px; height:36px;
      display:inline-flex; align-items:center; justify-content:center;
      background:transparent; border:0; padding:0; cursor:pointer;
      color:rgba(15,23,42,.7);
    "
                    onmouseover="this.style.color='rgba(15,23,42,1)';"
                    onmouseout="this.style.color='rgba(15,23,42,.7)';">
                    <i data-lucide="eye" style="width:20px; height:20px"></i>
                  </button>
                </div>
                <div class="box ml-px mt-4 grid h-2 w-full grid-flow-col gap-3 [--color:var(--color-foreground)]">
                  <div class="active bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
                  <div class="active bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
                  <div class="active bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
                  <div class="bg-(--color)/20 border-(--color)/30 -ml-px h-full rounded border [&.active]:[--color:var(--color-success)]"></div>
                </div>



                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                  Register as
                </label>
                <select id="role" name="role" class="field-select" required>
                  <option value="" <?= set_select('role', '', true) ?>>-- Select Role --</option>
                  <option value="worker" <?= set_select('role', 'worker') ?>>Skilled Worker</option>
                  <option value="client" <?= set_select('role', 'client') ?>>Individual / Employer</option>
                </select>


                <div class="flex text-xs sm:text-sm">
                  <div class="flex gap-2.5 mr-auto flex-row items-center">
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

                    <label class="font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 opacity-70" for="tos">
                      I agree to the JobMatch DavOr
                      <a class="text-primary ml-1" href="#" id="openPrivacy">Privacy Policy</a>.
                    </label>
                  </div>
                </div>

                <?php if (form_error('accept_privacy')): ?>
                  <div class="text-red-600 text-sm mt-2"><?= form_error('accept_privacy'); ?></div>
                <?php endif; ?>

              </div>
              <div class="mt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Human verification</label>
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

              <style>
                .hp {
                  position: absolute;
                  left: -9999px;
                  width: 1px;
                  height: 1px;
                  overflow: hidden;
                }
              </style>

              <div class="mt-5 text-center xl:mt-10 xl:text-left">
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
                  class="[--color:var(--color-foreground)] cursor-pointer inline-flex border items-center justify-center gap-2 whitespace-nowrap rounded-lg text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 [&_svg]:pointer-events-none [&_svg]:size-4 [&_svg]:shrink-0 text-(--color) hover:bg-(--color)/5 bg-background border-(--color)/20 h-10 box mt-4 w-full px-4 py-5">
                  Login
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    #pmModal {
      position: fixed;
      inset: 0;
      display: none;
      align-items: flex-start;
      justify-content: center;
      padding: 1rem;
      z-index: 2147483647
    }

    #pmModal.show {
      display: flex
    }

    #pmBackdrop {
      position: absolute;
      inset: 0;
      background: rgba(2, 6, 23, .62)
    }

    #pmPanel {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 900px;
      max-height: 85vh;
      border-radius: 14px;
      background: #fff;
      border: 1px solid rgba(15, 23, 42, .08);
      box-shadow: 0 24px 80px rgba(2, 6, 23, .35);
      overflow: hidden;
      display: flex;
      flex-direction: column
    }

    #pmHead {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 14px 18px;
      border-bottom: 1px solid #e5e7eb
    }

    #pmTitle {
      font-weight: 700
    }

    #pmBody {
      flex: 1 1 auto;
      overflow: auto;
      max-height: calc(85vh - 110px);
      padding: 16px 18px;
      line-height: 1.6;
      color: #0f172a
    }

    #pmBody h4 {
      margin: 10px 0 6px;
      font-weight: 800
    }

    #pmBody p,
    #pmBody li {
      font-size: .95rem
    }

    #pmFoot {
      position: sticky;
      bottom: 0;
      background: #fff;
      border-top: 1px solid #e5e7eb;
      padding: 10px 18px;
      display: flex;
      justify-content: flex-end;
      gap: .5rem
    }

    .pm-btn {
      border-radius: 10px;
      padding: .55rem 1rem;
      font-weight: 700;
      border: 1px solid #e5e7eb;
      background: #fff;
      cursor: pointer
    }

    .pm-btn-primary {
      background: #2563eb;
      border-color: #2563eb;
      color: #fff
    }

    .pm-btn:hover {
      filter: brightness(.97)
    }

    .pm-close:hover {
      opacity: .85;
      transform: scale(1.02)
    }

    .pm-btn:focus,
    .pm-close:focus {
      outline: 0;
      box-shadow: 0 0 0 3px rgba(43, 77, 165, .35)
    }

    .alert-inline {
      display: flex;
      align-items: center;
      gap: .5rem;
      padding: .5rem .75rem;
      margin-top: .5rem;
      border-radius: .5rem;
    }

    .input-invalid {
      border-color: #ef4444 !important;
      box-shadow: 0 0 0 3px rgba(239, 68, 68, .12) !important;
    }

    .input-valid {
      border-color: #22c55e !important;
      box-shadow: 0 0 0 3px rgba(34, 197, 94, .12) !important;
    }
  </style>

  <div id="pmModal" role="dialog" aria-modal="true" aria-labelledby="pmTitle">
    <div id="pmBackdrop" data-close="1"></div>
    <div id="pmPanel">
      <div id="pmHead">
        <h3 id="pmTitle">Privacy Policy</h3>
        <button type="button" class="pm-btn pm-close" data-close="1" aria-label="Close">âœ•</button>
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

  <script src="<?= base_url('dist/js/vendors/dom.js') ?>"></script>
  <script src="<?= base_url('dist/js/vendors/lucide.js') ?>"></script>
  <script src="<?= base_url('dist/js/vendors/modal.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/base/page-loader.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/base/lucide.js') ?>"></script>
  <script src="<?= base_url('dist/js/components/theme-switcher.js') ?>"></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  <script>
    (function() {
      document.querySelectorAll('[data-toggle="password"]').forEach(function(btn) {
        var sel = btn.getAttribute('data-target');
        var input = document.querySelector(sel);
        if (!input) return;

        function setType(type) {
          input.type = type;
          var icon = btn.querySelector('[data-lucide]');
          if (icon) {
            icon.setAttribute('data-lucide', type === 'text' ? 'eye-off' : 'eye');
            if (window.lucide && window.lucide.createIcons) window.lucide.createIcons();
          }
          btn.setAttribute('aria-label', type === 'text' ? 'Hide password' : 'Show password');
        }

        btn.addEventListener('click', function() {
          setType(input.type === 'password' ? 'text' : 'password');
        });
      });
    })();
  </script>

  <script>
    (function() {
      const email = document.getElementById('signupEmail');
      const hint = document.getElementById('emailHint');
      const btn = document.getElementById('btnRegister');
      const url = "<?= site_url('auth/email-available'); ?>";

      if (!email || !hint) return;

      function setInputState(state) {
        email.classList.remove('input-invalid', 'input-valid');
        if (state === 'err') email.classList.add('input-invalid');
        if (state === 'ok') email.classList.add('input-valid');
      }

      function setHint(msg, kind) {
        hint.className = 'alert alert-inline ' + (kind === 'err' ?
          'alert-danger' :
          kind === 'ok' ?
          'alert-success' :
          'alert-info');
        if (!msg) {
          hint.hidden = true;
          return;
        }
        hint.hidden = false;
        hint.textContent = msg;
      }

      function isLikelyEmail(v) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
      }

      let t = null;
      async function check() {
        const v = email.value.trim().toLowerCase();
        const tos = document.getElementById('tos');

        if (!v) {
          setHint('', 'info');
          setInputState('neutral');
          if (btn) btn.disabled = !(tos && tos.checked);
          return;
        }
        if (!isLikelyEmail(v)) {
          setHint('Enter a valid email.', 'err');
          setInputState('err');
          if (btn) btn.disabled = true;
          return;
        }

        try {
          const res = await fetch(url + '?email=' + encodeURIComponent(v), {
            credentials: 'same-origin'
          });
          const data = await res.json();
          if (!data.ok) {
            setHint(data.msg || 'Unable to verify email.', 'err');
            setInputState('err');
            if (btn) btn.disabled = true;
            return;
          }

          if (data.available) {
            setHint('Great, this email is available.', 'ok');
            setInputState('ok');
            if (btn) btn.disabled = !(tos && tos.checked);
          } else {
            setHint('This email is already registered. Please log in or use another email.', 'err');
            setInputState('err');
            if (btn) btn.disabled = true;
          }
        } catch (e) {
          setHint('Unable to verify email right now.', 'err');
          setInputState('err');
          if (btn) btn.disabled = true;
        }
      }

      email.addEventListener('input', () => {
        clearTimeout(t);
        t = setTimeout(check, 350);
      });
      email.addEventListener('blur', check);
      if (email.value) check();
    })();
  </script>

  <script>
    (function() {
      const modal = document.getElementById('pmModal');
      const openEl = document.getElementById('openPrivacy');
      const agree = document.getElementById('pmAgree');
      const tos = document.getElementById('tos');
      const btn = document.getElementById('btnRegister');
      const email = document.getElementById('signupEmail');

      if (!modal || !openEl) return;

      function openModal(e) {
        if (e) e.preventDefault();
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
      }

      function closeModal() {
        modal.classList.remove('show');
        document.body.style.overflow = '';
      }

      function emailIsOk() {
        if (!email) return true;
        const v = (email.value || '').trim();
        return v === '' || email.classList.contains('input-valid');
      }

      function syncBtn() {
        if (!btn) return;
        btn.disabled = !(tos && tos.checked && emailIsOk());
      }

      openEl.addEventListener('click', openModal);

      modal.addEventListener('click', (e) => {
        if (e.target.dataset.close === '1' || e.target.id === 'pmBackdrop') closeModal();
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('show')) closeModal();
      });

      agree && agree.addEventListener('click', function() {
        if (tos && !tos.checked) {
          tos.checked = true;
          tos.dispatchEvent(new Event('change'));
        }
        closeModal();
      });

      tos && tos.addEventListener('change', syncBtn);
      email && email.addEventListener('input', syncBtn);
      email && email.addEventListener('blur', syncBtn);

      syncBtn();
    })();
  </script>

  <script>
    (function() {
      document.querySelectorAll('.alert .btn-close').forEach(function(btn) {
        btn.addEventListener('click', function() {
          var alert = btn.closest('.alert');
          if (!alert) return;
          alert.classList.remove('show');
          alert.addEventListener('transitionend', function() {
            alert.remove();
          }, {
            once: true
          });
        });
      });

      setTimeout(function() {
        document.querySelectorAll('.alert').forEach(function(alert) {
          alert.classList.remove('show');
          alert.addEventListener('transitionend', function() {
            alert.remove();
          }, {
            once: true
          });
        });
      }, 4000);
    })();
  </script>
  <script>
    (function() {
      const form = document.querySelector('form[action*="auth/signup"]');
      const btn = document.getElementById('btnRegister');
      const tos = document.getElementById('tos');
      const email = document.getElementById('signupEmail');

      function emailIsOk() {
        if (!email) return true;
        const v = (email.value || '').trim();
        return v === '' || email.classList.contains('input-valid');
      }
      let greOk = false;
      window.recaptchaOk = function() {
        greOk = true;
        syncBtn();
      };
      window.recaptchaExpired = function() {
        greOk = false;
        syncBtn();
      };
      window.recaptchaError = function() {
        greOk = false;
        syncBtn();
      };

      function applyDisabled(disabled) {
        if (!btn) return;
        btn.disabled = disabled;
        btn.setAttribute('aria-disabled', disabled ? 'true' : 'false');
        if (disabled) btn.setAttribute('tabindex', '-1');
        else btn.removeAttribute('tabindex');
      }

      function syncBtn() {
        const disabled = !(tos && tos.checked && emailIsOk() && greOk);
        applyDisabled(disabled);
      }
      btn && btn.addEventListener('click', function(e) {
        if (btn.disabled) e.preventDefault();
      });
      form && form.addEventListener('submit', function(e) {
        if (btn && btn.disabled) e.preventDefault();
      });

      tos && tos.addEventListener('change', syncBtn);
      email && email.addEventListener('input', syncBtn);
      email && email.addEventListener('blur', syncBtn);
      syncBtn();
    })();
  </script>

</body>

</html>