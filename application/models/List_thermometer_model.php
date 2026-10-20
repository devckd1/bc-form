<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class List_thermometer_model extends CI_Model
{


	public function rules()
	{
		return [
			[
				'field' => 'kode_thermometer',
				'label' => 'Kode Thermometer',
				'rules' => 'required'
			],
			[
				'field' => 'model',
				'label' => 'Model',
				'rules' => 'required'
			],
			[
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			],
			[
				'field' => 'standar',
				'label' => 'Standar',
				'rules' => 'trim'
			],
			[
				'field' => 'hasil',
				'label' => 'Hasil',
				'rules' => 'trim'
			]
		];
	}

	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();

		$data = [
			'uuid'              => $uuid,
			'username'          => $this->session->userdata('username'),
			'kode_thermometer'  => $this->input->post('kode_thermometer', true),
			'area'              => $this->input->post('area', true),
			'model'             => $this->input->post('model', true),
			'standar'           => $this->input->post('standar', true),
			'hasil'             => $this->input->post('hasil', true),
			'plant'             => $this->session->userdata('plant')
		];

		$this->db->insert('list_thermometer', $data);

		return $this->db->affected_rows() > 0;
	}

	public function update($uuid)
	{
		$plant = $this->session->userdata('plant');

		$data = [
			'kode_thermometer' => $this->input->post('kode_thermometer', true),
			'area'             => $this->input->post('area', true),
			'model'            => $this->input->post('model', true),
			'standar'          => $this->input->post('standar', true),
			'hasil'             => $this->input->post('hasil', true),
			'modified_at'       => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->update('list_thermometer', $data);

		return $this->db->affected_rows() > 0;
	}

	public function get_all()
	{
		$plant = $this->session->userdata('plant');
		$type_user = (int) $this->session->userdata('tipe_user');

		// Selain superadmin, filter plant
		if ($type_user !== 9) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('list_thermometer')->result();
	}

	public function get_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->get('list_thermometer')
			->row();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('list_thermometer');
	}

	public function get_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->order_by('kode_thermometer', 'ASC')
			->get('list_thermometer')
			->result();
	}
}
