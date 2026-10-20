<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends MY_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('auth_model');
		$this->load->model('produksi_model');
		$this->load->model('penerimaankemasan_model');
		$this->load->model('seasoning_model');
		$this->load->model('pemeriksaanchemical_model');
		$this->load->model('loading_model');
		$this->load->model('kontaminasi_model');
		$this->load->model('pegawai_model');
		$this->load->model('suhu_model');

		if (!$this->auth_model->current_user()) {
			redirect('login');
		}
	}

	public function index()
	{
		$pegawai = $this->auth_model->current_user();
		$show_modal = false;
		$tipe_user = $this->session->userdata('tipe_user');

		if (
			!$this->session->userdata('produksi_data') &&
			$this->session->userdata('show_produksi_modal') &&
			in_array($tipe_user, [4, 8])
		) {
			$show_modal = true;
		}

		$plant_uuid = $this->session->userdata('plant');

		/*
		|--------------------------------------------------------------------------
		| FILTER TANGGAL
		|--------------------------------------------------------------------------
		| Default: kemarin sampai hari ini
		*/
		$from_date = $this->input->get('from_date');
		$to_date   = $this->input->get('to_date');

		if (
			!$from_date ||
			!preg_match('/^\d{4}-\d{2}-\d{2}$/', $from_date)
		) {
			$from_date = date('Y-m-d', strtotime('-1 day'));
		}

		if (
			!$to_date ||
			!preg_match('/^\d{4}-\d{2}-\d{2}$/', $to_date)
		) {
			$to_date = date('Y-m-d');
		}

		// Jika tanggal terbalik, tukar
		if ($from_date > $to_date) {
			$temp = $from_date;
			$from_date = $to_date;
			$to_date = $temp;
		}

		/*
		|--------------------------------------------------------------------------
		| DATA GRAFIK SUHU
		|--------------------------------------------------------------------------
		| Grafik suhu tetap menggunakan tanggal akhir yang dipilih.
		*/
		$tanggal = $to_date;

		$suhu_data = $this->suhu_model
			->get_suhu_today_by_plant($plant_uuid, $tanggal);

		/*
		|--------------------------------------------------------------------------
		| DATA PRODUKSI
		|--------------------------------------------------------------------------
		| Untuk KPI produk, ambil data berdasarkan tanggal akhir.
		*/
		$proses = $this->produksi_model
			->get_produksi_by_plant_and_date($plant_uuid, $tanggal);

		$proses_produksi = [];
		$proses_packing = [];

		if ($proses) {
			$proses_produksi = json_decode(
				$proses->proses_produksi ?? '[]',
				true
			) ?? [];

			$proses_packing = json_decode(
				$proses->proses_packing ?? '[]',
				true
			) ?? [];
		}

		/*
		|--------------------------------------------------------------------------
		| ARRAY KPI
		|--------------------------------------------------------------------------
		*/
		$kadar_air_arr = [];
		$suhu_produk_arr = [];
		$bulk_density_arr = [];

		$kadar_air_info = [];
		$suhu_produk_info = [];
		$bulk_density_info = [];

		if (is_array($proses_packing)) {

			foreach ($proses_packing as $item) {

				$p = $item['pemeriksaan_finished_product'] ?? [];

				$kadar = trim($p['kadar_air_produk'][0] ?? '');
				$suhu  = trim($p['suhu_sebelum_packing'][0] ?? '');
				$bdens = trim($p['bulk_density'][0] ?? '');

				$nama_produk = trim(
					$p['nama_produk'][0] ?? ''
				);

				$kode_produksi = trim(
					$p['kode_produksi'][0] ?? ''
				);

				/*
				|--------------------------------------------------------------------------
				| KADAR AIR
				|--------------------------------------------------------------------------
				*/
				if (is_numeric($kadar)) {

					$kadar = (float) $kadar;

					$kadar_air_arr[] = $kadar;

					$kadar_air_info[] = [
						'nilai'  => $kadar,
						'produk' => $nama_produk,
						'kode'   => $kode_produksi
					];
				}

				/*
				|--------------------------------------------------------------------------
				| SUHU PRODUK
				|--------------------------------------------------------------------------
				*/
				if (is_numeric($suhu)) {

					$suhu = (float) $suhu;

					$suhu_produk_arr[] = $suhu;

					$suhu_produk_info[] = [
						'nilai'  => $suhu,
						'produk' => $nama_produk,
						'kode'   => $kode_produksi
					];
				}

				/*
				|--------------------------------------------------------------------------
				| BULK DENSITY
				|--------------------------------------------------------------------------
				*/
				if (is_numeric($bdens)) {

					$bdens = (float) $bdens;

					$bulk_density_arr[] = $bdens;

					$bulk_density_info[] = [
						'nilai'  => $bdens,
						'produk' => $nama_produk,
						'kode'   => $kode_produksi
					];
				}
			}
		}

		/*
		|--------------------------------------------------------------------------
		| FUNGSI EXTREME
		|--------------------------------------------------------------------------
		*/
		if (!function_exists('ambil_extreme_info')) {

			function ambil_extreme_info($info_array, $tipe = 'max')
			{
				if (empty($info_array)) {
					return [null, '', ''];
				}

				$fungsi = ($tipe === 'max') ? 'max' : 'min';

				$nilai_ekstrim = $fungsi(
					array_column($info_array, 'nilai')
				);

				foreach ($info_array as $info) {

					if ($info['nilai'] === $nilai_ekstrim) {

						return [
							$nilai_ekstrim,
							$info['produk'],
							$info['kode']
						];
					}
				}

				return [null, '', ''];
			}
		}

		/*
		|--------------------------------------------------------------------------
		| EXTREME KADAR AIR
		|--------------------------------------------------------------------------
		*/
		list(
			$ka_max,
			$ka_max_produk,
			$ka_max_kode
		) = ambil_extreme_info(
			$kadar_air_info,
			'max'
		);

		list(
			$ka_min,
			$ka_min_produk,
			$ka_min_kode
		) = ambil_extreme_info(
			$kadar_air_info,
			'min'
		);

		/*
		|--------------------------------------------------------------------------
		| EXTREME SUHU
		|--------------------------------------------------------------------------
		*/
		list(
			$suhu_max,
			$suhu_max_produk,
			$suhu_max_kode
		) = ambil_extreme_info(
			$suhu_produk_info,
			'max'
		);

		list(
			$suhu_min,
			$suhu_min_produk,
			$suhu_min_kode
		) = ambil_extreme_info(
			$suhu_produk_info,
			'min'
		);

		/*
		|--------------------------------------------------------------------------
		| EXTREME BULK DENSITY
		|--------------------------------------------------------------------------
		*/
		list(
			$bd_max,
			$bd_max_produk,
			$bd_max_kode
		) = ambil_extreme_info(
			$bulk_density_info,
			'max'
		);

		list(
			$bd_min,
			$bd_min_produk,
			$bd_min_kode
		) = ambil_extreme_info(
			$bulk_density_info,
			'min'
		);

		/*
		|--------------------------------------------------------------------------
		| ROTI GOSONG
		|--------------------------------------------------------------------------
		*/
		$roti_gosong = $this->db
			->select('shift, SUM(total_berat) as total_kg')
			->where('plant', $plant_uuid)
			->where('date >=', $from_date)
			->where('date <=', $to_date)
			->group_by('shift')
			->order_by('shift', 'ASC')
			->get('roti_gosong')
			->result();

		/*
		|--------------------------------------------------------------------------
		| KONTAMINASI
		|--------------------------------------------------------------------------
		*/
		$kontaminasi_chart = $this->db
			->select('
				jenis_kontaminasi,
				SUM(jumlah_temuan) as jumlah
			')
			->where('plant', $plant_uuid)
			->where(
				'created_at >=',
				$from_date . ' 00:00:00'
			)
			->where(
				'created_at <=',
				$to_date . ' 23:59:59'
			)
			->group_by('jenis_kontaminasi')
			->get('kontaminasi')
			->result();

		/*
		|--------------------------------------------------------------------------
		| KETIDAKSESUAIAN METAL DETECTOR
		|--------------------------------------------------------------------------
		*/
		$ketidaksesuaian_md = $this->db
			->select('shift, COUNT(*) AS jumlah')
			->where('plant', $plant_uuid)
			->where(
				'created_at >=',
				$from_date . ' 00:00:00'
			)
			->where(
				'created_at <=',
				$to_date . ' 23:59:59'
			)
			->group_by('shift')
			->order_by('shift', 'ASC')
			->get('metal')
			->result();

		/*
		|--------------------------------------------------------------------------
		| DATA DASHBOARD
		|--------------------------------------------------------------------------
		*/
		$data = [

			'plant_uuid' => $plant_uuid,

			// FILTER
			'from_date' => $from_date,
			'to_date'   => $to_date,

			'nama_pegawai' => $pegawai
				? $pegawai->nama
				: 'Tamu',

			'latest_today' =>
			$this->produksi_model->get_latest_today(),

			'count_batch' =>
			$this->produksi_model->count_today_same_product(),

			'packaging' =>
			$this->penerimaankemasan_model->get_latest_kemasan(),

			'seasoning' =>
			$this->seasoning_model->get_latest_seasoning(),

			'chemical' =>
			$this->pemeriksaanchemical_model->get_latest_chemical(),

			'loading' =>
			$this->loading_model->get_latest_loading(),

			'jumlah_temuan' =>
			$this->kontaminasi_model->get_temuan_per_hari(),

			'temuan' =>
			$this->kontaminasi_model->get_latest_temuan_bulan_ini(),

			'pegawai_produksi' =>
			$this->pegawai_model
				->get_pegawai_produksi_by_plant($plant_uuid),

			'show_modal' => $show_modal,

			'suhu_data' => $suhu_data,

			'tanggal_dipilih' => $tanggal,

			'active_nav' => 'home',

			/*
			|--------------------------------------------------------------------------
			| KPI KADAR AIR
			|--------------------------------------------------------------------------
			*/
			'kadar_air_max' => $ka_max,
			'kadar_air_max_produk' => $ka_max_produk,
			'kadar_air_max_kode' => $ka_max_kode,

			'kadar_air_min' => $ka_min,
			'kadar_air_min_produk' => $ka_min_produk,
			'kadar_air_min_kode' => $ka_min_kode,

			/*
			|--------------------------------------------------------------------------
			| KPI SUHU PRODUK
			|--------------------------------------------------------------------------
			*/
			'suhu_produk_max' => $suhu_max,
			'suhu_produk_max_produk' => $suhu_max_produk,
			'suhu_produk_max_kode' => $suhu_max_kode,

			'suhu_produk_min' => $suhu_min,
			'suhu_produk_min_produk' => $suhu_min_produk,
			'suhu_produk_min_kode' => $suhu_min_kode,

			/*
			|--------------------------------------------------------------------------
			| KPI BULK DENSITY
			|--------------------------------------------------------------------------
			*/
			'bulk_density_max' => $bd_max,
			'bulk_density_max_produk' => $bd_max_produk,
			'bulk_density_max_kode' => $bd_max_kode,

			'bulk_density_min' => $bd_min,
			'bulk_density_min_produk' => $bd_min_produk,
			'bulk_density_min_kode' => $bd_min_kode,

			/*
			|--------------------------------------------------------------------------
			| CHART
			|--------------------------------------------------------------------------
			*/
			'roti_gosong' =>
			$roti_gosong,

			'kontaminasi_chart' =>
			$kontaminasi_chart,

			'ketidaksesuaian_md' =>
			$ketidaksesuaian_md,
		];

		$this->active_nav = 'home';

		$this->render(
			'home/home',
			$data
		);
	}

	public function set_produksi_data()
	{
		$this->session->set_userdata('produksi_data', [
			'tanggal' => $this->input->post('tanggal'),
			'shift' => $this->input->post('shift'),
			'nama_produksi' => $this->input->post('nama_produksi')
		]);

		$this->session->unset_userdata('show_produksi_modal');
		redirect('home');
	}
}
