<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<link rel="stylesheet" href="<?= base_url('assets/css/translate-banner.css') ?>">

<div id="trModal" class="tr-modal" aria-hidden="true" data-i18n-base="<?= base_url('assets/i18n') ?>">
  <div class="tr-card" role="dialog" aria-modal="true" aria-labelledby="trTitle">
    <div class="tr-head">
      <h6 id="trTitle"><span data-i18n="guide.beacon">Guided steps</span> - Translate</h6>
      <button type="button" id="trClose" class="tr-close" aria-label="Close">&times;</button>
    </div>
    <div class="tr-body">
      <div class="tr-sub">Pick language</div>
      <div class="tr-row">
        <button type="button" data-lang="en">English</button>
        <button type="button" data-lang="ceb">Bisaya</button>
      </div>
      <div class="tr-actions">
        <button type="button" id="trApply" class="tr-apply">
          <span data-i18n="btn.proceed">Proceed</span>
        </button>
      </div>
    </div>
  </div>
</div>

<script src="<?= base_url('assets/js/i18n.js') ?>?v=<?= filemtime(FCPATH.'assets/js/i18n.js') ?>"></script>
<script src="<?= base_url('assets/js/i18n.autoscan.js') ?>?v=<?= filemtime(FCPATH.'assets/js/i18n.autoscan.js') ?>"></script>
<script src="<?= base_url('assets/js/translate-banner.js') ?>?v=<?= filemtime(FCPATH.'assets/js/translate-banner.js') ?>"></script>
