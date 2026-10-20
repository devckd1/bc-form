<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Residu extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('residu_model');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'residu' => $this->residu_model->get_data_by_plant()
		);

		$this->active_nav = 'residu';
		$this->render('form/residu/residu', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'residu' => $this->residu_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'residu';
		$this->render('form/residu/residu-detail', $data);
	}

	public function tambah()
	{

		$rules = $this->residu_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->residu_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Residu Klorin berhasil di simpan');
				redirect('residu');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Residu Klorin gagal di simpan');
				redirect('residu');
			}
		}

		$this->active_nav = 'residu';
		$this->render('form/residu/residu-tambah');
	}

	public function edit($uuid)
	{
		$rules = $this->residu_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->residu_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Residu Klorin berhasil di Update');
				redirect('residu');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Residu Klorin gagal di Update');
				redirect('residu');
			}
		}

		$data = array(
			'residu' => $this->residu_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'residu';
		$this->render('form/residu/residu-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('residu');
		}

		$deleted = $this->residu_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('residu');
	}

	public function verifikasi()
	{
		$data = array(
			'residu' => $this->residu_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-residu';
		$this->render('form/residu/residu-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->residu_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->residu_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Status Verifikasi Residu Klorin berhasil di Update');
				redirect('residu/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Status Verifikasi Residu Klorin gagal di Update');
				redirect('residu/verifikasi');
			}
		}

		$data = array(
			'residu' => $this->residu_model->get_by_uuid($uuid)
		);


		$this->active_nav = 'verifikasi-residu';
		$this->render('form/residu/residu-status', $data);
	}

	public function audit()
	{
		$data = array(
			'residu' => $this->residu_model->get_audit_by_plant()
		);

		$this->active_nav = 'residu';

		$this->render(
			'form/residu/residu-audit',
			$data
		);
	}

	public function edit_audit($uuid)
	{
		$residu = $this->residu_model->get_audit_by_uuid($uuid);

		if (!$residu) {
			show_404();
			return;
		}

		$this->form_validation->set_rules(
			$this->residu_model->rules()
		);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->residu_model->update_audit($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Audit Residu Klorin berhasil diperbarui.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data Audit Residu Klorin gagal diperbarui.'
				);
			}

			redirect('residu/audit');
			return;
		}

		$data = [
			'residu' => $residu,
			'mode' => 'audit'
		];

		$this->active_nav = 'residu';
		$this->render('form/residu/residu-edit', $data);
	}

	public function detail_audit($uuid)
	{
		$residu = $this->residu_model->get_audit_by_uuid($uuid);

		if (!$residu) {
			show_404();
			return;
		}

		$data = [
			'residu' => $residu,
			'mode' => 'audit'
		];

		$this->active_nav = 'residu';
		$this->render('form/residu/residu-detail', $data);
	}

	public function cetak()
	{
		$bulan = $this->input->get('bulan');

		if (empty($bulan)) {
			show_error('Tidak ada bulan yang dipilih', 404);
		}

		[$tahun, $bln] = explode('-', $bulan);
		$start_date = "$tahun-$bln-01";
		$end_date = date("Y-m-t", strtotime($start_date));

		$plant = $this->session->userdata('plant');

		$residu_data = $this->residu_model->get_by_month($start_date, $end_date, $plant);
		$residu_data_verif = $this->residu_model->get_last_verif_by_month($start_date, $end_date, $plant);

		if (!$residu_data || !$residu_data_verif) {
			show_error('Data tidak ditemukan, Pilih bulan yang ingin dicetak', 404);
		}

		$data['residu'] = $residu_data_verif;

		$this->load->model('pegawai_model');
		$data['residu']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['residu']->username);
		$data['residu']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['residu']->nama_spv);

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 9.5, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 10);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 35);
		}

		$pdf->Ln(10);
		$pdf->SetFont('times', 'B', 10);
		$pdf->MultiCell(0, 5, 'VERIFIKASI RESIDU KLORIN', 0, 'C');
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 9);
		$y = $pdf->GetY();
		$pdf->SetY($y);
		$pdf->MultiCell(40, 3, 'Area : ' . $data['residu']->area, 0, 'L');
		$pdf->SetY($y);
		$pdf->SetX(70);
		$pdf->MultiCell(60, 3, 'Bulan : ' . date('F Y', strtotime($start_date)), 0, 'L');
		$pdf->MultiCell(0, 3, 'Titik Sampling : ' . $data['residu']->titik_sampling, 0, 'L');
		$pdf->Ln(1);

		// Header Tabel
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(15, 10, 'Tanggal', 1, 0, 'C');
		$pdf->Cell(25, 10, 'Standar', 1, 0, 'C');
		$pdf->Cell(25, 10, 'Hasil Pemeriksaan', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(35, 10, 'Tindakan Koreksi', 1, 0, 'C');
		$pdf->Cell(30, 10, 'Verifikasi', 1, 0, 'C');
		$pdf->Cell(40, 5, 'Diverifikasi Oleh', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$pdf->Cell(15, 0, '', 0, 0, 'L');
		$pdf->Cell(25, 5, '(PPM)', 0, 0, 'C');
		$pdf->Cell(25, 5, '(PPM)', 0, 0, 'C');
		$pdf->Cell(85, 5, '', 0, 0, 'C');
		$pdf->Cell(20, 5, 'Nama', 1, 0, 'C');
		$pdf->Cell(20, 5, 'Paraf', 1, 0, 'C');
		$pdf->Cell(25, 5, '', 0, 1, 'C');

		// Isi tabel
		$pdf->SetFont('times', '', 7);
		foreach ($residu_data as $residu) {
			$created_date = (new DateTime($residu->date))->format('d');
			$pdf->Cell(15, 5, $created_date, 1, 0, 'C');
			$pdf->Cell(25, 5, $residu->standar, 1, 0, 'C');
			$pdf->Cell(25, 5, $residu->hasil_pemeriksaan, 1, 0, 'C');
			$pdf->Cell(20, 5, $residu->keterangan, 1, 0, 'C');
			$pdf->Cell(35, 5, $residu->tindakan, 1, 0, 'C');
			$pdf->Cell(30, 5, $residu->verifikasi, 1, 0, 'C');
			$pdf->Cell(20, 5, $residu->username, 1, 0, 'C');
			$pdf->Cell(20, 5, "", 1, 0, 'C');
			$pdf->Ln();
		}

		// Catatan
		$pdf->SetY($pdf->GetY() + 2);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($residu_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		// Tanda tangan
		$y_verifikasi = $pdf->GetY() + 5;
		$pdf->SetFont('times', '', 8);

		if ($data['residu']->nama_lengkap_qc && $data['residu']->nama_lengkap_spv) {
			$pdf->SetXY(25, $y_verifikasi);
			$pdf->Cell(35, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(25, $y_verifikasi + 10);
			$pdf->SetFont('times', 'U', 8);
			$pdf->Cell(35, 5, $data['residu']->nama_lengkap_qc, 0, 1, 'C');
			$pdf->SetFont('times', '', 8);
			$pdf->Cell(65, 5, 'QC Inspector', 0, 0, 'C');

			$pdf->SetXY(150, $y_verifikasi);
			$pdf->Cell(49, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$update_tanggal = (new DateTime($data['residu']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['residu']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 167, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(150, $y_verifikasi + 24);
			$pdf->Cell(49, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$filename = "Verifikasi_Penggunaan_Residu_Klorin_{$bulan}.pdf";
		$pdf->Output($filename, 'I');
	}

	public function cetak_audit()
	{
		$bulan = $this->input->get('bulan');

		if (empty($bulan)) {
			show_error('Tidak ada bulan yang dipilih', 404);
		}

		[$tahun, $bln] = explode('-', $bulan);
		$start_date = "$tahun-$bln-01";
		$end_date = date("Y-m-t", strtotime($start_date));

		$plant = $this->session->userdata('plant');

		$residu_data = $this->residu_model->get_audit_by_month($start_date, $end_date, $plant);
		$residu_data_verif = $this->residu_model->get_last_verif_by_month($start_date, $end_date, $plant);

		if (!$residu_data || !$residu_data_verif) {
			show_error('Data tidak ditemukan, Pilih bulan yang ingin dicetak', 404);
		}

		$data['residu'] = $residu_data_verif;

		$this->load->model('pegawai_model');
		$data['residu']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['residu']->username);
		$data['residu']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['residu']->nama_spv);

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 9.5, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 10);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 35);
		}

		$pdf->Ln(10);
		$pdf->SetFont('times', 'B', 10);
		$pdf->MultiCell(0, 5, 'VERIFIKASI RESIDU KLORIN', 0, 'C');
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 9);
		$y = $pdf->GetY();
		$pdf->SetY($y);
		$pdf->MultiCell(40, 3, 'Area : ' . $data['residu']->area, 0, 'L');
		$pdf->SetY($y);
		$pdf->SetX(70);
		$pdf->MultiCell(60, 3, 'Bulan : ' . date('F Y', strtotime($start_date)), 0, 'L');
		$pdf->MultiCell(0, 3, 'Titik Sampling : ' . $data['residu']->titik_sampling, 0, 'L');
		$pdf->Ln(1);

		// Header Tabel
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(15, 10, 'Tanggal', 1, 0, 'C');
		$pdf->Cell(25, 10, 'Standar', 1, 0, 'C');
		$pdf->Cell(25, 10, 'Hasil Pemeriksaan', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(35, 10, 'Tindakan Koreksi', 1, 0, 'C');
		$pdf->Cell(30, 10, 'Verifikasi', 1, 0, 'C');
		$pdf->Cell(40, 5, 'Diverifikasi Oleh', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$pdf->Cell(15, 0, '', 0, 0, 'L');
		$pdf->Cell(25, 5, '(PPM)', 0, 0, 'C');
		$pdf->Cell(25, 5, '(PPM)', 0, 0, 'C');
		$pdf->Cell(85, 5, '', 0, 0, 'C');
		$pdf->Cell(20, 5, 'Nama', 1, 0, 'C');
		$pdf->Cell(20, 5, 'Paraf', 1, 0, 'C');
		$pdf->Cell(25, 5, '', 0, 1, 'C');

		// Isi tabel
		$pdf->SetFont('times', '', 7);
		foreach ($residu_data as $residu) {
			$created_date = (new DateTime($residu->date))->format('d');
			$pdf->Cell(15, 5, $created_date, 1, 0, 'C');
			$pdf->Cell(25, 5, $residu->standar, 1, 0, 'C');
			$pdf->Cell(25, 5, $residu->hasil_pemeriksaan, 1, 0, 'C');
			$pdf->Cell(20, 5, $residu->keterangan, 1, 0, 'C');
			$pdf->Cell(35, 5, $residu->tindakan, 1, 0, 'C');
			$pdf->Cell(30, 5, $residu->verifikasi, 1, 0, 'C');
			$pdf->Cell(20, 5, $residu->username, 1, 0, 'C');
			$pdf->Cell(20, 5, "", 1, 0, 'C');
			$pdf->Ln();
		}

		// Catatan
		$pdf->SetY($pdf->GetY() + 2);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($residu_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		// Tanda tangan
		$y_verifikasi = $pdf->GetY() + 5;
		$pdf->SetFont('times', '', 8);

		if ($data['residu']->nama_lengkap_qc && $data['residu']->nama_lengkap_spv) {
			$pdf->SetXY(25, $y_verifikasi);
			$pdf->Cell(35, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(25, $y_verifikasi + 10);
			$pdf->SetFont('times', 'U', 8);
			$pdf->Cell(35, 5, $data['residu']->nama_lengkap_qc, 0, 1, 'C');
			$pdf->SetFont('times', '', 8);
			$pdf->Cell(65, 5, 'QC Inspector', 0, 0, 'C');

			$pdf->SetXY(150, $y_verifikasi);
			$pdf->Cell(49, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$update_tanggal = (new DateTime($data['residu']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['residu']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 167, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(150, $y_verifikasi + 24);
			$pdf->Cell(49, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$filename = "Verifikasi_Penggunaan_Residu_Klorin_{$bulan}.pdf";
		$pdf->Output($filename, 'I');
	}
}
