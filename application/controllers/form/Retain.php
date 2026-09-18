<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use Dompdf\Dompdf;
setlocale(LC_TIME, 'id_ID.UTF-8');

class Retain extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->model('auth_model'); 
		$this->load->model('retain_model');
		$this->load->model('plant_model');
		if(!$this->auth_model->current_user()){
			redirect('login');
		}
	}

	public function index()
	{
		$data = array(
			'retain' => $this->retain_model->get_data_by_plant()
		);

		$this->active_nav = 'retain'; 
		$this->render('form/retain/retain', $data); 
	}

	public function detail($uuid)
	{
		$data = array(
			'retain' => $this->retain_model->get_retain_with_plant($uuid),
		);

		$this->active_nav = 'retain'; 
		$this->render('form/retain/retain-detail', $data);
	}

	public function tambah()
	{

		$rules = $this->retain_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			$insert = $this->retain_model->insert();
			if ($insert) {
				$this->session->set_flashdata('success_msg', 'Data Retain Sample Report berhasil di simpan');
				redirect('retain');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Retain Sample Report gagal di simpan');
				redirect('retain');
			}
		}

		$this->active_nav = 'retain'; 
		$this->render('form/retain/retain-tambah');
	}

	public function edit($uuid)
	{
		$rules = $this->retain_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {
			
			$update = $this->retain_model->update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Retain Sample Report berhasil di Update');
				redirect('retain');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Retain Sample Report gagal di Update');
				redirect('retain');
			}
		}

		$data = array(
			'retain' => $this->retain_model->get_by_uuid($uuid)
		);
		$this->render('form/retain/retain-edit', $data);
	}

	public function delete($uuid)
	{
		if (!$uuid) {
			$this->session->set_flashdata('error_msg', 'ID tidak ditemukan.');
			redirect('retain');
		}

		$deleted = $this->retain_model->delete_by_uuid($uuid);

		if ($deleted) {
			$this->session->set_flashdata('success_msg', 'Data berhasil dihapus.');
		} else {
			$this->session->set_flashdata('error_msg', 'Gagal menghapus data.');
		}

		redirect('retain');
	}
	
	public function verifikasi()
	{
		$data = array(
			'retain' => $this->retain_model->get_data_by_plant()
		);

		$this->active_nav = 'verifikasi-retain'; 
		$this->render('form/retain/retain-verifikasi', $data);
	}

	public function status($uuid)
	{
		$rules = $this->retain_model->rules_verifikasi();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() == TRUE) {

			$update = $this->retain_model->verifikasi_update($uuid);
			if ($update) {
				$this->session->set_flashdata('success_msg', 'Data Retain Sample Report berhasil di Update');
				redirect('retain/verifikasi');
			}else {
				$this->session->set_flashdata('error_msg', 'Data Retain Sample Report gagal di Update');
				redirect('retain/verifikasi');
			}
		}

		$data = array(
			'retain' => $this->retain_model->get_retain_with_plant($uuid),
		);

		$this->active_nav = 'verifikasi-retain'; 
		$this->render('form/retain/retain-status', $data);
	}

	// public function diketahui()
	// {
	// 	$data = array(
	// 		'retain' => $this->retain_model->get_data_by_plant(),
	// 		'active_nav' => 'diketahui-retain', 
	// 	);

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/retain/retain-diketahui', $data);
	// 	$this->load->view('partials/footer');
	// }


	// public function statusprod($uuid)
	// {
	// 	$rules = $this->retain_model->rules_diketahui();
	// 	$this->form_validation->set_rules($rules);

	// 	if ($this->form_validation->run() == TRUE) {
	
	// 		$update = $this->retain_model->diketahui_update($uuid);
	// 		if ($update) {
	// 			$this->session->set_flashdata('success_msg', 'Status Retain Sample Report berhasil di Update');
	// 			redirect('retain/diketahui');
	// 		}else {
	// 			$this->session->set_flashdata('error_msg', 'Status Retain Sample Report gagal di Update');
	// 			redirect('retain/diketahui');
	// 		}
	// 	}

	// 	$data = array(
	// 		'retain' => $this->retain_model->get_by_uuid($uuid),
	// 		'active_nav' => 'diketahui-retain');

	// 	$this->load->view('partials/head', $data);
	// 	$this->load->view('form/retain/retain-statusprod', $data);
	// 	$this->load->view('partials/footer');
	// }

	public function cetak()
	{
		$selected_items = $this->input->post('checkbox');

		if (empty($selected_items)) {
			show_error('Tidak ada item yang dipilih', 404);
		}

		$retain_data = $this->retain_model->get_by_uuid_retain($selected_items);
		$retain_data_verif = $this->retain_model->get_by_uuid_retain_verif($selected_items);

		if (empty($retain_data_verif)) {
			show_error('Data tidak ditemukan, Pilih data yang ingin dicetak', 404);
		}

    $data['retain'] = $retain_data_verif[0]; // ambil salah satu sebagai header

    require_once APPPATH . 'third_party/tcpdf/tcpdf.php';

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'LEGAL', true, 'UTF-8', false);
    $pdf->setPrintHeader(false);
    $pdf->SetMargins(10, 10, 10);
    $pdf->AddPage();
    $pdf->SetFont('times', 'B', 12);

    // Logo
    $logo_path = FCPATH . 'assets/img/logo.jpg';
    if (file_exists($logo_path)) {
    	$pdf->Image($logo_path, 10, 10, 35);
    }

    $pdf->Ln(10);
    $pdf->MultiCell(0, 5, 'RETAIN SAMPLE REPORT', 0, 'C');
    $pdf->Ln(5);

    setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'indonesian');
    $tanggal = $data['retain']->date;
    $datetime = new DateTime($tanggal);
    $formatted_date = date('l, d F Y', $date->getTimestamp());

    $pdf->SetFont('times', '', 9);
    $label_width = 35;
    $value_width = 80;

    $pdf->Cell($label_width, 5, 'Plant', 0, 0);
    $pdf->Cell(2, 5, ':', 0, 0);
    $pdf->Cell($value_width, 5, $data['retain']->plant, 0, 1);

    $pdf->Cell($label_width, 5, 'Sample Type', 0, 0);
    $pdf->Cell(2, 5, ':', 0, 0);
    $pdf->Cell($value_width, 5, $data['retain']->sample_type, 0, 1);

    $pdf->Cell($label_width, 5, 'Collection Date', 0, 0);
    $pdf->Cell(2, 5, ':', 0, 0);
    $pdf->Cell($value_width, 5, $formatted_date, 0, 1);

    $pdf->Cell($label_width, 5, 'Sample Storage', 0, 0);
    $pdf->Cell(2, 5, ':', 0, 0);
    $pdf->Cell($value_width, 5, $data['retain']->sample_storage, 0, 1);

    $pdf->Ln(2);

    // Header tabel
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(10, 8, 'No', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Nama Produk', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Kode Produksi', 1, 0, 'C');
    $pdf->Cell(30, 8, 'Best Before', 1, 0, 'C');
    $pdf->Cell(30, 8, 'Quantity (gr)', 1, 0, 'C');
    $pdf->Cell(35, 8, 'Remarks', 1, 1, 'C');

    $pdf->SetFont('times', '', 9);
    $no = 1;
    foreach ($retain_data_verif as $item) {
    	if (!empty($item->description)) {
    		$desc_array = json_decode($item->description, true);
    		foreach ($desc_array as $row) {
    			$bb = !empty($row['best_before']) ? (new DateTime($row['best_before']))->format('d-m-Y') : '-';
    			$pdf->Cell(10, 6, $no++, 1, 0, 'C');
    			$pdf->Cell(40, 6, $row['nama_produk'] ?? '-', 1, 0, 'L');
    			$pdf->Cell(40, 6, $row['kode_produksi'] ?? '-', 1, 0, 'L');
    			$pdf->Cell(30, 6, $bb, 1, 0, 'C');
    			$pdf->Cell(30, 6, $row['quantity'] ?? '-', 1, 0, 'C');
    			$pdf->Cell(35, 6, $row['remarks'] ?? '-', 1, 1, 'L');
    		}
    	}
    }

    $pdf->SetFont('times', 'I', 7);
    $pdf->Cell(190, 5, 'QB 12/00', 0, 1, 'R'); 

    // Catatan
    $this->load->model('pegawai_model');
    $data['retain']->nama_lengkap_qc = $this->pegawai_model->get_nama_lengkap($data['retain']->username);
    $data['retain']->nama_lengkap_spv = $this->pegawai_model->get_nama_lengkap($data['retain']->nama_spv);

    $pdf->Ln(2);
    $pdf->SetFont('times', '', 7);
    $pdf->Cell(5, 3, 'Catatan : ', 0, 1, 'L');
    foreach ($retain_data as $item) {
    	if (!empty($item->catatan)) {
    		$pdf->Cell(8, 0, '', 0, 0, 'L');
    		$pdf->Cell(200, 0, ' - ' . $item->catatan, 0, 1, 'L');
    	}
    }

    $y = $pdf->GetY();
    $status_verifikasi = true;
    foreach ($retain_data as $item) {
    	if ($item->status_spv != '1') {
    		$status_verifikasi = false;
    		break;
    	}
    }

    $y_ttd   = $pdf->GetY() + 6;
    $qr_size = 15;

    $qc_usernames  = [];
    $qc_created_at = null;

    foreach ($retain_data as $item) {
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

    $spv_tanggal = !empty($data['retain_data']->tgl_update_spv)
    ? (new DateTime($data['retain']->tgl_update_spv))->format('d-m-Y | H:i')
    : '-';

    $qr_spv_text = "Disetujui secara digital oleh,\n"
    . $data['retain']->nama_lengkap_spv . "\n"
    . "Supervisor QC Bread Crumb\n"
    . $spv_tanggal;

    if ($status_verifikasi) {
    	$pdf->SetFont('times', '', 8);
    	$pdf->SetXY(20, $y_ttd);
    	$pdf->Cell(45, 5, 'Dibuat Oleh,', 0, 0, 'C');
    	$pdf->SetXY(85, $y_ttd);
    	$pdf->Cell(175, 5, 'Disetujui Oleh,', 0, 1, 'C');
    	$pdf->write2DBarcode($qr_qc_text, 'QRCODE,L', 35,$y_ttd + 5, $qr_size, $qr_size, null, 'N');
    	$pdf->write2DBarcode($qr_spv_text, 'QRCODE,L', 165, $y_ttd + 5, $qr_size, $qr_size, null, 'N');
    	$pdf->SetXY(20, $y_ttd + 20);
    	$pdf->Cell(45, 5, 'QC Inspector', 0, 0, 'C');
    	$pdf->SetXY(85, $y_ttd + 20);
    	$pdf->Cell(175, 5, 'Supervisor QC', 0, 1, 'C');
    } else {
    	$pdf->SetFont('times', '', 8);
    	$pdf->SetTextColor(255, 0, 0);
    	$pdf->SetXY(80, $y_ttd);
    	$pdf->Cell(80, 6, 'Data Belum Diverifikasi', 0, 1, 'C');
    	$pdf->SetTextColor(0, 0, 0);
    }
    $pdf->setPrintFooter(false);
    $filename = "Retain Sample Report_" . date('d-m-Y') . ".pdf";
    $pdf->Output($filename, 'I');
}

}

