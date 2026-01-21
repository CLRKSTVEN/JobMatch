<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Message_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_or_create_thread(int $u1, int $u2)
    {
        if ($u1 === $u2) return null;
        $a = min($u1, $u2);
        $b = max($u1, $u2);

        $row = $this->db->limit(1)->get_where('tw_threads', ['a_id'=>$a, 'b_id'=>$b])->row();
        if ($row) return $row;

        $now = date('Y-m-d H:i:s');
        $this->db->insert('tw_threads', ['a_id'=>$a, 'b_id'=>$b, 'created_at'=>$now, 'updated_at'=>$now]);
        $id = (int)$this->db->insert_id();

        return $this->db->limit(1)->get_where('tw_threads', ['id'=>$id])->row();
    }

    public function get_thread_for_user(int $thread_id, int $uid)
    {
        return $this->db->where('id', $thread_id)
            ->group_start()
                ->where('a_id', $uid)
                ->or_where('b_id', $uid)
            ->group_end()
            ->limit(1)
            ->get('tw_threads')->row();
    }

    public function list_messages(int $thread_id, int $limit=50, int $after_id=0)
    {
        $this->db->from('tw_messages')->where('thread_id', $thread_id);
        if ($after_id > 0) $this->db->where('id >', $after_id);

        return $this->db->order_by('id','ASC')->limit($limit)->get()->result();
    }
public function list_messages_for_user(int $thread_id, int $viewer_id, int $limit=50, int $after_id=0)
{
    $this->db->from('tw_messages')->where('thread_id', $thread_id);
    if ($after_id > 0) $this->db->where('id >', $after_id);

    $hasSender = $this->db->field_exists('deleted_by_sender_at', 'tw_messages');
    $hasRcpt   = $this->db->field_exists('deleted_by_recipient_at', 'tw_messages');

    if ($hasSender && $hasRcpt) {
        $this->db->group_start()
            ->group_start()
                ->where('sender_id', $viewer_id)
                ->where('(deleted_by_sender_at IS NULL)', null, false)
            ->group_end()
            ->or_group_start()
                ->where('sender_id <>', $viewer_id)
                ->where('(deleted_by_recipient_at IS NULL)', null, false)
            ->group_end()
        ->group_end();
    }

    return $this->db->order_by('id','ASC')->limit($limit)->get()->result();
}

    public function add_message(int $thread_id, int $sender_id, string $body)
    {
        $now = date('Y-m-d H:i:s');

        $data = [
            'thread_id'  => $thread_id,
            'sender_id'  => $sender_id,
            'body'       => $body,
            'created_at' => $now,
        ];

        if (!$this->db->insert('tw_messages', $data)) {
            return null;
        }

        $id = (int)$this->db->insert_id();
        if ($id <= 0) {
            $row = $this->db->where([
                        'thread_id'  => $thread_id,
                        'sender_id'  => $sender_id,
                        'created_at' => $now,
                    ])->order_by('id','DESC')->limit(1)->get('tw_messages')->row();
            if (!$row) return null;
            $this->db->where('id', $thread_id)->update('tw_threads', ['updated_at' => date('Y-m-d H:i:s')]);
            return $row;
        }

        $this->db->where('id', $thread_id)->update('tw_threads', ['updated_at' => date('Y-m-d H:i:s')]);

        return $this->db->get_where('tw_messages', ['id' => $id])->row();
    }

    public function mark_read(int $thread_id, int $uid): int
    {
        $this->db->where('thread_id', $thread_id)
                 ->where('sender_id <>', $uid)
                 ->where('read_at IS NULL', null, false)
                 ->update('tw_messages', ['read_at'=>date('Y-m-d H:i:s')]);
        return $this->db->affected_rows();
    }

    public function unread_messages_count(int $uid): int
    {
        $row = $this->db->select('COUNT(*) AS c')
            ->from('tw_messages m')
            ->join('tw_threads t', 't.id = m.thread_id', 'inner')
            ->where("(t.a_id = {$uid} OR t.b_id = {$uid})", null, false)
            ->where('m.sender_id <>', $uid)
            ->where('m.read_at IS NULL', null, false)
            ->get()->row();
        return (int)($row->c ?? 0);
    }

    public function latest_threads(int $uid, int $limit = 10): array
    {
        $limit = max(1, min(50, $limit));

        $sub = "(SELECT m1.* 
                 FROM tw_messages m1 
                 JOIN (SELECT thread_id, MAX(id) AS max_id 
                       FROM tw_messages GROUP BY thread_id) x 
                   ON x.max_id = m1.id) lm";

        $this->db->select("t.id AS thread_id, t.a_id, t.b_id, lm.body AS last_body, lm.created_at AS last_time,
                           u.id AS other_id, u.first_name, u.last_name, u.email")
                 ->from('tw_threads t')
                 ->join($sub, 'lm.thread_id = t.id', 'left')
                 ->join('users u', "(CASE WHEN t.a_id = {$uid} THEN t.b_id ELSE t.a_id END) = u.id", 'left', false)
                 ->where("(t.a_id = {$uid} OR t.b_id = {$uid})", null, false)
                 ->order_by('lm.id', 'DESC')
                 ->limit($limit);

        $rows = $this->db->get()->result();

        foreach ($rows as $r) {
            $r->unread = (int)$this->db->from('tw_messages')
                ->where('thread_id', (int)$r->thread_id)
                ->where('sender_id <>', $uid)
                ->where('read_at IS NULL', null, false)
                ->count_all_results();
        }
        return $rows;
    }
    public function delete_message_for_user(int $message_id, int $viewer_id): array
{
    $row = $this->db->get_where('tw_messages', ['id'=>$message_id])->row();
    if (!$row) return [false, 'Message not found'];

    $t = $this->db->select('*')->from('tw_threads')
        ->where('id', (int)$row->thread_id)
        ->group_start()->where('a_id', $viewer_id)->or_where('b_id', $viewer_id)->group_end()
        ->limit(1)->get()->row();
    if (!$t) return [false, 'Not authorized'];

    $hasSender = $this->db->field_exists('deleted_by_sender_at', 'tw_messages');
    $hasRcpt   = $this->db->field_exists('deleted_by_recipient_at', 'tw_messages');

    if ($hasSender && $hasRcpt) {
        if ((int)$row->sender_id === $viewer_id) {
            $this->db->where('id', $message_id)->update('tw_messages', ['deleted_by_sender_at'=>date('Y-m-d H:i:s')]);
        } else {
            $this->db->where('id', $message_id)->update('tw_messages', ['deleted_by_recipient_at'=>date('Y-m-d H:i:s')]);
        }
        return [true, 'Deleted'];
    }

    if ((int)$row->sender_id !== $viewer_id) return [false, 'You can only delete your own message'];
    $this->db->where('id', $message_id)->delete('tw_messages');
    return [true, 'Deleted'];
}

}
