<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Timbangan_model extends CI_Model
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
			// 	'field' => 'kode_timbangan',
			// 	'label' => 'Scales Code',
			// 	'rules' => 'required'
			// ], 
			// [
			// 	'field' => 'kapasitas',
			// 	'label' => 'Capacity',
			// 	'rules' => 'required'
			// ],
			// [
			// 	'field' => 'model',
			// 	'label' => 'Type',
			// 	'rules' => 'required'
			// ],
			// [
			// 	'field' => 'lokasi', 
			// 	'label' => 'Location',
			// 	'rules' => 'required'
			// ],
			// [
			// 	'field' => 'peneraan_standar',
			// 	'label' => 'Standart of Calibration',
			// 	'rules' => 'required'
			// ], 
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


	public function insert()
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$uuid = Uuid::uuid4()->toString();
		$audit_uuid = Uuid::uuid4()->toString();

		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$pukul = $this->input->post('pukul') ?? [];
		$kode_timbangan = $this->input->post('kode_timbangan') ?? [];
		$kapasitas = $this->input->post('kapasitas') ?? [];
		$model = $this->input->post('model') ?? [];
		$lokasi = $this->input->post('lokasi') ?? [];
		$peneraan_standar = $this->input->post('peneraan_standar') ?? [];
		$hasil = $this->input->post('hasil') ?? [];

		$peneraan_hasil = [];

		foreach ($pukul as $i => $jam) {
			$peneraan_hasil[] = [
				'pukul' => $jam,
				'kode_timbangan' => $kode_timbangan[$i] ?? '',
				'kapasitas' => $kapasitas[$i] ?? '',
				'model' => $model[$i] ?? '',
				'lokasi' => $lokasi[$i] ?? '',
				'peneraan_standar' => $peneraan_standar[$i] ?? '',
				'hasil' => $hasil[$i] ?? '',
			];
		}

		$data = [
			'username' => $username,
			'plant' => $plant,
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'peneraan_hasil' => json_encode(
				$peneraan_hasil,
				JSON_UNESCAPED_UNICODE
			),
			'keterangan' => $this->input->post('keterangan'),
			'catatan' => $this->input->post('catatan'),
			'status_produksi' => '1',
			'nama_produksi' => $nama_produksi,
			'status_spv' => '0',
		];

		// Record QC asli
		$qc_data = array_merge($data, [
			'uuid' => $uuid,
			'is_audit' => 0,
			'source_uuid' => null,
		]);

		// Snapshot audit terpisah
		$audit_data = array_merge($data, [
			'uuid' => $audit_uuid,
			'is_audit' => 1,
			'source_uuid' => $uuid,
		]);

		$this->db->trans_begin();

		$this->db->insert('timbangan', $qc_data);

		if ($this->db->affected_rows() <= 0) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->insert('timbangan', $audit_data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return $this->db->trans_status() !== false;
	}

	public function update($uuid)
	{
		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$kode_timbangan = $this->input->post('kode_timbangan');
		$kapasitas = $this->input->post('kapasitas');
		$model = $this->input->post('model');
		$lokasi = $this->input->post('lokasi');
		$peneraan_standar = $this->input->post('peneraan_standar');
		$keterangan = $this->input->post('keterangan');
		$catatan = $this->input->post('catatan');
		$old_data = $this->db->get_where('timbangan', ['uuid' => $uuid])->row_array();
		$pukul = $this->input->post('pukul');
		$hasil = $this->input->post('hasil');

		$peneraan_hasil = [];
		foreach ($pukul as $i => $b) {
			$peneraan_hasil[] = [
				'pukul'   => $b,
				'kode_timbangan'  => $kode_timbangan[$i],
				'kapasitas'  => $kapasitas[$i],
				'model'  => $model[$i],
				'lokasi'  => $lokasi[$i],
				'peneraan_standar'  => $peneraan_standar[$i],
				'hasil'  => $hasil[$i],
			];
		}

		$data = array(
			'username' => $username,
			'date' => $date,
			'shift' => $shift,
			'peneraan_hasil' => json_encode($peneraan_hasil),
			'keterangan' => $keterangan,
			'catatan' => $catatan,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('timbangan', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('timbangan', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus timbangan_logs
			$this->activity_logger->log_activity(
				'update',
				'timbangan_logs', // nama tabel log khusus timbangan
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

		$this->db->update('timbangan', $data, array('uuid' => $uuid));
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

		$this->db->update('timbangan', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('timbangan')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		return $this->db->get('timbangan')->row();
	}

	public function get_by_uuid_timbangan($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('timbangan');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_timbangan_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, nama_produksi, tgl_update_produksi, status_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('timbangan');

		$data_timbangan = $query->row();
		return $data_timbangan;
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

		return $this->db->get('timbangan')->result();
	}

	public function get_by_plant()
	{
		return $this->db
			->where('plant', $this->session->userdata('plant'))
			->order_by('kode_timbangan', 'ASC')
			->get('list_timbangan')
			->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('timbangan');
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
		$query = $this->db->get('timbangan');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null, $shift = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, nama_produksi, tgl_update_produksi, status_produksi');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('timbangan');

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('timbangan')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('timbangan')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('timbangan')
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

		return $this->db->get('timbangan')->result();
	}


	public function update_audit($uuid)
	{
		// Pastikan record adalah data audit milik plant aktif.
		$audit = $this->get_audit_by_uuid($uuid);

		if (!$audit) {
			return false;
		}

		$fields = [
			'pukul',
			'kode_timbangan',
			'kapasitas',
			'model',
			'lokasi',
			'peneraan_standar',
			'hasil'
		];

		$posted = [];

		foreach ($fields as $field) {
			$value = $this->input->post($field);

			$posted[$field] = is_array($value) ? $value : [];
		}

		// Tentukan jumlah baris detail yang dikirim.
		$jumlah = 0;

		foreach ($posted as $values) {
			$jumlah = max($jumlah, count($values));
		}

		$detail = [];

		for ($i = 0; $i < $jumlah; $i++) {
			$row = [];

			foreach ($fields as $field) {
				$row[$field] = trim(
					(string) ($posted[$field][$i] ?? '')
				);
			}

			// Lewati baris yang sepenuhnya kosong.
			$terisi = false;

			foreach ($row as $value) {
				if ($value !== '') {
					$terisi = true;
					break;
				}
			}

			if ($terisi) {
				$detail[] = $row;
			}
		}

		$data = [
			'date'            => $this->input->post('date', true),
			'shift'           => $this->input->post('shift', true),
			'peneraan_hasil'  => json_encode(
				$detail,
				JSON_UNESCAPED_UNICODE
			),
			'keterangan'      => $this->input->post('keterangan', true),
			'catatan'         => $this->input->post('catatan', true)
		];

		$this->db->trans_begin();

		// Pembatasan penting: hanya UUID audit dan plant aktif.
		$this->db
			->where('uuid', $uuid)
			->where('plant', $this->session->userdata('plant'))
			->where('is_audit', 1)
			->update('timbangan', $data);

		if (
			$this->db->trans_status() === false ||
			$this->db->error()['code'] !== 0
		) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}

	public function get_qc_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 0);

		$plant = $this->session->userdata('plant');

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		return $this->db->get('timbangan')->row();
	}
}
