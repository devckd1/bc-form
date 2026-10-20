<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Pengayakan_model extends CI_Model
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
			[
				'field' => 'nama_barang',
				'label' => 'Produk Name',
				'rules' => 'required'
			],
			[
				'field' => 'kode_produksi',
				'label' => 'Product Code',
				'rules' => 'required'
			],
			[
				'field' => 'expired_date',
				'label' => 'Expired Date',
				'rules' => 'required'
			],
			[
				'field' => 'jumlah_barang',
				'label' => 'Amount of Product',
				'rules' => 'required'
			],
			[
				'field' => 'kba_screenmess',
				'label' => 'Screen Mess Contamination'
				// 'rules' => 'required'
			],
			[
				'field' => 'kba_kerikil',
				'label' => 'Gravel Contamination'
				// 'rules' => 'required'
			],
			[
				'field' => 'kba_benang',
				'label' => 'Thread Contamination'
				// 'rules' => 'required'
			],
			[
				'field' => 'kondisi',
				'label' => 'Screen Condition',
			],
			[
				'field' => 'catatan',
				'label' => 'Notes',
			]

		];
	}

	public function insert()
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$uuid       = Uuid::uuid4()->toString();
		$audit_uuid = Uuid::uuid4()->toString();

		$username = $this->session->userdata('username');
		$plant    = $this->session->userdata('plant');

		$date          = $this->input->post('date');
		$shift         = $this->input->post('shift');
		$nama_barang   = $this->input->post('nama_barang');
		$kode_produksi = $this->input->post('kode_produksi');
		$expired_date  = $this->input->post('expired_date');
		$jumlah_barang = $this->input->post('jumlah_barang');

		// DATA ASLI
		$kba_screenmess = $this->input->post('kba_screenmess');
		$kba_kerikil    = $this->input->post('kba_kerikil');
		$kba_benang     = $this->input->post('kba_benang');
		$kondisi        = $this->input->post('kondisi');
		$catatan        = $this->input->post('catatan');

		$status_produksi = "1";
		$status_spv      = "0";

		/*
     * =========================================================
     * DATA UTAMA / QC
     * Tetap menyimpan data asli dari input user
     * =========================================================
     */
		$data = [
			'uuid'             => $uuid,
			'username'         => $username,
			'plant'            => $plant,
			'date'             => $date,
			'shift'            => $shift,
			'nama_barang'      => $nama_barang,
			'kode_produksi'    => $kode_produksi,
			'expired_date'     => $expired_date,
			'jumlah_barang'    => $jumlah_barang,

			'kba_screenmess'   => $kba_screenmess,
			'kba_kerikil'      => $kba_kerikil,
			'kba_benang'       => $kba_benang,

			'kondisi'          => $kondisi,
			'catatan'          => $catatan,

			'status_spv'       => $status_spv,
			'status_produksi'  => $status_produksi,
			'nama_produksi'    => $nama_produksi,

			'is_audit'         => 0,
			'source_uuid'      => null
		];

		/*
     * =========================================================
     * DATA AUDIT
     *
     * Tidak mengikuti hasil kontaminasi asli.
     *
     * Audit dianggap tidak ada kontaminasi:
     * Screen Mess = 0
     * Kerikil     = -
     * Benang      = -
     * =========================================================
     */
		$auditData = [
			'uuid'             => $audit_uuid,
			'username'         => $username,
			'plant'            => $plant,
			'date'             => $date,
			'shift'            => $shift,
			'nama_barang'      => $nama_barang,
			'kode_produksi'    => $kode_produksi,
			'expired_date'     => $expired_date,
			'jumlah_barang'    => $jumlah_barang,

			'kba_screenmess'   => '0',
			'kba_kerikil'      => '-',
			'kba_benang'       => '-',

			'kondisi'          => '-',
			'catatan'          => '',

			'status_spv'       => '0',
			'status_produksi'  => $status_produksi,
			'nama_produksi'    => $nama_produksi,

			'is_audit'         => 1,
			'source_uuid'      => $uuid
		];

		/*
     * =========================================================
     * SIMPAN QC + AUDIT DALAM SATU TRANSAKSI
     * =========================================================
     */
		$this->db->trans_start();

		$this->db->insert('pengayakan', $data);

		$this->db->insert('pengayakan', $auditData);

		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			return false;
		}

		return true;
	}

	public function update($uuid)
	{
		$plant = $this->session->userdata('plant');
		$username = $this->session->userdata('username');

		$old_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->get('pengayakan')
			->row_array();

		if (!$old_data) {
			return false;
		}

		$data = [
			'username'        => $username,
			'date'            => $this->input->post('date'),
			'shift'           => $this->input->post('shift'),
			'nama_barang'     => $this->input->post('nama_barang'),
			'kode_produksi'   => $this->input->post('kode_produksi'),
			'expired_date'    => $this->input->post('expired_date'),
			'jumlah_barang'   => $this->input->post('jumlah_barang'),
			'kba_screenmess'  => $this->input->post('kba_screenmess'),
			'kba_kerikil'     => $this->input->post('kba_kerikil'),
			'kba_benang'      => $this->input->post('kba_benang'),
			'kondisi'         => $this->input->post('kondisi'),
			'catatan'         => $this->input->post('catatan'),
			'modified_at'     => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->update('pengayakan', $data);

		$new_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->get('pengayakan')
			->row_array();

		$this->activity_logger->log_activity(
			'update',
			'pengayakan_logs',
			$uuid,
			$old_data,
			$new_data
		);

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
		$plant = $this->session->userdata('plant');

		$nama_spv    = $this->session->userdata('username');
		$status_spv  = $this->input->post('status_spv');
		$catatan_spv = $this->input->post('catatan_spv');

		$data = [
			'nama_spv'    => $nama_spv,
			'status_spv'  => $status_spv,
			'catatan_spv' => $catatan_spv,
			'tgl_update'  => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->update('pengayakan', $data);

		return ($this->db->affected_rows() > 0);
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
		$plant = $this->session->userdata('plant');

		$nama_produksi     = $this->session->userdata('username');
		$status_produksi   = $this->input->post('status_produksi');
		$catatan_produksi  = $this->input->post('catatan_produksi');

		$data = [
			'nama_produksi'     => $nama_produksi,
			'status_produksi'   => $status_produksi,
			'catatan_produksi'  => $catatan_produksi,
			'tgl_update_prod'   => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->update('pengayakan', $data);

		return ($this->db->affected_rows() > 0);
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('pengayakan')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->get('pengayakan')
			->row();
	}

	public function get_by_uuid_pengayakan($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('pengayakan');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_pengayakan_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update, username, nama_produksi, tgl_update_prod, status_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('pengayakan');

		$data_pengayakan = $query->row();
		return $data_pengayakan;
	}

	public function get_data_by_plant()
	{
		$this->db->where('is_audit', 0);
		$this->db->order_by('created_at', 'DESC');

		$plant = $this->session->userdata('plant');
		$type_user = (int) $this->session->userdata('tipe_user');

		if ($type_user == 9 || $type_user == 1) {
			return $this->db->get('pengayakan')->result();
		}

		return $this->db
			->where('plant', $plant)
			->get('pengayakan')
			->result();
	}

	public function delete_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0);

		return $this->db->delete('pengayakan');
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

		$this->db->order_by('date', 'ASC');

		$query = $this->db->get('pengayakan');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select(
			'nama_spv,
        tgl_update,
        username,
        nama_produksi,
        tgl_update_prod,
        status_produksi'
		);

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update', 'DESC');
		$this->db->limit(1);

		return $this->db
			->get('pengayakan')
			->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('pengayakan')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('pengayakan')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('pengayakan')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('pengayakan')
			->result();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');
		$username = $this->session->userdata('username');

		$old_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('pengayakan')
			->row_array();

		if (!$old_data) {
			return false;
		}

		$data = [
			'username'        => $username,
			'date'            => $this->input->post('date'),
			'shift'           => $this->input->post('shift'),
			'nama_barang'     => $this->input->post('nama_barang'),
			'kode_produksi'   => $this->input->post('kode_produksi'),
			'expired_date'    => $this->input->post('expired_date'),
			'jumlah_barang'   => $this->input->post('jumlah_barang'),
			'kba_screenmess'  => $this->input->post('kba_screenmess'),
			'kba_kerikil'     => $this->input->post('kba_kerikil'),
			'kba_benang'      => $this->input->post('kba_benang'),
			'kondisi'         => $this->input->post('kondisi'),
			'catatan'         => $this->input->post('catatan'),
			'modified_at'     => date('Y-m-d H:i:s')
		];

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('pengayakan', $data);

		$new_data = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('pengayakan')
			->row_array();

		$this->activity_logger->log_activity(
			'update',
			'pengayakan_logs',
			$uuid,
			$old_data,
			$new_data
		);

		return true;
	}
}
