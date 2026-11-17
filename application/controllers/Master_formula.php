
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_formula extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Master_formula_model', 'formula_model');
		$this->load->model('auth_model');
		$this->load->library('form_validation');
		$this->load->helper(['url', 'form']);
		$this->load->library('session');

		if (!$this->auth_model->current_user()) {
			redirect('login');
		}

		// Master Data hanya untuk tipe_user 0 dan 9
		if (!in_array((int) $this->session->userdata('tipe_user'), [0, 2, 9])) {
			show_error('Anda tidak memiliki akses ke halaman ini.', 403);
		}
	}

	// Generate UUID v4
	private function generate_uuid()
	{
		$data = random_bytes(16);

		$data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
		$data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

		return vsprintf(
			'%s%s-%s-%s-%s-%s%s%s',
			str_split(bin2hex($data), 4)
		);
	}

	// Ambil plant aktif
	private function get_plant()
	{
		return $this->session->userdata('plant');
	}

	// Ambil username aktif
	private function get_username()
	{
		return $this->session->userdata('username');
	}

	// Halaman daftar Master Formula
	public function index()
	{
		$data = [
			'formula' => $this->formula_model->get_all(
				$this->get_plant()
			)
		];

		$this->active_nav = 'master_formula';

		$this->render('master_formula/index', $data);
	}

	// Halaman tambah Master Formula
	// Halaman tambah Master Formula
	public function tambah()
	{
		$data = [
			'produk' => $this->formula_model->get_produk_dropdown(
				$this->get_plant()
			)
		];

		$this->active_nav = 'master_formula';

		$this->render('master_formula/tambah', $data);
	}

	// Simpan Master Formula
	public function simpan()
	{
		if ($this->input->method() !== 'post') {
			show_error('Metode tidak diizinkan.', 405);
		}

		$this->form_validation->set_rules(
			'produk_uuid',
			'Nama Produk',
			'required'
		);

		$this->form_validation->set_rules(
			'nama_formulasi',
			'Nama Formulasi',
			'required|trim'
		);

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata(
				'error_msg',
				'Nama produk dan nama formulasi wajib diisi.'
			);

			redirect('master_formula/tambah');
			return;
		}

		$data = [
			'uuid'           => $this->generate_uuid(),
			'produk_uuid'    => $this->input->post('produk_uuid', TRUE),
			'nama_formulasi' => trim(
				$this->input->post('nama_formulasi', TRUE)
			),
			'no_dokumen'     => $this->input->post('no_dokumen', TRUE),
			'revisi'         => $this->input->post('revisi', TRUE),
			'formula'        => $this->input->post('formula', TRUE),
			'plant'          => $this->get_plant(),
			'username'       => $this->get_username()
		];

		$details = $this->ambil_detail_post();

		$result = $this->formula_model->insert($data, $details);

		if ($result) {
			$this->session->set_flashdata(
				'success_msg',
				'Master Formula berhasil ditambahkan.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Master Formula gagal disimpan. Periksa kembali data formulasi.'
			);
		}

		redirect('master_formula');
	}

	// Update Master Formula
	public function update($uuid = null)
	{
		if ($this->input->method() !== 'post' || empty($uuid)) {
			show_404();
		}

		$plant = $this->get_plant();

		$formula = $this->formula_model->get_by_uuid($uuid, $plant);

		if (!$formula) {
			show_404();
		}

		$this->form_validation->set_rules(
			'produk_uuid',
			'Nama Produk',
			'required'
		);

		$this->form_validation->set_rules(
			'nama_formulasi',
			'Nama Formulasi',
			'required|trim'
		);

		if ($this->form_validation->run() === FALSE) {
			$this->session->set_flashdata(
				'error_msg',
				'Nama produk dan nama formulasi wajib diisi.'
			);

			redirect('master_formula/edit/' . $uuid);
			return;
		}

		$data = [
			'produk_uuid'    => $this->input->post('produk_uuid', TRUE),
			'nama_formulasi' => trim(
				$this->input->post('nama_formulasi', TRUE)
			),
			'no_dokumen'     => $this->input->post('no_dokumen', TRUE),
			'revisi'         => $this->input->post('revisi', TRUE),
			'formula'        => $this->input->post('formula', TRUE),
			'username'       => $this->get_username()
		];

		$details = $this->ambil_detail_post();

		$result = $this->formula_model->update(
			$uuid,
			$plant,
			$data,
			$details
		);

		if ($result) {
			$this->session->set_flashdata(
				'success_msg',
				'Master Formula berhasil diperbarui.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Master Formula gagal diperbarui. Periksa produk yang dipilih.'
			);
		}

		redirect('master_formula');
	}

	// Halaman Edit Master Formula
	public function edit($uuid = null)
	{
		if (empty($uuid)) {
			show_404();
		}

		$plant = $this->get_plant();

		$formula = $this->formula_model->get_by_uuid($uuid, $plant);

		if (!$formula) {
			show_404();
		}

		$data = [
			'formula' => $formula,
			'detail'  => $this->formula_model->get_detail($uuid),
			'produk'  => $this->formula_model->get_produk_dropdown($plant)
		];

		$this->active_nav = 'master_formula';

		$this->render('master_formula/edit', $data);
	}

	// Ambil Detail Master Formula untuk modal
	public function detail($uuid = null)
	{
		if (empty($uuid)) {
			show_404();
		}

		$plant = $this->get_plant();

		$formula = $this->formula_model->get_by_uuid($uuid, $plant);

		if (!$formula) {
			show_404();
		}

		$data = [
			'formula' => $formula,
			'detail'  => $this->formula_model->get_detail($uuid)
		];

		// View partial, bukan render layout utama
		$this->load->view('master_formula/detail', $data);
	}

	// Hapus Master Formula
	public function hapus($uuid)
	{
		if ($this->input->method() !== 'post') {
			show_error('Metode tidak diizinkan.', 405);
		}

		$plant = $this->get_plant();

		$formula = $this->formula_model->get_by_uuid($uuid, $plant);

		if (!$formula) {
			show_404();
		}

		$delete = $this->formula_model->delete($uuid, $plant);

		if ($delete) {
			$this->session->set_flashdata(
				'success_msg',
				'Master Formula berhasil dihapus.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Master Formula gagal dihapus.'
			);
		}

		redirect('master_formula');
	}


	// Mengambil daftar bahan dari form
	private function ambil_detail_post()
	{
		$nama_bahan = $this->input->post('nama_bahan');
		$standar_berat = $this->input->post('standar_berat');

		$details = [];

		if (!is_array($nama_bahan) || !is_array($standar_berat)) {
			return $details;
		}

		foreach ($nama_bahan as $index => $nama) {

			$nama = trim($nama);

			$berat = isset($standar_berat[$index])
				? trim($standar_berat[$index])
				: '';

			if ($nama === '' || $berat === '') {
				continue;
			}

			// Validasi angka
			if (!is_numeric($berat) || $berat < 0) {
				continue;
			}

			$details[] = [
				'nama_bahan'    => $nama,
				'standar_berat' => $berat
			];
		}

		return $details;
	}
}
