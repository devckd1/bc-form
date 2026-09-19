<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Magnettrap_model extends CI_Model
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
				'field' => 'nama_produk',
				'label' => 'Nama Produk',
				'rules' => 'required'
			],
			[
				'field' => 'kode_produksi',
				'label' => 'Kode Produksi',
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
				'field' => 'tahapan',
				'label' => 'Step of'
			],
			[
				'field' => 'kontaminasi',
				'label' => 'Type of Contamination'
			],
			[
				'field' => 'bukti',
				'label' => 'Evidence',
				'rules' => 'callback_file_check'
			],
			[
				'field' => 'analisis',
				'label' => 'Analysis'
			],
			[
				'field' => 'tindakan',
				'label' => 'Action'
			],
			[
				'field' => 'verifikasi',
				'label' => 'Verification'
			],
			[
				'field' => 'catatan',
				'label' => 'Notes'
			],
			[
				'field' => 'keterangan',
				'label' => 'Notes'
			]
		];
	}

	// ======================= Insert Data =======================
	public function insert($file_name)
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';
		$uuid = Uuid::uuid4()->toString();
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$data = [
			'uuid' => $uuid,
			'username' => $username,
			'plant' => $plant,
			'nama_produk' => $this->input->post('nama_produk'),
			'kode_produksi' => $this->input->post('kode_produksi'),
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'time' => $this->input->post('time'),
			'tahapan' => $this->input->post('tahapan'),
			'kontaminasi' => $this->input->post('kontaminasi'),
			'analisis' => $this->input->post('analisis'),
			'tindakan' => $this->input->post('tindakan'),
			'verifikasi' => $this->input->post('verifikasi'),
			'catatan' => $this->input->post('catatan'),
			'keterangan' => $this->input->post('keterangan'),
			'bukti' => $file_name,
			'status_enginer' => "0",
			'nama_enginer' => $nama_produksi,
			'status_spv' => "0",
			'created_at' => date("Y-m-d H:i:s")
		];

		return $this->db->insert('magnet_trap', $data);
	}

	// ======================= Update Data =======================
	public function update($uuid, $file_name)
	{
		$old_data = $this->db->get_where('magnet_trap', ['uuid' => $uuid])->row_array();

		$data = [
			'username' => $this->session->userdata('username'),
			'nama_produk' => $this->input->post('nama_produk'),
			'kode_produksi' => $this->input->post('kode_produksi'),
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'time' => $this->input->post('time'),
			'tahapan' => $this->input->post('tahapan'),
			'kontaminasi' => $this->input->post('kontaminasi'),
			'analisis' => $this->input->post('analisis'),
			'tindakan' => $this->input->post('tindakan'),
			'verifikasi' => $this->input->post('verifikasi'),
			'catatan' => $this->input->post('catatan'),
			'keterangan' => $this->input->post('keterangan'),
			'bukti' => $file_name,
			'modified_at' => date("Y-m-d H:i:s")
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('magnet_trap', $data);

		if (isset($this->activity_logger)) {
			$old_data = $this->db->get_where('magnet_trap', ['uuid' => $uuid])->row_array();
			$this->activity_logger->log_activity(
				'update',
				'magnet_trap_logs',
				$uuid,
				$old_data,
				$data
			);
		}

		return true;
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

		$this->db->update('magnet_trap', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function verifikasi_produksi($uuid)
	{
		$nama_enginer = $this->session->userdata('username');

		$data = [
			'nama_enginer' => $nama_enginer,
			'status_enginer' => '1',
			'tgl_update_enginer' => date('Y-m-d H:i:s')
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('magnet_trap', $data);

		return ($this->db->affected_rows() > 0);
	}

	public function rules_diketahui()
	{
		return [
			[
				'field' => 'status_enginer',
				'label' => 'Status',
				'rules' => 'required'
			],
			[
				'field' => 'catatan_enginer',
				'label' => 'Notes'
			]

		];
	}

	public function diketahui_update($uuid)
	{

		$nama_enginer = $this->session->userdata('username');
		$status_enginer = $this->input->post('status_enginer');
		$catatan_enginer = $this->input->post('catatan_enginer');

		$data = array(
			'nama_enginer' => $nama_enginer,
			'status_enginer' => $status_enginer,
			'catatan_enginer' => $catatan_enginer,
			'tgl_update_enginer' => date("Y-m-d H:i:s")
		);

		$this->db->update('magnet_trap', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('magnet_trap')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('magnet_trap', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_magnettrap($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('magnet_trap');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_magnettrap_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, date, username, shift, nama_enginer, status_enginer, tgl_update_enginer');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('magnet_trap');

		$data_magnet_trap = $query->row();
		return $data_magnet_trap;
	}

	public function get_data_by_plant()
	{
		$this->db->order_by('created_at', 'DESC');
		$plant = $this->session->userdata('plant');
		return $this->db->get_where('magnet_trap', ['plant' => $plant])->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('magnet_trap');
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
		$query = $this->db->get('magnet_trap');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

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
			date,
			username,
			shift,
			nama_enginer,
			status_enginer,
			tgl_update_enginer,
			created_at,
			status_spv
		');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('magnet_trap');

		return $query->row();
	}

	public function get_by_month($tahun, $bulan, $plant)
	{
		return $this->db
			->where('YEAR(date)', $tahun)
			->where('MONTH(date)', $bulan)
			->where('plant', $plant)
			->order_by('date', 'ASC')
			->get('magnet_trap')
			->result();
	}
}
