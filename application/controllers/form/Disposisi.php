<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;
setlocale(LC_TIME, 'id_ID.UTF-8');

class Disposisi extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('auth_model'); 
		$this->load->model('disposisi_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'disposisi' => $this->disposisi_model->get_data_by_plant() 
		);

		$this->active_nav = 'disposisi'; 
		$this->render('form/disposisi/disposisi', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'disposisi' => $this->disposisi_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'disposisi'; 
		$this->render('form/disposisi/disposisi-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->disposisi_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->disposisi_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Disposisi Produk dan Prosedur berhasil di simpan');
				redirect('disposisi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Disposisi Produk dan Prosedur gagal di simpan');
				redirect('disposisi');
			}
		}

		$this->active_nav = 'disposisi'; 
		$this->render('form/disposisi/disposisi-tambah');
	}

	public function edit($uuid)
	{
		$rules = $this->disposisi_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$update = $this->disposisi_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Disposisi Produk dan Prosedur berhasil diupdate.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Disposisi Produk dan Prosedur gagal diupdate.');
			}

			redirect('disposisi');
		}

		$data = [
			'disposisi' => $this->disposisi_model->get_by_uuid($uuid)
		];

		$this->active_nav = 'disposisi'; 
		$this->render('form/disposisi/disposisi-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('disposisi');
		}

		$deleted = $this->disposisi_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('disposisi');
	}

	public function verifikasi()
	{
		$data = array(
			'disposisi' => $this->disposisi_model->get_data_by_plant(),
		);

		$this->active_nav = 'verifikasi-disposisi'; 
		$this->render('form/disposisi/disposisi-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->disposisi_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->disposisi_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Disposisi Produk dan Prosedur berhasil di Update');
				redirect('disposisi/verifikasi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Disposisi Produk dan Prosedur gagal di Update');
				redirect('disposisi/verifikasi');
			}
		}

		$data = array(
			'disposisi' => $this->disposisi_model->get_by_uuid($uuid),
		);
		$this->active_nav = 'verifikasi-disposisi'; 
		$this->render('form/disposisi/disposisi-status', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'disposisi' => $this->disposisi_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-disposisi', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/disposisi/disposisi-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->disposisi_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {
			
	// 		$update = $this->disposisi_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Disposisi Produk dan Prosedur berhasil di Update');
	// 			redirect('disposisi/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Disposisi Produk dan Prosedur gagal di Update');
	// 			redirect('disposisi/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'disposisi' => $this->disposisi_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-disposisi');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/disposisi/disposisi-statusprod', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function cetak()
	{
		$selected_items = $this->input->post('checkbox'); 

		log_message('debug', 'UUID yang dipilih: ' . print_r($selected_items, true));

		if (empty($selected_items)) {
			show_error('Tidak ada item yang dipilih', 404);
		}

		$disposisi_data = $this->disposisi_model->get_by_uuid_disposisi($selected_items);

		$disposisi_data_verif = $this->disposisi_model->get_by_uuid_disposisi_verif($selected_items);

		$data['disposisi'] = $disposisi_data_verif;


		if (!$data['disposisi']) {
			show_error('Data tidak ditemukan, Pilih data yang ingin dicetak', 404);
		}

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false); 
		$pdf->SetMargins(10, 14, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 12);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 15, 10, 38);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(7, "\n");
		$pdf->MultiCell(0, 5, 'DISPOSISI PRODUK DAN PROSEDUR', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['disposisi']->date;
		$date = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $date->getTimestamp());
		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);

		$labelWidth = 30;
		$colonWidth = 2;
		$valueWidth = 153;
		$totalWidth = $labelWidth + $colonWidth + $valueWidth;

		$startX = 15;
		$startY = $pdf->GetY(); 

		$pdf->SetX($startX);
		$pdf->Cell($labelWidth, 5, 'Nomor', 0, 0, 'L');
		$pdf->Cell($colonWidth, 5, ':', 0, 0, 'L');
		$pdf->MultiCell($valueWidth, 5, $data['disposisi']->nomor, 0, 'L');

		$pdf->SetX($startX);
		$pdf->Cell($labelWidth, 5, 'Tanggal', 0, 0, 'L');
		$pdf->Cell($colonWidth, 5, ':', 0, 0, 'L');
		$pdf->MultiCell($valueWidth, 5, $formatted_date, 0, 'L');

		$pdf->SetX($startX);
		$pdf->Cell($labelWidth, 5, 'Kepada', 0, 0, 'L');
		$pdf->Cell($colonWidth, 5, ':', 0, 0, 'L');
		$pdf->MultiCell($valueWidth, 5, $data['disposisi']->kepada, 0, 'L');
		$pdf->Ln(3);
		$pdf->SetX($startX);
		$pdf->Cell($labelWidth, 5, 'Disposisi', 0, 0, 'L');
		$pdf->Cell($colonWidth, 5, ':', 0, 0, 'L');
		$pdf->MultiCell($valueWidth, 5, $data['disposisi']->disposisi, 0, 'L');
		$pdf->Ln(5);
		$pdf->SetX($startX);
		$pdf->Cell($labelWidth, 5, 'Dasar Disposisi', 0, 0, 'L');
		$pdf->Cell($colonWidth, 5, ':', 0, 0, 'L');
		$pdf->MultiCell($valueWidth, 5, $data['disposisi']->dasar_disposisi, 0, 'L');
		$pdf->Ln(5);
		$pdf->SetX($startX);
		$pdf->Cell($labelWidth, 5, 'Uraian Disposisi', 0, 0, 'L');
		$pdf->Cell($colonWidth, 5, ':', 0, 0, 'L');
		$pdf->MultiCell($valueWidth, 5, $data['disposisi']->uraian_disposisi, 0, 'L');
		$pdf->Ln(5);
		$pdf->SetX($startX);
		$pdf->Cell($labelWidth, 5, 'Catatan', 0, 0, 'L');
		$pdf->Cell($colonWidth, 5, ':', 0, 0, 'L');
		$pdf->MultiCell($valueWidth, 5, $data['disposisi']->catatan, 0, 'L');
		$pdf->Cell($colonWidth, 2, '', 0, 1, 'L');
		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(190, 5, 'QW 18/00', 0, 1, 'R'); 
		$endY = $pdf->GetY(); 
		$pdf->Rect($startX, $startY, $totalWidth, $endY - $startY);

		$pdf->SetFont('times', '', 10);
		$ccStartY = $pdf->GetY();
		$pdf->SetX($startX);

		$pdf->Cell($labelWidth, 5, 'CC', 0, 0, 'L');
		$pdf->Cell($colonWidth, 5, ':', 0, 0, 'L');
		$afterLabelX = $pdf->GetX();
		$afterLabelY = $pdf->GetY();
		$pdf->SetXY($afterLabelX, $afterLabelY);
		$pdf->MultiCell($valueWidth, 5, $data['disposisi']->cc, 0, 'L');
		$pdf->Cell($colonWidth, 2, '', 0, 1, 'L');
		$ccEndY = $pdf->GetY();
		$pdf->Rect($startX, $ccStartY, $totalWidth, $ccEndY - $ccStartY);

		$boxWidth = $totalWidth / 3;
		$boxHeight = 42; 
		$pdf->Rect($startX, $ccEndY, $totalWidth, $boxHeight);
		$pdf->Line($startX + $boxWidth, $ccEndY, $startX + $boxWidth, $ccEndY + $boxHeight);
		$pdf->Line($startX + 2 * $boxWidth, $ccEndY, $startX + 2 * $boxWidth, $ccEndY + $boxHeight);
		$pdf->SetFont('times', '', 10);

		$labelY    = $ccEndY + 2;  
		$lineY     = $labelY + 25; 
		$titleY    = $lineY + 5; 

		$pdf->SetXY($startX, $labelY);
		$pdf->Cell($boxWidth, 6, 'Dibuat Oleh :', 0, 0, 'C');
		$pdf->SetXY($startX, $lineY);
		$pdf->Cell($boxWidth, 6, '(_____________)', 0, 0, 'C');
		$pdf->SetXY($startX, $titleY);
		$pdf->Cell($boxWidth, 6, 'Spv. QC', 0, 0, 'C');
		$pdf->SetXY($startX + $boxWidth, $labelY);
		$pdf->Cell($boxWidth, 6, 'Mengetahui :', 0, 0, 'C');
		$pdf->SetXY($startX + $boxWidth, $lineY);
		$pdf->Cell($boxWidth / 2, 6, '(___________)', 0, 0, 'C');
		$pdf->Cell($boxWidth / 2, 6, '(___________)', 0, 0, 'C');
		$pdf->SetXY($startX + $boxWidth, $titleY);
		$pdf->Cell($boxWidth / 2, 6, 'Spv. Produksi', 0, 0, 'C');
		$pdf->Cell($boxWidth / 2, 6, 'Manager Produksi', 0, 0, 'C');
		$pdf->SetXY($startX + 2 * $boxWidth, $labelY);
		$pdf->Cell($boxWidth, 6, 'Diperiksa Oleh :', 0, 0, 'C');
		$pdf->SetXY($startX + 2 * $boxWidth, $lineY);
		$pdf->Cell($boxWidth, 6, '(_____________)', 0, 0, 'C');
		$pdf->SetXY($startX + 2 * $boxWidth, $titleY);
		$pdf->Cell($boxWidth, 6, 'Manager QC', 0, 0, 'C');

		// $nama_spv = $data['disposisi']->nama_spv;
		// $tanggal_update = $data['disposisi']->tgl_update_spv;
		// $update = new DateTime($tanggal_update); 
		// $update_tanggal = $update->format('d-m-Y | H:i');

		// $status_verifikasi = true;

		// foreach ($disposisi_data as $item) {
		// 	if ($item->status_spv != '1') {
		// 		$status_verifikasi = false;
		// 		break; 
		// 	}
		// }

		// if ($status_verifikasi) {
		// 	$pdf->SetFont('times', '', 8);
		// 	$pdf->SetXY(40, $y_after_keterangan);
		// 	$pdf->Cell(25, 5, 'Diperiksa Oleh : '. $data['disposisi']->username, 0, 0, 'C');
		// 	$pdf->SetXY(95, $y_after_keterangan);
		// 	$pdf->Cell(70, 5, 'Diketahui WH : ' . $data['disposisi']->nama_wh, 0, 0, 'L');

		// 	$url = 'Diverifikasi secara digital oleh,' . "\n" . $nama_spv . "\n" . 'SPV QC Bread Crumb' . "\n" . $update_tanggal;

		// 	$pdf->SetFont('times', '', 8);
		// 	$pdf->SetXY(140, $y_after_keterangan);
		// 	$pdf->Cell(60, 4, 'Disetujui oleh,', 0, 0, 'C');  .,                        . ,
		// 	$pdf->write2DBarcode($url, 'QRCODE,L', 161, $y_after_keterangan + 5, 18, 18, null, 'N');
		// 	$pdf->SetXY(140, $y_after_keterangan + 23);
		// 	$pdf->Cell(60, 4, 'Supervisor QC', 0, 0, 'C');
		// } else {
		// 	$pdf->SetTextColor(255, 0, 0);
		// 	$pdf->SetFont('times', '', 8);
		// 	$pdf->SetXY(150, $y_after_keterangan);
		// 	$pdf->Cell(60, 4, 'Data Belum Diverifikasi', 0, 0, 'C');
		// }

		$pdf->setPrintFooter(false);
		$filename = "Pemeriksaan disposisi Produk_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}
}

