<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Pemeriksaanpengiriman_model extends CI_Model
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
				'field' => 'nama_supplier',
				'label' => 'Supplier Name',
				'rules' => 'required'
			],
			[
				'field' => 'nama_barang',
				'label' => 'Name of Product',
				'rules' => 'required'
			],
			[
				'field' => 'jenis_mobil',
				'label' => 'Car Type',
				'rules' => 'required'
			],
			[
				'field' => 'no_polisi',
				'label' => 'Police Number',
				'rules' => 'required'
			],
			[
				'field' => 'identitas_pengantar',
				'label' => 'Sender Identity',
				'rules' => 'required'
			],
			[
				'field' => 'segel',
				'label' => 'Seal'
			],
			[
				'field' => 'kebersihan',
				'label' => 'Cleanliness'
			],
			[
				'field' => 'Bocor',
				'label' => 'Leak'
			],
			[
				'field' => 'Hama',
				'label' => 'Pest'
			],
			[
				'field' => 'jam_datang',
				'label' => 'Incoming'
			],
			[
				'field' => 'keterangan',
				'label' => 'Notes'
			]
		];
	}

	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');
		$date = $this->input->post('date');
		$nama_supplier = $this->input->post('nama_supplier');
		$nama_barang = $this->input->post('nama_barang');
		$jenis_mobil = $this->input->post('jenis_mobil');
		$no_polisi = $this->input->post('no_polisi');
		$identitas_pengantar = $this->input->post('identitas_pengantar');
		$segel = $this->input->post('segel');
		$kebersihan = $this->input->post('kebersihan');
		$bocor = $this->input->post('bocor');
		$hama = $this->input->post('hama');
		$jam_datang = $this->input->post('jam_datang');
		$keterangan = $this->input->post('keterangan');
		$status_spv = "0";

		$data = array(
			'uuid' => $uuid,
			'username' => $username,
			'plant' => $plant,
			'date' => $date,
			'nama_supplier' => $nama_supplier,
			'nama_barang' => $nama_barang,
			'jenis_mobil' => $jenis_mobil,
			'no_polisi' => $no_polisi,
			'identitas_pengantar' => $identitas_pengantar,
			'segel' => $segel,
			'kebersihan' => $kebersihan,
			'bocor' => $bocor,
			'hama' => $hama,
			'jam_datang' => $jam_datang,
			'keterangan' => $keterangan,
			'status_spv' => $status_spv
		);

		$this->db->insert('pemeriksaan_pengiriman', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function update($uuid)
	{
		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$nama_supplier = $this->input->post('nama_supplier');
		$nama_barang = $this->input->post('nama_barang');
		$jenis_mobil = $this->input->post('jenis_mobil');
		$no_polisi = $this->input->post('no_polisi');
		$identitas_pengantar = $this->input->post('identitas_pengantar');
		$segel = $this->input->post('segel');
		$kebersihan = $this->input->post('kebersihan');
		$bocor = $this->input->post('bocor');
		$hama = $this->input->post('hama');
		$jam_datang = $this->input->post('jam_datang');
		$keterangan = $this->input->post('keterangan');

		$old_data = $this->db->get_where('pemeriksaan_pengiriman', ['uuid' => $uuid])->row_array();

		$data = array(
			'username' => $username,
			'date' => $date,
			'nama_supplier' => $nama_supplier,
			'nama_barang' => $nama_barang,
			'jenis_mobil' => $jenis_mobil,
			'no_polisi' => $no_polisi,
			'identitas_pengantar' => $identitas_pengantar,
			'segel' => $segel,
			'kebersihan' => $kebersihan,
			'bocor' => $bocor,
			'hama' => $hama,
			'jam_datang' => $jam_datang,
			'keterangan' => $keterangan,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('pemeriksaan_pengiriman', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('pemeriksaan_pengiriman', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus pemeriksaan_pengiriman_logs
			$this->activity_logger->log_activity(
				'update',
				'pemeriksaan_pengiriman_logs', // nama tabel log khusus pemeriksaan_pengiriman
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

		$this->db->update('pemeriksaan_pengiriman', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('pemeriksaan_pengiriman')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('pemeriksaan_pengiriman', array('uuid' => $uuid))->row();
		return $data;
	}

	public function get_by_uuid_pemeriksaanpengiriman($uuid_array)
	{
		if (empty($uuid_array)) {
			return false;
		}
		log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

		$this->db->where_in('uuid', $uuid_array);
		$query = $this->db->get('pemeriksaan_pengiriman');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}

	public function get_by_uuid_pemeriksaanpengiriman_verif($uuid_array)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date');
		$this->db->where_in('uuid', $uuid_array);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('pemeriksaan_pengiriman');

		$data_pemeriksaan_pengiriman = $query->row();
		return $data_pemeriksaan_pengiriman;
	}

	public function get_data_by_plant($limit = null, $start = 0, $tanggal = '')
	{
		$plant = $this->session->userdata('plant');

		$this->db->from('pemeriksaan_pengiriman');
		$this->db->where('plant', $plant);

		if (!empty($tanggal)) {
			$this->db->where('date', $tanggal);
		}

		$this->db->order_by('created_at', 'DESC');

		if ($limit !== null) {
			$this->db->limit($limit, $start);
		}

		return $this->db->get()->result();
	}

	public function count_pemeriksaanpengiriman_by_plant($tanggal = '')
	{
		$plant = $this->session->userdata('plant');

		$this->db->from('pemeriksaan_pengiriman');
		$this->db->where('plant', $plant);

		if (!empty($tanggal)) {
			$this->db->where('date', $tanggal);
		}

		return $this->db->count_all_results();
	}

	public function verifikasi_massal_spv($tanggal)
	{
		$plant = $this->session->userdata('plant');
		$nama_spv = $this->session->userdata('username');

		$this->db->trans_begin();

		$this->db->where('plant', $plant);
		$this->db->where('date', $tanggal);
		$this->db->where_in('status_spv', ['0', '2']);

		$this->db->update('pemeriksaan_pengiriman', [
			'nama_spv'       => $nama_spv,
			'status_spv'     => '1',
			'tgl_update_spv' => date('Y-m-d H:i:s')
		]);

		$jumlah = $this->db->affected_rows();

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return $jumlah;
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('pemeriksaan_pengiriman');
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
		$query = $this->db->get('pemeriksaan_pengiriman');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('pemeriksaan_pengiriman');

		return $query->row();
	}
}
