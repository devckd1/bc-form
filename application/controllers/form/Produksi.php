<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Produksi extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model'); 
		$this->load->model('produksi_model');
		$this->load->model('packing_model');
		$this->load->model('produk_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$this->load->library('pagination');
		$this->load->helper('url');

		$plant = $this->session->userdata('plant');
		$keyword = $this->input->get('keyword');

    // Hitung total data
		$total_rows = $this->produksi_model->count_all_by_plant($plant, $keyword);

    // Konfigurasi pagination
		$config['base_url'] = base_url('produksi');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = 10;
		$config['page_query_string'] = TRUE; 
		$config['reuse_query_string'] = TRUE; 

    // Styling bootstrap
		$config['full_tag_open'] = '<ul class="pagination justify-content-center">';
		$config['full_tag_close'] = '</ul>';
		$config['attributes'] = ['class' => 'page-link'];
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
		$config['cur_tag_close'] = '</span></li>';

		$this->pagination->initialize($config);

		$page = $this->input->get('per_page'); 
		$start = ($page) ? $page : 0;

		$data = [
			'produksi' => $this->produksi_model->get_data_by_plant_paginated($plant, $config['per_page'], $start, $keyword),
			'pagination' => $this->pagination->create_links(),
			'keyword' => $keyword
		];

		$this->active_nav = 'produksi'; 
		$this->render('form/produksi/produksi', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'produksi' => $this->produksi_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'produksi'; 
		$this->render('form/produksi/produksi-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->produksi_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->produksi_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di simpan');
				redirect('produksi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Produksi gagal di simpan');
				redirect('produksi');
			}
		}

		$kode_produksi_terakhir = $this->produksi_model->getLastKodeProduksiHariIni();
		$plant = $this->session->userdata('plant');
		$produk_list = $this->produk_model->get_all_produk_by_plant($plant);

		// $produk_list = $this->produk_model->get_all_produk();

		$data = array(
			'kode_produksi_terakhir' => $kode_produksi_terakhir,
			'produk_list' => $produk_list
		);

		$this->active_nav = 'produksi'; 
		$this->render('form/produksi/produksi-tambah', $data);
	}

	public function file_check_kode($str)
	{
		if (!empty($_FILES['gambar_kode_kemasan']['name'])) {
			$allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];
			$mime = mime_content_type($_FILES['gambar_kode_kemasan']['tmp_name']);

			if (!in_array($mime, $allowed_mime)) {
				$this->form_validation->set_message('file_check_kode', 'File harus berupa gambar JPG, PNG, atau GIF.');
				return false;
			}

			if ($_FILES['gambar_kode_kemasan']['size'] > 2 * 1024 * 1024) {
				$this->form_validation->set_message('file_check_kode', 'Ukuran gambar maksimal 2MB.');
				return false;
			}
		}

		return true;
	}

	public function file_check_kondisi($str)
	{
		if (!empty($_FILES['gambar_kondisi_kemasan']['name'])) {
			$allowed_mime = ['image/jpeg', 'image/png', 'image/gif'];
			$mime = mime_content_type($_FILES['gambar_kondisi_kemasan']['tmp_name']);

			if (!in_array($mime, $allowed_mime)) {
				$this->form_validation->set_message('file_check_kondisi', 'File harus berupa gambar JPG, PNG, atau GIF.');
				return false;
			}

			if ($_FILES['gambar_kondisi_kemasan']['size'] > 2 * 1024 * 1024) {
				$this->form_validation->set_message('file_check_kondisi', 'Ukuran gambar maksimal 2MB.');
				return false;
			}
		}

		return true;
	}

	public function edit($uuid)
	{
		$rules = $this->produksi_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->produksi_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di Update');
				redirect('produksi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Produksi gagal di Update');
				redirect('produksi');
			}
		}

		$data = array(
			'produksi' => $this->produksi_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'produksi'; 
		$this->render('form/produksi/produksi-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('produksi');
		}

		$deleted = $this->produksi_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('produksi');
	}

	public function bahan($uuid)
	{
		$rules = $this->produksi_model->rules_bahan();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->produksi_model->material($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di Update');
				redirect('produksi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Produksi gagal di Update');
				redirect('produksi');
			}
		}

		$data = array(
			'produksi' => $this->produksi_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'produksi'; 
		$this->render('form/produksi/produksi-bahan', $data);
	}

	public function mixing($uuid)
	{
		$rules = $this->produksi_model->rules_mixing();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->produksi_model->mixed($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di Update');
				redirect('produksi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Produksi gagal di Update');
				redirect('produksi');
			}
		}

		$data = array(
			'produksi' => $this->produksi_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'produksi'; 
		$this->render('form/produksi/produksi-mixing', $data);
	}

	public function fermentasi($uuid)
	{
		$rules = $this->produksi_model->rules_fermen();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->produksi_model->fermented($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di Update');
				redirect('produksi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Produksi gagal di Update');
				redirect('produksi');
			}
		}

		$data = array(
			'produksi' => $this->produksi_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'produksi'; 
		$this->render('form/produksi/produksi-fermentasi', $data);
	}

	public function baking($uuid)
	{
		$rules = $this->produksi_model->rules_bake();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->produksi_model->baked($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di Update');
				redirect('produksi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Produksi gagal di Update');
				redirect('produksi');
			}
		}

		$data = array(
			'produksi' => $this->produksi_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'produksi'; 
		$this->render('form/produksi/produksi-baking', $data);
	}

	public function stalling($uuid)
	{
		$rules = $this->produksi_model->rules_stalling();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->produksi_model->rest($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di Update');
				redirect('produksi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Produksi gagal di Update');
				redirect('produksi');
			}
		}

		$data = array(
			'produksi' => $this->produksi_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'produksi'; 
		$this->render('form/produksi/produksi-stalling', $data);
	}

	// public function grinding($uuid)
	// {
	// 	$rules = $this->produksi_model->rules_grinding();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) { 

	// 		$update = $this->produksi_model->grind($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di Update');
	// 			redirect('produksi');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Data Verifikasi Produksi gagal di Update');
	// 			redirect('produksi');
	// 		}
	// 	}

	// 	$data = array(
	// 		'produksi' => $this->produksi_model->get_by_uuid($uuid),
	// 		'active_nav' => 'produksi'
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/produksi/produksi-grinding', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function drying($uuid)
	{
		$rules = $this->produksi_model->rules_drying();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->produksi_model->dry($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di Update');
				redirect('produksi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Produksi gagal di Update');
				redirect('produksi');
			}
		}

		$data = array(
			'produksi' => $this->produksi_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'produksi'; 
		$this->render('form/produksi/produksi-drying', $data);
	}

	// public function packing($uuid)
	// {
	// 	$rules = $this->produksi_model->rules_packing();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->produksi_model->pack($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di Update');
	// 			redirect('produksi');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Data Verifikasi Produksi gagal di Update');
	// 			redirect('produksi');
	// 		}
	// 	}

	// 	$data = array(
	// 		'produksi' => $this->produksi_model->get_by_uuid($uuid)
	// 	);

	// 	$this->active_nav = 'produksi'; 
	// 	$this->render('form/produksi/produksi-packing', $data);
	// }

	public function packing($uuid)
	{
		$rules = $this->produksi_model->rules_packing();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$result = $this->produksi_model->pack($uuid);

        // ✅ Jika upload gagal
			if (!$result['status']) {

				$data = [
					'produksi' => $this->produksi_model->get_by_uuid($uuid),
					'upload_error' => $result['error']
				];

				$this->active_nav = 'produksi';
				return $this->render('form/produksi/produksi-packing', $data);
			}

        // ✅ Jika sukses
			$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di Update');
			redirect('produksi');
		}

    // ✅ Jika validasi form gagal
		$data = [
			'produksi' => $this->produksi_model->get_by_uuid($uuid)
		];

		$this->active_nav = 'produksi';
		$this->render('form/produksi/produksi-packing', $data);
	}

	public function verifikasi() 
	{
		$data = array(
			'produksi' => $this->produksi_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-produksi'; 
		$this->render('form/produksi/produksi-verifikasi', $data);
	}

	public function status($uuid) 
	{
		$rules = $this->produksi_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->produksi_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Produksi berhasil di Update');
				redirect('produksi/verifikasi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Produksi gagal di Update');
				redirect('produksi/verifikasi');
			}
		}

		$data = array(
			'produksi' => $this->produksi_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-produksi'; 
		$this->render('form/produksi/produksi-status', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'produksi' => $this->produksi_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-produksi', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/produksi/produksi-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->produksi_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->produksi_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Verifikasi Produksi berhasil di Update');
	// 			redirect('produksi/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Verifikasi Produksi gagal di Update');
	// 			redirect('produksi/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'produksi' => $this->produksi_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-produksi');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/produksi/produksi-statusprod', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function cetak()
	{
    // Ambil tanggal dan nama produk dari form
		$tanggal = $this->input->post('tanggal');
		$nama_produk = $this->input->post('nama_produk');

		log_message('debug', 'Tanggal cetak: ' . $tanggal);
		log_message('debug', 'Nama produk: ' . $nama_produk);

		if (empty($tanggal) || empty($nama_produk)) {
			show_error('Tanggal dan nama produk harus diisi', 404);
		}

		$produksi_data = $this->produksi_model->get_by_uuid_produksi($tanggal, $nama_produk);
		$produksi_data_verif = $this->produksi_model->get_by_uuid_produksi_verif($tanggal, $nama_produk);

		$data['produksi'] = $produksi_data_verif;

		if (!$produksi_data || !$produksi_data_verif) {
			$this->session->set_flashdata('error_msg', 'Data tidak ditemukan untuk tanggal yang dipilih.');
			redirect('produksi/verifikasi'); 
		}

		$this->load->model('pegawai_model');
		$nama_qc = $this->pegawai_model->get_nama_lengkap($data['produksi']->username);
		$nama_prod = $this->pegawai_model->get_nama_lengkap($data['produksi']->nama_produksi);
		$nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['produksi']->nama_spv);

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false); 
		$pdf->AddPage();

		$maxColumnsPerPage = 4;
		$chunks = array_chunk($produksi_data, $maxColumnsPerPage);

		foreach ($chunks as $chunkIndex => $chunk) {
			if ($chunkIndex > 0) {
				$pdf->AddPage();
			}

			$pdf->SetMargins(9, 10, 8);
			$pdf->SetFont('times', 'B', 13);

			$logo_path = FCPATH . 'assets/img/logo.jpg';
			if (file_exists($logo_path)) {
				$pdf->Image($logo_path, 10, 10, 35);
			} else {
				$pdf->Write(7, "Logo tidak ditemukan\n");
			}

			$pdf->Write(11, "\n");
			$pdf->MultiCell(0, 5, 'VERIFIKASI PROSES PRODUKSI', 0, 'C');
			$pdf->Ln(5);

			setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
			$tanggal = $data['produksi']->date;
			$date = new DateTime($tanggal);
			$formatted_date = date('l, d F Y', $date->getTimestamp());
			$formatted_date2 = date('l, d F Y', $date->getTimestamp());

			$pdf->SetFont('times', '', 8);
			$pdf->SetX(8);
			$pdf->Write(0, 'Tanggal: ' . $formatted_date);
			$pdf->SetX($pdf->GetX() + 10);
			$pdf->Write(0, 'Shift: ' . $data['produksi']->shift);
			$pdf->SetX($pdf->GetX() + 10);
			$pdf->Write(0, 'Produk: ' . $data['produksi']->nama_produk);
			$pdf->Ln(4);

			$pdf->Cell(35, 4, 'Jenis Produk', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;
			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->nama_produk, 1, 0, 'C');
			}
			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C');
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Kode Produksi', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->kode_produksi, 1, 0, 'C');
			}
			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->SetFont('times', 'B', 7);
			$pdf->Cell(35, 4, 'Parameter', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;
			foreach ($chunk as $item) {
				$pdf->Cell(20, 4, 'Kode', 1, 0, 'C');
				$pdf->Cell(10, 4, 'Kg', 1, 0, 'C');
				$pdf->Cell(10, 4, 'Sens', 1, 0, 'C');
			}
			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(20, 4, 'Kode', 1, 0, 'C'); 
				$pdf->Cell(10, 4, 'Kg', 1, 0, 'C'); 
				$pdf->Cell(10, 4, 'Sens', 1, 0, 'C');
			}

			$pdf->Ln();
			$pdf->Cell(195, 4, 'Raw Material', 1, 0, 'L');
			$pdf->Ln();
			$pdf->SetFont('times', '', 7);
			$pdf->Cell(35, 4, 'Tepung Terigu', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(20, 4, $item->tegu_kode, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->tegu_berat, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->tegu_sens, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(20, 4, '', 1, 0, 'C');
				$pdf->Cell(10, 4, '', 1, 0, 'C'); 
				$pdf->Cell(10, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Tapioka Stract', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(20, 4, $item->tapioka_kode, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->tapioka_berat, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->tapioka_sens, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(20, 4, '', 1, 0, 'C');
				$pdf->Cell(10, 4, '', 1, 0, 'C'); 
				$pdf->Cell(10, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Ragi', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(20, 4, $item->ragi_kode, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->ragi_berat, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->ragi_sens, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(20, 4, '', 1, 0, 'C');
				$pdf->Cell(10, 4, '', 1, 0, 'C'); 
				$pdf->Cell(10, 4, '', 1, 0, 'C');
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Bread Improver', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(20, 4, $item->bread_kode, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->bread_berat, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->bread_sens, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(20, 4, '', 1, 0, 'C'); 
				$pdf->Cell(10, 4, '', 1, 0, 'C');
				$pdf->Cell(10, 4, '', 1, 0, 'C'); 
			}
			$pdf->Ln();

			$maxColumns = 4;
			$selectedProduksi = array_slice($chunk, 0, $maxColumns);

			$premixColumns = [];
			foreach ($selectedProduksi as $item) {
				$premixData = json_decode($item->premix, true);
				$premixColumns[] = is_array($premixData) ? $premixData : [];
			}

			$maxRows = 0;
			foreach ($premixColumns as $col) {
				$maxRows = max($maxRows, count($col));
			}

			$pdf->Cell(195, 4, 'Premix', 1, 0, 'L');
			$pdf->Ln();
			for ($row = 0; $row < $maxRows; $row++) {
				$nama_premix = $premixColumns[0][$row]['nama_premix'] ?? '';
				$pdf->Cell(35, 4, $nama_premix, 1, 0, 'L');

				for ($col = 0; $col < $maxColumns; $col++) {
					$kode  = $premixColumns[$col][$row]['kode']  ?? '';
					$berat = $premixColumns[$col][$row]['berat'] ?? '';
					$sens  = $premixColumns[$col][$row]['sens']  ?? '';

					$pdf->Cell(20, 4, $kode, 1, 0, 'C');
					$pdf->Cell(10, 4, $berat, 1, 0, 'C');
					$pdf->Cell(10, 4, $sens, 1, 0, 'C');
				}

				$pdf->Ln();
			}

			$pdf->Cell(35, 4, 'Shortening', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(20, 4, $item->shortening_kode, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->shortening_berat, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->shortening_sens, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(20, 4, '', 1, 0, 'C');
				$pdf->Cell(10, 4, '', 1, 0, 'C'); 
				$pdf->Cell(10, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Chill Water (15 ± 1°C)', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(20, 4, $item->chill_water_kode, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->chill_water_berat, 1, 0, 'C');
				$pdf->Cell(10, 4, $item->chill_water_sens, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(20, 4, '', 1, 0, 'C');
				$pdf->Cell(10, 4, '', 1, 0, 'C'); 
				$pdf->Cell(10, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->SetFont('times', 'B', 7);
			$pdf->Cell(195, 4, 'Mixing Dough', 1, 0, 'L');
			$pdf->Ln();
			$pdf->SetFont('times', '', 7);
			$pdf->Cell(35, 4, 'Waktu Mixing (11 Menit)', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->mix_dough_waktu_1, 1, 0, 'C');
			}
			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Hasil & Nomor Mesin', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$hasil = ($item->mix_dough_hasil == 1) ? 'Oke' : 'Tidak Oke';
				$pdf->Cell(40, 4, $hasil . ' / ' . $item->mix_dough_mesin, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Dough Cutting(630-670 g)', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->mix_dough_cutting, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C');
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Suhu & RH Ruang', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->mix_dough_suhu_ruang. ' / '. $item->mix_dough_rh_ruang, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Suhu Adonan (29-31°C)', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->mix_dough_suhu_adonan, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->SetFont('times', 'B', 7);
			$pdf->Cell(195, 4, 'Fermentasi', 1, 0, 'L');
			$pdf->Ln();
			$pdf->SetFont('times', '', 7);
			$pdf->Cell(35, 4, 'Suhu (°C)', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->fermen_suhu, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'RH (%)', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->fermen_rh, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Jam Mulai', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$jam_mulai = date('H:i', strtotime($item->fermen_jam_mulai));
				$pdf->Cell(40, 4, $jam_mulai, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Jam Selesai', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$jam_selesai = date('H:i', strtotime($item->fermen_jam_selesai));
				$pdf->Cell(40, 4, $jam_selesai, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Lama Proses', 1, 0, 'L');

			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->fermen_lama_proses, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->SetFont('times', 'B', 7);
			$pdf->Cell(195, 4, 'Electric Baking', 1, 0, 'L');
			$pdf->SetFont('times', '', 7);
			$pdf->Ln();
			$pdf->Cell(35, 4, 'Suhu Produk(80-97°C)', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->electric_baking_suhu, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C');
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'No.Mesin & Expand Roti(%)', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->electric_baking_mesin. ' / '. $item->electric_baking_expand, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->SetFont('times', 'B', 7);
			$pdf->Cell(195, 4, 'Sensori', 1, 0, 'L');
			$pdf->Ln();
			$pdf->SetFont('times', '', 7);
			$pdf->Cell(35, 4, 'Kematangan', 1, 0, 'L');

			$pdf->SetFont('dejavusans', '', 7);	
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, ($item->sens_kematangan == 'oke') ? '✔' : '✘', 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->SetFont('times', '', 7);
			$pdf->Ln();
			$pdf->Cell(35, 4, 'Rasa', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 7);	
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, ($item->sens_rasa == 'oke') ? '✔' : '✘', 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C');
			}

			$pdf->SetFont('times', '', 7);
			$pdf->Ln();
			$pdf->Cell(35, 4, 'Aroma', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 7);	
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, ($item->sens_aroma == 'oke') ? '✔' : '✘', 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->SetFont('times', '', 7);
			$pdf->Ln();
			$pdf->Cell(35, 4, 'Tekstur', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 7);	
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, ($item->sens_tekstur == 'oke') ? '✔' : '✘', 1, 0, 'C');
			}
			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C');
			}

			$pdf->SetFont('times', '', 7);
			$pdf->Ln();
			$pdf->Cell(35, 4, 'Warna', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 7);	
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, ($item->sens_warna == 'oke') ? '✔' : '✘', 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln(6);
			$pdf->SetFont('times', '', 8);
			$tanggal_stall = $data['produksi']->date_stall;
			$stall = new DateTime($tanggal_stall);
			$formatted_stall = date('l, d F Y', $stall->getTimestamp());
			$pdf->SetX(8);
			$pdf->Write(0, 'Tanggal: ' . $formatted_stall);
			$pdf->SetX($pdf->GetX() + 10);
			$pdf->Write(0, 'Shift: ' . $data['produksi']->shift_pack);
			$pdf->SetX($pdf->GetX() + 10);
			$pdf->Write(0, 'Produk: ' . $data['produksi']->nama_produk);
			$pdf->Ln(4);
			$pdf->SetFont('times', 'B', 7);
			$pdf->Cell(195, 4, 'Stalling', 1, 0, 'L');
			$pdf->Ln();

			$pdf->SetFont('times', '', 7);
			$pdf->Cell(35, 4, 'Jam Mulai', 1, 0, 'L');
			$dataCount = count($chunk);
			$emptyColumns = 4 - $dataCount;

			foreach ($chunk as $item) {
				$jamMulai = date('H:i', strtotime($item->stall_jam_mulai));
				$pdf->Cell(40, 4, $jamMulai, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();

			$pdf->Cell(35, 4, 'Jam Berhenti', 1, 0, 'L');
			foreach ($chunk as $item) {
				$jamBerhenti = date('H:i', strtotime($item->stall_jam_berhenti));
				$pdf->Cell(40, 4, $jamBerhenti, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Kadar Air 32-34(%)', 1, 0, 'L');
			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->stall_kadar_air, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->SetFont('times', 'B', 7);
			$pdf->Cell(195, 4, 'Drying', 1, 0, 'L');
			$pdf->Ln();
			$pdf->SetFont('times', '', 7);

			$pdf->Cell(35, 4, 'Suhu (°C)', 1, 0, 'L');
			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->dry_suhu, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Speed Rotasi (4-6 RPM)', 1, 0, 'L');
			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->dry_rotasi, 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->Cell(35, 4, 'Kadar Air 4-8(%)', 1, 0, 'L');
			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, $item->dry_kadar_air, 1, 0, 'C');
			}
			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C');
			}

			$pdf->Ln();
			$pdf->SetFont('times', 'B', 7);
			$pdf->Cell(195, 4, 'Produk', 1, 0, 'L');
			$pdf->Ln();
			$pdf->SetFont('times', '', 7);
			$pdf->Cell(35, 4, 'Hasil', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 7);    
			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, ($item->produk_hasil == 'oke') ? '✔' : '✘', 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->SetFont('times', '', 7);
			$pdf->Ln();
			$pdf->Cell(35, 4, 'Rasa', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 7);    
			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, ($item->produk_rasa == 'oke') ? '✔' : '✘', 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->SetFont('times', '', 7);
			$pdf->Ln();
			$pdf->Cell(35, 4, 'Aroma', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 7);    
			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, ($item->produk_aroma == 'oke') ? '✔' : '✘', 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C');
			}

			$pdf->SetFont('times', '', 7);
			$pdf->Ln();

			$pdf->Cell(35, 4, 'Tekstur', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 7);    
			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, ($item->produk_tekstur == 'oke') ? '✔' : '✘', 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C');
			}

			$pdf->SetFont('times', '', 7);
			$pdf->Ln();

			$pdf->Cell(35, 4, 'Warna', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 7);    
			foreach ($chunk as $item) {
				$pdf->Cell(40, 4, ($item->produk_warna == 'oke') ? '✔' : '✘', 1, 0, 'C');
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->Ln();
			$pdf->SetFont('times', 'B', 7);
			$pdf->Cell(195, 4, 'Packing Area', 1, 0, 'L');
			$pdf->Ln();

			$pdf->SetFont('times', '', 7);
			$rowHeight = 6;
			$totalHeight = $rowHeight * 3;
			$pdf->MultiCell(35, $rowHeight, 'Nama Produk', 1, 'L', false, 0, '', '', true, 0, false, true, $rowHeight, 'M');
			$pdf->Ln();
			$pdf->MultiCell(35, $rowHeight, 'Kode Kemasan', 1, 'L', false, 0, '', '', true, 0, false, true, $rowHeight, 'M');
			$pdf->Ln();
			$pdf->MultiCell(35, $rowHeight, 'Best Before', 1, 'L', false, 0, '', '', true, 0, false, true, $rowHeight, 'M');
			$pdf->Ln();

			$pdf->SetY($pdf->GetY() - $totalHeight); 
			$pdf->SetX(44); 

			foreach ($chunk as $item) {
				$imagePath = FCPATH . 'uploads/' . $item->gambar_kode_kemasan;
				if (file_exists($imagePath)) {
					$x = $pdf->GetX();
					$y = $pdf->GetY();
					$pdf->MultiCell(40, $totalHeight, '', 1, 'C', false, 0); 
					$pdf->Image($imagePath, $x + 5, $y + 1.5, 30, 14);
				} else {
					$pdf->MultiCell(40, $totalHeight, 'Tidak ada gambar', 1, 'C', false, 0);
				}
			}

			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->MultiCell(40, $totalHeight, '', 1, 'C', false, 0);
			}
			$pdf->Ln();
			$pdf->SetFont('times', '', 7);
			$pdf->Cell(35, 4, 'Kondisi Kemasan', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 7);  
			foreach ($chunk as $item) {
				$kondisi = ($item->packing_kondisi_kemasan == 1) ? '✔' : '✘';
				$pdf->Cell(40, 4, $kondisi, 1, 0, 'C');
			}
			for ($i = 0; $i < $emptyColumns; $i++) {
				$pdf->Cell(40, 4, '', 1, 0, 'C'); 
			}

			$pdf->SetFont('times', 'I', 7);
			$pdf->Cell(330, 5, 'QB 06/00', 0, 1, 'R'); 

			$pdf->Ln();
			$pdf->SetFont('times', '', 7);
			$tanggal_update = $data['produksi']->tgl_update;
			$update = new DateTime($tanggal_update); 
			$update_tanggal = $update->format('d-m-Y | H:i');

			$tanggal_update = $data['produksi']->tgl_update_prod;
			$update_prod = new DateTime($tanggal_update); 
			$update_tanggal_prod = $update_prod->format('d-m-Y | H:i');

			$status_verifikasi = true;
			foreach ($produksi_data as $item) {
				if ($item->status_spv != '1') {
					$status_verifikasi = false;
					break; 
				}
			}

			$pdf->SetY($pdf->GetY() + 2); 
			$pdf->SetFont('dejavusans', '', 5);
			$pdf->MultiCell(0, 7, "✓ : Ok\n✗ : Tidak Ok", 0, 'L');

			$pdf->SetY($pdf->GetY() + 2); 
			$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L'); 
			foreach ($produksi_data as $item) {
				if (!empty($item->catatan)) {
					$pdf->Cell(8, 0, '', 0, 0, 'L'); 
					$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
				}
			}

			// $y_after_keterangan = $pdf->GetY();

			$y_ttd   = $pdf->GetY() + 6;
			$qr_size = 15;

			$qc_usernames  = [];
			$qc_created_at = null;

			foreach ($produksi_data as $item) {
				if (!empty($item->username)) {
					$qc_usernames[] = $item->username;
				}

				if (!$qc_created_at && !empty($item->created_at)) {
					$qc_created_at = $item->created_at;
				}
			}

			$qc_usernames = array_unique($qc_usernames);

			$nama_qc = [];
			foreach ($qc_usernames as $username) {
				$nama = $this->pegawai_model->get_nama_lengkap($username);
				if (!empty($nama)) {
					$nama_qc[] = $nama;
				}
			}

			$qc_nama_text = !empty($nama_qc)
			? implode(', ', array_unique($nama_qc))
			: '-';

			$qc_tanggal = $qc_created_at
			? (new DateTime($qc_created_at))->format('d-m-Y | H:i')
			: '-';

			$qr_qc_text = "Dibuat secara digital oleh,\n"
			. $qc_nama_text . "\n"
			. "QC Inspector\n"
			. $qc_tanggal;

			$qr_produksi_text = null;

			if (!empty($data['produksi']->nama_produksi) && !empty($data['produksi']->tgl_update_prod)) {
				$prod_tanggal = (new DateTime($data['produksi']->tgl_update_prod ?? $data['produksi']->tgl_update_prod))
				->format('d-m-Y | H:i');

				$qr_produksi_text = "Diketahui secara digital oleh,\n"
				. $data['produksi']->nama_produksi . "\n"
				. "Foreman/Forelady Produksi\n"
				. $prod_tanggal;
			}

			$spv_tanggal = !empty($data['produksi']->tgl_update)
			? (new DateTime($data['produksi']->tgl_update))->format('d-m-Y | H:i')
			: '-';

			$qr_spv_text = "Disetujui secara digital oleh,\n"
			. ($nama_lengkap_spv ?: $data['produksi']->nama_spv) . "\n"
			. "Supervisor QC Bread Crumb\n"
			. $spv_tanggal;

			if ($status_verifikasi) {
				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(20, $y_ttd);
				$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');
				$pdf->SetXY(85, $y_ttd);
				$pdf->Cell(45, 5, 'Diketahui Oleh,', 0, 0, 'C');
				$pdf->SetXY(150, $y_ttd);
				$pdf->Cell(45, 5, 'Disetujui Oleh,', 0, 1, 'C');
				$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35,$y_ttd + 5, $qr_size, $qr_size, null, 'N');
				if ($qr_produksi_text) {
					$pdf->write2DBarcode($qr_produksi_text, 'QRCODE,L', 100, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
				}
				$pdf->write2DBarcode($qr_spv_text, 'QRCODE,L', 165, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
				$pdf->SetXY(20, $y_ttd + 20);
				$pdf->Cell(45, 5, 'QC Inspector', 0, 0, 'C');
				$pdf->SetXY(85, $y_ttd + 20);
				$pdf->Cell(45, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
				$pdf->SetXY(150, $y_ttd + 20);
				$pdf->Cell(45, 5, 'Supervisor QC', 0, 1, 'C');
			} else {
				$pdf->SetFont('times', '', 8);
				$pdf->SetTextColor(255, 0, 0);
				$pdf->SetXY(80, $y_ttd);
				$pdf->Cell(80, 6, 'Data Belum Diverifikasi', 0, 1, 'C');
				$pdf->SetTextColor(0, 0, 0);
			}

			$pdf->SetTextColor(0, 0, 0);
			$pdf->setPrintFooter(false);
		}
		$filename = "Verifikasi Produksi_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}

	public function get_produk_by_tanggal()
	{
		$tanggal = $this->input->post('tanggal');
		$produk = $this->produksi_model->get_produk_by_tanggal($tanggal);
		header('Content-Type: application/json');
		echo json_encode($produk);
	}

	public function export_excel()
	{
		$tanggal = $this->input->post('tanggal');
		$produk  = $this->input->post('nama_produk');

		if (!$tanggal || !$produk) {
			show_error('Tanggal dan Produk harus dipilih.');
		}

		$this->load->model('produksi_model');
		$data_mixing = $this->produksi_model->get_data_by_tanggal_produk($tanggal, $produk);

		if (empty($data_mixing)) {
			show_error('Data produksi tidak ditemukan.');
		}

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setTitle('Data Produksi');
		$sheet->mergeCells('A1:E1');
		$sheet->setCellValue('A1', "Laporan Produksi\nTanggal: " . date('d-m-Y', strtotime($tanggal)) . " Produk: " . $produk);
		$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		$headers = ['Tanggal', 'Nama Produk', 'Kode Produksi', 'Shift',];
		$col = 'A';
		foreach ($headers as $header) {
			$sheet->setCellValue($col . '3', $header);
			$sheet->getStyle($col . '3')->getFont()->setBold(true);
			$sheet->getStyle($col . '3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$col++;
		}

		$row = 4;
		foreach ($data_mixing as $item) {
			$sheet->setCellValue('A' . $row, $item->date);
			$sheet->setCellValue('B' . $row, $item->nama_produk);
			$sheet->setCellValue('C' . $row, $item->kode_produksi);
			$sheet->setCellValue('D' . $row, $item->shift);
			$row++;
		}

		foreach (range('A', 'E') as $columnID) {
			$sheet->getColumnDimension($columnID)->setAutoSize(true);
		}

		$lastRow = $row - 1;
		$sheet->getStyle("A3:E{$lastRow}")->applyFromArray([
			'borders' => [
				'allBorders' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['argb' => 'FF000000'],
				],
			],
		]);

		$sheet->freezePane('A4');

		$filename = "Laporan_Produksi_{$tanggal}_{$produk}.xlsx";

		ob_end_clean();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}


}

