<?php

/*
|--------------------------------------------------------------------------
| MASTER CHILLER
|--------------------------------------------------------------------------
*/

$master_chiller_1 = $master_chiller['Chiller 1'] ?? null;
$master_chiller_2 = $master_chiller['Chiller 2'] ?? null;
$master_chiller_3 = $master_chiller['Chiller 3'] ?? null;
$master_chiller_4 = $master_chiller['Chiller 4'] ?? null;


/*
|--------------------------------------------------------------------------
| FUNGSI NILAI AUDIT
|--------------------------------------------------------------------------
|
| 1. Data asli ada + Master ada
|    -> nilai tengah Master
|
| 2. Data asli ada + Master tidak ada
|    -> nilai asli
|
| 3. Data asli kosong
|    -> -
|
*/

function nilai_detail_audit_chiller($nilai_asli, $master)
{
	// Kondisi 3
	// Data asli kosong
	if ($nilai_asli === null || $nilai_asli === '') {
		return '-';
	}

	// Kondisi 2
	// Data asli ada + Master tidak ada
	if (!$master || empty($master->range_standar)) {
		return $nilai_asli;
	}

	/*
     * Ambil angka dari range Master.
     *
     * Contoh:
     * 0 - 4
     * 0-4
     * 0 sampai 4
     */
	preg_match_all(
		'/-?\d+(?:[.,]\d+)?/',
		$master->range_standar,
		$matches
	);

	// Range tidak valid
	if (count($matches[0]) < 2) {
		return $nilai_asli;
	}

	$min = (float) str_replace(',', '.', $matches[0][0]);
	$max = (float) str_replace(',', '.', $matches[0][1]);

	// Kondisi 1
	// Nilai tengah Master
	$nilai_tengah = ($min + $max) / 2;

	// Contoh 2.0 menjadi 2
	if (floor($nilai_tengah) == $nilai_tengah) {
		return (int) $nilai_tengah;
	}

	return $nilai_tengah;
}

?>


<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-3 text-gray-800 font-weight-bold">

		Detail Audit Pemeriksaan Suhu Chiller

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>

	</h1>


	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">

			<li class="breadcrumb-item">

				<a class="text-white"
					href="<?= base_url('chiller/audit'); ?>">

					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Chiller \ Detail

				</a>

			</li>

		</ol>
	</nav>


	<!-- Informasi Audit -->
	<div class="alert alert-audit">

		<i class="fas fa-info-circle mr-1"></i>

		<strong>Informasi:</strong>

		Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan untuk keperluan pemeriksaan serta verifikasi.
		Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.

	</div>


	<div class="card shadow mb-4 card-audit">

		<div class="card-body">

			<div class="table-responsive">

				<table class="table table-bordered" cellspacing="0">

					<?php
					$formattedDate = '-';

					if (!empty($chiller->date)) {
						$datetime = new DateTime($chiller->date);
						$formattedDate = $datetime->format('d-m-Y');
					}
					?>


					<thead class="text-center">

						<tr>

							<th colspan="7"
								class="text-center font-weight-bold">

								PEMERIKSAAN SUHU CHILLER

							</th>

						</tr>

					</thead>


					<tbody>

						<!-- Tanggal dan Waktu -->
						<tr>

							<td colspan="2">
								<b>Tanggal:</b>
								<?= $formattedDate; ?>
							</td>

							<td>
								<b>Pukul:</b>

								<?= !empty($chiller->waktu)
									? date('H:i', strtotime($chiller->waktu))
									: '-'; ?>
							</td>

							<td colspan="4"></td>

						</tr>


						<!-- Hasil Pemeriksaan -->
						<tr class="bg-light text-center">

							<td colspan="7"
								class="font-weight-bold">

								Hasil Pemeriksaan Audit

							</td>

						</tr>


						<tr class="table-primary text-center">

							<th colspan="3">
								Chiller
							</th>

							<th colspan="4">
								Suhu (°C)
							</th>

						</tr>


						<!-- Chiller 1 -->
						<tr class="text-center">

							<td colspan="3">

								<b>Chiller No. 1</b>

							</td>

							<td colspan="4">

								<?= nilai_detail_audit_chiller(
									$chiller->chiller_1,
									$master_chiller_1
								); ?>

								°C

							</td>

						</tr>


						<!-- Chiller 2 -->
						<tr class="text-center">

							<td colspan="3">

								<b>Chiller No. 2</b>

							</td>

							<td colspan="4">

								<?= nilai_detail_audit_chiller(
									$chiller->chiller_2,
									$master_chiller_2
								); ?>

								°C

							</td>

						</tr>


						<!-- Chiller 3 -->
						<tr class="text-center">

							<td colspan="3">

								<b>Chiller No. 3</b>

							</td>

							<td colspan="4">

								<?= nilai_detail_audit_chiller(
									$chiller->chiller_3,
									$master_chiller_3
								); ?>

								°C

							</td>

						</tr>


						<!-- Chiller 4 -->
						<tr class="text-center">

							<td colspan="3">

								<b>Chiller No. 4</b>

							</td>

							<td colspan="4">

								<?= nilai_detail_audit_chiller(
									$chiller->chiller_4,
									$master_chiller_4
								); ?>

								°C

							</td>

						</tr>


						<!-- Catatan -->
						<tr class="bg-light">

							<td>
								<b>Catatan</b>
							</td>

							<td colspan="6">

								<?= !empty($chiller->catatan)
									? $chiller->catatan
									: 'Tidak ada'; ?>

							</td>

						</tr>


						<!-- Verifikasi -->
						<tr class="table-primary text-center">

							<th colspan="7">
								VERIFIKASI
							</th>

						</tr>


						<!-- QC -->
						<tr>

							<td>
								<b>QC</b>
							</td>

							<td colspan="6">

								<?= !empty($chiller->username)
									? $chiller->username
									: '-'; ?>

							</td>

						</tr>


						<!-- Produksi -->
						<tr>

							<td>
								<b>Produksi</b>
							</td>

							<td colspan="6">

								<?= !empty($chiller->nama_produksi)
									? $chiller->nama_produksi
									: 'Belum dikoreksi'; ?>

							</td>

						</tr>


						<!-- Status Supervisor -->
						<tr>

							<td>
								<b>Status Supervisor</b>
							</td>

							<td colspan="6">

								<?php

								switch ($chiller->status_spv) {

									case 1:

										echo '<span class="text-success font-weight-bold">
                                                Verified
                                              </span>';

										break;


									case 2:

										echo '<span class="text-danger font-weight-bold">
                                                Revision
                                              </span>';

										break;


									default:

										echo '<span class="text-secondary font-weight-bold">
                                                Created
                                              </span>';

										break;
								}

								?>

							</td>

						</tr>


						<!-- Catatan Supervisor -->
						<tr>

							<td>
								<b>Catatan Supervisor</b>
							</td>

							<td colspan="6">

								<?= !empty($chiller->catatan_spv)
									? $chiller->catatan_spv
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
		padding: 8px 16px;
		border-radius: 0.25rem;

	}


	.breadcrumb .breadcrumb-item a {

		color: #fff !important;
		font-weight: 500;

	}


	.breadcrumb .breadcrumb-item a:hover {

		text-decoration: underline;

	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}


	.table {

		width: 100%;
		font-size: 15px;

	}


	.table td,
	.table th {

		padding: 10px 12px;
		vertical-align: middle;
		word-break: break-word;

	}


	.badge-mode-audit {

		display: inline-block;

		background-color: #5a3298;

		color: #fff;

		padding: 6px 12px;

		border-radius: 6px;

		font-size: 12px;

		font-weight: 700;

		vertical-align: middle;

		margin-left: 8px;

		box-shadow:
			0 2px 5px rgba(90, 50, 152, 0.25);

	}


	.audit-title {

		display: inline-block;

		margin-left: 8px;

		padding: 4px 8px;

		background-color: #5a3298;

		color: #fff;

		border-radius: 4px;

		font-size: 11px;

		font-weight: bold;

	}


	.alert-audit {

		background-color: #f3e8ff;

		border-left: 4px solid #6f42c1;

		color: #4b2a75;

	}


	@media (max-width: 768px) {

		.table td,
		.table th {

			font-size: 14px;
			padding: 8px;

		}


		h1.h3 {

			font-size: 20px;

		}

	}
</style>
