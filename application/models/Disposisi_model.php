<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Disposisi_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('activity_logger');
	}


	public function rules()
	{
		return [
			[
				'field' => 'date',
				'label' => 'Date',
				'rules' => 'required'
			],
			[
				'field' => 'nomor',
				'label' => 'Number',
				'rules' => 'required'
			],
			[
				'field' => 'kepada',
				'label' => 'Dear',
				'rules' => 'required'
			],
			[
				'field' => 'disposisi',
				'label' => 'Disposition',
				'rules' => 'required'
			],
			[
				'field' => 'dasar_disposisi',
				'label' => 'Based Disposition'
			],
			[
				'field' => 'uraian_disposisi',
				'label' => 'Description'
			],
			[
				'field' => 'catatan',
				'label' => 'Notes'
			],
			[
				'field' => 'cc',
				'label' => 'CC'
			]
		];
	}


	public function insert()
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		// UUID record QC
		$uuid_qc = Uuid::uuid4()->toString();

		// Data dasar dari form
		$data = array(
			'uuid'             => $uuid_qc,
			'source_uuid'      => null,
			'is_audit'         => 0,
			'username'         => $username,
			'plant'            => $plant,
			'date'             => $this->input->post('date'),
			'nomor'            => $this->input->post('nomor'),
			'kepada'           => $this->input->post('kepada'),
			'disposisi'        => $this->input->post('disposisi'),
			'dasar_disposisi'  => $this->input->post('dasar_disposisi'),
			'uraian_disposisi' => $this->input->post('uraian_disposisi'),
			'catatan'          => $this->input->post('catatan'),
			'cc'               => $this->input->post('cc'),
			'status_produksi'  => '1',
			'nama_produksi'    => $nama_produksi,
			'status_spv'       => '0'
		);

		// Mulai transaksi
		$this->db->trans_begin();

		// 1. Simpan data QC aktual
		$this->db->insert('disposisi', $data);

		// 2. Buat snapshot Audit dari data yang sama
		if ($this->db->trans_status() !== false) {
			$data_audit = $data;

			$data_audit['uuid'] = Uuid::uuid4()->toString();
			$data_audit['source_uuid'] = $uuid_qc;
			$data_audit['is_audit'] = 1;

			$this->db->insert('disposisi', $data_audit);
		}

		// Pastikan kedua insert berhasil
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
		$nomor = $this->input->post('nomor');
		$kepada = $this->input->post('kepada');
		$disposisi = $this->input->post('disposisi');
		$dasar_disposisi = $this->input->post('dasar_disposisi');
		$uraian_disposisi = $this->input->post('uraian_disposisi');
		$catatan = $this->input->post('catatan');
		$cc = $this->input->post('cc');

		// ambil data lama sebelum update
		$old_data = $this->db->get_where('disposisi', ['uuid' => $uuid])->row_array();

		$data = array(
			'username' => $username,
			'date' => $date,
			'nomor' => $nomor,
			'kepada' => $kepada,
			'disposisi' => $disposisi,
			'dasar_disposisi' => $dasar_disposisi,
			'uraian_disposisi' => $uraian_disposisi,
			'catatan' => $catatan,
			'cc' => $cc,
			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('disposisi', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('disposisi', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus disposisi_logs
			$this->activity_logger->log_activity(
				'update',
				'disposisi_logs', // nama tabel log khusus disposisi
				$uuid,
				$old_data,
				$new_data
			);
			return true;
		}
		return false;
	}

	// public function update($uuid)
	// {
	// 	$username = $this->session->userdata('username');
	// 	$date = $this->input->post('date');
	// 	$nomor = $this->input->post('nomor');
	// 	$kepada = $this->input->post('kepada');
	// 	$disposisi = $this->input->post('disposisi');
	// 	$dasar_disposisi = $this->input->post('dasar_disposisi');
	// 	$uraian_disposisi = $this->input->post('uraian_disposisi');
	// 	$catatan = $this->input->post('catatan');
	// 	$cc = $this->input->post('cc');

	// 	$data = array(
	// 		'username' => $username,
	// 		'date' => $date,
	// 		'nomor' => $nomor,
	// 		'kepada' => $kepada,
	// 		'disposisi' => $disposisi,
	// 		'dasar_disposisi' => $dasar_disposisi,
	// 		'uraian_disposisi' => $uraian_disposisi,
	// 		'catatan' => $catatan,
	// 		'cc' => $cc,

	// 		'modified_at' => date("Y-m-d H:i:s") 
	// 	);

	// 	$this->db->update('disposisi', $data, array('uuid' => $uuid));
	// 	return($this->db->affected_rows() > 0) ? true :false;

	// }

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

		$this->db->update('disposisi', $data, array('uuid' => $uuid));
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

		$this->db->update('disposisi', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('disposisi')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('disposisi', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_disposisi($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('disposisi');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_disposisi_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, nomor, kepada, disposisi, dasar_disposisi, uraian_disposisi, catatan, cc');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('disposisi');

		$data_disposisi = $query->row();
		return $data_disposisi;
	}


	public function get_data_by_plant()
	{
		$this->db->where('is_audit', 0);

		$this->db->order_by('created_at', 'DESC');

		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		if ($type_user == 9 || $type_user == 1) {
			return $this->db->get('disposisi')->result();
		}

		$this->db->where('plant', $plant);

		return $this->db->get('disposisi')->result();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('disposisi')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('disposisi')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('disposisi')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('disposisi')
			->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('disposisi');
	}

	public function get_qc_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		return $this->db->get('disposisi')->row();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		// Validasi snapshot Audit berdasarkan UUID dan plant aktif
		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 1);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!$this->db->get('disposisi')->row()) {
			return false;
		}

		$data = [
			'date'             => $this->input->post('date', true),
			'nomor'            => $this->input->post('nomor', true),
			'kepada'           => $this->input->post('kepada', true),
			'disposisi'        => $this->input->post('disposisi', true),
			'dasar_disposisi'  => $this->input->post('dasar_disposisi', true),
			'uraian_disposisi' => $this->input->post('uraian_disposisi', true),
			'catatan'          => $this->input->post('catatan', true),
			'cc'               => $this->input->post('cc', true)
		];

		// Batasi update hanya pada snapshot Audit
		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 1);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		return $this->db->update('disposisi', $data);
	}
}
