<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;
setlocale(LC_TIME, 'id_ID.UTF-8');

class Ketidaksesuaian extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model'); 
		$this->load->model('ketidaksesuaian_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'ketidaksesuaian' => $this->ketidaksesuaian_model->get_data_by_plant()
		);

		$this->active_nav = 'ketidaksesuaian'; 
		$this->render('form/ketidaksesuaian/ketidaksesuaian', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'ketidaksesuaian' => $this->ketidaksesuaian_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'ketidaksesuaian'; 
		$this->render('form/ketidaksesuaian/ketidaksesuaian-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->ketidaksesuaian_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->ketidaksesuaian_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Ketidaksesuaian Produk berhasil di simpan');
				redirect('ketidaksesuaian');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Ketidaksesuaian Produk gagal di simpan');
				redirect('ketidaksesuaian');
			}
		}

		$this->active_nav = 'ketidaksesuaian'; 
		$this->render('form/ketidaksesuaian/ketidaksesuaian-tambah');
	}

	public function edit($uuid)
	{
		$rules = $this->ketidaksesuaian_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			
			$update = $this->ketidaksesuaian_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Ketidaksesuaian Produk berhasil di Update');
				redirect('ketidaksesuaian');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Ketidaksesuaian Produk gagal di Update');
				redirect('ketidaksesuaian');
			}
		}

		$data = array(
			'ketidaksesuaian' => $this->ketidaksesuaian_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'ketidaksesuaian'; 
		$this->render('form/ketidaksesuaian/ketidaksesuaian-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('ketidaksesuaian');
		}

		$deleted = $this->ketidaksesuaian_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('ketidaksesuaian');
	}
	
	public function verifikasi()
	{
		$data = array(
			'ketidaksesuaian' => $this->ketidaksesuaian_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-ketidaksesuaian'; 
		$this->render('form/ketidaksesuaian/ketidaksesuaian-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->ketidaksesuaian_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->ketidaksesuaian_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Ketidaksesuaian Produk berhasil di Update');
				redirect('ketidaksesuaian/verifikasi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Ketidaksesuaian Produk gagal di Update');
				redirect('ketidaksesuaian/verifikasi');
			}
		}

		$data = array(
			'ketidaksesuaian' => $this->ketidaksesuaian_model->get_by_uuid($uuid),
		);
		
		$this->active_nav = 'ketidaksesuaian'; 
		$this->render('form/ketidaksesuaian/ketidaksesuaian-status', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'ketidaksesuaian' => $this->ketidaksesuaian_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-ketidaksesuaian', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/ketidaksesuaian/ketidaksesuaian-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->ketidaksesuaian_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->ketidaksesuaian_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Ketidaksesuaian Produk berhasil di Update');
	// 			redirect('ketidaksesuaian/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Ketidaksesuaian Produk gagal di Update');
	// 			redirect('ketidaksesuaian/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'ketidaksesuaian' => $this->ketidaksesuaian_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-ketidaksesuaian');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/ketidaksesuaian/ketidaksesuaian-statusprod', $data);
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

		$ketidaksesuaian_data = $this->ketidaksesuaian_model->get_by_date($tanggal, $plant, $shift); 
		$ketidaksesuaian_data_verif = $this->ketidaksesuaian_model->get_last_verif_by_date($tanggal, $plant, $shift); 

		if (!$ketidaksesuaian_data || !$ketidaksesuaian_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['ketidaksesuaian'] = $ketidaksesuaian_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false); 
		$pdf->SetMargins(17, 16, 15); 
		$pdf->AddPage('L', 'LEGAL');
		$pdf->SetFont('times', 'B', 14);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 17, 14, 45);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(10, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN KETIDAKSESUAIAN PROSES PRODUKSI', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['ketidaksesuaian']->date;
		$date = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $date->getTimestamp());

		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(16);
		$pdf->Write(0, 'Hari / Tanggal : ' . $formatted_date);
		$pdf->SetX($pdf->GetX() + 20);
		$pdf->Write(0, 'Shift: ' . $data['ketidaksesuaian']->shift);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 11);

		$pdf->Cell(10, 10, 'No.', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Jam', 1, 0, 'C');
		$pdf->Cell(35, 10, 'Nama Produk', 1, 0, 'C');
		$pdf->Cell(50, 10, 'Uraian Ketidaksesuaian', 1, 0, 'C');
		$pdf->Cell(15, 10, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(50, 10, 'Analisis Penyebab', 1, 0, 'C');
		$pdf->Cell(50, 10, 'Tindakan / Disposisi', 1, 0, 'C');
		$pdf->Cell(50, 10, 'Verifikasi', 1, 0, 'C');
		$pdf->Cell(40, 4, 'Paraf', 1, 1, 'C');

		$pdf->Cell(130, 10, '', 0, 0, 'C');  
		$pdf->Cell(50, 6, '/ Kategori Bahaya', 0, 0, 'C');
		$pdf->Cell(100, 10, '', 0, 0, 'C'); 
		$pdf->Cell(20, 4, 'QC', 1, 0, 'C');  
		$pdf->Cell(20, 4, 'Produksi', 1, 1, 'C'); 

		$no = 1;
		foreach ($ketidaksesuaian_data as $ketidaksesuaian) {
			$pdf->SetFont('times', '', 10);

			$created_time = (new DateTime($ketidaksesuaian->waktu))->format('H:i');

			$cols = [
				$no,
				$created_time,
				$ketidaksesuaian->nama_produk,
				$ketidaksesuaian->ketidaksesuaian,
				$ketidaksesuaian->jumlah,
				$ketidaksesuaian->penyebab,
				$ketidaksesuaian->tindakan,
				$ketidaksesuaian->verifikasi,
				$ketidaksesuaian->username,
				$ketidaksesuaian->nama_produksi
			];

			$widths = [10, 20, 35, 50, 15, 50, 50, 50, 20, 20];
			$lineHeight = 5;
			$nbLines = [];

			foreach ($cols as $i => $text) {
				$nbLines[$i] = $pdf->getNumLines($text, $widths[$i]);
			}

			$maxLines = max($nbLines);
			$rowHeight = $lineHeight * $maxLines;

			$x = $pdf->GetX();
			$y = $pdf->GetY();

			foreach ($cols as $i => $text) {
				$pdf->MultiCell($widths[$i], $rowHeight, $text, 1, 'C', false, 0, $x, $y, true, 0, false, true, $rowHeight, 'M');
				$x += $widths[$i];
			}

			$pdf->Ln($rowHeight);
			$no++;
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(190, 5, 'QB 17/00', 0, 1, 'R'); 

		$pdf->SetY($pdf->GetY() + 3); 
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(10, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($ketidaksesuaian_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L'); 
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$this->load->model('pegawai_model');
		$data['ketidaksesuaian']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['ketidaksesuaian']->username);
		$data['ketidaksesuaian']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['ketidaksesuaian']->nama_spv);
		$data['ketidaksesuaian']->nama_lengkap_produksi = $data['ketidaksesuaian']->nama_produksi;

		$y_after_keterangan = $pdf->GetY();
		$status_verifikasi = true;
		foreach ($ketidaksesuaian_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		// if ($status_verifikasi) {
		// 	$y_verifikasi = $y_after_keterangan;

		// // Dibuat oleh (QC)
		// 	$pdf->SetXY(25, $y_verifikasi + 5);
		// 	$pdf->Cell(95, 5, 'Dibuat Oleh,', 0, 0, 'C');
		// 	$pdf->SetXY(25, $y_verifikasi + 10);
		// 	$pdf->SetFont('times', 'U', 8); 
		// 	$pdf->Cell(95, 5, $data['ketidaksesuaian']->nama_lengkap_qc, 0, 1, 'C');
		// 	$pdf->SetFont('times', '', 8); 
		// 	$pdf->Cell(112, 5, 'QC Inspector', 0, 0, 'C');

		// // // Diketahui oleh (Produksi)
		// // 	$pdf->SetXY(90, $y_verifikasi + 5);
		// // 	$pdf->Cell(135, 5, 'Diketahui Oleh,', 0, 0, 'C');
		// // 	if ($data['ketidaksesuaian']->status_produksi == 1 && !empty($data['ketidaksesuaian']->nama_produksi)) {
		// // 		$update_tanggal_produksi = (new DateTime($data['ketidaksesuaian']->tgl_update_produksi))->format('d-m-Y | H:i');
		// // 		$qr_text_produksi = "Diketahui secara digital oleh,\n" . $data['ketidaksesuaian']->nama_lengkap_produksi . "\nForeman/Forelady Produksi\n" . $update_tanggal_produksi;
		// // 		$pdf->write2DBarcode($qr_text_produksi, 'QRCODE,L', 150, $y_verifikasi + 10, 15, 15, null, 'N');
		// // 		$pdf->SetXY(90, $y_verifikasi + 24);
		// // 		$pdf->Cell(135, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
		// // 	} else {
		// // 		$pdf->SetXY(90, $y_verifikasi + 10);
		// // 		$pdf->Cell(135, 5, 'Belum Diverifikasi', 0, 0, 'C');
		// // 	}

		// 	// Diketahui oleh (Produksi) - tanpa barcode
		// 	$pdf->SetXY(90, $y_verifikasi + 5);
		// 	$pdf->Cell(135, 5, 'Diketahui Oleh,', 0, 0, 'C');

		// 	if ($data['ketidaksesuaian']->status_produksi == 1 && !empty($data['ketidaksesuaian']->nama_produksi)) {
		// 		$update_tanggal_produksi = (new DateTime($data['ketidaksesuaian']->tgl_update_produksi))->format('d-m-Y | H:i');

		// 		$pdf->SetFont('times', 'U', 8);
		// 		$pdf->SetXY(90, $y_verifikasi + 10);
		// 		$pdf->Cell(135, 5, $data['ketidaksesuaian']->nama_produksi, 0, 1, 'C');

		// 		$pdf->SetFont('times', '', 8);
		// 		$pdf->SetXY(90, $y_verifikasi + 15);
		// 		$pdf->Cell(135, 5, 'Foreman/Forelady Produksi', 0, 1, 'C');

		// 		// $pdf->SetXY(90, $y_verifikasi + 20);
		// 		// $pdf->Cell(135, 5, $update_tanggal_produksi, 0, 0, 'C');
		// 	} else {
		// 		$pdf->SetFont('times', '', 8);
		// 		$pdf->SetXY(90, $y_verifikasi + 10);
		// 		$pdf->Cell(135, 5, 'Belum Diverifikasi', 0, 0, 'C');
		// 	}


		// // Disetujui oleh (SPV)
		// 	$pdf->SetXY(150, $y_verifikasi + 5);
		// 	$pdf->Cell(189, 5, 'Disetujui Oleh,', 0, 0, 'C');
		// 	$update_tanggal = (new DateTime($data['ketidaksesuaian']->tgl_update_spv))->format('d-m-Y | H:i');
		// 	$qr_text = "Diverifikasi secara digital oleh,\n" . $data['ketidaksesuaian']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
		// 	$pdf->write2DBarcode($qr_text, 'QRCODE,L', 237, $y_verifikasi + 10, 15, 15, null, 'N');
		// 	$pdf->SetXY(170, $y_verifikasi + 24);
		// 	$pdf->Cell(149, 5, 'Supervisor QC', 0, 0, 'C');
		// } else {
		// 	$pdf->SetTextColor(255, 0, 0); 
		// 	$pdf->SetFont('times', '', 8);
		// 	$pdf->SetXY(200, $y_after_keterangan);
		// 	$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		// }

		$y_ttd   = $pdf->GetY() + 6;
		$qr_size = 15;

		$qc_usernames  = [];
		$qc_created_at = null;

		foreach ($ketidaksesuaian_data as $item) {
			if (!empty($item->username)) {
				$qc_usernames[] = $item->username;
			}

			if (!$qc_created_at && !empty($item->created_at)) {
				$qc_created_at = $item->created_at;
			}
		}

		$qc_usernames = array_unique($qc_usernames);

		$qc_nama_lengkap = [];
		foreach ($qc_usernames as $username) {
			$nama = $this->pegawai_model->get_nama_lengkap($username);
			if (!empty($nama)) {
				$qc_nama_lengkap[] = $nama;
			}
		}

		$qc_nama_text = !empty($qc_nama_lengkap)
		? implode(', ', array_unique($qc_nama_lengkap))
		: '-';

		$qc_tanggal = $qc_created_at
		? (new DateTime($qc_created_at))->format('d-m-Y | H:i')
		: '-';

		$qr_qc_text = "Dibuat secara digital oleh,\n"
		. $qc_nama_text . "\n"
		. "QC Inspector\n"
		. $qc_tanggal;

		$qr_produksi_text = null;

		if (!empty($data['ketidaksesuaian']->nama_lengkap_produksi) && !empty($data['ketidaksesuaian']->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($data['ketidaksesuaian']->tgl_update_produksi ?? $data['ketidaksesuaian']->tgl_update_produksi))
			->format('d-m-Y | H:i');

			$qr_produksi_text = "Diketahui secara digital oleh,\n"
			. $data['ketidaksesuaian']->nama_lengkap_produksi . "\n"
			. "Foreman/Forelady Produksi\n"
			. $prod_tanggal;
		}

		$spv_tanggal = !empty($data['ketidaksesuaian']->tgl_update_spv)
		? (new DateTime($data['ketidaksesuaian']->tgl_update_spv))->format('d-m-Y | H:i')
		: '-';

		$qr_spv_text = "Disetujui secara digital oleh,\n"
		. $data['ketidaksesuaian']->nama_lengkap_spv . "\n"
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
		

		$pdf->setPrintFooter(false);
		$filename = "Ketidaksesuaian Produk_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');

	}
}

