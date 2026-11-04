<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Thermometer_model extends CI_Model
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
			// [
			// 	'field' => 'kode_thermo',
			// 	'label' => 'Thermometer Code',
			// 	'rules' => 'required'
			// ], 
			// [
			// 	'field' => 'area',
			// 	'label' => 'Area',
			// 	'rules' => 'required'
			// ],
			// [
			// 	'field' => 'model',
			// 	'label' => 'Type of Thermometer',
			// 	'rules' => 'required'
			// ],
			[
				'field' => 'tindakan_perbaikan',
				'label' => 'Corrective Action'
			],
			[
				'field' => 'keterangan',
				'label' => 'Notes'
			],
		];
	}

	public function insert()
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$uuid       = Uuid::uuid4()->toString();
		$audit_uuid = Uuid::uuid4()->toString();

		$username = $this->session->userdata('username');
		$plant    = $this->session->userdata('plant');

		$date = $this->input->post('date');
		$shift = $this->input->post('shift');

		$tindakan_perbaikan = $this->input->post('tindakan_perbaikan');
		$keterangan         = $this->input->post('keterangan');

		$kode_thermo = $this->input->post('kode_thermo');
		$area        = $this->input->post('area');
		$model       = $this->input->post('model');
		$pukul       = $this->input->post('pukul');
		$standar     = $this->input->post('standar');
		$hasil       = $this->input->post('hasil');

		$status_produksi = "0";
		$status_spv      = "0";

		/*
     * ==========================================
     * DATA QC UTAMA
     * ==========================================
     */
		$peneraan_hasil = [];

		for ($i = 0; $i < count($pukul); $i++) {

			$peneraan_hasil[] = [
				'pukul'       => $pukul[$i],
				'kode_thermo' => isset($kode_thermo[$i]) ? $kode_thermo[$i] : '',
				'area'        => isset($area[$i]) ? $area[$i] : '',
				'model'       => isset($model[$i]) ? $model[$i] : '',
				'standar'     => isset($standar[$i]) ? $standar[$i] : '',
				'hasil'       => isset($hasil[$i]) ? $hasil[$i] : '',
			];
		}

		$data = [
			'uuid'              => $uuid,
			'username'          => $username,
			'plant'             => $plant,
			'date'              => $date,
			'shift'             => $shift,
			'peneraan_hasil'    => json_encode($peneraan_hasil),
			'tindakan_perbaikan' => $tindakan_perbaikan,
			'keterangan'        => $keterangan,
			'status_produksi'   => $status_produksi,
			'nama_produksi'     => $nama_produksi,
			'status_spv'        => $status_spv,

			'is_audit'          => 0,
			'source_uuid'       => null
		];

		/*
     * ==========================================
     * DATA AUDIT
     *
     * Untuk sementara snapshot audit mengikuti
     * data QC asli.
     * ==========================================
     */
		$auditData = [
			'uuid'               => $audit_uuid,
			'username'           => $username,
			'plant'              => $plant,
			'date'               => $date,
			'shift'              => $shift,
			'peneraan_hasil'     => json_encode($peneraan_hasil),
			'tindakan_perbaikan' => $tindakan_perbaikan,
			'keterangan'         => $keterangan,
			'status_produksi'    => $status_produksi,
			'nama_produksi'      => $nama_produksi,
			'status_spv'         => $status_spv,

			'is_audit'           => 1,
			'source_uuid'        => $uuid
		];

		/*
     * ==========================================
     * QC + AUDIT SATU TRANSAKSI
     * ==========================================
     */
		$this->db->trans_start();

		$this->db->insert('thermometer', $data);

		$this->db->insert('thermometer', $auditData);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return false;
		}

		return true;
	}

	public function update($uuid)
	{
		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$kode_thermo = $this->input->post('kode_thermo');
		$area = $this->input->post('area');
		$model = $this->input->post('model');
		$tindakan_perbaikan = $this->input->post('tindakan_perbaikan');
		$keterangan = $this->input->post('keterangan');
		$old_data = $this->db->get_where('thermometer', ['uuid' => $uuid])->row_array();
		$kode_thermo = $this->input->post('kode_thermo');
		$area = $this->input->post('area');
		$model = $this->input->post('model');
		$pukul = $this->input->post('pukul');
		$standar = $this->input->post('standar');
		$hasil = $this->input->post('hasil');

		$peneraan_hasil = [];
		foreach ($pukul as $i => $b) {
			$peneraan_hasil[] = [
				'pukul'   => $b,
				'kode_thermo'  => $kode_thermo[$i],
				'model'  => $model[$i],
				'area'  => $area[$i],
				'standar'  => $standar[$i],
				'hasil'  => $hasil[$i],
			];
		}

		$data = array(
			'username' => $username,
			'date' => $date,
			'shift' => $shift,
			'peneraan_hasil' => json_encode($peneraan_hasil),
			'tindakan_perbaikan' => $tindakan_perbaikan,
			'keterangan' => $keterangan,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('thermometer', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('thermometer', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus thermometer_logs
			$this->activity_logger->log_activity(
				'update',
				'thermometer_logs', // nama tabel log khusus thermometer
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

		$this->db->update('thermometer', $data, array('uuid' => $uuid));
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
			->update('thermometer', $data);

		return $this->db->affected_rows() > 0;
	}

	public function verifikasi_massal_spv($tanggal, $shift = '')
	{
		$plant = $this->session->userdata('plant');
		$tipe_user = $this->session->userdata('tipe_user');
		$username = $this->session->userdata('username');

		if (empty($tanggal) || empty($username)) {
			return false;
		}

		// Filter data yang memenuhi syarat
		$this->db
			->from('thermometer')
			->where('is_audit', 0)
			->where('DATE(date)', $tanggal)
			->where_in('status_spv', ['0', '2']);

		if ($tipe_user != 9 && $tipe_user != 1) {
			if (empty($plant)) {
				return false;
			}

			$this->db->where('plant', $plant);
		}

		if (!empty($shift) && $shift !== 'semua') {
			$this->db->where('shift', $shift);
		}

		$total = $this->db->count_all_results();

		if ($total == 0) {
			return 0;
		}

		// Update data
		$this->db->trans_begin();

		$this->db
			->where('is_audit', 0)
			->where('DATE(date)', $tanggal)
			->where_in('status_spv', ['0', '2']);

		if ($tipe_user != 9 && $tipe_user != 1) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift) && $shift !== 'semua') {
			$this->db->where('shift', $shift);
		}

		$this->db->update('thermometer', [
			'nama_spv' => $username,
			'status_spv' => '1',
			'tgl_update_spv' => date('Y-m-d H:i:s')
		]);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return $total;
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

		$this->db->update('thermometer', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('thermometer')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$query = $this->db->get('thermometer');

		log_message(
			'error',
			'Thermometer detail: UUID=' . $uuid .
				', plant=' . $plant .
				', query=' . $this->db->last_query()
		);

		return $query->row();
	}

	public function get_by_uuid_thermometer($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('thermometer');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_thermometer_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, nama_produksi, tgl_update_produksi, status_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('thermometer');

		$data_thermometer = $query->row();
		return $data_thermometer;
	}

	public function get_data_by_plant($limit = 10, $start = 0, $tanggal = '', $shift = '')
	{
		$plant = $this->session->userdata('plant');
		$tipe_user = $this->session->userdata('tipe_user');

		$this->db->where('is_audit', 0);

		// Pertahankan akses lintas plant untuk tipe user 1 dan 9
		if ($tipe_user != 9 && $tipe_user != 1) {
			if (!empty($plant)) {
				$this->db->where('plant', $plant);
			}
		}

		if (!empty($tanggal)) {
			$this->db->where('DATE(date)', $tanggal);
		}

		if (!empty($shift) && $shift !== 'semua') {
			$this->db->where('shift', $shift);
		}

		return $this->db
			->order_by('date', 'DESC')
			->order_by('created_at', 'DESC')
			->limit($limit, $start)
			->get('thermometer')
			->result();
	}

	public function count_thermometer_by_plant($tanggal = '', $shift = '')
	{
		$plant = $this->session->userdata('plant');
		$tipe_user = $this->session->userdata('tipe_user');

		$this->db
			->from('thermometer')
			->where('is_audit', 0);

		if ($tipe_user != 9 && $tipe_user != 1) {
			if (!empty($plant)) {
				$this->db->where('plant', $plant);
			}
		}

		if (!empty($tanggal)) {
			$this->db->where('DATE(date)', $tanggal);
		}

		if (!empty($shift) && $shift !== 'semua') {
			$this->db->where('shift', $shift);
		}

		return $this->db->count_all_results();
	}

	public function delete_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		return $this->db->delete('thermometer');
	}

	public function get_by_date($date, $plant = null)
	{
		$this->db->where('date', $date);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		return $this->db
			->order_by('created_at', 'ASC')
			->get('thermometer')
			->result();
	}

	public function get_last_verif_by_date($date, $plant = null)
	{
		$this->db->where('date', $date);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		return $this->db
			->order_by('created_at', 'DESC')
			->get('thermometer')
			->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('thermometer')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('thermometer')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('thermometer')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant, $shift = null)
	{
		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('thermometer')->result();
	}

	public function update_audit($uuid)
	{
		$username = $this->session->userdata('username');
		$plant    = $this->session->userdata('plant');

		$date = $this->input->post('date');
		$shift = $this->input->post('shift');

		$kode_thermo = $this->input->post('kode_thermo');
		$area        = $this->input->post('area');
		$model       = $this->input->post('model');
		$pukul       = $this->input->post('pukul');
		$standar     = $this->input->post('standar');
		$hasil       = $this->input->post('hasil');

		$tindakan_perbaikan = $this->input->post('tindakan_perbaikan');
		$keterangan         = $this->input->post('keterangan');

		$peneraan_hasil = [];

		if (is_array($pukul)) {
			foreach ($pukul as $i => $value) {
				$peneraan_hasil[] = [
					'pukul'        => isset($pukul[$i]) ? $pukul[$i] : '',
					'kode_thermo'  => isset($kode_thermo[$i]) ? $kode_thermo[$i] : '',
					'model'        => isset($model[$i]) ? $model[$i] : '',
					'area'         => isset($area[$i]) ? $area[$i] : '',
					'standar'      => isset($standar[$i]) ? $standar[$i] : '',
					'hasil'        => isset($hasil[$i]) ? $hasil[$i] : '',
				];
			}
		}

		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 1);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$old_data = $this->db->get('thermometer')->row_array();

		if (!$old_data) {
			return false;
		}

		$data = [
			'username'             => $username,
			'date'                 => $date,
			'shift'                => $shift,
			'peneraan_hasil'       => json_encode($peneraan_hasil),
			'tindakan_perbaikan'   => $tindakan_perbaikan,
			'keterangan'           => $keterangan,
			'modified_at'          => date('Y-m-d H:i:s')
		];

		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 1);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$update = $this->db->update('thermometer', $data);

		if (!$update) {
			return false;
		}

		// Ambil data Audit terbaru untuk log
		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 1);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$new_data = $this->db->get('thermometer')->row_array();

		$this->activity_logger->log_activity(
			'update',
			'thermometer_logs',
			$uuid,
			$old_data,
			$new_data
		);

		return true;
	}

	public function get_qc_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->get('thermometer')
			->row();
	}
}
