<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Magnettrap extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');  
		$this->load->model('magnettrap_model');
		$this->load->library('upload');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'magnettrap' => $this->magnettrap_model->get_data_by_plant()
		);

		$this->active_nav = 'magnettrap'; 
		$this->render('form/magnettrap/magnettrap', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'magnettrap' => $this->magnettrap_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'magnettrap'; 
		$this->render('form/magnettrap/magnettrap-detail', $data);
	}


	public function file_check($str)
	{
		if (!isset($_FILES['bukti']) || $_FILES['bukti']['size'] == 0) {
			return true;
		}

		$max_size = 2 * 1024 * 1024;
		$file_size = $_FILES['bukti']['size'];
		$mime_type = $_FILES['bukti']['type'];

		$allowed_mime_types = ['image/jpeg', 'image/png', 'application/pdf'];

		if (!in_array($mime_type, $allowed_mime_types)) {
			$this->form_validation->set_message('file_check', 'File harus berformat JPEG, PNG, atau PDF');
			return false;
		}

		if ($file_size > $max_size) {
			$this->form_validation->set_message('file_check', 'Ukuran file maksimal 2MB');
			return false;
		}

		return true;
	}

	public function tambah()
	{
		$rules = $this->magnettrap_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$file_name = null; 

			if (!empty($_FILES['bukti']['name'])) {
				$config = array(
					'upload_path'   => "./uploads/magnettrap/",
					'allowed_types' => "jpg|png|jpeg|pdf",
					'overwrite'     => FALSE,
					'max_size'      => 2048,
					'encrypt_name'  => TRUE
				);

				$this->load->library(['upload', 'image_lib']);
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('bukti')) {
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error_msg', 'Upload gagal: ' . $error);
					redirect('magnettrap/tambah');
				}

				$data = $this->upload->data();
				$file_name = $data['file_name'];

                // Kompres gambar jika format image
				if (in_array($data['file_ext'], ['.jpg', '.jpeg', '.png'])) {
					$resize_config['image_library']  = 'gd2';
					$resize_config['source_image']   = $data['full_path'];
					$resize_config['maintain_ratio'] = TRUE;
					$resize_config['width']          = 800;
					$resize_config['height']         = 800;
					$resize_config['quality']        = '70%';

					$this->image_lib->initialize($resize_config);
					if (!$this->image_lib->resize()) {
						log_message('error', 'Kompresi gagal: ' . $this->image_lib->display_errors());
					}
					$this->image_lib->clear();
				}
			}

			$insert = $this->magnettrap_model->insert($file_name);

			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Magnet Trap berhasil disimpan');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Magnet Trap gagal disimpan');
			}

			redirect('magnettrap');
		}

		$data = array(
			'magnettrap' => $this->magnettrap_model->get_data_by_plant()
		);
		$this->active_nav = 'magnettrap'; 
		$this->render('form/magnettrap/magnettrap-tambah', $data);
	}

	public function edit($uuid)
	{
		$magnettrap = $this->magnettrap_model->get_by_uuid($uuid);
		$rules = $this->magnettrap_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$file_name = $magnettrap->bukti; 

			if (!empty($_FILES['bukti']['name'])) {
				$config = array(
					'upload_path'   => "./uploads/magnettrap/",
					'allowed_types' => "jpg|png|jpeg|pdf",
					'overwrite'     => FALSE,
					'max_size'      => 2048,
					'encrypt_name'  => TRUE
				);

				$this->load->library(['upload', 'image_lib']);
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('bukti')) {
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error_msg', 'Upload gagal: ' . $error);
					redirect('magnettrap/edit/' . $uuid);
				}

				$data = $this->upload->data();
				$file_name = $data['file_name'];

                // Kompres gambar jika format image
				if (in_array($data['file_ext'], ['.jpg', '.jpeg', '.png'])) {
					$resize_config['image_library']  = 'gd2';
					$resize_config['source_image']   = $data['full_path'];
					$resize_config['maintain_ratio'] = TRUE;
					$resize_config['width']          = 800;
					$resize_config['height']         = 800;
					$resize_config['quality']        = '70%';

					$this->image_lib->initialize($resize_config);
					if (!$this->image_lib->resize()) {
						log_message('error', 'Kompresi gagal: ' . $this->image_lib->display_errors());
					}
					$this->image_lib->clear();
				}

                // Hapus file lama
				$old_path = FCPATH . 'uploads/magnettrap/' . $magnettrap->bukti;
				if (!empty($magnettrap->bukti) && file_exists($old_path)) {
					unlink($old_path);
				}

			}

			$update = $this->magnettrap_model->update($uuid, $file_name);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Magnet Trap berhasil diupdate');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Magnet Trap gagal diupdate');
			}

			redirect('magnettrap');
		}

		$data = array(
			'magnettrap' => $magnettrap
		);

		$this->active_nav = 'magnettrap'; 
		$this->render('form/magnettrap/magnettrap-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('magnettrap');
		}

		$deleted = $this->magnettrap_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('magnettrap');
	}

	public function verifikasi()
	{
		$data = array(
			'magnettrap' => $this->magnettrap_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-magnettrap'; 
		$this->render('form/magnettrap/magnettrap-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->magnettrap_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$update = $this->magnettrap_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Magnet Trap berhasil di Update');
				redirect('magnettrap/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Magnet Trap gagal di Update');
				redirect('magnettrap/verifikasi');
			}
		}

		$data = array(
			'magnettrap' => $this->magnettrap_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'verifikasi-magnettrap'; 
		$this->render('form/magnettrap/magnettrap-status', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'magnettrap' => $this->magnettrap_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-magnettrap', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/magnettrap/magnettrap-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }

	// public function statuseng($uuid)
	// {
	// 	$rules = $this->magnettrap_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {
	// 		$update = $this->magnettrap_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Magnet Trap berhasil di Update');
	// 			redirect('magnettrap/diketahui');
	// 		} else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Magnet Trap gagal di Update');
	// 			redirect('magnettrap/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'magnettrap' => $this->magnettrap_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-magnettrap'
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/magnettrap/magnettrap-statuseng', $data);
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

		$magnettrap_data = $this->magnettrap_model->get_by_date($tanggal, $plant, $shift); 
		$magnettrap_data_verif = $this->magnettrap_model->get_last_verif_by_date($tanggal, $plant, $shift); 

		if (!$magnettrap_data || !$magnettrap_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['magnettrap'] = $magnettrap_data_verif;

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
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN MAGNET TRAP', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['magnettrap']->date;
		$date = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $date->getTimestamp());

		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(16);
		$pdf->Write(0, 'Hari / Tanggal : ' . $formatted_date);
		$pdf->SetX($pdf->GetX() + 20);
		$pdf->Write(0, 'Shift: ' . $data['magnettrap']->shift);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 11);

		$pdf->Cell(15, 10, 'Pukul', 1, 0, 'C');
		$pdf->Cell(35, 10, 'Tahapan', 1, 0, 'C');
		$pdf->Cell(50, 10, 'Jenis Kontaminasi', 1, 0, 'C');
		$pdf->Cell(45, 10, 'Bukti', 1, 0, 'C');
		$pdf->Cell(55, 10, 'Analisis Temuan', 1, 0, 'C');
		$pdf->Cell(50, 10, 'Tindakan Koreksi', 1, 0, 'C');
		$pdf->Cell(35, 10, 'Verifikasi', 1, 0, 'C');
		$pdf->Cell(35, 10, 'Keterangan', 1, 1, 'C');

		foreach ($magnettrap_data as $magnettrap) {
			$formattedTime = date('H:i', strtotime($magnettrap->time));
			$pdf->Cell(15, 20, $formattedTime, 1, 0, 'C');
			$pdf->Cell(35, 20, $magnettrap->tahapan, 1, 0, 'L');
			$pdf->Cell(50, 20, $magnettrap->kontaminasi, 1, 0, 'C');

			$colWidth = 45;
			$colHeight = 20;
			$maxWidthImage = 22;  
			$maxHeightImage = 12; 

			$image_path = FCPATH . 'uploads/' . $magnettrap->bukti;
			$pdf->Rect($pdf->GetX(), $pdf->GetY(), $colWidth, $colHeight);

			if (file_exists($image_path)) {
				list($width, $height) = getimagesize($image_path);
				$aspectRatio = $width / $height;
				if ($width > $maxWidthImage || $height > $maxHeightImage) {
					if ($width > $height) {
						$newWidth = $maxWidthImage;
						$newHeight = $newWidth / $aspectRatio;
					} else {
						$newHeight = $maxHeightImage;
						$newWidth = $newHeight * $aspectRatio; 
					}
				} else {
					$newWidth = $width;
					$newHeight = $height;
				}
				$xPos = $pdf->GetX() + ($colWidth - $newWidth) / 2; 
				$yPos = $pdf->GetY() + ($colHeight - $newHeight) / 2; 
				$pdf->Image($image_path, $xPos, $yPos, $newWidth, $newHeight);
				$pdf->SetX($pdf->GetX() + $colWidth);
			} else {
				$pdf->Cell($colWidth, $colHeight, 'Gambar Tidak Ada', 1, 0, 'C');
				$pdf->SetX($pdf->GetX() + $colWidth);
			}

			$pdf->Cell(55, 20, $magnettrap->analisis, 1, 0, 'C');
			$pdf->Cell(50, 20, $magnettrap->tindakan, 1, 0, 'C');
			$pdf->Cell(35, 20, $magnettrap->verifikasi, 1, 0, 'C');
			$pdf->Cell(35, 20, !empty($magnettrap->keterangan) ? $magnettrap->keterangan : '-', 1, 0, 'C');
			$pdf->Ln();
		}

		$pdf->SetY($pdf->GetY() + 3); 
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(10, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($magnettrap_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L'); 
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$this->load->model('pegawai_model');
		$data['magnettrap']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['magnettrap']->username);
		$data['magnettrap']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['magnettrap']->nama_spv);
		$data['magnettrap']->nama_lengkap_enginer = $data['magnettrap']->nama_enginer;

		$y_after_keterangan = $pdf->GetY();
		$status_verifikasi = true;
		foreach ($magnettrap_data as $item) {
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
			$pdf->Cell(95, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(25, $y_verifikasi + 10);
			$pdf->SetFont('times', 'U', 8); 
			$pdf->Cell(95, 5, $data['magnettrap']->nama_lengkap_qc, 0, 1, 'C');
			$pdf->SetFont('times', '', 8); 
			$pdf->Cell(112, 5, 'QC Inspector', 0, 0, 'C');

			// Diketahui oleh (Produksi) - tanpa barcode
			$pdf->SetXY(90, $y_verifikasi + 5);
			$pdf->Cell(135, 5, 'Diketahui Oleh,', 0, 0, 'C');

			if ($data['magnettrap']->status_enginer == 1 && !empty($data['magnettrap']->nama_enginer)) {
				$update_tanggal_enginer = (new DateTime($data['magnettrap']->tgl_update_enginer))->format('d-m-Y | H:i');

				$pdf->SetFont('times', 'U', 8);
				$pdf->SetXY(90, $y_verifikasi + 10);
				$pdf->Cell(135, 5, $data['magnettrap']->nama_enginer, 0, 1, 'C');

				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(90, $y_verifikasi + 15);
				$pdf->Cell(135, 5, 'Foreman/Forelady Engineering', 0, 1, 'C');

				// $pdf->SetXY(90, $y_verifikasi + 20);
				// $pdf->Cell(135, 5, $update_tanggal_enginer, 0, 0, 'C');
			} else {
				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(90, $y_verifikasi + 10);
				$pdf->Cell(135, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}


		// Disetujui oleh (SPV)
			$pdf->SetXY(150, $y_verifikasi + 5);
			$pdf->Cell(189, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$update_tanggal = (new DateTime($data['magnettrap']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['magnettrap']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 237, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(170, $y_verifikasi + 24);
			$pdf->Cell(149, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0); 
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(200, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}


		$pdf->setPrintFooter(false);
		$filename = "Pemeriksaan Magnet Trap_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');

	}

	public function export_excel()
	{
		$this->load->model('magnettrap_model');

		$bulan = $this->input->post('bulan');
		if (!$bulan) show_error('Bulan tidak dipilih', 404);

		[$tahun, $bulanAngka] = explode('-', $bulan);
		$plant = $this->session->userdata('plant');

		$data = $this->magnettrap_model->get_by_month($tahun, $bulanAngka, $plant);
		if (!$data) show_error('Data kosong', 404);

		$template = FCPATH.'assets/excel/Pemeriksaan Magnet.xlsx';
		if (!file_exists($template)) {
			show_error('Template Excel tidak ditemukan', 404);
		}

		$spreadsheet = IOFactory::load($template);
		$sheet = $spreadsheet->getActiveSheet();

    // ================= JUDUL =================
		$namaBulan = [
			'01'=>'JANUARI','02'=>'FEBRUARI','03'=>'MARET','04'=>'APRIL',
			'05'=>'MEI','06'=>'JUNI','07'=>'JULI','08'=>'AGUSTUS',
			'09'=>'SEPTEMBER','10'=>'OKTOBER','11'=>'NOVEMBER','12'=>'DESEMBER'
		];

		$sheet->setCellValue('A3', "{$namaBulan[$bulanAngka]} {$tahun}");
		$sheet->getStyle('A3')->getFont()->setBold(true);

    // ================= TAHAPAN UNIQUE =================
		$tahapanList = [];
		foreach ($data as $d) {
			$tahapanList[$d->tahapan] = true;
		}
		$tahapanList = array_keys($tahapanList);

		$rowStart = 6;
		foreach ($tahapanList as $i => $t) {
			$sheet->setCellValue('A'.($rowStart + $i), $t);
		}

    // ================= TANGGAL UNIQUE =================
		$tanggalList = [];
		foreach ($data as $d) {
			$tanggalList[$d->date] = true;
		}
		$tanggalList = array_keys($tanggalList);
		sort($tanggalList);

    // ================= HEADER TANGGAL =================
    $colIndex = 2; // kolom B

    foreach ($tanggalList as $tgl) {
    	$colStart = Coordinate::stringFromColumnIndex($colIndex);
    	$colEnd   = Coordinate::stringFromColumnIndex($colIndex + 2);

    	$sheet->mergeCells("{$colStart}4:{$colEnd}4");
    	$sheet->setCellValue("{$colStart}4", date('d', strtotime($tgl)));
    	$sheet->getStyle("{$colStart}4")->getAlignment()
    	->setHorizontal(Alignment::HORIZONTAL_CENTER);

    	$colIndex += 3;
    }

    // ================= ISI DATA =================
    foreach ($data as $d) {

    	$row = $rowStart + array_search($d->tahapan, $tahapanList);

    	$tglIndex = array_search($d->date, $tanggalList);
    	if ($tglIndex === false) continue;

        $shiftAngka = (int) substr($d->shift, 0, 1); // 1–3
        $col = 2 + ($tglIndex * 3) + ($shiftAngka - 1);
        $colLetter = Coordinate::stringFromColumnIndex($col);

        $val = preg_replace('/[^0-9,\.]/', '', $d->keterangan);
        $val = str_replace(',', '.', $val);
        $val = floatval($val);

        $sheet->setCellValue($colLetter.$row, $val);
    }

    // ================= POSISI FIX =================
    $totalRow  = 10;   // TOTAL KOLOM HARUS DI BARIS 10
    $colTotal  = 'BM'; // TOTAL PER BARIS
    $colPersen = 'BN'; // TOTAL PERSENTASE

    // ================= TOTAL KOLOM (BARIS 10) =================
    foreach ($tanggalList as $i => $tgl) {
    	for ($s = 0; $s < 3; $s++) {

    		$col = 2 + ($i * 3) + $s;
    		$colL = Coordinate::stringFromColumnIndex($col);

    		$sheet->setCellValue(
    			$colL.$totalRow,
    			"=SUM({$colL}{$rowStart}:{$colL}".($totalRow - 1).")"
    		);
    	}
    }

    // ================= TOTAL PER BARIS & PERSENTASE =================
    $lastDataCol = Coordinate::stringFromColumnIndex(1 + count($tanggalList) * 3);

    for ($i = 0; $i < count($tahapanList); $i++) {
    	$r = $rowStart + $i;

    // TOTAL PER BARIS → BM
    	$sheet->setCellValue(
    		"BM{$r}",
    		"=SUM(B{$r}:{$lastDataCol}{$r})"
    	);

    // PERSENTASE → BN (1.14 / 100 = 0.0114)
    	$sheet->setCellValue(
    		"BN{$r}",
    		"=BM{$r}/100"
    	);

    // FORMAT NUMBER BIASA (BUKAN %)
    	$sheet->getStyle("BN{$r}")
    	->getNumberFormat()
    	->setFormatCode('0.0000');
    }

    // ================= OUTPUT =================
    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=Magnet_Trap_{$bulan}.xlsx");
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit;
}

}

