<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;
setlocale(LC_TIME, 'id_ID.UTF-8');

class Pembuatanlarutan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model'); 
		$this->load->model('pembuatanlarutan_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'pembuatanlarutan' => $this->pembuatanlarutan_model->get_data_by_plant()
		);

		$this->active_nav = 'pembuatanlarutan'; 
		$this->render('form/pembuatanlarutan/pembuatanlarutan', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'pembuatanlarutan' => $this->pembuatanlarutan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'pembuatanlarutan'; 
		$this->render('form/pembuatanlarutan/pembuatanlarutan-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->pembuatanlarutan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->pembuatanlarutan_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Pembuatan Larutan berhasil di simpan');
				redirect('pembuatanlarutan');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Pembuatan Larutan gagal di simpan');
				redirect('pembuatanlarutan');
			}
		}

		$this->active_nav = 'pembuatanlarutan'; 
		$this->render('form/pembuatanlarutan/pembuatanlarutan-tambah');
	}

	public function edit($uuid)
	{
		$rules = $this->pembuatanlarutan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			
			$update = $this->pembuatanlarutan_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Pembuatan Larutan berhasil di Update');
				redirect('pembuatanlarutan');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Pembuatan Larutan gagal di Update');
				redirect('pembuatanlarutan');
			}
		}

		$data = array(
			'pembuatanlarutan' => $this->pembuatanlarutan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'pembuatanlarutan'; 
		$this->render('form/pembuatanlarutan/pembuatanlarutan-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('pembuatanlarutan');
		}

		$deleted = $this->pembuatanlarutan_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('pembuatanlarutan');
	}
	
	public function verifikasi()
	{
		$data = array(
			'pembuatanlarutan' => $this->pembuatanlarutan_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-pembuatanlarutan'; 
		$this->render('form/pembuatanlarutan/pembuatanlarutan-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->pembuatanlarutan_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->pembuatanlarutan_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Pembuatan Larutan berhasil di Update');
				redirect('pembuatanlarutan/verifikasi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Pembuatan Larutan gagal di Update');
				redirect('pembuatanlarutan/verifikasi');
			}
		}

		$data = array(
			'pembuatanlarutan' => $this->pembuatanlarutan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-pembuatanlarutan'; 
		$this->render('form/pembuatanlarutan/pembuatanlarutan-status', $data);
	}

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal');  

		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$pembuatanlarutan_data = $this->pembuatanlarutan_model->get_by_date($tanggal, $plant); 
		$pembuatanlarutan_data_verif = $this->pembuatanlarutan_model->get_last_verif_by_date($tanggal, $plant); 

		if (!$pembuatanlarutan_data || !$pembuatanlarutan_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['pembuatanlarutan'] = $pembuatanlarutan_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false); 
		$pdf->SetMargins(10, 14, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 10);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 38);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(7, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN PEMBUATAN LARUTAN', 0, 'C');
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(10);
		$pdf->Write(0, 'Area : ' . $data['pembuatanlarutan']->area);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 11);

		$pdf->Cell(10, 12, 'No', 1, 0, 'C');
		$pdf->Cell(22, 12, 'Tanggal', 1, 0, 'C');
		$pdf->Cell(15, 12, 'Jam', 1, 0, 'C');
		$pdf->Cell(35, 12, 'Nama Chemical', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Expired', 1, 0, 'C');
		$pdf->Cell(25, 12, 'Konsentrasi', 1, 0, 'C');
		$pdf->Cell(30, 6, 'Pengenceran', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Catatan', 1, 0, 'C');
		$pdf->Cell(15, 12, 'QC', 1, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$pdf->Cell(102, 5, '', 0, 0, 'L');
		$pdf->Cell(25, 6, 'Larutan (ppm)', 0, 0, 'C');
		$pdf->SetFont('times', '', 7);
		$pdf->Cell(15, 6, 'Larutan Beku', 1, 0, 'C');
		$pdf->SetFont('times', '', 10);
		$pdf->Cell(15, 6, 'Air', 1, 0, 'C');
		$pdf->Cell(15, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$no = 1;
		foreach ($pembuatanlarutan_data as $pembuatanlarutan) {
			setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
			$tanggal = $pembuatanlarutan->date;
			$date = new DateTime($tanggal);
			$formatted_date = date('l, d F Y', $date->getTimestamp());

			$exp = $pembuatanlarutan->expired;
			$exp = new DateTime($exp); 
			$exp_date = $exp->format('d-m-Y');

			$time = $pembuatanlarutan->pukul;
			$time2 = new DateTime($time); 
			$created_time = $time2->format('H:i');
			$pdf->SetFont('times', '', 10);

			$pdf->Cell(10, 7, $no, 1, 0, 'C');
			$pdf->Cell(22, 7, $formatted_date, 1, 0, 'C');
			$pdf->Cell(15, 7, $created_time, 1, 0, 'C');
			$pdf->Cell(35, 7, $pembuatanlarutan->nama_chemical, 1, 0, 'C');
			$pdf->Cell(20, 7, $exp_date, 1, 0, 'C');
			$pdf->Cell(25, 7, $pembuatanlarutan->konsentrasi, 1, 0, 'C');
			$pdf->Cell(15, 7, $pembuatanlarutan->larutan_beku, 1, 0, 'C');
			$pdf->Cell(15, 7, $pembuatanlarutan->air, 1, 0, 'C');
			$pdf->Cell(20, 7, !empty($pembuatanlarutan->catatan) ? $pembuatanlarutan->catatan : '-', 1, 0, 'C');
			$pdf->Cell(15, 7, $pembuatanlarutan->username, 1, 0, 'C');
			$pdf->Ln();
			$no++;
		}

		$this->load->model('pegawai_model');
		$data['pembuatanlarutan']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['pembuatanlarutan']->username);
		$data['pembuatanlarutan']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['pembuatanlarutan']->nama_spv);

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($pembuatanlarutan_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;

			$pdf->SetXY(25, $y_verifikasi + 5);
			$pdf->Cell(35, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(25, $y_verifikasi + 10);
			$pdf->SetFont('times', 'U', 8); 
			$pdf->Cell(35, 5, $data['pembuatanlarutan']->nama_lengkap_qc, 0, 1, 'C');
			$pdf->SetFont('times', '', 8); 
			$pdf->Cell(65, 5, 'QC Inspector', 0, 0, 'C');

			$pdf->SetXY(150, $y_verifikasi + 5);
			$pdf->Cell(49, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$update_tanggal = (new DateTime($data['pembuatanlarutan']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['pembuatanlarutan']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 167, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(150, $y_verifikasi + 24);
			$pdf->Cell(49, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0); 
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(100, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}


		$pdf->setPrintFooter(false);
		$currentDate = date('d-m-Y');
		$filename = "Pembuatan Larutan_{$currentDate}.pdf";
		$pdf->Output($filename, 'I');

	}
}

