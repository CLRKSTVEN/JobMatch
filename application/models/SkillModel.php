<?php defined('BASEPATH') OR exit('No direct script access allowed');

class SkillModel extends CI_Model
{
    protected $table = 'skills';

    public function all()
    {
        return $this->db->order_by('Title','ASC')->get($this->table)->result();
    }

    public function getTitleById($id)
    {
        $row = $this->db->select('Title')->from($this->table)->where('skillID',(int)$id)->get()->row();
        return $row ? $row->Title : null;
    }
}

