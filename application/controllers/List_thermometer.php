
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class List_thermometer extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('list_thermometer_model');

		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = [
			'list_thermometer' => $this->list_thermometer_model->get_all()
		];

		$this->active_nav = 'list_thermometer';
		$this->render('list_thermometer/list_thermometer', $data);
	}

	public function tambah()
	{
		$rules = $this->list_thermometer_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$insert = $this->list_thermometer_model->insert();

			if ($insert) {
				$this->session->set_flashdata(
					'success_msg',
					'Data List Thermometer berhasil disimpan'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data List Thermometer gagal disimpan'
				);
			}

			redirect('list_thermometer');
		}

		$this->active_nav = 'list_thermometer';
		$this->render('list_thermometer/list_thermometer-tambah');
	}

	public function edit($uuid)
	{
		$thermometer = $this->list_thermometer_model->get_by_uuid($uuid);

		if (!$thermometer) {
			$this->session->set_flashdata(
				'error_msg',
				'Data List Thermometer tidak ditemukan.'
			);
			redirect('list_thermometer');
			return;
		}

		$rules = $this->list_thermometer_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->list_thermometer_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data List Thermometer berhasil diupdate'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data List Thermometer gagal diupdate'
				);
			}

			redirect('list_thermometer');
		}

		$data = [
			'list_thermometer' => $thermometer
		];

		$this->active_nav = 'list_thermometer';
		$this->render('list_thermometer/list_thermometer-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata(
				'error_msg',
				'ID tidak ditemukan.'
			);
			redirect('list_thermometer');
			return;
		}

		$deleted = $this->list_thermometer_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata(
				'success_msg',
				'Data List Thermometer berhasil dihapus.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Gagal menghapus data List Thermometer.'
			);
		}

		redirect('list_thermometer');
	}
}
