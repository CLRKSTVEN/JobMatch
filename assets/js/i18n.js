/* Lightweight i18n (keys + phrase mode) */
(function (w, d) {
  const baseEl = d.querySelector("[data-i18n-base]");
  const I18N_BASE =
    w.I18N_BASE ||
    (baseEl && baseEl.dataset && baseEl.dataset.i18nBase) ||
    (d.body && d.body.dataset && d.body.dataset.i18nBase) ||
    "/assets/i18n";
  const LS_KEY = "lang_pref";

  const state = {
    lang: "en",
    dict: { en: { keys: {}, phrases: {} } },
    ready: false,
  };

  async function loadLang(lang) {
    if (state.dict[lang]) return;
    const url = `${I18N_BASE}/${lang}.json?v=${Date.now()}`;
    const res = await fetch(url, { cache: "no-store" });
    if (!res.ok) throw new Error("i18n load failed: " + url);
    state.dict[lang] = await res.json();
  }

  function tKey(key) {
    const cur = state.dict[state.lang]?.keys?.[key];
    if (cur != null) return cur;
    const en = state.dict.en?.keys?.[key];
    return en != null ? en : null;
  }
  function tPhrase(phrase) {
    const cur = state.dict[state.lang]?.phrases?.[phrase?.trim?.()];
    return cur != null ? cur : null;
  }

  function applyKeys(root) {
    (root || d).querySelectorAll("[data-i18n]").forEach((el) => {
      const k = el.getAttribute("data-i18n");
      const v = tKey(k);
      if (v != null) el.textContent = v;
    });
  }
  function applyPhrases(root) {
    // Cheap & cheerful phrase replace for common UI elements
    const selector =
      "h1,h2,h3,h4,h5,h6,button,a,th,td,label,div span,strong,small";
    (root || d).querySelectorAll(selector).forEach((el) => {
      if (el.hasAttribute("data-i18n")) return; // keys win
      const txt =
        el.childNodes.length === 1 && el.childNodes[0].nodeType === 3
          ? el.textContent.trim()
          : null;
      if (!txt) return;
      const v = tPhrase(txt);
      if (v != null) el.textContent = v;
    });
  }

  async function init({ defaultLang = "en" } = {}) {
    state.lang = localStorage.getItem(LS_KEY) || defaultLang || "en";
    // Always load EN baseline, then overlay selected lang
    await loadLang("en");
    if (state.lang !== "en") {
      try {
        await loadLang(state.lang);
      } catch (e) {
        console.warn(e);
        state.lang = "en";
      }
    }
    applyAll();
    state.ready = true;
  }
  function applyAll(root) {
    applyKeys(root);
    applyPhrases(root);
  }
  async function setLang(lang) {
    if (lang === state.lang) return;
    await loadLang(lang);
    state.lang = lang;
    localStorage.setItem(LS_KEY, lang);
    applyAll();
  }

  // Expose
  w.I18N = {
    init,
    setLang,
    applyAll,
    get lang() {
      return state.lang;
    },
  };
})(window, document);
