<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Kebersihanmesin_model extends CI_Model
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
				'field' => 'mesin',
				'label' => 'Things',
				'rules' => 'required'
			],
			[
				'field' => 'perbaikan',
				'label' => 'Type of Repair',
				'rules' => 'required'
			],
			[
				'field' => 'area',
				'label' => 'Area'
			],
			[
				'field' => 'tgl_perbaikan',
				'label' => 'Dates of Repair'
			],
			[
				'field' => 'kondisi',
				'label' => 'Condition'
			],
			[
				'field' => 'spare_part',
				'label' => 'Spare Part'
			],
			[
				'field' => 'keterangan',
				'label' => 'Notes'
			]
		];
	}


	public function insert()
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$uuid = Uuid::uuid4()->toString();
		$audit_uuid = Uuid::uuid4()->toString();

		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$data = [
			'uuid' => $uuid,
			'username' => $username,
			'plant' => $plant,
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'mesin' => $this->input->post('mesin'),
			'perbaikan' => $this->input->post('perbaikan'),
			'area' => $this->input->post('area'),
			'tgl_perbaikan' => $this->input->post('tgl_perbaikan'),
			'kondisi' => $this->input->post('kondisi'),
			'spare_part' => $this->input->post('spare_part'),
			'keterangan' => $this->input->post('keterangan'),
			'status_produksi' => '0',
			'nama_produksi' => '',
			'status_spv' => '0',
			'is_audit' => 0,
			'source_uuid' => null
		];

		$this->db->trans_begin();

		// 1. Simpan data QC
		$this->db->insert('kebersihan_mesin', $data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		// 2. Buat snapshot Audit dari data QC
		$audit_data = $data;
		$audit_data['uuid'] = $audit_uuid;
		$audit_data['is_audit'] = 1;
		$audit_data['source_uuid'] = $uuid;

		$this->db->insert('kebersihan_mesin', $audit_data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}


	public function update($uuid)
	{
		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$mesin = $this->input->post('mesin');
		$perbaikan = $this->input->post('perbaikan');
		$area = $this->input->post('area');
		$tgl_perbaikan = $this->input->post('tgl_perbaikan');
		$kondisi = $this->input->post('kondisi');
		$spare_part = $this->input->post('spare_part');
		$keterangan = $this->input->post('keterangan');

		$old_data = $this->db->get_where('kebersihan_mesin', ['uuid' => $uuid])->row_array();

		$data = array(
			'username' => $username,
			'date' => $date,
			'shift' => $shift,
			'mesin' => $mesin,
			'perbaikan' => $perbaikan,
			'area' => $area,
			'tgl_perbaikan' => $tgl_perbaikan,
			'kondisi' => $kondisi,
			'spare_part' => $spare_part,
			'keterangan' => $keterangan,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('kebersihan_mesin', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('kebersihan_mesin', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus kebersihan_mesin_logs
			$this->activity_logger->log_activity(
				'update',
				'kebersihan_mesin_logs', // nama tabel log khusus kebersihan_mesin
				$uuid,
				$old_data,
				$new_data
			);
			return true;
		}
		return false;
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

		$nama_spv = $this->session->userdata('username');
		$status_spv = $this->input->post('status_spv');
		$catatan_spv = $this->input->post('catatan_spv');

		$data = array(
			'nama_spv' => $nama_spv,
			'status_spv' => $status_spv,
			'catatan_spv' => $catatan_spv,
			'tgl_update_spv' => date("Y-m-d H:i:s")
		);

		$this->db->update('kebersihan_mesin', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function verifikasi_produksi($uuid)
	{
		$plant    = $this->session->userdata('plant');
		$username = $this->session->userdata('username');

		$data = [
			'nama_produksi'       => $username,
			'status_produksi'     => '1',
			'tgl_update_produksi' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->where('status_produksi', '0')
			->update('kebersihan_mesin', $data);

		return $this->db->affected_rows() > 0;
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

		$this->db->update('kebersihan_mesin', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('kebersihan_mesin')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('kebersihan_mesin', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_kebersihanmesin($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('kebersihan_mesin');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_kebersihanmesin_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kebersihan_mesin');

		$data_kebersihan_mesin = $query->row();
		return $data_kebersihan_mesin;
	}



	public function get_data_by_plant($limit = null, $start = 0, $tanggal = '')
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->from('kebersihan_mesin');
		$this->db->where('is_audit', 0);

		// Pembatasan plant sesuai hak akses
		if (!in_array($type_user, [9, 1])) {
			$this->db->where('plant', $plant);
		}

		// Filter tanggal
		if (!empty($tanggal)) {
			$this->db->where('date', $tanggal);
		}

		$this->db->order_by('created_at', 'DESC');

		// Pagination hanya jika limit diberikan
		if ($limit !== null) {
			$this->db->limit($limit, $start);
		}

		return $this->db->get()->result();
	}


	public function count_kebersihanmesin_by_plant($tanggal = '')
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->from('kebersihan_mesin');
		$this->db->where('is_audit', 0);

		if (!in_array($type_user, [9, 1])) {
			$this->db->where('plant', $plant);
		}

		if (!empty($tanggal)) {
			$this->db->where('date', $tanggal);
		}

		return $this->db->count_all_results();
	}

	public function verifikasi_massal_spv($tanggal)
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');
		$nama_spv = $this->session->userdata('username');

		if (empty($tanggal) || empty($nama_spv)) {
			return false;
		}

		// Hitung data yang memenuhi syarat
		$this->db->from('kebersihan_mesin');
		$this->db->where('is_audit', 0);
		$this->db->where('date', $tanggal);
		$this->db->where('status_produksi', '1');
		$this->db->where_in('status_spv', ['0', '2']);

		if (!in_array($type_user, [9, 1])) {
			$this->db->where('plant', $plant);
		}

		$total_data = $this->db->count_all_results();

		if ($total_data == 0) {
			return 0;
		}

		$this->db->trans_begin();

		// Update hanya data QC yang siap diverifikasi
		$this->db->where('is_audit', 0);
		$this->db->where('date', $tanggal);
		$this->db->where('status_produksi', '1');
		$this->db->where_in('status_spv', ['0', '2']);

		if (!in_array($type_user, [9, 1])) {
			$this->db->where('plant', $plant);
		}

		$this->db->update('kebersihan_mesin', [
			'nama_spv'       => $nama_spv,
			'status_spv'     => '1',
			'tgl_update_spv' => date('Y-m-d H:i:s')
		]);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$jumlah_update = $this->db->affected_rows();

		$this->db->trans_commit();

		return $jumlah_update;
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('kebersihan_mesin');
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
		$query = $this->db->get('kebersihan_mesin');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null, $shift = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kebersihan_mesin');

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('kebersihan_mesin')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_mesin')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_mesin')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('kebersihan_mesin')
			->result();
	}


	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');
		$username = $this->session->userdata('username');

		$data = [
			'username' => $username,
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'mesin' => $this->input->post('mesin'),
			'perbaikan' => $this->input->post('perbaikan'),
			'area' => $this->input->post('area'),
			'tgl_perbaikan' => $this->input->post('tgl_perbaikan'),
			'kondisi' => $this->input->post('kondisi'),
			'spare_part' => $this->input->post('spare_part'),
			'keterangan' => $this->input->post('keterangan'),
			'modified_at' => date('Y-m-d H:i:s')
		];

		$record = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_mesin')
			->row_array();

		if (!$record) {
			return false;
		}

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('kebersihan_mesin', $data);

		return $this->db->affected_rows() > 0;
	}
}
