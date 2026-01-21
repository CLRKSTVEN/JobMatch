<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->input->is_cli_request()) {
            show_error('CLI only', 403);
        }
        $this->load->database();
    }

    /**
     * Deactivate users who haven't signed in for 2 years.
     * Rule:
     *  - last_login_at < now - 2 years
     *  - OR (last_login_at IS NULL AND created_at < now - 2 years)
     * Only touches currently active users.
     */
    public function autodeactivate_stale()
    {
        $cutoff = date('Y-m-d H:i:s', strtotime('-2 years'));
        $now    = date('Y-m-d H:i:s');

        $this->db->where('is_active', 1)
                 ->group_start()
                    ->where('last_login_at <', $cutoff)
                    ->or_group_start()
                        ->where('last_login_at IS NULL', null, false)
                        ->where('created_at <', $cutoff)
                    ->group_end()
                 ->group_end()
                 ->update('users', [
                    'is_active'  => 0,
                    'updated_at' => $now,
                 ]);

        // If you track textual status, keep it consistent:
        if ($this->db->field_exists('status', 'users')) {
            $this->db->where('is_active', 0)
                     ->where('updated_at', $now)
                     ->update('users', ['status' => 'inactive']);
        }

        echo "Auto-deactivated: " . $this->db->affected_rows() . PHP_EOL;
    }
}
