<?php defined('BASEPATH') OR exit('No direct script access allowed');

class ClientProfile_model extends CI_Model
{
    protected $has_company_position = false;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->has_company_position = $this->db->field_exists('company_position', 'client_profile');
    }

    protected $table = 'client_profile';

    public function ensure_row($user_id, array $seed = [])
    {
        $user_id = (int) $user_id;
        $row = $this->db->get_where($this->table, ['clientID' => $user_id])->row();
        if ($row) return $row;

        if (!$this->has_company_position && array_key_exists('company_position', $seed)) {
            unset($seed['company_position']);
        }

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
            'avatar'            => $seed['avatar'] ?? '',
            'address'           => $seed['address'] ?? null,
            'employer'          => $seed['employer'] ?? null,
            'business_name'     => $seed['business_name'] ?? null,
            'business_location' => $seed['business_location'] ?? null,
            'id_image'          => $seed['id_image'] ?? null,
            'certificates'      => $seed['certificates'] ?? null, 
            'business_permit'   => $seed['business_permit'] ?? null,
        ];
        if ($this->has_company_position) {
            $data['company_position'] = $seed['company_position'] ?? null;
        }
        $data = array_merge($data, $seed);

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
        $this->db->join($this->table.' c', 'c.clientID = u.id', 'left');
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
        'fName','mName','lName','phoneNo','companyName',
        'city','province','brgy','avatar','address','employer',
        'business_name','business_location','id_image',
        'certificates','business_permit'
    ];
    if ($this->has_company_position) {
        $allowed[] = 'company_position';
    }
    $clean = array_intersect_key($data, array_flip($allowed));

    if (isset($clean['certificates']) && is_array($clean['certificates'])) {
        $clean['certificates'] = json_encode(array_values(array_unique($clean['certificates'])));
    }

    $exists = $this->db->select('clientID')
        ->from($this->table)
        ->where('clientID', $user_id)
        ->limit(1)->get()->num_rows() > 0;

    if ($exists) {
        if (empty($clean)) {
            return $this->get($user_id);
        }
        $clean['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('clientID', $user_id)->update($this->table, $clean);
        return $this->get($user_id);
    } else {
        $defaults = [
            'fName' => '', 'mName' => '', 'lName' => '',
            'phoneNo' => '', 'companyName' => '',
            'city' => '', 'province' => '', 'brgy' => '',
            'avatar' => '', 'address' => null, 'employer' => null,
            'business_name' => null, 'business_location' => null,
            'id_image' => null, 'certificates' => null, 'business_permit' => null,
        ];
        if ($this->has_company_position) {
            $defaults['company_position'] = null;
        }

        $row = array_merge($defaults, $clean);
        if (isset($row['certificates']) && is_array($row['certificates'])) {
            $row['certificates'] = json_encode($row['certificates']);
        }

        $row['clientID']   = $user_id;
        $row['created_at'] = date('Y-m-d H:i:s');
        $row['updated_at'] = date('Y-m-d H:i:s');

        $this->db->insert($this->table, $row);
        return $this->get($user_id);
    }
}

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

    return (int)$this->db->count_all_results();
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
    if (!empty($types)) { $this->db->where_in('type', $types); }

    $this->db->update('tw_notifications', $data);
    return $this->db->affected_rows();
}



}
