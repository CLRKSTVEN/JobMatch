<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Hires extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url','security']);
        $this->load->library(['session']);
        $this->load->model('Personnel_model', 'personnelm');
    }

  
    public function accept()
    {
        if (!$this->session->userdata('logged_in')) {
            return redirect('auth/login');
        }

        $role = (string)$this->session->userdata('role');
        $user_id = (int)$this->session->userdata('user_id');

        $client_id  = (int)$this->input->get_post('client_id', true);
        $worker_id  = (int)$this->input->get_post('worker_id', true);
        $project_id = (int)$this->input->get_post('project_id', true) ?: null;
        $rate       = $this->input->get_post('rate', true);
        $rate       = ($rate !== null && $rate !== '') ? (float)$rate : null;
        $rate_unit  = $this->input->get_post('rate_unit', true) ?: null;

        if ($client_id <= 0 || $worker_id <= 0) {
            $this->session->set_flashdata('error', 'Invalid hire parameters.');
            return redirect('search'); 
        }

        if ($role === 'worker' && $user_id !== $worker_id) {
            $this->session->set_flashdata('error', 'You are not allowed to accept this hire.');
            return redirect('dashboard/worker');
        }

        $id = $this->personnelm->ensure_hired($client_id, $worker_id, $project_id, $rate, $rate_unit);
        if ($id > 0) {
            $this->session->set_flashdata('success', 'Hire accepted. Added to your Personnel • Hired.');
        } else {
            $this->session->set_flashdata('error', 'Could not accept hire.');
        }

        if ($role === 'client') {
            return redirect('personnel/hired');
        }
        return redirect('dashboard/worker');
    }
}
