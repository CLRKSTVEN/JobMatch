(function () {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }

  function parseJson(value, fallback) {
    if (!value) return fallback;
    try {
      return JSON.parse(value);
    } catch (e) {
      return fallback;
    }
  }

  function init() {
    var config = document.getElementById('jmPesoConfig');
    var addressData = parseJson(config && config.dataset.address, []);
    var storeUrl = config && config.dataset.storeUrl ? config.dataset.storeUrl : '';
    var updateUrl = config && config.dataset.updateUrl ? config.dataset.updateUrl : '';
    var forcePublic = config && config.dataset.forcePublic === '1';

    initLocation(addressData);
    initModal({
      storeUrl: storeUrl,
      updateUrl: updateUrl,
      forcePublic: forcePublic
    });
  }

  function initModal(cfg) {
    var $backdrop = document.getElementById('vacancyModal');
    if (!$backdrop) return;
    var $card = $backdrop.querySelector('.tw-modal-card');
    if (!$card) return;
    var $close = document.getElementById('modalCloseBtn');
    var $cancel = document.getElementById('modalCancelBtn');
    var $openCreate = document.getElementById('openCreateModalBtn');

    var form = document.getElementById('vacancyForm');
    if (!form) return;
    var f_id = document.getElementById('v_id');
    var f_title = document.getElementById('v_title');
    var f_desc = document.getElementById('v_description');
    var f_site = document.getElementById('v_website_url');
    var f_type = document.getElementById('v_post_type');
    var f_vis = document.getElementById('v_visibility');
    var f_min = document.getElementById('v_price_min');
    var f_max = document.getElementById('v_price_max');
    var f_loc = document.getElementById('v_location_text');
    var f_file = document.getElementById('v_attachment');
    var f_remove = document.getElementById('v_remove_media');

    var wrapCurrent = document.getElementById('attachmentCurrent');
    var iconCurrent = document.getElementById('attachmentCurrentIcon');
    var nameCurrent = document.getElementById('attachmentCurrentName');
    var linkPreview = document.getElementById('attachmentPreviewLink');
    var btnRemoveAtt = document.getElementById('attachmentRemoveBtn');

    var wrapSelected = document.getElementById('attachmentSelected');
    var iconSelected = document.getElementById('attachmentSelectedIcon');
    var nameSelected = document.getElementById('attachmentSelectedName');
    var btnSelectedClear = document.getElementById('attachmentSelectedClear');

    var removalNotice = document.getElementById('attachmentRemovalNotice');
    var btnUndoRemove = document.getElementById('attachmentUndoRemove');
    var forcePublic = !!cfg.forcePublic;

    var $modeTitle = document.getElementById('modalModeTitle');
    var $submitText = document.getElementById('modalSubmitText');

    var currentAttachment = null;

    var iconForType = function (type) {
      return type === 'pdf' ? 'mdi-file-pdf-box' : (type === 'image' ? 'mdi-image-outline' : 'mdi-file-outline');
    };
    var iconForFile = function (file) {
      if (!file || !file.type) return 'mdi-file-outline';
      return file.type.indexOf('pdf') !== -1 ? 'mdi-file-pdf-box' : 'mdi-image-outline';
    };

    function resetAttachmentUI() {
      if (f_file) {
        f_file.value = '';
      }
      if (f_remove) {
        f_remove.value = '';
      }
      if (wrapCurrent) {
        wrapCurrent.hidden = true;
      }
      if (wrapSelected) {
        wrapSelected.hidden = true;
      }
      if (removalNotice) {
        removalNotice.hidden = true;
      }
      if (linkPreview) {
        linkPreview.href = '#';
        linkPreview.hidden = false;
      }
    }

    function showExistingAttachment(type, name, viewer) {
      if (wrapCurrent) {
        wrapCurrent.hidden = false;
      }
      if (iconCurrent) {
        iconCurrent.className = 'mdi ' + iconForType(type);
      }
      if (nameCurrent) {
        nameCurrent.textContent = name || 'Attachment';
      }
      if (linkPreview) {
        if (viewer) {
          linkPreview.href = viewer;
          linkPreview.hidden = false;
        } else {
          linkPreview.hidden = true;
        }
      }
      if (removalNotice) {
        removalNotice.hidden = true;
      }
    }

    function markAttachmentRemoval() {
      if (wrapCurrent) {
        wrapCurrent.hidden = true;
      }
      if (wrapSelected) {
        wrapSelected.hidden = true;
      }
      if (removalNotice) {
        removalNotice.hidden = false;
      }
      if (f_remove) {
        f_remove.value = '1';
      }
      if (f_file) {
        f_file.value = '';
      }
    }

    function handleFileSelected(file) {
      if (!file) {
        if (wrapSelected) {
          wrapSelected.hidden = true;
        }
        if (currentAttachment && (!f_remove || f_remove.value === '')) {
          showExistingAttachment(currentAttachment.type, currentAttachment.name, currentAttachment.viewer);
        }
        return;
      }
      if (iconSelected) {
        iconSelected.className = 'mdi ' + iconForFile(file);
      }
      if (nameSelected) {
        nameSelected.textContent = file.name || 'Attachment';
      }
      if (wrapSelected) {
        wrapSelected.hidden = false;
      }
      if (wrapCurrent) {
        wrapCurrent.hidden = true;
      }
      if (removalNotice) {
        removalNotice.hidden = true;
      }
      if (f_remove) {
        f_remove.value = '';
      }
    }

    function openModal() {
      $backdrop.classList.add('show');
      setTimeout(function () {
        if (f_title) f_title.focus();
      }, 80);
      document.documentElement.style.overflow = 'hidden';
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      $backdrop.classList.remove('show');
      document.documentElement.style.overflow = '';
      document.body.style.overflow = '';
    }

    function setModeCreate() {
      if ($modeTitle) $modeTitle.textContent = 'Create Job Vacancy';
      if ($submitText) $submitText.textContent = 'Post Vacancy';
      if (cfg.storeUrl) {
        form.action = cfg.storeUrl;
      }
      if (f_id) f_id.value = '';
      if (f_title) f_title.value = '';
      if (f_desc) f_desc.value = '';
      if (f_site) f_site.value = '';
      if (f_type) f_type.value = 'hire';
      if (f_vis) f_vis.value = 'public';
      if (f_min) f_min.value = '';
      if (f_max) f_max.value = '';
      if (f_loc) f_loc.value = '';
      currentAttachment = null;
      resetAttachmentUI();
      if (window.__loc_reset) window.__loc_reset();
    }

    function setModeEdit(d) {
      if ($modeTitle) $modeTitle.textContent = 'Update Job Vacancy';
      if ($submitText) $submitText.textContent = 'Save Changes';
      if (cfg.updateUrl) {
        form.action = cfg.updateUrl + '/' + (d.id || '');
      }
      if (f_id) f_id.value = d.id || '';
      if (f_title) f_title.value = d.title || '';
      if (f_desc) f_desc.value = d.description || '';
      if (f_site) f_site.value = d.website_url || '';
      if (f_type) f_type.value = d.post_type || 'hire';
      if (f_vis) {
        if (forcePublic) {
          f_vis.value = 'public';
        } else {
          f_vis.value = d.visibility || 'public';
        }
      }
      if (f_min) f_min.value = d.price_min || '';
      if (f_max) f_max.value = d.price_max || '';
      if (f_loc) f_loc.value = d.location_text || '';
      currentAttachment = null;
      resetAttachmentUI();
      if (d.mediaViewer) {
        currentAttachment = {
          type: d.mediaType || '',
          name: d.mediaName || 'Attachment',
          viewer: d.mediaViewer || ''
        };
        showExistingAttachment(currentAttachment.type, currentAttachment.name, currentAttachment.viewer);
      }
      if (window.__loc_loadFromText) window.__loc_loadFromText(d.location_text || '');
    }

    if ($openCreate) {
      $openCreate.addEventListener('click', function (e) {
        e.preventDefault();
        setModeCreate();
        openModal();
      });
    }

    document.querySelectorAll('.editVacancyBtn').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        var d = btn.dataset;
        setModeEdit(d);
        openModal();
      });
    });

    if (btnRemoveAtt) {
      btnRemoveAtt.addEventListener('click', function (e) {
        e.preventDefault();
        markAttachmentRemoval();
      });
    }

    if (btnUndoRemove) {
      btnUndoRemove.addEventListener('click', function (e) {
        e.preventDefault();
        if (f_remove) {
          f_remove.value = '';
        }
        if (removalNotice) {
          removalNotice.hidden = true;
        }
        if (currentAttachment) {
          showExistingAttachment(currentAttachment.type, currentAttachment.name, currentAttachment.viewer);
        }
      });
    }

    if (btnSelectedClear) {
      btnSelectedClear.addEventListener('click', function (e) {
        e.preventDefault();
        if (f_file) {
          f_file.value = '';
        }
        if (wrapSelected) {
          wrapSelected.hidden = true;
        }
        if (currentAttachment && (!f_remove || f_remove.value !== '1')) {
          showExistingAttachment(currentAttachment.type, currentAttachment.name, currentAttachment.viewer);
        }
      });
    }

    if (f_file) {
      f_file.addEventListener('change', function () {
        var file = f_file.files && f_file.files[0] ? f_file.files[0] : null;
        handleFileSelected(file);
      });
    }

    document.querySelectorAll('.form-card').forEach(function (card) {
      var btn = card.querySelector('.form-card-toggle');
      var body = card.querySelector('.form-card-body');
      var icon = card.querySelector('.toggle-icon');
      if (!btn || !body) return;

      var open = card.dataset.open !== '0';

      var setState = function () {
        card.classList.toggle('collapsed', !open);
        body.hidden = !open;
        if (icon) {
          icon.className = 'mdi ' + (open ? 'mdi-chevron-up' : 'mdi-chevron-down') + ' toggle-icon';
        }
      };

      setState();

      btn.addEventListener('click', function () {
        open = !open;
        setState();
      });
    });

    [$close, $cancel].forEach(function (el) {
      if (el) el.addEventListener('click', closeModal);
    });
    $backdrop.addEventListener('click', function (e) {
      if (!$card.contains(e.target)) closeModal();
    });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && $backdrop.classList.contains('show')) closeModal();
    });
    $card.addEventListener('click', function (e) {
      e.stopPropagation();
    });

    window.setModeCreate = setModeCreate;
    window.setModeEdit = setModeEdit;
  }

  function initLocation(addressData) {
    var unique = function (arr) { return Array.from(new Set(arr)); };
    var byProv = function (prov) { return addressData.filter(function (r) { return r.Province === prov; }); };
    var byProvCity = function (prov, city) {
      return addressData.filter(function (r) { return r.Province === prov && r.City === city; });
    };

    var selProv = document.getElementById('v_province');
    var selCity = document.getElementById('v_city');
    var selBrgy = document.getElementById('v_brgy');
    var txtLoc = document.getElementById('v_location_text');
    var hidAdd = document.getElementById('v_address_id');

    if (!selProv || !selCity || !selBrgy || !txtLoc || !hidAdd) return;

    function resetCity() {
      selCity.innerHTML = '<option value="">Select City/Municipality</option>';
      selCity.disabled = true;
    }

    function resetBrgy() {
      selBrgy.innerHTML = '<option value="">Select Barangay</option>';
      selBrgy.disabled = true;
    }

    function composeLocation() {
      var p = selProv.value || '';
      var c = selCity.value || '';
      var b = selBrgy.value || '';
      txtLoc.value = [b, c, p].filter(Boolean).join(', ');
    }

    function fillProvinces() {
      if (!selProv || selProv.dataset.filled === '1') return;
      var provinces = unique(addressData.map(function (r) { return r.Province; })).sort(function (a, b) {
        return a.localeCompare(b);
      });
      provinces.forEach(function (p) {
        var opt = document.createElement('option');
        opt.value = p;
        opt.textContent = p;
        selProv.appendChild(opt);
      });
      selProv.dataset.filled = '1';
    }

    selProv.addEventListener('change', function () {
      resetCity();
      resetBrgy();
      hidAdd.value = '';
      var prov = this.value;
      if (!prov) {
        composeLocation();
        return;
      }
      var cities = unique(byProv(prov).map(function (r) { return r.City; })).sort(function (a, b) {
        return a.localeCompare(b);
      });
      cities.forEach(function (c) {
        var opt = document.createElement('option');
        opt.value = c;
        opt.textContent = c;
        selCity.appendChild(opt);
      });
      selCity.disabled = false;
      composeLocation();
    });

    selCity.addEventListener('change', function () {
      resetBrgy();
      hidAdd.value = '';
      var prov = selProv.value;
      var city = this.value;
      if (!prov || !city) {
        composeLocation();
        return;
      }
      var brgys = byProvCity(prov, city).map(function (r) {
        return { name: r.Brgy, id: r.AddID };
      });
      brgys.forEach(function (obj) {
        var opt = document.createElement('option');
        opt.value = obj.name;
        opt.textContent = obj.name;
        opt.dataset.addid = obj.id;
        selBrgy.appendChild(opt);
      });
      selBrgy.disabled = false;
      composeLocation();
    });

    selBrgy.addEventListener('change', function () {
      var opt = this.selectedOptions[0];
      hidAdd.value = opt && opt.dataset.addid ? opt.dataset.addid : '';
      composeLocation();
    });

    window.__loc_reset = function () {
      fillProvinces();
      selProv.value = '';
      resetCity();
      resetBrgy();
      txtLoc.value = '';
      hidAdd.value = '';
    };

    window.__loc_loadFromText = function (text) {
      fillProvinces();
      var saved = (text || '').split(',').map(function (s) { return s.trim(); });
      var bSaved = saved[0] || '';
      var cSaved = saved[1] || '';
      var pSaved = saved[2] || '';
      if (pSaved && Array.from(selProv.options).some(function (o) { return o.value === pSaved; })) {
        selProv.value = pSaved;
        resetCity();
        resetBrgy();
        var cities = unique(byProv(pSaved).map(function (r) { return r.City; })).sort(function (a, b) {
          return a.localeCompare(b);
        });
        cities.forEach(function (c) {
          var opt = document.createElement('option');
          opt.value = c;
          opt.textContent = c;
          if (c === cSaved) opt.selected = true;
          selCity.appendChild(opt);
        });
        selCity.disabled = false;
        if (cSaved) {
          var brgys = byProvCity(pSaved, cSaved).map(function (r) {
            return { name: r.Brgy, id: r.AddID };
          });
          brgys.forEach(function (obj) {
            var opt = document.createElement('option');
            opt.value = obj.name;
            opt.textContent = obj.name;
            opt.dataset.addid = obj.id;
            if (obj.name === bSaved) opt.selected = true;
            selBrgy.appendChild(opt);
          });
          selBrgy.disabled = false;
          var selected = selBrgy.selectedOptions[0];
          hidAdd.value = selected && selected.dataset.addid ? selected.dataset.addid : '';
        }
      } else {
        selProv.value = '';
        resetCity();
        resetBrgy();
        hidAdd.value = '';
      }
      composeLocation();
    };

    var openCreateBtn = document.getElementById('openCreateModalBtn');
    if (openCreateBtn) {
      openCreateBtn.addEventListener('click', fillProvinces, { once: true });
    }
  }
})();
