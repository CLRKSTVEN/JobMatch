<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Search_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

      public function search_workers_by_terms(array $terms, int $limit = 12, int $offset = 0, ?int $excludeUserId = null)
    {
        $db = $this->db;

        // Base filters & joins (users + skills + worker_profile)
        $this->_apply_workers_filters($db, $terms, $excludeUserId);

        // Reviews subquery (avg + count) joined once
        $db->join(
            '(SELECT workerID, COUNT(*) AS cnt, AVG(rating) AS avg_rating
                FROM reviews
                GROUP BY workerID) rv',
            'rv.workerID = u.id',
            'left'
        );

        // Selects (profile, skills, rates, rating)
        $db->select("
            u.id,
            u.first_name, u.last_name, u.email, u.phone,

            MAX(NULLIF(TRIM(wp.avatar),   '')) AS w_avatar,
            MAX(NULLIF(TRIM(wp.city),     '')) AS w_city,
            MAX(NULLIF(TRIM(wp.province), '')) AS w_province,
            MAX(NULLIF(TRIM(wp.brgy),     '')) AS w_brgy,
            MAX(NULLIF(TRIM(wp.headline), '')) AS w_headline,
            MAX(NULLIF(TRIM(wp.phoneNo),  '')) AS w_phone,

            /* prefer live reviews avg; fallback to stored wp.avgRating; else 0 */
            COALESCE(MAX(rv.avg_rating), MAX(wp.avgRating), 0) AS w_rating,
            /* total review count for the worker */
            COALESCE(MAX(rv.cnt), 0) AS w_rating_count,

            MAX(wp.expected_rate)              AS w_expected_rate,
            MAX(NULLIF(TRIM(wp.rate_unit),'')) AS w_rate_unit,

            GROUP_CONCAT(DISTINCT s.Title ORDER BY s.Title SEPARATOR ', ') AS skills,

            MIN(ws.minRate) AS minRate,
            MAX(ws.maxRate) AS maxRate
        ", false)
        ->group_by('u.id');

        // Sort order
        if (!empty($terms)) {
            // 1) Highest average rating first
            $db->order_by('COALESCE(MAX(rv.avg_rating), MAX(wp.avgRating), 0)', 'DESC', false);
            // 2) Then more reviews (tie breaker)
            $db->order_by('COALESCE(MAX(rv.cnt), 0)', 'DESC', false);
            // 3) Then more matched skills
            $db->order_by('COUNT(DISTINCT s.skillID)', 'DESC', false);
            // 4) Finally, name
            $db->order_by('u.last_name', 'ASC');
            $db->order_by('u.first_name', 'ASC');
        } else {
            // No terms: default alphabetical
            $db->order_by('u.last_name', 'ASC');
            $db->order_by('u.first_name', 'ASC');
        }

        // Pagination
        $db->limit($limit, $offset);

        $rows = $db->get()->result();

        // Count (distinct workers) for pagination
        $countDB = $this->load->database('default', true);
        $this->_apply_workers_filters($countDB, $terms, $excludeUserId);
        $countDB->select('COUNT(DISTINCT u.id) AS total', false);
        $total = (int)($countDB->get()->row()->total ?? 0);

        return ['total' => $total, 'rows' => $rows];
    }

    /**
     * Search clients by terms (name/company/location).
     */
    public function search_clients_by_terms(array $terms, int $limit = 12, int $offset = 0, ?int $excludeUserId = null)
    {
        $db = $this->db;

        $this->_apply_clients_filters($db, $terms, $excludeUserId);

        $db->select("
            u.id,
            u.first_name, u.last_name, u.email, u.phone,
            cp.fName AS cfName, cp.lName AS clName,

            MAX(NULLIF(TRIM(cp.avatar),''))   AS c_avatar,
            MAX(NULLIF(TRIM(cp.city),''))     AS c_city,
            MAX(NULLIF(TRIM(cp.province),'')) AS c_province,
            MAX(NULLIF(TRIM(cp.brgy),''))     AS c_brgy,

            MAX(NULLIF(TRIM(cp.companyName),''))   AS companyName,
            MAX(NULLIF(TRIM(cp.employer),''))      AS employer,
            MAX(NULLIF(TRIM(cp.business_name),'')) AS business_name,
            MAX(NULLIF(TRIM(cp.address),''))       AS address
        ", false)
        ->group_by('u.id')
        ->order_by('cp.companyName IS NULL, cp.companyName', 'ASC', false)
        ->order_by('u.last_name', 'ASC')
        ->order_by('u.first_name', 'ASC')
        ->limit($limit, $offset);

        $rows = $db->get()->result();

        $countDB = $this->load->database('default', true);
        $this->_apply_clients_filters($countDB, $terms, $excludeUserId);
        $countDB->select('COUNT(DISTINCT u.id) AS total', false);
        $total = (int)($countDB->get()->row()->total ?? 0);

        return ['total' => $total, 'rows' => $rows];
    }

    /* -------------------------- helpers -------------------------- */

    private function _apply_workers_filters(CI_DB_query_builder $qb, array $terms, ?int $excludeUserId): void
    {
     $qb->from('users u')
   ->join('worker_skills ws', 'ws.workerID = u.id AND (ws.is_active = 1 OR ws.is_active IS NULL)', 'left')
   ->join('skills s',         's.skillID   = ws.skillsID', 'left')
   ->join('worker_profile wp','wp.workerID = u.id', 'left')
   ->where('u.role', 'worker')
   ->where('u.is_active', 1);

if ($this->db->field_exists('visibility','users')) {
    // Hide workers who set their account to Private
    $qb->where('u.visibility', 'public');
}


        if ($excludeUserId) {
            $qb->where('u.id <>', (int)$excludeUserId);
        }

        if (!empty($terms)) {
            $qb->group_start();
            foreach ($terms as $t) {
                $qb->or_like('s.Title',      $t, 'both');
                $qb->or_like('u.first_name', $t, 'both');
                $qb->or_like('u.last_name',  $t, 'both');
                $qb->or_like('wp.city',      $t, 'both');
                $qb->or_like('wp.province',  $t, 'both');
                $qb->or_like('wp.brgy',      $t, 'both');
            }
            $qb->group_end();
        }
    }

    private function _apply_clients_filters(CI_DB_query_builder $qb, array $terms, ?int $excludeUserId): void
    {
       $qb->from('users u')
   ->join('client_profile cp', 'cp.clientID = u.id', 'left')
   ->where('u.role', 'client')
   ->where('u.is_active', 1);

if ($this->db->field_exists('visibility','users')) {
    $qb->where('u.visibility', 'public');
}


        if ($excludeUserId) {
            $qb->where('u.id <>', (int)$excludeUserId);
        }

        if (!empty($terms)) {
            $qb->group_start();
            foreach ($terms as $t) {
                $qb->or_like('u.first_name',     $t, 'both');
                $qb->or_like('u.last_name',      $t, 'both');
                $qb->or_like('cp.fName',         $t, 'both');
                $qb->or_like('cp.lName',         $t, 'both');
                $qb->or_like('cp.companyName',   $t, 'both');
                $qb->or_like('cp.employer',      $t, 'both');
                $qb->or_like('cp.business_name', $t, 'both');
                $qb->or_like('cp.city',          $t, 'both');
                $qb->or_like('cp.province',      $t, 'both');
                $qb->or_like('cp.brgy',          $t, 'both');
            }
            $qb->group_end();
        }
    }
private function _apply_base_filters(CI_DB_query_builder $qb, array $terms): void
{
   $qb->from('users u')
   ->join('worker_skills ws', 'ws.workerID = u.id AND (ws.is_active = 1 OR ws.is_active IS NULL)', 'left')
   ->join('skills s',         's.skillID   = ws.skillsID', 'left')
   ->join('worker_profile wp','wp.workerID = u.id', 'left')
   ->where('u.role', 'worker')
   ->where('u.is_active', 1);

if ($this->db->field_exists('visibility','users')) {
    $qb->where('u.visibility', 'public');
}


    if (!empty($terms)) {
        $qb->group_start();
        foreach ($terms as $t) {
            $qb->or_like('s.Title',      $t, 'both');
            $qb->or_like('u.first_name', $t, 'both');
            $qb->or_like('u.last_name',  $t, 'both');
            $qb->or_like('wp.city',      $t, 'both');
            $qb->or_like('wp.province',  $t, 'both');
            $qb->or_like('wp.brgy',      $t, 'both');
        }
        $qb->group_end();
    }
}

}
