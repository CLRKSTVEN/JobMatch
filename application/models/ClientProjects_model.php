<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ClientProjects_model extends CI_Model
{
    private $table = 'client_projects';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function create(int $clientID, array $payload): bool
    {
        $now = date('Y-m-d H:i:s');
        $payload['clientID']   = $clientID;
        $payload['created_at'] = $now;
        $payload['updated_at'] = $now;
        return $this->db->insert($this->table, $payload);
    }

  
public function close(int $clientID, int $projectID): bool
{
    $now = date('Y-m-d H:i:s');
    $this->db->trans_start();

    $this->db->where('id', $projectID)
             ->where('clientID', $clientID)
             ->where('status', 'active')
             ->update($this->table, ['status' => 'closed', 'updated_at' => $now]);

    if ($this->db->affected_rows() > 0) {

        if ($this->db->table_exists('transactions')) {
            $this->db->where('projectID', $projectID)
                     ->where('clientID', $clientID)
                     ->where_in('status', ['accepted','active'])
                     ->update('transactions', ['status' => 'completed', 'completed_at' => $now]);

            if ($this->db->affected_rows() === 0) {
                $this->db->where('projectID', $projectID)
                         ->where_in('status', ['accepted','active'])
                         ->update('transactions', ['status' => 'completed', 'completed_at' => $now]);
            }
        }

        $hiresTable = $this->db->table_exists('personnel_hires') ? 'personnel_hires'
                    : ($this->db->table_exists('personnel_hired') ? 'personnel_hired' : null);

        if ($hiresTable) {
            $this->db->where('project_id', $projectID)
                     ->where('client_id', $clientID)
                     ->where_in('status', ['hired','active'])
                     ->update($hiresTable, ['status' => 'completed', 'updated_at' => $now]);
        }
    }

    $this->db->trans_complete();
    return $this->db->trans_status();
}


    public function active_by_client(int $clientID, int $limit = 12, int $offset = 0): array
    {
        return $this->db->order_by('created_at','DESC')
                        ->get_where($this->table, [
                            'clientID' => $clientID,
                            'status'   => 'active'
                        ], $limit, $offset)
                        ->result();
    }

    public function active_total(int $clientID): int
    {
        return (int) $this->db->where('clientID', $clientID)
                              ->where('status', 'active')
                              ->count_all_results($this->table);
    }

    public function history_by_client(int $clientID, int $limit = 12, int $offset = 0): array
    {
        return $this->db->order_by('updated_at','DESC')
                        ->where('clientID', $clientID)
                        ->where_in('status', ['closed'])
                        ->get($this->table, $limit, $offset)
                        ->result();
    }

    public function history_total(int $clientID): int
    {
        return (int) $this->db->where('clientID', $clientID)
                              ->where_in('status',['closed'])
                              ->count_all_results($this->table);
    }

    public function get_one(int $clientID, int $projectID)
    {
        return $this->db->get_where($this->table, [
            'id'       => $projectID,
            'clientID' => $clientID
        ])->row();
    }
}
