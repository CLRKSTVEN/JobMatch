/* Auto re-translate when DOM changes (MutationObserver) */
(function (w, d) {
  const mo = new MutationObserver((muts) => {
    if (!w.I18N) return;
    // Re-apply only on added nodes for perf
    muts.forEach((m) => {
      m.addedNodes &&
        m.addedNodes.forEach((n) => {
          if (n.nodeType === 1) w.I18N.applyAll(n);
        });
    });
  });
  function init() {
    mo.observe(d.body, { childList: true, subtree: true });
  }
  w.I18NAutoScan = { init };
})(window, document);
