<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class TesdaDashboard_model extends CI_Model
{
    private $users = 'users';
    private $wp    = 'worker_profile';

    /** 1) Total ACTIVE workers */
    public function total_workers(): int
    {
        $this->db->from($this->users);
        $this->db->where('role', 'worker');
        $this->db->where('is_active', 1);
        // include status='active' when present
        $this->db->group_start()
                 ->where('status', 'active')
                 ->or_where('status IS NULL', null, false)
                 ->group_end();
        return (int)$this->db->count_all_results();
    }

    /** 2) New workers in last 7 days (use users.created_at for consistency) */
    public function new_last7(): int
    {
        $since = date('Y-m-d H:i:s', strtotime('-7 days'));
        $this->db->from($this->users);
        $this->db->where('role', 'worker');
        $this->db->where('created_at >=', $since);
        return (int)$this->db->count_all_results();
    }

    /** 3) TESDA certified (has non-empty cert no; expiry NULL or future) */
    public function tesda_certified_active(): int
    {
        $today = date('Y-m-d');
        $this->db->from($this->wp);
        $this->db->where('tesda_cert_no IS NOT NULL', null, false);
        // IMPORTANT: use <> '' and raw = false so CI doesn’t escape
        $this->db->where("TRIM(tesda_cert_no) <> ''", null, false);
        $this->db->group_start()
                 ->where('tesda_expiry IS NULL', null, false)
                 ->or_where('tesda_expiry >=', $today)
                 ->group_end();
        return (int)$this->db->count_all_results();
    }

    /** 4) Certificates expiring in next 30 days (inclusive) */
    public function certs_expiring_30d(): int
    {
        $today = date('Y-m-d');
        $max   = date('Y-m-d', strtotime('+30 days'));

        $this->db->from($this->wp);
        $this->db->where('tesda_expiry IS NOT NULL', null, false);
        $this->db->where('tesda_expiry >=', $today);
        $this->db->where('tesda_expiry <=', $max);
        // only count those with an actual cert no
        $this->db->where('tesda_cert_no IS NOT NULL', null, false);
        $this->db->where("TRIM(tesda_cert_no) <> ''", null, false);

        return (int)$this->db->count_all_results();
    }

    public function get_stats(): array
    {
        return [
            'total_workers' => $this->total_workers(),
            'new_7d'        => $this->new_last7(),
            'tesda_cert'    => $this->tesda_certified_active(),
            'expiring_30d'  => $this->certs_expiring_30d(),
        ];
    }
}
