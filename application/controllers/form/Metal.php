<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Metal extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('metal_model');
		$this->load->model('pegawai_model');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$this->load->library('pagination');

		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		if (!in_array($type_user, [0, 1])) {
			$this->db->where('plant', $plant);
		}

		$config['total_rows'] = $this->db->count_all_results('metal');

		$config['base_url'] = base_url('metal/index');
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);

		$start = $this->uri->segment(3) ?? 0;

		$data = array(
			'metal' => $this->metal_model->get_data_by_plant(
				$config['per_page'],
				$start
			),
			'pagination' => $this->pagination->create_links(),
			'start' => $start
		);

		$this->active_nav = 'metal';
		$this->render('form/metal/metal', $data);
	}

	public function detail($uuid)
	{
		$metal = $this->metal_model->get_qc_by_uuid($uuid);

		if (!$metal) {
			show_404();
			return;
		}

		$data = [
			'metal' => $metal,
			'mode' => 'qc'
		];

		$this->active_nav = 'metal';
		$this->render('form/metal/metal-detail', $data);
	}

	// public function tambah()
	// {
	// 	$rules = $this->metal_model->rules();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {
	// 		$insert = $this->metal_model->insert();
	// 		if ($insert) {
	// 			$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Metal Detector berhasil di simpan');
	// 			redirect('metal');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Metal Detector gagal di simpan');
	// 			redirect('metal');
	// 		}
	// 	}

	// 	$this->active_nav = 'metal'; 
	// 	$this->render('form/metal/metal-tambah');
	// }

	public function tambah()
	{
		$plant_uuid = $this->session->userdata('plant');

		// ambil data terakhir
		$data['last_metal'] = $this->metal_model->get_last_by_plant($plant_uuid);

		$rules = $this->metal_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->metal_model->insert();

			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Metal Detector berhasil di simpan');
				redirect('metal');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Metal Detector gagal di simpan');
				redirect('metal');
			}
		}

		$this->active_nav = 'metal';
		$this->render('form/metal/metal-tambah', $data);
	}

	public function edit($uuid)
	{
		$metal = $this->metal_model->get_qc_by_uuid($uuid);

		if (!$metal) {
			show_404();
			return;
		}

		//$this->form_validation->set_rules($this->metal_model->rules());
		$this->form_validation->set_rules($this->metal_model->rules_update());

		if ($this->form_validation->run() === TRUE) {
			$update = $this->metal_model->update($uuid);

			$this->session->set_flashdata(
				$update ? 'success_msg' : 'error_msg',
				$update
					? 'Data Pemeriksaan Metal Detector berhasil diupdate.'
					: 'Data Pemeriksaan Metal Detector gagal diupdate.'
			);

			redirect('metal');
			return;
		}

		$data = [
			'metal' => $metal,
			'mode' => 'qc'
		];

		$this->active_nav = 'metal';
		$this->render('form/metal/metal-edit', $data);
	}


	// public function edit2($uuid)
	// {
	// 	$rules = $this->metal_model->rules_update2();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->metal_model->update2($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Metal Detector berhasil di Update');
	// 			redirect('metal');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Metal Detector gagal di Update');
	// 			redirect('metal');
	// 		}
	// 	}

	// 	$data = array(
	// 		'metal' => $this->metal_model->get_by_uuid($uuid),
	// 		'active_nav' => 'metal');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/metal/metal-edit2', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function edit3($uuid)
	// {
	// 	$rules = $this->metal_model->rules_update3();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->metal_model->update3($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Metal Detector berhasil di Update');
	// 			redirect('metal');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Metal Detector gagal di Update');
	// 			redirect('metal');
	// 		}
	// 	}

	// 	$data = array(
	// 		'metal' => $this->metal_model->get_by_uuid($uuid),
	// 		'active_nav' => 'metal');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/metal/metal-edit3', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('metal');
		}

		$deleted = $this->metal_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('metal');
	}

	public function verifikasi()
	{
		$this->load->library('pagination');

		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		if (!in_array($type_user, [0, 1])) {
			$this->db->where('plant', $plant);
		}

		$config['total_rows'] = $this->db->count_all_results('metal');

		$config['base_url'] = base_url('metal/verifikasi');
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;

		$this->pagination->initialize($config);

		$start = $this->uri->segment(3) ?? 0;

		$data = array(
			'metal' => $this->metal_model->get_data_by_plant(
				$config['per_page'],
				$start
			),
			'pagination' => $this->pagination->create_links(),
			'start' => $start
		);

		$this->active_nav = 'verifikasi-metal';
		$this->render('form/metal/metal-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->metal_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->metal_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Metal Detector berhasil di Update');
				redirect('metal/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Metal Detector gagal di Update');
				redirect('metal/verifikasi');
			}
		}

		$data = array(
			'metal' => $this->metal_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-metal';
		$this->render('form/metal/metal-status', $data);
	}

	public function audit()
	{
		$data = array(
			'metal' => $this->metal_model->get_audit_by_plant()
		);

		$this->active_nav = 'metal';

		$this->render(
			'form/metal/metal-audit',
			$data
		);
	}

	public function detail_audit($uuid)
	{
		$metal = $this->metal_model->get_audit_by_uuid($uuid);

		if (!$metal) {
			show_404();
			return;
		}

		$data = [
			'metal' => $metal,
			'mode' => 'audit'
		];

		$this->active_nav = 'metal';
		$this->render('form/metal/metal-detail', $data);
	}

	public function edit_audit($uuid)
	{
		$metal = $this->metal_model->get_audit_by_uuid($uuid);

		if (!$metal) {
			show_404();
			return;
		}

		$this->form_validation->set_rules($this->metal_model->rules_update());

		if ($this->form_validation->run() === TRUE) {
			$update = $this->metal_model->update_audit($uuid);

			$this->session->set_flashdata(
				$update ? 'success_msg' : 'error_msg',
				$update
					? 'Data Audit Metal Detector berhasil diupdate.'
					: 'Data Audit Metal Detector gagal diupdate.'
			);

			redirect('metal/audit');
			return;
		}

		$data = [
			'metal' => $metal,
			'mode' => 'audit'
		];

		$this->active_nav = 'metal';
		$this->render('form/metal/metal-edit', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'metal' => $this->metal_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-metal', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/metal/metal-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->metal_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->metal_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Metal Detector berhasil di Update');
	// 			redirect('metal/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Metal Detector gagal di Update');
	// 			redirect('metal/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'metal' => $this->metal_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-metal');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/metal/metal-statusprod', $data);
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
		$is_cikande = ($plant === '651ac623-5e48-44cc-b2f6-5d622603f53c');
		$is_salatiga = !$is_cikande;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 10, 10);

		$this->load->model('Pegawai_model');

		// ============================================================
		// FUNGSI HELPER: cetak header tabel
		// ============================================================
		$printTableHeader = function () use ($pdf, &$data, $is_salatiga) {

			$pdf->SetFont('times', '', 8);
			$pdf->SetX(10);

			$pdf->Cell(14, 16, 'Pukul', 1, 0, 'C');
			$pdf->Cell(50, 16, 'Produk / Kode Produksi', 1, 0, 'C');
			$pdf->Cell(12, 16, 'No.', 1, 0, 'C');
			$pdf->Cell(12, 16, 'Deteksi', 1, 0, 'C');
			$pdf->Cell(60, 4, 'STD. Spesimen', 1, 0, 'C');
			$pdf->Cell($is_salatiga ? 46 : 20, 16, 'Keterangan', 1, 0, 'C');

			if (!$is_salatiga) {
				$pdf->Cell(26, 4, 'Paraf', 1, 1, 'C');
			} else {
				$pdf->Cell(26, 4, '', 0, 1, 'C');
			}

			$pdf->Cell(64, 4, '', 0, 0);
			$pdf->Cell(12, 12, 'Program', 0, 0, 'C');
			$pdf->Cell(12, 12, 'NG', 0, 0, 'C');
			$pdf->Cell(20, 4, 'Fe (mm)', 1, 0, 'C');
			$pdf->Cell(20, 4, 'Non FE (mm)', 1, 0, 'C');
			$pdf->Cell(20, 4, 'SUS 304 (mm)', 1, 0, 'C');
			$pdf->Cell(20, 4, '', 0, 0);

			if (!$is_salatiga) {
				$pdf->Cell(13, 12, 'QC', 1, 0, 'C');
				$pdf->Cell(13, 12, 'Prod', 1, 0, 'C');
			}

			$pdf->Cell(10, 4, '', 0, 1);

			$pdf->Cell(76, 4, '', 0, 0);
			$pdf->Cell(12, 8, 'Product', 0, 0, 'C');
			$pdf->Cell(20, 4, $data['metal']->std_fe, 1, 0, 'C');
			$pdf->Cell(20, 4, $data['metal']->std_nonfe, 1, 0, 'C');
			$pdf->Cell(20, 4, $data['metal']->std_sus304, 1, 0, 'C');

			if (!$is_salatiga) {
				$pdf->Cell(46, 4, '', 0, 0);
				$pdf->Cell(16, 4, '', 0, 1);
			} else {
				$pdf->Cell(62, 4, '', 0, 1);
			}

			$pdf->Cell(88, 4, '', 0, 0);
			$pdf->Cell(6, 4, 'D', 1, 0, 'C');
			$pdf->Cell(7, 4, 'T', 1, 0, 'C');
			$pdf->Cell(7, 4, 'B', 1, 0, 'C');
			$pdf->Cell(6, 4, 'D', 1, 0, 'C');
			$pdf->Cell(7, 4, 'T', 1, 0, 'C');
			$pdf->Cell(7, 4, 'B', 1, 0, 'C');
			$pdf->Cell(6, 4, 'D', 1, 0, 'C');
			$pdf->Cell(7, 4, 'T', 1, 0, 'C');
			$pdf->Cell(7, 4, 'B', 1, 0, 'C');

			if (!$is_salatiga) {
				$pdf->Cell(46, 0, '', 0, 0);
				$pdf->Cell(16, 4, '', 0, 1);
			} else {
				$pdf->Cell(62, 4, '', 0, 1);
			}
		};

		$shift_list = [1, 2, 3];
		$ada_data = false;

		foreach ($shift_list as $shift) {

			$metal_data = $this->metal_model->get_by_date($tanggal, $plant, $shift);
			$metal_data_verif = $this->metal_model->get_last_verif_by_date($tanggal, $plant, $shift);

			if (!$metal_data || !$metal_data_verif) {
				continue;
			}

			$data['metal'] = $metal_data_verif;
			$ada_data = true;

			$pdf->AddPage();

			// Logo
			$logo_path = FCPATH . 'assets/img/cpi-logo.png';
			if (file_exists($logo_path)) {
				$pdf->Image($logo_path, 10, 10, 10);
			}

			// Header perusahaan
			$pdf->SetFont('times', 'B', 7);

			$headerX = 22;
			$headerY = 10;

			$pdf->SetXY($headerX, $headerY);
			$pdf->Cell(0, 3, 'PT. CHAROEN POKPHAND INDONESIA', 0, 1);

			$pdf->SetX($headerX);
			$pdf->Cell(0, 3, 'FOOD DIVISION', 0, 1);

			// Judul
			$pdf->Ln(8);
			$pdf->SetFont('times', 'B', 12);
			$pdf->Cell(0, 6, 'VERIFIKASI SENSITIVITAS METAL DETECTOR', 0, 1, 'C');

			$pdf->Ln(8);

			setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
			$tanggal_pdf = $data['metal']->date_metal;
			$date = new DateTime($tanggal_pdf);
			$formatted_date = date('l, d F Y', $date->getTimestamp());
			$formatted_date2 = date('l, d F Y', $date->getTimestamp());

			$pdf->SetFont('times', '', 8);
			$pdf->SetX(10);
			$pdf->Write(0, 'Tanggal : ' . $formatted_date);
			$pdf->SetX($pdf->GetX() + 20);
			$pdf->Write(0, 'Shift : ' . $shift);
			$pdf->Ln(6);

			// Cetak header tabel pertama kali
			$printTableHeader();

			$tableStartX = 10;

			foreach ($metal_data as $metal) {

				$formattedTime = date('H:i', strtotime($metal->time));

				$row = [
					$formattedTime,
					$metal->nama_produk . ' - ' . $metal->kode_produksi,
					$metal->no_program,
					$metal->deteksi_ng,

					($metal->fe_d === null || $metal->fe_d === '') ? '-' : (($metal->fe_d == 'terdeteksi') ? '✔' : '✘'),
					($metal->nonfe_d === null || $metal->nonfe_d === '') ? '-' : (($metal->nonfe_d == 'terdeteksi') ? '✔' : '✘'),
					($metal->sus_d === null || $metal->sus_d === '') ? '-' : (($metal->sus_d == 'terdeteksi') ? '✔' : '✘'),

					($metal->fe_t === null || $metal->fe_t === '') ? '-' : (($metal->fe_t == 'terdeteksi') ? '✔' : '✘'),
					($metal->nonfe_t === null || $metal->nonfe_t === '') ? '-' : (($metal->nonfe_t == 'terdeteksi') ? '✔' : '✘'),
					($metal->sus_t === null || $metal->sus_t === '') ? '-' : (($metal->sus_t == 'terdeteksi') ? '✔' : '✘'),

					($metal->fe_b === null || $metal->fe_b === '') ? '-' : (($metal->fe_b == 'terdeteksi') ? '✔' : '✘'),
					($metal->nonfe_b === null || $metal->nonfe_b === '') ? '-' : (($metal->nonfe_b == 'terdeteksi') ? '✔' : '✘'),
					($metal->sus_b === null || $metal->sus_b === '') ? '-' : (($metal->sus_b == 'terdeteksi') ? '✔' : '✘'),

					!empty($metal->keterangan) ? $metal->keterangan : '-'
				];

				if (!$is_salatiga) {
					$row[] = $metal->username_1;
					$row[] = $metal->nama_produksi_metal;
				}
				//!empty($metal->keterangan) ? $metal->keterangan : '-',
				//$metal->username_1,
				//$metal->nama_produksi_metal


				if ($is_salatiga) {
					$widths = [14, 50, 12, 12, 6, 7, 7, 6, 7, 7, 6, 7, 7, 46];
				} else {
					$widths = [14, 50, 12, 12, 6, 7, 7, 6, 7, 7, 6, 7, 7, 20, 13, 13];
				}
				//$widths = [14, 50, 12, 12, 6, 7, 7, 6, 7, 7, 6, 7, 7, 20, 13, 13];

				// Hitung jumlah baris maksimum
				$maxLines = 1;

				foreach ($row as $i => $txt) {
					if ($i >= 4 && $i <= 12) {
						$pdf->SetFont('dejavusans', '', 8);
					} else {
						$pdf->SetFont('times', '', 8);
					}

					$lines = $pdf->getNumLines($txt, $widths[$i]);

					if ($lines > $maxLines) {
						$maxLines = $lines;
					}
				}

				// tinggi per baris
				$lineHeight = 4;

				// tinggi row mengikuti kolom terpanjang
				$rowHeight = max(6, $maxLines * $lineHeight);

				// ============================================================
				// CEK PAGE BREAK MANUAL
				// ============================================================
				$page_height_limit = $pdf->getPageHeight() - 15;
				if (($pdf->GetY() + $rowHeight) > $page_height_limit) {
					$pdf->AddPage();
					$pdf->SetX($tableStartX); // Y sudah otomatis di margin atas
					$printTableHeader();      // cetak ulang header tabel di halaman baru
				}
				// ============================================================

				$startX = $pdf->GetX();
				$startY = $pdf->GetY();

				foreach ($row as $i => $txt) {

					if ($i >= 4 && $i <= 12) {
						$pdf->SetFont('dejavusans', '', 8);
					} else {
						$pdf->SetFont('times', '', 8);
					}

					$align = 'C';

					$pdf->MultiCell(
						$widths[$i],
						$rowHeight,
						$txt,
						1,
						$align,
						false,
						0,
						$startX,
						$startY,
						true,
						0,
						false,
						true,
						$rowHeight,
						'M'
					);

					$startX += $widths[$i];
				}

				$pdf->SetXY($tableStartX, $startY + $rowHeight);
			}

			$pdf->SetFont('times', 'I', 8);
			$pdf->Cell(190, 5, 'QB 07/00', 0, 1, 'R');

			$this->load->model('Pegawai_model');

			$nama_lengkap_qc = $this->Pegawai_model->get_nama_lengkap($data['metal']->username_1);
			$nama_lengkap_spv = $this->Pegawai_model->get_nama_lengkap($data['metal']->nama_spv_metal);
			$nama_lengkap_produksi = $data['metal']->nama_produksi_metal;

			$tanggal_update = $data['metal']->tgl_update_spv_metal;
			$update = new DateTime($tanggal_update);
			$update_tanggal = $update->format('d-m-Y | H:i');

			$tanggal_update_prod = $data['metal']->tgl_update_produksi_metal;
			$update_prod = new DateTime($tanggal_update_prod);
			$update_tanggal_prod = $update_prod->format('d-m-Y | H:i');

			$status_verifikasi = true;
			foreach ($metal_data as $item) {
				if ($item->status_spv != '1') {
					$status_verifikasi = false;
					break;
				}
			}

			$pdf->SetY($pdf->GetY() + 3);
			$pdf->SetFont('dejavusans', '', 5);

			$col1 = "1. Belt Conveyor Berhenti\n2. Rejector";
			$col2 = "✓ : Spesimen terdeteksi oleh metal detector\n✗ : Spesimen tidak terdeteksi oleh metal detector";
			$col3 = "D : Depan\nT : Tengah\nB : Belakang";

			$startX = 10;
			$startY = $pdf->GetY();

			$colWidth1 = 55;
			$colWidth2 = 90;
			$colWidth3 = 45;

			$text1 = "1) Deteksi NG Product\n" . $col1;
			$text2 = "2) Hasil Verifikasi\n" . $col2;
			$text3 = $col3;

			$h1 = $pdf->getStringHeight($colWidth1, $text1);
			$h2 = $pdf->getStringHeight($colWidth2, $text2);
			$h3 = $pdf->getStringHeight($colWidth3, $text3);

			$maxHeight = max($h1, $h2, $h3);

			$pdf->SetXY($startX, $startY);
			$pdf->MultiCell($colWidth1, 4, $text1, 0, 'L', false);

			$pdf->SetXY($startX + $colWidth1, $startY);
			$pdf->MultiCell($colWidth2, 4, $text2, 0, 'L', false);

			$pdf->SetXY($startX + $colWidth1 + $colWidth2, $startY);
			$pdf->MultiCell($colWidth3, 4, $text3, 0, 'L', false);

			$pdf->SetY($startY + $maxHeight + 3);

			$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
			foreach ($metal_data as $item) {
				if (!empty($item->catatan)) {
					$pdf->Cell(13, 0, '', 0, 0, 'L');
					$pdf->Cell(13, 0, ' - ' . $item->catatan, 0, 1, 'L');
				}
			}

			$y_after_keterangan = $pdf->GetY() + 5;

			$signature_height = 45;
			$pdf->startTransaction();
			$start_y = $pdf->GetY();
			$page_height = $pdf->getPageHeight() - 15;

			if (($start_y + $signature_height) > $page_height) {
				$pdf->rollbackTransaction(true);
				$pdf->AddPage();
			} else {
				$pdf->commitTransaction();
			}

			if ($status_verifikasi) {

				/* ===============================
           1️⃣ QC (MULTI USER)
           ================================ */
				$qc_usernames  = [];
				$qc_created_at = null;

				foreach ($metal_data as $item) {
					if (!empty($item->username_1)) {
						$qc_usernames[] = $item->username_1;
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
					? implode(', ', $qc_nama_lengkap)
					: '-';

				$qc_tanggal = $qc_created_at
					? (new DateTime($qc_created_at))->format('d-m-Y | H:i')
					: '-';

				$qr_qc_text = "Dibuat secara digital oleh,\n"
					. $qc_nama_text . "\n"
					. "QC Inspector\n"
					. $qc_tanggal;

				/* ===============================
           2️⃣ PRODUKSI
           ================================ */
				$prod_tanggal = !empty($data['metal']->tgl_update_produksi_metal)
					? (new DateTime($data['metal']->tgl_update_produksi_metal))->format('d-m-Y | H:i')
					: '-';

				$nama_lengkap_prod = !empty($data['metal']->nama_produksi_metal)
					? $this->pegawai_model->get_nama_lengkap($data['metal']->nama_produksi_metal)
					: '-';

				$qr_prod_text = "Diketahui secara digital oleh,\n"
					. ($nama_lengkap_prod ?: $data['metal']->nama_produksi_metal) . "\n"
					. "Foreman / Forelady Produksi\n"
					. $prod_tanggal;

				/* ===============================
           3️⃣ SPV
           ================================ */
				$spv_tanggal = !empty($data['metal']->tgl_update_spv_metal)
					? (new DateTime($data['metal']->tgl_update_spv_metal))->format('d-m-Y | H:i')
					: '-';

				$nama_lengkap_spv = !empty($data['metal']->nama_spv_metal)
					? $this->pegawai_model->get_nama_lengkap($data['metal']->nama_spv_metal)
					: '-';

				$qr_spv_text = "Disetujui secara digital oleh,\n"
					. ($nama_lengkap_spv ?: $data['metal']->nama_spv_metal) . "\n"
					. "Supervisor QC Bread Crumb\n"
					. $spv_tanggal;

				/* ===============================
           CETAK QR & LABEL
           ================================ */
				$y = $pdf->GetY() + 5;
				$qr_size = 15;

				$pdf->SetFont('times', '', 8);
				$pdf->SetTextColor(0, 0, 0);

				// Label
				$pdf->SetXY(20, $y);
				$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');
				$pdf->SetXY(85, $y);
				$pdf->Cell(45, 5, 'Diketahui Oleh,', 0, 0, 'C');
				$pdf->SetXY(150, $y);
				$pdf->Cell(45, 5, 'Disetujui Oleh,', 0, 1, 'C');

				// QR
				$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35, $y + 5, $qr_size, $qr_size, null, 'N');
				$pdf->write2DBarcode($qr_prod_text, 'QRCODE,L', 100, $y + 5, $qr_size, $qr_size, null, 'N');
				$pdf->write2DBarcode($qr_spv_text, 'QRCODE,L', 165, $y + 5, $qr_size, $qr_size, null, 'N');

				// Jabatan
				$pdf->SetXY(20, $y + 22);
				$pdf->Cell(45, 5, 'QC Inspector', 0, 0, 'C');
				$pdf->SetXY(85, $y + 22);
				$pdf->Cell(45, 5, 'Foreman / Forelady', 0, 0, 'C');
				$pdf->SetXY(150, $y + 22);
				$pdf->Cell(45, 5, 'Supervisor QC', 0, 1, 'C');
			} else {
				$pdf->SetTextColor(255, 0, 0);
				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(100, $y_after_keterangan);
				$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
				$pdf->SetTextColor(0, 0, 0);
			}
		}
		if (!$ada_data) {
			show_error('Data tidak ditemukan', 404);
		}
		$pdf->setPrintFooter(false);
		$filename = "Metal Detector_{$formatted_date2}.pdf";
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
		$is_cikande = ($plant === '651ac623-5e48-44cc-b2f6-5d622603f53c');
		$is_salatiga = !$is_cikande;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 10, 10);

		$this->load->model('Pegawai_model');

		// ============================================================
		// FUNGSI HELPER: cetak header tabel
		// ============================================================
		$printTableHeader = function () use ($pdf, &$data, $is_salatiga) {

			$pdf->SetFont('times', '', 8);
			$pdf->SetX(10);

			$pdf->Cell(14, 16, 'Pukul', 1, 0, 'C');
			$pdf->Cell(50, 16, 'Produk / Kode Produksi', 1, 0, 'C');
			$pdf->Cell(12, 16, 'No.', 1, 0, 'C');
			$pdf->Cell(12, 16, 'Deteksi', 1, 0, 'C');
			$pdf->Cell(60, 4, 'STD. Spesimen', 1, 0, 'C');
			$pdf->Cell($is_salatiga ? 46 : 20, 16, 'Keterangan', 1, 0, 'C');

			if (!$is_salatiga) {
				$pdf->Cell(26, 4, 'Paraf', 1, 1, 'C');
			} else {
				$pdf->Cell(26, 4, '', 0, 1, 'C');
			}

			$pdf->Cell(64, 4, '', 0, 0);
			$pdf->Cell(12, 12, 'Program', 0, 0, 'C');
			$pdf->Cell(12, 12, 'NG', 0, 0, 'C');
			$pdf->Cell(20, 4, 'Fe (mm)', 1, 0, 'C');
			$pdf->Cell(20, 4, 'Non FE (mm)', 1, 0, 'C');
			$pdf->Cell(20, 4, 'SUS 304 (mm)', 1, 0, 'C');
			$pdf->Cell(20, 4, '', 0, 0);

			if (!$is_salatiga) {
				$pdf->Cell(13, 12, 'QC', 1, 0, 'C');
				$pdf->Cell(13, 12, 'Prod', 1, 0, 'C');
			}

			$pdf->Cell(10, 4, '', 0, 1);

			$pdf->Cell(76, 4, '', 0, 0);
			$pdf->Cell(12, 8, 'Product', 0, 0, 'C');
			$pdf->Cell(20, 4, $data['metal']->std_fe, 1, 0, 'C');
			$pdf->Cell(20, 4, $data['metal']->std_nonfe, 1, 0, 'C');
			$pdf->Cell(20, 4, $data['metal']->std_sus304, 1, 0, 'C');

			if (!$is_salatiga) {
				$pdf->Cell(46, 4, '', 0, 0);
				$pdf->Cell(16, 4, '', 0, 1);
			} else {
				$pdf->Cell(62, 4, '', 0, 1);
			}

			$pdf->Cell(88, 4, '', 0, 0);
			$pdf->Cell(6, 4, 'D', 1, 0, 'C');
			$pdf->Cell(7, 4, 'T', 1, 0, 'C');
			$pdf->Cell(7, 4, 'B', 1, 0, 'C');
			$pdf->Cell(6, 4, 'D', 1, 0, 'C');
			$pdf->Cell(7, 4, 'T', 1, 0, 'C');
			$pdf->Cell(7, 4, 'B', 1, 0, 'C');
			$pdf->Cell(6, 4, 'D', 1, 0, 'C');
			$pdf->Cell(7, 4, 'T', 1, 0, 'C');
			$pdf->Cell(7, 4, 'B', 1, 0, 'C');

			if (!$is_salatiga) {
				$pdf->Cell(46, 0, '', 0, 0);
				$pdf->Cell(16, 4, '', 0, 1);
			} else {
				$pdf->Cell(62, 4, '', 0, 1);
			}
		};

		$shift_list = [1, 2, 3];
		$ada_data = false;

		foreach ($shift_list as $shift) {

			$metal_data = $this->metal_model->get_audit_by_date($tanggal, $plant, $shift);
			$metal_data_verif = $this->metal_model->get_last_verif_by_date($tanggal, $plant, $shift);

			if (!$metal_data || !$metal_data_verif) {
				continue;
			}

			$data['metal'] = $metal_data_verif;
			$ada_data = true;

			$pdf->AddPage();

			// Logo
			$logo_path = FCPATH . 'assets/img/cpi-logo.png';
			if (file_exists($logo_path)) {
				$pdf->Image($logo_path, 10, 10, 10);
			}

			// Header perusahaan
			$pdf->SetFont('times', 'B', 7);

			$headerX = 22;
			$headerY = 10;

			$pdf->SetXY($headerX, $headerY);
			$pdf->Cell(0, 3, 'PT. CHAROEN POKPHAND INDONESIA', 0, 1);

			$pdf->SetX($headerX);
			$pdf->Cell(0, 3, 'FOOD DIVISION', 0, 1);

			// Judul
			$pdf->Ln(8);
			$pdf->SetFont('times', 'B', 12);
			$pdf->Cell(0, 6, 'VERIFIKASI SENSITIVITAS METAL DETECTOR', 0, 1, 'C');

			$pdf->Ln(8);

			setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
			$tanggal_pdf = $data['metal']->date_metal;
			$date = new DateTime($tanggal_pdf);
			$formatted_date = date('l, d F Y', $date->getTimestamp());
			$formatted_date2 = date('l, d F Y', $date->getTimestamp());

			$pdf->SetFont('times', '', 8);
			$pdf->SetX(10);
			$pdf->Write(0, 'Tanggal : ' . $formatted_date);
			$pdf->SetX($pdf->GetX() + 20);
			$pdf->Write(0, 'Shift : ' . $shift);
			$pdf->Ln(6);

			// Cetak header tabel pertama kali
			$printTableHeader();

			$tableStartX = 10;

			foreach ($metal_data as $metal) {

				$formattedTime = date('H:i', strtotime($metal->time));

				$row = [
					$formattedTime,
					$metal->nama_produk . ' - ' . $metal->kode_produksi,
					$metal->no_program,
					$metal->deteksi_ng,

					($metal->fe_d === null || $metal->fe_d === '') ? '-' : (($metal->fe_d == 'terdeteksi') ? '✔' : '✘'),
					($metal->nonfe_d === null || $metal->nonfe_d === '') ? '-' : (($metal->nonfe_d == 'terdeteksi') ? '✔' : '✘'),
					($metal->sus_d === null || $metal->sus_d === '') ? '-' : (($metal->sus_d == 'terdeteksi') ? '✔' : '✘'),

					($metal->fe_t === null || $metal->fe_t === '') ? '-' : (($metal->fe_t == 'terdeteksi') ? '✔' : '✘'),
					($metal->nonfe_t === null || $metal->nonfe_t === '') ? '-' : (($metal->nonfe_t == 'terdeteksi') ? '✔' : '✘'),
					($metal->sus_t === null || $metal->sus_t === '') ? '-' : (($metal->sus_t == 'terdeteksi') ? '✔' : '✘'),

					($metal->fe_b === null || $metal->fe_b === '') ? '-' : (($metal->fe_b == 'terdeteksi') ? '✔' : '✘'),
					($metal->nonfe_b === null || $metal->nonfe_b === '') ? '-' : (($metal->nonfe_b == 'terdeteksi') ? '✔' : '✘'),
					($metal->sus_b === null || $metal->sus_b === '') ? '-' : (($metal->sus_b == 'terdeteksi') ? '✔' : '✘'),

					!empty($metal->keterangan) ? $metal->keterangan : '-'
				];

				if (!$is_salatiga) {
					$row[] = $metal->username_1;
					$row[] = $metal->nama_produksi_metal;
				}
				//!empty($metal->keterangan) ? $metal->keterangan : '-',
				//$metal->username_1,
				//$metal->nama_produksi_metal


				if ($is_salatiga) {
					$widths = [14, 50, 12, 12, 6, 7, 7, 6, 7, 7, 6, 7, 7, 46];
				} else {
					$widths = [14, 50, 12, 12, 6, 7, 7, 6, 7, 7, 6, 7, 7, 20, 13, 13];
				}
				//$widths = [14, 50, 12, 12, 6, 7, 7, 6, 7, 7, 6, 7, 7, 20, 13, 13];

				// Hitung jumlah baris maksimum
				$maxLines = 1;

				foreach ($row as $i => $txt) {
					if ($i >= 4 && $i <= 12) {
						$pdf->SetFont('dejavusans', '', 8);
					} else {
						$pdf->SetFont('times', '', 8);
					}

					$lines = $pdf->getNumLines($txt, $widths[$i]);

					if ($lines > $maxLines) {
						$maxLines = $lines;
					}
				}

				// tinggi per baris
				$lineHeight = 4;

				// tinggi row mengikuti kolom terpanjang
				$rowHeight = max(6, $maxLines * $lineHeight);

				// ============================================================
				// CEK PAGE BREAK MANUAL
				// ============================================================
				$page_height_limit = $pdf->getPageHeight() - 15;
				if (($pdf->GetY() + $rowHeight) > $page_height_limit) {
					$pdf->AddPage();
					$pdf->SetX($tableStartX); // Y sudah otomatis di margin atas
					$printTableHeader();      // cetak ulang header tabel di halaman baru
				}
				// ============================================================

				$startX = $pdf->GetX();
				$startY = $pdf->GetY();

				foreach ($row as $i => $txt) {

					if ($i >= 4 && $i <= 12) {
						$pdf->SetFont('dejavusans', '', 8);
					} else {
						$pdf->SetFont('times', '', 8);
					}

					$align = 'C';

					$pdf->MultiCell(
						$widths[$i],
						$rowHeight,
						$txt,
						1,
						$align,
						false,
						0,
						$startX,
						$startY,
						true,
						0,
						false,
						true,
						$rowHeight,
						'M'
					);

					$startX += $widths[$i];
				}

				$pdf->SetXY($tableStartX, $startY + $rowHeight);
			}

			$pdf->SetFont('times', 'I', 8);
			$pdf->Cell(190, 5, 'QB 07/00', 0, 1, 'R');

			$this->load->model('Pegawai_model');

			$nama_lengkap_qc = $this->Pegawai_model->get_nama_lengkap($data['metal']->username_1);
			$nama_lengkap_spv = $this->Pegawai_model->get_nama_lengkap($data['metal']->nama_spv_metal);
			$nama_lengkap_produksi = $data['metal']->nama_produksi_metal;

			$tanggal_update = $data['metal']->tgl_update_spv_metal;
			$update = new DateTime($tanggal_update);
			$update_tanggal = $update->format('d-m-Y | H:i');

			$tanggal_update_prod = $data['metal']->tgl_update_produksi_metal;
			$update_prod = new DateTime($tanggal_update_prod);
			$update_tanggal_prod = $update_prod->format('d-m-Y | H:i');

			$status_verifikasi = true;
			foreach ($metal_data as $item) {
				if ($item->status_spv != '1') {
					$status_verifikasi = false;
					break;
				}
			}

			$pdf->SetY($pdf->GetY() + 3);
			$pdf->SetFont('dejavusans', '', 5);

			$col1 = "1. Belt Conveyor Berhenti\n2. Rejector";
			$col2 = "✓ : Spesimen terdeteksi oleh metal detector\n✗ : Spesimen tidak terdeteksi oleh metal detector";
			$col3 = "D : Depan\nT : Tengah\nB : Belakang";

			$startX = 10;
			$startY = $pdf->GetY();

			$colWidth1 = 55;
			$colWidth2 = 90;
			$colWidth3 = 45;

			$text1 = "1) Deteksi NG Product\n" . $col1;
			$text2 = "2) Hasil Verifikasi\n" . $col2;
			$text3 = $col3;

			$h1 = $pdf->getStringHeight($colWidth1, $text1);
			$h2 = $pdf->getStringHeight($colWidth2, $text2);
			$h3 = $pdf->getStringHeight($colWidth3, $text3);

			$maxHeight = max($h1, $h2, $h3);

			$pdf->SetXY($startX, $startY);
			$pdf->MultiCell($colWidth1, 4, $text1, 0, 'L', false);

			$pdf->SetXY($startX + $colWidth1, $startY);
			$pdf->MultiCell($colWidth2, 4, $text2, 0, 'L', false);

			$pdf->SetXY($startX + $colWidth1 + $colWidth2, $startY);
			$pdf->MultiCell($colWidth3, 4, $text3, 0, 'L', false);

			$pdf->SetY($startY + $maxHeight + 3);

			$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
			foreach ($metal_data as $item) {
				if (!empty($item->catatan)) {
					$pdf->Cell(13, 0, '', 0, 0, 'L');
					$pdf->Cell(13, 0, ' - ' . $item->catatan, 0, 1, 'L');
				}
			}

			$y_after_keterangan = $pdf->GetY() + 5;

			$signature_height = 45;
			$pdf->startTransaction();
			$start_y = $pdf->GetY();
			$page_height = $pdf->getPageHeight() - 15;

			if (($start_y + $signature_height) > $page_height) {
				$pdf->rollbackTransaction(true);
				$pdf->AddPage();
			} else {
				$pdf->commitTransaction();
			}

			if ($status_verifikasi) {

				/* ===============================
           1️⃣ QC (MULTI USER)
           ================================ */
				$qc_usernames  = [];
				$qc_created_at = null;

				foreach ($metal_data as $item) {
					if (!empty($item->username_1)) {
						$qc_usernames[] = $item->username_1;
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
					? implode(', ', $qc_nama_lengkap)
					: '-';

				$qc_tanggal = $qc_created_at
					? (new DateTime($qc_created_at))->format('d-m-Y | H:i')
					: '-';

				$qr_qc_text = "Dibuat secara digital oleh,\n"
					. $qc_nama_text . "\n"
					. "QC Inspector\n"
					. $qc_tanggal;

				/* ===============================
           2️⃣ PRODUKSI
           ================================ */
				$prod_tanggal = !empty($data['metal']->tgl_update_produksi_metal)
					? (new DateTime($data['metal']->tgl_update_produksi_metal))->format('d-m-Y | H:i')
					: '-';

				$nama_lengkap_prod = !empty($data['metal']->nama_produksi_metal)
					? $this->pegawai_model->get_nama_lengkap($data['metal']->nama_produksi_metal)
					: '-';

				$qr_prod_text = "Diketahui secara digital oleh,\n"
					. ($nama_lengkap_prod ?: $data['metal']->nama_produksi_metal) . "\n"
					. "Foreman / Forelady Produksi\n"
					. $prod_tanggal;

				/* ===============================
           3️⃣ SPV
           ================================ */
				$spv_tanggal = !empty($data['metal']->tgl_update_spv_metal)
					? (new DateTime($data['metal']->tgl_update_spv_metal))->format('d-m-Y | H:i')
					: '-';

				$nama_lengkap_spv = !empty($data['metal']->nama_spv_metal)
					? $this->pegawai_model->get_nama_lengkap($data['metal']->nama_spv_metal)
					: '-';

				$qr_spv_text = "Disetujui secara digital oleh,\n"
					. ($nama_lengkap_spv ?: $data['metal']->nama_spv_metal) . "\n"
					. "Supervisor QC Bread Crumb\n"
					. $spv_tanggal;

				/* ===============================
           CETAK QR & LABEL
           ================================ */
				$y = $pdf->GetY() + 5;
				$qr_size = 15;

				$pdf->SetFont('times', '', 8);
				$pdf->SetTextColor(0, 0, 0);

				// Label
				$pdf->SetXY(20, $y);
				$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');
				$pdf->SetXY(85, $y);
				$pdf->Cell(45, 5, 'Diketahui Oleh,', 0, 0, 'C');
				$pdf->SetXY(150, $y);
				$pdf->Cell(45, 5, 'Disetujui Oleh,', 0, 1, 'C');

				// QR
				$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35, $y + 5, $qr_size, $qr_size, null, 'N');
				$pdf->write2DBarcode($qr_prod_text, 'QRCODE,L', 100, $y + 5, $qr_size, $qr_size, null, 'N');
				$pdf->write2DBarcode($qr_spv_text, 'QRCODE,L', 165, $y + 5, $qr_size, $qr_size, null, 'N');

				// Jabatan
				$pdf->SetXY(20, $y + 22);
				$pdf->Cell(45, 5, 'QC Inspector', 0, 0, 'C');
				$pdf->SetXY(85, $y + 22);
				$pdf->Cell(45, 5, 'Foreman / Forelady', 0, 0, 'C');
				$pdf->SetXY(150, $y + 22);
				$pdf->Cell(45, 5, 'Supervisor QC', 0, 1, 'C');
			} else {
				$pdf->SetTextColor(255, 0, 0);
				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(100, $y_after_keterangan);
				$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
				$pdf->SetTextColor(0, 0, 0);
			}
		}
		if (!$ada_data) {
			show_error('Data tidak ditemukan', 404);
		}
		$pdf->setPrintFooter(false);
		$filename = "Metal Detector_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}
}
