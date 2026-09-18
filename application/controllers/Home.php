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
		if (
			!$this->session->userdata('produksi_data') &&
			$this->session->userdata('show_produksi_modal')
		) {
			$show_modal = true;
		}

		$plant_uuid = $this->session->userdata('plant');

		// URL Grafana
		//$grafana_suhu = 'http://10.71.3.27:3001/d-solo/ca3cd41e-8328-457f-9449-6151ecba7207/breadcrumb-suhu-ruang?orgId=1&from=now-24h&to=now&timezone=browser&refresh=1m&panelId=panel-17';

		//$tanggal = $this->input->get('tanggal');
		//if (!$tanggal || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
		//	$tanggal = date('Y-m-d');
		//}

		$from_date = $this->input->get('from_date');
		$to_date   = $this->input->get('to_date');

		// Default: kemarin sampai hari ini
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

		// Jika terbalik, tukar
		if ($from_date > $to_date) {
			$temp = $from_date;
			$from_date = $to_date;
			$to_date = $temp;
		}

		// Grafik suhu
		$tanggal = $to_date;

		$suhu_data = $this->suhu_model
			->get_suhu_today_by_plant($plant_uuid, $tanggal);

		$mixing = $this->produksi_model
			->get_produksi_by_plant_and_date($plant_uuid, $tanggal);

		if (!$mixing || empty($mixing->proses_packing)) {
			$mixing = $this->produksi_model
				->get_last_produksi_with_packing($plant_uuid);

			if ($mixing) {
				$tanggal = $mixing->date;
			}
		}

		// KPI
		$kpi = $this->getKpiData($mixing);

		$roti_gosong = $this->db
			->select('shift, SUM(total_berat) as total_kg')
			->where('plant', $plant_uuid)
			->where('date >=', $from_date)
			->where('date <=', $to_date)
			->group_by('shift')
			->order_by('shift', 'ASC')
			->get('roti_gosong')
			->result();

		$kontaminasi_chart = $this->db
			->select('
				jenis_kontaminasi,
				SUM(jumlah_temuan) as jumlah
			')
			->where('plant', $plant_uuid)
			->where('created_at >=', $from_date . ' 00:00:00')
			->where('created_at <=', $to_date . ' 23:59:59')
			->group_by('jenis_kontaminasi')
			->get('kontaminasi')
			->result();

		$ketidaksesuaian_md = $this->db
			->select('shift, COUNT(*) AS jumlah')
			->where('plant', $plant_uuid)
			->where('created_at >=', $from_date . ' 00:00:00')
			->where('created_at <=', $to_date . ' 23:59:59')
			->group_by('shift')
			->order_by('shift', 'ASC')
			->get('metal')
			->result();

		$data = [
			'plant_uuid' => $plant_uuid,
			'from_date' => $from_date,
			'to_date'   => $to_date,
			'nama_pegawai'      => $pegawai ? $pegawai->nama : 'Tamu',

			'latest_today'      => $this->produksi_model->get_latest_today(),
			'count_batch'       => $this->produksi_model->count_today_same_product(),

			'packaging'         => $this->penerimaankemasan_model->get_latest_kemasan(),
			'seasoning'         => $this->seasoning_model->get_latest_seasoning(),
			'chemical'          => $this->pemeriksaanchemical_model->get_latest_chemical(),
			'loading'           => $this->loading_model->get_latest_loading(),

			'jumlah_temuan'     => $this->kontaminasi_model->get_temuan_per_hari(),
			'temuan'            => $this->kontaminasi_model->get_latest_temuan_bulan_ini(),

			'pegawai_produksi'  => $this->pegawai_model->get_pegawai_produksi_by_plant($plant_uuid),

			'show_modal'        => $show_modal,
			'suhu_data'         => $suhu_data,
			'tanggal_dipilih'   => $tanggal,
			'active_nav'        => 'home',

			'kpi' => $kpi,
			'roti_gosong' => $roti_gosong,
			'kontaminasi_chart' => $kontaminasi_chart,
			'ketidaksesuaian_md' => $ketidaksesuaian_md,

			'kadar_air_min' => $kpi['kadar_air']['min'],
			'kadar_air_max' => $kpi['kadar_air']['max'],
			'kadar_air_avg' => $kpi['kadar_air']['avg'],

			'kadar_air_min_produk' => $kpi['kadar_air']['min_produk'],
			'kadar_air_max_produk' => $kpi['kadar_air']['max_produk'],

			'kadar_air_min_kode' => $kpi['kadar_air']['min_kode'],
			'kadar_air_max_kode' => $kpi['kadar_air']['max_kode'],


			'suhu_produk_min' => $kpi['suhu']['min'],
			'suhu_produk_max' => $kpi['suhu']['max'],
			'suhu_produk_avg' => $kpi['suhu']['avg'],

			'suhu_produk_min_produk' => $kpi['suhu']['min_produk'],
			'suhu_produk_max_produk' => $kpi['suhu']['max_produk'],

			'suhu_produk_min_kode' => $kpi['suhu']['min_kode'],
			'suhu_produk_max_kode' => $kpi['suhu']['max_kode'],


			'bulk_density_min' => $kpi['bulk_density']['min'],
			'bulk_density_max' => $kpi['bulk_density']['max'],
			'bulk_density_avg' => $kpi['bulk_density']['avg'],

			'bulk_density_min_produk' => $kpi['bulk_density']['min_produk'],
			'bulk_density_max_produk' => $kpi['bulk_density']['max_produk'],

			'bulk_density_min_kode' => $kpi['bulk_density']['min_kode'],
			'bulk_density_max_kode' => $kpi['bulk_density']['max_kode'],
		];

		$data['mixing_debug'] = $mixing;

		$this->active_nav = 'home';
		$this->render('home/dashboard_spv', $data);
	}

	private function getKpiData($mixing)
	{
		$result = [
			'kadar_air' => [
				'min' => null,
				'max' => null,
				'avg' => null,
				'min_produk' => '',
				'max_produk' => '',
				'min_kode' => '',
				'max_kode' => ''
			],
			'suhu' => [
				'min' => null,
				'max' => null,
				'avg' => null,
				'min_produk' => '',
				'max_produk' => '',
				'min_kode' => '',
				'max_kode' => ''
			],
			'bulk_density' => [
				'min' => null,
				'max' => null,
				'avg' => null,
				'min_produk' => '',
				'max_produk' => '',
				'min_kode' => '',
				'max_kode' => ''
			]
		];

		if (!$mixing) {
			return $result;
		}

		$packing = json_decode($mixing->proses_packing, true);

		if (!is_array($packing)) {
			return $result;
		}

		$ka = [];
		$sp = [];
		$bd = [];

		foreach ($packing as $item) {

			$p = $item['pemeriksaan_finished_product'] ?? [];

			$produk = trim($p['nama_produk'][0] ?? '');
			$kode   = trim($p['kode_produksi'][0] ?? '');

			if (is_numeric($p['kadar_air_produk'][0] ?? '')) {
				$ka[] = [
					'nilai' => (float)$p['kadar_air_produk'][0],
					'produk' => $produk,
					'kode' => $kode
				];
			}

			if (is_numeric($p['suhu_sebelum_packing'][0] ?? '')) {
				$sp[] = [
					'nilai' => (float)$p['suhu_sebelum_packing'][0],
					'produk' => $produk,
					'kode' => $kode
				];
			}

			if (is_numeric($p['bulk_density'][0] ?? '')) {
				$bd[] = [
					'nilai' => (float)$p['bulk_density'][0],
					'produk' => $produk,
					'kode' => $kode
				];
			}
		}

		$result['kadar_air'] = $this->calculateKpi($ka);
		$result['suhu'] = $this->calculateKpi($sp);
		$result['bulk_density'] = $this->calculateKpi($bd);

		return $result;
	}

	private function calculateKpi($rows)
	{
		if (empty($rows)) {
			return [
				'min' => null,
				'max' => null,
				'avg' => null,
				'min_produk' => '',
				'max_produk' => '',
				'min_kode' => '',
				'max_kode' => ''
			];
		}

		$nilai = array_column($rows, 'nilai');

		$min = min($nilai);
		$max = max($nilai);
		$avg = round(array_sum($nilai) / count($nilai), 2);

		$hasil = [
			'min' => $min,
			'max' => $max,
			'avg' => $avg,
			'min_produk' => '',
			'max_produk' => '',
			'min_kode' => '',
			'max_kode' => ''
		];

		foreach ($rows as $r) {

			if ($r['nilai'] == $min) {
				$hasil['min_produk'] = $r['produk'];
				$hasil['min_kode'] = $r['kode'];
			}

			if ($r['nilai'] == $max) {
				$hasil['max_produk'] = $r['produk'];
				$hasil['max_kode'] = $r['kode'];
			}
		}

		return $hasil;
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

	/*public function index old()
	{
		$pegawai = $this->auth_model->current_user();
		$show_modal = false;

		if (!$this->session->userdata('produksi_data') && $this->session->userdata('show_produksi_modal')) {
			$show_modal = true;
		}

		$plant_uuid = $this->session->userdata('plant');

		// Ambil tanggal dari GET, fallback ke hari ini
		$tanggal = $this->input->get('tanggal');
		if (!$tanggal || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
			$tanggal = date('Y-m-d');
		}

		$suhu_data = $this->suhu_model->get_suhu_today_by_plant($plant_uuid, $tanggal);

		// Ambil data proses_produksi dan proses_packing
		$proses = $this->produksi_model->get_produksi_by_plant_and_date($plant_uuid, $tanggal);
		$proses_produksi = [];
		$proses_packing = [];

		if ($proses) {
			$proses_produksi = json_decode($proses->proses_produksi, true);
			$proses_packing = json_decode($proses->proses_packing, true);
		}

		// Inisialisasi array dan info produk
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
				$suhu = trim($p['suhu_sebelum_packing'][0] ?? '');
				$bdens = trim($p['bulk_density'][0] ?? '');

				$nama_produk = trim($p['nama_produk'][0] ?? '');
				$kode_produksi = trim($p['kode_produksi'][0] ?? '');

				if (is_numeric($kadar)) {
					$kadar = (float)$kadar;
					$kadar_air_arr[] = $kadar;
					$kadar_air_info[] = [
						'nilai' => $kadar,
						'produk' => $nama_produk,
						'kode' => $kode_produksi
					];
				}

				if (is_numeric($suhu)) {
					$suhu = (float)$suhu;
					$suhu_produk_arr[] = $suhu;
					$suhu_produk_info[] = [
						'nilai' => $suhu,
						'produk' => $nama_produk,
						'kode' => $kode_produksi
					];
				}

				if (is_numeric($bdens)) {
					$bdens = (float)$bdens;
					$bulk_density_arr[] = $bdens;
					$bulk_density_info[] = [
						'nilai' => $bdens,
						'produk' => $nama_produk,
						'kode' => $kode_produksi
					];
				}
			}
		}

		// Fungsi bantu ambil info ekstrem
		function ambil_extreme_info($info_array, $tipe = 'max')
		{
			if (empty($info_array)) return [null, '', ''];
			$fungsi = ($tipe === 'max') ? 'max' : 'min';
			$nilai_ekstrim = $fungsi(array_column($info_array, 'nilai'));
			foreach ($info_array as $info) {
				if ($info['nilai'] === $nilai_ekstrim) {
					return [$nilai_ekstrim, $info['produk'], $info['kode']];
				}
			}
			return [null, '', ''];
		}

		// Ambil nilai dan info final
		list($ka_max, $ka_max_produk, $ka_max_kode) = ambil_extreme_info($kadar_air_info, 'max');
		list($ka_min, $ka_min_produk, $ka_min_kode) = ambil_extreme_info($kadar_air_info, 'min');

		list($suhu_max, $suhu_max_produk, $suhu_max_kode) = ambil_extreme_info($suhu_produk_info, 'max');
		list($suhu_min, $suhu_min_produk, $suhu_min_kode) = ambil_extreme_info($suhu_produk_info, 'min');

		list($bd_max, $bd_max_produk, $bd_max_kode) = ambil_extreme_info($bulk_density_info, 'max');
		list($bd_min, $bd_min_produk, $bd_min_kode) = ambil_extreme_info($bulk_density_info, 'min');

		$data = [
			'nama_pegawai' => $pegawai ? $pegawai->nama : "Tamu",
			'latest_today' => $this->produksi_model->get_latest_today(),
			'count_batch' => $this->produksi_model->count_today_same_product(),
			'packaging' => $this->penerimaankemasan_model->get_latest_kemasan(),
			'seasoning' => $this->seasoning_model->get_latest_seasoning(),
			'chemical' => $this->pemeriksaanchemical_model->get_latest_chemical(),
			'loading' => $this->loading_model->get_latest_loading(),
			'jumlah_temuan' => $this->kontaminasi_model->get_temuan_per_hari(),
			'temuan' => $this->kontaminasi_model->get_latest_temuan_bulan_ini(),
			'pegawai_produksi' => $this->pegawai_model->get_pegawai_produksi_by_plant($plant_uuid),
			'show_modal' => $show_modal,
			'suhu_data' => $suhu_data,
			'tanggal_dipilih' => $tanggal,
			'active_nav' => 'home',

			// Tambahan untuk dashboard:
			'kadar_air_max' => $ka_max,
			'kadar_air_max_produk' => $ka_max_produk,
			'kadar_air_max_kode' => $ka_max_kode,
			'kadar_air_min' => $ka_min,
			'kadar_air_min_produk' => $ka_min_produk,
			'kadar_air_min_kode' => $ka_min_kode,

			'suhu_produk_max' => $suhu_max,
			'suhu_produk_max_produk' => $suhu_max_produk,
			'suhu_produk_max_kode' => $suhu_max_kode,
			'suhu_produk_min' => $suhu_min,
			'suhu_produk_min_produk' => $suhu_min_produk,
			'suhu_produk_min_kode' => $suhu_min_kode,

			'bulk_density_max' => $bd_max,
			'bulk_density_max_produk' => $bd_max_produk,
			'bulk_density_max_kode' => $bd_max_kode,
			'bulk_density_min' => $bd_min,
			'bulk_density_min_produk' => $bd_min_produk,
			'bulk_density_min_kode' => $bd_min_kode,
		];

		$this->active_nav = 'home';
		$this->render('home/dashboard_user', $data);
	}*/
}
