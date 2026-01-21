(function () {
  function clearPreviews(container) {
    if (!container) return;
    (container.dataset.urls || "").split("|").forEach((u) => {
      if (u) {
        try {
          URL.revokeObjectURL(u);
        } catch (e) {}
      }
    });
    container.dataset.urls = "";
    container.innerHTML = "";
  }
  function addUrl(container, url) {
    const a = (container.dataset.urls || "").split("|").filter(Boolean);
    a.push(url);
    container.dataset.urls = a.join("|");
  }
  function makeTile(el, name) {
    const t = document.createElement("div");
    t.className = "preview-tile";
    t.appendChild(el);
    const m = document.createElement("div");
    m.className = "preview-meta";
    m.textContent = name || "Selected file";
    t.appendChild(m);
    return t;
  }
  function renderImage(container, file, url) {
    const img = new Image();
    img.className = "preview-img";
    img.alt = file.name;
    img.src = url;
    container.appendChild(makeTile(img, file.name));
  }
  function renderPdf(container, file, url) {
    const iframe = document.createElement("iframe");
    iframe.className = "preview-pdf";
    iframe.src = url;
    iframe.title = file.name;
    container.appendChild(makeTile(iframe, file.name));
  }
  function handleFiles(input, container, multiple = false, limit = 12) {
    if (!input || !container) return;
    clearPreviews(container);
    const files = Array.from(input.files || []);
    if (!files.length) return;
    (multiple ? files.slice(0, limit) : files.slice(0, 1)).forEach((file) => {
      const url = URL.createObjectURL(file);
      addUrl(container, url);
      const t = (file.type || "").toLowerCase();
      if (t.startsWith("image/")) renderImage(container, file, url);
      else if (t === "application/pdf" || /\.pdf$/i.test(file.name))
        renderPdf(container, file, url);
      else {
        const a = document.createElement("a");
        a.href = url;
        a.target = "_blank";
        a.textContent = file.name;
        a.className =
          "block px-3 py-6 text-center text-sm text-blue-600 hover:text-blue-800";
        container.appendChild(makeTile(a, file.name));
      }
    });
  }

  const avatarInput = document.getElementById("avatar");
  const avatarTileWrap = document.getElementById("preview-avatar");
  const avatarCircle = document.getElementById("avatarPreview");
  if (avatarInput) {
    avatarInput.addEventListener("change", (e) => {
      handleFiles(avatarInput, avatarTileWrap, false);
      const f = e.target.files && e.target.files[0];
      if (f && avatarCircle) avatarCircle.src = URL.createObjectURL(f);
    });
  }
  const certInput = document.getElementById("cert_files");
  const certWrap = document.getElementById("preview-cert_files");
  if (certInput)
    certInput.addEventListener("change", () =>
      handleFiles(certInput, certWrap, true, 12)
    );
})();

(function () {
  const phone = document.getElementById("phoneNo");
  if (!phone) return;
  phone.addEventListener("input", (e) => {
    let v = e.target.value.replace(/\D/g, "").slice(0, 10);
    if (v.length >= 7) v = v.replace(/(\d{3})(\d{3})(\d{0,4})/, "$1 $2 $3");
    else if (v.length >= 4) v = v.replace(/(\d{3})(\d{0,3})/, "$1 $2");
    e.target.value = v.trim();
  });
})();

(function () {
  const bio = document.getElementById("bio"),
    cnt = document.getElementById("bioCount");
  function up() {
    if (bio && cnt)
      cnt.textContent = bio.value.length + " / " + (bio.maxLength || 600);
  }
  if (bio) {
    bio.addEventListener("input", up);
    up();
  }
})();

(function () {
  const btns = [...document.querySelectorAll("[data-tab]")];
  const panels = [...document.querySelectorAll("[data-panel]")];
  if (!btns.length || !panels.length) return;
  function setActive(tab) {
    btns.forEach((b) => b.classList.toggle("active", b.dataset.tab === tab));
    panels.forEach((p) =>
      p.classList.toggle("hidden", p.dataset.panel !== tab)
    );
    const url = new URL(window.location);
    url.searchParams.set("tab", tab);
    history.replaceState({}, "", url);
  }
  btns.forEach((b) =>
    b.addEventListener("click", () => setActive(b.dataset.tab))
  );
  const initial = new URLSearchParams(location.search).get("tab") || "info";
  setActive(initial);
})();

(function () {
  const boxes = [
    ...document.querySelectorAll('input[name="availability_days[]"]'),
  ];
  const w = document.getElementById("pickWeekdays"),
    a = document.getElementById("pickAll"),
    n = document.getElementById("clearDays");
  function setDays(set) {
    boxes.forEach((cb) => (cb.checked = set.has(cb.value)));
  }
  if (w)
    w.addEventListener("click", (e) => {
      e.preventDefault();
      setDays(new Set(["Mon", "Tue", "Wed", "Thu", "Fri"]));
    });
  if (a)
    a.addEventListener("click", (e) => {
      e.preventDefault();
      setDays(new Set(["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"]));
    });
  if (n)
    n.addEventListener("click", (e) => {
      e.preventDefault();
      setDays(new Set());
    });
})();

(function () {
  const list = document.getElementById("expList");
  const addBtn = document.getElementById("addExp");
  if (!list || !addBtn) return;
  function addRow(data = {}, focus = true) {
    const i = list.children.length;
    const wrap = document.createElement("div");
    wrap.className = "experience-card";
    wrap.innerHTML = `
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
        <div class="input-group">
          <input type="text" name="exp[${i}][role]" placeholder="Job Title/Role" value="${(
      data.role || ""
    ).replace(/"/g, "&quot;")}" class="form-input">
        </div>
        <div class="input-group">
          <input type="text" name="exp[${i}][employer]" placeholder="Company/Project" value="${(
      data.employer || ""
    ).replace(/"/g, "&quot;")}" class="form-input">
        </div>
        <div class="input-group">
          <input type="text" name="exp[${i}][from]" placeholder="Start Year" value="${(
      data.from || ""
    ).replace(/"/g, "&quot;")}" class="form-input">
        </div>
        <div class="flex gap-3">
          <div class="input-group flex-1">
            <input type="text" name="exp[${i}][to]" placeholder="End Year/Present" value="${(
      data.to || ""
    ).replace(/"/g, "&quot;")}" class="form-input">
          </div>
          <button type="button" class="exp-remove remove-button">Remove</button>
        </div>
      </div>
      <div class="input-group">
        <textarea name="exp[${i}][desc]" rows="3" placeholder="Describe responsibilities, achievements, tools..." class="form-input resize-y">${
      data.desc || ""
    }</textarea>
      </div>`;
    list.appendChild(wrap);
    wrap
      .querySelector(".exp-remove")
      .addEventListener("click", () => wrap.remove());
    if (focus) wrap.querySelector("input").focus();
    if (window.attachHasValue)
      window.attachHasValue(wrap.querySelectorAll(".form-input"));
  }
  list.querySelectorAll(".exp-remove").forEach((b) =>
    b.addEventListener("click", (e) => {
      e.preventDefault();
      b.closest(".experience-card").remove();
    })
  );
  addBtn.addEventListener("click", () => addRow());
})();

(function () {
  function mark(el) {
    const v = (el.value ?? "").toString();
    el.classList.toggle("has-value", v.trim().length > 0);
  }
  window.attachHasValue = function (nodes) {
    nodes.forEach((el) => {
      mark(el);
      el.addEventListener("input", () => mark(el));
      el.addEventListener("change", () => mark(el));
      el.addEventListener("blur", () => mark(el));
    });
  };
  const initial = document.querySelectorAll(
    "input.form-input, textarea.form-input, select.form-input"
  );
  attachHasValue(initial);
})();

(function () {
  let allOptions = [];
  try {
    const el = document.getElementById("skillOptionsJson");
    allOptions = JSON.parse(el ? el.textContent : "[]");
  } catch (e) {
    allOptions = [];
  }

  const selectedCSV = document.getElementById("skills");
  const chipsWrap = document.getElementById("skillsSelected");
  const search = document.getElementById("skillsSearch");
  const toggleBtn = document.getElementById("skillsToggle");
  const panel = document.getElementById("skillsPanel");
  const list = document.getElementById("skillsList");
  const emptyMsg = panel ? panel.querySelector("[data-empty]") : null;

  if (!panel || !list || !selectedCSV) return;

  let selected = (selectedCSV.value || "")
    .split(",")
    .map((s) => s.trim())
    .filter(Boolean);

  const openPanel = () => panel.classList.remove("hidden");
  const closePanel = () => panel.classList.add("hidden");
  const isOpen = () => !panel.classList.contains("hidden");
  const syncHidden = () => (selectedCSV.value = selected.join(", "));

  const esc = (s) =>
    s.replace(
      /[&<>"']/g,
      (c) =>
        ({
          "&": "&amp;",
          "<": "&lt;",
          ">": "&gt;",
          '"': "&quot;",
          "'": "&#39;",
        }[c])
    );

  function renderChips() {
    if (!chipsWrap) return;
    chipsWrap.innerHTML = "";
    selected.forEach((s) => {
      const chip = document.createElement("span");
      chip.className = "skill-chip";
      chip.dataset.skillChip = s;
      chip.innerHTML = `
        <svg class="w-3.5 h-3.5 text-blue-600" viewBox="0 0 24 24" fill="currentColor"><path d="M9 12l2 2 4-4 1.5 1.5L11 17l-3.5-3.5L9 12z"/></svg>
        <span>${esc(s)}</span>
        <button type="button" class="remove-skill" aria-label="Remove ${esc(
          s
        )}">
          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>`;
      chip.querySelector(".remove-skill").addEventListener("click", () => {
        selected = selected.filter((x) => x.toLowerCase() !== s.toLowerCase());
        syncHidden();
        renderChips();
        renderList(search ? search.value : "");
      });
      chipsWrap.appendChild(chip);
    });
  }

  function renderList(q = "") {
    const query = (q || "").toLowerCase();
    list.innerHTML = "";
    const filtered = allOptions.filter((o) => o.toLowerCase().includes(query));
    if (emptyMsg) emptyMsg.classList.toggle("hidden", filtered.length > 0);

    const selLower = selected.map((x) => x.toLowerCase());
    filtered.forEach((o) => {
      const li = document.createElement("li");
      const isSel = selLower.includes(o.toLowerCase());
      li.className = "dropdown-item";
      li.setAttribute("role", "option");
      li.setAttribute("aria-selected", isSel ? "true" : "false");
      li.innerHTML = `
        <div class="flex items-center gap-3 flex-1">
          <span>${esc(o)}</span>
        </div>
        <div class="ml-auto">
          ${
            isSel
              ? '<svg class="w-4 h-4 text-blue-600" viewBox="0 0 24 24" fill="currentColor"><path d="M9 12l2 2 4-4"/></svg>'
              : '<svg class="w-4 h-4 text-gray-300" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="2"/></svg>'
          }
        </div>`;
      li.addEventListener("click", () => {
        if (isSel) {
          selected = selected.filter(
            (x) => x.toLowerCase() !== o.toLowerCase()
          );
        } else {
          selected.push(o);
        }
        syncHidden();
        renderChips();
        renderList(search ? search.value : "");
      });
      list.appendChild(li);
    });
  }

  if (toggleBtn)
    toggleBtn.addEventListener("click", () => {
      isOpen() ? closePanel() : openPanel();
    });
  if (search) {
    search.addEventListener("focus", openPanel);
    search.addEventListener("input", () => renderList(search.value));
    search.addEventListener("keydown", (e) => {
      if (!isOpen()) return;
      const items = Array.from(list.querySelectorAll(".dropdown-item"));
      if (!items.length) return;
      const cur = items.findIndex((el) => el.classList.contains("bg-gray-50"));

      if (e.key === "ArrowDown") {
        e.preventDefault();
        const idx = Math.min(cur < 0 ? 0 : cur + 1, items.length - 1);
        items.forEach((el) => el.classList.remove("bg-gray-50"));
        items[idx].classList.add("bg-gray-50");
        items[idx].scrollIntoView({ block: "nearest" });
      } else if (e.key === "ArrowUp") {
        e.preventDefault();
        const idx = Math.max(cur < 0 ? items.length - 1 : cur - 1, 0);
        items.forEach((el) => el.classList.remove("bg-gray-50"));
        items[idx].classList.add("bg-gray-50");
        items[idx].scrollIntoView({ block: "nearest" });
      } else if (e.key === "Enter" && cur >= 0) {
        e.preventDefault();
        items[cur].click();
      } else if (e.key === "Escape") {
        closePanel();
      }
    });
  }

  document.addEventListener("click", (e) => {
    const within =
      e.target.closest("#skillsPanel") ||
      e.target.closest("#skillsSearch") ||
      e.target.closest("#skillsToggle");
    if (!within) closePanel();
  });

  syncHidden();
  renderChips();
  renderList("");
})();
