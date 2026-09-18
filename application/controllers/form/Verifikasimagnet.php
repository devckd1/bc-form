<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;
setlocale(LC_TIME, 'id_ID.UTF-8');

class Verifikasimagnet extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model'); 
		$this->load->model('verifikasimagnet_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'verifikasimagnet' => $this->verifikasimagnet_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasimagnet'; 
		$this->render('form/verifikasimagnet/verifikasimagnet', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'verifikasimagnet' => $this->verifikasimagnet_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasimagnet'; 
		$this->render('form/verifikasimagnet/verifikasimagnet-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->verifikasimagnet_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->verifikasimagnet_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Magnet Trap berhasil di simpan');
				redirect('verifikasimagnet');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Magnet Trap gagal di simpan');
				redirect('verifikasimagnet');
			}
		}

		$this->active_nav = 'verifikasimagnet'; 
		$this->render('form/verifikasimagnet/verifikasimagnet-tambah');
	}


	public function edit($uuid)
	{
		$rules = $this->verifikasimagnet_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			
			$update = $this->verifikasimagnet_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Magnet Trap berhasil di Update');
				redirect('verifikasimagnet');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Magnet Trap gagal di Update');
				redirect('verifikasimagnet');
			}
		}

		$data = array(
			'verifikasimagnet' => $this->verifikasimagnet_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasimagnet'; 
		$this->render('form/verifikasimagnet/verifikasimagnet-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('verifikasimagnet');
		}

		$deleted = $this->verifikasimagnet_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('verifikasimagnet');
	}
	
	public function verifikasi()
	{
		$data = array(
			'verifikasimagnet' => $this->verifikasimagnet_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-verifikasimagnet'; 
		$this->render('form/verifikasimagnet/verifikasimagnet-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->verifikasimagnet_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->verifikasimagnet_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Magnet Trap berhasil di Update');
				redirect('verifikasimagnet/verifikasi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Magnet Trap gagal di Update');
				redirect('verifikasimagnet/verifikasi');
			}
		}

		$data = array(
			'verifikasimagnet' => $this->verifikasimagnet_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-verifikasimagnet'; 
		$this->render('form/verifikasimagnet/verifikasimagnet-status', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'verifikasimagnet' => $this->verifikasimagnet_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-verifikasimagnet', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/verifikasimagnet/verifikasimagnet-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }

	// public function statusprod($uuid)
	// {
	// 	$rules = $this->verifikasimagnet_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {
			
	// 		$update = $this->verifikasimagnet_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Verifikasi Magnet Trap berhasil di Update');
	// 			redirect('verifikasimagnet/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Verifikasi Magnet Trap gagal di Update');
	// 			redirect('verifikasimagnet/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'verifikasimagnet' => $this->verifikasimagnet_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-verifikasimagnet');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/verifikasimagnet/verifikasimagnet-statusprod', $data);
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

		$verifikasimagnet_data = $this->verifikasimagnet_model->get_by_date($tanggal, $plant); 
		$verifikasimagnet_data_verif = $this->verifikasimagnet_model->get_last_verif_by_date($tanggal, $plant); 

		if (!$verifikasimagnet_data || !$verifikasimagnet_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['verifikasimagnet'] = $verifikasimagnet_data_verif;
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false); 
		$pdf->SetMargins(10, 9.5, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 10);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 30);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(7, "\n");
		$pdf->MultiCell(0, 5, 'VERIFIKASI MAGNET TRAP', 0, 'C');
		$pdf->Ln(4);

		$pdf->SetFont('times', '', 9);

		$pdf->Cell(32, 12, 'Tanggal', 1, 0, 'C');
		$pdf->Cell(10, 12, 'Shift', 1, 0, 'C');
		$pdf->Cell(30, 12, 'Nama Produk', 1, 0, 'C');
		$pdf->Cell(35, 12, 'Kode Produksi', 1, 0, 'C');
		$pdf->Cell(15, 12, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(38, 12, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(36, 6, 'Paraf', 1, 1, 'C');

		$pdf->Cell(107, 12, '', 0, 0, 'C');
		$pdf->Cell(15, 6, 'Temuan', 0, 0, 'C');
		$pdf->Cell(38, 12, '', 0, 0, 'L');
		$pdf->Cell(18, 6, 'QC', 1, 0, 'C');
		$pdf->Cell(18, 6, 'Prod', 1, 1, 'C');

		foreach ($verifikasimagnet_data as $verifikasimagnet) {
			setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
			$tanggal = $verifikasimagnet->date;
			$date = new DateTime($tanggal);
			$formatted_date = date('l, d F Y', $date->getTimestamp());
			$pdf->SetFont('times', '', 8);
			
			$pdf->Cell(32, 6, $formatted_date, 1, 0, 'C');
			$pdf->Cell(10, 6, $verifikasimagnet->shift, 1, 0, 'C');
			$pdf->Cell(30, 6, $verifikasimagnet->nama_produk, 1, 0, 'C');
			$pdf->Cell(35, 6, $verifikasimagnet->kode_produksi, 1, 0, 'C');
			$pdf->Cell(15, 6, $verifikasimagnet->jumlah_temuan, 1, 0, 'C');
			$pdf->Cell(38, 6, !empty($verifikasimagnet->keterangan) ? $verifikasimagnet->keterangan : '-', 1, 0, 'C');
			$pdf->Cell(18, 6, $verifikasimagnet->username, 1, 0, 'C');
			$pdf->Cell(18, 6, $verifikasimagnet->nama_produksi, 1, 0, 'C');
			$pdf->Ln();
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(190, 5, 'QB 19/00', 0, 1, 'R'); 

		$this->load->model('pegawai_model');
		$data['verifikasimagnet']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['verifikasimagnet']->username);
		$data['verifikasimagnet']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['verifikasimagnet']->nama_spv);
		$data['verifikasimagnet']->nama_lengkap_produksi = $data['verifikasimagnet']->nama_produksi;

		$pdf->SetY($pdf->GetY() + 2); 
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($verifikasimagnet_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L'); 
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($verifikasimagnet_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

// 		if ($status_verifikasi) {
// 			$y_verifikasi = $y_after_keterangan;

// 		// Dibuat oleh (QC)
// 			$pdf->SetXY(25, $y_verifikasi + 5);
// 			$pdf->Cell(35, 5, 'Dibuat Oleh,', 0, 0, 'C');
// 			$pdf->SetXY(25, $y_verifikasi + 10);
// 			$pdf->SetFont('times', 'U', 8); // underline
// 			$pdf->Cell(35, 5, $data['verifikasimagnet']->nama_lengkap_qc, 0, 1, 'C');
// 			$pdf->SetFont('times', '', 8); 
// 			$pdf->Cell(65, 5, 'QC Inspector', 0, 0, 'C');

// 		// // Diketahui oleh (Produksi)
// 		// 	$pdf->SetXY(90, $y_verifikasi + 5);
// 		// 	$pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');
// 		// 	if ($data['verifikasimagnet']->status_produksi == 1 && !empty($data['verifikasimagnet']->nama_produksi)) {
// 		// 		$update_tanggal_produksi = (new DateTime($data['verifikasimagnet']->tgl_update_produksi))->format('d-m-Y | H:i');
// 		// 		$qr_text_produksi = "Diketahui secara digital oleh,\n" . $data['verifikasimagnet']->nama_lengkap_produksi . "\nForeman/Forelady Produksi\n" . $update_tanggal_produksi;
// 		// 		$pdf->write2DBarcode($qr_text_produksi, 'QRCODE,L', 100, $y_verifikasi + 10, 15, 15, null, 'N');
// 		// 		$pdf->SetXY(90, $y_verifikasi + 24);
// 		// 		$pdf->Cell(35, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
// 		// 	} else {
// 		// 		$pdf->SetXY(90, $y_verifikasi + 10);
// 		// 		$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
// 		// 	}

// // Diketahui oleh (Produksi) - tanpa barcode
// 			$pdf->SetXY(90, $y_verifikasi + 5);
// 			$pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');

// 			if ($data['verifikasimagnet']->status_produksi == 1 && !empty($data['verifikasimagnet']->nama_produksi)) {
// 				$update_tanggal_produksi = (new DateTime($data['verifikasimagnet']->tgl_update_produksi))->format('d-m-Y | H:i');

// 				$pdf->SetFont('times', 'U', 8);
// 				$pdf->SetXY(90, $y_verifikasi + 10);
// 				$pdf->Cell(35, 5, $data['verifikasimagnet']->nama_produksi, 0, 1, 'C');

// 				$pdf->SetFont('times', '', 8);
// 				$pdf->SetXY(90, $y_verifikasi + 15);
// 				$pdf->Cell(35, 5, 'Foreman/Forelady Produksi', 0, 1, 'C');

// 				// $pdf->SetXY(90, $y_verifikasi + 20);
// 				// $pdf->Cell(35, 5, $update_tanggal_produksi, 0, 0, 'C');
// 			} else {
// 				$pdf->SetFont('times', '', 8);
// 				$pdf->SetXY(90, $y_verifikasi + 10);
// 				$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
// 			}

// 		// Disetujui oleh (SPV)
// 			$pdf->SetXY(150, $y_verifikasi + 5);
// 			$pdf->Cell(49, 5, 'Disetujui Oleh,', 0, 0, 'C');
// 			$update_tanggal = (new DateTime($data['verifikasimagnet']->tgl_update_spv))->format('d-m-Y | H:i');
// 			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['verifikasimagnet']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
// 			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 167, $y_verifikasi + 10, 15, 15, null, 'N');
// 			$pdf->SetXY(150, $y_verifikasi + 24);
// 			$pdf->Cell(49, 5, 'Supervisor QC', 0, 0, 'C');
// 		} else {
// 			$pdf->SetTextColor(255, 0, 0); 
// 			$pdf->SetFont('times', '', 8);
// 			$pdf->SetXY(100, $y_after_keterangan);
// 			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
// 		}


$y_ttd   = $pdf->GetY() + 6;
		$qr_size = 15;

		$qc_usernames  = [];
		$qc_created_at = null;

		foreach ($verifikasimagnet_data as $item) {
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

		if (!empty($data['verifikasimagnet']->nama_lengkap_produksi) && !empty($data['verifikasimagnet']->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($data['verifikasimagnet']->tgl_update_produksi ?? $data['verifikasimagnet']->tgl_update_produksi))
			->format('d-m-Y | H:i');

			$qr_produksi_text = "Diketahui secara digital oleh,\n"
			. $data['verifikasimagnet']->nama_lengkap_produksi . "\n"
			. "Foreman/Forelady Produksi\n"
			. $prod_tanggal;
		}

		$spv_tanggal = !empty($data['verifikasimagnet']->tgl_update_spv)
		? (new DateTime($data['verifikasimagnet']->tgl_update_spv))->format('d-m-Y | H:i')
		: '-';

		$qr_spv_text = "Disetujui secara digital oleh,\n"
		. $data['verifikasimagnet']->nama_lengkap_spv . "\n"
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

		$currentDate = date('d-m-Y');
		$filename = "Verifikasi Magnet Trap_{$currentDate}.pdf";
		$pdf->Output($filename, 'I');
	}
}

