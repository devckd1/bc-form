<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Kebersihanruang_model extends CI_Model
{

	protected $table = 'kebersihan_ruang';

	public function rules()
	{
		return [

			[
				'field' => 'date',
				'label' => 'Tanggal',
				'rules' => 'required'
			],

			[
				'field' => 'shift',
				'label' => 'Shift',
				'rules' => 'required'
			],

			[
				'field' => 'lokasi',
				'label' => 'Lokasi',
				'rules' => 'required'
			],

			[
				'field' => 'bagian[]',
				'label' => 'Bagian',
				'rules' => 'required'
			],
		];
	}

	public function insert()
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';

		$uuid     = \Ramsey\Uuid\Uuid::uuid4()->toString();
		$username = $this->session->userdata('username');
		$plant    = $this->session->userdata('plant');

		$date   = $this->input->post('date');
		$shift  = $this->input->post('shift');
		$lokasi = $this->input->post('lokasi');

		$bagian   = $this->input->post('bagian') ?? [];
		$kondisi  = $this->input->post('kondisi') ?? [];
		$problem  = $this->input->post('problem') ?? [];
		$tindakan = $this->input->post('tindakan') ?? [];
		$foto1    = $this->input->post('foto1') ?? '';
		$foto2    = $this->input->post('foto2') ?? '';

		$detail = [];

		foreach ($bagian as $index => $nama_bagian) {

			if (!isset($kondisi[$index])) {
				return false;
			}

			$detail[] = [
				'bagian'   => $nama_bagian,
				'kondisi'  => is_array($kondisi[$index])
					? implode(',', $kondisi[$index])
					: $kondisi[$index],
				'problem'  => $problem[$index]  ?? '',
				'tindakan' => $tindakan[$index] ?? '',
			];
		}

		$data = [
			'uuid'             => $uuid,
			'username'         => $username,
			'plant'            => $plant,
			'date'             => $date,
			'shift'            => $shift,
			'lokasi'           => $lokasi,
			'detail'           => json_encode($detail),
			'foto1'            => $foto1,
			'foto2'            => $foto2,
			'status_produksi'  => 1,
			'status_spv'       => 0,
			'nama_produksi'    => $nama_produksi,
			'created_at'       => date("Y-m-d H:i:s"),
			'modified_at'      => date("Y-m-d H:i:s"),
		];

		$this->db->insert('kebersihan_ruang', $data);

		return ($this->db->affected_rows() > 0);
	}

	public function update($uuid)
	{
		$bagian    = $this->input->post('bagian') ?? [];
		$kondisi   = $this->input->post('kondisi') ?? [];
		$problem   = $this->input->post('problem') ?? [];
		$tindakan  = $this->input->post('tindakan') ?? [];
		$verifikasi = $this->input->post('verifikasi') ?? [];

		$old_data = $this->db
			->get_where('kebersihan_ruang', ['uuid' => $uuid])
			->row_array();

		$detail = [];

		foreach ($bagian as $i => $b) {

			$detail[] = [
				'bagian'   => $b,

				// ✅ PERBAIKAN PENTING DI SINI
				'kondisi'  => isset($kondisi[$i])
					? (is_array($kondisi[$i])
						? implode(',', $kondisi[$i])
						: $kondisi[$i])
					: 'bersih',

				'problem'  => $problem[$i] ?? '',
				'tindakan' => $tindakan[$i] ?? '',
				'verifikasi' => $verifikasi[$i] ?? null
			];
		}

		$updateData = [
			'date'        => $this->input->post('date'),
			'shift'       => $this->input->post('shift'),
			'lokasi'      => $this->input->post('lokasi'),
			'username'    => $this->session->userdata('username'),
			'detail'      => json_encode($detail),
			'modified_at' => date('Y-m-d H:i:s'),
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('kebersihan_ruang', $updateData);

		if ($this->db->affected_rows() > 0) {

			$new_data = $this->db
				->get_where('kebersihan_ruang', ['uuid' => $uuid])
				->row_array();

			$this->activity_logger->log_activity(
				'update',
				'kebersihan_ruang_logs',
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

		$this->db->update('kebersihan_ruang', $data, array('uuid' => $uuid));
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

		$this->db->update('kebersihan_ruang', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('kebersihan_ruang')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('kebersihan_ruang', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_bagian_by_uuid($uuid)
	{
		return $this->db->get_where('kebersihan_ruang', ['uuid' => $uuid])->result_array();
	}

	public function get_details_by_uuid($uuid)
	{
		return $this->db->get_where('kebersihanruang_detail', ['uuid_kebersihanruang' => $uuid])->result_array();
	}


	public function get_by_uuid_kebersihanruang($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('kebersihan_ruang');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_kebersihanruang_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kebersihan_ruang');

		$data_kebersihan_ruang = $query->row();
		return $data_kebersihan_ruang;
	}

	public function get_data_by_plant()
	{
		$this->db->order_by('created_at', 'DESC');

		$plant = $this->session->userdata('plant');

		return $this->db->get_where('kebersihan_ruang', [
			'plant'    => $plant,
			'is_audit' => 0
		])->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('kebersihan_ruang');
	}

	public function get_by_date($tanggal, $plant = null, $shift = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('date', 'ASC');
		$query = $this->db->get('kebersihan_ruang');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null, $shift = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		if (!empty($shift)) {
			$this->db->where('shift', $shift);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('kebersihan_ruang');

		return $query->row();
	}

	private function upload_foto($field)
	{
		if (empty($_FILES[$field]['name'])) {
			return null;
		}

		$config['upload_path']   = './uploads/kebersihan/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['max_size']      = 4096; // 4MB biar aman sebelum compress
		$config['encrypt_name']  = TRUE;

		$CI = &get_instance();
		$CI->load->library('upload', $config);

		if ($CI->upload->do_upload($field)) {

			$file = $CI->upload->data();

			// 🔥 COMPRESS GAMBAR
			$config2['image_library']  = 'gd2';
			$config2['source_image']   = $file['full_path'];
			$config2['maintain_ratio'] = TRUE;
			$config2['quality']        = '60%'; // compress (bisa 40-70)
			$config2['width']          = 1280;  // resize max width
			$config2['new_image']      = $file['full_path'];

			$CI->load->library('image_lib', $config2);
			$CI->image_lib->resize();
			$CI->image_lib->clear();

			return $file['file_name'];
		}

		return null;
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('kebersihan_ruang')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_ruang')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('kebersihan_ruang')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('kebersihan_ruang')
			->result();
	}
}
