<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Kekuatanmagnet extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('kekuatanmagnet_model');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'kekuatanmagnet' => $this->kekuatanmagnet_model->get_data_by_plant(),
		);

		$this->active_nav = 'kekuatanmagnet';
		$this->render('form/kekuatanmagnet/kekuatanmagnet', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'kekuatanmagnet' => $this->kekuatanmagnet_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'kekuatanmagnet';
		$this->render('form/kekuatanmagnet/kekuatanmagnet-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->kekuatanmagnet_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->kekuatanmagnet_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Kekuatan Magnet Trap berhasil di simpan');
				redirect('kekuatanmagnet');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Kekuatan Magnet Trap gagal di simpan');
				redirect('kekuatanmagnet');
			}
		}

		$this->active_nav = 'kekuatanmagnet';
		$this->render('form/kekuatanmagnet/kekuatanmagnet-tambah');
	}

	public function edit($uuid)
	{
		$rules = $this->kekuatanmagnet_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->kekuatanmagnet_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Kekuatan Magnet Trap berhasil di Update');
				redirect('kekuatanmagnet');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Kekuatan Magnet Trap gagal di Update');
				redirect('kekuatanmagnet');
			}
		}

		$data = array(
			'kekuatanmagnet' => $this->kekuatanmagnet_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'kekuatanmagnet';
		$this->render('form/kekuatanmagnet/kekuatanmagnet-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('kekuatanmagnet');
		}

		$deleted = $this->kekuatanmagnet_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('kekuatanmagnet');
	}

	public function verifikasi()
	{
		$data = array(
			'kekuatanmagnet' => $this->kekuatanmagnet_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-kekuatanmagnet';
		$this->render('form/kekuatanmagnet/kekuatanmagnet-verifikasi', $data);
	}


	public function status($uuid)
	{
		$rules = $this->kekuatanmagnet_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->kekuatanmagnet_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Kekuatan Magnet Trap berhasil di Update');
				redirect('kekuatanmagnet/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Kekuatan Magnet Trap gagal di Update');
				redirect('kekuatanmagnet/verifikasi');
			}
		}

		$data = array(
			'kekuatanmagnet' => $this->kekuatanmagnet_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-kekuatanmagnet';
		$this->render('form/kekuatanmagnet/kekuatanmagnet-status', $data);
	}

	/*
    |--------------------------------------------------------------------------
    | DAFTAR AUDIT
    |--------------------------------------------------------------------------
    */
	public function audit()
	{
		$data = [
			'kekuatanmagnet' =>
			$this->kekuatanmagnet_model->get_data_audit_by_plant()
		];

		$this->active_nav = 'audit-kekuatanmagnet';

		$this->render(
			'form/kekuatanmagnet/kekuatanmagnet-audit',
			$data
		);
	}

	/*
    |--------------------------------------------------------------------------
    | DETAIL AUDIT
    |--------------------------------------------------------------------------
    */
	public function detail_audit($uuid)
	{
		$audit = $this->kekuatanmagnet_model
			->get_audit_by_uuid($uuid);

		if (!$audit) {
			$this->session->set_flashdata(
				'error',
				'Data Audit tidak ditemukan atau bukan milik plant aktif.'
			);

			redirect('kekuatanmagnet/audit');
			return;
		}

		$data = [
			'kekuatanmagnet' => $audit,
			'mode' => 'audit'
		];

		$this->active_nav = 'audit-kekuatanmagnet';

		$this->render(
			'form/kekuatanmagnet/kekuatanmagnet-detail',
			$data
		);
	}

	/*
    |--------------------------------------------------------------------------
    | EDIT AUDIT
    |--------------------------------------------------------------------------
    */
	public function edit_audit($uuid)
	{
		$audit = $this->kekuatanmagnet_model
			->get_audit_by_uuid($uuid);

		if (!$audit) {
			$this->session->set_flashdata(
				'error',
				'Data Audit tidak ditemukan atau bukan milik plant aktif.'
			);

			redirect('kekuatanmagnet/audit');
			return;
		}

		$rules = $this->kekuatanmagnet_model->rules();

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->kekuatanmagnet_model
				->update_audit($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success',
					'Data Audit berhasil diperbarui.'
				);

				redirect('kekuatanmagnet/audit');
				return;
			}

			$this->session->set_flashdata(
				'error',
				'Data Audit gagal diperbarui.'
			);

			redirect('kekuatanmagnet/audit');
			return;
		}

		$data = [
			'kekuatanmagnet' => $audit,
			'mode' => 'audit'
		];

		$this->active_nav = 'audit-kekuatanmagnet';

		$this->render(
			'form/kekuatanmagnet/kekuatanmagnet-edit',
			$data
		);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'kekuatanmagnet' => $this->kekuatanmagnet_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-kekuatanmagnet', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/kekuatanmagnet/kekuatanmagnet-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->kekuatanmagnet_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->kekuatanmagnet_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Kekuatan Magnet Trap berhasil di Update');
	// 			redirect('kekuatanmagnet/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Kekuatan Magnet Trap gagal di Update');
	// 			redirect('kekuatanmagnet/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'kekuatanmagnet' => $this->kekuatanmagnet_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-kekuatanmagnet');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/kekuatanmagnet/kekuatanmagnet-statusprod', $data);
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

		$kekuatanmagnet_data = $this->kekuatanmagnet_model->get_by_date($tanggal, $plant);
		$kekuatanmagnet_data_verif = $this->kekuatanmagnet_model->get_last_verif_by_date($tanggal, $plant);

		if (!$kekuatanmagnet_data || !$kekuatanmagnet_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['kekuatanmagnet'] = $kekuatanmagnet_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 10, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 11);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 35);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(8, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN KEKUATAN MAGNET TRAP', 0, 'C');
		$pdf->Ln(4);

		$pdf->SetFont('times', '', 9);

		$pdf->Cell(30, 12, 'Hari/Tanggal', 1, 0, 'C');
		$pdf->Cell(51, 12, 'Nama Alat', 1, 0, 'C');
		$pdf->Cell(35, 12, 'Nilai Pengukuran (Gauss)', 1, 0, 'C');
		$pdf->Cell(40, 12, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(38, 6, 'Paraf', 1, 1, 'C');

		$pdf->Cell(156, 12, '', 0, 0, 'L');
		$pdf->Cell(19, 6, 'QC', 1, 0, 'C');
		$pdf->Cell(19, 6, 'Prod', 1, 1, 'C');

		foreach ($kekuatanmagnet_data as $kekuatanmagnet) {
			setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');

			$tanggal = $kekuatanmagnet->date;
			$date = new DateTime($tanggal);
			$formatted_date = strftime('%A, %d %B %Y', $date->getTimestamp());

			// Simpan posisi awal
			$x = $pdf->GetX();
			$y = $pdf->GetY();

			// Tentukan tinggi baris berdasarkan kolom yang bisa wrap
			$keterangan = !empty($kekuatanmagnet->keterangan) ? $kekuatanmagnet->keterangan : '-';

			$h1 = $pdf->getStringHeight(30, $formatted_date);
			$h2 = $pdf->getStringHeight(51, $kekuatanmagnet->nama_alat);
			$h3 = $pdf->getStringHeight(35, $kekuatanmagnet->nilai);
			$h4 = $pdf->getStringHeight(40, $keterangan);
			$h5 = $pdf->getStringHeight(19, $kekuatanmagnet->username);
			$h6 = $pdf->getStringHeight(19, $kekuatanmagnet->nama_produksi);

			$rowHeight = max($h1, $h2, $h3, $h4, $h5, $h6);

			$pdf->SetFont('times', '', 8);

			// Tanggal
			$pdf->MultiCell(30, $rowHeight, $formatted_date, 1, 'C', false, 0, $x, $y);

			// Nama Alat
			$x += 30;
			$pdf->MultiCell(51, $rowHeight, $kekuatanmagnet->nama_alat, 1, 'C', false, 0, $x, $y);

			// Nilai
			$x += 51;
			$pdf->MultiCell(35, $rowHeight, $kekuatanmagnet->nilai, 1, 'C', false, 0, $x, $y);

			// Keterangan
			$x += 35;
			$pdf->MultiCell(40, $rowHeight, $keterangan, 1, 'C', false, 0, $x, $y);

			// Username
			$x += 40;
			$pdf->MultiCell(19, $rowHeight, $kekuatanmagnet->username, 1, 'C', false, 0, $x, $y);

			// Produksi
			$x += 19;
			$pdf->MultiCell(19, $rowHeight, $kekuatanmagnet->nama_produksi, 1, 'C', false, 1, $x, $y);

			// Pindah ke baris berikutnya
			$pdf->SetY($y + $rowHeight);
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(190, 5, 'QB 20/00', 0, 1, 'R');

		$this->load->model('pegawai_model');
		$data['kekuatanmagnet']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['kekuatanmagnet']->username);
		$data['kekuatanmagnet']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['kekuatanmagnet']->nama_spv);
		$data['kekuatanmagnet']->nama_lengkap_produksi = $data['kekuatanmagnet']->nama_produksi;

		$pdf->SetY($pdf->GetY() + 2);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($kekuatanmagnet_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($kekuatanmagnet_data as $item) {
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
		// 			$pdf->Cell(35, 5, $data['kekuatanmagnet']->nama_lengkap_qc, 0, 1, 'C');
		// 			$pdf->SetFont('times', '', 8); 
		// 			$pdf->Cell(65, 5, 'QC Inspector', 0, 0, 'C');

		// 		// Diketahui oleh (Produksi)
		// 			// $pdf->SetXY(90, $y_verifikasi + 5);
		// 			// $pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');
		// 			// if ($data['kekuatanmagnet']->status_produksi == 1 && !empty($data['kekuatanmagnet']->nama_produksi)) {
		// 			// 	$update_tanggal_produksi = (new DateTime($data['kekuatanmagnet']->tgl_update_produksi))->format('d-m-Y | H:i');
		// 			// 	$qr_text_produksi = "Diketahui secara digital oleh,\n" . $data['kekuatanmagnet']->nama_lengkap_produksi . "\nForeman/Forelady Produksi\n" . $update_tanggal_produksi;
		// 			// 	$pdf->write2DBarcode($qr_text_produksi, 'QRCODE,L', 100, $y_verifikasi + 10, 15, 15, null, 'N');
		// 			// 	$pdf->SetXY(90, $y_verifikasi + 24);
		// 			// 	$pdf->Cell(35, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
		// 			// } else {
		// 			// 	$pdf->SetXY(90, $y_verifikasi + 10);
		// 			// 	$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
		// 			// }

		// // Diketahui Oleh (Produksi) - tanpa barcode
		// 			$pdf->SetXY(90, $y_verifikasi + 5);
		// 			$pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');

		// 			if ($data['kekuatanmagnet']->status_produksi == 1 && !empty($data['kekuatanmagnet']->nama_produksi)) {
		// 				$update_tanggal_produksi = (new DateTime($data['kekuatanmagnet']->tgl_update_produksi))->format('d-m-Y | H:i');

		// 				$pdf->SetFont('times', 'U', 8);
		// 				$pdf->SetXY(90, $y_verifikasi + 10);
		// 				$pdf->Cell(35, 5, $data['kekuatanmagnet']->nama_produksi, 0, 1, 'C');

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
		// 			$update_tanggal = (new DateTime($data['kekuatanmagnet']->tgl_update_spv))->format('d-m-Y | H:i');
		// 			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['kekuatanmagnet']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
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

		foreach ($kekuatanmagnet_data as $item) {
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

		if (!empty($data['kekuatanmagnet']->nama_lengkap_produksi) && !empty($data['kekuatanmagnet']->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($data['kekuatanmagnet']->tgl_update_produksi ?? $data['kekuatanmagnet']->tgl_update_produksi))
				->format('d-m-Y | H:i');

			$qr_produksi_text = "Diketahui secara digital oleh,\n"
				. $data['kekuatanmagnet']->nama_lengkap_produksi . "\n"
				. "Foreman/Forelady Produksi\n"
				. $prod_tanggal;
		}

		$spv_tanggal = !empty($data['kekuatanmagnet']->tgl_update_spv)
			? (new DateTime($data['kekuatanmagnet']->tgl_update_spv))->format('d-m-Y | H:i')
			: '-';

		$qr_spv_text = "Disetujui secara digital oleh,\n"
			. $data['kekuatanmagnet']->nama_lengkap_spv . "\n"
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
			$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
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
		$filename = "Pemeriksaan Magnet Trap_{$currentDate}.pdf";
		$pdf->Output($filename, 'I');
	}

	public function cetak_audit()
	{
		$tanggal = $this->input->post('tanggal');

		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$kekuatanmagnet_data = $this->kekuatanmagnet_model->get_audit_by_date($tanggal, $plant);
		$kekuatanmagnet_data_verif = $this->kekuatanmagnet_model->get_last_verif_by_date($tanggal, $plant);

		if (!$kekuatanmagnet_data || !$kekuatanmagnet_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['kekuatanmagnet'] = $kekuatanmagnet_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 10, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 11);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 35);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(8, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN KEKUATAN MAGNET TRAP', 0, 'C');
		$pdf->Ln(4);

		$pdf->SetFont('times', '', 9);

		$pdf->Cell(30, 12, 'Hari/Tanggal', 1, 0, 'C');
		$pdf->Cell(51, 12, 'Nama Alat', 1, 0, 'C');
		$pdf->Cell(35, 12, 'Nilai Pengukuran (Gauss)', 1, 0, 'C');
		$pdf->Cell(40, 12, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(38, 6, 'Paraf', 1, 1, 'C');

		$pdf->Cell(156, 12, '', 0, 0, 'L');
		$pdf->Cell(19, 6, 'QC', 1, 0, 'C');
		$pdf->Cell(19, 6, 'Prod', 1, 1, 'C');

		foreach ($kekuatanmagnet_data as $kekuatanmagnet) {
			setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');

			$tanggal = $kekuatanmagnet->date;
			$date = new DateTime($tanggal);
			$timestamp = strtotime($tanggal);

			$bulan = [
				1 => 'Januari',
				2 => 'Februari',
				3 => 'Maret',
				4 => 'April',
				5 => 'Mei',
				6 => 'Juni',
				7 => 'Juli',
				8 => 'Agustus',
				9 => 'September',
				10 => 'Oktober',
				11 => 'November',
				12 => 'Desember'
			];

			$formatted_date = date('d', $timestamp) . ' ' .
				$bulan[(int) date('n', $timestamp)] . ' ' .
				date('Y', $timestamp);

			$formatted_date2 = date('d-m-Y', $timestamp);

			// Simpan posisi awal
			$x = $pdf->GetX();
			$y = $pdf->GetY();

			// Tentukan tinggi baris berdasarkan kolom yang bisa wrap
			$keterangan = !empty($kekuatanmagnet->keterangan) ? $kekuatanmagnet->keterangan : '-';

			$h1 = $pdf->getStringHeight(30, $formatted_date);
			$h2 = $pdf->getStringHeight(51, $kekuatanmagnet->nama_alat);
			$h3 = $pdf->getStringHeight(35, $kekuatanmagnet->nilai);
			$h4 = $pdf->getStringHeight(40, $keterangan);
			$h5 = $pdf->getStringHeight(19, $kekuatanmagnet->username);
			$h6 = $pdf->getStringHeight(19, $kekuatanmagnet->nama_produksi);

			$rowHeight = max($h1, $h2, $h3, $h4, $h5, $h6);

			$pdf->SetFont('times', '', 8);

			// Tanggal
			$pdf->MultiCell(30, $rowHeight, $formatted_date, 1, 'C', false, 0, $x, $y);

			// Nama Alat
			$x += 30;
			$pdf->MultiCell(51, $rowHeight, $kekuatanmagnet->nama_alat, 1, 'C', false, 0, $x, $y);

			// Nilai
			$x += 51;
			$pdf->MultiCell(35, $rowHeight, $kekuatanmagnet->nilai, 1, 'C', false, 0, $x, $y);

			// Keterangan
			$x += 35;
			$pdf->MultiCell(40, $rowHeight, $keterangan, 1, 'C', false, 0, $x, $y);

			// Username
			$x += 40;
			$pdf->MultiCell(19, $rowHeight, $kekuatanmagnet->username, 1, 'C', false, 0, $x, $y);

			// Produksi
			$x += 19;
			$pdf->MultiCell(19, $rowHeight, $kekuatanmagnet->nama_produksi, 1, 'C', false, 1, $x, $y);

			// Pindah ke baris berikutnya
			$pdf->SetY($y + $rowHeight);
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(190, 5, 'QB 20/00', 0, 1, 'R');

		$this->load->model('pegawai_model');
		$data['kekuatanmagnet']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['kekuatanmagnet']->username);
		$data['kekuatanmagnet']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['kekuatanmagnet']->nama_spv);
		$data['kekuatanmagnet']->nama_lengkap_produksi = $data['kekuatanmagnet']->nama_produksi;

		$pdf->SetY($pdf->GetY() + 2);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($kekuatanmagnet_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($kekuatanmagnet_data as $item) {
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
		// 			$pdf->Cell(35, 5, $data['kekuatanmagnet']->nama_lengkap_qc, 0, 1, 'C');
		// 			$pdf->SetFont('times', '', 8); 
		// 			$pdf->Cell(65, 5, 'QC Inspector', 0, 0, 'C');

		// 		// Diketahui oleh (Produksi)
		// 			// $pdf->SetXY(90, $y_verifikasi + 5);
		// 			// $pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');
		// 			// if ($data['kekuatanmagnet']->status_produksi == 1 && !empty($data['kekuatanmagnet']->nama_produksi)) {
		// 			// 	$update_tanggal_produksi = (new DateTime($data['kekuatanmagnet']->tgl_update_produksi))->format('d-m-Y | H:i');
		// 			// 	$qr_text_produksi = "Diketahui secara digital oleh,\n" . $data['kekuatanmagnet']->nama_lengkap_produksi . "\nForeman/Forelady Produksi\n" . $update_tanggal_produksi;
		// 			// 	$pdf->write2DBarcode($qr_text_produksi, 'QRCODE,L', 100, $y_verifikasi + 10, 15, 15, null, 'N');
		// 			// 	$pdf->SetXY(90, $y_verifikasi + 24);
		// 			// 	$pdf->Cell(35, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
		// 			// } else {
		// 			// 	$pdf->SetXY(90, $y_verifikasi + 10);
		// 			// 	$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
		// 			// }

		// // Diketahui Oleh (Produksi) - tanpa barcode
		// 			$pdf->SetXY(90, $y_verifikasi + 5);
		// 			$pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');

		// 			if ($data['kekuatanmagnet']->status_produksi == 1 && !empty($data['kekuatanmagnet']->nama_produksi)) {
		// 				$update_tanggal_produksi = (new DateTime($data['kekuatanmagnet']->tgl_update_produksi))->format('d-m-Y | H:i');

		// 				$pdf->SetFont('times', 'U', 8);
		// 				$pdf->SetXY(90, $y_verifikasi + 10);
		// 				$pdf->Cell(35, 5, $data['kekuatanmagnet']->nama_produksi, 0, 1, 'C');

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
		// 			$update_tanggal = (new DateTime($data['kekuatanmagnet']->tgl_update_spv))->format('d-m-Y | H:i');
		// 			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['kekuatanmagnet']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
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

		foreach ($kekuatanmagnet_data as $item) {
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

		if (!empty($data['kekuatanmagnet']->nama_lengkap_produksi) && !empty($data['kekuatanmagnet']->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($data['kekuatanmagnet']->tgl_update_produksi ?? $data['kekuatanmagnet']->tgl_update_produksi))
				->format('d-m-Y | H:i');

			$qr_produksi_text = "Diketahui secara digital oleh,\n"
				. $data['kekuatanmagnet']->nama_lengkap_produksi . "\n"
				. "Foreman/Forelady Produksi\n"
				. $prod_tanggal;
		}

		$spv_tanggal = !empty($data['kekuatanmagnet']->tgl_update_spv)
			? (new DateTime($data['kekuatanmagnet']->tgl_update_spv))->format('d-m-Y | H:i')
			: '-';

		$qr_spv_text = "Disetujui secara digital oleh,\n"
			. $data['kekuatanmagnet']->nama_lengkap_spv . "\n"
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
			$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
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
		$filename = "Pemeriksaan Magnet Trap_{$currentDate}.pdf";
		$pdf->Output($filename, 'I');
	}
}
