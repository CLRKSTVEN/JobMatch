<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Presence_model extends CI_Model
{
private $ONLINE_WINDOW = 25;
private $AWAY_WINDOW   = 600; 


    public function ping(int $user_id, ?string $status = null): bool
    {
        if ($user_id <= 0) return false;
        $now = date('Y-m-d H:i:s');

        $data = [
            'user_id'    => $user_id,
            'last_seen'  => $now,
            'ip_addr'    => $this->input->ip_address(),
            'user_agent' => substr((string)$this->input->user_agent(), 0, 255),
        ];
        if ($status) $data['status'] = $status;
        $exists = $this->db->select('user_id')->get_where('tw_presence', ['user_id'=>$user_id], 1)->row();
        if ($exists) {
            return $this->db->where('user_id', $user_id)->update('tw_presence', $data);
        } else {
            return $this->db->insert('tw_presence', $data);
        }
    }

public function get_presence(int $user_id): ?array
{
    if ($user_id <= 0) return null;
    $row = $this->db->get_where('tw_presence', ['user_id'=>$user_id], 1)->row();
    if (!$row) return null;
    if (strtolower((string)$row->status) === 'offline') {
        return [
            'user_id'   => (int)$row->user_id,
            'status'    => 'offline',
            'last_seen' => $row->last_seen,
            'age'       => max(0, time() - strtotime($row->last_seen ?? '1970-01-01 00:00:00')),
        ];
    }

    $last  = strtotime($row->last_seen ?? '1970-01-01 00:00:00');
    $age   = time() - $last;
    $state = 'offline';
    if ($age <= $this->ONLINE_WINDOW) {
        $state = 'online';
    } elseif ($age <= $this->AWAY_WINDOW) {
        $state = 'away';
    }

    return [
        'user_id'   => (int)$row->user_id,
        'status'    => $state,
        'last_seen' => $row->last_seen,
        'age'       => $age,
    ];
}
public function mark_offline(int $user_id): bool
{
    if ($user_id <= 0) return false;
    return $this->db->where('user_id', $user_id)->update('tw_presence', [
        'status'    => 'offline',
        'last_seen' => date('Y-m-d H:i:s'),
        'ip_addr'   => $this->input->ip_address(),
        'user_agent'=> substr((string)$this->input->user_agent(), 0, 255),
    ]);
}

}
