<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reagen extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('reagen_model');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'bulan_tahun' => $this->reagen_model->get_bulan_tahun_by_plant()
		);

		$this->active_nav = 'reagen';
		$this->render('form/reagen/reagen', $data);
	}

	public function detail($bulan, $tahun)
	{
		$data = [
			'reagen'     => $this->reagen_model->get_by_bulan_tahun($bulan, $tahun),
			'bulan'      => $bulan,
			'tahun'      => $tahun
		];

		$this->active_nav = 'reagen';
		$this->render('form/reagen/reagen-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->reagen_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->reagen_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Penggunaan Reagen Klorin berhasil di simpan');
				redirect('reagen');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Penggunaan Reagen Klorin gagal di simpan');
				redirect('reagen');
			}
		}

		$plant = $this->session->userdata('plant');

		// AMBIL NO LOT TERAKHIR BERDASARKAN PLANT
		$last_lot = $this->db
			->select('no_lot')
			->from('reagen')
			->where('no_lot IS NOT NULL')
			->where('plant', $plant)
			->order_by('id', 'DESC')
			->limit(1)
			->get()
			->row();

		// AMBIL VOLUME AKHIR TERAKHIR BERDASARKAN PLANT
		$last_volume = $this->db
			->select('volume_akhir')
			->from('reagen')
			->where('volume_akhir IS NOT NULL')
			->where('plant', $plant)
			->order_by('id', 'DESC')
			->limit(1)
			->get()
			->row();

		$data = array(
			'last_no_lot' => $last_lot->no_lot ?? '',
			'last_volume' => 22
		);

		$this->active_nav = 'reagen';
		$this->render('form/reagen/reagen-tambah', $data);
	}
	public function edit($uuid)
	{
		$rules = $this->reagen_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			$update = $this->reagen_model->update($uuid);

			// ambil konteks halaman asal
			$bulan = $this->input->post('redirect_bulan');
			$tahun = $this->input->post('redirect_tahun');

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Verifikasi Penggunaan Reagen Klorin berhasil di Update'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data Verifikasi Penggunaan Reagen Klorin gagal di Update'
				);
			}

			// 🔥 BALIK KE DETAIL PER BULAN
			if ($bulan && $tahun) {
				redirect('reagen/detail/' . $bulan . '/' . $tahun);
			} else {
				redirect('reagen');
			}
		}

		// load form edit jika validasi gagal / pertama buka
		$data = [
			'reagen' => $this->reagen_model->get_by_uuid($uuid)
		];

		$this->active_nav = 'reagen';
		$this->render('form/reagen/reagen-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('reagen');
		}

		// ambil konteks halaman asal dari GET parameter
		$bulan = $this->input->get('bulan');
		$tahun = $this->input->get('tahun');

		$deleted = $this->reagen_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		// redirect kembali ke halaman asal
		if ($bulan && $tahun) {
			redirect('reagen/detail/' . $bulan . '/' . $tahun);
		} else {
			redirect('reagen');
		}
	}

	public function verifikasi()
	{
		$bulan_tahun = $this->reagen_model->get_bulan_tahun_by_plant();

		$data = [
			'bulan_tahun' => $bulan_tahun
		];

		$this->active_nav = 'verifikasi-reagen';
		$this->render('form/reagen/reagen-verifikasi', $data);
	}

	public function verifikasi_bulan($bulan, $tahun)
	{
		$update = $this->reagen_model->verifikasi_bulan($bulan, $tahun);

		if ($update) {
			$this->session->set_flashdata('success_msg', 'Semua data bulan ' . $bulan . ' / ' . $tahun . ' berhasil diverifikasi');
		} else {
			$this->session->set_flashdata('error_msg', 'Verifikasi gagal.');
		}

		redirect('reagen/verifikasi');
	}

	public function status($bulan, $tahun)
	{
		$reagen = $this->reagen_model->get_by_bulan_tahun($bulan, $tahun);

		$data = [
			'reagen' => $reagen,
			'bulan'  => $bulan,
			'tahun'  => $tahun
		];

		$this->active_nav = 'verifikasi-reagen';
		$this->render('form/reagen/reagen-status', $data);
	}

	public function audit()
	{
		$data = [
			'bulan_tahun' => $this->reagen_model
				->get_bulan_tahun_audit_by_plant()
		];

		$this->active_nav = 'reagen';
		$this->render('form/reagen/reagen-audit', $data);
	}

	public function detail_audit($bulan, $tahun)
	{
		$bulan = (int) $bulan;
		$tahun = (int) $tahun;

		if ($bulan < 1 || $bulan > 12 || $tahun < 2000) {
			show_404();
			return;
		}

		$data = [
			'reagen' => $this->reagen_model
				->get_audit_by_bulan_tahun($bulan, $tahun),
			'bulan'  => $bulan,
			'tahun'  => $tahun,
			'mode'   => 'audit'
		];

		$this->active_nav = 'reagen';
		$this->render('form/reagen/reagen-detail', $data);
	}

	public function edit_audit($uuid)
	{
		$reagen = $this->reagen_model->get_audit_by_uuid($uuid);

		if (!$reagen) {
			show_404();
			return;
		}

		if ($this->input->method() === 'post') {
			$rules = $this->reagen_model->rules();
			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() === TRUE) {
				$berhasil = $this->reagen_model->update_audit($uuid);

				$bulan = date('n', strtotime($reagen->date));
				$tahun = date('Y', strtotime($reagen->date));

				if ($berhasil) {
					$this->session->set_flashdata(
						'success_msg',
						'Data Audit Reagen berhasil diperbarui.'
					);
				} else {
					$this->session->set_flashdata(
						'error_msg',
						'Data Audit Reagen gagal diperbarui.'
					);
				}

				redirect('reagen/detail-audit/' . $bulan . '/' . $tahun);
				return;
			}
		}

		$data = [
			'reagen' => $reagen,
			'mode'   => 'audit'
		];

		$this->active_nav = 'reagen';
		$this->render('form/reagen/reagen-edit', $data);
	}

	public function cetak($bulan = null, $tahun = null)
	{
		// Pastikan parameter valid
		if (!$bulan || !$tahun) {
			show_error('Bulan atau tahun tidak valid', 404);
		}

		$start_date = "$tahun-$bulan-01";
		$end_date = date("Y-m-t", strtotime($start_date));
		$plant = $this->session->userdata('plant');

		// Ambil data seperti versi lama, tapi sesuai bulan & tahun dari URL
		$reagen_data = $this->reagen_model->get_by_month($start_date, $end_date, $plant);
		$reagen_data_verif = $this->reagen_model->get_last_verif_by_month($start_date, $end_date, $plant);

		if (!$reagen_data || !$reagen_data_verif) {
			show_error('Data tidak ditemukan, Pilih bulan yang ingin dicetak', 404);
		}

		$data['reagen'] = $reagen_data_verif;

		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(10, 9.5, 10);
		$pdf->AddPage();
		$pdf->SetFont('times', 'B', 12);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 35);
		} else {
			$pdf->Write(7, "Logo tidak ditemukan\n");
		}

		$pdf->Write(9, "\n");
		$pdf->SetFont('times', 'B', 10);
		$pdf->MultiCell(0, 5, 'VERIFIKASI PENGGUNAAN REAGEN KLORIN', 0, 'C');

		$pdf->SetFont('times', '', 9);
		$pdf->MultiCell(0, 3, 'Bulan : ' . date('F', strtotime($start_date)) . ' ' . $tahun, 0, 'L');

		$pdf->Ln(1);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(12, 10, 'Tanggal', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Nama Larutan', 1, 0, 'C');
		$pdf->Cell(20, 10, 'No. Lot', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Best Before', 1, 0, 'C');
		$pdf->Cell(20, 10, 'Tanggal', 1, 0, 'C');
		$pdf->Cell(30, 10, 'Volume Penggunaan', 1, 0, 'C');
		$pdf->Cell(30, 10, 'Volume Akhir', 1, 0, 'C');
		$pdf->Cell(40, 5, 'Diverifikasi Oleh', 1, 0, 'C');
		$pdf->Cell(10, 5, '', 0, 1, 'C');

		$pdf->Cell(72, 0, '', 0, 0, 'L');
		$pdf->Cell(20, 5, 'Buka Botol', 0, 0, 'C');
		$pdf->Cell(30, 5, 'Larutan (mL)', 0, 0, 'C');
		$pdf->Cell(30, 5, 'Larutan (mL)', 0, 0, 'C');
		$pdf->Cell(40, 5, 'Nama', 1, 0, 'C');
		$pdf->Cell(0, 0, '', 0, 0, 'C');
		$pdf->Cell(25, 5, '', 0, 1, 'C');

		$pdf->SetFont('times', '', 8);
		foreach ($reagen_data as $reagen) {
			$created_date = (new DateTime($reagen->date))->format('d');
			$ex_date = (new DateTime($reagen->best_before))->format('d-m-Y');
			$open = (new DateTime($reagen->tgl_buka_botol))->format('d-m-Y');
			$pdf->Cell(12, 5, $created_date, 1, 0, 'C');
			$pdf->writeHTMLCell(20, 5, '', '', str_replace('₂', '<sub>2</sub>', $reagen->nama_larutan), 1, 0, false, true, 'L', true);
			$pdf->Cell(20, 5, $reagen->no_lot, 1, 0, 'C');
			$pdf->Cell(20, 5, $ex_date, 1, 0, 'C');
			$pdf->Cell(20, 5, $open, 1, 0, 'C');
			$pdf->Cell(30, 5, $reagen->volume_penggunaan, 1, 0, 'C');
			$pdf->Cell(30, 5, $reagen->volume_akhir, 1, 0, 'C');
			$pdf->Cell(40, 5, $reagen->username, 1, 0, 'C');
			$pdf->Ln();
		}

		$this->load->model('pegawai_model');
		$data['reagen']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['reagen']->username);
		$data['reagen']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['reagen']->nama_spv);

		$pdf->SetY($pdf->GetY() + 2);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($reagen_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(10, 0, '', 0, 0, 'L');
				$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($reagen_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;

			$qc_usernames = array_unique(array_column($reagen_data, 'username'));
			$this->load->model('pegawai_model');
			$qc_nama_list = [];
			foreach ($qc_usernames as $username) {
				// Ambil nama lengkap QC
				$nama = $this->pegawai_model->get_nama_lengkap($username);

				// Ambil entry pertama dari QC ini
				$entry = current(array_filter($reagen_data, fn($r) => $r->username === $username));
				$tanggal = (new DateTime($entry->created_at))->format('d-m-Y | H:i');

				// Gabungkan nama dan tanggal
				$qc_nama_list[] = "$nama - $tanggal";
			}

			$qr_text_qc = "Diverifikasi oleh QC:\n" . implode("\n", $qc_nama_list);


			// QR QC kiri bawah
			$x_qc = 25;
			$y_qc = $y_verifikasi + 5;
			$pdf->SetXY($x_qc, $y_qc);
			$pdf->Cell(35, 5, 'Dibuat Oleh,', 0, 1, 'C');
			$pdf->write2DBarcode($qr_text_qc, 'QRCODE,L', $x_qc, $y_qc + 5, 35, 15, null, 'N');
			$pdf->SetXY($x_qc, $y_qc + 20);
			$pdf->Cell(35, 5, 'QC Inspector', 0, 0, 'C');

			// QR Supervisor kanan bawah
			$x_spv = 150;
			$pdf->SetXY($x_spv, $y_verifikasi + 5);
			$pdf->Cell(49, 5, 'Disetujui Oleh,', 0, 0, 'C');

			$update_tanggal = (new DateTime($data['reagen']->tgl_update_spv))->format('d-m-Y | H:i');
			$qr_text_spv = "Diverifikasi secara digital oleh,\n" . $data['reagen']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text_spv, 'QRCODE,L', $x_spv, $y_verifikasi + 10, 48, 15, null, 'N');
			$pdf->SetXY($x_spv, $y_verifikasi + 24);
			$pdf->Cell(49, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(100, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}

		$pdf->setPrintFooter(false);
		$filename = "Verifikasi Penggunaan Reagen Klorin_{$bulan}.pdf";
		$pdf->Output($filename, 'I');
	}
}
