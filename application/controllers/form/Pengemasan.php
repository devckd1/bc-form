<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;
setlocale(LC_TIME, 'id_ID.UTF-8');

class Pengemasan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model'); 
		$this->load->model('pengemasan_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'pengemasan' => $this->pengemasan_model->get_data_by_plant()
		);

		$this->active_nav = 'pengemasan'; 
		$this->render('form/pengemasan/pengemasan', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'pengemasan' => $this->pengemasan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'pengemasan'; 
		$this->render('form/pengemasan/pengemasan-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->pengemasan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->pengemasan_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Proses Pengemasan berhasil di simpan');
				redirect('pengemasan');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Proses Pengemasan gagal di simpan');
				redirect('pengemasan');
			}
		}

		$this->active_nav = 'pengemasan'; 
		$this->render('form/pengemasan/pengemasan-tambah');
	}

	public function edit($uuid)
	{
		$rules = $this->pengemasan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			
			$update = $this->pengemasan_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Proses Pengemasan berhasil di Update');
				redirect('pengemasan');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Proses Pengemasan gagal di Update');
				redirect('pengemasan');
			}
		}

		$data = array(
			'pengemasan' => $this->pengemasan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'pengemasan'; 
		$this->render('form/pengemasan/pengemasan-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('pengemasan');
		}

		$deleted = $this->pengemasan_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('pengemasan');
	}
	
	public function verifikasi()
	{
		$data = array(
			'pengemasan' => $this->pengemasan_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-pengemasan'; 
		$this->render('form/pengemasan/pengemasan-verifikasi', $data);
	}


	public function status($uuid)
	{
		$rules = $this->pengemasan_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->pengemasan_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Proses Pengemasan berhasil di Update');
				redirect('pengemasan/verifikasi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Proses Pengemasan gagal di Update');
				redirect('pengemasan/verifikasi');
			}
		}

		$data = array(
			'pengemasan' => $this->pengemasan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-pengemasan'; 
		$this->render('form/pengemasan/pengemasan-status', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'pengemasan' => $this->pengemasan_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-pengemasan', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/pengemasan/pengemasan-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->pengemasan_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {
			
	// 		$update = $this->pengemasan_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Proses Pengemasan berhasil di Update');
	// 			redirect('pengemasan/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Proses Pengemasan gagal di Update');
	// 			redirect('pengemasan/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'pengemasan' => $this->pengemasan_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-pengemasan');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/pengemasan/pengemasan-statusprod', $data);
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

		$pengemasan_data = $this->pengemasan_model->get_by_date($tanggal, $plant); 
		$pengemasan_data_verif = $this->pengemasan_model->get_last_verif_by_date($tanggal, $plant); 

		if (!$pengemasan_data || !$pengemasan_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['pengemasan'] = $pengemasan_data_verif;
		
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false); 
		$pdf->SetMargins(17, 16, 15); 
		$pdf->AddPage('L', 'LEGAL');
		$pdf->SetFont('times', 'B', 13);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 17, 14, 38);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(9, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN PROSES PENGEMASAN', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['pengemasan']->date;
		$date = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $date->getTimestamp());

		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(16);
		$pdf->Write(0, 'Hari / Tanggal : ' . $formatted_date);
		$pdf->SetX($pdf->GetX() + 20);
		$pdf->Write(0, 'Shift: ' . $data['pengemasan']->shift);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 11);

		$pdf->Cell(14, 14, 'Pukul', 1, 0, 'C');
		$pdf->Cell(32, 14, 'Nama Produk', 1, 0, 'C');
		$pdf->Cell(50, 14, 'Kode Produksi / Expired Date', 1, 0, 'C');
		$pdf->Cell(20, 14, 'Kadar Air', 1, 0, 'C');
		$pdf->Cell(20, 14, 'Kondisi', 1, 0, 'C');
		$pdf->Cell(24, 14, 'Kondisi Seal', 1, 0, 'C');
		$pdf->Cell(35, 14, 'Berat Kotor per pack', 1, 0, 'C');
		$pdf->Cell(38, 14, 'Berat Kotor per carton', 1, 0, 'C');
		$pdf->Cell(25, 14, 'Labelisasi', 1, 0, 'C');
		$pdf->Cell(25, 14, 'Kondisi Seal', 1, 0, 'C');
		$pdf->Cell(35, 14, 'Keterangan', 1, 0, 'C');

		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$pdf->Cell(14, 8, '', 0, 0, 'C');
		$pdf->Cell(32, 8, '', 0, 0, 'C');
		$pdf->Cell(50, 8, '', 0, 0, 'C');
		$pdf->Cell(20, 8, '(%)', 0, 0, 'C');
		$pdf->Cell(20, 8, 'Produk', 0, 0, 'C');
		$pdf->Cell(24, 8, 'Kemasan', 0, 0, 'C');
		$pdf->Cell(35, 8, '(gram)', 0, 0, 'C');
		$pdf->Cell(38, 8, '(Kg)', 0, 0, 'C');
		$pdf->Cell(25, 8, '(Karton Box)', 0, 0, 'C');
		$pdf->Cell(25, 8, '(Karton Box)', 0, 0, 'C');
		$pdf->Cell(35, 8, '', 0, 1, 'C');

		foreach ($pengemasan_data as $pengemasan) {
			$bb = $pengemasan->best_before;
			$best_before = new DateTime($bb);
			$formatted_bb = date('l, d F Y', $best_before->getTimestamp());

			$pukul = $pengemasan->waktu;
			$pukul2 = new DateTime($pukul);
			$formatted_time =  $pukul2->format('H:i');

			$pdf->SetFont('times', '', 10);
			$pdf->Cell(14, 8, $formatted_time, 1, 0, 'C');
			$pdf->Cell(32, 8, $pengemasan->nama_produk, 1, 0, 'C');
			$pdf->Cell(50, 8, $pengemasan->kode_produksi. " / ". $formatted_bb, 1, 0, 'C');
			$pdf->Cell(20, 8, $pengemasan->kadar_air, 1, 0, 'C');
			$pdf->Cell(20, 8, $pengemasan->kondisi_produk, 1, 0, 'C');
			$pdf->Cell(24, 8, $pengemasan->kondisi_seal, 1, 0, 'C');
			$pdf->Cell(35, 8, $pengemasan->berat_pack, 1, 0, 'C');
			$pdf->Cell(38, 8, $pengemasan->berat_carton, 1, 0, 'C');
			$pdf->Cell(25, 8, $pengemasan->labelisasi, 1, 0, 'C');
			$pdf->Cell(25, 8, $pengemasan->kondisi_karton, 1, 0, 'C');
			$pdf->Cell(35, 8, !empty($pengemasan->keterangan) ? $pengemasan->keterangan : '-', 1, 0, 'C');
			$pdf->Ln();
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetXY(20, $pdf->GetY() + 2);
		$pdf->Cell(60, 5, 'Keterangan :', 0, 1, 'L');

		$pdf->SetX(20);
		$pdf->Cell(60, 3, 'Range per Pack (100 gr): 99-109 gr', 0, 1, 'L');
		$pdf->SetX(20);
		$pdf->Cell(60, 3, 'Range per Carton (100 gr): 2738-2978 gr', 0, 1, 'L');
		$pdf->SetX(20);
		$pdf->Cell(60, 3, 'Range per Pack (200 gr): 211-201 gr', 0, 1, 'L');
		$pdf->SetX(20);
		$pdf->Cell(60, 3, 'Range per Carton (200 gr): 5669-5429 gr', 0, 1, 'L');
		$pdf->SetX(20);
		$pdf->Cell(60, 3, 'Range per Pack (10000 gr): 9950-10110 gr', 0, 1, 'L');
		$pdf->SetX(20);
		$pdf->Cell(60, 3, 'Range per Pack (5000 gr): 5068-5118 gr', 0, 1, 'L');

		$y_verifikasi = $pdf->GetY() - 26; 
		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		$this->load->model('pegawai_model');
		$data['pengemasan']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['pengemasan']->username);
		$data['pengemasan']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['pengemasan']->nama_spv);
		$data['pengemasan']->nama_lengkap_produksi = $data['pengemasan']->nama_produksi;

		$status_verifikasi = true;
		foreach ($pengemasan_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		if ($status_verifikasi) {
	// Dibuat oleh (QC)
			$pdf->SetXY(90, $y_verifikasi + 5);
			$pdf->Cell(35, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(90, $y_verifikasi + 10);
			$pdf->SetFont('times', 'U', 8);
			$pdf->Cell(35, 5, $data['pengemasan']->nama_lengkap_qc, 0, 1, 'C');
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(90, $y_verifikasi + 15);
			$pdf->Cell(35, 5, 'QC Inspector', 0, 0, 'C');

			if ($data['pengemasan']->status_produksi == 1 && !empty($data['pengemasan']->nama_produksi)) {
				$update_tanggal_produksi = (new DateTime($data['pengemasan']->tgl_update_produksi))->format('d-m-Y | H:i');

				$pdf->SetXY(140, $y_verifikasi + 5);
				$pdf->Cell(60, 5, 'Diketahui Oleh,', 0, 0, 'C');
				$pdf->SetFont('times', 'U', 8);
				$pdf->SetXY(140, $y_verifikasi + 10);
				$pdf->Cell(60, 5, $data['pengemasan']->nama_produksi, 0, 0, 'C');

				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(140, $y_verifikasi + 15);
				$pdf->Cell(60, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
			} else {
				$pdf->SetXY(140, $y_verifikasi + 10);
				$pdf->Cell(60, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}


	// Disetujui oleh (SPV)
			$update_tanggal = (new DateTime($data['pengemasan']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['pengemasan']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->SetXY(200, $y_verifikasi + 5);
			$pdf->Cell(70, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 227, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(200, $y_verifikasi + 24);
			$pdf->Cell(70, 5, 'Supervisor QC', 0, 0, 'C');

		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(150, $y_verifikasi + 10);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}


		$pdf->setPrintFooter(false);
		$filename = "Data Release Packing_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');

	}
}

