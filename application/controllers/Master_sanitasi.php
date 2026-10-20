<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ramsey\Uuid\Uuid;

class Master_sanitasi extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('master_sanitasi_model');
		$this->load->model('auth_model');
	}

	public function index()
	{
		$data['master_sanitasi'] = $this->master_sanitasi_model->get_all();

		$data['active_nav'] = 'master_sanitasi';

		$this->load->view('partials/head', $data);
		$this->load->view('master/sanitasi/index', $data);
		$this->load->view('partials/footer', $data);
	}

	public function tambah()
	{
		if ($this->input->method() === 'post') {

			$plant = $this->session->userdata('plant');

			$data = [
				'uuid'         => Uuid::uuid4()->toString(),
				'plant'        => $plant,
				'area'         => $this->input->post('area', true),
				'standar_ppm'  => $this->input->post('standar_ppm', true),
				'standar_suhu' => $this->input->post('standar_suhu', true),
				'keterangan'   => 'OK'
			];

			if ($this->master_sanitasi_model->insert($data)) {
				$this->session->set_flashdata(
					'success_msg',
					'Master Sanitasi berhasil ditambahkan.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Master Sanitasi gagal ditambahkan.'
				);
			}

			redirect('master-sanitasi');
		}

		$data['active_nav'] = 'master_sanitasi';

		$this->load->view('partials/head', $data);
		$this->load->view('master/sanitasi/tambah', $data);
		$this->load->view('partials/footer', $data);
	}

	public function edit($uuid)
	{
		$data['master'] = $this->master_sanitasi_model->get_by_uuid($uuid);

		if (!$data['master']) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Master Sanitasi tidak ditemukan.'
			);

			redirect('master-sanitasi');
		}

		if ($this->input->method() === 'post') {

			$update = [
				'area'         => $this->input->post('area', true),
				'standar_ppm'  => $this->input->post('standar_ppm', true),
				'standar_suhu' => $this->input->post('standar_suhu', true),
				'keterangan'   => 'OK'
			];

			if ($this->master_sanitasi_model->update($uuid, $update)) {
				$this->session->set_flashdata(
					'success_msg',
					'Master Sanitasi berhasil diperbarui.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Master Sanitasi gagal diperbarui.'
				);
			}

			redirect('master-sanitasi');
		}

		$data['active_nav'] = 'master_sanitasi';

		$this->load->view('partials/head', $data);
		$this->load->view('master/sanitasi/edit', $data);
		$this->load->view('partials/footer', $data);
	}

	public function delete($uuid)
	{
		$master = $this->master_sanitasi_model->get_by_uuid($uuid);

		if (!$master) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Master Sanitasi tidak ditemukan.'
			);

			redirect('master-sanitasi');
		}

		if ($this->master_sanitasi_model->delete($uuid)) {
			$this->session->set_flashdata(
				'success_msg',
				'Master Sanitasi berhasil dihapus.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Master Sanitasi gagal dihapus.'
			);
		}

		redirect('master-sanitasi');
	}
}
