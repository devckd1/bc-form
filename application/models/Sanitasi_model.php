<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;

class Sanitasi_model extends CI_Model
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
				'field' => 'waktu',
				'label' => 'Time',
				'rules' => 'required'
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

		$uuid = Uuid::uuid4()->toString();
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$waktu = $this->input->post('waktu');
		$catatan = $this->input->post('catatan');

		$status_produksi = '1';
		$status_spv = '0';

		$sub_area = $this->input->post('sub_area') ?? [];
		$standar = $this->input->post('standar') ?? [];
		$aktual = $this->input->post('aktual') ?? [];
		$suhu_air = $this->input->post('suhu_air') ?? [];
		$keterangan = $this->input->post('keterangan') ?? [];
		$tindakan = $this->input->post('tindakan') ?? [];

		$area = [];

		$this->load->library(['upload', 'image_lib']);

		for ($i = 0; $i < count($sub_area); $i++) {
			$gambar = null;

			if (
				isset($_FILES['gambar']['name'][$i]) &&
				$_FILES['gambar']['name'][$i] !== ''
			) {
				$_FILES['file']['name']     = $_FILES['gambar']['name'][$i];
				$_FILES['file']['type']     = $_FILES['gambar']['type'][$i];
				$_FILES['file']['tmp_name'] = $_FILES['gambar']['tmp_name'][$i];
				$_FILES['file']['error']    = $_FILES['gambar']['error'][$i];
				$_FILES['file']['size']     = $_FILES['gambar']['size'][$i];

				$config = [
					'upload_path'   => './uploads/sanitasi/',
					'allowed_types' => 'jpg|jpeg|png|webp',
					'file_name'     => 'gambar_' . time() . '_' . $i,
					'overwrite'     => false
				];

				$this->upload->initialize($config);

				if ($this->upload->do_upload('file')) {
					$uploadData = $this->upload->data();
					$gambar = $uploadData['file_name'];

					// Kompres gambar setelah upload.
					$config_compress = [
						'image_library'  => 'gd2',
						'source_image'   => $uploadData['full_path'],
						'maintain_ratio' => true,
						'quality'        => '70%',
						'width'          => 1600,
						'height'         => 1600
					];

					$this->image_lib->initialize($config_compress);

					if (!$this->image_lib->resize()) {
						log_message(
							'error',
							'Kompres gagal: ' . $this->image_lib->display_errors()
						);
					}

					$this->image_lib->clear();
				} else {
					log_message(
						'error',
						'Upload gagal: ' . $this->upload->display_errors()
					);
				}
			}

			$area[] = [
				'sub_area'   => $sub_area[$i],
				'standar'    => $standar[$i] ?? '',
				'aktual'     => $aktual[$i] ?? '',
				'suhu_air'   => $suhu_air[$i] ?? '',
				'keterangan' => $keterangan[$i] ?? '',
				'tindakan'   => $tindakan[$i] ?? '',
				'gambar'     => $gambar
			];
		}

		$data = [
			'uuid'            => $uuid,
			'username'        => $username,
			'plant'           => $plant,
			'date'            => $date,
			'shift'            => $shift,
			'waktu'            => $waktu,
			'area'             => json_encode($area),
			'catatan'          => $catatan,
			'status_produksi'  => $status_produksi,
			'nama_produksi'    => $nama_produksi,
			'status_spv'       => $status_spv,
			'is_audit'         => 0,
			'source_uuid'      => null
		];

		$this->db->trans_begin();

		// Simpan data QC.
		$this->db->insert('sanitasi', $data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		// Salin data menjadi snapshot Audit.
		$audit_data = $data;
		$audit_data['uuid'] = Uuid::uuid4()->toString();
		$audit_data['is_audit'] = 1;
		$audit_data['source_uuid'] = $uuid;

		$this->db->insert('sanitasi', $audit_data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}

	public function update($uuid)
	{
		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$waktu = $this->input->post('waktu');
		$catatan = $this->input->post('catatan');

		$sub_area   = $this->input->post('sub_area');
		$standar    = $this->input->post('standar');
		$aktual     = $this->input->post('aktual');
		$suhu_air   = $this->input->post('suhu_air');
		$keterangan = $this->input->post('keterangan');
		$tindakan   = $this->input->post('tindakan');
		$gambar_lama = $this->input->post('gambar_lama');

		$old_data = $this->db->get_where('sanitasi', ['uuid' => $uuid])->row_array();

		$area = [];

		$this->load->library(['upload', 'image_lib']);

		for ($i = 0; $i < count($sub_area); $i++) {
			$gambar = isset($gambar_lama[$i]) ? $gambar_lama[$i] : null;

			if (!empty($_FILES['gambar']['name'][$i])) {
				$_FILES['file']['name']     = $_FILES['gambar']['name'][$i];
				$_FILES['file']['type']     = $_FILES['gambar']['type'][$i];
				$_FILES['file']['tmp_name'] = $_FILES['gambar']['tmp_name'][$i];
				$_FILES['file']['error']    = $_FILES['gambar']['error'][$i];
				$_FILES['file']['size']     = $_FILES['gambar']['size'][$i];

				$config['upload_path']   = './uploads/sanitasi/';
				$config['allowed_types'] = 'jpg|jpeg|png|webp';
				$config['file_name']     = 'gambar_' . time() . '_' . $i;
				$config['overwrite']     = false;

				$this->upload->initialize($config);

				if ($this->upload->do_upload('file')) {
					$uploadData = $this->upload->data();
					$gambar = $uploadData['file_name'];

					// --- Kompres (SSR) setelah upload ---
					$config_resize['image_library']  = 'gd2'; // SSR pakai GD2
					$config_resize['source_image']   = $uploadData['full_path'];
					$config_resize['maintain_ratio'] = TRUE;
					$config_resize['width']          = 800; // ubah sesuai kebutuhan
					$config_resize['height']         = 800;
					$config_resize['quality']        = '70%'; // kompres kualitas 70%

					$this->image_lib->initialize($config_resize);
					if (!$this->image_lib->resize()) {
						log_message('error', 'Gagal kompres gambar: ' . $this->image_lib->display_errors());
					}

					$this->image_lib->clear();
				} else {
					log_message('error', 'Upload gagal (update): ' . $this->upload->display_errors());
				}
			}

			$area[] = [
				'sub_area'   => $sub_area[$i],
				'standar'    => $standar[$i],
				'aktual'     => $aktual[$i],
				'suhu_air'   => $suhu_air[$i],
				'keterangan' => $keterangan[$i],
				'tindakan'   => $tindakan[$i],
				'gambar'     => $gambar
			];
		}

		$data = array(
			'username'     => $username,
			'date'         => $date,
			'shift'        => $shift,
			'waktu'        => $waktu,
			'area'         => json_encode($area),
			'catatan'      => $catatan,
			'modified_at'  => date("Y-m-d H:i:s")
		);

		$this->db->update('sanitasi', $data, ['uuid' => $uuid]);

		$new_data = $this->db->get_where('sanitasi', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus sanitasi_logs
			$this->activity_logger->log_activity(
				'update',
				'sanitasi_logs',
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

		$this->db->update('sanitasi', $data, array('uuid' => $uuid));
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

		$this->db->update('sanitasi', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('sanitasi')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('sanitasi', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_sanitasi($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('sanitasi');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_sanitasi_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, nama_produksi, tgl_update_produksi, status_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('sanitasi');

		$data_sanitasi = $query->row();
		return $data_sanitasi;
	}

	public function get_data_by_plant()
	{
		$this->db->order_by('created_at', 'DESC');
		$plant = $this->session->userdata('plant');
		return $this->db->get_where('sanitasi', ['plant' => $plant])->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('sanitasi');
	}

	public function get_by_date($date, $plant = null, $shift = null)
	{
		$this->db->where('DATE(date)', $date);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$query = $this->db->get('sanitasi');

		log_message('debug', 'QUERY SANITASI: ' . $this->db->last_query());

		return $query->result();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('sanitasi')->result();
	}


	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('sanitasi')
			->row();
	}


	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('sanitasi')
			->row();
	}


	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('sanitasi')
			->result();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');
		$username = $this->session->userdata('username');

		// Pastikan data merupakan snapshot Audit milik plant aktif.
		$audit = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('sanitasi')
			->row();

		if (!$audit) {
			return false;
		}

		$sub_area = $this->input->post('sub_area') ?? [];
		$standar = $this->input->post('standar') ?? [];
		$aktual = $this->input->post('aktual') ?? [];
		$suhu_air = $this->input->post('suhu_air') ?? [];
		$keterangan = $this->input->post('keterangan') ?? [];
		$tindakan = $this->input->post('tindakan') ?? [];

		// Pertahankan nama gambar lama jika form tidak mengunggah gambar baru.
		$area_lama = json_decode($audit->area ?? '[]', true);
		if (!is_array($area_lama)) {
			$area_lama = [];
		}

		$area = [];

		$this->load->library('upload');

		for ($i = 0; $i < count($sub_area); $i++) {
			$gambar = $area_lama[$i]['gambar'] ?? null;

			if (
				isset($_FILES['gambar']['name'][$i]) &&
				$_FILES['gambar']['name'][$i] !== ''
			) {
				$_FILES['file']['name']     = $_FILES['gambar']['name'][$i];
				$_FILES['file']['type']     = $_FILES['gambar']['type'][$i];
				$_FILES['file']['tmp_name'] = $_FILES['gambar']['tmp_name'][$i];
				$_FILES['file']['error']    = $_FILES['gambar']['error'][$i];
				$_FILES['file']['size']     = $_FILES['gambar']['size'][$i];

				$config = [
					'upload_path'   => './uploads/sanitasi/',
					'allowed_types' => 'jpg|jpeg|png|webp',
					'file_name'     => 'audit_' . time() . '_' . $i,
					'overwrite'     => false
				];

				$this->upload->initialize($config);

				if ($this->upload->do_upload('file')) {
					$uploadData = $this->upload->data();
					$gambar = $uploadData['file_name'];

					// Kompres gambar baru.
					$this->load->library('image_lib');

					$config_compress = [
						'image_library'  => 'gd2',
						'source_image'   => $uploadData['full_path'],
						'maintain_ratio' => true,
						'quality'        => '70%',
						'width'          => 1600,
						'height'         => 1600
					];

					$this->image_lib->initialize($config_compress);

					if (!$this->image_lib->resize()) {
						log_message(
							'error',
							'Kompres gambar Audit gagal: ' .
								$this->image_lib->display_errors()
						);
					}

					$this->image_lib->clear();
				} else {
					log_message(
						'error',
						'Upload gambar Audit gagal: ' .
							$this->upload->display_errors()
					);
				}
			}

			$area[] = [
				'sub_area'   => $sub_area[$i],
				'standar'    => $standar[$i] ?? '',
				'aktual'     => $aktual[$i] ?? '',
				'suhu_air'   => $suhu_air[$i] ?? '',
				'keterangan' => $keterangan[$i] ?? '',
				'tindakan'   => $tindakan[$i] ?? '',
				'gambar'     => $gambar
			];
		}

		$data = [
			'username' => $username,
			'date'     => $this->input->post('date'),
			'shift'    => $this->input->post('shift'),
			'waktu'    => $this->input->post('waktu'),
			'area'     => json_encode($area),
			'catatan'  => $this->input->post('catatan'),
			'modified_at' => date('Y-m-d H:i:s')
		];

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('sanitasi', $data);
	}

	/*public function get_by_date($date, $plant = null)
	{
		$this->db->where('DATE(date)', $date);
		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}
		$query = $this->db->get('sanitasi');
		log_message('debug', 'QUERY SANITASI: ' . $this->db->last_query());
		return $query->result();
	}*/
}
