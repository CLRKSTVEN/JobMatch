<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hotline_model extends CI_Model
{
    public function __construct(){
        parent::__construct();
        $this->load->database();
    }

    /* ----- Admin CRUD ----- */
    public function all($only_active = false){
        if ($only_active) $this->db->where('is_active', 1);
        return $this->db->order_by('sort_order ASC, title ASC')
                        ->get('hotline_numbers')->result();
    }

    public function by_audience_for_public($aud = 'all'){
        $this->db->where('is_active', 1);
        $this->db->group_start()
                 ->where('audience', 'all')
                 ->or_where('audience', $aud)
                 ->group_end();
        return $this->db->order_by('sort_order ASC, title ASC')
                        ->get('hotline_numbers')->result();
    }

    public function get($id){
        return $this->db->get_where('hotline_numbers', ['id' => (int)$id])->row();
    }

    public function create($data){
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->insert('hotline_numbers', $data);
        return (int)$this->db->insert_id();
    }

    public function update($id, $data){
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->update('hotline_numbers', $data, ['id' => (int)$id]);
    }

    public function delete($id){
        return $this->db->delete('hotline_numbers', ['id' => (int)$id]);
    }

    public function toggle($id){
        $row = $this->get($id);
        if (!$row) return false;
        $new = $row->is_active ? 0 : 1;
        return $this->update($id, ['is_active' => $new]);
    }
}
