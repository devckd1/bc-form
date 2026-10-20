<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		Edit Audit Pemeriksaan Suhu Chiller

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('chiller/audit'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Suhu Chiller
				</a>
			</li>

			<li class="breadcrumb-item active" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>

		<strong>Informasi:</strong>

		Halaman ini merupakan menu Edit Audit Pemeriksaan Suhu Chiller.
		Perubahan data pada halaman ini tidak akan mengubah data aktual
		pada menu pemeriksaan.
	</div>

	<div class="card shadow mb-4 card-audit">

		<div class="card-body">

			<form class="user"
				method="post"
				action="<?= base_url('chiller/edit-audit/' . $chiller->uuid); ?>">

				<div class="form-group row">

					<div class="col-sm-6">

						<label class="form-label font-weight-bold">
							Tanggal
						</label>

						<input type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'invalid' : '' ?>"
							value="<?= set_value('date', $chiller->date); ?>">

						<div class="invalid-feedback <?= !empty(form_error('date')) ? 'd-block' : ''; ?>">
							<?= form_error('date'); ?>
						</div>

					</div>

					<div class="col-sm-6">

						<label class="form-label font-weight-bold">
							Waktu
						</label>

						<input type="time"
							name="waktu"
							class="form-control <?= form_error('waktu') ? 'invalid' : '' ?>"
							value="<?= set_value('waktu', $chiller->waktu); ?>">

						<div class="invalid-feedback <?= !empty(form_error('waktu')) ? 'd-block' : ''; ?>">
							<?= form_error('waktu'); ?>
						</div>

					</div>

				</div>


				<label class="form-label font-weight-bold">
					Suhu Chiller (°C)
				</label>


				<div class="form-group row">

					<?php
					$chiller_fields = [
						1 => 'chiller_1',
						2 => 'chiller_2',
						3 => 'chiller_3',
						4 => 'chiller_4'
					];
					?>

					<?php foreach ($chiller_fields as $nomor => $field): ?>

						<?php
						$master = null;

						foreach ($master_chiller as $item) {
							if ($item->nama_chiller === 'Chiller ' . $nomor) {
								$master = $item;
								break;
							}
						}
						?>

						<div class="col-sm-3">

							<label class="form-label font-weight-bold">
								Chiller No.<?= $nomor; ?>
							</label>

							<?php
							$nilai_asli = $chiller->$field;
							$nilai_tampil = $nilai_asli;

							$min = null;
							$max = null;

							/*
 * KONDISI:
 * 1. Data asli ada + Master ada
 *    -> tampilkan nilai tengah Master
 *
 * 2. Data asli ada + Master tidak ada
 *    -> tampilkan nilai asli
 *
 * 3. Data asli kosong
 *    -> tetap kosong
 */

							if ($nilai_asli !== null && $nilai_asli !== '') {

								if ($master && !empty($master->range_standar)) {

									preg_match_all(
										'/-?\d+(?:[.,]\d+)?/',
										$master->range_standar,
										$matches
									);

									if (count($matches[0]) >= 2) {

										$min = (float) str_replace(',', '.', $matches[0][0]);
										$max = (float) str_replace(',', '.', $matches[0][1]);

										// Nilai tengah Master
										$nilai_tampil = ($min + $max) / 2;

										// Hilangkan .0 jika hasilnya bilangan bulat
										if (floor($nilai_tampil) == $nilai_tampil) {
											$nilai_tampil = (int) $nilai_tampil;
										}
									}
								}
							}
							?>

							<input type="number"
								name="<?= $field; ?>"
								class="form-control <?= form_error($field) ? 'invalid' : ''; ?>"
								value="<?= set_value($field, $nilai_tampil); ?>"
								<?= $min !== null ? 'min="' . $min . '"' : ''; ?>
								<?= $max !== null ? 'max="' . $max . '"' : ''; ?>
								step="0.1">

							<div class="invalid-feedback <?= !empty(form_error($field)) ? 'd-block' : ''; ?>">
								<?= form_error($field); ?>
							</div>

						</div>

					<?php endforeach; ?>

				</div>


				<hr>


				<div class="row form-group">

					<div class="col-sm-6">

						<label class="form-label font-weight-bold">
							Catatan
						</label>

						<textarea class="form-control"
							name="catatan"><?= set_value('catatan', $chiller->catatan); ?></textarea>

						<div class="invalid-feedback <?= !empty(form_error('catatan')) ? 'd-block' : ''; ?>">
							<?= form_error('catatan'); ?>
						</div>

					</div>

				</div>


				<div class="row">

					<div class="col">

						<button type="submit"
							class="btn btn-md btn-success mr-2">

							<i class="fa fa-save"></i>
							Simpan Audit

						</button>

						<a href="<?= base_url('chiller/audit'); ?>"
							class="btn btn-md btn-danger">

							<i class="fa fa-times"></i>
							Batal

						</a>

					</div>

				</div>

			</form>

		</div>

	</div>

</div>


<style>
th {
		background-color: #f8f9fc;
	}

	.breadcrumb {
		background-color: #2E86C1;
	}
	th {
		background-color: #f8f9fc;
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
		padding: 6px 12px;
		border-radius: 6px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
		box-shadow: 0 2px 5px rgba(90, 50, 152, 0.25);
	}
</style>
