<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_sanitasi_model extends CI_Model
{
	private $table = 'master_sanitasi';

	public function get_all()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('deleted_at IS NULL', null, false);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get($this->table)->result();
	}

	public function get_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('deleted_at IS NULL', null, false)
			->get($this->table)
			->row();
	}

	public function insert($data)
	{
		return $this->db->insert($this->table, $data);
	}

	public function update($uuid, $data)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('deleted_at IS NULL', null, false)
			->update($this->table, $data);
	}

	public function delete($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('deleted_at IS NULL', null, false)
			->update($this->table, [
				'deleted_at' => date('Y-m-d H:i:s')
			]);
	}
}
