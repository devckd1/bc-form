<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Reagen_model extends CI_Model
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
				'field' => 'nama_larutan',
				'label' => 'Name of Solvent',
				'rules' => 'required'
			],
			[
				'field' => 'no_lot',
				'label' => 'Lot Number',
				'rules' => 'required'
			],
			[
				'field' => 'best_before',
				'label' => 'Best Before',
				'rules' => 'required'
			],
			[
				'field' => 'tgl_buka_botol',
				'label' => 'Open Bottle Date',
				'rules' => 'required'
			],
			[
				'field' => 'volume_penggunaan',
				'label' => 'Volume of Use',
				'rules' => 'required'
			],
			[
				'field' => 'volume_akhir',
				'label' => 'Last Volume',
				'rules' => 'required'
			],
			[
				'field' => 'catatan',
				'label' => 'Notes',
			]

		];
	}

	public function insert()
	{
		$this->load->library('form_validation');

		$plant = $this->session->userdata('plant');
		$username = $this->session->userdata('username');

		$uuid_qc = Ramsey\Uuid\Uuid::uuid4()->toString();
		$uuid_audit = Ramsey\Uuid\Uuid::uuid4()->toString();

		$data = [
			'uuid'              => $uuid_qc,
			'username'          => $username,
			'plant'             => $plant,
			'date'              => $this->input->post('date', true),
			'nama_larutan'      => $this->input->post('nama_larutan', true),
			'no_lot'             => $this->input->post('no_lot', true),
			'best_before'       => $this->input->post('best_before', true),
			'tgl_buka_botol'    => $this->input->post('tgl_buka_botol', true),
			'volume_penggunaan' => $this->input->post('volume_penggunaan', true),
			'volume_akhir'      => $this->input->post('volume_akhir', true),
			'catatan'           => $this->input->post('catatan', true),
			'status_spv'        => 0,
			'is_audit'          => 0,
			'source_uuid'       => null
		];

		$this->db->trans_begin();

		// Simpan data QC
		$this->db->insert('reagen', $data);

		if ($this->db->affected_rows() <= 0) {
			$this->db->trans_rollback();
			return false;
		}

		// Salin data QC menjadi snapshot Audit
		$data_audit = $data;
		$data_audit['uuid'] = $uuid_audit;
		$data_audit['is_audit'] = 1;
		$data_audit['source_uuid'] = $uuid_qc;

		$this->db->insert('reagen', $data_audit);

		if ($this->db->affected_rows() <= 0) {
			$this->db->trans_rollback();
			return false;
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

		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$nama_larutan = $this->input->post('nama_larutan');
		$no_lot = $this->input->post('no_lot');
		$best_before = $this->input->post('best_before');
		$tgl_buka_botol = $this->input->post('tgl_buka_botol');
		$volume_penggunaan = $this->input->post('volume_penggunaan');
		$volume_akhir = $this->input->post('volume_akhir');
		$catatan = $this->input->post('catatan');
		$old_data = $this->db->get_where('reagen', ['uuid' => $uuid])->row_array();

		$data = array(
			'username' => $username,
			'date' => $date,
			'nama_larutan' => $nama_larutan,
			'no_lot' => $no_lot,
			'best_before' => $best_before,
			'tgl_buka_botol' => $tgl_buka_botol,
			'volume_penggunaan' => $volume_penggunaan,
			'volume_akhir' => $volume_akhir,
			'catatan' => $catatan,

			'modified_at' => date("Y-m-d H:i:s")
		);


		$this->db->update('reagen', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('reagen', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus reagen_logs
			$this->activity_logger->log_activity(
				'update',
				'reagen_logs', // nama tabel log khusus reagen
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

		$this->db->update('reagen', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('reagen')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('reagen', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_reagen($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('reagen');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_reagen_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, date');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('reagen');

		$data_reagen = $query->row();
		return $data_reagen;
	}

	// public function get_Reagen_by_month($start, $end)
	// {
	// 	$this->db->where('date >=', $start);
	// 	$this->db->where('date <=', $end);
	// 	$this->db->order_by('date', 'ASC'); 
	// 	return $this->db->get('reagen')->result();
	// }

	// public function get_one_verified_by_month($start, $end)
	// {
	// 	$this->db->where('date >=', $start);
	// 	$this->db->where('date <=', $end);
	// 	$this->db->where('status_spv', 1);
	// 	$this->db->limit(1);
	// 	return $this->db->get('reagen')->row();
	// }

	public function get_by_month($start_date, $end_date, $plant = null)
	{
		// $this->db->select('date, standar, hasil_pemeriksaan, keterangan, tindakan, verifikasi, username, catatan'); 

		$this->db->where('date >=', $start_date);
		$this->db->where('date <=', $end_date);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('date', 'ASC');
		$query = $this->db->get('reagen');

		log_message('debug', 'Query get_by_month: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_month($start_date, $end_date, $plant = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date');
		$this->db->where('date >=', $start_date);
		$this->db->where('date <=', $end_date);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('reagen');

		return $query->row();
	}

	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->where('is_audit', 0);

		// Superadmin dan manager dapat melihat semua plant
		if (in_array($type_user, ['9', '1', 9, 1])) {
			$this->db->order_by('created_at', 'DESC');
			return $this->db->get('reagen')->result();
		}

		// User biasa hanya melihat plant aktif
		$this->db->where('plant', $plant);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('reagen')->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('reagen');
	}

	public function get_all_no_lot()
	{
		$this->db->select('no_lot');
		$this->db->distinct();
		$query = $this->db->get('reagen');
		return $query->result_array();
	}

	public function get_bulan_tahun_by_plant()
	{
		$this->db->select('MONTH(date) as bulan, YEAR(date) as tahun');
		$this->db->from('reagen');
		$this->db->where('is_audit', 0);

		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		if (!in_array($type_user, [9, 1, '9', '1'])) {
			$this->db->where('plant', $plant);
		}

		$this->db->group_by(['YEAR(date)', 'MONTH(date)']);
		$this->db->order_by('YEAR(date)', 'ASC');
		$this->db->order_by('MONTH(date)', 'ASC');

		return $this->db->get()->result();
	}

	public function detail_bulan($bulan, $tahun)
	{
		$data['reagen'] = $this->db
			->where('MONTH(date)', $bulan)
			->where('YEAR(date)', $tahun)
			->get('reagen')
			->result();

		$this->load->view('form/reagen/detail_bulan', $data);
	}

	public function get_by_bulan_tahun($bulan, $tahun)
	{
		$this->db->from('reagen');
		$this->db->where('MONTH(date)', $bulan);
		$this->db->where('YEAR(date)', $tahun);
		$this->db->where('plant', $this->session->userdata('plant'));
		$this->db->order_by('date', 'ASC');

		return $this->db->get()->result();
	}

	public function verifikasi_bulan($bulan, $tahun)
	{
		$data = ['status_spv' => 1, 'tgl_update_spv' => date('Y-m-d H:i:s')];
		$this->db->where('MONTH(date)', $bulan);
		$this->db->where('YEAR(date)', $tahun);
		$this->db->where('plant', $this->session->userdata('plant'));
		return $this->db->update('reagen', $data);
	}

	public function get_bulan_tahun_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->select('MONTH(date) as bulan, YEAR(date) as tahun');
		$this->db->from('reagen');
		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->group_by(['YEAR(date)', 'MONTH(date)']);
		$this->db->order_by('YEAR(date)', 'ASC');
		$this->db->order_by('MONTH(date)', 'ASC');

		return $this->db->get()->result();
	}

	public function get_audit_by_bulan_tahun($bulan, $tahun)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->from('reagen')
			->where('plant', $plant)
			->where('is_audit', 1)
			->where('MONTH(date)', $bulan)
			->where('YEAR(date)', $tahun)
			->order_by('date', 'ASC')
			->get()
			->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('reagen')
			->row();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		// Pastikan UUID merupakan data Audit pada plant aktif.
		$audit = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('reagen')
			->row();

		if (!$audit) {
			return false;
		}

		$data = [
			'date'              => $this->input->post('date', true),
			'nama_larutan'      => $this->input->post('nama_larutan', true),
			'no_lot'            => $this->input->post('no_lot', true),
			'best_before'       => $this->input->post('best_before', true),
			'tgl_buka_botol'    => $this->input->post('tgl_buka_botol', true),
			'volume_penggunaan' => $this->input->post('volume_penggunaan', true),
			'volume_akhir'      => $this->input->post('volume_akhir', true),
			'catatan'            => $this->input->post('catatan', true),
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('reagen', $data);

		return $this->db->affected_rows() >= 0
			&& $this->db->trans_status() !== false;
	}
}
