<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Kebersihankaryawan_model extends CI_Model
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
				'field' => 'nama',
				'label' => 'Name',
				'rules' => 'required'
			],
			[
				'field' => 'bagian',
				'label' => 'Area',
				'rules' => 'required'
			],
			[
				'field' => 'seragam',
				'label' => 'Part of Cleanliness'
			],
			[
				'field' => 'apron',
				'label' => 'Part of Cleanliness'
			],
			[
				'field' => 'tangan_kuku',
				'label' => 'Part of Cleanliness'
			],
			[
				'field' => 'kosmetik',
				'label' => 'Part of Cleanliness'
			],
			[
				'field' => 'perhiasan',
				'label' => 'Part of Cleanliness'
			],
			[
				'field' => 'masker',
				'label' => 'Part of Cleanliness'
			],
			[
				'field' => 'topi_hairnet',
				'label' => 'Part of Cleanliness'
			],
			[
				'field' => 'sepatu',
				'label' => 'Part of Cleanliness'
			],
			[
				'field' => 'tindakan',
				'label' => 'Corrective Action'
			],
			[
				'field' => 'catatan',
				'label' => 'Notes'
			]
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
		$nama = $this->input->post('nama');
		$bagian = $this->input->post('bagian');

		$seragam = $this->input->post('seragam');
		$apron = $this->input->post('apron');
		$tangan_kuku = $this->input->post('tangan_kuku');
		$kosmetik = $this->input->post('kosmetik');
		$perhiasan = $this->input->post('perhiasan');
		$masker = $this->input->post('masker');
		$topi_hairnet = $this->input->post('topi_hairnet');
		$sepatu = $this->input->post('sepatu');

		$tindakan = $this->input->post('tindakan');
		$catatan = $this->input->post('catatan');

		$status_spv = "0";
		$status_produksi = "1";


		// ==========================================
		// DATA QC UTAMA
		// ==========================================

		$data = [
			'uuid' => $uuid,
			'is_audit' => 0,
			'source_uuid' => null,

			'username' => $username,
			'plant' => $plant,

			'date' => $date,
			'shift' => $shift,
			'nama' => $nama,
			'bagian' => $bagian,

			'seragam' => $seragam,
			'apron' => $apron,
			'tangan_kuku' => $tangan_kuku,
			'kosmetik' => $kosmetik,
			'perhiasan' => $perhiasan,
			'masker' => $masker,
			'topi_hairnet' => $topi_hairnet,
			'sepatu' => $sepatu,

			'tindakan' => $tindakan,
			'catatan' => $catatan,

			'status_produksi' => $status_produksi,
			'nama_produksi' => $nama_produksi,
			'status_spv' => $status_spv
		];


		// ==========================================
		// TRANSACTION
		// ==========================================

		$this->db->trans_start();


		// ==========================================
		// SIMPAN QC UTAMA
		// ==========================================

		$this->db->insert(
			'kebersihan_karyawan',
			$data
		);


		// ==========================================
		// BUAT AUDIT OTOMATIS
		// ==========================================

		if ($this->db->affected_rows() > 0) {

			$audit_uuid = Uuid::uuid4()->toString();


			// ==========================================
			// HASIL PEMERIKSAAN AUDIT
			// ==========================================
			// ok             -> ok
			// tidak oke      -> ok
			// tidak dipakai  -> tidak dipakai
			// kosong         -> kosong
			// ==========================================

			$audit_seragam = empty($seragam)
				? null
				: ($seragam == 'tidak dipakai' ? 'tidak dipakai' : 'ok');

			$audit_apron = empty($apron)
				? null
				: ($apron == 'tidak dipakai' ? 'tidak dipakai' : 'ok');

			$audit_tangan_kuku = empty($tangan_kuku)
				? null
				: ($tangan_kuku == 'tidak dipakai' ? 'tidak dipakai' : 'ok');

			$audit_kosmetik = empty($kosmetik)
				? null
				: ($kosmetik == 'tidak dipakai' ? 'tidak dipakai' : 'ok');

			$audit_perhiasan = empty($perhiasan)
				? null
				: ($perhiasan == 'tidak dipakai' ? 'tidak dipakai' : 'ok');

			$audit_masker = empty($masker)
				? null
				: ($masker == 'tidak dipakai' ? 'tidak dipakai' : 'ok');

			$audit_topi_hairnet = empty($topi_hairnet)
				? null
				: ($topi_hairnet == 'tidak dipakai' ? 'tidak dipakai' : 'ok');

			$audit_sepatu = empty($sepatu)
				? null
				: ($sepatu == 'tidak dipakai' ? 'tidak dipakai' : 'ok');


			// ==========================================
			// TINDAKAN AUDIT
			// ==========================================
			// Ada isi -> "-"
			// Kosong  -> kosong
			// ==========================================

			$audit_tindakan = !empty(trim($tindakan))
				? '-'
				: null;


			// --------------------------------------
			// DATA AUDIT
			// --------------------------------------

			$audit_data = [
				'uuid' => $audit_uuid,

				'is_audit' => 1,
				'source_uuid' => $uuid,

				'username' => $username,
				'plant' => $plant,

				'date' => $date,
				'shift' => $shift,
				'nama' => $nama,
				'bagian' => $bagian,

				'seragam' => $audit_seragam,
				'apron' => $audit_apron,
				'tangan_kuku' => $audit_tangan_kuku,
				'kosmetik' => $audit_kosmetik,
				'perhiasan' => $audit_perhiasan,
				'masker' => $audit_masker,
				'topi_hairnet' => $audit_topi_hairnet,
				'sepatu' => $audit_sepatu,

				'tindakan' => $audit_tindakan,

				// Catatan tidak diubah
				'catatan' => $catatan,

				'status_produksi' => $status_produksi,
				'nama_produksi' => $nama_produksi,
				'status_spv' => $status_spv
			];


			$this->db->insert(
				'kebersihan_karyawan',
				$audit_data
			);
		}


		$this->db->trans_complete();


		return $this->db->trans_status();
	}

	public function update($uuid)
	{
		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$nama = $this->input->post('nama');
		$bagian = $this->input->post('bagian');
		$seragam = $this->input->post('seragam');
		$apron = $this->input->post('apron');
		$tangan_kuku = $this->input->post('tangan_kuku');
		$kosmetik = $this->input->post('kosmetik');
		$perhiasan = $this->input->post('perhiasan');
		$masker = $this->input->post('masker');
		$topi_hairnet = $this->input->post('topi_hairnet');
		$sepatu = $this->input->post('sepatu');
		$tindakan = $this->input->post('tindakan');
		$catatan = $this->input->post('catatan');

		$old_data = $this->db->get_where('kebersihan_karyawan', ['uuid' => $uuid])->row_array();

		$data = array(
			'username' => $username,
			'date' => $date,
			'shift' => $shift,
			'nama' => $nama,
			'bagian' => $bagian,
			'seragam' => $seragam,
			'apron' => $apron,
			'tangan_kuku' => $tangan_kuku,
			'kosmetik' => $kosmetik,
			'perhiasan' => $perhiasan,
			'masker' => $masker,
			'topi_hairnet' => $topi_hairnet,
			'sepatu' => $sepatu,
			'tindakan' => $tindakan,
			'catatan' => $catatan,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('kebersihan_karyawan', $data, ['uuid' => $uuid]);

		$new_data = $this->db->get_where('kebersihan_karyawan', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			$this->activity_logger->log_activity(
				'update',
				'kebersihan_karyawan_logs',
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

		$this->db->update('kebersihan_karyawan', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
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

		$this->db->update('kebersihan_karyawan', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('kebersihan_karyawan')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('kebersihan_karyawan', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_kebersihankaryawan($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('kebersihan_karyawan');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_kebersihankaryawan_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kebersihan_karyawan');

		$data_kebersihan_karyawan = $query->row();
		return $data_kebersihan_karyawan;
	}

	public function get_data_by_plant($limit, $start, $plant, $type_user)
	{
		$this->db->select('
		uuid,
		date,
		nama,
		bagian,
		seragam,
		apron,
		tangan_kuku,
		kosmetik,
		perhiasan,
		masker,
		topi_hairnet,
		sepatu,
		tindakan,
		status_spv,
		modified_at,
		tgl_update_spv
	');

		$this->db->from('kebersihan_karyawan');

		// Hanya data QC utama
		$this->db->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit, $start);

		return $this->db->get()->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('kebersihan_karyawan');
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
		$query = $this->db->get('kebersihan_karyawan');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_shift_by_date($tanggal, $plant = null)
	{
		$this->db->select('shift');
		$this->db->from('kebersihan_karyawan');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->group_by('shift');
		$this->db->order_by('shift', 'ASC');

		return $this->db->get()->result();
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kebersihan_karyawan');

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('kebersihan_karyawan')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_karyawan')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_karyawan')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('kebersihan_karyawan')
			->result();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');
		$username = $this->session->userdata('username');

		// Ambil data audit lama
		$old_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_karyawan')
			->row_array();

		if (!$old_data) {
			return false;
		}

		$data = array(
			'username' => $username,
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'nama' => $this->input->post('nama'),
			'bagian' => $this->input->post('bagian'),
			'seragam' => $this->input->post('seragam'),
			'apron' => $this->input->post('apron'),
			'tangan_kuku' => $this->input->post('tangan_kuku'),
			'kosmetik' => $this->input->post('kosmetik'),
			'perhiasan' => $this->input->post('perhiasan'),
			'masker' => $this->input->post('masker'),
			'topi_hairnet' => $this->input->post('topi_hairnet'),
			'sepatu' => $this->input->post('sepatu'),
			'tindakan' => $this->input->post('tindakan'),
			'catatan' => $this->input->post('catatan'),
			'modified_at' => date('Y-m-d H:i:s')
		);

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('kebersihan_karyawan', $data);

		if ($this->db->affected_rows() > 0) {

			$new_data = $this->db
				->where('uuid', $uuid)
				->where('plant', $plant)
				->where('is_audit', 1)
				->get('kebersihan_karyawan')
				->row_array();

			$this->activity_logger->log_activity(
				'update',
				'kebersihan_karyawan_logs',
				$uuid,
				$old_data,
				$new_data
			);

			return true;
		}

		return false;
	}
}
