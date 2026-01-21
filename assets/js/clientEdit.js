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
    const arr = (container.dataset.urls || "").split("|").filter(Boolean);
    arr.push(url);
    container.dataset.urls = arr.join("|");
  }
  function makeTile(el, name) {
    const tile = document.createElement("div");
    tile.className = "preview-tile";
    tile.appendChild(el);
    const meta = document.createElement("div");
    meta.className = "preview-meta";
    meta.textContent = name || "Selected file";
    tile.appendChild(meta);
    return tile;
  }
  function renderImage(container, file, url) {
    const img = document.createElement("img");
    img.className = "preview-img";
    img.alt = file.name;
    img.src = url;
    container.appendChild(makeTile(img, file.name));
  }
  function renderPdf(container, file, url) {
    const iframe = document.createElement("iframe");
    iframe.className = "w-full h-32 border-0";
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
  const avatarWrap = document.getElementById("preview-avatar");
  const avatarImg = document.getElementById("avatarPreview");
  if (avatarInput) {
    avatarInput.addEventListener("change", (e) => {
      handleFiles(avatarInput, avatarWrap, false);
      const f = e.target.files && e.target.files[0];
      if (f && avatarImg) avatarImg.src = URL.createObjectURL(f);
    });
  }

  const idInput = document.getElementById("id_image");
  if (idInput)
    idInput.addEventListener("change", () =>
      handleFiles(idInput, document.getElementById("preview-id_image"), false)
    );

  const certInput = document.getElementById("certificates");
  if (certInput)
    certInput.addEventListener("change", () =>
      handleFiles(
        certInput,
        document.getElementById("preview-certificates"),
        true,
        12
      )
    );

  const permitInput = document.getElementById("business_permit");
  if (permitInput)
    permitInput.addEventListener("change", () =>
      handleFiles(
        permitInput,
        document.getElementById("preview-business_permit"),
        false
      )
    );

  document.querySelectorAll(".file-upload-area").forEach((area) => {
    const input = area.querySelector('input[type="file"]');
    ["dragover", "dragleave", "drop"].forEach((ev) =>
      area.addEventListener(ev, (e) => e.preventDefault())
    );
    area.addEventListener("dragover", () => {
      area.style.background = "var(--primary-50)";
      area.style.borderColor = "var(--primary-400)";
    });
    area.addEventListener("dragleave", () => {
      area.style.background = "var(--gray-50)";
      area.style.borderColor = "var(--gray-300)";
    });
    area.addEventListener("drop", (e) => {
      area.style.background = "var(--gray-50)";
      area.style.borderColor = "var(--gray-300)";
      const files = e.dataTransfer.files;
      if (files?.length) {
        input.files = files;
        input.dispatchEvent(new Event("change"));
      }
    });
  });

  const phone = document.getElementById("phoneNo");
  if (phone) {
    phone.addEventListener("input", function (e) {
      let v = e.target.value.replace(/\D/g, "").slice(0, 10);
      if (v.length >= 7) v = v.replace(/(\d{3})(\d{3})(\d{0,4})/, "$1 $2 $3");
      else if (v.length >= 4) v = v.replace(/(\d{3})(\d{0,3})/, "$1 $2");
      e.target.value = v.trim();
    });
  }
})();

(function () {
  const tabBtns = Array.from(document.querySelectorAll("[data-tab]"));
  const panels = Array.from(document.querySelectorAll("[data-panel]"));
  function setActive(tab) {
    tabBtns.forEach((btn) =>
      btn.classList.toggle("active", btn.dataset.tab === tab)
    );
    panels.forEach((p) =>
      p.classList.toggle("hidden", p.dataset.panel !== tab)
    );
    const url = new URL(window.location);
    url.searchParams.set("tab", tab);
    history.replaceState({}, "", url);
  }
  tabBtns.forEach((btn) =>
    btn.addEventListener("click", () => setActive(btn.dataset.tab))
  );
  setActive(new URLSearchParams(location.search).get("tab") || "info");
})();
