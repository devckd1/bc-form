
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_tahapan_magnettrap extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Master_tahapan_magnettrap_model');
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

    public function index()
    {
        $data = [
            'master_tahapan' => $this->Master_tahapan_magnettrap_model->get_all()
        ];

        $this->active_nav = 'master_tahapan_magnettrap';

        $this->render('master_tahapan_magnettrap/index', $data);
    }

    public function tambah()
    {
        $this->form_validation->set_rules(
            'nama_tahapan',
            'Nama Tahapan',
            'required|trim|max_length[255]'
        );

        if ($this->form_validation->run() == TRUE) {

            $nama = trim($this->input->post('nama_tahapan', TRUE));

            if ($this->Master_tahapan_magnettrap_model->exists_name($nama)) {

                $this->session->set_flashdata(
                    'error_msg',
                    'Nama tahapan sudah tersedia.'
                );

                redirect('master_tahapan_magnettrap/tambah');
                return;
            }

            $data = [
                'uuid'         => $this->generate_uuid(),
                'plant'        => $this->session->userdata('plant'),
                'nama_tahapan' => $nama
            ];

            $insert = $this->Master_tahapan_magnettrap_model->insert($data);

            if ($insert) {
                $this->session->set_flashdata(
                    'success_msg',
                    'Tahapan Magnet Trap berhasil ditambahkan.'
                );
            } else {
                $this->session->set_flashdata(
                    'error_msg',
                    'Tahapan Magnet Trap gagal ditambahkan.'
                );
            }

            redirect('master_tahapan_magnettrap');
            return;
        }

        $this->active_nav = 'master_tahapan_magnettrap';

        $this->render('master_tahapan_magnettrap/tambah');
    }

    public function edit($uuid)
    {
        $item = $this->Master_tahapan_magnettrap_model->get_by_uuid($uuid);

        if (!$item) {
            show_404();
        }

        $this->form_validation->set_rules(
            'nama_tahapan',
            'Nama Tahapan',
            'required|trim|max_length[255]'
        );

        if ($this->form_validation->run() == TRUE) {

            $nama = trim($this->input->post('nama_tahapan', TRUE));

            if ($this->Master_tahapan_magnettrap_model->exists_name($nama, $uuid)) {

                $this->session->set_flashdata(
                    'error_msg',
                    'Nama tahapan sudah tersedia.'
                );

                redirect('master_tahapan_magnettrap/edit/' . $uuid);
                return;
            }

            $update = $this->Master_tahapan_magnettrap_model->update($uuid, [
                'nama_tahapan' => $nama
            ]);

            if ($update) {
                $this->session->set_flashdata(
                    'success_msg',
                    'Tahapan Magnet Trap berhasil diperbarui.'
                );
            } else {
                $this->session->set_flashdata(
                    'error_msg',
                    'Tahapan Magnet Trap gagal diperbarui.'
                );
            }

            redirect('master_tahapan_magnettrap');
            return;
        }

        $data = [
            'item' => $item
        ];

        $this->active_nav = 'master_tahapan_magnettrap';

        $this->render('master_tahapan_magnettrap/edit', $data);
    }

    public function hapus($uuid)
    {
        if ($this->input->method() !== 'post') {
            show_error('Metode tidak diizinkan.', 405);
        }

        $item = $this->Master_tahapan_magnettrap_model->get_by_uuid($uuid);

        if (!$item) {
            show_404();
        }

        $delete = $this->Master_tahapan_magnettrap_model->delete($uuid);

        if ($delete) {
            $this->session->set_flashdata(
                'success_msg',
                'Tahapan Magnet Trap berhasil dihapus.'
            );
        } else {
            $this->session->set_flashdata(
                'error_msg',
                'Tahapan Magnet Trap gagal dihapus.'
            );
        }

        redirect('master_tahapan_magnettrap');
    }
}
