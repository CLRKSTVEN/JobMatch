<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{

    private $table = 'users';

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert($this->table, $data);
    }

    public function get_by_email($email)
    {
        return $this->db->get_where($this->table, ['email' => $email])->row();
    }

    public function verify_credentials($email, $password)
    {
        $user = $this->get_by_email($email);
        if (!$user) {
            return [false, "Account not found."];
        }

        if (!password_verify($password, $user->password_hash)) {
            return [false, "Invalid password."];
        }

        if ((int)$user->is_active !== 1) {
            return [false, "Your account is not yet approved by the admin."];
        }

        return [true, $user];
    }

    public function get_pending_users()
    {
        return $this->db->get_where($this->table, ['is_active' => 0])->result();
    }

    public function activate_user($id)
    {
        return $this->db->update($this->table, [
            'is_active' => 1,
            'updated_at' => date('Y-m-d H:i:s')
        ], ['id' => $id]);
    }

    public function get_by_token($token)
    {
        return $this->db->get_where('users', ['activation_token' => $token])->row();
    }

    public function activate($id)
    {
        $this->db->where('id', $id);
        $this->db->update('users', ['is_active' => 1, 'activation_token' => null]);
    }
}
