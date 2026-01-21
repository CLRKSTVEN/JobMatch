<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Peso_model extends CI_Model
{
    public function mine($posterId)
    {
        return $this->db->from('jobs')
            ->where('poster_id', (int)$posterId)
            ->order_by('created_at','DESC')
            ->get()->result_array();
    }

    public function find($id, $posterId)
    {
        return $this->db->from('jobs')
            ->where('id', (int)$id)
            ->where('poster_id', (int)$posterId)
            ->get()->row_array();
    }

    public function create($posterId, $data, ?array $media = null)
    {
        $row = [
            'post_type'     => $data['post_type'] ?? 'hire',
            'poster_id'     => (int)$posterId,
            'title'         => $data['title'],
            'description'   => $data['description'] ?? null,
            'website_url'   => ($data['website_url'] ?? '') !== '' ? trim($data['website_url']) : null,
            'media_json'    => $media ? json_encode($media, JSON_UNESCAPED_UNICODE) : null,
            'location_text' => $data['location_text'] ?? null,
            'price_min'     => $data['price_min'] !== '' ? (float)$data['price_min'] : null,
            'price_max'     => $data['price_max'] !== '' ? (float)$data['price_max'] : null,
            'visibility'    => in_array(($data['visibility'] ?? 'public'), ['public','followers'], true) ? $data['visibility'] : 'public',
            'status'        => 'open',
        ];
        $this->db->insert('jobs', $row);
        return (int)$this->db->insert_id();
    }

    public function update_job($id, $posterId, $data, ?array $mediaAction = null)
    {
        $row = [
            'post_type'     => $data['post_type'] ?? 'hire',
            'title'         => $data['title'],
            'description'   => $data['description'] ?? null,
            'website_url'   => ($data['website_url'] ?? '') !== '' ? trim($data['website_url']) : null,
            'location_text' => $data['location_text'] ?? null,
            'price_min'     => $data['price_min'] !== '' ? (float)$data['price_min'] : null,
            'price_max'     => $data['price_max'] !== '' ? (float)$data['price_max'] : null,
            'visibility'    => in_array(($data['visibility'] ?? 'public'), ['public','followers'], true) ? $data['visibility'] : 'public',
        ];
        if ($mediaAction) {
            $mode = $mediaAction['mode'] ?? '';
            if ($mode === 'set' && isset($mediaAction['data']) && is_array($mediaAction['data'])) {
                $row['media_json'] = json_encode($mediaAction['data'], JSON_UNESCAPED_UNICODE);
            } elseif ($mode === 'remove') {
                $row['media_json'] = null;
            }
        }
        return $this->db->where('id', (int)$id)
            ->where('poster_id', (int)$posterId)
            ->update('jobs', $row);
    }

    public function toggle_status($id, $posterId)
    {
        $job = $this->find($id, $posterId);
        if (!$job) return false;
        $new = ($job['status'] === 'open') ? 'closed' : 'open';
        return $this->db->where('id',(int)$id)
            ->where('poster_id',(int)$posterId)
            ->update('jobs', ['status'=>$new]);
    }

    public function delete_job($id, $posterId)
    {
        return $this->db->where('id',(int)$id)
            ->where('poster_id',(int)$posterId)
            ->delete('jobs');
    }

    public function latest_public_open($limit = 10)
    {
        return $this->db->from('jobs')
            ->select('id,title,description,website_url,location_text,price_min,price_max,created_at,media_json')
            ->where('status','open')
            ->where('visibility','public')
            ->order_by('created_at','DESC')
            ->limit((int)$limit)
            ->get()->result_array();
    }
}
