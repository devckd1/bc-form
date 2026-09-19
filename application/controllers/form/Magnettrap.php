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

	public function verifikasi_produksi($uuid)
	{
		$magnettrap = $this->magnettrap_model->get_by_uuid($uuid);

		if (!$magnettrap) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Pemeriksaan Magnet Trap tidak ditemukan'
			);

			redirect('magnettrap');
			return;
		}

		$update = $this->magnettrap_model->verifikasi_produksi($uuid);

		if ($update) {
			$this->session->set_flashdata(
				'success_msg',
				'Pemeriksaan Magnet Trap berhasil diverifikasi'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Pemeriksaan Magnet Trap gagal diverifikasi'
			);
		}

		redirect('magnettrap');
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

		// =====================================================
		// AMBIL DATA
		// =====================================================
		$magnettrap_data = $this->magnettrap_model->get_by_date(
			$tanggal,
			$plant,
			$shift
		);

		$magnettrap_data_verif = $this->magnettrap_model->get_last_verif_by_date(
			$tanggal,
			$plant,
			$shift
		);

		if (!$magnettrap_data || !$magnettrap_data_verif) {
			show_error(
				'Data tidak ditemukan, Pilih tanggal yang ingin dicetak',
				404
			);
		}

		$data['magnettrap'] = $magnettrap_data_verif;

		// =====================================================
		// TCPDF
		// =====================================================
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

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

		// Margin dibuat sedikit lebih kecil karena sekarang
		// tabel mempunyai 10 kolom.
		$pdf->SetMargins(10, 12, 10);

		$pdf->SetAutoPageBreak(true, 15);

		// Landscape Legal
		$pdf->AddPage('L', 'LEGAL');

		// =====================================================
		// FONT JUDUL
		// =====================================================
		$pdf->SetFont('times', 'B', 13);

		// =====================================================
		// LOGO
		// =====================================================
		$logo_path = FCPATH . 'assets/img/logo.jpg';

		if (file_exists($logo_path)) {
			$pdf->Image(
				$logo_path,
				10,
				10,
				38
			);
		} else {
			$pdf->Write(
				7,
				"Logo tidak ditemukan\n"
			);
		}

		$pdf->Write(9, "\n");

		// =====================================================
		// JUDUL
		// =====================================================
		$pdf->MultiCell(
			0,
			5,
			'PEMERIKSAAN MAGNET TRAP',
			0,
			'C'
		);

		$pdf->Ln(4);

		// =====================================================
		// FORMAT TANGGAL
		// =====================================================
		setlocale(
			LC_TIME,
			'id_ID.UTF-8',
			'id_ID',
			'indonesian'
		);

		$tanggalHeader = $data['magnettrap']->date;

		$date = new DateTime($tanggalHeader);

		$formatted_date = date(
			'l, d F Y',
			$date->getTimestamp()
		);

		$formatted_date2 = date(
			'l, d F Y',
			$date->getTimestamp()
		);

		// =====================================================
		// INFORMASI PEMERIKSAAN
		// =====================================================
		$pdf->SetFont('times', '', 10);

		$pdf->SetX(10);

		$pdf->Write(
			0,
			'Hari / Tanggal : ' . $formatted_date
		);

		$pdf->SetX(
			$pdf->GetX() + 15
		);

		$pdf->Write(
			0,
			'Shift : ' . $data['magnettrap']->shift
		);

		$pdf->Ln(6);

		// =====================================================
		// HEADER TABEL
		// =====================================================

		$pdf->SetFont('times', 'B', 8);

		/*
     * Total lebar tabel:
     *
     * 13 + 25 + 35 + 28 + 38
     * + 42 + 48 + 43 + 30 + 30
     *
     * = 332 mm
     *
     * Legal Landscape = ±356 mm
     * dengan margin 10 mm kiri/kanan masih cukup.
     */

		$w = [
			'pukul'        => 13,
			'tahapan'      => 25,
			'produk'       => 35,
			'kode'         => 28,
			'kontaminasi'  => 38,
			'bukti'        => 42,
			'analisis'     => 48,
			'tindakan'     => 43,
			'verifikasi'   => 30,
			'keterangan'   => 30
		];

		$headerHeight = 12;

		$pdf->Cell(
			$w['pukul'],
			$headerHeight,
			'Pukul',
			1,
			0,
			'C'
		);

		$pdf->Cell(
			$w['tahapan'],
			$headerHeight,
			'Tahapan',
			1,
			0,
			'C'
		);

		$pdf->Cell(
			$w['produk'],
			$headerHeight,
			'Nama Produk',
			1,
			0,
			'C'
		);

		$pdf->Cell(
			$w['kode'],
			$headerHeight,
			'Kode Produksi',
			1,
			0,
			'C'
		);

		$pdf->Cell(
			$w['kontaminasi'],
			$headerHeight,
			'Jenis Kontaminasi',
			1,
			0,
			'C'
		);

		$pdf->Cell(
			$w['bukti'],
			$headerHeight,
			'Bukti',
			1,
			0,
			'C'
		);

		$pdf->Cell(
			$w['analisis'],
			$headerHeight,
			'Analisis Temuan',
			1,
			0,
			'C'
		);

		$pdf->Cell(
			$w['tindakan'],
			$headerHeight,
			'Tindakan Koreksi',
			1,
			0,
			'C'
		);

		$pdf->Cell(
			$w['verifikasi'],
			$headerHeight,
			'Verifikasi',
			1,
			0,
			'C'
		);

		$pdf->Cell(
			$w['keterangan'],
			$headerHeight,
			'Keterangan',
			1,
			1,
			'C'
		);

		// =====================================================
		// ISI TABEL
		// =====================================================

		$pdf->SetFont('times', '', 7.5);

		foreach ($magnettrap_data as $magnettrap) {

			$formattedTime = !empty($magnettrap->time)
				? date(
					'H:i',
					strtotime($magnettrap->time)
				)
				: '-';

			/*
         * Tinggi baris.
         * Dibuat 22 supaya teks dan gambar masih nyaman.
         */
			$rowHeight = 22;

			// -------------------------------------------------
			// Pukul
			// -------------------------------------------------
			$pdf->Cell(
				$w['pukul'],
				$rowHeight,
				$formattedTime,
				1,
				0,
				'C'
			);

			// -------------------------------------------------
			// Tahapan
			// -------------------------------------------------
			$pdf->MultiCell(
				25,
				22,
				$magnettrap->tahapan,
				1,
				'L',
				false,
				0,
				'',
				'',
				true,
				0,
				false,
				true,
				22,
				'M'
			);

			// -------------------------------------------------
			// Nama Produk
			// -------------------------------------------------
			$pdf->Cell(
				$w['produk'],
				$rowHeight,
				!empty($magnettrap->nama_produk)
					? $magnettrap->nama_produk
					: '-',
				1,
				0,
				'L'
			);

			// -------------------------------------------------
			// Kode Produksi
			// -------------------------------------------------
			$pdf->Cell(
				$w['kode'],
				$rowHeight,
				!empty($magnettrap->kode_produksi)
					? $magnettrap->kode_produksi
					: '-',
				1,
				0,
				'L'
			);

			// -------------------------------------------------
			// Jenis Kontaminasi
			// -------------------------------------------------
			$pdf->Cell(
				$w['kontaminasi'],
				$rowHeight,
				!empty($magnettrap->kontaminasi)
					? $magnettrap->kontaminasi
					: '-',
				1,
				0,
				'L'
			);

			// -------------------------------------------------
			// BUKTI / GAMBAR
			// -------------------------------------------------
			$colWidth = $w['bukti'];
			$colHeight = $rowHeight;

			$maxWidthImage = 28;
			$maxHeightImage = 16;

			$xBeforeImage = $pdf->GetX();
			$yBeforeImage = $pdf->GetY();

			// Border cell
			$pdf->Rect(
				$xBeforeImage,
				$yBeforeImage,
				$colWidth,
				$colHeight
			);

			$image_path = null;

			if (!empty($magnettrap->bukti)) {

				$path1 = FCPATH .
					'uploads/' .
					$magnettrap->bukti;

				$path2 = FCPATH .
					'uploads/magnettrap/' .
					$magnettrap->bukti;

				if (file_exists($path1)) {

					$image_path = $path1;
				} elseif (file_exists($path2)) {

					$image_path = $path2;
				}
			}

			if (
				$image_path &&
				file_exists($image_path)
			) {

				$imageInfo = @getimagesize(
					$image_path
				);

				if (
					$imageInfo &&
					!empty($imageInfo[0]) &&
					!empty($imageInfo[1])
				) {

					$width = $imageInfo[0];
					$height = $imageInfo[1];

					$aspectRatio =
						$width / $height;

					if (
						$width > $maxWidthImage ||
						$height > $maxHeightImage
					) {

						if ($width > $height) {

							$newWidth =
								$maxWidthImage;

							$newHeight =
								$newWidth /
								$aspectRatio;
						} else {

							$newHeight =
								$maxHeightImage;

							$newWidth =
								$newHeight *
								$aspectRatio;
						}
					} else {

						$newWidth = $width;
						$newHeight = $height;
					}

					// Posisi gambar di tengah cell
					$xPos =
						$xBeforeImage +
						(($colWidth - $newWidth) / 2);

					$yPos =
						$yBeforeImage +
						(($colHeight - $newHeight) / 2);

					$pdf->Image(
						$image_path,
						$xPos,
						$yPos,
						$newWidth,
						$newHeight
					);
				}
			} else {

				$pdf->SetXY(
					$xBeforeImage,
					$yBeforeImage
				);

				$pdf->Cell(
					$colWidth,
					$colHeight,
					'Gambar Tidak Ada',
					0,
					0,
					'C'
				);
			}

			// Geser posisi ke kolom berikutnya
			$pdf->SetXY(
				$xBeforeImage + $colWidth,
				$yBeforeImage
			);

			// -------------------------------------------------
			// Analisis Temuan
			// -------------------------------------------------
			$pdf->Cell(
				$w['analisis'],
				$rowHeight,
				!empty($magnettrap->analisis)
					? $magnettrap->analisis
					: '-',
				1,
				0,
				'L'
			);

			// -------------------------------------------------
			// Tindakan Koreksi
			// -------------------------------------------------
			$pdf->Cell(
				$w['tindakan'],
				$rowHeight,
				!empty($magnettrap->tindakan)
					? $magnettrap->tindakan
					: '-',
				1,
				0,
				'L'
			);

			// -------------------------------------------------
			// Verifikasi
			// -------------------------------------------------
			$pdf->Cell(
				$w['verifikasi'],
				$rowHeight,
				!empty($magnettrap->verifikasi)
					? $magnettrap->verifikasi
					: '-',
				1,
				0,
				'L'
			);

			// -------------------------------------------------
			// Keterangan
			// -------------------------------------------------
			$pdf->Cell(
				$w['keterangan'],
				$rowHeight,
				!empty($magnettrap->keterangan)
					? $magnettrap->keterangan
					: '-',
				1,
				1,
				'L'
			);
		}

		// =====================================================
		// CATATAN
		// =====================================================

		$pdf->SetY(
			$pdf->GetY() + 3
		);

		$pdf->SetFont(
			'times',
			'',
			8
		);

		$pdf->Cell(
			10,
			3,
			'Catatan : ',
			0,
			1,
			'L'
		);

		foreach ($magnettrap_data as $item) {

			if (!empty($item->catatan)) {

				$pdf->Cell(
					10,
					0,
					'',
					0,
					0,
					'L'
				);

				$pdf->MultiCell(
					300,
					0,
					' - ' . $item->catatan,
					0,
					'L',
					false,
					1
				);
			}
		}

		// =====================================================
		// NAMA LENGKAP QC / SPV / ENGINEER
		// =====================================================

		$this->load->model(
			'pegawai_model'
		);

		$data['magnettrap']->nama_lengkap_qc =
			$this->pegawai_model->get_nama_lengkap(
				$data['magnettrap']->username
			);

		$data['magnettrap']->nama_lengkap_spv =
			$this->pegawai_model->get_nama_lengkap(
				$data['magnettrap']->nama_spv
			);

		$data['magnettrap']->nama_lengkap_enginer =
			$data['magnettrap']->nama_enginer;

		// =====================================================
		// CEK STATUS VERIFIKASI
		// =====================================================

		$y_after_keterangan =
			$pdf->GetY();

		$status_verifikasi = true;

		foreach ($magnettrap_data as $item) {

			if ($item->status_spv != '1') {

				$status_verifikasi = false;

				break;
			}
		}

		// =====================================================
		// BLOK VERIFIKASI
		// =====================================================

		$pdf->SetFont(
			'times',
			'',
			8
		);

		$pdf->SetTextColor(
			0,
			0,
			0
		);

		if ($status_verifikasi) {

			$y_verifikasi = $y_after_keterangan + 3;

			// =================================================
			// DATA VERIFIKASI
			// =================================================

			$nama_qc = !empty($data['magnettrap']->nama_lengkap_qc)
				? $data['magnettrap']->nama_lengkap_qc
				: $data['magnettrap']->username;

			$nama_engineer = !empty($data['magnettrap']->nama_enginer)
				? $data['magnettrap']->nama_enginer
				: '-';

			$nama_spv = !empty($data['magnettrap']->nama_lengkap_spv)
				? $data['magnettrap']->nama_lengkap_spv
				: '-';

			// =================================================
			// TANGGAL VERIFIKASI
			// =================================================

			$tanggal_qc = !empty($data['magnettrap']->created_at)
				? (new DateTime(
					$data['magnettrap']->created_at
				))->format('d-m-Y | H:i')
				: (new DateTime(
					$data['magnettrap']->date
				))->format('d-m-Y');

			$tanggal_engineer = !empty($data['magnettrap']->tgl_update_enginer)
				? (new DateTime(
					$data['magnettrap']->tgl_update_enginer
				))->format('d-m-Y | H:i')
				: '-';

			$tanggal_spv = !empty($data['magnettrap']->tgl_update_spv)
				? (new DateTime(
					$data['magnettrap']->tgl_update_spv
				))->format('d-m-Y | H:i')
				: '-';


			// =================================================
			// POSISI 3 KOLOM
			// =================================================

			$col1_x = 15;
			$col2_x = 120;
			$col3_x = 225;

			$col_width = 100;

			// =================================================
			// JUDUL
			// =================================================

			$pdf->SetFont(
				'times',
				'',
				8
			);

			// QC
			$pdf->SetXY(
				$col1_x,
				$y_verifikasi
			);

			$pdf->Cell(
				$col_width,
				5,
				'Dibuat Oleh,',
				0,
				0,
				'C'
			);

			// ENGINEERING
			$pdf->SetXY(
				$col2_x,
				$y_verifikasi
			);

			$pdf->Cell(
				$col_width,
				5,
				'Diketahui Oleh,',
				0,
				0,
				'C'
			);

			// SPV
			$pdf->SetXY(
				$col3_x,
				$y_verifikasi
			);

			$pdf->Cell(
				$col_width,
				5,
				'Disetujui Oleh,',
				0,
				0,
				'C'
			);


			// =================================================
			// QR CODE QC INSPECTOR
			// =================================================

			if (!empty($nama_qc)) {

				$qr_qc =
					"Diverifikasi secara digital oleh,\n" .
					$nama_qc .
					"\nQC Inspector\n" .
					$tanggal_qc;

				$pdf->write2DBarcode(
					$qr_qc,
					'QRCODE,L',
					$col1_x + 42,
					$y_verifikasi + 7,
					18,
					18,
					null,
					'N'
				);
			}


			// =================================================
			// QR CODE FOREMAN / FORELADY ENGINEERING
			// =================================================

			if (
				$data['magnettrap']->status_enginer == 1 &&
				!empty($nama_engineer)
			) {

				$qr_engineer =
					"Diverifikasi secara digital oleh,\n" .
					$nama_engineer .
					"\nForeman/Forelady Engineering\n" .
					$tanggal_engineer;

				$pdf->write2DBarcode(
					$qr_engineer,
					'QRCODE,L',
					$col2_x + 42,
					$y_verifikasi + 7,
					18,
					18,
					null,
					'N'
				);
			}


			// =================================================
			// QR CODE SPV
			// =================================================

			if (
				!empty($nama_spv) &&
				$data['magnettrap']->status_spv == 1
			) {

				$qr_spv =
					"Diverifikasi secara digital oleh,\n" .
					$nama_spv .
					"\nSPV QC Bread Crumb\n" .
					$tanggal_spv;

				$pdf->write2DBarcode(
					$qr_spv,
					'QRCODE,L',
					$col3_x + 42,
					$y_verifikasi + 7,
					18,
					18,
					null,
					'N'
				);
			}


			// =================================================
			// NAMA QC
			// =================================================

			$pdf->SetFont(
				'times',
				'U',
				8
			);

			$pdf->SetXY(
				$col1_x,
				$y_verifikasi + 26
			);

			$pdf->Cell(
				$col_width,
				5,
				$nama_qc,
				0,
				0,
				'C'
			);


			// =================================================
			// NAMA ENGINEERING
			// =================================================

			$pdf->SetXY(
				$col2_x,
				$y_verifikasi + 26
			);

			if (
				$data['magnettrap']->status_enginer == 1 &&
				!empty($nama_engineer)
			) {

				$pdf->Cell(
					$col_width,
					5,
					$nama_engineer,
					0,
					0,
					'C'
				);
			} else {

				$pdf->SetFont(
					'times',
					'',
					8
				);

				$pdf->Cell(
					$col_width,
					5,
					'Belum Diverifikasi',
					0,
					0,
					'C'
				);

				$pdf->SetFont(
					'times',
					'U',
					8
				);
			}


			// =================================================
			// NAMA SPV
			// =================================================

			$pdf->SetXY(
				$col3_x,
				$y_verifikasi + 26
			);

			$pdf->Cell(
				$col_width,
				5,
				$nama_spv,
				0,
				0,
				'C'
			);


			// =================================================
			// JABATAN
			// =================================================

			$pdf->SetFont(
				'times',
				'',
				8
			);

			// QC
			$pdf->SetXY(
				$col1_x,
				$y_verifikasi + 31
			);

			$pdf->Cell(
				$col_width,
				5,
				'QC Inspector',
				0,
				0,
				'C'
			);

			// Engineering
			$pdf->SetXY(
				$col2_x,
				$y_verifikasi + 31
			);

			$pdf->Cell(
				$col_width,
				5,
				'Foreman/Forelady Engineering',
				0,
				0,
				'C'
			);

			// SPV
			$pdf->SetXY(
				$col3_x,
				$y_verifikasi + 31
			);

			$pdf->Cell(
				$col_width,
				5,
				'Supervisor QC',
				0,
				0,
				'C'
			);
		} else {

			// =================================================
			// BELUM DIVERIFIKASI
			// =================================================

			$pdf->SetTextColor(
				255,
				0,
				0
			);

			$pdf->SetFont(
				'times',
				'',
				8
			);

			$pdf->SetXY(
				200,
				$y_after_keterangan
			);

			$pdf->Cell(
				80,
				5,
				'Data Belum Diverifikasi',
				0,
				0,
				'C'
			);
		}

		// =====================================================
		// OUTPUT PDF
		// =====================================================

		$filename =
			"Pemeriksaan Magnet Trap_{$formatted_date2}.pdf";

		$pdf->Output(
			$filename,
			'I'
		);
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
		$sheet->getStyle('A3')->getFont()->setBold(true);

		// ================= TAHAPAN UNIQUE =================
		$tahapanList = [];
		foreach ($data as $d) {
			$tahapanList[$d->tahapan] = true;
		}
		$tahapanList = array_keys($tahapanList);

		$rowStart = 6;
		foreach ($tahapanList as $i => $t) {
			$sheet->setCellValue('A' . ($rowStart + $i), $t);
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

			$sheet->setCellValue($colLetter . $row, $val);
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
					$colL . $totalRow,
					"=SUM({$colL}{$rowStart}:{$colL}" . ($totalRow - 1) . ")"
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
