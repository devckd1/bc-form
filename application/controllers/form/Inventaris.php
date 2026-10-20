<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Inventaris extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('auth_model');
		$this->load->model('inventaris_model');
		$this->load->model('alatqc_model');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'inventaris' => $this->inventaris_model->get_data_by_plant(),
		);

		$this->active_nav = 'inventaris';
		$this->render('form/inventaris/inventaris', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'inventaris' => $this->inventaris_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'inventaris';
		$this->render('form/inventaris/inventaris-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->inventaris_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->inventaris_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Checklist Inventaris Peralatan QC Bread Crumb berhasil disimpan');
				redirect('inventaris');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Checklist Inventaris Peralatan QC Bread Crumb gagal disimpan');
				redirect('inventaris');
			}
		}

		$data = array(
			'alat_list' => $this->alatqc_model->get_all()
		);

		$this->active_nav = 'inventaris';
		$this->render('form/inventaris/inventaris-tambah', $data);
	}

	public function edit($uuid)
	{
		$rules = $this->inventaris_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$update = $this->inventaris_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Checklist Inventaris Peralatan QC Bread Crumb berhasil diupdate.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Checklist Inventaris Peralatan QC Bread Crumb gagal diupdate.');
			}

			redirect('inventaris');
		}

		$data = [
			'inventaris' => $this->inventaris_model->get_by_uuid($uuid),
		];

		$this->active_nav = 'inventaris';
		$this->render('form/inventaris/inventaris-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('inventaris');
		}

		$deleted = $this->inventaris_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('inventaris');
	}

	public function check($uuid)
	{
		// Ensure the 'kondisi_akhir' field exists and is an array
		$kondisi_akhir_data = $this->input->post('kondisi_akhir');

		if ($kondisi_akhir_data && is_array($kondisi_akhir_data)) {
			// Loop to apply validation rules for each kondisi_akhir input
			foreach ($kondisi_akhir_data as $key => $val) {
				$this->form_validation->set_rules("kondisi_akhir[$key]", 'Kondisi Akhir', 'required|in_list[Tidak Tersedia,Baik,Rusak,Hilang,Bersih,Kotor,Habis,Baik Bersih Masih]');
			}
		}

		if ($this->form_validation->run() == TRUE) {
			// If validation passes, update the data
			$update = $this->inventaris_model->update_check($uuid);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Checklist Inventaris Peralatan QC Bread Crumb diupdate.');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Checklist Inventaris Peralatan QC Bread Crumb gagal diupdate.');
			}

			redirect('inventaris');
		}

		// If validation fails or it's the first request, load the data
		$data = [
			'inventaris' => $this->inventaris_model->get_by_uuid($uuid)
		];

		$this->active_nav = 'inventaris';
		$this->render('form/inventaris/inventaris-check', $data);
	}


	public function verifikasi()
	{
		$data = array(
			'inventaris' => $this->inventaris_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-inventaris';
		$this->render('form/inventaris/inventaris-verifikasi', $data);
	}


	public function status($uuid)
	{
		$rules = $this->inventaris_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->inventaris_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Checklist Inventaris Peralatan QC Bread Crumb berhasil di Update');
				redirect('inventaris/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Checklist Inventaris Peralatan QC Bread Crumb gagal di Update');
				redirect('inventaris/verifikasi');
			}
		}

		$data = array(
			'inventaris' => $this->inventaris_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-inventaris';
		$this->render('form/inventaris/inventaris-status', $data);
	}

	public function audit()
	{
		$data = array(
			'inventaris' => $this->inventaris_model->get_audit_by_plant()
		);

		$this->active_nav = 'inventaris';

		$this->render(
			'form/inventaris/inventaris-audit',
			$data
		);
	}


	public function edit_audit($uuid)
	{
		$inventaris = $this->inventaris_model->get_audit_by_uuid($uuid);

		if (!$inventaris) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Inventaris tidak ditemukan.'
			);
			redirect('inventaris/audit');
			return;
		}

		$rules = $this->inventaris_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->inventaris_model->update_audit($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Audit Inventaris berhasil diupdate.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data Audit Inventaris gagal diupdate.'
				);
			}

			redirect('inventaris/audit');
			return;
		}

		$data = [
			'inventaris' => $inventaris,
			'mode'       => 'audit',
		];

		$this->active_nav = 'inventaris';

		$this->render(
			'form/inventaris/inventaris-edit',
			$data
		);
	}


	public function detail_audit($uuid)
	{
		$inventaris = $this->inventaris_model->get_audit_by_uuid($uuid);

		if (!$inventaris) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Inventaris tidak ditemukan.'
			);
			redirect('inventaris/audit');
			return;
		}

		$data = [
			'inventaris' => $inventaris,
			'mode'       => 'audit',
		];

		$this->active_nav = 'inventaris';

		$this->render(
			'form/inventaris/inventaris-detail',
			$data
		);
	}

	public function cetak()
	{
		$selected_items = $this->input->post('checkbox');

		log_message('debug', 'UUID yang dipilih: ' . print_r($selected_items, true));

		if (empty($selected_items)) {
			show_error('Tidak ada item yang dipilih', 404);
		}

		$inventaris_data = $this->inventaris_model->get_by_uuid_inventaris($selected_items);

		$inventaris_data_verif = $this->inventaris_model->get_by_uuid_inventaris_verif($selected_items);

		$data['inventaris'] = $inventaris_data_verif;


		if (!$data['inventaris']) {
			show_error('Data tidak ditemukan, Pilih data yang ingin dicetak', 404);
		}

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
		$pdf->MultiCell(0, 5, 'CHECKLIST INVENTARIS PERALATAN QC BREADCRUMB', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['inventaris']->date;
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
		$pdf->SetX(10);
		$pdf->Write(0, 'Tanggal: ' . $formatted_date);
		$pdf->SetX($pdf->GetX() + 20);
		$pdf->Write(0, 'Shift: ' . $data['inventaris']->shift);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 10);

		$pdf->Cell(10, 10, 'No.', 1, 0, 'C');
		$pdf->Cell(70, 10, 'Nama Alat', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Jumlah', 1, 0, 'C');
		$pdf->Cell(52, 5, 'Kondisi', 1, 0, 'C');
		$pdf->Cell(40, 10, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$pdf->Cell(100, 10, '', 0, 0, 'L');
		$pdf->Cell(26, 5, 'Awal Shift', 1, 0, 'C');
		$pdf->Cell(26, 5, 'Akhir Shift', 1, 0, 'C');
		$pdf->Cell(40, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$no = 1;
		foreach ($inventaris_data as $row) {
			$pdf->SetFont('times', '', 9);
			$inventaris_list = json_decode($row->peralatan);

			if ($inventaris_list && is_array($inventaris_list)) {
				foreach ($inventaris_list as $item) {
					$nama_alat = isset($item->nama_alat) ? $item->nama_alat : '-';
					$jumlah = isset($item->jumlah) ? $item->jumlah : '-';
					$kondisi_awal = isset($item->kondisi_awal) ? $item->kondisi_awal : '-';
					$kondisi_akhir = isset($item->kondisi_akhir) ? $item->kondisi_akhir : '-';
					$keterangan = isset($item->keterangan) ? $item->keterangan : '-';

					$pdf->SetFont('times', '', 8);

					$height_nama_alat = $pdf->getStringHeight(70, $nama_alat);
					$height_keterangan = $pdf->getStringHeight(40, $keterangan);
					$row_height = max($height_nama_alat, $height_keterangan, 5);

					$pdf->MultiCell(10, $row_height, $no, 1, 'C', false, 0);
					$pdf->MultiCell(70, $row_height, $nama_alat, 1, 'L', false, 0);
					$pdf->MultiCell(20, $row_height, $jumlah, 1, 'C', false, 0);
					$pdf->MultiCell(26, $row_height, $kondisi_awal, 1, 'C', false, 0);
					$pdf->MultiCell(26, $row_height, $kondisi_akhir, 1, 'C', false, 0);
					$pdf->MultiCell(40, $row_height, $keterangan, 1, 'L', false, 1);

					$no++;
				}
			}
		}

		$pdf->SetY($pdf->GetY() + 3);
		$pdf->SetFont('dejavusans', '', 5);
		$kiri = "(-) = Tidak Tersedia\n(1) Baik ; (2) Rusak ; (3) Hilang\n(4) Bersih ; (5) Kotor ; (6) Habis\n(7) Baik, Bersih, Masih";
		$posY = $pdf->GetY();
		$pdf->SetXY(10, $posY);
		$pdf->MultiCell(90, 4, $kiri, 0, 'L');

		$this->load->model('pegawai_model');
		$data['inventaris']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['inventaris']->username);
		$data['inventaris']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['inventaris']->nama_spv);
		$data['inventaris']->nama_qc_update = $this->pegawai_model->get_nama_lengkap($data['inventaris']->qc_update);

		$nama_spv = $data['inventaris']->nama_spv;
		$tanggal_update = $data['inventaris']->tgl_update_spv;
		$update = new DateTime($tanggal_update);
		$update_tanggal = $update->format('d-m-Y | H:i');

		$status_verifikasi = true;

		foreach ($inventaris_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$y_after_keterangan = $pdf->GetY();

		if ($status_verifikasi) {
			$pdf->SetFont('times', '', 8);
			$pdf->SetTextColor(0, 0, 0);
			$y_verifikasi = $y_after_keterangan;
			$pdf->SetXY(65, $y_verifikasi + 10);
			$pdf->Cell(20, 5, 'Diperiksa Oleh, ', 0, 0, 'C');
			$pdf->SetXY(25, $y_verifikasi + 15);
			$pdf->Cell(35, 5, 'Awal Shift :', 0, 0, 'C');
			$pdf->SetXY(25, $y_verifikasi + 20);
			$pdf->SetFont('times', 'U', 8);
			$pdf->Cell(35, 5, $data['inventaris']->nama_lengkap_qc, 0, 0, 'C');
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(25, $y_verifikasi + 25);
			$pdf->Cell(35, 5, 'QC Inspector', 0, 0, 'C');
			$pdf->SetXY(90, $y_verifikasi + 15);
			$pdf->Cell(30, 5, 'Akhir Shift :', 0, 0, 'C');
			$pdf->SetXY(90, $y_verifikasi + 20);
			$pdf->SetFont('times', 'U', 8);
			$pdf->Cell(30, 5, $data['inventaris']->nama_qc_update, 0, 0, 'C');
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(25, $y_verifikasi + 25);
			$pdf->Cell(160, 5, 'QC Inspector', 0, 0, 'C');
			$pdf->SetXY(150, $y_verifikasi + 10);
			$pdf->Cell(49, 5, 'Disetujui oleh,', 0, 0, 'C');
			$qr_text = 'Diverifikasi secara digital oleh,' . "\n"
				. $data['inventaris']->nama_lengkap_spv . "\n"
				. 'SPV QC Bread Crumb' . "\n"
				. $update_tanggal;

			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 167, $y_verifikasi + 15, 15, 15, null, 'N');

			$pdf->SetXY(150, $y_verifikasi + 30);
			$pdf->Cell(49, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(100, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$filename = "Checklist Inventaris Peralatan QC_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}
}
