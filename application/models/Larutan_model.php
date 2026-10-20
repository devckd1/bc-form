<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Larutan_model extends CI_Model
{

	// protected $table = 'larutan';
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
		$produksi_data = $this->session->userdata('produksi_data');
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$bahan        = $this->input->post('bahan');
		$kadar        = $this->input->post('kadar');
		$bahan_kimia  = $this->input->post('bahan_kimia');
		$air_bersih   = $this->input->post('air_bersih');
		$volume_akhir = $this->input->post('volume_akhir');
		$kebutuhan    = $this->input->post('kebutuhan');
		$keterangan   = $this->input->post('keterangan');
		$tindakan     = $this->input->post('tindakan');
		$verifikasi   = $this->input->post('verifikasi');

		$nama_bahan = [];

		if (!is_array($bahan) || empty($bahan)) {
			return false;
		}

		for ($i = 0; $i < count($bahan); $i++) {
			$nama_bahan[] = [
				'bahan'        => $bahan[$i] ?? '',
				'kadar'        => $kadar[$i] ?? '',
				'bahan_kimia'  => $bahan_kimia[$i] ?? '',
				'air_bersih'   => $air_bersih[$i] ?? '',
				'volume_akhir' => $volume_akhir[$i] ?? '',
				'kebutuhan'    => $kebutuhan[$i] ?? '',
				'keterangan'   => isset($keterangan[$i]) ? 'Sesuai' : '',
				'tindakan'     => $tindakan[$i] ?? '',
				'verifikasi'   => $verifikasi[$i] ?? ''
			];
		}

		$uuid = Uuid::uuid4()->toString();

		$data = [
			'uuid'            => $uuid,
			'username'        => $username,
			'plant'           => $plant,
			'date'            => $this->input->post('date'),
			'shift'           => $this->input->post('shift'),
			'nama_bahan'      => json_encode($nama_bahan),
			'catatan'         => $this->input->post('catatan'),
			'status_spv'      => '0',
			'status_produksi' => '1',
			'nama_produksi'   => $produksi_data['nama_produksi'] ?? '',
			'created_at'      => date('Y-m-d H:i:s'),
			'is_audit'        => 0,
			'source_uuid'     => null
		];

		$this->db->trans_begin();

		// Simpan data QC
		$this->db->insert('larutan', $data);

		// Buat snapshot Audit dari data QC
		if ($this->db->trans_status() !== false) {
			$audit_data = $data;
			$audit_data['uuid'] = Uuid::uuid4()->toString();
			$audit_data['is_audit'] = 1;
			$audit_data['source_uuid'] = $uuid;

			$this->db->insert('larutan', $audit_data);
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}

	public function update($uuid)
	{
		$username     = $this->session->userdata('username');
		$plant        = $this->session->userdata('plant');
		$date         = $this->input->post('date');
		$shift        = $this->input->post('shift');
		$catatan      = $this->input->post('catatan');

		$bahan         = $this->input->post('bahan');
		$kadar         = $this->input->post('kadar');
		$bahan_kimia   = $this->input->post('bahan_kimia');
		$air_bersih    = $this->input->post('air_bersih');
		$volume_akhir  = $this->input->post('volume_akhir');
		$kebutuhan     = $this->input->post('kebutuhan');
		$keterangan    = $this->input->post('keterangan');
		$tindakan      = $this->input->post('tindakan');
		$verifikasi    = $this->input->post('verifikasi');

		$old_data = $this->db->get_where('larutan', ['uuid' => $uuid])->row_array();

		$nama_bahan = [];

		for ($i = 0; $i < count($bahan); $i++) {
			$nama_bahan[] = [
				'bahan'       => $bahan[$i],
				'kadar'       => $kadar[$i],
				'bahan_kimia' => $bahan_kimia[$i],
				'air_bersih'  => $air_bersih[$i],
				'volume_akhir' => $volume_akhir[$i],
				'kebutuhan'   => $kebutuhan[$i],
				'keterangan'  => isset($keterangan[$i]) ? 'Sesuai' : '',
				'tindakan'    => $tindakan[$i],
				'verifikasi'  => $verifikasi[$i],
			];
		}

		$data = [
			'username'     => $username,
			'plant'        => $plant,
			'date'         => $date,
			'shift'        => $shift,
			'nama_bahan'   => json_encode($nama_bahan),
			'catatan'      => $catatan,
			'modified_at'  => date('Y-m-d H:i:s')
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('larutan', $data);

		if ($this->db->affected_rows() > 0) {
			$new_data = $this->db->get_where('larutan', ['uuid' => $uuid])->row_array();

			// Catat log activity
			$this->activity_logger->log_activity(
				'update',
				'larutan_logs',
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

		$this->db->update('larutan', $data, array('uuid' => $uuid));
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

		$this->db->update('larutan', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('larutan')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('larutan', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_bagian_by_uuid($uuid)
	{
		return $this->db->get_where('larutan', ['uuid' => $uuid])->result_array();
	}

	public function get_details_by_uuid($uuid)
	{
		return $this->db->get_where('sensori_detail', ['uuid_sensori' => $uuid])->result_array();
	}


	public function get_by_uuid_larutan($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('larutan');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_larutan_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, nama_produksi, shift, status_produksi, tgl_update_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('larutan');

		$data_sensori = $query->row();
		return $data_sensori;
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

		return $this->db->get('larutan')->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('larutan');
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
		$query = $this->db->get('larutan');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null, $shift = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, nama_produksi, shift, status_produksi, tgl_update_produksi');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('larutan');

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('larutan')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('larutan')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('larutan')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('larutan')
			->result();
	}


	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		$record = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('larutan')
			->row();

		if (!$record) {
			return false;
		}

		$bahan        = $this->input->post('bahan');
		$kadar        = $this->input->post('kadar');
		$bahan_kimia  = $this->input->post('bahan_kimia');
		$air_bersih   = $this->input->post('air_bersih');
		$volume_akhir = $this->input->post('volume_akhir');
		$kebutuhan    = $this->input->post('kebutuhan');
		$keterangan   = $this->input->post('keterangan');
		$tindakan     = $this->input->post('tindakan');
		$verifikasi   = $this->input->post('verifikasi');

		if (!is_array($bahan) || empty($bahan)) {
			return false;
		}

		$nama_bahan = [];

		for ($i = 0; $i < count($bahan); $i++) {
			$nama_bahan[] = [
				'bahan'        => $bahan[$i] ?? '',
				'kadar'        => $kadar[$i] ?? '',
				'bahan_kimia'  => $bahan_kimia[$i] ?? '',
				'air_bersih'   => $air_bersih[$i] ?? '',
				'volume_akhir' => $volume_akhir[$i] ?? '',
				'kebutuhan'    => $kebutuhan[$i] ?? '',
				'keterangan'   => isset($keterangan[$i]) ? 'Sesuai' : '',
				'tindakan'     => $tindakan[$i] ?? '',
				'verifikasi'   => $verifikasi[$i] ?? ''
			];
		}

		$data = [
			'username'    => $this->session->userdata('username'),
			'date'        => $this->input->post('date'),
			'shift'       => $this->input->post('shift'),
			'nama_bahan'  => json_encode($nama_bahan),
			'catatan'     => $this->input->post('catatan'),
			'modified_at' => date('Y-m-d H:i:s')
		];

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('larutan', $data);
	}
}
