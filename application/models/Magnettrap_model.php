<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Magnettrap_model extends CI_Model
{

	public function rules()
	{
		return [
			[
				'field' => 'date',
				'label' => 'Date',
				'rules' => 'required'
			],
			[
				'field' => 'shift',
				'label' => 'Shift',
				'rules' => 'required'
			],
			[
				'field' => 'time',
				'label' => 'Time',
				'rules' => 'required'
			],
			[
				'field' => 'tahapan',
				'label' => 'Step of'
			],
			[
				'field' => 'kontaminasi',
				'label' => 'Type of Contamination'
			],
			[
				'field' => 'bukti',
				'label' => 'Evidence',
				'rules' => 'callback_file_check'
			],
			[
				'field' => 'analisis',
				'label' => 'Analysis'
			],
			[
				'field' => 'tindakan',
				'label' => 'Action'
			],
			[
				'field' => 'verifikasi',
				'label' => 'Verification'
			],
			[
				'field' => 'catatan',
				'label' => 'Notes'
			],
			[
				'field' => 'keterangan',
				'label' => 'Notes'
			]
		];
	}

	// ======================= Insert Data =======================
	public function insert($file_name)
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$uuid = Uuid::uuid4()->toString();

		$data = [
			'uuid' => $uuid,
			'is_audit' => 0,
			'source_uuid' => null,
			'username' => $username,
			'plant' => $plant,
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'time' => $this->input->post('time'),
			'tahapan' => $this->input->post('tahapan'),
			'kontaminasi' => $this->input->post('kontaminasi'),
			'analisis' => $this->input->post('analisis'),
			'tindakan' => $this->input->post('tindakan'),
			'verifikasi' => $this->input->post('verifikasi'),
			'catatan' => $this->input->post('catatan'),
			'keterangan' => $this->input->post('keterangan'),
			'bukti' => $file_name,
			'status_enginer' => '1',
			'nama_enginer' => $nama_produksi,
			'status_spv' => '0',
			'created_at' => date('Y-m-d H:i:s')
		];

		$this->db->trans_begin();

		// Simpan data QC
		$this->db->insert('magnet_trap', $data);

		// Buat snapshot Audit dari data QC
		$audit_data = $data;
		$audit_data['uuid'] = Uuid::uuid4()->toString();
		$audit_data['is_audit'] = 1;
		$audit_data['source_uuid'] = $uuid;

		$this->db->insert('magnet_trap', $audit_data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}

	// ======================= Update Data =======================
	public function update($uuid, $file_name)
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		$old_data = $this->db->get('magnet_trap')->row_array();

		if (!$old_data) {
			return false;
		}

		$data = [
			'username' => $this->session->userdata('username'),
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'time' => $this->input->post('time'),
			'tahapan' => $this->input->post('tahapan'),
			'kontaminasi' => $this->input->post('kontaminasi'),
			'analisis' => $this->input->post('analisis'),
			'tindakan' => $this->input->post('tindakan'),
			'verifikasi' => $this->input->post('verifikasi'),
			'catatan' => $this->input->post('catatan'),
			'keterangan' => $this->input->post('keterangan'),
			'bukti' => $file_name,
			'modified_at' => date('Y-m-d H:i:s')
		];

		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		$this->db->update('magnet_trap', $data);

		if ($this->db->trans_status() === false) {
			return false;
		}

		if (isset($this->activity_logger)) {
			$this->activity_logger->log_activity(
				'update',
				'magnet_trap_logs',
				$uuid,
				$old_data,
				$data
			);
		}

		return $this->db->affected_rows() >= 0;
	}

	public function rules_verifikasi()
	{
		return [
			[
				'field' => 'status_spv',
				'label' => 'Status',
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

		$nama_spv = $this->session->userdata('username');
		$status_spv = $this->input->post('status_spv');
		$catatan_spv = $this->input->post('catatan_spv');

		$data = array(
			'nama_spv' => $nama_spv,
			'status_spv' => $status_spv,
			'catatan_spv' => $catatan_spv,
			'tgl_update_spv' => date("Y-m-d H:i:s")
		);

		$this->db->update('magnet_trap', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function rules_diketahui()
	{
		return [
			[
				'field' => 'status_enginer',
				'label' => 'Status',
				'rules' => 'required'
			],
			[
				'field' => 'catatan_enginer',
				'label' => 'Notes'
			]

		];
	}

	public function diketahui_update($uuid)
	{

		$nama_enginer = $this->session->userdata('username');
		$status_enginer = $this->input->post('status_enginer');
		$catatan_enginer = $this->input->post('catatan_enginer');

		$data = array(
			'nama_enginer' => $nama_enginer,
			'status_enginer' => $status_enginer,
			'catatan_enginer' => $catatan_enginer,
			'tgl_update_enginer' => date("Y-m-d H:i:s")
		);

		$this->db->update('magnet_trap', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('magnet_trap')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db
			->where('uuid', $uuid)
			->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		return $this->db->get('magnet_trap')->row();
	}

	public function get_by_uuid_magnettrap($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('magnet_trap');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_magnettrap_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, date, username, shift, nama_enginer, status_enginer, tgl_update_enginer');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('magnet_trap');

		$data_magnet_trap = $query->row();
		return $data_magnet_trap;
	}

	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		return $this->db
			->order_by('created_at', 'DESC')
			->get('magnet_trap')
			->result();
	}

	public function delete_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db
			->where('uuid', $uuid)
			->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		return $this->db->delete('magnet_trap');
	}

	public function get_by_date($tanggal, $plant = null, $shift = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('date', 'ASC');
		$query = $this->db->get('magnet_trap');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null, $shift = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, date, username, shift, nama_enginer, status_enginer, tgl_update_enginer');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('magnet_trap');

		return $query->row();
	}

	public function get_by_month($tahun, $bulan, $plant)
	{
		return $this->db
			->where('YEAR(date)', $tahun)
			->where('MONTH(date)', $bulan)
			->where('plant', $plant)
			->order_by('date', 'ASC')
			->get('magnet_trap')
			->result();
	}

	public function get_data_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('magnet_trap')
			->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('magnet_trap')
			->row();
	}

	public function get_audit_by_date($date)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('date', $date)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('magnet_trap')
			->result();
	}

	public function update_audit($uuid, $file_name)
	{
		$plant = $this->session->userdata('plant');

		$record = $this->get_audit_by_uuid($uuid);

		if (!$record) {
			return false;
		}

		$data = [
			'username' => $this->session->userdata('username'),
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'time' => $this->input->post('time'),
			'tahapan' => $this->input->post('tahapan'),
			'kontaminasi' => $this->input->post('kontaminasi'),
			'analisis' => $this->input->post('analisis'),
			'tindakan' => $this->input->post('tindakan'),
			'verifikasi' => $this->input->post('verifikasi'),
			'catatan' => $this->input->post('catatan'),
			'keterangan' => $this->input->post('keterangan'),
			'bukti' => $file_name,
			'modified_at' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('magnet_trap', $data);

		return $this->db->trans_status() !== false;
	}
}
