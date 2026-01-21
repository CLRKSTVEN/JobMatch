<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdminReports extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        // TODO: add your admin auth/ACL here
        $this->load->model('AdminReport_model', 'R');
        $this->load->helper(['url','html']);
    }
public function index() {
    $this->load->model('AdminReport_model', 'R');

    // KPI tiles
    $data['total_jobs']            = $this->R->totalJobs();
    $data['jobs_with_apps']        = $this->R->jobsWithApps();
    $data['total_client_projects'] = $this->R->totalClientProjects();
    $data['projects_with_apps']    = $this->R->projectsWithApps();

    // Tables
    $data['jobs_all']      = $this->R->allJobsWithApplicantTotals();
    $data['jobs_applied']  = $this->R->jobsWithApplicantsOnly();
    $data['clients_sum']   = $this->R->clientProjectsSummary();

    $clientIDs = array_map(function($r){ return (int)$r['clientID']; }, $data['clients_sum']);
    $data['client_labels'] = $this->R->clientLabelMap($clientIDs);

  $data['printMode'] = (bool)$this->input->get('print');
  if ($data['printMode']) {
    // include names for print
    $data['jobApplicants'] = $this->R->applicantsByJobForPrint();
    $this->load->view('admin/reports_dashboard_print_min', $data);
    return; // IMPORTANT: prevent the normal view from loading
  }

  $data['page_title'] = 'Admin Reports — Jobs & Projects';
  $this->load->view('admin/reports_dashboard', $data);
}


    public function client($id = null) {
    $clientID = (int)$id;
    if ($clientID <= 0) show_404();

    $data['page_title'] = 'Client Projects — Report';
    $data['clientID']   = $clientID;
    $data['projects']   = $this->R->projectsByClient($clientID);

    $labMap = $this->R->clientLabelMap([$clientID]);
    $data['client_label'] = $labMap[$clientID] ?? ('Client #'.$clientID);

  $data['printMode'] = (bool)$this->input->get('print');
  if ($data['printMode']) {
    $data['projectApplicants'] = $this->R->applicantsByClientProjectForPrint($clientID);
    $this->load->view('admin/reports_client_projects_print_min', $data);
    return; // IMPORTANT
  }

  $this->load->view('admin/reports_client_projects', $data);
}

}
