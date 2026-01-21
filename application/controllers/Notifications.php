<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url','html','security']);
        $this->load->library(['session']);
        $this->load->model('ClientProfile_model','cp');
        $this->load->model('ClientProjects_model','cpm');
        $this->load->database();
    }

    private function _json($ok, $msg='OK', $extra=[])
    {
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode(array_merge(['ok'=>(bool)$ok,'message'=>$msg], (array)$extra)));
    }

    private function _uid(): int
    {
        foreach (['id','user_id','uid','account_id'] as $k) {
            $v = $this->session->userdata($k);
            if (is_numeric($v)) return (int)$v;
        }
        return 0;
    }

    public function feed()
    {
        $uid = $this->_uid();
        if ($uid <= 0) return $this->_json(false, 'Unauthorized');

        $limit = (int)($this->input->get('limit') ?: 10);
        $rows  = $this->cp->get_notifications($uid, $limit, 0, ['hire']); 

        $items = [];
        foreach ($rows as $r) {
        $actorName = trim(($r->actor_fname ?? '').' '.($r->actor_lname ?? ''));

$DEFAULT_AVATAR_REL = 'uploads/avatars/avatar.png';

$norm = function($raw){
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

$rawAvatar = $r->actor_avatar ?? $r->avatar ?? '';
$avatar    = '';

if ($rawAvatar !== '' && !$is_default_avatar($rawAvatar)) {
    $avatar = function_exists('avatar_url') ? avatar_url($rawAvatar) : $norm($rawAvatar);
}

if (!$avatar) {
    $wp = $this->db->get_where('worker_profile', ['workerID' => (int)$r->actor_id])->row();
    $cp = $this->db->get_where('client_profile', ['clientID' => (int)$r->actor_id])->row();

    if     ($wp && !empty($wp->avatar) && !$is_default_avatar($wp->avatar))
        $avatar = function_exists('avatar_url') ? avatar_url($wp->avatar) : $norm($wp->avatar);
    elseif ($cp && !empty($cp->avatar) && !$is_default_avatar($cp->avatar))
        $avatar = function_exists('avatar_url') ? avatar_url($cp->avatar) : $norm($cp->avatar);
}

if (!$avatar) $avatar = base_url($DEFAULT_AVATAR_REL);

$items[] = [
    'id'      => (int)$r->id,
    'type'    => (string)$r->type,
    'title'   => (string)$r->title,
    'body'    => (string)$r->body,
    'link'    => (string)$r->link,
    'is_read' => (int)$r->is_read,
    'created' => date('M d, Y h:i A', strtotime($r->created_at)),
    'actor'   => $actorName !== '' ? $actorName : (string)$r->actor_id,
    'avatar'  => $avatar,
];


        }
        return $this->_json(true, 'OK', ['items'=>$items]);
    }

    public function count()
    {
        $uid = $this->_uid();
        if ($uid <= 0) return $this->_json(false, 'Unauthorized');

        $unread = (int)$this->cp->unread_count($uid, ['hire']); 
        return $this->_json(true, 'OK', ['unread'=>$unread]);
    }

    public function mark_read($id=null)
    {
        $uid = $this->_uid();
        if ($uid <= 0) return $this->_json(false, 'Unauthorized');
        $ok = false;
        if ($id) $ok = $this->cp->mark_read((int)$id, $uid);
        return $this->_json($ok, $ok ? 'Updated' : 'Failed');
    }

    public function notify_hire()
    {
        $actor_id = 0;
        foreach (['id','user_id','uid','account_id'] as $k) {
            $v = $this->session->userdata($k);
            if (is_numeric($v)) { $actor_id = (int)$v; break; }
        }
        if ($actor_id <= 0) return $this->_json(false, 'Unauthorized');
        if (($this->session->userdata('role') ?? '') !== 'client') {
            return $this->_json(false, 'Only clients can send hire requests');
        }

        $recipient = (int)($this->input->post('user_id') ?: $this->input->post('worker_id'));
        if ($recipient <= 0) return $this->_json(false, 'Invalid recipient');

        $exists = $this->db->select('id')->from('users')
            ->where(['id'=>$recipient,'role'=>'worker','is_active'=>1])
            ->limit(1)->get()->num_rows() > 0;
        if (!$exists) return $this->_json(false, 'Worker not found or inactive');

        $pid = (int)$this->input->post('project_id');
        $pTitle = '';
        if ($pid > 0) {
            $pr = $this->db->get_where('client_projects', ['id'=>$pid, 'clientID'=>$actor_id])->row();
            if ($pr) { $pTitle = (string)$pr->title; } else { $pid = 0; }
        }

        $ru = strtolower((string)$this->input->post('rate_unit'));
        if (!in_array($ru, ['hour','day','project'], true)) $ru = '';
        $rate = $this->input->post('rate');
        $rate = is_numeric($rate) ? number_format((float)$rate, 2, '.', '') : '';

        $first = (string)($this->session->userdata('first_name') ?? '');
        $last  = (string)($this->session->userdata('last_name') ?? '');
        $clientName = trim($first.' '.$last);

        $title = 'Hire request';
        $bodyBase = $clientName ? ($clientName.' wants to discuss hiring.') : 'A client wants to discuss hiring.';
        $body = $pid > 0 ? ($bodyBase.' Project: “'.$pTitle.'”.') : $bodyBase;
        if ($ru || $rate) {
            $suffix = $ru ?: 'project';
            $body  .= ' Proposed: '.($rate ? ('₱'.$rate.' / '.$suffix) : ('per '.$suffix)).'.';
        }

        $q = ['to' => $actor_id];
        if ($pid > 0) $q['pid'] = $pid;
        if ($this->input->post('invite') === '1') $q['invite'] = '1';
        if ($ru)          $q['ru']     = $ru;
        if ($rate !== '') $q['amount'] = $rate;

        $link = site_url('messages/start?' . http_build_query($q));

        $ok = $this->cp->add_notification($recipient, $actor_id, 'hire', $title, $body, $link);
        return $this->_json((bool)$ok, $ok ? 'Notified' : 'Failed');
    }
}
