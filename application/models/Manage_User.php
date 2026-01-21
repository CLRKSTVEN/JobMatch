<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Manage_User extends CI_Model
{
    private $table = 'users';

public function search(
    string $q = '',
    string $role = '',
    ?int $isActive = null,
    int $limit = 100,
    int $offset = 0,
    ?string $statusText = null
) {
    $this->db->from($this->table . ' u');
    // Join both profile tables
    $this->db->join('worker_profile wp', 'wp.workerID = u.id', 'left');
    $this->db->join('client_profile cp', 'cp.clientID = u.id', 'left');

    // Select with unified avatar
    $this->db->select("
        u.id, u.email, u.role, u.is_active, u.status,
        u.first_name, u.last_name, u.created_at,
        COALESCE(NULLIF(TRIM(wp.avatar), ''), NULLIF(TRIM(cp.avatar), '')) AS avatar
    ", false);

    if ($q !== '') {
        $this->db->group_start()
                 ->like('u.email', $q)
                 ->or_like('u.first_name', $q)
                 ->or_like('u.last_name', $q)
                 ->group_end();
    }

    if ($role !== '') {
        $this->db->where('LOWER(u.role) =', strtolower($role));
    }

    if ($isActive !== null) {
        $this->db->where('u.is_active', (int)$isActive);
    }

    if ($statusText !== null) {
        $this->db->where('LOWER(u.status) =', strtolower($statusText));
    }

    $this->db->group_by('u.id');
    return $this->db->order_by('u.created_at', 'DESC')
                    ->limit($limit, $offset)
                    ->get()
                    ->result();
}


 public function set_active(int $id, int $active): bool
{
    $data = [
        'is_active'  => $active,
        'updated_at' => date('Y-m-d H:i:s'),
    ];
    if ($this->db->field_exists('status', $this->table)) {
        $data['status'] = $active ? 'active' : 'inactive';
    }
    return (bool)$this->db->where('id', $id)->update($this->table, $data);
}

}
