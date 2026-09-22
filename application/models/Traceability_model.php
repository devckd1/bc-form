<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Traceability_model extends CI_Model
{
	/**
	 * ============================================================
	 * SEARCH TRACEABILITY
	 * ============================================================
	 */
	public function searchTraceability($tokens = [], $date = null, $cfg = [])
	{
		$table = $this->safeTable($cfg);

		if (!$table) {
			return [];
		}

		$this->db->from($table);

		// Plant
		$this->applyPlantFilter($cfg);

		// Soft delete
		$this->applySoftDeleteFilter($cfg);

		// Date
		$this->applyDateFilter($cfg, $date);

		$searchColumns = !empty($cfg['search_columns'])
			? $cfg['search_columns']
			: [];

		$jsonColumns = !empty($cfg['json_search_columns'])
			? $cfg['json_search_columns']
			: [];

		$isDateOnly = !empty($cfg['date_only']);

		/*
		|--------------------------------------------------------------------------
		| SEARCH KEYWORD
		|--------------------------------------------------------------------------
		| Setiap token harus ditemukan pada salah satu search column / JSON column.
		*/
		if (
			!empty($tokens) &&
			!$isDateOnly &&
			(!empty($searchColumns) || !empty($jsonColumns))
		) {

			foreach ($tokens as $token) {

				$this->db->group_start();

				$first = true;

				/*
				|--------------------------------------------------------------
				| Normal columns
				|--------------------------------------------------------------
				*/
				foreach ($searchColumns as $column) {

					if (!$this->isSafeIdentifier($column)) {
						continue;
					}

					if ($first) {

						$this->db->like(
							$table . '.' . $column,
							$token
						);

						$first = false;

					} else {

						$this->db->or_like(
							$table . '.' . $column,
							$token
						);
					}
				}

				/*
				|--------------------------------------------------------------
				| JSON columns
				|--------------------------------------------------------------
				| MariaDB tidak menggunakan CAST(... AS JSON).
				| Search dilakukan terhadap isi JSON sebagai text.
				*/
				foreach ($jsonColumns as $column) {

					if (!$this->isSafeIdentifier($column)) {
						continue;
					}

					$condition = $this->jsonContainsCondition(
						$table,
						$column,
						$token
					);

					if ($first) {

						$this->db->where(
							$condition,
							null,
							false
						);

						$first = false;

					} else {

						$this->db->or_where(
							$condition,
							null,
							false
						);
					}
				}

				$this->db->group_end();
			}
		}

		$key = $this->getRouteKey($cfg);
		$dateColumn = $this->getDateColumn($cfg);

		if (!$this->isSafeIdentifier($key)) {
			return [];
		}

		$select = [
			$table . '.*',
			$table . '.' . $key . ' AS form_key'
		];

		if (
			$dateColumn &&
			$this->isSafeIdentifier($dateColumn)
		) {
			$select[] = $table . '.' . $dateColumn . ' AS form_date';
		}

		/*
		|--------------------------------------------------------------------------
		| SHIFT
		|--------------------------------------------------------------------------
		*/
		if ($this->hasColumnConfig($cfg, 'shift')) {

			if ($this->hasColumn($table, 'shift')) {
				$select[] = $table . '.shift AS shift';
			} else {
				$select[] = 'NULL AS shift';
			}

		} else {

			$select[] = 'NULL AS shift';
		}

		$this->db->select(
			implode(', ', $select),
			false
		);

		if (
			$dateColumn &&
			$this->isSafeIdentifier($dateColumn)
		) {

			$this->db->order_by(
				$table . '.' . $dateColumn,
				'DESC'
			);

		} else {

			$this->db->order_by(
				$table . '.' . $key,
				'DESC'
			);
		}

		return $this->db
			->get()
			->result_array();
	}


	/**
	 * ============================================================
	 * GET DETAIL TRACEABILITY
	 * ============================================================
	 */
	public function getTraceabilityDetails(
		$formKey,
		$tokens = [],
		$date = null,
		$cfg = []
	) {
		$table = $this->safeTable($cfg);

		if (!$table) {
			return [];
		}

		$key = $this->getRouteKey($cfg);

		if (!$this->isSafeIdentifier($key)) {
			return [];
		}

		/*
		|--------------------------------------------------------------------------
		| Ambil 1 record
		|--------------------------------------------------------------------------
		*/
		$this->db->from($table);

		$this->db->where(
			$table . '.' . $key,
			$formKey
		);

		// Plant scoping
		$this->applyPlantFilter($cfg);

		// Soft delete
		$this->applySoftDeleteFilter($cfg);

		// Date scoping
		$this->applyDateFilter($cfg, $date);

		$row = $this->db
			->get()
			->row_array();

		if (empty($row)) {
			return [];
		}

		/*
		|--------------------------------------------------------------------------
		| DETAIL SECTIONS
		|--------------------------------------------------------------------------
		| Struktur:
		|
		| 'Informasi Pemeriksaan' => [
		|     'Tanggal' => 'date',
		|     'Shift' => 'shift'
		| ]
		|
		*/
		$detailSections = !empty($cfg['detail_sections'])
			? $cfg['detail_sections']
			: [];

		$details = [];

		/*
		|--------------------------------------------------------------------------
		| Render berdasarkan detail_sections
		|--------------------------------------------------------------------------
		*/
		foreach ($detailSections as $section => $fields) {

			if (!is_array($fields)) {
				continue;
			}

			foreach ($fields as $label => $column) {

				if (!$this->isSafeIdentifier($column)) {
					continue;
				}

				if (!array_key_exists($column, $row)) {
					continue;
				}

				$value = $row[$column];

				/*
				|--------------------------------------------------------------
				| Format khusus field
				|--------------------------------------------------------------
				*/
				$formatted = $this->formatDetailField(
					isset($cfg['module_key']) ? $cfg['module_key'] : '',
					$column,
					$value,
					$row,
					$cfg
				);

				/*
				|--------------------------------------------------------------
				| Tetap masukkan field meskipun kosong.
				| Renderer frontend dapat menentukan apakah "-" ditampilkan.
				|--------------------------------------------------------------
				*/
				$details[] = [
					'section' => $section,
					'field'   => $label,
					'value'   => $formatted['value'],
					'format'  => $formatted['format']
				];
			}
		}

		/*
		|--------------------------------------------------------------------------
		| FALLBACK display_fields
		|--------------------------------------------------------------------------
		| Untuk config lama yang belum menggunakan detail_sections.
		|--------------------------------------------------------------------------
		*/
		if (
			empty($detailSections) &&
			!empty($cfg['display_fields'])
		) {

			foreach ($cfg['display_fields'] as $label => $column) {

				if (!$this->isSafeIdentifier($column)) {
					continue;
				}

				if (!array_key_exists($column, $row)) {
					continue;
				}

				$formatted = $this->formatDetailField(
					isset($cfg['module_key']) ? $cfg['module_key'] : '',
					$column,
					$row[$column],
					$row,
					$cfg
				);

				$details[] = [
					'section' => 'Informasi',
					'field'   => $label,
					'value'   => $formatted['value'],
					'format'  => $formatted['format']
				];
			}
		}

		return $details;
	}


	/**
	 * ============================================================
	 * FORMAT DETAIL FIELD
	 * ============================================================
	 */
	private function formatDetailField(
		$module,
		$column,
		$value,
		$row = [],
		$cfg = []
	) {
		/*
		|--------------------------------------------------------------------------
		| Default
		|--------------------------------------------------------------------------
		*/
		if ($value === null || $value === '') {

			return [
				'value'  => '-',
				'format' => 'text'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| TANGGAL
		|--------------------------------------------------------------------------
		*/
		$dateColumns = [
			'date',
			'date_metal',
			'expired',
			'expired_date',
			'best_before',
			'date_stall'
		];

		if (in_array($column, $dateColumns, true)) {

			$formattedDate = $this->formatDateValue($value);

			return [
				'value'  => $formattedDate,
				'format' => 'date'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| WAKTU
		|--------------------------------------------------------------------------
		*/
		$timeColumns = [
			'time',
			'update_time_t',
			'update_time_b'
		];

		if (in_array($column, $timeColumns, true)) {

			return [
				'value'  => $this->formatTimeValue($value),
				'format' => 'time'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| STATUS SUPERVISOR
		|--------------------------------------------------------------------------
		*/
		if ($column === 'status_spv') {

			return [
				'value'  => $this->formatSupervisorStatus($value),
				'format' => 'status'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| DETEKSI NG METAL
		|--------------------------------------------------------------------------
		*/
		if ($column === 'deteksi_ng') {

			$map = [
				'1' => 'Belt Conveyor Berhenti',
				'2' => 'Rejector'
			];

			return [
				'value'  => isset($map[(string) $value])
					? $map[(string) $value]
					: '-',
				'format' => 'text'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| FIELD DETEKSI METAL
		|--------------------------------------------------------------------------
		*/
		$metalDetectionColumns = [
			'fe_d',
			'nonfe_d',
			'sus_d',
			'fe_t',
			'nonfe_t',
			'sus_t',
			'fe_b',
			'nonfe_b',
			'sus_b'
		];

		if (in_array($column, $metalDetectionColumns, true)) {

			return [
				'value'  => $this->formatMetalDetection($value),
				'format' => 'check'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| CHECK "SESUAI"
		|--------------------------------------------------------------------------
		| Digunakan Chemical, Kemasan, Seasoning.
		|--------------------------------------------------------------------------
		*/
		$sesuaiColumns = [
			'kemasan',
			'warna',
			'kotoran',
			'aroma',
			'panjang',
			'diameter',
			'lebar',
			'tinggi',
			'berat',
			'delaminasi',
			'bau',
			'desain'
		];

		if (in_array($column, $sesuaiColumns, true)) {

			return [
				'value'  => $this->formatExpectedCheck(
					$value,
					'sesuai'
				),
				'format' => 'check'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| HALAL BERLAKU
		|--------------------------------------------------------------------------
		*/
		if ($column === 'halal_berlaku') {

			return [
				'value'  => $this->formatExpectedCheck(
					$value,
					'berlaku'
				),
				'format' => 'check'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| PENERIMAAN CHEMICAL / KEMASAN / SEASONING
		|--------------------------------------------------------------------------
		*/
		if ($column === 'penerimaan') {

			$expected = 'ok';

			return [
				'value'  => $this->formatExpectedCheck(
					$value,
					$expected
				),
				'format' => 'check'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| COA
		|--------------------------------------------------------------------------
		*/
		if ($column === 'coa') {

			return [
				'value'  => $this->formatExpectedCheck(
					$value,
					'ada'
				),
				'format' => 'check'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| LOGO HALAL / SERTIFIKAT HALAL / ALLERGEN
		|--------------------------------------------------------------------------
		| Pada view asli field ini ditampilkan sebagai nilai.
		| Jadi tidak dipaksa menjadi icon.
		|--------------------------------------------------------------------------
		*/
		if (
			$column === 'logo_halal' ||
			$column === 'sertif_halal' ||
			$column === 'allergen'
		) {

			return [
				'value'  => $this->formatPlainValue($value),
				'format' => 'text'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| KONDISI MOBIL
		|--------------------------------------------------------------------------
		*/
		if ($column === 'kondisi_mobil') {

			return [
				'value'  => $this->formatKondisiMobil(
					$value,
					$module
				),
				'format' => 'kondisi_mobil'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| JSON PRODUK SENSORI
		|--------------------------------------------------------------------------
		*/
		if (
			$module === 'sensori' &&
			$column === 'produk'
		) {

			return [
				'value'  => $this->formatSensoriProduk($value),
				'format' => 'sensori_produk'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| JSON LOADING
		|--------------------------------------------------------------------------
		*/
		if (
			$module === 'loading' &&
			$column === 'loading'
		) {

			return [
				'value'  => $this->formatLoadingProduk($value),
				'format' => 'loading_produk'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| JSON PREMIX
		|--------------------------------------------------------------------------
		*/
		if (
			$module === 'produksi' &&
			$column === 'premix'
		) {

			return [
				'value'  => $this->formatPremix($value),
				'format' => 'premix'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| PROSES PRODUKSI / PACKING
		|--------------------------------------------------------------------------
		| Disimpan sebagai JSON pada mixing.
		|--------------------------------------------------------------------------
		*/
		if (
			$module === 'produksi' &&
			(
				$column === 'proses_produksi' ||
				$column === 'proses_packing'
			)
		) {

			return [
				'value'  => $this->formatJsonData($value),
				'format' => 'json'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| BUKTI GAMBAR
		|--------------------------------------------------------------------------
		*/
		if (
			$column === 'bukti' &&
			(
				$module === 'magnettrap' ||
				$module === 'kontaminasi'
			)
		) {

			return [
				'value'  => $this->formatEvidenceImage(
					$value,
					$module
				),
				'format' => 'image'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| GAMBAR KODE KEMASAN
		|--------------------------------------------------------------------------
		*/
		if ($column === 'gambar_kode_kemasan') {

			return [
				'value'  => $this->formatUploadFile(
					$value,
					'image'
				),
				'format' => 'image'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| BUKTI COA
		|--------------------------------------------------------------------------
		*/
		if ($column === 'bukti_coa') {

			return [
				'value'  => $this->formatUploadFile(
					$value,
					'file'
				),
				'format' => 'file'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| CATATAN
		|--------------------------------------------------------------------------
		*/
		if (
			$column === 'catatan' ||
			$column === 'catatan_metal' ||
			$column === 'catatan_spv'
		) {

			return [
				'value'  => $this->formatEmptyText(
					$value,
					'Tidak ada'
				),
				'format' => 'text'
			];
		}

		/*
		|--------------------------------------------------------------------------
		| DEFAULT JSON / TEXT
		|--------------------------------------------------------------------------
		*/
		$formatted = $this->formatTraceabilityValue($value);

		/*
		| Kalau JSON menghasilkan array/object,
		| tandai sebagai json supaya renderer frontend tahu.
		*/
		if (is_array($formatted) || is_object($formatted)) {

			return [
				'value'  => $formatted,
				'format' => 'json'
			];
		}

		return [
			'value'  => $formatted,
			'format' => 'text'
		];
	}


	/**
	 * ============================================================
	 * FORMAT DATE
	 * ============================================================
	 */
	private function formatDateValue($value)
	{
		if (empty($value)) {
			return '-';
		}

		try {

			return (new DateTime($value))
				->format('d-m-Y');

		} catch (Exception $e) {

			return $value;
		}
	}


	/**
	 * ============================================================
	 * FORMAT TIME
	 * ============================================================
	 */
	private function formatTimeValue($value)
	{
		if (empty($value)) {
			return '-';
		}

		/*
		| Kalau sudah H:i
		*/
		if (preg_match('/^\d{2}:\d{2}$/', (string) $value)) {
			return $value;
		}

		try {

			return (new DateTime($value))
				->format('H:i');

		} catch (Exception $e) {

			return $value;
		}
	}


	/**
	 * ============================================================
	 * STATUS SUPERVISOR
	 * ============================================================
	 */
	private function formatSupervisorStatus($value)
	{
		switch ((string) $value) {

			case '1':
				return 'Verified';

			case '2':
				return 'Revision';

			case '0':
			default:
				return 'Created';
		}
	}


	/**
	 * ============================================================
	 * METAL DETECTION
	 * ============================================================
	 */
	private function formatMetalDetection($value)
	{
		if ($value === null || $value === '') {
			return '-';
		}

		if (strtolower(trim((string) $value)) === 'terdeteksi') {
			return '✔';
		}

		return '✘';
	}


	/**
	 * ============================================================
	 * EXPECTED CHECK
	 * ============================================================
	 */
	private function formatExpectedCheck($value, $expected)
	{
		if ($value === null || $value === '') {
			return '-';
		}

		if (
			strtolower(trim((string) $value)) ===
			strtolower(trim((string) $expected))
		) {
			return '✔';
		}

		return '✘';
	}


	/**
	 * ============================================================
	 * KONDISI MOBIL
	 * ============================================================
	 */
	private function formatKondisiMobil($value, $module = '')
	{
		if ($value === null || $value === '') {
			return '-';
		}

		$data = $value;

		/*
		| JSON
		*/
		if (is_string($data)) {

			$decoded = json_decode(
				$data,
				true
			);

			if (json_last_error() === JSON_ERROR_NONE) {
				$data = $decoded;
			}
		}

		/*
		| Kalau berupa array
		*/
		if (is_array($data)) {

			$labels = [
				'bersih' =>
					'Bersih',

				'bocor' =>
					'Bocor',

				'bebas_dari_hama' =>
					'Bebas dari Hama',

				'tidak_berdebu' =>
					'Tidak Berdebu',

				'tidak_ada_sampah' =>
					'Tidak ada Sampah',

				'kering' =>
					'Kering',

				'basah' =>
					'Basah',

				'tidak_berbau' =>
					'Tidak Berbau',

				'tidak_ada_produk_non_halal' =>
					'Tidak ada produk Non Halal',

				'tidak_ada_aktivitas_binatang' =>
					'Tidak ada Aktivitas Binatang'
			];

			$result = [];

			foreach ($data as $key => $item) {

				$label = isset($labels[$key])
					? $labels[$key]
					: $key;

				$result[] = [
					'key'   => $key,
					'label' => $label,
					'value' => (
						$item === null ||
						$item === ''
					)
						? '-'
						: $item
				];
			}

			return $result;
		}

		/*
		| Format lama / string biasa
		*/
		if (is_string($data)) {

			$parts = array_map(
				'trim',
				explode(',', $data)
			);

			$result = [];

			foreach ($parts as $part) {

				if ($part === '') {
					continue;
				}

				$result[] = [
					'key'   => $part,
					'label' => $part,
					'value' => $part
				];
			}

			return !empty($result)
				? $result
				: '-';
		}

		return $data;
	}


	/**
	 * ============================================================
	 * SENSORI FINISHED GOOD
	 * ============================================================
	 */
	private function formatSensoriProduk($value)
	{
		$data = $this->decodeJsonArray($value);

		if (empty($data)) {
			return [];
		}

		$result = [];

		foreach ($data as $item) {

			if (!is_array($item)) {
				continue;
			}

			$result[] = [
				'kode_produksi' =>
					isset($item['kode_produksi'])
						? $item['kode_produksi']
						: '-',

				'best_before' =>
					isset($item['best_before'])
						? $this->formatDateValue(
							$item['best_before']
						)
						: '-',

				'warna' =>
					$this->formatSensoriCheck(
						isset($item['warna'])
							? $item['warna']
							: null
					),

				'tekstur' =>
					$this->formatSensoriCheck(
						isset($item['tekstur'])
							? $item['tekstur']
							: null
					),

				'rasa' =>
					$this->formatSensoriCheck(
						isset($item['rasa'])
							? $item['rasa']
							: null
					),

				'aroma' =>
					$this->formatSensoriCheck(
						isset($item['aroma'])
							? $item['aroma']
							: null
					),

				'kenampakan' =>
					$this->formatSensoriCheck(
						isset($item['kenampakan'])
							? $item['kenampakan']
							: null
					)
			];
		}

		return $result;
	}


	/**
	 * ============================================================
	 * SENSORI CHECK
	 * ============================================================
	 */
	private function formatSensoriCheck($value)
	{
		if ($value === null || $value === '') {
			return '-';
		}

		$normalized = strtolower(
			trim((string) $value)
		);

		if ($normalized === 'ok') {
			return '✔';
		}

		if (
			$normalized === 'tidak ok' ||
			$normalized === 'tidak_ok'
		) {
			return '✘';
		}

		return $value;
	}


	/**
	 * ============================================================
	 * LOADING PRODUK
	 * ============================================================
	 */
	private function formatLoadingProduk($value)
	{
		$data = $this->decodeJsonArray($value);

		if (empty($data)) {
			return [];
		}

		$result = [];

		foreach ($data as $item) {

			if (!is_array($item)) {
				continue;
			}

			$result[] = [
				'nama_produk' =>
					isset($item['nama_produk'])
						? $item['nama_produk']
						: '-',

				'kondisi_produk' =>
					isset($item['kondisi_produk'])
						? $item['kondisi_produk']
						: '-',

				'kondisi_kemasan' =>
					isset($item['kondisi_kemasan'])
						? $item['kondisi_kemasan']
						: '-',

				'kode_produksi' =>
					isset($item['kode_produksi'])
						? $item['kode_produksi']
						: '-',

				'expired' =>
					isset($item['expired'])
						? $this->formatDateValue(
							$item['expired']
						)
						: '-',

				'keterangan' =>
					isset($item['keterangan']) &&
					$item['keterangan'] !== ''
						? $item['keterangan']
						: '-'
			];
		}

		return $result;
	}


	/**
	 * ============================================================
	 * PREMIX
	 * ============================================================
	 */
	private function formatPremix($value)
	{
		$data = $this->decodeJsonArray($value);

		if (empty($data)) {
			return [];
		}

		$result = [];

		foreach ($data as $item) {

			if (!is_array($item)) {
				continue;
			}

			$result[] = [
				'kode' =>
					isset($item['kode'])
						? $item['kode']
						: '-',

				'berat' =>
					isset($item['berat'])
						? $item['berat']
						: '-',

				'sens' =>
					isset($item['sens'])
						? $item['sens']
						: '-'
			];
		}

		return $result;
	}


	/**
	 * ============================================================
	 * JSON DATA
	 * ============================================================
	 */
	private function formatJsonData($value)
	{
		$data = $this->decodeJsonArray($value);

		if ($data === null) {
			return $value;
		}

		return $data;
	}


	/**
	 * ============================================================
	 * EVIDENCE IMAGE
	 * ============================================================
	 */
	private function formatEvidenceImage($value, $module)
	{
		if (empty($value)) {
			return [
				'exists' => false,
				'url'    => null,
				'name'   => null
			];
		}

		$file = trim((string) $value);

		/*
		| Lokasi sesuai view asli.
		*/
		$candidates = [];

		if ($module === 'magnettrap') {

			$candidates[] = FCPATH . 'uploads/' . $file;
			$candidates[] = FCPATH . 'uploads/magnettrap/' . $file;

		} elseif ($module === 'kontaminasi') {

			$candidates[] = FCPATH . 'uploads/' . $file;
			$candidates[] = FCPATH . 'uploads/kontaminasi/' . $file;
		}

		$found = false;
		$url = null;

		foreach ($candidates as $path) {

			if (file_exists($path)) {

				$found = true;

				if ($module === 'magnettrap') {

					if (strpos($path, FCPATH . 'uploads/magnettrap/') === 0) {

						$url = base_url(
							'uploads/magnettrap/' . $file
						);

					} else {

						$url = base_url(
							'uploads/' . $file
						);
					}

				} elseif ($module === 'kontaminasi') {

					if (strpos($path, FCPATH . 'uploads/kontaminasi/') === 0) {

						$url = base_url(
							'uploads/kontaminasi/' . $file
						);

					} else {

						$url = base_url(
							'uploads/' . $file
						);
					}
				}

				break;
			}
		}

		return [
			'exists' => $found,
			'url'    => $url,
			'name'   => $file
		];
	}


	/**
	 * ============================================================
	 * UPLOAD FILE / IMAGE
	 * ============================================================
	 */
	private function formatUploadFile($value, $type = 'file')
	{
		if (empty($value)) {

			return [
				'exists' => false,
				'url'    => null,
				'name'   => null
			];
		}

		$file = trim((string) $value);

		$path = FCPATH . 'uploads/' . $file;

		return [
			'exists' => file_exists($path),
			'url'    => base_url(
				'uploads/' . $file
			),
			'name'   => $file,
			'type'   => $type
		];
	}


	/**
	 * ============================================================
	 * PLAIN VALUE
	 * ============================================================
	 */
	private function formatPlainValue($value)
	{
		if ($value === null || $value === '') {
			return '-';
		}

		return $value;
	}


	/**
	 * ============================================================
	 * EMPTY TEXT
	 * ============================================================
	 */
	private function formatEmptyText(
		$value,
		$emptyText = '-'
	) {
		if (
			$value === null ||
			trim((string) $value) === ''
		) {
			return $emptyText;
		}

		return $value;
	}


	/**
	 * ============================================================
	 * TRACEABILITY VALUE
	 * ============================================================
	 */
	private function formatTraceabilityValue($value)
	{
		if ($value === null || $value === '') {
			return '-';
		}

		/*
		| Array / object
		*/
		if (
			is_array($value) ||
			is_object($value)
		) {
			return $value;
		}

		/*
		| JSON string
		*/
		if (is_string($value)) {

			$decoded = json_decode(
				$value,
				true
			);

			if (
				json_last_error() ===
				JSON_ERROR_NONE
			) {
				return $decoded;
			}
		}

		return $value;
	}


	/**
	 * ============================================================
	 * DECODE JSON ARRAY
	 * ============================================================
	 */
	private function decodeJsonArray($value)
	{
		if (is_array($value)) {
			return $value;
		}

		if (
			$value === null ||
			$value === ''
		) {
			return [];
		}

		$decoded = json_decode(
			$value,
			true
		);

		if (
			json_last_error() !==
			JSON_ERROR_NONE
		) {
			return [];
		}

		return is_array($decoded)
			? $decoded
			: [];
	}


	/**
	 * ============================================================
	 * PLANT FILTER
	 * ============================================================
	 */
	private function applyPlantFilter($cfg)
	{
		$plantColumn = isset($cfg['plant_column'])
			? $cfg['plant_column']
			: null;

		$plant = $this->session->userdata('plant');

		if (
			empty($plant) ||
			empty($plantColumn) ||
			!$this->isSafeIdentifier($plantColumn)
		) {
			return;
		}

		$table = $this->safeTable($cfg);

		if (!$table) {
			return;
		}

		$this->db->where(
			$table . '.' . $plantColumn,
			$plant
		);
	}


	/**
	 * ============================================================
	 * DATE FILTER
	 * ============================================================
	 */
	private function applyDateFilter($cfg, $date)
	{
		if (empty($date)) {
			return;
		}

		$dateObject = DateTime::createFromFormat(
			'Y-m-d',
			$date
		);

		if (
			!$dateObject ||
			$dateObject->format('Y-m-d') !== $date
		) {
			return;
		}

		$dateColumn = $this->getDateColumn($cfg);

		if (
			empty($dateColumn) ||
			!$this->isSafeIdentifier($dateColumn)
		) {
			return;
		}

		$table = $this->safeTable($cfg);

		if (!$table) {
			return;
		}

		$startDate = $date . ' 00:00:00';

		$endDate = $dateObject
			->modify('+1 day')
			->format('Y-m-d') . ' 00:00:00';

		$this->db->where(
			$table . '.' . $dateColumn . ' >=',
			$startDate
		);

		$this->db->where(
			$table . '.' . $dateColumn . ' <',
			$endDate
		);
	}


	/**
	 * ============================================================
	 * ROUTE KEY
	 * ============================================================
	 */
	private function getRouteKey($cfg)
	{
		return !empty($cfg['route_key_column'])
			? $cfg['route_key_column']
			: 'uuid';
	}


	/**
	 * ============================================================
	 * DATE COLUMN
	 * ============================================================
	 */
	private function getDateColumn($cfg)
	{
		return !empty($cfg['date_column'])
			? $cfg['date_column']
			: null;
	}


	/**
	 * ============================================================
	 * SAFE TABLE
	 * ============================================================
	 */
	private function safeTable($cfg)
	{
		$table = isset($cfg['form_table'])
			? $cfg['form_table']
			: '';

		return $this->isSafeIdentifier($table)
			? $table
			: null;
	}


	/**
	 * ============================================================
	 * CONFIG COLUMN
	 * ============================================================
	 */
	private function hasColumnConfig($cfg, $column)
	{
		return isset($cfg[$column]) &&
			$cfg[$column] === true;
	}


	/**
	 * ============================================================
	 * SAFE IDENTIFIER
	 * ============================================================
	 */
	private function isSafeIdentifier($value)
	{
		return is_string($value) &&
			preg_match(
				'/^[A-Za-z0-9_]+$/',
				$value
			);
	}


	/**
	 * ============================================================
	 * JSON SEARCH
	 * ============================================================
	 */
	private function jsonContainsCondition(
		$table,
		$column,
		$token
	) {
		$needle = '%' . $token . '%';

		$escaped = $this->db->escape(
			$needle
		);

		return "`{$table}`.`{$column}` LIKE {$escaped}";
	}


	/**
	 * ============================================================
	 * TRACEABILITY VISIBLE COLUMN
	 * ============================================================
	 */
	private function isTraceabilityVisibleColumn($column)
	{
		$hidden = [
			'uuid',
			'id',
			'user_uuid',
			'plant',
			'plant_uuid',
			'created_at',
			'updated_at',
			'deleted_at'
		];

		return !in_array(
			$column,
			$hidden,
			true
		);
	}


	/**
	 * ============================================================
	 * SOFT DELETE
	 * ============================================================
	 */
	private function applySoftDeleteFilter($cfg)
	{
		$table = $this->safeTable($cfg);

		if (!$table) {
			return;
		}

		/*
		| Hanya diterapkan jika kolom deleted_at memang ada.
		*/
		if (
			!$this->hasColumn(
				$table,
				'deleted_at'
			)
		) {
			return;
		}

		$this->db->where(
			$table . '.deleted_at IS NULL',
			null,
			false
		);
	}


	/**
	 * ============================================================
	 * HAS COLUMN
	 * ============================================================
	 */
	private function hasColumn(
		$table,
		$column
	) {
		if (
			!$this->isSafeIdentifier($table) ||
			!$this->isSafeIdentifier($column)
		) {
			return false;
		}

		return $this->db->field_exists(
			$column,
			$table
		);
	}
}
