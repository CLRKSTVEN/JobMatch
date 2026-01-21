<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Landing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->database();
        $this->load->model('User_model', 'user_model');
    }

    public function index(): void
    {
        $isLoggedIn = filter_var(
            $this->session->userdata('logged_in'),
            FILTER_VALIDATE_BOOLEAN,
            FILTER_NULL_ON_FAILURE
        ) === true;

        $role = strtolower((string)$this->session->userdata('role'));

        $quickStats = [
            'workers'         => 0,
            'employers'       => 0,
            'open_jobs'       => 0,
            'active_hotlines' => 0,
        ];

        try {
            if (isset($this->user_model)) {
                $quickStats['workers'] = (int) $this->user_model->count_workers();
                $quickStats['employers'] = (int) $this->user_model->count_employers();
            }

            if ($this->db->table_exists('jobs')) {
                $quickStats['open_jobs'] = (int) $this->db
                    ->where('status', 'open')
                    ->where('visibility', 'public')
                    ->count_all_results('jobs');
                $this->db->reset_query();
            }

            if ($this->db->table_exists('hotline_numbers')) {
                $quickStats['active_hotlines'] = (int) $this->db
                    ->where('is_active', 1)
                    ->count_all_results('hotline_numbers');
                $this->db->reset_query();
            }
        } catch (\Throwable $e) {
            log_message('error', 'Landing::index quick stats error: '.$e->getMessage());
        }

        $this->load->view('landing', [
            'is_logged_in' => $isLoggedIn,
            'role'         => $role,
            'first_name'   => (string)$this->session->userdata('first_name'),
            'quick_stats'  => $quickStats,
        ]);
    }
}
