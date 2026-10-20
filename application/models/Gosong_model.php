<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Gosong_model extends CI_Model
{

	public function rules()
	{
		return [
			['field' => 'date', 'label' => 'Tanggal', 'rules' => 'required'],
			['field' => 'shift_main', 'label' => 'Shift', 'rules' => 'required'],
			['field' => 'shift_group', 'label' => 'Grup Shift', 'rules' => 'required'],
			['field' => 'total_berat', 'label' => 'Total Barang', 'rules' => 'required|numeric']
		];
	}


	public function insert()
	{
		$username = $this->session->userdata('username');
		$nama_produksi = $this->session->userdata('produksi_data')['nama_produksi'] ?? '';
		$plant = $this->session->userdata('plant');

		$date = $this->input->post('date');
		$shift_main = $this->input->post('shift_main');
		$shift_group = $this->input->post('shift_group');
		$shift = $shift_main . $shift_group;

		$total_berat = number_format(
			(float) $this->input->post('total_berat'),
			3,
			'.',
			''
		);

		$uuid = Uuid::uuid4()->toString();
		$now = date('Y-m-d H:i:s');

		$data = [
			'uuid' => $uuid,
			'username' => $username,
			'plant' => $plant,
			'date' => $date,
			'shift' => $shift,
			'total_berat' => $total_berat,
			'status_spv' => '0',
			'status_produksi' => '1',
			'nama_produksi' => $nama_produksi,
			'created_at' => $now,
			'modified_at' => $now,
			'is_audit' => 0,
			'source_uuid' => null
		];

		$this->db->trans_begin();

		// Simpan data QC
		$this->db->insert('roti_gosong', $data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		// Buat snapshot Audit dari data QC
		$audit_data = $data;
		$audit_data['uuid'] = Uuid::uuid4()->toString();
		$audit_data['is_audit'] = 1;
		$audit_data['source_uuid'] = $uuid;

		$this->db->insert('roti_gosong', $audit_data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}


	public function update($uuid)
	{
		// Ambil data dari session
		$username = $this->session->userdata('username');

		// Ambil data dari form
		$date = $this->input->post('date');
		$shift_main = $this->input->post('shift_main');
		$shift_group = $this->input->post('shift_group');
		$shift = $shift_main . $shift_group;

		// Simpan total_berat sebagai float 3 digit di belakang koma
		$total_berat = number_format((float)$this->input->post('total_berat'), 3, '.', '');

		$old_data = $this->db->get_where('roti_gosong', ['uuid' => $uuid])->row_array();

		$data = [
			'username' => $username,
			'date' => $date,
			'shift' => $shift,
			'total_berat' => $total_berat,
			'modified_at' => date("Y-m-d H:i:s")
		];

		$this->db->update('roti_gosong', $data, ['uuid' => $uuid]);

		$new_data = $this->db->get_where('roti_gosong', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			$this->activity_logger->log_activity(
				'update',
				'roti_gosong_logs',
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

		$this->db->update('roti_gosong', $data, array('uuid' => $uuid));
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

		$this->db->update('roti_gosong', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('roti_gosong')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('roti_gosong', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_roti_gosong($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('roti_gosong');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_roti_gosong_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, nama_produksi, tgl_update_produksi, status_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('roti_gosong');

		$data_roti_gosong = $query->row();
		return $data_roti_gosong;
	}


	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->where('is_audit', 0);
		$this->db->order_by('created_at', 'DESC');

		if ($type_user == 9 || $type_user == 1) {
			return $this->db->get('roti_gosong')->result();
		}

		return $this->db
			->where('plant', $plant)
			->get('roti_gosong')
			->result();
	}


	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('roti_gosong');
	}

	public function get_by_date($tanggal, $plant = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('date', 'ASC');
		$query = $this->db->get('roti_gosong');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, nama_produksi, tgl_update_produksi, status_produksi, created_at');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('roti_gosong');

		return $query->row();
	}

	public function get_by_month($tahun, $bulan, $plant)
	{
		return $this->db
			->where('YEAR(date)', $tahun)
			->where('MONTH(date)', $bulan)
			->where('plant', $plant)
			->order_by('date', 'ASC')
			->get('roti_gosong')
			->result();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('roti_gosong')
			->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('roti_gosong')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('roti_gosong')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant = null)
	{
		if (empty($tanggal)) {
			return [];
		}

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 1);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('roti_gosong')->result();
	}


	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');
		$username = $this->session->userdata('username');

		$old_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('roti_gosong')
			->row_array();

		if (!$old_data) {
			return false;
		}

		$date = $this->input->post('date');
		$shift_main = $this->input->post('shift_main');
		$shift_group = $this->input->post('shift_group');
		$shift = $shift_main . $shift_group;

		$total_berat = number_format(
			(float) $this->input->post('total_berat'),
			3,
			'.',
			''
		);

		$data = [
			'username' => $username,
			'date' => $date,
			'shift' => $shift,
			'total_berat' => $total_berat,
			'modified_at' => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1);

		if (!$this->db->update('roti_gosong', $data)) {
			return false;
		}

		$new_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('roti_gosong')
			->row_array();

		if (isset($this->activity_logger)) {
			$this->activity_logger->log_activity(
				'update',
				'roti_gosong_logs',
				$uuid,
				$old_data,
				$new_data
			);
		}

		return true;
	}
}
