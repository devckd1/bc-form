<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;
setlocale(LC_TIME, 'id_ID.UTF-8');

class Pemeriksaanpengiriman extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model'); 
		$this->load->model('pemeriksaanpengiriman_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'pemeriksaanpengiriman' => $this->pemeriksaanpengiriman_model->get_data_by_plant()
		);

		$this->active_nav = 'pemeriksaanpengiriman'; 
		$this->render('form/pemeriksaanpengiriman/pemeriksaanpengiriman', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'pemeriksaanpengiriman' => $this->pemeriksaanpengiriman_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'pemeriksaanpengiriman'; 
		$this->render('form/pemeriksaanpengiriman/pemeriksaanpengiriman-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->pemeriksaanpengiriman_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->pemeriksaanpengiriman_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Pengiriman RM, pemeriksaanpengiriman, Kemasan dan Chemical berhasil di simpan');
				redirect('pemeriksaanpengiriman');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Pengiriman RM, pemeriksaanpengiriman, Kemasan dan Chemical gagal di simpan');
				redirect('pemeriksaanpengiriman');
			}
		}

		$this->active_nav = 'pemeriksaanpengiriman'; 
		$this->render('form/pemeriksaanpengiriman/pemeriksaanpengiriman-tambah', $data);
	}

	public function edit($uuid)
	{
		$rules = $this->pemeriksaanpengiriman_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			
			$update = $this->pemeriksaanpengiriman_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Pengiriman RM, pemeriksaanpengiriman, Kemasan dan Chemical berhasil di Update');
				redirect('pemeriksaanpengiriman');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Pengiriman RM, pemeriksaanpengiriman, Kemasan dan Chemical gagal di Update');
				redirect('pemeriksaanpengiriman');
			}
		}

		$data = array(
			'pemeriksaanpengiriman' => $this->pemeriksaanpengiriman_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'pemeriksaanpengiriman'; 
		$this->render('form/pemeriksaanpengiriman/pemeriksaanpengiriman-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('pemeriksaanpengiriman');
		}

		$deleted = $this->pemeriksaanpengiriman_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('pemeriksaanpengiriman');
	}
	
	public function verifikasi()
	{
		$data = array(
			'pemeriksaanpengiriman' => $this->pemeriksaanpengiriman_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-pemeriksaanpengiriman'; 
		$this->render('form/pemeriksaanpengiriman/pemeriksaanpengiriman-verifikasi', $data);
	}


	public function status($uuid)
	{
		$rules = $this->pemeriksaanpengiriman_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->pemeriksaanpengiriman_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Pengiriman RM, pemeriksaanpengiriman, Kemasan dan Chemical berhasil di Update');
				redirect('pemeriksaanpengiriman/verifikasi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Pengiriman RM, pemeriksaanpengiriman, Kemasan dan Chemical gagal di Update');
				redirect('pemeriksaanpengiriman/verifikasi');
			}
		}

		$data = array(
			'pemeriksaanpengiriman' => $this->pemeriksaanpengiriman_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-pemeriksaanpengiriman'; 
		$this->render('form/pemeriksaanpengiriman/pemeriksaanpengiriman-status', $data);
	}

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal');  

		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$pemeriksaanpengiriman_data = $this->pemeriksaanpengiriman_model->get_by_date($tanggal, $plant); 
		$pemeriksaanpengiriman_data_verif = $this->pemeriksaanpengiriman_model->get_last_verif_by_date($tanggal, $plant); 

		if (!$pemeriksaanpengiriman_data || !$pemeriksaanpengiriman_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['pemeriksaanpengiriman'] = $pemeriksaanpengiriman_data_verif;
		
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false); 
		$pdf->SetMargins(17, 16, 15); 
		$pdf->AddPage('L', 'LEGAL');
		$pdf->SetFont('times', 'B', 13);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 17, 14, 45);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(9, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN PENGIRIMAN RM, SEASONING, KEMASAN DAN CHEMICAL', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');

		$pdf->SetFont('times', '', 11);
		$pdf->Cell(12, 12, 'No.', 1, 0, 'C');
		$pdf->Cell(35, 12, 'Hari, Tanggal', 1, 0, 'C');
		$pdf->Cell(35, 12, 'Nama Supplier', 1, 0, 'C');
		$pdf->Cell(35, 12, 'Nama Barang', 1, 0, 'C');
		$pdf->Cell(25, 12, 'Jenis Mobil', 1, 0, 'C');
		$pdf->Cell(25, 12, 'No. Polisi', 1, 0, 'C');
		$pdf->Cell(25, 12, 'Identitas', 1, 0, 'C');
		$pdf->Cell(78, 6, 'Kondisi Mobil', 1, 0, 'C');
		$pdf->Cell(30, 12, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(20, 12, 'QC', 1, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$pdf->Cell(117, 0, '', 0, 0, 'L');
		$pdf->Cell(25, 6, 'Pengangkut', 0, 0, 'C');
		$pdf->Cell(25, 6, '', 0, 0, 'C');
		$pdf->Cell(25, 6, 'Pengantar', 0, 0, 'C');
		$pdf->SetFont('times', '', 9);
		$pdf->Cell(15, 6, 'Segel', 1, 0, 'C');
		$pdf->Cell(15, 6, 'Kebersihan', 1, 0, 'C');
		$pdf->Cell(15, 6, 'Bocor', 1, 0, 'C');
		$pdf->Cell(15, 6, 'Hama', 1, 0, 'C');
		$pdf->Cell(18, 6, 'Jam Datang', 1, 0, 'C');
		$pdf->SetFont('times', '', 11);
		$pdf->Cell(50, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$no = 1;
		foreach ($pemeriksaanpengiriman_data as $pemeriksaanpengiriman) {
			$tanggal = $pemeriksaanpengiriman->date;
			$date = new DateTime($tanggal);
			$formatted_date = date('l, d F Y', $date->getTimestamp());

			$time = $pemeriksaanpengiriman->jam_datang;
			$time2 = new DateTime($time); 
			$created_time = $time2->format('H:i');

			$pdf->SetFont('times', '', 11);
			$pdf->Cell(12, 8, $no, 1, 0, 'C');
			$pdf->Cell(35, 8, $formatted_date, 1, 0, 'C');
			$pdf->Cell(35, 8, $pemeriksaanpengiriman->nama_supplier, 1, 0, 'C');
			$pdf->Cell(35, 8, $pemeriksaanpengiriman->nama_barang, 1, 0, 'C');
			$pdf->Cell(25, 8, $pemeriksaanpengiriman->jenis_mobil, 1, 0, 'C');
			$pdf->Cell(25, 8, $pemeriksaanpengiriman->no_polisi, 1, 0, 'C');
			$pdf->Cell(25, 8, $pemeriksaanpengiriman->identitas_pengantar, 1, 0, 'C');
			$pdf->SetFont('dejavusans', '', 10);	
			$pdf->Cell(15, 8, ($pemeriksaanpengiriman->segel == 'ok') ? '✔' : (($pemeriksaanpengiriman->segel == 'tidak ok') ? '✘' : '−'), 1, 0, 'C');
			$pdf->Cell(15, 8, ($pemeriksaanpengiriman->kebersihan == 'ok') ? '✔' : (($pemeriksaanpengiriman->kebersihan == 'tidak ok') ? '✘' : '−'), 1, 0, 'C');
			$pdf->Cell(15, 8, ($pemeriksaanpengiriman->bocor == 'ok') ? '✔' : (($pemeriksaanpengiriman->bocor == 'tidak ok') ? '✘' : '−'), 1, 0, 'C');
			$pdf->Cell(15, 8, ($pemeriksaanpengiriman->hama == 'ok') ? '✔' : (($pemeriksaanpengiriman->hama == 'tidak ok') ? '✘' : '−'), 1, 0, 'C');
			$pdf->SetFont('times', '', 11);
			$pdf->Cell(18, 8, $created_time, 1, 0, 'C');
			$pdf->Cell(30, 8, !empty($pemeriksaanpengiriman->keterangan) ? $pemeriksaanpengiriman->keterangan : '-', 1, 0, 'C');
			$pdf->Cell(20, 8, $pemeriksaanpengiriman->username, 1, 0, 'C');
			$pdf->Ln();
			$no++;
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(330, 5, 'QW 02/00', 0, 1, 'R'); 

		$this->load->model('pegawai_model');
		$data['pemeriksaanpengiriman']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['pemeriksaanpengiriman']->username);
		$data['pemeriksaanpengiriman']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['pemeriksaanpengiriman']->nama_spv);

		$y_after_keterangan = $pdf->GetY() + 5;
		$status_verifikasi = true;
		foreach ($pemeriksaanpengiriman_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 9);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;

	// Dibuat oleh (QC)
			$pdf->SetXY(60, $y_verifikasi);
			$pdf->Cell(50, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(60, $y_verifikasi + 10);
			$pdf->SetFont('times', 'U', 9);
			$pdf->Cell(50, 5, $data['pemeriksaanpengiriman']->nama_lengkap_qc, 0, 1, 'C');
			$pdf->SetFont('times', '', 9); 
			$pdf->SetXY(60, $y_verifikasi + 15);
			$pdf->Cell(50, 5, 'QC Inspector', 0, 0, 'C');

	// Disetujui oleh (SPV)
			$update_tanggal = (new DateTime($data['pemeriksaanpengiriman']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['pemeriksaanpengiriman']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->SetXY(160, $y_verifikasi);
			$pdf->Cell(150, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 227, $y_verifikasi + 5, 16, 16, null, 'N');
			$pdf->SetXY(160, $y_verifikasi + 20);
			$pdf->Cell(150, 5, 'Supervisor QC', 0, 0, 'C');

		} else {
			$pdf->SetTextColor(255, 0, 0); 
			$pdf->SetFont('times', '', 9);
			$pdf->SetXY(200, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$currentDate = date('d-m-Y');
		$filename = "Pemeriksaan Pengiriman_{$currentDate}.pdf";
		$pdf->Output($filename, 'I');

	}
}

