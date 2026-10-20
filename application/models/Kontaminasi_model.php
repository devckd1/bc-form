<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Kontaminasi_model extends CI_Model
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
				'field' => 'jenis_kontaminasi',
				'label' => 'Type of Contamination'
			],
			[
				'field' => 'bukti',
				'label' => 'Evidence',
				'rules' => 'callback_file_check'
			],
			[
				'field' => 'nama_produk',
				'label' => 'Name of Code'
			],
			[
				'field' => 'kode_produksi',
				'label' => 'Product Code'
			],
			[
				'field' => 'tahapan',
				'label' => 'Step'
			],
			[
				'field' => 'analisis',
				'label' => 'Analysis'
			],
			[
				'field' => 'tindakan',
				'label' => 'Corrective Action'
			],
			[
				'field' => 'jumlah_temuan',
				'label' => 'Amount of Contamination'
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

	public function insert($file_name)
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$uuid_qc = Uuid::uuid4()->toString();
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$data = [
			'uuid'             => $uuid_qc,
			'is_audit'         => 0,
			'source_uuid'      => null,
			'username'         => $username,
			'plant'            => $plant,
			'date'             => $this->input->post('date'),
			'shift'            => $this->input->post('shift'),
			'time'             => $this->input->post('time'),
			'jenis_kontaminasi' => $this->input->post('jenis_kontaminasi'),
			'nama_produk'      => $this->input->post('nama_produk'),
			'kode_produksi'    => $this->input->post('kode_produksi'),
			'jumlah_temuan'    => $this->input->post('jumlah_temuan'),
			'tahapan'          => $this->input->post('tahapan'),
			'analisis'         => $this->input->post('analisis'),
			'tindakan'         => $this->input->post('tindakan'),
			'keterangan'       => $this->input->post('keterangan'),
			'catatan'          => $this->input->post('catatan'),
			'bukti'            => $file_name,
			'status_produksi'  => '1',
			'nama_produksi'    => $nama_produksi,
			'status_spv'       => '0'
		];

		$this->db->trans_begin();

		// Simpan data QC utama
		$this->db->insert('kontaminasi', $data);

		// Buat snapshot Audit jika insert QC berhasil
		if ($this->db->trans_status() !== false) {
			$data_audit = $data;

			$data_audit['uuid'] = Uuid::uuid4()->toString();
			$data_audit['is_audit'] = 1;
			$data_audit['source_uuid'] = $uuid_qc;

			$this->db->insert('kontaminasi', $data_audit);
		}

		// Rollback jika salah satu insert gagal
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}

	public function update($uuid, $file_name)
	{
		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$time = $this->input->post('time');
		$jenis_kontaminasi = $this->input->post('jenis_kontaminasi');
		$nama_produk = $this->input->post('nama_produk');
		$kode_produksi = $this->input->post('kode_produksi');
		$jumlah_temuan = $this->input->post('jumlah_temuan');
		$tahapan = $this->input->post('tahapan');
		$analisis = $this->input->post('analisis');
		$tindakan = $this->input->post('tindakan');
		$keterangan = $this->input->post('keterangan');
		$catatan = $this->input->post('catatan');

		$old_data = $this->db->get_where('kontaminasi', ['uuid' => $uuid])->row_array();

		$data = array(
			'username' => $username,
			'date' => $date,
			'shift' => $shift,
			'time' => $time,
			'jenis_kontaminasi' => $jenis_kontaminasi,
			'kode_produksi' => $kode_produksi,
			'kode_produksi' => $kode_produksi,
			'jumlah_temuan' => $jumlah_temuan,
			'tahapan' => $tahapan,
			'analisis' => $analisis,
			'tindakan' => $tindakan,
			'keterangan' => $keterangan,
			'catatan' => $catatan,
			'bukti' => $file_name,
			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('kontaminasi', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('kontaminasi', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus kontaminasi_logs
			$this->activity_logger->log_activity(
				'update',
				'kontaminasi_logs', // nama tabel log khusus kontaminasi
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

		$this->db->update('kontaminasi', $data, array('uuid' => $uuid));
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

		$this->db->update('kontaminasi', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('kontaminasi')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('kontaminasi', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_kontaminasi($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('kontaminasi');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_kontaminasi_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, date, username, shift, nama_produksi, tgl_update_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kontaminasi');

		$data_kontaminasi = $query->row();
		return $data_kontaminasi;
	}

	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->from('kontaminasi');
		$this->db->where('is_audit', 0);

		if ($type_user == 9 || $type_user == 1) {
			$this->db->order_by('created_at', 'DESC');
			return $this->db->get()->result();
		}

		$this->db->where('plant', $plant);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get()->result();
	}

	public function get_temuan_per_hari()
	{
		$user_plant = $this->session->userdata('plant');

		$this->db->select("
			DATE(date) as tanggal,
			SUM(jumlah_temuan) as jumlah_temuan,
			GROUP_CONCAT(DISTINCT nama_produk SEPARATOR ', ') as nama_produk,
			GROUP_CONCAT(DISTINCT jenis_kontaminasi SEPARATOR ', ') as jenis_kontaminasi
			");
		$this->db->from("kontaminasi");
		$this->db->where('plant', $user_plant);
		$this->db->where('date >=', date('Y-m-01'));
		$this->db->where('date <=', date('Y-m-t'));
		$this->db->group_by("DATE(date)");
		$this->db->order_by("DATE(date)", "ASC");

		return $this->db->get()->result_array();
	}


	public function get_latest_temuan_bulan_ini()
	{
		$user_plant = $this->session->userdata('plant');

		$this->db->select('jenis_kontaminasi, nama_produk, kode_produksi, jumlah_temuan');
		$this->db->from('kontaminasi');
		$this->db->where('plant', $user_plant);
		$this->db->where('date >=', date('Y-m-01'));
		$this->db->where('date <=', date('Y-m-t'));
		$this->db->order_by('date', 'DESC');
		$this->db->limit(10);

		return $this->db->get()->result_array();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('kontaminasi');
	}

	public function get_by_date($tanggal, $plant = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('date', 'ASC');
		$query = $this->db->get('kontaminasi');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, date, username, shift, nama_produksi, tgl_update_produksi');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kontaminasi');

		return $query->row();
	}

	// public function get_by_month($tahun, $bulan, $plant = null)
	// {
	// 	$this->db->where('YEAR(date)', $tahun);
	// 	$this->db->where('MONTH(date)', $bulan);

	// 	if (!empty($plant)) {
	// 		$this->db->where('plant', $plant);
	// 	}

	// 	return $this->db->get('kontaminasi')->result();
	// }

	public function get_by_month($tahun, $bulan, $plant)
	{
		$this->db->where('YEAR(date)', $tahun);
		$this->db->where('MONTH(date)', $bulan);
		$this->db->where('plant', $plant);

		return $this->db->get('kontaminasi')->result();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('kontaminasi')
			->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kontaminasi')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kontaminasi')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant = null)
	{
		if (empty($tanggal)) {
			return [];
		}

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 1);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('date', 'ASC');

		return $this->db
			->get('kontaminasi')
			->result();
	}

	public function get_qc_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->get('kontaminasi')
			->row();
	}
}
