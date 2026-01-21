<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Personnel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url','html','security']);
        $this->load->library(['session']);
        $this->load->model('Personnel_model', 'personnelm');
    }

    private function _must_be_client()
    {
        if (!$this->session->userdata('logged_in')) return redirect('auth/login');
        if ((string)$this->session->userdata('role') !== 'client') {
            $this->session->set_flashdata('error', 'Clients only.');
            return redirect('dashboard/'.($this->session->userdata('role') ?: 'user'));
        }
    }

   public function hired()
{
    if (!$this->session->userdata('logged_in')) return redirect('auth/login');

    $uid  = (int)$this->session->userdata('user_id');
    $role = (string)$this->session->userdata('role');

    if ($role !== 'client') {
        $this->session->set_flashdata('error', 'Clients only.');
        return redirect('dashboard/'.$role);
    }

    $this->load->model('Personnel_model','personnelm');
    $items = $this->personnelm->hired_by_client($uid, 100, 0);

    $this->load->view('personnel_hired', [
        'page_title' => 'Hired Personnel',
        'items'      => $items
    ]);
}

}
