<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Address_model extends CI_Model
{
    private $table = 'settings_address'; // make sure this exact name exists on prod

    public function get_provinces()
    {
        if (!$this->db->table_exists($this->table)) return array();
        $res = $this->db->distinct()
                        ->select('Province')
                        ->from($this->table)
                        ->where('Province <>', '')
                        ->order_by('Province','ASC')
                        ->get()
                        ->result_array();

        return array_map(function($r){ return $r['Province']; }, $res);
    }

    public function get_cities($province)
    {
        if (!$this->db->table_exists($this->table)) return array();
        $res = $this->db->distinct()
                        ->select('City')
                        ->from($this->table)
                        ->where('Province', $province)
                        ->where('City <>', '')
                        ->order_by('City','ASC')
                        ->get()
                        ->result_array();

        return array_map(function($r){ return $r['City']; }, $res);
    }

    public function get_barangays($province, $city)
    {
        if (!$this->db->table_exists($this->table)) return array();
        $res = $this->db->distinct()
                        ->select('Brgy')
                        ->from($this->table)
                        ->where(array('Province' => $province, 'City' => $city))
                        ->where('Brgy <>', '')
                        ->order_by('Brgy','ASC')
                        ->get()
                        ->result_array();

        return array_map(function($r){ return $r['Brgy']; }, $res);
    }
}
