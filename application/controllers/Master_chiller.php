<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_chiller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->library('form_validation');
        $this->load->model('auth_model');
        $this->load->model('master_chiller_model');

        if (!$this->auth_model->current_user()) {
            redirect('login');
        }
    }

    public function index()
    {
        $data = [
            'master_chiller' => $this->master_chiller_model->get_all()
        ];

        $this->active_nav = 'master-chiller';
        $this->render('master/chiller/index', $data);
    }

    public function tambah()
    {
        $rules = $this->master_chiller_model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            if ($this->master_chiller_model->insert()) {
                $this->session->set_flashdata(
                    'success_msg',
                    'Master Chiller berhasil ditambahkan.'
                );
            } else {
                $this->session->set_flashdata(
                    'error_msg',
                    'Master Chiller gagal ditambahkan.'
                );
            }

            redirect('master-chiller');
        }

        $this->active_nav = 'master-chiller';
        $this->render('master/chiller/tambah');
    }

    public function edit($uuid)
    {
        $data['master_chiller'] =
            $this->master_chiller_model->get_by_uuid($uuid);

        if (!$data['master_chiller']) {
            $this->session->set_flashdata(
                'error_msg',
                'Data Master Chiller tidak ditemukan.'
            );

            redirect('master-chiller');
        }

        $rules = $this->master_chiller_model->rules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === TRUE) {

            if ($this->master_chiller_model->update($uuid)) {
                $this->session->set_flashdata(
                    'success_msg',
                    'Master Chiller berhasil diperbarui.'
                );
            } else {
                $this->session->set_flashdata(
                    'error_msg',
                    'Master Chiller gagal diperbarui.'
                );
            }

            redirect('master-chiller');
        }

        $this->active_nav = 'master-chiller';
        $this->render('master/chiller/edit', $data);
    }

    public function delete($uuid)
    {
        if (!$uuid) {
            $this->session->set_flashdata(
                'error_msg',
                'UUID tidak ditemukan.'
            );

            redirect('master-chiller');
        }

        if ($this->master_chiller_model->delete($uuid)) {
            $this->session->set_flashdata(
                'success_msg',
                'Master Chiller berhasil dihapus.'
            );
        } else {
            $this->session->set_flashdata(
                'error_msg',
                'Master Chiller gagal dihapus.'
            );
        }

        redirect('master-chiller');
    }
}
