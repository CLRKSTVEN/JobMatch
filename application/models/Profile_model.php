<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_model extends CI_Model
{
    private $table = 'users';

    public function get_profile($user_id)
    {
        return $this->db->get_where($this->table, ['id' => $user_id])->row();
    }

    public function update_profile($user_id, $data)
    {
        $this->db->where('id', $user_id);
        return $this->db->update($this->table, $data);
    }
       public function get_by_user($user_id)
    {
        return $this->db->get_where('users', ['id' => $user_id])->row();
    }

    public function is_profile_complete($user_id): bool
    {
        $u = $this->get_by_user($user_id);
        if (!$u) return false;

        $required = [
            'first_name',
            'last_name',
            'phone',
        ];

        foreach ($required as $f) {
            if (!isset($u->$f) || trim((string)$u->$f) === '') {
                return false;
            }
        }
        return true;
    }
public function search_skills(string $q = '', int $offset = 0, int $limit = 50): array
{
    // ----- Count -----
    $cnt = $this->db->from('skills');
    if ($q !== '') {
        $cnt->group_start()
            ->like('Title', $q)
            ->or_like('Description', $q)
            ->group_end();
    }
    $total = (int)$cnt->count_all_results(); // resets builder

    // ----- Page -----
    $list = $this->db->from('skills');
    if ($q !== '') {
        $list->group_start()
             ->like('Title', $q)
             ->or_like('Description', $q)
             ->group_end();
    }
    $rows = $list->order_by('Title', 'ASC')
                 ->limit($limit, $offset)
                 ->get()
                 ->result();

    return [$rows, $total];
}
public function list_by_user(int $user_id): array
{
    if (!$user_id) return [];

    return $this->db
        ->select('
            d.id,
            d.doc_name,
            d.doc_type_id,
            d.skill_id,
            d.expiry_date,
            d.file_path,
            d.is_active,
            t.doc_type AS doc_type,
            t.doc_type AS type,
            t.code,
            s.Title   AS skill
        ', false)
        ->from('documents d')
        ->join('doc_type t', 't.id = d.doc_type_id', 'left')
        ->join('skills   s', 's.id = d.skill_id',    'left')
        ->where('d.user_id', $user_id)
        ->where('(d.is_deleted IS NULL OR d.is_deleted = 0)', null, false)
        ->order_by('d.expiry_date', 'ASC')
        ->get()
        ->result_array();
}

    public function list_doc_types(): array
    {
        return $this->db->select('id, doc_type, code')
                        ->from('doc_type')
                        ->order_by('doc_type','ASC')
                        ->get()->result_array();
    }
}
