<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Sanitasiwarehouse_model extends CI_Model
{

	protected $table = 'sanitasi_wh';

	public function rules()
	{
		return [
			[
				'field' => 'date',
				'label' => 'Date',
				'rules' => 'required'
			],
			[
				'field' => 'area',
				'label' => 'Location',
				'rules' => 'required'
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
		$area = $this->input->post('area');

		$bagian = $this->input->post('bagian');
		$kondisi = $this->input->post('kondisi');
		$problem = $this->input->post('problem');
		$tindakan = $this->input->post('tindakan');

		$detail = [];
		for ($i = 0; $i < count($bagian); $i++) {
			$detail[] = [
				'bagian' => $bagian[$i],
				'kondisi' => isset($kondisi[$i]) ? $kondisi[$i] : '',
				'problem' => isset($problem[$i]) ? $problem[$i] : '',
				'tindakan' => isset($tindakan[$i]) ? $tindakan[$i] : '',
			];
		}

		$status_spv = "0";
		$status_wh = "1";

		$data = array(
			'uuid' => $uuid,
			'username' => $username,
			'plant' => $plant,
			'date' => $date,
			'area' => $area,
			'detail' => json_encode($detail),
			'nama_wh' => $nama_produksi,
			'status_wh' => $status_wh,
			'status_spv' => $status_spv
		);

		$this->db->insert('sanitasi_wh', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}


	public function update($uuid)
	{
		$bagian    = $this->input->post('bagian');
		$kondisi   = $this->input->post('kondisi');
		$problem   = $this->input->post('problem');
		$tindakan  = $this->input->post('tindakan');
		$old_data = $this->db->get_where('sanitasi_wh', ['uuid' => $uuid])->row_array();
		$detail = [];
		foreach ($bagian as $i => $b) {
			$detail[] = [
				'bagian'   => $b,
				'kondisi'  => $kondisi[$i],
				'problem'  => $problem[$i],
				'tindakan' => $tindakan[$i],
			];
		}

		$updateData = [
			'date'       => $this->input->post('date'),
			'username'       => $this->session->userdata('username'),
			'detail'     => json_encode($detail),
			'modified_at' => date('Y-m-d H:i:s'),
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('sanitasi_wh', $updateData);

		if ($this->db->affected_rows() > 0) {
			$new_data = $this->db->get_where('sanitasi_wh', ['uuid' => $uuid])->row_array();

			// Catat log activity
			$this->activity_logger->log_activity(
				'update',
				'sanitasi_wh_logs',
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

		$this->db->update('sanitasi_wh', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function rules_diketahui()
	{
		return [
			[
				'field' => 'status_wh',
				'label' => 'Status',
				'rules' => 'required'
			],
			[
				'field' => 'catatan_wh',
				'label' => 'Notes'
			]

		];
	}

	public function diketahui_update($uuid)
	{

		$nama_wh = $this->session->userdata('username');
		$status_wh = $this->input->post('status_wh');
		$catatan_wh = $this->input->post('catatan_wh');

		$data = array(
			'nama_wh' => $nama_wh,
			'status_wh' => $status_wh,
			'catatan_wh' => $catatan_wh,
			'tgl_update_wh' => date("Y-m-d H:i:s")
		);

		$this->db->update('sanitasi_wh', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->where('is_audit', 0);
		$this->db->order_by('created_at', 'DESC');

		return $this->db
			->get('sanitasi_wh')
			->result();
	}

	public function get_by_uuid($uuid)
	{
		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->get('sanitasi_wh')
			->row();
	}

	public function get_bagian_by_uuid($uuid)
	{
		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->get('sanitasi_wh')
			->result_array();
	}

	public function get_details_by_uuid($uuid)
	{
		return $this->db->get_where('sanitasiwarehouse_detail', ['uuid_sanitasiwarehouse' => $uuid])->result_array();
	}


	public function get_by_uuid_sanitasiwarehouse($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}

		$this->db->where_in('uuid', $uuid_array);
		$this->db->where('is_audit', 0);

		$query = $this->db->get('sanitasi_wh');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_by_uuid_sanitasiwarehouse_verif($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}

		$this->db->select(
			'nama_spv, tgl_update_spv, username, date, 
         nama_wh, status_wh, tgl_update_wh'
		);

		$this->db->where_in('uuid', $uuid_array);
		$this->db->where('is_audit', 0);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('sanitasi_wh');

		return $query->row();
	}

	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 0)
			->order_by('created_at', 'DESC')
			->get('sanitasi_wh')
			->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('sanitasi_wh');
	}

	public function get_by_date($tanggal, $plant = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('date', 'ASC');

		$query = $this->db->get('sanitasi_wh');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select(
			'nama_spv, tgl_update_spv, username, date, 
         nama_wh, status_wh, tgl_update_wh, created_at'
		);

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('sanitasi_wh');

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('sanitasi_wh')
			->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('sanitasi_wh')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('sanitasi_wh')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant = null)
	{
		if (empty($tanggal)) {
			return false;
		}

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 1);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('date', 'ASC');

		$query = $this->db->get('sanitasi_wh');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}
}
