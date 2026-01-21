<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class WorkerImport_model extends CI_Model
{
    private $map = [
        'email'            => 'email',
        'first_name'       => 'first_name',
        'last_name'        => 'last_name',
        'phone'            => 'phone',
        'province'         => 'province',
        'city'             => 'city',
        'brgy'             => 'brgy',
        'bio'              => 'bio',
        'headline'         => 'headline',
        'years_experience' => 'years_experience',
        'skills'           => 'skills',
        'tesda_cert_no'    => 'tesda_cert_no',
        'tesda_expiry'     => 'tesda_expiry'
    ];

    public function build_preview(array $rows)
    {
        $errors = []; $dupes = []; $clean = [];
        $valid_count = 0; $skip_count = 0;

        $emails   = $this->db->select('email')->from('users')->get()->result_array();
        $emailSet = array_flip(array_map('strtolower', array_column($emails,'email')));

        foreach ($rows as $i => $r) {
            $line = $i + 2;
            $item = [];
            foreach ($this->map as $k => $col) {
                $item[$col] = isset($r[$k]) ? trim((string)$r[$k]) : null;
            }

            if (!empty($item['tesda_expiry'])) {
                $ts = strtotime($item['tesda_expiry']);
                $item['tesda_expiry'] = $ts ? date('Y-m-d', $ts) : null;
            }

            if (empty($item['email'])) {
                $errors[] = "Row $line: missing email (row will be skipped).";
                $skip_count++;
            } else {
                $valid_count++;
                if (isset($emailSet[strtolower($item['email'])])) {
                    $dupes[] = "Row $line: email already exists (".$item['email'].") — will update.";
                }
            }

            $clean[] = $item;
        }

        return [
            'rows'        => $clean,
            'errors'      => $errors,
            'dupes'       => $dupes,
            'valid_count' => $valid_count,
            'skip_count'  => $skip_count
        ];
    }

    public function import_rows(array $rows)
    {
        $this->db->trans_start();

        $inserted = 0; $updated = 0;
        $created_users = 0; $skill_links = 0;
        $skipped_no_email = 0;
        $notes = [];

        foreach ($rows as $r) {
            $rec = [];
            foreach ($this->map as $k=>$col) {
                $rec[$col] = isset($r[$k]) ? trim((string)$r[$k]) : null;
            }

            if (empty($rec['email'])) { $skipped_no_email++; continue; }

            if (!empty($rec['tesda_expiry'])) {
                $ts = strtotime($rec['tesda_expiry']);
                $rec['tesda_expiry'] = $ts ? date('Y-m-d', $ts) : null;
            }

            $user = $this->db->get_where('users', ['email'=>$rec['email']])->row();
         if (!$user) {
    $ln   = isset($rec['last_name']) ? trim((string)$rec['last_name']) : '';
    $base = strtolower(preg_replace('/[^a-z0-9]/i', '', $ln)); 

    if ($base === '') {
        $raw    = bin2hex(random_bytes(4));
        $source = 'random (no/invalid last_name)';
    } else {
        $raw    = $base;
        $source = 'last name';
    }

    $hash = password_hash($raw, PASSWORD_BCRYPT);

    $this->db->insert('users', [
        'email'         => $rec['email'],
        'password_hash' => $hash,
        'role'          => 'worker',
        'is_active'     => 1,
        'first_name'    => $rec['first_name'] ?? '',
        'last_name'     => $rec['last_name']  ?? '',
        'phone'         => $rec['phone']      ?? '',
        'updated_at'    => date('Y-m-d H:i:s')
    ]);
    $user_id = $this->db->insert_id();
    $created_users++;
    $notes[] = 'Created '.$rec['email'].' with temp password ('.$source.'): '.$raw;
} else {

                $user_id = $user->id;
                $user_upd = ['updated_at'=>date('Y-m-d H:i:s')];
                if (!empty($rec['first_name'])) $user_upd['first_name'] = $rec['first_name'];
                if (!empty($rec['last_name']))  $user_upd['last_name']  = $rec['last_name'];
                if (!empty($rec['phone']))      $user_upd['phone']      = $rec['phone'];
                if (count($user_upd) > 1) {
                    $this->db->where('id',$user_id)->update('users',$user_upd);
                }
            }

            $exists = $this->db->get_where('worker_profile',['workerID'=>$user_id])->row();
            $wp_upd = ['updated_at'=>date('Y-m-d H:i:s')];

            if (!empty($rec['province']))         $wp_upd['province']          = $rec['province'];
            if (!empty($rec['city']))             $wp_upd['city']              = $rec['city'];
            if (!empty($rec['brgy']))             $wp_upd['brgy']              = $rec['brgy'];
            if (!empty($rec['phone']))            $wp_upd['phoneNo']           = $rec['phone'];
            if (!empty($rec['bio']))              $wp_upd['bio']               = $rec['bio'];
            if (!empty($rec['headline']))         $wp_upd['headline']          = $rec['headline'];
            if ($rec['years_experience'] !== null && $rec['years_experience'] !== '')
                                                  $wp_upd['years_experience']  = (int)$rec['years_experience'];
            if (!empty($rec['tesda_cert_no']))    $wp_upd['tesda_cert_no']     = $rec['tesda_cert_no'];
            if (!empty($rec['tesda_expiry']))     $wp_upd['tesda_expiry']      = $rec['tesda_expiry'];

            if ($exists) {
                if (count($wp_upd) > 1) {
                    $this->db->where('workerID',$user_id)->update('worker_profile',$wp_upd);
                }
                $updated++;
            } else {
                $wp_upd['workerID']   = $user_id;
                $wp_upd['created_at'] = date('Y-m-d');
                $this->db->insert('worker_profile',$wp_upd);
                $inserted++;
            }

            if (!empty($rec['skills'])) {
                $titles = array_filter(array_map('trim', explode(';',$rec['skills'])));
                if ($titles) {
                    $found = $this->db->select('skillID, Title')->from('skills')
                                      ->where_in('Title', $titles)->get()->result();
                    $title_to_id=[]; foreach($found as $s){ $title_to_id[strtolower($s->Title)]=$s->skillID; }

                    foreach ($titles as $t) {
                        $sid = $title_to_id[strtolower($t)] ?? null;
                        if (!$sid) {
                            $this->db->insert('skills',['Title'=>$t,'Description'=>'']);
                            $sid=$this->db->insert_id();
                        }
                        $exists = $this->db->get_where('worker_skills',[
                            'workerID'=>$user_id,'skillsID'=>$sid
                        ])->row();
                        if (!$exists) {
                            $this->db->insert('worker_skills',[
                                'workerID'=>$user_id,'skillsID'=>$sid,
                                'minRate'=>0,'maxRate'=>0,'is_active'=>1,'attachment'=>''
                            ]);
                            $skill_links++;
                        }
                    }
                }
            }
        }

        $this->db->trans_complete();
        if (!$this->db->trans_status()) {
            return ['ok'=>false,'message'=>'Transaction failed.'];
        }

        return [
            'ok'                => true,
            'inserted'          => $inserted,
            'updated'           => $updated,
            'created_users'     => $created_users,
            'skill_links'       => $skill_links,
            'skipped_no_email'  => $skipped_no_email,
            'notes'             => $notes
        ];
    }
}
