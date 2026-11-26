
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_formula_model extends CI_Model
{
	protected $table = 'master_formula';
	protected $table_detail = 'master_formula_detail';

	// Daftar bahan yang diperbolehkan
	//private function daftar_bahan()
	//{
	//	return [
	//		'Tepung Terigu',
	//		'Tepung Tapioka',
	//		'Yeast',
	//		'Bread Improve',
	//		'N55BC',
	//		'NP55BCY / NP55BCO',
	//		'Shortening',
	//		'Chill Water (14 - 16°C)'
	//	];
	//}

	// Ambil daftar material untuk dropdown Master Formula
	public function get_material_dropdown()
	{
		return $this->db
			->select('material, berat')
			->from('material')
			->order_by('material', 'ASC')
			->get()
			->result();
	}

	// Generate UUID
	private function generate_uuid()
	{
		$query = $this->db->query("SELECT UUID() AS uuid");
		return $query->row()->uuid;
	}

	// Ambil semua Master Formula berdasarkan plant
	public function get_all($plant)
	{
		return $this->db
			->select('mf.*, p.nama_produk')
			->from($this->table . ' mf')
			->join('produk p', 'p.uuid = mf.produk_uuid', 'left')
			->where('mf.plant', $plant)
			->order_by('mf.created_at', 'DESC')
			->get()
			->result();
	}

	// Ambil Master Formula berdasarkan UUID
	public function get_by_uuid($uuid, $plant)
	{
		return $this->db
			->select('mf.*, p.nama_produk')
			->from($this->table . ' mf')
			->join('produk p', 'p.uuid = mf.produk_uuid', 'left')
			->where('mf.uuid', $uuid)
			->where('mf.plant', $plant)
			->get()
			->row();
	}

	// Dropdown semua produk berdasarkan plant
	// Produk boleh memiliki lebih dari satu formulasi
	public function get_produk_dropdown($plant, $formula_uuid = null)
	{
		return $this->db
			->select('p.uuid, p.nama_produk')
			->from('produk p')
			->where('p.plant', $plant)
			->order_by('p.nama_produk', 'ASC')
			->get()
			->result();
	}

	// Ambil daftar bahan formulasi
	public function get_detail($formula_uuid)
	{
		return $this->db
			->where('formula_uuid', $formula_uuid)
			->order_by('urutan', 'ASC')
			->get($this->table_detail)
			->result();
	}

	// Validasi produk berdasarkan UUID dan plant
	private function produk_valid($produk_uuid, $plant)
	{
		return $this->db
			->where('uuid', $produk_uuid)
			->where('plant', $plant)
			->count_all_results('produk') > 0;
	}

	// Validasi nama formulasi agar tidak duplikat dalam plant
	public function nama_formulasi_tersedia($nama, $plant, $exclude_uuid = null)
	{
		$this->db
			->from($this->table)
			->where('plant', $plant)
			->where('nama_formulasi', trim($nama));

		if ($exclude_uuid !== null) {
			$this->db->where('uuid !=', $exclude_uuid);
		}

		return $this->db->count_all_results() === 0;
	}

	// Validasi detail bahan
	// Validasi detail bahan berdasarkan tabel material
	private function detail_valid($details)
	{
		if (empty($details) || !is_array($details)) {
			return false;
		}

		$material = $this->get_material_dropdown();

		$daftar_bahan = array_map(function ($row) {
			return $row->material;
		}, $material);

		foreach ($details as $detail) {

			if (
				empty($detail['nama_bahan']) ||
				!in_array($detail['nama_bahan'], $daftar_bahan, true)
			) {
				return false;
			}

			if (
				!isset($detail['standar_berat']) ||
				$detail['standar_berat'] === '' ||
				!is_numeric($detail['standar_berat']) ||
				$detail['standar_berat'] < 0
			) {
				return false;
			}
		}

		return true;
	}

	// Simpan Master Formula baru
	public function insert($data, $details = [])
	{
		$plant = $data['plant'];
		$produk_uuid = $data['produk_uuid'];

		if (!$this->produk_valid($produk_uuid, $plant)) {
			return false;
		}

		//if (!$this->nama_formulasi_tersedia(
		//	$data['nama_formulasi'],
		//	$plant
		//)) {
		//	return false;
		//}

		if (!$this->detail_valid($details)) {
			return false;
		}

		$uuid = $this->generate_uuid();

		$formula = [
			'uuid'           => $uuid,
			'produk_uuid'    => $produk_uuid,
			'nama_formulasi' => trim($data['nama_formulasi']),
			'no_dokumen'     => $data['no_dokumen'] ?? null,
			'revisi'         => $data['revisi'] ?? null,
			'formula'        => $data['formula'] ?? null,
			'plant'          => $plant,
			'username'       => $data['username']
		];

		$this->db->trans_begin();

		$this->db->insert($this->table, $formula);

		if ($this->db->affected_rows() < 1) {
			$this->db->trans_rollback();
			return false;
		}

		foreach ($details as $index => $detail) {

			$this->db->insert($this->table_detail, [
				'uuid'          => $this->generate_uuid(),
				'formula_uuid'  => $uuid,
				'nama_bahan'    => $detail['nama_bahan'],
				'standar_berat' => $detail['standar_berat'],
				'urutan'        => $index + 1
			]);
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return $uuid;
	}

	// Update Master Formula
	public function update($uuid, $plant, $data, $details = [])
	{
		$master = $this->get_by_uuid($uuid, $plant);

		if (!$master) {
			return false;
		}

		if (!$this->produk_valid($data['produk_uuid'], $plant)) {
			return false;
		}

		//if (!$this->nama_formulasi_tersedia(
		//	$data['nama_formulasi'],
		//	$plant,
		//	$uuid
		//)) {
		//	return false;
		//}

		if (!$this->detail_valid($details)) {
			return false;
		}

		$formula = [
			'produk_uuid'    => $data['produk_uuid'],
			'nama_formulasi' => trim($data['nama_formulasi']),
			'no_dokumen'     => $data['no_dokumen'] ?? null,
			'revisi'         => $data['revisi'] ?? null,
			'formula'        => $data['formula'] ?? null,
			'username'       => $data['username']
		];

		$this->db->trans_begin();

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->update($this->table, $formula);

		// Hapus detail lama
		$this->db
			->where('formula_uuid', $uuid)
			->delete($this->table_detail);

		// Simpan detail terbaru
		foreach ($details as $index => $detail) {

			$this->db->insert($this->table_detail, [
				'uuid'          => $this->generate_uuid(),
				'formula_uuid'  => $uuid,
				'nama_bahan'    => $detail['nama_bahan'],
				'standar_berat' => $detail['standar_berat'],
				'urutan'        => $index + 1
			]);
		}

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}

	// Hapus Master Formula dan seluruh detailnya
	public function delete($uuid, $plant)
	{
		$master = $this->get_by_uuid($uuid, $plant);

		if (!$master) {
			return false;
		}

		$this->db->trans_begin();

		$this->db
			->where('formula_uuid', $uuid)
			->delete($this->table_detail);

		$this->db
			->where('uuid', $uuid)
			->where('plant', $plant)
			->delete($this->table);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return false;
		}

		$this->db->trans_commit();

		return true;
	}

	// Ambil daftar formulasi berdasarkan produk dan plant
	public function get_dropdown_by_produk($produk_uuid, $plant)
	{
		return $this->db
			->select('uuid, nama_formulasi, no_dokumen, revisi, formula')
			->where('produk_uuid', $produk_uuid)
			->where('plant', $plant)
			->order_by('nama_formulasi', 'ASC')
			->get($this->table)
			->result();
	}

	// Ambil satu formulasi berdasarkan UUID, produk, dan plant
	public function get_by_uuid_produk($formula_uuid, $produk_uuid, $plant)
	{
		return $this->db
			->where('uuid', $formula_uuid)
			->where('produk_uuid', $produk_uuid)
			->where('plant', $plant)
			->get($this->table)
			->row();
	}

	// Ambil Master Formula berdasarkan produk
	public function get_by_produk($produk_uuid, $plant)
	{
		$formulas = $this->db
			->where('produk_uuid', $produk_uuid)
			->where('plant', $plant)
			->order_by('created_at', 'DESC')
			->get($this->table)
			->result();

		foreach ($formulas as $formula) {
			$formula->detail = $this->get_detail($formula->uuid);
		}

		return $formulas;
	}
}
