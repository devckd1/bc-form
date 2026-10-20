<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Seasoning extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('seasoning_model');
		$this->load->library('upload');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'seasoning' => $this->seasoning_model->get_data_by_plant()
		);

		$this->active_nav = 'seasoning';
		$this->render('form/seasoning/seasoning', $data);
	}

	public function detail($uuid)
	{
		$seasoning = $this->seasoning_model->get_by_uuid($uuid);

		if (!$seasoning || (int) $seasoning->is_audit !== 0) {
			redirect('seasoning');
			return;
		}

		$data = [
			'seasoning' => $seasoning,
			'mode'      => 'qc'
		];

		$this->active_nav = 'seasoning';
		$this->render('form/seasoning/seasoning-detail', $data);
	}

	public function file_check($str)
	{
		if (isset($_FILES['bukti_coa']) && $_FILES['bukti_coa']['error'] != 4) {
			$allowed_mime_types = array('image/jpeg', 'image/png', 'application/pdf');
			$mime_type = $_FILES['bukti_coa']['type'];

			if (!in_array($mime_type, $allowed_mime_types)) {
				$this->form_validation->set_message('file_check', 'File harus berformat JPEG, PNG, atau PDF');
				return false;
			}
		}
		return true;
	}

	public function tambah()
	{
		$rules = $this->seasoning_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$file_name = null;
			if (isset($_FILES['bukti_coa']) && $_FILES['bukti_coa']['error'] != 4) {
				$config = array(
					'upload_path'   => "./uploads/seasoning/",
					'allowed_types' => "jpg|png|jpeg|pdf",
					'overwrite'     => TRUE,
					'max_size'      => 2048,
					'encrypt_name'  => TRUE
				);
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('bukti_coa')) {
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error_msg', 'Upload gagal: ' . $error);
					redirect('seasoning/tambah');
				} else {
					$data = $this->upload->data();
					$file_name = $data['file_name'];

					// === Kompres gambar jika bukan PDF ===
					if ($data['is_image']) {
						$this->_compress_image($data['full_path'], $data['file_type']);
					}
				}
			}

			$update = $this->seasoning_model->insert($file_name);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Seasoning dari Pemasok berhasil disimpan');
				redirect('seasoning');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Seasoning dari Pemasok gagal disimpan');
				redirect('seasoning');
			}
		}

		$data = array(
			'seasoning' => $this->seasoning_model->get_data_by_plant()
		);

		$this->active_nav = 'seasoning';
		$this->render('form/seasoning/seasoning-tambah', $data);
	}

	public function edit($uuid)
	{
		$seasoning = $this->seasoning_model->get_by_uuid($uuid);

		if (!$seasoning || (int) $seasoning->is_audit !== 0) {
			redirect('seasoning');
			return;
		}

		$rules = $this->seasoning_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$config = [
				'upload_path'   => './uploads/seasoning/',
				'allowed_types' => 'jpg|png|jpeg|pdf',
				'overwrite'     => FALSE,
				'max_size'      => 2048,
				'encrypt_name'  => TRUE
			];

			$this->upload->initialize($config);

			$file_name = $seasoning->bukti_coa;

			if (!empty($_FILES['bukti_coa']['name'])) {
				if (!$this->upload->do_upload('bukti_coa')) {
					$this->session->set_flashdata(
						'error_msg',
						'Upload gagal: ' . $this->upload->display_errors('', '')
					);

					redirect('seasoning/edit/' . $uuid);
					return;
				}

				$upload = $this->upload->data();
				$file_name = $upload['file_name'];

				if (!empty($upload['is_image'])) {
					$this->_compress_image(
						$upload['full_path'],
						$upload['file_type']
					);
				}
			}

			$update = $this->seasoning_model->update($uuid, $file_name);

			if ($update) {
				// Hapus file lama hanya setelah update QC berhasil.
				// Jangan hapus jika nama file lama dan baru sama.
				if (
					!empty($_FILES['bukti_coa']['name']) &&
					!empty($seasoning->bukti_coa) &&
					$seasoning->bukti_coa !== $file_name &&
					file_exists('./uploads/seasoning/' . $seasoning->bukti_coa)
				) {
					@unlink('./uploads/seasoning/' . $seasoning->bukti_coa);
				}

				$this->session->set_flashdata(
					'success_msg',
					'Data Pemeriksaan Seasoning dari Pemasok berhasil diupdate'
				);
			} else {
				// Bersihkan file baru jika penyimpanan gagal.
				if (
					!empty($_FILES['bukti_coa']['name']) &&
					$file_name !== $seasoning->bukti_coa &&
					file_exists('./uploads/seasoning/' . $file_name)
				) {
					@unlink('./uploads/seasoning/' . $file_name);
				}

				$this->session->set_flashdata(
					'error_msg',
					'Data Pemeriksaan Seasoning dari Pemasok gagal diupdate'
				);
			}

			redirect('seasoning');
			return;
		}

		$data = [
			'seasoning' => $seasoning,
			'mode'      => 'qc'
		];

		$this->active_nav = 'seasoning';
		$this->render('form/seasoning/seasoning-edit', $data);
	}

	private function _compress_image($path, $mime)
	{
		if (!file_exists($path)) return false;

		switch ($mime) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg($path);
				break;
			case 'image/png':
				$image = imagecreatefrompng($path);
				break;
			case 'image/webp':
				$image = imagecreatefromwebp($path);
				break;
			default:
				return false;
		}

		$width  = imagesx($image);
		$height = imagesy($image);

		// Resize max lebar 1024px
		$new_width  = min($width, 800);
		$new_height = ($height / $width) * $new_width;

		$tmp = imagecreatetruecolor($new_width, $new_height);
		imagecopyresampled($tmp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		imagejpeg($tmp, $path, 70);

		imagedestroy($image);
		imagedestroy($tmp);
		return true;
	}


	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('seasoning');
		}

		$deleted = $this->seasoning_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('seasoning');
	}

	public function verifikasi()
	{
		$data = array(
			'seasoning' => $this->seasoning_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-seasoning';
		$this->render('form/seasoning/seasoning-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->seasoning_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->seasoning_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Seasoning dari Pemasok berhasil di Update');
				redirect('seasoning/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Seasoning dari Pemasok gagal di Update');
				redirect('seasoning/verifikasi');
			}
		}

		$data = array(
			'seasoning' => $this->seasoning_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-seasoning';
		$this->render('form/seasoning/seasoning-status', $data);
	}

	public function audit()
	{
		$data = array(
			'seasoning' => $this->seasoning_model->get_audit_by_plant()
		);

		$this->active_nav = 'seasoning';

		$this->render(
			'form/seasoning/seasoning-audit',
			$data
		);
	}

	public function detail_audit($uuid)
	{
		$seasoning = $this->seasoning_model->get_audit_by_uuid($uuid);

		if (!$seasoning) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Seasoning tidak ditemukan.'
			);
			redirect('seasoning/audit');
			return;
		}

		$data = [
			'seasoning' => $seasoning,
			'mode'      => 'audit'
		];

		$this->active_nav = 'seasoning';
		$this->render('form/seasoning/seasoning-detail', $data);
	}

	public function edit_audit($uuid)
	{
		$seasoning = $this->seasoning_model->get_audit_by_uuid($uuid);

		if (!$seasoning) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Seasoning tidak ditemukan.'
			);
			redirect('seasoning/audit');
			return;
		}

		$rules = $this->seasoning_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$config = [
				'upload_path'   => './uploads/seasoning/',
				'allowed_types' => 'jpg|png|jpeg|pdf',
				'overwrite'     => FALSE,
				'max_size'      => 2048,
				'encrypt_name'  => TRUE
			];

			$this->upload->initialize($config);

			// Pertahankan file yang tercatat pada snapshot Audit.
			$file_name = $seasoning->bukti_coa;

			if (!empty($_FILES['bukti_coa']['name'])) {
				if (!$this->upload->do_upload('bukti_coa')) {
					$this->session->set_flashdata(
						'error_msg',
						'Upload gagal: ' . $this->upload->display_errors('', '')
					);

					redirect('seasoning/edit-audit/' . $uuid);
					return;
				}

				$upload = $this->upload->data();
				$file_name = $upload['file_name'];

				if (!empty($upload['is_image'])) {
					$this->_compress_image(
						$upload['full_path'],
						$upload['file_type']
					);
				}
			}

			$update = $this->seasoning_model->update_audit(
				$uuid,
				$file_name
			);

			if ($update) {
				// Jangan hapus file lama otomatis.
				// File dapat masih digunakan oleh data QC atau snapshot lain.
				$this->session->set_flashdata(
					'success_msg',
					'Data Audit Pemeriksaan Seasoning berhasil diupdate'
				);
			} else {
				// Jika update gagal, hapus hanya file baru yang diunggah.
				if (
					!empty($_FILES['bukti_coa']['name']) &&
					$file_name !== $seasoning->bukti_coa &&
					file_exists('./uploads/seasoning/' . $file_name)
				) {
					@unlink('./uploads/seasoning/' . $file_name);
				}

				$this->session->set_flashdata(
					'error_msg',
					'Data Audit Pemeriksaan Seasoning gagal diupdate'
				);
			}

			redirect('seasoning/audit');
			return;
		}

		$data = [
			'seasoning' => $seasoning,
			'mode'      => 'audit'
		];

		$this->active_nav = 'seasoning';
		$this->render('form/seasoning/seasoning-edit', $data);
	}

	// 	public function cetak()
	// 	{
	// 		$tanggal = $this->input->post('tanggal');  

	// 		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));

	// 		if (empty($tanggal)) {
	// 			show_error('Tidak ada tanggal yang dipilih', 404);
	// 		}

	// 		$plant = $this->session->userdata('plant');

	// 		$seasoning_data = $this->seasoning_model->get_by_date($tanggal, $plant); 
	// 		$seasoning_data_verif = $this->seasoning_model->get_last_verif_by_date($tanggal, $plant); 

	// 		if (!$seasoning_data || !$seasoning_data_verif) {
	// 			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
	// 		}

	// 		$data['seasoning'] = $seasoning_data_verif;

	// 		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

	// 		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
	// 		$pdf->setPrintHeader(false); 
	// 		$pdf->SetMargins(17, 16, 15); 
	// 		$pdf->AddPage('L', 'LEGAL');
	// 		$pdf->SetFont('times', 'B', 13);

	// 		$logo_path = FCPATH . 'assets/img/logo.jpg';
	// 		if (file_exists($logo_path)) {
	// 			$pdf->Image($logo_path, 17, 14, 45);
	// 		} else {
	// 			$pdf->Write(7, "Logo tidak ditemukan\n");
	// 		}

	// 		$pdf->Write(9, "\n");
	// 		$pdf->MultiCell(0, 5, 'PEMERIKSAAN SEASONING DARI PEMASOK', 0, 'C');
	// 		$pdf->Ln(5);

	// 		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');	
	// 		$tanggal = $data['seasoning']->date;
	// 		$date = new DateTime($tanggal);
	// 		$formatted_date = strftime('%A, %d %B %Y', $date->getTimestamp());
	// 		$formatted_date2 = strftime('%d %B %Y', $date->getTimestamp());

	// 		$pdf->SetFont('times', '', 10);
	// 		$pdf->SetX(17);
	// 		$pdf->Write(0, 'Tanggal: ' . $formatted_date);
	// 		$pdf->Ln(6);

	// 		$pdf->SetFont('times', '', 8);
	// 		$pdf->Cell(6, 15, 'No.', 1, 0, 'C');
	// 		$pdf->Cell(20, 15, 'Jenis Seasoning', 1, 0, 'C');
	// 		$pdf->Cell(15, 15, 'Spesifikasi', 1, 0, 'C');
	// 		$pdf->Cell(15, 15, 'Pemasok', 1, 0, 'C');
	// 		$pdf->Cell(12, 15, 'Jenis', 1, 0, 'C');
	// 		$pdf->Cell(12, 15, 'No.', 1, 0, 'C');
	// 		$pdf->Cell(12, 15, 'Identitas', 1, 0, 'C');
	// 		$pdf->Cell(12, 15, 'No. PO', 1, 0, 'C');
	// 		$pdf->Cell(15, 15, 'Kondisi', 1, 0, 'C');
	// 		$pdf->Cell(15, 15, 'Kode', 1, 0, 'C');
	// 		$pdf->Cell(12, 15, 'Exp. Date', 1, 0, 'C');
	// 		$pdf->Cell(10, 15, 'Jumlah', 1, 0, 'C');
	// 		$pdf->Cell(10, 15, 'Sampel', 1, 0, 'C');
	// 		$pdf->Cell(10, 15, 'Jumlah', 1, 0, 'C');
	// 		$pdf->Cell(24, 5, 'Kondisi Fisik', 1, 0, 'C');
	// 		$pdf->Cell(12, 10, 'Logo', 1, 0, 'C');
	// 		$pdf->Cell(10, 15, 'Kadar', 1, 0, 'C');
	// 		$pdf->Cell(15, 15, 'Negara Asal', 1, 0, 'C');
	// 		$pdf->Cell(10, 15, 'Segel', 1, 0, 'C');
	// 		$pdf->Cell(14, 5, 'Penerimaan', 1, 0, 'C');
	// 		$pdf->Cell(28, 5, 'Persyaratan Dokumen', 1, 0, 'C');
	// 		$pdf->Cell(14, 5, 'Allergen', 1, 0, 'C');
	// 		$pdf->Cell(20, 15, 'Keterangan', 1, 0, 'C');
	// 		$pdf->Cell(10, 5, '', 0, 1, 'C');

	// 		$pdf->Cell(57, 0, '', 0, 0, 'L');
	// 		$pdf->Cell(11, 10, 'Mobil', 0, 0, 'L');
	// 		$pdf->Cell(12, 10, 'Polisi', 0, 0, 'C');
	// 		$pdf->Cell(12, 10, 'Pengantar', 0, 0, 'C');
	// 		$pdf->Cell(12, 10, '/ DO', 0, 0, 'C');
	// 		$pdf->Cell(15, 10, 'Mobil', 0, 0, 'C');
	// 		$pdf->Cell(15, 10, 'Produksi', 0, 0, 'C');
	// 		$pdf->Cell(12, 10, '', 0, 0, 'C');
	// 		$pdf->Cell(10, 10, 'Datang', 0, 0, 'C');
	// 		$pdf->Cell(10, 10, '', 0, 0, 'C');
	// 		$pdf->Cell(10, 10, 'Reject', 0, 0, 'C');
	// 		$pdf->SetFont('times', '', 5);
	// 		$pdf->Cell(6, 10, 'Kemasan', 1, 0, 'C');
	// 		$pdf->Cell(6, 10, 'Warna', 1, 0, 'C');
	// 		$pdf->Cell(6, 10, 'Kotoran', 1, 0, 'C');
	// 		$pdf->Cell(6, 10, 'Aroma', 1, 0, 'C');
	// 		$pdf->SetFont('times', '', 8);
	// 		$pdf->Cell(12, 5, 'Halal', 0, 0, 'C');
	// 		$pdf->Cell(10, 10, 'Air (%)', 0, 0, 'C');
	// 		$pdf->Cell(15, 10, 'Dibuatnya', 0, 0, 'C');
	// 		$pdf->Cell(10, 10, '', 0, 0, 'C');
	// 		$pdf->Cell(7, 10, 'OK', 1, 0, 'C');
	// 		$pdf->Cell(7, 10, 'Tolak', 1, 0, 'C');
	// 		$pdf->Cell(18, 5, 'Halal', 1, 0, 'C');
	// 		$pdf->Cell(10, 10, 'COA', 1, 0, 'C');
	// 		$pdf->Cell(7, 10, 'A', 1, 0, 'C');
	// 		$pdf->Cell(7, 10, 'NA', 1, 0, 'C');
	// 		$pdf->Cell(20, 0, '', 0, 0, 'C');
	// 		$pdf->Cell(5, 0, '', 0, 0, 'C');
	// 		$pdf->Cell(5, 5, '', 0, 1, 'C');

	// 		$pdf->Cell(200, 0, '', 0, 0, 'L');
	// 		$pdf->Cell(6, 5, 'Ada', 1, 0, 'C');
	// 		$pdf->Cell(6, 5, 'Tdk', 1, 0, 'C');
	// 		$pdf->Cell(49, 0, '', 0, 0, 'C');
	// 		$pdf->SetFont('times', '', 5);
	// 		$pdf->Cell(9, 5, 'Berlaku', 1, 0, 'C');
	// 		$pdf->Cell(9, 5, 'Tdk Berlaku', 1, 0, 'C');
	// 		$pdf->SetFont('times', '', 7);
	// 		$pdf->Cell(34, 0, '', 0, 0, 'C');
	// 		$pdf->Cell(10, 5, '', 0, 1, 'C');

	// 		$no = 1;
	// 		$kondisi_mobil_map = [
	// 			1 => 'Bersih',
	// 			2 => 'Kotor',
	// 			3 => 'Bau',
	// 			4 => 'Bocor',
	// 			5 => 'Basah',
	// 			6 => 'Kering',
	// 			7 => 'Bebas Hama',
	// 		];

	// 		foreach ($seasoning_data as $seasoning) {
	// 			$exp = (new DateTime($seasoning->expired))->format('d-m-Y');
	// 			$kondisi_arr = explode(',', $seasoning->kondisi_mobil);
	// 			$kondisi_labels = array_map(function ($val) use ($kondisi_mobil_map) {
	// 				return isset($kondisi_mobil_map[(int)$val]) ? $kondisi_mobil_map[(int)$val] : $val;
	// 			}, $kondisi_arr);
	// 			$kondisi_text = implode(', ', $kondisi_labels);

	//     // Data kolom
	// 			$row = [
	// 				['w' => 6,  'txt' => $no],
	// 				['w' => 20, 'txt' => $seasoning->jenis_seasoning],
	// 				['w' => 15, 'txt' => $seasoning->spesifikasi],
	// 				['w' => 15, 'txt' => $seasoning->pemasok],
	// 				['w' => 12, 'txt' => $seasoning->jenis_mobil],
	// 				['w' => 12, 'txt' => $seasoning->no_polisi],
	// 				['w' => 12, 'txt' => $seasoning->identitas_pengantar],
	// 				['w' => 12, 'txt' => $seasoning->no_po],
	// 				['w' => 15, 'txt' => $kondisi_text],
	// 				['w' => 15, 'txt' => $seasoning->kode_produksi],
	// 				['w' => 12, 'txt' => $exp],
	// 				['w' => 10, 'txt' => $seasoning->jumlah_barang],
	// 				['w' => 10, 'txt' => $seasoning->sampel],
	// 				['w' => 10, 'txt' => $seasoning->jumlah_reject],
	// 				['w' => 6,  'txt' => ($seasoning->kemasan == 'sesuai') ? '✔' : (($seasoning->kemasan == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
	// 				['w' => 6,  'txt' => ($seasoning->warna == 'sesuai') ? '✔' : (($seasoning->warna == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
	// 				['w' => 6,  'txt' => ($seasoning->kotoran == 'sesuai') ? '✔' : (($seasoning->kotoran == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
	// 				['w' => 6,  'txt' => ($seasoning->aroma == 'sesuai') ? '✔' : (($seasoning->aroma == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
	// 				['w' => 12, 'txt' => $seasoning->logo_halal],
	// 				['w' => 10, 'txt' => $seasoning->kadar_air],
	// 				['w' => 15, 'txt' => $seasoning->negara_asal],
	// 				['w' => 10, 'txt' => $seasoning->segel],
	// 				['w' => 14, 'txt' => $seasoning->penerimaan],
	// 				['w' => 18, 'txt' => $seasoning->sertif_halal],
	// 				['w' => 10, 'txt' => $seasoning->coa],
	// 				['w' => 14, 'txt' => $seasoning->allergen],
	// 				['w' => 20, 'txt' => !empty($seasoning->keterangan) ? $seasoning->keterangan : '-'],
	// 			];

	//     // Hitung tinggi maksimum berdasarkan line count terbanyak
	// 			$lineHeight = 6;
	// 			$maxLines = 1;
	// 			foreach ($row as $col) {
	// 				$pdf->SetFont(isset($col['font']) ? $col['font'] : 'times', '', 8);
	// 				$lines = $pdf->getNumLines($col['txt'], $col['w']);
	// 				if ($lines > $maxLines) $maxLines = $lines;
	// 			}
	// 			$rowHeight = $lineHeight * $maxLines;

	//     // Simpan posisi awal
	// 			$x = $pdf->GetX();
	// 			$y = $pdf->GetY();

	//     // Tulis setiap kolom dengan tinggi seragam
	// 			foreach ($row as $col) {
	// 				$pdf->SetFont(isset($col['font']) ? $col['font'] : 'times', '', 8);
	// 				$pdf->MultiCell($col['w'], $rowHeight, $col['txt'], 1, 'C', false, 0, '', '', true, 0, false, true, $rowHeight, 'M');
	// 			}

	// 			$pdf->Ln();
	// 			$no++;
	// 		}

	// 		$pdf->SetFont('times', 'I', 7);
	// 		$pdf->Cell(330, 5, 'QW 02/00', 0, 1, 'R'); 

	// 		$this->load->model('pegawai_model');
	// 		$data['seasoning']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['seasoning']->username);
	// 		$data['seasoning']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['seasoning']->nama_spv);

	// // 1. KETERANGAN
	// 		$pdf->SetY($pdf->GetY() + 2);
	// 		$pdf->SetFont('dejavusans', '', 7);	
	// 		$pdf->SetX(17); 
	// 		$pdf->Cell(30, 5, 'Keterangan : ', 0, 0, 'L');
	// 		$pdf->Ln(4);
	// 		$pdf->SetX(17); 
	// 		$pdf->Cell(60, 3, '(✔) Sesuai Spesifikasi/Standar', 0, 0, 'L');
	// 		$pdf->Cell(50, 3, '(A) Allergen', 0, 1, 'L'); 
	// 		$pdf->SetX(17); 
	// 		$pdf->Cell(60, 3, '(✘) Tidak Sesuai Spesifikasi/Standar', 0, 0, 'L');
	// 		$pdf->Cell(50, 3, '(NA) NonAllergen', 0, 1, 'L');

	// // 2. CATATAN
	// 		$pdf->SetY($pdf->GetY() + 3); 
	// 		$pdf->SetFont('times', '', 8);
	// 		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
	// 		foreach ($seasoning_data as $item) {
	// 			if (!empty($item->catatan)) {
	// 				$pdf->Cell(10, 0, '', 0, 0, 'L'); 
	// 				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
	// 			}
	// 		}

	// 		$y_after_keterangan = $pdf->GetY() + 5;
	// 		$status_verifikasi = true;
	// 		foreach ($seasoning_data as $item) {
	// 			if ($item->status_spv != '1') {
	// 				$status_verifikasi = false;
	// 				break;
	// 			}
	// 		}

	// // 3. VERIFIKASI & QR CODE
	// 		$pdf->SetFont('times', '', 9);
	// 		$pdf->SetTextColor(0, 0, 0);

	// 		if ($status_verifikasi) {
	// 			$y_verifikasi = $y_after_keterangan;

	// 	// Dibuat oleh (QC)
	// 			$pdf->SetXY(60, $y_verifikasi);
	// 			$pdf->Cell(50, 5, 'Dibuat Oleh,', 0, 0, 'C');
	// 			if (!empty($data['seasoning']->nama_lengkap_qc)) {
	// 				$update_tanggal_qc = !empty($data['seasoning']->created_at)
	// 				? (new DateTime($data['seasoning']->created_at))->format('d-m-Y | H:i')
	// 				: date('d-m-Y | H:i'); 

	// 				$qr_text_qc = "Dibuat secara digital oleh,\n" .
	// 				$data['seasoning']->nama_lengkap_qc . "\nQC Inspector\n" . $update_tanggal_qc;
	// 				$pdf->write2DBarcode($qr_text_qc, 'QRCODE,L', 78, $y_verifikasi + 5, 15, 15, null, 'N');
	// 				$pdf->SetXY(60, $y_verifikasi + 20);
	// 				$pdf->Cell(50, 5, 'QC Inspector', 0, 0, 'C');
	// 			} else {
	// 				$pdf->SetXY(60, $y_verifikasi + 15);
	// 				$pdf->Cell(50, 5, 'Belum Diverifikasi', 0, 0, 'C');
	// 			}

	// 	// Disetujui oleh (SPV)
	// 			$update_tanggal = (new DateTime($data['seasoning']->tgl_update_spv))->format('d-m-Y | H:i');
	// 			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['seasoning']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
	// 			$pdf->SetXY(160, $y_verifikasi);
	// 			$pdf->Cell(150, 5, 'Disetujui Oleh,', 0, 0, 'C');
	// 			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 227, $y_verifikasi + 5, 16, 16, null, 'N');
	// 			$pdf->SetXY(160, $y_verifikasi + 20);
	// 			$pdf->Cell(150, 5, 'Supervisor QC', 0, 0, 'C');

	// 		} else {
	// 			$pdf->SetTextColor(255, 0, 0); 
	// 			$pdf->SetFont('times', '', 9);
	// 			$pdf->SetXY(200, $y_after_keterangan);
	// 			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
	// 		}

	// 		$pdf->setPrintFooter(false);
	// 		$filename = "Pemeriksaan Seasoning_{$formatted_date2}.pdf";
	// 		$pdf->Output($filename, 'I');

	// 	}

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal');
		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$seasoning_data = $this->seasoning_model->get_by_date($tanggal, $plant);
		$seasoning_verif = $this->seasoning_model->get_last_verif_by_date($tanggal, $plant);

		if (!$seasoning_data || !$seasoning_verif) {
			show_error('Data tidak ditemukan', 404);
		}

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF('L', 'mm', 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(17, 16, 15);
		$pdf->SetAutoPageBreak(true, 15);
		$pdf->AddPage();

		/* =====================================================
       HEADER DOKUMEN (TIDAK DIUBAH)
       ===================================================== */
		$pdf->SetFont('times', 'B', 13);
		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 17, 14, 45);
		}

		$pdf->Ln(9);
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN SEASONING DARI PEMASOK', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$date = new DateTime($seasoning_verif->date);
		$formatted_date = strftime('%A, %d %B %Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->Write(0, 'Tanggal: ' . $formatted_date);
		$pdf->Ln(6);
		/* =====================================================
       HEADER TABEL (ASLI, TIDAK DIUBAH)
       ===================================================== */
		// === SIMPAN SETTING ASLI ===
		$autoBreak = $pdf->getAutoPageBreak();
		$breakMargin = $pdf->getBreakMargin();

		// === MATIKAN AUTO PAGE BREAK SEBELUM HEADER ===
		$pdf->SetAutoPageBreak(false, 0);

		// === GAMBAR HEADER TABEL (ASLI, TIDAK DIUBAH) ===
		$this->_drawTableHeader($pdf);

		// === SET POSISI Y AMAN SETELAH HEADER ===
		$pdf->SetY($pdf->GetY());

		// === AKTIFKAN KEMBALI AUTO PAGE BREAK ===
		$pdf->SetAutoPageBreak($autoBreak, $breakMargin);


		/* =====================================================
       ISI TABEL
       ===================================================== */
		$no = 1;
		$lineHeight = 6;
		$bufferTTD = 65; // sebelumnya 45 (INI WAJIB)

		foreach ($seasoning_data as $s) {

			$exp = !empty($s->expired) ? date('d-m-Y', strtotime($s->expired)) : '-';

			$row = [
				['w' => 6,  't' => $no],
				['w' => 20, 't' => $s->jenis_seasoning],
				['w' => 15, 't' => $s->spesifikasi],
				['w' => 15, 't' => $s->pemasok],
				['w' => 12, 't' => $s->jenis_mobil],
				['w' => 12, 't' => $s->no_polisi],
				['w' => 12, 't' => $s->identitas_pengantar],
				['w' => 12, 't' => $s->no_po],
				['w' => 15, 't' => $s->kondisi_mobil],
				['w' => 15, 't' => $s->kode_produksi],
				['w' => 12, 't' => $exp],
				['w' => 10, 't' => $s->jumlah_barang],
				['w' => 10, 't' => $s->sampel],
				['w' => 10, 't' => $s->jumlah_reject],

				// ==== KONDISI FISIK (ASLI, TIDAK DIUBAH)
				['w' => 6, 't' => ($s->kemasan == 'sesuai') ? '✔' : (($s->kemasan == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 6, 't' => ($s->warna   == 'sesuai') ? '✔' : (($s->warna   == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 6, 't' => ($s->kotoran == 'sesuai') ? '✔' : (($s->kotoran == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 6, 't' => ($s->aroma   == 'sesuai') ? '✔' : (($s->aroma   == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],

				['w' => 12, 't' => $s->logo_halal],
				['w' => 10, 't' => $s->kadar_air],
				['w' => 15, 't' => $s->negara_asal],
				['w' => 10, 't' => $s->segel],
				['w' => 14, 't' => $s->penerimaan],
				['w' => 18, 't' => $s->sertif_halal],
				['w' => 10, 't' => $s->coa],
				['w' => 14, 't' => $s->allergen],
				['w' => 20, 't' => !empty($s->keterangan) ? $s->keterangan : '-'],
			];

			/* === HITUNG TINGGI BARIS === */
			$maxLines = 1;
			foreach ($row as $c) {
				$pdf->SetFont(isset($c['font']) ? $c['font'] : 'times', '', 8);
				$maxLines = max($maxLines, $pdf->getNumLines($c['t'], $c['w']));
			}
			$rowHeight = $lineHeight * $maxLines;

			/* === CEK PAGE BREAK (INI YANG FIX NUMPUK) === */
			if (($pdf->GetY() + $rowHeight) > ($pdf->getPageHeight() - $pdf->getBreakMargin())) {
				$pdf->AddPage();
				$this->_drawTableHeader($pdf);
				$pdf->SetAutoPageBreak($autoBreak);
			}

			/* === RENDER BARIS === */
			$x = $pdf->GetX();
			$y = $pdf->GetY();

			foreach ($row as $c) {
				$pdf->SetXY($x, $y);
				$pdf->SetFont(isset($c['font']) ? $c['font'] : 'times', '', 8);
				$pdf->MultiCell($c['w'], $rowHeight, $c['t'], 1, 'C', false, 0);
				$x += $c['w'];
			}

			$pdf->SetY($y + $rowHeight);


			$no++;
		}

		/* =====================================================
       CATATAN (ASLI)
       ===================================================== */
		$pdf->Ln(3);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(0, 5, 'Catatan :', 0, 1);

		foreach ($seasoning_data as $s) {
			if (!empty($s->catatan)) {

				$x = $pdf->GetX();
				$y = $pdf->GetY();

				// tanda strip
				$pdf->Cell(5, 5, '-', 0, 0);

				// paksa MultiCell mulai setelah strip
				$pdf->SetXY($x + 5, $y);
				$pdf->MultiCell(195, 5, $s->catatan, 0, 'L');
			}
		}


		/* =====================================================
       TTD + QR (TERKUNCI)
       ===================================================== */
		if (($pdf->GetY() + 40) > ($pdf->getPageHeight() - $pdf->getBreakMargin())) {
			// $pdf->AddPage();
		}

		$this->load->model('pegawai_model');
		$qc  = $this->pegawai_model->get_nama_lengkap($seasoning_verif->username);
		$spv = $this->pegawai_model->get_nama_lengkap($seasoning_verif->nama_spv);

		$y = $pdf->GetY() + 5;
		$pdf->SetFont('times', '', 9);

		$pdf->SetXY(70, $y);
		$pdf->Cell(50, 5, 'Dibuat Oleh,', 0, 0, 'C');
		$pdf->write2DBarcode("Dibuat oleh\n$qc", 'QRCODE,L', 88, $y + 6, 14, 14);
		$pdf->SetXY(70, $y + 22);
		$pdf->Cell(50, 5, 'QC Inspector', 0, 0, 'C');

		$pdf->SetXY(190, $y);
		$pdf->Cell(50, 5, 'Disetujui Oleh,', 0, 0, 'C');
		$pdf->write2DBarcode("Disetujui oleh\n$spv", 'QRCODE,L', 208, $y + 6, 14, 14);
		$pdf->SetXY(190, $y + 22);
		$pdf->Cell(50, 5, 'Supervisor QC', 0, 0, 'C');

		$pdf->Output('Pemeriksaan_Seasoning.pdf', 'I');
	}

	public function cetak_audit()
	{
		$tanggal = $this->input->post('tanggal');
		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$seasoning_data = $this->seasoning_model->get_audit_by_date($tanggal, $plant);
		$seasoning_verif = $this->seasoning_model->get_last_verif_by_date($tanggal, $plant);

		if (!$seasoning_data || !$seasoning_verif) {
			show_error('Data tidak ditemukan', 404);
		}

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF('L', 'mm', 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(17, 16, 15);
		$pdf->SetAutoPageBreak(true, 15);
		$pdf->AddPage();

		/* =====================================================
       HEADER DOKUMEN (TIDAK DIUBAH)
       ===================================================== */
		$pdf->SetFont('times', 'B', 13);
		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 17, 14, 45);
		}

		$pdf->Ln(9);
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN SEASONING DARI PEMASOK', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$date = new DateTime($seasoning_verif->date);
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
		$pdf->Write(0, 'Tanggal: ' . $formatted_date);
		$pdf->Ln(6);
		/* =====================================================
       HEADER TABEL (ASLI, TIDAK DIUBAH)
       ===================================================== */
		// === SIMPAN SETTING ASLI ===
		$autoBreak = $pdf->getAutoPageBreak();
		$breakMargin = $pdf->getBreakMargin();

		// === MATIKAN AUTO PAGE BREAK SEBELUM HEADER ===
		$pdf->SetAutoPageBreak(false, 0);

		// === GAMBAR HEADER TABEL (ASLI, TIDAK DIUBAH) ===
		$this->_drawTableHeader($pdf);

		// === SET POSISI Y AMAN SETELAH HEADER ===
		$pdf->SetY($pdf->GetY());

		// === AKTIFKAN KEMBALI AUTO PAGE BREAK ===
		$pdf->SetAutoPageBreak($autoBreak, $breakMargin);


		/* =====================================================
       ISI TABEL
       ===================================================== */
		$no = 1;
		$lineHeight = 6;
		$bufferTTD = 65; // sebelumnya 45 (INI WAJIB)

		foreach ($seasoning_data as $s) {

			$exp = !empty($s->expired) ? date('d-m-Y', strtotime($s->expired)) : '-';

			$row = [
				['w' => 6,  't' => $no],
				['w' => 20, 't' => $s->jenis_seasoning],
				['w' => 15, 't' => $s->spesifikasi],
				['w' => 15, 't' => $s->pemasok],
				['w' => 12, 't' => $s->jenis_mobil],
				['w' => 12, 't' => $s->no_polisi],
				['w' => 12, 't' => $s->identitas_pengantar],
				['w' => 12, 't' => $s->no_po],
				['w' => 15, 't' => $s->kondisi_mobil],
				['w' => 15, 't' => $s->kode_produksi],
				['w' => 12, 't' => $exp],
				['w' => 10, 't' => $s->jumlah_barang],
				['w' => 10, 't' => $s->sampel],
				['w' => 10, 't' => $s->jumlah_reject],

				// ==== KONDISI FISIK (ASLI, TIDAK DIUBAH)
				['w' => 6, 't' => ($s->kemasan == 'sesuai') ? '✔' : (($s->kemasan == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 6, 't' => ($s->warna   == 'sesuai') ? '✔' : (($s->warna   == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 6, 't' => ($s->kotoran == 'sesuai') ? '✔' : (($s->kotoran == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 6, 't' => ($s->aroma   == 'sesuai') ? '✔' : (($s->aroma   == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],

				['w' => 12, 't' => $s->logo_halal],
				['w' => 10, 't' => $s->kadar_air],
				['w' => 15, 't' => $s->negara_asal],
				['w' => 10, 't' => $s->segel],
				['w' => 14, 't' => $s->penerimaan],
				['w' => 18, 't' => $s->sertif_halal],
				['w' => 10, 't' => $s->coa],
				['w' => 14, 't' => $s->allergen],
				['w' => 20, 't' => !empty($s->keterangan) ? $s->keterangan : '-'],
			];

			/* === HITUNG TINGGI BARIS === */
			$maxLines = 1;
			foreach ($row as $c) {
				$pdf->SetFont(isset($c['font']) ? $c['font'] : 'times', '', 8);
				$maxLines = max($maxLines, $pdf->getNumLines($c['t'], $c['w']));
			}
			$rowHeight = $lineHeight * $maxLines;

			/* === CEK PAGE BREAK (INI YANG FIX NUMPUK) === */
			if (($pdf->GetY() + $rowHeight) > ($pdf->getPageHeight() - $pdf->getBreakMargin())) {
				$pdf->AddPage();
				$this->_drawTableHeader($pdf);
				$pdf->SetAutoPageBreak($autoBreak);
			}

			/* === RENDER BARIS === */
			$x = $pdf->GetX();
			$y = $pdf->GetY();

			foreach ($row as $c) {
				$pdf->SetXY($x, $y);
				$pdf->SetFont(isset($c['font']) ? $c['font'] : 'times', '', 8);
				$pdf->MultiCell($c['w'], $rowHeight, $c['t'], 1, 'C', false, 0);
				$x += $c['w'];
			}

			$pdf->SetY($y + $rowHeight);


			$no++;
		}

		/* =====================================================
       CATATAN (ASLI)
       ===================================================== */
		$pdf->Ln(3);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(0, 5, 'Catatan :', 0, 1);

		foreach ($seasoning_data as $s) {
			if (!empty($s->catatan)) {

				$x = $pdf->GetX();
				$y = $pdf->GetY();

				// tanda strip
				$pdf->Cell(5, 5, '-', 0, 0);

				// paksa MultiCell mulai setelah strip
				$pdf->SetXY($x + 5, $y);
				$pdf->MultiCell(195, 5, $s->catatan, 0, 'L');
			}
		}


		/* =====================================================
       TTD + QR (TERKUNCI)
       ===================================================== */
		if (($pdf->GetY() + 40) > ($pdf->getPageHeight() - $pdf->getBreakMargin())) {
			// $pdf->AddPage();
		}

		$this->load->model('pegawai_model');
		$qc  = $this->pegawai_model->get_nama_lengkap($seasoning_verif->username);
		$spv = $this->pegawai_model->get_nama_lengkap($seasoning_verif->nama_spv);

		$y = $pdf->GetY() + 5;
		$pdf->SetFont('times', '', 9);

		$pdf->SetXY(70, $y);
		$pdf->Cell(50, 5, 'Dibuat Oleh,', 0, 0, 'C');
		$pdf->write2DBarcode("Dibuat oleh\n$qc", 'QRCODE,L', 88, $y + 6, 14, 14);
		$pdf->SetXY(70, $y + 22);
		$pdf->Cell(50, 5, 'QC Inspector', 0, 0, 'C');

		$pdf->SetXY(190, $y);
		$pdf->Cell(50, 5, 'Disetujui Oleh,', 0, 0, 'C');
		$pdf->write2DBarcode("Disetujui oleh\n$spv", 'QRCODE,L', 208, $y + 6, 14, 14);
		$pdf->SetXY(190, $y + 22);
		$pdf->Cell(50, 5, 'Supervisor QC', 0, 0, 'C');

		$pdf->Output('Pemeriksaan_Seasoning.pdf', 'I');
	}

	private function _drawTableHeader($pdf)
	{
		$pdf->SetFont('times', '', 8);

		$pdf->Cell(6, 15, 'No.', 1, 0, 'C');
		$pdf->Cell(20, 15, 'Jenis Seasoning', 1, 0, 'C');
		$pdf->Cell(15, 15, 'Spesifikasi', 1, 0, 'C');
		$pdf->Cell(15, 15, 'Pemasok', 1, 0, 'C');
		$pdf->Cell(12, 15, 'Jenis', 1, 0, 'C');
		$pdf->Cell(12, 15, 'No.', 1, 0, 'C');
		$pdf->Cell(12, 15, 'Identitas', 1, 0, 'C');
		$pdf->Cell(12, 15, 'No. PO', 1, 0, 'C');
		$pdf->Cell(15, 15, 'Kondisi', 1, 0, 'C');
		$pdf->Cell(15, 15, 'Kode', 1, 0, 'C');
		$pdf->Cell(12, 15, 'Exp. Date', 1, 0, 'C');
		$pdf->Cell(10, 15, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(10, 15, 'Sampel', 1, 0, 'C');
		$pdf->Cell(10, 15, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(24, 5, 'Kondisi Fisik', 1, 0, 'C');
		$pdf->Cell(12, 10, 'Logo', 1, 0, 'C');
		$pdf->Cell(10, 15, 'Kadar', 1, 0, 'C');
		$pdf->Cell(15, 15, 'Negara Asal', 1, 0, 'C');
		$pdf->Cell(10, 15, 'Segel', 1, 0, 'C');
		$pdf->Cell(14, 5, 'Penerimaan', 1, 0, 'C');
		$pdf->Cell(28, 5, 'Persyaratan Dokumen', 1, 0, 'C');
		$pdf->Cell(14, 5, 'Allergen', 1, 0, 'C');
		$pdf->Cell(20, 15, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$pdf->Cell(57, 0, '', 0, 0, 'L');
		$pdf->Cell(11, 10, 'Mobil', 0, 0, 'L');
		$pdf->Cell(12, 10, 'Polisi', 0, 0, 'C');
		$pdf->Cell(12, 10, 'Pengantar', 0, 0, 'C');
		$pdf->Cell(12, 10, '/ DO', 0, 0, 'C');
		$pdf->Cell(15, 10, 'Mobil', 0, 0, 'C');
		$pdf->Cell(15, 10, 'Produksi', 0, 0, 'C');
		$pdf->Cell(12, 10, '', 0, 0, 'C');
		$pdf->Cell(10, 10, 'Datang', 0, 0, 'C');
		$pdf->Cell(10, 10, '', 0, 0, 'C');
		$pdf->Cell(10, 10, 'Reject', 0, 0, 'C');
		$pdf->SetFont('times', '', 5);
		$pdf->Cell(6, 10, 'Kemasan', 1, 0, 'C');
		$pdf->Cell(6, 10, 'Warna', 1, 0, 'C');
		$pdf->Cell(6, 10, 'Kotoran', 1, 0, 'C');
		$pdf->Cell(6, 10, 'Aroma', 1, 0, 'C');
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(12, 5, 'Halal', 0, 0, 'C');
		$pdf->Cell(10, 10, 'Air (%)', 0, 0, 'C');
		$pdf->Cell(15, 10, 'Dibuatnya', 0, 0, 'C');
		$pdf->Cell(10, 10, '', 0, 0, 'C');
		$pdf->Cell(7, 10, 'OK', 1, 0, 'C');
		$pdf->Cell(7, 10, 'Tolak', 1, 0, 'C');
		$pdf->Cell(18, 5, 'Halal', 1, 0, 'C');
		$pdf->Cell(10, 10, 'COA', 1, 0, 'C');
		$pdf->Cell(7, 10, 'A', 1, 0, 'C');
		$pdf->Cell(7, 10, 'NA', 1, 0, 'C');
		$pdf->Cell(20, 0, '', 0, 0, 'C');
		$pdf->Cell(5, 0, '', 0, 0, 'C');
		$pdf->Cell(5, 5, '', 0, 1, 'C');

		$pdf->Cell(200, 0, '', 0, 0, 'L');
		$pdf->Cell(6, 5, 'Ada', 1, 0, 'C');
		$pdf->Cell(6, 5, 'Tdk', 1, 0, 'C');
		$pdf->Cell(49, 0, '', 0, 0, 'C');
		$pdf->SetFont('times', '', 5);
		$pdf->Cell(9, 5, 'Berlaku', 1, 0, 'C');
		$pdf->Cell(9, 5, 'Tdk Berlaku', 1, 0, 'C');
		$pdf->SetFont('times', '', 7);
		$pdf->Cell(34, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');
		$pdf->SetFont('times', '', 7);
	}
}
