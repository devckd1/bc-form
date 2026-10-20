<?php
$result = json_decode($sanitasi->area, true);

if (!is_array($result)) {
	$result = [];
}
?>

<div class="container-fluid">

	<h1 class="h3 mb-3 text-gray-800 font-weight-bold">
		Detail Pemeriksaan Sanitasi

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('sanitasi/audit'); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					Daftar Audit Pemeriksaan Sanitasi \ Detail
				</a>
			</li>
		</ol>
	</nav>

	<!-- Info Audit -->
	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi Audit:</strong>
		Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan untuk keperluan pemeriksaan serta verifikasi.
		Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<!-- Detail Pemeriksaan -->
	<div class="card shadow mb-4 card-audit">

		<div class="card-body">

			<div class="table-responsive">

				<?php
				$datetime = (new DateTime($sanitasi->date))->format('d-m-Y');
				?>

				<table class="table table-bordered">

					<thead class="text-center">
						<tr>
							<th colspan="7"
								class="font-weight-bold">
								PEMERIKSAAN SANITASI
							</th>
						</tr>
					</thead>

					<tbody>

						<!-- Tanggal -->
						<tr>
							<td colspan="2">
								<strong>Tanggal</strong>
							</td>

							<td colspan="5">
								<?= $datetime; ?>
							</td>
						</tr>

						<!-- Shift -->
						<tr>
							<td colspan="2">
								<strong>Shift</strong>
							</td>

							<td colspan="5">
								<?= htmlspecialchars($sanitasi->shift); ?>
							</td>
						</tr>

						<!-- Pukul -->
						<tr>
							<td colspan="2">
								<strong>Pukul</strong>
							</td>

							<td colspan="5">
								<?= date('H:i', strtotime($sanitasi->waktu)); ?>
							</td>
						</tr>

						<!-- Hasil Pemeriksaan -->
						<tr class="text-center bg-light">
							<th colspan="7">
								DAFTAR HASIL PEMERIKSAAN AUDIT
							</th>
						</tr>

						<tr class="text-center table-primary">
							<th>Area</th>
							<th>Standar (ppm)</th>
							<th>Aktual</th>
							<th>Bukti</th>
							<th>Suhu Air</th>
							<th>Keterangan</th>
							<th>Tindakan</th>
						</tr>

						<?php foreach ($result as $row): ?>

							<?php
							$sub_area = trim($row['sub_area'] ?? '');

							$master = $master_sanitasi[$sub_area] ?? null;

							/*
							* STANDAR PPM
							*/
							$standar_asli = $row['standar'] ?? '';

							if ($standar_asli === null || $standar_asli === '') {
								$standar_tampil = '-';
							} elseif ($master && $master->standar_ppm !== '') {
								$standar_tampil = $master->standar_ppm;
							} else {
								$standar_tampil = $standar_asli;
							}

							/*
							* SUHU AIR
							*/
							$suhu_asli = $row['suhu_air'] ?? '';

							if ($suhu_asli === null || $suhu_asli === '') {

								$suhu_tampil = '-';
							} elseif ($master && !empty($master->standar_suhu)) {

								// Ambil angka dari range standar, contoh: 25 - 30
								$range = str_replace(',', '.', $master->standar_suhu);

								preg_match_all('/\d+(?:\.\d+)?/', $range, $matches);

								if (count($matches[0]) >= 2) {

									$min = (float) $matches[0][0];
									$max = (float) $matches[0][1];

									$nilai_tengah = ($min + $max) / 2;

									$suhu_tampil = ($nilai_tengah == (int) $nilai_tengah)
										? (int) $nilai_tengah
										: $nilai_tengah;
								} else {

									$suhu_tampil = $suhu_asli;
								}
							} else {

								$suhu_tampil = $suhu_asli;
							}
							?>

							<tr>

								<!-- Area -->
								<td>
									<?= htmlspecialchars($sub_area); ?>
								</td>

								<!-- Standar -->
								<td class="text-center font-weight-bold">
									<?= htmlspecialchars($standar_tampil); ?>
								</td>

								<!-- Aktual -->
								<td class="text-center">
									<?= htmlspecialchars($row['aktual'] ?? ''); ?>
								</td>

								<!-- Bukti -->
								<td class="text-center">

									<?php if (!empty($row['gambar'])): ?>

										<img
											src="<?= base_url('uploads/sanitasi/' . $row['gambar']); ?>"
											alt="Bukti"
											class="bukti-image">

									<?php else: ?>

										<span class="text-muted">
											Tidak ada
										</span>

									<?php endif; ?>

								</td>

								<!-- Suhu Air -->
								<td class="text-center font-weight-bold">
									<?= htmlspecialchars($suhu_tampil); ?>
								</td>

								<!-- Keterangan -->
								<td class="text-center">

									<?php if (!empty($row['keterangan'])): ?>

										<span class="badge badge-success">
											<?= htmlspecialchars($row['keterangan']); ?>
										</span>

									<?php else: ?>

										<span class="text-muted">
											-
										</span>

									<?php endif; ?>

								</td>

								<!-- Tindakan -->
								<td>
									<?= !empty($row['tindakan'])
										? htmlspecialchars($row['tindakan'])
										: '-'; ?>
								</td>

							</tr>

						<?php endforeach; ?>

						<!-- Catatan -->
						<tr class="bg-light">

							<td>
								<strong>Catatan</strong>
							</td>

							<td colspan="6">
								<?= !empty($sanitasi->catatan)
									? htmlspecialchars($sanitasi->catatan)
									: 'Tidak ada'; ?>
							</td>

						</tr>

						<!-- Verifikasi -->
						<tr class="text-center">

							<th colspan="7">
								VERIFIKASI
							</th>

						</tr>

						<!-- QC -->
						<tr>

							<td>
								<strong>QC</strong>
							</td>

							<td colspan="6">
								<?= htmlspecialchars($sanitasi->username); ?>
							</td>

						</tr>

						<!-- Produksi -->
						<tr>

							<td>
								<strong>Produksi</strong>
							</td>

							<td colspan="6">
								<?= !empty($sanitasi->nama_produksi)
									? htmlspecialchars($sanitasi->nama_produksi)
									: 'Belum dikoreksi'; ?>
							</td>

						</tr>

						<!-- Status SPV -->
						<tr>

							<td>
								<strong>Status Supervisor</strong>
							</td>

							<td colspan="6">

								<?php
								if ($sanitasi->status_spv == 0) {
									echo '<span class="badge badge-secondary">Created</span>';
								} elseif ($sanitasi->status_spv == 1) {
									echo '<span class="badge badge-success">Verified</span>';
								} elseif ($sanitasi->status_spv == 2) {
									echo '<span class="badge badge-danger">Revision</span>';
								}
								?>

							</td>

						</tr>

						<!-- Catatan SPV -->
						<tr>

							<td>
								<strong>Catatan Supervisor</strong>
							</td>

							<td colspan="6">
								<?= !empty($sanitasi->catatan_spv)
									? htmlspecialchars($sanitasi->catatan_spv)
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
	.badge-mode-audit {
		display: inline-block;
		background-color: #6f42c1;
		color: #fff;
		font-size: 12px;
		padding: 6px 10px;
		border-radius: 5px;
		vertical-align: middle;
		margin-left: 8px;
	}

	.breadcrumb {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb .breadcrumb-item a:hover {
		text-decoration: underline;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	.alert-audit {
		background-color: #f3e8ff;
		border-left: 4px solid #6f42c1;
		color: #4b2a75;
	}

	.header-audit {
		background-color: #6f42c1;
		color: #fff;
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

	.bukti-image {
		width: 100px;
		height: auto;
		border-radius: 4px;
		cursor: pointer;
	}

	@media (max-width: 768px) {

		.table td,
		.table th {
			font-size: 14px;
			padding: 8px;
		}

		.table img {
			width: 80px;
		}

		h1.h3 {
			font-size: 20px;
		}

		.badge-mode-audit {
			display: block;
			width: fit-content;
			margin: 8px auto 0;
		}

	}
</style>
