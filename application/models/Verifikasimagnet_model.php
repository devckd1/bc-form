<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Verifikasimagnet_model extends CI_Model
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
				'field' => 'nama_produk',
				'label' => 'Product Name',
				'rules' => 'required'
			],
			[
				'field' => 'kode_produksi',
				'label' => 'Production Code',
				'rules' => 'required'
			],
			[
				'field' => 'jumlah_temuan',
				'label' => 'Amount of Findings',
				'rules' => 'required'
			],
			[
				'field' => 'keterangan',
				'label' => 'Description'
			],
			[
				'field' => 'catatan',
				'label' => 'Notes'
			]
		];
	}

	private function upload_bukti_temuan()
	{
		$config['upload_path']   = './uploads/bukti_temuan/';
		$config['allowed_types'] = 'jpg|jpeg|png|pdf';
		$config['max_size']      = 2048; // 2MB
		$config['encrypt_name']  = TRUE;

		$this->load->library('upload', $config);

		if (!is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0755, true);
		}

		if ($this->upload->do_upload('bukti_temuan')) {
			$data = $this->upload->data();
			return $data['file_name'];
		}

		return null;
	}

	public function insert()
	{
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';
		$uuid_qc = Uuid::uuid4()->toString();

		$data = [
			'uuid'              => $uuid_qc,
			'username'          => $this->session->userdata('username'),
			'plant'             => $this->session->userdata('plant'),
			'date'              => $this->input->post('date'),
			'shift'             => $this->input->post('shift'),
			'nama_produk'       => $this->input->post('nama_produk'),
			'kode_produksi'     => $this->input->post('kode_produksi'),
			'jumlah_temuan'     => $this->input->post('jumlah_temuan'),
			'keterangan'        => $this->input->post('keterangan'),
			'catatan'           => $this->input->post('catatan'),
			'status_produksi'   => '1',
			'status_spv'        => '0',
			'nama_produksi'     => $nama_produksi,
			'is_audit'          => 0,
			'source_uuid'       => null
		];

		if (!empty($_FILES['bukti_temuan']['name'])) {
			$file_name = $this->upload_bukti_temuan();

			if (!$file_name) {
				return false;
			}

			$data['bukti_temuan'] = $file_name;
		}

		$data_audit = $data;
		$data_audit['uuid'] = Uuid::uuid4()->toString();
		$data_audit['is_audit'] = 1;
		$data_audit['source_uuid'] = $uuid_qc;

		$this->db->trans_begin();

		$this->db->insert('verifikasi_mt', $data);
		$insert_qc = $this->db->affected_rows() > 0;

		if ($insert_qc) {
			$this->db->insert('verifikasi_mt', $data_audit);
		}

		if (
			!$insert_qc ||
			$this->db->trans_status() === false ||
			$this->db->affected_rows() <= 0
		) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();
		return true;
	}

	public function update($uuid)
	{
		$old_data = $this->db->get_where('verifikasi_mt', ['uuid' => $uuid])->row_array();

		$data = [
			'username' => $this->session->userdata('username'),
			'date' => $this->input->post('date'),
			'shift' => $this->input->post('shift'),
			'nama_produk' => $this->input->post('nama_produk'),
			'kode_produksi' => $this->input->post('kode_produksi'),
			'jumlah_temuan' => $this->input->post('jumlah_temuan'),
			'keterangan' => $this->input->post('keterangan'),
			'catatan' => $this->input->post('catatan'),
			'modified_at' => date('Y-m-d H:i:s')
		];

		// ✅ Kalau user upload file baru, hapus yang lama
		if (!empty($_FILES['bukti_temuan']['name'])) {
			$new_file = $this->upload_bukti_temuan();
			if ($new_file) {
				$data['bukti_temuan'] = $new_file;

				// hapus file lama kalau ada
				if (!empty($old_data['bukti_temuan']) && file_exists('./uploads/bukti_temuan/' . $old_data['bukti_temuan'])) {
					unlink('./uploads/bukti_temuan/' . $old_data['bukti_temuan']);
				}
			}
		} else {
			// ✅ kalau gak upload baru, pakai file lama
			$data['bukti_temuan'] = $old_data['bukti_temuan'];
		}

		$this->db->update('verifikasi_mt', $data, ['uuid' => $uuid]);
		$new_data = $this->db->get_where('verifikasi_mt', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			$this->activity_logger->log_activity(
				'update',
				'verifikasi_mt_logs',
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

		$this->db->update('verifikasi_mt', $data, array('uuid' => $uuid));
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

		$this->db->update('verifikasi_mt', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('verifikasi_mt')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('verifikasi_mt', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_verifikasimagnet($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('verifikasi_mt');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_verifikasimagnet_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('verifikasi_mt');

		$data_verifikasimagnet = $query->row();
		return $data_verifikasimagnet;
	}

	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->where('is_audit', 0);

		if ($type_user != 9 && $type_user != 1) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('verifikasi_mt')->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete(' verifikasi_mt');
	}

	public function get_by_date($tanggal, $plant = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('date', 'ASC');
		$query = $this->db->get('verifikasi_mt');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, nama_produksi, status_produksi, tgl_update_produksi');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('verifikasi_mt');

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('verifikasi_mt')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('verifikasi_mt')
			->row();
	}

	public function get_qc_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 0)
			->get('verifikasi_mt')
			->row();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		// Pastikan record merupakan snapshot Audit pada plant aktif.
		$audit = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('verifikasi_mt')
			->row();

		if (!$audit) {
			return false;
		}

		$data = [
			'date'           => $this->input->post('date', true),
			'shift'          => $this->input->post('shift', true),
			'nama_produk'    => $this->input->post('nama_produk', true),
			'kode_produksi'  => $this->input->post('kode_produksi', true),
			'jumlah_temuan'  => 0,
			'keterangan'     => $this->input->post('keterangan', true),
			'catatan'        => $this->input->post('catatan', true),
		];

		// Bukti lama tetap dipertahankan jika tidak upload file baru.
		if (!empty($_FILES['bukti_temuan']['name'])) {
			$config['upload_path']   = FCPATH . 'uploads/bukti_temuan/';
			$config['allowed_types'] = 'jpg|jpeg|png|pdf';
			$config['max_size']      = 2048;
			$config['encrypt_name']  = true;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('bukti_temuan')) {
				$this->session->set_flashdata(
					'error_msg',
					$this->upload->display_errors('', '')
				);
				return false;
			}

			$upload = $this->upload->data();
			$data['bukti_temuan'] = $upload['file_name'];
		}

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1);

		return $this->db->update('verifikasi_mt', $data);
	}
}
