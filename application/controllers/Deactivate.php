<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Deactivate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library(['session']);
        $this->load->helper(['url']);
        $this->load->database();
        $this->load->model('User_model','um');
    }

    // If your PHP is older than 7.0, remove the ": int" return type.
    private function _uid()
    {
        foreach (['id','user_id','uid','account_id'] as $k) {
            $v = $this->session->userdata($k);
            if (is_numeric($v)) return (int)$v;
        }
        return 0;
    }

    private function _out($ok, $msg='OK', $extra=[])
    {
        // return fresh CSRF too (your admin JS expects this pattern)
        $out = array_merge(
            ['ok' => (bool)$ok, 'message' => $msg],
            (array)$extra,
            [
                'csrf_name' => $this->security->get_csrf_token_name(),
                'csrf_hash' => $this->security->get_csrf_hash(),
            ]
        );
        $this->output->set_content_type('application/json')->set_output(json_encode($out));
    }

    public function index()
    {
        if (!$this->session->userdata('logged_in')) {
            return redirect('auth/login?next='.rawurlencode(current_url()));
        }
        $data = ['page_title' => 'Account Controls'];
        $this->load->view('account_deactivate', $data);
    }

    public function do_action()
    {
        $me = $this->_uid();
        if ($me <= 0) return $this->_out(false, 'Unauthorized');

        $action = (string)$this->input->post('action', true);

        if ($action === 'deactivate') {
            // user self-deactivation: disable login + mark inactive
            $updateData = [
                'is_active'  => 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            if ($this->db->field_exists('status','users')) {
                $updateData['status'] = 'inactive';
            }

            $ok = $this->db->update('users', $updateData, ['id' => $me]);

            // destroy session so they’re signed out
            $this->session->sess_destroy();

            return $this->_out(
                (bool)$ok,
                $ok ? 'Deactivated. An administrator can reactivate your account.' : 'Failed to deactivate.'
            );
        }

        if ($action === 'reactivate') {
            // Not allowed from user side anymore.
            return $this->_out(false, 'Reactivation is admin-only. Please contact support/admin.');
        }

        if ($action === 'delete') {
            $confirm = (string)$this->input->post('confirm');
            if ($confirm !== 'DELETE') return $this->_out(false, 'Type DELETE to confirm.');

            $res = $this->_hard_delete_user($me);
            $ok  = isset($res[0]) ? (bool)$res[0] : false;
            $msg = isset($res[1]) ? (string)$res[1] : 'Delete failed.';

            // end session regardless
            $this->session->sess_destroy();

            return $this->_out($ok, $msg);
        }

        return $this->_out(false, 'Unknown action');
    }

    /**
     * Hard-delete user and related records (best-effort; checks table/column existence).
     * Leaves system integrity for historical tables when necessary.
     *
     * @param int $user_id
     * @return array [ok(bool), message(string)]
     */
    private function _hard_delete_user($user_id)
    {
        $this->db->trans_begin();

        try {
            // Presence / notifications
            if ($this->db->table_exists('tw_presence')) {
                $this->db->where('user_id', $user_id)->delete('tw_presence');
            }
            if ($this->db->table_exists('tw_notifications')) {
                $this->db->group_start()
                         ->where('user_id', $user_id)
                         ->or_where('actor_id', $user_id)
                         ->group_end()
                         ->delete('tw_notifications');
            }

            // Messaging: delete messages in threads that involve this user
            if ($this->db->table_exists('tw_threads')) {
                $threads = $this->db->select('id')
                                    ->from('tw_threads')
                                    ->group_start()
                                        ->where('a_id', $user_id)
                                        ->or_where('b_id', $user_id)
                                    ->group_end()
                                    ->get()
                                    ->result();

                if (!empty($threads)) {
                    // Replace arrow function with classic anonymous function for PHP<7.4
                    $tids = array_map(function($r){ return (int)$r->id; }, $threads);

                    if ($this->db->table_exists('tw_messages')) {
                        $this->db->where_in('thread_id', $tids)->delete('tw_messages');
                    }
                    $this->db->where_in('id', $tids)->delete('tw_threads');
                }
            }

            // Any stray messages (if thread deletion above missed something)
            if ($this->db->table_exists('tw_messages') && $this->db->field_exists('sender_id','tw_messages')) {
                $this->db->where('sender_id', (int)$user_id)->delete('tw_messages');
            }

            // Profiles
            if ($this->db->table_exists('worker_profile')) {
                $this->db->where('workerID', (int)$user_id)->delete('worker_profile');
            }
            if ($this->db->table_exists('client_profile')) {
                $this->db->where('clientID', (int)$user_id)->delete('client_profile');
            }

            // Personnel relationships
            if ($this->db->table_exists('personnel')) {
                if ($this->db->field_exists('clientID','personnel')) {
                    $this->db->where('clientID', (int)$user_id)->delete('personnel');
                }
                if ($this->db->field_exists('workerID','personnel')) {
                    $this->db->where('workerID', (int)$user_id)->delete('personnel');
                }
            }

            // Transactions
            if ($this->db->table_exists('transactions')) {
                if ($this->db->field_exists('clientID','transactions')) {
                    $this->db->where('clientID', (int)$user_id)->delete('transactions');
                }
                if ($this->db->field_exists('workerID','transactions')) {
                    $this->db->where('workerID', (int)$user_id)->delete('transactions');
                }
            }

            // Projects owned by this user (if any)
            if ($this->db->table_exists('client_projects') && $this->db->field_exists('clientID','client_projects')) {
                $this->db->where('clientID', (int)$user_id)->delete('client_projects');
            }

            // Finally: delete the user
            $this->db->where('id', (int)$user_id)->delete('users');

            if ($this->db->trans_status() === false) {
                throw new Exception('DB error.');
            }
            $this->db->trans_commit();
            return [true, 'Account permanently deleted.'];
        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Hard delete failed for user '.$user_id.' : '.$e->getMessage());
            return [false, 'Delete failed. Please contact support.'];
        } catch (Throwable $e) { // for PHP 7+
            $this->db->trans_rollback();
            log_message('error', 'Hard delete failed for user '.$user_id.' : '.$e->getMessage());
            return [false, 'Delete failed. Please contact support.'];
        }
    }
}
