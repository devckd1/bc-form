<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Sanitasi extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('sanitasi_model');
		$this->load->library('upload');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$this->load->library('pagination');

		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		if (!in_array($type_user, [9, 1])) {
			$this->db->where('plant', $plant);
		}

		$config['total_rows'] = $this->db->count_all_results('sanitasi');

		$config['base_url'] = base_url('sanitasi/index');
		$config['per_page'] = 50;
		$config['uri_segment'] = 5;

		$this->pagination->initialize($config);

		$start = $this->uri->segment(3) ?? 0;

		$data = array(
			'sanitasi' => $this->sanitasi_model->get_data_by_plant(
				$config['per_page'],
				$start
			),
			'pagination' => $this->pagination->create_links(),
			'start' => $start
		);

		$this->active_nav = 'sanitasi';
		$this->render('form/sanitasi/sanitasi', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'sanitasi' => $this->sanitasi_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'sanitasi';
		$this->render('form/sanitasi/sanitasi-detail', $data);
	}

	public function tambah()
	{
		$this->load->library('form_validation');

		$rules = $this->sanitasi_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->sanitasi_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Sanitasi berhasil disimpan');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Sanitasi gagal disimpan');
			}
			redirect('sanitasi');
		}

		$this->active_nav = 'sanitasi';
		$this->render('form/sanitasi/sanitasi-tambah');
	}

	public function edit($uuid)
	{
		$this->load->library('form_validation');

		$rules = $this->sanitasi_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$update = $this->sanitasi_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Sanitasi berhasil diupdate');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Sanitasi gagal diupdate');
			}
			redirect('sanitasi');
		}

		$data = array(
			'sanitasi'   => $this->sanitasi_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'sanitasi';
		$this->render('form/sanitasi/sanitasi-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('sanitasi');
		}

		$deleted = $this->sanitasi_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('sanitasi');
	}

	public function verifikasi()
	{
		$this->load->library('pagination');

		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		if (!in_array($type_user, [9, 1])) {
			$this->db->where('plant', $plant);
		}

		$config['total_rows'] = $this->db->count_all_results('sanitasi');

		$config['base_url'] = base_url('sanitasi/index');
		$config['per_page'] = 50;
		$config['uri_segment'] = 5;

		$this->pagination->initialize($config);

		$start = $this->uri->segment(3) ?? 0;

		$data = array(
			'sanitasi' => $this->sanitasi_model->get_data_by_plant(
				$config['per_page'],
				$start
			),
			'pagination' => $this->pagination->create_links(),
			'start' => $start
		);

		$this->active_nav = 'verifikasi-sanitasi';
		$this->render('form/sanitasi/sanitasi-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->sanitasi_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->sanitasi_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Sanitasi berhasil di Update');
				redirect('sanitasi/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Sanitasi gagal di Update');
				redirect('sanitasi/verifikasi');
			}
		}

		$data = array(
			'sanitasi' => $this->sanitasi_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-sanitasi';
		$this->render('form/sanitasi/sanitasi-status', $data);
	}

	public function audit()
	{
		$data = array(
			'sanitasi' => $this->sanitasi_model->get_audit_by_plant()
		);

		$this->active_nav = 'sanitasi';

		$this->render(
			'form/sanitasi/sanitasi-audit',
			$data
		);
	}

	public function edit_audit($uuid)
	{
		$this->load->model('master_sanitasi_model');

		$sanitasi = $this->sanitasi_model->get_audit_by_uuid($uuid);

		if (!$sanitasi) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Sanitasi tidak ditemukan.'
			);

			redirect('sanitasi/audit');
		}

		$rules = $this->sanitasi_model->rules();

		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			$update = $this->sanitasi_model->update_audit($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Audit Sanitasi berhasil diperbarui.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data Audit Sanitasi gagal diperbarui.'
				);
			}

			redirect('sanitasi/audit');
		}

		$data['sanitasi'] = $sanitasi;
		$data['master_sanitasi'] = $this->master_sanitasi_model->get_all();
		$data['active_nav'] = 'sanitasi';
		$data['page_title'] = 'Detail Audit Sanitasi';

		$this->load->view('partials/head', $data);
		$this->load->view(
			'form/sanitasi/sanitasi-edit-audit',
			$data
		);
		$this->load->view('partials/footer', $data);
	}

	public function detail_audit($uuid)
	{
		$this->load->model('master_sanitasi_model');

		$sanitasi = $this->sanitasi_model->get_audit_by_uuid($uuid);

		if (!$sanitasi) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Sanitasi tidak ditemukan.'
			);

			redirect('sanitasi/audit');
		}

		$master_sanitasi = $this->master_sanitasi_model->get_all();

		$data['master_sanitasi'] = [];

		foreach ($master_sanitasi as $master) {
			$data['master_sanitasi'][trim($master->area)] = $master;
		}

		$data['sanitasi'] = $sanitasi;
		$data['active_nav'] = 'sanitasi';
		$data['page_title'] = 'Detail Audit Sanitasi';

		$this->load->view('partials/head', $data);
		$this->load->view('form/sanitasi/sanitasi-detail-audit', $data);
		$this->load->view('partials/footer', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'sanitasi' => $this->sanitasi_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-sanitasi', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/sanitasi/sanitasi-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->sanitasi_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->sanitasi_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Sanitasi berhasil di Update');
	// 			redirect('sanitasi/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Sanitasi gagal di Update');
	// 			redirect('sanitasi/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'sanitasi' => $this->sanitasi_model->get_by_uuid($uuid), 
	// 		'active_nav' => 'diketahui-sanitasi');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/sanitasi/sanitasi-statusprod', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function cetak()
	{
		$date = $this->input->post('date');
		if (empty($date)) {
			show_error('Tanggal belum dipilih', 404);
		}

		$date = date('Y-m-d', strtotime($date));

		$plant = $this->session->userdata('plant');
		if (empty($plant)) {
			show_error('Plant tidak ditemukan di session.', 403);
		}

		$sanitasi_data = $this->sanitasi_model->get_by_date($date, $plant);
		if (empty($sanitasi_data)) {
			show_error('Data tidak ditemukan untuk tanggal ini atau bukan milik plant Anda.', 404);
		}

		$this->load->model('pegawai_model');
		$nama_qc   = $this->pegawai_model->get_nama_lengkap($sanitasi_data[0]->username);
		$nama_prod = $sanitasi_data[0]->nama_produksi;
		$nama_spv  = $this->pegawai_model->get_nama_lengkap($sanitasi_data[0]->nama_spv);

		// Inisialisasi TCPDF
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 14, 10);
		$pdf->AddPage();

		$pdf->SetFont('times', 'B', 12);

		// Tambahkan logo
		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 38);
		}

		// Judul
		$pdf->Ln(10);
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN SANITASI', 0, 'C');
		$pdf->Ln(4);

		$datetime        = new DateTime($date);
		$formatted_date  = $datetime->format('l, d F Y');
		$formatted_date2 = $datetime->format('d F Y');

		$pdf->SetFont('times', '', 9);
		$pdf->SetX(10);
		$pdf->Write(0, 'Hari / Tanggal: ' . $formatted_date);
		$pdf->SetX($pdf->GetX() + 20);
		$pdf->Write(0, 'Shift: ' . $sanitasi_data[0]->shift);
		$pdf->Ln(5);

		// Header Tabel
		$pdf->SetFont('times', '', 9);
		$pdf->Cell(15, 10, 'Pukul', 1, 0, 'C');
		$pdf->Cell(30, 10, 'Area', 1, 0, 'C');
		$pdf->Cell(50, 5, 'Kadar Klorin (ppm)', 1, 0, 'C');
		$pdf->Cell(20, 10, 'sanitasi Air', 1, 0, 'C');
		$pdf->Cell(37, 10, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(40, 10, 'Tindakan Koreksi', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');
		$pdf->Cell(45, 5, '', 0, 0, 'L');
		$pdf->Cell(25, 5, 'Standar', 1, 0, 'C');
		$pdf->Cell(25, 5, 'Aktual', 1, 0, 'C');
		$pdf->Cell(77, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		// Isi Tabel
		foreach ($sanitasi_data as $sanitasi_entry) {
			$time = (new DateTime($sanitasi_entry->waktu))->format('H:i');
			$sanitasi_areas = json_decode($sanitasi_entry->area);

			if ($sanitasi_areas && is_array($sanitasi_areas)) {
				$first_row = true;
				foreach ($sanitasi_areas as $sanitasi) {
					$pdf->SetFont('times', '', 8);
					$pdf->Cell(15, 5, $first_row ? $time : '', 1, 0, 'C');
					$first_row = false;
					$pdf->Cell(30, 5, $sanitasi->sub_area ?? '-', 1, 0, 'L');
					$pdf->Cell(25, 5, $sanitasi->standar ?? '-', 1, 0, 'C');
					$pdf->Cell(25, 5, $sanitasi->aktual ?? '-', 1, 0, 'C');
					$pdf->Cell(20, 5, $sanitasi->sanitasi_air ?? '-', 1, 0, 'C');
					$pdf->Cell(37, 5, $sanitasi->keterangan ?? '-', 1, 0, 'C');
					$pdf->Cell(40, 5, $sanitasi->tindakan ?? '-', 1, 1, 'C');
				}
			}
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(190, 5, 'QB 04/00', 0, 1, 'R');

		// Catatan
		$pdf->Ln(2);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($sanitasi_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(13, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		// Tanda tangan & QR Code
		$y_ttd = $pdf->GetY() + 6;
		$qr_size = 15;

		// Cek status verifikasi
		$status_verifikasi = true;
		foreach ($sanitasi_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		// QC
		$qc_usernames = [];
		$qc_created_at = null;
		foreach ($sanitasi_data as $item) {
			if (!empty($item->username)) $qc_usernames[] = $item->username;
			if (!$qc_created_at && !empty($item->created_at)) $qc_created_at = $item->created_at;
		}
		$qc_usernames = array_unique($qc_usernames);

		$qc_nama_lengkap = [];
		foreach ($qc_usernames as $username) {
			$nama = $this->pegawai_model->get_nama_lengkap($username);
			if (!empty($nama)) $qc_nama_lengkap[] = $nama;
		}
		$qc_nama_text = !empty($qc_nama_lengkap) ? implode(', ', array_unique($qc_nama_lengkap)) : '-';
		$qc_tanggal = $qc_created_at ? (new DateTime($qc_created_at))->format('d-m-Y | H:i') : '-';
		$qr_qc_text = "Dibuat secara digital oleh,\n$qc_nama_text\nQC Inspector\n$qc_tanggal";

		// Produksi
		$qr_produksi_text = null;
		if (!empty($nama_prod) && !empty($sanitasi_data[0]->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($sanitasi_data[0]->tgl_update_produksi))->format('d-m-Y | H:i');
			$qr_produksi_text = "Diketahui secara digital oleh,\n$nama_prod\nForeman/Forelady Produksi\n$prod_tanggal";
		}

		// SPV
		$spv_tanggal = !empty($sanitasi_data[0]->tgl_update_spv)
			? (new DateTime($sanitasi_data[0]->tgl_update_spv))->format('d-m-Y | H:i')
			: '-';
		$qr_spv_text = "Disetujui secara digital oleh,\n$nama_spv\nSupervisor QC Bread Crumb\n$spv_tanggal";

		// Cetak tanda tangan & QR Code
		if ($status_verifikasi) {
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(20, $y_ttd);
			$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(85, $y_ttd);
			$pdf->Cell(45, 5, 'Diketahui Oleh,', 0, 0, 'C');
			$pdf->SetXY(150, $y_ttd);
			$pdf->Cell(45, 5, 'Disetujui Oleh,', 0, 1, 'C');

			$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
			if ($qr_produksi_text) $pdf->write2DBarcode($qr_produksi_text, 'QRCODE,L', 100, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
			$pdf->write2DBarcode($qr_spv_text, 'QRCODE,L', 165, $y_ttd + 5, $qr_size, $qr_size, null, 'N');

			$pdf->SetXY(20, $y_ttd + 20);
			$pdf->Cell(45, 5, 'QC Inspector', 0, 0, 'C');
			$pdf->SetXY(85, $y_ttd + 20);
			$pdf->Cell(45, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
			$pdf->SetXY(150, $y_ttd + 20);
			$pdf->Cell(45, 5, 'Supervisor QC', 0, 1, 'C');
		} else {
			$pdf->SetFont('times', '', 8);
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetXY(80, $y_ttd);
			$pdf->Cell(80, 6, 'Data Belum Diverifikasi', 0, 1, 'C');
			$pdf->SetTextColor(0, 0, 0);
		}

		// Output PDF
		$filename = "Sanitasi_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}

	public function cetak_audit()
	{
		$date = $this->input->post('date');
		if (empty($date)) {
			show_error('Tanggal belum dipilih', 404);
		}

		$date = date('Y-m-d', strtotime($date));

		$plant = $this->session->userdata('plant');
		if (empty($plant)) {
			show_error('Plant tidak ditemukan di session.', 403);
		}

		$sanitasi_data = $this->sanitasi_model->get_audit_by_date($date, $plant);
		if (empty($sanitasi_data)) {
			show_error('Data tidak ditemukan untuk tanggal ini atau bukan milik plant Anda.', 404);
		}

		$this->load->model('pegawai_model');
		$nama_qc   = $this->pegawai_model->get_nama_lengkap($sanitasi_data[0]->username);
		$nama_prod = $sanitasi_data[0]->nama_produksi;
		$nama_spv  = $this->pegawai_model->get_nama_lengkap($sanitasi_data[0]->nama_spv);

		// Inisialisasi TCPDF
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 14, 10);
		$pdf->AddPage();

		$pdf->SetFont('times', 'B', 12);

		// Tambahkan logo
		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 38);
		}

		// Judul
		$pdf->Ln(10);
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN SANITASI', 0, 'C');
		$pdf->Ln(4);

		$datetime        = new DateTime($date);
		$formatted_date  = $datetime->format('l, d F Y');
		$formatted_date2 = $datetime->format('d F Y');

		$pdf->SetFont('times', '', 9);
		$pdf->SetX(10);
		$pdf->Write(0, 'Hari / Tanggal: ' . $formatted_date);
		$pdf->SetX($pdf->GetX() + 20);
		$pdf->Write(0, 'Shift: ' . $sanitasi_data[0]->shift);
		$pdf->Ln(5);

		// Header Tabel
		$pdf->SetFont('times', '', 9);
		$pdf->Cell(15, 10, 'Pukul', 1, 0, 'C');
		$pdf->Cell(30, 10, 'Area', 1, 0, 'C');
		$pdf->Cell(50, 5, 'Kadar Klorin (ppm)', 1, 0, 'C');
		$pdf->Cell(20, 10, 'sanitasi Air', 1, 0, 'C');
		$pdf->Cell(37, 10, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(40, 10, 'Tindakan Koreksi', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');
		$pdf->Cell(45, 5, '', 0, 0, 'L');
		$pdf->Cell(25, 5, 'Standar', 1, 0, 'C');
		$pdf->Cell(25, 5, 'Aktual', 1, 0, 'C');
		$pdf->Cell(77, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		// Isi Tabel
		foreach ($sanitasi_data as $sanitasi_entry) {
			$time = (new DateTime($sanitasi_entry->waktu))->format('H:i');
			$sanitasi_areas = json_decode($sanitasi_entry->area);

			if ($sanitasi_areas && is_array($sanitasi_areas)) {
				$first_row = true;
				foreach ($sanitasi_areas as $sanitasi) {
					$pdf->SetFont('times', '', 8);
					$pdf->Cell(15, 5, $first_row ? $time : '', 1, 0, 'C');
					$first_row = false;
					$pdf->Cell(30, 5, $sanitasi->sub_area ?? '-', 1, 0, 'L');
					$pdf->Cell(25, 5, $sanitasi->standar ?? '-', 1, 0, 'C');
					$pdf->Cell(25, 5, $sanitasi->aktual ?? '-', 1, 0, 'C');
					$pdf->Cell(20, 5, $sanitasi->sanitasi_air ?? '-', 1, 0, 'C');
					$pdf->Cell(37, 5, $sanitasi->keterangan ?? '-', 1, 0, 'C');
					$pdf->Cell(40, 5, $sanitasi->tindakan ?? '-', 1, 1, 'C');
				}
			}
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(190, 5, 'QB 04/00', 0, 1, 'R');

		// Catatan
		$pdf->Ln(2);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($sanitasi_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(13, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		// Tanda tangan & QR Code
		$y_ttd = $pdf->GetY() + 6;
		$qr_size = 15;

		// Cek status verifikasi
		$status_verifikasi = true;
		foreach ($sanitasi_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		// QC
		$qc_usernames = [];
		$qc_created_at = null;
		foreach ($sanitasi_data as $item) {
			if (!empty($item->username)) $qc_usernames[] = $item->username;
			if (!$qc_created_at && !empty($item->created_at)) $qc_created_at = $item->created_at;
		}
		$qc_usernames = array_unique($qc_usernames);

		$qc_nama_lengkap = [];
		foreach ($qc_usernames as $username) {
			$nama = $this->pegawai_model->get_nama_lengkap($username);
			if (!empty($nama)) $qc_nama_lengkap[] = $nama;
		}
		$qc_nama_text = !empty($qc_nama_lengkap) ? implode(', ', array_unique($qc_nama_lengkap)) : '-';
		$qc_tanggal = $qc_created_at ? (new DateTime($qc_created_at))->format('d-m-Y | H:i') : '-';
		$qr_qc_text = "Dibuat secara digital oleh,\n$qc_nama_text\nQC Inspector\n$qc_tanggal";

		// Produksi
		$qr_produksi_text = null;
		if (!empty($nama_prod) && !empty($sanitasi_data[0]->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($sanitasi_data[0]->tgl_update_produksi))->format('d-m-Y | H:i');
			$qr_produksi_text = "Diketahui secara digital oleh,\n$nama_prod\nForeman/Forelady Produksi\n$prod_tanggal";
		}

		// SPV
		$spv_tanggal = !empty($sanitasi_data[0]->tgl_update_spv)
			? (new DateTime($sanitasi_data[0]->tgl_update_spv))->format('d-m-Y | H:i')
			: '-';
		$qr_spv_text = "Disetujui secara digital oleh,\n$nama_spv\nSupervisor QC Bread Crumb\n$spv_tanggal";

		// Cetak tanda tangan & QR Code
		if ($status_verifikasi) {
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(20, $y_ttd);
			$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(85, $y_ttd);
			$pdf->Cell(45, 5, 'Diketahui Oleh,', 0, 0, 'C');
			$pdf->SetXY(150, $y_ttd);
			$pdf->Cell(45, 5, 'Disetujui Oleh,', 0, 1, 'C');

			$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
			if ($qr_produksi_text) $pdf->write2DBarcode($qr_produksi_text, 'QRCODE,L', 100, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
			$pdf->write2DBarcode($qr_spv_text, 'QRCODE,L', 165, $y_ttd + 5, $qr_size, $qr_size, null, 'N');

			$pdf->SetXY(20, $y_ttd + 20);
			$pdf->Cell(45, 5, 'QC Inspector', 0, 0, 'C');
			$pdf->SetXY(85, $y_ttd + 20);
			$pdf->Cell(45, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
			$pdf->SetXY(150, $y_ttd + 20);
			$pdf->Cell(45, 5, 'Supervisor QC', 0, 1, 'C');
		} else {
			$pdf->SetFont('times', '', 8);
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetXY(80, $y_ttd);
			$pdf->Cell(80, 6, 'Data Belum Diverifikasi', 0, 1, 'C');
			$pdf->SetTextColor(0, 0, 0);
		}

		// Output PDF
		$filename = "Sanitasi_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}
}
