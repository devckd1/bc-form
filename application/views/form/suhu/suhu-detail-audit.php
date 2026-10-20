<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		Detail Audit Pemeriksaan Suhu Ruang

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white"
					href="<?= base_url('suhu/audit'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Suhu Ruang \ Detail
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi Audit:</strong>
		Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan untuk keperluan pemeriksaan serta verifikasi.
		Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<div class="card shadow mb-4 card-audit">
		<div class="card-body">

			<div class="table-responsive">

				<table class="table table-bordered" cellspacing="0">

					<thead class="text-center">

						<?php
						$datetime = (new DateTime($suhu->date))->format('d-m-Y');
						?>

						<tr>
							<th colspan="7" class="text-center font-weight-bold">
								PEMERIKSAAN SUHU RUANG - AUDIT
							</th>
						</tr>

					</thead>

					<tbody>

						<tr>
							<td colspan="2">
								<b>Tanggal:</b>
								<?= $datetime; ?>
							</td>

							<td>
								<b>Shift:</b>
								<?= $suhu->shift; ?>
							</td>

							<td colspan="4">
								<b>Pukul:</b>
								<?= date('H:i', strtotime($suhu->pukul)); ?>
							</td>
						</tr>

						<tr class="bg-light">
							<td colspan="7" class="font-weight-bold text-center">
								Hasil Pemeriksaan Audit
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th>Lokasi</th>
							<th colspan="3">Suhu (°C)</th>
							<th colspan="3">RH (%)</th>
						</tr>

						<?php

						/*
         * ============================================================
         * PLANT AKTIF
         * ============================================================
         */

						$plant_uuid = $this->session->userdata('plant');


						/*
         * ============================================================
         * AMBIL MASTER SUHU SESUAI PLANT AKTIF
         * ============================================================
         */

						$master_suhu = $this->db
							->where('plant', $plant_uuid)
							->where('is_active', 1)
							->get('master_suhu')
							->result();


						/*
         * ============================================================
         * MAPPING MASTER BERDASARKAN NAMA LOKASI
         * ============================================================
         */

						$master_map = [];

						foreach ($master_suhu as $master) {

							$master_map[$master->nama_lokasi] = $master;
						}


						/*
         * ============================================================
         * FUNGSI NILAI TENGAH STANDAR
         *
         * Contoh:
         * 25 - 35 = 30
         * 65 - 80 = 72.5
         * ============================================================
         */

						$nilai_tengah = function ($range) {

							if ($range === null || trim($range) === '') {
								return '';
							}

							$range = trim($range);


							// Hilangkan satuan
							$range = str_replace(
								['°C', '%'],
								'',
								$range
							);


							// Rapikan spasi
							$range = preg_replace('/\s+/', ' ', $range);

							$range = trim($range);


							/*
             * Jika berupa range
             * Contoh:
             * 25 - 35
             * 65-80
             */

							if (preg_match(
								'/^(-?\d+(?:\.\d+)?)\s*-\s*(-?\d+(?:\.\d+)?)$/',
								$range,
								$match
							)) {

								$min = (float) $match[1];

								$max = (float) $match[2];

								return ($min + $max) / 2;
							}


							/*
             * Jika Master berisi angka langsung
             */

							if (is_numeric($range)) {

								return (float) $range;
							}


							return '';
						};


						/*
         * ============================================================
         * FUNGSI CEK NILAI TERHADAP STANDAR
         *
         * Return:
         * true  = sesuai standar
         * false = tidak sesuai
         * ============================================================
         */

						$sesuai_standar = function ($nilai, $range) {

							if (
								$nilai === null ||
								$nilai === '' ||
								$range === null ||
								trim($range) === ''
							) {

								return false;
							}


							$nilai = (float) $nilai;


							$range = trim($range);


							// Hilangkan satuan
							$range = str_replace(
								['°C', '%'],
								'',
								$range
							);


							// Rapikan spasi
							$range = preg_replace('/\s+/', ' ', $range);

							$range = trim($range);


							/*
             * Jika berupa range
             */

							if (preg_match(
								'/^(-?\d+(?:\.\d+)?)\s*-\s*(-?\d+(?:\.\d+)?)$/',
								$range,
								$match
							)) {

								$min = (float) $match[1];

								$max = (float) $match[2];


								return (
									$nilai >= $min &&
									$nilai <= $max
								);
							}


							/*
             * Jika Master berupa angka langsung
             */

							if (is_numeric($range)) {

								return $nilai == (float) $range;
							}


							return false;
						};


						/*
						* ============================================================
						* AMBIL DATA LOKASI DARI DATA AUDIT
						* ============================================================
						*/

						$lokasi_data = json_decode(
							$suhu->lokasi,
							true
						);

						?>


						<?php if (!empty($lokasi_data)): ?>


							<?php foreach ($lokasi_data as $row): ?>


								<?php

								/*
								* ====================================================
								* NAMA LOKASI
								* ====================================================
								*/

								$nama_lokasi = $row['nama_lokasi'] ?? '';


								/*
								* Ambil Master berdasarkan lokasi
								*/

								$master = $master_map[$nama_lokasi] ?? null;


												/*
								* ====================================================
								* SUHU
								* ====================================================
								*/

								$nilai_suhu = $row['suhu'] ?? '';

								/*
								* KONDISI:
								* 1. Data asli ada + Master ada
								*    → gunakan nilai tengah Master
								*
								* 2. Data asli ada + Master tidak ada
								*    → gunakan nilai asli
								*
								* 3. Data asli kosong
								*    → tetap kosong
								*/

								if (
									$nilai_suhu !== null &&
									trim((string) $nilai_suhu) !== ''
								) {

									if (
										$master &&
										isset($master->standar_suhu) &&
										trim((string) $master->standar_suhu) !== ''
									) {

										$nilai_suhu = $nilai_tengah(
											$master->standar_suhu
										);
									} else {

										// Tidak ada Master → pertahankan nilai asli
										$nilai_suhu = $row['suhu'];
									}
								}


								/*
								* ====================================================
								* RH
								* ====================================================
								*/

								$nilai_rh = $row['rh'] ?? '';

								/*
								* KONDISI:
								* 1. Data asli ada + Master ada
								*    → gunakan nilai tengah Master
								*
								* 2. Data asli ada + Master tidak ada
								*    → gunakan nilai asli
								*
								* 3. Data asli kosong
								*    → tetap kosong
								*/

								if (
									$nilai_rh !== null &&
									trim((string) $nilai_rh) !== ''
								) {

									if (
										$master &&
										isset($master->standar_rh) &&
										trim((string) $master->standar_rh) !== ''
									) {

										$nilai_rh = $nilai_tengah(
											$master->standar_rh
										);
									} else {

										// Tidak ada Master → pertahankan nilai asli
										$nilai_rh = $row['rh'];
									}
								}

								?>


								<tr>

									<!-- LOKASI -->

									<td>

										<?= html_escape(
											$nama_lokasi ?: '-'
										); ?>

									</td>


									<!-- SUHU -->

									<td colspan="3">

										<?= $nilai_suhu !== ''
											? html_escape($nilai_suhu)
											: '-'; ?>

									</td>


									<!-- RH -->

									<td colspan="3">

										<?= $nilai_rh !== ''
											? html_escape($nilai_rh)
											: '-'; ?>

									</td>

								</tr>


							<?php endforeach; ?>

						<?php else: ?>

							<tr>
								<td colspan="7" class="text-center">
									Tidak ada data pemeriksaan.
								</td>
							</tr>

						<?php endif; ?>

						<tr class="bg-light">

							<td>
								<b>Catatan</b>
							</td>

							<td colspan="6">
								<?= !empty($suhu->catatan)
									? html_escape($suhu->catatan)
									: 'Tidak ada'; ?>
							</td>

						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">
								VERIFIKASI AUDIT
							</th>
						</tr>

						<tr>
							<td>QC</td>

							<td colspan="6">
								<?= html_escape($suhu->username); ?>
							</td>
						</tr>

						<tr>
							<td>Produksi</td>

							<td colspan="6">
								<?= !empty($suhu->nama_produksi)
									? html_escape($suhu->nama_produksi)
									: 'Belum dikoreksi'; ?>
							</td>
						</tr>

						<tr>
							<td>Status Supervisor</td>

							<td colspan="6">

								<?php
								switch ($suhu->status_spv) {

									case 1:
										echo '<span class="text-success font-weight-bold">Verified</span>';
										break;

									case 2:
										echo '<span class="text-danger font-weight-bold">Revision</span>';
										break;

									default:
										echo '<span class="text-secondary font-weight-bold">Created</span>';
										break;
								}
								?>

							</td>
						</tr>

						<tr>
							<td>Catatan Supervisor</td>

							<td colspan="6">
								<?= !empty($suhu->catatan_spv)
									? html_escape($suhu->catatan_spv)
									: 'Tidak ada'; ?>
							</td>
						</tr>

					</tbody>

				</table>

			</div>

		</div>
	</div>

</div>
<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	.breadcrumb a {
		color: #fff !important;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	/* Label Mode Audit */
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 7px 14px;
		margin-left: 8px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 700;
		letter-spacing: .5px;
		vertical-align: middle;
		box-shadow: 0 3px 8px rgba(90, 50, 152, 0.35);
	}
</style>
