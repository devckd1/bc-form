<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Pemeriksaanchemical_model extends CI_Model
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
				'field' => 'jenis_chemical',
				'label' => 'Chemicals',
				'rules' => 'required'
			],
			[
				'field' => 'pemasok',
				'label' => 'Supplier',
				'rules' => 'required'
			],
			['field' => 'jenis_mobil', 'label' => 'Transportation', 'rules' => 'required'],
			['field' => 'no_polisi', 'label' => 'Police Number', 'rules' => 'required'],
			['field' => 'identitas_pengantar', 'label' => 'Identity', 'rules' => 'required'],
			['field' => 'no_po', 'label' => 'PO Number', 'rules' => 'required'],
			[
				'field' => 'kode_produksi',
				'label' => 'Production Code',
				'rules' => 'required'
			],
			[
				'field' => 'expired',
				'label' => 'Expired',
				'rules' => 'required'
			],
			[
				'field' => 'jumlah_barang',
				'label' => 'Amount of Chemicals',
				'rules' => 'required'
			],
			[
				'field' => 'sampel',
				'label' => 'Sample',
				'rules' => 'required'
			],
			[
				'field' => 'jumlah_reject',
				'label' => 'Rejected',
				'rules' => 'required'
			],
			[
				'field' => 'kemasan',
				'label' => 'Packaging'
			],
			[
				'field' => 'warna',
				'label' => 'Color'
			],
			[
				'field' => 'ph',
				'label' => 'Ph'
			],
			[
				'field' => 'halal_berlaku',
				'label' => 'Halal Applicable'
			],
			[
				'field' => 'segel',
				'label' => 'Seal'
			],
			[
				'field' => 'coa',
				'label' => 'COA'
			],
			[
				'field' => 'bukti_coa',
				'label' => 'Evidence',
				'rules' => 'callback_file_check'
			],
			[
				'field' => 'penerimaan',
				'label' => 'received'
			],
			[
				'field' => 'keterangan',
				'label' => 'Notes'
			],
			[
				'field' => 'catatan',
				'label' => 'Notes'
			]
		];
	}


	public function insert($file_name)
	{
		$uuid_qc = Uuid::uuid4()->toString();

		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$kondisi_mobil = $this->input->post('kondisi_mobil');

		if (empty($kondisi_mobil)) {
			$kondisi_mobil = ['Tidak Sesuai'];
		}

		$kondisi_mobil_str = implode(', ', $kondisi_mobil);

		$data = array(
			'uuid'                => $uuid_qc,
			'is_audit'            => 0,
			'source_uuid'         => null,
			'username'            => $username,
			'plant'               => $plant,
			'date'                => $this->input->post('date'),
			'shift'               => $this->input->post('shift'),
			'jenis_chemical'      => $this->input->post('jenis_chemical'),
			'pemasok'             => $this->input->post('pemasok'),
			'jenis_mobil'         => $this->input->post('jenis_mobil'),
			'no_polisi'           => $this->input->post('no_polisi'),
			'identitas_pengantar' => $this->input->post('identitas_pengantar'),
			'no_po'               => $this->input->post('no_po'),
			'kondisi_mobil'       => $kondisi_mobil_str,
			'kode_produksi'       => $this->input->post('kode_produksi'),
			'expired'             => $this->input->post('expired'),
			'jumlah_barang'       => $this->input->post('jumlah_barang'),
			'sampel'              => $this->input->post('sampel'),
			'jumlah_reject'       => $this->input->post('jumlah_reject'),
			'kemasan'             => $this->input->post('kemasan'),
			'warna'                => $this->input->post('warna'),
			'ph'                   => $this->input->post('ph'),
			'halal_berlaku'       => $this->input->post('halal_berlaku'),
			'segel'                => $this->input->post('segel'),
			'coa'                  => $this->input->post('coa'),
			'bukti_coa'           => $file_name,
			'penerimaan'          => $this->input->post('penerimaan'),
			'keterangan'          => $this->input->post('keterangan'),
			'catatan'              => $this->input->post('catatan'),
			'status_spv'          => '0'
		);

		$this->db->trans_begin();

		// Simpan data QC utama
		$this->db->insert('pemeriksaan_chemical', $data);

		// Buat snapshot Audit jika insert QC berhasil
		if ($this->db->trans_status() !== false) {
			$data_audit = $data;

			$data_audit['uuid'] = Uuid::uuid4()->toString();
			$data_audit['is_audit'] = 1;
			$data_audit['source_uuid'] = $uuid_qc;

			$this->db->insert('pemeriksaan_chemical', $data_audit);
		}

		// Batalkan transaksi jika salah satu insert gagal
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}

	public function update($uuid, $file_name)
	{
		$username = $this->session->userdata('username');
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$jenis_chemical = $this->input->post('jenis_chemical');
		$pemasok = $this->input->post('pemasok');
		$jenis_mobil = $this->input->post('jenis_mobil');
		$no_polisi = $this->input->post('no_polisi');
		$identitas_pengantar = $this->input->post('identitas_pengantar');
		$no_po = $this->input->post('no_po');
		$kode_produksi = $this->input->post('kode_produksi');
		$expired = $this->input->post('expired');
		$jumlah_barang = $this->input->post('jumlah_barang');
		$sampel = $this->input->post('sampel');
		$jumlah_reject = $this->input->post('jumlah_reject');
		$kemasan = $this->input->post('kemasan');
		$warna = $this->input->post('warna');
		$ph = $this->input->post('ph');
		$halal_berlaku = $this->input->post('halal_berlaku');
		$segel = $this->input->post('segel');
		$coa = $this->input->post('coa');
		$penerimaan = $this->input->post('penerimaan');
		$keterangan = $this->input->post('keterangan');
		$catatan = $this->input->post('catatan');
		$kondisi_mobil = $this->input->post('kondisi_mobil');

		$old_data = $this->db->get_where('pemeriksaan_chemical', ['uuid' => $uuid])->row_array();

		if (empty($kondisi_mobil)) {
			$kondisi_mobil = ['Tidak Sesuai'];
		}
		$kondisi_mobil_str = implode(", ", $kondisi_mobil);

		$data = array(
			'username' => $username,
			'date' => $date,
			'shift' => $shift,
			'jenis_chemical' => $jenis_chemical,
			'pemasok' => $pemasok,
			'jenis_mobil' => $jenis_mobil,
			'no_polisi' => $no_polisi,
			'identitas_pengantar' => $identitas_pengantar,
			'no_po' => $no_po,
			'kondisi_mobil' => $kondisi_mobil_str,
			'kode_produksi' => $kode_produksi,
			'expired' => $expired,
			'jumlah_barang' => $jumlah_barang,
			'sampel' => $sampel,
			'jumlah_reject' => $jumlah_reject,
			'kemasan' => $kemasan,
			'warna' => $warna,
			'ph' => $ph,
			'halal_berlaku' => $halal_berlaku,
			'segel' => $segel,
			'coa' => $coa,
			'bukti_coa' => $file_name,
			'penerimaan' => $penerimaan,
			'keterangan' => $keterangan,
			'catatan' => $catatan,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('pemeriksaan_chemical', $data, ['uuid' => $uuid]);

		// ambil data baru setelah update
		$new_data = $this->db->get_where('pemeriksaan_chemical', ['uuid' => $uuid])->row_array();

		if ($this->db->affected_rows() > 0) {
			// simpan log ke tabel khusus pemeriksaan_chemical_logs
			$this->activity_logger->log_activity(
				'update',
				'pemeriksaan_chemical_logs', // nama tabel log khusus pemeriksaan_chemical
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

		$this->db->update('pemeriksaan_chemical', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('pemeriksaan_chemical')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('pemeriksaan_chemical', array('uuid' => $uuid))->row();
		return $data;
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
		$query = $this->db->get('pemeriksaan_chemical');

		log_message('debug', 'Query get_by_date: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift');
		$this->db->where('DATE(date)', $tanggal);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('pemeriksaan_chemical');

		return $query->row();
	}


	public function get_by_tanggal($tanggal)
	{
		return $this->db->where('DATE(date)', $tanggal)
			->order_by('date', 'ASC')
			->get('pemeriksaan_chemical')
			->result();
	}

	// Ambil info verifikasi (nama_spv, username, dsb) hanya satu baris terakhir dari tanggal tsb
	public function get_by_tanggal_verif($tanggal)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift, created_at');
		$this->db->where('DATE(date)', $tanggal);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('pemeriksaan_chemical');

		return $query->row();
	}


	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');
		$type_user = $this->session->userdata('tipe_user');

		$this->db->where('is_audit', 0);
		$this->db->order_by('created_at', 'DESC');

		if ($type_user == 9 || $type_user == 1) {
			return $this->db->get('pemeriksaan_chemical')->result();
		}

		return $this->db
			->get_where('pemeriksaan_chemical', [
				'plant' => $plant,
				'is_audit' => 0
			])
			->result();
	}

	public function get_latest_chemical()
	{
		$user_plant = $this->session->userdata('plant');

		$this->db->select('jenis_chemical, kode_produksi, pemasok, jumlah_barang');
		$this->db->from('pemeriksaan_chemical');
		$this->db->where('plant', $user_plant);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit(10);

		$query = $this->db->get();
		return $query->result_array();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('pemeriksaan_chemical');
	}

	public function get_audit_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 1)
			->order_by('created_at', 'DESC')
			->get('pemeriksaan_chemical')
			->result();
	}

	public function get_audit_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('pemeriksaan_chemical')
			->row();
	}

	public function get_audit_by_source_uuid($source_uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('source_uuid', $source_uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->get('pemeriksaan_chemical')
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

		$query = $this->db->get('pemeriksaan_chemical');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}


	public function update_audit($uuid, $file_name)
	{
		$plant = $this->session->userdata('plant');

		$kondisi_mobil = $this->input->post('kondisi_mobil');

		$data = array(
			'date'                => $this->input->post('date', TRUE),
			'shift'               => $this->input->post('shift', TRUE),
			'jenis_chemical'      => $this->input->post('jenis_chemical', TRUE),
			'pemasok'             => $this->input->post('pemasok', TRUE),
			'jenis_mobil'         => $this->input->post('jenis_mobil', TRUE),
			'no_polisi'           => $this->input->post('no_polisi', TRUE),
			'identitas_pengantar' => $this->input->post('identitas_pengantar', TRUE),
			'no_po'               => $this->input->post('no_po', TRUE),
			'kondisi_mobil'       => is_array($kondisi_mobil)
				? implode(',', $kondisi_mobil)
				: '',
			'kode_produksi'       => $this->input->post('kode_produksi', TRUE),
			'expired'             => $this->input->post('expired', TRUE),
			'jumlah_barang'       => $this->input->post('jumlah_barang', TRUE),
			'sampel'              => $this->input->post('sampel', TRUE),
			'jumlah_reject'       => $this->input->post('jumlah_reject', TRUE),
			'kemasan'             => $this->input->post('kemasan', TRUE),
			'warna'               => $this->input->post('warna', TRUE),
			'ph'                  => $this->input->post('ph', TRUE),
			'halal_berlaku'       => $this->input->post('halal_berlaku', TRUE),
			'segel'               => $this->input->post('segel', TRUE),
			'penerimaan'          => $this->input->post('penerimaan', TRUE),
			'coa'                 => $this->input->post('coa', TRUE),
			'bukti_coa'           => $file_name,
			'keterangan'          => $this->input->post('keterangan', TRUE),
			'catatan'             => $this->input->post('catatan', TRUE)
		);

		return $this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->where('is_audit', 1)
			->update('pemeriksaan_chemical', $data);
	}
}
