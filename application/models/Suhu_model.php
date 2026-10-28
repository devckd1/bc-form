<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Suhu_model extends CI_Model
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
				'field' => 'pukul',
				'label' => 'Pukul',
				'rules' => 'required'
			],
			[
				'field' => 'shift',
				'label' => 'Shift',
				'rules' => 'required'
			],
			[
				'field' => 'catatan',
				'label' => 'Catatan',
				'rules' => 'trim'
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
		$pukul = $this->input->post('pukul');
		$shift = $this->input->post('shift');
		$lokasi = $this->input->post('lokasi');
		$catatan = $this->input->post('catatan');

		$status_produksi = "0";
		$status_spv = "0";

		/*
	|--------------------------------------------------------------------------
	| Data QC Utama
	|--------------------------------------------------------------------------
	*/
		$data = [
			'uuid' => $uuid,
			'plant' => $plant,
			'username' => $username,
			'date' => $date,
			'pukul' => $pukul,
			'shift' => $shift,
			'lokasi' => json_encode($lokasi),
			'catatan' => $catatan,
			'nama_produksi' => $nama_produksi,
			'status_produksi' => $status_produksi,
			'status_spv' => $status_spv,
			'is_audit' => 0,
			'source_uuid' => null
		];

		$this->db->trans_start();

		// Simpan data QC utama
		$this->db->insert('suhu', $data);

		/*
	|--------------------------------------------------------------------------
	| Buat Audit Otomatis
	|--------------------------------------------------------------------------
	*/
		if ($this->db->affected_rows() > 0) {

			$audit_uuid = Uuid::uuid4()->toString();

			$audit_lokasi = [];

			if (!empty($lokasi) && is_array($lokasi)) {

				foreach ($lokasi as $item) {

					$nama_lokasi = trim($item['nama_lokasi'] ?? '');

					if ($nama_lokasi === '') {
						continue;
					}

					// Ambil Master Suhu sesuai plant + lokasi
					$master = $this->db
						->where('plant', $plant)
						->where('nama_lokasi', $nama_lokasi)
						->where('is_active', 1)
						->get('master_suhu')
						->row();

					$standar_suhu = null;
					$standar_rh = null;

					if ($master) {

						$standar_suhu = $this->convert_range_to_midpoint(
							$master->standar_suhu
						);

						if (!empty($master->standar_rh)) {
							$standar_rh = $this->convert_range_to_midpoint(
								$master->standar_rh
							);
						}
					}

					$audit_lokasi[] = [
						'nama_lokasi' => $nama_lokasi,

						// Hasil pemeriksaan QC
						'suhu' => $item['suhu'] ?? '',
						'rh' => $item['rh'] ?? '',

						// Snapshot standar dari Master
						'standar_suhu' => $standar_suhu,
						'standar_rh' => $standar_rh
					];
				}
			}

			/*
		|--------------------------------------------------------------------------
		| Data Audit
		|--------------------------------------------------------------------------
		*/
			$audit_data = [
				'uuid' => $audit_uuid,
				'plant' => $plant,
				'username' => $username,
				'date' => $date,
				'pukul' => $pukul,
				'shift' => $shift,
				'lokasi' => json_encode($audit_lokasi),
				'catatan' => $catatan,
				'nama_produksi' => $nama_produksi,
				'status_produksi' => $status_produksi,
				'status_spv' => $status_spv,
				'is_audit' => 1,
				'source_uuid' => $uuid
			];

			$this->db->insert('suhu', $audit_data);
		}

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	private function convert_range_to_midpoint($range)
	{
		if ($range === null || $range === '') {
			return null;
		}

		$range = trim($range);

		// Hilangkan satuan jika ada
		$range = str_replace(['°C', '%'], '', $range);

		// Normalisasi tanda minus/spasi
		$range = preg_replace('/\s+/', ' ', $range);

		/*
	|--------------------------------------------------------------------------
	| Format: 25 - 35
	|--------------------------------------------------------------------------
	*/
		if (preg_match('/^(-?\d+(?:\.\d+)?)\s*-\s*(-?\d+(?:\.\d+)?)$/', $range, $match)) {

			$min = (float) $match[1];
			$max = (float) $match[2];

			return ($min + $max) / 2;
		}

		/*
	|--------------------------------------------------------------------------
	| Kalau Master berisi angka tunggal
	|--------------------------------------------------------------------------
	*/
		if (is_numeric($range)) {
			return (float) $range;
		}

		return null;
	}

	public function update($uuid)
	{
		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$pukul = $this->input->post('pukul');
		$shift = $this->input->post('shift');
		$lokasi = $this->input->post('lokasi');
		$catatan = $this->input->post('catatan');
		$old_data = $this->db->get_where('suhu', ['uuid' => $uuid])->row_array();
		$data = [
			'username' => $username,
			'date' => $date,
			'pukul' => $pukul,
			'shift' => $shift,
			'lokasi' => json_encode($lokasi),
			'catatan' => $catatan,
			'modified_at' => date("Y-m-d H:i:s")
		];


		$this->db->update('suhu', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('suhu', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus suhu_logs
			$this->activity_logger->log_activity(
				'update',
				'suhu_logs', // nama tabel log khusus suhu
				$uuid,
				$old_data,
				$new_data
			);
			return true;
		}
		return false;
	}

	public function update_audit($uuid)
	{
		$username = $this->session->userdata('username');
		$plant    = $this->session->userdata('plant');

		$date    = $this->input->post('date');
		$pukul   = $this->input->post('pukul');
		$shift   = $this->input->post('shift');
		$lokasi  = $this->input->post('lokasi');
		$catatan = $this->input->post('catatan');

		$old_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('suhu')
			->row_array();

		if (!$old_data) {
			return false;
		}

		/*
     * Ambil standar dari Master Suhu
     * berdasarkan nama lokasi.
     */
		$audit_lokasi = [];

		if (!empty($lokasi) && is_array($lokasi)) {

			foreach ($lokasi as $item) {

				$nama_lokasi = trim($item['nama_lokasi'] ?? '');

				if ($nama_lokasi === '') {
					continue;
				}

				$master = $this->db
					->where('plant', $plant)
					->where('nama_lokasi', $nama_lokasi)
					->where('is_active', 1)
					->get('master_suhu')
					->row();

				$standar_suhu = null;
				$standar_rh   = null;

				if ($master) {
					// Simpan RANGE asli dari Master
					$standar_suhu = $master->standar_suhu;
					$standar_rh   = $master->standar_rh;
				}

				$audit_lokasi[] = [
					'nama_lokasi'  => $nama_lokasi,
					'suhu'         => $item['suhu'] ?? '',
					'rh'           => $item['rh'] ?? '',
					'standar_suhu' => $standar_suhu,
					'standar_rh'   => $standar_rh
				];
			}
		}

		$data = [
			'username'    => $username,
			'date'        => $date,
			'pukul'       => $pukul,
			'shift'       => $shift,
			'lokasi'      => json_encode($audit_lokasi),
			'catatan'     => $catatan,
			'modified_at' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('suhu', $data);

		$new_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('suhu')
			->row_array();

		if ($this->db->affected_rows() > 0) {

			$this->activity_logger->log_activity(
				'update',
				'suhu_logs',
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

		$this->db->update('suhu', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function verifikasi_produksi($uuid)
	{
		$plant = $this->session->userdata('plant');

		$suhu = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->where('status_produksi', '0')
			->get('suhu')
			->row();

		if (!$suhu) {
			return false;
		}

		$data = [
			'nama_produksi' => $this->session->userdata('username'),
			'status_produksi' => '1',
			'tgl_update_produksi' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->where('status_produksi', '0')
			->update('suhu', $data);

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

		$this->db->update('suhu', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('suhu')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('suhu', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_suhu($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('suhu');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_suhu_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, nama_produksi, status_produksi, tgl_update_produksi, shift');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('suhu');

		$data_suhu = $query->row();
		return $data_suhu;
	}

	public function get_suhu_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->select('*');
		$this->db->from('suhu');
		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 0); // Hanya data utama, bukan audit

		// Urut tanggal terbaru dulu
		$this->db->order_by('date', 'DESC');

		// Urut shift perhari: Shift 2 → Shift 1 → Shift 3
		$this->db->order_by("
        CASE
            WHEN TIME(pukul) >= '15:00:00' AND TIME(pukul) < '23:00:00' THEN 1
            WHEN TIME(pukul) >= '07:00:00' AND TIME(pukul) < '15:00:00' THEN 2
            WHEN TIME(pukul) >= '23:00:00' OR TIME(pukul) < '07:00:00' THEN 3
        END
    ", '', false);

		// Urut jam kronologis di dalam shift
		$this->db->order_by('pukul', 'DESC');

		return $this->db->get()->result();
	}


	//public function get_suhu_by_plant($limit, $start)
	//{
	//	$plant = $this->session->userdata('plant');
	//	$type_user = $this->session->userdata('tipe_user');

	//	$this->db->select('uuid,date,shift,pukul,status_spv,modified_at,tgl_update_spv');
	//	$this->db->from('suhu');

	// HANYA DATA UTAMA
	//	$this->db->where('is_audit', 0);

	//	if (!in_array($type_user, [9, 1])) {
	//		$this->db->where('plant', $plant);
	//	}

	//	$this->db->order_by('date', 'DESC');

	//	$this->db->order_by("
	//    CASE
	//        WHEN TIME(pukul) >= '15:00:00'
	//            AND TIME(pukul) < '23:00:00' THEN 1
	//        WHEN TIME(pukul) >= '07:00:00'
	//            AND TIME(pukul) < '15:00:00' THEN 2
	//        WHEN TIME(pukul) >= '23:00:00'
	//            OR TIME(pukul) < '07:00:00' THEN 3
	//        END
	//", '', false);

	//	$this->db->order_by('pukul', 'DESC');

	//	$this->db->limit($limit, $start);

	//	return $this->db->get()->result();
	//}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('suhu');
	}
	public function get_by_date($tanggal)
	{
		$this->db->where('DATE(date)', $tanggal);
		$this->db->order_by('pukul', 'ASC');
		return $this->db->get('suhu')->result();
	}

	public function get_by_date_verif($tanggal)
	{
		$this->db->where('DATE(date)', $tanggal);
		$this->db->order_by('pukul', 'ASC');
		$query = $this->db->get('suhu');
		$result = $query->result();

		return !empty($result) ? $result[0] : null;
	}

	public function get_by_date_verif_excel($tanggal)
	{
		$this->db->where('DATE(date)', $tanggal);
		$this->db->order_by('pukul', 'ASC');
		return $this->db->get('suhu')->result();
	}

	public function get_by_date_and_plant($tanggal, $plant)
	{
		if ($plant === '651ac623-5e48-44cc-b2f6-5d622603f53c') {
			$this->db->where_in('lokasi', [
				"Ruang Produksi",
				"Gudang Premix",
				"Gudang Raw Material",
				"Gudang Finish Good",
				"Proofing Room",
				"Aging Room 1",
				"Aging Room 2",
				"Ruang Produksi (Bubble)"
			]);
		} else {
			$this->db->where_in('lokasi', [
				"Ruang Pengayakan",
				"Ruang RM",
				"Chiller 1",
				"Chiller 2",
				"Chiller 3",
				"Chiller 4",
				"Chiller 5",
				"Chiller 6",
				"Ruang Mixing",
				"Area Baking",
				"Area Cutting & Grinding",
				"Ruang Aging",
				"Area Packing"
			]);
		}
		$this->db->where('date', $tanggal);
		return $this->db->get('suhu')->result();
	}

	public function get_by_date_and_plant_pdf($tanggal, $plant_uuid)
	{
		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('plant', $plant_uuid);
		return $this->db->get('suhu')->result();
	}

	public function get_by_date_verif_and_plant($tanggal, $plant_uuid)
	{
		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('plant', $plant_uuid);
		return $this->db->get('suhu')->row();
	}

	public function get_suhu_today_by_plant($plant_uuid, $tanggal = null)
	{
		$this->db->where('plant', $plant_uuid);

		if ($tanggal) {
			$this->db->where('DATE(date)', $tanggal);
		} else {
			$this->db->where('DATE(date)', date('Y-m-d'));
		}

		$this->db->order_by('date', 'ASC');
		$query = $this->db->get('suhu');
		$results = $query->result();

		foreach ($results as $row) {
			$row->lokasi = json_decode($row->lokasi, true);
		}

		return $results;
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('suhu')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('suhu')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('suhu')
			->row();
	}

	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 0);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('suhu')->result();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		$this->db->where('date', $tanggal);
		$this->db->where('plant', $plant);
		$this->db->order_by('shift', 'ASC');
		$this->db->order_by('pukul', 'ASC');

		return $this->db
			->get('suhu')
			->result();
	}
}
