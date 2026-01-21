<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SchoolAdmin Controller (CI3)
 * - Dashboard (stats + recent table)
 * - Workers list (filter/search)
 * - Create (emails the user their email + password)
 * - Edit / Delete / Resend Email (new temp)
 * - CSV bulk (emails each)
 */
class SchoolAdmin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url','form','security']);
        $this->load->library(['session','form_validation','upload']);
        $this->load->model('SchoolAdminModel');


        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    /** Dashboard */
    public function index()
    {
        $data = [
            'title'  => 'School Admin Dashboard',
            'stats'  => $this->SchoolAdminModel->stats(),
            'recent' => $this->SchoolAdminModel->recent_users(10),
        ];
        $this->load->view('school_admin_dashboard', $data);
    }

    /** Workers list */
    public function workers()
    {
        $role   = trim((string)$this->input->get('role'));
        $active = $this->input->get('is_active');
        $q      = trim((string)$this->input->get('q'));

        if ($active === null || $active === '') $active = 'ALL';

        $data = [
            'title'     => 'Workers',
            'q'         => $q,
            'role'      => $role ?: 'ALL',
            'is_active' => $active,
            'rows'      => $this->SchoolAdminModel->get_users($role, $active, $q),
            'roles'     => ['worker'],
        ];
        $this->load->view('school_admin_workers', $data);
    }

    /** Show create form */
    public function create()
    {
        $data = [
            'title' => 'Create Worker',
            'roles' => ['worker'],
        ];
        $this->load->view('school_admin_form', $data);
    }

    /** Handle create + email */
    public function store()
    {
        $this->form_validation->set_rules('first_name','First Name','required|trim');
        $this->form_validation->set_rules('last_name','Last Name','required|trim');
        $this->form_validation->set_rules('email','Email','required|valid_email|trim');


        $this->form_validation->set_rules('password','Password','required|min_length[8]');
        $this->form_validation->set_rules('password_confirm','Confirm Password','required|matches[password]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('danger', validation_errors());
            return redirect('school-admin/create');
        }


        $payload = [
            'email'       => strtolower(trim($this->input->post('email', TRUE))),
            'first_name'  => $this->input->post('first_name', TRUE),
            'last_name'   => $this->input->post('last_name', TRUE),
            'phone'       => $this->input->post('phone', TRUE),
            'role'        => 'worker',
            'is_active'   => $this->input->post('is_active', TRUE) === '0' ? 0 : 1,
            'status'      => $this->input->post('status', TRUE) ?: 'active',
            'visibility'  => $this->input->post('visibility', TRUE) ?: 'private',
            'password'    => $this->input->post('password', TRUE),
        ];

        $res = $this->SchoolAdminModel->create_user($payload);
        if (!$res['ok']) {
            $this->session->set_flashdata('danger', $res['message'] ?? 'Failed to create user.');
            return redirect('school-admin/create');
        }


        $passwordToSend = $res['temp_password'];

        $sent = $this->_send_welcome(
            $payload['email'],
            trim($payload['first_name'].' '.$payload['last_name']),
            $passwordToSend,
            'worker'
        );

        $this->session->set_flashdata(
            $sent ? 'success' : 'danger',
            $sent ? 'Worker created and email sent.' : 'Worker created, but email failed to send. Check logs shown above.'
        );
        return redirect('school-admin/workers');
    }

    /** Show edit form */
    public function edit($id)
    {
        $id = (int)$id;
        $user = $this->SchoolAdminModel->get_user_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('danger','User not found.');
            return redirect('school-admin/workers');
        }

        $data = [
            'title' => 'Edit Worker',
            'roles' => ['worker'],
            'user'  => $user
        ];
        $this->load->view('school_admin_form', $data);
    }

    /** Update */
    public function update($id)
    {
        $id = (int)$id;

        $this->form_validation->set_rules('first_name','First Name','required|trim');
        $this->form_validation->set_rules('last_name','Last Name','required|trim');
        $this->form_validation->set_rules('email','Email','required|valid_email|trim');

        $pw = trim((string)$this->input->post('password', TRUE));
        if ($pw !== '') {
            $this->form_validation->set_rules('password','Password','min_length[8]');
            $this->form_validation->set_rules('password_confirm','Confirm Password','matches[password]');
        }

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('danger', validation_errors());
            return redirect('school-admin/edit/'.$id);
        }

        $payload = [
            'email'       => strtolower(trim($this->input->post('email', TRUE))),
            'first_name'  => $this->input->post('first_name', TRUE),
            'last_name'   => $this->input->post('last_name', TRUE),
            'phone'       => $this->input->post('phone', TRUE),
            'role'        => 'worker',
            'is_active'   => $this->input->post('is_active', TRUE) === '0' ? 0 : 1,
            'status'      => $this->input->post('status', TRUE) ?: 'active',
            'visibility'  => $this->input->post('visibility', TRUE) ?: 'private',
        ];
        if ($pw !== '') $payload['password'] = $pw;

        $res = $this->SchoolAdminModel->update_user($id, $payload);
        if (!$res['ok']) {
            $this->session->set_flashdata('danger', $res['message'] ?? 'Failed to update user.');
            return redirect('school-admin/edit/'.$id);
        }

        $this->session->set_flashdata('success', 'Worker updated.');
        return redirect('school-admin/workers');
    }

    /** Delete (hard delete) */
    public function delete($id)
    {
        $id = (int)$id;
        $res = $this->SchoolAdminModel->delete_user($id);

        if ($res['ok']) {
            $this->session->set_flashdata('success','Worker deleted.');
        } else {
            log_message('error', 'Delete user failed (id='.$id.'): '.$res['message']);
            $this->session->set_flashdata('danger','Unable to delete worker. '.$res['message']);
        }
        return redirect('school-admin/workers');
    }

    /** Resend welcome email with NEW temp password (and update hash) */
    public function resend_email($id)
    {
        $id = (int)$id;
        $user = $this->SchoolAdminModel->get_user_by_id($id);
        if (!$user) {
            $this->session->set_flashdata('danger','User not found.');
            return redirect('school-admin/workers');
        }

        $set = $this->SchoolAdminModel->set_temp_password($id);
        if (!$set['ok']) {
            $this->session->set_flashdata('danger','Could not regenerate password. '.$set['message']);
            return redirect('school-admin/workers');
        }

        $sent = $this->_send_welcome(
            $user->email,
            trim(($user->first_name ?? '').' '.($user->last_name ?? '')),
            $set['temp_password'],
            'worker'
        );

        $this->session->set_flashdata($sent ? 'success' : 'danger',
            $sent ? 'Welcome email re-sent.' : 'Re-send failed. See logs shown above.');
        return redirect('school-admin/workers');
    }

    /** EXACTLY mirror Auth’s working email pattern */
    private function _send_welcome($to, $fullName, $plainPassword, $role)
    {

        $this->load->library('email');


        $this->email->from('trabawho@mati.gov.ph', 'TRABAWHO');
        $this->email->reply_to('no-reply@mati.gov.ph', 'TrabaWHO');
        $this->email->to($to);
        $this->email->subject('Your TrabaWHO account');
        $this->email->set_mailtype('html');
        $this->email->set_newline("\r\n");
        $this->email->set_crlf("\r\n");


        $bannerRel  = 'assets/images/trabawhotext.png';
        $logoRel    = 'assets/images/logo.png';
        $publicBase = 'https://trabawho.mati.gov.ph';

        $bannerPath = FCPATH . $bannerRel;
        $logoPath   = FCPATH . $logoRel;

        $bannerCid = $logoCid = null;
        if (is_file($bannerPath)) {
            $this->email->attach($bannerPath, 'inline', basename($bannerPath));
            $bannerCid = $this->email->attachment_cid($bannerPath);
        }
        if (is_file($logoPath)) {
            $this->email->attach($logoPath, 'inline', basename($logoPath));
            $logoCid = $this->email->attachment_cid($logoPath);
        }

        $bannerSrc = $bannerCid ? "cid:{$bannerCid}" : rtrim($publicBase,'/').'/'.ltrim($bannerRel,'/');
        $logoSrc   = $logoCid   ? "cid:{$logoCid}"   : rtrim($publicBase,'/').'/'.ltrim($logoRel,'/');


        if ($this->load->view('email_worker_account_created', [], TRUE) !== '') {
            $message = $this->load->view('email_worker_account_created', [
                'full_name' => trim($fullName) !== '' ? $fullName : 'User',
                'email'     => $to,
                'password'  => $plainPassword,
                'role'      => $role,
                'bannerSrc' => $bannerSrc,
                'logoSrc'   => $logoSrc,
            ], TRUE);
        } else {

            $message = "
                <!doctype html><html><body style='font-family:Arial,sans-serif'>
                    <h2>Welcome to TrabaWHO</h2>
                    <p>Hi ".htmlspecialchars($fullName ?: 'User').",</p>
                    <p>Your account has been created.</p>
                    <p><strong>Login Email:</strong> ".htmlspecialchars($to)."<br>
                       <strong>Password:</strong> ".htmlspecialchars($plainPassword)."<br>
                       <strong>Role:</strong> ".htmlspecialchars($role)."</p>
                    <p>Please change your password after first login.</p>
                </body></html>
            ";
        }

        $this->email->message($message);
        $ok = $this->email->send(false);

        if (!$ok) {

            $debug = $this->email->print_debugger(['headers']);
            log_message('error', 'Welcome email failed for '.$to.': '.$debug);
            $this->session->set_flashdata('danger',
                'Email failed to send. <br><pre style="white-space:pre-wrap">'.
                htmlspecialchars($debug, ENT_QUOTES, 'UTF-8').'</pre>'
            );
        }


        $this->email->clear(TRUE);
        return $ok;
    }
}
