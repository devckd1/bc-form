<?php
$sanitasi_data = json_decode($sanitasi->area, true);

if (!is_array($sanitasi_data)) {
	echo "<div class='alert alert-danger'>Data area tidak valid.</div>";
	$sanitasi_data = [];
}

/*
|--------------------------------------------------------------------------
| Mapping Master Sanitasi berdasarkan Area
|--------------------------------------------------------------------------
*/
$master_by_area = [];

if (!empty($master_sanitasi)) {
	foreach ($master_sanitasi as $master) {
		$master_by_area[trim($master->area)] = $master;
	}
}
?>

<div class="container-fluid">

	<!-- Judul -->
	<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
		Edit Pemeriksaan Sanitasi

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
					Daftar Audit Pemeriksaan Sanitasi
				</a>
			</li>

			<li class="breadcrumb-item active" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<!-- Info Audit -->
	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>

		<strong>Informasi:</strong>

		Halaman ini merupakan menu Edit Audit Pemeriksaan Sanitasi.
		Perubahan data pada halaman ini tidak akan mengubah data aktual
		pada menu pemeriksaan.
	</div>

	<!-- Form -->
	<div class="card shadow mb-4 card-audit">

		<div class="card-body">

			<form method="post"
				action="<?= base_url('sanitasi/edit-audit/' . $sanitasi->uuid); ?>"
				enctype="multipart/form-data">

				<!-- Tanggal, Shift, Pukul -->
				<div class="form-group row">

					<div class="col-md-3">
						<label class="font-weight-bold">
							Tanggal
						</label>

						<input type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= htmlspecialchars($sanitasi->date); ?>">

						<div class="invalid-feedback">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-md-3">
						<label class="font-weight-bold">
							Shift
						</label>

						<select name="shift"
							class="form-control <?= form_error('shift') ? 'is-invalid' : ''; ?>">

							<option value="1"
								<?= $sanitasi->shift == '1' ? 'selected' : ''; ?>>
								Shift 1
							</option>

							<option value="2"
								<?= $sanitasi->shift == '2' ? 'selected' : ''; ?>>
								Shift 2
							</option>

							<option value="3"
								<?= $sanitasi->shift == '3' ? 'selected' : ''; ?>>
								Shift 3
							</option>

						</select>

						<div class="invalid-feedback">
							<?= form_error('shift'); ?>
						</div>
					</div>

					<div class="col-md-3">
						<label class="font-weight-bold">
							Pukul
						</label>

						<input type="time"
							name="waktu"
							class="form-control <?= form_error('waktu') ? 'is-invalid' : ''; ?>"
							value="<?= htmlspecialchars($sanitasi->waktu); ?>">

						<div class="invalid-feedback">
							<?= form_error('waktu'); ?>
						</div>
					</div>

				</div>

				<hr>

				<label class="font-weight-bold">
					Hasil Pemeriksaan Audit
				</label>

				<!-- Tabel -->
				<div class="table-responsive">

					<table class="table table-bordered small">

						<thead class="thead-light text-center">
							<tr>
								<th>Area</th>
								<th>Standar (ppm)</th>
								<th>Aktual</th>
								<th>Suhu Air</th>
								<th>Keterangan</th>
								<th>Tindakan</th>
								<th>Gambar</th>
							</tr>
						</thead>

						<tbody>

							<?php foreach ($sanitasi_data as $i => $detail): ?>

								<?php
								$sub_area = trim($detail['sub_area'] ?? '');

								/*
	 * Ambil Master berdasarkan area.
	 */
								$master = $master_by_area[$sub_area] ?? null;

								/*
	 * Standar PPM:
	 * Jika Master ada → gunakan Master.
	 * Jika Master tidak ada → gunakan data asli.
	 */
								$standar_ppm = $detail['standar'] ?? '';

								if ($master && $master->standar_ppm !== '') {
									$standar_ppm = $master->standar_ppm;
								}

								/*
	 * Suhu Air:
	 * Jika data asli kosong → tetap kosong.
	 * Jika Master ada → ambil nilai tengah standar_suhu.
	 * Jika Master tidak ada → gunakan data asli.
	 */
								$suhu_asli = $detail['suhu_air'] ?? '';

								if ($suhu_asli === null || $suhu_asli === '') {

									$suhu_air = '';
								} elseif ($master && !empty($master->standar_suhu)) {

									$range_suhu = trim(str_replace(',', '.', $master->standar_suhu));

									/*
		 * Contoh:
		 * 25 - 30 → 27.5
		 * 20 - 30 → 25
		 * 0 - 4   → 2
		 */
									if (preg_match(
										'/^\s*(-?\d+(?:\.\d+)?)\s*(?:-|–|—|s\/d|to)\s*(-?\d+(?:\.\d+)?)\s*$/i',
										$range_suhu,
										$matches
									)) {

										$min = (float) $matches[1];
										$max = (float) $matches[2];

										$nilai_tengah = ($min + $max) / 2;

										$suhu_air = ($nilai_tengah == (int) $nilai_tengah)
											? (int) $nilai_tengah
											: $nilai_tengah;
									} else {

										$suhu_air = $suhu_asli;
									}
								} else {

									$suhu_air = $suhu_asli;
								}

								/*
	 * Keterangan tidak diedit manual.
	 */
								$keterangan = $detail['keterangan'] ?? '';
								?>

								<tr>

									<!-- AREA -->
									<td>
										<input type="text"
											class="form-control"
											name="sub_area[]"
											value="<?= htmlspecialchars($sub_area); ?>"
											>
									</td>

									<!-- STANDAR PPM -->
									<td>
										<input type="text"
											class="form-control"
											name="standar[]"
											value="<?= htmlspecialchars($standar_ppm); ?>"
											>
									</td>

									<!-- AKTUAL -->
									<td>
										<input type="text"
											class="form-control"
											name="aktual[]"
											value="<?= htmlspecialchars($detail['aktual'] ?? ''); ?>">
									</td>

									<!-- SUHU AIR -->
									<td>
										<input type="text"
											class="form-control"
											name="suhu_air[]"
											value="<?= htmlspecialchars($suhu_air); ?>"
											>
									</td>

									<!-- KETERANGAN -->
									<td>

										<input type="text"
											class="form-control"
											value="<?= htmlspecialchars($keterangan); ?>"
											>

										<input type="hidden"
											name="keterangan[]"
											value="<?= htmlspecialchars($keterangan); ?>">

									</td>

									<!-- TINDAKAN -->
									<td>
										<input type="text"
											class="form-control"
											name="tindakan[]"
											value="<?= htmlspecialchars($detail['tindakan'] ?? ''); ?>">
									</td>

									<!-- GAMBAR -->
									<td>

										<input type="file"
											class="form-control-file"
											name="gambar[]">

										<?php if (!empty($detail['gambar'])): ?>

											<small class="d-block mt-1">

												<a href="<?= base_url('uploads/sanitasi/' . $detail['gambar']); ?>"
													target="_blank">

													<i class="fas fa-image mr-1"></i>
													Lihat Gambar Sebelumnya

												</a>

											</small>

											<input type="hidden"
												name="gambar_lama[]"
												value="<?= htmlspecialchars($detail['gambar']); ?>">

										<?php else: ?>

											<input type="hidden"
												name="gambar_lama[]"
												value="">

										<?php endif; ?>

									</td>

								</tr>

							<?php endforeach; ?>

						</tbody>

					</table>

				</div>

				<!-- Catatan -->
				<div class="form-group">

					<label class="font-weight-bold">
						Catatan
					</label>

					<textarea name="catatan"
						class="form-control <?= form_error('catatan') ? 'is-invalid' : ''; ?>"
						rows="3"><?= htmlspecialchars($sanitasi->catatan); ?></textarea>

					<div class="invalid-feedback">
						<?= form_error('catatan'); ?>
					</div>

				</div>

				<!-- Tombol -->
				<div class="form-group">

					<button type="submit"
						class="btn btn-success">

						<i class="fas fa-save mr-1"></i>
						Simpan

					</button>

					<a href="<?= base_url('sanitasi/audit'); ?>"
						class="btn btn-danger">

						<i class="fas fa-times mr-1"></i>
						Batal

					</a>

				</div>

			</form>

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

	.breadcrumb a {
		color: #fff;
		font-weight: 500;
		text-decoration: none;
	}

	.breadcrumb a:hover {
		text-decoration: underline;
	}

	.breadcrumb-item.active {
		color: #e9d8ff;
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

	.btn-audit-save {
		background-color: #6f42c1;
		border-color: #6f42c1;
		color: #fff;
	}

	.btn-audit-save:hover {
		background-color: #59339d;
		border-color: #59339d;
		color: #fff;
	}

	.table td input[type="text"],
	.table td input[type="file"] {
		height: 30px;
		font-size: 0.85rem;
	}

	.table th,
	.table td {
		vertical-align: middle;
		text-align: center;
	}

	@media (max-width: 768px) {

		.table-responsive {
			font-size: 13px;
		}

		.badge-mode-audit {
			display: block;
			width: fit-content;
			margin-left: 0;
			margin-top: 8px;
		}

	}
</style>
