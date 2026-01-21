<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Services extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url']);
        $this->load->model('Analytics_model', 'analytics');
    }

    public function mix()
    {
        $uid  = (int)($this->session->userdata('user_id') ?: 0);
        $role = strtolower((string)$this->session->userdata('role'));

        if ($uid <= 0 || $role !== 'worker') {
            return $this->_json(['ok'=>false,'msg'=>'Unauthorized'], 401);
        }

        $status = $this->input->get('status', true);
        if ($status === 'any') $status = null;

        $data = $this->analytics->get_worker_service_mix($uid, $status);
        return $this->_json(['ok'=>true, 'items'=>$data]);
    }

    private function _json(array $payload, int $status = 200)
    {
        $this->output->set_status_header($status)
            ->set_content_type('application/json','utf-8')
            ->set_output(json_encode($payload, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES))->_display();
        exit;
    }
}
