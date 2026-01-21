<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portfolio extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(['url','form','html']);
        $this->load->library(['session','form_validation','upload']);
        $this->load->model('WorkerProfile_model','wp');
        $this->load->model('Reviews_model','reviews');
        if (!$this->session->userdata('logged_in')) { redirect('auth/login'); }
        if ($this->session->userdata('role') !== 'worker') { show_error('Unauthorized', 403); }
    }

  public function index()
{
    $uid   = (int)$this->session->userdata('user_id');
    $stats = $this->reviews->stats($uid);

    $profile = $this->wp->get($uid);

    $byPath  = [];
    $sources = [];

    if (!empty($profile->cert_files)) {
        $sources[] = $profile->cert_files;
    }
    if ($this->db->field_exists('certificates', 'worker_profile') && !empty($profile->certificates)) {
        $sources[] = $profile->certificates;
    }

    foreach ($sources as $src) {
        $arr = is_string($src) ? json_decode($src, true) : (array)$src;
        if (!is_array($arr)) continue;

        foreach ($arr as $row) {
            if (is_string($row) && $row !== '') {
                $p = $row;
                $t = pathinfo($p, PATHINFO_FILENAME);
            } elseif (is_array($row) && !empty($row['path'])) {
                $p = (string)$row['path'];
                $t = trim((string)($row['title'] ?? ''));
                if ($t === '' && $p !== '') $t = pathinfo($p, PATHINFO_FILENAME);
            } else {
                continue;
            }

            if ($p !== '') {
                $byPath[$p] = ['path' => $p, 'title' => $t];
            }
        }
    }

    $certs = array_values($byPath);

    $data = [
        'mode'           => 'list',
        'page_title'     => 'My Portfolio',
        'items'          => $this->wp->list_portfolio($uid, true),
        'times_hired'    => $this->reviews->times_hired($uid),
        'reviews'        => $stats,
        'latest_reviews' => $this->reviews->latest($uid, 3),
        'profile'        => $profile,
        'certs'          => $certs, 
    ];

    $this->load->view('portfolio', $data);
}

    public function create()
    {
        $uid = (int)$this->session->userdata('user_id');
        $data = [
            'mode'       => 'form',   
            'page_title' => 'Add Portfolio Item',
            'profile'    => $this->wp->get($uid),
        ];
        $this->load->view('portfolio', $data);
    }

    public function store()
    {
        $uid = (int)$this->session->userdata('user_id');

        $this->form_validation->set_rules('title','Title','trim|required|max_length[150]');
        $this->form_validation->set_rules('description','Description','trim|max_length[5000]');
        $this->form_validation->set_rules('visibility','Visibility','in_list[public,private]');

        if (!$this->form_validation->run()) {
            $this->session->set_flashdata('error', validation_errors('', ''));
            redirect('portfolio/create'); return;
        }

        $savedFiles = [];
        $files = $_FILES['files'] ?? null;
        if ($files && !empty($files['name'][0])) {
            $dir = FCPATH.'uploads/portfolio';
            if (!is_dir($dir)) @mkdir($dir, 0777, true);
            $count = count($files['name']);
            for ($i=0; $i<$count; $i++) {
                if (empty($files['name'][$i])) continue;
                $_FILES['one_file'] = [
                    'name'     => $files['name'][$i],
                    'type'     => $files['type'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error'    => $files['error'][$i],
                    'size'     => $files['size'][$i],
                ];
              $cfg = [
    'upload_path'      => $dir,
    'allowed_types'    => 'pdf|jpg|jpeg|png|webp',
'max_size'      => 2048,
    'file_ext_tolower' => TRUE,
    'remove_spaces'    => TRUE,
    'encrypt_name'     => TRUE,
    'detect_mime'      => TRUE,
];
$this->upload->initialize($cfg);

if ($this->upload->do_upload('one_file')) {
    $up    = $this->upload->data();
    $path  = $up['full_path'];
    $ext   = $up['file_ext'];

    if (!validate_uploaded_file_signature($path, $ext)) {
        @unlink($path);
        $this->session->set_flashdata('error', 'Invalid or unsafe file: '.$files['name'][$i]);
        redirect('portfolio/create'); return;
    }

    $imgExts = ['.jpg','.jpeg','.png','.gif','.webp'];
    if (in_array(strtolower($ext), $imgExts, true) && function_exists('safe_image_reencode')) {
        if (!safe_image_reencode($path, $ext)) {
            @unlink($path);
            $this->session->set_flashdata('error', 'Failed to sanitize image: '.$files['name'][$i]);
            redirect('portfolio/create'); return;
        }
    }

    $savedFiles[] = 'uploads/portfolio/'.$up['file_name'];
} else {
    $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
    redirect('portfolio/create'); return;
}

            }
        }

        $item = [
            'title'       => $this->input->post('title', true),
            'description' => $this->input->post('description', true),
            'files'       => $savedFiles,
            'visibility'  => $this->input->post('visibility', true) ?: 'public',
        ];

        if ($this->wp->add_portfolio_item($uid, $item)) {
            $this->session->set_flashdata('success', 'Portfolio item posted.');
        } else {
            $this->session->set_flashdata('error', 'Could not save portfolio item.');
        }
        redirect('portfolio');
    }
   public function certificates()
{
    $uid = (int)$this->session->userdata('user_id');
    $profile = $this->wp->get($uid);

    $byPath  = [];
    $sources = [];

    if (!empty($profile->cert_files)) {
        $sources[] = $profile->cert_files;
    }
    if ($this->db->field_exists('certificates', 'worker_profile') && !empty($profile->certificates)) {
        $sources[] = $profile->certificates;
    }

    foreach ($sources as $src) {
        $arr = is_string($src) ? json_decode($src, true) : (array)$src;
        if (!is_array($arr)) continue;

        foreach ($arr as $row) {
            if (is_string($row) && $row !== '') {
                $p = $row;
                $t = pathinfo($p, PATHINFO_FILENAME);
            } elseif (is_array($row) && !empty($row['path'])) {
                $p = (string)$row['path'];
                $t = trim((string)($row['title'] ?? ''));
                if ($t === '' && $p !== '') {
                    $t = pathinfo($p, PATHINFO_FILENAME);
                }
            } else {
                continue;
            }

            if ($p !== '') {
                $byPath[$p] = ['path' => $p, 'title' => $t];
            }
        }
    }

    $certs = array_values($byPath);

    $data = [
        'mode'       => 'cert-form',
        'page_title' => 'Certificates',
        'profile'    => $profile,
        'certs'      => $certs, 
    ];

    $this->load->view('portfolio', $data);
}


public function cert_store()
{
    $uid = (int)$this->session->userdata('user_id');
    $profile = $this->wp->get($uid);

    $hasLegacyCol = $this->db->field_exists('certificates', 'worker_profile');

    $existing = [];
    $sources = [];
    if (!empty($profile->cert_files))   $sources[] = $profile->cert_files;
    if ($hasLegacyCol && !empty($profile->certificates)) $sources[] = $profile->certificates;

    foreach ($sources as $src) {
        $arr = is_string($src) ? json_decode($src, true) : (array)$src;
        if (!is_array($arr)) continue;
        foreach ($arr as $row) {
            if (is_string($row) && $row !== '') {
                $existing[] = ['path'=>$row, 'title'=>pathinfo($row, PATHINFO_FILENAME)];
            } elseif (is_array($row) && !empty($row['path'])) {
                $p = (string)$row['path'];
                $t = trim((string)($row['title'] ?? ''));
                if ($t === '') $t = pathinfo($p, PATHINFO_FILENAME);
                $existing[] = ['path'=>$p, 'title'=>$t];
            }
        }
    }

    $saved  = [];
    $files  = $_FILES['cert_files'] ?? null;
    $titles = $this->input->post('cert_titles', true);
    if (!is_array($titles)) $titles = [];

    if ($files && !empty($files['name'][0])) {
        $dir = FCPATH.'uploads/certificates';
        if (!is_dir($dir)) @mkdir($dir, 0777, true);

        $count = count($files['name']);
        for ($i=0; $i<$count; $i++) {
            if (empty($files['name'][$i])) continue;

            $_FILES['one_cert'] = [
                'name'     => $files['name'][$i],
                'type'     => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error'    => $files['error'][$i],
                'size'     => $files['size'][$i],
            ];

            $cfg = [
                'upload_path'      => $dir,
                'allowed_types'    => 'pdf|jpg|jpeg|png|webp',
                'max_size'         => 2048,
                'file_ext_tolower' => TRUE,
                'remove_spaces'    => TRUE,
                'encrypt_name'     => TRUE,
                'detect_mime'      => TRUE,
            ];
            $this->upload->initialize($cfg);

            if ($this->upload->do_upload('one_cert')) {
                $up    = $this->upload->data();
                $path  = $up['full_path'];
                $ext   = $up['file_ext'];

                if (!validate_uploaded_file_signature($path, $ext)) {
                    @unlink($path);
                    $this->session->set_flashdata('error', 'Invalid or unsafe certificate file: '.$files['name'][$i]);
                    redirect('portfolio/certificates'); return;
                }

                $imgExts = ['.jpg','.jpeg','.png','.gif','.webp'];
                if (in_array(strtolower($ext), $imgExts, true) && function_exists('safe_image_reencode')) {
                    if (!safe_image_reencode($path, $ext)) {
                        @unlink($path);
                        $this->session->set_flashdata('error', 'Failed to sanitize image: '.$files['name'][$i]);
                        redirect('portfolio/certificates'); return;
                    }
                }

                $postedTitle = isset($titles[$i]) ? trim((string)$titles[$i]) : '';
                if ($postedTitle === '') $postedTitle = pathinfo($files['name'][$i], PATHINFO_FILENAME);
                $postedTitle = strip_tags($postedTitle);
                if (mb_strlen($postedTitle) > 150) $postedTitle = mb_substr($postedTitle, 0, 150);

                $saved[] = [
                    'path'  => 'uploads/certificates/'.$up['file_name'],
                    'title' => $postedTitle,
                ];
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors('', ''));
                redirect('portfolio/certificates'); return;
            }
        }
    } else {
        if (empty($existing)) {
            $this->session->set_flashdata('error', 'Please choose at least one PDF or image.');
            redirect('portfolio/certificates'); return;
        }
    }

    $byPath = [];
    foreach ($existing as $e) {
        if (!empty($e['path'])) {
            $byPath[$e['path']] = ['path'=>$e['path'], 'title'=>($e['title'] ?? pathinfo($e['path'], PATHINFO_FILENAME))];
        }
    }
    foreach ($saved as $s) {
        $byPath[$s['path']] = ['path'=>$s['path'], 'title'=>($s['title'] ?? pathinfo($s['path'], PATHINFO_FILENAME))];
    }
    $all = array_values($byPath);
    if (empty($all)) {
        $this->session->set_flashdata('error', 'No certificates to save.');
        redirect('portfolio/certificates'); return;
    }

    $json = json_encode($all, JSON_UNESCAPED_SLASHES);
    $payload = [
        'cert_files' => $json,
        'updated_at' => date('Y-m-d H:i:s'),
    ];
    if ($hasLegacyCol) {
        $payload['certificates'] = $json;
    }

    $exists = $this->db->select('workerID')->get_where('worker_profile', ['workerID'=>$uid])->row();
    if ($exists) {
        $ok = $this->db->where('workerID',$uid)->update('worker_profile', $payload);
    } else {
        $payload['workerID']   = $uid;
        $payload['created_at'] = date('Y-m-d H:i:s');
        $ok = $this->db->insert('worker_profile', $payload);
    }

    $this->session->set_flashdata($ok ? 'success' : 'error', $ok ? 'Certificates uploaded.' : 'Could not save certificates.');
    redirect('portfolio');
}


}
