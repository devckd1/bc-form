<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;

setlocale(LC_TIME, 'id_ID.UTF-8');

class Thermometer extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('thermometer_model');
		$this->load->model('list_thermometer_model');
		$this->load->model('pegawai_model');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$this->load->library('pagination');

		$tanggal = trim($this->input->get('tanggal', TRUE) ?? '');
		$shift = trim($this->input->get('shift', TRUE) ?? '');

		$config['base_url'] = base_url('thermometer/index');
		$config['total_rows'] = $this->thermometer_model
			->count_thermometer_by_plant($tanggal, $shift);

		$config['per_page'] = 10;
		$config['uri_segment'] = 3;
		$config['reuse_query_string'] = TRUE;
		$config['use_page_numbers'] = FALSE;

		$this->pagination->initialize($config);

		$start = (int) $this->uri->segment(3, 0);

		$data = [
			'thermometer' => $this->thermometer_model->get_data_by_plant(
				$config['per_page'],
				$start,
				$tanggal,
				$shift
			),
			'pagination' => $this->pagination->create_links(),
			'start' => $start,
			'filter_tanggal' => $tanggal,
			'filter_shift' => $shift
		];

		$this->active_nav = 'thermometer';
		$this->render('form/thermometer/thermometer', $data);
	}

	public function detail($uuid)
	{
		$thermometer = $this->thermometer_model->get_qc_by_uuid($uuid);

		if (!$thermometer) {
			show_404();
			return;
		}

		$is_popup = $this->input->get('popup') == '1';

		$data = [
			'thermometer' => $thermometer,
			'mode' => 'qc',
			'is_popup' => $is_popup
		];

		$this->active_nav = 'thermometer';

		if ($is_popup) {
			$this->load->view('form/thermometer/thermometer-detail', $data);
		} else {
			$this->render('form/thermometer/thermometer-detail', $data);
		}
	}

	// public function tambah()
	// {
	// 	$rules = $this->thermometer_model->rules();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {
	// 		$insert = $this->thermometer_model->insert();
	// 		if ($insert) {
	// 			$this->session->set_flashdata('success_msg', 'Data Peneraan Thermometer berhasil di simpan');
	// 			redirect('thermometer');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Data Peneraan Thermometer gagal di simpan');
	// 			redirect('thermometer');
	// 		}
	// 	}

	// 	$this->active_nav = 'thermometer'; 
	// 	$this->render('form/thermometer/thermometer-tambah');
	// }

	public function tambah()
	{
		// Ambil data list thermometer
		$data['list_thermo'] = $this->list_thermometer_model->get_by_plant();

		$rules = $this->thermometer_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$insert = $this->thermometer_model->insert();

			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Peneraan Thermometer berhasil di simpan');
				redirect('thermometer');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Peneraan Thermometer gagal di simpan');
				redirect('thermometer');
			}
		}

		$this->active_nav = 'thermometer';
		$this->render('form/thermometer/thermometer-tambah', $data);
	}

	public function edit($uuid)
	{
		$thermometer = $this->thermometer_model->get_qc_by_uuid($uuid);

		if (!$thermometer) {
			show_404();
			return;
		}

		$data['thermometer'] = $thermometer;
		$data['mode'] = 'qc';

		$data['list_thermo'] = $this->db
			->order_by('kode_thermometer', 'ASC')
			->get('list_thermometer')
			->result();

		$this->form_validation->set_rules(
			$this->thermometer_model->rules()
		);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->thermometer_model->update($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Peneraan Thermometer berhasil di Update'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data Peneraan Thermometer gagal di Update'
				);
			}

			redirect('thermometer');
			return;
		}

		$this->active_nav = 'thermometer';

		$this->render('form/thermometer/thermometer-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('thermometer');
		}

		$deleted = $this->thermometer_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('thermometer');
	}

	public function verifikasi()
	{
		$data = array(
			'thermometer' => $this->thermometer_model->get_data_by_plant()
		);

		$this->active_nav = 'thermometer';
		$this->render('form/thermometer/thermometer', $data);
	}

	public function verifikasi_produksi($uuid)
	{
		if ((int) $this->session->userdata('tipe_user') !== 3) {
			show_error('Anda tidak memiliki akses untuk verifikasi Produksi.', 403);
			return;
		}

		$hasil = $this->thermometer_model->verifikasi_produksi($uuid);

		if ($hasil) {
			$this->session->set_flashdata(
				'success_msg',
				'Data Peneraan Thermometer berhasil di-approve Produksi.'
			);
		} else {
			$this->session->set_flashdata(
				'error_msg',
				'Data gagal diverifikasi atau sudah diverifikasi sebelumnya.'
			);
		}

		redirect('thermometer');
	}

	public function status($uuid)
	{
		$rules = $this->thermometer_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->thermometer_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Peneraan Thermometer berhasil di Update');
				redirect('thermometer/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Peneraan Thermometer gagal di Update');
				redirect('thermometer/verifikasi');
			}
		}

		$data = array(
			'thermometer' => $this->thermometer_model->get_by_uuid($uuid),
		);

		$this->active_nav = 'verifikasi-thermometer';
		$this->render('form/thermometer/thermometer-status', $data);
	}

	public function verifikasi_massal()
	{
		if ($this->input->method(TRUE) !== 'POST') {
			show_error('Method tidak diizinkan.', 405);
			return;
		}

		$tipe_user = (int) $this->session->userdata('tipe_user');

		$tanggal = trim($this->input->post('tanggal', TRUE) ?? '');
		$shift = trim($this->input->post('shift', TRUE) ?? '');
		$jenis_verifikasi = trim($this->input->post('jenis_verifikasi', TRUE) ?? 'spv');

		// Hak akses SPV
		$role_spv = [0, 1, 2, 9];

		// Hak akses Produksi
		$role_produksi = [0, 1, 3, 9];

		if (
			($jenis_verifikasi === 'spv' && !in_array($tipe_user, $role_spv)) ||
			($jenis_verifikasi === 'produksi' && !in_array($tipe_user, $role_produksi))
		) {
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => false,
					'message' => 'Anda tidak memiliki hak akses untuk verifikasi ini.'
				]));
		}

		if (!in_array($jenis_verifikasi, ['spv', 'produksi'], true)) {
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => false,
					'message' => 'Jenis verifikasi tidak valid.'
				]));
		}

		$tanggal_obj = DateTime::createFromFormat('Y-m-d', $tanggal);

		if (
			!$tanggal_obj ||
			$tanggal_obj->format('Y-m-d') !== $tanggal
		) {
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => false,
					'message' => 'Tanggal tidak valid.'
				]));
		}

		if (!in_array($shift, ['', 'semua', '1', '2', '3'], true)) {
			return $this->output
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => false,
					'message' => 'Shift tidak valid.'
				]));
		}

		$updated = $this->thermometer_model->verifikasi_massal(
			$tanggal,
			$shift,
			$jenis_verifikasi
		);

		if ($updated === false) {
			$response = [
				'success' => false,
				'message' => 'Verifikasi massal Thermometer gagal diproses.'
			];
		} elseif ($updated > 0) {
			$nama_verifikasi = $jenis_verifikasi === 'spv' ? 'SPV' : 'Produksi';

			$response = [
				'success' => true,
				'updated' => $updated,
				'message' => 'Verifikasi massal Thermometer ' . $nama_verifikasi . ' berhasil.'
			];
		} else {
			$response = [
				'success' => false,
				'updated' => 0,
				'message' => 'Tidak ada data yang memenuhi syarat verifikasi pada tanggal dan shift tersebut.'
			];
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}

	public function audit()
	{
		$data = [
			'thermometer' => $this->thermometer_model->get_audit_by_plant()
		];

		$this->active_nav = 'thermometer';

		$this->render(
			'form/thermometer/thermometer-audit',
			$data
		);
	}

	public function edit_audit($uuid)
	{
		$thermometer = $this->thermometer_model->get_audit_by_uuid($uuid);

		if (!$thermometer) {
			show_404();
			return;
		}

		$data = [
			'thermometer' => $thermometer,
			'mode' => 'audit',
			'list_thermo' => $this->list_thermometer_model->get_by_plant()
		];

		$this->form_validation->set_rules(
			$this->thermometer_model->rules()
		);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->thermometer_model->update_audit($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Audit Peneraan Thermometer berhasil diupdate.'
				);

				redirect('thermometer/audit');
				return;
			}

			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Peneraan Thermometer gagal diupdate.'
			);
		}

		// Ambil kembali snapshot Audit, bukan data QC.
		$data['thermometer'] = $this->thermometer_model->get_audit_by_uuid($uuid);

		if (!$data['thermometer']) {
			show_404();
			return;
		}

		$this->active_nav = 'thermometer';

		$this->render('form/thermometer/thermometer-edit', $data);
	}

	public function detail_audit($uuid)
	{
		$thermometer = $this->thermometer_model->get_audit_by_uuid($uuid);

		if (!$thermometer) {
			show_404();
			return;
		}

		$data = [
			'thermometer' => $thermometer,
			'mode' => 'audit'
		];

		$this->active_nav = 'thermometer';

		$this->render('form/thermometer/thermometer-detail', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'thermometer' => $this->thermometer_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-thermometer', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/thermometer/thermometer-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->thermometer_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {

	// 		$update = $this->thermometer_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Peneraan Thermometer berhasil di Update');
	// 			redirect('thermometer/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Peneraan Thermometer gagal di Update');
	// 			redirect('thermometer/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'thermometer' => $this->thermometer_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-thermometer');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/thermometer/thermometer-statusprod', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function cetak()
	{
		$tanggal = $this->input->post('tanggal');
		$shift   = $this->input->post('shift');

		if (empty($tanggal) || empty($shift)) {
			show_error('Tanggal dan shift harus dipilih', 404);
		}

		$plant = $this->session->userdata('plant');
		if (empty($plant)) {
			show_error('Plant tidak ditemukan di session.', 403);
		}

		$thermo_data  = $this->thermometer_model->get_by_date($tanggal, $plant, $shift);
		$thermo_verif = $this->thermometer_model->get_last_verif_by_date($tanggal, $plant, $shift);

		if (!$thermo_data || !$thermo_verif) {
			show_error('Data tidak ditemukan untuk tanggal dan shift ini.', 404);
		}

		// 🔹 Merge data berdasarkan model + kode_thermo + area + shift
		$merged = [];
		foreach ($thermo_data as $row) {
			$hasil_array = json_decode($row->peneraan_hasil, true);
			if (!is_array($hasil_array)) continue;

			foreach ($hasil_array as $hasil) {
				$model  = $hasil['model'] ?? '-';
				$kode   = $hasil['kode_thermo'] ?? '-';
				$area   = $hasil['area'] ?? '-';
				$pukul  = $hasil['pukul'] ?? '';
				$standar = $hasil['standar'] ?? '';
				$hasil_uji = $hasil['hasil'] ?? '';

				$key = "{$model}|{$kode}|{$area}|{$shift}";

				if (!isset($merged[$key])) {
					$merged[$key] = [
						'model' => $model,
						'kode_thermo' => $kode,
						'area' => $area,
						'checks' => [],
						'tindakan_perbaikan' => $row->tindakan_perbaikan ?? '-',
						'keterangan' => $row->keterangan ?? '-'
					];
				}

				$merged[$key]['checks'][] = [
					'pukul' => $pukul,
					'standar' => $standar,
					'hasil' => $hasil_uji
				];
			}
		}

		$merged_data = array_values($merged);

		// === Mulai cetak PDF ===
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(17, 16, 15);
		$pdf->AddPage('L', 'LEGAL');

		// Judul
		$pdf->SetFont('times', 'B', 15);
		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 17, 14, 45);
		}
		// Judul dan Header
		$pdf->Image(FCPATH . 'assets/img/logo.jpg', 17, 14, 45);
		$pdf->Write(9, "\n");
		$pdf->MultiCell(0, 5, 'PENERAAN THERMOMETER', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$date = new DateTime($thermo_verif->date);
		$formatted_date = strftime('%A, %d %B %Y', $date->getTimestamp());
		$pdf->SetFont('times', '', 10);
		$pdf->Write(0, 'Tanggal: ' . $formatted_date . " | Shift: " . $shift);
		$pdf->Ln(5);

		// === Table Header ===
		$pdf->SetFont('times', '', 11);
		$pdf->Cell(10, 15, 'No', 1, 0, 'C');
		$pdf->Cell(32, 15, 'Jenis Thermometer', 1, 0, 'C');
		$pdf->Cell(52, 15, 'Kode / Area', 1, 0, 'C');
		$pdf->Cell(162, 5, 'Hasil Peneraan', 1, 0, 'C');
		$pdf->Cell(34, 15, 'Tindakan Perbaikan', 1, 0, 'C');
		$pdf->Cell(30, 15, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		// Baris sub-header
		$pdf->SetFont('times', '', 9);
		$pdf->Cell(94, 10, '', 0, 0, 'C');
		for ($i = 1; $i <= 6; $i++) {
			$pdf->Cell(27, 5, 'Check-' . $i, 1, 0, 'C');
		}
		$pdf->Cell(64, 5, '', 0, 1, 'C');

		$pdf->Cell(94, 5, '', 0, 0, 'C');
		for ($i = 0; $i < 6; $i++) {
			$pdf->Cell(9, 5, 'Pukul', 1, 0, 'C');
			$pdf->Cell(9, 5, 'Std', 1, 0, 'C');
			$pdf->Cell(9, 5, 'Hasil', 1, 0, 'C');
		}
		$pdf->Cell(64, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		// === Isi Tabel ===
		$no = 1;
		foreach ($merged_data as $data) {
			$pdf->SetFont('times', '', 10);
			$pdf->Cell(10, 8, $no++, 1, 0, 'C');
			$pdf->Cell(32, 8, $data['model'], 1, 0, 'C');
			$pdf->Cell(52, 8, $data['kode_thermo'] . " / " . $data['area'], 1, 0, 'C');

			$pdf->SetFont('times', '', 8);
			for ($i = 0; $i < 6; $i++) {
				$p = $data['checks'][$i]['pukul'] ?? '';
				$s = $data['checks'][$i]['standar'] ?? '';
				$h = $data['checks'][$i]['hasil'] ?? '';
				$pdf->Cell(9, 8, $p, 1, 0, 'C');
				$pdf->Cell(9, 8, $s, 1, 0, 'C');
				$pdf->Cell(9, 8, $h, 1, 0, 'C');
			}

			$pdf->Cell(34, 8, $data['tindakan_perbaikan'], 1, 0, 'C');
			$pdf->Cell(30, 8, $data['keterangan'], 1, 0, 'C');
			$pdf->Ln();
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(310, 5, 'QB 05/00', 0, 1, 'R');

		// === Bagian TTD & QR ===
		$y_ttd = $pdf->GetY() + 6;
		$qr_size = 15;

		// Cek status verifikasi
		$status_verifikasi = true;
		foreach ($thermo_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$thermo_verif->nama_lengkap_qc        = $this->pegawai_model->get_nama_lengkap($thermo_verif->username);
		$thermo_verif->nama_lengkap_spv       = $this->pegawai_model->get_nama_lengkap($thermo_verif->nama_spv);
		$thermo_verif->nama_lengkap_produksi  = $thermo_verif->nama_produksi;

		$qr_qc_text = "Dibuat secara digital oleh,\n"
			. $thermo_verif->nama_lengkap_qc . "\nQC Inspector\n"
			. (new DateTime($thermo_verif->created_at))->format('d-m-Y | H:i');

		$qr_produksi_text = null;
		if (!empty($thermo_verif->nama_lengkap_produksi) && !empty($thermo_verif->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($thermo_verif->tgl_update_produksi))->format('d-m-Y | H:i');
			$qr_produksi_text = "Diketahui secara digital oleh,\n"
				. $thermo_verif->nama_lengkap_produksi . "\nForeman/Forelady Produksi\n" . $prod_tanggal;
		}

		$spv_tanggal = !empty($thermo_verif->tgl_update_spv)
			? (new DateTime($thermo_verif->tgl_update_spv))->format('d-m-Y | H:i')
			: '-';
		$qr_spv_text = "Disetujui secara digital oleh,\n"
			. $thermo_verif->nama_lengkap_spv . "\nSupervisor QC Bread Crumb\n" . $spv_tanggal;

		if ($status_verifikasi) {
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(20, $y_ttd);
			$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(85, $y_ttd);
			$pdf->Cell(130, 5, 'Diketahui Oleh,', 0, 0, 'C');
			$pdf->SetXY(150, $y_ttd);
			$pdf->Cell(220, 5, 'Disetujui Oleh,', 0, 1, 'C');

			$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
			if ($qr_produksi_text) $pdf->write2DBarcode($qr_produksi_text, 'QRCODE,L', 143, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
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

		// Output PDF
		$formatted_date2 = (new DateTime($thermo_verif->date))->format('Y-m-d');
		$filename = "Peneraan_Thermometer_{$formatted_date2}_Shift{$shift}.pdf";
		$pdf->Output($filename, 'I');
	}

	public function cetak_audit()
	{
		$tanggal = $this->input->post('tanggal');
		$shift   = $this->input->post('shift');

		if (empty($tanggal) || empty($shift)) {
			show_error('Tanggal dan shift harus dipilih', 404);
		}

		$plant = $this->session->userdata('plant');
		if (empty($plant)) {
			show_error('Plant tidak ditemukan di session.', 403);
		}

		$thermo_data  = $this->thermometer_model->get_audit_by_date($tanggal, $plant, $shift);
		$thermo_verif = $this->thermometer_model->get_last_verif_by_date($tanggal, $plant, $shift);

		if (!$thermo_data || !$thermo_verif) {
			show_error('Data tidak ditemukan untuk tanggal dan shift ini.', 404);
		}

		// 🔹 Merge data berdasarkan model + kode_thermo + area + shift
		$merged = [];
		foreach ($thermo_data as $row) {
			$hasil_array = json_decode($row->peneraan_hasil, true);
			if (!is_array($hasil_array)) continue;

			foreach ($hasil_array as $hasil) {
				$model  = $hasil['model'] ?? '-';
				$kode   = $hasil['kode_thermo'] ?? '-';
				$area   = $hasil['area'] ?? '-';
				$pukul  = $hasil['pukul'] ?? '';
				$standar = $hasil['standar'] ?? '';
				$hasil_uji = $hasil['hasil'] ?? '';

				$key = "{$model}|{$kode}|{$area}|{$shift}";

				if (!isset($merged[$key])) {
					$merged[$key] = [
						'model' => $model,
						'kode_thermo' => $kode,
						'area' => $area,
						'checks' => [],
						'tindakan_perbaikan' => $row->tindakan_perbaikan ?? '-',
						'keterangan' => $row->keterangan ?? '-'
					];
				}

				$merged[$key]['checks'][] = [
					'pukul' => $pukul,
					'standar' => $standar,
					'hasil' => $hasil_uji
				];
			}
		}

		$merged_data = array_values($merged);

		// === Mulai cetak PDF ===
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetMargins(17, 16, 15);
		$pdf->AddPage('L', 'LEGAL');

		// Judul
		$pdf->SetFont('times', 'B', 15);
		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 17, 14, 45);
		}
		// Judul dan Header
		$pdf->Image(FCPATH . 'assets/img/logo.jpg', 17, 14, 45);
		$pdf->Write(9, "\n");
		$pdf->MultiCell(0, 5, 'PENERAAN THERMOMETER', 0, 'C');
		$pdf->Ln(5);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$date = new DateTime($thermo_verif->date);
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
		$pdf->SetFont('times', '', 10);
		$pdf->Write(0, 'Tanggal: ' . $formatted_date . " | Shift: " . $shift);
		$pdf->Ln(5);

		// === Table Header ===
		$pdf->SetFont('times', '', 11);
		$pdf->Cell(10, 15, 'No', 1, 0, 'C');
		$pdf->Cell(32, 15, 'Jenis Thermometer', 1, 0, 'C');
		$pdf->Cell(52, 15, 'Kode / Area', 1, 0, 'C');
		$pdf->Cell(162, 5, 'Hasil Peneraan', 1, 0, 'C');
		$pdf->Cell(34, 15, 'Tindakan Perbaikan', 1, 0, 'C');
		$pdf->Cell(30, 15, 'Keterangan', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		// Baris sub-header
		$pdf->SetFont('times', '', 9);
		$pdf->Cell(94, 10, '', 0, 0, 'C');
		for ($i = 1; $i <= 6; $i++) {
			$pdf->Cell(27, 5, 'Check-' . $i, 1, 0, 'C');
		}
		$pdf->Cell(64, 5, '', 0, 1, 'C');

		$pdf->Cell(94, 5, '', 0, 0, 'C');
		for ($i = 0; $i < 6; $i++) {
			$pdf->Cell(9, 5, 'Pukul', 1, 0, 'C');
			$pdf->Cell(9, 5, 'Std', 1, 0, 'C');
			$pdf->Cell(9, 5, 'Hasil', 1, 0, 'C');
		}
		$pdf->Cell(64, 0, '', 0, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		// === Isi Tabel ===
		$no = 1;
		foreach ($merged_data as $data) {
			$pdf->SetFont('times', '', 10);
			$pdf->Cell(10, 8, $no++, 1, 0, 'C');
			$pdf->Cell(32, 8, $data['model'], 1, 0, 'C');
			$pdf->Cell(52, 8, $data['kode_thermo'] . " / " . $data['area'], 1, 0, 'C');

			$pdf->SetFont('times', '', 8);
			for ($i = 0; $i < 6; $i++) {
				$p = $data['checks'][$i]['pukul'] ?? '';
				$s = $data['checks'][$i]['standar'] ?? '';
				$h = $data['checks'][$i]['hasil'] ?? '';
				$pdf->Cell(9, 8, $p, 1, 0, 'C');
				$pdf->Cell(9, 8, $s, 1, 0, 'C');
				$pdf->Cell(9, 8, $h, 1, 0, 'C');
			}

			$pdf->Cell(34, 8, $data['tindakan_perbaikan'], 1, 0, 'C');
			$pdf->Cell(30, 8, $data['keterangan'], 1, 0, 'C');
			$pdf->Ln();
		}

		$pdf->SetFont('times', 'I', 7);
		$pdf->Cell(310, 5, 'QB 05/00', 0, 1, 'R');

		// === Bagian TTD & QR ===
		$y_ttd = $pdf->GetY() + 6;
		$qr_size = 15;

		// Cek status verifikasi
		$status_verifikasi = true;
		foreach ($thermo_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$thermo_verif->nama_lengkap_qc        = $this->pegawai_model->get_nama_lengkap($thermo_verif->username);
		$thermo_verif->nama_lengkap_spv       = $this->pegawai_model->get_nama_lengkap($thermo_verif->nama_spv);
		$thermo_verif->nama_lengkap_produksi  = $thermo_verif->nama_produksi;

		$qr_qc_text = "Dibuat secara digital oleh,\n"
			. $thermo_verif->nama_lengkap_qc . "\nQC Inspector\n"
			. (new DateTime($thermo_verif->created_at))->format('d-m-Y | H:i');

		$qr_produksi_text = null;
		if (!empty($thermo_verif->nama_lengkap_produksi) && !empty($thermo_verif->tgl_update_produksi)) {
			$prod_tanggal = (new DateTime($thermo_verif->tgl_update_produksi))->format('d-m-Y | H:i');
			$qr_produksi_text = "Diketahui secara digital oleh,\n"
				. $thermo_verif->nama_lengkap_produksi . "\nForeman/Forelady Produksi\n" . $prod_tanggal;
		}

		$spv_tanggal = !empty($thermo_verif->tgl_update_spv)
			? (new DateTime($thermo_verif->tgl_update_spv))->format('d-m-Y | H:i')
			: '-';
		$qr_spv_text = "Disetujui secara digital oleh,\n"
			. $thermo_verif->nama_lengkap_spv . "\nSupervisor QC Bread Crumb\n" . $spv_tanggal;

		if ($status_verifikasi) {
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(20, $y_ttd);
			$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');
			$pdf->SetXY(85, $y_ttd);
			$pdf->Cell(130, 5, 'Diketahui Oleh,', 0, 0, 'C');
			$pdf->SetXY(150, $y_ttd);
			$pdf->Cell(220, 5, 'Disetujui Oleh,', 0, 1, 'C');

			$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
			if ($qr_produksi_text) $pdf->write2DBarcode($qr_produksi_text, 'QRCODE,L', 143, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
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

		// Output PDF
		$formatted_date2 = (new DateTime($thermo_verif->date))->format('Y-m-d');
		$filename = "Peneraan_Thermometer_{$formatted_date2}_Shift{$shift}.pdf";
		$pdf->Output($filename, 'I');
	}
}
