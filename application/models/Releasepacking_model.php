<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Releasepacking_model extends CI_Model
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
				'label' => 'Product',
				'rules' => 'required'
			],
			[
				'field' => 'kode_produksi',
				'label' => 'Product Code',
				'rules' => 'required'
			],
			[
				'field' => 'best_before',
				'label' => 'Best Before',
				'rules' => 'required'
			],
			[
				'field' => 'jumlah',
				'label' => 'Amount',
				'rules' => 'required'
			],
			[
				'field' => 'keterangan',
				'label' => 'Notes'
			]
		];
	}


	public function insert()
	{
		$uuid_qc = Uuid::uuid4()->toString();

		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$data = array(
			'uuid'          => $uuid_qc,
			'is_audit'      => 0,
			'source_uuid'   => null,
			'username'      => $username,
			'plant'         => $plant,
			'date'          => $this->input->post('date'),
			'nama_produk'   => $this->input->post('nama_produk'),
			'kode_produksi' => $this->input->post('kode_produksi'),
			'best_before'   => $this->input->post('best_before'),
			'jumlah'        => $this->input->post('jumlah'),
			'keterangan'    => $this->input->post('keterangan'),
			'status_spv'    => '0'
		);

		$this->db->trans_begin();

		// Simpan data QC utama
		$this->db->insert('release_packing', $data);

		// Jika insert QC berhasil, buat snapshot Audit
		if ($this->db->trans_status() !== false) {
			$data_audit = $data;

			$data_audit['uuid'] = Uuid::uuid4()->toString();
			$data_audit['is_audit'] = 1;
			$data_audit['source_uuid'] = $uuid_qc;

			$this->db->insert('release_packing', $data_audit);
		}

		// Batalkan keduanya jika salah satu insert gagal
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
		$nama_produk = $this->input->post('nama_produk');
		$kode_produksi = $this->input->post('kode_produksi');
		$best_before = $this->input->post('best_before');
		$jumlah = $this->input->post('jumlah');
		$keterangan = $this->input->post('keterangan');
		$old_data = $this->db->get_where('release_packing', ['uuid' => $uuid])->row_array();
		$data = array(
			'username' => $username,
			'date' => $date,
			'nama_produk' => $nama_produk,
			'kode_produksi' => $kode_produksi,
			'best_before' => $best_before,
			'jumlah' => $jumlah,
			'keterangan' => $keterangan,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('release_packing', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('release_packing', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus release_packing_logs
			$this->activity_logger->log_activity(
				'update',
				'release_packing_logs', // nama tabel log khusus release_packing
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

		$this->db->update('release_packing', $data, array('uuid' => $uuid));
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

		$this->db->update('release_packing', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('release_packing')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('release_packing', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_releasepacking($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('release_packing');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_releasepacking_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('release_packing');

		$data_release_packing = $query->row();
		return $data_release_packing;
	}


	public function get_data_by_plant($limit, $start)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->select('
            uuid,
            date,
            nama_produk,
            kode_produksi,
            best_before,
            jumlah,
            status_spv
        ')
			->from('release_packing')
			->where('plant', $plant)
			->where('is_audit', 0)
			->order_by('created_at', 'DESC')
			->limit($limit, $start)
			->get()
			->result();
	}

	public function get_data_verifikasi()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->select('uuid,date,nama_produk,kode_produksi,best_before,jumlah,status_spv,modified_at,tgl_update_spv')
			->from('release_packing')
			->where('plant', $plant)
			->order_by('created_at', 'DESC')
			->get()
			->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('release_packing');
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
		$query = $this->db->get('release_packing');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('release_packing');

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('release_packing')
			->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('release_packing')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('release_packing')
			->row();
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

		$this->db->order_by('date', 'ASC');

		$query = $this->db->get('release_packing');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		$data = array(
			'date'         => $this->input->post('date', true),
			'nama_produk'  => $this->input->post('nama_produk', true),
			'kode_produksi' => $this->input->post('kode_produksi', true),
			'best_before'  => $this->input->post('best_before', true),
			'jumlah'       => $this->input->post('jumlah', true),
			'keterangan'   => $this->input->post('keterangan', true),
		);

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('release_packing', $data);
	}
}
