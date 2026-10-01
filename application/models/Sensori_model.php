<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Sensori_model extends CI_Model
{

	protected $table = 'sensori_fg';

	public function rules()
	{
		return [
			[
				'field' => 'date',
				'label' => 'Date',
				'rules' => 'required'
			],
			[
				'field' => 'nama_produk',
				'label' => 'Product Name',
				'rules' => 'required'
			],
			[
				'field' => 'tindakan',
				'label' => 'Action'
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
		$nama_produk = $this->input->post('nama_produk');
		$tindakan = $this->input->post('tindakan');
		$catatan = $this->input->post('catatan');

		$kode_produksi = $this->input->post('kode_produksi');
		$best_before = $this->input->post('best_before');
		$warna = $this->input->post('warna');
		$tekstur = $this->input->post('tekstur');
		$rasa = $this->input->post('rasa');
		$aroma = $this->input->post('aroma');
		$kenampakan = $this->input->post('kenampakan');

		$produk = [];
		for ($i = 0; $i < count($kode_produksi); $i++) {
			$produk[] = [
				'kode_produksi' => $kode_produksi[$i],
				'best_before' => isset($best_before[$i]) ? $best_before[$i] : '',
				'warna' => isset($warna[$i]) ? $warna[$i] : '',
				'tekstur' => isset($tekstur[$i]) ? $tekstur[$i] : '',
				'rasa' => isset($rasa[$i]) ? $rasa[$i] : '',
				'aroma' => isset($aroma[$i]) ? $aroma[$i] : '',
				'kenampakan' => isset($kenampakan[$i]) ? $kenampakan[$i] : '',
			];
		}

		$status_spv = "0";
		$status_produksi = "1";

		$data = array(
			'uuid' => $uuid,
			'username' => $username,
			'plant' => $plant,
			'date' => $date,
			'nama_produk' => $nama_produk,
			'tindakan' => $tindakan,
			'catatan' => $catatan,
			'produk' => json_encode($produk),
			'status_spv' => $status_spv,
			'nama_produksi' => $nama_produksi,
			'status_produksi' => $status_produksi
		);

		$this->db->insert('sensori_fg', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$username = $this->session->userdata('username');
		$kode_produksi = $this->input->post('kode_produksi');
		$best_before = $this->input->post('best_before');
		$warna = $this->input->post('warna');
		$tekstur = $this->input->post('tekstur');
		$rasa = $this->input->post('rasa');
		$aroma = $this->input->post('aroma');
		$kenampakan = $this->input->post('kenampakan');
		$old_data = $this->db->get_where('sensori_fg', ['uuid' => $uuid])->row_array();
		$date = $this->input->post('date');
		$nama_produk = $this->input->post('nama_produk');
		$tindakan = $this->input->post('tindakan');
		$catatan = $this->input->post('catatan');

		$produk = [];
		foreach ($kode_produksi as $i => $b) {
			$produk[] = [
				'kode_produksi'   => $b,
				'best_before'  => $best_before[$i],
				'warna'  => $warna[$i],
				'tekstur' => $tekstur[$i],
				'rasa' => $rasa[$i],
				'aroma' => $aroma[$i],
				'kenampakan' => $kenampakan[$i],
			];
		}

		$updateData = [
			'username' => $username,
			'date' => $date,
			'nama_produk' => $nama_produk,
			'tindakan' => $tindakan,
			'catatan' => $catatan,
			'produk'     => json_encode($produk),
			'modified_at' => date('Y-m-d H:i:s'),
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('sensori_fg', $updateData);

		if ($this->db->affected_rows() > 0) {
			$new_data = $this->db->get_where('sensori_fg', ['uuid' => $uuid])->row_array();

			// Catat log activity
			$this->activity_logger->log_activity(
				'update',
				'sensori_fg_logs',
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

		$this->db->update('sensori_fg', $data, array('uuid' => $uuid));
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

		$this->db->update('sensori_fg', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('sensori_fg')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->get('sensori_fg')
			->row();
	}

	public function get_bagian_by_uuid($uuid)
	{
		return $this->db->get_where('sensori_fg', ['uuid' => $uuid])->result_array();
	}

	public function get_details_by_uuid($uuid)
	{
		return $this->db->get_where('sensori_detail', ['uuid_sensori' => $uuid])->result_array();
	}


	public function get_by_uuid_sensori($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$this->db->where('is_audit', 0);
		$query = $this->db->get('sensori_fg');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_sensori_verif($uuid_array)
	{
		$this->db->select('
		nama_spv,
		tgl_update_spv,
		username,
		date,
		nama_produksi,
		status_produksi,
		tgl_update_produksi
	');

		$this->db->where_in('uuid', $uuid_array);
		$this->db->where('is_audit', 0);

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('sensori_fg');

		return $query->row();
	}

	public function get_data_by_plant()
	{
		$this->db->where('is_audit', 0);
		$this->db->order_by('created_at', 'DESC');

		$plant = $this->session->userdata('plant');

		return $this->db
			->get_where('sensori_fg', ['plant' => $plant])
			->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('sensori_fg');
	}

	// Data operasional
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

		$query = $this->db->get('sensori_fg');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}


	// Data Audit
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

		$query = $this->db->get('sensori_fg');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select('
		nama_spv,
		tgl_update_spv,
		username,
		date,
		nama_produksi,
		status_produksi,
		tgl_update_produksi
	');

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get('sensori_fg');

		return $query->row();
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('sensori_fg')
			->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('sensori_fg')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('sensori_fg')
			->row();
	}
}
