<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH . '../vendor/autoload.php');
// require_once(APPPATH . 'libraries/phpqrcode.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Suhu extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('suhu_model');
		$this->load->model('pegawai_model');
		$this->load->helper(['url', 'form']);
		$this->load->library(['session']);
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'suhu' => $this->suhu_model->get_suhu_by_plant()
		);

		$this->active_nav = 'suhu';
		$this->render('form/suhu/suhu', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'suhu' => $this->suhu_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'suhu';
		$this->render('form/suhu/suhu-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->suhu_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->suhu_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Suhu Ruang berhasil disimpan');
				redirect('suhu');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Suhu Ruang gagal disimpan');
				redirect('suhu');
			}
		}

		// Ambil UUID plant dari session
		$plant_uuid = $this->session->userdata('plant');

		// Pemetaan UUID → nama plant
		$plant_map = [
			'651ac623-5e48-44cc-b2f6-5d622603f53c' => 'Cikande',
			'1eb341e0-1ec4-4484-ba8f-32d23352b84d' => 'Salatiga'
		];

		// Ambil nama plant berdasarkan UUID, default ke 'Unknown' jika tidak ditemukan
		$plant_name = isset($plant_map[$plant_uuid]) ? $plant_map[$plant_uuid] : 'Unknown';

		$data = array(
			'plant' => $plant_name
		);

		$this->active_nav = 'suhu';
		$this->render('form/suhu/suhu-tambah', $data);
	}

	public function edit($uuid)
	{
		$rules = $this->suhu_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->suhu_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Suhu Ruang berhasil di Update');
				redirect('suhu');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Suhu Ruang gagal di Update');
				redirect('suhu');
			}
		}

		$data = array(
			'suhu' => $this->suhu_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'suhu';
		$this->render('form/suhu/suhu-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('suhu');
		}

		$deleted = $this->suhu_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('suhu');
	}

	public function verifikasi()
	{
		$data = array(
			'suhu' => $this->suhu_model->get_suhu_by_plant()
		);

		$this->active_nav = 'verifikasi-suhu';
		$this->render('form/suhu/suhu-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->suhu_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->suhu_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Suhu Ruang berhasil di Update');
				redirect('suhu/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Suhu Ruang gagal di Update');
				redirect('suhu/verifikasi');
			}
		}

		$data = array(
			'suhu' => $this->suhu_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-suhu';
		$this->render('form/suhu/suhu-status', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'suhu' => $this->suhu_model->get_suhu_by_plant(),
	// 		'active_nav' => 'diketahui-suhu', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/suhu/suhu-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->suhu_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->suhu_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Suhu Ruang berhasil di Update');
	// 			redirect('suhu/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Suhu Ruang gagal di Update');
	// 			redirect('suhu/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'suhu' => $this->suhu_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-suhu');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/suhu/suhu-statusprod', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal');
		$plant_id = $this->session->userdata('plant');
		$plant_uuid = $this->session->userdata('plant');

		if (empty($tanggal)) {
			show_error('Tanggal tidak boleh kosong', 404);
		}

		$this->load->model('pegawai_model');
		$this->load->model('suhu_model');

		// Ambil semua data tanggal tersebut
		$suhu_data = $this->suhu_model->get_by_date_and_plant_pdf($tanggal, $plant_uuid);

		if (empty($suhu_data)) {
			show_error('Data tidak ditemukan', 404);
		}

		// Ambil data verifikasi
		$suhu_data_verif = $this->suhu_model->get_by_date_verif_and_plant($tanggal, $plant_uuid);

		$data['suhu'] = $suhu_data_verif;

		$data['suhu']->nama_lengkap_qc =
			$this->pegawai_model->get_nama_lengkap($data['suhu']->username);

		$data['suhu']->nama_lengkap_spv =
			$this->pegawai_model->get_nama_lengkap($data['suhu']->nama_spv);

		$data['suhu']->nama_lengkap_produksi =
			$data['suhu']->nama_produksi;

		// =====================================
		// GROUP BERDASARKAN SHIFT
		// =====================================

		$shift_data = [];

		foreach ($suhu_data as $row) {

			$shift = (int)$row->shift;

			if (!isset($shift_data[$shift])) {
				$shift_data[$shift] = [];
			}

			$shift_data[$shift][] = $row;
		}

		ksort($shift_data);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$is_cikande = ($plant_id === '651ac623-5e48-44cc-b2f6-5d622603f53c');
		$is_salatiga = !$is_cikande;

		$pdf = new TCPDF(
			PDF_PAGE_ORIENTATION,
			PDF_UNIT,
			'LEGAL',
			true,
			'UTF-8',
			false
		);

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(10, 14, 10);

		$datetime = new DateTime($tanggal);

		$formatted_date = date(
			'l, d F Y',
			$datetime->getTimestamp()
		);

		$formatted_date2 = date(
			'l, d F Y',
			$datetime->getTimestamp()
		);

		$logo_path = FCPATH . 'assets/img/cpi-logo.png';

		// =====================================
		// LOKASI & STANDAR
		// =====================================

		$lokasi = $is_salatiga
			? [
				"Ruang Pengayakan",
				"Ruang RM",
				"Chiller 1",
				"Chiller 2",
				"Chiller 3",
				"Chiller 4",
				"Chiller 5",
				"Chiller 6",
				"Ruang Mixing",
				"Area Baking",
				"Area Cutting & Grinding",
				"Ruang Aging",
				"Area Packing"
			]
			: [
				"Ruang Produksi",
				"Gudang Premix",
				"Gudang Raw Material",
				"Gudang Finish Good",
				"Proofing Room",
				"Aging Room 1",
				"Aging Room 2",
				"Area Packing",
				"Ruang Produksi (Bubble)"
			];

		$standar = [];

		foreach ($lokasi as $nama) {

			if ($is_salatiga) {

				if (strpos($nama, 'Chiller') !== false) {
					$standar[$nama] = ['0-4', ''];
				} elseif ($nama == 'Ruang RM') {
					$standar[$nama] = ['15-22', ''];
				} elseif ($nama == 'Ruang Aging') {
					$standar[$nama] = ['35-45', ''];
				} else {
					$standar[$nama] = ['25-35', ''];
				}
			} else {

				$default = [
					"Ruang Produksi" => ["25-35", "65-80"],
					"Gudang Premix" => ["15-22", "45-55"],
					"Gudang Raw Material" => ["25-35", "60-75"],
					"Gudang Finish Good" => ["28-36", "60-75"],
					"Proofing Room" => ["34-36", "78-82"],
					"Aging Room 1" => ["35-45", "50-70"],
					"Aging Room 2" => ["35-45", "50-70"],
					"Area Packing" => ["25-35", ""],
					"Ruang Produksi (Bubble)" => ["25-35", "65-80"]
				];

				$standar[$nama] = $default[$nama] ?? ['', ''];
			}
		}

		$lokasi_unik = [];

		foreach ($suhu_data as $item) {

			$lokasi_array = json_decode($item->lokasi, true);

			if (!$lokasi_array) {
				continue;
			}

			foreach ($lokasi_array as $lok) {
				$lokasi_unik[] = $lok['nama_lokasi'];
			}
		}

		$lokasi_unik = array_unique($lokasi_unik);

		$total_kolom_data = count($lokasi_unik) * ($is_cikande ? 2 : 1);

		if ($is_salatiga) {

			$max_table_width = $pdf->getPageWidth() - 20;

			$col_pukul = 18;

			$col_suhu = ($max_table_width - $col_pukul) / $total_kolom_data;
		} else {

			$col_suhu = 10;

			$max_table_width = 195;

			$col_pukul = 25;
		}

		// =====================================
		// MULAI LOOP SHIFT
		// =====================================

		if ($is_salatiga) {

			foreach ($shift_data as $shift => $data_shift) {

				$pdf->AddPage('L');

				// Logo
				if (file_exists($logo_path)) {
					$pdf->Image($logo_path, 10, 10, 10);
				}

				// Header Perusahaan
				$pdf->SetFont('times', 'B', 7);

				$headerX = 22;
				$headerY = 10;

				$pdf->SetXY($headerX, $headerY);
				$pdf->Cell(0, 3, 'PT. CHAROEN POKPHAND INDONESIA', 0, 1);

				$pdf->SetX($headerX);
				$pdf->Cell(0, 3, 'FOOD DIVISION', 0, 1);

				// Judul
				$pdf->Ln(6);
				$pdf->SetFont('times', 'B', 12);
				$pdf->Cell(0, 6, 'PEMANTAUAN SUHU DAN KELEMBAPAN RUANG', 0, 1, 'C');

				$pdf->Ln(6);

				$pdf->SetFont('times', '', 9);

				$pdf->Cell(125, 5, 'Tanggal : ' . $formatted_date, 0, 0, 'L');
				$pdf->Cell(30, 5, 'Shift : ' . $shift, 0, 1, 'L');

				$pdf->Ln(3);

				// =====================================
				// MATRIX DATA SHIFT
				// =====================================

				$matrix = [];
				$humidity_matrix = [];

				foreach ($data_shift as $item) {

					$jam = date(
						'H:i',
						strtotime($item->pukul)
					);

					$lokasi_array = json_decode(
						$item->lokasi,
						true
					);

					if (!$lokasi_array) {
						continue;
					}

					foreach ($lokasi_array as $lok) {

						$nama = trim($lok['nama_lokasi']);

						$nilai_suhu = trim((string)($lok['suhu'] ?? ''));
						$nilai_rh   = trim((string)($lok['rh'] ?? ''));

						$matrix[$nama][$jam] =
							($nilai_suhu === '' || strtolower($nilai_suhu) === 'kosong')
							? '-'
							: $nilai_suhu;

						$humidity_matrix[$nama][$jam] =
							($nilai_rh === '' || strtolower($nilai_rh) === 'kosong')
							? '-'
							: $nilai_rh;
					}
				}
				// =====================================
				// DAFTAR JAM SHIFT INI
				// =====================================

				$pdf->SetFont('times', '', 8);

				$jam_list = [];

				foreach ($data_shift as $item) {

					$jam = date('H:i', strtotime($item->pukul));

					if (!in_array($jam, $jam_list)) {
						$jam_list[] = $jam;
					}
				}

				sort($jam_list);

				$w_no = 10;
				$w_lokasi = 40;
				$w_std = 18;
				$w_ket = 40;

				$total_width = $pdf->getPageWidth() - 20;

				$w_jam = ($total_width - $w_no - $w_lokasi - $w_std - $w_ket)
					/ max(count($jam_list), 1);

				// =====================================
				// HEADER TABEL
				// =====================================

				$y = $pdf->GetY();
				$x = $pdf->GetX();

				$pdf->Cell($w_no, 12, 'No.', 1, 0, 'C');

				$pdf->Cell($w_lokasi, 12, 'Lokasi', 1, 0, 'C');

				$pdf->Cell($w_std, 12, 'Standar', 1, 0, 'C');

				$pdf->Cell(
					$w_jam * count($jam_list),
					6,
					'Hasil Pemeriksaan',
					1,
					0,
					'C'
				);

				$pdf->Cell(
					$w_ket,
					12,
					'Keterangan',
					1,
					0,
					'C'
				);

				$pdf->SetXY(
					$x + $w_no + $w_lokasi + $w_std,
					$y + 6
				);

				foreach ($jam_list as $jam) {

					$pdf->Cell(
						$w_jam,
						6,
						$jam,
						1,
						0,
						'C'
					);
				}

				$pdf->Ln();

				// =====================================
				// TABEL SUHU
				// =====================================

				$pdf->SetFont('times', 'B', 9);

				$pdf->Cell(
					$w_no + $w_lokasi + $w_std + ($w_jam * count($jam_list)) + $w_ket,
					6,
					'Pemantauan Suhu (°C)',
					1,
					1,
					'L'
				);

				$pdf->SetFont('times', '', 8);

				$no = 1;

				foreach ($lokasi as $nama_lokasi) {

					$pdf->Cell(
						$w_no,
						6,
						$no++,
						1,
						0,
						'C'
					);

					$pdf->Cell(
						$w_lokasi,
						6,
						$nama_lokasi,
						1,
						0,
						'L'
					);

					$pdf->Cell(
						$w_std,
						6,
						$standar[$nama_lokasi][0],
						1,
						0,
						'C'
					);

					foreach ($jam_list as $jam) {

						$nilai = $matrix[$nama_lokasi][$jam] ?? '-';

						$pdf->Cell(
							$w_jam,
							6,
							$nilai,
							1,
							0,
							'C'
						);
					}

					$pdf->Cell(
						$w_ket,
						6,
						'',
						1,
						1,
						'C'
					);
				}

				// =====================================
				// TABEL KELEMBAPAN
				// =====================================

				$pdf->SetFont('times', 'B', 9);

				$pdf->Cell(
					$w_no + $w_lokasi + $w_std + ($w_jam * count($jam_list)) + $w_ket,
					6,
					'Pemantauan Kelembapan (%)',
					1,
					1,
					'L'
				);

				$pdf->SetFont('times', '', 8);

				$pdf->Cell(
					$w_no,
					6,
					'1',
					1,
					0,
					'C'
				);

				$pdf->Cell(
					$w_lokasi,
					6,
					'Ruang Aging',
					1,
					0,
					'L'
				);

				$pdf->Cell(
					$w_std,
					6,
					'',
					1,
					0,
					'C'
				);

				foreach ($jam_list as $jam) {

					$nilai = $humidity_matrix['Ruang Aging'][$jam] ?? '-';

					$pdf->Cell(
						$w_jam,
						6,
						$nilai,
						1,
						0,
						'C'
					);
				}

				$pdf->Cell(
					$w_ket,
					6,
					'',
					1,
					1,
					'C'
				);
				// =====================================
				// CATATAN SHIFT
				// =====================================

				$pdf->Ln(2);

				$pdf->SetFont('times', 'I', 7);

				$page_width = $pdf->getPageWidth();

				$pdf->Cell(
					$page_width - 20,
					5,
					'QB 06/00',
					0,
					1,
					'R'
				);

				$pdf->SetFont('times', '', 8);

				$pdf->Cell(
					5,
					4,
					'Catatan :',
					0,
					1
				);

				$catatanSudah = [];

				foreach ($data_shift as $item) {

					if (
						!empty($item->catatan) &&
						!in_array($item->catatan, $catatanSudah)
					) {

						$catatanSudah[] = $item->catatan;

						$pdf->Cell(
							8,
							4,
							'',
							0,
							0
						);

						$pdf->MultiCell(
							180,
							4,
							'- ' . $item->catatan,
							0,
							'L'
						);
					}
				}

				// =====================================
				// STATUS VERIFIKASI SHIFT
				// =====================================

				$status_verifikasi = true;

				foreach ($data_shift as $item) {

					if ($item->status_spv != '1') {

						$status_verifikasi = false;
						break;
					}
				}

				// =====================================
				// DATA QC SHIFT
				// =====================================

				$qc_usernames = [];

				$qc_created_at = null;

				$produksi_nama = '';

				$produksi_tgl = '';

				$spv_nama = '';

				$spv_tgl = '';

				foreach ($data_shift as $item) {

					if (!empty($item->username)) {
						$qc_usernames[] = $item->username;
					}

					if (!$qc_created_at && !empty($item->created_at)) {
						$qc_created_at = $item->created_at;
					}

					if (empty($produksi_nama) && !empty($item->nama_produksi)) {
						$produksi_nama = $item->nama_produksi;
					}

					if (empty($produksi_tgl) && !empty($item->tgl_update_produksi)) {
						$produksi_tgl = $item->tgl_update_produksi;
					}

					if (empty($spv_nama) && !empty($item->nama_spv)) {
						$spv_nama = $item->nama_spv;
					}

					if (empty($spv_tgl) && !empty($item->tgl_update_spv)) {
						$spv_tgl = $item->tgl_update_spv;
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

				$qc_text = implode(', ', $qc_nama);

				$qc_time = $qc_created_at
					? date('d-m-Y | H:i', strtotime($qc_created_at))
					: '-';

				$produksi_time = $produksi_tgl
					? date('d-m-Y | H:i', strtotime($produksi_tgl))
					: '-';

				$spv_time = $spv_tgl
					? date('d-m-Y | H:i', strtotime($spv_tgl))
					: '-';

				// =====================================
				// QR
				// =====================================

				$y_ttd = $pdf->GetY() + 8;

				$qr_size = 15;

				$page_width = $pdf->getPageWidth();

				$x1 = 40;
				$x2 = ($page_width / 2) - 10;
				$x3 = $page_width - 70;

				if ($status_verifikasi) {

					$pdf->SetXY($x1, $y_ttd);
					$pdf->Cell(45, 5, 'Dibuat Oleh', 0, 0, 'C');

					$pdf->SetXY($x2, $y_ttd);
					$pdf->Cell(45, 5, 'Diketahui Oleh', 0, 0, 'C');

					$pdf->SetXY($x3, $y_ttd);
					$pdf->Cell(45, 5, 'Disetujui Oleh', 0, 1, 'C');

					$pdf->write2DBarcode(
						"Dibuat secara digital oleh\n" . $qc_text . "\nQC Inspector\n" . $qc_time,
						'QRCODE,L',
						$x1 + 15,
						$y_ttd + 5,
						$qr_size,
						$qr_size
					);

					if (!empty($produksi_nama)) {

						$pdf->write2DBarcode(
							"Diketahui secara digital oleh\n" . $produksi_nama . "\nForeman/Forelady Produksi\n" . $produksi_time,
							'QRCODE,L',
							$x2 + 15,
							$y_ttd + 5,
							$qr_size,
							$qr_size
						);
					}

					$pdf->write2DBarcode(
						"Disetujui secara digital oleh\n" . $this->pegawai_model->get_nama_lengkap($spv_nama) . "\nSupervisor QC Bread Crumb\n" . $spv_time,
						'QRCODE,L',
						$x3 + 15,
						$y_ttd + 5,
						$qr_size,
						$qr_size
					);

					$pdf->SetXY($x1, $y_ttd + 20);
					$pdf->Cell(45, 5, 'QC Inspector', 0, 0, 'C');

					$pdf->SetXY($x2, $y_ttd + 20);
					$pdf->Cell(45, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');

					$pdf->SetXY($x3, $y_ttd + 20);
					$pdf->Cell(45, 5, 'Supervisor QC', 0, 1, 'C');
				} else {

					$pdf->Ln(8);

					$pdf->SetTextColor(255, 0, 0);

					$pdf->Cell(
						0,
						6,
						'Data Belum Diverifikasi',
						0,
						1,
						'C'
					);

					$pdf->SetTextColor(0, 0, 0);
				}
			} // ===== END FOREACH SHIFT =====

			$filename = "Suhu Ruang_{$formatted_date2}.pdf";
			$pdf->Output($filename, 'I');
		} else {

			foreach ($shift_data as $shift => $data_shift) {

				$pdf->AddPage(); // Portrait seperti controller lama

				$pdf->SetFont('times', 'B', 12);

				if (file_exists($logo_path)) {
					$pdf->Image($logo_path, 10, 10, 35);
				}

				$pdf->Ln(10);

				$pdf->MultiCell(
					0,
					5,
					'PEMANTAUAN SUHU DAN KELEMBAPAN RUANG',
					0,
					'C'
				);

				$pdf->Ln(6);

				$pdf->SetFont('times', '', 9);

				$pdf->Cell(90, 5, 'Tanggal : ' . $formatted_date, 0, 0, 'L');
				$pdf->Cell(30, 5, 'Shift : ' . $shift, 0, 1, 'L');

				$pdf->Ln(3);

				// FORMAT CIKANDE LAMA
				$grouped_by_time = [];

				foreach ($data_shift as $item) {

					$jam = date('H:i', strtotime($item->pukul));

					$lokasi_array = json_decode($item->lokasi, true);

					if (!$lokasi_array) {
						continue;
					}

					foreach ($lokasi_array as $lok) {

						$grouped_by_time[$jam][$lok['nama_lokasi']] = [
							'suhu' => $lok['suhu'] ?? '-',
							'rh'   => $lok['rh'] ?? '-'
						];
					}
				}

				ksort($grouped_by_time);
				$pdf->SetFont('times', '', 6.5);
				$baris_tinggi = 4;

				$pdf->Cell($col_pukul, $baris_tinggi * 3, 'Pukul', 1, 0, 'C');

				foreach ($lokasi_unik as $lokasi) {

					$width = ($lokasi == 'Ruang Produksi (Bubble)')
						? ($col_suhu * 3) // Bubble lebih lebar
						: ($col_suhu * 2);

					$pdf->Cell($width, $baris_tinggi, $lokasi, 1, 0, 'C');
				}

				$pdf->Ln();

				$pdf->Cell($col_pukul, $baris_tinggi, '', 0, 0);

				foreach ($lokasi_unik as $lokasi) {

					if ($lokasi == 'Ruang Produksi (Bubble)') {
						$pdf->Cell($col_suhu * 1.5, $baris_tinggi, 'Suhu', 1, 0, 'C');
						$pdf->Cell($col_suhu * 1.5, $baris_tinggi, 'RH (%)', 1, 0, 'C');
					} else {
						$pdf->Cell($col_suhu, $baris_tinggi, 'Suhu', 1, 0, 'C');
						$pdf->Cell($col_suhu, $baris_tinggi, 'RH (%)', 1, 0, 'C');
					}
				}

				$pdf->Ln();

				$pdf->Cell($col_pukul, $baris_tinggi, 'Standar', 1, 0, 'C');

				foreach ($lokasi_unik as $lokasi) {

					if ($lokasi == 'Ruang Produksi (Bubble)') {
						$pdf->Cell($col_suhu * 1.5, $baris_tinggi, $standar[$lokasi][0], 1, 0, 'C');
						$pdf->Cell($col_suhu * 1.5, $baris_tinggi, $standar[$lokasi][1], 1, 0, 'C');
					} else {
						$pdf->Cell($col_suhu, $baris_tinggi, $standar[$lokasi][0], 1, 0, 'C');
						$pdf->Cell($col_suhu, $baris_tinggi, $standar[$lokasi][1], 1, 0, 'C');
					}
				}

				$pdf->Ln();

				foreach ($grouped_by_time as $jam => $lokasi_data) {

					$pdf->Cell($col_pukul, $baris_tinggi, $jam, 1, 0, 'C');

					foreach ($lokasi_unik as $lokasi) {

						$suhu = $lokasi_data[$lokasi]['suhu'] ?? '-';
						$rh   = $lokasi_data[$lokasi]['rh'] ?? '-';

						if ($lokasi == 'Ruang Produksi (Bubble)') {
							$pdf->Cell($col_suhu * 1.5, $baris_tinggi, $suhu, 1, 0, 'C');
							$pdf->Cell($col_suhu * 1.5, $baris_tinggi, $rh, 1, 0, 'C');
						} else {
							$pdf->Cell($col_suhu, $baris_tinggi, $suhu, 1, 0, 'C');
							$pdf->Cell($col_suhu, $baris_tinggi, $rh, 1, 0, 'C');
						}
					}

					$pdf->Ln();
				}

				$pdf->SetFont('times', 'I', 7);
				$page_width = $pdf->getPageWidth();

				$pdf->Cell($page_width - 20, 5, 'QB 06/00', 0, 1, 'R');

				$pdf->SetY($pdf->GetY() + 2);
				$pdf->SetFont('times', '', 8);
				$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
				foreach ($data_shift as $item) {
					if (!empty($item->catatan)) {
						$pdf->Cell(8, 0, '', 0, 0, 'L');
						$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
					}
				}

				$y_after_keterangan = $pdf->GetY() + 2;
				$status_verifikasi = true;
				foreach ($data_shift as $item) {
					if ($item->status_spv != '1') {
						$status_verifikasi = false;
						break;
					}
				}

				$pdf->SetFont('times', '', 8);
				$pdf->SetTextColor(0, 0, 0);

				$y_ttd   = $pdf->GetY() + 6;
				$qr_size = 15;
				$page_width = $pdf->getPageWidth();

				if ($is_salatiga) {

					$x1 = 40;
					$x2 = ($page_width / 2) - 10;
					$x3 = $page_width - 70;
				} else {

					$x1 = 20;
					$x2 = 85;
					$x3 = 150;
				}

				$qc_usernames  = [];
				$qc_created_at = null;

				foreach ($data_shift as $item) {
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

				if (!empty($data['suhu']->nama_lengkap_produksi) && !empty($data['suhu']->tgl_update_produksi)) {
					$prod_tanggal = (new DateTime($data['suhu']->tgl_update_produksi ?? $data['suhu']->tgl_update_produksi))
						->format('d-m-Y | H:i');

					$qr_produksi_text = "Diketahui secara digital oleh,\n"
						. $data['suhu']->nama_lengkap_produksi . "\n"
						. "Foreman/Forelady Produksi\n"
						. $prod_tanggal;
				}

				$spv_tanggal = !empty($data['suhu']->tgl_update_spv)
					? (new DateTime($data['suhu']->tgl_update_spv))->format('d-m-Y | H:i')
					: '-';

				$qr_spv_text = "Disetujui secara digital oleh,\n"
					. $data['suhu']->nama_lengkap_spv . "\n"
					. "Supervisor QC Bread Crumb\n"
					. $spv_tanggal;

				if ($status_verifikasi) {
					$pdf->SetFont('times', '', 8);
					$pdf->SetXY($x1, $y_ttd);
					$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');

					$pdf->SetXY($x2, $y_ttd);
					$pdf->Cell(45, 5, 'Diketahui Oleh,', 0, 0, 'C');

					$pdf->SetXY($x3, $y_ttd);
					$pdf->Cell(45, 5, 'Disetujui Oleh,', 0, 1, 'C');

					$pdf->write2DBarcode(
						$qr_qc_text,
						'QRCODE,L',
						$x1 + 15,
						$y_ttd + 5,
						$qr_size,
						$qr_size,
						null,
						'N'
					);

					if ($qr_produksi_text) {

						$pdf->write2DBarcode(
							$qr_produksi_text,
							'QRCODE,L',
							$x2 + 15,
							$y_ttd + 5,
							$qr_size,
							$qr_size,
							null,
							'N'
						);
					}

					$pdf->write2DBarcode(
						$qr_spv_text,
						'QRCODE,L',
						$x3 + 15,
						$y_ttd + 5,
						$qr_size,
						$qr_size,
						null,
						'N'
					);

					$pdf->SetXY($x1, $y_ttd + 20);
					$pdf->Cell(45, 5, 'QC Inspector', 0, 0, 'C');

					$pdf->SetXY($x2, $y_ttd + 20);
					$pdf->Cell(45, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');

					$pdf->SetXY($x3, $y_ttd + 20);
					$pdf->Cell(45, 5, 'Supervisor QC', 0, 1, 'C');
				} else {
					$pdf->SetFont('times', '', 8);
					$pdf->SetTextColor(255, 0, 0);
					$page_width = $pdf->getPageWidth();

					$pdf->SetXY(($page_width / 2) - 40, $y_ttd);
					$pdf->Cell(80, 6, 'Data Belum Diverifikasi', 0, 1, 'C');
					$pdf->SetTextColor(0, 0, 0);
				}

				$pdf->setPrintFooter(false);
			}
			$filename = "Suhu Ruang_{$formatted_date2}.pdf";
			$pdf->Output($filename, 'I');
		}
	}


	/*public function cetak()
	{
		$tanggal = $this->input->post('tanggal');
		$plant_id = $this->session->userdata('plant');

		if (empty($tanggal)) {
			show_error('Tanggal tidak boleh kosong', 404);
		}

		$user_uuid = $this->session->userdata('uuid');
		$this->load->model('pegawai_model');
		$plant_uuid = $this->pegawai_model->get_plant_uuid_by_user($user_uuid);

		$this->load->model('suhu_model');
		$plant_uuid = $this->session->userdata('plant');

		$suhu_data = $this->suhu_model->get_by_date_and_plant_pdf($tanggal, $plant_uuid);
		$suhu_data_verif = $this->suhu_model->get_by_date_verif_and_plant($tanggal, $plant_uuid);

		$data['suhu'] = $suhu_data_verif;

		if (!$data['suhu']) {
			show_error('Data tidak ditemukan', 404);
		}

		$data['suhu']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['suhu']->username);
		$data['suhu']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['suhu']->nama_spv);
		$data['suhu']->nama_lengkap_produksi = $data['suhu']->nama_produksi;

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$is_cikande = ($plant_id === '651ac623-5e48-44cc-b2f6-5d622603f53c');
		$is_salatiga = !$is_cikande;

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);

		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 14, 10);

		if ($is_salatiga) {
			$pdf->AddPage('L'); // Landscape
		} else {
			$pdf->AddPage(); // Portrait
		}

		$pdf->SetFont('times', 'B', 12);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 35);
		}

		$pdf->Ln(10);
		$pdf->MultiCell(0, 5, 'PEMANTAUAN SUHU DAN KELEMBAPAN RUANG', 0, 'C');
		$pdf->Ln(4);

		$datetime = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $datetime->getTimestamp());
		$formatted_date2 = date('l, d F Y', $datetime->getTimestamp());

		$pdf->SetFont('times', '', 9);
		$pdf->SetX(10);
		$pdf->Write(0, 'Tanggal: ' . $formatted_date);
		$pdf->Ln(6);

		//$is_cikande = ($plant_id === '651ac623-5e48-44cc-b2f6-5d622603f53c');
		//$is_salatiga = !$is_cikande;

		$lokasi = $is_salatiga
			? [
				"Ruang Pengayakan",
				"Ruang RM",
				"Chiller 1",
				"Chiller 2",
				"Chiller 3",
				"Chiller 4",
				"Chiller 5",
				"Chiller 6",
				"Ruang Mixing",
				"Area Baking",
				"Area Cutting & Grinding",
				"Ruang Aging",
				"Area Packing"
			]
			: [
				"Ruang Produksi",
				"Gudang Premix",
				"Gudang Raw Material",
				"Gudang Finish Good",
				"Proofing Room",
				"Aging Room 1",
				"Aging Room 2",
				"Area Packing",
				"Ruang Produksi (Bubble)"
			];

		$standar = [];

		foreach ($lokasi as $nama) {

			if ($is_salatiga) {

				if (strpos($nama, 'Chiller') !== false) {
					$standar[$nama] = ['0-4', ''];
				} elseif ($nama == 'Ruang RM') {
					$standar[$nama] = ['15-22', ''];
				} elseif ($nama == 'Ruang Aging') {
					$standar[$nama] = ['35-45', ''];
				} else {
					$standar[$nama] = ['25-35', ''];
				}
			} else {

				$default = [
					"Ruang Produksi" => ["25-35", "65-80"],
					"Gudang Premix" => ["15-22", "45-55"],
					"Gudang Raw Material" => ["25-35", "60-75"],
					"Gudang Finish Good" => ["28-36", "60-75"],
					"Proofing Room" => ["34-36", "78-82"],
					"Aging Room 1" => ["35-45", "50-70"],
					"Aging Room 2" => ["35-45", "50-70"],
					"Area Packing" => ["25-35", ""],
					"Ruang Produksi (Bubble)" => ["25-35", "65-80"]
				];

				$standar[$nama] = $default[$nama] ?? ['', ''];
			}
		}

		$lokasi_unik = [];
		foreach ($suhu_data as $item) {
			$lokasi_array = json_decode($item->lokasi, true);
			foreach ($lokasi_array as $lok) {
				$lokasi_unik[] = $lok['nama_lokasi'];
			}
		}
		$lokasi_unik = array_unique($lokasi_unik);

		$total_kolom_data = count($lokasi_unik) * ($is_cikande ? 2 : 1);

		if ($is_salatiga) {

			$max_table_width = $pdf->getPageWidth() - 20; // margin kiri+kanan 10
			$col_pukul = 18;
			$col_suhu = ($max_table_width - $col_pukul) / $total_kolom_data;
		} else {

			$col_suhu = 10;
			$max_table_width = 195;
			$col_pukul = 25;
		}
		// =====================================
		// MATRIX DATA SUHU & RH
		// =====================================

		$matrix = [];
		$humidity_matrix = [];

		foreach ($suhu_data as $item) {

			$jam = date('H:i', strtotime($item->pukul));

			$lokasi_array = json_decode($item->lokasi, true);

			if (!$lokasi_array) {
				continue;
			}

			foreach ($lokasi_array as $lok) {

				$nama_lokasi = trim($lok['nama_lokasi']);

				// suhu
				$nilai_suhu = trim((string)($lok['suhu'] ?? ''));

				$matrix[$nama_lokasi][$jam] =
					($nilai_suhu === '' || strtolower($nilai_suhu) === 'kosong')
					? '-'
					: $nilai_suhu;

				// RH
				$nilai_rh = trim((string)($lok['rh'] ?? ''));

				$humidity_matrix[$nama_lokasi][$jam] =
					($nilai_rh === '' || strtolower($nilai_rh) === 'kosong')
					? '-'
					: $nilai_rh;
			}
		}
		if ($is_salatiga) {

			$pdf->SetFont('times', '', 8);

			$jam_list = [];

			foreach ($suhu_data as $item) {

				$jam = date('H:i', strtotime($item->pukul));

				if (!in_array($jam, $jam_list)) {
					$jam_list[] = $jam;
				}
			}

			sort($jam_list);

			$w_no = 10;
			$w_lokasi = 40;
			$w_std = 18;
			$w_ket = 40;

			$total_width = $pdf->getPageWidth() - 20;

			$w_jam = ($total_width - $w_no - $w_lokasi - $w_std - $w_ket)
				/ count($jam_list);

			// HEADER
			$y = $pdf->GetY();
			$x = $pdf->GetX();

			// No
			$pdf->Cell($w_no, 12, 'No.', 1, 0, 'C');

			// Lokasi
			$pdf->Cell($w_lokasi, 12, 'Lokasi', 1, 0, 'C');

			// Standar
			$pdf->Cell($w_std, 12, 'Standar', 1, 0, 'C');

			// Hasil Pemeriksaan
			$pdf->Cell(
				$w_jam * count($jam_list),
				6,
				'Hasil Pemeriksaan',
				1,
				0,
				'C'
			);

			// Keterangan
			$pdf->Cell($w_ket, 12, 'Keterangan', 1, 0, 'C');

			// pindah ke bawah header hasil pemeriksaan
			$pdf->SetXY(
				$x + $w_no + $w_lokasi + $w_std,
				$y + 6
			);

			// header jam
			foreach ($jam_list as $jam) {
				$pdf->Cell($w_jam, 6, $jam, 1, 0, 'C');
			}

			$pdf->Ln();

			$pdf->SetFont('times', 'B', 9);

			$pdf->Cell(
				$w_no + $w_lokasi + $w_std + ($w_jam * count($jam_list)) + $w_ket,
				6,
				'Pemantauan Suhu (°C)',
				1,
				1,
				'L'
			);

			$pdf->SetFont('times', '', 8);

			$no = 1;

			foreach ($lokasi as $nama_lokasi) {

				$pdf->Cell($w_no, 6, $no++, 1, 0, 'C');

				$pdf->Cell(
					$w_lokasi,
					6,
					$nama_lokasi,
					1,
					0,
					'L'
				);

				$pdf->Cell(
					$w_std,
					6,
					$standar[$nama_lokasi][0],
					1,
					0,
					'C'
				);

				foreach ($jam_list as $jam) {

					$nilai = $matrix[$nama_lokasi][$jam] ?? '-';

					$pdf->Cell(
						$w_jam,
						6,
						$nilai,
						1,
						0,
						'C'
					);
				}

				$pdf->Cell($w_ket, 6, '', 1, 1, 'C');
			}
			// ====================================
			// PEMANTAUAN KELEMBAPAN
			// ====================================

			$pdf->SetFont('times', 'B', 9);

			$pdf->Cell(
				$w_no + $w_lokasi + $w_std + ($w_jam * count($jam_list)) + $w_ket,
				6,
				'Pemantauan Kelembapan (%)',
				1,
				1,
				'L'
			);

			$pdf->SetFont('times', '', 8);

			$pdf->Cell($w_no, 6, '1', 1, 0, 'C');

			$pdf->Cell(
				$w_lokasi,
				6,
				'Ruang Aging',
				1,
				0,
				'L'
			);

			$pdf->Cell($w_std, 6, '', 1, 0, 'C');

			foreach ($jam_list as $jam) {

				$nilai = $humidity_matrix['Ruang Aging'][$jam] ?? '-';

				$pdf->Cell(
					$w_jam,
					6,
					$nilai,
					1,
					0,
					'C'
				);
			}

			$pdf->Cell($w_ket, 6, '', 1, 1, 'C');
		} else {

			// FORMAT CIKANDE LAMA
			$grouped_by_time = [];

			foreach ($suhu_data as $item) {

				$jam = date('H:i', strtotime($item->pukul));

				$lokasi_array = json_decode($item->lokasi, true);

				if (!$lokasi_array) {
					continue;
				}

				foreach ($lokasi_array as $lok) {

					$grouped_by_time[$jam][$lok['nama_lokasi']] = [
						'suhu' => $lok['suhu'] ?? '-',
						'rh'   => $lok['rh'] ?? '-'
					];
				}
			}

			ksort($grouped_by_time);
			$pdf->SetFont('times', '', 6.5);
			$baris_tinggi = 4;

			$pdf->Cell($col_pukul, $baris_tinggi * 3, 'Pukul', 1, 0, 'C');

			foreach ($lokasi_unik as $lokasi) {

				$width = ($lokasi == 'Ruang Produksi (Bubble)')
					? ($col_suhu * 3) // Bubble lebih lebar
					: ($col_suhu * 2);

				$pdf->Cell($width, $baris_tinggi, $lokasi, 1, 0, 'C');
			}

			$pdf->Ln();

			$pdf->Cell($col_pukul, $baris_tinggi, '', 0, 0);

			foreach ($lokasi_unik as $lokasi) {

				if ($lokasi == 'Ruang Produksi (Bubble)') {
					$pdf->Cell($col_suhu * 1.5, $baris_tinggi, 'Suhu', 1, 0, 'C');
					$pdf->Cell($col_suhu * 1.5, $baris_tinggi, 'RH (%)', 1, 0, 'C');
				} else {
					$pdf->Cell($col_suhu, $baris_tinggi, 'Suhu', 1, 0, 'C');
					$pdf->Cell($col_suhu, $baris_tinggi, 'RH (%)', 1, 0, 'C');
				}
			}

			$pdf->Ln();

			$pdf->Cell($col_pukul, $baris_tinggi, 'Standar', 1, 0, 'C');

			foreach ($lokasi_unik as $lokasi) {

				if ($lokasi == 'Ruang Produksi (Bubble)') {
					$pdf->Cell($col_suhu * 1.5, $baris_tinggi, $standar[$lokasi][0], 1, 0, 'C');
					$pdf->Cell($col_suhu * 1.5, $baris_tinggi, $standar[$lokasi][1], 1, 0, 'C');
				} else {
					$pdf->Cell($col_suhu, $baris_tinggi, $standar[$lokasi][0], 1, 0, 'C');
					$pdf->Cell($col_suhu, $baris_tinggi, $standar[$lokasi][1], 1, 0, 'C');
				}
			}

			$pdf->Ln();

			foreach ($grouped_by_time as $jam => $lokasi_data) {

				$pdf->Cell($col_pukul, $baris_tinggi, $jam, 1, 0, 'C');

				foreach ($lokasi_unik as $lokasi) {

					$suhu = $lokasi_data[$lokasi]['suhu'] ?? '-';
					$rh   = $lokasi_data[$lokasi]['rh'] ?? '-';

					if ($lokasi == 'Ruang Produksi (Bubble)') {
						$pdf->Cell($col_suhu * 1.5, $baris_tinggi, $suhu, 1, 0, 'C');
						$pdf->Cell($col_suhu * 1.5, $baris_tinggi, $rh, 1, 0, 'C');
					} else {
						$pdf->Cell($col_suhu, $baris_tinggi, $suhu, 1, 0, 'C');
						$pdf->Cell($col_suhu, $baris_tinggi, $rh, 1, 0, 'C');
					}
				}

				$pdf->Ln();
			}
		}

		$pdf->SetFont('times', 'I', 7);
		$page_width = $pdf->getPageWidth();

		$pdf->Cell($page_width - 20, 5, 'QB 06/00', 0, 1, 'R');

		$pdf->SetY($pdf->GetY() + 2);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($suhu_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(8, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($suhu_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		$y_ttd   = $pdf->GetY() + 6;
		$qr_size = 15;
		$page_width = $pdf->getPageWidth();

		if ($is_salatiga) {

			$x1 = 40;
			$x2 = ($page_width / 2) - 10;
			$x3 = $page_width - 70;
		} else {

			$x1 = 20;
			$x2 = 85;
			$x3 = 150;
		}

		$qc_usernames  = [];
		$qc_created_at = null;

		foreach ($suhu_data as $item) {
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

		if (!empty($data['suhu']->nama_lengkap_produksi) && !empty($data['suhu']->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($data['suhu']->tgl_update_produksi ?? $data['suhu']->tgl_update_produksi))
				->format('d-m-Y | H:i');

			$qr_produksi_text = "Diketahui secara digital oleh,\n"
				. $data['suhu']->nama_lengkap_produksi . "\n"
				. "Foreman/Forelady Produksi\n"
				. $prod_tanggal;
		}

		$spv_tanggal = !empty($data['suhu']->tgl_update_spv)
			? (new DateTime($data['suhu']->tgl_update_spv))->format('d-m-Y | H:i')
			: '-';

		$qr_spv_text = "Disetujui secara digital oleh,\n"
			. $data['suhu']->nama_lengkap_spv . "\n"
			. "Supervisor QC Bread Crumb\n"
			. $spv_tanggal;

		if ($status_verifikasi) {
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY($x1, $y_ttd);
			$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');

			$pdf->SetXY($x2, $y_ttd);
			$pdf->Cell(45, 5, 'Diketahui Oleh,', 0, 0, 'C');

			$pdf->SetXY($x3, $y_ttd);
			$pdf->Cell(45, 5, 'Disetujui Oleh,', 0, 1, 'C');

			$pdf->write2DBarcode(
				$qr_qc_text,
				'QRCODE,L',
				$x1 + 15,
				$y_ttd + 5,
				$qr_size,
				$qr_size,
				null,
				'N'
			);

			if ($qr_produksi_text) {

				$pdf->write2DBarcode(
					$qr_produksi_text,
					'QRCODE,L',
					$x2 + 15,
					$y_ttd + 5,
					$qr_size,
					$qr_size,
					null,
					'N'
				);
			}

			$pdf->write2DBarcode(
				$qr_spv_text,
				'QRCODE,L',
				$x3 + 15,
				$y_ttd + 5,
				$qr_size,
				$qr_size,
				null,
				'N'
			);

			$pdf->SetXY($x1, $y_ttd + 20);
			$pdf->Cell(45, 5, 'QC Inspector', 0, 0, 'C');

			$pdf->SetXY($x2, $y_ttd + 20);
			$pdf->Cell(45, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');

			$pdf->SetXY($x3, $y_ttd + 20);
			$pdf->Cell(45, 5, 'Supervisor QC', 0, 1, 'C');
		} else {
			$pdf->SetFont('times', '', 8);
			$pdf->SetTextColor(255, 0, 0);
			$page_width = $pdf->getPageWidth();

			$pdf->SetXY(($page_width / 2) - 40, $y_ttd);
			$pdf->Cell(80, 6, 'Data Belum Diverifikasi', 0, 1, 'C');
			$pdf->SetTextColor(0, 0, 0);
		}

		$pdf->setPrintFooter(false);
		$filename = "Suhu Ruang_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}*/


	public function export_excel()
	{
		require_once(APPPATH . 'libraries/phpqrcode.php');

		$tanggal = $this->input->post('tanggal') ?: $this->input->get('tanggal');
		if (!$tanggal) {
			show_error('Tanggal tidak boleh kosong');
		}

		$this->load->model('suhu_model');
		$this->load->model('pegawai_model');

		$plant_uuid = $this->session->userdata('plant');
		$suhu_data = $this->suhu_model->get_by_date_and_plant_pdf($tanggal, $plant_uuid);

		if (!$suhu_data) {
			show_error('Data tidak ditemukan');
		}

		$data['suhu'] = $suhu_data[0];
		$data['suhu']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['suhu']->username ?? '');
		$data['suhu']->nama_lengkap_produksi = $this->pegawai_model->get_nama_lengkap($data['suhu']->nama_produksi ?? '');
		$data['suhu']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['suhu']->nama_spv ?? '');

		$pegawai_login = $this->pegawai_model->get_by_uuid($this->session->userdata('user_uuid'));
		$is_salatiga = ($pegawai_login->plant === '1eb341e0-1ec4-4484-ba8f-32d23352b84d');

		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		$judul = $is_salatiga ? 'PEMERIKSAAN SUHU RUANG - SALATIGA' : 'PEMERIKSAAN SUHU RUANG - CIKANDE';
		$lokasi = $is_salatiga
			? ["Ruang Pengayakan", "Ruang RM", "Chiller 1", "Chiller 2", "Chiller 3", "Chiller 4", "Chiller 5", "Chiller 6", "Ruang Mixing", "Area Baking", "Area Cutting & Grinding", "Ruang Aging", "Area Packing"]
			: ["Ruang Produksi", "Gudang Premix", "Gudang Raw Material", "Gudang Finish Good", "Proofing Room", "Aging Room 1", "Aging Room 2", "Ruang Produksi (Bubble)"];

		$standar = [];
		foreach ($lokasi as $nama) {
			$standar[$nama] = ["", ""];
			if ($is_salatiga) {
				if (str_contains($nama, 'Chiller')) {
					$standar[$nama] = ["0-4", ""];
				} elseif ($nama == 'Ruang RM') {
					$standar[$nama] = ["15-22", ""];
				} elseif ($nama == 'Ruang Aging') {
					$standar[$nama] = ["35-45", ""];
				} else {
					$standar[$nama] = ["25-35", ""];
				}
			} else {
				$default = [
					"Ruang Produksi" => ["25-35", "65-80"],
					"Gudang Premix" => ["15-22", "45-55"],
					"Gudang Raw Material" => ["25-35", "60-75"],
					"Gudang Finish Good" => ["28-36", "60-75"],
					"Proofing Room" => ["34-36", "78-82"],
					"Aging Room 1" => ["35-45", "50-70"],
					"Aging Room 2" => ["35-45", "50-70"],
					"Ruang Produksi (Bubble)" => ["25-35", "65-80"]
				];
				$standar[$nama] = $default[$nama] ?? ["", ""];
			}
		}

		$sheet->mergeCells('A1:Z1');
		$sheet->setCellValue('A1', $judul);
		$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setRGB('000000');
		$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

		$sheet->mergeCells('A2:Z2');
		$sheet->setCellValue('A2', 'Tanggal: ' . date('d-m-Y', strtotime($tanggal)));

		// Header Lokasi
		$sheet->setCellValue('A4', 'Pukul');
		$col = 2;
		foreach ($lokasi as $nama) {
			$colLetter = Coordinate::stringFromColumnIndex($col);
			$nextColLetter = Coordinate::stringFromColumnIndex($col + 1);
			$sheet->mergeCells("{$colLetter}4:{$nextColLetter}4");
			$sheet->setCellValue("{$colLetter}4", $nama);
			$sheet->setCellValue("{$colLetter}5", 'Suhu');
			$sheet->setCellValue("{$nextColLetter}5", 'RH%');
			$col += 2;
		}

		// Baris STD
		$sheet->setCellValue('A6', 'STD');
		$col = 2;
		foreach ($lokasi as $nama) {
			$sheet->setCellValue(Coordinate::stringFromColumnIndex($col) . '6', $standar[$nama][0]);
			$sheet->setCellValue(Coordinate::stringFromColumnIndex($col + 1) . '6', $standar[$nama][1]);
			$col += 2;
		}

		// Data suhu per jam
		$grouped = [];
		foreach ($suhu_data as $item) {
			$jam = date('H:i', strtotime($item->pukul));
			$lokasi_array = json_decode($item->lokasi, true);
			foreach ($lokasi_array as $lok) {
				$grouped[$jam][$lok['nama_lokasi']] = (object)[
					'suhu' => $lok['suhu'] ?? '',
					'rh' => $lok['rh'] ?? ''
				];
			}
		}

		ksort($grouped);
		$row = 7;
		foreach ($grouped as $jam => $lokasi_data) {
			$sheet->setCellValue("A{$row}", $jam);
			$col = 2;
			foreach ($lokasi as $nama) {
				$isi = $lokasi_data[$nama] ?? null;
				$sheet->setCellValue(Coordinate::stringFromColumnIndex($col) . $row, $isi->suhu ?? '');
				$sheet->setCellValue(Coordinate::stringFromColumnIndex($col + 1) . $row, $isi->rh ?? '');
				$col += 2;
			}
			$row++;
		}

		// Tanda tangan
		$row += 3;
		$sheet->setCellValue("C{$row}", "Dibuat Oleh");
		$sheet->setCellValue("G{$row}", "Diketahui Oleh");
		$sheet->setCellValue("K{$row}", "Disetujui Oleh");

		$row += 4;
		$sheet->setCellValue("C{$row}", $data['suhu']->nama_lengkap_qc ?: '-');
		$sheet->setCellValue("C" . ($row + 1), "QC Inspector");
		$sheet->setCellValue("G{$row}", $data['suhu']->nama_lengkap_produksi ?: '-');
		$sheet->setCellValue("G" . ($row + 1), "Foreman/Forelady Produksi");
		$sheet->setCellValue("K{$row}", $data['suhu']->nama_lengkap_spv ?: '-');
		$sheet->setCellValue("K" . ($row + 1), "Supervisor QC");

		// QR SPV
		$qrTextSPV = "Diverifikasi secara digital oleh:\n" .
			($data['suhu']->nama_lengkap_spv ?: '-') .
			"\nSupervisor QC Bread Crumb\nTanggal: " . ($data['suhu']->tgl_update_spv ?? '-');
		$qrPathSPV = FCPATH . 'assets/qr_spv.png';
		QRcode::png($qrTextSPV, $qrPathSPV, QR_ECLEVEL_H, 4);
		$drawingSPV = new Drawing();
		$drawingSPV->setPath($qrPathSPV);
		$drawingSPV->setCoordinates("K" . ($row - 3));
		$drawingSPV->setHeight(80);
		$drawingSPV->setWorksheet($sheet);

		// Border & style
		$lastDataCol = Coordinate::stringFromColumnIndex($col - 1);
		$lastDataRow = $row - 6;
		$sheet->getStyle("A4:{$lastDataCol}{$lastDataRow}")->applyFromArray([
			'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
			'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
		]);

		// Output Excel
		$lokasi_excel = $is_salatiga ? 'Salatiga' : 'Cikande';
		$filename = 'Laporan_Suhu_Ruang_' . $lokasi_excel . '_' . date('d-m-Y', strtotime($tanggal)) . '.xlsx';
		ob_end_clean();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit;
	}

	public function export_excel_salatiga()
	{
		require_once(APPPATH . 'libraries/phpqrcode.php');

		$this->load->model('suhu_model');
		$this->load->model('pegawai_model');

		$tanggal = $this->input->post('tanggal');
		if (empty($tanggal)) {
			show_error('Tanggal tidak boleh kosong', 404);
		}

		$plant_salatiga = '1eb341e0-1ec4-4484-ba8f-32d23352b84d';

		$suhu_data_raw = $this->suhu_model->get_by_date_verif_excel($tanggal);

		$suhu_data = array_values(array_filter($suhu_data_raw, function ($item) use ($plant_salatiga) {
			return isset($item->plant) && $item->plant === $plant_salatiga;
		}));

		if (empty($suhu_data)) {
			show_error('Data Suhu tidak ditemukan', 404);
		}

		// =========================
		// CEK STATUS VERIFIKASI SPV
		// =========================
		$status_verifikasi = true;
		foreach ($suhu_data as $item) {
			if ((string)$item->status_spv !== '1') {
				$status_verifikasi = false;
				break;
			}
		}

		// =========================
		// LOAD TEMPLATE
		// =========================
		$filePath = FCPATH . 'assets/excel/Pemeriksaan Suhu Ruang.xlsx';
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
		$sheet = $spreadsheet->getActiveSheet();

		$sheet->setCellValue('A2', 'Tanggal : ' . date('d-m-Y', strtotime($tanggal)));

		$data = [];
		$jamList = [];

		// foreach ($suhu_data as $item) {
		//     $jam = date('H:i', strtotime($item->pukul));
		//     $jamList[$jam] = true;

		//     foreach (json_decode($item->lokasi, true) as $lok) {
		//         $area = $lok['nama_lokasi'];
		//         $data[$area][$jam] = $lok['suhu'] ?? '-';
		//     }
		// }

		foreach ($suhu_data as $item) {

			// Ambil jam
			$jam = date('H:i', strtotime($item->pukul));
			$jamList[$jam] = true;

			foreach (json_decode($item->lokasi, true) as $lok) {

				$area = $lok['nama_lokasi'];
				$suhu = trim($lok['suhu'] ?? '');
				$rh   = $lok['rh'] ?? null;

				// Simpan suhu (apa adanya, termasuk "Kosong")
				$data[$area][$jam] = $suhu;

				// Khusus Ruang Aging → RH baris bawah
				if ($area === 'Ruang Aging' && $rh !== null) {
					$data[$area . ' (RH)'][$jam] = $rh . '%';
				}
			}
		}

		$jamList = array_keys($jamList);
		sort($jamList);

		$colStart = 3;
		$rowJam   = 5;

		foreach ($jamList as $i => $jam) {
			$col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + $i);
			$sheet->setCellValue($col . $rowJam, $jam);
		}

		$rowArea = 6;
		foreach ($data as $area => $jamData) {
			$sheet->setCellValue('A' . $rowArea, $area);

			foreach ($jamList as $i => $jam) {
				$col = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colStart + $i);
				$sheet->setCellValue($col . $rowArea, $jamData[$jam] ?? '-');
			}
			$rowArea++;
		}

		// =========================
		// TTD / VERIFIKASI
		// =========================
		$ttdRow = $rowArea + 2;
		$ttdDataRow = $ttdRow + 1;

		if (!$status_verifikasi) {

			// =========================
			// DATA BELUM VERIFIKASI
			// ========================= 
			$sheet->mergeCells("B{$ttdRow}:J{$ttdRow}");
			$sheet->setCellValue("B{$ttdRow}", 'Data belum terverifikasi');

			$sheet->getStyle("B{$ttdRow}")->applyFromArray([
				'font' => [
					'color' => ['rgb' => 'FF0000'],
					'bold'  => true,
					'size'  => 12
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
				]
			]);
		} else {

			// =========================
			// QR & TTD DIGITAL
			// =========================
			$sheet->setCellValue("B{$ttdRow}", 'Dibuat Oleh,');
			$sheet->setCellValue("E{$ttdRow}", 'Diketahui Oleh,');
			$sheet->setCellValue("H{$ttdRow}", 'Disetujui Oleh,');

			$sheet->mergeCells("B{$ttdRow}:D{$ttdRow}");
			$sheet->mergeCells("E{$ttdRow}:G{$ttdRow}");
			$sheet->mergeCells("H{$ttdRow}:J{$ttdRow}");

			$sheet->getRowDimension($ttdDataRow)->setRowHeight(80);

			// ===== QC =====
			$qc_users = [];
			$qc_created_at = null;

			foreach ($suhu_data as $item) {
				if (!empty($item->username)) {
					$qc_users[] = $item->username;
				}
				if (!$qc_created_at && !empty($item->created_at)) {
					$qc_created_at = $item->created_at;
				}
			}

			$qc_users = array_unique($qc_users);
			$qc_names = [];

			foreach ($qc_users as $u) {
				$nama = $this->pegawai_model->get_nama_lengkap($u);
				if ($nama) $qc_names[] = $nama;
			}

			$qc_date = $qc_created_at
				? date('d-m-Y | H:i', strtotime($qc_created_at))
				: '-';

			$prod_date = !empty($suhu_data[0]->tgl_update_produksi)
				? date('d-m-Y | H:i', strtotime($suhu_data[0]->tgl_update_produksi))
				: '-';

			$spv_date = !empty($suhu_data[0]->tgl_update_spv)
				? date('d-m-Y | H:i', strtotime($suhu_data[0]->tgl_update_spv))
				: '-';

			$qrTexts = [
				'B' => "Dibuat secara digital oleh\n" .
					implode(', ', $qc_names) . "\nQC Inspector\n" . $qc_date,

				'E' => "Diketahui secara digital oleh\nForeman / Forelady Produksi\n" . $prod_date,

				'H' => "Disetujui secara digital oleh\nSupervisor QC\n" . $spv_date
			];

			foreach ($qrTexts as $col => $text) {
				$tmpFile = sys_get_temp_dir() . '/qr_' . uniqid() . '.png';
				QRcode::png($text, $tmpFile, QR_ECLEVEL_H, 4);

				$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
				$drawing->setPath($tmpFile);
				$drawing->setCoordinates($col . $ttdDataRow);
				$drawing->setHeight(80);
				$drawing->setWorksheet($sheet);
			}

			$ketRow = $ttdDataRow + 1;

			$sheet->setCellValue("B{$ketRow}", "QC Inspector");
			$sheet->setCellValue("E{$ketRow}", "Foreman / Forelady Produksi");
			$sheet->setCellValue("H{$ketRow}", "Supervisor QC");

			$sheet->mergeCells("B{$ketRow}:D{$ketRow}");
			$sheet->mergeCells("E{$ketRow}:G{$ketRow}");
			$sheet->mergeCells("H{$ketRow}:J{$ketRow}");
		}

		// =========================
		// OUTPUT
		// =========================
		$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment; filename=Suhu_Ruang_{$tanggal}.xlsx");
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
		exit;
	}
}
