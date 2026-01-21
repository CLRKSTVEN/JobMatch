<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->helper(['url','html']);
        $this->load->database();

        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') !== 'client') {
            redirect('auth/login');
        }

        $this->load->model('Payments_model', 'pay');
    }

    public function index()
    {
        $uid   = (int) $this->session->userdata('user_id');
        $page  = max(1, (int)$this->input->get('page'));
        $limit = 12;
        $offset= ($page - 1) * $limit;

        $data  = $this->pay->list_for_client($uid, $limit, $offset);

        $total_pages = (int) ceil($data['total_rows'] / $limit);
        $data['page_title']  = 'Payments (Spend History)';
        $data['page']        = $page;
        $data['total_pages'] = $total_pages;
        $data['prev_url']    = $page > 1 ? site_url('payments?page='.($page-1)) : null;
        $data['next_url']    = $page < $total_pages ? site_url('payments?page='.($page+1)) : null;

        $this->load->view('payments_client', $data);
    }
}
