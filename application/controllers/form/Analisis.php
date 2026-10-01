<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Analisis extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('auth_model');
		$this->load->model('analisis_model');
		$this->load->library('audit_library');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'analisis' => $this->analisis_model->get_data_by_plant(),
		);

		$this->active_nav = 'analisis';
		$this->render('form/analisis/analisis', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'analisis' => $this->analisis_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'analisis';
		$this->render('form/analisis/analisis-detail', $data);
	}

	public function tambah()
	{

		$rules = $this->analisis_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->analisis_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Laporan analisis Finish Good berhasil di simpan');
				redirect('analisis');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Laporan analisis Finish Good gagal di simpan');
				redirect('analisis');
			}
		}

		$this->active_nav = 'analisis';
		$this->render('form/analisis/analisis-tambah');
	}


	public function edit($uuid)
	{
		$rules = $this->analisis_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$update = $this->analisis_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Permohonan Analisis Sampel Laboratorium berhasil diupdate.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Permohonan Analisis Sampel Laboratorium gagal diupdate.');
			}

			redirect('analisis');
		}

		$data = [
			'analisis' => $this->analisis_model->get_by_uuid($uuid),
			'bagian_list' => $this->analisis_model->get_bagian_by_uuid($uuid)
		];

		$this->active_nav = 'analisis';
		$this->render('form/analisis/analisis-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('analisis');
		}

		$deleted = $this->analisis_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('analisis');
	}

	public function analis($uuid)
	{
		// Validasi hanya untuk field lain, misal catatan saja
		$this->form_validation->set_rules('catatan', 'Catatan', 'trim');

		if ($this->form_validation->run() == TRUE) {
			$update = $this->analisis_model->analysis($uuid);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil diupdate');
				redirect('analisis');
			} else {
				$this->session->set_flashdata('error_msg', 'Data gagal diupdate');
				redirect('analisis');
			}
		}

		$data = [
			'analisis' => $this->analisis_model->get_by_uuid($uuid),
			'larutan' => $this->analisis_model->get_by_uuid($uuid)
		];

		$this->active_nav = 'analisis';
		$this->render('form/analisis/analisis-analis', $data);
	}

	public function check_analisis($analisis)
	{
		if (!is_array($analisis)) {
			$this->form_validation->set_message('check_analisis', 'Pilih minimal satu analisis atau isi bagian lainnya.');
			return false;
		}

		foreach ($analisis as $value) {
			if (trim($value) !== '') {
				return true;
			}
		}

		$this->form_validation->set_message('check_analisis', 'Pilih minimal satu analisis atau isi bagian lainnya.');
		return false;
	}

	public function ajax_detail($uuid)
	{
		$data['analisis'] = $this->analisis_model->get_by_uuid($uuid);

		if (!$data['analisis']) {
			show_404();
		}

		$this->load->view('form/analisis/ajax-detail', $data);
	}

	public function verifikasi()
	{
		$data = array(
			'analisis' => $this->analisis_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-analisis';
		$this->render('form/analisis/analisis-verifikasi', $data);
	}


	public function status($uuid)
	{
		$rules = $this->analisis_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->analisis_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Permohonan Analisis Sampel Laboratorium berhasil di Update');
				redirect('analisis/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Permohonan Analisis Sampel Laboratorium gagal di Update');
				redirect('analisis/verifikasi');
			}
		}

		$data = array(
			'analisis' => $this->analisis_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-analisis';
		$this->render('form/analisis/analisis-status', $data);
	}

	public function copy_to_audit($uuid)
	{
		$source = $this->analisis_model->get_by_uuid($uuid);

		if (!$source) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Analisis Lab tidak ditemukan.'
			);

			redirect('analisis/verifikasi');
			return;
		}

		$existing = $this->analisis_model
			->get_audit_by_source_uuid($uuid);

		if ($existing) {
			$this->session->set_flashdata(
				'error_msg',
				'Data ini sudah dikirim ke Audit.'
			);

			redirect('analisis/verifikasi');
			return;
		}

		$result = $this->audit_library->copy_to_audit(
			'analisis_lab',
			$uuid
		);

		if ($result) {
			$this->session->set_flashdata(
				'success_msg',
				'Data Analisis Lab berhasil dikirim ke Audit.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Data Analisis Lab gagal dikirim ke Audit.'
			);
		}

		redirect('analisis/verifikasi');
	}

	public function audit()
	{
		$data = array(
			'data' => $this->analisis_model->get_audit_by_plant()
		);

		$this->active_nav = 'verifikasi-analisis';

		$this->render(
			'form/analisis/analisis-audit',
			$data
		);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'analisis' => $this->analisis_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-analisis', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/analisis/analisis-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->analisis_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->analisis_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Permohonan Analisis Sampel Laboratorium berhasil di Update');
	// 			redirect('analisis/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Permohonan Analisis Sampel Laboratorium gagal di Update');
	// 			redirect('analisis/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'analisis' => $this->analisis_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-analisis');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/analisis/analisis-statusprod', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function diterima()
	{
		$data = array(
			'analisis' => $this->analisis_model->get_data_by_plant(),
		);

		$this->active_nav = 'diterima-analisis';
		$this->render('form/analisis/analisis-diterima', $data);
	}


	public function statuslab($uuid)
	{
		$rules = $this->analisis_model->rules_diterima();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->analisis_model->diterima_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Status Permohonan Analisis Sampel Laboratorium berhasil di Update');
				redirect('analisis/diterima');
			} else {
				$this->session->set_flashdata('error_msg', 'Status Permohonan Analisis Sampel Laboratorium gagal di Update');
				redirect('analisis/diterima');
			}
		}

		$data = array(
			'analisis' => $this->analisis_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'diterima-analisis';
		$this->render('form/analisis/analisis-statuslab', $data);
	}

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal');

		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$analisis_data = $this->analisis_model->get_by_date($tanggal, $plant);
		$analisis_data_verif = $this->analisis_model->get_last_verif_by_date($tanggal, $plant);

		if (!$analisis_data || !$analisis_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['analisis'] = $analisis_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 14, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 10);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 38);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(7, "\n");
		$pdf->MultiCell(0, 5, 'PERMOHONAN ANALISIS SAMPEL LABORATORIUM', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['analisis']->date;
		$date = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $date->getTimestamp());

		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 8);
		$pdf->SetX(10);

		$label_width = 35;
		$value_width = 80;

		$pdf->Cell($label_width, 4, 'Tanggal Sampling', 0, 0);
		$pdf->Cell(2, 4, ':', 0, 0);
		$pdf->Cell($value_width, 4, $formatted_date, 0, 1);
		$pdf->Cell($label_width, 4, 'Tipe Sampel', 0, 0);
		$pdf->Cell(2, 4, ':', 0, 0);
		$pdf->Cell($value_width, 4, $data['analisis']->tipe_sampel, 0, 1);
		$pdf->Cell($label_width, 4, 'Penyimpanan', 0, 0);
		$pdf->Cell(2, 4, ':', 0, 0);
		$pdf->Cell($value_width, 4, $data['analisis']->penyimpanan, 0, 1);
		$pdf->Ln(1);
		$pdf->SetFont('times', '', 9);

		$pdf->Cell(10, 10, 'No.', 1, 0, 'C');
		$pdf->Cell(25, 10, 'Nama Produk', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Kode Produksi', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Best Before', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(20, 10, 'QC', 1, 0, 'C');
		$pdf->Cell(75, 5, 'Permintaan Analisis', 1, 0, 'C');
		$pdf->Cell(5, 5, '', 0, 1, 'C');

		$pdf->Cell(75, 10, '', 0, 0, 'L');
		$pdf->Cell(20, 5, 'Sampel (g)', 0, 0, 'C');
		$pdf->Cell(20, 5, '', 0, 0, 'C');
		$pdf->Cell(25, 5, 'Kimia', 1, 0, 'C');
		$pdf->Cell(25, 5, 'Mikrobiologi', 1, 0, 'C');
		$pdf->Cell(25, 5, 'Lainnya', 1, 0, 'C');
		$pdf->Cell(5, 0, '', 0, 0, 'C');
		$pdf->Cell(5, 5, '', 0, 1, 'C');

		$no = 1;
		$pdf->SetFont('times', '', 7);
		foreach ($analisis_data as $analisis) {
			$created_date = (new DateTime($analisis->date))->format('d m Y');
			$bb = (new DateTime($analisis->best_before))->format('d m Y');
			$pdf->Cell(10, 5, $no++, 1, 0, 'C');
			$pdf->Cell(25, 5, $analisis->nama_produk, 1, 0, 'C');
			$pdf->Cell(20, 5, $analisis->kode_produksi, 1, 0, 'C');
			$pdf->Cell(20, 5, $bb, 1, 0, 'C');
			$pdf->Cell(20, 5, $analisis->jumlah_sampel, 1, 0, 'C');
			$pdf->Cell(20, 5, $analisis->username, 1, 0, 'C');

			// $mapping = [
			// 	'1' => 'Moisture',
			// 	'2' => 'Salinity',
			// 	'3' => 'pH',
			// 	'4' => 'Bulk Density',
			// 	'5' => 'TPC',
			// 	'6' => 'Enterobacter',
			// 	'7' => 'Salmonella',
			// 	'8' => 'Yeast and Mold',
			// ];
			$analisis1 = [];
			$analisis2 = [];
			$analisis3 = [];
			$nilai_analisis = explode(',', $analisis->analisis);

			foreach ($nilai_analisis as $nilai) {
				$nilai = trim($nilai);
				if (is_numeric($nilai)) {
					if ($nilai >= 1 && $nilai <= 4) {
						$analisis1[] = $nilai;
					} elseif ($nilai >= 5 && $nilai <= 8) {
						$analisis2[] = $nilai;
					} else {
						$analisis3[] = $nilai;
					}
				} else {
					$analisis3[] = $nilai;
				}
			}

			$pdf->Cell(25, 5, implode(', ', $analisis1), 1, 0, 'C');
			$pdf->Cell(25, 5, implode(', ', $analisis2), 1, 0, 'C');
			$pdf->Cell(25, 5, implode(', ', $analisis3), 1, 0, 'C');
			$pdf->Ln();
		}

		// $pdf->SetFont('times', 'I', 7);
		// $pdf->Cell(190, 5, 'QW 18/00', 0, 1, 'R'); 

		$pdf->SetY($pdf->GetY() + 3);
		$pdf->SetFont('dejavusans', '', 6);
		$pdf->MultiCell(0, 10, "Keterangan :\nKimia: 1. Moisture 2. Salinity 3. pH 4. Bulk Density\nMikrobiologi: 5. TPC 6. Enterobacter 7. Salmonella 8. Yeast & Mold", 0, 'L');

		$this->load->model('pegawai_model');
		$data['analisis']->nama_lengkap_lab = $this->pegawai_model->get_nama_lengkap($data['analisis']->nama_lab);
		$data['analisis']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['analisis']->nama_spv);
		$data['analisis']->nama_lengkap_produksi = $data['analisis']->nama_produksi;

		$pdf->SetY($pdf->GetY());
		$pdf->SetFont('times', '', 7);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($analisis_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY();
		$status_verifikasi = true;
		foreach ($analisis_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;

			// Diketahui oleh (Produksi)
			$pdf->SetXY(30, $y_verifikasi + 5);
			$pdf->Cell(45, 5, 'Diketahui Oleh,', 0, 0, 'C');
			if ($data['analisis']->status_produksi == 1 && !empty($data['analisis']->nama_produksi)) {
				$update_tanggal_produksi = (new DateTime($data['analisis']->tgl_update_produksi))->format('d-m-Y | H:i');
				$qr_text_produksi = "Diketahui secara digital oleh,\n" . $data['analisis']->nama_produksi . "\nForeman/Forelady Produksi\n" . $update_tanggal_produksi;
				$pdf->write2DBarcode($qr_text_produksi, 'QRCODE,L', 45, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(30, $y_verifikasi + 25);
				$pdf->Cell(45, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
			} else {
				$pdf->SetXY(30, $y_verifikasi + 10);
				$pdf->Cell(45, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}

			// Disetujui oleh (SPV)
			$pdf->SetXY(95, $y_verifikasi + 5);
			$pdf->Cell(40, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$update_tanggal = (new DateTime($data['analisis']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text_spv = "Diverifikasi secara digital oleh,\n" . $data['analisis']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text_spv, 'QRCODE,L', 107, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(95, $y_verifikasi + 25);
			$pdf->Cell(40, 5, 'Supervisor QC', 0, 0, 'C');

			// Dibuat oleh (Lab) jika catatan_lab == "Sesuai"
			if (strtolower(trim($data['analisis']->catatan_lab)) == 'sesuai') {
				$pdf->SetXY(160, $y_verifikasi + 5);
				$pdf->Cell(30, 5, 'Diterima Oleh,', 0, 0, 'C');
				$update_tanggal_lab = (new DateTime($data['analisis']->tgl_update_lab))->format('d-m-Y | H:i');
				$qr_text_lab = "Diterima secara digital oleh,\n" . $data['analisis']->nama_lengkap_lab . "\nAnalis Laboratorium\n" . $update_tanggal_lab;
				$pdf->write2DBarcode($qr_text_lab, 'QRCODE,L', 168, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(160, $y_verifikasi + 25);
				$pdf->Cell(30, 5, 'Analis Laboratorium', 0, 0, 'C');
			}
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(100, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$filename = "Permohonan Analisis Sampel Laboratorium_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}

	public function cetak_audit()
	{
		$tanggal = $this->input->post('tanggal');

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$analisis_data =
			$this->analisis_model->get_audit_by_date($tanggal, $plant);

		if (empty($analisis_data)) {
			show_error('Data audit tidak ditemukan, pilih tanggal yang ingin dicetak', 404);
		}

		$data['analisis'] = $analisis_data[0];

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
		$pdf->SetMargins(10, 14, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 10);

		$logo_path = FCPATH . 'assets/img/logo.jpg';

		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 38);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(7, "\n");

		$pdf->MultiCell(
			0,
			5,
			'PERMOHONAN ANALISIS SAMPEL LABORATORIUM - AUDIT',
			0,
			'C'
		);

		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');

		$tanggal_data = $data['analisis']->date;
		$date = new DateTime($tanggal_data);

		$formatted_date = date('l, d F Y', $date->getTimestamp());
		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 8);
		$pdf->SetX(10);

		$label_width = 35;
		$value_width = 80;

		$pdf->Cell($label_width, 4, 'Tanggal Sampling', 0, 0);
		$pdf->Cell(2, 4, ':', 0, 0);
		$pdf->Cell($value_width, 4, $formatted_date, 0, 1);

		$pdf->Cell($label_width, 4, 'Tipe Sampel', 0, 0);
		$pdf->Cell(2, 4, ':', 0, 0);
		$pdf->Cell(
			$value_width,
			4,
			$data['analisis']->tipe_sampel,
			0,
			1
		);

		$pdf->Cell($label_width, 4, 'Penyimpanan', 0, 0);
		$pdf->Cell(2, 4, ':', 0, 0);
		$pdf->Cell(
			$value_width,
			4,
			$data['analisis']->penyimpanan,
			0,
			1
		);

		$pdf->Ln(1);
		$pdf->SetFont('times', '', 9);

		$pdf->Cell(10, 10, 'No.', 1, 0, 'C');
		$pdf->Cell(25, 10, 'Nama Produk', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Kode Produksi', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Best Before', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(20, 10, 'QC', 1, 0, 'C');
		$pdf->Cell(75, 5, 'Permintaan Analisis', 1, 0, 'C');
		$pdf->Cell(5, 5, '', 0, 1, 'C');

		$pdf->Cell(75, 10, '', 0, 0, 'L');
		$pdf->Cell(20, 5, 'Sampel (g)', 0, 0, 'C');
		$pdf->Cell(20, 5, '', 0, 0, 'C');
		$pdf->Cell(25, 5, 'Kimia', 1, 0, 'C');
		$pdf->Cell(25, 5, 'Mikrobiologi', 1, 0, 'C');
		$pdf->Cell(25, 5, 'Lainnya', 1, 0, 'C');
		$pdf->Cell(5, 0, '', 0, 0, 'C');
		$pdf->Cell(5, 5, '', 0, 1, 'C');

		$no = 1;

		$pdf->SetFont('times', '', 7);

		foreach ($analisis_data as $analisis) {

			$bb = !empty($analisis->best_before)
				? (new DateTime($analisis->best_before))->format('d m Y')
				: '-';

			$pdf->Cell(10, 5, $no++, 1, 0, 'C');
			$pdf->Cell(25, 5, $analisis->nama_produk, 1, 0, 'C');
			$pdf->Cell(20, 5, $analisis->kode_produksi, 1, 0, 'C');
			$pdf->Cell(20, 5, $bb, 1, 0, 'C');
			$pdf->Cell(20, 5, $analisis->jumlah_sampel, 1, 0, 'C');
			$pdf->Cell(20, 5, $analisis->username, 1, 0, 'C');

			$analisis1 = [];
			$analisis2 = [];
			$analisis3 = [];

			$nilai_analisis = explode(',', $analisis->analisis);

			foreach ($nilai_analisis as $nilai) {

				$nilai = trim($nilai);

				if (is_numeric($nilai)) {

					if ($nilai >= 1 && $nilai <= 4) {
						$analisis1[] = $nilai;
					} elseif ($nilai >= 5 && $nilai <= 8) {
						$analisis2[] = $nilai;
					} else {
						$analisis3[] = $nilai;
					}
				} else {

					$analisis3[] = $nilai;
				}
			}

			$pdf->Cell(
				25,
				5,
				implode(', ', $analisis1),
				1,
				0,
				'C'
			);

			$pdf->Cell(
				25,
				5,
				implode(', ', $analisis2),
				1,
				0,
				'C'
			);

			$pdf->Cell(
				25,
				5,
				implode(', ', $analisis3),
				1,
				0,
				'C'
			);

			$pdf->Ln();
		}

		$pdf->SetY($pdf->GetY() + 3);

		$pdf->SetFont('dejavusans', '', 6);

		$pdf->MultiCell(
			0,
			10,
			"Keterangan :\n" .
				"Kimia: 1. Moisture 2. Salinity 3. pH 4. Bulk Density\n" .
				"Mikrobiologi: 5. TPC 6. Enterobacter 7. Salmonella 8. Yeast & Mold",
			0,
			'L'
		);

		$this->load->model('pegawai_model');

		$data['analisis']->nama_lengkap_lab =
			$this->pegawai_model->get_nama_lengkap(
				$data['analisis']->nama_lab
			);

		$data['analisis']->nama_lengkap_spv =
			$this->pegawai_model->get_nama_lengkap(
				$data['analisis']->nama_spv
			);

		$data['analisis']->nama_lengkap_produksi =
			$data['analisis']->nama_produksi;

		$pdf->SetY($pdf->GetY());
		$pdf->SetFont('times', '', 7);

		$pdf->Cell(
			5,
			3,
			'Catatan : ',
			0,
			1,
			'L'
		);

		foreach ($analisis_data as $item) {

			if (!empty($item->catatan)) {

				$pdf->Cell(
					10,
					0,
					'',
					0,
					0,
					'L'
				);

				$pdf->Cell(
					200,
					0,
					' - ' . $item->catatan,
					0,
					1,
					'L'
				);
			}
		}

		$y_after_keterangan = $pdf->GetY();

		$status_verifikasi = true;

		foreach ($analisis_data as $item) {

			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {

			$y_verifikasi = $y_after_keterangan;

			// Diketahui oleh Produksi
			$pdf->SetXY(30, $y_verifikasi + 5);

			$pdf->Cell(
				45,
				5,
				'Diketahui Oleh,',
				0,
				0,
				'C'
			);

			if (
				$data['analisis']->status_produksi == 1 &&
				!empty($data['analisis']->nama_produksi)
			) {

				$update_tanggal_produksi =
					!empty($data['analisis']->tgl_update_produksi)
					? (new DateTime(
						$data['analisis']->tgl_update_produksi
					))->format('d-m-Y | H:i')
					: date('d-m-Y | H:i');

				$qr_text_produksi =
					"Diketahui secara digital oleh,\n" .
					$data['analisis']->nama_produksi .
					"\nForeman/Forelady Produksi\n" .
					$update_tanggal_produksi;

				$pdf->write2DBarcode(
					$qr_text_produksi,
					'QRCODE,L',
					45,
					$y_verifikasi + 10,
					15,
					15,
					null,
					'N'
				);

				$pdf->SetXY(30, $y_verifikasi + 25);

				$pdf->Cell(
					45,
					5,
					'Foreman/Forelady Produksi',
					0,
					0,
					'C'
				);
			} else {

				$pdf->SetXY(30, $y_verifikasi + 10);

				$pdf->Cell(
					45,
					5,
					'Belum Diverifikasi',
					0,
					0,
					'C'
				);
			}

			// Disetujui oleh SPV
			$pdf->SetXY(95, $y_verifikasi + 5);

			$pdf->Cell(
				40,
				5,
				'Disetujui Oleh,',
				0,
				0,
				'C'
			);

			$update_tanggal =
				!empty($data['analisis']->tgl_update_spv)
				? (new DateTime(
					$data['analisis']->tgl_update_spv
				))->format('d-m-Y | H:i')
				: date('d-m-Y | H:i');

			$qr_text_spv =
				"Diverifikasi secara digital oleh,\n" .
				$data['analisis']->nama_lengkap_spv .
				"\nSPV QC Bread Crumb\n" .
				$update_tanggal;

			$pdf->write2DBarcode(
				$qr_text_spv,
				'QRCODE,L',
				107,
				$y_verifikasi + 10,
				15,
				15,
				null,
				'N'
			);

			$pdf->SetXY(95, $y_verifikasi + 25);

			$pdf->Cell(
				40,
				5,
				'Supervisor QC',
				0,
				0,
				'C'
			);

			// Diterima oleh Lab
			if (
				strtolower(trim($data['analisis']->catatan_lab)) == 'sesuai'
			) {

				$pdf->SetXY(160, $y_verifikasi + 5);

				$pdf->Cell(
					30,
					5,
					'Diterima Oleh,',
					0,
					0,
					'C'
				);

				$update_tanggal_lab =
					!empty($data['analisis']->tgl_update_lab)
					? (new DateTime(
						$data['analisis']->tgl_update_lab
					))->format('d-m-Y | H:i')
					: date('d-m-Y | H:i');

				$qr_text_lab =
					"Diterima secara digital oleh,\n" .
					$data['analisis']->nama_lengkap_lab .
					"\nAnalis Laboratorium\n" .
					$update_tanggal_lab;

				$pdf->write2DBarcode(
					$qr_text_lab,
					'QRCODE,L',
					168,
					$y_verifikasi + 10,
					15,
					15,
					null,
					'N'
				);

				$pdf->SetXY(160, $y_verifikasi + 25);

				$pdf->Cell(
					30,
					5,
					'Analis Laboratorium',
					0,
					0,
					'C'
				);
			}
		} else {

			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 8);

			$pdf->SetXY(100, $y_after_keterangan);

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
			"Permohonan_Analisis_Sampel_Laboratorium_Audit_{$formatted_date2}.pdf";

		$pdf->Output($filename, 'I');
	}
}
