<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Falserejection extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('falserejection_model');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'falserejection' => $this->falserejection_model->get_data_by_plant()
		);

		$this->active_nav = 'falserejection';
		$this->render('form/falserejection/falserejection', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'falserejection' => $this->falserejection_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'falserejection';
		$this->render('form/falserejection/falserejection-detail', $data);
	}

	public function edit($uuid)
	{
		$rules = $this->falserejection_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->falserejection_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data False Rejection berhasil di Update');
				redirect('falserejection');
			} else {
				$this->session->set_flashdata('error_msg', 'Data False Rejection gagal di Update');
				redirect('falserejection');
			}
		}

		$data = array(
			'falserejection' => $this->falserejection_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'falserejection';
		$this->render('form/falserejection/falserejection-edit', $data);
	}

	public function verifikasi()
	{
		$data = array(
			'falserejection' => $this->falserejection_model->get_data_by_plant(),
		);

		$this->active_nav = 'verifikasi-falserejection';
		$this->render('form/falserejection/falserejection-verifikasi', $data);
	}


	public function status($uuid)
	{
		$rules = $this->falserejection_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->falserejection_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data False Rejection berhasil di Update');
				redirect('falserejection/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data False Rejection gagal di Update');
				redirect('falserejection/verifikasi');
			}
		}

		$data = array(
			'falserejection' => $this->falserejection_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-falserejection';
		$this->render('form/falserejection/falserejection-status', $data);
	}

	public function audit()
	{
		$data = array(
			'falserejection' => $this->falserejection_model->get_audit_by_plant()
		);

		$this->active_nav = 'falserejection';
		$this->render('form/falserejection/falserejection-audit', $data);
	}

	public function detail_audit($uuid)
	{
		$audit = $this->falserejection_model->get_audit_by_uuid($uuid);

		if (!$audit) {
			show_404();
			return;
		}

		$data = array(
			'falserejection' => $audit
		);

		$this->active_nav = 'falserejection';
		$this->render('form/falserejection/falserejection-detail', $data);
	}

	public function edit_audit($uuid)
	{
		$audit = $this->falserejection_model->get_audit_by_uuid($uuid);

		if (!$audit) {
			show_404();
			return;
		}

		$this->form_validation->set_rules(
			$this->falserejection_model->rules()
		);

		if ($this->form_validation->run() === TRUE) {
			if ($this->falserejection_model->update_audit($uuid)) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Audit False Rejection berhasil diperbarui.'
				);
				redirect('falserejection/audit');
				return;
			}

			$this->session->set_flashdata(
				'error_msg',
				'Data Audit False Rejection gagal diperbarui.'
			);
		}

		$data = array(
			'falserejection' => $audit
		);

		$this->active_nav = 'falserejection';
		$this->render('form/falserejection/falserejection-edit', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'falserejection' => $this->falserejection_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-falserejection', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/falserejection/falserejection-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->falserejection_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->falserejection_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status False Rejection berhasil di Update');
	// 			redirect('falserejection/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status False Rejection gagal di Update');
	// 			redirect('falserejection/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'falserejection' => $this->falserejection_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-falserejection');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/falserejection/falserejection-statusprod', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal');

		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$this->load->model('falserejection_model');

		$falserejection_data = $this->falserejection_model->get_by_date($tanggal);
		$falserejection_data_verif = $this->falserejection_model->get_last_verif_by_date($tanggal);

		if (!$falserejection_data || !$falserejection_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['falserejection'] = $falserejection_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
		$pdf = new TCPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 9.5, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 12);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 30);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(7, "\n");
		$pdf->MultiCell(0, 5, 'MONITORING FALSE REJECTION', 0, 'C');
		$pdf->Ln(3);

		$pdf->SetFont('times', 'B', 10);
		$pdf->SetX(10);
		$pdf->Write(0, 'Mesin: ' . (!empty($data['metal']->no_mesin) ? $data['metal']->no_mesin : ' - '));
		$pdf->Ln(5);

		$pdf->SetFont('times', 'B', 10);

		$pdf->Cell(25, 12, 'Tanggal / Shift', 1, 0, 'C');
		$pdf->Cell(55, 12, 'Nama Produk', 1, 0, 'C');
		$pdf->Cell(45, 12, 'Kode Produksi', 1, 0, 'C');
		$pdf->Cell(37, 12, 'Jumlah Pack/Bag', 1, 0, 'C');
		$pdf->Cell(37, 12, 'Jumlah Pack/Bag yang', 1, 0, 'C');
		$pdf->Cell(37, 12, 'Jenis Kontaminan', 1, 0, 'C');
		$pdf->Cell(37, 12, 'Posisi Kontaminan', 1, 0, 'C');
		$pdf->Cell(37, 12, 'False Rejection', 1, 0, 'C');
		$pdf->Cell(25, 12, 'Paraf QC', 1, 0, 'C');
		$pdf->Cell(25, 6, '', 0, 1, 'C');

		$pdf->Cell(25, 6, '', 0, 0, 'C');
		$pdf->Cell(55, 6, '', 0, 0, 'C');
		$pdf->Cell(45, 6, '', 0, 0, 'C');
		$pdf->Cell(37, 7, 'yang Tidak Lolos', 0, 0, 'C');
		$pdf->Cell(37, 7, 'Terdapat Kontaminan', 0, 0, 'C');
		$pdf->Cell(37, 6, '', 0, 0, 'C');
		$pdf->Cell(37, 6, '', 0, 0, 'C');
		$pdf->Cell(37, 0, '', 0, 0, 'C');
		$pdf->Cell(25, 6, '', 0, 1, 'C');

		$pdf->SetFont('times', '', 10);
		foreach ($falserejection_data as $falserejection) {
			$dateFormatted = date('d-m-Y', strtotime($falserejection->date_false_rejection));
			$pdf->Cell(25, 6, $dateFormatted . ' / ' . $falserejection->shift_monitoring, 1, 0, 'C');

			$pdf->Cell(55, 6, $falserejection->nama_produk, 1, 0, 'C');
			$pdf->Cell(45, 6, $falserejection->kode_produksi, 1, 0, 'C');
			$pdf->Cell(37, 6, $falserejection->jumlah_tidak_lolos, 1, 0, 'C');
			$pdf->Cell(37, 6, $falserejection->jumlah_kontaminasi, 1, 0, 'C');
			$pdf->Cell(37, 6, $falserejection->jenis_kontaminasi, 1, 0, 'C');
			$pdf->Cell(37, 6, $falserejection->posisi_kontaminasi, 1, 0, 'C');
			$pdf->Cell(37, 6, $falserejection->false_rejection, 1, 0, 'C');
			$pdf->Cell(25, 6, $falserejection->username_2, 1, 0, 'C');
			$pdf->Ln();
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(330, 5, 'QB 13/00', 0, 1, 'R');

		// Ambil nama lengkap dari model
		$this->load->model('pegawai_model');
		$nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['falserejection']->username_2);
		// $nama_lengkap_prod = $this->pegawai_model->get_nama_lengkap($data['falserejection']->nama_produksi_false);
		$nama_lengkap_prod = $data['falserejection']->nama_produksi_false;
		$nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['falserejection']->nama_spv_false);


		// Waktu update
		$tanggal_update = $data['falserejection']->tgl_update_spv_false;
		$update_tanggal = (new DateTime($tanggal_update))->format('d-m-Y | H:i');

		// Cek status verifikasi
		$status_verifikasi = true;
		foreach ($falserejection_data as $item) {
			if ($item->status_spv_false != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		// Dapatkan posisi terakhir Y dari tabel
		$y_setelah_tabel = $pdf->GetY() + 5;

		// ----------------- CATATAN -------------------
		$pdf->SetFont('times', '', 9);
		$pdf->SetXY(12, $y_setelah_tabel);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($falserejection_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(12, 0, '', 0, 0, 'L');
				$pdf->Cell(180, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 8;

		// ----------------- VERIFIKASI & QR -------------------
		if ($status_verifikasi) {
			$pdf->SetFont('times', '', 9);
			$pdf->SetTextColor(0, 0, 0);

			// Dibuat oleh
			$pdf->SetXY(20, $y_after_keterangan);
			$pdf->Cell(60, 5, 'Diperiksa Oleh,', 0, 0, 'C');
			$pdf->SetXY(20, $y_after_keterangan + 12);
			$pdf->SetFont('times', 'U', 9);
			$pdf->Cell(60, 5, $nama_lengkap_qc, 0, 1, 'C');
			$pdf->SetFont('times', '', 9);
			$pdf->SetXY(20, $y_after_keterangan + 17);
			$pdf->Cell(60, 5, 'QC Inspector', 0, 0, 'C');

			// // Produksi
			// 		$pdf->SetXY(110, $y_after_keterangan);
			// 		$pdf->Cell(80, 5, 'Diketahui Oleh,', 0, 0, 'C');
			// 		if (!empty($data['falserejection']->nama_produksi_false)) {
			// 			$qr_text_prod = "Diketahui secara digital oleh,\n" . $nama_lengkap_prod . "\nForeman/Forelady Produksi\n" . $data['falserejection']->tgl_update_produksi_false;
			// 			$pdf->write2DBarcode($qr_text_prod, 'QRCODE,L', 141, $y_after_keterangan + 5, 17, 17, null, 'N');
			// 			$pdf->SetXY(120, $y_after_keterangan + 22);
			// 			$pdf->Cell(60, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
			// 		}

			// Produksi
			$pdf->SetXY(110, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Diketahui Oleh,', 0, 0, 'C');

			if (!empty($data['falserejection']->nama_produksi_false)) {
				$nama_lengkap_prod = $data['falserejection']->nama_produksi_false;
				$tanggal_update = (new DateTime($data['falserejection']->tgl_update_produksi_false))->format('d-m-Y | H:i');

				$pdf->SetFont('times', 'U', 8);
				$pdf->SetXY(120, $y_after_keterangan + 8);
				$pdf->Cell(60, 5, $nama_lengkap_prod, 0, 1, 'C');

				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(120, $y_after_keterangan + 13);
				$pdf->Cell(60, 5, 'Foreman/Forelady Produksi', 0, 1, 'C');

				// $pdf->SetXY(120, $y_after_keterangan + 18);
				// $pdf->Cell(60, 5, $tanggal_update, 0, 0, 'C');
			} else {
				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(120, $y_after_keterangan + 8);
				$pdf->Cell(60, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}


			// SPV
			$pdf->SetXY(195, $y_after_keterangan);
			$pdf->Cell(90, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 232, $y_after_keterangan + 5, 17, 17, null, 'N');
			$pdf->SetXY(215, $y_after_keterangan + 22);
			$pdf->Cell(50, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			// Belum diverifikasi
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 10);
			$pdf->SetXY(140, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}


		$pdf->setPrintFooter(false);

		$currentDate = date('d-m-Y');
		$filename = "False Rejection_{$currentDate}.pdf";
		$pdf->Output($filename, 'I');
	}

	public function cetak_audit()
	{
		$tanggal = $this->input->post('tanggal');

		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));

		if (empty($tanggal)) {
			show_error('Tidak ada tanggal yang dipilih', 404);
		}

		$this->load->model('falserejection_model');

		$falserejection_data = $this->falserejection_model->get_audit_by_date($tanggal);
		$falserejection_data_verif = $this->falserejection_model->get_last_verif_by_date($tanggal);

		if (!$falserejection_data || !$falserejection_data_verif) {
			show_error('Data tidak ditemukan, Pilih tanggal yang ingin dicetak', 404);
		}

		$data['falserejection'] = $falserejection_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
		$pdf = new TCPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 9.5, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 12);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 30);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(7, "\n");
		$pdf->MultiCell(0, 5, 'MONITORING FALSE REJECTION', 0, 'C');
		$pdf->Ln(3);

		$pdf->SetFont('times', 'B', 10);
		$pdf->SetX(10);
		$pdf->Write(0, 'Mesin: ' . (!empty($data['metal']->no_mesin) ? $data['metal']->no_mesin : ' - '));
		$pdf->Ln(5);

		$pdf->SetFont('times', 'B', 10);

		$pdf->Cell(25, 12, 'Tanggal / Shift', 1, 0, 'C');
		$pdf->Cell(55, 12, 'Nama Produk', 1, 0, 'C');
		$pdf->Cell(45, 12, 'Kode Produksi', 1, 0, 'C');
		$pdf->Cell(37, 12, 'Jumlah Pack/Bag', 1, 0, 'C');
		$pdf->Cell(37, 12, 'Jumlah Pack/Bag yang', 1, 0, 'C');
		$pdf->Cell(37, 12, 'Jenis Kontaminan', 1, 0, 'C');
		$pdf->Cell(37, 12, 'Posisi Kontaminan', 1, 0, 'C');
		$pdf->Cell(37, 12, 'False Rejection', 1, 0, 'C');
		$pdf->Cell(25, 12, 'Paraf QC', 1, 0, 'C');
		$pdf->Cell(25, 6, '', 0, 1, 'C');

		$pdf->Cell(25, 6, '', 0, 0, 'C');
		$pdf->Cell(55, 6, '', 0, 0, 'C');
		$pdf->Cell(45, 6, '', 0, 0, 'C');
		$pdf->Cell(37, 7, 'yang Tidak Lolos', 0, 0, 'C');
		$pdf->Cell(37, 7, 'Terdapat Kontaminan', 0, 0, 'C');
		$pdf->Cell(37, 6, '', 0, 0, 'C');
		$pdf->Cell(37, 6, '', 0, 0, 'C');
		$pdf->Cell(37, 0, '', 0, 0, 'C');
		$pdf->Cell(25, 6, '', 0, 1, 'C');

		$pdf->SetFont('times', '', 10);
		foreach ($falserejection_data as $falserejection) {
			$dateFormatted = date('d-m-Y', strtotime($falserejection->date_false_rejection));
			$pdf->Cell(25, 6, $dateFormatted . ' / ' . $falserejection->shift_monitoring, 1, 0, 'C');

			$pdf->Cell(55, 6, $falserejection->nama_produk, 1, 0, 'C');
			$pdf->Cell(45, 6, $falserejection->kode_produksi, 1, 0, 'C');
			$pdf->Cell(37, 6, $falserejection->jumlah_tidak_lolos, 1, 0, 'C');
			$pdf->Cell(37, 6, $falserejection->jumlah_kontaminasi, 1, 0, 'C');
			$pdf->Cell(37, 6, $falserejection->jenis_kontaminasi, 1, 0, 'C');
			$pdf->Cell(37, 6, $falserejection->posisi_kontaminasi, 1, 0, 'C');
			$pdf->Cell(37, 6, $falserejection->false_rejection, 1, 0, 'C');
			$pdf->Cell(25, 6, $falserejection->username_2, 1, 0, 'C');
			$pdf->Ln();
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(330, 5, 'QB 13/00', 0, 1, 'R');

		// Ambil nama lengkap dari model
		$this->load->model('pegawai_model');
		$nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['falserejection']->username_2);
		// $nama_lengkap_prod = $this->pegawai_model->get_nama_lengkap($data['falserejection']->nama_produksi_false);
		$nama_lengkap_prod = $data['falserejection']->nama_produksi_false;
		$nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['falserejection']->nama_spv_false);


		// Waktu update
		$tanggal_update = $data['falserejection']->tgl_update_spv_false;
		$update_tanggal = (new DateTime($tanggal_update))->format('d-m-Y | H:i');

		// Cek status verifikasi
		$status_verifikasi = true;
		foreach ($falserejection_data as $item) {
			if ($item->status_spv_false != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		// Dapatkan posisi terakhir Y dari tabel
		$y_setelah_tabel = $pdf->GetY() + 5;

		// ----------------- CATATAN -------------------
		$pdf->SetFont('times', '', 9);
		$pdf->SetXY(12, $y_setelah_tabel);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($falserejection_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(12, 0, '', 0, 0, 'L');
				$pdf->Cell(180, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 8;

		// ----------------- VERIFIKASI & QR -------------------
		if ($status_verifikasi) {
			$pdf->SetFont('times', '', 9);
			$pdf->SetTextColor(0, 0, 0);

			// Dibuat oleh
			$pdf->SetXY(20, $y_after_keterangan);
			$pdf->Cell(60, 5, 'Diperiksa Oleh,', 0, 0, 'C');
			$pdf->SetXY(20, $y_after_keterangan + 12);
			$pdf->SetFont('times', 'U', 9);
			$pdf->Cell(60, 5, $nama_lengkap_qc, 0, 1, 'C');
			$pdf->SetFont('times', '', 9);
			$pdf->SetXY(20, $y_after_keterangan + 17);
			$pdf->Cell(60, 5, 'QC Inspector', 0, 0, 'C');

			// // Produksi
			// 		$pdf->SetXY(110, $y_after_keterangan);
			// 		$pdf->Cell(80, 5, 'Diketahui Oleh,', 0, 0, 'C');
			// 		if (!empty($data['falserejection']->nama_produksi_false)) {
			// 			$qr_text_prod = "Diketahui secara digital oleh,\n" . $nama_lengkap_prod . "\nForeman/Forelady Produksi\n" . $data['falserejection']->tgl_update_produksi_false;
			// 			$pdf->write2DBarcode($qr_text_prod, 'QRCODE,L', 141, $y_after_keterangan + 5, 17, 17, null, 'N');
			// 			$pdf->SetXY(120, $y_after_keterangan + 22);
			// 			$pdf->Cell(60, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
			// 		}

			// Produksi
			$pdf->SetXY(110, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Diketahui Oleh,', 0, 0, 'C');

			if (!empty($data['falserejection']->nama_produksi_false)) {
				$nama_lengkap_prod = $data['falserejection']->nama_produksi_false;
				$tanggal_update = (new DateTime($data['falserejection']->tgl_update_produksi_false))->format('d-m-Y | H:i');

				$pdf->SetFont('times', 'U', 8);
				$pdf->SetXY(120, $y_after_keterangan + 8);
				$pdf->Cell(60, 5, $nama_lengkap_prod, 0, 1, 'C');

				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(120, $y_after_keterangan + 13);
				$pdf->Cell(60, 5, 'Foreman/Forelady Produksi', 0, 1, 'C');

				// $pdf->SetXY(120, $y_after_keterangan + 18);
				// $pdf->Cell(60, 5, $tanggal_update, 0, 0, 'C');
			} else {
				$pdf->SetFont('times', '', 8);
				$pdf->SetXY(120, $y_after_keterangan + 8);
				$pdf->Cell(60, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}


			// SPV
			$pdf->SetXY(195, $y_after_keterangan);
			$pdf->Cell(90, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 232, $y_after_keterangan + 5, 17, 17, null, 'N');
			$pdf->SetXY(215, $y_after_keterangan + 22);
			$pdf->Cell(50, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			// Belum diverifikasi
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 10);
			$pdf->SetXY(140, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}


		$pdf->setPrintFooter(false);

		$currentDate = date('d-m-Y');
		$filename = "False Rejection_{$currentDate}.pdf";
		$pdf->Output($filename, 'I');
	}
}
