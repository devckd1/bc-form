<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Pecahbelah_model extends CI_Model
{

	protected $table = 'benda_pecah';

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
			]
		];
	}

	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');

		$nama_barang = $this->input->post('nama_barang');
		$area = $this->input->post('area');
		$pemilik = $this->input->post('pemilik');
		$jumlah = $this->input->post('jumlah');
		$kondisi_awal = $this->input->post('kondisi_awal');
		$keterangan = $this->input->post('keterangan');

		$benda_pecah = [];
		for ($i = 0; $i < count($nama_barang); $i++) {
			$benda_pecah[] = [
				'nama_barang'   => $nama_barang[$i],
				'area'          => $area[$i] ?? '',
				'pemilik'       => $pemilik[$i] ?? '',
				'jumlah'        => $jumlah[$i] ?? '',
				'kondisi_awal'  => isset($kondisi_awal[$i]) ? $kondisi_awal[$i] : 'Tidak Ok',
				'kondisi_akhir' => '-',
				'keterangan'    => $keterangan[$i] ?? '',
			];
		}

		$data = [
			'uuid'              => $uuid,
			'username'          => $username,
			'plant'             => $plant,
			'date'              => $date,
			'shift'             => $shift,
			'benda_pecah'       => json_encode($benda_pecah),
			'status_spv'        => "0",
			'status_produksi'   => "0"
		];

		$this->db->insert('benda_pecah', $data);
		return ($this->db->affected_rows() > 0);
	}

	public function update($uuid)
	{
		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');

		$nama_barang = $this->input->post('nama_barang');
		$area = $this->input->post('area');
		$pemilik = $this->input->post('pemilik');
		$jumlah = $this->input->post('jumlah');
		$kondisi_awal = $this->input->post('kondisi_awal');
		$kondisi_akhir = $this->input->post('kondisi_akhir');
		$keterangan = $this->input->post('keterangan');

		$old_data = $this->db->get_where('benda_pecah', ['uuid' => $uuid])->row_array();

		$benda_pecah = [];
		foreach ($nama_barang as $i => $b) {
			$benda_pecah[] = [
				'nama_barang'   => $b,
				'area'          => $area[$i] ?? '',
				'pemilik'       => $pemilik[$i] ?? '',
				'jumlah'        => $jumlah[$i] ?? '',
				'kondisi_awal'  => isset($kondisi_awal[$i]) ? 'Ok' : 'Tidak Ok',
				'kondisi_akhir' => isset($kondisi_akhir[$i]) ? 'Ok' : 'Tidak Ok',
				'keterangan'    => $keterangan[$i] ?? '',
			];
		}

		$updateData = [
			'qc_update'      => $username,
			'date'          => $date,
			'shift'         => $shift,
			'benda_pecah'   => json_encode($benda_pecah),
			'modified_at'   => date('Y-m-d H:i:s'),
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('benda_pecah', $updateData);

		if ($this->db->affected_rows() > 0) {
			$new_data = $this->db->get_where('benda_pecah', ['uuid' => $uuid])->row_array();

			// Catat log activity
			$this->activity_logger->log_activity(
				'update',
				'benda_pecah_logs',
				$uuid,
				$old_data,
				$new_data
			);

			return true;
		}

		return false;
	}

	public function update_check($uuid)
	{
		$username = $this->session->userdata('username');
		$nama_barang = $this->input->post('nama_barang');
		$area = $this->input->post('area');
		$pemilik = $this->input->post('pemilik');
		$jumlah = $this->input->post('jumlah');
		$kondisi_awal = $this->input->post('kondisi_awal');
		$keterangan = $this->input->post('keterangan');
		$kondisi_akhir = $this->input->post('kondisi_akhir');

		$benda_pecah = [];
		foreach ($nama_barang as $i => $b) {
			$benda_pecah[] = [
				'nama_barang'   => $b,
				'area'  => $area[$i],
				'pemilik'  => $pemilik[$i],
				'jumlah'  => $jumlah[$i],
				'kondisi_awal'  => $kondisi_awal[$i],
				'kondisi_akhir' => isset($kondisi_akhir[$i]) ? $kondisi_akhir[$i] : '',
				'keterangan' => $keterangan[$i],
			];
		}

		$updateData = [
			'qc_update' => $username,
			'benda_pecah' => json_encode($benda_pecah),
			'modified_at' => date('Y-m-d H:i:s'),
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('benda_pecah', $updateData);

		return ($this->db->affected_rows() > 0);
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

		$this->db->update('benda_pecah', $data, array('uuid' => $uuid));
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

		$this->db->update('benda_pecah', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}
	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('benda_pecah')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('benda_pecah', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_pecahbelah($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('benda_pecah');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_pecahbelah_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, qc_update, shift, created_at, modified_at, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('benda_pecah');

		$data_benda_pecah = $query->row();
		return $data_benda_pecah;
	}

	public function get_data_by_plant()
	{
		$this->db->order_by('created_at', 'DESC');

		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 0);

		return $this->db->get('benda_pecah')->result();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('benda_pecah')
			->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('benda_pecah')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('benda_pecah')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant = null, $shift = null)
	{
		if (empty($tanggal)) {
			return [];
		}

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 1);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('benda_pecah')->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('benda_pecah');
	}

	public function get_by_date($tanggal, $plant = null, $shift = null)
	{
		if (empty($tanggal)) {
			return [];
		}

		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('created_at', 'ASC');

		return $this->db->get('benda_pecah')->result();
	}

	public function get_by_date_verif($tanggal, $plant = null, $shift = null)
	{
		if (empty($tanggal)) {
			return null;
		}

		$this->db->select('nama_spv, tgl_update_spv, username, date, qc_update, shift, created_at, modified_at, nama_produksi, status_produksi, tgl_update_produksi');

		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		return $this->db->get('benda_pecah')->row();
	}
}
