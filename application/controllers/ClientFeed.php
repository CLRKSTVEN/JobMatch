<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ClientFeed extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('role') !== 'client') {
            show_error('Access Denied: Clients only.', 403);
        }
        $this->load->model('WorkerFeedModel', 'feed');
        $this->load->helper(['url','form']);
        $this->load->library('form_validation');
        
    }

    private function me()
    {
        foreach (['id','user_id','users_id','uid','userId'] as $k) {
            $v = (int)$this->session->userdata($k);
            if ($v > 0) return $v;
        }
        $email = (string)$this->session->userdata('email');
        if ($email !== '') {
            $row = $this->db->select('id')->from('users')->where('email',$email)->get()->row();
            if ($row) return (int)$row->id;
        }
        show_error('Not logged in correctly. User id missing in session.', 401);
    }

public function index()
{
    $uid   = $this->me();
    $scope = $this->input->get('scope', true) ?: 'all';

    $data['posts']         = ($scope === 'mine') ? $this->feed->get_feed_by_user($uid, 50)
                                                 : $this->feed->get_feed_global(50);
    $data['scope']         = $scope;
    $data['post_action']   = 'client/feed/post';
    $data['delete_action'] = 'client/feed/delete';   // ← add this
    $data['me_id']         = $uid;                   // ← and this
    $data['page_title']    = 'Timeline · Client';
$data['api_url']      = 'client/feed/api_new'; 
    $this->load->view('worker_feed', $data);
}

    public function post()
    {
        $uid = $this->me();
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
                $up = $this->upload->data();
                $media[] = 'uploads/posts/'.$up['file_name'];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors('',''));
            }
        }

        $this->feed->create_post($uid, [
            'body'  => $this->input->post('body', true),
            'media' => $media
        ]);
        $this->session->set_flashdata('post_ok', 1);
        redirect('client/feed?scope=all');
    }
public function delete($postId)
{
    $uid = $this->me();
    $this->feed->delete_post($uid, (int)$postId);
    redirect('client/feed?scope=all');
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
