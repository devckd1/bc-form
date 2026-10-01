<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Larutan extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('auth_model');
		$this->load->model('larutan_model');
		$this->load->library('audit_library');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'larutan' => $this->larutan_model->get_data_by_plant()
		);

		$this->active_nav = 'larutan';
		$this->render('form/larutan/larutan', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'larutan' => $this->larutan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'larutan';
		$this->render('form/larutan/larutan-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->larutan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->larutan_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Pembuatan Larutan Cleaning dan Sanitasi berhasil di simpan');
				redirect('larutan');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Pembuatan Larutan Cleaning dan Sanitasi gagal di simpan');
				redirect('larutan');
			}
		}

		$this->active_nav = 'larutan';
		$this->render('form/larutan/larutan-tambah');
	}

	public function edit($uuid)
	{
		$rules = $this->larutan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$update = $this->larutan_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Pembuatan Larutan Cleaning dan Sanitasi berhasil diupdate.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Pembuatan Larutan Cleaning dan Sanitasi gagal diupdate.');
			}

			redirect('larutan');
		}

		$data = [
			'larutan' => $this->larutan_model->get_by_uuid($uuid),
			'bagian_list' => $this->larutan_model->get_bagian_by_uuid($uuid)
		];

		$this->active_nav = 'larutan';
		$this->render('form/larutan/larutan-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('larutan');
		}

		$deleted = $this->larutan_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('larutan');
	}

	public function verifikasi()
	{
		$data = array(
			'larutan' => $this->larutan_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-larutan';
		$this->render('form/larutan/larutan-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->larutan_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->larutan_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Pembuatan Larutan Cleaning dan Sanitasi berhasil di Update');
				redirect('larutan/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Pembuatan Larutan Cleaning dan Sanitasi gagal di Update');
				redirect('larutan/verifikasi');
			}
		}

		$data = array(
			'larutan' => $this->larutan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-larutan';
		$this->render('form/larutan/larutan-status', $data);
	}

	public function copy_to_audit($uuid)
	{
		if (empty($uuid)) {
			$this->session->set_flashdata(
				'error_msg',
				'UUID data tidak ditemukan.'
			);

			redirect('larutan/verifikasi');
			return;
		}

		$audit_uuid = $this->audit_library->copy_to_audit(
			'larutan',
			$uuid
		);

		if (!$audit_uuid) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Larutan tidak ditemukan atau gagal dibuat sebagai Audit.'
			);

			redirect('larutan/verifikasi');
			return;
		}

		$this->session->set_flashdata(
			'success_msg',
			'Data Larutan berhasil dikirim ke Audit.'
		);

		redirect('larutan/verifikasi');
	}

	public function audit()
	{
		$data = array(
			'larutan' => $this->larutan_model->get_audit_by_plant()
		);

		$this->active_nav = 'verifikasi-larutan';

		$this->render(
			'form/larutan/larutan-audit',
			$data
		);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'larutan' => $this->larutan_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-larutan', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/larutan/larutan-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->larutan_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->larutan_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Verifikasi Pembuatan Larutan Cleaning dan Sanitasi berhasil di Update');
	// 			redirect('larutan/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Verifikasi Pembuatan Larutan Cleaning dan Sanitasi gagal di Update');
	// 			redirect('larutan/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'larutan' => $this->larutan_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-larutan');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/larutan/larutan-statusprod', $data);
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

		$larutan_data = $this->larutan_model->get_by_date($tanggal, $plant, $shift);
		$larutan_data_verif = $this->larutan_model->get_last_verif_by_date($tanggal, $plant, $shift);

		if (!$larutan_data || !$larutan_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['larutan'] = $larutan_data_verif;

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
		$pdf->MultiCell(0, 5, 'VERIFIKASI PEMBUATAN LARUTAN CLEANING DAN SANITASI', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$date = new DateTime($data['larutan']->date);
		$formatted_date = date('l, d F Y', $date->getTimestamp());
		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(16);
		$pdf->Write(0, 'Hari / Tanggal : ' . $formatted_date);
		$pdf->Ln();
		$pdf->SetX(16);
		$pdf->Write(0, 'Shift : ' . $data['larutan']->shift);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 9);
		$pdf->Cell(10, 12, 'No.', 1, 0, 'C');
		$pdf->Cell(40, 12, 'NAMA BAHAN', 1, 0, 'C');
		$pdf->Cell(30, 12, 'KADAR YANG', 1, 0, 'C');
		$pdf->Cell(60, 4, 'VERIFIKASI FORMULASI', 1, 0, 'C');
		$pdf->Cell(35, 12, 'KEBUTUHAN', 1, 0, 'C');
		$pdf->Cell(35, 12, 'KETERANGAN', 1, 0, 'C');
		$pdf->Cell(40, 12, 'TINDAKAN KOREKSI', 1, 0, 'C');
		$pdf->Cell(67, 12, 'VERIFIKASI SETELAH TINDAKAN KOREKSI', 1, 0, 'C');
		$pdf->Cell(0, 4, '', 0, 1, 'C');

		$pdf->Cell(50, 0, '', 0, 0, 'L');
		$pdf->Cell(30, 10, 'DIINGINKAN', 0, 0, 'C');
		$pdf->SetFont('times', '', 7);
		$pdf->Cell(20, 8, 'BAHAN KIMIA', 1, 0, 'C');
		$pdf->Cell(20, 8, 'AIR BERSIH', 1, 0, 'C');
		$pdf->Cell(20, 8, 'VOLUME AKHIR', 1, 0, 'C');
		$pdf->Cell(0, 0, '', 0, 1, 'C');

		$pdf->Cell(80, 0, '', 0, 0, 'C');
		$pdf->Cell(20, 6, '(ML)', 0, 0, 'C');
		$pdf->Cell(20, 6, '(ML)', 0, 0, 'C');
		$pdf->Cell(20, 6, '(ML)', 0, 0, 'C');
		$pdf->Cell(10, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');
		$no = 1;
		$pdf->SetFont('times', '', 9);

		foreach ($larutan_data as $larutan) {
			$bahan_list = json_decode($larutan->nama_bahan, true);

			foreach ($bahan_list as $bahan) {
				$pdf->Cell(10, 8, $no, 1, 0, 'C');
				$pdf->Cell(40, 8, $bahan['bahan'] ?? '-', 1, 0, 'L');
				$pdf->Cell(30, 8, $bahan['kadar'] ?? '-', 1, 0, 'C');
				$pdf->Cell(20, 8, $bahan['bahan_kimia'] ?? '-', 1, 0, 'C');
				$pdf->Cell(20, 8, $bahan['air_bersih'] ?? '-', 1, 0, 'C');
				$pdf->Cell(20, 8, $bahan['volume_akhir'] ?? '-', 1, 0, 'C');
				$pdf->Cell(35, 8, $bahan['kebutuhan'] ?? '-', 1, 0, 'L');
				$pdf->Cell(35, 8, $bahan['keterangan'] ?? '-', 1, 0, 'L');
				$pdf->Cell(40, 8, $bahan['tindakan'] ?? '-', 1, 0, 'L');
				$pdf->Cell(67, 8, $bahan['verifikasi'] ?? '-', 1, 0, 'L');
				$pdf->Ln();
				$no++;
			}
		}

		$pdf->SetY($pdf->GetY() + 3);
		$pdf->SetFont('dejavusans', '', 6);
		$pdf->SetXY(18, $pdf->GetY());
		$pdf->MultiCell(90, 4, "✔ = Perbandingan formulasi sesuai\n✘ = Perbandingan formulasi tidak sesuai", 0, 'L');
		$pdf->SetXY(105, $pdf->GetY());
		$pdf->MultiCell(90, 4, "Pelarut yang digunakan adalah pelarut air", 0, 'L');

		$pdf->SetY($pdf->GetY() + 3);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(10, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($larutan_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$this->load->model('pegawai_model');
		$data['larutan']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['larutan']->username);
		$data['larutan']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['larutan']->nama_spv);
		$data['larutan']->nama_lengkap_produksi = $data['larutan']->nama_produksi;

		$status_verifikasi = true;
		foreach ($larutan_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);
		$y_verifikasi = $pdf->GetY();

		if ($status_verifikasi) {
			// Dibuat Oleh (QC)
			$pdf->SetXY(25, $y_verifikasi + 5);
			$pdf->Cell(95, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(25, $y_verifikasi + 10);
			$pdf->SetFont('times', 'U', 8);
			$pdf->Cell(95, 5, $data['larutan']->nama_lengkap_qc, 0, 1, 'C');
			$pdf->SetFont('times', '', 8);
			$pdf->Cell(112, 5, 'QC Inspector', 0, 0, 'C');

			// // Diketahui Oleh (Produksi)
			// 	$pdf->SetXY(90, $y_verifikasi + 5);
			// 	$pdf->Cell(135, 5, 'Diketahui Oleh,', 0, 0, 'C');
			// 	if ($data['larutan']->status_produksi == 1 && !empty($data['larutan']->nama_produksi)) {
			// 		$qr_text_produksi = "Diketahui secara digital oleh,\n" . $data['larutan']->nama_lengkap_produksi . "\nForeman/Forelady Produksi\n" . date('d-m-Y | H:i', strtotime($data['larutan']->tgl_update_produksi));
			// 		$pdf->write2DBarcode($qr_text_produksi, 'QRCODE,L', 150, $y_verifikasi + 10, 15, 15, null, 'N');
			// 		$pdf->SetXY(90, $y_verifikasi + 24);
			// 		$pdf->Cell(135, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
			// 	} else {
			// 		$pdf->SetXY(90, $y_verifikasi + 10);
			// 		$pdf->Cell(135, 5, 'Belum Diverifikasi', 0, 0, 'C');
			// 	}

			// Diketahui Oleh (Produksi) - tanpa barcode
			$pdf->SetXY(90, $y_verifikasi + 5);
			$pdf->Cell(135, 5, 'Diketahui Oleh,', 0, 0, 'C');

			if ($data['larutan']->status_produksi == 1 && !empty($data['larutan']->nama_produksi)) {
				$update_tanggal_produksi = (new DateTime($data['larutan']->tgl_update_produksi))->format('d-m-Y | H:i');

				$pdf->SetFont('times', 'U', 8);
				$pdf->SetXY(90, $y_verifikasi + 10);
				$pdf->Cell(135, 5, $data['larutan']->nama_produksi, 0, 1, 'C');

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

			// Disetujui Oleh (SPV)
			$pdf->SetXY(150, $y_verifikasi + 5);
			$pdf->Cell(189, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['larutan']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . date('d-m-Y | H:i', strtotime($data['larutan']->tgl_update_spv));
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 237, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(170, $y_verifikasi + 24);
			$pdf->Cell(149, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(200, $y_verifikasi);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$filename = "Pemeriksaan Perbaikan Mesin_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}

	public function cetak_audit()
	{
		$tanggal = $this->input->post('tanggal');
		$shift   = $this->input->post('shift');

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$larutan_data = $this->larutan_model->get_audit_by_date($tanggal, $plant, $shift);

		if (!$larutan_data) {
			show_error('Data audit tidak ditemukan, pilih tanggal yang ingin dicetak', 404);
		}

		$data['larutan'] = $larutan_data[0];

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
		$pdf->MultiCell(
			0,
			5,
			'VERIFIKASI PEMBUATAN LARUTAN CLEANING DAN SANITASI - AUDIT',
			0,
			'C'
		);
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');

		$date = new DateTime($data['larutan']->date);
		$formatted_date = date('l, d F Y', $date->getTimestamp());
		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(16);
		$pdf->Write(0, 'Hari / Tanggal : ' . $formatted_date);
		$pdf->Ln();

		$pdf->SetX(16);
		$pdf->Write(0, 'Shift : ' . $data['larutan']->shift);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 9);

		$pdf->Cell(10, 12, 'No.', 1, 0, 'C');
		$pdf->Cell(40, 12, 'NAMA BAHAN', 1, 0, 'C');
		$pdf->Cell(30, 12, 'KADAR YANG', 1, 0, 'C');
		$pdf->Cell(60, 4, 'VERIFIKASI FORMULASI', 1, 0, 'C');
		$pdf->Cell(35, 12, 'KEBUTUHAN', 1, 0, 'C');
		$pdf->Cell(35, 12, 'KETERANGAN', 1, 0, 'C');
		$pdf->Cell(40, 12, 'TINDAKAN KOREKSI', 1, 0, 'C');
		$pdf->Cell(67, 12, 'VERIFIKASI SETELAH TINDAKAN KOREKSI', 1, 0, 'C');
		$pdf->Cell(0, 4, '', 0, 1, 'C');

		$pdf->Cell(50, 0, '', 0, 0, 'L');
		$pdf->Cell(30, 10, 'DIINGINKAN', 0, 0, 'C');

		$pdf->SetFont('times', '', 7);
		$pdf->Cell(20, 8, 'BAHAN KIMIA', 1, 0, 'C');
		$pdf->Cell(20, 8, 'AIR BERSIH', 1, 0, 'C');
		$pdf->Cell(20, 8, 'VOLUME AKHIR', 1, 0, 'C');
		$pdf->Cell(0, 0, '', 0, 1, 'C');

		$pdf->Cell(80, 0, '', 0, 0, 'C');
		$pdf->Cell(20, 6, '(ML)', 0, 0, 'C');
		$pdf->Cell(20, 6, '(ML)', 0, 0, 'C');
		$pdf->Cell(20, 6, '(ML)', 0, 0, 'C');
		$pdf->Cell(10, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$no = 1;

		$pdf->SetFont('times', '', 9);

		foreach ($larutan_data as $larutan) {

			$bahan_list = json_decode($larutan->nama_bahan, true);

			if (!is_array($bahan_list)) {
				continue;
			}

			foreach ($bahan_list as $bahan) {

				$pdf->Cell(10, 8, $no, 1, 0, 'C');
				$pdf->Cell(40, 8, $bahan['bahan'] ?? '-', 1, 0, 'L');
				$pdf->Cell(30, 8, $bahan['kadar'] ?? '-', 1, 0, 'C');
				$pdf->Cell(20, 8, $bahan['bahan_kimia'] ?? '-', 1, 0, 'C');
				$pdf->Cell(20, 8, $bahan['air_bersih'] ?? '-', 1, 0, 'C');
				$pdf->Cell(20, 8, $bahan['volume_akhir'] ?? '-', 1, 0, 'C');
				$pdf->Cell(35, 8, $bahan['kebutuhan'] ?? '-', 1, 0, 'L');
				$pdf->Cell(35, 8, $bahan['keterangan'] ?? '-', 1, 0, 'L');
				$pdf->Cell(40, 8, $bahan['tindakan'] ?? '-', 1, 0, 'L');
				$pdf->Cell(67, 8, $bahan['verifikasi'] ?? '-', 1, 0, 'L');

				$pdf->Ln();

				$no++;
			}
		}

		$pdf->SetY($pdf->GetY() + 3);
		$pdf->SetFont('dejavusans', '', 6);

		$pdf->SetXY(18, $pdf->GetY());
		$pdf->MultiCell(
			90,
			4,
			"✔ = Perbandingan formulasi sesuai\n✘ = Perbandingan formulasi tidak sesuai",
			0,
			'L'
		);

		$pdf->SetXY(105, $pdf->GetY());
		$pdf->MultiCell(
			90,
			4,
			"Pelarut yang digunakan adalah pelarut air",
			0,
			'L'
		);

		$pdf->SetY($pdf->GetY() + 3);
		$pdf->SetFont('times', '', 8);

		$pdf->Cell(10, 3, 'Catatan : ', 0, 1, 'L');

		foreach ($larutan_data as $item) {

			if (!empty($item->catatan)) {

				$pdf->Cell(10, 0, '', 0, 0, 'L');

				$pdf->Cell(
					200,
					0,
					' - ' . $item->catatan,
					0,
					1,
					'L'
				);
			}
		}

		$this->load->model('pegawai_model');

		$data['larutan']->nama_lengkap_qc =
			$this->pegawai_model->get_nama_lengkap(
				$data['larutan']->username
			);

		$data['larutan']->nama_lengkap_spv =
			$this->pegawai_model->get_nama_lengkap(
				$data['larutan']->nama_spv
			);

		$data['larutan']->nama_lengkap_produksi =
			$data['larutan']->nama_produksi;

		$status_verifikasi = true;

		foreach ($larutan_data as $item) {

			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		$y_verifikasi = $pdf->GetY();

		if ($status_verifikasi) {

			// Dibuat Oleh (QC)
			$pdf->SetXY(25, $y_verifikasi + 5);
			$pdf->Cell(95, 5, 'Dibuat Oleh,', 0, 0, 'C');

			$pdf->SetXY(25, $y_verifikasi + 10);
			$pdf->SetFont('times', 'U', 8);
			$pdf->Cell(
				95,
				5,
				$data['larutan']->nama_lengkap_qc,
				0,
				1,
				'C'
			);

			$pdf->SetFont('times', '', 8);
			$pdf->Cell(112, 5, 'QC Inspector', 0, 0, 'C');

			// Diketahui Oleh (Produksi)
			$pdf->SetXY(90, $y_verifikasi + 5);
			$pdf->Cell(135, 5, 'Diketahui Oleh,', 0, 0, 'C');

			if (
				$data['larutan']->status_produksi == 1 &&
				!empty($data['larutan']->nama_produksi)
			) {

				$pdf->SetFont('times', 'U', 8);
				$pdf->SetXY(90, $y_verifikasi + 10);
				$pdf->Cell(
					135,
					5,
					$data['larutan']->nama_produksi,
					0,
					1,
					'C'
				);

				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(90, $y_verifikasi + 15);
				$pdf->Cell(
					135,
					5,
					'Foreman/Forelady Produksi',
					0,
					1,
					'C'
				);
			} else {

				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(90, $y_verifikasi + 10);
				$pdf->Cell(
					135,
					5,
					'Belum Diverifikasi',
					0,
					0,
					'C'
				);
			}

			// Disetujui Oleh (SPV)
			$pdf->SetXY(150, $y_verifikasi + 5);
			$pdf->Cell(
				189,
				5,
				'Disetujui Oleh,',
				0,
				0,
				'C'
			);

			$qr_text =
				"Diverifikasi secara digital oleh,\n" .
				$data['larutan']->nama_lengkap_spv . "\n" .
				"SPV QC Bread Crumb\n" .
				date(
					'd-m-Y | H:i',
					strtotime($data['larutan']->tgl_update_spv)
				);

			$pdf->write2DBarcode(
				$qr_text,
				'QRCODE,L',
				237,
				$y_verifikasi + 10,
				15,
				15,
				null,
				'N'
			);

			$pdf->SetXY(170, $y_verifikasi + 24);
			$pdf->Cell(
				149,
				5,
				'Supervisor QC',
				0,
				0,
				'C'
			);
		} else {

			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(200, $y_verifikasi);
			$pdf->Cell(
				80,
				5,
				'Data Belum Diverifikasi',
				0,
				0,
				'C'
			);
		}

		$pdf->setPrintFooter(false);

		$filename =
			"Pembuatan_Larutan_Audit_{$formatted_date2}.pdf";

		$pdf->Output($filename, 'I');
	}
}
