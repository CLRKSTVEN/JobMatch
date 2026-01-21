<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Reviews_model extends CI_Model
{
    private $table = 'reviews';


    public function stats($workerID)
    {
        $row = $this->db->select('COUNT(*) AS count, COALESCE(AVG(rating),0) AS avg', false)
                        ->from($this->table)
                        ->where('workerID', (int)$workerID)
                        ->get()->row();
        return ['count' => (int)($row->count ?? 0), 'avg' => (float)($row->avg ?? 0.0)];
    }

   
public function latest($workerID, $limit = 5)
{
    $rows = $this->db->select([
                        'r.rating',
                        'r.comment AS comment',
                        'r.created_at AS created_at',
                        'TRIM(CONCAT(COALESCE(u.first_name, ""), " ", COALESCE(u.last_name, ""))) AS client_name',
                        'COALESCE(cp.title, "Project") AS job_title',
                    ], false)
                    ->from($this->table.' r')
                    ->join('transactions t', 't.transactionID = r.transactionID', 'left')
                    ->join('client_projects cp', 'cp.id = t.projectID', 'left')
                    ->join('users u', 'u.id = r.clientID', 'left')
                    ->where('r.workerID', (int)$workerID)
                    ->order_by('r.created_at', 'DESC')
                    ->limit((int)$limit)
                    ->get()
                    ->result();

    foreach ($rows as $r) {
        $r->time_ago = $this->time_ago($r->created_at ?? null);
        $r->rating = (int)($r->rating ?? 0);
        if (!isset($r->comment))   { $r->comment = null; }
        if (!isset($r->created_at)){ $r->created_at = null; }
        if ($r->client_name === '') { $r->client_name = '—'; }
        if (!isset($r->job_title))  { $r->job_title = 'Project'; }
    }
    return $rows;
}


    public function times_hired($workerID)
    {
        if ($this->db->table_exists('transactions')) {
            $statuses = ['completed','Completed','done','Done','paid','Paid','closed','Closed'];
            $q = $this->db->select('COUNT(*) AS c', false)
                          ->from('transactions t')
                          ->join('client_projects cp', 'cp.id = t.projectID', 'left')
                          ->where('t.workerID', (int)$workerID)
                          ->where_in('t.status', $statuses);


            $row = $q->get()->row();
            return (int)($row->c ?? 0);
        }

        $row = $this->db->select('COUNT(DISTINCT transactionID) AS c', false)
                        ->from($this->table)
                        ->where('workerID', (int)$workerID)
                        ->get()->row();
        return (int)($row->c ?? 0);
    }

    public function add($transactionID, $clientID, $workerID, $rating, $comment = '')
    {
        $rating = (int)$rating;
        if ($rating < 1 || $rating > 5) {
            return [false, 'Rating must be between 1 and 5'];
        }

        if ($this->db->table_exists('transactions')) {
            $tx = $this->db->get_where('transactions', [
                'transactionID' => (int)$transactionID, 
                'workerID'      => (int)$workerID,
                'clientID'      => (int)$clientID
            ])->row();
            if (!$tx) return [false, 'Invalid transaction'];
        }

        $exists = $this->db->get_where($this->table, [
            'transactionID' => (int)$transactionID,
            'clientID'      => (int)$clientID
        ])->row();
        if ($exists) return [false, 'You already reviewed this job'];

        $ok = $this->db->insert($this->table, [
            'transactionID' => (int)$transactionID,
            'clientID'      => (int)$clientID,
            'workerID'      => (int)$workerID,
            'rating'        => $rating,
            'comment'       => trim((string)$comment) ?: null,
            'created_at'    => date('Y-m-d H:i:s'),
        ]);
        return [$ok, $ok ? $this->db->insert_id() : 'Could not save review'];
    }


    private function time_ago(?string $dt): string
    {
        if (!$dt) return '';
        $t = strtotime($dt);
        if (!$t) return '';
        $diff = time() - $t;

        if ($diff < 60) return $diff.'s ago';
        $m = floor($diff / 60);
        if ($m < 60) return $m.' min'.($m==1?'':'s').' ago';
        $h = floor($m / 60);
        if ($h < 24) return $h.' hr'.($h==1?'':'s').' ago';
        $d = floor($h / 24);
        if ($d < 30) return $d.' day'.($d==1?'':'s').' ago';
        $mo = floor($d / 30);
        if ($mo < 12) return $mo.' mo'.($mo==1?'':'s').' ago';
        $y = floor($mo / 12);
        return $y.' yr'.($y==1?'':'s').' ago';
    }
}
