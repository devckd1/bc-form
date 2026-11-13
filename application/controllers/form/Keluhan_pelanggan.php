
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Keluhan_pelanggan extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('keluhan_pelanggan_model', 'keluhan_model');

		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	/**
	 * Halaman daftar keluhan pelanggan.
	 */
	public function index()
	{
		$data = [
			'keluhan' => $this->keluhan_model->get_data_by_plant()
		];

		$this->active_nav = 'keluhan-pelanggan';
		$this->render('form/keluhan_pelanggan/index', $data);
	}

	/**
	 * Halaman tambah keluhan pelanggan.
	 */
	public function tambah()
	{
		$data = [
			'produk' => $this->keluhan_model->get_produk_by_plant()
		];

		$this->active_nav = 'keluhan-pelanggan';
		$this->render('form/keluhan_pelanggan/tambah', $data);
	}

	/**
	 * Proses simpan keluhan pelanggan.
	 */
	public function simpan()
	{
		$this->form_validation->set_rules(
			$this->keluhan_model->rules()
		);

		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata(
				'error',
				validation_errors()
			);

			redirect('form/keluhan_pelanggan/tambah');
			return;
		}

		$hasil = $this->keluhan_model->insert();

		if ($hasil) {
			$this->session->set_flashdata(
				'success',
				'Data keluhan pelanggan berhasil disimpan.'
			);

			redirect('form/keluhan_pelanggan');
			return;
		}

		$this->session->set_flashdata(
			'error',
			'Data gagal disimpan. Periksa log aplikasi untuk mengetahui penyebabnya.'
		);

		redirect('form/keluhan_pelanggan/tambah');
	}

	/**
	 * Halaman detail keluhan pelanggan.
	 */
	public function detail($uuid = null)
	{
		if (empty($uuid)) {
			show_404();
			return;
		}

		$data['keluhan'] = $this->keluhan_model->get_by_uuid($uuid);

		if (!$data['keluhan']) {
			show_404();
			return;
		}

		$this->render('form/keluhan_pelanggan/detail', $data);
	}
}
