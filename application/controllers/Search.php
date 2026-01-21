<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Search extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url','html','security']);
        $this->load->library(['session','pagination']);
        $this->load->model('Search_model','searchm');
    }

public function index()
{
    if (!$this->session->userdata('logged_in')) {
        return redirect('auth/login');
    }

    $uid  = (int)$this->session->userdata('user_id');
    $role = (string)$this->session->userdata('role');

    $profile = null;
    if ($role === 'client') {
        $this->load->model('ClientProfile_model','cp');
        $profile = $this->cp->get($uid);
    } elseif ($role === 'worker') {
        $this->load->model('WorkerProfile_model','wp');
        $profile = $this->wp->get($uid);
    }

    $q     = trim((string)$this->input->get('q', true));
    $page  = max(1, (int)$this->input->get('page'));
    $pp    = 12;
    $off   = ($page-1) * $pp;
    $terms = $this->_terms($q);

    $mode = ($role === 'worker') ? 'clients' : 'workers';

    if ($q === '' || empty($terms)) {
        $data = [
            'page_title' => 'Search',
            'q'          => $q,
            'terms'      => [],
            'workers'    => [],
            'pagination' => '',
            'profile'    => $profile,
            'mode'       => $mode,
        ];
        return $this->load->view('result', $data);
    }

    if ($mode === 'clients') {
        $result = $this->searchm->search_clients_by_terms($terms, $pp, $off, $uid);
    } else {
        $result = $this->searchm->search_workers_by_terms($terms, $pp, $off);
    }

    $this->load->library('pagination');
    $config = [
        'base_url'              => site_url('search'),
        'total_rows'            => $result['total'],
        'per_page'              => $pp,
        'page_query_string'     => true,
        'query_string_segment'  => 'page',
        'use_page_numbers'      => true,
        'reuse_query_string'    => true,

        'full_tag_open'         => '<nav aria-label="Search pages"><ul class="pagination pagination-sm justify-content-center mb-0">',
        'full_tag_close'        => '</ul></nav>',

        'first_link'            => 'First',
        'first_tag_open'        => '<li class="page-item">',
        'first_tag_close'       => '</li>',

        'last_link'             => 'Last',
        'last_tag_open'         => '<li class="page-item">',
        'last_tag_close'        => '</li>',

        'next_link'             => '&raquo;',
        'next_tag_open'         => '<li class="page-item">',
        'next_tag_close'        => '</li>',

        'prev_link'             => '&laquo;',
        'prev_tag_open'         => '<li class="page-item">',
        'prev_tag_close'        => '</li>',

        'cur_tag_open'          => '<li class="page-item active" aria-current="page"><span class="page-link">',
        'cur_tag_close'         => '</span></li>',

        'num_tag_open'          => '<li class="page-item">',
        'num_tag_close'         => '</li>',

        'attributes'            => ['class' => 'page-link'],
    ];
    $this->pagination->initialize($config);

    $shown = count($result['rows']);

    $data = [
        'page_title' => 'Search',
        'q'          => $q,
        'terms'      => $terms,
        'workers'    => $result['rows'],
        'pagination' => $this->pagination->create_links(),
        'profile'    => $profile,
        'mode'       => $mode,
        'shown'      => $shown,
        'total'      => $result['total'],
    ];
    $this->load->view('result', $data);
}



    private function _terms($q)
    {
        if ($q === '') return [];
        $raw = preg_split('/[,\s]+/u', mb_strtolower($q));
        $out = array_values(array_unique(array_filter($raw, function($t){ return $t !== ''; })));
        return array_slice($out, 0, 10);
    }
}
