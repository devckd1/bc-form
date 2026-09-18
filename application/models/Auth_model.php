<?php

class Auth_model extends CI_Model
{
	private $_table = 'pegawai';
	const SESSION_KEY = 'user_uuid';

	public function rules()
	{
		return [
			[
				'field' => 'username',
				'label' => 'Username',
				'rules' => 'required'
			],
			[
				'field' => 'password',
				'label' => 'Password',
				'rules' => 'required'
			]
		];
	}

	public function login($username, $password)
	{
		$this->db->where('username', $username);
		$query = $this->db->get($this->_table);
		$user = $query->row();

		if (!$user) {
			return FALSE;
		}

		if (!password_verify($password, $user->password)) {
			return FALSE;
		}

		// Plant utama dari pegawai
		$plant_utama = $user->plant;

		$this->session->set_userdata([
			self::SESSION_KEY => $user->uuid,
			'username' => $user->username,
			'nama' => $user->nama,
			'tipe_user' => $user->tipe_user,
			'plant' => $plant_utama,
			'plant_utama' => $plant_utama,
			'foto' => $user->foto ?? 'profil.png'
		]);

		return $this->session->has_userdata(self::SESSION_KEY);
	}

	public function current_user()
	{
		if (!$this->session->has_userdata(self::SESSION_KEY)) {
			return null;
		}

		$user_uuid = $this->session->userdata(self::SESSION_KEY);

		$this->db->select('pegawai.*, plant.plant as nama_plant, departemen.departemen as nama_departemen');
		$this->db->from('pegawai');
		$this->db->join('plant', 'plant.uuid = pegawai.plant', 'left');
		$this->db->join('departemen', 'departemen.uuid = pegawai.departemen', 'left');
		$this->db->where('pegawai.uuid', $user_uuid);

		return $this->db->get()->row();
	}

	/**
	 * Cek apakah user mempunyai akses ke plant tertentu
	 */
	public function user_has_plant($user_uuid, $plant_uuid)
	{
		return $this->db
			->where('user_uuid', $user_uuid)
			->where('plant_uuid', $plant_uuid)
			->get('user_plant')
			->num_rows() > 0;
	}

	/**
	 * Ambil semua plant yang bisa diakses user
	 *
	 * Plant utama selalu dimasukkan.
	 */
	public function get_user_plants($user_uuid)
	{
		$user = $this->db
			->select('plant')
			->where('uuid', $user_uuid)
			->get('pegawai')
			->row();

		if (!$user) {
			return [];
		}

		$this->db->select('plant.*');
		$this->db->from('plant');
		$this->db->where('plant.uuid', $user->plant);

		// Tambahkan plant dari akses user_plant
		$this->db->or_where(
			'plant.uuid IN (
				SELECT plant_uuid
				FROM user_plant
				WHERE user_uuid = ' . $this->db->escape($user_uuid) . '
			)',
			null,
			false
		);

		$this->db->order_by('plant.plant', 'ASC');

		return $this->db->get()->result();
	}

	/**
	 * Ganti plant aktif
	 */
	public function switch_plant($plant_uuid)
	{
		$user_uuid = $this->session->userdata(self::SESSION_KEY);

		if (!$user_uuid) {
			return FALSE;
		}

		// Plant utama
		$user = $this->db
			->where('uuid', $user_uuid)
			->get('pegawai')
			->row();

		if (!$user) {
			return FALSE;
		}

		$allowed = ($user->plant === $plant_uuid);

		// Atau plant tambahan dari user_plant
		if (!$allowed) {
			$allowed = $this->user_has_plant($user_uuid, $plant_uuid);
		}

		if (!$allowed) {
			return FALSE;
		}

		$this->session->set_userdata('plant', $plant_uuid);

		return TRUE;
	}

	public function logout()
	{
		$this->session->unset_userdata([
			self::SESSION_KEY,
			'show_produksi_modal',
			'produksi_data',
			'plant',
			'plant_utama'
		]);

		return !$this->session->has_userdata(self::SESSION_KEY);
	}
}
