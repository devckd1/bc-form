
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_formula_model extends CI_Model
{
	protected $table = 'master_formula';
	protected $table_detail = 'master_formula_detail';

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

	// Dropdown produk yang belum memiliki Master Formula
	public function get_produk_dropdown($plant, $formula_uuid = null)
	{
		$this->db->select('p.uuid, p.nama_produk');
		$this->db->from('produk p');

		$this->db->join(
			'master_formula mf',
			'mf.produk_uuid = p.uuid AND mf.plant = p.plant',
			'left'
		);

		$this->db->where('p.plant', $plant);

		if ($formula_uuid !== null) {
			$this->db->group_start()
				->where('mf.id IS NULL', null, false)
				->or_where('mf.uuid', $formula_uuid)
				->group_end();
		} else {
			$this->db->where('mf.id IS NULL', null, false);
		}

		return $this->db
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

	// Simpan Master Formula baru
	public function insert($data, $details = [])
	{
		$plant = $data['plant'];
		$produk_uuid = $data['produk_uuid'];

		if (!$this->produk_valid($produk_uuid, $plant)) {
			return false;
		}

		$uuid = $this->generate_uuid();

		$formula = [
			'uuid'           => $uuid,
			'produk_uuid'    => $produk_uuid,
			'nama_formulasi' => $data['nama_formulasi'],
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
				'uuid'         => $this->generate_uuid(),
				'formula_uuid' => $uuid,
				'nama_bahan'   => $detail['nama_bahan'],
				'komposisi'    => $detail['komposisi'] ?? null,
				'satuan'       => $detail['satuan'] ?? null,
				'urutan'       => $index + 1
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

		$formula = [
			'produk_uuid'    => $data['produk_uuid'],
			'nama_formulasi' => $data['nama_formulasi'],
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

		// Ganti seluruh detail dengan data terbaru
		$this->db
			->where('formula_uuid', $uuid)
			->delete($this->table_detail);

		foreach ($details as $index => $detail) {
			$this->db->insert($this->table_detail, [
				'uuid'         => $this->generate_uuid(),
				'formula_uuid' => $uuid,
				'nama_bahan'   => $detail['nama_bahan'],
				'komposisi'    => $detail['komposisi'] ?? null,
				'satuan'       => $detail['satuan'] ?? null,
				'urutan'       => $index + 1
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


	public function get_by_produk($produk_uuid, $plant)
	{
		$formula = $this->db
			->where('produk_uuid', $produk_uuid)
			->where('plant', $plant)
			->get('master_formula')
			->row();

		if (!$formula) {
			return null;
		}

		$formula->detail = $this->get_detail($formula->uuid);

		return $formula;
	}
}
