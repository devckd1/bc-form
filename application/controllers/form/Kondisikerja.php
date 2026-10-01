<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Kondisikerja extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('kondisikerja_model');
		$this->load->library('audit_library');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'kondisikerja' => $this->kondisikerja_model->get_data_by_plant()
		);

		$this->active_nav = 'kondisikerja';
		$this->render('form/kondisikerja/kondisikerja', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'kondisikerja' => $this->kondisikerja_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'kondisikerja';
		$this->render('form/kondisikerja/kondisikerja-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->kondisikerja_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->kondisikerja_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Kondisi Kerja Selama Produksi berhasil di simpan');
				redirect('kondisikerja');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Kondisi Kerja Selama Produksi gagal di simpan');
				redirect('kondisikerja');
			}
		}

		$this->active_nav = 'kondisikerja';
		$this->render('form/kondisikerja/kondisikerja-tambah');
	}

	public function edit($uuid)
	{
		$rules = $this->kondisikerja_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->kondisikerja_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Kondisi Kerja Selama Produksi berhasil di Update');
				redirect('kondisikerja');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Kondisi Kerja Selama Produksi gagal di Update');
				redirect('kondisikerja');
			}
		}

		$data = array(
			'kondisikerja' => $this->kondisikerja_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'kondisikerja';
		$this->render('form/kondisikerja/kondisikerja-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('kondisikerja');
		}

		$deleted = $this->kondisikerja_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('kondisikerja');
	}


	public function verifikasi()
	{
		$data = array(
			'kondisikerja' => $this->kondisikerja_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-kondisikerja';
		$this->render('form/kondisikerja/kondisikerja-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->kondisikerja_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->kondisikerja_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Kondisi Kerja Selama Produksi berhasil di Update');
				redirect('kondisikerja/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Kondisi Kerja Selama Produksi gagal di Update');
				redirect('kondisikerja/verifikasi');
			}
		}

		$data = array(
			'kondisikerja' => $this->kondisikerja_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-kondisikerja';
		$this->render('form/kondisikerja/kondisikerja-status', $data);
	}

	public function copy_to_audit($uuid)
	{
		if (empty($uuid)) {
			$this->session->set_flashdata(
				'error_msg',
				'UUID data tidak ditemukan.'
			);

			redirect('kondisikerja/verifikasi');
			return;
		}

		$audit_uuid = $this->audit_library->copy_to_audit(
			'kondisi_kerja',
			$uuid
		);

		if (!$audit_uuid) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Kondisi Kerja tidak ditemukan atau gagal dibuat sebagai Audit.'
			);

			redirect('kondisikerja/verifikasi');
			return;
		}

		$this->session->set_flashdata(
			'success_msg',
			'Data Kondisi Kerja berhasil dikirim ke Audit.'
		);

		redirect('kondisikerja/verifikasi');
	}

	public function audit()
	{
		$data = array(
			'kondisikerja' => $this->kondisikerja_model->get_audit_by_plant()
		);

		$this->active_nav = 'verifikasi-kondisikerja';

		$this->render(
			'form/kondisikerja/kondisikerja-audit',
			$data
		);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'kondisikerja' => $this->kondisikerja_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-kondisikerja', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/kondisikerja/kondisikerja-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->kondisikerja_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->kondisikerja_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Kondisi Kerja Selama Produksi berhasil di Update');
	// 			redirect('kondisikerja/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Kondisi Kerja Selama Produksi gagal di Update');
	// 			redirect('kondisikerja/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'kondisikerja' => $this->kondisikerja_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-kondisikerja');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/kondisikerja/kondisikerja-statusprod', $data);
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

		$kondisikerja_data = $this->kondisikerja_model->get_by_date($tanggal, $plant, $shift);
		$kondisikerja_data_verif = $this->kondisikerja_model->get_last_verif_by_date($tanggal, $plant, $shift);

		if (!$kondisikerja_data || !$kondisikerja_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['kondisikerja'] = $kondisikerja_data_verif;

		$this->load->model('pegawai_model');
		$data['kondisikerja']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['kondisikerja']->username);
		$data['kondisikerja']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['kondisikerja']->nama_spv);
		$data['kondisikerja']->nama_lengkap_produksi = $data['kondisikerja']->nama_produksi;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 14, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 11);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 38);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(7, "\n");
		$pdf->MultiCell(0, 5, 'KONDISI KERJA SELAMA PRODUKSI', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['kondisikerja']->date;
		$date = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $date->getTimestamp());

		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 9);
		$pdf->SetX(10);
		$pdf->Write(0, 'Hari / Tanggal : ' . $formatted_date);
		$pdf->SetX($pdf->GetX() + 20);
		$pdf->Write(0, 'Shift: ' . $data['kondisikerja']->shift);
		$pdf->SetX($pdf->GetX() + 20);
		$pdf->Write(0, 'Area: ' . $data['kondisikerja']->area);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 10);

		$pdf->Cell(15, 12, 'Pukul', 1, 0, 'C');
		$pdf->Cell(35, 12, 'ITEM', 1, 0, 'C');
		$pdf->Cell(25, 5, 'Kondisi', 1, 0, 'C');
		$pdf->Cell(40, 12, 'Problem', 1, 0, 'C');
		$pdf->Cell(40, 12, 'Tindakan Koreksi', 1, 0, 'C');
		$pdf->Cell(35, 12, 'Verifikasi', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$pdf->Cell(50, 5, '', 0, 0, 'L');
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(12, 7, 'OK', 1, 0, 'C');
		$pdf->Cell(13, 7, 'Tidak Ok', 1, 0, 'C');
		$pdf->Cell(94, 5, '', 0, 0, 'C');
		$pdf->Cell(10, 7, '', 0, 1, 'C');

		foreach ($kondisikerja_data as $kondisikerja) {
			$time = $kondisikerja->waktu;
			$time2 = new DateTime($time);
			$created_time = $time2->format('H:i');

			$ok_values = ['✓', '-'];
			$not_ok_values = ['x', 'X', '1', '2', '3', '4', '5', '6', '7'];
			$kondisi_higiene = trim($kondisikerja->kondisi_higiene);
			$higiene_ok = in_array($kondisi_higiene, $ok_values) ? $kondisi_higiene : '';
			$higiene_not_ok = in_array($kondisi_higiene, $not_ok_values) ? $kondisi_higiene : '';
			$kondisi_peralatan = trim($kondisikerja->kondisi_peralatan);
			$peralatan_ok = in_array($kondisi_peralatan, $ok_values) ? $kondisi_peralatan : '';
			$peralatan_not_ok = in_array($kondisi_peralatan, $not_ok_values) ? $kondisi_peralatan : '';
			$kondisi_kebersihan = trim($kondisikerja->kondisi_kebersihan);
			$kebersihan_ok = in_array($kondisi_kebersihan, $ok_values) ? $kondisi_kebersihan : '';
			$kebersihan_not_ok = in_array($kondisi_kebersihan, $not_ok_values) ? $kondisi_kebersihan : '';

			$pdf->SetFont('times', '', 8);
			$pdf->Cell(15, 15, $created_time, 1, 0, 'C');
			$pdf->Cell(35, 5, 'Higiene Karyawan', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 9);
			$pdf->Cell(12, 5, $higiene_ok, 1, 0, 'C');
			$pdf->SetFont('times', '', 8);
			$pdf->Cell(13, 5, $higiene_not_ok, 1, 0, 'C');
			$pdf->Cell(40, 5, $kondisikerja->problem_higiene, 1, 0, 'L');
			$pdf->Cell(40, 5, $kondisikerja->tindakan_higiene, 1, 0, 'L');
			$pdf->Cell(35, 5, $kondisikerja->verifikasi_higiene, 1, 0, 'L');
			// $pdf->Cell(15, 15, $kondisikerja->username, 1, 0, 'C');
			// $pdf->Cell(15, 15, $kondisikerja->nama_produksi, 1, 0, 'C');
			$pdf->Cell(10, 5, '', 0, 1, 'C');

			$pdf->Cell(15, 0, '', 0, 0);
			$pdf->Cell(35, 5, 'Kebersihan Peralatan', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 9);
			$pdf->Cell(12, 5, $peralatan_ok, 1, 0, 'C');
			$pdf->SetFont('times', '', 8);
			$pdf->Cell(13, 5, $peralatan_not_ok, 1, 0, 'C');
			$pdf->Cell(40, 5, $kondisikerja->problem_peralatan, 1, 0, 'L');
			$pdf->Cell(40, 5, $kondisikerja->tindakan_peralatan, 1, 0, 'L');
			$pdf->Cell(35, 5, $kondisikerja->verifikasi_peralatan, 1, 0, 'L');
			$pdf->Cell(10, 5, '', 0, 1);

			$pdf->Cell(15, 0, '', 0, 0, 'C');
			$pdf->Cell(35, 5, 'Kebersihan Area', 1, 0, 'L');
			$pdf->SetFont('dejavusans', '', 8);
			$pdf->Cell(12, 5, $kebersihan_ok, 1, 0, 'C');
			$pdf->Cell(13, 5, $kebersihan_not_ok, 1, 0, 'C');
			$pdf->SetFont('times', '', 8);
			$pdf->Cell(40, 5, $kondisikerja->problem_kebersihan, 1, 0, 'L');
			$pdf->Cell(40, 5, $kondisikerja->tindakan_kebersihan, 1, 0, 'L');
			$pdf->Cell(35, 5, $kondisikerja->verifikasi_kebersihan, 1, 0, 'L');
			$pdf->Cell(10, 0, '', 0, 0);
			$pdf->Cell(10, 5, '', 0, 1, 'C');
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(190, 5, 'QB 03/00', 0, 1, 'R');

		$nama_spv = $data['kondisikerja']->nama_spv;
		$tanggal_update = $data['kondisikerja']->tgl_update_spv;
		$update = new DateTime($tanggal_update);
		$update_tanggal = $update->format('d-m-Y | H:i');

		$status_verifikasi = true;

		foreach ($kondisikerja_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetY($pdf->GetY() + 3);
		$pdf->SetFont('dejavusans', '', 5);
		$kiri = "1 Berdebu\n2 Basah, ada genangan air\n3 Sisa produksi (remah-remah roti, tepung, sisa adonan)\n4 Noda (karat, cat, tinta)\n5 Pertumbuhan mikroorganisme (jamur, bau busuk, biofilm)\n6 Kontak / kontaminasi material non halal\n7 Higiene karyawan tidak sesuai GMP";
		$kanan = "✓ : Ok, sesuai SSOP, bersih, bebas najis / material non halal\n✗ : Tidak Ok, tidak sesuai SSOP\n- : Tidak ada atau tidak digunakan";
		$posY = $pdf->GetY();
		$pdf->SetXY(10, $posY);
		$pdf->MultiCell(90, 4, $kiri, 0, 'L');
		$pdf->SetXY(105, $posY);
		$pdf->MultiCell(90, 4, $kanan, 0, 'L');

		$pdf->SetY($pdf->GetY() + 10);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($kondisikerja_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($kondisikerja_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		// if ($status_verifikasi) {
		// 	$y_verifikasi = $y_after_keterangan;

		// // Dibuat oleh (QC)
		// 	$pdf->SetXY(25, $y_verifikasi + 5);
		// 	$pdf->Cell(35, 5, 'Dibuat Oleh,', 0, 0, 'C');
		// 	$pdf->SetXY(25, $y_verifikasi + 10);
		// 	$pdf->SetFont('times', 'U', 8); // underline
		// 	$pdf->Cell(35, 5, $data['kondisikerja']->nama_lengkap_qc, 0, 1, 'C');
		// 	$pdf->SetFont('times', '', 8); 
		// 	$pdf->Cell(65, 5, 'QC Inspector', 0, 0, 'C');

		// // Diketahui oleh (Produksi)
		// 	$pdf->SetXY(90, $y_verifikasi + 5);
		// 	$pdf->Cell(35, 5, 'Diketahui Oleh,', 0, 0, 'C');
		// 	if ($data['kondisikerja']->status_produksi == 1 && !empty($data['kondisikerja']->nama_produksi)) {
		// 		$pdf->SetXY(90, $y_verifikasi + 10);
		// 		$pdf->SetFont('times', 'U', 8);
		// 		$pdf->Cell(35, 5, $data['kondisikerja']->nama_produksi, 0, 1, 'C');
		// 		$pdf->SetFont('times', '', 8);
		// 		$pdf->SetXY(90, $y_verifikasi + 15);
		// 		$pdf->Cell(35, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
		// 	} else {
		// 		$pdf->SetXY(90, $y_verifikasi + 10);
		// 		$pdf->Cell(35, 5, 'Belum Diverifikasi', 0, 0, 'C');
		// 	}

		// // Disetujui oleh (SPV)
		// 	$pdf->SetXY(150, $y_verifikasi + 5);
		// 	$pdf->Cell(49, 5, 'Disetujui Oleh,', 0, 0, 'C');
		// 	$update_tanggal = (new DateTime($data['kondisikerja']->tgl_update_spv))->format('d-m-Y | H:i');
		// 	$qr_text = "Diverifikasi secara digital oleh,\n" . $data['kondisikerja']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
		// 	$pdf->write2DBarcode($qr_text, 'QRCODE,L', 167, $y_verifikasi + 10, 15, 15, null, 'N');
		// 	$pdf->SetXY(150, $y_verifikasi + 24);
		// 	$pdf->Cell(49, 5, 'Supervisor QC', 0, 0, 'C');
		// } else {
		// 	$pdf->SetTextColor(255, 0, 0); 
		// 	$pdf->SetFont('times', '', 8);
		// 	$pdf->SetXY(100, $y_after_keterangan);
		// 	$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		// }

		$y_ttd   = $pdf->GetY() + 6;
		$qr_size = 15;

		$qc_usernames  = [];
		$qc_created_at = null;

		foreach ($kondisikerja_data as $item) {
			if (!empty($item->username)) {
				$qc_usernames[] = $item->username;
			}

			if (!$qc_created_at && !empty($item->created_at)) {
				$qc_created_at = $item->created_at;
			}
		}

		$qc_usernames = array_unique($qc_usernames);

		$qc_nama_lengkap = [];
		foreach ($qc_usernames as $username) {
			$nama = $this->pegawai_model->get_nama_lengkap($username);
			if (!empty($nama)) {
				$qc_nama_lengkap[] = $nama;
			}
		}

		$qc_nama_text = !empty($qc_nama_lengkap)
			? implode(', ', array_unique($qc_nama_lengkap))
			: '-';

		$qc_tanggal = $qc_created_at
			? (new DateTime($qc_created_at))->format('d-m-Y | H:i')
			: '-';

		$qr_qc_text = "Dibuat secara digital oleh,\n"
			. $qc_nama_text . "\n"
			. "QC Inspector\n"
			. $qc_tanggal;

		$qr_produksi_text = null;

		if (!empty($data['kondisikerja']->nama_lengkap_produksi) && !empty($data['kondisikerja']->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($data['kondisikerja']->tgl_update_produksi ?? $data['kondisikerja']->tgl_update_produksi))
				->format('d-m-Y | H:i');

			$qr_produksi_text = "Diketahui secara digital oleh,\n"
				. $data['kondisikerja']->nama_lengkap_produksi . "\n"
				. "Foreman/Forelady Produksi\n"
				. $prod_tanggal;
		}

		$spv_tanggal = !empty($data['kondisikerja']->tgl_update_spv)
			? (new DateTime($data['kondisikerja']->tgl_update_spv))->format('d-m-Y | H:i')
			: '-';

		$qr_spv_text = "Disetujui secara digital oleh,\n"
			. $data['kondisikerja']->nama_lengkap_spv . "\n"
			. "Supervisor QC Bread Crumb\n"
			. $spv_tanggal;

		if ($status_verifikasi) {
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(20, $y_ttd);
			$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(85, $y_ttd);
			$pdf->Cell(45, 5, 'Diketahui Oleh,', 0, 0, 'C');
			$pdf->SetXY(150, $y_ttd);
			$pdf->Cell(45, 5, 'Disetujui Oleh,', 0, 1, 'C');
			$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
			if ($qr_produksi_text) {
				$pdf->write2DBarcode($qr_produksi_text, 'QRCODE,L', 100, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
			}
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

		$pdf->setPrintFooter(false);
		$filename = "kondisi Kerja_{$formatted_date2}.pdf";
		$pdf->Output($filename, 'I');
	}

	public function cetak_audit()
	{
		$tanggal = $this->input->post('tanggal');
		$shift   = $this->input->post('shift');

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		if (empty($plant)) {
			show_error('Plant tidak ditemukan di session.', 403);
		}

		// =========================================================
		// KHUSUS DATA AUDIT
		// =========================================================
		$kondisikerja_data = $this->kondisikerja_model
			->get_audit_by_date($tanggal, $plant, $shift);

		if (empty($kondisikerja_data)) {
			show_error(
				'Data audit tidak ditemukan, pilih tanggal yang ingin dicetak.',
				404
			);
		}

		// =========================================================
		// DATA UTAMA
		// =========================================================
		$data['kondisikerja'] = $kondisikerja_data[0];

		$this->load->model('pegawai_model');

		$data['kondisikerja']->nama_lengkap_qc =
			$this->pegawai_model->get_nama_lengkap(
				$data['kondisikerja']->username
			);

		$data['kondisikerja']->nama_lengkap_spv =
			$this->pegawai_model->get_nama_lengkap(
				$data['kondisikerja']->nama_spv
			);

		$data['kondisikerja']->nama_lengkap_produksi =
			$data['kondisikerja']->nama_produksi;

		// =========================================================
		// TCPDF
		// =========================================================
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
		$pdf->SetFont('times', 'B', 11);

		// Logo
		$logo_path = FCPATH . 'assets/img/logo.jpg';

		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 38);
		}

		$pdf->Write(7, "\n");

		// =========================================================
		// JUDUL
		// =========================================================
		$pdf->MultiCell(
			0,
			5,
			'KONDISI KERJA SELAMA PRODUKSI - AUDIT',
			0,
			'C'
		);

		$pdf->Ln(5);

		// =========================================================
		// TANGGAL / SHIFT / AREA
		// =========================================================
		setlocale(
			LC_TIME,
			'id_ID.UTF-8',
			'id_ID',
			'indonesian'
		);

		$tanggal_pdf = $data['kondisikerja']->date;

		$date = new DateTime($tanggal_pdf);

		$formatted_date =
			date('l, d F Y', $date->getTimestamp());

		$formatted_date2 =
			date('l, d F Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 9);

		$pdf->SetX(10);

		$pdf->Write(
			0,
			'Hari / Tanggal : ' . $formatted_date
		);

		$pdf->SetX($pdf->GetX() + 20);

		$pdf->Write(
			0,
			'Shift: ' . $data['kondisikerja']->shift
		);

		$pdf->SetX($pdf->GetX() + 20);

		$pdf->Write(
			0,
			'Area: ' . $data['kondisikerja']->area
		);

		$pdf->Ln(5);

		// =========================================================
		// HEADER TABEL
		// =========================================================
		$pdf->SetFont('times', '', 10);

		$pdf->Cell(15, 12, 'Pukul', 1, 0, 'C');
		$pdf->Cell(35, 12, 'ITEM', 1, 0, 'C');
		$pdf->Cell(25, 5, 'Kondisi', 1, 0, 'C');
		$pdf->Cell(40, 12, 'Problem', 1, 0, 'C');
		$pdf->Cell(40, 12, 'Tindakan Koreksi', 1, 0, 'C');
		$pdf->Cell(35, 12, 'Verifikasi', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$pdf->Cell(50, 5, '', 0, 0, 'L');

		$pdf->SetFont('times', '', 8);

		$pdf->Cell(12, 7, 'OK', 1, 0, 'C');
		$pdf->Cell(13, 7, 'Tidak Ok', 1, 0, 'C');

		$pdf->Cell(94, 5, '', 0, 0, 'C');
		$pdf->Cell(10, 7, '', 0, 1, 'C');

		// =========================================================
		// ISI TABEL
		// =========================================================
		foreach ($kondisikerja_data as $kondisikerja) {

			$created_time = '-';

			if (!empty($kondisikerja->waktu)) {
				$time2 = new DateTime($kondisikerja->waktu);
				$created_time = $time2->format('H:i');
			}

			$ok_values = ['✓', '-'];
			$not_ok_values = [
				'x',
				'X',
				'1',
				'2',
				'3',
				'4',
				'5',
				'6',
				'7'
			];

			$kondisi_higiene =
				trim($kondisikerja->kondisi_higiene);

			$higiene_ok =
				in_array($kondisi_higiene, $ok_values)
				? $kondisi_higiene
				: '';

			$higiene_not_ok =
				in_array($kondisi_higiene, $not_ok_values)
				? $kondisi_higiene
				: '';

			$kondisi_peralatan =
				trim($kondisikerja->kondisi_peralatan);

			$peralatan_ok =
				in_array($kondisi_peralatan, $ok_values)
				? $kondisi_peralatan
				: '';

			$peralatan_not_ok =
				in_array($kondisi_peralatan, $not_ok_values)
				? $kondisi_peralatan
				: '';

			$kondisi_kebersihan =
				trim($kondisikerja->kondisi_kebersihan);

			$kebersihan_ok =
				in_array($kondisi_kebersihan, $ok_values)
				? $kondisi_kebersihan
				: '';

			$kebersihan_not_ok =
				in_array($kondisi_kebersihan, $not_ok_values)
				? $kondisi_kebersihan
				: '';

			// -----------------------------------------------------
			// HIGIENE KARYAWAN
			// -----------------------------------------------------
			$pdf->SetFont('times', '', 8);

			$pdf->Cell(
				15,
				15,
				$created_time,
				1,
				0,
				'C'
			);

			$pdf->Cell(
				35,
				5,
				'Higiene Karyawan',
				1,
				0,
				'L'
			);

			$pdf->SetFont('dejavusans', '', 9);

			$pdf->Cell(
				12,
				5,
				$higiene_ok,
				1,
				0,
				'C'
			);

			$pdf->SetFont('times', '', 8);

			$pdf->Cell(
				13,
				5,
				$higiene_not_ok,
				1,
				0,
				'C'
			);

			$pdf->Cell(
				40,
				5,
				$kondisikerja->problem_higiene,
				1,
				0,
				'L'
			);

			$pdf->Cell(
				40,
				5,
				$kondisikerja->tindakan_higiene,
				1,
				0,
				'L'
			);

			$pdf->Cell(
				35,
				5,
				$kondisikerja->verifikasi_higiene,
				1,
				0,
				'L'
			);

			$pdf->Cell(10, 5, '', 0, 1, 'C');

			// -----------------------------------------------------
			// KEBERSIHAN PERALATAN
			// -----------------------------------------------------
			$pdf->Cell(15, 0, '', 0, 0);

			$pdf->Cell(
				35,
				5,
				'Kebersihan Peralatan',
				1,
				0,
				'L'
			);

			$pdf->SetFont('dejavusans', '', 9);

			$pdf->Cell(
				12,
				5,
				$peralatan_ok,
				1,
				0,
				'C'
			);

			$pdf->SetFont('times', '', 8);

			$pdf->Cell(
				13,
				5,
				$peralatan_not_ok,
				1,
				0,
				'C'
			);

			$pdf->Cell(
				40,
				5,
				$kondisikerja->problem_peralatan,
				1,
				0,
				'L'
			);

			$pdf->Cell(
				40,
				5,
				$kondisikerja->tindakan_peralatan,
				1,
				0,
				'L'
			);

			$pdf->Cell(
				35,
				5,
				$kondisikerja->verifikasi_peralatan,
				1,
				0,
				'L'
			);

			$pdf->Cell(10, 5, '', 0, 1);

			// -----------------------------------------------------
			// KEBERSIHAN AREA
			// -----------------------------------------------------
			$pdf->Cell(
				15,
				0,
				'',
				0,
				0,
				'C'
			);

			$pdf->Cell(
				35,
				5,
				'Kebersihan Area',
				1,
				0,
				'L'
			);

			$pdf->SetFont('dejavusans', '', 8);

			$pdf->Cell(
				12,
				5,
				$kebersihan_ok,
				1,
				0,
				'C'
			);

			$pdf->Cell(
				13,
				5,
				$kebersihan_not_ok,
				1,
				0,
				'C'
			);

			$pdf->SetFont('times', '', 8);

			$pdf->Cell(
				40,
				5,
				$kondisikerja->problem_kebersihan,
				1,
				0,
				'L'
			);

			$pdf->Cell(
				40,
				5,
				$kondisikerja->tindakan_kebersihan,
				1,
				0,
				'L'
			);

			$pdf->Cell(
				35,
				5,
				$kondisikerja->verifikasi_kebersihan,
				1,
				0,
				'L'
			);

			$pdf->Cell(10, 0, '', 0, 0);
			$pdf->Cell(10, 5, '', 0, 1, 'C');
		}

		// =========================================================
		// FORM CODE
		// =========================================================
		$pdf->SetFont('times', 'I', 7);

		$pdf->Cell(
			190,
			5,
			'QB 03/00',
			0,
			1,
			'R'
		);

		// =========================================================
		// KETERANGAN
		// =========================================================
		$pdf->SetY($pdf->GetY() + 3);

		$pdf->SetFont('dejavusans', '', 5);

		$kiri =
			"1 Berdebu\n" .
			"2 Basah, ada genangan air\n" .
			"3 Sisa produksi (remah-remah roti, tepung, sisa adonan)\n" .
			"4 Noda (karat, cat, tinta)\n" .
			"5 Pertumbuhan mikroorganisme (jamur, bau busuk, biofilm)\n" .
			"6 Kontak / kontaminasi material non halal\n" .
			"7 Higiene karyawan tidak sesuai GMP";

		$kanan =
			"✓ : Ok, sesuai SSOP, bersih, bebas najis / material non halal\n" .
			"✗ : Tidak Ok, tidak sesuai SSOP\n" .
			"- : Tidak ada atau tidak digunakan";

		$posY = $pdf->GetY();

		$pdf->SetXY(10, $posY);
		$pdf->MultiCell(90, 4, $kiri, 0, 'L');

		$pdf->SetXY(105, $posY);
		$pdf->MultiCell(90, 4, $kanan, 0, 'L');

		// =========================================================
		// CATATAN
		// =========================================================
		$pdf->SetY($pdf->GetY() + 10);

		$pdf->SetFont('times', '', 8);

		$pdf->Cell(
			5,
			3,
			'Catatan : ',
			0,
			1,
			'L'
		);

		foreach ($kondisikerja_data as $item) {

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

		// =========================================================
		// STATUS VERIFIKASI
		// =========================================================
		$y_ttd   = $pdf->GetY() + 6;
		$qr_size = 15;

		$status_verifikasi = true;

		foreach ($kondisikerja_data as $item) {

			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		// =========================================================
		// QC
		// =========================================================
		$qc_usernames  = [];
		$qc_created_at = null;

		foreach ($kondisikerja_data as $item) {

			if (!empty($item->username)) {
				$qc_usernames[] = $item->username;
			}

			if (
				!$qc_created_at &&
				!empty($item->created_at)
			) {
				$qc_created_at = $item->created_at;
			}
		}

		$qc_usernames = array_unique($qc_usernames);

		$qc_nama_lengkap = [];

		foreach ($qc_usernames as $username) {

			$nama = $this->pegawai_model
				->get_nama_lengkap($username);

			if (!empty($nama)) {
				$qc_nama_lengkap[] = $nama;
			}
		}

		$qc_nama_text = !empty($qc_nama_lengkap)
			? implode(', ', array_unique($qc_nama_lengkap))
			: '-';

		$qc_tanggal = $qc_created_at
			? (new DateTime($qc_created_at))->format('d-m-Y | H:i')
			: '-';

		$qr_qc_text =
			"Dibuat secara digital oleh,\n" .
			$qc_nama_text . "\n" .
			"QC Inspector\n" .
			$qc_tanggal;

		// =========================================================
		// PRODUKSI
		// =========================================================
		$qr_produksi_text = null;

		if (
			!empty($data['kondisikerja']->nama_lengkap_produksi) &&
			!empty($data['kondisikerja']->tgl_update_produksi)
		) {

			$prod_tanggal =
				(new DateTime(
					$data['kondisikerja']->tgl_update_produksi
				))->format('d-m-Y | H:i');

			$qr_produksi_text =
				"Diketahui secara digital oleh,\n" .
				$data['kondisikerja']->nama_lengkap_produksi . "\n" .
				"Foreman/Forelady Produksi\n" .
				$prod_tanggal;
		}

		// =========================================================
		// SPV
		// =========================================================
		$spv_tanggal =
			!empty($data['kondisikerja']->tgl_update_spv)
			? (new DateTime(
				$data['kondisikerja']->tgl_update_spv
			))->format('d-m-Y | H:i')
			: '-';

		$qr_spv_text =
			"Disetujui secara digital oleh,\n" .
			$data['kondisikerja']->nama_lengkap_spv . "\n" .
			"Supervisor QC Bread Crumb\n" .
			$spv_tanggal;

		// =========================================================
		// QR & TANDA TANGAN
		// =========================================================
		if ($status_verifikasi) {

			$pdf->SetFont('times', '', 8);

			$pdf->SetXY(20, $y_ttd);
			$pdf->Cell(
				45,
				5,
				'Dibuat Oleh,',
				0,
				0,
				'C'
			);

			$pdf->SetXY(85, $y_ttd);
			$pdf->Cell(
				45,
				5,
				'Diketahui Oleh,',
				0,
				0,
				'C'
			);

			$pdf->SetXY(150, $y_ttd);
			$pdf->Cell(
				45,
				5,
				'Disetujui Oleh,',
				0,
				1,
				'C'
			);

			$pdf->write2DBarcode(
				$qr_qc_text,
				'QRCODE,L',
				35,
				$y_ttd + 5,
				$qr_size,
				$qr_size,
				null,
				'N'
			);

			if ($qr_produksi_text) {

				$pdf->write2DBarcode(
					$qr_produksi_text,
					'QRCODE,L',
					100,
					$y_ttd + 5,
					$qr_size,
					$qr_size,
					null,
					'N'
				);
			}

			$pdf->write2DBarcode(
				$qr_spv_text,
				'QRCODE,L',
				165,
				$y_ttd + 5,
				$qr_size,
				$qr_size,
				null,
				'N'
			);

			$pdf->SetXY(20, $y_ttd + 20);

			$pdf->Cell(
				45,
				5,
				'QC Inspector',
				0,
				0,
				'C'
			);

			$pdf->SetXY(85, $y_ttd + 20);

			$pdf->Cell(
				45,
				5,
				'Foreman/Forelady Produksi',
				0,
				0,
				'C'
			);

			$pdf->SetXY(150, $y_ttd + 20);

			$pdf->Cell(
				45,
				5,
				'Supervisor QC',
				0,
				1,
				'C'
			);
		} else {

			$pdf->SetFont('times', '', 8);
			$pdf->SetTextColor(255, 0, 0);

			$pdf->SetXY(80, $y_ttd);

			$pdf->Cell(
				80,
				6,
				'Data Belum Diverifikasi',
				0,
				1,
				'C'
			);

			$pdf->SetTextColor(0, 0, 0);
		}

		// =========================================================
		// OUTPUT
		// =========================================================
		$pdf->setPrintFooter(false);

		$filename =
			"Kondisi_Kerja_Audit_{$formatted_date2}.pdf";

		$pdf->Output(
			$filename,
			'I'
		);
	}
}
