(function () {
  document.querySelectorAll('[data-toggle="password"]').forEach(function (btn) {
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

    btn.addEventListener('click', function () {
      setType(input.type === 'password' ? 'text' : 'password');
    });
  });
})();

(function () {
  document.querySelectorAll('.alert .btn-close').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var alert = btn.closest('.alert');
      if (!alert) return;
      alert.classList.remove('show');
      alert.addEventListener(
        'transitionend',
        function () {
          alert.remove();
        },
        { once: true }
      );
    });
  });

  setTimeout(function () {
    document.querySelectorAll('.alert').forEach(function (alert) {
      alert.classList.remove('show');
      alert.addEventListener(
        'transitionend',
        function () {
          alert.remove();
        },
        { once: true }
      );
    });
  }, 4000);
})();

(function () {
  function shouldIgnore(e, link) {
    return (
      e.defaultPrevented ||
      e.button !== 0 ||
      e.metaKey ||
      e.ctrlKey ||
      e.shiftKey ||
      e.altKey ||
      !link ||
      link.target ||
      link.hasAttribute('download') ||
      link.getAttribute('href') === '#'
    );
  }

  var reduceMotion =
    window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  document.querySelectorAll('[data-auth-transition="1"]').forEach(function (link) {
    link.addEventListener('click', function (e) {
      if (shouldIgnore(e, link)) return;
      e.preventDefault();

      var href = link.getAttribute('href');
      if (reduceMotion) {
        window.location.href = href;
        return;
      }

      document.body.classList.add('jm-leave');
      setTimeout(function () {
        window.location.href = href;
      }, 220);
    });
  });
})();
