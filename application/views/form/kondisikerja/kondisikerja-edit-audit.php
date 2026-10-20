<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		Edit Audit Kondisi Kerja Selama Produksi
		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-audit">
			<li class="breadcrumb-item">
				<a href="<?= base_url('kondisikerja/audit') ?>">
					<i class="fas fa-arrow-left"></i> Daftar Audit Kondisi Kerja
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<div class="alert alert-audit">
		<i class="fas fa-info-circle mr-2"></i>
		<strong>MODE AUDIT:</strong>
		Perubahan pada halaman ini hanya mengubah data audit dan
		<strong>tidak akan mengubah data Kondisi Kerja asli</strong>.
	</div>

	<div class="card shadow mb-4 card-audit">
		<div class="card-header">
			<i class="fas fa-edit mr-2"></i>
			Form Edit Audit Kondisi Kerja
		</div>

		<div class="card-body">

			<form method="post"
				action="<?= base_url('kondisikerja/edit-audit/' . $kondisikerja->uuid) ?>">

				<!-- Informasi Umum -->
				<div class="form-group row">

					<div class="col-md-3">
						<label><strong>Tanggal</strong></label>
						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : '' ?>"
							value="<?= html_escape($kondisikerja->date) ?>">
						<div class="invalid-feedback">
							<?= form_error('date') ?>
						</div>
					</div>

					<div class="col-md-3">
						<label><strong>Shift</strong></label>

						<select
							name="shift"
							class="form-control <?= form_error('shift') ? 'is-invalid' : '' ?>">
							<option value="">-- Pilih Shift --</option>

							<option value="1"
								<?= $kondisikerja->shift == 1 ? 'selected' : '' ?>>
								1
							</option>

							<option value="2"
								<?= $kondisikerja->shift == 2 ? 'selected' : '' ?>>
								2
							</option>

							<option value="3"
								<?= $kondisikerja->shift == 3 ? 'selected' : '' ?>>
								3
							</option>
						</select>

						<div class="invalid-feedback">
							<?= form_error('shift') ?>
						</div>
					</div>

					<div class="col-md-3">
						<label><strong>Pukul</strong></label>

						<input
							type="time"
							name="waktu"
							class="form-control <?= form_error('waktu') ? 'is-invalid' : '' ?>"
							value="<?= html_escape($kondisikerja->waktu) ?>">

						<div class="invalid-feedback">
							<?= form_error('waktu') ?>
						</div>
					</div>

					<div class="col-md-3">
						<label><strong>Area</strong></label>

						<input
							type="text"
							name="area"
							class="form-control <?= form_error('area') ? 'is-invalid' : '' ?>"
							value="<?= html_escape($kondisikerja->area) ?>">

						<div class="invalid-feedback">
							<?= form_error('area') ?>
						</div>
					</div>

				</div>


				<?php

				$options = [
					'1' => '1. Berdebu',
					'2' => '2. Basah, ada genangan air',
					'3' => '3. Sisa produksi (remah-remah roti, tepung, sisa adonan)',
					'4' => '4. Noda (karat, cat, tinta)',
					'5' => '5. Pertumbuhan Mikroorganisme (jamur, bau busuk, biofilm)',
					'6' => '6. Kontak / kontaminasi material non halal',
					'7' => '7. Higiene karyawan tidak sesuai GMP',
					'✓' => '✓ Ok, Sesuai SSOP, bersih, bebas najis / material non halal',
					'X' => 'X Tidak Ok, tidak sesuai SSOP',
					'-' => '- Tidak ada / Tidak digunakan'
				];

				$sections = [
					'higiene' => 'HIGIENE KARYAWAN',
					'kebersihan' => 'KEBERSIHAN AREA/RUANGAN',
					'peralatan' => 'KEBERSIHAN PERALATAN'
				];

				?>


				<?php foreach ($sections as $key => $title): ?>

					<hr>

					<label class="section-title">
						<?= $title ?>
					</label>

					<div class="form-group row align-items-end">

						<!-- Kondisi -->
						<div class="col-md-3">

							<label>Kondisi</label>

							<select
								name="kondisi_<?= $key ?>"
								class="form-control <?= form_error('kondisi_' . $key) ? 'is-invalid' : '' ?>">

								<option value="">-- Pilih --</option>

								<?php foreach ($options as $val => $label): ?>

									<option
										value="<?= html_escape($val) ?>"
										<?= trim((string) $kondisikerja->{'kondisi_' . $key}) === trim((string) $val) ? 'selected' : '' ?>>
										<?= html_escape($label) ?>
									</option>

								<?php endforeach; ?>

							</select>

							<div class="invalid-feedback">
								<?= form_error('kondisi_' . $key) ?>
							</div>

						</div>


						<!-- Problem -->
						<div class="col-md-3">

							<label>Problem</label>

							<input
								type="text"
								name="problem_<?= $key ?>"
								class="form-control"
								value="<?= html_escape($kondisikerja->{'problem_' . $key}) ?>">

						</div>


						<!-- Tindakan -->
						<div class="col-md-3">

							<label>Tindakan Koreksi</label>

							<input
								type="text"
								name="tindakan_<?= $key ?>"
								class="form-control"
								value="<?= html_escape($kondisikerja->{'tindakan_' . $key}) ?>">

						</div>


						<!-- Verifikasi -->
						<div class="col-md-3">

							<label>Verifikasi</label>

							<input
								type="text"
								name="verifikasi_<?= $key ?>"
								class="form-control"
								value="<?= html_escape($kondisikerja->{'verifikasi_' . $key}) ?>">

						</div>

					</div>

				<?php endforeach; ?>


				<hr>

				<!-- Catatan -->
				<div class="form-group row">

					<div class="col-md-6">

						<label>
							<strong>Catatan</strong>
						</label>

						<input
							type="text"
							name="catatan"
							class="form-control"
							value="<?= html_escape($kondisikerja->catatan) ?>">

					</div>

				</div>


				<!-- Tombol -->
				<div class="form-group row mt-4">

					<div class="col">

						<button
							type="submit"
							class="btn btn-success">
							<i class="fas fa-save"></i>
							Simpan
						</button>

						<a
							href="<?= base_url('kondisikerja/audit') ?>"
							class="btn btn-danger">
							<i class="fas fa-times"></i>
							Batal
						</a>

					</div>

				</div>

			</form>

		</div>
	</div>

</div>


<style>
	/* Breadcrumb Audit */
	.breadcrumb-audit {
		background-color: #2E86C1;
	}

	.breadcrumb-audit a {
		color: #fff;
		text-decoration: none;
	}

	.breadcrumb-audit .active {
		color: #e8dff5;
	}


	/* Badge MODE AUDIT */
	.badge-mode-audit {
		display: inline-block;
		margin-left: 8px;
		padding: 6px 12px;
		background-color: #5a3298;
		color: #fff;
		border-radius: 6px;
		font-size: 13px;
		font-weight: 700;
		vertical-align: middle;
	}


	/* Alert Audit */
	.alert-audit {
		background-color: #f1eafa;
		border-left: 5px solid #5a3298;
		color: #432574;
	}


	/* Card Audit */
	.card-audit {
		border-left: 4px solid #5a3298;
	}

	.card-audit .card-header {
		background-color: #5a3298;
		color: #fff;
		font-weight: 600;
	}


	/* Section */
	.section-title {
		color: #050505;
		font-weight: 700;
		font-size: 15px;
	}


	/* Button */
	.btn-audit-save {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-audit-save:hover {
		background-color: #432574;
		border-color: #432574;
		color: #fff;
	}
</style>
