<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Personnel_model extends CI_Model
{
    protected $table = 'personnel_hired';

   
    public function ensure_hired(int $client_id, int $worker_id, ?int $project_id = null, ?float $rate = null, ?string $rate_unit = null)
    {
        $project_id = $project_id ?: null;
        $rate       = $rate !== null ? (float)$rate : null;
        $rate_unit  = $rate_unit ?: null;

        $this->db->from($this->table)
                 ->where('client_id', $client_id)
                 ->where('worker_id', $worker_id);
        if ($project_id !== null) {
            $this->db->where('project_id', $project_id);
        } else {
            $this->db->where('project_id IS NULL', null, false);
        }
        $row = $this->db->get()->row();

        $payload = [
            'client_id'  => $client_id,
            'worker_id'  => $worker_id,
            'project_id' => $project_id,
            'status'     => 'hired',
            'rate'       => $rate,
            'rate_unit'  => $rate_unit,
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        if ($row) {
            $this->db->where('id', (int)$row->id)->update($this->table, $payload);
            return (int)$row->id;
        } else {
            $payload['created_at'] = $payload['updated_at'];
            $this->db->insert($this->table, $payload);
            return (int)$this->db->insert_id();
        }
    }

      public function hired_by_client(int $client_id, int $limit = 50, int $offset = 0)
    {
        $this->db->select("
            ph.id,
            ph.client_id, ph.worker_id, ph.project_id, ph.status,
            ph.rate, ph.rate_unit, ph.created_at, ph.updated_at,
            u.first_name, u.last_name, u.email,
            wp.avatar AS w_avatar,
            cp2.title  AS project_title
        ", false)
        ->from($this->table." ph")
        ->join('users u', 'u.id = ph.worker_id', 'left')
        ->join('worker_profile wp', 'wp.workerID = ph.worker_id', 'left')
        ->join('client_projects cp2', 'cp2.id = ph.project_id', 'left')
        ->where('ph.client_id', $client_id)
        ->where('ph.status', 'hired')
        ->order_by('ph.updated_at', 'DESC')
        ->limit($limit, $offset);

        return $this->db->get()->result();
    }
}
