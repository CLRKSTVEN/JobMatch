<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AccountActions_model extends CI_Model
{
    public function __construct() { parent::__construct(); $this->load->database(); }

    private function _now(){ return date('Y-m-d H:i:s'); }

    public function deactivate_user(int $uid): bool
    {
        if ($uid <= 0) return false;

        $data = [
            'is_active'     => 0,
            'status'        => 'deactivated',
            'deactivated_at'=> $this->_now(),
            'updated_at'    => $this->_now(),
        ];
        return $this->db->update('users', $data, ['id' => $uid]);
    }

    public function reactivate_user(int $uid): bool
    {
        if ($uid <= 0) return false;

        $data = [
            'is_active'     => 1,
            'status'        => 'active',
            'deactivated_at'=> null,
            'updated_at'    => $this->_now(),
        ];
        return $this->db->update('users', $data, ['id' => $uid]);
    }

    /**
     * Soft delete: hide account + mark deleted_at + anonymize PII (email/name)
     * - Keeps referential integrity (messages, reviews, etc.)
     * - Can be turned into a hard delete by an admin job later.
     */
    public function soft_delete_user(int $uid): bool
    {
        if ($uid <= 0) return false;

        $u = $this->db->get_where('users', ['id' => $uid])->row();
        if (!$u) return false;

        // unique placeholder email to satisfy UNIQUE(email)
        $placeholderEmail = sprintf('deleted+%d@local.invalid', (int)$uid);

        $this->db->trans_start();

        $this->db->update('users', [
            'is_active'   => 0,
            'status'      => 'deleted',
            'deleted_at'  => $this->_now(),
            'updated_at'  => $this->_now(),
            'email'       => $placeholderEmail,
            'first_name'  => 'Deleted',
            'last_name'   => 'User',
            'phone'       => null
        ], ['id' => $uid]);

        // Optional: blank avatars in profiles (non-destructive)
        if ($this->db->table_exists('worker_profile')) {
            $this->db->where('workerID', $uid)->update('worker_profile', [
                'avatar' => null, 'headline' => null, 'phoneNo' => null
            ]);
        }
        if ($this->db->table_exists('client_profile')) {
            $this->db->where('clientID', $uid)->update('client_profile', [
                'avatar' => null, 'companyName' => null, 'business_name' => null, 'phoneNo' => null
            ]);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
