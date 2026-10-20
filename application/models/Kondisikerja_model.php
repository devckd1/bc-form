<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Kondisikerja_model extends CI_Model
{

	public function rules()
	{
		return [
			[
				'field' => 'date',
				'label' => 'Date',
				'rules' => 'required'
			],
			[
				'field' => 'shift',
				'label' => 'Shift',
				'rules' => 'required'
			],
			[
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			],
			[
				'field' => 'waktu',
				'label' => 'Time',
				'rules' => 'required'
			],
			[
				'field' => 'kondisi_higiene',
				'label' => 'Condition',
				'rules' => 'required'
			],
			[
				'field' => 'problem_higiene',
				'label' => 'Problem'
			],
			[
				'field' => 'tindakan_higiene',
				'label' => 'Action'
			],
			[
				'field' => 'verifikasi_higiene',
				'label' => 'Verification'
			],
			[
				'field' => 'kondisi_kebersihan',
				'label' => 'Condition',
				'rules' => 'required'
			],
			[
				'field' => 'problem_kebersihan',
				'label' => 'Problem'
			],
			[
				'field' => 'tindakan_kebersihan',
				'label' => 'Action'
			],
			[
				'field' => 'verifikasi_kebersihan',
				'label' => 'Verification'
			],
			[
				'field' => 'kondisi_peralatan',
				'label' => 'Condition',
				'rules' => 'required'
			],
			[
				'field' => 'problem_peralatan',
				'label' => 'Problem'
			],
			[
				'field' => 'tindakan_peralatan',
				'label' => 'Action'
			],
			[
				'field' => 'verifikasi_peralatan',
				'label' => 'Verification'
			],
			[
				'field' => 'catatan',
				'label' => 'Notes'
			]
		];
	}

	public function insert()
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$uuid       = Uuid::uuid4()->toString();
		$uuid_audit = Uuid::uuid4()->toString();

		$username = $this->session->userdata('username');
		$plant    = $this->session->userdata('plant');

		$date  = $this->input->post('date');
		$shift = $this->input->post('shift');
		$area  = $this->input->post('area');
		$waktu = $this->input->post('waktu');

		$data = array(
			'uuid'                  => $uuid,
			'is_audit'              => 0,
			'source_uuid'           => null,
			'username'              => $username,
			'plant'                 => $plant,
			'date'                  => $date,
			'shift'                 => $shift,
			'area'                  => $area,
			'waktu'                 => $waktu,

			'kondisi_higiene'       => $this->input->post('kondisi_higiene'),
			'problem_higiene'       => $this->input->post('problem_higiene'),
			'tindakan_higiene'      => $this->input->post('tindakan_higiene'),
			'verifikasi_higiene'   => $this->input->post('verifikasi_higiene'),

			'kondisi_kebersihan'    => $this->input->post('kondisi_kebersihan'),
			'problem_kebersihan'    => $this->input->post('problem_kebersihan'),
			'tindakan_kebersihan'   => $this->input->post('tindakan_kebersihan'),
			'verifikasi_kebersihan' => $this->input->post('verifikasi_kebersihan'),

			'kondisi_peralatan'      => $this->input->post('kondisi_peralatan'),
			'problem_peralatan'      => $this->input->post('problem_peralatan'),
			'tindakan_peralatan'     => $this->input->post('tindakan_peralatan'),
			'verifikasi_peralatan'  => $this->input->post('verifikasi_peralatan'),

			'catatan'               => $this->input->post('catatan'),

			'status_produksi'       => '1',
			'nama_produksi'         => $nama_produksi,
			'status_spv'            => '0'
		);


		/*
     * ============================================================
     * TRANSACTION
     * ============================================================
     */

		$this->db->trans_start();


		/*
     * ============================================================
     * 1. SIMPAN DATA ASLI
     * ============================================================
     */

		$this->db->insert('kondisi_kerja', $data);


		/*
     * ============================================================
     * 2. BUAT DATA AUDIT
     * ============================================================
     */

		$data_audit = $data;

		$data_audit['uuid']        = $uuid_audit;
		$data_audit['is_audit']    = 1;
		$data_audit['source_uuid'] = $uuid;


		/*
     * ============================================================
     * LOGIC AUDIT
     *
     * Kosong
     *    -> tetap kosong
     *
     * Kondisi sudah OK
     *    -> tetap seperti data asli
     *
     * Kondisi selain OK
     *    -> Kondisi = OK
     *    -> Problem = -
     *    -> Tindakan = -
     *    -> Verifikasi = OK
     * ============================================================
     */

		$kondisi_ok = '✓';

		$sections = array(
			'higiene',
			'kebersihan',
			'peralatan'
		);

		foreach ($sections as $section) {

			$field_kondisi    = 'kondisi_' . $section;
			$field_problem    = 'problem_' . $section;
			$field_tindakan   = 'tindakan_' . $section;
			$field_verifikasi = 'verifikasi_' . $section;

			$kondisi_asli = $data[$field_kondisi] ?? '';

			// Data asli kosong → tetap kosong
			if ($kondisi_asli === null || trim($kondisi_asli) === '') {

				$data_audit[$field_kondisi]    = '';
				$data_audit[$field_problem]    = '';
				$data_audit[$field_tindakan]   = '';
				$data_audit[$field_verifikasi] = '';

				continue;
			}

			// Data asli sudah OK → pertahankan data asli
			if (trim($kondisi_asli) === '✓') {
				continue;
			}

			// Data asli bukan OK → ubah menjadi kondisi OK
			$data_audit[$field_kondisi]    = '✓';
			$data_audit[$field_problem]    = '-';
			$data_audit[$field_tindakan]   = '-';
			$data_audit[$field_verifikasi] = 'OK';
		}


		/*
     * Audit tidak membawa status approval QC asli.
     * Tetap menjadi salinan audit.
     */

		$data_audit['status_produksi'] = '1';
		$data_audit['status_spv']      = '0';


		/*
     * ============================================================
     * SIMPAN AUDIT
     * ============================================================
     */

		$this->db->insert('kondisi_kerja', $data_audit);


		/*
     * ============================================================
     * SELESAI TRANSACTION
     * ============================================================
     */

		$this->db->trans_complete();


		if ($this->db->trans_status() === FALSE) {
			return false;
		}

		return true;
	}

	public function update($uuid)
	{
		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$area = $this->input->post('area');
		$waktu = $this->input->post('waktu');
		$kondisi_higiene = $this->input->post('kondisi_higiene');
		$problem_higiene = $this->input->post('problem_higiene');
		$tindakan_higiene = $this->input->post('tindakan_higiene');
		$verifikasi_higiene = $this->input->post('verifikasi_higiene');
		$kondisi_kebersihan = $this->input->post('kondisi_kebersihan');
		$problem_kebersihan = $this->input->post('problem_kebersihan');
		$tindakan_kebersihan = $this->input->post('tindakan_kebersihan');
		$verifikasi_kebersihan = $this->input->post('verifikasi_kebersihan');
		$kondisi_peralatan = $this->input->post('kondisi_peralatan');
		$problem_peralatan = $this->input->post('problem_peralatan');
		$tindakan_peralatan = $this->input->post('tindakan_peralatan');
		$verifikasi_peralatan = $this->input->post('verifikasi_peralatan');
		$catatan = $this->input->post('catatan');
		$old_data = $this->db->get_where('kondisi_kerja', ['uuid' => $uuid])->row_array();
		$data = array(
			'username' => $username,
			'date' => $date,
			'shift' => $shift,
			'area' => $area,
			'waktu' => $waktu,
			'kondisi_higiene' => $kondisi_higiene,
			'problem_higiene' => $problem_higiene,
			'tindakan_higiene' => $tindakan_higiene,
			'verifikasi_higiene' => $verifikasi_higiene,
			'kondisi_kebersihan' => $kondisi_kebersihan,
			'problem_kebersihan' => $problem_kebersihan,
			'tindakan_kebersihan' => $tindakan_kebersihan,
			'verifikasi_kebersihan' => $verifikasi_kebersihan,
			'kondisi_peralatan' => $kondisi_peralatan,
			'problem_peralatan' => $problem_peralatan,
			'tindakan_peralatan' => $tindakan_peralatan,
			'verifikasi_peralatan' => $verifikasi_peralatan,
			'catatan' => $catatan,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('kondisi_kerja', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('kondisi_kerja', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus kondisi_kerja_logs
			$this->activity_logger->log_activity(
				'update',
				'kondisi_kerja_logs', // nama tabel log khusus kondisi_kerja
				$uuid,
				$old_data,
				$new_data
			);
			return true;
		}
		return false;
	}

	public function rules_verifikasi()
	{
		return [
			[
				'field' => 'status_spv',
				'label' => 'Date',
				'rules' => 'required'
			],
			[
				'field' => 'catatan_spv',
				'label' => 'Notes'
			]

		];
	}

	public function verifikasi_update($uuid)
	{

		$nama_spv = $this->session->userdata('username');
		$status_spv = $this->input->post('status_spv');
		$catatan_spv = $this->input->post('catatan_spv');

		$data = array(
			'nama_spv' => $nama_spv,
			'status_spv' => $status_spv,
			'catatan_spv' => $catatan_spv,
			'tgl_update_spv' => date("Y-m-d H:i:s")
		);

		$this->db->update('kondisi_kerja', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function rules_diketahui()
	{
		return [
			[
				'field' => 'status_produksi',
				'label' => 'Status',
				'rules' => 'required'
			],
			[
				'field' => 'catatan_produksi',
				'label' => 'Notes'
			]

		];
	}

	public function diketahui_update($uuid)
	{

		$nama_produksi = $this->session->userdata('username');
		$status_produksi = $this->input->post('status_produksi');
		$catatan_produksi = $this->input->post('catatan_produksi');

		$data = array(
			'nama_produksi' => $nama_produksi,
			'status_produksi' => $status_produksi,
			'catatan_produksi' => $catatan_produksi,
			'tgl_update_produksi' => date("Y-m-d H:i:s")
		);

		$this->db->update('kondisi_kerja', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('kondisi_kerja')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->get('kondisi_kerja')
			->row();
	}

	public function get_by_uuid_kondisikerja($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('kondisi_kerja');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_kondisikerja_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, area, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kondisi_kerja');

		$data_kondisikerja = $query->row();
		return $data_kondisikerja;
	}

	// public function get_data_by_plant()
	// {
	// 	$this->db->order_by('created_at', 'DESC');
	// 	$plant = $this->session->userdata('plant');
	// 	$type_user = $this->session->userdata('tipe_user');

	// 	if ($type_user == 9 || $type_user == 1) {
	// 		return $this->db->get('kondisi_kerja')->result();
	// 	}
	// 	return $this->db->get_where('kondisi_kerja', ['plant' => $plant])->result();
	// }

	public function get_data_by_plant($limit, $start, $plant, $type_user)
	{
		$this->db->select('
		uuid,
		date,
		shift,
		area,
		kondisi_higiene,
		problem_higiene,
		tindakan_higiene,
		verifikasi_higiene,
		kondisi_peralatan,
		problem_peralatan,
		tindakan_peralatan,
		verifikasi_peralatan,
		kondisi_kebersihan,
		problem_kebersihan,
		tindakan_kebersihan,
		verifikasi_kebersihan,
		status_spv,
		modified_at,
		tgl_update_spv
	');

		$this->db->from('kondisi_kerja');

		// Hanya data QC utama
		$this->db->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($limit, $start);

		return $this->db->get()->result();
	}

	public function delete_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('uuid', $uuid);
		$this->db->where('is_audit', 0);
		$this->db->where('plant', $plant);

		return $this->db->delete('kondisi_kerja');
	}

	public function get_by_date($tanggal, $plant = null, $shift = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}
		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}


		$this->db->order_by('date', 'ASC');
		$query = $this->db->get('kondisi_kerja');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null, $shift = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, area, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}
		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}


		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kondisi_kerja');

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('kondisi_kerja')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kondisi_kerja')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kondisi_kerja')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('kondisi_kerja')
			->result();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		$data = array(
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'area' => $this->input->post('area'),
			'waktu' => $this->input->post('waktu'),

			'kondisi_higiene' => $this->input->post('kondisi_higiene'),
			'problem_higiene' => $this->input->post('problem_higiene'),
			'tindakan_higiene' => $this->input->post('tindakan_higiene'),
			'verifikasi_higiene' => $this->input->post('verifikasi_higiene'),

			'kondisi_kebersihan' => $this->input->post('kondisi_kebersihan'),
			'problem_kebersihan' => $this->input->post('problem_kebersihan'),
			'tindakan_kebersihan' => $this->input->post('tindakan_kebersihan'),
			'verifikasi_kebersihan' => $this->input->post('verifikasi_kebersihan'),

			'kondisi_peralatan' => $this->input->post('kondisi_peralatan'),
			'problem_peralatan' => $this->input->post('problem_peralatan'),
			'tindakan_peralatan' => $this->input->post('tindakan_peralatan'),
			'verifikasi_peralatan' => $this->input->post('verifikasi_peralatan'),

			'catatan' => $this->input->post('catatan'),

			'modified_at' => date('Y-m-d H:i:s')
		);

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('kondisi_kerja', $data);

		return $this->db->affected_rows() > 0;
	}

	public function detail_audit($uuid)
	{
		$kondisikerja = $this->kondisikerja_model->get_audit_by_uuid($uuid);

		if (!$kondisikerja) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Kondisi Kerja tidak ditemukan.'
			);

			redirect('kondisikerja/audit');
		}

		$data = array(
			'page_title'    => 'Detail Audit Kondisi Kerja',
			'kondisikerja'  => $kondisikerja
		);

		$this->active_nav = 'kondisikerja';

		$this->render(
			'form/kondisikerja/kondisikerja-detail-audit',
			$data
		);
	}


	public function edit_audit($uuid)
	{
		$kondisikerja = $this->kondisikerja_model->get_audit_by_uuid($uuid);

		if (!$kondisikerja) {
			$this->session->set_flashdata(
				'error_msg',
				'Data Audit Kondisi Kerja tidak ditemukan.'
			);

			redirect('kondisikerja/audit');
		}

		$rules = $this->kondisikerja_model->rules();
		$this->form_validation->set_rules($rules);

		if ($this->form_validation->run() === TRUE) {

			$update = $this->kondisikerja_model->update_audit($uuid);

			if ($update) {
				$this->session->set_flashdata(
					'success_msg',
					'Data Audit Kondisi Kerja berhasil diperbarui.'
				);
			} else {
				$this->session->set_flashdata(
					'error_msg',
					'Data Audit Kondisi Kerja gagal diperbarui.'
				);
			}

			redirect('kondisikerja/audit');
		}

		$data = array(
			'kondisikerja' => $kondisikerja
		);

		$this->active_nav = 'verifikasi-kondisikerja';
		$this->render(
			'form/kondisikerja/kondisikerja-edit-audit',
			$data
		);
	}

	public function apply_audit_logic($data)
	{
		$kondisi_ok = '✓ Ok, Sesuai SSOP, bersih, bebas najis / material non halal';

		$sections = [
			'higiene',
			'kebersihan',
			'peralatan'
		];

		foreach ($sections as $section) {

			$field_kondisi    = 'kondisi_' . $section;
			$field_problem    = 'problem_' . $section;
			$field_tindakan   = 'tindakan_' . $section;
			$field_verifikasi = 'verifikasi_' . $section;

			$kondisi_asli = $data->{$field_kondisi} ?? '';

			// Data asli kosong -> tetap kosong
			if ($kondisi_asli === null || trim($kondisi_asli) === '') {
				continue;
			}

			// Jika kondisi asli sudah OK -> jangan ubah
			if (
				trim($kondisi_asli) === '✓' ||
				trim($kondisi_asli) === $kondisi_ok
			) {
				continue;
			}

			// Selain OK -> normalisasi menjadi OK untuk Audit
			$data->{$field_kondisi} = $kondisi_ok;
			$data->{$field_problem} = '-';
			$data->{$field_tindakan} = '-';
			$data->{$field_verifikasi} = 'OK';
		}

		return $data;
	}
}
