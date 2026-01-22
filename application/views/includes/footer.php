<?php $hideFooterBar = !empty($hide_footer_bar); ?>

<?php if (!$hideFooterBar): ?>
  <footer class="footer" style="display:flex;justify-content:center;">
    <div class="d-sm-flex justify-content-center justify-content-sm-between">
      <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
        Copyright &copy; <?= date('Y') ?>
        <a href="#" id="twx-about-link" data-twx-open="about" aria-haspopup="dialog" aria-controls="twx-about-modal" style="text-decoration: none; font-weight: bold;">
          <span style="color: blue;">JobMatch</span><span style="color: red;"> DavOr</span>
        </a>
        All rights reserved.
      </span>
    </div>
  </footer>
<?php endif; ?>

<div id="twx-about-modal" class="twx-modal" aria-hidden="true">
  <div class="twx-modal__overlay" data-twx-close></div>
  <div class="twx-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="twx-about-title" aria-describedby="twx-about-desc" tabindex="-1">
    <button class="twx-modal__close" type="button" aria-label="Close" data-twx-close>
      <i class="mdi mdi-close"></i>
    </button>

    <header class="twx-modal__header">
      <div class="twx-brand-pill">
        <i class="mdi mdi-briefcase-account-outline"></i> JobMatch DavOr
      </div>
      <h3 id="twx-about-title">Where every task finds its expert.</h3>
      <p class="twx-sub" id="twx-about-desc">
        JobMatch DavOr connects people who need help with skilled workers who can deliver &mdash; fast, reliable, and transparent.
      </p>
    </header>

    <section class="twx-features">
      <div class="twx-feature">
        <i class="mdi mdi-magnify"></i>
        <div>
          <div class="twx-feature-title">Search &amp; hire</div>
          <div class="twx-feature-desc">Find workers by skill, view profiles &amp; portfolios, and start a job.</div>
        </div>
      </div>
      <div class="twx-feature">
        <i class="mdi mdi-message-text-outline"></i>
        <div>
          <div class="twx-feature-title">Message in-app</div>
          <div class="twx-feature-desc">Align on scope, confirm, complete, and review &mdash; all in one place.</div>
        </div>
      </div>
      <div class="twx-feature">
        <i class="mdi mdi-image-multiple-outline"></i>
        <div>
          <div class="twx-feature-title">Showcase work</div>
          <div class="twx-feature-desc">Add portfolio items (images/PDFs) and build trust with reviews.</div>
        </div>
      </div>
      <div class="twx-feature">
        <i class="mdi mdi-shield-account-outline"></i>
        <div>
          <div class="twx-feature-title">Quality &amp; safety</div>
          <div class="twx-feature-desc">Profiles, categories, and approvals help keep standards high.</div>
        </div>
      </div>
    </section>

    <footer class="twx-modal__footer">
      <span class="twx-note"><i class="mdi mdi-palette-swatch"></i> Brand: blue - gold - red</span>
      <button class="twx-btn" type="button" data-twx-close>Close</button>
    </footer>
  </div>
</div>

<style>
  .twx-modal {
    position: fixed;
    inset: 0;
    z-index: 2000;
    display: none
  }

  .twx-modal.twx-open {
    display: block
  }

  .twx-modal__overlay {
    position: absolute;
    inset: 0;
    background: rgba(15, 23, 42, .55);
    backdrop-filter: blur(2px)
  }

  .twx-modal__dialog {
    position: relative;
    max-width: min(720px, 92vw);
    margin: 6vh auto;
    background: #fff;
    border: 1px solid rgba(148, 163, 184, .35);
    border-radius: 16px;
    box-shadow: 0 30px 80px rgba(2, 6, 23, .35);
    padding: 18px 18px 16px;
    outline: 0;
  }

  .twx-modal__close {
    position: absolute;
    top: 10px;
    right: 10px;
    border: 1px solid rgba(148, 163, 184, .35);
    background: #fff;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: grid;
    place-items: center;
    font-size: 18px;
    color: #334155;
    cursor: pointer;
  }

  .twx-modal__close:hover {
    background: #f6f8fc
  }

  .twx-modal__header {
    display: grid;
    gap: 8px;
    margin-bottom: 8px
  }

  .twx-brand-pill {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-weight: 800;
    font-size: 12px;
    padding: .32rem .6rem;
    border-radius: 9999px;
    background: linear-gradient(90deg, #1d4ed8, #2563eb);
    color: #fff;
    width: max-content;
  }

  .twx-modal__header h3 {
    margin: 0;
    font-weight: 800;
    font-size: clamp(16px, 2.2vw, 20px);
    color: #1e3a8a
  }

  .twx-sub {
    margin: 0;
    color: #475569;
    font-size: 13px
  }

  .twx-features {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin: 10px 0 2px
  }

  @media (max-width:680px) {
    .twx-features {
      grid-template-columns: 1fr
    }
  }

  .twx-feature {
    display: flex;
    gap: 10px;
    align-items: flex-start;
    border: 1px solid rgba(148, 163, 184, .35);
    border-radius: 12px;
    padding: 10px
  }

  .twx-feature i {
    font-size: 18px;
    color: #2563eb
  }

  .twx-feature-title {
    font-weight: 800;
    font-size: 13px;
    color: #1e3a8a
  }

  .twx-feature-desc {
    font-size: 12.5px;
    color: #475569;
    margin-top: 2px
  }

  .twx-modal__footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
    padding-top: 10px;
    border-top: 1px solid rgba(148, 163, 184, .35)
  }

  .twx-note {
    font-size: 12px;
    color: #64748b;
    display: inline-flex;
    align-items: center;
    gap: 6px
  }

  .twx-btn {
    background: #f0b429;
    border: 1px solid #c89113;
    color: #111;
    font-weight: 800;
    border-radius: 10px;
    padding: .42rem .75rem;
    cursor: pointer;
  }

  .twx-btn:hover {
    filter: brightness(0.98)
  }
</style>

<script>
  (function() {
    const modal = document.getElementById('twx-about-modal');
    const triggers = modal ? Array.from(document.querySelectorAll('[data-twx-open="about"]')) : [];
    if (!modal || triggers.length === 0) {
      return;
    }

    const dialog = modal.querySelector('.twx-modal__dialog');
    const closers = modal.querySelectorAll('[data-twx-close]');
    let lastFocus = null;
    let scrollBarComp = 0;

    function lockScroll() {
      const scrollBar = window.innerWidth - document.documentElement.clientWidth;
      scrollBarComp = scrollBar;
      document.body.style.overflow = 'hidden';
      if (scrollBar > 0) {
        document.body.style.paddingRight = scrollBar + 'px';
      }
    }

    function unlockScroll() {
      document.body.style.overflow = '';
      if (scrollBarComp > 0) {
        document.body.style.paddingRight = '';
      }
    }

    function openModal(e) {
      if (e) e.preventDefault();
      lastFocus = document.activeElement;
      modal.classList.add('twx-open');
      modal.setAttribute('aria-hidden', 'false');
      lockScroll();
      dialog.focus();
    }

    function closeModal() {
      modal.classList.remove('twx-open');
      modal.setAttribute('aria-hidden', 'true');
      unlockScroll();
      if (lastFocus && typeof lastFocus.focus === 'function') lastFocus.focus();
    }

    triggers.forEach(el => el.addEventListener('click', openModal));
    closers.forEach(el => el.addEventListener('click', closeModal));
    modal.addEventListener('click', (e) => {
      if (e.target.matches('[data-twx-close], .twx-modal__overlay')) closeModal();
    });
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modal.classList.contains('twx-open')) closeModal();
    });
  })();
</script>
<?php if (!empty($this->session->userdata('logged_in'))): ?>
  <script>
    (function() {
      const CSRF_NAME = '<?= $this->security->get_csrf_token_name(); ?>';
      const CSRF_HASH = '<?= $this->security->get_csrf_hash(); ?>';

      function ping(status) {
        try {
          const fd = new FormData();
          fd.append(CSRF_NAME, CSRF_HASH);
          fd.append('status', status || (document.hidden ? 'away' : 'online'));
          fetch('<?= site_url('messages/api_presence_ping') ?>', {
            method: 'POST',
            credentials: 'same-origin',
            body: fd,
            cache: 'no-store'
          }).catch(() => {});
        } catch (_) {}
      }
      ping();
      setInterval(ping, 10000);

      document.addEventListener('visibilitychange', () => ping(document.hidden ? 'away' : 'online'));

      function beaconOffline() {
        try {
          const url = '<?= site_url('messages/presence_beacon') ?>?ts=' + Date.now();
          if (!navigator.sendBeacon || !navigator.sendBeacon(url)) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', url, false);
            try {
              xhr.send(null);
            } catch (_) {}
          }
        } catch (_) {}
      }
      window.addEventListener('pagehide', beaconOffline);
      window.addEventListener('beforeunload', beaconOffline);
      document.addEventListener('click', (e) => {
        const a = e.target.closest('a[href*="/logout"]');
        if (a) beaconOffline();
      }, true);
    })();
  </script>
<?php endif; ?>