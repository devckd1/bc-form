<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;
setlocale(LC_TIME, 'id_ID.UTF-8');

class Timbangan extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model'); 
		$this->load->model('timbangan_model');
		$this->load->model('list_timbangan_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'timbangan' => $this->timbangan_model->get_data_by_plant()
		);

		$this->active_nav = 'timbangan'; 
		$this->render('form/timbangan/timbangan', $data);
	}

	public function detail($uuid)
	{
		$data = array(
			'timbangan' => $this->timbangan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'timbangan'; 
		$this->render('form/timbangan/timbangan-detail', $data);
	}

	// public function tambah()
	// {
	// 	$rules = $this->timbangan_model->rules();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {
	// 		$insert = $this->timbangan_model->insert();
	// 		if ($insert) {
	// 			$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Timbangan berhasil di simpan');
	// 			redirect('timbangan');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Timbangan gagal di simpan');
	// 			redirect('timbangan');
	// 		}
	// 	}

	// 	$this->active_nav = 'timbangan'; 
	// 	$this->render('form/timbangan/timbangan-tambah');
	// }

	public function tambah()
	{
		$data['list_timbangan'] = $this->list_timbangan_model->get_by_plant();

		$rules = $this->timbangan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$insert = $this->timbangan_model->insert();

			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Timbangan berhasil di simpan');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Timbangan gagal di simpan');
			}

			redirect('timbangan');
		}

		$this->active_nav = 'timbangan'; 
		$this->render('form/timbangan/timbangan-tambah', $data);
	}

	// public function edit($uuid)
	// {
	// 	$rules = $this->timbangan_model->rules();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {
	
	// 		$update = $this->timbangan_model->update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Timbangan berhasil di Update');
	// 			redirect('timbangan');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Timbangan gagal di Update');
	// 			redirect('timbangan');
	// 		}
	// 	}

	// 	$data = array(
	// 		'timbangan' => $this->timbangan_model->get_by_uuid($uuid),
	// 	);

	// 	$this->active_nav = 'timbangan'; 
	// 	$this->render('form/timbangan/timbangan-edit', $data);
	// }

	public function edit($uuid)
	{
    // Ambil list master
		$data['list_timbangan'] = $this->db
		->order_by('kode_timbangan', 'ASC')
		->get('list_timbangan')
		->result();

		$data['timbangan'] = $this->timbangan_model->get_by_uuid($uuid);

		if (!$data['timbangan']) {
			show_404();
		}

		$rules = $this->timbangan_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->timbangan_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Timbangan berhasil di Update');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Timbangan gagal di Update');
			}

			redirect('timbangan');
		}

		$this->active_nav = 'timbangan'; 
		$this->render('form/timbangan/timbangan-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('timbangan');
		}

		$deleted = $this->timbangan_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('timbangan');
	}
	
	public function verifikasi()
	{
		$data = array(
			'timbangan' => $this->timbangan_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-timbangan'; 
		$this->render('form/timbangan/timbangan-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->timbangan_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->timbangan_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Pemeriksaan Timbangan berhasil di Update');
				redirect('timbangan/verifikasi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Pemeriksaan Timbangan gagal di Update');
				redirect('timbangan/verifikasi');
			}
		}

		$data = array(
			'timbangan' => $this->timbangan_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-timbangan'; 
		$this->render('form/timbangan/timbangan-status', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'timbangan' => $this->timbangan_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-timbangan', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/timbangan/timbangan-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }

	// public function statusprod($uuid)
	// {
	// 	$rules = $this->timbangan_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->timbangan_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Pemeriksaan Timbangan berhasil di Update');
	// 			redirect('timbangan/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Pemeriksaan Timbangan gagal di Update');
	// 			redirect('timbangan/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'timbangan' => $this->timbangan_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-timbangan');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/timbangan/timbangan-statusprod', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal');
		$shift   = $this->input->post('shift');

		log_message('debug', 'Tanggal yang dipilih: ' . print_r($tanggal, true));
		log_message('debug', 'Shift yang dipilih: ' . print_r($shift, true));

		if (empty($tanggal) || empty($shift)) {
			show_error('Tanggal dan shift harus dipilih', 404);
		}

		$plant = $this->session->userdata('plant');

		$timbangan_data = $this->timbangan_model->get_by_date($tanggal, $plant, $shift);
		$timbangan_data_verif = $this->timbangan_model->get_last_verif_by_date($tanggal, $plant, $shift);


		if (!$timbangan_data || !$timbangan_data_verif) {
			show_error('Data tidak ditemukan, pilih tanggal dan shift yang ingin dicetak', 404);
		}

		$data['timbangan'] = $timbangan_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(17, 16, 15);
		$pdf->AddPage('L', 'LEGAL');
		$pdf->SetFont('times', 'B', 15);

    // === LOGO ===
		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 17, 14, 45);
		}

		$pdf->Write(9, "\n");
		$pdf->MultiCell(0, 5, 'PEMERIKSAAN TIMBANGAN', 0, 'C');
		$pdf->Ln(5);

    // === FORMAT TANGGAL ===
		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['timbangan']->date;
		$date = new DateTime($tanggal);
		$formatted_date = date('l, d F Y', $date->getTimestamp());
		$formatted_date2 = date('l, d F Y', $date->getTimestamp());

    // === INFORMASI HEADER (Tanggal + Shift) ===
		$pdf->SetFont('times', '', 10);
		$pdf->SetX(16);
		$pdf->Write(0, 'Tanggal : ' . $formatted_date);
		$pdf->SetX($pdf->GetX() + 10);
		$pdf->Write(0, 'Shift : ' . $data['timbangan']->shift);
		$pdf->Ln(5);

    // === HEADER TABEL (tidak diubah) ===
		$pdf->SetFont('times', '', 11);
		$pdf->Cell(10, 15, 'No.', 1, 0, 'C');
		$pdf->Cell(60, 15, 'Jenis dan Kode Timbangan', 1, 0, 'C');
		$pdf->Cell(25, 15, 'Kapasitas (kg)', 1, 0, 'C');
		$pdf->Cell(25, 15, 'Lokasi', 1, 0, 'C');
		$pdf->Cell(25, 15, 'Standar', 1, 0, 'C');
		$pdf->Cell(140, 5, 'Hasil Pemeriksaan', 1, 0, 'C');
		$pdf->Cell(38, 15, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$pdf->Cell(145, 15, '', 0, 0, 'C');
		$pdf->SetFont('times', '', 9);
		for ($i = 1; $i <= 7; $i++) {
			$pdf->Cell(20, 5, 'Check-' . $i, 1, 0, 'C');
		}
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$pdf->Cell(120, 15, '', 0, 0, 'C');
		$pdf->SetFont('times', '', 11);
		$pdf->Cell(25, 1, 'Berat (g)', 0, 0, 'C');
		$pdf->SetFont('times', '', 9);
		for ($i = 1; $i <= 7; $i++) {
			$pdf->Cell(10, 5, 'Pukul', 1, 0, 'C');
			$pdf->Cell(10, 5, 'Hasil', 1, 0, 'C');
		}
		$pdf->Cell(38, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$merged = [];

		foreach ($timbangan_data as $row) {
			$hasil_array = json_decode($row->peneraan_hasil, true);
			if (!is_array($hasil_array)) continue;

			foreach ($hasil_array as $hasil) {
        // 🔹 Semua nilai diambil dari JSON, bukan kolom utama
				$model     = $hasil['model'] ?? '-';
				$kode      = $hasil['kode_timbangan'] ?? '-';
				$kapasitas = $hasil['kapasitas'] ?? '-';
				$lokasi    = $hasil['lokasi'] ?? '-';
				$standar   = $hasil['peneraan_standar'] ?? '-';
				$pukul     = $hasil['pukul'] ?? '';
				$hasil_uji = $hasil['hasil'] ?? '';

        // 🔹 Shift diambil dari tabel utama
				$shift = $row->shift ?? '-';

        // 🔹 Buat key unik gabungan model + kode + lokasi + shift
				$key = "{$model}|{$kode}|{$lokasi}|{$shift}";

				if (!isset($merged[$key])) {
					$merged[$key] = [
						'model' => $model,
						'kode_timbangan' => $kode,
						'kapasitas' => $kapasitas,
						'lokasi' => $lokasi,
						'peneraan_standar' => $standar,
						'shift' => $shift,
						'checks' => [],
						'keterangan' => $row->keterangan ?? '-',
						'catatan' => $row->catatan ?? ''
					];
				}

        // 🔹 Gabungkan hasil pemeriksaan berdasarkan pukul & hasil
				$merged[$key]['checks'][] = [
					'pukul' => $pukul,
					'hasil' => $hasil_uji
				];
			}
		}


    // === CETAK ISI DATA ===
		$no = 1;
		foreach ($merged as $item) {
			$pdf->SetFont('times', '', 10);
			$pdf->Cell(10, 8, $no++, 1, 0, 'C');
			$pdf->Cell(60, 8, $item['model'] . ' / ' . $item['kode_timbangan'], 1, 0, 'C');
			$pdf->Cell(25, 8, $item['kapasitas'], 1, 0, 'C');
			$pdf->Cell(25, 8, $item['lokasi'], 1, 0, 'C');
			$pdf->Cell(25, 8, $item['peneraan_standar'], 1, 0, 'C');

			for ($i = 0; $i < 7; $i++) {
				$pukul = $item['checks'][$i]['pukul'] ?? '';
				$hasil = $item['checks'][$i]['hasil'] ?? '';
				$pdf->Cell(10, 8, $pukul, 1, 0, 'C');
				$pdf->Cell(10, 8, $hasil, 1, 0, 'C');
			}

			$pdf->Cell(38, 8, $item['keterangan'], 1, 1, 'C');
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(315, 5, 'QB 08/00', 0, 1, 'R'); 

    // === CATATAN ===
		$pdf->SetY($pdf->GetY() + 3);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(10, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($merged as $item) {
			if (!empty($item['catatan'])) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item['catatan'], 0, 1, 'L');
			}
		}

    // === QR / TANDA TANGAN DIGITAL ===
		$this->load->model('pegawai_model');
		$data['timbangan']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['timbangan']->username);
		$data['timbangan']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['timbangan']->nama_spv);
		$data['timbangan']->nama_lengkap_produksi = $data['timbangan']->nama_produksi;

		$y_after_keterangan = $pdf->GetY();
		$status_verifikasi = true;
		foreach ($timbangan_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		$y_ttd   = $pdf->GetY() + 6;
		$qr_size = 15;

		$qc_usernames  = [];
		$qc_created_at = null;

		foreach ($timbangan_data as $item) {
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

		if (!empty($data['timbangan']->nama_lengkap_produksi) && !empty($data['timbangan']->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($data['timbangan']->tgl_update_produksi ?? $data['timbangan']->tgl_update_produksi))
			->format('d-m-Y | H:i');

			$qr_produksi_text = "Diketahui secara digital oleh,\n"
			. $data['timbangan']->nama_lengkap_produksi . "\n"
			. "Foreman/Forelady Produksi\n"
			. $prod_tanggal;
		}

		$spv_tanggal = !empty($data['timbangan']->tgl_update_spv)
		? (new DateTime($data['timbangan']->tgl_update_spv))->format('d-m-Y | H:i')
		: '-';

		$qr_spv_text = "Disetujui secara digital oleh,\n"
		. $data['timbangan']->nama_lengkap_spv . "\n"
		. "Supervisor QC Bread Crumb\n"
		. $spv_tanggal;

		if ($status_verifikasi) {
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(20, $y_ttd);
			$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(85, $y_ttd);
			$pdf->Cell(130, 5, 'Diketahui Oleh,', 0, 0, 'C');
			$pdf->SetXY(150, $y_ttd);
			$pdf->Cell(220, 5, 'Disetujui Oleh,', 0, 1, 'C');
			$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35,$y_ttd + 5, $qr_size, $qr_size, null, 'N');
			if ($qr_produksi_text) {
				$pdf->write2DBarcode($qr_produksi_text, 'QRCODE,L', 143, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
			}
			$pdf->write2DBarcode($qr_spv_text, 'QRCODE,L', 253, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
			$pdf->SetXY(20, $y_ttd + 20);
			$pdf->Cell(45, 5, 'QC Inspector', 0, 0, 'C');
			$pdf->SetXY(85, $y_ttd + 20);
			$pdf->Cell(130, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
			$pdf->SetXY(150, $y_ttd + 20);
			$pdf->Cell(220, 5, 'Supervisor QC', 0, 1, 'C');
		} else {
			$pdf->SetFont('times', '', 8);
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetXY(80, $y_ttd);
			$pdf->Cell(80, 6, 'Data Belum Diverifikasi', 0, 1, 'C');
			$pdf->SetTextColor(0, 0, 0);
		}

		$pdf->setPrintFooter(false);
		$filename = "Pemeriksaan Timbangan_{$formatted_date2}_Shift{$shift}.pdf";
		$pdf->Output($filename, 'I');
	}

}

