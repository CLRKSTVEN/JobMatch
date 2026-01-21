<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('partials/translate_banner'); ?>

<?php
$CI =& get_instance();
$CI =& get_instance();

$roleRaw = (string)($CI->session->userdata('role') ?? '');
$roleKey = strtolower(trim($roleRaw));
$roleMap = [
  'admin'        => 'admin',
  'administrator'=> 'admin',
  'admins'       => 'admin',

  'worker'       => 'worker',
  'workers'      => 'worker',
  'staff'        => 'worker',

  'client'       => 'client',
  'clients'      => 'client',
  'customer'     => 'client',
  'customers'    => 'client',

  'tesda_admin'  => 'tesda_admin',
  'tesda-admin'  => 'tesda_admin',
  'tesda'        => 'tesda_admin',
];

$role = $roleMap[$roleKey] ?? 'guest';

$seg0 = strtolower((string)$CI->uri->segment(1));
$seg1 = strtolower((string)$CI->uri->segment(2));

if ($seg0 === 'admin' || ($seg0 === 'dashboard' && $seg1 === 'admin')) {
  $dashPath = 'dashboard/admin';
} elseif ($seg0 === 'tesda' || $seg0 === 'tesda_admin' || ($seg0 === 'dashboard' && $seg1 === 'tesda')) {
  $dashPath = 'dashboard/tesda';
} else {
  if     ($role === 'admin')        { $dashPath = 'dashboard/admin'; }
  elseif ($role === 'client')       { $dashPath = 'dashboard/client'; }
  elseif ($role === 'worker')       { $dashPath = 'dashboard/worker'; }
  elseif ($role === 'tesda_admin')  { $dashPath = 'dashboard/tesda'; }
  else                              { $dashPath = 'auth/login'; } // 
}
$dashboard_url = site_url($dashPath);

$CI->load->model('ClientProfile_model');
$CI->load->model('Message_model','mm');

$current_uid = $CI->session->userdata('id') ?: $CI->session->userdata('user_id');
$current_uid = is_numeric($current_uid) ? (int)$current_uid : 0;

$msg_unread   = 0;
$msg_threads  = [];
if ($current_uid > 0) {
    $msg_unread  = (int)$CI->mm->unread_messages_count($current_uid);
    $msg_threads = $CI->mm->latest_threads($current_uid, 8);
}

$unread_count  = 0;
$initial_notes = [];
if ($current_uid > 0) {
  if (method_exists($CI->ClientProfile_model, 'unread_count')) {
    $unread_count = (int)$CI->ClientProfile_model->unread_count($current_uid);
  }
  if (method_exists($CI->ClientProfile_model, 'get_notifications')) {
    $initial_notes = $CI->ClientProfile_model->get_notifications($current_uid, 10, 0);
  }
}
?>

<nav class="navbar col-lg-12 col-12 p-lg-0 fixed-top d-flex flex-row">
  <div class="navbar-menu-wrapper d-flex align-items-center justify-content-between">
    <button class="navbar-toggler navbar-toggler align-items-center" type="button" data-bs-toggle="minimize">
      <i class="mdi mdi-menu"></i>
    </button>

    <ul class="navbar-nav navbar-nav-right ms-lg-auto">
      <?php if ($role === 'client'): ?>
      <li class="nav-item nav-search border-0 me-1 me-md-3 me-lg-5 d-none d-md-flex">
        <form id="topSearchForm" class="nav-link form-inline mt-2 mt-md-0"
              action="<?= site_url('search') ?>" method="get"
              onsubmit="return this.q.value.trim().length >= 2;">
          <div class="input-group tw-searchbar">
            <div class="input-group-append">
              <span class="input-group-text"><i class="mdi mdi-magnify"></i></span>
            </div>
            <input id="topSearchInput" type="text" class="form-control" name="q"
                   placeholder="Search skills you need."
                   value="<?= html_escape($CI->input->get('q')) ?>"
                   required minlength="2" enterkeyhint="search" autocomplete="off">
          </div>
        </form>
      </li>
      <?php endif; ?>

      <!-- Translate button visible to ALL roles -->
      <li class="nav-item ms-2">
        <a href="#" id="openTranslate" class="nav-link" title="Translate">
          <i class="mdi mdi-translate"></i>
          <span class="d-none d-md-inline" data-i18n="nav.translate">Translate</span>
        </a>
      </li>

      <style>
        .tw-searchbar{width:clamp(200px,24vw,220px)!important;background:#fff;border:1px solid #000!important;border-radius:9999px!important;overflow:hidden!important;transition:border-color .15s,box-shadow .15s;}
        .tw-searchbar:focus-within{border-color:#2563eb!important;box-shadow:0 0 0 3px rgba(37,99,235,.20)!important;}
        .tw-searchbar .form-control{height:36px!important;padding:.35rem .7rem!important;font-size:.92rem!important;color:#000!important;background:transparent!important;border:0!important;box-shadow:none!important;font-family:"Poppins",ui-sans-serif,system-ui,-apple-system,"Segoe UI",Roboto,"Helvetica Neue",Arial!important;}
        .tw-searchbar .input-group-text{padding-left:.55rem!important;padding-right:.35rem!important;background:transparent!important;border:0!important;color:#000!important;}
        .tw-searchbar .input-group-text .mdi{font-size:18px!important;line-height:1;}
        @media (max-width:768px){.tw-searchbar{width:100%!important;}}
      </style>

      <li class="nav-item dropdown dropleft">
        <a class="nav-link position-relative" id="notificationDropdown" href="#" data-bs-toggle="dropdown">
          <i class="mdi mdi-bell-outline"></i>
     <span id="notifBadge"
      class="badge rounded-pill bg-danger position-absolute d-none"
      style="top:-2px;right:-4px;">0</span>

        </a>

        <div class="dropdown-menu navbar-dropdown navbar-dropdown-large preview-list dropdown-menu-right"
             aria-labelledby="notificationDropdown" style="min-width:320px">
          <h6 class="p-3 mb-0">Notifications</h6>
          <div id="notifList">
            <?php if (empty($initial_notes)): ?>
              <div class="px-3 pb-3 text-muted small">No notifications yet.</div>
            <?php else: foreach ($initial_notes as $n):
$actor = trim(($n->actor_fname ?? '').' '.($n->actor_lname ?? '')) ?: (string)$n->actor_id;

$DEFAULT_AVATAR_REL = 'uploads/avatars/avatar.png';
$toAbs = function($raw){
  $raw = trim((string)$raw);
  if ($raw === '') return '';
  if (preg_match('#^https?://#i', $raw)) return $raw;
  return base_url(str_replace('\\','/',$raw));
};
$is_default_avatar = function($raw) use ($DEFAULT_AVATAR_REL){
  if ($raw === null) return false;
  $raw = trim((string)$raw);
  if ($raw === '') return false;
  $rel = str_replace(base_url(), '', $raw);
  $rel = ltrim(str_replace('\\','/',$rel), '/');
  return (strcasecmp($rel, $DEFAULT_AVATAR_REL) === 0) || (basename($rel) === basename($DEFAULT_AVATAR_REL));
};

$avatar = '';
$rawAvatar = (string)($n->actor_avatar ?? $n->avatar ?? '');
if ($rawAvatar !== '' && !$is_default_avatar($rawAvatar)) {
  $avatar = function_exists('avatar_url') ? avatar_url($rawAvatar) : $toAbs($rawAvatar);
}

if (!$avatar) {
  $wp = $CI->db->get_where('worker_profile', ['workerID'=>(int)$n->actor_id])->row();
  $cp = $CI->db->get_where('client_profile', ['clientID'=>(int)$n->actor_id])->row();
  if     ($wp && !empty($wp->avatar) && !$is_default_avatar($wp->avatar)) $avatar = function_exists('avatar_url') ? avatar_url($wp->avatar) : $toAbs($wp->avatar);
  elseif ($cp && !empty($cp->avatar) && !$is_default_avatar($cp->avatar)) $avatar = function_exists('avatar_url') ? avatar_url($cp->avatar) : $toAbs($cp->avatar);
}
if (!$avatar) $avatar = base_url($DEFAULT_AVATAR_REL);
?>

              <a href="<?= $n->link ?: 'javascript:void(0)' ?>"
                 class="dropdown-item preview-item d-flex align-items-start notif-item"
                 data-id="<?= (int)$n->id ?>">
<img
  src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>"
  class="me-2 rounded-circle"
  style="width:32px;height:32px;object-fit:cover"
  onerror="this.onerror=null;this.src='<?= htmlspecialchars(base_url('uploads/avatars/avatar.png'), ENT_QUOTES, 'UTF-8') ?>';">
                <div class="preview-item-content flex-grow">
                  <p class="preview-subject mb-0 <?= !$n->is_read ? 'fw-bold' : '' ?>">
                    <?= html_escape($n->title) ?>
                  </p>
                  <?php if (!empty($n->body)): ?>
                    <p class="small text-muted mb-0"><?= html_escape($n->body) ?></p>
                  <?php endif; ?>
                  <p class="small text-muted mb-0"><?= date('M d, Y h:i A', strtotime($n->created_at)) ?></p>
                </div>
              </a>
              <div class="dropdown-divider"></div>
            <?php endforeach; endif; ?>
          </div>
        </div>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link position-relative" id="messageDropdown" href="#" data-bs-toggle="dropdown">
          <i class="mdi mdi-email-outline"></i>
          <?php if ($msg_unread > 0): ?>
            <span id="msgBadge" class="badge rounded-pill bg-danger position-absolute" style="top:-2px;right:-4px;"><?= $msg_unread ?></span>
          <?php else: ?>
            <span id="msgBadge" class="badge rounded-pill bg-danger position-absolute d-none" style="top:-2px;right:-4px;">0</span>
          <?php endif; ?>
        </a>

        <div class="dropdown-menu navbar-dropdown navbar-dropdown-large preview-list dropdown-menu-right"
             aria-labelledby="messageDropdown" style="min-width:320px">
          <h6 class="p-3 mb-0">Messages</h6>
          <div id="msgList">
            <?php if (empty($msg_threads)): ?>
              <div class="px-3 pb-3 text-muted small">No conversations yet.</div>
            <?php else: foreach ($msg_threads as $t):
              $other_id = ($t->a_id == $current_uid) ? (int)$t->b_id : (int)$t->a_id;
              $name     = trim(($t->first_name ?? '').' '.($t->last_name ?? '')) ?: ('User #'.$other_id);
           $DEFAULT_AVATAR_REL = 'uploads/avatars/avatar.png';

$DEFAULT_AVATAR_REL = 'uploads/avatars/avatar.png';

$toAbs = function($raw){
  $raw = trim((string)$raw);
  if ($raw === '') return '';
  if (preg_match('#^https?://#i', $raw)) return $raw;
  return base_url(str_replace('\\','/',$raw));
};

$rawAvatar = $n->actor_avatar ?? $n->avatar ?? '';
$avatar = '';
if ($rawAvatar) {
  $avatar = function_exists('avatar_url') ? avatar_url($rawAvatar)
         : (preg_match('#^https?://#i',$rawAvatar) ? $rawAvatar
         : base_url(str_replace('\\','/',$rawAvatar)));
}
if (!$avatar) {
  $wp = $CI->db->get_where('worker_profile', ['workerID'=>(int)$n->actor_id])->row();
  $cp = $CI->db->get_where('client_profile', ['clientID'=>(int)$n->actor_id])->row();
  if     ($wp && !empty($wp->avatar)) $avatar = function_exists('avatar_url') ? avatar_url($wp->avatar) : base_url(str_replace('\\','/',$wp->avatar));
  elseif ($cp && !empty($cp->avatar)) $avatar = function_exists('avatar_url') ? avatar_url($cp->avatar) : base_url(str_replace('\\','/',$cp->avatar));
}
if (!$avatar) $avatar = base_url('uploads/avatars/avatar.png');

              $link = site_url('messages/t/'.$t->thread_id);
            ?>
              <a href="<?= $link ?>" class="dropdown-item preview-item d-flex align-items-start msg-item"
                 data-thread="<?= (int)$t->thread_id ?>">
               <?php $def = base_url($DEFAULT_AVATAR_REL); ?>
<img
  src="<?= htmlspecialchars($avatar, ENT_QUOTES, 'UTF-8') ?>"
  class="me-2 rounded-circle"
  style="width:32px;height:32px;object-fit:cover"
  onerror="this.onerror=null;this.src='<?= htmlspecialchars($def, ENT_QUOTES, 'UTF-8') ?>';">

                <div class="preview-item-content flex-grow">
                  <p class="preview-subject mb-0"><?= html_escape($name) ?></p>
                  <p class="small text-muted mb-0"><?= html_escape(mb_strimwidth((string)$t->last_body, 0, 80, '…')) ?></p>
                </div>
                <?php if (!empty($t->unread)): ?>
                  <span class="badge bg-primary ms-2"><?= (int)$t->unread ?></span>
                <?php endif; ?>
              </a>
              <div class="dropdown-divider"></div>
            <?php endforeach; endif; ?>
          </div>
        </div>
      </li>

      <!-- HOME ICON -->
      <li class="nav-item"><a class="nav-link" href="<?= $dashboard_url; ?>"><i class="mdi mdi-home-outline"></i></a></li>
    </ul>

    <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-bs-toggle="offcanvas">
      <span class="mdi mdi-menu"></span>
    </button>
  </div>
</nav>

<script>
(function(){
  const msgBadge   = document.getElementById('msgBadge');
  const msgList    = document.getElementById('msgList');
  const msgToggle  = document.getElementById('messageDropdown');
  const MSG_COUNT  = '<?= site_url("messages/api_unread") ?>';
  const MSG_FEED   = '<?= site_url("messages/api_feed") ?>';

  let lastMsgCount = (function(){
    if (!msgBadge || msgBadge.classList.contains('d-none')) return 0;
    const n = parseInt(msgBadge.textContent, 10);
    return isNaN(n) ? 0 : n;
  })();

  let msgInFlight = false;

  function setMsgBadge(n){
    if (!msgBadge) return;
    if (n > 0) { msgBadge.textContent = n; msgBadge.classList.remove('d-none'); }
    else { msgBadge.textContent = '0'; msgBadge.classList.add('d-none'); }
  }

  function esc(s){ return String(s||'')
    .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
    .replace(/"/g,'&quot;').replace(/'/g,'&#039;'); }

  function buildMsgList(items){
    if (!msgList) return;
    if (!items || !items.length){
      msgList.innerHTML = '<div class="px-3 pb-3 text-muted small">No conversations yet.</div>';
      return;
    }
    let html = '';
    items.forEach(t=>{
      html += `
        <a href="${t.link}" class="dropdown-item preview-item d-flex align-items-start msg-item"
           data-thread="${t.thread_id}">
${(() => { const av = (t.avatar && t.avatar.trim()) ? t.avatar : DEFAULT_AVATAR;
return `<img src="${esc(av)}" class="me-2 rounded-circle" style="width:32px;height:32px;object-fit:cover"
             onerror="this.onerror=null;this.src='${DEFAULT_AVATAR}'">`; })()}
          <div class="preview-item-content flex-grow">
            <p class="preview-subject mb-0">${esc(t.name)}</p>
            <p class="small text-muted mb-0">${esc(t.snippet)}</p>
          </div>
          ${t.unread ? `<span class="badge bg-primary ms-2">${t.unread}</span>` : ``}
        </a>
        <div class="dropdown-divider"></div>`;
    });
    msgList.innerHTML = html;
  }
  const DEFAULT_AVATAR = '<?= htmlspecialchars(base_url("uploads/avatars/avatar.png"), ENT_QUOTES, "UTF-8") ?>';

  function refreshMessages(){
    if (msgInFlight) return;
    msgInFlight = true;
    fetch(MSG_COUNT, {credentials:'same-origin'})
      .then(r=>r.json())
      .then(res=>{
        if (!res || !res.ok) return;
        const n = parseInt(res.unread, 10) || 0;
        const delta = n - lastMsgCount;
        if (delta !== 0){
          setMsgBadge(n);
          return fetch(MSG_FEED + '?limit=8', {credentials:'same-origin'})
            .then(r=>r.json())
            .then(f=>{
              if (f && f.ok) buildMsgList(f.items || []);
              if (delta > 0 && !(msgToggle?.parentElement?.classList.contains('show'))) {
                const m = delta === 1 ? 'New message' : `${delta} new messages`;
                window.showToast && window.showToast(m);
              }
            });
        }
      })
      .catch(()=>{})
      .finally(()=>{ msgInFlight = false; });
  }

  if (msgToggle){
    msgToggle.addEventListener('show.bs.dropdown', function(){
      fetch(MSG_FEED + '?limit=8', {credentials:'same-origin'})
        .then(r=>r.json()).then(f=>{ if (f && f.ok) buildMsgList(f.items || []); });
    });
  }

  setInterval(refreshMessages, 8000);
  window.addEventListener('focus', refreshMessages);
})();
</script>

<script>
(function(){
  function csrfPair(){
    <?php if ($this->security->get_csrf_token_name()): ?>
      return { name:'<?= $this->security->get_csrf_token_name(); ?>', hash:'<?= $this->security->get_csrf_hash(); ?>' };
    <?php else: ?> return null; <?php endif; ?>
  }
  function escapeHtml(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;'); }

  window.showToast = function(msg, kind){
    if (!msg) return;
    var wrap = document.getElementById('twToast');
    if (!wrap){
      wrap = document.createElement('div');
      wrap.id = 'twToast';
      wrap.style.position = 'fixed'; wrap.style.top = '16px'; wrap.style.right = '16px'; wrap.style.zIndex = '99999';
      document.body.appendChild(wrap);
    }
    var card = document.createElement('div');
    card.className = 'alert ' + (kind==='error' ? 'alert-danger' : 'alert-success') + ' shadow';
    card.style.marginTop = '8px'; card.style.transition = 'opacity .35s ease';
    card.textContent = msg; wrap.appendChild(card);
    setTimeout(function(){ card.style.opacity = '0'; setTimeout(function(){ card.remove(); }, 360); }, 2200);
  };

  document.addEventListener('DOMContentLoaded', function(){
    try { var s = sessionStorage.getItem('twToastMsg'); if (s){ window.showToast(s); sessionStorage.removeItem('twToastMsg'); } } catch(e){}
    try { var p = new URLSearchParams(window.location.search); if (p.get('sent') === '1'){ window.showToast('Hire request sent'); } } catch(e){}
  });
  const DEFAULT_AVATAR = '<?= htmlspecialchars(base_url("uploads/avatars/avatar.png"), ENT_QUOTES, "UTF-8") ?>';

  const notifToggler = document.getElementById('notificationDropdown');
  if (notifToggler){
    notifToggler.addEventListener('show.bs.dropdown', function(){
      fetch('<?= site_url("notifications/feed") ?>', { credentials:'same-origin' })
        .then(r=>r.json()).then(function(res){
          if(!res || !res.ok) return;
          const list = document.getElementById('notifList'); if (!list) return;

          if (!res.items || !res.items.length){
            list.innerHTML = '<div class="px-3 pb-3 text-muted small">No notifications yet.</div>';
          } else {
            let html = '';
            res.items.forEach(function(n){
              html += `
                <a href="${n.link ? n.link : 'javascript:void(0)'}"
                   class="dropdown-item preview-item d-flex align-items-start notif-item"
                   data-id="${n.id}">
${(() => { const av = (n.avatar && n.avatar.trim()) ? n.avatar : DEFAULT_AVATAR;
return `<img src="${escapeHtml(av)}" class="me-2 rounded-circle" style="width:32px;height:32px;object-fit:cover"
             onerror="this.onerror=null;this.src='${DEFAULT_AVATAR}'">`; })()}
                  <div class="preview-item-content flex-grow">
                    <p class="preview-subject mb-0 ${n.is_read ? '' : 'fw-bold'}">${escapeHtml(n.title)}</p>
                    ${n.body ? `<p class="small text-muted mb-0">${escapeHtml(n.body)}</p>` : ''}
                    <p class="small text-muted mb-0">${escapeHtml(n.created)}</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>`;
            });
            list.innerHTML = html;
          }
          return fetch('<?= site_url("notifications/count") ?>', { credentials:'same-origin' });
        })
        .then(r=>r ? r.json() : null)
        .then(function(cnt){
          if (!cnt || !cnt.ok) return;
          const badge = document.getElementById('notifBadge'); if (!badge) return;
          if (cnt.unread > 0){ badge.textContent = cnt.unread; badge.classList.remove('d-none'); }
          else { badge.classList.add('d-none'); }
        })
        .catch(()=>{});
    });
  }

  document.addEventListener('click', function(e){
    const item = e.target.closest('.notif-item');
    if (!item) return;

    e.preventDefault();
    const id = item.getAttribute('data-id');
    const href = item.getAttribute('href') || '#';

    const subj = item.querySelector('.preview-subject');
    const wasUnread = subj && subj.classList.contains('fw-bold');
    if (subj) subj.classList.remove('fw-bold');

    const badge = document.getElementById('notifBadge');
    if (wasUnread && badge && !badge.classList.contains('d-none')){
      let n = parseInt(badge.textContent, 10); n = isNaN(n) ? 0 : n;
      if (n > 0){ n -= 1; badge.textContent = n > 0 ? String(n) : '0'; if (n <= 0) badge.classList.add('d-none'); }
    }

    const fd = new FormData();
    const csrf = csrfPair(); if (csrf){ fd.append(csrf.name, csrf.hash); }

    fetch('<?= site_url("notifications/mark_read") ?>/' + id, {
      method: 'POST',
      body: fd,
      credentials: 'same-origin',
      keepalive: true
    })
    .catch(()=>{})
    .finally(function(){
      setTimeout(function(){ window.location.href = href; }, 350);
    });
  });
})();
</script>

<script>
(function(){
  const badge      = document.getElementById('notifBadge');
  const list       = document.getElementById('notifList');
  const dropdown   = document.getElementById('notificationDropdown');
  const FEED_URL   = '<?= site_url("notifications/feed") ?>';
  const COUNT_URL  = '<?= site_url("notifications/count") ?>';

  const USER_ID    = <?= (int)($this->session->userdata('id') ?: $this->session->userdata('user_id') ?: 0) ?>;
  const LAST_KEY   = 'twNotifLastId:' + USER_ID;
  const DEFAULT_AVATAR = '<?= htmlspecialchars(base_url("uploads/avatars/avatar.png"), ENT_QUOTES, "UTF-8") ?>';

  let lastUnread   = (function(){
    if (!badge || badge.classList.contains('d-none')) return 0;
    const n = parseInt(badge.textContent, 10);
    return isNaN(n) ? 0 : n;
  })();

  let lastSeenId   = parseInt(localStorage.getItem(LAST_KEY) || '0', 10);
  (function seedFromDom(){
    if (lastSeenId || !list) return;
    const first = list.querySelector('.notif-item');
    if (first) {
      const id = parseInt(first.getAttribute('data-id') || '0', 10);
      if (id) { lastSeenId = id; localStorage.setItem(LAST_KEY, String(id)); }
    }
  })();

  let inFlight = false;

  function setBadge(n){
    if (!badge) return;
    if (n > 0){
      badge.textContent = n;
      badge.classList.remove('d-none');
    } else {
      badge.textContent = '0';
      badge.classList.add('d-none');
    }
  }

  function rebuildList(items){
    if (!list) return;
    if (!items || !items.length){
      list.innerHTML = '<div class="px-3 pb-3 text-muted small">No notifications yet.</div>';
      return;
    }
    const esc = s => String(s||'')
      .replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
      .replace(/"/g,'&quot;').replace(/'/g,'&#039;');
    let html = '';
    items.forEach(n=>{
      html += `
        <a href="${n.link ? n.link : 'javascript:void(0)'}"
           class="dropdown-item preview-item d-flex align-items-start notif-item"
           data-id="${n.id}">
         ${(() => { const av = (n.avatar && n.avatar.trim()) ? n.avatar : DEFAULT_AVATAR;
return `<img src="${esc(av)}" class="me-2 rounded-circle"
             style="width:32px;height:32px;object-fit:cover"
             onerror="this.onerror=null;this.src='${DEFAULT_AVATAR}'">`; })()}

          <div class="preview-item-content flex-grow">
            <p class="preview-subject mb-0 ${n.is_read ? '' : 'fw-bold'}">${esc(n.title)}</p>
            ${n.body ? `<p class="small text-muted mb-0">${esc(n.body)}</p>` : ''}
            <p class="small text-muted mb-0">${esc(n.created)}</p>
          </div>
        </a>
        <div class="dropdown-divider"></div>`;
    });
    list.innerHTML = html;
  }

  function fetchFeedAndMaybeToast(unreadDelta){
    return fetch(FEED_URL + '?limit=10', {credentials:'same-origin'})
      .then(r=>r.json())
      .then(res=>{
        if (!res || !res.ok) return;
        const items = res.items || [];

        rebuildList(items);

        const maxId = items.reduce((m, x)=> Math.max(m, parseInt(x.id||0,10)||0), 0);
        if (maxId && maxId > lastSeenId) {
          localStorage.setItem(LAST_KEY, String(maxId));
          lastSeenId = maxId;

          const ddOpen = dropdown?.parentElement?.classList.contains('show');
          if (!ddOpen && unreadDelta > 0) {
            const msg = unreadDelta === 1 ? 'New notification' : `${unreadDelta} new notifications`;
            window.showToast && window.showToast(msg);
          }
        }
      }).catch(()=>{});
  }

  function tick(){
    if (inFlight) return;
    inFlight = true;

    fetch(COUNT_URL, {credentials:'same-origin'})
      .then(r=>r.json())
      .then(res=>{
        if (!res || !res.ok) return;
        const n = parseInt(res.unread, 10) || 0;
        const delta = n - lastUnread;
        if (delta !== 0){
          setBadge(n);
          fetchFeedAndMaybeToast(delta);
          lastUnread = n;
        }
      })
      .catch(()=>{})
      .finally(()=>{ inFlight = false; });
  }

  setInterval(tick, 5000);
  window.addEventListener('focus', tick);
  tick();
})();
</script>

<!-- Translate opener (works with partials/translate_banner + i18n.js) -->
<script>
(function(){
  const btn = document.getElementById('openTranslate');
  if (!btn) return;
  btn.addEventListener('click', function(e){
    e.preventDefault();
    // Prefer explicit API if your translator exposes it:
    if (window.I18N && typeof I18N.openPicker === 'function') { I18N.openPicker(); return; }
    if (window.I18N && typeof I18N.openBanner === 'function') { I18N.openBanner(); return; }
    // Fallback: broadcast an event that your banner listens for
    document.dispatchEvent(new CustomEvent('i18n:open'));
    // Optional gentle feedback if nothing handled it
    // if (typeof window.showToast === 'function') showToast('Translator not loaded on this page', 'error');
  });
})();
</script>


