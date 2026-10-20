<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ramsey\Uuid\Uuid;

class Master_chiller_model extends CI_Model
{
	private $table = 'master_chiller';

	public function rules()
	{
		return [
			[
				'field' => 'nama_chiller',
				'label' => 'Nama Chiller',
				'rules' => 'required|trim'
			],
			[
				'field' => 'range_standar',
				'label' => 'Range Standar',
				'rules' => 'required|trim'
			],
			[
				'field' => 'satuan',
				'label' => 'Satuan',
				'rules' => 'required|trim'
			],
			[
				'field' => 'keterangan',
				'label' => 'Keterangan',
				'rules' => 'trim'
			]
		];
	}

	public function get_all()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('deleted_at IS NULL', null, false);
		$this->db->order_by('nama_chiller', 'ASC');

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

	public function insert()
	{
		$data = [
			'uuid'          => Uuid::uuid4()->toString(),
			'plant'         => $this->session->userdata('plant'),
			'nama_chiller'  => $this->input->post('nama_chiller', true),
			'range_standar' => $this->input->post('range_standar', true),
			'satuan'        => $this->input->post('satuan', true),
			'keterangan'    => $this->input->post('keterangan', true)
		];

		return $this->db->insert($this->table, $data);
	}

	public function update($uuid)
	{
		$plant = $this->session->userdata('plant');

		$data = [
			'nama_chiller'  => $this->input->post('nama_chiller', true),
			'range_standar' => $this->input->post('range_standar', true),
			'satuan'        => $this->input->post('satuan', true),
			'keterangan'    => $this->input->post('keterangan', true)
		];

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->update($this->table, $data);
	}

	public function delete($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->update($this->table, [
				'deleted_at' => date('Y-m-d H:i:s')
			]);
	}
}
