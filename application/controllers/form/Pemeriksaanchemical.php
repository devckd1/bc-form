<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Pemeriksaanchemical extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('pemeriksaanchemical_model');
		$this->load->library('upload');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'pemeriksaanchemical' => $this->pemeriksaanchemical_model->get_data_by_plant()
		);

		$this->active_nav = 'pemeriksaanchemical';
		$this->render('form/pemeriksaanchemical/pemeriksaanchemical', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'pemeriksaanchemical' => $this->pemeriksaanchemical_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'pemeriksaanchemical';
		$this->render('form/pemeriksaanchemical/pemeriksaanchemical-detail', $data);
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
		$rules = $this->pemeriksaanchemical_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$file_name = null;

			if (isset($_FILES['bukti_coa']) && $_FILES['bukti_coa']['error'] != 4) {
				$config = array(
					'upload_path'   => "./uploads/penerimaan_chemical/",
					'allowed_types' => "jpg|png|jpeg|pdf",
					'overwrite'     => TRUE,
					'max_size'      => 2048,
					'encrypt_name'  => TRUE
				);
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('bukti_coa')) {
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error_msg', 'Upload gagal: ' . $error);
					redirect('pemeriksaanchemical/tambah');
				} else {
					$data = $this->upload->data();
					$file_name = $data['file_name'];

					// ==== Kompres Gambar Jika Bukan PDF ====
					if ($data['is_image']) {
						$this->_compress_image($data['full_path'], $data['file_type']);
					}
				}
			}

			$update = $this->pemeriksaanchemical_model->insert($file_name);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Chemical dari Supplier berhasil disimpan');
				redirect('pemeriksaanchemical');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Chemical dari Supplier gagal disimpan');
				redirect('pemeriksaanchemical');
			}
		}

		$data = array(
			'pemeriksaanchemical' => $this->pemeriksaanchemical_model->get_data_by_plant()
		);

		$this->active_nav = 'pemeriksaanchemical';
		$this->render('form/pemeriksaanchemical/pemeriksaanchemical-tambah', $data);
	}

	public function edit($uuid)
	{
		$pemeriksaanchemical = $this->pemeriksaanchemical_model->get_by_uuid($uuid);
		$rules = $this->pemeriksaanchemical_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$config = array(
				'upload_path' => "./uploads/penerimaan_chemical/",
				'allowed_types' => "jpg|png|jpeg|pdf",
				'overwrite' => TRUE,
				'max_size' => "2048",
				'encrypt_name' => TRUE
			);

			$this->upload->initialize($config);

			if (!empty($_FILES['bukti_coa']['name'])) {
				if (!$this->upload->do_upload('bukti_coa')) {
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error_msg', 'Upload gagal: ' . $error);
					redirect('pemeriksaanchemical/edit/' . $uuid);
				} else {
					$data = $this->upload->data();
					$file_name = $data['file_name'];

					// ==== Kompres Gambar Jika Bukan PDF ====
					if ($data['is_image']) {
						$this->_compress_image($data['full_path'], $data['file_type']);
					}
				}
			} else {
				$file_name = $pemeriksaanchemical->bukti_coa;
			}

			$update = $this->pemeriksaanchemical_model->update($uuid, $file_name);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Chemical dari Supplier berhasil diupdate');
				redirect('pemeriksaanchemical');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Chemical dari Supplier gagal diupdate');
				redirect('pemeriksaanchemical');
			}
		}

		$data = array(
			'pemeriksaanchemical' => $pemeriksaanchemical
		);

		$this->active_nav = 'pemeriksaanchemical';
		$this->render('form/pemeriksaanchemical/pemeriksaanchemical-edit', $data);
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

		$new_width = min($width, 800);
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
			redirect('pemeriksaanchemical');
		}

		$deleted = $this->pemeriksaanchemical_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('pemeriksaanchemical');
	}

	public function verifikasi()
	{
		$data = array(
			'pemeriksaanchemical' => $this->pemeriksaanchemical_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-pemeriksaanchemical';
		$this->render('form/pemeriksaanchemical/pemeriksaanchemical-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->pemeriksaanchemical_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->pemeriksaanchemical_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Chemical dari Supplier berhasil di Update');
				redirect('pemeriksaanchemical/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Chemical dari Supplier gagal di Update');
				redirect('pemeriksaanchemical/verifikasi');
			}
		}

		$data = array(
			'pemeriksaanchemical' => $this->pemeriksaanchemical_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-pemeriksaanchemical';
		$this->render('form/pemeriksaanchemical/pemeriksaanchemical-status', $data);
	}

	public function audit()
	{
		$data = array(
			'data' => $this->pemeriksaanchemical_model
				->get_audit_by_plant()
		);

		$this->active_nav = 'pemeriksaanchemical';

		$this->render(
			'form/pemeriksaanchemical/pemeriksaanchemical-audit',
			$data
		);
	}


	public function detail_audit($uuid)
	{
		$pemeriksaanchemical = $this->pemeriksaanchemical_model
			->get_audit_by_uuid($uuid);

		if (!$pemeriksaanchemical) {
			$this->session->set_flashdata(
				'error_msg',
				'Data audit Pemeriksaan Chemical tidak ditemukan.'
			);
			redirect('pemeriksaanchemical/audit');
			return;
		}

		$data = array(
			'pemeriksaanchemical' => $pemeriksaanchemical,
			'mode' => 'audit'
		);

		$this->active_nav = 'pemeriksaanchemical';

		$this->render(
			'form/pemeriksaanchemical/pemeriksaanchemical-detail',
			$data
		);
	}

	public function edit_audit($uuid)
	{
		$pemeriksaanchemical = $this->pemeriksaanchemical_model
			->get_audit_by_uuid($uuid);

		if (!$pemeriksaanchemical) {
			$this->session->set_flashdata(
				'error_msg',
				'Data audit Pemeriksaan Chemical tidak ditemukan.'
			);
			redirect('pemeriksaanchemical/audit');
			return;
		}

		$this->form_validation->set_rules(
			$this->pemeriksaanchemical_model->rules()
		);

		if ($this->form_validation->run() === TRUE) {

			// Pertahankan file snapshot jika tidak ada upload baru.
			$file_name = $pemeriksaanchemical->bukti_coa;
			$file_baru = false;

			$config = array(
				'upload_path'   => './uploads/penerimaan_chemical/',
				'allowed_types' => 'jpg|png|jpeg|pdf',
				'overwrite'     => FALSE,
				'max_size'      => 2048,
				'encrypt_name'  => TRUE
			);

			$this->upload->initialize($config);

			if (!empty($_FILES['bukti_coa']['name'])) {
				if (!$this->upload->do_upload('bukti_coa')) {
					$error = $this->upload->display_errors('', '');

					$this->session->set_flashdata(
						'error_msg',
						'Upload gagal: ' . $error
					);

					redirect('pemeriksaanchemical/edit-audit/' . $uuid);
					return;
				}

				$upload_data = $this->upload->data();
				$file_name = $upload_data['file_name'];
				$file_baru = true;

				if (!empty($upload_data['is_image'])) {
					$this->_compress_image(
						$upload_data['full_path'],
						$upload_data['file_type']
					);
				}
			}

			// Model wajib membatasi update ke uuid + plant + is_audit = 1.
			$update = $this->pemeriksaanchemical_model
				->update_audit($uuid, $file_name);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Audit Pemeriksaan Chemical berhasil diupdate.'
				);

				redirect('pemeriksaanchemical/audit');
				return;
			}

			// Jika update gagal, hapus hanya file baru yang baru diunggah.
			if ($file_baru) {
				$path_file_baru = './uploads/penerimaan_chemical/'
					. basename($file_name);

				if (is_file($path_file_baru)) {
					unlink($path_file_baru);
				}
			}

			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Pemeriksaan Chemical gagal diupdate.'
			);

			redirect('pemeriksaanchemical/edit-audit/' . $uuid);
			return;
		}

		$data = array(
			'pemeriksaanchemical' => $pemeriksaanchemical,
			'validation_errors' => validation_errors(),
			'mode' => 'audit'
		);

		$this->active_nav = 'pemeriksaanchemical';

		$this->render(
			'form/pemeriksaanchemical/pemeriksaanchemical-edit',
			$data
		);
	}

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal');

		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$pemeriksaanchemical_data = $this->pemeriksaanchemical_model->get_by_date($tanggal, $plant);
		$pemeriksaanchemical_data_verif = $this->pemeriksaanchemical_model->get_last_verif_by_date($tanggal, $plant);

		if (!$pemeriksaanchemical_data || !$pemeriksaanchemical_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['pemeriksaanchemical'] = $pemeriksaanchemical_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(17, 16, 15);
		$pdf->AddPage('L', 'LEGAL');
		$pdf->SetFont('times', 'B', 13);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 17, 14, 45);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(9, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN CHEMICAL DARI SUPPLIER', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['pemeriksaanchemical']->date;
		$date = new DateTime($tanggal);
		$formatted_date = strftime('%A, %d %B %Y', $date->getTimestamp());
		$formatted_date2 = strftime('%d %B %Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 9);
		$pdf->SetX(17);
		$label_width = 35;
		$value_width = 80;
		$pdf->Cell($label_width, 5, 'Hari / Tanggal', 0, 0);
		$pdf->Cell(2, 5, ':', 0, 0);
		$pdf->Cell($value_width, 5, $formatted_date, 0, 1);

		$pdf->Cell($label_width, 5, 'Shift', 0, 0);
		$pdf->Cell(2, 5, ':', 0, 0);
		$pdf->Cell($value_width, 5, $data['pemeriksaanchemical']->shift, 0, 1);
		$pdf->Ln(1);

		$pdf->SetFont('times', '', 8);
		$pdf->Cell(10, 12, 'No.', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Jenis Chemical', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Pemasok', 1, 0, 'C');
		$pdf->Cell(15, 12, 'Jenis Mobil', 1, 0, 'C');
		$pdf->Cell(15, 12, 'No. Polisi', 1, 0, 'C');
		$pdf->Cell(15, 12, 'Identitas', 1, 0, 'C');
		$pdf->Cell(15, 12, 'No. PO/DO', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Kondisi Mobil', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Kode Produksi', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Exp. Date', 1, 0, 'C');
		$pdf->Cell(12, 12, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(12, 12, 'Sampel', 1, 0, 'C');
		$pdf->Cell(12, 12, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(30, 6, 'Kondisi Fisik', 1, 0, 'C');
		$pdf->Cell(20, 6, 'Dokumen Halal', 1, 0, 'C');
		$pdf->Cell(10, 12, 'Segel', 1, 0, 'C');
		$pdf->Cell(10, 12, 'COA', 1, 0, 'C');
		$pdf->Cell(20, 6, 'Penerimaan', 1, 0, 'C');
		$pdf->Cell(27, 12, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$pdf->Cell(80, 0, '', 0, 0, 'L');
		$pdf->Cell(15, 6, 'Pengantar', 0, 0, 'C');
		$pdf->Cell(75, 6, '', 0, 0, 'C');
		$pdf->Cell(12, 6, 'Barang', 0, 0, 'C');
		$pdf->Cell(12, 6, '', 0, 0, 'C');
		$pdf->Cell(12, 6, 'Reject', 0, 0, 'C');
		$pdf->SetFont('times', '', 7);
		$pdf->Cell(10, 6, 'Kemasan', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Warna', 1, 0, 'C');
		$pdf->Cell(10, 6, 'pH', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Berlaku', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Tdk', 1, 0, 'C');
		$pdf->Cell(20, 6, '', 0, 0, 'C');
		$pdf->Cell(10, 6, 'OK', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Tolak', 1, 0, 'C');
		$pdf->Cell(30, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$no = 1;

		// Mapping kondisi mobil (jika bentuk kode angka)
		$kondisi_mobil_map = [
			1 => 'Bersih',
			2 => 'Kotor',
			3 => 'Bau',
			4 => 'Bocor',
			5 => 'Basah',
			6 => 'Kering',
			7 => 'Bebas Hama',
		];

		foreach ($pemeriksaanchemical_data as $pemeriksaanchemical) {
			$exp = (new DateTime($pemeriksaanchemical->expired))->format('d-m-Y');

			// Mapping kondisi mobil ke teks
			$kondisi_arr = explode(',', $pemeriksaanchemical->kondisi_mobil);
			$kondisi_labels = array_map(function ($val) use ($kondisi_mobil_map) {
				return $kondisi_mobil_map[(int)$val] ?? $val;
			}, $kondisi_arr);
			$kondisi_text = implode(', ', $kondisi_labels);

			// Buat array data baris
			$row_data = [
				['w' => 10, 'text' => $no],
				['w' => 20, 'text' => $pemeriksaanchemical->jenis_chemical],
				['w' => 20, 'text' => $pemeriksaanchemical->pemasok],
				['w' => 15, 'text' => $pemeriksaanchemical->jenis_mobil],
				['w' => 15, 'text' => $pemeriksaanchemical->no_polisi],
				['w' => 15, 'text' => $pemeriksaanchemical->identitas_pengantar],
				['w' => 15, 'text' => $pemeriksaanchemical->no_po],
				['w' => 20, 'text' => $kondisi_text],
				['w' => 20, 'text' => $pemeriksaanchemical->kode_produksi],
				['w' => 20, 'text' => $exp],
				['w' => 12, 'text' => $pemeriksaanchemical->jumlah_barang],
				['w' => 12, 'text' => $pemeriksaanchemical->sampel],
				['w' => 12, 'text' => $pemeriksaanchemical->jumlah_reject],
				['w' => 10, 'text' => ($pemeriksaanchemical->kemasan == 'sesuai') ? '✔' : (($pemeriksaanchemical->kemasan == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 10, 'text' => ($pemeriksaanchemical->warna == 'sesuai') ? '✔' : (($pemeriksaanchemical->warna == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 10, 'text' => $pemeriksaanchemical->ph],
				['w' => 20, 'text' => $pemeriksaanchemical->halal_berlaku],
				['w' => 10, 'text' => $pemeriksaanchemical->segel],
				['w' => 10, 'text' => ($pemeriksaanchemical->coa == 'ada') ? '✔' : (($pemeriksaanchemical->coa == 'tidak ada') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 20, 'text' => $pemeriksaanchemical->penerimaan],
				['w' => 27, 'text' => !empty($pemeriksaanchemical->keterangan) ? $pemeriksaanchemical->keterangan : '-'],
			];

			// Hitung tinggi baris dinamis
			$lineHeight = 6;
			$maxLines = 1;
			foreach ($row_data as $cell) {
				$pdf->SetFont($cell['font'] ?? 'times', '', 8);
				$nb = $pdf->getNumLines($cell['text'], $cell['w']);
				if ($nb > $maxLines) {
					$maxLines = $nb;
				}
			}
			$rowHeight = $lineHeight * $maxLines;

			// Tulis semua kolom pakai MultiCell
			foreach ($row_data as $cell) {
				$pdf->SetFont($cell['font'] ?? 'times', '', 8);
				$pdf->MultiCell($cell['w'], $rowHeight, $cell['text'], 1, 'C', false, 0);
			}

			$pdf->Ln($rowHeight);
			$no++;
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(330, 5, 'QW 04/00', 0, 1, 'R');

		$this->load->model('pegawai_model');
		$data['pemeriksaanchemical']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['pemeriksaanchemical']->username);
		$data['pemeriksaanchemical']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['pemeriksaanchemical']->nama_spv);

		$pdf->SetY($pdf->GetY() + 3);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($pemeriksaanchemical_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 5;
		$status_verifikasi = true;
		foreach ($pemeriksaanchemical_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 9);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;

			// Dibuat oleh (QC)
			$pdf->SetXY(60, $y_verifikasi);
			$pdf->Cell(50, 5, 'Dibuat Oleh,', 0, 0, 'C');
			if (!empty($data['pemeriksaanchemical']->nama_lengkap_qc)) {
				$update_tanggal_qc = !empty($data['pemeriksaanchemical']->created_at)
					? (new DateTime($data['pemeriksaanchemical']->created_at))->format('d-m-Y | H:i')
					: date('d-m-Y | H:i');

				$qr_text_qc = "Dibuat secara digital oleh,\n" .
					$data['pemeriksaanchemical']->nama_lengkap_qc . "\nQC Inspector\n" . $update_tanggal_qc;
				$pdf->write2DBarcode($qr_text_qc, 'QRCODE,L', 78, $y_verifikasi + 5, 15, 15, null, 'N');
				$pdf->SetXY(60, $y_verifikasi + 20);
				$pdf->Cell(50, 5, 'QC Inspector', 0, 0, 'C');
			} else {
				$pdf->SetXY(60, $y_verifikasi + 15);
				$pdf->Cell(50, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}

			// Disetujui oleh (SPV)
			$update_tanggal = (new DateTime($data['pemeriksaanchemical']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['pemeriksaanchemical']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->SetXY(160, $y_verifikasi);
			$pdf->Cell(150, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 227, $y_verifikasi + 5, 16, 16, null, 'N');
			$pdf->SetXY(160, $y_verifikasi + 20);
			$pdf->Cell(150, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 9);
			$pdf->SetXY(200, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$filename = "Penerimaan Chemical_{$formatted_date2}.pdf";
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

		$pemeriksaanchemical_data = $this->pemeriksaanchemical_model->get_audit_by_date($tanggal, $plant);
		$pemeriksaanchemical_data_verif = $this->pemeriksaanchemical_model->get_last_verif_by_date($tanggal, $plant);

		if (!$pemeriksaanchemical_data || !$pemeriksaanchemical_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['pemeriksaanchemical'] = $pemeriksaanchemical_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(17, 16, 15);
		$pdf->AddPage('L', 'LEGAL');
		$pdf->SetFont('times', 'B', 13);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 17, 14, 45);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(9, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN CHEMICAL DARI SUPPLIER', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['pemeriksaanchemical']->date;
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

		$pdf->SetFont('times', '', 9);
		$pdf->SetX(17);
		$label_width = 35;
		$value_width = 80;
		$pdf->Cell($label_width, 5, 'Hari / Tanggal', 0, 0);
		$pdf->Cell(2, 5, ':', 0, 0);
		$pdf->Cell($value_width, 5, $formatted_date, 0, 1);

		$pdf->Cell($label_width, 5, 'Shift', 0, 0);
		$pdf->Cell(2, 5, ':', 0, 0);
		$pdf->Cell($value_width, 5, $data['pemeriksaanchemical']->shift, 0, 1);
		$pdf->Ln(1);

		$pdf->SetFont('times', '', 8);
		$pdf->Cell(10, 12, 'No.', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Jenis Chemical', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Pemasok', 1, 0, 'C');
		$pdf->Cell(15, 12, 'Jenis Mobil', 1, 0, 'C');
		$pdf->Cell(15, 12, 'No. Polisi', 1, 0, 'C');
		$pdf->Cell(15, 12, 'Identitas', 1, 0, 'C');
		$pdf->Cell(15, 12, 'No. PO/DO', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Kondisi Mobil', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Kode Produksi', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Exp. Date', 1, 0, 'C');
		$pdf->Cell(12, 12, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(12, 12, 'Sampel', 1, 0, 'C');
		$pdf->Cell(12, 12, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(30, 6, 'Kondisi Fisik', 1, 0, 'C');
		$pdf->Cell(20, 6, 'Dokumen Halal', 1, 0, 'C');
		$pdf->Cell(10, 12, 'Segel', 1, 0, 'C');
		$pdf->Cell(10, 12, 'COA', 1, 0, 'C');
		$pdf->Cell(20, 6, 'Penerimaan', 1, 0, 'C');
		$pdf->Cell(27, 12, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$pdf->Cell(80, 0, '', 0, 0, 'L');
		$pdf->Cell(15, 6, 'Pengantar', 0, 0, 'C');
		$pdf->Cell(75, 6, '', 0, 0, 'C');
		$pdf->Cell(12, 6, 'Barang', 0, 0, 'C');
		$pdf->Cell(12, 6, '', 0, 0, 'C');
		$pdf->Cell(12, 6, 'Reject', 0, 0, 'C');
		$pdf->SetFont('times', '', 7);
		$pdf->Cell(10, 6, 'Kemasan', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Warna', 1, 0, 'C');
		$pdf->Cell(10, 6, 'pH', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Berlaku', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Tdk', 1, 0, 'C');
		$pdf->Cell(20, 6, '', 0, 0, 'C');
		$pdf->Cell(10, 6, 'OK', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Tolak', 1, 0, 'C');
		$pdf->Cell(30, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$no = 1;

		// Mapping kondisi mobil (jika bentuk kode angka)
		$kondisi_mobil_map = [
			1 => 'Bersih',
			2 => 'Kotor',
			3 => 'Bau',
			4 => 'Bocor',
			5 => 'Basah',
			6 => 'Kering',
			7 => 'Bebas Hama',
		];

		foreach ($pemeriksaanchemical_data as $pemeriksaanchemical) {
			$exp = (new DateTime($pemeriksaanchemical->expired))->format('d-m-Y');

			// Mapping kondisi mobil ke teks
			$kondisi_arr = explode(',', $pemeriksaanchemical->kondisi_mobil);
			$kondisi_labels = array_map(function ($val) use ($kondisi_mobil_map) {
				return $kondisi_mobil_map[(int)$val] ?? $val;
			}, $kondisi_arr);
			$kondisi_text = implode(', ', $kondisi_labels);

			// Buat array data baris
			$row_data = [
				['w' => 10, 'text' => $no],
				['w' => 20, 'text' => $pemeriksaanchemical->jenis_chemical],
				['w' => 20, 'text' => $pemeriksaanchemical->pemasok],
				['w' => 15, 'text' => $pemeriksaanchemical->jenis_mobil],
				['w' => 15, 'text' => $pemeriksaanchemical->no_polisi],
				['w' => 15, 'text' => $pemeriksaanchemical->identitas_pengantar],
				['w' => 15, 'text' => $pemeriksaanchemical->no_po],
				['w' => 20, 'text' => $kondisi_text],
				['w' => 20, 'text' => $pemeriksaanchemical->kode_produksi],
				['w' => 20, 'text' => $exp],
				['w' => 12, 'text' => $pemeriksaanchemical->jumlah_barang],
				['w' => 12, 'text' => $pemeriksaanchemical->sampel],
				['w' => 12, 'text' => $pemeriksaanchemical->jumlah_reject],
				['w' => 10, 'text' => ($pemeriksaanchemical->kemasan == 'sesuai') ? '✔' : (($pemeriksaanchemical->kemasan == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 10, 'text' => ($pemeriksaanchemical->warna == 'sesuai') ? '✔' : (($pemeriksaanchemical->warna == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 10, 'text' => $pemeriksaanchemical->ph],
				['w' => 20, 'text' => $pemeriksaanchemical->halal_berlaku],
				['w' => 10, 'text' => $pemeriksaanchemical->segel],
				['w' => 10, 'text' => ($pemeriksaanchemical->coa == 'ada') ? '✔' : (($pemeriksaanchemical->coa == 'tidak ada') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 20, 'text' => $pemeriksaanchemical->penerimaan],
				['w' => 27, 'text' => !empty($pemeriksaanchemical->keterangan) ? $pemeriksaanchemical->keterangan : '-'],
			];

			// Hitung tinggi baris dinamis
			$lineHeight = 6;
			$maxLines = 1;
			foreach ($row_data as $cell) {
				$pdf->SetFont($cell['font'] ?? 'times', '', 8);
				$nb = $pdf->getNumLines($cell['text'], $cell['w']);
				if ($nb > $maxLines) {
					$maxLines = $nb;
				}
			}
			$rowHeight = $lineHeight * $maxLines;

			// Tulis semua kolom pakai MultiCell
			foreach ($row_data as $cell) {
				$pdf->SetFont($cell['font'] ?? 'times', '', 8);
				$pdf->MultiCell($cell['w'], $rowHeight, $cell['text'], 1, 'C', false, 0);
			}

			$pdf->Ln($rowHeight);
			$no++;
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(330, 5, 'QW 04/00', 0, 1, 'R');

		$this->load->model('pegawai_model');
		$data['pemeriksaanchemical']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['pemeriksaanchemical']->username);
		$data['pemeriksaanchemical']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['pemeriksaanchemical']->nama_spv);

		$pdf->SetY($pdf->GetY() + 3);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($pemeriksaanchemical_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 5;
		$status_verifikasi = true;
		foreach ($pemeriksaanchemical_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 9);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;

			// Dibuat oleh (QC)
			$pdf->SetXY(60, $y_verifikasi);
			$pdf->Cell(50, 5, 'Dibuat Oleh,', 0, 0, 'C');
			if (!empty($data['pemeriksaanchemical']->nama_lengkap_qc)) {
				$update_tanggal_qc = !empty($data['pemeriksaanchemical']->created_at)
					? (new DateTime($data['pemeriksaanchemical']->created_at))->format('d-m-Y | H:i')
					: date('d-m-Y | H:i');

				$qr_text_qc = "Dibuat secara digital oleh,\n" .
					$data['pemeriksaanchemical']->nama_lengkap_qc . "\nQC Inspector\n" . $update_tanggal_qc;
				$pdf->write2DBarcode($qr_text_qc, 'QRCODE,L', 78, $y_verifikasi + 5, 15, 15, null, 'N');
				$pdf->SetXY(60, $y_verifikasi + 20);
				$pdf->Cell(50, 5, 'QC Inspector', 0, 0, 'C');
			} else {
				$pdf->SetXY(60, $y_verifikasi + 15);
				$pdf->Cell(50, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}

			// Disetujui oleh (SPV)
			$update_tanggal = (new DateTime($data['pemeriksaanchemical']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['pemeriksaanchemical']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->SetXY(160, $y_verifikasi);
			$pdf->Cell(150, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 227, $y_verifikasi + 5, 16, 16, null, 'N');
			$pdf->SetXY(160, $y_verifikasi + 20);
			$pdf->Cell(150, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 9);
			$pdf->SetXY(200, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$filename = "Penerimaan Chemical_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}
}
