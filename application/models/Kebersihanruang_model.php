<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Kebersihanruang_model extends CI_Model
{

	protected $table = 'kebersihan_ruang';

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
				'field' => 'lokasi',
				'label' => 'Lokasi',
				'rules' => 'required'
			],

			[
				'field' => 'bagian[]',
				'label' => 'Bagian',
				'rules' => 'required'
			],
		];
	}

	public function insert($foto1 = null, $foto2 = null)
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$uuid     = \Ramsey\Uuid\Uuid::uuid4()->toString();
		$audit_uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();

		$username = $this->session->userdata('username');
		$plant    = $this->session->userdata('plant');

		$date   = $this->input->post('date');
		$shift  = $this->input->post('shift');
		$lokasi = $this->input->post('lokasi');

		$bagian   = $this->input->post('bagian') ?? [];
		$kondisi  = $this->input->post('kondisi') ?? [];
		$problem  = $this->input->post('problem') ?? [];
		$tindakan = $this->input->post('tindakan') ?? [];

		/*
    |--------------------------------------------------------------------------
    | DETAIL QC UTAMA
    |--------------------------------------------------------------------------
    */
		$detail = [];

		foreach ($bagian as $index => $nama_bagian) {

			$detail[] = [
				'bagian'   => $nama_bagian,
				'kondisi'  => isset($kondisi[$index])
					? (is_array($kondisi[$index])
						? implode(',', $kondisi[$index])
						: $kondisi[$index])
					: 'bersih',

				'problem'  => $problem[$index] ?? '',
				'tindakan' => $tindakan[$index] ?? '',
			];
		}

		/*
    |--------------------------------------------------------------------------
    | DATA QC UTAMA
    |--------------------------------------------------------------------------
    */
		$data = [
			'uuid'           => $uuid,
			'username'       => $username,
			'plant'          => $plant,
			'date'           => $date,
			'shift'          => $shift,
			'lokasi'         => $lokasi,
			'detail'         => json_encode($detail),
			'foto1'          => $foto1,
			'foto2'          => $foto2,
			'status_produksi' => 0,
			'status_spv'     => 0,
			'nama_produksi'  => $nama_produksi,
			'is_audit'       => 0,
			'source_uuid'    => null,
			'created_at'     => date("Y-m-d H:i:s"),
			'modified_at'    => date("Y-m-d H:i:s"),
		];

		/*
    |--------------------------------------------------------------------------
    | DETAIL AUDIT
    |
    | Semua kondisi otomatis dibuat BERSIH.
    | Problem dan tindakan juga dikosongkan.
    |--------------------------------------------------------------------------
    */
		$audit_detail = [];

		foreach ($bagian as $index => $nama_bagian) {

			$audit_detail[] = [
				'bagian'     => $nama_bagian,
				'kondisi'    => 'bersih',
				'problem'    => '',
				'tindakan'   => '',
				'verifikasi' => '',
			];
		}

		/*
    |--------------------------------------------------------------------------
    | DATA AUDIT
    |--------------------------------------------------------------------------
    */
		$auditData = [
			'uuid'            => $audit_uuid,
			'username'        => $username,
			'plant'           => $plant,
			'date'            => $date,
			'shift'           => $shift,
			'lokasi'          => $lokasi,
			'detail'          => json_encode($audit_detail),
			'foto1'           => $foto1,
			'foto2'           => $foto2,
			'status_produksi' => 1,
			'status_spv'      => 0,
			'nama_produksi'   => $nama_produksi,
			'is_audit'        => 1,
			'source_uuid'     => $uuid,
			'created_at'      => date("Y-m-d H:i:s"),
			'modified_at'     => date("Y-m-d H:i:s"),
		];

		/*
    |--------------------------------------------------------------------------
    | TRANSACTION
    |--------------------------------------------------------------------------
    */
		$this->db->trans_start();

		// Simpan QC utama
		$this->db->insert(
			'kebersihan_ruang',
			$data
		);

		// Buat snapshot Audit
		$this->db->insert(
			'kebersihan_ruang',
			$auditData
		);

		$this->db->trans_complete();

		/*
    |--------------------------------------------------------------------------
    | HASIL
    |--------------------------------------------------------------------------
    */
		if ($this->db->trans_status() === FALSE) {
			return false;
		}

		return true;
	}

	// public function insert()
	// {
	// 	$produksi_data = $this->session->userdata('produksi_data');
	// 	$nama_produksi = $produksi_data['nama_produksi'] ?? '';

	// 	$uuid     = \Ramsey\Uuid\Uuid::uuid4()->toString();
	// 	$username = $this->session->userdata('username');
	// 	$plant    = $this->session->userdata('plant');

	// 	$date   = $this->input->post('date');
	// 	$shift  = $this->input->post('shift');
	// 	$lokasi = $this->input->post('lokasi');

	// 	$bagian   = $this->input->post('bagian') ?? [];
	// 	$kondisi  = $this->input->post('kondisi') ?? [];
	// 	$problem  = $this->input->post('problem') ?? [];
	// 	$tindakan = $this->input->post('tindakan') ?? [];

	// 	$detail = [];

	// 	foreach ($bagian as $index => $nama_bagian) {

	// 		if (!isset($kondisi[$index])) {
	// 			return false; 
	// 		}

	// 		$detail[] = [
	// 			'bagian'   => $nama_bagian,
	// 			'kondisi'  => is_array($kondisi[$index]) 
	// 			? implode(',', $kondisi[$index]) 
	// 			: $kondisi[$index],
	// 			'problem'  => $problem[$index]  ?? '',
	// 			'tindakan' => $tindakan[$index] ?? '',
	// 		];
	// 	}

	// 	$data = [
	// 		'uuid'             => $uuid,
	// 		'username'         => $username,
	// 		'plant'            => $plant,
	// 		'date'             => $date,
	// 		'shift'            => $shift,
	// 		'lokasi'           => $lokasi,
	// 		'detail'           => json_encode($detail),
	// 		'status_produksi'  => 1,
	// 		'status_spv'       => 0,
	// 		'nama_produksi'    => $nama_produksi,
	// 		'created_at'       => date("Y-m-d H:i:s"),
	// 		'modified_at'      => date("Y-m-d H:i:s"),
	// 	];

	// 	$this->db->insert('kebersihan_ruang', $data);

	// 	return ($this->db->affected_rows() > 0);
	// }


	public function update($uuid)
	{
		$plant = $this->session->userdata('plant');

		$bagian   = $this->input->post('bagian') ?? [];
		$kondisi  = $this->input->post('kondisi') ?? [];
		$problem  = $this->input->post('problem') ?? [];
		$tindakan = $this->input->post('tindakan') ?? [];
		$verifikasi = $this->input->post('verifikasi') ?? [];

		$old_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->get('kebersihan_ruang')
			->row_array();

		if (!$old_data) {
			return false;
		}

		$detail = [];

		foreach ($bagian as $i => $b) {

			$detail[] = [
				'bagian' => $b,

				'kondisi' => isset($kondisi[$i])
					? (
						is_array($kondisi[$i])
						? implode(',', $kondisi[$i])
						: $kondisi[$i]
					)
					: 'bersih',

				'problem'    => $problem[$i] ?? '',
				'tindakan'   => $tindakan[$i] ?? '',
				'verifikasi' => $verifikasi[$i] ?? '',
			];
		}

		$updateData = [
			'date'        => $this->input->post('date'),
			'shift'       => $this->input->post('shift'),
			'lokasi'      => $this->input->post('lokasi'),
			'username'    => $this->session->userdata('username'),
			'detail'      => json_encode(
				$detail,
				JSON_UNESCAPED_UNICODE
			),
			'modified_at' => date('Y-m-d H:i:s'),
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->update(
				'kebersihan_ruang',
				$updateData
			);

		if ($this->db->error()['code'] != 0) {
			return false;
		}

		$new_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->get('kebersihan_ruang')
			->row_array();

		$this->activity_logger->log_activity(
			'update',
			'kebersihan_ruang_logs',
			$uuid,
			$old_data,
			$new_data
		);

		return true;
	}

	// public function update($uuid)
	// {
	// 	$bagian    = $this->input->post('bagian') ?? [];
	// 	$kondisi   = $this->input->post('kondisi') ?? [];
	// 	$problem   = $this->input->post('problem') ?? [];
	// 	$tindakan  = $this->input->post('tindakan') ?? [];

	// 	$old_data = $this->db
	// 	->get_where('kebersihan_ruang', ['uuid'=>$uuid])
	// 	->row_array();

	// 	$detail = [];

	// 	foreach ($bagian as $i => $b) {

	// 		$detail[] = [
	// 			'bagian'   => $b,

	//         // ✅ PERBAIKAN PENTING DI SINI
	// 			'kondisi'  => isset($kondisi[$i]) 
	// 			? (is_array($kondisi[$i]) 
	// 				? implode(',', $kondisi[$i]) 
	// 				: $kondisi[$i])
	// 			: 'bersih',

	// 			'problem'  => $problem[$i] ?? '',
	// 			'tindakan' => $tindakan[$i] ?? '',
	// 		];
	// 	}

	// 	$updateData = [
	// 		'date'        => $this->input->post('date'),
	// 		'shift'       => $this->input->post('shift'),
	// 		'lokasi'      => $this->input->post('lokasi'), 
	// 		'username'    => $this->session->userdata('username'),
	// 		'detail'      => json_encode($detail),
	// 		'modified_at' => date('Y-m-d H:i:s'),
	// 	];

	// 	$this->db->where('uuid', $uuid);
	// 	$this->db->update('kebersihan_ruang', $updateData);

	// 	if ($this->db->affected_rows() > 0) {

	// 		$new_data = $this->db
	// 		->get_where('kebersihan_ruang', ['uuid' => $uuid])
	// 		->row_array();

	// 		$this->activity_logger->log_activity(
	// 			'update',
	// 			'kebersihan_ruang_logs',
	// 			$uuid,
	// 			$old_data,
	// 			$new_data
	// 		);

	// 		return true;
	// 	}

	// 	return false;
	// }


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
			'nama_spv'        => $nama_spv,
			'status_spv'      => $status_spv,
			'catatan_spv'     => $catatan_spv,
			'tgl_update_spv'  => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->update(
				'kebersihan_ruang',
				$data
			);

		return $this->db->error()['code'] == 0;
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
			->update('kebersihan_ruang', $data);

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
		$plant = $this->session->userdata('plant');

		$nama_produksi = $this->session->userdata('username');
		$status_produksi = $this->input->post('status_produksi');
		$catatan_produksi = $this->input->post('catatan_produksi');

		$data = [
			'nama_produksi'       => $nama_produksi,
			'status_produksi'     => $status_produksi,
			'catatan_produksi'    => $catatan_produksi,
			'tgl_update_produksi' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->update(
				'kebersihan_ruang',
				$data
			);

		return $this->db->error()['code'] == 0;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('kebersihan_ruang')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->get('kebersihan_ruang')
			->row();
	}

	public function get_bagian_by_uuid($uuid)
	{
		return $this->db->get_where('kebersihan_ruang', ['uuid' => $uuid])->result_array();
	}

	public function get_details_by_uuid($uuid)
	{
		return $this->db->get_where('kebersihanruang_detail', ['uuid_kebersihanruang' => $uuid])->result_array();
	}


	public function get_by_uuid_kebersihanruang($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('kebersihan_ruang');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_kebersihanruang_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kebersihan_ruang');

		$data_kebersihan_ruang = $query->row();
		return $data_kebersihan_ruang;
	}


	public function get_data_by_plant($limit = null, $start = 0, $tanggal = '')
	{
		$plant = $this->session->userdata('plant');
		$type_user = (int) $this->session->userdata('tipe_user');

		$this->db->select('
        uuid,
        date,
        shift,
        lokasi,
        detail,
        status_spv,
        status_produksi,
        nama_spv,
        nama_produksi,
        foto1,
        foto2,
        modified_at,
        tgl_update_spv,
        tgl_update_produksi
    ');

		$this->db->from('kebersihan_ruang');
		$this->db->where('is_audit', 0);

		if (!in_array($type_user, [1, 9])) {
			$this->db->where('plant', $plant);
		}

		if (!empty($tanggal)) {
			$this->db->where('date', $tanggal);
		}

		$this->db->order_by('created_at', 'DESC');

		if ($limit !== null) {
			$this->db->limit($limit, $start);
		}

		return $this->db->get()->result();
	}


	public function count_kebersihanruang_by_plant($tanggal = '')
	{
		$plant = $this->session->userdata('plant');
		$type_user = (int) $this->session->userdata('tipe_user');

		$this->db->from('kebersihan_ruang');
		$this->db->where('is_audit', 0);

		if (!in_array($type_user, [1, 9])) {
			$this->db->where('plant', $plant);
		}

		if (!empty($tanggal)) {
			$this->db->where('date', $tanggal);
		}

		return $this->db->count_all_results();
	}


	public function verifikasi_massal($tanggal, $jenis = 'spv')
	{
		$plant     = $this->session->userdata('plant');
		$tipe_user = (int) $this->session->userdata('tipe_user');
		$nama_spv  = $this->session->userdata('username');

		if (
			empty($tanggal) ||
			!in_array($jenis, ['spv', 'produksi'], true)
		) {
			return false;
		}

		if ($jenis === 'spv' && empty($nama_spv)) {
			return false;
		}

		// Hitung data yang memenuhi syarat
		$this->db->from('kebersihan_ruang');
		$this->db->where('is_audit', 0);
		$this->db->where('date', $tanggal);

		// Tipe user 1 dan 9 dapat memproses seluruh plant
		if (!in_array($tipe_user, [1, 9], true)) {
			$this->db->where('plant', $plant);
		}

		if ($jenis === 'produksi') {
			$this->db->where('status_produksi', '0');
		} else {
			$this->db->where('status_produksi', '1');
			$this->db->where_in('status_spv', ['0', '2']);
		}

		$total_data = $this->db->count_all_results();

		if ($total_data == 0) {
			return 0;
		}

		$this->db->trans_begin();

		// Kondisi update
		$this->db->where('is_audit', 0);
		$this->db->where('date', $tanggal);

		if (!in_array($tipe_user, [1, 9], true)) {
			$this->db->where('plant', $plant);
		}

		if ($jenis === 'produksi') {

			$this->db->where('status_produksi', '0');

			$this->db->update('kebersihan_ruang', [
				'status_produksi' => '1'
			]);
		} else {

			$this->db->where('status_produksi', '1');
			$this->db->where_in('status_spv', ['0', '2']);

			$this->db->update('kebersihan_ruang', [
				'nama_spv'       => $nama_spv,
				'status_spv'     => '1',
				'tgl_update_spv' => date('Y-m-d H:i:s')
			]);
		}

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
		$plant = $this->session->userdata('plant');

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0);

		return $this->db->delete('kebersihan_ruang');
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

		$query = $this->db->get('kebersihan_ruang');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null, $shift = null)
	{
		$this->db->select('
        nama_spv,
        tgl_update_spv,
        username,
        date,
        shift,
        nama_produksi,
        status_produksi,
        tgl_update_produksi
    ');

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('kebersihan_ruang');

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('kebersihan_ruang')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_ruang')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_ruang')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('kebersihan_ruang')
			->result();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		$old_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_ruang')
			->row_array();

		if (!$old_data) {
			return false;
		}

		$bagian     = $this->input->post('bagian') ?? [];
		$kondisi    = $this->input->post('kondisi') ?? [];
		$problem    = $this->input->post('problem') ?? [];
		$tindakan   = $this->input->post('tindakan') ?? [];
		$verifikasi = $this->input->post('verifikasi') ?? [];

		$detail = [];

		foreach ($bagian as $i => $b) {

			$detail[] = [
				'bagian' => $b,

				'kondisi' => isset($kondisi[$i])
					? (
						is_array($kondisi[$i])
						? implode(',', $kondisi[$i])
						: $kondisi[$i]
					)
					: 'bersih',

				'problem'    => $problem[$i] ?? '',
				'tindakan'   => $tindakan[$i] ?? '',
				'verifikasi' => $verifikasi[$i] ?? '',
			];
		}

		$updateData = [
			'date'        => $this->input->post('date'),
			'shift'       => $this->input->post('shift'),
			'lokasi'      => $this->input->post('lokasi'),
			'detail'      => json_encode(
				$detail,
				JSON_UNESCAPED_UNICODE
			),
			'modified_at' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update(
				'kebersihan_ruang',
				$updateData
			);

		if ($this->db->error()['code'] != 0) {
			return false;
		}

		$new_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_ruang')
			->row_array();

		$this->activity_logger->log_activity(
			'update',
			'kebersihan_ruang_logs',
			$uuid,
			$old_data,
			$new_data
		);

		return true;
	}
}
