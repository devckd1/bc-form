<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Sanitasiwarehouse extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->library('session');
		$this->load->model('auth_model');
		$this->load->model('sanitasiwarehouse_model');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'sanitasiwarehouse' => $this->sanitasiwarehouse_model->get_data_by_plant()
		);

		$this->active_nav = 'sanitasiwarehouse';
		$this->render('form/sanitasiwarehouse/sanitasiwarehouse', $data);
	}

	public function detail($uuid)
	{
		$sanitasiwarehouse = $this->sanitasiwarehouse_model->get_by_uuid($uuid);

		if (!$sanitasiwarehouse) {
			show_404();
			return;
		}

		$data = [
			'sanitasiwarehouse' => $sanitasiwarehouse,
			'mode' => 'qc',
		];

		$this->active_nav = 'sanitasiwarehouse';
		$this->render('form/sanitasiwarehouse/sanitasiwarehouse-detail', $data);
	}

	public function tambah()
	{

		$rules = $this->sanitasiwarehouse_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->sanitasiwarehouse_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Sanitasi Warehouse berhasil di simpan');
				redirect('sanitasiwarehouse');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Sanitasi Warehouse gagal di simpan');
				redirect('sanitasiwarehouse');
			}
		}

		$this->active_nav = 'sanitasiwarehouse';
		$this->render('form/sanitasiwarehouse/sanitasiwarehouse-tambah');
	}


	public function edit($uuid)
	{
		$sanitasiwarehouse = $this->sanitasiwarehouse_model->get_by_uuid($uuid);

		if (!$sanitasiwarehouse) {
			show_404();
			return;
		}

		$this->form_validation->set_rules(
			$this->sanitasiwarehouse_model->rules()
		);

		if ($this->form_validation->run() == TRUE) {
			$update = $this->sanitasiwarehouse_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Pemeriksaan Sanitasi Warehouse berhasil diupdate.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data Pemeriksaan Sanitasi Warehouse gagal diupdate.'
				);
			}

			redirect('sanitasiwarehouse');
			return;
		}

		$data = [
			'sanitasiwarehouse' => $sanitasiwarehouse,
			'bagian_list' => $this->sanitasiwarehouse_model->get_bagian_by_uuid($uuid),
			'mode' => 'qc',
		];

		$this->active_nav = 'sanitasiwarehouse';
		$this->render('form/sanitasiwarehouse/sanitasiwarehouse-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('sanitasiwarehouse');
		}

		$deleted = $this->sanitasiwarehouse_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('sanitasiwarehouse');
	}

	public function verifikasi()
	{
		$data = array(
			'sanitasiwarehouse' => $this->sanitasiwarehouse_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-sanitasiwarehouse';
		$this->render('form/sanitasiwarehouse/sanitasiwarehouse-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->sanitasiwarehouse_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->sanitasiwarehouse_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Sanitasi Warehouse berhasil di Update');
				redirect('sanitasiwarehouse/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Sanitasi Warehouse gagal di Update');
				redirect('sanitasiwarehouse/verifikasi');
			}
		}

		$data = array(
			'sanitasiwarehouse' => $this->sanitasiwarehouse_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-sanitasiwarehouse';
		$this->render('form/sanitasiwarehouse/sanitasiwarehouse-status', $data);
	}

	public function audit()
	{
		$data = array(
			'data' => $this->sanitasiwarehouse_model->get_audit_by_plant()
		);

		$this->active_nav = 'sanitasiwarehouse';

		$this->render(
			'form/sanitasiwarehouse/sanitasiwarehouse-audit',
			$data
		);
	}

	public function detail_audit($uuid)
	{
		$sanitasiwarehouse = $this->sanitasiwarehouse_model
			->get_audit_by_uuid($uuid);

		if (!$sanitasiwarehouse) {
			show_404();
			return;
		}

		$data = [
			'sanitasiwarehouse' => $sanitasiwarehouse,
			'mode' => 'audit',
		];

		$this->active_nav = 'sanitasiwarehouse';
		$this->render('form/sanitasiwarehouse/sanitasiwarehouse-detail', $data);
	}

	public function edit_audit($uuid)
	{
		$sanitasiwarehouse = $this->sanitasiwarehouse_model
			->get_audit_by_uuid($uuid);

		if (!$sanitasiwarehouse) {
			show_404();
			return;
		}

		$this->form_validation->set_rules(
			$this->sanitasiwarehouse_model->rules()
		);

		if ($this->form_validation->run() == TRUE) {
			$update = $this->sanitasiwarehouse_model->update_audit($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Audit Sanitasi Warehouse berhasil diupdate.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data Audit Sanitasi Warehouse gagal diupdate.'
				);
			}

			redirect('sanitasiwarehouse/audit');
			return;
		}

		$data = [
			'sanitasiwarehouse' => $sanitasiwarehouse,
			'bagian_list' => $this->sanitasiwarehouse_model->get_bagian_by_uuid($uuid),
			'mode' => 'audit',
		];

		$this->active_nav = 'sanitasiwarehouse';
		$this->render('form/sanitasiwarehouse/sanitasiwarehouse-edit', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'sanitasiwarehouse' => $this->sanitasiwarehouse_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-sanitasiwarehouse', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/sanitasiwarehouse/sanitasiwarehouse-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statuswh($uuid)
	// {
	// 	$rules = $this->sanitasiwarehouse_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->sanitasiwarehouse_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Sanitasi Warehouse berhasil di Update');
	// 			redirect('sanitasiwarehouse/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Sanitasi Warehouse gagal di Update');
	// 			redirect('sanitasiwarehouse/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'sanitasiwarehouse' => $this->sanitasiwarehouse_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-sanitasiwarehouse');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/sanitasiwarehouse/sanitasiwarehouse-statuswh', $data);
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

		$sanitasiwarehouse_data = $this->sanitasiwarehouse_model->get_by_date($tanggal, $plant);
		$sanitasiwarehouse_data_verif = $this->sanitasiwarehouse_model->get_last_verif_by_date($tanggal, $plant);

		if (!$sanitasiwarehouse_data || !$sanitasiwarehouse_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['sanitasiwarehouse'] = $sanitasiwarehouse_data_verif;

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
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN SANITASI WAREHOUSE', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['sanitasiwarehouse']->date;
		$date = new DateTime($tanggal);
		$formatted_date = strftime('%A, %d %B %Y', $date->getTimestamp());

		$formatted_date2 = strftime('%d %B %Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 9);
		$pdf->SetX(10);
		$pdf->Write(0, 'Hari / Tanggal : ' . $formatted_date);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 10);

		$pdf->Cell(10, 10, 'Area', 1, 0, 'C');
		$pdf->Cell(35, 10, 'Titik Pemeriksaan', 1, 0, 'C');
		$pdf->Cell(30, 5, 'Kondisi', 1, 0, 'C');
		$pdf->Cell(45, 10, 'Masalah', 1, 0, 'C');
		$pdf->Cell(45, 10, 'Tindakan Koreksi', 1, 0, 'C');
		$pdf->Cell(30, 5, 'Paraf', 1, 1, 'C');

		$pdf->Cell(45, 10, '', 0, 0, 'L');
		$pdf->Cell(15, 5, 'Ok', 1, 0, 'C');
		$pdf->Cell(15, 5, 'Kotor', 1, 0, 'C');
		$pdf->Cell(90, 5, '', 0, 0, 'C');
		$pdf->Cell(15, 5, 'QC', 1, 0, 'C');
		$pdf->Cell(15, 5, 'WH', 1, 1, 'C');

		foreach ($sanitasiwarehouse_data as $sanitasiwarehouse) {
			$pdf->SetFont('times', '', 8);

			$detail = json_decode($sanitasiwarehouse->detail);

			if ($detail && is_array($detail)) {
				$rowCount = count($detail);
				$rowHeight = 4;
				$totalHeight = $rowCount * $rowHeight;

				$x_start = $pdf->GetX();
				$y_start = $pdf->GetY();
				$pdf->SetXY($x_start, $y_start);
				$pdf->Cell(10, $totalHeight, $sanitasiwarehouse->area, 1, 0, 'L');

				$x_current = $x_start + 10;
				$y_current = $y_start;

				foreach ($detail as $index => $item) {
					$bagian = $item->bagian ?? '-';
					$kondisi_raw = strtolower(trim($item->kondisi ?? ''));
					$kondisi1 = $kondisi2 = '-';

					if ($kondisi_raw === 'bersih' || $kondisi_raw === '0') {
						$kondisi1 = '✔';
					} elseif (in_array($kondisi_raw, ['1', '2', '3', '4', '5', '6', '7'])) {
						$kondisi2 = $kondisi_raw;
					}

					$problem = $item->problem ?? '-';
					$tindakan = $item->tindakan ?? '-';
					$pdf->SetXY($x_current, $y_current + ($index * $rowHeight));
					$pdf->Cell(35, $rowHeight, $bagian, 1, 0, 'L');

					$pdf->SetFont('dejavusans', '', 8);
					$pdf->Cell(15, $rowHeight, $kondisi1, 1, 0, 'C');

					$pdf->SetFont('times', '', 8);
					$pdf->Cell(15, $rowHeight, $kondisi2, 1, 0, 'C');
					$pdf->Cell(45, $rowHeight, $problem, 1, 0, 'L');
					$pdf->Cell(45, $rowHeight, $tindakan, 1, 0, 'L');
					if ($index === 0) {
						$pdf->Cell(15, $totalHeight, $sanitasiwarehouse->username, 1, 0, 'C');
						$pdf->Cell(15, $totalHeight, $sanitasiwarehouse->nama_wh, 1, 0, 'C');
					}

					$pdf->Ln();
				}
				$pdf->Cell(195, 0, '', 1, 1, 'C');
			}
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(190, 5, 'QW 01/00', 0, 1, 'R');

		$y_last = $pdf->GetY();
		$y_last += 5;

		$pdf->SetFont('times', '', 7);
		$pdf->SetY($pdf->GetY() + 2);

		$pdf->MultiCell(0, 3, "V Bersih\n1. Berdebu\n2. Basah\n3. Sampah (sisa lakban, kertas, remah produk/bahan baku, plastik, kardus bekas\n4. Pertumbuhan mikroorganisme (jamur dan bau busuk)\n5. Pallet rusak/pecah\n6. Terdapat aktifitas binatang (tikus, kecoa, lalat, ulat, belatung)\n7. Sarang laba-laba", 0, 'L');

		$this->load->model('pegawai_model');
		$data['sanitasiwarehouse']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['sanitasiwarehouse']->username);
		$data['sanitasiwarehouse']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['sanitasiwarehouse']->nama_spv);
		// $data['sanitasiwarehouse']->nama_lengkap_wh = $this->pegawai_model->get_nama_lengkap($data['sanitasiwarehouse']->nama_wh);
		$data['sanitasiwarehouse']->nama_lengkap_wh = $data['sanitasiwarehouse']->nama_wh;

		$pdf->SetY($pdf->GetY() + 2);
		$pdf->SetFont('times', '', 7);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($sanitasiwarehouse_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY();
		$status_verifikasi = true;
		foreach ($sanitasiwarehouse_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;
			$pdf->SetXY(25, $y_verifikasi + 5);
			$pdf->Cell(35, 5, 'Dibuat Oleh,', 0, 0, 'C');
			if (!empty($data['sanitasiwarehouse']->nama_lengkap_qc)) {
				$update_tanggal_qc = !empty($data['sanitasiwarehouse']->created_at)
					? (new DateTime($data['sanitasiwarehouse']->created_at))->format('d-m-Y | H:i')
					: date('d-m-Y | H:i');

				$qr_text_qc = "Dibuat secara digital oleh,\n" .
					$data['sanitasiwarehouse']->nama_lengkap_qc . "\nQC Inspector\n" . $update_tanggal_qc;
				$pdf->write2DBarcode($qr_text_qc, 'QRCODE,L', 35, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(25, $y_verifikasi + 24);
				$pdf->Cell(35, 5, 'QC Inspector', 0, 0, 'C');
			} else {
				$pdf->SetXY(25, $y_verifikasi + 10);
				$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}


			// Diketahui oleh (Produksi)
			$pdf->SetXY(90, $y_verifikasi + 5);
			$pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');
			if ($data['sanitasiwarehouse']->status_wh == 1 && !empty($data['sanitasiwarehouse']->nama_wh)) {
				$update_tanggal_wh = (new DateTime($data['sanitasiwarehouse']->tgl_update_wh))
					->modify('+1 hour')
					->format('d-m-Y | H:i');
				$qr_text_wh = "Diketahui secara digital oleh,\n" . $data['sanitasiwarehouse']->nama_lengkap_wh . "\nForeman/Forelady Warehouse\n" . $update_tanggal_wh;
				$pdf->write2DBarcode($qr_text_wh, 'QRCODE,L', 100, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(90, $y_verifikasi + 24);
				$pdf->Cell(35, 5, 'Warehouse', 0, 0, 'C');
			} else {
				$pdf->SetXY(90, $y_verifikasi + 10);
				$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}


			// Disetujui oleh (SPV)
			$pdf->SetXY(150, $y_verifikasi + 5);
			$pdf->Cell(49, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$update_tanggal = (new DateTime($data['sanitasiwarehouse']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['sanitasiwarehouse']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 167, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(150, $y_verifikasi + 24);
			$pdf->Cell(49, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(100, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$filename = "Pemeriksaan Sanitasi Warehouse_{$formatted_date2}.pdf";
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

		$sanitasiwarehouse_data = $this->sanitasiwarehouse_model->get_audit_by_date($tanggal, $plant);
		$sanitasiwarehouse_data_verif = $this->sanitasiwarehouse_model->get_last_verif_by_date($tanggal, $plant);

		if (!$sanitasiwarehouse_data || !$sanitasiwarehouse_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['sanitasiwarehouse'] = $sanitasiwarehouse_data_verif;

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
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN SANITASI WAREHOUSE', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['sanitasiwarehouse']->date;
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
		$pdf->Write(0, 'Hari / Tanggal : ' . $formatted_date);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 10);

		$pdf->Cell(10, 10, 'Area', 1, 0, 'C');
		$pdf->Cell(35, 10, 'Titik Pemeriksaan', 1, 0, 'C');
		$pdf->Cell(30, 5, 'Kondisi', 1, 0, 'C');
		$pdf->Cell(45, 10, 'Masalah', 1, 0, 'C');
		$pdf->Cell(45, 10, 'Tindakan Koreksi', 1, 0, 'C');
		$pdf->Cell(30, 5, 'Paraf', 1, 1, 'C');

		$pdf->Cell(45, 10, '', 0, 0, 'L');
		$pdf->Cell(15, 5, 'Ok', 1, 0, 'C');
		$pdf->Cell(15, 5, 'Kotor', 1, 0, 'C');
		$pdf->Cell(90, 5, '', 0, 0, 'C');
		$pdf->Cell(15, 5, 'QC', 1, 0, 'C');
		$pdf->Cell(15, 5, 'WH', 1, 1, 'C');

		foreach ($sanitasiwarehouse_data as $sanitasiwarehouse) {
			$pdf->SetFont('times', '', 8);

			$detail = json_decode($sanitasiwarehouse->detail);

			if ($detail && is_array($detail)) {
				$rowCount = count($detail);
				$rowHeight = 4;
				$totalHeight = $rowCount * $rowHeight;

				$x_start = $pdf->GetX();
				$y_start = $pdf->GetY();
				$pdf->SetXY($x_start, $y_start);
				$pdf->Cell(10, $totalHeight, $sanitasiwarehouse->area, 1, 0, 'L');

				$x_current = $x_start + 10;
				$y_current = $y_start;

				foreach ($detail as $index => $item) {
					$bagian = $item->bagian ?? '-';
					$kondisi_raw = strtolower(trim($item->kondisi ?? ''));
					$kondisi1 = $kondisi2 = '-';

					if ($kondisi_raw === 'bersih' || $kondisi_raw === '0') {
						$kondisi1 = '✔';
					} elseif (in_array($kondisi_raw, ['1', '2', '3', '4', '5', '6', '7'])) {
						$kondisi2 = $kondisi_raw;
					}

					$problem = $item->problem ?? '-';
					$tindakan = $item->tindakan ?? '-';
					$pdf->SetXY($x_current, $y_current + ($index * $rowHeight));
					$pdf->Cell(35, $rowHeight, $bagian, 1, 0, 'L');

					$pdf->SetFont('dejavusans', '', 8);
					$pdf->Cell(15, $rowHeight, $kondisi1, 1, 0, 'C');

					$pdf->SetFont('times', '', 8);
					$pdf->Cell(15, $rowHeight, $kondisi2, 1, 0, 'C');
					$pdf->Cell(45, $rowHeight, $problem, 1, 0, 'L');
					$pdf->Cell(45, $rowHeight, $tindakan, 1, 0, 'L');
					if ($index === 0) {
						$pdf->Cell(15, $totalHeight, $sanitasiwarehouse->username, 1, 0, 'C');
						$pdf->Cell(15, $totalHeight, $sanitasiwarehouse->nama_wh, 1, 0, 'C');
					}

					$pdf->Ln();
				}
				$pdf->Cell(195, 0, '', 1, 1, 'C');
			}
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(190, 5, 'QW 01/00', 0, 1, 'R');

		$y_last = $pdf->GetY();
		$y_last += 5;

		$pdf->SetFont('times', '', 7);
		$pdf->SetY($pdf->GetY() + 2);

		$pdf->MultiCell(0, 3, "V Bersih\n1. Berdebu\n2. Basah\n3. Sampah (sisa lakban, kertas, remah produk/bahan baku, plastik, kardus bekas\n4. Pertumbuhan mikroorganisme (jamur dan bau busuk)\n5. Pallet rusak/pecah\n6. Terdapat aktifitas binatang (tikus, kecoa, lalat, ulat, belatung)\n7. Sarang laba-laba", 0, 'L');

		$this->load->model('pegawai_model');
		$data['sanitasiwarehouse']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['sanitasiwarehouse']->username);
		$data['sanitasiwarehouse']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['sanitasiwarehouse']->nama_spv);
		// $data['sanitasiwarehouse']->nama_lengkap_wh = $this->pegawai_model->get_nama_lengkap($data['sanitasiwarehouse']->nama_wh);
		$data['sanitasiwarehouse']->nama_lengkap_wh = $data['sanitasiwarehouse']->nama_wh;

		$pdf->SetY($pdf->GetY() + 2);
		$pdf->SetFont('times', '', 7);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($sanitasiwarehouse_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY();
		$status_verifikasi = true;
		foreach ($sanitasiwarehouse_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;
			$pdf->SetXY(25, $y_verifikasi + 5);
			$pdf->Cell(35, 5, 'Dibuat Oleh,', 0, 0, 'C');
			if (!empty($data['sanitasiwarehouse']->nama_lengkap_qc)) {
				$update_tanggal_qc = !empty($data['sanitasiwarehouse']->created_at)
					? (new DateTime($data['sanitasiwarehouse']->created_at))->format('d-m-Y | H:i')
					: date('d-m-Y | H:i');

				$qr_text_qc = "Dibuat secara digital oleh,\n" .
					$data['sanitasiwarehouse']->nama_lengkap_qc . "\nQC Inspector\n" . $update_tanggal_qc;
				$pdf->write2DBarcode($qr_text_qc, 'QRCODE,L', 35, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(25, $y_verifikasi + 24);
				$pdf->Cell(35, 5, 'QC Inspector', 0, 0, 'C');
			} else {
				$pdf->SetXY(25, $y_verifikasi + 10);
				$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}


			// Diketahui oleh (Produksi)
			$pdf->SetXY(90, $y_verifikasi + 5);
			$pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');
			if ($data['sanitasiwarehouse']->status_wh == 1 && !empty($data['sanitasiwarehouse']->nama_wh)) {
				$update_tanggal_wh = (new DateTime($data['sanitasiwarehouse']->tgl_update_wh))
					->modify('+1 hour')
					->format('d-m-Y | H:i');
				$qr_text_wh = "Diketahui secara digital oleh,\n" . $data['sanitasiwarehouse']->nama_lengkap_wh . "\nForeman/Forelady Warehouse\n" . $update_tanggal_wh;
				$pdf->write2DBarcode($qr_text_wh, 'QRCODE,L', 100, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(90, $y_verifikasi + 24);
				$pdf->Cell(35, 5, 'Warehouse', 0, 0, 'C');
			} else {
				$pdf->SetXY(90, $y_verifikasi + 10);
				$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}


			// Disetujui oleh (SPV)
			$pdf->SetXY(150, $y_verifikasi + 5);
			$pdf->Cell(49, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$update_tanggal = (new DateTime($data['sanitasiwarehouse']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['sanitasiwarehouse']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 167, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(150, $y_verifikasi + 24);
			$pdf->Cell(49, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(100, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$filename = "Pemeriksaan Sanitasi Warehouse_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}
}
