<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('auth_model');
		$this->load->library('form_validation');
	}

	public function index()
	{
		if ($this->session->userdata('logged_in')) {
			redirect('home');
		} else {
			redirect('auth/login');
		}
	}

	public function login()
	{

		if ($this->auth_model->current_user()) {
			redirect('home');
		}

		$rules = $this->auth_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == FALSE) {
			return $this->load->view('auth/login');
		}

		$username = $this->input->post('username');
		$password = $this->input->post('password');

		if ($this->auth_model->login($username, $password)) {
			$this->session->set_userdata('show_produksi_modal', true);
			redirect('home');
		} else {
			$this->session->set_flashdata('error_msg', 'Login Gagal, pastikan username dan passwrod benar!');
		}

		$this->load->view('auth/login');
	}

	public function switch_plant()
	{
		if (!$this->session->userdata('user_uuid')) {
			redirect('auth/login');
		}

		$plant_uuid = $this->input->post('plant_uuid');
		$redirect_url = $this->input->post('redirect_url');

		if (!$plant_uuid) {
			redirect('home');
		}

		if ($this->auth_model->switch_plant($plant_uuid)) {

			// Ambil nama plant
			$plant = $this->db
				->where('uuid', $plant_uuid)
				->get('plant')
				->row();

			$plant_name = $plant ? $plant->plant : 'Plant';

			$this->session->set_flashdata(
				'success_msg',
				'Berhasil switch ke plant ' . $plant_name . '.'
			);
		} else {

			$this->session->set_flashdata(
				'error_msg',
				'Anda tidak memiliki akses ke plant tersebut.'
			);
		}

		// Tetap di halaman sebelumnya
		if ($redirect_url) {
			redirect($redirect_url);
		}

		redirect('home');
	}

	public function logout()
	{
		$this->auth_model->logout();
		redirect(base_url('auth/login'));
	}
}
