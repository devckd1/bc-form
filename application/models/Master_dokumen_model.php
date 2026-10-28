
<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ramsey\Uuid\Uuid;

class Master_dokumen_model extends CI_Model
{
	private $table = 'master_dokumen';

	public function rules()
	{
		return [
			[
				'field' => 'nomor_dokumen',
				'label' => 'Nomor Dokumen',
				'rules' => 'required|trim'
			],
			[
				'field' => 'nama_dokumen',
				'label' => 'Nama Dokumen',
				'rules' => 'required|trim'
			],
			[
				'field' => 'tanggal_terbit',
				'label' => 'Tanggal Terbit',
				'rules' => 'required|trim'
			],
			[
				'field' => 'revisi',
				'label' => 'Revisi',
				'rules' => 'required|integer|greater_than_equal_to[0]'
			],
			[
				'field' => 'tanggal_revisi',
				'label' => 'Tanggal Revisi',
				'rules' => 'trim'
			]
		];
	}

	public function get_all()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('deleted_at IS NULL', null, false);
		$this->db->order_by('nomor_dokumen', 'ASC');

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
		$tanggal_revisi = $this->input->post('tanggal_revisi', true);

		$data = [
			'uuid'           => Uuid::uuid4()->toString(),
			'plant'          => $this->session->userdata('plant'),
			'nomor_dokumen'  => $this->input->post('nomor_dokumen', true),
			'nama_dokumen'   => $this->input->post('nama_dokumen', true),
			'tanggal_terbit' => $this->input->post('tanggal_terbit', true),
			'revisi'         => $this->input->post('revisi', true),
			'tanggal_revisi' => !empty($tanggal_revisi)
				? $tanggal_revisi
				: null
		];

		return $this->db->insert($this->table, $data);
	}

	public function update($uuid)
	{
		$plant = $this->session->userdata('plant');
		$tanggal_revisi = $this->input->post('tanggal_revisi', true);

		$data = [
			'nomor_dokumen'  => $this->input->post('nomor_dokumen', true),
			'nama_dokumen'   => $this->input->post('nama_dokumen', true),
			'tanggal_terbit' => $this->input->post('tanggal_terbit', true),
			'revisi'         => $this->input->post('revisi', true),
			'tanggal_revisi' => !empty($tanggal_revisi)
				? $tanggal_revisi
				: null
		];

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
