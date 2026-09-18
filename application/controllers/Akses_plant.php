<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Akses_plant extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('plant_model');
		$this->load->model('auth_model');
	}

	public function index()
	{
		// Hanya Admin
		if ($this->session->userdata('tipe_user') != 0) {
			show_error('Anda tidak memiliki akses ke halaman ini.', 403);
		}

		$data['page_title'] = 'Akses Plant';
		$data['active_nav'] = 'akses_plant';

		// Ambil semua user
		$data['users'] = $this->plant_model->get_all_users();

		// Ambil semua plant
		$data['plants'] = $this->plant_model->get_all();

		// User yang dipilih
		$selected_user = $this->input->get('user');

		$data['selected_user'] = $selected_user;

		// Default supaya tidak Undefined variable
		$data['selected_user_data'] = null;
		$data['user_access'] = [];

		// Jika ada user yang dipilih
		if ($selected_user) {

			foreach ($data['users'] as $user) {

				if ($user->uuid == $selected_user) {

					$data['selected_user_data'] = $user;
					break;
				}
			}

			// Ambil akses plant tambahan user
			$data['user_access'] =
				$this->plant_model->get_user_access_plants($selected_user);
		}

		// Tampilkan halaman
		$this->load->view('akses_plant/index', $data);
	}


	public function save()
	{
		if ($this->session->userdata('tipe_user') != 0) {
			show_error('Anda tidak memiliki akses ke halaman ini.', 403);
		}

		$user_uuid = $this->input->post('user_uuid');

		if (!$user_uuid) {
			redirect('akses-plant');
		}

		$plant_uuids = $this->input->post('plant_uuid');

		if (!is_array($plant_uuids)) {
			$plant_uuids = [];
		}

		// Ambil plant utama user
		$user = $this->db
			->where('uuid', $user_uuid)
			->get('pegawai')
			->row();

		if (!$user) {
			$this->session->set_flashdata(
				'error_msg',
				'User tidak ditemukan.'
			);

			redirect('akses-plant');
		}

		// Jangan simpan plant utama sebagai akses tambahan
		$plant_uuids = array_filter($plant_uuids, function ($plant_uuid) use ($user) {
			return $plant_uuid != $user->plant;
		});

		$this->plant_model->save_user_access(
			$user_uuid,
			$plant_uuids
		);

		$this->session->set_flashdata(
			'success_msg',
			'Akses plant berhasil diperbarui.'
		);

		redirect('akses-plant?user=' . urlencode($user_uuid));
	}
}
