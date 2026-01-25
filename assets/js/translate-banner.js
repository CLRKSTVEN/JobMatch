(function () {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  async function init() {
    if (!window.I18N || !window.I18NAutoScan) return;
    try {
      await I18N.init({ defaultLang: localStorage.getItem('lang_pref') || 'en' });
      I18NAutoScan.init();
    } catch (e) {}

    var modal = document.getElementById('trModal');
    if (!modal) return;
    var close = document.getElementById('trClose');
    var apply = document.getElementById('trApply');
    var btns = Array.prototype.slice.call(modal.querySelectorAll('[data-lang]'));
    var chosen = I18N.lang;

    function setActive() {
      btns.forEach(function (b) {
        b.classList.toggle('active', b.dataset.lang === chosen);
      });
    }

    btns.forEach(function (b) {
      b.addEventListener('click', function () {
        chosen = b.dataset.lang;
        setActive();
      });
    });
    setActive();

    function open() {
      modal.style.display = 'flex';
    }
    function hide() {
      modal.style.display = 'none';
    }

    document.addEventListener('click', function (e) {
      var t = e.target.closest('#openTranslate');
      if (t) {
        e.preventDefault();
        open();
      }
    });

    if (close) close.addEventListener('click', hide);
    modal.addEventListener('click', function (e) {
      if (e.target === modal) hide();
    });
    if (apply) {
      apply.addEventListener('click', async function () {
        try {
          await I18N.setLang(chosen);
        } catch (e) {}
        hide();
      });
    }
  }
})();
