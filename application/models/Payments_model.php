<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payments_model extends CI_Model
{
    
  public function list_for_client(int $clientId, int $limit = 12, int $offset = 0): array
    {
        $rows = $this->db->select("
                t.transactionID, t.projectID, t.workerID,
                t.rate_agreed, t.rateUnit AS rate_unit, t.status,
                COALESCE(t.completed_at, t.created_at) AS paid_at,
                w.first_name AS w_first, w.last_name AS w_last, w.email AS w_email,
                wp.avatar AS w_avatar,
                COALESCE(p.title, t.title) AS project_title
            ")
            ->from('transactions t')
            ->join('client_projects p', 'p.id = t.projectID', 'left')
            ->join('users w', 'w.id = t.workerID', 'left')
            ->join('worker_profile wp', 'wp.workerID = t.workerID', 'left') 
            ->where('t.clientID', (int)$clientId)
            ->where('t.rate_agreed IS NOT NULL', null, false)
            ->group_start()
                ->where('t.status', 'completed')
                ->or_where_in('p.status', ['closed','completed','complete','done'])
            ->group_end()
            ->order_by('paid_at', 'DESC')
            ->limit($limit, $offset)
            ->get()->result();

        $total_rows = (int) ($this->db->select('COUNT(*) AS c', false)
            ->from('transactions t')
            ->join('client_projects p', 'p.id = t.projectID', 'left')
            ->where('t.clientID', (int)$clientId)
            ->where('t.rate_agreed IS NOT NULL', null, false)
            ->group_start()
                ->where('t.status', 'completed')
                ->or_where_in('p.status', ['closed','completed','complete','done'])
            ->group_end()
            ->get()->row()->c ?? 0);

        $spend_total = (float) ($this->db->select('COALESCE(SUM(t.rate_agreed),0) AS total', false)
            ->from('transactions t')
            ->join('client_projects p', 'p.id = t.projectID', 'left')
            ->where('t.clientID', (int)$clientId)
            ->where('t.rate_agreed IS NOT NULL', null, false)
            ->group_start()
                ->where('t.status', 'completed')
                ->or_where_in('p.status', ['closed','completed','complete','done'])
            ->group_end()
            ->get()->row()->total ?? 0);

        return ['items' => $rows, 'total_rows' => $total_rows, 'spend_total' => $spend_total];
    }
}
