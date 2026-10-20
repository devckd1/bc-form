<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Chiller_model extends CI_Model
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
				'field' => 'waktu',
				'label' => 'Time',
				'rules' => 'required'
			],
			[
				'field' => 'chiller_1',
				'label' => 'Chiller no.1',
				'rules' => 'required'
			],
			[
				'field' => 'chiller_2',
				'label' => 'Chiller no.2',
				'rules' => 'required'
			],
			[
				'field' => 'chiller_3',
				'label' => 'Chiller no.3',
				'rules' => 'required'
			],
			[
				'field' => 'chiller_4',
				'label' => 'Chiller no.4',
				'rules' => 'required'
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
		$username      = $this->session->userdata('username');
		$plant         = $this->session->userdata('plant');

		$date       = $this->input->post('date');
		$waktu      = $this->input->post('waktu');
		$chiller_1  = $this->input->post('chiller_1');
		$chiller_2  = $this->input->post('chiller_2');
		$chiller_3  = $this->input->post('chiller_3');
		$chiller_4  = $this->input->post('chiller_4');
		$catatan    = $this->input->post('catatan');

		/*
     * UUID DATA UTAMA
     */
		$uuid = Uuid::uuid4()->toString();

		/*
     * DATA UTAMA
     */
		$data_utama = [
			'uuid'              => $uuid,
			'is_audit'          => 0,
			'source_uuid'       => null,
			'username'          => $username,
			'plant'             => $plant,
			'date'              => $date,
			'waktu'             => $waktu,
			'chiller_1'         => $chiller_1,
			'chiller_2'         => $chiller_2,
			'chiller_3'         => $chiller_3,
			'chiller_4'         => $chiller_4,
			'catatan'           => $catatan,
			'nama_produksi'     => $nama_produksi,
			'status_produksi'   => '1',
			'catatan_produksi'  => '',
			'nama_spv'          => '',
			'status_spv'        => '0',
			'catatan_spv'       => ''
		];

		/*
     * UUID UNTUK DATA AUDIT
     */
		$audit_uuid = Uuid::uuid4()->toString();

		/*
     * DATA AUDIT
     * Awalnya merupakan COPY dari data utama.
     */
		$data_audit = [
			'uuid'              => $audit_uuid,
			'is_audit'          => 1,
			'source_uuid'       => $uuid,
			'username'          => $username,
			'plant'             => $plant,
			'date'              => $date,
			'waktu'             => $waktu,
			'chiller_1'         => $chiller_1,
			'chiller_2'         => $chiller_2,
			'chiller_3'         => $chiller_3,
			'chiller_4'         => $chiller_4,
			'catatan'           => $catatan,
			'nama_produksi'     => $nama_produksi,
			'status_produksi'   => '1',
			'catatan_produksi'  => '',
			'nama_spv'          => '',
			'status_spv'        => '0',
			'catatan_spv'       => ''
		];

		/*
     * SIMPAN KEDUANYA DALAM 1 TRANSACTION
     */
		$this->db->trans_start();

		$this->db->insert('chiller', $data_utama);
		$this->db->insert('chiller', $data_audit);

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
		$waktu = $this->input->post('waktu');
		$chiller_1 = $this->input->post('chiller_1');
		$chiller_2 = $this->input->post('chiller_2');
		$chiller_3 = $this->input->post('chiller_3');
		$chiller_4 = $this->input->post('chiller_4');
		$catatan = $this->input->post('catatan');

		$old_data = $this->db->get_where('chiller', ['uuid' => $uuid])->row_array();

		$data = array(
			'username' => $username,
			'date' => $date,
			'waktu' => $waktu,
			'chiller_1' => $chiller_1,
			'chiller_2' => $chiller_2,
			'chiller_3' => $chiller_3,
			'chiller_4' => $chiller_4,
			'catatan' => $catatan,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('chiller', $data, ['uuid' => $uuid]);

		$new_data = $this->db->get_where('chiller', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			$this->activity_logger->log_activity(
				'update',
				'chiller_logs',
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

		$this->db->update('chiller', $data, array('uuid' => $uuid));
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

		$this->db->update('chiller', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('chiller')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->get('chiller')
			->row();
	}

	public function get_by_uuid_chiller($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('chiller');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_chiller_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('chiller');

		$data_chiller = $query->row();
		return $data_chiller;
	}

	// public function get_chiller_by_plant()
	// {
	// 	$this->db->order_by('created_at', 'DESC');
	// 	$plant = $this->session->userdata('plant'); 
	// 	$type_user = $this->session->userdata('tipe_user');

	// 	if ($type_user == 0 || $type_user == 1) {
	// 		return $this->db->get('chiller')->result();
	// 	}
	// 	return $this->db->get_where('chiller', ['plant' => $plant])->result();
	// }

	public function get_chiller_by_plant($limit, $start)
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->select('
        uuid,
        date,
        waktu,
        chiller_1,
        chiller_2,
        chiller_3,
        chiller_4,
        status_spv,
        modified_at,
        tgl_update_spv
    ');

		$this->db->from('chiller');

		// Hanya data utama
		$this->db->where('is_audit', 0);

		if (!in_array($type_user, [9, 1])) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit, $start);

		return $this->db->get()->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('chiller');
	}

	public function get_by_date($tanggal, $plant = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('TIME(waktu)', 'ASC');

		$query = $this->db->get('chiller');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select('
        nama_spv,
        tgl_update_spv,
        username,
        date,
        nama_produksi,
        status_produksi,
        tgl_update_produksi,
        created_at
    ');

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		return $this->db->get('chiller')->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('date', 'DESC');
		$this->db->order_by('waktu', 'DESC');

		return $this->db->get('chiller')->result();
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

		$this->db->order_by('waktu', 'ASC');

		$query = $this->db->get('chiller');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 1);

		// User biasa hanya boleh melihat audit plant aktif
		if (!in_array($type_user, [9, 1])) {
			$this->db->where('plant', $plant);
		}

		return $this->db->get('chiller')->row();
	}

	public function update_audit($uuid)
	{
		$audit = $this->get_audit_by_uuid($uuid);

		if (!$audit) {
			return false;
		}

		$data = [
			'date'         => $this->input->post('date'),
			'waktu'        => $this->input->post('waktu'),
			'chiller_1'    => $this->input->post('chiller_1'),
			'chiller_2'    => $this->input->post('chiller_2'),
			'chiller_3'    => $this->input->post('chiller_3'),
			'chiller_4'    => $this->input->post('chiller_4'),
			'catatan'      => $this->input->post('catatan'),
			'modified_at'  => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('is_audit', 1)
			->where('plant', $this->session->userdata('plant'))
			->update('chiller', $data);

		return $this->db->affected_rows() > 0;
	}
}
