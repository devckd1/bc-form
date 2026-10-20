<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Loading_model extends CI_Model
{

	protected $table = 'loading';

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
				'field' => 'no_pol',
				'label' => 'Police Number',
				'rules' => 'required'
			],
			[
				'field' => 'start_loading',
				'label' => 'Start Loading',
				'rules' => 'required'
			],
			[
				'field' => 'finish_loading',
				'label' => 'Finish Loading',
				'rules' => 'required'
			],
			[
				'field' => 'nama_supir',
				'label' => 'Driver Name',
				'rules' => 'required'
			],
			[
				'field' => 'ekspedisi',
				'label' => 'Expedition'
			],
			[
				'field' => 'Tujuan',
				'label' => 'Destination'
			],
			[
				'field' => 'no_segel',
				'label' => 'Seal Number'
			],
		];
	}

	public function insert()
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$uuid_qc = Uuid::uuid4()->toString();
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$kondisi_mobil_list = [
			'bersih' => 'Bersih',
			'bocor' => 'Bocor',
			'bebas_dari_hama' => 'Bebas dari Hama',
			'tidak_berdebu' => 'Tidak Berdebu',
			'tidak_ada_sampah' => 'Tidak ada Sampah',
			'kering' => 'Kering',
			'basah' => 'Basah',
			'tidak_berbau' => 'Tidak Berbau',
			'tidak_ada_produk_non_halal' => 'Tidak ada produk Non Halal',
			'tidak_ada_aktivitas_binatang' => 'Tidak ada Aktivitas Binatang'
		];

		$kondisi_mobil_post = $this->input->post('keterangan_kondisi');
		$kondisi_mobil = [];

		foreach ($kondisi_mobil_list as $key => $label) {
			$kondisi_mobil[$key] = $kondisi_mobil_post[$key] ?? '';
		}

		$nama_produk     = $this->input->post('nama_produk');
		$kondisi_produk  = $this->input->post('kondisi_produk');
		$kondisi_kemasan = $this->input->post('kondisi_kemasan');
		$kode_produksi   = $this->input->post('kode_produksi');
		$expired         = $this->input->post('expired');
		$keterangan      = $this->input->post('keterangan');

		$loading = [];

		if (is_array($nama_produk)) {
			for ($i = 0; $i < count($nama_produk); $i++) {
				$loading[] = [
					'nama_produk'     => $nama_produk[$i],
					'kondisi_produk'  => $kondisi_produk[$i] ?? '',
					'kondisi_kemasan' => $kondisi_kemasan[$i] ?? '',
					'kode_produksi'   => $kode_produksi[$i] ?? '',
					'expired'         => $expired[$i] ?? '',
					'keterangan'      => $keterangan[$i] ?? ''
				];
			}
		}

		$data = [
			'uuid'           => $uuid_qc,
			'is_audit'       => 0,
			'source_uuid'    => null,
			'username'       => $username,
			'plant'          => $plant,
			'date'           => $this->input->post('date'),
			'shift'          => $this->input->post('shift'),
			'no_pol'         => $this->input->post('no_pol'),
			'start_loading'  => $this->input->post('start_loading'),
			'finish_loading' => $this->input->post('finish_loading'),
			'nama_supir'     => $this->input->post('nama_supir'),
			'ekspedisi'      => $this->input->post('ekspedisi'),
			'tujuan'         => $this->input->post('tujuan'),
			'no_segel'       => $this->input->post('no_segel'),
			'kondisi_mobil'  => json_encode($kondisi_mobil, JSON_UNESCAPED_UNICODE),
			'loading'        => json_encode($loading, JSON_UNESCAPED_UNICODE),
			'status_spv'     => '0',
			'nama_wh'        => $nama_produksi,
			'status_wh'      => '1'
		];

		$this->db->trans_begin();

		// Simpan record QC
		$this->db->insert($this->table, $data);

		// Buat snapshot Audit jika insert QC berhasil
		if ($this->db->trans_status() !== false) {
			$data_audit = $data;
			$data_audit['uuid'] = Uuid::uuid4()->toString();
			$data_audit['is_audit'] = 1;
			$data_audit['source_uuid'] = $uuid_qc;

			$this->db->insert($this->table, $data_audit);
		}

		// Rollback jika salah satu insert gagal
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
		$old_data = $this->db->get_where('loading', ['uuid' => $uuid])->row_array();

		// Gunakan key slug yang sama seperti insert
		$kondisi_mobil_list = [
			'bersih' => 'Bersih',
			'bocor' => 'Bocor',
			'bebas_dari_hama' => 'Bebas dari Hama',
			'tidak_berdebu' => 'Tidak Berdebu',
			'tidak_ada_sampah' => 'Tidak ada Sampah',
			'kering' => 'Kering',
			'basah' => 'Basah',
			'tidak_berbau' => 'Tidak Berbau',
			'tidak_ada_produk_non_halal' => 'Tidak ada produk Non Halal',
			'tidak_ada_aktivitas_binatang' => 'Tidak ada Aktivitas Binatang'
		];

		// Ambil data dari form
		$kondisi_mobil_post = $this->input->post('keterangan_kondisi');
		$kondisi_mobil = [];
		foreach ($kondisi_mobil_list as $key => $label) {
			$kondisi_mobil[$key] = $kondisi_mobil_post[$key] ?? '';
		}

		// Loading produk
		$nama_produk     = $this->input->post('nama_produk');
		$kondisi_produk  = $this->input->post('kondisi_produk');
		$kondisi_kemasan = $this->input->post('kondisi_kemasan');
		$kode_produksi   = $this->input->post('kode_produksi');
		$expired         = $this->input->post('expired');
		$keterangan      = $this->input->post('keterangan');

		$loading = [];
		if (is_array($nama_produk)) {
			for ($i = 0; $i < count($nama_produk); $i++) {
				$loading[] = [
					'nama_produk'     => $nama_produk[$i],
					'kondisi_produk'  => $kondisi_produk[$i] ?? '',
					'kondisi_kemasan' => $kondisi_kemasan[$i] ?? '',
					'kode_produksi'   => $kode_produksi[$i] ?? '',
					'expired'         => $expired[$i] ?? '',
					'keterangan'      => $keterangan[$i] ?? '',
				];
			}
		}

		$data = [
			'username'       => $username,
			'date'           => $this->input->post('date'),
			'shift'          => $this->input->post('shift'),
			'no_pol'         => $this->input->post('no_pol'),
			'start_loading'  => $this->input->post('start_loading'),
			'finish_loading' => $this->input->post('finish_loading'),
			'nama_supir'     => $this->input->post('nama_supir'),
			'ekspedisi'      => $this->input->post('ekspedisi'),
			'tujuan'         => $this->input->post('tujuan'),
			'no_segel'       => $this->input->post('no_segel'),
			'kondisi_mobil'  => json_encode($kondisi_mobil, JSON_UNESCAPED_UNICODE),
			'loading'        => json_encode($loading, JSON_UNESCAPED_UNICODE)
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('loading', $data);

		if ($this->db->affected_rows() > 0) {
			$new_data = $this->db->get_where('loading', ['uuid' => $uuid])->row_array();
			$this->activity_logger->log_activity('update', 'loading_logs', $uuid, $old_data, $new_data);
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

		$this->db->update('loading', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function rules_diketahui()
	{
		return [
			[
				'field' => 'status_wh',
				'label' => 'Status',
				'rules' => 'required'
			],
			[
				'field' => 'catatan_wh',
				'label' => 'Notes'
			]

		];
	}

	public function diketahui_update($uuid)
	{

		$nama_wh = $this->session->userdata('username');
		$status_wh = $this->input->post('status_wh');
		$catatan_wh = $this->input->post('catatan_wh');

		$data = array(
			'nama_wh' => $nama_wh,
			'status_wh' => $status_wh,
			'catatan_wh' => $catatan_wh,
			'tgl_update_wh' => date("Y-m-d H:i:s")
		);

		$this->db->update('loading', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('loading')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('loading', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_bagian_by_uuid($uuid)
	{
		return $this->db->get_where('loading', ['uuid' => $uuid])->result_array();
	}

	public function get_details_by_uuid($uuid)
	{
		return $this->db->get_where('loading_detail', ['uuid_loading' => $uuid])->result_array();
	}


	public function get_by_uuid_loading($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('loading');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_loading_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, nama_wh, shift, no_pol, start_loading, finish_loading, nama_supir, ekspedisi, tujuan, no_segel, status_wh, tgl_update_wh, created_at');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('loading');

		$data_loading = $query->row();
		return $data_loading;
	}

	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->where('is_audit', 0);
		$this->db->order_by('created_at', 'DESC');

		if ($type_user == 9 || $type_user == 1) {
			return $this->db->get($this->table)->result();
		}

		return $this->db
			->where('plant', $plant)
			->get($this->table)
			->result();
	}

	public function get_latest_loading()
	{
		$user_plant = $this->session->userdata('plant');

		$this->db->select('date, start_loading, finish_loading, tujuan');
		$this->db->from('loading');
		$this->db->where('plant', $user_plant);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit(10);

		$query = $this->db->get();
		return $query->result_array();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('loading');
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('loading')
			->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('loading')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('loading')
			->row();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		// Pastikan record yang akan diedit adalah snapshot Audit.
		$audit = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('loading')
			->row();

		if (!$audit) {
			return false;
		}

		$kondisiMobil = $this->input->post('keterangan_kondisi');

		if (!is_array($kondisiMobil)) {
			$kondisiMobil = [];
		}

		$kondisiMobil = array_intersect_key(
			$kondisiMobil,
			array_flip([
				'bersih',
				'bocor',
				'bebas_dari_hama',
				'tidak_berdebu',
				'tidak_ada_sampah',
				'kering',
				'basah',
				'tidak_berbau',
				'tidak_ada_produk_non_halal',
				'tidak_ada_aktivitas_binatang'
			])
		);

		$loadingData = [];

		$namaProduk     = $this->input->post('nama_produk');
		$kondisiProduk  = $this->input->post('kondisi_produk');
		$kondisiKemasan = $this->input->post('kondisi_kemasan');
		$kodeProduksi   = $this->input->post('kode_produksi');
		$expired        = $this->input->post('expired');
		$keterangan     = $this->input->post('keterangan');

		$namaProduk     = is_array($namaProduk) ? $namaProduk : [];
		$kondisiProduk  = is_array($kondisiProduk) ? $kondisiProduk : [];
		$kondisiKemasan = is_array($kondisiKemasan) ? $kondisiKemasan : [];
		$kodeProduksi   = is_array($kodeProduksi) ? $kodeProduksi : [];
		$expired        = is_array($expired) ? $expired : [];
		$keterangan     = is_array($keterangan) ? $keterangan : [];

		$jumlahProduk = max(
			count($namaProduk),
			count($kondisiProduk),
			count($kondisiKemasan),
			count($kodeProduksi),
			count($expired),
			count($keterangan)
		);

		for ($i = 0; $i < $jumlahProduk; $i++) {
			$loadingData[] = [
				'nama_produk'     => trim($namaProduk[$i] ?? ''),
				'kondisi_produk'  => trim($kondisiProduk[$i] ?? ''),
				'kondisi_kemasan' => trim($kondisiKemasan[$i] ?? ''),
				'kode_produksi'   => trim($kodeProduksi[$i] ?? ''),
				'expired'         => trim($expired[$i] ?? ''),
				'keterangan'      => trim($keterangan[$i] ?? '')
			];
		}

		$data = [
			'date'           => $this->input->post('date', true),
			'shift'          => $this->input->post('shift', true),
			'no_pol'         => $this->input->post('no_pol', true),
			'start_loading'  => $this->input->post('start_loading', true),
			'finish_loading' => $this->input->post('finish_loading', true),
			'nama_supir'     => $this->input->post('nama_supir', true),
			'ekspedisi'      => $this->input->post('ekspedisi', true),
			'tujuan'         => $this->input->post('tujuan', true),
			'no_segel'       => $this->input->post('no_segel', true),
			'kondisi_mobil'  => json_encode($kondisiMobil),
			'loading'        => json_encode($loadingData)
		];

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('loading', $data);
	}
}
