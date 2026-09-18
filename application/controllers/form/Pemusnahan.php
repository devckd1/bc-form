<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;
setlocale(LC_TIME, 'id_ID.UTF-8');

class Pemusnahan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model'); 
		$this->load->model('pemusnahan_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'pemusnahan' => $this->pemusnahan_model->get_data_by_plant()
		);

		$this->active_nav = 'pemusnahan'; 
		$this->render('form/pemusnahan/pemusnahan', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'pemusnahan' => $this->pemusnahan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'pemusnahan'; 
		$this->render('form/pemusnahan/pemusnahan-detail', $data);
	}

	public function tambah()
	{

		$rules = $this->pemusnahan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->pemusnahan_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemusnahan Produk berhasil di simpan');
				redirect('pemusnahan');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemusnahan Produk gagal di simpan');
				redirect('pemusnahan');
			}
		}

		$this->active_nav = 'pemusnahan'; 
		$this->render('form/pemusnahan/pemusnahan-tambah', $data);
	}

	public function edit($uuid)
	{
		$rules = $this->pemusnahan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			
			$update = $this->pemusnahan_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemusnahan Produk berhasil di Update');
				redirect('pemusnahan');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemusnahan Produk gagal di Update');
				redirect('pemusnahan');
			}
		}

		$data = array(
			'pemusnahan' => $this->pemusnahan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'pemusnahan'; 
		$this->render('form/pemusnahan/pemusnahan-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('pemusnahan');
		}

		$deleted = $this->pemusnahan_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('pemusnahan');
	}
	
	public function verifikasi()
	{
		$data = array(
			'pemusnahan' => $this->pemusnahan_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-pemusnahan'; 
		$this->render('form/pemusnahan/pemusnahan-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->pemusnahan_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->pemusnahan_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemusnahan Produk berhasil di Update');
				redirect('pemusnahan/verifikasi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemusnahan Produk gagal di Update');
				redirect('pemusnahan/verifikasi');
			}
		}

		$data = array(
			'pemusnahan' => $this->pemusnahan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-pemusnahan'; 
		$this->render('form/pemusnahan/pemusnahan-status', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'pemusnahan' => $this->pemusnahan_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-pemusnahan', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/pemusnahan/pemusnahan-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->pemusnahan_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {
			
	// 		$update = $this->pemusnahan_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemusnahan Produk berhasil di Update');
	// 			redirect('pemusnahan/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemusnahan Produk gagal di Update');
	// 			redirect('pemusnahan/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'pemusnahan' => $this->pemusnahan_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-pemusnahan');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/pemusnahan/pemusnahan-statusprod', $data);
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

		$pemusnahan_data = $this->pemusnahan_model->get_by_date($tanggal, $plant); 
		$pemusnahan_data_verif = $this->pemusnahan_model->get_last_verif_by_date($tanggal, $plant); 

		if (!$pemusnahan_data || !$pemusnahan_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['pemusnahan'] = $pemusnahan_data_verif;

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

		$pdf->Write(10, "\n");
		$pdf->MultiCell(0, 5, 'PEMUSNAHAN BARANG / PRODUK', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['pemusnahan']->date;
		$date = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $date->getTimestamp());

		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(17);
		$pdf->Write(0, 'Hari / Tanggal : ' . $formatted_date);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 11);

		$pdf->Cell(15, 10, 'No.', 1, 0, 'C');
		$pdf->Cell(60, 10, 'Nama Produk', 1, 0, 'C');
		$pdf->Cell(60, 10, 'Kode Produksi', 1, 0, 'C');
		$pdf->Cell(50, 10, 'Best Before', 1, 0, 'C');
		$pdf->Cell(90, 10, 'Analisa', 1, 0, 'C');
		$pdf->Cell(40, 10, 'Keterangan', 1, 1, 'C');	

		foreach ($pemusnahan_data as $pemusnahan) {
			$bb = $pemusnahan->best_before;
			$best_before = new DateTime($bb);
			$formatted_bb = date('l, d F Y', $best_before->getTimestamp());
			$no = 1;
			$pdf->SetFont('times', '', 10);
			$pdf->Cell(15, 8, $no, 1, 0, 'C');
			$pdf->Cell(60, 8, $pemusnahan->nama_produk, 1, 0, 'C');
			$pdf->Cell(60, 8, $pemusnahan->kode_produksi, 1, 0, 'C');
			$pdf->Cell(50, 8, $formatted_bb, 1, 0, 'C');
			$pdf->Cell(90, 8, $pemusnahan->analisa, 1, 0, 'C');
			$pdf->Cell(40, 8, !empty($pemusnahan->keterangan) ? $pemusnahan->keterangan : '-', 1, 0, 'C');
			$pdf->Ln();
			$no++;
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(315, 5, 'QB 14/00', 0, 1, 'R'); 

		$this->load->model('pegawai_model');
		$data['pemusnahan']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['pemusnahan']->username);
		$data['pemusnahan']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['pemusnahan']->nama_spv);

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($pemusnahan_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 9);
		$pdf->SetTextColor(0, 0, 0);

$y_ttd   = $pdf->GetY() + 6;
		$qr_size = 15;

		$qc_usernames  = [];
		$qc_created_at = null;

		foreach ($pemusnahan_data as $item) {
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

		$spv_tanggal = !empty($data['pemusnahan']->tgl_update_spv)
		? (new DateTime($data['pemusnahan']->tgl_update_spv))->format('d-m-Y | H:i')
		: '-';

		$qr_spv_text = "Disetujui secara digital oleh,\n"
		. $data['pemusnahan']->nama_lengkap_spv . "\n"
		. "Supervisor QC Bread Crumb\n"
		. $spv_tanggal;

		if ($status_verifikasi) {
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(20, $y_ttd);
			$pdf->Cell(85, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(85, $y_ttd);
			$pdf->Cell(310, 5, 'Disetujui Oleh,', 0, 1, 'C');
			$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 55,$y_ttd + 5, $qr_size, $qr_size, null, 'N');
			$pdf->write2DBarcode($qr_spv_text, 'QRCODE,L', 232, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
			$pdf->SetXY(20, $y_ttd + 20);
			$pdf->Cell(85, 5, 'QC Inspector', 0, 0, 'C');
			$pdf->SetXY(85, $y_ttd + 20);
			$pdf->Cell(310, 5, 'Supervisor QC', 0, 1, 'C');
		} else {
			$pdf->SetFont('times', '', 8);
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetXY(80, $y_ttd);
			$pdf->Cell(80, 6, 'Data Belum Diverifikasi', 0, 1, 'C');
			$pdf->SetTextColor(0, 0, 0);
		}

		$pdf->setPrintFooter(false);
		$filename = "Data Release Packing_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');

	}
}

