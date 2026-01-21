<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TesdaDashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('session');

        $role  = strtolower((string)$this->session->userdata('role'));
        $level = strtolower((string)$this->session->userdata('level'));
        if (!in_array($role, ['tesda_admin','admin'], true) && $level !== 'tesda') {
            show_error('Forbidden', 403);
        }

        $this->load->model('Tesda_model'); // <- important
    }

public function index()
{
    $this->load->model('Tesda_model');
    $stats = $this->Tesda_model->metrics();

    $this->load->view('dashboard_tesda', [
        'page_title' => 'TESDA Dashboard',
        'stats'      => $stats,
    ]);
}
}
