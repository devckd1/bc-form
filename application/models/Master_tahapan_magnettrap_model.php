
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_tahapan_magnettrap_model extends CI_Model
{
    private $table = 'master_tahapan_magnettrap';

    public function get_all()
    {
        return $this->db
            ->where('plant', $this->session->userdata('plant'))
            ->order_by('nama_tahapan', 'ASC')
            ->get($this->table)
            ->result();
    }

    public function get_by_uuid($uuid)
    {
        return $this->db
            ->where('uuid', $uuid)
            ->where('plant', $this->session->userdata('plant'))
            ->get($this->table)
            ->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($uuid, $data)
    {
        return $this->db
            ->where('uuid', $uuid)
            ->where('plant', $this->session->userdata('plant'))
            ->update($this->table, $data);
    }

    public function delete($uuid)
    {
        return $this->db
            ->where('uuid', $uuid)
            ->where('plant', $this->session->userdata('plant'))
            ->delete($this->table);
    }

    public function exists_name($nama, $exclude_uuid = null)
    {
        $this->db
            ->where('plant', $this->session->userdata('plant'))
            ->where('nama_tahapan', $nama);

        if ($exclude_uuid !== null) {
            $this->db->where('uuid !=', $exclude_uuid);
        }

        return $this->db->count_all_results($this->table) > 0;
    }
}
