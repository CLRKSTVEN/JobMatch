<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
  .tr-modal{position:fixed;inset:0;display:none;align-items:center;justify-content:center;z-index:9999;background:rgba(0,0,0,.35)}
  .tr-card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 15px 40px rgba(0,0,0,.15);padding:14px;max-width:360px;width:92vw}
  .tr-head{display:flex;align-items:center;justify-content:space-between}
  .tr-head h6{margin:0;font-weight:800;font-size:14px}
  .tr-body{margin-top:10px}
  .tr-row{display:flex;gap:8px;margin-top:6px}
  .tr-row button{flex:1;border:1px solid #e5e7eb;border-radius:10px;padding:.5rem .75rem;font-weight:700;background:#fff}
  .tr-row button.active{border-color:#2563eb;background:#f5f8ff;color:#1d4ed8}
</style>

<div id="trModal" class="tr-modal" aria-hidden="true">
  <div class="tr-card" role="dialog" aria-modal="true" aria-labelledby="trTitle">
    <div class="tr-head">
      <h6 id="trTitle"><span data-i18n="guide.beacon">Guided steps</span> - Translate</h6>
      <button type="button" id="trClose" aria-label="Close" style="border:none;background:transparent;font-size:18px">&times;</button>
    </div>
    <div class="tr-body">
      <div style="font-size:12px;color:#64748b;margin-bottom:6px">Pick language</div>
      <div class="tr-row">
        <button type="button" data-lang="en">English</button>
        <button type="button" data-lang="ceb">Bisaya</button>
      </div>
      <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:12px">
        <button type="button" id="trApply" style="border:1px solid #2563eb;background:#2563eb;color:#fff;border-radius:10px;padding:.45rem .8rem;font-weight:800">
          <span data-i18n="btn.proceed">Proceed</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script>window.I18N_BASE = "<?= base_url('assets/i18n') ?>";</script>
<script src="<?= base_url('assets/js/i18n.js') ?>?v=<?= filemtime(FCPATH.'assets/js/i18n.js') ?>"></script>
<script src="<?= base_url('assets/js/i18n.autoscan.js') ?>?v=<?= filemtime(FCPATH.'assets/js/i18n.autoscan.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', async function(){
  await I18N.init({ defaultLang: (localStorage.getItem('lang_pref') || 'en') });
  I18NAutoScan.init();

  const modal = document.getElementById('trModal');
  const close = document.getElementById('trClose');
  const apply = document.getElementById('trApply');
  const btns  = Array.from(modal.querySelectorAll('[data-lang]'));
  let chosen  = I18N.lang;

  function setActive(){
    btns.forEach(b=> b.classList.toggle('active', b.dataset.lang===chosen));
  }
  btns.forEach(b=> b.addEventListener('click', ()=>{ chosen=b.dataset.lang; setActive(); }));
  setActive();

  function open(){ modal.style.display='flex'; }
  function hide(){ modal.style.display='none'; }

  document.addEventListener('click', (e)=>{
    const t = e.target.closest('#openTranslate');
    if (t){ e.preventDefault(); open(); }
  });

  close.addEventListener('click', hide);
  modal.addEventListener('click', e=>{ if(e.target===modal) hide(); });
  apply.addEventListener('click', async ()=>{ await I18N.setLang(chosen); hide(); });
});
</script>


