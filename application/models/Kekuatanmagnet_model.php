
<?php
defined('BASEPATH') or exit('No direct script access allowed');

date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Kekuatanmagnet_model extends CI_Model
{
	private $table = 'kekuatan_mt';

	public function rules()
	{
		return [
			[
				'field' => 'date',
				'label' => 'Date',
				'rules' => 'required'
			],
			[
				'field' => 'nama_alat',
				'label' => 'Things',
				'rules' => 'required'
			],
			[
				'field' => 'nilai',
				'label' => 'Measurement Value',
				'rules' => 'required'
			],
			[
				'field' => 'keterangan',
				'label' => 'Notes'
			],
			[
				'field' => 'catatan',
				'label' => 'Notes'
			]
		];
	}

	/*
    |--------------------------------------------------------------------------
    | INSERT QC + SNAPSHOT AUDIT
    |--------------------------------------------------------------------------
    */

	public function insert()
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$uuid = Uuid::uuid4()->toString();
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$data = [
			'uuid' => $uuid,
			'is_audit' => 0,
			'source_uuid' => null,
			'username' => $username,
			'plant' => $plant,
			'date' => $this->input->post('date'),
			'nama_alat' => $this->input->post('nama_alat'),
			'nilai' => $this->input->post('nilai'),
			'keterangan' => $this->input->post('keterangan'),
			'catatan' => $this->input->post('catatan'),
			'status_produksi' => '1',
			'nama_produksi' => $nama_produksi,
			'status_spv' => '0'
		];

		$this->db->trans_begin();

		$this->db->insert($this->table, $data);

		if ($this->db->affected_rows() <= 0) {
			$this->db->trans_rollback();
			return false;
		}

		$audit_data = $data;
		$audit_data['uuid'] = Uuid::uuid4()->toString();
		$audit_data['is_audit'] = 1;
		$audit_data['source_uuid'] = $uuid;

		$this->db->insert($this->table, $audit_data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}

	/*
    |--------------------------------------------------------------------------
    | UPDATE QC
    |--------------------------------------------------------------------------
    */

	public function update($uuid)
	{
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$old_data = $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->where('plant', $plant)
			->get($this->table)
			->row_array();

		if (!$old_data) {
			return false;
		}

		$data = [
			'username' => $username,
			'date' => $this->input->post('date'),
			'nama_alat' => $this->input->post('nama_alat'),
			'nilai' => $this->input->post('nilai'),
			'keterangan' => $this->input->post('keterangan'),
			'catatan' => $this->input->post('catatan'),
			'modified_at' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->where('plant', $plant)
			->update($this->table, $data);

		if ($this->db->affected_rows() > 0) {
			$new_data = $this->db
				->where('uuid', $uuid)
				->where('is_audit', 0)
				->get($this->table)
				->row_array();

			if (isset($this->activity_logger)) {
				$this->activity_logger->log_activity(
					'update',
					'kekuatan_mt_logs',
					$uuid,
					$old_data,
					$new_data
				);
			}

			return true;
		}

		// Update dengan nilai yang sama tetap dianggap berhasil.
		$current = $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->where('plant', $plant)
			->get($this->table)
			->row();

		return $current !== null;
	}

	/*
    |--------------------------------------------------------------------------
    | AUDIT - LIST
    |--------------------------------------------------------------------------
    */

	public function get_data_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('is_audit', 1);
		$this->db->where('plant', $plant);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get($this->table)->result();
	}

	/*
    |--------------------------------------------------------------------------
    | AUDIT - DETAIL / EDIT
    |--------------------------------------------------------------------------
    */

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 1)
			->where('plant', $plant)
			->get($this->table)
			->row();
	}

	/*
    |--------------------------------------------------------------------------
    | AUDIT - UPDATE SNAPSHOT ONLY
    |--------------------------------------------------------------------------
    */

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		$audit = $this->db
			->where('uuid', $uuid)
			->where('is_audit', 1)
			->where('plant', $plant)
			->get($this->table)
			->row();

		if (!$audit) {
			return false;
		}

		$data = [
			'date' => $this->input->post('date'),
			'nama_alat' => $this->input->post('nama_alat'),
			'nilai' => $this->input->post('nilai'),
			'keterangan' => $this->input->post('keterangan'),
			'catatan' => $this->input->post('catatan'),
			'modified_at' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('is_audit', 1)
			->where('plant', $plant)
			->update($this->table, $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		}

		// Data tidak berubah, tetapi UUID snapshot masih valid.
		return $this->get_audit_by_uuid($uuid) !== null;
	}

	/*
    |--------------------------------------------------------------------------
    | VERIFIKASI
    |--------------------------------------------------------------------------
    */

	public function rules_verifikasi()
	{
		return [
			[
				'field' => 'status_spv',
				'label' => 'Date',
				'rules' => 'required'
			],
			[
				'field' => 'catatan_spv',
				'label' => 'Notes'
			]
		];
	}

	public function verifikasi_update($uuid)
	{
		$plant = $this->session->userdata('plant');

		$data = [
			'nama_spv' => $this->session->userdata('username'),
			'status_spv' => $this->input->post('status_spv'),
			'catatan_spv' => $this->input->post('catatan_spv'),
			'tgl_update_spv' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->where('plant', $plant)
			->update($this->table, $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->where('plant', $plant)
			->get($this->table)
			->row() !== null;
	}

	/*
    |--------------------------------------------------------------------------
    | DIKETAHUI PRODUKSI
    |--------------------------------------------------------------------------
    */

	public function rules_diketahui()
	{
		return [
			[
				'field' => 'status_produksi',
				'label' => 'Status',
				'rules' => 'required'
			],
			[
				'field' => 'catatan_produksi',
				'label' => 'Notes'
			]
		];
	}

	public function diketahui_update($uuid)
	{
		$plant = $this->session->userdata('plant');

		$data = [
			'nama_produksi' => $this->session->userdata('username'),
			'status_produksi' => $this->input->post('status_produksi'),
			'catatan_produksi' => $this->input->post('catatan_produksi'),
			'tgl_update_produksi' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->where('plant', $plant)
			->update($this->table, $data);

		if ($this->db->affected_rows() > 0) {
			return true;
		}

		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->where('plant', $plant)
			->get($this->table)
			->row() !== null;
	}

	/*
    |--------------------------------------------------------------------------
    | DATA QC
    |--------------------------------------------------------------------------
    */

	public function get_all()
	{
		$this->db->where('is_audit', 0);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get($this->table)->result();
	}

	public function get_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->where('plant', $plant)
			->get($this->table)
			->row();
	}

	public function get_by_uuid_kekuatanmagnet($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}

		$this->db->where_in('uuid', $uuid_array);
		$this->db->where('is_audit', 0);

		$query = $this->db->get($this->table);

		return $query->num_rows() > 0
			? $query->result()
			: false;
	}

	public function get_by_uuid_kekuatanmagnet_verif($uuid_array)
	{
		if (empty($uuid_array)) {
			return null;
		}

		$this->db->select(
			'nama_spv, tgl_update_spv, username, date, nama_produksi, status_produksi, tgl_update_produksi'
		);
		$this->db->where_in('uuid', $uuid_array);
		$this->db->where('is_audit', 0);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		return $this->db->get($this->table)->row();
	}

	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('created_at', 'DESC');

		return $this->db->get($this->table)->result();
	}

	public function delete_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->where('plant', $plant)
			->delete($this->table);
	}

	/*
    |--------------------------------------------------------------------------
    | DATA BERDASARKAN TANGGAL
    |--------------------------------------------------------------------------
    */

	public function get_by_date($tanggal, $plant = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('date', 'ASC');

		$query = $this->db->get($this->table);

		return $query->num_rows() > 0
			? $query->result()
			: false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select(
			'nama_spv, tgl_update_spv, username, date, nama_produksi, status_produksi, tgl_update_produksi'
		);
		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		return $this->db->get($this->table)->row();
	}
}
