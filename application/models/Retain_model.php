<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Retain_model extends CI_Model
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
				'field' => 'plant',
				'label' => 'Plant',
				'rules' => 'required'
			],
			[
				'field' => 'sample_type',
				'label' => 'Type of Sample',
				'rules' => 'required'
			],
			[
				'field' => 'sample_storage',
				'label' => 'Storage of Sample',
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
		$uuid_qc = Uuid::uuid4()->toString();

		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$plant = $this->input->post('plant');
		$sample_type = $this->input->post('sample_type');
		$sample_storage = $this->input->post('sample_storage');
		$description = $this->input->post('description');
		$catatan = $this->input->post('catatan');
		$status_spv = '0';

		$data = array(
			'uuid'           => $uuid_qc,
			'is_audit'       => 0,
			'source_uuid'    => null,
			'username'       => $username,
			'date'           => $date,
			'plant'          => $plant,
			'sample_type'    => $sample_type,
			'sample_storage' => $sample_storage,
			'description'    => json_encode($description),
			'catatan'        => $catatan,
			'status_spv'     => $status_spv
		);

		$this->db->trans_begin();

		// Simpan record QC
		$this->db->insert('retain', $data);

		// Buat record Audit jika insert QC berhasil
		if ($this->db->trans_status() !== false) {
			$data_audit = $data;

			$data_audit['uuid'] = Uuid::uuid4()->toString();
			$data_audit['is_audit'] = 1;
			$data_audit['source_uuid'] = $uuid_qc;

			$this->db->insert('retain', $data_audit);
		}

		// Pastikan kedua insert berhasil
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
		$plant = $this->input->post('plant');
		$sample_type = $this->input->post('sample_type');
		$sample_storage = $this->input->post('sample_storage');
		$description = $this->input->post('description');
		$catatan = $this->input->post('catatan');
		$old_data = $this->db->get_where('retain', ['uuid' => $uuid])->row_array();
		$data = array(
			'username' => $username,
			'date' => $date,
			'plant' => $plant,
			'sample_type' => $sample_type,
			'sample_storage' => $sample_storage,
			'description' => json_encode($description),
			'catatan' => $catatan,
			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('retain', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('retain', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus retain_logs
			$this->activity_logger->log_activity(
				'update',
				'retain_logs', // nama tabel log khusus retain
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

		$this->db->update('retain', $data, array('uuid' => $uuid));
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

		$this->db->update('retain', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('a.created_at', 'DESC');
		$this->db->select('a.*, b.plant');
		$this->db->from('retain a');
		$this->db->join('plant b', 'a.plant=b.uuid', 'left');

		$data = $this->db->get()->result();

		return $data;
	}

	public function get_retain_with_plant($uuid)
	{
		$this->db->select('retain.*, plant.plant');
		$this->db->from('retain');
		$this->db->join('plant', 'plant.uuid = retain.plant', 'left');
		$this->db->where('retain.uuid', $uuid);
		return $this->db->get()->row();
	}

	public function get_plant($uuid)
	{
		$this->db->where('plant', $uuid);
		$query = $this->db->get('retain');
		return $query->result_array();
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('retain', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_retain($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('retain');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_retain_verif($uuid_array)
	{
		$this->db->select('retain.description, retain.nama_spv, retain.tgl_update_spv, retain.username, retain.date, plant.plant AS plant, retain.sample_type, retain.sample_storage');
		$this->db->from('retain');
		$this->db->join('plant', 'plant.uuid = retain.plant', 'left');
		$this->db->where_in('retain.uuid', $uuid_array);
		$this->db->order_by('retain.tgl_update_spv', 'DESC');
		$query = $this->db->get();

		return $query->result();
	}


	public function get_data_by_plant()
	{
		$this->db->select('a.*, b.plant AS nama_plant');
		$this->db->from('retain a');
		$this->db->join('plant b', 'a.plant = b.uuid', 'left');

		$this->db->where('a.is_audit', 0);
		$this->db->order_by('a.created_at', 'DESC');

		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		if ($type_user == 9 || $type_user == 1) {
			return $this->db->get()->result();
		}

		$this->db->where('a.plant', $plant);

		return $this->db->get()->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('retain');
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->select('a.*, b.plant AS nama_plant');
		$this->db->from('retain a');
		$this->db->join('plant b', 'a.plant = b.uuid', 'left');

		$this->db->where('a.plant', $plant);
		$this->db->where('a.is_audit', 1);
		$this->db->order_by('a.created_at', 'DESC');

		return $this->db->get()->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('retain')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('retain')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('retain')
			->result();
	}

	public function get_qc_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		$this->db->select('a.*, b.plant AS nama_plant');
		$this->db->from('retain a');
		$this->db->join('plant b', 'a.plant = b.uuid', 'left');

		$this->db->where('a.uuid', $uuid);
		$this->db->where('a.plant', $plant);
		$this->db->where('a.is_audit', 0);

		return $this->db->get()->row();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		$description = $this->input->post('description');

		if (!is_array($description)) {
			$description = [];
		}

		$data = [
			'date'           => $this->input->post('date', TRUE),
			'sample_type'    => $this->input->post('sample_type', TRUE),
			'sample_storage' => $this->input->post('sample_storage', TRUE),
			'description'    => json_encode(
				array_values($description),
				JSON_UNESCAPED_UNICODE
			),
			'catatan'        => $this->input->post('catatan', TRUE),
		];

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('retain', $data);
	}
}
