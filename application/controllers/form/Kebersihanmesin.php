<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kebersihanmesin extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');  
		$this->load->model('kebersihanmesin_model');
		$this->load->library('upload');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'kebersihanmesin' => $this->kebersihanmesin_model->get_data_by_plant()
		);

		$this->active_nav = 'kebersihanmesin'; 
		$this->render('form/kebersihanmesin/kebersihanmesin', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'kebersihanmesin' => $this->kebersihanmesin_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'kebersihanmesin'; 
		$this->render('form/kebersihanmesin/kebersihanmesin-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->kebersihanmesin_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->kebersihanmesin_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Kebersihan dan Sanitasi setelah Perbaikan Mesin berhasil di simpan');
				redirect('kebersihanmesin');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Kebersihan dan Sanitasi setelah Perbaikan Mesin gagal di simpan');
				redirect('kebersihanmesin');
			}
		}

		$this->active_nav = 'kebersihanmesin'; 
		$this->render('form/kebersihanmesin/kebersihanmesin-tambah');
	}

	public function edit($uuid)
	{
		$rules = $this->kebersihanmesin_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$update = $this->kebersihanmesin_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Kebersihan dan Sanitasi setelah Perbaikan Mesin berhasil diupdate.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Kebersihan dan Sanitasi setelah Perbaikan Mesin gagal diupdate.');
			}

			redirect('kebersihanmesin');
		}

		$data = [
			'kebersihanmesin' => $this->kebersihanmesin_model->get_by_uuid($uuid)
		];

		$this->active_nav = 'kebersihanmesin'; 
		$this->render('form/kebersihanmesin/kebersihanmesin-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('kebersihanmesin');
		}

		$deleted = $this->kebersihanmesin_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('kebersihanmesin');
	}

	public function verifikasi()
	{
		$data = array(
			'kebersihanmesin' => $this->kebersihanmesin_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-kebersihanmesin'; 
		$this->render('form/kebersihanmesin/kebersihanmesin-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->kebersihanmesin_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$update = $this->kebersihanmesin_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Kebersihan dan Sanitasi setelah Perbaikan Mesin berhasil di Update');
				redirect('kebersihanmesin/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Kebersihan dan Sanitasi setelah Perbaikan Mesin gagal di Update');
				redirect('kebersihanmesin/verifikasi');
			}
		}

		$data = array(
			'kebersihanmesin' => $this->kebersihanmesin_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'verifikasi-kebersihanmesin'; 
		$this->render('form/kebersihanmesin/kebersihanmesin-status', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'kebersihanmesin' => $this->kebersihanmesin_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-kebersihanmesin', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/kebersihanmesin/kebersihanmesin-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }

	// public function statusprod($uuid)
	// {
	// 	$rules = $this->kebersihanmesin_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {
	// 		$update = $this->kebersihanmesin_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Kebersihan dan Sanitasi setelah Perbaikan Mesin berhasil di Update');
	// 			redirect('kebersihanmesin/diketahui');
	// 		} else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Kebersihan dan Sanitasi setelah Perbaikan Mesin gagal di Update');
	// 			redirect('kebersihanmesin/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'kebersihanmesin' => $this->kebersihanmesin_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-kebersihanmesin'
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/kebersihanmesin/kebersihanmesin-statusprod', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal');  
		$shift   = $this->input->post('shift'); 

		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$kebersihanmesin_data = $this->kebersihanmesin_model->get_by_date($tanggal, $plant, $shift); 
		$kebersihanmesin_data_verif = $this->kebersihanmesin_model->get_last_verif_by_date($tanggal, $plant, $shift); 

		if (!$kebersihanmesin_data || !$kebersihanmesin_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['kebersihanmesin'] = $kebersihanmesin_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false); 
		$pdf->SetMargins(17, 16, 15); 
		$pdf->AddPage('L', 'LEGAL');
		$pdf->SetFont('times', 'B', 14);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 17, 14, 38);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(9, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN KEBERSIHAN DAN SANITASI SETELAH PERBAIKAN MESIN', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['kebersihanmesin']->date;
		$date = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $date->getTimestamp());

		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(16);
		$pdf->Write(0, 'Hari / Tanggal : ' . $formatted_date);
		$pdf->Ln();
		$pdf->SetX(16);
		$pdf->Write(0, 'Shift : ' . $data['kebersihanmesin']->shift);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 11);

		$pdf->Cell(50, 10, 'Mesin / Peralatan', 1, 0, 'C');
		$pdf->Cell(60, 10, 'Jenis Perbaikan', 1, 0, 'C');
		$pdf->Cell(30, 10, 'Area', 1, 0, 'C');
		$pdf->Cell(30, 10, 'Tanggal Perbaikan', 1, 0, 'C');
		$pdf->Cell(40, 5, 'Kondisi', 1, 0, 'C');
		$pdf->Cell(50, 5, 'Spare Part yang Tertinggal', 1, 0, 'C');
		$pdf->Cell(60, 10, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$pdf->Cell(170, 0, '', 0, 0, 'L');
		$pdf->Cell(20, 4, 'Bersih', 1, 0, 'C');   
		$pdf->Cell(20, 4, 'Kotor', 1, 0, 'C');  
		$pdf->Cell(25, 4, 'Ada', 1, 0, 'C');    
		$pdf->Cell(25, 4, 'Tidak Ada', 1, 0, 'C'); 

		$pdf->Cell(10, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 4, '', 0, 1, 'C');

		$pdf->SetFont('times', '', 10);
		foreach ($kebersihanmesin_data as $kebersihanmesin) {
			$tanggal = $kebersihanmesin->tgl_perbaikan;
			$repaired = new DateTime($tanggal); 
			$repaired = $repaired->format('d-m-Y');

			$pdf->Cell(50, 8, $kebersihanmesin->mesin, 1, 0, 'L');
			$pdf->Cell(60, 8, $kebersihanmesin->perbaikan, 1, 0, 'L');
			$pdf->Cell(30, 8, $kebersihanmesin->area, 1, 0, 'C');
			$pdf->Cell(30, 8, $repaired, 1, 0, 'C');
			$pdf->SetFont('dejavusans', '', 10);
			$kondisi_1 = '';
			$kondisi_2 = '';

			if ($kebersihanmesin->kondisi == 'Bersih') {
				$kondisi_1 = '✓';
				$kondisi_2 = '';
			} elseif ($kebersihanmesin->kondisi == 'Kotor') {
				$kondisi_1 = '';
				$kondisi_2 = '✗';
			}

			$pdf->Cell(20, 8, $kondisi_1, 1, 0, 'C');
			$pdf->Cell(20, 8, $kondisi_2, 1, 0, 'C');

			$spare_part_ada = '';
			$spare_part_tidak_ada = '';

			if ($kebersihanmesin->spare_part == 'Ada') {
				$spare_part_ada = '✓';
				$spare_part_tidak_ada = '';
			} elseif ($kebersihanmesin->spare_part == 'Tidak Ada') {
				$spare_part_ada = '';
				$spare_part_tidak_ada = '✗';
			}

			$pdf->Cell(25, 8, $spare_part_ada, 1, 0, 'C');
			$pdf->Cell(25, 8, $spare_part_tidak_ada, 1, 0, 'C');


			$pdf->SetFont('times', '', 10);
			$pdf->Cell(60, 8, $kebersihanmesin->keterangan, 1, 0, 'L');
			$pdf->Ln();
		}

		$this->load->model('pegawai_model');
		$data['kebersihanmesin']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['kebersihanmesin']->username);
		$data['kebersihanmesin']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['kebersihanmesin']->nama_spv);
		$data['kebersihanmesin']->nama_lengkap_produksi = $data['kebersihanmesin']->nama_produksi;

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($kebersihanmesin_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;

		// Dibuat oleh (QC)
			$pdf->SetXY(25, $y_verifikasi + 5);
			$pdf->Cell(95, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(25, $y_verifikasi + 10);
			$pdf->SetFont('times', 'U', 8); // underline
			$pdf->Cell(95, 5, $data['kebersihanmesin']->nama_lengkap_qc, 0, 1, 'C');
			$pdf->SetFont('times', '', 8); 
			$pdf->Cell(112, 5, 'QC Inspector', 0, 0, 'C');

		// // Diketahui oleh (Produksi)
		// 	$pdf->SetXY(90, $y_verifikasi + 5);
		// 	$pdf->Cell(135, 5, 'Diketahui Oleh,', 0, 0, 'C');
		// 	if ($data['kebersihanmesin']->status_produksi == 1 && !empty($data['kebersihanmesin']->nama_produksi)) {
		// 		$update_tanggal_produksi = (new DateTime($data['kebersihanmesin']->tgl_update_produksi))->format('d-m-Y | H:i');
		// 		$qr_text_produksi = "Diketahui secara digital oleh,\n" . $data['kebersihanmesin']->nama_lengkap_produksi . "\nForeman/Forelady Produksi\n" . $update_tanggal_produksi;
		// 		$pdf->write2DBarcode($qr_text_produksi, 'QRCODE,L', 150, $y_verifikasi + 10, 15, 15, null, 'N');
		// 		$pdf->SetXY(90, $y_verifikasi + 24);
		// 		$pdf->Cell(135, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
		// 	} else {
		// 		$pdf->SetXY(90, $y_verifikasi + 10);
		// 		$pdf->Cell(135, 5, 'Belum Diverifikasi', 0, 0, 'C');
		// 	}

// Diketahui oleh (Produksi) - tanpa barcode
			$pdf->SetXY(90, $y_verifikasi + 5);
			$pdf->Cell(135, 5, 'Diketahui Oleh,', 0, 0, 'C');

			if ($data['kebersihanmesin']->status_produksi == 1 && !empty($data['kebersihanmesin']->nama_produksi)) {
				$update_tanggal_produksi = (new DateTime($data['kebersihanmesin']->tgl_update_produksi))->format('d-m-Y | H:i');

				$pdf->SetFont('times', 'U', 8);
				$pdf->SetXY(90, $y_verifikasi + 10);
				$pdf->Cell(135, 5, $data['kebersihanmesin']->nama_produksi, 0, 1, 'C');

				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(90, $y_verifikasi + 15);
				$pdf->Cell(135, 5, 'Foreman/Forelady Produksi', 0, 1, 'C');

				// $pdf->SetXY(90, $y_verifikasi + 20);
				// $pdf->Cell(135, 5, $update_tanggal_produksi, 0, 0, 'C');
			} else {
				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(90, $y_verifikasi + 10);
				$pdf->Cell(135, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}

		// Disetujui oleh (SPV)
			$pdf->SetXY(150, $y_verifikasi + 5);
			$pdf->Cell(189, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$update_tanggal = (new DateTime($data['kebersihanmesin']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['kebersihanmesin']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 237, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(170, $y_verifikasi + 24);
			$pdf->Cell(149, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0); 
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(200, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$filename = "Pemeriksaan Perbaikan Mesin_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');

	}
}

