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
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$keterangan = $this->input->post('keterangan');
		$catatan = $this->input->post('catatan');
		$status_produksi = "1";
		$status_spv = "0";

		$kode_timbangan = $this->input->post('kode_timbangan');
		$kapasitas = $this->input->post('kapasitas');
		$model = $this->input->post('model');
		$lokasi = $this->input->post('lokasi');
		$peneraan_standar = $this->input->post('peneraan_standar');
		$pukul = $this->input->post('pukul');
		$hasil = $this->input->post('hasil');

		$peneraan_hasil = [];
		for ($i = 0; $i < count($pukul); $i++) {
			$peneraan_hasil[] = [
				'pukul' => $pukul[$i],
				'kode_timbangan' => isset($kode_timbangan[$i]) ? $kode_timbangan[$i] : '',
				'kapasitas' => isset($kapasitas[$i]) ? $kapasitas[$i] : '',
				'model' => isset($model[$i]) ? $model[$i] : '',
				'lokasi' => isset($lokasi[$i]) ? $lokasi[$i] : '',
				'peneraan_standar' => isset($peneraan_standar[$i]) ? $peneraan_standar[$i] : '',
				'hasil' => isset($hasil[$i]) ? $hasil[$i] : '',
			];
		}

		$data = array(
			'uuid' => $uuid,
			'username' => $username,
			'plant' => $plant,
			'date' => $date,
			'shift' => $shift,
			'peneraan_hasil' => json_encode($peneraan_hasil),
			'keterangan' => $keterangan,
			'catatan' => $catatan,
			'status_produksi' => $status_produksi,
			'nama_produksi' => $nama_produksi,
			'status_spv' => $status_spv
		);

		$this->db->insert('timbangan', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
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
		$data = $this->db->get_where('timbangan', array('uuid' => $uuid))->row();
		return $data;
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
		$this->db->order_by('created_at', 'DESC');

		$plant = $this->session->userdata('plant');

		return $this->db->get_where('timbangan', [
			'plant'    => $plant,
			'is_audit' => 0
		])->result();
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
}
