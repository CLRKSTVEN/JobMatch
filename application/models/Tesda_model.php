<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tesda_model extends CI_Model
{
   public function metrics()
    {
        $today = date('Y-m-d');
        $in30  = date('Y-m-d', strtotime('+30 days'));

        // 1) Skilled workers (active)
        $workers_total = (int)$this->db->from('users')
            ->where('role','worker')
            ->where('is_active', 1)
            ->group_start()->where('status','active')->or_where('status IS NULL', null, false)->group_end()
            ->count_all_results();

        // 2) New (last 7 days)
        $workers_new_7d = (int)$this->db->from('users')
            ->where('role','worker')
            ->where('created_at >=', date('Y-m-d H:i:s', strtotime('-7 days')))
            ->count_all_results();

        // 3) TESDA Certified (has ANY TESDA doc, expired or not)
        $workers_certified = (int)$this->db->select('COUNT(DISTINCT d.user_id) AS c', false)
            ->from('documents d')
            ->join('doc_type t', 't.id = d.doc_type_id', 'inner')
            ->where('t.doc_type', 'TESDA Certificate')   // see dump
            // ->where('d.is_active', 1)                 // add back if you only want active docs
            ->get()->row()->c;

        // 4) Certs expiring in next 30 days (inclusive)
        $certs_expiring_30d = (int)$this->db->select('COUNT(*) AS c', false)
            ->from('documents d')
            ->join('doc_type t', 't.id = d.doc_type_id', 'inner')
            ->where('t.doc_type', 'TESDA Certificate')
            ->where('d.expiry_date >=', $today)
            ->where('d.expiry_date <=', $in30)
            // ->where('d.is_active', 1)
            ->get()->row()->c;

        return [
            'workers_total'      => $workers_total,
            'workers_new_7d'     => $workers_new_7d,
            'workers_certified'  => $workers_certified,
            'certs_expiring_30d' => $certs_expiring_30d,
        ];
    }
}
