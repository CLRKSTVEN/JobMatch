<?php defined('BASEPATH') or exit('No direct script access allowed');

class ClientProfile_model extends CI_Model
{
    protected $table = 'client_profile';
    protected $has_company_position = false;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->has_company_position = $this->db->field_exists('company_position', $this->table);
    }

    protected function sanitize_payload(array $data): array
    {
        // Only normalize fields that are PRESENT in payload.
        // Do NOT add missing fields, because that overwrites existing DB values.

        $stringFields = ['fName', 'mName', 'lName', 'phoneNo', 'companyName', 'city', 'province', 'brgy'];

        foreach ($stringFields as $f) {
            if (array_key_exists($f, $data)) {
                $data[$f] = ($data[$f] === null) ? '' : (string)$data[$f];
            }
        }

        // Avatar: only sanitize if provided; never inject it if missing
        if (array_key_exists('avatar', $data)) {
            $data['avatar'] = ($data['avatar'] === null) ? '' : (string)$data['avatar'];
        }

        // Optional nullable fields (convert empty string to NULL)
        $nullableFields = ['address', 'employer', 'business_name', 'business_location', 'id_image', 'business_permit'];
        foreach ($nullableFields as $f) {
            if (array_key_exists($f, $data) && $data[$f] === '') {
                $data[$f] = null;
            }
        }

        // Certificates: store JSON string or NULL
        if (array_key_exists('certificates', $data)) {
            if (is_array($data['certificates'])) {
                $vals = array_values(array_unique(array_filter($data['certificates'], function ($x) {
                    return $x !== null && $x !== '';
                })));
                $data['certificates'] = !empty($vals) ? json_encode($vals) : null;
            } else {
                $data['certificates'] = (isset($data['certificates']) && trim((string)$data['certificates']) !== '')
                    ? (string)$data['certificates']
                    : null;
            }
        }

        if (!$this->has_company_position && array_key_exists('company_position', $data)) {
            unset($data['company_position']);
        }

        return $data;
    }

    public function ensure_row($user_id, array $seed = [])
    {
        $user_id = (int) $user_id;

        $row = $this->db->get_where($this->table, ['clientID' => $user_id])->row();
        if ($row) return $row;

        // Base defaults
        $data = [
            'clientID'          => $user_id,
            'fName'             => $seed['fName'] ?? '',
            'mName'             => $seed['mName'] ?? '',
            'lName'             => $seed['lName'] ?? '',
            'phoneNo'           => $seed['phoneNo'] ?? '',
            'companyName'       => $seed['companyName'] ?? '',
            'city'              => $seed['city'] ?? '',
            'province'          => $seed['province'] ?? '',
            'brgy'              => $seed['brgy'] ?? '',
            'avatar'            => $seed['avatar'] ?? '',   // will be sanitized anyway
            'address'           => $seed['address'] ?? null,
            'employer'          => $seed['employer'] ?? null,
            'business_name'     => $seed['business_name'] ?? null,
            'business_location' => $seed['business_location'] ?? null,
            'id_image'          => $seed['id_image'] ?? null,
            'certificates'      => $seed['certificates'] ?? null,
            'business_permit'   => $seed['business_permit'] ?? null,
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'        => date('Y-m-d H:i:s'),
        ];

        if ($this->has_company_position) {
            $data['company_position'] = $seed['company_position'] ?? null;
        }

        // Merge extra seed data (but sanitize afterward so NULL can't break NOT NULL cols)
        $data = array_merge($data, $seed);

        $data = $this->sanitize_payload($data);

        $this->db->insert($this->table, $data);

        return $this->db->get_where($this->table, ['clientID' => $user_id])->row();
    }

    public function get($user_id)
    {
        $columns = [
            'u.id as user_id',
            'u.email',
            'u.first_name',
            'u.last_name',
            'u.role',
            'c.clientID',
            'c.fName',
            'c.mName',
            'c.lName',
            'c.phoneNo',
            'c.companyName'
        ];

        if ($this->has_company_position) {
            $columns[] = 'c.company_position';
        }

        $columns = array_merge($columns, [
            'c.city',
            'c.province',
            'c.brgy',
            'c.avatar',
            'c.address',
            'c.employer',
            'c.business_name',
            'c.business_location',
            'c.id_image',
            'c.certificates',
            'c.business_permit',
            'c.created_at',
            'c.updated_at'
        ]);

        $this->db->select(implode(', ', $columns));
        $this->db->from('users u');
        $this->db->join($this->table . ' c', 'c.clientID = u.id', 'left');
        $this->db->where('u.id', (int)$user_id);

        return $this->db->get()->row();
    }

    public function is_complete($p)
    {
        if (!$p) return false;

        $hasNames = (trim((string)$p->fName) !== '' || trim((string)$p->first_name) !== '')
            && (trim((string)$p->lName) !== '' || trim((string)$p->last_name)  !== '');

        $hasId = !empty($p->id_image);

        return $hasNames && $hasId;
    }

    public function upsert(int $user_id, array $data = [])
    {
        $user_id = (int) $user_id;

        $allowed = [
            'fName',
            'mName',
            'lName',
            'phoneNo',
            'companyName',
            'city',
            'province',
            'brgy',
            'avatar',
            'address',
            'employer',
            'business_name',
            'business_location',
            'id_image',
            'certificates',
            'business_permit'
        ];

        if ($this->has_company_position) $allowed[] = 'company_position';

        $clean = array_intersect_key($data, array_flip($allowed));
        $clean = $this->sanitize_payload($clean);

        $exists = $this->db->select('clientID')
            ->from($this->table)
            ->where('clientID', $user_id)
            ->limit(1)->get()->num_rows() > 0;

        // Always set timestamps (but don't let it trick "changed" logic)
        $now = date('Y-m-d H:i:s');

        if ($exists) {
            if (empty($clean)) {
                return ['ok' => true, 'changed' => false, 'row' => $this->get($user_id)];
            }

            $clean['updated_at'] = $now;

            $ok = $this->db->where('clientID', $user_id)->update($this->table, $clean);

            if (!$ok) {
                $err = $this->db->error();
                return ['ok' => false, 'changed' => false, 'error' => $err['message'] ?? 'DB update failed'];
            }

            $changed = ($this->db->affected_rows() > 0);

            return ['ok' => true, 'changed' => $changed, 'row' => $this->get($user_id)];
        }

        // Insert path
        $defaults = [
            'fName' => '',
            'mName' => '',
            'lName' => '',
            'phoneNo' => '',
            'companyName' => '',
            'city' => '',
            'province' => '',
            'brgy' => '',
            'avatar' => '',
            'address' => null,
            'employer' => null,
            'business_name' => null,
            'business_location' => null,
            'id_image' => null,
            'certificates' => null,
            'business_permit' => null,
        ];
        if ($this->has_company_position) $defaults['company_position'] = null;

        $row = array_merge($defaults, $clean);
        $row = $this->sanitize_payload($row);

        $row['clientID']   = $user_id;
        $row['created_at'] = $now;
        $row['updated_at'] = $now;

        $ok = $this->db->insert($this->table, $row);

        if (!$ok) {
            $err = $this->db->error();
            return ['ok' => false, 'changed' => false, 'error' => $err['message'] ?? 'DB insert failed'];
        }

        return ['ok' => true, 'changed' => true, 'row' => $this->get($user_id)];
    }


    /* =======================
     * Notifications
     * ======================= */

    public function add_notification(int $user_id, int $actor_id, string $type, string $title, string $body = '', string $link = '')
    {
        $data = [
            'user_id'    => $user_id,
            'actor_id'   => $actor_id,
            'type'       => $type,
            'title'      => $title,
            'body'       => $body,
            'link'       => $link,
            'is_read'    => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        return $this->db->insert('tw_notifications', $data);
    }

    public function get_notifications(int $user_id, int $limit = 10, int $offset = 0, array $types = null)
    {
        $this->db->select('n.*, u.first_name AS actor_fname, u.last_name AS actor_lname')
            ->from('tw_notifications n')
            ->join('users u', 'u.id = n.actor_id', 'left')
            ->where('n.user_id', $user_id);

        if ($types && count($types)) {
            $this->db->where_in('n.type', $types);
        }

        return $this->db->order_by('n.created_at', 'DESC')
            ->limit($limit, $offset)
            ->get()
            ->result();
    }

    public function unread_count(int $user_id, array $types = null): int
    {
        $this->db->from('tw_notifications')
            ->where('user_id', $user_id)
            ->where('is_read', 0);

        if ($types && count($types)) {
            $this->db->where_in('type', $types);
        }

        return (int) $this->db->count_all_results();
    }

    public function mark_read(int $id, int $user_id): bool
    {
        $data = ['is_read' => 1];

        if ($this->db->field_exists('read_at', 'tw_notifications')) {
            $data['read_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where('id', $id)
            ->where('user_id', $user_id)
            ->update('tw_notifications', $data);

        return $this->db->affected_rows() > 0;
    }

    public function mark_read_by_actor(int $user_id, int $actor_id, array $types = []): int
    {
        $data = ['is_read' => 1];

        if ($this->db->field_exists('read_at', 'tw_notifications')) {
            $data['read_at'] = date('Y-m-d H:i:s');
        }

        $this->db->where('user_id', $user_id)
            ->where('actor_id', $actor_id)
            ->where('is_read', 0);

        if (!empty($types)) {
            $this->db->where_in('type', $types);
        }

        $this->db->update('tw_notifications', $data);

        return $this->db->affected_rows();
    }
}
