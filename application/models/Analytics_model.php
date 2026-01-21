<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Analytics_model extends CI_Model
{
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

   public function get_worker_service_mix(int $workerId, ?string $statusFilter = 'completed')
{
    // We’ll build once and group by a “resolved” skill id/title.
    $this->db->start_cache();

    $this->db->from('transactions t')
             ->join('client_projects cp', 'cp.id = t.projectID', 'left')
             // primary mapping: transaction’s skillsID -> s1
             ->join('skills s1', 's1.skillID = t.skillsID', 'left')
             // fallback mapping: category text contains a skill title -> s2
             // NOTE: cp.category is comma-separated text of skill titles; we normalize spaces.
             ->join(
                'skills s2',
                "s2.Title IS NOT NULL AND (
                    FIND_IN_SET(s2.Title, REPLACE(REPLACE(cp.category,' ,',','), ', ', ',')) > 0
                )",
                'left',
                false
             )
             ->where('t.workerID', $workerId);

if ($statusFilter) {
    if ($statusFilter === 'completed') {
        $this->db->group_start()
                 ->where('t.status', 'completed')
                 ->or_where('t.completed_at IS NOT NULL', null, false)
                 ->group_end();
    } elseif ($statusFilter === 'in_progress') {
        $this->db->where_in('t.status', ['accepted','active'])
                 ->where('cp.status', 'active')
                 ->where('t.completed_at IS NULL', null, false);
    } elseif ($statusFilter === 'any') {
    } else {
        $this->db->where('t.status', $statusFilter);
    }
}


    $resolvedId    = 'COALESCE(s1.skillID, s2.skillID)';   
    $resolvedTitle = 'COALESCE(s1.Title,   s2.Title)'; 

    $rows = $this->db
        ->select("$resolvedId  AS rid", false)
        ->select("$resolvedTitle AS rtitle", false)
        ->select('COUNT(*) AS cnt', false)
        ->group_by('rid, rtitle')
        ->get()
        ->result();

    $this->db->flush_cache();

    // Summarize + compute percentages
    $total = 0;
    foreach ($rows as $r) $total += (int)$r->cnt;
    $den = max(1, $total);

    $out  = [];
    $otherCnt = 0;

    foreach ($rows as $r) {
        $cnt = (int)$r->cnt;
        $sid = isset($r->rid) ? (int)$r->rid : 0;
        $ttl = trim((string)($r->rtitle ?? ''));

        if ($sid > 0 && $ttl !== '') {
            $out[] = [
                'skillID' => $sid,
                'title'   => $ttl,
                'count'   => $cnt,
                'percent' => round(($cnt / $den) * 100, 2),
            ];
        } else {
            $otherCnt += $cnt;
        }
    }

    if ($otherCnt > 0) {
        $out[] = [
            'skillID' => null,
            'title'   => 'Other',
            'count'   => $otherCnt,
            'percent' => round(($otherCnt / $den) * 100, 2),
        ];
    }

    // Sort by count desc
    usort($out, fn($a,$b) => $b['count'] <=> $a['count']);

    return ['total' => $total, 'rows' => $out];
}

}
