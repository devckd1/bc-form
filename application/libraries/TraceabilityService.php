<?php

defined('BASEPATH') or exit('No direct script access allowed');

class TraceabilityService
{
	protected $CI;
	protected $modules = [];

	public function __construct()
	{
		$this->CI = &get_instance();

		$this->CI->config->load('traceability');

		$this->modules = $this->CI->config->item(
			'traceability_modules'
		);

		if (!is_array($this->modules)) {
			$this->modules = [];
		}
	}


	/**
	 * Pecah keyword menjadi beberapa token.
	 *
	 * Contoh:
	 * "BC MIX 123"
	 *
	 * menjadi:
	 * [
	 *     "BC",
	 *     "MIX",
	 *     "123"
	 * ]
	 */
	public function tokenize($term)
	{
		$term = trim((string) $term);

		if ($term === '') {
			return [];
		}

		return preg_split(
			'/\s+/',
			$term,
			-1,
			PREG_SPLIT_NO_EMPTY
		);
	}


	/**
	 * Cari data Traceability ke seluruh module.
	 */
	public function traceByBatch(
		$searchTerm = '',
		$date = null
	) {
		$tokens = $this->tokenize($searchTerm);

		$results = [];

		foreach ($this->modules as $key => $cfg) {

			/*
             * =================================================
             * MODULE DATE ONLY
             * =================================================
             *
             * Contoh:
             * reagen
             *
             * Module date_only hanya dicari berdasarkan tanggal.
             */
			if (!empty($cfg['date_only'])) {

				/*
                 * Kalau tidak ada tanggal,
                 * jangan cari module ini.
                 */
				if (empty($date)) {
					continue;
				}

				/*
                 * Jangan kirim keyword ke module date_only.
                 */
				$moduleTokens = [];
			} else {

				/*
                 * Module normal menggunakan keyword.
                 */
				$moduleTokens = $tokens;
			}


			/*
             * =================================================
             * CARI DATA
             * =================================================
             */
			$matched = $this->matchDetails(
				$cfg,
				$moduleTokens,
				$date
			);

			if (empty($matched)) {
				continue;
			}


			/*
             * =================================================
             * GROUP BERDASARKAN FORM KEY
             * =================================================
             */
			$forms = [];

			foreach ($matched as $row) {

				$formKey = isset($row['form_key'])
					? $row['form_key']
					: null;

				if (
					$formKey === null ||
					$formKey === ''
				) {
					continue;
				}


				if (!isset($forms[$formKey])) {
					$forms[$formKey] = [
						'form_key' => $formKey,

						'kode_produksi' => isset($row['kode_produksi'])
							? $row['kode_produksi']
							: null,

						'nama_produk' => isset($row['nama_produk'])
							? $row['nama_produk']
							: (
								isset($row['nama_barang'])
								? $row['nama_barang']
								: null
							),

						'date' => isset($row['form_date'])
							? $row['form_date']
							: null,

						'shift' => isset($row['shift'])
							? $row['shift']
							: null,

						'pdf_url' => null,
						'match_count' => 0
					];
				}


				$forms[$formKey]['match_count']++;
			}


			if (empty($forms)) {
				continue;
			}


			/*
             * =================================================
             * HASIL MODULE
             * =================================================
             */
			$results[] = [
				'module' => $key,

				'label' => isset($cfg['label'])
					? $cfg['label']
					: $key,

				'forms' => array_values($forms)
			];
		}


		return $results;
	}


	/**
	 * Jalankan pencarian ke model.
	 */
	protected function matchDetails(
		$cfg,
		$tokens,
		$date = null
	) {
		if (empty($cfg['detail_model'])) {
			return [];
		}


		$modelName = $cfg['detail_model'];

		$this->CI->load->model($modelName);

		$model = $this->CI->$modelName;


		if (!method_exists(
			$model,
			'searchTraceability'
		)) {
			return [];
		}


		return $model->searchTraceability(
			$tokens,
			$date,
			$cfg
		);
	}


	/**
	 * Ambil detail satu form.
	 *
	 * Dipanggil melalui AJAX:
	 *
	 * traceability/form-details
	 */
	public function getFormDetails(
		$moduleKey,
		$formKey,
		$searchTerm = '',
		$date = null
	) {

		/*
         * =================================================
         * VALIDASI MODULE
         * =================================================
         */
		if (!isset($this->modules[$moduleKey])) {
			return [
				'details' => []
			];
		}


		$cfg = $this->modules[$moduleKey];


		/*
         * =================================================
         * TOKEN
         * =================================================
         */
		$tokens = $this->tokenize(
			$searchTerm
		);


		/*
         * Module date-only tidak menggunakan keyword.
         */
		if (!empty($cfg['date_only'])) {
			$tokens = [];
		}


		/*
         * =================================================
         * MODEL
         * =================================================
         */
		if (empty($cfg['detail_model'])) {
			return [
				'details' => []
			];
		}


		$modelName = $cfg['detail_model'];

		$this->CI->load->model($modelName);

		$model = $this->CI->$modelName;


		/*
         * =================================================
         * VALIDASI METHOD
         * =================================================
         */
		if (!method_exists(
			$model,
			'getTraceabilityDetails'
		)) {
			return [
				'details' => []
			];
		}


		/*
         * =================================================
         * AMBIL DETAIL
         * =================================================
         */
		$details = $model->getTraceabilityDetails(
			$formKey,
			$tokens,
			$date,
			$cfg
		);


		return [
			'details' => $details
		];
	}
}
