<?php
defined('BASEPATH') or exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class Proses extends MY_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model');
		$this->load->model('proses_model');
		$this->load->model('packing_model');
		$this->load->model('produk_model');
		$this->load->model('pegawai_model');
		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'proses' => $this->proses_model->get_data_by_plant()
		);

		$this->active_nav = 'proses'; 
		$this->render('form/proses/proses', $data);
	}

	public function detail($uuid)
	{
		$proses = $this->proses_model->get_by_uuid($uuid);

		if ($proses && isset($proses->proses_produksi)) {
			$proses->proses_produksi = json_decode($proses->proses_produksi, true);
		}

		$data = array(
			'proses' => $proses
		);

		$this->active_nav = 'proses'; 
		$this->render('form/proses/proses-detail', $data);
	}

	public function tambah()
	{
		$rules = $this->proses_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->proses_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Proses Produksi berhasil di simpan');
				redirect('proses');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Proses Produksi gagal di simpan');
				redirect('proses');
			}
		}

		$kode_produksi_terakhir = $this->proses_model->getLastKodeproduksiHariIni();
		// $produk_list = $this->produk_model->get_all_produk();

		$plant = $this->session->userdata('plant');
		$produk_list = $this->produk_model->get_all_produk_by_plant($plant);

		$data = array(
			'kode_produksi_terakhir' => $kode_produksi_terakhir,
			'produk_list' => $produk_list
		);

		$this->active_nav = 'proses'; 
		$this->render('form/proses/proses-tambah', $data);
	}

	public function edit($uuid)
	{
		$rules = $this->proses_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->proses_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi Proses Produksi berhasil di Update');
				redirect('proses');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi Proses Produksi gagal di Update');
				redirect('proses');
			}
		}
		$plant = $this->session->userdata('plant');
		$produk_list = $this->produk_model->get_all_produk_by_plant($plant);

		$data = array(
			'proses' => $this->proses_model->get_by_uuid($uuid),
			'produk_list' => $produk_list
		);

		$this->active_nav = 'proses'; 
		$this->render('form/proses/proses-edit', $data);
	}

	public function packing($uuid)
	{
		$rules = $this->proses_model->rules_packing();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {
			$update = $this->proses_model->update_packing($uuid);

			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data berhasil diperbarui.');
			} else {
				$this->session->set_flashdata('error_msg', 'Gagal memperbarui data.');
			}

			return redirect('proses');
		}


		// === 2. AMBIL DATA PROSES MIXING ===
		$proses = $this->proses_model->get_by_uuid($uuid);

		// Stop if not found
		if (!$proses) {
			show_404();
		}


		// === 3. DECODE JSON FROM proses_produksi ===
		$data_produksi = [];
		if (!empty($proses->proses_produksi)) {
			$decoded = json_decode($proses->proses_produksi, true);
			if (is_array($decoded)) {
				$data_produksi = $decoded;
			}
		}

		// === 4. DECODE JSON FROM proses_packing ===
		$data_packing = [];
		if (!empty($proses->proses_packing)) {
			$decoded = json_decode($proses->proses_packing, true);
			if (is_array($decoded)) {
				$data_packing = $decoded;
			}
		}


		// === 5. AMBIL nama_produk (PAKING -> PROSES V1) ===
		// proses_packing[nama_produk][0] → proses->nama_produk → ""
		$nama_produk_index1 =
		$data_packing['nama_produk'][0]
		?? $data_packing['nama_produk']
		?? $proses->nama_produk
		?? '';


		// === 6. AMBIL kode_produksi (PACKING -> DOUGH MIXING INDEX 1) ===
		$kode_produksi_index1 =
		$data_packing['kode_produksi'][0]
		?? $data_packing['kode_produksi']
		?? ($data_produksi['dough_mixing']['kode_produksi'][1] ?? '')
		?? '';


		// === 7. LIST PRODUK ===
		$produk_list = $this->produk_model->get_all_produk();


		// === 8. KIRIM DATA KE VIEW ===
		$data = [
			'proses'                => $proses,
			'produk_list'           => $produk_list,
			'data_produksi'         => $data_produksi,
			'data_packing'          => $data_packing,
			'nama_produk_index1'    => $nama_produk_index1,
			'kode_produksi_index1'  => $kode_produksi_index1
		];

		$this->active_nav = 'proses'; 
		$this->render('form/proses/proses-packing', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('proses');
		}

		$deleted = $this->proses_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('proses');
	}

	public function verifikasi()
	{
		$data = array(
			'proses' => $this->proses_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-proses'; 
		$this->render('form/proses/proses-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->proses_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->proses_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Verifikasi proses berhasil di Update');
				redirect('proses/verifikasi');
			} else {
				$this->session->set_flashdata('error_msg', 'Data Verifikasi proses gagal di Update');
				redirect('proses/verifikasi');
			}
		}

		$data = array(
			'proses' => $this->proses_model->get_by_uuid($uuid)
		);

		$this->active_nav = 'verifikasi-proses'; 
		$this->render('form/proses/proses-status', $data);
	}

	public function cetak()
	{
		error_reporting(0);

		// Ambil input tanggal dan shift dari form
		$tanggal = $this->input->post('tanggal');
		$shift = $this->input->post('shift');

		if (empty($tanggal) || empty($shift)) {
			redirect('produksi?error=nodata');
			return;
		}

		// Ambil data produksi dan verifikasi berdasarkan tanggal + shift
		$proses_data = $this->proses_model->get_by_tanggal_shift($tanggal, $shift);
		$proses_data_verif = $this->proses_model->get_by_tanggal_shift_verif($tanggal, $shift);

		if (empty($proses_data) || empty($proses_data_verif)) {
			redirect('produksi?error=notfound');
			return;
		}

		$data['proses'] = $proses_data_verif;

		// Ambil nama lengkap pegawai
		$this->load->model('pegawai_model');
		$data['proses']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['proses']->username);
		$data['proses']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['proses']->nama_spv);
		$data['proses']->nama_lengkap_produksi = $data['proses']->nama_produksi;

		$proses_packing_all = [];
		foreach ($proses_data as $pd) {
			$packing = json_decode($pd->proses_packing ?? '{}', true);
			if (!empty($packing)) {
				foreach ($packing as $key => $item) {
					if (is_numeric($key)) { // hanya ambil batch numeric
						$proses_packing_all[] = $item;
					}
				}
			}
		}

		// Jika packing lebih dari 12, pecah menjadi beberapa halaman
		$packing_chunks = array_chunk($proses_packing_all, 12);

		// Load TCPDF
		require_once APPPATH . 'third_party/tcpdf/tcpdf.php';
		$pdf = new TCPDF('L', PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
		$pdf->setPrintHeader(false);
		$pdf->SetMargins(9, 10, 8);
		$pdf->setPrintFooter(false);

		// -----------------------------
		// Halaman Produksi
		// -----------------------------
		foreach ($proses_data as $index => $pd) {
			$proses_produksi = json_decode($pd->proses_produksi ?? '[]', true);
			if (empty($proses_produksi)) continue; // ← skip empty rows
			$pdf->AddPage();
			$this->_generate_halaman_produksi($pdf, $data, [$pd], $proses_produksi);
		}

		foreach ($packing_chunks as $chunk) {
			$pdf->AddPage();
			$this->_generate_halaman_packing($pdf, $data, $chunk, $proses_data);
		}


		// Output PDF
		$filename = 'Verifikasi_Proses_Produksi_' . date('d-m-Y') . '.pdf';
		$pdf->Output($filename, 'I');
	}


	private function _generate_halaman_produksi($pdf, $data, $proses_data, $proses_produksi)
	{
		$pdf->SetFont('times', 'B', 13);
		$pdf->SetTextColor(0, 0, 0);
		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 35);
		}

		$pdf->Write(11, "\n");
		$pdf->MultiCell(0, 5, 'VERIFIKASI PROSES PRODUKSI', 0, 'C');
		$pdf->Ln(3);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['proses']->date;
		$date = new DateTime($tanggal);
		$formatted_date = strftime('%A, %d %B %Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(10);
		$pdf->Write(0, 'Hari / Tanggal: ' . $formatted_date);
		$pdf->SetX($pdf->GetX() + 10);
		$pdf->Write(0, 'Shift: ' . $data['proses']->shift);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 9);

		$label_param_produksi = [
			'dough' => 'DOUGH MIXING',
			'no_dokumen' => 'Acuan: No. dokumen / revisi',
			'no_formula' => 'No. formula',
			'jenis_produk' => 'Jenis Produk',
			'kode_produksi' => 'Kode Produksi',
			'berat_rm' => 'Kondisi RM',
			'terigu' => 'Tepung Terigu',
			'tapioka' => 'Tepung Tapioka (2.270 kg)',
			'yeast' => 'Yeast (0.732 kg)',
			'bread_improver' => 'Bread Improver',
			'premix' => 'Premix',
			'shortening' => 'Shortening (0.252 kg)',
			'chill_water' => 'Chill Water (14–16°C)',
			'mixing_place' => 'Waktu Mixing (menit speed 1/2)',
			'hasil_mixing' => 'Hasil Mixing',
			'sensor_mixing' => 'Sensori',
			'suhu_adonan' => 'Suhu Adonan (29–31°C)',
			'kecepatan_adonan' => 'Beat Adonan (630–670 g/pcs)',
			'proofing' => 'PROOFING',
			'jam_mulai_proofing' => 'Jam Mulai / Selesai',
			'suhu_setting_proofing' => 'Suhu Setting / Aktual (34–36°C)',
			'rh_setting_proofing' => 'RH Setting / Aktual (78–82%)',
			'durasi_waktu_proofing' => 'Durasi Waktu (60–70 menit)',
			'hasil_proofing' => 'Hasil Proofing',
			'electric_baking' => 'ELECTRIC BAKING',
			'baking_time' => 'Baking Time (menit High / Low)',
			'hasil_baking' => 'Hasil Baking',
			'suhu_produk' => 'Suhu Produk (kisaran 80–97°C)',
			'sensor_baking' => 'Sensori Produk',
		];

		$mapping_field = [
			'no_dokumen' => ['dough_mixing', 'dokumen'],
			'revisi' => ['dough_mixing', 'revisi'],
			'no_formula' => ['dough_mixing', 'no_formula'],
			'jenis_produk' => ['dough_mixing', 'nama_produk'],
			'kode_produksi' => ['dough_mixing', 'kode_produksi'],

			'terigu' => ['kondisi_rm', 'tepung_terigu'],
			'tapioka' => ['kondisi_rm', 'tepung_tapioka'],
			'yeast' => ['kondisi_rm', 'yeast'],
			'bread_improver' => ['kondisi_rm', 'bread_improver'],
			'premix' => ['kondisi_rm', 'premix'],
			'shortening' => ['kondisi_rm', 'shortening'],
			'chill_water' => ['kondisi_rm', 'chill_water'],

			'waktu_mixing_1' => ['mixing', 'waktu_mixing_1'],
			'waktu_mixing_2' => ['mixing', 'waktu_mixing_2'],
			'sensor_mixing' => ['mixing', 'sensori'],
			'suhu_adonan' => ['mixing', 'suhu_adonan'],
			'kecepatan_adonan' => ['mixing', 'berat_adonan'],

			'jam_mulai_proofing' => ['proofing', 'jam_mulai'],
			'suhu_setting_proofing' => ['proofing', 'suhu_setting'],
			'suhu_aktual_proofing' => ['proofing', 'suhu_aktual'],
			'rh_setting_proofing' => ['proofing', 'rh_setting'],
			'rh_aktual_proofing' => ['proofing', 'rh_aktual'],
			'durasi_waktu_proofing' => ['proofing', 'durasi_waktu'],
			'hasil_proofing' => ['proofing', 'hasil_proofing'],

			'baking_time_high' => ['electric_baking', 'baking_time_high'],
			'baking_time_low' => ['electric_baking', 'baking_time_low'],
			'suhu_produk' => ['hasil_baking', 'suhu_produk'],
			'sensor_baking' => ['hasil_baking', 'sensori_produk'],
		];

		$pdf->SetFont('times', '', 8);
		foreach ($label_param_produksi as $key => $label) {
			$pdf->Cell(39, 4, $label, 1);

			if (in_array($key, ['dough', 'berat_rm', 'hasil_mixing', 'proofing', 'electric_baking', 'hasil_baking'])) {
				for ($i = 0; $i < 11; $i++) {
					$cellWidth = ($i === 0) ? 20 : 27;
					// ❌ Kosongkan kolom pertama untuk semua kecuali berat_rm
					if ($key === 'berat_rm' && $i === 0) {
						$pdf->Cell($cellWidth, 4, 'Berat (Kg)', 1, 0, 'C');
					} else {
						$pdf->Cell($cellWidth, 4, '', 1);
					}
				}
				$pdf->Ln();
				continue;
			}

			// 🔹 No Dokumen / Revisi
			if ($key === 'no_dokumen') {
				for ($i = 0; $i < 11; $i++) {
					$cellWidth = ($i === 0) ? 20 : 27;
					if ($i === 0) {
						// ❌ Kosongkan kolom pertama
						$pdf->Cell($cellWidth, 4, '', 1);
						continue;
					}
					$high = $proses_produksi['dough_mixing']['dokumen'][$i] ?? '';
					$low  = $proses_produksi['dough_mixing']['revisi'][$i] ?? '';
					$pdf->Cell($cellWidth, 4, $high . ' / ' . $low, 1);
				}
				$pdf->Ln();
				continue;
			}

			// 🔹 Baking Time High / Low
			if ($key === 'baking_time') {
				for ($i = 0; $i < 11; $i++) {
					$cellWidth = ($i === 0) ? 20 : 27;
					if ($i === 0) {
						$pdf->Cell($cellWidth, 4, '', 1);
						continue;
					}
					$high = $proses_produksi['electric_baking']['baking_time_high'][$i] ?? '';
					$low  = $proses_produksi['electric_baking']['baking_time_low'][$i] ?? '';
					$pdf->Cell($cellWidth, 4, $high . ' / ' . $low, 1);
				}
				$pdf->Ln();
				continue;
			}

			// 🔹 Jam Mulai / Selesai Proofing
			if ($key === 'jam_mulai_proofing') {
				for ($i = 0; $i < 11; $i++) {
					$cellWidth = ($i === 0) ? 20 : 27;
					if ($i === 0) {
						$pdf->Cell($cellWidth, 4, '', 1);
						continue;
					}
					$high = $proses_produksi['proofing']['jam_mulai'][$i] ?? '';
					$low  = $proses_produksi['proofing']['jam_selesai'][$i] ?? '';
					$pdf->Cell($cellWidth, 4, $high . ' / ' . $low, 1);
				}
				$pdf->Ln();
				continue;
			}

			// 🔹 Suhu Setting / Aktual Proofing
			if ($key === 'suhu_setting_proofing') {
				for ($i = 0; $i < 11; $i++) {
					$cellWidth = ($i === 0) ? 20 : 27;
					if ($i === 0) {
						$pdf->Cell($cellWidth, 4, '', 1);
						continue;
					}
					$high = $proses_produksi['proofing']['suhu_setting'][$i] ?? '';
					$low  = $proses_produksi['proofing']['suhu_aktual'][$i] ?? '';
					$pdf->Cell($cellWidth, 4, $high . ' / ' . $low, 1);
				}
				$pdf->Ln();
				continue;
			}

			// 🔹 RH Setting / Aktual Proofing
			if ($key === 'rh_setting_proofing') {
				for ($i = 0; $i < 11; $i++) {
					$cellWidth = ($i === 0) ? 20 : 27;
					if ($i === 0) {
						$pdf->Cell($cellWidth, 4, '', 1);
						continue;
					}
					$high = $proses_produksi['proofing']['rh_setting'][$i] ?? '';
					$low  = $proses_produksi['proofing']['rh_aktual'][$i] ?? '';
					$pdf->Cell($cellWidth, 4, $high . ' / ' . $low, 1);
				}
				$pdf->Ln();
				continue;
			}

			// 🔹 Waktu Mixing 1 / 2
			if ($key === 'mixing_place') {
				for ($i = 0; $i < 11; $i++) {
					$cellWidth = ($i === 0) ? 20 : 27;
					if ($i === 0) {
						$pdf->Cell($cellWidth, 4, '', 1);
						continue;
					}
					$mix1 = $proses_produksi['mixing']['waktu_mixing_1'][$i] ?? '';
					$mix2 = $proses_produksi['mixing']['waktu_mixing_2'][$i] ?? '';
					$pdf->Cell($cellWidth, 4, $mix1 . ' / ' . $mix2, 1);
				}
				$pdf->Ln();
				continue;
			}

			// 🔹 Semua field yang masuk mapping
			if (isset($mapping_field[$key])) {
				[$group, $field] = $mapping_field[$key];
				for ($i = 0; $i < 11; $i++) {
					$cellWidth = ($i === 0) ? 20 : 27;
					$value = $proses_produksi[$group][$field][$i] ?? '';

					if ($i === 0) {
						if ($group === 'kondisi_rm') {
							$pdf->Cell($cellWidth, 4, $value, 1);
						} else {
							$pdf->Cell($cellWidth, 4, '', 1);
						}
						continue;
					}

					$checkmark_values = ['1', '✓', 'oke', 'ok', 'baik'];
					$is_checked = in_array(strtolower(trim($value)), array_map('strtolower', $checkmark_values));

					if (
						($group === 'kondisi_rm' && $is_checked) ||
						($group === 'mixing' && $field === 'sensori' && $is_checked) ||
						($group === 'hasil_baking' && $field === 'sensori_produk' && $is_checked)
					) {
						$pdf->SetFont('dejavusans', '', 8);
						$pdf->Cell($cellWidth, 4, '✓', 1, 0, 'C');
						$pdf->SetFont('times', '', 8);
					} else {
						$pdf->Cell($cellWidth, 4, $value, 1);
					}
				}
			} else {
				for ($i = 0; $i < 11; $i++) {
					$cellWidth = ($i === 0) ? 20 : 27;
					$pdf->Cell($cellWidth, 4, '', 1);
				}
			}

			$pdf->Ln();
		}

		$y_after_table = $pdf->GetY();

		$status_verifikasi = true;
		foreach ($proses_data as $item) {
			if (!isset($item->status_spv) || $item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);

		$pdf->Ln(2);
		$pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
		foreach ($proses_data as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(40, 0, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($proses_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;
			$pdf->SetXY(45, $y_verifikasi + 5);
			$pdf->Cell(55, 5, 'Dibuat Oleh,', 0, 0, 'C');
			if (!empty($data['proses']->nama_lengkap_qc)) {
				$update_tanggal_qc = !empty($data['proses']->created_at)
				? (new DateTime($data['proses']->created_at))->format('d-m-Y | H:i')
				: date('d-m-Y | H:i');

				$qr_text_qc = "Dibuat secara digital oleh,\n" .
				$data['proses']->nama_lengkap_qc . "\nQC Inspector\n" . $update_tanggal_qc;
				$pdf->write2DBarcode($qr_text_qc, 'QRCODE,L', 65, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(45, $y_verifikasi + 24);
				$pdf->Cell(55, 5, 'QC Inspector', 0, 0, 'C');
			} else {
				$pdf->SetXY(45, $y_verifikasi + 10);
				$pdf->Cell(55, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}

			// Diketahui oleh (Produksi)
			$pdf->SetXY(130, $y_verifikasi + 5);
			$pdf->Cell(85, 5, 'Diketahui Oleh,', 0, 0, 'C');
			if ($data['proses']->status_produksi == 1 && !empty($data['proses']->nama_produksi)) {
				$update_tanggal_produksi = (new DateTime($data['proses']->tgl_update_prod))->format('d-m-Y | H:i');
				$qr_text_produksi = "Diketahui secara digital oleh,\n" . $data['proses']->nama_produksi . "\nForeman/Forelady Produksi\n" . $update_tanggal_produksi;
				$pdf->write2DBarcode($qr_text_produksi, 'QRCODE,L', 165, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(130, $y_verifikasi + 24);
				$pdf->Cell(85, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
			} else {
				$pdf->SetXY(130, $y_verifikasi + 10);
				$pdf->Cell(85, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}

			// Disetujui oleh (SPV)
			$pdf->SetXY(210, $y_verifikasi + 5);
			$pdf->Cell(119, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$update_tanggal = (new DateTime($data['proses']->tgl_update))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['proses']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 262, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(190, $y_verifikasi + 24);
			$pdf->Cell(159, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(100, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}
	}

	private function _generate_halaman_packing($pdf, $data, $proses_packing, $proses_data)
	{
		$pdf->SetFont('times', 'B', 13);

		$logo_path = FCPATH . 'assets/img/logo.jpg';
		if (file_exists($logo_path)) {
			$pdf->Image($logo_path, 10, 10, 35);
		}

		$pdf->SetTextColor(0, 0, 0);
		$pdf->Write(11, "\n");
		$pdf->MultiCell(0, 5, 'VERIFIKASI PROSES PRODUKSI', 0, 'C');
		$pdf->Ln(3);

		setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
		$tanggal = $data['proses']->date;
		$date = new DateTime($tanggal);
		$formatted_date = strftime('%A, %d %B %Y', $date->getTimestamp());

		$pdf->SetFont('times', '', 10);
		$pdf->SetX(10);
		$pdf->Write(0, 'Hari / Tanggal: ' . $formatted_date);
		$pdf->SetX($pdf->GetX() + 10);
		$pdf->Write(0, 'Shift: ' . $data['proses']->shift_pack);
		$pdf->Ln(5);

		$pdf->SetFont('times', '', 9);

		// -----------------------
		// Fix jumlah batch selalu 12
		// -----------------------
		$max_batch = 12;
		$jumlah_batch = count($proses_packing);
		$proses_packing_fixed = [];

		for ($i = 0; $i < $max_batch; $i++) {
			if (isset($proses_packing[$i])) {
				$proses_packing_fixed[$i] = $proses_packing[$i];
			} else {
				// batch kosong jika data tidak ada
				$proses_packing_fixed[$i] = [
					'stalling_aging' => [],
					'grinding' => [],
					'drying' => [],
					'pemeriksaan_finished_product' => []
				];
			}
		}

		$proses_packing = $proses_packing_fixed;
		$jumlah_batch = $max_batch;

		// -----------------------
		// Parsing data
		// -----------------------
		$parsed_data = [
			'stalling_aging' => [],
			'grinding' => [],
			'drying' => [],
			'pemeriksaan_finished_product' => [],
		];

		for ($i = 0; $i < $jumlah_batch; $i++) {
			foreach (['stalling_aging', 'grinding', 'drying', 'pemeriksaan_finished_product'] as $section) {
				$section_data = $proses_packing[$i][$section] ?? [];
				if (!is_array($section_data)) {
					$section_data = [];
				}

				foreach ($section_data as $key => $value) {
					if (is_array($value)) {
						$parsed_data[$section][$key][$i] = $value[0] ?? '';
					} else {
						$parsed_data[$section][$key][$i] = '';
					}
				}
			}
		}

		// === STALLING / AGING ===
		$pdf->SetFont('times', 'B', 9);
		$pdf->Cell(40 + ($jumlah_batch * 25), 5, 'STALLING / AGING', 1, 1, 'L');
		$pdf->SetFont('times', '', 8);

		$params_stall = [
			'Jam Mulai / Jam Selesai' => ['jam_mulai', 'jam_selesai'],
			'Lama Aging (9 - 12 jam)' => ['lama_aging'],
			'Kadar Air Produk (32 - 34%)' => ['kadar_air'],
		];

		foreach ($params_stall as $label => $fields) {
			$pdf->Cell(40, 5, $label, 1);
			for ($i = 0; $i < $jumlah_batch; $i++) {
				$val = '';
				foreach ($fields as $idx => $field) {
					$val .= ($parsed_data['stalling_aging'][$field][$i] ?? '') . ($idx < count($fields) - 1 ? ' / ' : '');
				}
				$pdf->Cell(25, 5, $val, 1);
			}
			$pdf->Ln();
		}

		// === GRINDING ===
		$pdf->SetFont('times', 'B', 9);
		$pdf->Cell(40 + ($jumlah_batch * 25), 5, 'GRINDING', 1, 1, 'L');
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(40, 5, 'Hasil Grinding', 1);
		for ($i = 0; $i < $jumlah_batch; $i++) {
			$val = $parsed_data['grinding']['hasil_grinding'][$i] ?? '';
			$pdf->SetFont('dejavusans', 'B', 9);
			if (strtolower(trim($val)) === 'oke') {
				$val = '✓';
			}
			$pdf->Cell(25, 5, $val, 1, 0, 'C');
		}
		$pdf->Ln();

		// === DRYING ===
		$pdf->SetFont('times', 'B', 9);
		$pdf->Cell(40 + ($jumlah_batch * 25), 5, 'DRYING', 1, 1, 'L');
		$pdf->SetFont('times', '', 8);

		$params_drying = [
			'Suhu Setting / Aktual (85° - 90°C)' => ['suhu_setting', 'suhu_aktual'],
			'Dryer Speed (4 - 6 rpm)' => ['dryer_speed']
		];

		foreach ($params_drying as $label => $fields) {
			$pdf->Cell(40, 5, $label, 1);
			for ($i = 0; $i < $jumlah_batch; $i++) {
				$val = '';
				foreach ($fields as $idx => $field) {
					$val .= ($parsed_data['drying'][$field][$i] ?? '') . ($idx < count($fields) - 1 ? ' / ' : '');
				}
				$pdf->Cell(25, 5, $val, 1);
			}
			$pdf->Ln();
		}

		// === PEMERIKSAAN FINISHED PRODUCT ===
		$pdf->SetFont('times', 'B', 9);
		$pdf->Cell(40 + ($jumlah_batch * 25), 5, 'PEMERIKSAAN FINISHED PRODUCT', 1, 1, 'L');
		$pdf->SetFont('times', '', 7);

		$params_fp = [
			'Nama Produk' => 'nama_produk',
			'Kode Produksi' => 'kode_produksi',
			'Best Before' => 'best_before',
			'Suhu Produk Sebelum Packing (32 - 35°C)' => 'suhu_sebelum_packing',
			'Kadar Air Produk (4 - 8%)' => 'kadar_air_produk',
			'Bulk Density (225 - 325 g/l)' => 'bulk_density',
			'Sensori Produk' => 'sensori_produk',
			'Kondisi Kemasan/Ketepatan Labelisasi' => ['kondisi_kemasan', 'ketepatan_labelisasi'],
			'Kode Supplier' => 'kode_supplier',
			'Net Weight' => 'net_weight',
			'Bukti Labelisasi' => 'bukti_labelisasi',
		];

		$cell_width_default = 25;
		$cell_width_image = 25;
		$cell_height_image = 20;

		foreach ($params_fp as $label => $field) {
			$pdf->SetFont('times', '', 8);
			$cell_height = ($field === 'bukti_labelisasi') ? $cell_height_image : 5;
			$pdf->Cell(40, $cell_height, $label, 1, 0, 'L');

			for ($i = 0; $i < $jumlah_batch; $i++) {
				$val = '';

				if (is_array($field)) {
					$combined = [];
					foreach ($field as $f) {
						$data_val = $parsed_data['pemeriksaan_finished_product'][$f][$i] ?? '';
						if (in_array($f, ['kondisi_kemasan', 'ketepatan_labelisasi']) && trim($data_val) === 'Oke') {
							$data_val = '✓';
							$combined[] = ['text' => $data_val, 'font' => 'dejavusans'];
						} else {
							$combined[] = ['text' => $data_val, 'font' => 'times'];
						}
					}
					$val = implode(' / ', array_column($combined, 'text'));
					$font = end($combined)['font'];
					$pdf->SetFont($font, '', 8);
					$pdf->Cell($cell_width_default, $cell_height, $val, 1, 0, 'C');
					$pdf->SetFont('times', '', 8);
				} elseif ($field === 'sensori_produk') {
					$val = $parsed_data['pemeriksaan_finished_product'][$field][$i] ?? '';
					if (trim($val) === 'Oke') {
						$pdf->SetFont('dejavusans', '', 8);
						$val = '✓';
					} else {
						$pdf->SetFont('times', '', 8);
					}
					$pdf->Cell($cell_width_default, $cell_height, $val, 1, 0, 'C');
				} elseif ($field === 'bukti_labelisasi') {
					$image_path = $parsed_data['pemeriksaan_finished_product'][$field][$i] ?? '';
					$image_full_path = FCPATH . '/uploads/bukti_labelisasi/' . $image_path;

					if (!empty($image_path) && file_exists($image_full_path)) {
						$x = $pdf->GetX();
						$y = $pdf->GetY();

						$pdf->Cell($cell_width_image, $cell_height_image, '', 1, 0, 'C');
						$pdf->Image($image_full_path, $x + 1.5, $y + 1.5, $cell_width_image - 3, $cell_height_image - 3, '', '', '', false, 300);
					} else {
						$pdf->Cell($cell_width_image, $cell_height_image, '-', 1, 0, 'C');
					}
				} else {
					$val = $parsed_data['pemeriksaan_finished_product'][$field][$i] ?? '';
					$pdf->SetFont('times', '', 8);
					$pdf->Cell($cell_width_default, $cell_height, $val, 1, 0, 'C');
				}
			}
			$pdf->Ln();
		}

		$pdf->Ln(2);
		$pdf->SetFont('times', '', 8);
		$pdf->Cell(10, 5, 'Catatan :', 0, 1, 'L');
		foreach ($data['proses_data'] ?? [] as $item) {
			if (!empty($item->catatan)) {
				$pdf->Cell(50, 5, ' - ' . $item->catatan, 0, 1, 'L');
			}
		}

		$y_after_keterangan = $pdf->GetY() + 2;
		$status_verifikasi = true;
		foreach ($proses_data as $item) {
			if ($item->status_spv != '1') {
				$status_verifikasi = false;
				break;
			}
		}

		$pdf->SetFont('times', '', 8);
		$pdf->SetTextColor(0, 0, 0);

		if ($status_verifikasi) {
			$y_verifikasi = $y_after_keterangan;
			$pdf->SetXY(45, $y_verifikasi + 5);
			$pdf->Cell(55, 5, 'Dibuat Oleh,', 0, 0, 'C');
			if (!empty($data['proses']->nama_lengkap_qc)) {
				$update_tanggal_qc = !empty($data['proses']->created_at)
				? (new DateTime($data['proses']->created_at))->format('d-m-Y | H:i')
				: date('d-m-Y | H:i');

				$qr_text_qc = "Dibuat secara digital oleh,\n" .
				$data['proses']->nama_lengkap_qc . "\nQC Inspector\n" . $update_tanggal_qc;
				$pdf->write2DBarcode($qr_text_qc, 'QRCODE,L', 65, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(45, $y_verifikasi + 24);
				$pdf->Cell(55, 5, 'QC Inspector', 0, 0, 'C');
			} else {
				$pdf->SetXY(45, $y_verifikasi + 10);
				$pdf->Cell(55, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}

			$pdf->SetXY(130, $y_verifikasi + 5);
			$pdf->Cell(85, 5, 'Diketahui Oleh,', 0, 0, 'C');
			if ($data['proses']->status_produksi == 1 && !empty($data['proses']->nama_produksi)) {
				$update_tanggal_produksi = (new DateTime($data['proses']->tgl_update_prod))->format('d-m-Y | H:i');
				$qr_text_produksi = "Diketahui secara digital oleh,\n" . $data['proses']->nama_produksi . "\nForeman/Forelady Produksi\n" . $update_tanggal_produksi;
				$pdf->write2DBarcode($qr_text_produksi, 'QRCODE,L', 165, $y_verifikasi + 10, 15, 15, null, 'N');
				$pdf->SetXY(130, $y_verifikasi + 24);
				$pdf->Cell(85, 5, 'Foreman/Forelady Produksi', 0, 0, 'C');
			} else {
				$pdf->SetXY(130, $y_verifikasi + 10);
				$pdf->Cell(85, 5, 'Belum Diverifikasi', 0, 0, 'C');
			}

			$pdf->SetXY(210, $y_verifikasi + 5);
			$pdf->Cell(119, 5, 'Disetujui Oleh,', 0, 0, 'C');
			$update_tanggal = (new DateTime($data['proses']->tgl_update))->format('d-m-Y | H:i');
			$qr_text = "Diverifikasi secara digital oleh,\n" . $data['proses']->nama_lengkap_spv . "\nSPV QC Bread Crumb\n" . $update_tanggal;
			$pdf->write2DBarcode($qr_text, 'QRCODE,L', 262, $y_verifikasi + 10, 15, 15, null, 'N');
			$pdf->SetXY(190, $y_verifikasi + 24);
			$pdf->Cell(159, 5, 'Supervisor QC', 0, 0, 'C');
		} else {
			$pdf->SetTextColor(255, 0, 0);
			$pdf->SetFont('times', '', 8);
			$pdf->SetXY(100, $y_after_keterangan);
			$pdf->Cell(80, 5, 'Data Belum Diverifikasi', 0, 0, 'C');
		}
	}

	public function export_excel()
	{
		ini_set('memory_limit', '1024M');
		set_time_limit(0);

		$tanggal = $this->input->post('tanggal');
		$shift   = $this->input->post('shift');

		if (!$tanggal || !$shift) {
			show_error('Tanggal atau Shift tidak dipilih', 404);
		}

		$rawData = $this->proses_model
		->get_by_tanggal_shift_verif_excel($tanggal, $shift);

		if (empty($rawData)) {
			show_error('Data tidak ditemukan', 404);
		}

		/* ================= LOAD TEMPLATE ================= */

		$reader = IOFactory::createReader('Xlsx');
		$reader->setReadDataOnly(false);

		$spreadsheet = $reader->load(
			FCPATH . 'assets/excel/Verifikasi Proses Produksi.xlsx'
		);

		$sheetProduksi = $spreadsheet->getSheet(0);
		$sheetPacking  = $spreadsheet->getSheet(1);

		/* ================= FORMAT TANGGAL ================= */

		$hariIndonesia  = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
		$bulanIndonesia = ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];

		$dt = new DateTime($tanggal);

		$tanggalFormatted =
		$hariIndonesia[(int)$dt->format('w')] . ', ' .
		$dt->format('d') . ' ' .
		$bulanIndonesia[(int)$dt->format('m')] . ' ' .
		$dt->format('Y');

/* ==================================================
   =============== PRODUKSI =========================
   ================================================== */

// ================= HEADER GLOBAL (HANYA SEKALI) =================
   $sheetProduksi->setCellValue('D8', $tanggalFormatted);
   $sheetProduksi->setCellValue('D10', ($rawData[0]->nama_produk ?? ''));

// ================= TEMPLATE HANYA AREA TABEL =================
   $templateStartRow = 13; 
   $templateEndRow   = 27; 
   $templateHeight   = $templateEndRow - $templateStartRow + 1;

   $startCol = 'A';
   $endCol   = 'Z';

   $startColIndex = Coordinate::columnIndexFromString('H');

   foreach ($rawData as $i => $rowData) {

   	$rowOffset = $i * $templateHeight;

   	if ($i > 0) {

   		$insertRow = $templateStartRow + $rowOffset;

        // Insert block kosong untuk tabel baru
   		$sheetProduksi->insertNewRowBefore($insertRow, $templateHeight);

        // COPY VALUE + STYLE PER CELL (AGAR BORDER & WARNA IKUT)
   		for ($r = 0; $r < $templateHeight; $r++) {

   			for ($c = Coordinate::columnIndexFromString($startCol);
   				$c <= Coordinate::columnIndexFromString($endCol);
   				$c++) {

   				$colLetter = Coordinate::stringFromColumnIndex($c);

   				$sourceCell = $colLetter . ($templateStartRow + $r);
   				$targetCell = $colLetter . ($insertRow + $r);

                // Copy value
   				$sheetProduksi->setCellValue(
   					$targetCell,
   					$sheetProduksi->getCell($sourceCell)->getValue()
   				);

                // Copy full style (border, fill, font, alignment)
   				$sheetProduksi->duplicateStyle(
   					$sheetProduksi->getStyle($sourceCell),
   					$targetCell
   				);
   			}
   		}

        // COPY MERGE DI AREA TABEL SAJA
   		foreach ($sheetProduksi->getMergeCells() as $mergeRange) {

   			if (preg_match('/([A-Z]+)(\d+):([A-Z]+)(\d+)/', $mergeRange, $match)) {

   				$mergeStartRow = (int)$match[2];
   				$mergeEndRow   = (int)$match[4];

   				if ($mergeStartRow >= $templateStartRow &&
   					$mergeEndRow   <= $templateEndRow) {

   					$newRange = preg_replace_callback(
   						'/\d+/',
   						function($m) use ($rowOffset) {
   							return $m[0] + $rowOffset;
   						},
   						$mergeRange
   					);

   					$sheetProduksi->mergeCells($newRange);
   				}
   			}
   		}
   	}

    // ================= ISI DATA PRODUKSI =================

   	$produksi = json_decode($rowData->proses_produksi, true);
   	if (!is_array($produksi)) continue;

   	$kodeProduksi = $produksi['dough_mixing']['kode_produksi'] ?? [];
   	if (empty($kodeProduksi)) continue;

   	$paramMap = [
   		15 => ['kondisi_rm','chill_water'],
   		16 => ['mixing','suhu_adonan'],
   		19 => ['proofing','suhu_aktual'],
   		20 => ['proofing','rh_aktual'],
   		21 => ['proofing','durasi_waktu'],
   		25 => ['hasil_baking','suhu_produk'],
   	];

   	$currentColIndex = $startColIndex;
   	$filledCols = [];

   	foreach ($kodeProduksi as $k => $kode) {

   		if ($k == 0 || empty($kode)) continue;

   		$colLetter = Coordinate::stringFromColumnIndex($currentColIndex);

   		$sheetProduksi->setCellValueExplicit(
   			$colLetter.(13 + $rowOffset),
   			$kode,
   			\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
   		);

   		foreach ($paramMap as $row => $map) {

   			$value = $produksi[$map[0]][$map[1]][$k-1] ?? null;

   			if ($value !== null && $value !== '') {
   				$sheetProduksi->setCellValue(
   					$colLetter.($row + $rowOffset),
   					$value
   				);
   			}
   		}

   		$filledCols[] = $currentColIndex;
   		$currentColIndex++;
   	}

   	if (empty($filledCols)) continue;

   	$lastFilledCol = end($filledCols);

   	$avgCol = Coordinate::stringFromColumnIndex($lastFilledCol + 1);
   	$minCol = Coordinate::stringFromColumnIndex($lastFilledCol + 2);
   	$maxCol = Coordinate::stringFromColumnIndex($lastFilledCol + 3);

   	$startDataCol = Coordinate::stringFromColumnIndex($startColIndex);
   	$endDataCol   = Coordinate::stringFromColumnIndex($lastFilledCol);

   	foreach (array_keys($paramMap) as $row) {

   		$range = "{$startDataCol}".($row+$rowOffset).
   		":{$endDataCol}".($row+$rowOffset);

   		$sheetProduksi->setCellValue(
   			$avgCol.($row+$rowOffset),
   			"=IFERROR(AVERAGE($range),\"\")"
   		);
   		$sheetProduksi->setCellValue(
   			$minCol.($row+$rowOffset),
   			"=IFERROR(MIN($range),\"\")"
   		);
   		$sheetProduksi->setCellValue(
   			$maxCol.($row+$rowOffset),
   			"=IFERROR(MAX($range),\"\")"
   		);
   	}
   }
    /* ==================================================
       =============== PACKING ==========================
       ================================================== */

       $sheetPacking->setCellValue('D8', $tanggalFormatted);
       $sheetPacking->setCellValue('D10',($rawData[0]->nama_produk ?? ''));

       $startColIndex   = Coordinate::columnIndexFromString('H');
       $currentColIndex = $startColIndex;

       foreach ($rawData as $rowData) {

       	$packing = json_decode($rowData->proses_packing, true);
       	if (empty($packing[0])) continue;

       	$p = $packing[0];
       	$kode = $p['pemeriksaan_finished_product']['kode_produksi'][0] ?? '';

       	if (empty($kode)) continue;

       	$colLetter = Coordinate::stringFromColumnIndex($currentColIndex);

       	$sheetPacking->setCellValueExplicit(
       		$colLetter.'14',
       		$kode,
       		DataType::TYPE_STRING
       	);

       	$mapSheet2 = [
       		16 => $p['stalling_aging']['lama_aging'][0] ?? '',
       		19 => $p['stalling_aging']['kadar_air'][0] ?? '',
       		20 => ($p['drying']['suhu_setting'][0] ?? '') . '/' .
       		($p['drying']['suhu_aktual'][0] ?? ''),
       		21 => $p['drying']['dryer_speed'][0] ?? '',
       		24 => $p['pemeriksaan_finished_product']['suhu_sebelum_packing'][0] ?? '',
       		25 => $p['pemeriksaan_finished_product']['kadar_air_produk'][0] ?? '',
       		26 => $p['pemeriksaan_finished_product']['bulk_density'][0] ?? '',
       		27 => $p['pemeriksaan_finished_product']['net_weight'][0] ?? '',
       		29 => $p['pemeriksaan_finished_product']['kondisi_kemasan'][0] ?? '',
       		30 => $p['pemeriksaan_finished_product']['ketepatan_labelisasi'][0] ?? '',
       	];

       	foreach ($mapSheet2 as $row => $value) {
       		if ($value !== '') {
       			$sheetPacking->setCellValue($colLetter.$row, $value);
       		}
       	}

       	$currentColIndex++;
       }

       /* ================= OUTPUT ================= */

       gc_collect_cycles();

       $filename = "Verifikasi_Produksi_{$tanggal}_Shift{$shift}.xlsx";

       header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
       header('Content-Disposition: attachment; filename="'.$filename.'"');
       header('Cache-Control: max-age=0');

       $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
       $writer->setPreCalculateFormulas(false);
       $writer->save('php://output');
       exit;
   }
}
