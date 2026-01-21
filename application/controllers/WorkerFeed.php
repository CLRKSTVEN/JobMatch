<?php defined('BASEPATH') OR exit('No direct script access allowed');

class WorkerFeed extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('role') !== 'worker') {
            show_error('Access Denied: Workers only.', 403);
        }
        $this->load->model('WorkerFeedModel', 'feed');
        $this->load->helper(['url', 'form']);
        $this->load->library('form_validation');
        
    }

 private function me()
{
    $keys = ['id','user_id','users_id','uid','userId'];
    foreach ($keys as $k) {
        $v = (int) $this->session->userdata($k);
        if ($v > 0) return $v;
    }
    $email = (string) $this->session->userdata('email');
    if ($email !== '') {
        $row = $this->db->select('id')->from('users')->where('email',$email)->get()->row();
        if ($row) return (int)$row->id;
    }

    show_error('Not logged in correctly. User id missing in session.', 401);
}
public function index()
{
    $wid   = $this->me();
    $scope = $this->input->get('scope', true) ?: 'all';

    $data['posts']         = ($scope === 'mine') ? $this->feed->get_feed_by_user($wid, 50)
                                                 : $this->feed->get_feed_global(50);
    $data['scope']         = $scope;
    $data['post_action']   = 'worker/feed/post';
    $data['delete_action'] = 'worker/feed/delete';   // ← add this
    $data['me_id']         = $wid;                   // ← and this
    $data['page_title']    = 'Timeline · Worker';
$data['api_url']      = 'worker/feed/api_new';
    $this->load->view('worker_feed', $data);
}

public function post()
{
    $wid = $this->me();
    $this->form_validation->set_rules('body', 'Body', 'trim|required|max_length[5000]');
    if (!$this->form_validation->run()) return $this->index();

    $media = [];
    if (!empty($_FILES['photo']['name'])) {
        $config = [
            'upload_path'   => FCPATH.'uploads/posts/',
            'allowed_types' => 'jpg|jpeg|png|gif|webp',
            'max_size'      => 4096,
            'encrypt_name'  => true,
        ];
        if (!is_dir($config['upload_path'])) mkdir($config['upload_path'], 0755, true);
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('photo')) {
            $up   = $this->upload->data();  
            $path = 'uploads/posts/'.$up['file_name']; 
            $media[] = $path;
        } else {
            $this->session->set_flashdata('error', $this->upload->display_errors('',''));
        }
    }

    $payload = [
        'body'  => $this->input->post('body', true),
        'media' => $media,
    ];
    $this->feed->create_post($wid, $payload);
    $this->session->set_flashdata('post_ok', 1);
    redirect('worker/feed');
}

    public function delete($postId)
    {
        $wid = $this->me();
        $this->feed->delete_post($wid, (int)$postId);
        redirect('worker/feed');
    }

    public function apply()
    {
        $wid = $this->me();

        $this->form_validation->set_rules('project_id', 'Project', 'required|integer');
        $this->form_validation->set_rules('pitch', 'Pitch', 'trim|required|max_length[5000]');
        $this->form_validation->set_rules('expected_rate', 'Expected Rate', 'trim|decimal');
        $this->form_validation->set_rules('rate_unit', 'Rate Unit', 'trim|in_list[hour,day,project]');

        if (!$this->form_validation->run()) {
            return $this->index();
        }

        $pid = (int)$this->input->post('project_id', true);
        $payload = [
            'pitch'         => $this->input->post('pitch', true),
            'expected_rate' => $this->input->post('expected_rate', true),
            'rate_unit'     => $this->input->post('rate_unit', true)
        ];
        $this->feed->apply_to_project($wid, $pid, $payload);
        redirect('worker/feed');
    }

    public function withdraw($appId)
    {
        $wid = $this->me();
        $this->feed->withdraw_application($wid, (int)$appId);
        redirect('worker/feed');
    }

    public function api_new()
{
    $meId  = $this->me();
    $after = (int)$this->input->get('after', true);
    $scope = $this->input->get('scope', true) ?: 'all';

    if ($scope === 'mine') {
        $rows = $this->feed->get_feed_since_by_user($meId, $after, 20);
    } else {
        $rows = $this->feed->get_feed_since_global($after, 20);
    }

    $out = [];
    foreach ($rows as $r) {
        $avatar = function_exists('avatar_url') ? avatar_url($r->author_avatar ?? '') : ($r->author_avatar ?? '');
        $media  = [];
        if (!empty($r->media)) {
            $tmp = json_decode($r->media, true);
            if (is_array($tmp)) {
                foreach ($tmp as $m) {
                    $m = (string)$m;
                    $media[] = preg_match('#^https?://#i', $m) ? $m : base_url($m);
                }
            }
        }

        $ts  = strtotime(($r->created_at ?: 'now') . ' UTC'); 
        $iso = gmdate(DateTime::ATOM, $ts); 

        $out[] = [
            'id'           => (int)$r->id,
            'worker_id'    => (int)$r->worker_id,
            'first_name'   => (string)$r->first_name,
            'last_name'    => (string)$r->last_name,
            'created_at'   => $iso,  
            'created_ts'   => $ts * 1000,
            'body'         => (string)($r->body ?? ''),
            'author_photo' => $avatar ?: base_url('uploads/avatars/avatar.png'),
            'media'        => $media,
        ];
    }

    $this->output
         ->set_content_type('application/json')
         ->set_header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0')
         ->set_header('Pragma: no-cache')
         ->set_output(json_encode(['ok' => true, 'items' => $out]));
}

}
