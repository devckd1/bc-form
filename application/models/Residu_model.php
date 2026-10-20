<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Residu_model extends CI_Model
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
				'field' => 'area',
				'label' => 'Area',
				'rules' => 'required'
			],
			[
				'field' => 'titik_sampling',
				'label' => 'Point Sampler',
				'rules' => 'required'
			],
			[
				'field' => 'standar',
				'label' => 'Standart',
				'rules' => 'required'
			],
			[
				'field' => 'hasil_pemeriksaan',
				'label' => 'Result',
				'rules' => 'required'
			],
			[
				'field' => 'keterangan',
				'label' => 'Notes'
			],
			[
				'field' => 'tindakan',
				'label' => 'Corrective Action'
			],
			[
				'field' => 'verifikasi',
				'label' => 'Verification'
			],
			[
				'field' => 'catatan',
				'label' => 'Notes',
			]

		];
	}

	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();
		$audit_uuid = Uuid::uuid4()->toString();

		$data = [
			'uuid' => $uuid,
			'username' => $this->session->userdata('username'),
			'plant' => $this->session->userdata('plant'),
			'date' => $this->input->post('date'),
			'area' => $this->input->post('area'),
			'titik_sampling' => $this->input->post('titik_sampling'),
			'standar' => $this->input->post('standar'),
			'hasil_pemeriksaan' => $this->input->post('hasil_pemeriksaan'),
			'keterangan' => $this->input->post('keterangan'),
			'tindakan' => $this->input->post('tindakan'),
			'verifikasi' => $this->input->post('verifikasi'),
			'catatan' => $this->input->post('catatan'),
			'status_spv' => '0',
			'is_audit' => 0,
			'source_uuid' => null
		];

		$this->db->trans_begin();

		// Simpan data QC
		$this->db->insert('residu', $data);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		// Buat snapshot Audit dari data QC
		$audit_data = $data;
		$audit_data['uuid'] = $audit_uuid;
		$audit_data['is_audit'] = 1;
		$audit_data['source_uuid'] = $uuid;

		$this->db->insert('residu', $audit_data);

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
		$area = $this->input->post('area');
		$titik_sampling = $this->input->post('titik_sampling');
		$standar = $this->input->post('standar');
		$hasil_pemeriksaan = $this->input->post('hasil_pemeriksaan');
		$keterangan = $this->input->post('keterangan');
		$tindakan = $this->input->post('tindakan');
		$verifikasi = $this->input->post('verifikasi');
		$catatan = $this->input->post('catatan');
		$old_data = $this->db->get_where('residu', ['uuid' => $uuid])->row_array();

		$data = array(
			'username' => $username,
			'date' => $date,
			'area' => $area,
			'titik_sampling' => $titik_sampling,
			'standar' => $standar,
			'hasil_pemeriksaan' => $hasil_pemeriksaan,
			'keterangan' => $keterangan,
			'tindakan' => $tindakan,
			'verifikasi' => $verifikasi,
			'catatan' => $catatan,

			'modified_at' => date("Y-m-d H:i:s")
		);


		$this->db->update('residu', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('residu', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus residu_logs
			$this->activity_logger->log_activity(
				'update',
				'residu_logs', // nama tabel log khusus residu
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
				'label' => 'Status',
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

		$this->db->update('residu', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('residu')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('residu', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_residu($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('residu');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_residu_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update, date');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('residu');

		$data_residu = $query->row();
		return $data_residu;
	}

	// public function get_residu_by_month($start, $end)
	// {
	// 	$this->db->where('date >=', $start);
	// 	$this->db->where('date <=', $end);
	// 	$this->db->order_by('date', 'ASC'); 
	// 	return $this->db->get('residu')->result();
	// }

	// public function get_one_verified_by_month($start, $end)
	// {
	// 	$this->db->select('r.area, r.titik_sampling, r.username, r.nama_spv, r.tgl_update_spv');
	// 	$this->db->from('residu r');
	// 	$this->db->where('r.date >=', $start);
	// 	$this->db->where('r.date <=', $end);
	// 	$this->db->where('r.status_spv', 1);
	// 	$this->db->limit(1);
	// 	return $this->db->get()->row();
	// }

	public function get_by_month($start_date, $end_date, $plant = null)
	{
		$this->db->select('date, standar, hasil_pemeriksaan, keterangan, tindakan, verifikasi, username, catatan');

		$this->db->where('date >=', $start_date);
		$this->db->where('date <=', $end_date);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('date', 'ASC');
		$query = $this->db->get('residu');

		log_message('debug', 'Query get_by_month: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_month($start_date, $end_date, $plant = null)
	{
		$this->db->select('area, titik_sampling, nama_spv, tgl_update_spv, username, date,
			standar, hasil_pemeriksaan, keterangan, tindakan, verifikasi, catatan');
		$this->db->where('date >=', $start_date);
		$this->db->where('date <=', $end_date);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('residu');

		return $query->row();
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

		return $this->db->get('residu')->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('residu');
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('plant', $plant);
		$this->db->where('is_audit', 1);
		$this->db->order_by('created_at', 'DESC');

		return $this->db->get('residu')->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('residu')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('residu')
			->row();
	}

	public function get_audit_by_date($tanggal, $plant)
	{
		return $this->db
			->where('date', $tanggal)
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('residu')
			->result();
	}

	public function get_audit_by_month($start_date, $end_date, $plant)
	{
		return $this->db
			->where('is_audit', 1)
			->where('plant', $plant)
			->where('date >=', $start_date)
			->where('date <=', $end_date)
			->order_by('date', 'ASC')
			->get('residu')
			->result();
	}

	public function update_audit($uuid)
	{
		$plant = $this->session->userdata('plant');

		// Pastikan snapshot Audit milik plant aktif
		$record = $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('residu')
			->row();

		if (!$record) {
			return false;
		}

		$data = [
			'username' => $this->session->userdata('username'),
			'date' => $this->input->post('date'),
			'area' => $this->input->post('area'),
			'titik_sampling' => $this->input->post('titik_sampling'),
			'standar' => $this->input->post('standar'),
			'hasil_pemeriksaan' => $this->input->post('hasil_pemeriksaan'),
			'keterangan' => $this->input->post('keterangan'),
			'tindakan' => $this->input->post('tindakan'),
			'verifikasi' => $this->input->post('verifikasi'),
			'catatan' => $this->input->post('catatan'),
			'modified_at' => date('Y-m-d H:i:s')
		];

		// Update hanya snapshot Audit
		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('residu', $data);
	}
}
