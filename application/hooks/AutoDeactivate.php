<?php defined('BASEPATH') OR exit('No direct script access allowed');

class AutoDeactivate
{
    // run at most once per 24h
    private $intervalSeconds = 86400; // 24*3600

    public function run()
    {
        $CI =& get_instance();
        // Very lightweight throttle via temp file
        $tmpDir = APPPATH . 'cache' . DIRECTORY_SEPARATOR;
        if (!is_dir($tmpDir)) @mkdir($tmpDir, 0755, true);
        $flag   = $tmpDir . 'autodeactivate.flag';

        $last = @filemtime($flag) ?: 0;
        if ((time() - $last) < $this->intervalSeconds) {
            return; // too soon, skip
        }

        // touch first to avoid stampede on high traffic
        @touch($flag);

        // do the DB work
        $this->deactivateStale($CI);
    }

    private function deactivateStale($CI)
    {
        $CI->load->database();

        $cutoff = date('Y-m-d H:i:s', strtotime('-2 years'));
        $now    = date('Y-m-d H:i:s');

        // Active users who haven't logged in for 2 years:
        // last_login_at < cutoff OR (last_login_at IS NULL AND created_at < cutoff)
        $CI->db->where('is_active', 1)
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

        if ($CI->db->field_exists('status', 'users')) {
            // Only touch rows we just changed (same updated_at)
            $CI->db->where('is_active', 0)
                   ->where('updated_at', $now)
                   ->update('users', ['status' => 'inactive']);
        }
    }
}
