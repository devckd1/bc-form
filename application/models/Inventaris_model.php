<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Inventaris_model extends CI_Model
{

	protected $table = 'inventaris';

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
			]
		];
	}

	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');

		$nama_alat = $this->input->post('nama_alat');
		$jumlah = $this->input->post('jumlah');
		$kondisi_awal = $this->input->post('kondisi_awal');
		$keterangan = $this->input->post('keterangan');

		$peralatan = [];
		for ($i = 0; $i < count($nama_alat); $i++) {
			$peralatan[] = [
				'nama_alat' => $nama_alat[$i],
				'jumlah' => isset($jumlah[$i]) ? $jumlah[$i] : '',
				'kondisi_awal' => isset($kondisi_awal[$i])
					? (is_array($kondisi_awal[$i]) ? implode(', ', $kondisi_awal[$i]) : $kondisi_awal[$i])
					: '',
				'kondisi_akhir' => '-',
				'keterangan' => isset($keterangan[$i]) ? $keterangan[$i] : '',
			];
		}

		$data = [
			'uuid' => $uuid,
			'username' => $username,
			'plant' => $plant,
			'date' => $date,
			'shift' => $shift,
			'peralatan' => json_encode($peralatan),
			'status_spv' => "0",
			'created_at' => date('Y-m-d H:i:s')
		];

		$this->db->insert('inventaris', $data);
		return ($this->db->affected_rows() > 0);
	}

	public function update($uuid)
	{
		$username       = $this->session->userdata('username');
		$date           = $this->input->post('date');
		$shift          = $this->input->post('shift');
		$nama_alat      = $this->input->post('nama_alat');
		$jumlah         = $this->input->post('jumlah');
		$kondisi_awal   = $this->input->post('kondisi_awal');
		$kondisi_akhir  = $this->input->post('kondisi_akhir');
		$keterangan     = $this->input->post('keterangan');

		$old_data = $this->db->get_where('inventaris', ['uuid' => $uuid])->row_array();
		$data = $this->db->get_where('inventaris', ['uuid' => $uuid])->row();
		if (!$data) {
			return false;
		}

		$peralatan_lama = json_decode($data->peralatan, true);
		$index_lama = [];

		if (!empty($peralatan_lama)) {
			foreach ($peralatan_lama as $item) {
				$nama_key = strtolower(trim($item['nama_alat']));
				$index_lama[$nama_key] = $item;
			}
		}

		$peralatan_baru = [];

		foreach ($nama_alat as $i => $nama) {
			$nama_key           = strtolower(trim($nama));
			$jumlah_val         = isset($jumlah[$i]) ? trim($jumlah[$i]) : '';
			$kondisi_awal_val   = isset($kondisi_awal[$i]) ? trim($kondisi_awal[$i]) : '';
			$kondisi_akhir_val   = isset($kondisi_akhir[$i]) ? trim($kondisi_akhir[$i]) : '';
			$keterangan_val     = isset($keterangan[$i]) ? trim($keterangan[$i]) : '';

			$peralatan_baru[] = [
				'nama_alat'     => $nama,
				'jumlah'        => $jumlah_val,
				'kondisi_awal'  => $kondisi_awal_val,
				'kondisi_akhir' => $kondisi_akhir_val,
				'keterangan'    => $keterangan_val,
			];
		}

		$updateData = [
			'qc_update'      => $username,
			'date'          => $date,
			'shift'         => $shift,
			'peralatan'     => json_encode($peralatan_baru),
			'modified_at'   => date('Y-m-d H:i:s'),
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('inventaris', $updateData);

		if ($this->db->affected_rows() > 0) {
			$new_data = $this->db->get_where('inventaris', ['uuid' => $uuid])->row_array();

			// Catat log activity
			$this->activity_logger->log_activity(
				'update',
				'inventaris_logs',
				$uuid,
				$old_data,
				$new_data
			);

			return true;
		}

		return false;
	}

	public function update_check($uuid)
	{
		$username = $this->session->userdata('username');
		$nama_alat = $this->input->post('nama_alat');
		$jumlah = $this->input->post('jumlah');
		$kondisi_awal = $this->input->post('kondisi_awal');
		$keterangan = $this->input->post('keterangan');
		$kondisi_akhir = $this->input->post('kondisi_akhir');

		$peralatan = [];
		foreach ($nama_alat as $i => $b) {
			$peralatan[] = [
				'nama_alat'   => $b,
				'jumlah'  => $jumlah[$i],
				'kondisi_awal'  => $kondisi_awal[$i],
				'kondisi_akhir' => isset($kondisi_akhir[$i]) ? $kondisi_akhir[$i] : '',
				'keterangan' => $keterangan[$i],
			];
		}

		$updateData = [
			'qc_update' => $username,
			'peralatan' => json_encode($peralatan),
			'modified_at' => date('Y-m-d H:i:s'),
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('inventaris', $updateData);

		return ($this->db->affected_rows() > 0);
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

		$this->db->update('inventaris', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('inventaris')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('inventaris', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_inventaris($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('inventaris');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_inventaris_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, qc_update, shift');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('inventaris');

		$data_inventaris = $query->row();
		return $data_inventaris;
	}

	public function get_data_by_plant()
	{
		$this->db->order_by('created_at', 'DESC');

		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 0)
			->get('inventaris')
			->result();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('inventaris')
			->result();
	}


	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('inventaris')
			->row();
	}


	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('inventaris')
			->row();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('inventaris');
	}
}
