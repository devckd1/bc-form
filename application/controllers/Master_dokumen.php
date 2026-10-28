
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_dokumen extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('master_dokumen_model');

		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = [
			'master_dokumen' => $this->master_dokumen_model->get_all()
		];

		$this->active_nav = 'master-dokumen';
		$this->render('master/dokumen/index', $data);
	}

	public function tambah()
	{
		$rules = $this->master_dokumen_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			if ($this->master_dokumen_model->insert()) {
				$this->session->set_flashdata(
					'success_msg',
					'Master Dokumen berhasil ditambahkan.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Master Dokumen gagal ditambahkan.'
				);
			}

			redirect('master-dokumen');
		}

		$this->active_nav = 'master-dokumen';
		$this->render('master/dokumen/tambah');
	}

	public function edit($uuid)
	{
		$data['master_dokumen'] =
			$this->master_dokumen_model->get_by_uuid($uuid);

		if (!$data['master_dokumen']) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Master Dokumen tidak ditemukan.'
			);

			redirect('master-dokumen');
		}

		$rules = $this->master_dokumen_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			if ($this->master_dokumen_model->update($uuid)) {
				$this->session->set_flashdata(
					'success_msg',
					'Master Dokumen berhasil diperbarui.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Master Dokumen gagal diperbarui.'
				);
			}

			redirect('master-dokumen');
		}

		$this->active_nav = 'master-dokumen';
		$this->render('master/dokumen/edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata(
				'error_msg',
				'UUID tidak ditemukan.'
			);

			redirect('master-dokumen');
		}

		if ($this->master_dokumen_model->delete($uuid)) {
			$this->session->set_flashdata(
				'success_msg',
				'Master Dokumen berhasil dihapus.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Master Dokumen gagal dihapus.'
			);
		}

		redirect('master-dokumen');
	}
}
