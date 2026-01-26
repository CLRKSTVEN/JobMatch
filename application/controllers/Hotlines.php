<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hotlines extends CI_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Hotline_model','Hotlines');
        $this->load->helper(['url','html']);
    }

    public function index(){
        // Decide audience by current role; default to 'all'
        $role = $this->session->userdata('role') ?: 'all';
        $aud = in_array($role, ['worker','client','admin'], true) ? $role : 'all';

        $data['page_title'] = 'Hotline Numbers';
        $data['rows'] = $this->Hotlines->by_audience_for_public($aud);
        $data['audience'] = $aud;
        $this->load->view('hotlines_index', $data);
    }
}
