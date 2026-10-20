<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Magnettrap extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('magnettrap_model');
		$this->load->library('upload');
		if (!$this->auth_model->current_user()) {
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

	public function audit()
	{
		$data = [
			'magnettrap' => $this->magnettrap_model->get_data_audit_by_plant()
		];

		$this->active_nav = 'magnettrap';
		$this->render('form/magnettrap/magnettrap-audit', $data);
	}

	public function detail_audit($uuid)
	{
		$magnettrap = $this->magnettrap_model->get_audit_by_uuid($uuid);

		if (!$magnettrap) {
			show_404();
			return;
		}

		$data = [
			'magnettrap' => $magnettrap,
			'mode' => 'audit'
		];

		$this->active_nav = 'magnettrap';
		$this->render('form/magnettrap/magnettrap-detail', $data);
	}

	public function edit_audit($uuid)
	{
		$magnettrap = $this->magnettrap_model->get_audit_by_uuid($uuid);

		if (!$magnettrap) {
			show_404();
			return;
		}

		$rules = $this->magnettrap_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->input->method() === 'post') {
			if ($this->form_validation->run() === true) {
				$file_name = $magnettrap->bukti;

				if (
					!empty($_FILES['bukti']['name']) &&
					!empty($_FILES['bukti']['tmp_name'])
				) {
					$config['upload_path'] = './uploads/magnettrap/';
					$config['allowed_types'] = 'jpg|jpeg|png|pdf';
					$config['max_size'] = 2048;
					$config['encrypt_name'] = true;

					if (!is_dir($config['upload_path'])) {
						mkdir($config['upload_path'], 0755, true);
					}

					$this->upload->initialize($config);

					if ($this->upload->do_upload('bukti')) {
						$upload_data = $this->upload->data();
						$file_name = $upload_data['file_name'];

						if ($upload_data['is_image']) {
							$this->load->library('image_lib');

							$image_config = [
								'image_library' => 'gd2',
								'source_image' => $upload_data['full_path'],
								'maintain_ratio' => true,
								'width' => 800,
								'height' => 800,
								'quality' => '70%'
							];

							$this->image_lib->initialize($image_config);
							$this->image_lib->resize();
							$this->image_lib->clear();
						}
					} else {
						$this->session->set_flashdata(
							'error_msg',
							strip_tags($this->upload->display_errors())
						);

						redirect('magnettrap/edit-audit/' . rawurlencode($uuid));
						return;
					}
				}

				$berhasil = $this->magnettrap_model->update_audit(
					$uuid,
					$file_name
				);

				if ($berhasil) {
					$this->session->set_flashdata(
						'success_msg',
						'Data Audit Magnet Trap berhasil diperbarui.'
					);
				} else {
					$this->session->set_flashdata(
						'error_msg',
						'Data Audit Magnet Trap gagal diperbarui.'
					);
				}

				redirect('magnettrap/audit');
				return;
			}
		}

		$data = [
			'magnettrap' => $magnettrap,
			'mode' => 'audit'
		];

		$this->active_nav = 'magnettrap';
		$this->render('form/magnettrap/magnettrap-edit', $data);
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
		$formatted_date = strftime('%A, %d %B %Y', $date->getTimestamp());

		$formatted_date2 = strftime('%d %B %Y', $date->getTimestamp());

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

			// Simpan posisi awal baris
			$startX = $pdf->GetX();
			$startY = $pdf->GetY();

			$w1 = 15;
			$w2 = 35;
			$w3 = 50;
			$w4 = 45;
			$w5 = 55;
			$w6 = 50;
			$w7 = 35;
			$w8 = 35;

			// Hitung tinggi yang dibutuhkan teks
			$hAnalisis   = $pdf->getStringHeight($w5, $magnettrap->analisis);
			$hTindakan   = $pdf->getStringHeight($w6, $magnettrap->tindakan);
			$hVerifikasi = $pdf->getStringHeight($w7, $magnettrap->verifikasi);
			$hKet        = $pdf->getStringHeight($w8, !empty($magnettrap->keterangan) ? $magnettrap->keterangan : '-');

			// Tinggi minimum row
			$rowHeight = max(20, $hAnalisis, $hTindakan, $hVerifikasi, $hKet);

			// Kolom Pukul
			$pdf->MultiCell($w1, $rowHeight, $formattedTime, 1, 'C', false, 0);

			// Kolom Tahapan
			$pdf->MultiCell($w2, $rowHeight, $magnettrap->tahapan, 1, 'L', false, 0);

			// Kolom Jenis Kontaminasi
			$pdf->MultiCell($w3, $rowHeight, $magnettrap->kontaminasi, 1, 'C', false, 0);

			// Simpan posisi kolom bukti
			$xBukti = $pdf->GetX();
			$yBukti = $pdf->GetY();

			// Border kolom bukti
			$pdf->Rect($xBukti, $yBukti, $w4, $rowHeight);

			$image_path = FCPATH . 'uploads/magnettrap/' . $magnettrap->bukti;

			if (!empty($magnettrap->bukti) && file_exists($image_path)) {

				$maxWidthImage  = 22;
				$maxHeightImage = min(12, $rowHeight - 2);

				list($width, $height) = getimagesize($image_path);

				$aspectRatio = $width / $height;

				if ($width > $height) {
					$newWidth = $maxWidthImage;
					$newHeight = $newWidth / $aspectRatio;
				} else {
					$newHeight = $maxHeightImage;
					$newWidth = $newHeight * $aspectRatio;
				}

				$xPos = $xBukti + (($w4 - $newWidth) / 2);
				$yPos = $yBukti + (($rowHeight - $newHeight) / 2);

				$pdf->Image($image_path, $xPos, $yPos, $newWidth, $newHeight);
			} else {

				$pdf->SetXY($xBukti, $yBukti);
				$pdf->MultiCell($w4, $rowHeight, 'Gambar Tidak Ada', 0, 'C', false, 0);
			}

			$pdf->SetXY($xBukti + $w4, $yBukti);
			$pdf->MultiCell($w5, $rowHeight, $magnettrap->analisis, 1, 'C', false, 0);
			$pdf->MultiCell($w6, $rowHeight, $magnettrap->tindakan, 1, 'C', false, 0);
			$pdf->MultiCell($w7, $rowHeight, $magnettrap->verifikasi, 1, 'C', false, 0);
			$pdf->MultiCell($w8, $rowHeight, !empty($magnettrap->keterangan) ? $magnettrap->keterangan : '-', 1, 'C', false, 1);
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

	public function cetak_audit()
	{
		$tanggal = $this->input->post('tanggal');
		$shift   = $this->input->post('shift');

		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$magnettrap_data = $this->magnettrap_model->get_audit_by_date($tanggal, $plant, $shift);
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

			// Simpan posisi awal baris
			$startX = $pdf->GetX();
			$startY = $pdf->GetY();

			$w1 = 15;
			$w2 = 35;
			$w3 = 50;
			$w4 = 45;
			$w5 = 55;
			$w6 = 50;
			$w7 = 35;
			$w8 = 35;

			// Hitung tinggi yang dibutuhkan teks
			$hAnalisis   = $pdf->getStringHeight($w5, $magnettrap->analisis);
			$hTindakan   = $pdf->getStringHeight($w6, $magnettrap->tindakan);
			$hVerifikasi = $pdf->getStringHeight($w7, $magnettrap->verifikasi);
			$hKet        = $pdf->getStringHeight($w8, !empty($magnettrap->keterangan) ? $magnettrap->keterangan : '-');

			// Tinggi minimum row
			$rowHeight = max(20, $hAnalisis, $hTindakan, $hVerifikasi, $hKet);

			// Kolom Pukul
			$pdf->MultiCell($w1, $rowHeight, $formattedTime, 1, 'C', false, 0);

			// Kolom Tahapan
			$pdf->MultiCell($w2, $rowHeight, $magnettrap->tahapan, 1, 'L', false, 0);

			// Kolom Jenis Kontaminasi
			$pdf->MultiCell($w3, $rowHeight, $magnettrap->kontaminasi, 1, 'C', false, 0);

			// Simpan posisi kolom bukti
			$xBukti = $pdf->GetX();
			$yBukti = $pdf->GetY();

			// Border kolom bukti
			$pdf->Rect($xBukti, $yBukti, $w4, $rowHeight);

			$image_path = FCPATH . 'uploads/magnettrap/' . $magnettrap->bukti;

			if (!empty($magnettrap->bukti) && file_exists($image_path)) {

				$maxWidthImage  = 22;
				$maxHeightImage = min(12, $rowHeight - 2);

				list($width, $height) = getimagesize($image_path);

				$aspectRatio = $width / $height;

				if ($width > $height) {
					$newWidth = $maxWidthImage;
					$newHeight = $newWidth / $aspectRatio;
				} else {
					$newHeight = $maxHeightImage;
					$newWidth = $newHeight * $aspectRatio;
				}

				$xPos = $xBukti + (($w4 - $newWidth) / 2);
				$yPos = $yBukti + (($rowHeight - $newHeight) / 2);

				$pdf->Image($image_path, $xPos, $yPos, $newWidth, $newHeight);
			} else {

				$pdf->SetXY($xBukti, $yBukti);
				$pdf->MultiCell($w4, $rowHeight, 'Gambar Tidak Ada', 0, 'C', false, 0);
			}

			$pdf->SetXY($xBukti + $w4, $yBukti);
			$pdf->MultiCell($w5, $rowHeight, $magnettrap->analisis, 1, 'C', false, 0);
			$pdf->MultiCell($w6, $rowHeight, $magnettrap->tindakan, 1, 'C', false, 0);
			$pdf->MultiCell($w7, $rowHeight, $magnettrap->verifikasi, 1, 'C', false, 0);
			$pdf->MultiCell($w8, $rowHeight, !empty($magnettrap->keterangan) ? $magnettrap->keterangan : '-', 1, 'C', false, 1);
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

		$template = FCPATH . 'assets/excel/Pemeriksaan Magnet.xlsx';
		if (!file_exists($template)) {
			show_error('Template Excel tidak ditemukan', 404);
		}

		$spreadsheet = IOFactory::load($template);
		$sheet = $spreadsheet->getActiveSheet();

		// ================= JUDUL =================
		$namaBulan = [
			'01' => 'JANUARI',
			'02' => 'FEBRUARI',
			'03' => 'MARET',
			'04' => 'APRIL',
			'05' => 'MEI',
			'06' => 'JUNI',
			'07' => 'JULI',
			'08' => 'AGUSTUS',
			'09' => 'SEPTEMBER',
			'10' => 'OKTOBER',
			'11' => 'NOVEMBER',
			'12' => 'DESEMBER'
		];

		$sheet->setCellValue('A3', "{$namaBulan[$bulanAngka]} {$tahun}");

		// ================= TAHAPAN FIX =================
		$tahapanList = [
			'Sparator magnet transfer conveyor',
			'Sparator magnet cooling conveyor',
			'Kontaminasi logam dari hopper 1',
			'Kontaminasi logam dari hopper 2',
		];

		$rowStart = 6;

		foreach ($tahapanList as $i => $t) {
			$sheet->setCellValue('A' . ($rowStart + $i), $t);
		}

		// ================= MAPPING =================
		$tahapanMap = [
			'sparator magnet transfer conveyor' => 0,
			'sparator magnet cooling conveyor' => 1,
			'kontaminasi logam dari hopper 1' => 2,
			'kontaminasi logam dari hopper 2' => 3,
		];

		// ================= TANGGAL =================
		$tanggalList = [];
		foreach ($data as $d) {
			$tanggalList[$d->date] = true;
		}
		$tanggalList = array_keys($tanggalList);
		sort($tanggalList);

		// ================= HEADER TANGGAL =================
		$colIndex = 2;

		foreach ($tanggalList as $tgl) {
			$colStart = Coordinate::stringFromColumnIndex($colIndex);
			$colEnd   = Coordinate::stringFromColumnIndex($colIndex + 2);

			$sheet->mergeCells("{$colStart}4:{$colEnd}4");
			$sheet->setCellValue("{$colStart}4", date('d', strtotime($tgl)));

			$colIndex += 3;
		}

		// ================= HEADER SHIFT =================
		$colIndex = 2;

		foreach ($tanggalList as $tgl) {

			$colA = Coordinate::stringFromColumnIndex($colIndex);
			$colB = Coordinate::stringFromColumnIndex($colIndex + 1);
			$colC = Coordinate::stringFromColumnIndex($colIndex + 2);

			$sheet->setCellValue($colA . '5', 'A');
			$sheet->setCellValue($colB . '5', 'B');
			$sheet->setCellValue($colC . '5', 'C');

			$colIndex += 3;
		}

		// ================= ISI DATA =================
		foreach ($data as $d) {

			$key = strtolower(trim($d->tahapan));
			$key = preg_replace('/\s+/', ' ', $key);

			// fallback data lama
			if ($key === 'kontaminasi logam dari hopper') {
				$key = 'kontaminasi logam dari hopper 1';
			}

			if (!isset($tahapanMap[$key])) continue;

			$row = $rowStart + $tahapanMap[$key];

			$tglIndex = array_search($d->date, $tanggalList);
			if ($tglIndex === false) continue;

			$shiftAngka = (int) substr($d->shift, 0, 1);
			$col = 2 + ($tglIndex * 3) + ($shiftAngka - 1);

			$colLetter = Coordinate::stringFromColumnIndex($col);

			$val = preg_replace('/[^0-9,\.]/', '', $d->keterangan);
			$val = str_replace(',', '.', $val);

			$sheet->setCellValue($colLetter . $row, (float)$val);
		}

		// ================= TOTAL HARIAN (MERGE A+B+C) =================
		$lastRow = $rowStart + count($tahapanList);

		$sheet->setCellValue("A{$lastRow}", "Total Harian");

		foreach ($tanggalList as $i => $tgl) {

			$colStartIndex = 2 + ($i * 3);

			$colA = Coordinate::stringFromColumnIndex($colStartIndex);
			$colB = Coordinate::stringFromColumnIndex($colStartIndex + 1);
			$colC = Coordinate::stringFromColumnIndex($colStartIndex + 2);

			$sheet->mergeCells("{$colA}{$lastRow}:{$colC}{$lastRow}");

			$sheet->setCellValue(
				$colA . $lastRow,
				"=SUM({$colA}{$rowStart}:{$colA}" . ($lastRow - 1) . ")"
					. "+SUM({$colB}{$rowStart}:{$colB}" . ($lastRow - 1) . ")"
					. "+SUM({$colC}{$rowStart}:{$colC}" . ($lastRow - 1) . ")"
			);

			$sheet->getStyle("{$colA}{$lastRow}")
				->getAlignment()
				->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		}

		// ================= TOTAL PER BARIS =================
		$lastDataCol = Coordinate::stringFromColumnIndex(1 + count($tanggalList) * 3);

		$colTotal  = 'BM';
		$colPersen = 'BN';

		for ($i = 0; $i < count($tahapanList); $i++) {

			$r = $rowStart + $i;

			$sheet->setCellValue(
				"{$colTotal}{$r}",
				"=SUM(B{$r}:{$lastDataCol}{$r})"
			);

			$sheet->setCellValue(
				"{$colPersen}{$r}",
				"={$colTotal}{$r}/100"
			);

			$sheet->getStyle("{$colPersen}{$r}")
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
