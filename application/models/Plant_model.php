<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Plant_model extends CI_Model
{

	public function rules()
	{
		return [
			[
				'field' => 'plant',
				'label' => 'Plant',
				'rules' => 'required'
			]
		];
	}


	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();

		$plant = $this->input->post('plant');
		$user_uuid = $this->session->userdata('username');

		$data = array(
			'uuid' => $uuid,
			'user_uuid' => $user_uuid,
			'plant' => $plant
		);

		$this->db->insert('plant', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{

		$plant = $this->input->post('plant');

		$data = array(
			'plant' => $plant,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('plant', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$data = $this->db->get('plant')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('plant', array('uuid' => $uuid))->row();
		return $data;
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('plant');
	}

	public function getUuid($data)
	{
		$this->db->select('*');
		$this->db->from('plant');
		$this->db->like('plant', $data);

		$query = $this->db->get();
		return $query->row();
	}

	public function get_by_user($user_uuid)
	{
		$this->db->select('plant.*');
		$this->db->from('user_plant');
		$this->db->join('plant', 'plant.uuid = user_plant.plant_uuid');
		$this->db->where('user_plant.user_uuid', $user_uuid);
		$this->db->order_by('plant.plant', 'ASC');

		return $this->db->get()->result();
	}

	public function user_has_plant($user_uuid, $plant_uuid)
	{
		return $this->db
			->where('user_uuid', $user_uuid)
			->where('plant_uuid', $plant_uuid)
			->get('user_plant')
			->num_rows() > 0;
	}

	public function add_user_plant($user_uuid, $plant_uuid)
	{
		// Jangan duplikat
		if ($this->user_has_plant($user_uuid, $plant_uuid)) {
			return true;
		}

		return $this->db->insert('user_plant', [
			'user_uuid'  => $user_uuid,
			'plant_uuid' => $plant_uuid,
			'created_at' => date('Y-m-d H:i:s')
		]);
	}

	public function delete_user_plant($user_uuid, $plant_uuid)
	{
		return $this->db
			->where('user_uuid', $user_uuid)
			->where('plant_uuid', $plant_uuid)
			->delete('user_plant');
	}

	public function get_all_users()
	{
		$this->db->select('pegawai.uuid, pegawai.username, pegawai.nama, pegawai.plant, plant.plant as nama_plant');
		$this->db->from('pegawai');
		$this->db->join('plant', 'plant.uuid = pegawai.plant', 'left');
		$this->db->order_by('pegawai.nama', 'ASC');

		return $this->db->get()->result();
	}

	public function get_user_access_plants($user_uuid)
	{
		$this->db->select('plant_uuid');
		$this->db->from('user_plant');
		$this->db->where('user_uuid', $user_uuid);

		$result = $this->db->get()->result();

		return array_map(function ($row) {
			return $row->plant_uuid;
		}, $result);
	}

	public function save_user_access($user_uuid, $plant_uuids)
	{
		// Hapus akses tambahan sebelumnya
		$this->db->where('user_uuid', $user_uuid);
		$this->db->delete('user_plant');

		// Tambahkan akses baru
		if (!empty($plant_uuids)) {

			foreach ($plant_uuids as $plant_uuid) {

				$this->db->insert('user_plant', [
					'user_uuid'  => $user_uuid,
					'plant_uuid' => $plant_uuid,
					'created_at' => date('Y-m-d H:i:s')
				]);
			}
		}

		return true;
	}
}
