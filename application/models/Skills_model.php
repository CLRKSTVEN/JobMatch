<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Skills_model extends CI_Model
{
    private function normalize_title($t)
    {
        $t = trim($t);
        return preg_replace('/\s+/', ' ', $t);
    }

    public function get_or_create_by_title($title)
    {
        $title = $this->normalize_title($title);
        if ($title === '') return null;

        $this->db->select('skillID')->from('skills')->where('Title', $title);
        $row = $this->db->get()->row();
        if ($row) return (int)$row->skillID;

        $this->db->insert('skills', ['Title' => $title]);
        return (int)$this->db->insert_id();
    }

    /**
     * 
     * @param string|int $workerID
     * @param array $titles
     * @param array $rates
     *
     * 
     */
    public function replace_worker_skills($workerID, array $titles, array $rates = [])
    {
        $titles = array_values(array_unique(array_filter(array_map(function($t){
            return $this->normalize_title($t);
        }, $titles))));

        $this->db->trans_start();

        $skillIDs = []; 
        foreach ($titles as $t) {
            $sid = $this->get_or_create_by_title($t);
            if ($sid) $skillIDs[$t] = $sid;
        }

        if (empty($skillIDs)) {
            $this->db->delete('worker_skills', ['workerID' => (string)$workerID]);
            $this->db->trans_complete();
            return $this->db->trans_status();
        }

        $skillIDsStr = array_map('strval', array_values($skillIDs));

        $this->db->where('workerID', (string)$workerID)
                 ->where_not_in('skillsID', $skillIDsStr)
                 ->delete('worker_skills');

        $this->db->select('skillsID')
                 ->from('worker_skills')
                 ->where('workerID', (string)$workerID);
        $existing = array();
        foreach ($this->db->get()->result() as $r) {
            $existing[] = (string)$r->skillsID;
        }

        $toInsert = [];
        foreach ($skillIDs as $title => $sid) {
            $sidStr = (string)$sid;
            if (!in_array($sidStr, $existing, true)) {
                $min = isset($rates[$title]['min']) && $rates[$title]['min'] !== '' ? (float)$rates[$title]['min'] : 0.00;
                $max = isset($rates[$title]['max']) && $rates[$title]['max'] !== '' ? (float)$rates[$title]['max'] : 0.00;

                $toInsert[] = [
                    'workerID'  => (string)$workerID,
                    'skillsID'  => $sidStr, 
                    'minRate'   => number_format($min, 2, '.', ''),
                    'maxRate'   => number_format($max, 2, '.', ''),
                    'is_active' => 1,
                    'attachment'=> '',
                ];
            }
        }
        if (!empty($toInsert)) {
            $this->db->insert_batch('worker_skills', $toInsert);
        }

        if (!empty($rates)) {
            foreach ($skillIDs as $title => $sid) {
                if (!isset($rates[$title])) continue;
                $min = isset($rates[$title]['min']) && $rates[$title]['min'] !== '' ? (float)$rates[$title]['min'] : null;
                $max = isset($rates[$title]['max']) && $rates[$title]['max'] !== '' ? (float)$rates[$title]['max'] : null;

                if ($min !== null || $max !== null) {
                    $upd = [];
                    if ($min !== null) $upd['minRate'] = number_format($min, 2, '.', '');
                    if ($max !== null) $upd['maxRate'] = number_format($max, 2, '.', '');
                    if (!empty($upd)) {
                        $this->db->where('workerID', (string)$workerID)
                                 ->where('skillsID', (string)$sid)
                                 ->update('worker_skills', $upd);
                    }
                }
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_titles_by_worker($workerID)
    {
        $this->db->select('s.Title')
                 ->from('worker_skills ws')
                 ->join('skills s', 's.skillID = CAST(ws.skillsID AS UNSIGNED)', 'inner')
                 ->where('ws.workerID', (string)$workerID)
                 ->order_by('s.Title','ASC');

        $rows = $this->db->get()->result();
        $out  = array();
        foreach ($rows as $r) {
            $out[] = $r->Title;
        }
        return $out;
    }

  
    public function get_worker_skill_rates($workerID)
    {
        $this->db->select('s.Title, ws.minRate, ws.maxRate')
                 ->from('worker_skills ws')
                 ->join('skills s', 's.skillID = CAST(ws.skillsID AS UNSIGNED)', 'inner')
                 ->where('ws.workerID', (string)$workerID);

        $rows = $this->db->get()->result();
        $out  = array();
        foreach ($rows as $r) {
            $out[$r->Title] = array(
                'min' => (float)$r->minRate,
                'max' => (float)$r->maxRate,
            );
        }
        return $out;
    }
}
