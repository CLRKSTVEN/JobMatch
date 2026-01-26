<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Visibility extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url','security']);
        $this->load->library(['session']);
        $this->load->database();

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login?next='.rawurlencode(current_url()));
        }
    }

    private function _uid(): int
    {
        foreach (['id','user_id','uid','account_id'] as $k) {
            $v = $this->session->userdata($k);
            if (is_numeric($v)) return (int)$v;
        }
        return 0;
    }

    public function index()
    {
        $me = $this->_uid();
        if ($me <= 0) show_error('Unauthorized', 401);

        $u = $this->db->select('id, role, visibility, is_active')
                      ->from('users')->where('id',$me)->limit(1)->get()->row();
        $data = [
            'page_title' => 'Privacy / Visibility',
            'me'         => $u
        ];
        $this->load->view('privacy_index', $data);
    }

    // POST: visibility=public|private
    public function set()
    {
        $out = function($ok,$msg='OK',$extra=[]){
            $this->output->set_content_type('application/json')
                ->set_output(json_encode(array_merge(['ok'=>$ok,'message'=>$msg],$extra)));
        };

        if (!$this->session->userdata('logged_in')) return $out(false,'Unauthorized');
        $me = $this->_uid();
        if ($me <= 0) return $out(false,'Unauthorized');

        $vis = strtolower(trim((string)$this->input->post('visibility', true)));
        if (!in_array($vis, ['public','private'], true)) return $out(false,'Invalid value');

        $ok = $this->db->where('id',$me)->update('users', [
            'visibility' => $vis,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        return $out((bool)$ok, $ok ? 'Updated' : 'Failed');
    }
}
