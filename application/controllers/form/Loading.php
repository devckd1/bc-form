<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Loading extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('auth_model');
		$this->load->model('loading_model');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'loading' => $this->loading_model->get_data_by_plant()
		);

		$this->active_nav = 'loading';
		$this->render('form/loading/loading', $data);
	}

	public function detail($uuid)
	{
		$loading = $this->loading_model->get_by_uuid($uuid);

		if (!$loading || (int) $loading->is_audit !== 0) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Pemeriksaan Loading Produk tidak ditemukan.'
			);
			redirect('loading');
			return;
		}

		$data = [
			'loading' => $loading,
			'mode'    => 'qc'
		];

		$this->active_nav = 'loading';
		$this->render('form/loading/loading-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->loading_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->loading_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Loading Produk berhasil di simpan');
				redirect('loading');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Loading Produk gagal di simpan');
				redirect('loading');
			}
		}

		$this->active_nav = 'loading';
		$this->render('form/loading/loading-tambah');
	}

	public function edit($uuid)
	{
		$loading = $this->loading_model->get_by_uuid($uuid);

		if (!$loading || (int) $loading->is_audit !== 0) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Pemeriksaan Loading Produk tidak ditemukan.'
			);
			redirect('loading');
			return;
		}

		$rules = $this->loading_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->loading_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Pemeriksaan Loading Produk berhasil diupdate.'
				);
				redirect('loading');
				return;
			}

			$this->session->set_flashdata(
				'error_msg',
				'Data Pemeriksaan Loading Produk gagal diupdate.'
			);
		}

		$data = [
			'loading'     => $loading,
			'bagian_list' => $this->loading_model->get_bagian_by_uuid($uuid),
			'mode'        => 'qc'
		];

		$this->active_nav = 'loading';
		$this->render('form/loading/loading-edit', $data);
	}
	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('loading');
		}

		$deleted = $this->loading_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('loading');
	}

	public function verifikasi()
	{
		$data = array(
			'loading' => $this->loading_model->get_data_by_plant(),
			'active_nav' => 'verifikasi-loading',
		);

		$this->active_nav = 'verifikasi-loading';
		$this->render('form/loading/loading-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->loading_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->loading_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Loading Produk berhasil di Update');
				redirect('loading/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Loading Produk gagal di Update');
				redirect('loading/verifikasi');
			}
		}

		$data = array(
			'loading' => $this->loading_model->get_by_uuid($uuid),
			'active_nav' => 'verifikasi-loading'
		);

		$this->active_nav = 'verifikasi-loading';
		$this->render('form/loading/loading-status', $data);
	}

	public function audit()
	{
		$data = array(
			'data' => $this->loading_model->get_audit_by_plant()
		);

		$this->active_nav = 'loading';

		$this->render(
			'form/loading/loading-audit',
			$data
		);
	}

	public function detail_audit($uuid)
	{
		$loading = $this->loading_model->get_audit_by_uuid($uuid);

		if (!$loading) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Pemeriksaan Loading Produk tidak ditemukan.'
			);
			redirect('loading/audit');
			return;
		}

		$data = [
			'loading' => $loading,
			'mode'    => 'audit'
		];

		$this->active_nav = 'loading';
		$this->render('form/loading/loading-detail', $data);
	}

	public function edit_audit($uuid)
	{
		$loading = $this->loading_model->get_audit_by_uuid($uuid);

		if (!$loading) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Pemeriksaan Loading Produk tidak ditemukan.'
			);
			redirect('loading/audit');
			return;
		}

		$rules = $this->loading_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->loading_model->update_audit($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Audit Pemeriksaan Loading Produk berhasil diupdate.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data Audit Pemeriksaan Loading Produk gagal diupdate.'
				);
			}

			redirect('loading/audit');
			return;
		}

		$data = [
			'loading'     => $loading,
			'bagian_list' => $this->loading_model->get_bagian_by_uuid($uuid),
			'mode'        => 'audit'
		];

		$this->active_nav = 'loading';
		$this->render('form/loading/loading-edit', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'loading' => $this->loading_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-loading', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/loading/loading-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statuswh($uuid)
	// {
	// 	$rules = $this->loading_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->loading_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Loading Produk berhasil di Update');
	// 			redirect('loading/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Loading Produk gagal di Update');
	// 			redirect('loading/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'loading' => $this->loading_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-loading');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/loading/loading-statuswh', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function cetak()
	{
		$selected_items = $this->input->post('checkbox');

		log_message('debug', 'UUID yang dipilih: ' . print_r($selected_items, true));

		if (empty($selected_items)) {
			show_error('Tidak ada item yang dipilih', 404);
		}

		$loading_data = $this->loading_model->get_by_uuid_loading($selected_items);

		$loading_data_verif = $this->loading_model->get_by_uuid_loading_verif($selected_items);

		$data['loading'] = $loading_data_verif;


		if (!$data['loading']) {
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
			$pdf->Image($logo_path, 10, 10, 38);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(7, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN LOADING PRODUK', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['loading']->date;
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

		$start = new DateTime($data['loading']->start_loading);
		$startFormatted = $start->format('H:i');
		$finish = new DateTime($data['loading']->finish_loading);
		$finishFormatted = $finish->format('H:i');

		$pdf->SetFont('times', '', 9);

		$pdf->SetFont('times', '', 8);
		$pdf->SetX(10);

		$labelWidth = 20;
		$colonWidth = 2;
		$valueWidth = 35;

		$pdf->Cell($labelWidth, 2, 'Hari / Tanggal', 0, 0, 'L');
		$pdf->Cell($colonWidth, 2, ':', 0, 0, 'L');
		$pdf->Cell($valueWidth, 2, $formatted_date, 0, 0, 'L');
		$pdf->Cell($labelWidth, 2, 'Start Loading', 0, 0, 'L');
		$pdf->Cell($colonWidth, 2, ':', 0, 0, 'L');
		$pdf->Cell($valueWidth, 2, $startFormatted, 0, 0, 'L');
		$pdf->Cell($labelWidth, 2, 'Ekspedisi', 0, 0, 'L');
		$pdf->Cell($colonWidth, 2, ':', 0, 0, 'L');
		$pdf->Cell($valueWidth, 2, $data['loading']->ekspedisi, 0, 1, 'L');
		$pdf->SetX(10);
		$pdf->Cell($labelWidth, 2, 'Shift', 0, 0, 'L');
		$pdf->Cell($colonWidth, 2, ':', 0, 0, 'L');
		$pdf->Cell($valueWidth, 2, $data['loading']->shift, 0, 0, 'L');
		$pdf->Cell($labelWidth, 2, 'Finish Loading', 0, 0, 'L');
		$pdf->Cell($colonWidth, 2, ':', 0, 0, 'L');
		$pdf->Cell($valueWidth, 2, $finishFormatted, 0, 0, 'L');
		$pdf->Cell($labelWidth, 2, 'Tujuan', 0, 0, 'L');
		$pdf->Cell($colonWidth, 2, ':', 0, 0, 'L');
		$pdf->Cell($valueWidth, 2, $data['loading']->tujuan, 0, 1, 'L');
		$pdf->SetX(10);
		$pdf->Cell($labelWidth, 2, 'No. Pol Mobil', 0, 0, 'L');
		$pdf->Cell($colonWidth, 2, ':', 0, 0, 'L');
		$pdf->Cell($valueWidth, 2, $data['loading']->no_pol, 0, 0, 'L');
		$pdf->Cell($labelWidth, 2, 'Nama Supir', 0, 0, 'L');
		$pdf->Cell($colonWidth, 2, ':', 0, 0, 'L');
		$pdf->Cell($valueWidth, 2, $data['loading']->nama_supir, 0, 0, 'L');
		$pdf->Cell($labelWidth, 2, 'No. Segel', 0, 0, 'L');
		$pdf->Cell($colonWidth, 2, ':', 0, 0, 'L');
		$pdf->Cell($valueWidth, 2, $data['loading']->no_segel, 0, 0, 'L');
		$pdf->Ln(2);

		foreach ($loading_data as $loading) {

			$pdf->SetFont('times', '', 8);

			$kondisi_mobil_array = json_decode($loading->kondisi_mobil, true);

			if (!empty($kondisi_mobil_array) && is_array($kondisi_mobil_array)) {

				$pdf->Ln(3);

				$baris_kondisi = [];
				$baris_nilai = [];
				$kolom_kondisi_sudah_tampil = false;

				foreach ($kondisi_mobil_array as $kondisi => $status) {

					$status = strtolower(trim($status));

					if ($status === 'ok' || $status === 'bersih') {
						$nilai = '✔';
					} elseif ($status === 'tidak' || $status === 'kotor') {
						$nilai = '✘';
					} else {
						$nilai = $status;
					}

					$baris_kondisi[] = ucwords(str_replace('_', ' ', $kondisi));
					$baris_nilai[] = $nilai;

					// Tampilkan setiap 5 kolom
					if (count($baris_kondisi) == 5) {

						$pdf->SetFont('times', '', 7);

						if (!$kolom_kondisi_sudah_tampil) {
							$pdf->Cell(28, 16, 'Kondisi Mobil', 1, 0, 'C');
							$kolom_kondisi_sudah_tampil = true;
						} else {
							$pdf->Cell(28, 4, '', 0, 0, 'C');
						}

						foreach ($baris_kondisi as $item) {
							$pdf->Cell(33, 4, $item, 1, 0, 'C');
						}

						$pdf->Ln();

						$pdf->SetFont('dejavusans', '', 7);
						$pdf->Cell(28, 4, '', 0, 0, 'C');

						foreach ($baris_nilai as $nilai) {
							$pdf->Cell(33, 4, $nilai, 1, 0, 'C');
						}

						$pdf->Ln();

						$baris_kondisi = [];
						$baris_nilai = [];
					}
				}

				// Sisa data yang kurang dari 5 kolom
				if (!empty($baris_kondisi)) {

					$pdf->SetFont('times', '', 7);

					if (!$kolom_kondisi_sudah_tampil) {
						$pdf->Cell(28, 12, 'Kondisi Mobil', 1, 0, 'C');
						$kolom_kondisi_sudah_tampil = true;
					} else {
						$pdf->Cell(28, 4, '', 0, 0, 'C');
					}

					foreach ($baris_kondisi as $item) {
						$pdf->Cell(33, 4, $item, 1, 0, 'C');
					}

					for ($i = count($baris_kondisi); $i < 5; $i++) {
						$pdf->Cell(33, 4, '', 1, 0, 'C');
					}

					$pdf->Ln();

					$pdf->SetFont('dejavusans', '', 7);
					$pdf->Cell(28, 4, '', 0, 0, 'C');

					foreach ($baris_nilai as $nilai) {
						$pdf->Cell(33, 4, $nilai, 1, 0, 'C');
					}

					for ($i = count($baris_nilai); $i < 5; $i++) {
						$pdf->Cell(33, 4, '', 1, 0, 'C');
					}

					$pdf->Ln();
				}
			} else {

				$pdf->Cell(0, 6, 'Data kondisi mobil tidak tersedia.', 0, 1, 'L');
			}
		}

		$pdf->Ln(2);
		$pdf->SetFont('times', '', 10);
		$pdf->Cell(7, 10, 'No', 1, 0, 'C');
		$pdf->Cell(43, 10, 'Nama Produk', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Kondisi', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Kondisi', 1, 0, 'C');
		$pdf->Cell(40, 10, 'Kode Produksi', 1, 0, 'C');
		$pdf->Cell(40, 10, 'Kode Expired', 1, 0, 'C');
		$pdf->Cell(23, 10, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$pdf->Cell(50, 5, '', 0, 0, 'L');
		$pdf->Cell(20, 6, 'Produk', 0, 0, 'C');
		$pdf->Cell(20, 6, 'Kemasan', 0, 0, 'C');
		$pdf->Cell(108, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');


		$no = 1;
		foreach ($loading_data as $loading) {
			$pdf->SetFont('times', '', 9);

			$loading = json_decode($loading->loading);

			if ($loading && is_array($loading)) {
				foreach ($loading as $loading) {
					$nama_produk = isset($loading->nama_produk) ? $loading->nama_produk : '-';
					$kondisi_produk = isset($loading->kondisi_produk) ? $loading->kondisi_produk : '-';
					$kondisi_kemasan = isset($loading->kondisi_kemasan) ? $loading->kondisi_kemasan : '-';
					$kode_produksi = isset($loading->kode_produksi) ? $loading->kode_produksi : '-';
					$expired = isset($loading->expired) ? $loading->expired : '-';
					$keterangan = isset($loading->keterangan) ? $loading->keterangan : '-';

					$pdf->SetFont('times', '', 8);

					$h1 = $pdf->getStringHeight(43, $nama_produk);
					$h2 = $pdf->getStringHeight(40, $kode_produksi);
					$h3 = $pdf->getStringHeight(23, $keterangan);

					$tinggi = max($h1, $h2, $h3, 5);

					$x = $pdf->GetX();
					$y = $pdf->GetY();

					$pdf->MultiCell(7, $tinggi, $no, 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(43, $tinggi, $nama_produk, 1, 'L', 0, 0, '', '', true);
					$pdf->MultiCell(20, $tinggi, $kondisi_produk, 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(20, $tinggi, $kondisi_kemasan, 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(40, $tinggi, $kode_produksi, 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(40, $tinggi, $expired, 1, 'C', 0, 0, '', '', true);
					$pdf->MultiCell(23, $tinggi, $keterangan, 1, 'C', 0, 1, '', '', true);

					$no++;
				}
			}
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(193, 5, 'QW 07/02', 0, 1, 'R');

		$y_last = $pdf->GetY();
		$y_last += 1;

		$pdf->SetFont('times', '', 7);
		$pdf->SetY($pdf->GetY() + 1);
		$pdf->Cell(15, 5, 'Keterangan: ', 0, 1, 'C');
		$pdf->SetFont('dejavusans', '', 6);
		$pdf->MultiCell(40, 10, "✔ Ok\n✘ Tidak", 0, 'L', false, 0);
		$pdf->SetFont('times', '', 7);
		$pdf->MultiCell(0, 10, "1. Noda (Karat, cat, tinta)\n2. Bekas oli di lantai/dinding\n3. Pallet Rusak/Pecah", 0, 'L');


		$this->load->model('pegawai_model');
		$data['loading']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['loading']->username);
		$data['loading']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['loading']->nama_spv);
		// $data['loading']->nama_lengkap_wh = $this->pegawai_model->get_nama_lengkap($data['loading']->nama_wh);
		$data['loading']->nama_lengkap_wh = $data['loading']->nama_wh;


		$pdf->SetY($pdf->GetY() + 2);
		$pdf->SetFont('times', '', 7);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($loading_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY();
		$status_verifikasi = true;
		foreach ($loading_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;

			// Dibuat oleh (QC)
			$pdf->SetXY(25, $y_verifikasi + 5);
			$pdf->Cell(35, 5, 'Dibuat Oleh,', 0, 0, 'C');
			if (!empty($data['loading']->nama_lengkap_qc)) {
				$update_tanggal_qc = !empty($data['loading']->created_at)
					? (new DateTime($data['loading']->created_at))->format('d-m-Y | H:i')
					: date('d-m-Y | H:i');

				$qr_text_qc = "Dibuat secara digital oleh,\n" .
					$data['loading']->nama_lengkap_qc . "\nQC Inspector\n" . $update_tanggal_qc;
				$pdf->write2DBarcode($qr_text_qc, 'QRCODE,L', 35, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(25, $y_verifikasi + 24);
				$pdf->Cell(35, 5, 'QC Inspector', 0, 0, 'C');
			} else {
				$pdf->SetXY(25, $y_verifikasi + 10);
				$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}

			// Diketahui oleh (Produksi)
			$pdf->SetXY(90, $y_verifikasi + 5);
			$pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');
			if ($data['loading']->status_wh == 1 && !empty($data['loading']->nama_wh)) {
				$update_tanggal_wh = (new DateTime($data['loading']->tgl_update_wh))->format('d-m-Y | H:i');
				$qr_text_wh = "Diketahui secara digital oleh,\n" . $data['loading']->nama_lengkap_wh . "\nForeman/Forelady Warehouse\n" . $update_tanggal_wh;
				$pdf->write2DBarcode($qr_text_wh, 'QRCODE,L', 100, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(90, $y_verifikasi + 24);
				$pdf->Cell(35, 5, 'Warehouse', 0, 0, 'C');
			} else {
				$pdf->SetXY(90, $y_verifikasi + 10);
				$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}

			// if ($data['loading']->status_wh == 1 && !empty($data['loading']->nama_wh)) {
			// 	$update_tanggal_wh = (new DateTime($data['loading']->tgl_update_wh))->format('d-m-Y | H:i');

			// 	$pdf->SetXY(90, $y_verifikasi + 5);
			// 	$pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');
			// 	$pdf->SetFont('times', 'U', 8);
			// 	$pdf->SetXY(90, $y_verifikasi + 10);
			// 	$pdf->Cell(35, 5, $data['loading']->nama_wh, 0, 0, 'C');

			// 	$pdf->SetFont('times', '', 8);
			// 	$pdf->SetXY(90, $y_verifikasi + 14);
			// 	$pdf->Cell(35, 5, 'Warehouse', 0, 0, 'C');

			// 	// $pdf->SetXY(90, $y_verifikasi + 18);
			// 	// $pdf->Cell(35, 5, $update_tanggal_wh, 0, 0, 'C');
			// } else {
			// 	$pdf->SetFont('times', '', 8);
			// 	$pdf->SetXY(90, $y_verifikasi + 10);
			// 	$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
			// }

			// Disetujui oleh (SPV)
			$pdf->SetXY(150, $y_verifikasi + 5);
			$pdf->Cell(49, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$update_tanggal = (new DateTime($data['loading']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['loading']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
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
		$filename = "Pemeriksaan Loading Produk_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}
}
