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
		$uuid = Uuid::uuid4()->toString();
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');
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
		$status_spv = "0";

		$kondisi_mobil = $this->input->post('kondisi_mobil');
		if (empty($kondisi_mobil)) {
			$kondisi_mobil = ['Tidak Sesuai'];
		}
		$kondisi_mobil_str = implode(", ", $kondisi_mobil);

		$data = array(
			'uuid' => $uuid,
			'username' => $username,
			'plant' => $plant,
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
			'status_spv' => $status_spv
		);

		$this->db->insert('pemeriksaan_chemical', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
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
		$this->db
			->where('is_audit', 0)
			->order_by('created_at', 'DESC');

		return $this->db
			->get('pemeriksaan_chemical')
			->result();
	}

	public function get_by_uuid($uuid)
	{
		return $this->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->get('pemeriksaan_chemical')
			->row();
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

		$query = $this->db->get('pemeriksaan_chemical');

		if ($query->num_rows() > 0) {
			return $query->result();
		}

		return false;
	}

	public function get_last_verif_by_date($tanggal, $plant = null)
	{
		$this->db->select('nama_spv, tgl_update_spv, username, date, shift');
		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		return $this->db
			->get('pemeriksaan_chemical')
			->row();
	}


	public function get_by_tanggal($tanggal)
	{
		return $this->db
			->where('DATE(date)', $tanggal)
			->where('is_audit', 0)
			->order_by('date', 'ASC')
			->get('pemeriksaan_chemical')
			->result();
	}

	// Ambil info verifikasi (nama_spv, username, dsb) hanya satu baris terakhir dari tanggal tsb
	public function get_by_tanggal_verif($tanggal)
	{
		$this->db->select(
			'nama_spv, tgl_update_spv, username, date, shift, created_at'
		);

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('is_audit', 0);
		$this->db->order_by('tgl_update_spv', 'DESC');
		$this->db->limit(1);

		return $this->db
			->get('pemeriksaan_chemical')
			->row();
	}

	public function get_data_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->where('plant', $plant)
			->where('is_audit', 0)
			->order_by('created_at', 'DESC')
			->get('pemeriksaan_chemical')
			->result();
	}

	public function get_latest_chemical()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
			->select('jenis_chemical, kode_produksi, pemasok, jumlah_barang')
			->where('plant', $plant)
			->where('is_audit', 0)
			->order_by('created_at', 'DESC')
			->limit(10)
			->get('pemeriksaan_chemical')
			->result_array();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db
			->where('uuid', $uuid)
			->where('is_audit', 0);

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
}
