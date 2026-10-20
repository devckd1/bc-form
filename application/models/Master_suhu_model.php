<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_suhu_model extends CI_Model
{
	protected $table = 'master_suhu';

	public function __construct()
	{
		parent::__construct();
	}

	public function rules()
	{
		return [
			[
				'field' => 'nama_lokasi',
				'label' => 'Nama Lokasi',
				'rules' => 'required|trim'
			],
			[
				'field' => 'standar_suhu',
				'label' => 'Standar Suhu',
				'rules' => 'required|trim'
			],
			[
				'field' => 'standar_rh',
				'label' => 'Standar RH',
				'rules' => 'trim'
			],
			[
				'field' => 'keterangan',
				'label' => 'Keterangan',
				'rules' => 'trim'
			]
		];
	}

	public function get_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('deleted_at IS NULL', null, false)
			->order_by('nama_lokasi', 'ASC')
			->get($this->table)
			->result();
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

	public function get_active_by_lokasi($nama_lokasi)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('nama_lokasi', $nama_lokasi)
			->where('is_active', 1)
			->where('deleted_at IS NULL', null, false)
			->get($this->table)
			->row();
	}

	public function insert()
	{
		$uuid = Ramsey\Uuid\Uuid::uuid4()->toString();

		$plant = $this->session->userdata('plant');

		$data = [
			'uuid'          => $uuid,
			'plant'         => $plant,
			'nama_lokasi'   => $this->input->post('nama_lokasi', TRUE),
			'standar_suhu'  => $this->input->post('standar_suhu', TRUE),
			'standar_rh'    => $this->input->post('standar_rh', TRUE),
			'keterangan'    => $this->input->post('keterangan', TRUE),
			'is_active'     => 1,
			'created_at'    => date('Y-m-d H:i:s'),
			'updated_at'    => date('Y-m-d H:i:s'),
			'deleted_at'    => null
		];

		return $this->db->insert($this->table, $data);
	}

	public function update($uuid)
	{
		$plant = $this->session->userdata('plant');

		$data = [
			'nama_lokasi'  => $this->input->post('nama_lokasi', TRUE),
			'standar_suhu' => $this->input->post('standar_suhu', TRUE),
			'standar_rh'   => $this->input->post('standar_rh', TRUE),
			'keterangan'   => $this->input->post('keterangan', TRUE),
			'updated_at'   => date('Y-m-d H:i:s')
		];

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('deleted_at IS NULL', null, false)
			->update($this->table, $data);
	}

	public function toggle_status($uuid)
	{
		$plant = $this->session->userdata('plant');

		$data = $this->get_by_uuid($uuid);

		if (!$data) {
			return false;
		}

		$status = $data->is_active == 1 ? 0 : 1;

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('deleted_at IS NULL', null, false)
			->update($this->table, [
				'is_active'  => $status,
				'updated_at' => date('Y-m-d H:i:s')
			]);
	}

	public function delete_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('deleted_at IS NULL', null, false)
			->update($this->table, [
				'deleted_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			]);
	}

	public function lokasi_exists($nama_lokasi, $exclude_uuid = null)
	{
		$plant = $this->session->userdata('plant');

		$this->db
			->where('plant', $plant)
			->where('nama_lokasi', $nama_lokasi)
			->where('deleted_at IS NULL', null, false);

		if (!empty($exclude_uuid)) {
			$this->db->where('uuid !=', $exclude_uuid);
		}

		return $this->db
			->count_all_results($this->table) > 0;
	}
}
