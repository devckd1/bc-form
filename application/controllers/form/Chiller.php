<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
setlocale(LC_TIME, 'id_ID.UTF-8');

class Chiller extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model'); 
		$this->load->model('chiller_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'chiller' => $this->chiller_model->get_chiller_by_plant()
		);

		$this->active_nav = 'chiller'; 
		$this->render('form/chiller/chiller', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'chiller' => $this->chiller_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'chiller'; 
		$this->render('form/chiller/chiller-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->chiller_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->chiller_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Suhu Chiller berhasil di simpan');
				redirect('chiller');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Suhu Chiller gagal di simpan');
				redirect('chiller');
			}
		}

		$this->active_nav = 'chiller'; 
		$this->render('form/chiller/chiller-tambah');
	}

	public function edit($uuid)
	{
		$rules = $this->chiller_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->chiller_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Suhu Chiller berhasil di Update');
				redirect('chiller');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Suhu Chiller gagal di Update');
				redirect('chiller');
			}
		}

		$data = array(
			'chiller' => $this->chiller_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'chiller'; 
		$this->render('form/chiller/chiller-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('chiller');
		}

		$deleted = $this->chiller_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('chiller');
	}

	public function verifikasi()
	{
		$data = array(
			'chiller' => $this->chiller_model->get_chiller_by_plant()
		);

		$this->active_nav = 'verifikasi-chiller'; 
		$this->render('form/chiller/chiller-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->chiller_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->chiller_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Suhu Chiller berhasil di Update');
				redirect('chiller/verifikasi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Suhu Chiller gagal di Update');
				redirect('chiller/verifikasi');
			}
		}

		$data = array(
			'chiller' => $this->chiller_model->get_by_uuid($uuid),
			'active_nav' => 'verifikasi-chiller');

		$this->load->view('partials/head', $data);
		$this->load->view('form/chiller/chiller-status', $data);
		$this->load->view('partials/footer');
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'chiller' => $this->chiller_model->get_chiller_by_plant(),
	// 		'active_nav' => 'diketahui-chiller', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/chiller/chiller-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->chiller_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->chiller_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Suhu Chiller berhasil di Update');
	// 			redirect('chiller/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Suhu Chiller gagal di Update');
	// 			redirect('chiller/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'chiller' => $this->chiller_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-chiller');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/chiller/chiller-statusprod', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal');  

		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$chiller_data = $this->chiller_model->get_by_date($tanggal, $plant); 
		$chiller_data_verif = $this->chiller_model->get_last_verif_by_date($tanggal, $plant); 

		if (!$chiller_data || !$chiller_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['chiller'] = $chiller_data_verif;

		$this->load->model('pegawai_model');
		$data['chiller']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['chiller']->username);
		$data['chiller']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['chiller']->nama_spv);
		$data['chiller']->nama_lengkap_produksi = $data['chiller']->nama_produksi;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false); 
		$pdf->SetMargins(10, 14, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 12);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 35);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(7, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN SUHU CHILLER', 0, 'C');
		$pdf->Ln(4);

		setlocale(LC_TIME, 'IND');
		$tanggal = $data['chiller']->date;
		$datetime = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $date->getTimestamp());
		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 9);
		$pdf->SetX(10);
		$pdf->Cell(190, 6, 'Hari / Tanggal: ' . $formatted_date, 1, 1);	

		$pdf->SetFont('times', '', 9);

		$pdf->Cell(20, 6, 'Pukul', 1, 0, 'C');
		$pdf->Cell(30, 6, 'Chiller 1', 1, 0, 'C');
		$pdf->Cell(30, 6, 'Chiller 2', 1, 0, 'C');
		$pdf->Cell(30, 6, 'Chiller 3', 1, 0, 'C');
		$pdf->Cell(30, 6, 'Chiller 4', 1, 0, 'C');
		$pdf->Cell(50, 6, 'Paraf', 1, 1, 'C');

		$pdf->Cell(20, 8, 'STD', 1, 0, 'C');
		$pdf->Cell(120, 8, '0 - 4°C', 1, 0, 'C');   
		$pdf->Cell(25, 8, 'QC', 1, 0, 'C');  
		$pdf->Cell(25, 8, 'Produksi', 1, 1, 'C'); 

		foreach ($chiller_data as $chiller) {
			$time = $chiller->waktu;
			$time2 = new DateTime($time); 
			$created_time = $time2->format('H:i');

			$pdf->Cell(20, 5, $created_time, 1, 0, 'C');
			$pdf->Cell(30, 5, $chiller->chiller_1, 1, 0, 'C');
			$pdf->Cell(30, 5, $chiller->chiller_2, 1, 0, 'C');
			$pdf->Cell(30, 5, $chiller->chiller_3, 1, 0, 'C');
			$pdf->Cell(30, 5, $chiller->chiller_4, 1, 0, 'C');
			$pdf->Cell(25, 5, $chiller->username, 1, 0, 'C');
			$pdf->Cell(25, 5, $chiller->nama_produksi, 1, 0, 'C');
			$pdf->Ln();
		}  

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(190, 5, 'QB 23/00', 0, 1, 'R'); 

		$pdf->SetY($pdf->GetY() + 2); 
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($chiller_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(13, 0, '', 0, 0, 'L'); 
				$pdf->Cell(13, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($chiller_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		// if ($status_verifikasi) {
		// 	$y_verifikasi = $y_after_keterangan;
		// 	$pdf->SetXY(25, $y_verifikasi + 5);
		// 	$pdf->Cell(35, 5, 'Dibuat Oleh,', 0, 0, 'C');
		// 	if (!empty($data['chiller']->nama_lengkap_qc)) {
		// 		$update_tanggal_qc = !empty($data['chiller']->created_at)
		// 		? (new DateTime($data['chiller']->created_at))->format('d-m-Y | H:i')
		// 		: date('d-m-Y | H:i'); 

		// 		$qr_text_qc = "Dibuat secara digital oleh,\n" .
		// 		$data['chiller']->nama_lengkap_qc . "\nQC Inspector\n" . $update_tanggal_qc;
		// 		$pdf->write2DBarcode($qr_text_qc, 'QRCODE,L', 35, $y_verifikasi + 10, 15, 15, null, 'N');
		// 		$pdf->SetXY(25, $y_verifikasi + 24);
		// 		$pdf->Cell(35, 5, 'QC Inspector', 0, 0, 'C');
		// 	} else {
		// 		$pdf->SetXY(25, $y_verifikasi + 10);
		// 		$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
		// 	}

		// 	// Diketahui oleh (Produksi)
		// 	$pdf->SetXY(90, $y_verifikasi + 5);
		// 	$pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');
		// 	if ($data['chiller']->status_produksi == 1 && !empty($data['chiller']->nama_produksi)) {
		// 		$update_tanggal_produksi = (new DateTime($data['chiller']->tgl_update_produksi))->format('d-m-Y | H:i');
		// 		$qr_text_produksi = "Diketahui secara digital oleh,\n" . $data['chiller']->nama_produksi . "\nForeman/Forelady Produksi\n" . $update_tanggal_produksi;
		// 		$pdf->write2DBarcode($qr_text_produksi, 'QRCODE,L', 100, $y_verifikasi + 10, 15, 15, null, 'N');
		// 		$pdf->SetXY(90, $y_verifikasi + 24);
		// 		$pdf->Cell(35, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
		// 	} else {
		// 		$pdf->SetXY(90, $y_verifikasi + 10);
		// 		$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
		// 	}

		// // Disetujui oleh (SPV)
		// 	$pdf->SetXY(150, $y_verifikasi + 5);
		// 	$pdf->Cell(49, 5, 'Disetujui Oleh,', 0, 0, 'C');
		// 	$update_tanggal = (new DateTime($data['chiller']->tgl_update_spv))->format('d-m-Y | H:i');
		// 	$qr_text = "Diverifikasi secara digital oleh,\n" . $data['chiller']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
		// 	$pdf->write2DBarcode($qr_text, 'QRCODE,L', 167, $y_verifikasi + 10, 15, 15, null, 'N');
		// 	$pdf->SetXY(150, $y_verifikasi + 24);
		// 	$pdf->Cell(49, 5, 'Supervisor QC', 0, 0, 'C');
		// } else {
		// 	$pdf->SetTextColor(255, 0, 0); 
		// 	$pdf->SetFont('times', '', 8);
		// 	$pdf->SetXY(100, $y_after_keterangan);
		// 	$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		// }

		$y_ttd   = $pdf->GetY() + 6;
		$qr_size = 15;

		$qc_usernames  = [];
		$qc_created_at = null;

		foreach ($chiller_data as $item) {
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

		if (!empty($data['chiller']->nama_lengkap_produksi) && !empty($data['chiller']->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($data['chiller']->tgl_update_produksi ?? $data['chiller']->tgl_update_produksi))
			->format('d-m-Y | H:i');

			$qr_produksi_text = "Diketahui secara digital oleh,\n"
			. $data['chiller']->nama_lengkap_produksi . "\n"
			. "Foreman/Forelady Produksi\n"
			. $prod_tanggal;
		}

		$spv_tanggal = !empty($data['chiller']->tgl_update_spv)
		? (new DateTime($data['chiller']->tgl_update_spv))->format('d-m-Y | H:i')
		: '-';

		$qr_spv_text = "Disetujui secara digital oleh,\n"
		. $data['chiller']->nama_lengkap_spv . "\n"
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
		$filename = "Chiller_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}

	public function export_excel()
	{
		$tanggal = $this->input->post('tanggal');  

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$chiller_data = $this->chiller_model->get_by_date($tanggal, $plant); 
		$chiller_data_verif = $this->chiller_model->get_last_verif_by_date($tanggal, $plant); 

		if (!$chiller_data || !$chiller_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['chiller'] = $chiller_data_verif;

		ob_clean();
		ob_start();

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

    // Judul
		$sheet->mergeCells('A1:G1');
		$sheet->setCellValue('A1', 'PEMERIKSAAN SUHU CHILLER');
		$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
		$sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

    // Format tanggal
		$datetime = new DateTime($data['chiller']->date);
		$formatted_date = date('l, d F Y', $date->getTimestamp());

		$sheet->setCellValue('A3', 'Hari / Tanggal: ' . $formatted_date);

    // Header tabel
		$sheet->setCellValue('A5', 'Pukul');
		$sheet->setCellValue('B5', 'Chiller 1');
		$sheet->setCellValue('C5', 'Chiller 2');
		$sheet->setCellValue('D5', 'Chiller 3');
		$sheet->setCellValue('E5', 'Chiller 4');
		$sheet->setCellValue('F5', 'Paraf QC');
		$sheet->setCellValue('G5', 'Paraf Produksi');

		$sheet->getStyle('A5:G5')->getFont()->setBold(true);
		$sheet->getStyle('A5:G5')->getAlignment()->setHorizontal('center');

    // STD row
		$sheet->setCellValue('A6', 'STD');
		$sheet->mergeCells('B6:E6');
		$sheet->setCellValue('B6', '0 - 5°C');
		$sheet->setCellValue('F6', 'QC');
		$sheet->setCellValue('G6', 'Produksi');

	// Data
		$row = 7;
		foreach ($chiller_data as $chiller) {
			$time2 = new DateTime($chiller->waktu); 
			$created_time = $time2->format('H:i');

			$sheet->setCellValue('A' . $row, $created_time);
			$sheet->setCellValue('B' . $row, $chiller->chiller_1);
			$sheet->setCellValue('C' . $row, $chiller->chiller_2);
			$sheet->setCellValue('D' . $row, $chiller->chiller_3);
			$sheet->setCellValue('E' . $row, $chiller->chiller_4);
			$sheet->setCellValue('F' . $row, $chiller->username);
			$sheet->setCellValue('G' . $row, $chiller->nama_produksi);

			$row++;
		}

// kasih border untuk seluruh tabel dari header sampai data terakhir
		$lastRow = $row - 1;
		$sheet->getStyle("A5:G{$lastRow}")
		->getBorders()
		->getAllBorders()
		->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

		$sheet->getStyle("A5:G{$lastRow}")
		->getAlignment()
		->setHorizontal('center');


		$row += 2;
		$sheet->setCellValue('A' . $row, 'Catatan:');
		$row++;
		foreach ($chiller_data as $item) {
			if (!empty($item->catatan)) {
				$sheet->setCellValue('A' . $row, '- ' . $item->catatan);
				$row++;
			}
		}

    // Output Excel
		$filename = "Chiller_" . $datetime->format('d-m-Y') . ".xlsx";
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
    exit; // penting biar ga ada output lain
}


}

