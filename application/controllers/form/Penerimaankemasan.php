<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Penerimaankemasan extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('penerimaankemasan_model');
		$this->load->library('upload');
		$this->load->library('audit_library');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'penerimaankemasan' => $this->penerimaankemasan_model->get_data_by_plant()
		);

		$this->active_nav = 'penerimaankemasan';
		$this->render('form/penerimaankemasan/penerimaankemasan', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'penerimaankemasan' => $this->penerimaankemasan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'penerimaankemasan';
		$this->render('form/penerimaankemasan/penerimaankemasan-detail', $data);
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
		$rules = $this->penerimaankemasan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$file_name = null;
			if (isset($_FILES['bukti_coa']) && $_FILES['bukti_coa']['error'] != 4) {
				$config = array(
					'upload_path'   => "./uploads/penerimaan_kemasan/",
					'allowed_types' => "jpg|png|jpeg|pdf",
					'overwrite'     => TRUE,
					'max_size'      => 2048, // dalam KB
					'encrypt_name'  => TRUE
				);
				$this->upload->initialize($config);

				if (!$this->upload->do_upload('bukti_coa')) {
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error_msg', 'Upload gagal: ' . $error);
					redirect('penerimaankemasan/tambah');
				} else {
					$data = $this->upload->data();
					$file_name = $data['file_name'];

					// === Kompres Gambar Jika Bukan PDF ===
					if ($data['file_ext'] != '.pdf') {
						$this->load->library('image_lib');
						$resize_config = [
							'image_library'  => 'gd2',
							'source_image'   => './uploads/penerimaan_kemasan/' . $file_name,
							'maintain_ratio' => TRUE,
							'quality'        => '70%',
							'width'          => 800,
							'height'         => 800,
						];
						$this->image_lib->initialize($resize_config);
						$this->image_lib->resize();
						$this->image_lib->clear();
					}
				}
			}

			$update = $this->penerimaankemasan_model->insert($file_name);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Penerimaan Kemasan dari Supplier berhasil disimpan');
				redirect('penerimaankemasan');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal disimpan');
				redirect('penerimaankemasan');
			}
		}

		$data = array(
			'penerimaankemasan' => $this->penerimaankemasan_model->get_data_by_plant()
		);

		$this->active_nav = 'penerimaankemasan';
		$this->render('form/penerimaankemasan/penerimaankemasan-tambah');
	}

	public function edit($uuid)
	{
		$penerimaankemasan = $this->penerimaankemasan_model->get_by_uuid($uuid);
		$rules = $this->penerimaankemasan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$config = array(
				'upload_path'   => "./uploads/penerimaan_kemasan/",
				'allowed_types' => "jpg|png|jpeg|pdf",
				'overwrite'     => TRUE,
				'max_size'      => 2048,
				'encrypt_name'  => TRUE
			);
			$this->upload->initialize($config);

			// Cek apakah ada file baru di-upload
			if (!empty($_FILES['bukti_coa']['name'])) {
				if (!$this->upload->do_upload('bukti_coa')) {
					$error = $this->upload->display_errors();
					$this->session->set_flashdata('error_msg', 'Upload gagal: ' . $error);
					redirect('penerimaankemasan/edit/' . $uuid);
				} else {
					$file_data = $this->upload->data();
					$file_name = $file_data['file_name'];

					// === Kompres Gambar Jika Bukan PDF ===
					if ($file_data['file_ext'] != '.pdf') {
						$this->load->library('image_lib');
						$resize_config = [
							'image_library'  => 'gd2',
							'source_image'   => './uploads/penerimaan_kemasan/' . $file_name,
							'maintain_ratio' => TRUE,
							'quality'        => '70%',
							'width'          => 800,
							'height'         => 800,
						];
						$this->image_lib->initialize($resize_config);
						$this->image_lib->resize();
						$this->image_lib->clear();
					}

					// Hapus file lama jika ada
					if (!empty($penerimaankemasan->bukti_coa) && file_exists('./uploads/penerimaan_kemasan/' . $penerimaankemasan->bukti_coa)) {
						unlink('./uploads/penerimaan_kemasan/' . $penerimaankemasan->bukti_coa);
					}
				}
			} else {
				$file_name = $penerimaankemasan->bukti_coa;
			}

			$update = $this->penerimaankemasan_model->update($uuid, $file_name);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil diupdate');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal diupdate');
			}
			redirect('penerimaankemasan');
		} else {
			$data = array(
				'penerimaankemasan' => $penerimaankemasan,
				'validation_errors' => validation_errors()
			);
		}

		$this->active_nav = 'penerimaankemasan';
		$this->render('form/penerimaankemasan/penerimaankemasan-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('penerimaankemasan');
		}

		$deleted = $this->penerimaankemasan_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('penerimaankemasan');
	}

	public function verifikasi()
	{
		$data = array(
			'penerimaankemasan' => $this->penerimaankemasan_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-penerimaankemasan';
		$this->render('form/penerimaankemasan/penerimaankemasan-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->penerimaankemasan_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->penerimaankemasan_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Penerimaan Kemasan dari Supplier berhasil di Update');
				redirect('penerimaankemasan/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Penerimaan Kemasan dari Supplier gagal di Update');
				redirect('penerimaankemasan/verifikasi');
			}
		}

		$data = array(
			'penerimaankemasan' => $this->penerimaankemasan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-penerimaankemasan';
		$this->render('form/penerimaankemasan/penerimaankemasan-status', $data);
	}

	public function copy_to_audit($uuid)
	{
		$data = $this->penerimaankemasan_model->get_by_uuid($uuid);

		if (!$data) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Penerimaan Kemasan tidak ditemukan.'
			);

			redirect('penerimaankemasan/verifikasi');
			return;
		}

		$existing = $this->penerimaankemasan_model
			->get_audit_by_source_uuid($uuid);

		if ($existing) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Penerimaan Kemasan ini sudah dikirim ke Audit.'
			);

			redirect('penerimaankemasan/verifikasi');
			return;
		}

		$result = $this->audit_library->copy_to_audit(
			'penerimaan_kemasan',
			$uuid
		);

		if ($result) {
			$this->session->set_flashdata(
				'success_msg',
				'Data Penerimaan Kemasan berhasil dikirim ke Audit.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Data Penerimaan Kemasan gagal dikirim ke Audit.'
			);
		}

		redirect('penerimaankemasan/verifikasi');
	}

	public function audit()
	{
		$data = array(
			'data' => $this->penerimaankemasan_model->get_audit_by_plant()
		);

		$this->active_nav = 'verifikasi-penerimaankemasan';

		$this->render(
			'form/penerimaankemasan/penerimaankemasan-audit',
			$data
		);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'penerimaankemasan' => $this->penerimaankemasan_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-penerimaankemasan', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/penerimaankemasan/penerimaankemasan-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->penerimaankemasan_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->penerimaankemasan_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Penerimaan Kemasan dari Supplier berhasil di Update');
	// 			redirect('penerimaankemasan/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Penerimaan Kemasan dari Supplier gagal di Update');
	// 			redirect('penerimaankemasan/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'penerimaankemasan' => $this->penerimaankemasan_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-penerimaankemasan');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/penerimaankemasan/penerimaankemasan-statusprod', $data);
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

		$penerimaankemasan_data = $this->penerimaankemasan_model->get_by_date($tanggal, $plant);
		$penerimaankemasan_data_verif = $this->penerimaankemasan_model->get_last_verif_by_date($tanggal, $plant);

		if (!$penerimaankemasan_data || !$penerimaankemasan_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['penerimaankemasan'] = $penerimaankemasan_data_verif;

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
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN PENERIMAAN KEMASAN DARI SUPPLIER', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['penerimaankemasan']->date;
		$date = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $date->getTimestamp());
		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(17);
		$pdf->Write(0, 'Tanggal: ' . $formatted_date);
		$pdf->SetX($pdf->GetX() + 20);
		$pdf->Write(0, 'Shift: ' . $data['penerimaankemasan']->shift);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 7);
		$pdf->Cell(10, 12, 'No.', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Jenis Kemasan', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Pemasok', 1, 0, 'C');
		$pdf->Cell(15, 12, 'Jenis Mobil', 1, 0, 'C');
		$pdf->Cell(15, 12, 'No. Polisi', 1, 0, 'C');
		$pdf->Cell(15, 12, 'Identitas', 1, 0, 'C');
		$pdf->Cell(12, 12, 'No. PO/DO', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Kondisi Mobil', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Kode Produksi', 1, 0, 'C');
		$pdf->Cell(10, 12, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(10, 12, 'Sampel', 1, 0, 'C');
		$pdf->Cell(10, 12, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(81, 6, 'Kondisi Fisik', 1, 0, 'C');
		$pdf->Cell(10, 12, 'Segel', 1, 0, 'C');
		$pdf->Cell(10, 12, 'COA', 1, 0, 'C');
		$pdf->Cell(20, 6, 'Penerimaan', 1, 0, 'C');
		$pdf->Cell(25, 12, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$pdf->Cell(81, 0, '', 0, 0, 'L');
		$pdf->Cell(15, 0, 'Pengantar', 0, 0, 'L');
		$pdf->Cell(51, 0, '', 0, 0, 'L');
		$pdf->Cell(10, 6, 'Datang', 0, 0, 'C');
		$pdf->Cell(10, 6, '(Pcs)', 0, 0, 'C');
		$pdf->Cell(10, 6, 'Reject', 0, 0, 'C');
		$pdf->SetFont('times', '', 7);
		$pdf->Cell(9, 6, 'Warna', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Panjang', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Diameter', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Lebar', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Tinggi', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Berat', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Delaminasi', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Bau', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Desain', 1, 0, 'C');
		$pdf->Cell(20, 6, '', 0, 0, 'C');
		$pdf->Cell(10, 6, 'OK', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Tolak', 1, 0, 'C');
		$pdf->Cell(25, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$no = 1;
		$kondisi_mobil_map = [
			1 => 'Bersih',
			2 => 'Kotor',
			3 => 'Bau',
			4 => 'Bocor',
			5 => 'Basah',
			6 => 'Kering',
			7 => 'Bebas Hama',
		];

		foreach ($penerimaankemasan_data as $penerimaankemasan) {
			$pdf->SetFont('times', '', 8);

			// Buat teks kondisi mobil (mapping kode ke deskripsi)
			$kondisi_arr = explode(',', $penerimaankemasan->kondisi_mobil);
			$kondisi_labels = array_map(function ($val) use ($kondisi_mobil_map) {
				return isset($kondisi_mobil_map[(int)$val]) ? $kondisi_mobil_map[(int)$val] : $val;
			}, $kondisi_arr);
			$kondisi_text = implode(', ', $kondisi_labels);

			// Buat baris data
			$row_data = [
				['w' => 10, 'text' => $no],
				['w' => 20, 'text' => $penerimaankemasan->jenis_kemasan],
				['w' => 20, 'text' => $penerimaankemasan->pemasok],
				['w' => 15, 'text' => $penerimaankemasan->jenis_mobil],
				['w' => 15, 'text' => $penerimaankemasan->no_polisi],
				['w' => 15, 'text' => $penerimaankemasan->identitas_pengantar],
				['w' => 12, 'text' => $penerimaankemasan->no_po],
				['w' => 20, 'text' => $kondisi_text],
				['w' => 20, 'text' => $penerimaankemasan->kode_produksi],
				['w' => 10, 'text' => $penerimaankemasan->jumlah_datang],
				['w' => 10, 'text' => $penerimaankemasan->sampel],
				['w' => 10, 'text' => $penerimaankemasan->jumlah_reject],

				// Simbol centang silang (ganti font ke DejaVu)
				['w' => 9, 'text' => ($penerimaankemasan->warna == 'sesuai') ? '✔' : (($penerimaankemasan->warna == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 9, 'text' => ($penerimaankemasan->panjang == 'sesuai') ? '✔' : (($penerimaankemasan->panjang == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 9, 'text' => ($penerimaankemasan->diameter == 'sesuai') ? '✔' : (($penerimaankemasan->diameter == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 9, 'text' => ($penerimaankemasan->lebar == 'sesuai') ? '✔' : (($penerimaankemasan->lebar == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 9, 'text' => ($penerimaankemasan->tinggi == 'sesuai') ? '✔' : (($penerimaankemasan->tinggi == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 9, 'text' => ($penerimaankemasan->berat == 'sesuai') ? '✔' : (($penerimaankemasan->berat == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 9, 'text' => ($penerimaankemasan->delaminasi == 'sesuai') ? '✔' : (($penerimaankemasan->delaminasi == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 9, 'text' => ($penerimaankemasan->bau == 'sesuai') ? '✔' : (($penerimaankemasan->bau == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 9, 'text' => ($penerimaankemasan->desain == 'sesuai') ? '✔' : (($penerimaankemasan->desain == 'tidak sesuai') ? '✘' : '−'), 'font' => 'dejavusans'],

				['w' => 10, 'text' => $penerimaankemasan->segel],
				['w' => 10, 'text' => ($penerimaankemasan->coa == 'ada') ? '✔' : (($penerimaankemasan->coa == 'tidak ada') ? '✘' : '−'), 'font' => 'dejavusans'],
				['w' => 20, 'text' => $penerimaankemasan->penerimaan],
				['w' => 25, 'text' => !empty($penerimaankemasan->keterangan) ? $penerimaankemasan->keterangan : '-'],
			];

			// Hitung tinggi baris berdasarkan isi terpanjang
			$lineHeight = 6;
			$maxLines = 1;
			foreach ($row_data as $cell) {
				$pdf->SetFont(isset($cell['font']) ? $cell['font'] : 'times', '', 8);
				$nb = $pdf->getNumLines($cell['text'], $cell['w']);
				if ($nb > $maxLines) {
					$maxLines = $nb;
				}
			}
			$rowHeight = $lineHeight * $maxLines;

			// Simpan posisi awal
			$startX = $pdf->GetX();
			$startY = $pdf->GetY();

			// Cetak semua cell
			foreach ($row_data as $cell) {
				$pdf->SetFont(isset($cell['font']) ? $cell['font'] : 'times', '', 8);
				$pdf->MultiCell($cell['w'], $rowHeight, $cell['text'], 1, 'C', false, 0);
			}

			$pdf->Ln($rowHeight);
			$no++;
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(330, 5, 'QW 03/00', 0, 1, 'R');

		$pdf->SetY($pdf->GetY() + 3);
		$pdf->SetFont('dejavusans', '', 6);
		$pdf->MultiCell(0, 10, "Keterangan : ✔ Sesuai Standar", 0, 'L');

		$this->load->model('pegawai_model');
		$data['penerimaankemasan']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['penerimaankemasan']->username);
		$data['penerimaankemasan']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['penerimaankemasan']->nama_spv);

		$y_after_keterangan = $pdf->GetY();
		$status_verifikasi = true;
		foreach ($penerimaankemasan_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 9);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;

			$pdf->SetXY(60, $y_verifikasi);
			$pdf->Cell(50, 5, 'Dibuat Oleh,', 0, 0, 'C');
			if (!empty($data['penerimaankemasan']->nama_lengkap_qc)) {
				$update_tanggal_qc = !empty($data['penerimaankemasan']->created_at)
					? (new DateTime($data['penerimaankemasan']->created_at))->format('d-m-Y | H:i')
					: date('d-m-Y | H:i');

				$qr_text_qc = "Dibuat secara digital oleh,\n" .
					$data['penerimaankemasan']->nama_lengkap_qc . "\nQC Inspector\n" . $update_tanggal_qc;
				$pdf->write2DBarcode($qr_text_qc, 'QRCODE,L', 78, $y_verifikasi + 5, 15, 15, null, 'N');
				$pdf->SetXY(60, $y_verifikasi + 20);
				$pdf->Cell(50, 5, 'QC Inspector', 0, 0, 'C');
			} else {
				$pdf->SetXY(60, $y_verifikasi + 15);
				$pdf->Cell(50, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}

			// Disetujui oleh (SPV)
			$update_tanggal = (new DateTime($data['penerimaankemasan']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['penerimaankemasan']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
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
		$filename = "Penerimaan Kemasan_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}

	public function cetak_audit()
	{
		$tanggal = $this->input->post('tanggal');

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$penerimaankemasan_data =
			$this->penerimaankemasan_model->get_audit_by_date($tanggal, $plant);

		if (empty($penerimaankemasan_data)) {
			show_error('Data audit tidak ditemukan, pilih tanggal yang ingin dicetak', 404);
		}

		$data['penerimaankemasan'] = $penerimaankemasan_data[0];

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

		$pdf->MultiCell(
			0,
			5,
			'PEMERIKSAAN PENERIMAAN KEMASAN DARI SUPPLIER - AUDIT',
			0,
			'C'
		);

		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');

		$tanggal_data = $data['penerimaankemasan']->date;
		$date = new DateTime($tanggal_data);

		$formatted_date = date('l, d F Y', $date->getTimestamp());
		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(17);
		$pdf->Write(0, 'Tanggal: ' . $formatted_date);

		$pdf->SetX($pdf->GetX() + 20);
		$pdf->Write(0, 'Shift: ' . $data['penerimaankemasan']->shift);

		$pdf->Ln(5);

		$pdf->SetFont('times', '', 7);

		$pdf->Cell(10, 12, 'No.', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Jenis Kemasan', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Pemasok', 1, 0, 'C');
		$pdf->Cell(15, 12, 'Jenis Mobil', 1, 0, 'C');
		$pdf->Cell(15, 12, 'No. Polisi', 1, 0, 'C');
		$pdf->Cell(15, 12, 'Identitas', 1, 0, 'C');
		$pdf->Cell(12, 12, 'No. PO/DO', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Kondisi Mobil', 1, 0, 'C');
		$pdf->Cell(20, 12, 'Kode Produksi', 1, 0, 'C');
		$pdf->Cell(10, 12, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(10, 12, 'Sampel', 1, 0, 'C');
		$pdf->Cell(10, 12, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(81, 6, 'Kondisi Fisik', 1, 0, 'C');
		$pdf->Cell(10, 12, 'Segel', 1, 0, 'C');
		$pdf->Cell(10, 12, 'COA', 1, 0, 'C');
		$pdf->Cell(20, 6, 'Penerimaan', 1, 0, 'C');
		$pdf->Cell(25, 12, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$pdf->Cell(81, 0, '', 0, 0, 'L');
		$pdf->Cell(15, 0, 'Pengantar', 0, 0, 'L');
		$pdf->Cell(51, 0, '', 0, 0, 'L');
		$pdf->Cell(10, 6, 'Datang', 0, 0, 'C');
		$pdf->Cell(10, 6, '(Pcs)', 0, 0, 'C');
		$pdf->Cell(10, 6, 'Reject', 0, 0, 'C');

		$pdf->SetFont('times', '', 7);

		$pdf->Cell(9, 6, 'Warna', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Panjang', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Diameter', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Lebar', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Tinggi', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Berat', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Delaminasi', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Bau', 1, 0, 'C');
		$pdf->Cell(9, 6, 'Desain', 1, 0, 'C');
		$pdf->Cell(20, 6, '', 0, 0, 'C');
		$pdf->Cell(10, 6, 'OK', 1, 0, 'C');
		$pdf->Cell(10, 6, 'Tolak', 1, 0, 'C');
		$pdf->Cell(25, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 6, '', 0, 1, 'C');

		$no = 1;

		$kondisi_mobil_map = [
			1 => 'Bersih',
			2 => 'Kotor',
			3 => 'Bau',
			4 => 'Bocor',
			5 => 'Basah',
			6 => 'Kering',
			7 => 'Bebas Hama',
		];

		foreach ($penerimaankemasan_data as $penerimaankemasan) {

			$pdf->SetFont('times', '', 8);

			$kondisi_arr = explode(',', $penerimaankemasan->kondisi_mobil);

			$kondisi_labels = array_map(function ($val) use ($kondisi_mobil_map) {
				return isset($kondisi_mobil_map[(int)$val])
					? $kondisi_mobil_map[(int)$val]
					: $val;
			}, $kondisi_arr);

			$kondisi_text = implode(', ', $kondisi_labels);

			$row_data = [
				['w' => 10, 'text' => $no],
				['w' => 20, 'text' => $penerimaankemasan->jenis_kemasan],
				['w' => 20, 'text' => $penerimaankemasan->pemasok],
				['w' => 15, 'text' => $penerimaankemasan->jenis_mobil],
				['w' => 15, 'text' => $penerimaankemasan->no_polisi],
				['w' => 15, 'text' => $penerimaankemasan->identitas_pengantar],
				['w' => 12, 'text' => $penerimaankemasan->no_po],
				['w' => 20, 'text' => $kondisi_text],
				['w' => 20, 'text' => $penerimaankemasan->kode_produksi],
				['w' => 10, 'text' => $penerimaankemasan->jumlah_datang],
				['w' => 10, 'text' => $penerimaankemasan->sampel],
				['w' => 10, 'text' => $penerimaankemasan->jumlah_reject],

				[
					'w' => 9,
					'text' => ($penerimaankemasan->warna == 'sesuai')
						? '✔'
						: (($penerimaankemasan->warna == 'tidak sesuai') ? '✘' : '−'),
					'font' => 'dejavusans'
				],

				[
					'w' => 9,
					'text' => ($penerimaankemasan->panjang == 'sesuai')
						? '✔'
						: (($penerimaankemasan->panjang == 'tidak sesuai') ? '✘' : '−'),
					'font' => 'dejavusans'
				],

				[
					'w' => 9,
					'text' => ($penerimaankemasan->diameter == 'sesuai')
						? '✔'
						: (($penerimaankemasan->diameter == 'tidak sesuai') ? '✘' : '−'),
					'font' => 'dejavusans'
				],

				[
					'w' => 9,
					'text' => ($penerimaankemasan->lebar == 'sesuai')
						? '✔'
						: (($penerimaankemasan->lebar == 'tidak sesuai') ? '✘' : '−'),
					'font' => 'dejavusans'
				],

				[
					'w' => 9,
					'text' => ($penerimaankemasan->tinggi == 'sesuai')
						? '✔'
						: (($penerimaankemasan->tinggi == 'tidak sesuai') ? '✘' : '−'),
					'font' => 'dejavusans'
				],

				[
					'w' => 9,
					'text' => ($penerimaankemasan->berat == 'sesuai')
						? '✔'
						: (($penerimaankemasan->berat == 'tidak sesuai') ? '✘' : '−'),
					'font' => 'dejavusans'
				],

				[
					'w' => 9,
					'text' => ($penerimaankemasan->delaminasi == 'sesuai')
						? '✔'
						: (($penerimaankemasan->delaminasi == 'tidak sesuai') ? '✘' : '−'),
					'font' => 'dejavusans'
				],

				[
					'w' => 9,
					'text' => ($penerimaankemasan->bau == 'sesuai')
						? '✔'
						: (($penerimaankemasan->bau == 'tidak sesuai') ? '✘' : '−'),
					'font' => 'dejavusans'
				],

				[
					'w' => 9,
					'text' => ($penerimaankemasan->desain == 'sesuai')
						? '✔'
						: (($penerimaankemasan->desain == 'tidak sesuai') ? '✘' : '−'),
					'font' => 'dejavusans'
				],

				['w' => 10, 'text' => $penerimaankemasan->segel],

				[
					'w' => 10,
					'text' => ($penerimaankemasan->coa == 'ada')
						? '✔'
						: (($penerimaankemasan->coa == 'tidak ada') ? '✘' : '−'),
					'font' => 'dejavusans'
				],

				['w' => 20, 'text' => $penerimaankemasan->penerimaan],

				[
					'w' => 25,
					'text' =>
					!empty($penerimaankemasan->keterangan)
						? $penerimaankemasan->keterangan
						: '-'
				],
			];

			$lineHeight = 6;
			$maxLines = 1;

			foreach ($row_data as $cell) {
				$pdf->SetFont(
					isset($cell['font']) ? $cell['font'] : 'times',
					'',
					8
				);

				$nb = $pdf->getNumLines($cell['text'], $cell['w']);

				if ($nb > $maxLines) {
					$maxLines = $nb;
				}
			}

			$rowHeight = $lineHeight * $maxLines;

			foreach ($row_data as $cell) {
				$pdf->SetFont(
					isset($cell['font']) ? $cell['font'] : 'times',
					'',
					8
				);

				$pdf->MultiCell(
					$cell['w'],
					$rowHeight,
					$cell['text'],
					1,
					'C',
					false,
					0
				);
			}

			$pdf->Ln($rowHeight);

			$no++;
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(330, 5, 'QW 03/00', 0, 1, 'R');

		$pdf->SetY($pdf->GetY() + 3);

		$pdf->SetFont('dejavusans', '', 6);
		$pdf->MultiCell(
			0,
			10,
			"Keterangan : ✔ Sesuai Standar",
			0,
			'L'
		);

		$this->load->model('pegawai_model');

		$data['penerimaankemasan']->nama_lengkap_qc =
			$this->pegawai_model->get_nama_lengkap(
				$data['penerimaankemasan']->username
			);

		$data['penerimaankemasan']->nama_lengkap_spv =
			$this->pegawai_model->get_nama_lengkap(
				$data['penerimaankemasan']->nama_spv
			);

		$y_after_keterangan = $pdf->GetY();

		$status_verifikasi = true;

		foreach ($penerimaankemasan_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 9);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {

			$y_verifikasi = $y_after_keterangan;

			$pdf->SetXY(60, $y_verifikasi);
			$pdf->Cell(50, 5, 'Dibuat Oleh,', 0, 0, 'C');

			if (!empty($data['penerimaankemasan']->nama_lengkap_qc)) {

				$update_tanggal_qc = !empty($data['penerimaankemasan']->created_at)
					? (new DateTime(
						$data['penerimaankemasan']->created_at
					))->format('d-m-Y | H:i')
					: date('d-m-Y | H:i');

				$qr_text_qc =
					"Dibuat secara digital oleh,\n" .
					$data['penerimaankemasan']->nama_lengkap_qc .
					"\nQC Inspector\n" .
					$update_tanggal_qc;

				$pdf->write2DBarcode(
					$qr_text_qc,
					'QRCODE,L',
					78,
					$y_verifikasi + 5,
					15,
					15,
					null,
					'N'
				);

				$pdf->SetXY(60, $y_verifikasi + 20);
				$pdf->Cell(50, 5, 'QC Inspector', 0, 0, 'C');
			} else {

				$pdf->SetXY(60, $y_verifikasi + 15);
				$pdf->Cell(
					50,
					5,
					'Belum Diverifikasi',
					0,
					0,
					'C'
				);
			}

			// Disetujui oleh SPV
			$update_tanggal = !empty($data['penerimaankemasan']->tgl_update_spv)
				? (new DateTime(
					$data['penerimaankemasan']->tgl_update_spv
				))->format('d-m-Y | H:i')
				: date('d-m-Y | H:i');

			$qr_text =
				"Diverifikasi secara digital oleh,\n" .
				$data['penerimaankemasan']->nama_lengkap_spv .
				"\nSPV QC Bread Crumb\n" .
				$update_tanggal;

			$pdf->SetXY(160, $y_verifikasi);
			$pdf->Cell(
				150,
				5,
				'Disetujui Oleh,',
				0,
				0,
				'C'
			);

			$pdf->write2DBarcode(
				$qr_text,
				'QRCODE,L',
				227,
				$y_verifikasi + 5,
				16,
				16,
				null,
				'N'
			);

			$pdf->SetXY(160, $y_verifikasi + 20);
			$pdf->Cell(
				150,
				5,
				'Supervisor QC',
				0,
				0,
				'C'
			);
		} else {

			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 9);

			$pdf->SetXY(200, $y_after_keterangan);
			$pdf->Cell(
				80,
				5,
				'Data Belum Diverifikasi',
				0,
				0,
				'C'
			);
		}

		$pdf->setPrintFooter(false);

		$filename =
			"Penerimaan_Kemasan_Audit_{$formatted_date2}.pdf";

		$pdf->Output($filename, 'I');
	}
}
