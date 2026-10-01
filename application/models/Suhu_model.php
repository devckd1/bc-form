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
		$status_produksi = "1";
		$status_spv = "0";

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
			'status_spv' => $status_spv
		];

		$this->db->insert('suhu', $data);
		return $this->db->affected_rows() > 0;
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

		// Urut tanggal terbaru dulu
		$this->db->order_by('date', 'DESC');

		// Urut shift perhari: Shift 2 → Shift 1 → Shift 3
		$this->db->order_by("
			CASE
			WHEN TIME(pukul) >= '15:00:00' AND TIME(pukul) < '23:00:00' THEN 1  -- Shift 2
			WHEN TIME(pukul) >= '07:00:00' AND TIME(pukul) < '15:00:00' THEN 2  -- Shift 1
			WHEN TIME(pukul) >= '23:00:00' OR TIME(pukul) < '07:00:00' THEN 3   -- Shift 3
			END
			", '', false);

		// Urut jam kronologis di dalam shift
		$this->db->order_by('pukul', 'DESC');

		return $this->db->get()->result();
	}

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
		$this->db->order_by('pukul', 'ASC');

		$results = $this->db->get('suhu')->result();

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
