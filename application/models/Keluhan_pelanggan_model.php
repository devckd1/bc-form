
<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ramsey\Uuid\Uuid;

class Keluhan_pelanggan_model extends CI_Model
{
	protected $table = 'keluhan_pelanggan';
	protected $table_produk = 'produk';

	public function rules()
	{
		return [
			[
				'field' => 'tanggal_keluhan',
				'label' => 'Tanggal Keluhan Pelanggan',
				'rules' => 'required'
			],
			[
				'field' => 'produk_uuid',
				'label' => 'Nama Produk',
				'rules' => 'required'
			],
			[
				'field' => 'kode_produksi',
				'label' => 'Kode Produksi',
				'rules' => 'required|exact_length[10]'
			],
			[
				'field' => 'pelanggan',
				'label' => 'Pelanggan',
				'rules' => 'required'
			],
			[
				'field' => 'jumlah_komplain',
				'label' => 'Jumlah Produk Komplain',
				'rules' => 'required|numeric|greater_than[0]'
			],
			[
				'field' => 'satuan',
				'label' => 'Satuan Unit',
				'rules' => 'required'
			],
			[
				'field' => 'jenis_ketidaksesuaian',
				'label' => 'Jenis Ketidaksesuaian',
				'rules' => 'required'
			]
		];
	}

	/**
	 * Mengambil daftar produk berdasarkan plant aktif.
	 */
	public function get_produk_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$tipe_user = $this->session->userdata('tipe_user');

		$this->db->from($this->table_produk);

		if ($tipe_user != 9) {
			$this->db->where('plant', $plant);
		}

		return $this->db
			->order_by('nama_produk', 'ASC')
			->get()
			->result();
	}

	/**
	 * Mengambil satu produk dan memastikan plant sesuai.
	 */
	public function get_produk($produk_uuid)
	{
		$plant = $this->session->userdata('plant');
		$tipe_user = $this->session->userdata('tipe_user');

		$this->db->from($this->table_produk);
		$this->db->where('uuid', $produk_uuid);

		if ($tipe_user != 9) {
			$this->db->where('plant', $plant);
		}

		return $this->db->get()->row();
	}

	/**
	 * Membaca tanggal produksi dari kode produksi.
	 *
	 * Contoh: QK11301AB0 = 11 November 2026.
	 */
	public function parse_kode_produksi($kode)
	{
		$kode = strtoupper(trim($kode));

		if (!preg_match('/^[A-Z][A-L][0-9]{2}[0-9][A-Z0-9]{5}$/', $kode)) {
			return false;
		}

		$kode_tahun = substr($kode, 0, 1);
		$kode_bulan = substr($kode, 1, 1);
		$kode_hari = substr($kode, 2, 2);

		// Q = 2026, R = 2027, S = 2028, dan seterusnya.
		$tahun = 2026 + (ord($kode_tahun) - ord('Q'));

		// A = Januari, B = Februari, dan seterusnya.
		$bulan = ord($kode_bulan) - ord('A') + 1;

		$hari = (int) $kode_hari;

		if ($tahun < 2026 || !checkdate($bulan, $hari, $tahun)) {
			return false;
		}

		return [
			'tanggal' => sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari),
			'kode_plant' => substr($kode, 4, 1)
		];
	}

	/**
	 * Menghitung tanggal Best Before berdasarkan masa simpan produk.
	 */
	public function hitung_best_before($tanggal_produksi)
	{
		try {
			$tanggal = new DateTime($tanggal_produksi);
			$tanggal->modify('+1 year');

			return $tanggal->format('Y-m-d');
		} catch (Exception $e) {
			return false;
		}
	}

	/**
	 * Menyimpan keluhan pelanggan.
	 */


	public function insert()
	{
		$produk_uuid = $this->input->post('produk_uuid', true);
		$kode_produksi = strtoupper(trim($this->input->post('kode_produksi', true)));

		// 1. Ambil plant dari session user
		$plant_aktif = $this->session->userdata('plant');

		if (empty($plant_aktif)) {
			log_message('error', 'Keluhan Pelanggan: Plant user tidak ditemukan di session.');
			return false;
		}

		// 2. Ambil produk
		$produk = $this->get_produk($produk_uuid);

		if (!$produk) {
			log_message('error', 'Keluhan Pelanggan: Produk tidak ditemukan atau bukan milik plant yang diizinkan.');
			return false;
		}

		// 3. Baca kode produksi
		$hasil_kode = $this->parse_kode_produksi($kode_produksi);

		if (!$hasil_kode) {
			log_message('error', 'Keluhan Pelanggan: Format atau tanggal kode produksi tidak valid.');
			return false;
		}

		// 4. Validasi kode plant produksi dengan plant user
		$kode_plant_aktif = [
			'1eb341e0-1ec4-4484-ba8f-32d23352b84d' => '3', // Salatiga
			'651ac623-5e48-44cc-b2f6-5d622603f53c' => '1'  // Cikande
		];

		if (!isset($kode_plant_aktif[$plant_aktif])) {
			log_message('error', 'Keluhan Pelanggan: UUID plant user tidak dikenali.');
			return false;
		}

		if ($hasil_kode['kode_plant'] !== $kode_plant_aktif[$plant_aktif]) {
			log_message('error', 'Keluhan Pelanggan: Kode plant produksi tidak sesuai dengan plant user.');
			return false;
		}

		// 5. Hitung tanggal produksi dan Best Before
		$tanggal_produksi = $hasil_kode['tanggal'];
		$best_before = $this->hitung_best_before($tanggal_produksi);

		if (!$best_before) {
			log_message('error', 'Keluhan Pelanggan: Gagal menghitung Best Before.');
			return false;
		}

		// 6. Siapkan data
		$data = [
			'uuid' => Uuid::uuid4()->toString(),
			'username' => $this->session->userdata('username'),
			'plant' => $plant_aktif,
			'tanggal_keluhan' => $this->input->post('tanggal_keluhan', true),
			'produk_uuid' => $produk->uuid,
			'nama_produk' => $produk->nama_produk,
			'kode_produksi' => $kode_produksi,
			'tanggal_produksi' => $tanggal_produksi,
			'best_before' => $best_before,
			'pelanggan' => $this->input->post('pelanggan', true),
			'jumlah_komplain' => $this->input->post('jumlah_komplain', true),
			'satuan' => $this->input->post('satuan', true),
			'jenis_ketidaksesuaian' => $this->input->post('jenis_ketidaksesuaian', true)
		];

		// 7. Simpan ke database
		$hasil = $this->db->insert($this->table, $data);

		if (!$hasil) {
			log_message(
				'error',
				'Keluhan Pelanggan DB Error: ' . json_encode($this->db->error())
			);

			return false;
		}

		return true;
	}
	/**
	 * Mengambil seluruh data keluhan berdasarkan plant aktif.
	 */
	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$tipe_user = $this->session->userdata('tipe_user');

		$this->db->from($this->table);

		if ($tipe_user != 9) {
			$this->db->where('plant', $plant);
		}

		return $this->db
			->order_by('tanggal_keluhan', 'DESC')
			->order_by('created_at', 'DESC')
			->get()
			->result();
	}

	/**
	 * Mengambil detail keluhan berdasarkan UUID.
	 */
	public function get_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');
		$tipe_user = $this->session->userdata('tipe_user');

		$this->db->from($this->table);
		$this->db->where('uuid', $uuid);

		if ($tipe_user != 9) {
			$this->db->where('plant', $plant);
		}

		return $this->db->get()->row();
	}
}
