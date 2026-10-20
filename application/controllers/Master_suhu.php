<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_suhu extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Master_suhu_model');
		$this->load->model('auth_model');
		$this->load->library('form_validation');
		$this->load->helper(['url', 'form']);
		$this->load->library('session');

		if (!$this->auth_model->current_user()) {
			redirect('login');
		}

		// Master Data hanya untuk tipe_user 0
		if ((int) $this->session->userdata('tipe_user') !== 0) {
			show_error('Anda tidak memiliki akses ke halaman ini.', 403);
		}
	}

	public function index()
	{
		$data = [
			'master_suhu' => $this->Master_suhu_model->get_by_plant()
		];

		$this->active_nav = 'master_suhu';

		$this->render('master_suhu/index', $data);
	}

	public function tambah()
	{
		$rules = $this->Master_suhu_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$nama_lokasi = $this->input->post('nama_lokasi', TRUE);

			if ($this->Master_suhu_model->lokasi_exists($nama_lokasi)) {

				$this->session->set_flashdata(
					'error_msg',
					'Lokasi tersebut sudah tersedia pada Master Data Suhu.'
				);

				redirect('master-suhu/tambah');
				return;
			}

			$insert = $this->Master_suhu_model->insert();

			if ($insert) {
				$this->session->set_flashdata(
					'success_msg',
					'Master Data Suhu berhasil ditambahkan.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Master Data Suhu gagal ditambahkan.'
				);
			}

			redirect('master-suhu');
			return;
		}

		$this->active_nav = 'master_suhu';

		$this->render('master_suhu/tambah');
	}

	public function edit($uuid)
	{
		$master = $this->Master_suhu_model->get_by_uuid($uuid);

		if (!$master) {
			show_404();
		}

		$rules = $this->Master_suhu_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$nama_lokasi = $this->input->post('nama_lokasi', TRUE);

			if (
				$this->Master_suhu_model
				->lokasi_exists($nama_lokasi, $uuid)
			) {
				$this->session->set_flashdata(
					'error_msg',
					'Lokasi tersebut sudah digunakan oleh Master Data lain.'
				);

				redirect('master-suhu/edit/' . $uuid);
				return;
			}

			$update = $this->Master_suhu_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Master Data Suhu berhasil diperbarui.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Master Data Suhu gagal diperbarui.'
				);
			}

			redirect('master-suhu');
			return;
		}

		$data = [
			'master' => $master
		];

		$this->active_nav = 'master_suhu';

		$this->render('master_suhu/edit', $data);
	}

	public function status($uuid)
	{
		$master = $this->Master_suhu_model->get_by_uuid($uuid);

		if (!$master) {
			show_404();
		}

		$update = $this->Master_suhu_model->toggle_status($uuid);

		if ($update) {
			$status = $master->is_active == 1
				? 'dinonaktifkan'
				: 'diaktifkan';

			$this->session->set_flashdata(
				'success_msg',
				'Master Data Suhu berhasil ' . $status . '.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Status Master Data Suhu gagal diperbarui.'
			);
		}

		redirect('master-suhu');
	}

	public function delete($uuid)
	{
		$master = $this->Master_suhu_model->get_by_uuid($uuid);

		if (!$master) {
			show_404();
		}

		$delete = $this->Master_suhu_model->delete_by_uuid($uuid);

		if ($delete) {
			$this->session->set_flashdata(
				'success_msg',
				'Master Data Suhu berhasil dihapus.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Master Data Suhu gagal dihapus.'
			);
		}

		redirect('master-suhu');
	}
}
