<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pemeriksaan_gabungan extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('kebersihankaryawan_model');
		$this->load->model('sanitasi_model');
		$this->load->model('pegawai_model');
	}

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal') ?: $this->input->post('date');

		if (empty($tanggal)) {
			show_error('Tanggal wajib dipilih.', 404);
		}

		$plant = $this->session->userdata('plant');

		if (empty($plant)) {
			show_error('Plant tidak ditemukan.', 403);
		}

		$shifts = $this->kebersihankaryawan_model
			->get_shift_by_date($tanggal, $plant);

		if (empty($shifts)) {
			show_error('Data tidak ditemukan.', 404);
		}

		// Ambil data
		/*$kebersihan = $this->kebersihankaryawan_model
			->get_by_date($tanggal, $plant);

		$sanitasi = $this->sanitasi_model
			->get_by_date($tanggal, $plant);

		if (empty($kebersihan) && empty($sanitasi)) {
			show_error('Data tidak ditemukan.', 404);
		}*/

		// Header PDF
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(10, 14, 10);
		//$pdf->AddPage();

		// =========================
		// HEADER PDF
		// =========================

		foreach ($shifts as $index => $row) {

			$shift = $row->shift;

			$kebersihan = $this->kebersihankaryawan_model
				->get_by_date($tanggal, $plant, $shift);

			$sanitasi = $this->sanitasi_model
				->get_by_date($tanggal, $plant, $shift);

			$pdf->AddPage();

			// Logo kecil kiri atas
			$logo_path = FCPATH . 'assets/img/cpi-logo.png';
			if (file_exists($logo_path)) {
				$pdf->Image($logo_path, 10, 10, 10); // kecil
			}

			$pdf->SetFont('times', 'B', 7);

			$pdf->SetXY(25, 10);
			$pdf->Cell(0, 3, 'PT. CHAROEN POKPHAND INDONESIA', 0, 1);

			$pdf->SetXY(25, 13);
			$pdf->Cell(0, 3, 'FOOD DIVISION', 0, 1);

			$pdf->SetXY(25, 16);
			$pdf->Cell(0, 3, 'SALATIGA - INDONESIA', 0, 1);

			// Judul form
			$pdf->SetFont('times', 'B', 11);
			$pdf->SetY(28);

			$pdf->Cell(
				0,
				6,
				'PEMERIKSAAN KEBERSIHAN KARYAWAN DAN KONTROL SANITASI',
				0,
				1,
				'C'
			);

			$pdf->Ln(3);

			// ======================================================
			// FORMAT TANGGAL INDONESIA
			// ======================================================

			$datetime = new DateTime($tanggal);

			$hari = [
				'Sunday'    => 'Minggu',
				'Monday'    => 'Senin',
				'Tuesday'   => 'Selasa',
				'Wednesday' => 'Rabu',
				'Thursday'  => 'Kamis',
				'Friday'    => 'Jumat',
				'Saturday'  => 'Sabtu'
			];

			$bulan = [
				'January'   => 'Januari',
				'February'  => 'Februari',
				'March'     => 'Maret',
				'April'     => 'April',
				'May'       => 'Mei',
				'June'      => 'Juni',
				'July'      => 'Juli',
				'August'    => 'Agustus',
				'September' => 'September',
				'October'   => 'Oktober',
				'November'  => 'November',
				'December'  => 'Desember'
			];

			$formatted_date =
				$hari[$datetime->format('l')] . ', ' .
				$datetime->format('d') . ' ' .
				$bulan[$datetime->format('F')] . ' ' .
				$datetime->format('Y');

			// ======================================================
			// INFORMASI HARI/TANGGAL & SHIFT
			// ======================================================

			$pdf->SetFont('times', '', 8);

			$pdf->Ln(10);

			$pdf->Cell(
				90,
				5,
				'Hari/Tanggal : ' . $formatted_date,
				0,
				0
			);

			$pdf->Cell(
				50,
				5,
				'Shift : ' . $shift,
				//'Shift : ' . ($kebersihan[0]->shift ?? '-'),
				0,
				1
			);

			$pdf->Ln(3);
			$pdf->SetFont('times', 'B', 9);
			$pdf->Cell(0, 5, 'KEBERSIHAN KARYAWAN', 0, 1, 'L');
			$pdf->Ln(2);

			$pdf->SetFont('times', 'B', 8);

			// BARIS HEADER ATAS
			$pdf->Cell(10, 12, 'No.', 1, 0, 'C');
			$pdf->Cell(25, 12, 'Nama', 1, 0, 'C');
			$pdf->Cell(20, 12, 'Bagian', 1, 0, 'C');

			$pdf->Cell(40, 6, 'GMP Karyawan', 1, 0, 'C');
			$pdf->Cell(50, 6, 'Verifikasi Kelengkapan Kerja', 1, 0, 'C');

			$pdf->Cell(25, 12, 'Tindakan Koreksi', 1, 0, 'C');
			$pdf->Cell(25, 12, 'Keterangan', 1, 0, 'C'); // JANGAN 1

			// Simpan posisi
			$x = $pdf->GetX();
			$y = $pdf->GetY();

			// Pindah ke bawah header GMP
			$pdf->SetXY(65, $y + 6);

			// GMP
			$pdf->Cell(10, 6, 'Kuku', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Kosm', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Perh', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Lain', 1, 0, 'C');

			// Verifikasi
			$pdf->Cell(10, 6, 'Srgm', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Apron', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Hair', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Mask', 1, 0, 'C');
			$pdf->Cell(10, 6, 'Boot', 1, 1, 'C');

			$no = 1;

			foreach ($kebersihan as $item) {

				$pdf->SetFont('times', '', 7);

				$pdf->Cell(10, 6, $no, 1, 0, 'C');
				$pdf->Cell(25, 6, $item->nama, 1, 0, 'L');
				$pdf->Cell(20, 6, $item->bagian, 1, 0, 'C');

				$pdf->SetFont('dejavusans', '', 9);

				// GMP Karyawan
				$pdf->Cell(10, 6, ($item->tangan_kuku == 'ok') ? '✔' : (($item->tangan_kuku == 'tidak oke') ? '✘' : '−'), 1, 0, 'C');
				$pdf->Cell(10, 6, ($item->kosmetik == 'ok') ? '✔' : (($item->kosmetik == 'tidak oke') ? '✘' : '−'), 1, 0, 'C');
				$pdf->Cell(10, 6, ($item->perhiasan == 'ok') ? '✔' : (($item->perhiasan == 'tidak oke') ? '✘' : '−'), 1, 0, 'C');
				$pdf->Cell(10, 6, '-', 1, 0, 'C');

				// Verifikasi Kelengkapan Kerja
				$pdf->Cell(10, 6, ($item->seragam == 'ok') ? '✔' : (($item->seragam == 'tidak oke') ? '✘' : '−'), 1, 0, 'C');
				$pdf->Cell(10, 6, ($item->apron == 'ok') ? '✔' : (($item->apron == 'tidak oke') ? '✘' : '−'), 1, 0, 'C');
				$pdf->Cell(10, 6, ($item->topi_hairnet == 'ok') ? '✔' : (($item->topi_hairnet == 'tidak oke') ? '✘' : '−'), 1, 0, 'C');
				$pdf->Cell(10, 6, ($item->masker == 'ok') ? '✔' : (($item->masker == 'tidak oke') ? '✘' : '−'), 1, 0, 'C');
				$pdf->Cell(10, 6, ($item->sepatu == 'ok') ? '✔' : (($item->sepatu == 'tidak oke') ? '✘' : '−'), 1, 0, 'C');

				$pdf->SetFont('times', '', 7);

				$pdf->Cell(
					25,
					6,
					!empty($item->tindakan) ? $item->tindakan : '-',
					1,
					0,
					'L'
				);

				$pdf->Cell(
					25,
					6,
					!empty($item->catatan) ? '*' : '-',
					1,
					1,
					'C'
				);

				$no++;
			}



			// ======================================================
			// KETERANGAN KEBERSIHAN
			// ======================================================

			$pdf->SetY($pdf->GetY() + 3);
			$pdf->SetFont('times', '', 7);

			$pdf->Cell(10, 3, 'Keterangan : ', 0, 1, 'L');

			$pdf->Cell(30, 3, 'V : OK', 0, 1, 'L');
			$pdf->Cell(30, 3, 'X : Tidak OK', 0, 1, 'L');
			$pdf->Cell(30, 3, '- : Tidak ada atau tidak digunakan', 0, 1, 'L');

			$pdf->Ln();


			// ======================================================
			// CATATAN KEBERSIHAN
			// ======================================================

			$pdf->SetFont('times', '', 8);
			$pdf->Cell(15, 4, 'Catatan :', 0, 1, 'L');

			foreach ($kebersihan as $item) {

				if (!empty($item->catatan)) {

					$pdf->Cell(10, 4, '', 0, 0);
					$pdf->MultiCell(180, 4, '- ' . $item->catatan, 0, 'L');
				}
			}

			$pdf->Ln(3);

			// ======================================================
			// TABEL SANITASI
			// ======================================================

			$pdf->Ln(5);
			$pdf->SetFont('times', 'B', 9);
			$pdf->Cell(0, 5, 'KONTROL SANITASI', 0, 1, 'L');
			$pdf->Ln(2);

			$pdf->SetFont('times', '', 8);

			// Header
			$pdf->Cell(15, 10, 'Pukul', 1, 0, 'C');
			$pdf->Cell(30, 10, 'Area', 1, 0, 'C');
			$pdf->Cell(50, 5, 'Kadar Klorin (ppm)', 1, 0, 'C');
			$pdf->Cell(20, 10, 'Suhu Air', 1, 0, 'C');
			$pdf->Cell(37, 10, 'Keterangan', 1, 0, 'C');
			$pdf->Cell(40, 10, 'Tindakan Koreksi', 1, 0, 'C');
			$pdf->Cell(10, 5, '', 0, 1);

			$pdf->Cell(45, 5, '', 0, 0);
			$pdf->Cell(25, 5, 'Standar', 1, 0, 'C');
			$pdf->Cell(25, 5, 'Aktual', 1, 0, 'C');
			$pdf->Cell(77, 0, '', 0, 0);
			$pdf->Cell(10, 5, '', 0, 1);

			foreach ($sanitasi as $item) {

				$jam = date('H:i', strtotime($item->waktu));

				$areas = json_decode($item->area);

				if ($areas && is_array($areas)) {

					$jumlah_area = count($areas);
					$tinggi_pukul = $jumlah_area * 5;

					$y_awal = $pdf->GetY();

					// Kolom Pukul (merge vertikal)
					$pdf->Cell(15, $tinggi_pukul, $jam, 1, 0, 'C');

					// Simpan posisi setelah kolom pukul
					$x_setelah_pukul = $pdf->GetX();

					$first = true;

					foreach ($areas as $area) {

						if (!$first) {
							$pdf->SetX($x_setelah_pukul);
						}

						$pdf->SetFont('times', '', 8);

						$pdf->Cell(30, 5, $area->sub_area ?? '-', 1, 0, 'L');
						$pdf->Cell(25, 5, $area->standar ?? '-', 1, 0, 'C');
						$pdf->Cell(25, 5, $area->aktual ?? '-', 1, 0, 'C');
						$pdf->Cell(20, 5, $area->suhu_air ?? '-', 1, 0, 'C');
						$pdf->Cell(37, 5, $area->keterangan ?? '-', 1, 0, 'C');
						$pdf->Cell(40, 5, $area->tindakan ?? '-', 1, 1, 'C');

						$first = false;
					}

					// Pastikan posisi Y berada di bawah grup area
					$pdf->SetY($y_awal + $tinggi_pukul);
				}
			}

			$pdf->SetFont('times', 'BI', 7);
			$pdf->Cell(190, 5, 'QB 01/00', 0, 1, 'R');

			$pdf->Ln(2);

			// ======================================================
			// CATATAN SANITASI
			// ======================================================

			$pdf->SetFont('times', '', 8);
			$pdf->Cell(15, 4, 'Catatan :', 0, 1, 'L');

			foreach ($sanitasi as $item) {

				if (!empty($item->catatan)) {

					$pdf->Cell(10, 4, '', 0, 0);
					$pdf->MultiCell(180, 4, '- ' . $item->catatan, 0, 'L');
				}
			}

			$pdf->Ln(5);

			// ======================================================
			// QR CODE
			// ======================================================

			$status_verifikasi = true;

			foreach ($kebersihan as $item) {
				if ($item->status_spv != '1') {
					$status_verifikasi = false;
					break;
				}
			}

			foreach ($sanitasi as $item) {
				if ($item->status_spv != '1') {
					$status_verifikasi = false;
					break;
				}
			}

			$y_ttd   = $pdf->GetY() + 5;
			$qr_size = 15;

			$qc_usernames = [];
			$qc_created_at = null;

			foreach ($kebersihan as $item) {

				if (!empty($item->username)) {
					$qc_usernames[] = $item->username;
				}

				if (!$qc_created_at && !empty($item->created_at)) {
					$qc_created_at = $item->created_at;
				}
			}

			foreach ($sanitasi as $item) {

				if (!empty($item->username)) {
					$qc_usernames[] = $item->username;
				}

				if (!$qc_created_at && !empty($item->created_at)) {
					$qc_created_at = $item->created_at;
				}
			}

			$qc_usernames = array_unique($qc_usernames);

			$qc_nama = [];

			foreach ($qc_usernames as $username) {

				$nama = $this->pegawai_model->get_nama_lengkap($username);

				if (!empty($nama)) {
					$qc_nama[] = $nama;
				}
			}

			$qc_text = implode(', ', array_unique($qc_nama));

			$qc_tanggal = $qc_created_at
				? (new DateTime($qc_created_at))->format('d-m-Y | H:i')
				: '-';

			$qr_qc_text =
				"Dibuat secara digital oleh,\n" .
				$qc_text . "\nQC Inspector\n" .
				$qc_tanggal;

			$produksi = !empty($kebersihan)
				? $kebersihan[0]
				: $sanitasi[0];

			$qr_produksi_text = null;

			if (!empty($produksi->nama_produksi)) {

				$tgl = (new DateTime($produksi->tgl_update_produksi))
					->format('d-m-Y | H:i');

				$qr_produksi_text =
					"Diketahui secara digital oleh,\n" .
					$produksi->nama_produksi .
					"\nForeman/Forelady Produksi\n" .
					$tgl;
			}

			$spv = !empty($kebersihan)
				? $kebersihan[0]
				: $sanitasi[0];

			$nama_spv = $this->pegawai_model->get_nama_lengkap($spv->nama_spv);

			$spv_tanggal = !empty($spv->tgl_update_spv)
				? (new DateTime($spv->tgl_update_spv))->format('d-m-Y | H:i')
				: '-';

			$qr_spv_text =
				"Disetujui secara digital oleh,\n" .
				$nama_spv .
				"\nSupervisor QC Bread Crumb\n" .
				$spv_tanggal;

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

				$pdf->Cell(0, 6, 'Data Belum Diverifikasi', 0, 1, 'C');

				$pdf->SetTextColor(0, 0, 0);
			}
		}

		$pdf->Output("Pemeriksaan_Gabungan.pdf", 'I');
	}
}
