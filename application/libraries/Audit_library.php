<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Ramsey\Uuid\Uuid;

class Audit_library
{
	protected $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	/**
	 * Copy satu record operasional menjadi record Audit.
	 *
	 * @param string $table
	 * @param string $uuid
	 * @return string|false
	 */
	public function copy_to_audit($table, $uuid)
	{
		$source = $this->CI->db
			->where('uuid', $uuid)
			->where('is_audit', 0)
			->get($table)
			->row_array();

		if (!$source) {
			return false;
		}

		// Pastikan plant sesuai dengan plant aktif
		$plant = $this->CI->session->userdata('plant');

		if (
			isset($source['plant']) &&
			$source['plant'] !== $plant
		) {
			return false;
		}

		// Cegah satu data dibuat menjadi Audit berkali-kali
		$existing = $this->CI->db
			->where('source_uuid', $uuid)
			->where('is_audit', 1)
			->get($table)
			->row();

		if ($existing) {
			return $existing->uuid;
		}

		// UUID baru untuk record Audit
		$audit_uuid = Uuid::uuid4()->toString();

		$source['uuid'] = $audit_uuid;
		$source['is_audit'] = 1;
		$source['source_uuid'] = $uuid;

		// ID adalah AUTO_INCREMENT
		unset($source['id']);

		/*
     * Reset status approval/verifikasi.
     */
		if ($table === 'seasoning') {

			if (array_key_exists('status_spv', $source)) {
				$source['status_spv'] = 0;
			}

			if (array_key_exists('nama_spv', $source)) {
				$source['nama_spv'] = '';
			}

			if (array_key_exists('catatan_spv', $source)) {
				$source['catatan_spv'] = '';
			}

			/*
         * tgl_update_spv tidak diubah.
         * Kolom tersebut NOT NULL pada tabel seasoning.
         */
		}

		/*
     * Record Audit dibuat pada waktu sekarang.
     */
		if (array_key_exists('created_at', $source)) {
			$source['created_at'] = date('Y-m-d H:i:s');
		}

		if (array_key_exists('modified_at', $source)) {
			$source['modified_at'] = date('Y-m-d H:i:s');
		}

		$insert = $this->CI->db->insert($table, $source);

		if (!$insert) {
			return false;
		}

		return $audit_uuid;
	}
}
