<?php
$isAudit = isset($mode) && $mode === 'audit';

$baseUrl = $isAudit
	? 'pemeriksaanchemical/audit'
	: 'pemeriksaanchemical';

$actionUrl = $isAudit
	? 'pemeriksaanchemical/edit-audit/'
	: 'pemeriksaanchemical/edit/';

$keteranganMobil = [
	1 => 'Bersih',
	2 => 'Kotor',
	3 => 'Bau',
	4 => 'Bocor',
	5 => 'Basah',
	6 => 'Kering',
	7 => 'Bebas Hama'
];

$mobilTerpilih = $pemeriksaanchemical->kondisi_mobil ?? '';
$mobilTerpilih = is_array($mobilTerpilih)
	? $mobilTerpilih
	: explode(',', (string) $mobilTerpilih);

$mobilTerpilih = array_map('trim', $mobilTerpilih);


$mobilTerpilih = array_values(array_filter($mobilTerpilih, function ($value) {
	return $value !== '';
}));

// Default hanya untuk Audit jika belum ada nilai.
if ($isAudit && empty($mobilTerpilih)) {
	$mobilTerpilih = ['1', '7']; // Bersih dan Bebas Hama
}

function selected_chemical($actual, $expected)
{
	return (string) $actual === (string) $expected ? 'selected' : '';
}

function checked_chemical($actual, $expected)
{
	return (string) $actual === (string) $expected ? 'checked' : '';
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Update Pemeriksaan Chemical dari Supplier
			<?php if ($isAudit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb <?= $isAudit ? 'breadcrumb-qc' : 'breadcrumb-qc'; ?>">
			<li class="breadcrumb-item">
				<a href="<?= base_url($baseUrl); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $isAudit ? 'Daftar Audit' : 'Daftar Pemeriksaan Chemical dari Supplier'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Edit</li>
		</ol>
	</nav>

	<?php if ($isAudit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Pemeriksaan Chemical.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<?php if (!empty($validation_errors)): ?>
				<div class="alert alert-danger">
					<?= $validation_errors; ?>
				</div>
			<?php endif; ?>

			<form
				class="user"
				method="post"
				action="<?= base_url($actionUrl . rawurlencode($pemeriksaanchemical->uuid)); ?>"
				enctype="multipart/form-data">

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Tanggal</label>
						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= set_value('date', $pemeriksaanchemical->date); ?>">
						<div class="invalid-feedback"><?= form_error('date'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Shift</label>
						<select
							name="shift"
							class="form-control <?= form_error('shift') ? 'is-invalid' : ''; ?>">
							<?php foreach ([1, 2, 3] as $shift): ?>
								<option
									value="<?= $shift; ?>"
									<?= selected_chemical(
										set_value('shift', $pemeriksaanchemical->shift),
										$shift
									); ?>>
									<?= $shift; ?>
								</option>
							<?php endforeach; ?>
						</select>
						<div class="invalid-feedback"><?= form_error('shift'); ?></div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Jenis Chemical</label>
						<input
							type="text"
							name="jenis_chemical"
							class="form-control <?= form_error('jenis_chemical') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jenis_chemical', $pemeriksaanchemical->jenis_chemical)); ?>">
						<div class="invalid-feedback"><?= form_error('jenis_chemical'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Pemasok</label>
						<input
							type="text"
							name="pemasok"
							class="form-control <?= form_error('pemasok') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('pemasok', $pemeriksaanchemical->pemasok)); ?>">
						<div class="invalid-feedback"><?= form_error('pemasok'); ?></div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Jenis Mobil</label>
						<input
							type="text"
							name="jenis_mobil"
							class="form-control <?= form_error('jenis_mobil') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jenis_mobil', $pemeriksaanchemical->jenis_mobil)); ?>">
						<div class="invalid-feedback"><?= form_error('jenis_mobil'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">No. Polisi</label>
						<input
							type="text"
							name="no_polisi"
							class="form-control <?= form_error('no_polisi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('no_polisi', $pemeriksaanchemical->no_polisi)); ?>">
						<div class="invalid-feedback"><?= form_error('no_polisi'); ?></div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Identitas Pengantar</label>
						<input
							type="text"
							name="identitas_pengantar"
							class="form-control <?= form_error('identitas_pengantar') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('identitas_pengantar', $pemeriksaanchemical->identitas_pengantar)); ?>">
						<div class="invalid-feedback"><?= form_error('identitas_pengantar'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">No. PO / DO</label>
						<input
							type="text"
							name="no_po"
							class="form-control <?= form_error('no_po') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('no_po', $pemeriksaanchemical->no_po)); ?>">
						<div class="invalid-feedback"><?= form_error('no_po'); ?></div>
					</div>
				</div>

				<hr>

				<div class="form-group">
					<label class="font-weight-bold d-block mb-2">Kondisi Mobil</label>
					<small class="text-danger font-italic">*Centang sesuai kondisi</small>

					<div class="table-responsive mt-2">
						<table class="table table-bordered text-center">
							<thead>
								<tr>
									<?php foreach ($keteranganMobil as $label): ?>
										<th><?= html_escape($label); ?></th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php foreach ($keteranganMobil as $key => $label): ?>
										<td>
											<input
												type="checkbox"
												name="kondisi_mobil[]"
												value="<?= $key; ?>"
												<?= in_array((string) $key, $mobilTerpilih, true) ? 'checked' : ''; ?>
												style="transform:scale(1.5);">
										</td>
									<?php endforeach; ?>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Kode Produksi</label>
						<input
							type="text"
							name="kode_produksi"
							class="form-control <?= form_error('kode_produksi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('kode_produksi', $pemeriksaanchemical->kode_produksi)); ?>">
						<div class="invalid-feedback"><?= form_error('kode_produksi'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Expired</label>
						<input
							type="date"
							name="expired"
							class="form-control <?= form_error('expired') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('expired', $pemeriksaanchemical->expired)); ?>">
						<div class="invalid-feedback"><?= form_error('expired'); ?></div>
					</div>
				</div>

				<div class="form-group row">
					<?php
					$jumlahFields = [
						'jumlah_barang' => 'Jumlah Barang',
						'sampel' => 'Sampel (Pcs)',
						'jumlah_reject' => 'Jumlah Reject'
					];
					foreach ($jumlahFields as $field => $label):
					?>
						<div class="col-sm-4">
							<label class="font-weight-bold"><?= $label; ?></label>
							<input
								type="text"
								name="<?= $field; ?>"
								class="form-control <?= form_error($field) ? 'is-invalid' : ''; ?>"
								value="<?= html_escape(set_value($field, $pemeriksaanchemical->$field)); ?>">
							<div class="invalid-feedback"><?= form_error($field); ?></div>
						</div>
					<?php endforeach; ?>
				</div>

				<hr>
				<label class="font-weight-bold">Kondisi Fisik</label>

				<div class="form-group row">
					<?php foreach (
						[
							'kemasan' => 'Kemasan',
							'warna' => 'Warna'
						] as $field => $label
					): ?>
						<div class="col-sm-3">
							<label class="font-weight-bold d-block"><?= $label; ?></label>

							<?php foreach (['sesuai' => 'Sesuai', 'tidak sesuai' => 'Tidak Sesuai'] as $value => $text): ?>
								<div class="form-check form-check-inline">
									<input
										class="form-check-input <?= form_error($field) ? 'is-invalid' : ''; ?>"
										type="radio"
										name="<?= $field; ?>"
										value="<?= $value; ?>"

										<?php
										$nilaiFisik = set_value($field, $pemeriksaanchemical->$field ?? '');

										if ($isAudit && trim((string) $nilaiFisik) === '') {
											$nilaiFisik = 'sesuai';
										}
										?>

										<?= checked_chemical($nilaiFisik, $value); ?>
										<label class="form-check-label"><?= $text; ?></label>
								</div>
							<?php endforeach; ?>

							<div class="text-danger small"><?= form_error($field); ?></div>
						</div>
					<?php endforeach; ?>

					<div class="col-sm-3">
						<label class="font-weight-bold">pH</label>
						<input
							type="text"
							name="ph"
							class="form-control <?= form_error('ph') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('ph', $pemeriksaanchemical->ph)); ?>">
						<div class="invalid-feedback"><?= form_error('ph'); ?></div>
					</div>
				</div>

				<hr>

				<div class="form-group row">

					<div class="col-sm-3">
						<label class="font-weight-bold d-block">Halal</label>

						<?php
						$halalBerlaku = set_value(
							'halal_berlaku',
							$pemeriksaanchemical->halal_berlaku ?? ''
						);

						if ($isAudit && trim((string) $halalBerlaku) === '') {
							$halalBerlaku = 'berlaku';
						}
						?>

						<?php foreach (['berlaku' => 'Berlaku', 'tidak berlaku' => 'Tidak Berlaku'] as $value => $label): ?>
							<div class="form-check form-check-inline">
								<input
									class="form-check-input"
									type="radio"
									name="halal_berlaku"
									value="<?= $value; ?>"
									<?= checked_chemical($halalBerlaku, $value); ?>>
								<label class="form-check-label"><?= $label; ?></label>
							</div>
						<?php endforeach; ?>

						<div class="text-danger small"><?= form_error('halal_berlaku'); ?></div>
					</div>


					<div class="col-sm-3">
						<label class="font-weight-bold d-block">Segel</label>

						<?php
						$nilaiSegel = set_value(
							'segel',
							$pemeriksaanchemical->segel ?? ''
						);

						if ($isAudit && trim((string) $nilaiSegel) === '') {
							$nilaiSegel = 'Sesuai';
						}
						?>

						<?php foreach (['Sesuai', 'Tidak Sesuai'] as $value): ?>
							<div class="form-check form-check-inline">
								<input
									class="form-check-input"
									type="radio"
									name="segel"
									value="<?= $value; ?>"
									<?= checked_chemical($nilaiSegel, $value); ?>>
								<label class="form-check-label"><?= $value; ?></label>
							</div>
						<?php endforeach; ?>

						<div class="text-danger small"><?= form_error('segel'); ?></div>
					</div>


					<div class="col-sm-3">
						<label class="font-weight-bold d-block">Penerimaan</label>

						<?php
						$nilaiPenerimaan = set_value(
							'penerimaan',
							$pemeriksaanchemical->penerimaan ?? ''
						);

						if ($isAudit && trim((string) $nilaiPenerimaan) === '') {
							$nilaiPenerimaan = 'ok';
						}
						?>

						<?php foreach (['ok' => 'Ok', 'tolak' => 'Tolak'] as $value => $label): ?>
							<div class="form-check form-check-inline">
								<input
									class="form-check-input"
									type="radio"
									name="penerimaan"
									value="<?= $value; ?>"
									<?= checked_chemical($nilaiPenerimaan, $value); ?>>
								<label class="form-check-label"><?= $label; ?></label>
							</div>
						<?php endforeach; ?>

						<div class="text-danger small"><?= form_error('penerimaan'); ?></div>
					</div>
				</div>

				<hr>

				<div class="form-group row">

					<div class="col-sm-3">
						<label class="font-weight-bold d-block">COA</label>

						<?php
						$nilaiCoa = set_value(
							'coa',
							$pemeriksaanchemical->coa ?? ''
						);

						if ($isAudit && trim((string) $nilaiCoa) === '') {
							$nilaiCoa = 'ada';
						}
						?>

						<?php foreach (['ada' => 'Ada', 'tidak ada' => 'Tidak Ada'] as $value => $label): ?>
							<div class="form-check form-check-inline">
								<input
									class="form-check-input"
									type="radio"
									name="coa"
									value="<?= $value; ?>"
									<?= checked_chemical($nilaiCoa, $value); ?>>
								<label class="form-check-label"><?= $label; ?></label>
							</div>
						<?php endforeach; ?>

						<div class="text-danger small"><?= form_error('coa'); ?></div>
					</div>

					<div class="col-sm-5">
						<label class="form-label font-weight-bold" for="bukti_coa">
							<?= !empty($pemeriksaanchemical->bukti_coa) ? 'Update Bukti COA' : 'Upload Bukti COA'; ?>
						</label>

						<input
							type="file"
							name="bukti_coa"
							id="bukti_coa"
							class="form-control no-border"
							accept="image/*,application/pdf"
							capture="camera">

						<?php if (!empty($pemeriksaanchemical->bukti_coa)): ?>
							<a
								href="<?= base_url('uploads/penerimaan_chemical/' . rawurlencode(basename($pemeriksaanchemical->bukti_coa))); ?>"
								target="_blank"
								rel="noopener noreferrer">
								Lihat Bukti COA Sebelumnya
							</a>
						<?php endif; ?>

						<small class="d-block text-danger font-italic">
							*Upload COA jika ada
						</small>
					</div>
				</div>

				<hr>


				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Keterangan</label>

						<?php
						$keteranganLama = $pemeriksaanchemical->keterangan ?? '';
						$defaultKeterangan = $keteranganLama;

						if ($isAudit && trim((string) $keteranganLama) !== '') {
							$defaultKeterangan = '-';
						}

						$nilaiKeterangan = set_value('keterangan', $defaultKeterangan);
						?>

						<textarea
							class="form-control"
							name="keterangan"
							rows="3"><?= html_escape($nilaiKeterangan); ?></textarea>

						<div class="text-danger small"><?= form_error('keterangan'); ?></div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Catatan</label>

						<?php
						$catatanLama = $pemeriksaanchemical->catatan ?? '';
						$defaultCatatan = $catatanLama;

						if ($isAudit && trim((string) $catatanLama) !== '') {
							$defaultCatatan = '-';
						}

						$nilaiCatatan = set_value('catatan', $defaultCatatan);
						?>

						<textarea
							class="form-control"
							name="catatan"
							rows="3"><?= html_escape($nilaiCatatan); ?></textarea>

						<div class="text-danger small"><?= form_error('catatan'); ?></div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn <?= $isAudit ? 'btn-success' : 'btn-success'; ?> mr-2">
							<i class="fas fa-save mr-1"></i> Simpan
						</button>

						<a href="<?= base_url($baseUrl); ?>" class="btn btn-danger">
							<i class="fas fa-times mr-1"></i> Batal
						</a>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<style>
	.breadcrumb {
		padding: 8px 16px;
		border-radius: .25rem;
	}

	.breadcrumb-qc {
		background-color: #2E86C1;
	}

	.breadcrumb-audit {
		background-color: #5a3298;
	}

	.breadcrumb a {
		color: #fff;
		font-weight: 500;
	}

	.badge-mode-audit {
		display: inline-block;
		background: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}

	.btn-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-audit:hover {
		background-color: #452575;
		border-color: #452575;
		color: #fff;
	}

	.no-border {
		border: none;
		padding-left: 0;
	}
</style>
