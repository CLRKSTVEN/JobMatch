(function () {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function init() {
    // Only transform the "Recent Jobs" table inside the last panel
    var tbl = document.querySelector('.panel.panel--wide table');
    if (!tbl) return;

    function applyLabels(table) {
      var heads = Array.from(table.querySelectorAll('thead th')).map(function (th) {
        return th.textContent.trim();
      });
      table.querySelectorAll('tbody tr').forEach(function (tr) {
        Array.from(tr.children).forEach(function (td, i) {
          if (!td.hasAttribute('data-th') && heads[i]) td.setAttribute('data-th', heads[i]);
        });
      });
    }

    applyLabels(tbl);

    // Re-apply if rows change (e.g., pagination/AJAX later)
    var obs = new MutationObserver(function () {
      applyLabels(tbl);
    });
    if (tbl.tBodies && tbl.tBodies[0]) {
      obs.observe(tbl.tBodies[0], {
        childList: true,
        subtree: true
      });
    }
  }
})();
