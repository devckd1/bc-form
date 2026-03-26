<?php
date_default_timezone_set('Asia/Jakarta');

use Ramsey\Uuid\Uuid;


class Proses_model extends CI_Model
{
	protected $table = 'mixing';

	public function rules()
	{
		return [
			[
				'field' => 'date',
				'label' => 'Tanggal',
				'rules' => 'required'
			],
			[
				'field' => 'shift',
				'label' => 'Shift',
				'rules' => 'required'
			],
			[
				'field' => 'nama_produk',
				'label' => 'Nama Produk',
				'rules' => 'required'
			],
			[
				'field' => 'catatan',
				'label' => 'Notes'
			]
		];
	}

	public function insert()
	{
		$uuid = Uuid::uuid4()->toString();
		$produksi_data = $this->session->userdata('produksi_data');
		$nama_produksi = $produksi_data['nama_produksi'] ?? '';
		$username = $this->session->userdata('username');
		$plant = $this->session->userdata('plant');

		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$nama_produk = $this->input->post('nama_produk');
		$catatan = $this->input->post('catatan');
		$status_produksi = "1";
		$status_spv = "0";

		$proses_input = $this->input->post('proses_produksi');
		$cleaned = [];

		if (is_array($proses_input)) {
			foreach ($proses_input as $kategori => $params) {
				foreach ($params as $param => $cols) {
					for ($i = 0; $i < 11; $i++) {
						// Standar berat di index 0
						if ($kategori == 'kondisi_rm' && $i == 0) {
							$cleaned[$kategori][$param][$i] = isset($cols[0]) ? $cols[0] : '';
						} else {
							$cleaned[$kategori][$param][$i] = isset($cols[$i]) ? $cols[$i] : '';
						}

						// Default otomatis
						if ($kategori == 'mixing' && $param == 'waktu_mixing_1' && $i == 0) $cleaned[$kategori][$param][$i] = '3';
						if ($kategori == 'mixing' && $param == 'waktu_mixing_2' && $i == 0) $cleaned[$kategori][$param][$i] = '8';
						if ($kategori == 'electric_baking' && $param == 'baking_time_high' && $i == 0) $cleaned[$kategori][$param][$i] = '5';
						if ($kategori == 'electric_baking' && $param == 'baking_time_low' && $i == 0) $cleaned[$kategori][$param][$i] = '7';
					}
				}
			}
		}

		$json_proses = json_encode($cleaned);

		$data = [
			'uuid' => $uuid,
			'username' => $username,
			'plant' => $plant,
			'date' => $date,
			'shift' => $shift,
			'jenis_produk' => $nama_produk,
			'nama_produk' => $nama_produk,
			'catatan' => $catatan,
			'status_spv' => $status_spv,
			'status_produksi' => $status_produksi,
			'nama_produksi' => $nama_produksi,
			'proses_produksi' => $json_proses
		];

		return $this->db->insert($this->table, $data);
	}

	public function update($uuid)
	{
		$date = $this->input->post('date');
		$shift = $this->input->post('shift');
		$nama_produk = $this->input->post('nama_produk');
		$catatan = $this->input->post('catatan');

		// Get existing record for logging
		$old_data = $this->db->get_where($this->table, ['uuid' => $uuid])->row_array();

		$proses_input = $this->input->post('proses_produksi');
		$cleaned = []; // always start fresh

		if (is_array($proses_input)) {
			foreach ($proses_input as $kategori => $params) {
				foreach ($params as $param => $cols) {
					for ($i = 0; $i < 11; $i++) {
						// Always use submitted value, even if empty (user intentionally cleared it)
						if (isset($cols[$i])) {
							$cleaned[$kategori][$param][$i] = trim($cols[$i]);
						} else {
							// Field not submitted at all (e.g. unchecked checkbox) = clear it
							$cleaned[$kategori][$param][$i] = '';
						}

						// Defaults
						if ($kategori == 'mixing' && $param == 'waktu_mixing_1' && $i == 0) $cleaned[$kategori][$param][$i] = '3';
						if ($kategori == 'mixing' && $param == 'waktu_mixing_2' && $i == 0) $cleaned[$kategori][$param][$i] = '8';
						if ($kategori == 'electric_baking' && $param == 'baking_time_high' && $i == 0) $cleaned[$kategori][$param][$i] = '5';
						if ($kategori == 'electric_baking' && $param == 'baking_time_low' && $i == 0) $cleaned[$kategori][$param][$i] = '7';
					}
				}
			}
		}

		$json_proses = json_encode($cleaned);

		$data = [
			'date'            => $date,
			'shift'           => $shift,
			'nama_produk'     => $nama_produk,
			'jenis_produk'    => $nama_produk,
			'catatan'         => $catatan,
			'proses_produksi' => $json_proses,
			'modified_at'     => date('Y-m-d H:i:s')
		];

		$this->db->where('uuid', $uuid);
		$this->db->update($this->table, $data);

		if ($this->db->affected_rows() > 0) {
			$new_data = $this->db->get_where($this->table, ['uuid' => $uuid])->row_array();

			if (property_exists($this, 'activity_logger')) {
				$this->activity_logger->log_activity(
					'update',
					$this->table . '_logs',
					$uuid,
					$old_data,
					$new_data
				);
			}

			return true;
		}

		return false;
	}

	public function rules_packing()
	{
		return [
			[
				'field' => 'date_stall',
				'label' => 'Tanggal',
				'rules' => 'trim'
			],
			[
				'field' => 'shift_pack',
				'label' => 'Shift',
				'rules' => 'trim'
			],
			[
				'field' => 'catatan_packing',
				'label' => 'Catatan',
				'rules' => 'trim'
			],
		];
	}

	public function update_packing($uuid)
	{
		$date_stall      = $this->input->post('date_stall');
		$shift_pack      = $this->input->post('shift_pack');
		$proses_packing  = $this->input->post('packing');
		$catatan_packing = $this->input->post('catatan_packing');

		// Ambil data lama
		$old_data = $this->db->get_where('mixing', ['uuid' => $uuid])->row_array();
		if (!$old_data) {
			return false;
		}

		if (!is_array($proses_packing)) {
			$proses_packing = [];
		}

		// === Ambil data dari proses_produksi index ke-1 ===
		$proses_produksi = json_decode($old_data['proses_produksi'], true);
		$nama_produk = '';
		$kode_produksi = '';

		if (isset($proses_produksi['dough_mixing'])) {
			$dm = $proses_produksi['dough_mixing'];
			$nama_produk   = isset($dm['nama_produk'][1]) ? $dm['nama_produk'][1] : '';
			$kode_produksi = isset($dm['kode_produksi'][1]) ? $dm['kode_produksi'][1] : '';
		}

		// === Tambahkan nama_produk & kode_produksi ke proses_packing ===
		$proses_packing['nama_produk']   = $nama_produk;
		$proses_packing['kode_produksi'] = $kode_produksi;

		// === Upload Gambar ===
		if (!empty($_FILES['packing']['name']) && is_array($_FILES['packing']['name'])) {
			foreach ($_FILES['packing']['name'] as $col => $kategori_arr) {
				if (
					isset($kategori_arr['pemeriksaan_finished_product']['bukti_labelisasi'][0]) &&
					!empty($kategori_arr['pemeriksaan_finished_product']['bukti_labelisasi'][0])
				) {

					$file_name = $_FILES['packing']['name'][$col]['pemeriksaan_finished_product']['bukti_labelisasi'][0];
					$tmp_name  = $_FILES['packing']['tmp_name'][$col]['pemeriksaan_finished_product']['bukti_labelisasi'][0];

					$upload_path = './uploads/bukti_labelisasi/';
					if (!is_dir($upload_path)) {
						mkdir($upload_path, 0777, true);
					}

					$new_name = uniqid('bukti_') . '_' . preg_replace('/\s+/', '_', $file_name);
					$target_file = $upload_path . $new_name;

					// Kompres dan resize
					$info = @getimagesize($tmp_name);
					$mime = $info ? $info['mime'] : null;

					switch ($mime) {
						case 'image/jpeg':
							$image = imagecreatefromjpeg($tmp_name);
							break;
						case 'image/png':
							$image = imagecreatefrompng($tmp_name);
							break;
						case 'image/webp':
							$image = imagecreatefromwebp($tmp_name);
							break;
						default:
							$image = null;
							break;
					}

					if ($image) {
						$width = imagesx($image);
						$height = imagesy($image);
						$new_width = min($width, 800);
						$new_height = ($height / $width) * $new_width;

						$tmp = imagecreatetruecolor($new_width, $new_height);
						imagecopyresampled($tmp, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
						imagejpeg($tmp, $target_file, 70);

						imagedestroy($image);
						imagedestroy($tmp);

						$proses_packing[$col]['pemeriksaan_finished_product']['bukti_labelisasi'][0] = $new_name;
					}
				} else {
					// Tidak upload baru, ambil file lama
					if (!empty($old_data['proses_packing'])) {
						$old_json = json_decode($old_data['proses_packing'], true);
						if (isset($old_json[$col]['pemeriksaan_finished_product']['bukti_labelisasi'][0])) {
							$proses_packing[$col]['pemeriksaan_finished_product']['bukti_labelisasi'][0] =
								$old_json[$col]['pemeriksaan_finished_product']['bukti_labelisasi'][0];
						}
					}
				}
			}
		}

		// === Encode JSON ===
		$packing_json = json_encode($proses_packing, JSON_UNESCAPED_UNICODE);

		// === Update ke database ===
		$data = [
			'date_stall'      => $date_stall,
			'shift_pack'      => $shift_pack,
			'catatan_packing' => $catatan_packing,
			'proses_packing'  => $packing_json,
			'updated_at'      => date('Y-m-d H:i:s')
		];

		$this->db->where('uuid', $uuid);
		$this->db->update('mixing', $data);

		// === Logging jika ada perubahan ===
		if ($this->db->affected_rows() > 0) {
			$new_data = $this->db->get_where('mixing', ['uuid' => $uuid])->row_array();
			if (isset($this->activity_logger)) {
				$this->activity_logger->log_activity('update', 'mixing_logs', $uuid, $old_data, $new_data);
			}
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
			'tgl_update' => date("Y-m-d H:i:s")
		);

		$this->db->update('mixing', $data, array('uuid' => $uuid));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	public function get_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('mixing')->result();
		return $data;
	}


	public function get_proses()
	{
		$this->db->order_by('created_at', 'DESC');
		$data = $this->db->get('mixing')->result();
		return $data;
	}

	public function get_by_uuid($uuid)
	{
		$data = $this->db->get_where('mixing', array('uuid' => $uuid))->row();
		return $data;
	}

	// public function get_by_uuid_proses($uuid_array)
	// {
	// 	if (empty($uuid_array)) {
	// 		return false;
	// 	}
	// 	log_message('debug', 'Array UUID yang diterima: ' . print_r($uuid_array, true));

	// 	$this->db->where_in('uuid', $uuid_array);
	// 	$query = $this->db->get('mixing');

	// 	log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

	// 	if ($query->num_rows() > 0) {
	// 		return $query->result(); 
	// 	}	
	// 	return false;  
	// }

	// public function get_by_uuid_proses_verif($uuid_array)
	// {
	// 	$this->db->select('nama_spv, tgl_update, date, shift, date_stall, nama_produk, shift_pack, catatan, status_spv, username, premix, nama_produksi, tgl_update_prod, status_produksi');
	// 	$this->db->where_in('uuid', $uuid_array);
	// 	$this->db->order_by('tgl_update', 'DESC');  
	// 	$this->db->limit(1);  
	// 	$query = $this->db->get('mixing');

	// 	$data_produksi = $query->row();  
	// 	return $data_produksi; 
	// }

	public function get_by_tanggal_shift($tanggal, $shift)
	{
		if (empty($tanggal) || empty($shift)) {
			return false;
		}

		log_message('debug', 'Tanggal dan shift yang diterima: ' . $tanggal . ' - Shift ' . $shift);

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('shift', $shift);
		$query = $this->db->get('mixing');

		log_message('debug', 'Query yang dijalankan: ' . $this->db->last_query());

		if ($query->num_rows() > 0) {
			return $query->result();
		}
		return false;
	}


	public function get_by_tanggal_shift_verif($tanggal, $shift)
	{
		if (empty($tanggal) || empty($shift)) {
			return false;
		}

		$this->db->select('nama_spv, tgl_update, date, shift, date_stall, nama_produk, shift_pack, catatan, status_spv, username, premix, nama_produksi, tgl_update_prod, status_produksi, proses_produksi, proses_packing');
		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('shift', $shift);
		$this->db->order_by('tgl_update', 'DESC');
		$this->db->limit(1);
		$query = $this->db->get('mixing');

		log_message('debug', 'Query verifikasi dijalankan: ' . $this->db->last_query());

		return $query->row();
	}

	public function get_by_tanggal_shift_verif_excel($tanggal, $shift)
	{
		if (empty($tanggal) || empty($shift)) {
			return [];
		}

		$this->db->select('
			nama_spv,
			tgl_update,
			date,
			shift,
			date_stall,
			nama_produk,
			shift_pack,
			catatan,
			status_spv,
			username,
			premix,
			nama_produksi,
			tgl_update_prod,
			status_produksi,
			proses_produksi,
			proses_packing
			');

		$this->db->where('DATE(date)', $tanggal);
		$this->db->where('shift', $shift);
		$this->db->order_by('tgl_update', 'DESC');

		$query = $this->db->get('mixing');

		log_message('error', 'EXPORT QUERY: ' . $this->db->last_query());
		log_message('error', 'EXPORT TOTAL: ' . $query->num_rows());

		// ✅ WAJIB RESULT()
		return $query->result();
	}

	public function get_latest_today()
	{
		$plant = $this->session->userdata('plant');

		$this->db->where('date', date('Y-m-d'));
		$this->db->where('plant', $plant);
		$this->db->order_by('created_at', 'DESC');
		$query = $this->db->get('mixing', 1);

		return $query->row_array();
	}

	public function count_today_same_product()
	{
		$plant = $this->session->userdata('plant');

		$this->db->select('nama_produk');
		$this->db->where('plant', $plant);
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit(1);
		$last_updated_product = $this->db->get('mixing')->row_array();

		if (!$last_updated_product) {
			return 0;
		}

		$this->db->where('date', date('Y-m-d'));
		$this->db->where('nama_produk', $last_updated_product['nama_produk']);
		$this->db->where('plant', $plant);
		return $this->db->count_all_results('mixing');
	}

	public function get_data_by_plant()
	{
		$this->db->order_by('created_at', 'DESC');
		$plant = $this->session->userdata('plant');
		return $this->db->get_where('mixing', ['plant' => $plant])->result();
	}

	public function delete_by_uuid($uuid)
	{
		$this->db->where('uuid', $uuid);
		return $this->db->delete('mixing');
	}

	// application/models/Proses_model.php
	public function getLastKodeproduksiHariIni($plant = null)
	{
		$today = date('Y-m-d');
		$this->db->select('kode_produksi');
		$this->db->from('mixing');

		$this->db->where('DATE(created_at)', $today);

		if (!empty($plant)) {
			$this->db->where('plant', $plant);
		}

		$this->db->order_by('created_at', 'DESC');
		$this->db->limit(1);

		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			return $query->row()->kode_produksi;
		}

		return null;
	}
}
