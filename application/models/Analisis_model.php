<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Analisis_model extends CI_Model
{

	protected $table = 'analisis_lab';

	public function rules()
	{
		return [
			[
				'field' => 'date',
				'label' => 'Date',
				'rules' => 'required'
			],
			[
				'field' => 'tipe_sampel',
				'label' => 'Type of Sample',
				'rules' => 'required'
			],
			[
				'field' => 'penyimpanan',
				'label' => 'Storage',
				'rules' => 'required'
			],
			[
				'field' => 'nama_produk',
				'label' => 'Name of Product',
				'rules' => 'required'
			],
			[
				'field' => 'kode_produksi',
				'label' => 'Production Code',
				'rules' => 'required'
			],
			[
				'field' => 'best_before',
				'label' => 'Best Before',
				'rules' => 'required'
			],
			[
				'field' => 'jumlah_sampel',
				'label' => 'Quantity of Sample'
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

		$data = [
			'uuid'           => $uuid,
			'username'       => $username,
			'plant'          => $plant,
			'date'           => $this->input->post('date'),
			'tipe_sampel'    => $this->input->post('tipe_sampel'),
			'penyimpanan'    => $this->input->post('penyimpanan'),
			'nama_produk'    => $this->input->post('nama_produk'),
			'kode_produksi'  => $this->input->post('kode_produksi'),
			'best_before'    => $this->input->post('best_before'),
			'jumlah_sampel'  => $this->input->post('jumlah_sampel'),
			'status_spv'     => '0',
			'status_produksi' => '1',
			'nama_produksi'  => $nama_produksi,
			'status_lab'     => '0',
			'is_audit'       => 0,
			'source_uuid'    => null
		];

		$this->db->trans_begin();

		// Simpan data QC.
		$this->db->insert('analisis_lab', $data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		// Buat snapshot Audit dari data QC.
		$audit_data = $data;
		$audit_data['uuid'] = Uuid::uuid4()->toString();
		$audit_data['is_audit'] = 1;
		$audit_data['source_uuid'] = $uuid;

		$this->db->insert('analisis_lab', $audit_data);

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
		$tipe_sampel = $this->input->post('tipe_sampel');
		$penyimpanan = $this->input->post('penyimpanan');
		$nama_produk = $this->input->post('nama_produk');
		$kode_produksi = $this->input->post('kode_produksi');
		$best_before = $this->input->post('best_before');
		$jumlah_sampel = $this->input->post('jumlah_sampel');

		// ambil data lama sebelum update
		$old_data = $this->db->get_where('analisis_lab', ['uuid' => $uuid])->row_array();

		$data = array(
			'username' => $username,
			'date' => $date,
			'tipe_sampel' => $tipe_sampel,
			'penyimpanan' => $penyimpanan,
			'nama_produk' => $nama_produk,
			'kode_produksi' => $kode_produksi,
			'best_before' => $best_before,
			'jumlah_sampel' => $jumlah_sampel,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('analisis_lab', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('analisis_lab', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus analisis_logs
			$this->activity_logger->log_activity(
				'update',
				'analisis_logs', // nama tabel log khusus analisis_lab
				$uuid,
				$old_data,
				$new_data
			);
			return true;
		}
		return false;
	}


	public function analysis($uuid)
	{
		$plant = $this->session->userdata('plant');
		$username = $this->session->userdata('username');
		$type_user = $this->session->userdata('tipe_user');

		// Ambil data QC.
		$this->db
			->where('uuid', $uuid)
			->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		$qc = $this->db->get('analisis_lab')->row();

		if (!$qc) {
			return false;
		}

		// Siapkan data Permintaan Analisis.
		$analisis = $this->input->post('analisis');

		if (is_array($analisis)) {
			$analisis = array_filter($analisis, function ($value) {
				return trim($value) !== '';
			});

			$analisis = implode(',', $analisis);
		} else {
			$analisis = '';
		}

		$data = [
			'username'    => $username,
			'analisis'    => $analisis,
			'catatan'     => $this->input->post('catatan'),
			'modified_at' => date('Y-m-d H:i:s')
		];

		$this->db->trans_begin();

		// Update data QC.
		$this->db
			->where('uuid', $uuid)
			->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		$this->db->update('analisis_lab', $data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		// Cari snapshot Audit berdasarkan UUID sumber.
		$this->db
			->where('source_uuid', $uuid)
			->where('is_audit', 1)
			->where('plant', $qc->plant);

		$audit = $this->db->get('analisis_lab')->row();

		// Jika snapshot tersedia, update data Audit juga.
		if ($audit) {
			$this->db
				->where('uuid', $audit->uuid)
				->where('source_uuid', $uuid)
				->where('is_audit', 1)
				->where('plant', $qc->plant)
				->update('analisis_lab', $data);

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				return false;
			}
		}

		$this->db->trans_commit();

		return true;
	}

	public function get_qc_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db
			->where('uuid', $uuid)
			->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		return $this->db->get('analisis_lab')->row();
	}

	public function analysis_audit($uuid)
	{
		$plant = $this->session->userdata('plant');
		$username = $this->session->userdata('username');

		// Validasi snapshot Audit pada plant aktif.
		$audit = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('analisis_lab')
			->row();

		if (!$audit) {
			return false;
		}

		$analisis = $this->input->post('analisis');

		if (is_array($analisis)) {
			$analisis = array_filter($analisis, function ($value) {
				return trim($value) !== '';
			});

			$analisis = implode(',', $analisis);
		} else {
			$analisis = '';
		}

		$data = [
			'username'    => $username,
			'analisis'    => $analisis,
			'catatan'     => $this->input->post('catatan'),
			'modified_at' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('analisis_lab', $data);

		return $this->db->affected_rows() > 0;
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

		$this->db->update('analisis_lab', $data, array('uuid' => $uuid));
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

		$this->db->update('analisis_lab', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function rules_diterima()
	{
		return [
			[
				'field' => 'status_lab',
				'label' => 'Status',
				'rules' => 'required'
			],
			[
				'field' => 'catatan_lab',
				'label' => 'Notes'
			]

		];
	}

	public function diterima_update($uuid)
	{

		$nama_lab = $this->session->userdata('username');
		$status_lab = $this->input->post('status_lab');
		$catatan_lab = $this->input->post('catatan_lab');

		$data = array(
			'nama_lab' => $nama_lab,
			'status_lab' => $status_lab,
			'catatan_lab' => $catatan_lab,
			'tgl_update_lab' => date("Y-m-d H:i:s")
		);

		$this->db->update('analisis_lab', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('analisis_lab')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('analisis_lab', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_bagian_by_uuid($uuid)
	{
		return $this->db->get_where('analisis_lab', ['uuid' => $uuid])->result_array();
	}

	public function get_details_by_uuid($uuid)
	{
		return $this->db->get_where('analisis_detail', ['uuid_analisis' => $uuid])->result_array();
	}

	public function get_detail($uuid)
	{
		return $this->db->get_where('analisis_lab', ['uuid' => $uuid])->row();
	}

	public function get_by_uuid_analisis($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('analisis_lab');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_analisis_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, nama_produksi, tipe_sampel, penyimpanan, nama_lab, tgl_update_lab, status_lab, catatan_lab, status_produksi, tgl_update_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('analisis_lab');

		$data_analisis = $query->row();
		return $data_analisis;
	}

	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->where('is_audit', 0);
		$this->db->order_by('created_at', 'DESC');

		if ($type_user == 9 || $type_user == 1) {
			return $this->db->get('analisis_lab')->result();
		}

		return $this->db
			->where('plant', $plant)
			->get('analisis_lab')
			->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('analisis_lab');
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
		$query = $this->db->get('analisis_lab');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, nama_produksi, tipe_sampel, penyimpanan, nama_lab, tgl_update_lab, status_lab, catatan_lab, status_produksi, tgl_update_produksi');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('analisis_lab');

		return $query->row();
	}

	public function get_audit_by_date($tanggal, $plant = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 1);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('date', 'ASC');
		$query = $this->db->get('analisis_lab');

		log_message('debug', 'Query get_audit_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_audit_verif_by_date($tanggal, $plant = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->select('
		nama_spv,
		tgl_update_spv,
		username,
		date,
		nama_produksi,
		tipe_sampel,
		penyimpanan,
		nama_lab,
		tgl_update_lab,
		status_lab,
		catatan_lab,
		status_produksi,
		tgl_update_produksi
	');

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 1);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('analisis_lab');

		log_message(
			'debug',
			'Query get_last_audit_verif_by_date: ' . $this->db->last_query()
		);

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('analisis_lab')
			->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('analisis_lab')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('analisis_lab')
			->row();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');
		$username = $this->session->userdata('username');

		// Pastikan snapshot Audit tersedia di plant aktif.
		$audit = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('analisis_lab')
			->row();

		if (!$audit) {
			return false;
		}

		$data = [
			'username'      => $username,
			'date'          => $this->input->post('date'),
			'tipe_sampel'   => $this->input->post('tipe_sampel'),
			'penyimpanan'   => $this->input->post('penyimpanan'),
			'nama_produk'   => $this->input->post('nama_produk'),
			'kode_produksi' => $this->input->post('kode_produksi'),
			'best_before'   => $this->input->post('best_before'),
			'jumlah_sampel' => $this->input->post('jumlah_sampel'),
			'modified_at'   => date('Y-m-d H:i:s')
		];

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('analisis_lab', $data);
	}
}
