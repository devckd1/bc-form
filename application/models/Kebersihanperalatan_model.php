<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Kebersihanperalatan_model extends CI_Model
{

	public function rules()
	{
		return [
			[
				'field' => 'date',
				'label' => 'Tanggal',
				'rules' => 'required'
			],
			[
				'field' => 'shift',
				'label' => 'Shift',
				'rules' => 'required'
			],
			[
				'field' => 'catatan',
				'label' => 'Catatan'
			],
		];
	}

	public function insert()
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$uuid = Uuid::uuid4()->toString();

		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$catatan = $this->input->post('catatan');

		$status_spv = "0";
		$status_produksi = "1";

		$nama_alat = $this->input->post('peralatan');
		$kondisi = $this->input->post('kondisi');
		$problem = $this->input->post('problem');
		$tindakan = $this->input->post('tindakan');

		$daftar_peralatan = [];

		if (is_array($nama_alat)) {
			foreach ($nama_alat as $i => $nama) {
				$daftar_peralatan[] = [
					'nama' => $nama,
					'kondisi' => $kondisi[$i] ?? '',
					'problem' => $problem[$i] ?? '',
					'tindakan' => $tindakan[$i] ?? ''
				];
			}
		}

		$peralatan_json = json_encode(
			$daftar_peralatan,
			JSON_UNESCAPED_UNICODE
		);

		/*
     * =========================
     * DATA QC UTAMA
     * =========================
     */
		$data = [
			'uuid' => $uuid,
			'is_audit' => 0,
			'source_uuid' => null,
			'username' => $username,
			'plant' => $plant,
			'date' => $date,
			'shift' => $shift,
			'catatan' => $catatan,
			'peralatan' => $peralatan_json,
			'status_produksi' => $status_produksi,
			'nama_produksi' => $nama_produksi,
			'status_spv' => $status_spv
		];

		/*
     * =========================
     * AUTO AUDIT
     * =========================
     */
		$audit_uuid = Uuid::uuid4()->toString();

		$audit_data = [
			'uuid' => $audit_uuid,
			'is_audit' => 1,
			'source_uuid' => $uuid,
			'username' => $username,
			'plant' => $plant,
			'date' => $date,
			'shift' => $shift,
			'catatan' => $catatan,
			'peralatan' => $peralatan_json,
			'status_produksi' => $status_produksi,
			'nama_produksi' => $nama_produksi,
			'status_spv' => $status_spv
		];

		/*
     * =========================
     * TRANSACTION
     * =========================
     */
		$this->db->trans_start();

		$this->db->insert(
			'kebersihan_peralatan',
			$data
		);

		$this->db->insert(
			'kebersihan_peralatan',
			$audit_data
		);

		$this->db->trans_complete();

		return $this->db->trans_status();
	}


	public function update($uuid)
	{
		$plant = $this->session->userdata('plant');
		$username = $this->session->userdata('username');

		$old_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->get('kebersihan_peralatan')
			->row_array();

		if (!$old_data) {
			return false;
		}

		$nama_alat = $this->input->post('peralatan');
		$kondisi = $this->input->post('kondisi');
		$problem = $this->input->post('problem');
		$tindakan = $this->input->post('tindakan');

		$daftar_peralatan = [];

		if (is_array($nama_alat)) {
			foreach ($nama_alat as $i => $nama) {
				$daftar_peralatan[] = [
					'nama' => $nama,
					'kondisi' => $kondisi[$i] ?? '',
					'problem' => $problem[$i] ?? '',
					'tindakan' => $tindakan[$i] ?? ''
				];
			}
		}

		$data = [
			'username' => $username,
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'catatan' => $this->input->post('catatan'),
			'peralatan' => json_encode(
				$daftar_peralatan,
				JSON_UNESCAPED_UNICODE
			),
			'modified_at' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->update('kebersihan_peralatan', $data);

		if ($this->db->error()['code'] != 0) {
			return false;
		}

		$new_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->get('kebersihan_peralatan')
			->row_array();

		$this->activity_logger->log_activity(
			'update',
			'kebersihan_peralatan_logs',
			$uuid,
			$old_data,
			$new_data
		);

		return true;
	}

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

		$nama_spv = $this->session->userdata('username');
		$status_spv = $this->input->post('status_spv');
		$catatan_spv = $this->input->post('catatan_spv');

		$data = [
			'nama_spv' => $nama_spv,
			'status_spv' => $status_spv,
			'catatan_spv' => $catatan_spv,
			'tgl_update_spv' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->update(
				'kebersihan_peralatan',
				$data
			);

		return $this->db->error()['code'] == 0;
	}

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

		$nama_produksi = $this->session->userdata('username');
		$status_produksi = $this->input->post('status_produksi');
		$catatan_produksi = $this->input->post('catatan_produksi');

		$data = array(
			'nama_produksi' => $nama_produksi,
			'status_produksi' => $status_produksi,
			'catatan_produksi' => $catatan_produksi,
			'tgl_update_produksi' => date("Y-m-d H:i:s")
		);

		$this->db->update('kebersihan_peralatan', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('kebersihan_peralatan')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->get('kebersihan_peralatan')
			->row();
	}

	public function get_by_uuid_kebersihanperalatan($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('kebersihan_peralatan');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_kebersihanperalatan_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, nama_produksi, tgl_update_produksi, status_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kebersihan_peralatan');

		$data_kebersihan_peralatan = $query->row();
		return $data_kebersihan_peralatan;
	}

	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$type_user = (int) $this->session->userdata('tipe_user');

		$this->db->select(
			'uuid, date, shift, status_spv, modified_at, tgl_update_spv'
		);

		$this->db->from('kebersihan_peralatan');

		// MENU UTAMA = HANYA DATA QC
		$this->db->where('is_audit', 0);

		if (!in_array($type_user, [1, 9])) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('created_at', 'DESC');

		return $this->db->get()->result();
	}

	public function count_all_data()
	{
		$plant = $this->session->userdata('plant');
		$type_user = (int) $this->session->userdata('tipe_user');

		$this->db->where('is_audit', 0);

		if (!in_array($type_user, [1, 9])) {
			$this->db->where('plant', $plant);
		}

		return $this->db->count_all_results(
			'kebersihan_peralatan'
		);
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('kebersihan_peralatan');
	}

	public function get_by_date($tanggal, $plant = null, $shift = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('date', 'ASC');

		$query = $this->db->get(
			'kebersihan_peralatan'
		);

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date(
		$tanggal,
		$plant = null,
		$shift = null
	) {
		$this->db->select(
			'nama_spv, tgl_update_spv, username, date, shift, 
         nama_produksi, tgl_update_produksi, status_produksi'
		);

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by(
			'tgl_update_spv',
			'DESC'
		);

		$this->db->limit(1);

		$query = $this->db->get(
			'kebersihan_peralatan'
		);

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('kebersihan_peralatan')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_peralatan')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_peralatan')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('kebersihan_peralatan')
			->result();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		/*
     * Ambil data Audit lama
     */
		$old_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_peralatan')
			->row_array();

		if (!$old_data) {
			return false;
		}

		$nama_alat = $this->input->post('peralatan');
		$kondisi = $this->input->post('kondisi');
		$problem = $this->input->post('problem');
		$tindakan = $this->input->post('tindakan');

		$daftar_peralatan = [];

		if (is_array($nama_alat)) {
			foreach ($nama_alat as $i => $nama) {
				$daftar_peralatan[] = [
					'nama' => $nama,
					'kondisi' => $kondisi[$i] ?? '',
					'problem' => $problem[$i] ?? '',
					'tindakan' => $tindakan[$i] ?? ''
				];
			}
		}

		$data = [
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'catatan' => $this->input->post('catatan'),
			'peralatan' => json_encode(
				$daftar_peralatan,
				JSON_UNESCAPED_UNICODE
			),
			'modified_at' => date('Y-m-d H:i:s')
		];

		/*
     * HANYA update record Audit
     */
		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('kebersihan_peralatan', $data);

		if ($this->db->error()['code'] != 0) {
			return false;
		}

		/*
     * Ambil data setelah update untuk logging
     */
		$new_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_peralatan')
			->row_array();

		$this->activity_logger->log_activity(
			'update',
			'kebersihan_peralatan_logs',
			$uuid,
			$old_data,
			$new_data
		);

		return true;
	}
}
