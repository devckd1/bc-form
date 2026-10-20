<?php 
date_default_timezone_set('Asia/Jakarta');
use Ramsey\Uuid\Uuid;

class List_timbangan_model extends CI_Model {
	
	public function rules()
	{
		return[
			[
				'field' => 'kode_timbangan',
				'label' => 'Kode Timbangan',
				'rules' => 'required'
			],
			[
				'field' => 'kapasitas',
				'label' => 'Capacity',
				'rules' => 'required'
			],
			[
				'field' => 'model',
				'label' => 'Model',
				'rules' => 'required'
			],
			[
				'field' => 'lokasi',
				'label' => 'Location',
				'rules' => 'required'
			],
			 [
			 	'field' => 'standar',
			 	'label' => 'Standart',
			 	'rules' => 'required'
			 ]
		];
	}

	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();

		$kode_timbangan = $this->input->post('kode_timbangan');
		$kapasitas = $this->input->post('kapasitas');
		$model = $this->input->post('model');
		$lokasi = $this->input->post('lokasi');
		$standar = $this->input->post('standar');
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant'); 

		$data = array(
			'uuid' => $uuid,
			'username' => $username,
			'kode_timbangan' => $kode_timbangan,
			'kapasitas' => $kapasitas,
			'model' => $model,
			'lokasi' => $lokasi,
			'standar' => $standar,
			'plant' => $plant
		);

		$this->db->insert('list_timbangan', $data);
		return($this->db->affected_rows() > 0) ? true :false;

	}

	public function update($uuid)
	{

		$kode_timbangan = $this->input->post('kode_timbangan');
		$kapasitas = $this->input->post('kapasitas');
		$model = $this->input->post('model');
		$lokasi = $this->input->post('lokasi');
		$standar = $this->input->post('standar');

		$data = array(
			'kode_timbangan' => $kode_timbangan,
			'kapasitas' => $kapasitas,
			'model' => $model,
			'lokasi' => $lokasi,
			'standar' => $standar,

			'modified_at' => date("Y-m-d H:i:s")
		);

		$this->db->update('list_timbangan', $data, array('uuid' => $uuid));
		return($this->db->affected_rows() > 0) ? true :false;

	}

	public function get_all()
{
    $plant = $this->session->userdata('plant');
    $type_user = (int) $this->session->userdata('tipe_user');

    if ($type_user !== 9) {
        $this->db->where('plant', $plant);
    }

    $this->db->order_by('created_at', 'DESC');

    return $this->db->get('list_timbangan')->result();
}

	public function get_by_uuid($uuid)
	{
		$plant = $this->session->userdata('plant');

		return $this->db
		->where('uuid', $uuid)
		->where('plant', $plant)
		->get('list_timbangan')
		->row();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('list_timbangan');
	}

	public function get_by_plant()
	{
		$plant = $this->session->userdata('plant');

		return $this->db
		->where('plant', $plant)
		->order_by('kode_timbangan', 'ASC')
		->get('list_timbangan')
		->result();
	}
}
