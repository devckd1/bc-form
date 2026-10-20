<?php
defined('BASEPATH') or exit('No direct script access allowed');

$mode = $mode ?? 'qc';
$isAudit = $mode === 'audit';

$baseUrl = $isAudit ? 'seasoning/audit' : 'seasoning';
$editUrl = $isAudit
	? 'seasoning/edit-audit/' . $seasoning->uuid
	: 'seasoning/edit/' . $seasoning->uuid;

$keterangan = [
	1 => 'Bersih',
	2 => 'Kotor',
	3 => 'Bau',
	4 => 'Bocor',
	5 => 'Basah',
	6 => 'Kering',
	7 => 'Bebas Hama'
];

$mobilTerpilih = array_map(
	'trim',
	explode(',', (string) ($seasoning->kondisi_mobil ?? ''))
);

$mobilTerpilih = array_values(array_filter($mobilTerpilih, function ($value) {
	return $value !== '';
}));

if ($isAudit && empty($mobilTerpilih)) {
	$mobilTerpilih = ['1', '7']; // Bersih dan Bebas Hama
}

$fisik = [
	'kemasan' => 'Kemasan',
	'warna'   => 'Warna',
	'kotoran' => 'Kotoran',
	'aroma'   => 'Aroma'
];

function seasoning_selected($actual, $expected)
{
	return (string) $actual === (string) $expected ? 'selected' : '';
}

function seasoning_checked($actual, $expected)
{
	return (string) $actual === (string) $expected ? 'checked' : '';
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			Update Pemeriksaan Seasoning dari Pemasok

			<?php if ($isAudit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb <?= $isAudit ? 'breadcrumb-audit' : ''; ?>">
			<li class="breadcrumb-item ">
				<a href="<?= base_url($baseUrl); ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $isAudit
						? 'Daftar Audit Pemeriksaan Seasoning dari Pemasok'
						: 'Daftar Pemeriksaan Seasoning dari Pemasok'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active text-white" aria-current="page">
				<?= $isAudit ? 'Edit Audit' : 'Edit'; ?>
			</li>
		</ol>
	</nav>

	<?php if ($isAudit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Pemeriksaan Seasoning.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4 <?= $isAudit ? 'card-audit' : ''; ?>">
		<div class="card-body">

			<form class="user"
				method="post"
				action="<?= base_url($editUrl); ?>"
				enctype="multipart/form-data">

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Tanggal</label>
						<input type="date" name="date"
							class="form-control <?= form_error('date') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $seasoning->date)); ?>">
						<div class="invalid-feedback <?= form_error('date') ? 'd-block' : ''; ?>">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Jenis Seasoning</label>
						<input type="text" name="jenis_seasoning"
							class="form-control <?= form_error('jenis_seasoning') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('jenis_seasoning', $seasoning->jenis_seasoning)); ?>">
						<div class="invalid-feedback <?= form_error('jenis_seasoning') ? 'd-block' : ''; ?>">
							<?= form_error('jenis_seasoning'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Spesifikasi</label>
						<input type="text" name="spesifikasi"
							class="form-control <?= form_error('spesifikasi') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('spesifikasi', $seasoning->spesifikasi)); ?>">
						<div class="invalid-feedback <?= form_error('spesifikasi') ? 'd-block' : ''; ?>">
							<?= form_error('spesifikasi'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Pemasok</label>
						<input type="text" name="pemasok"
							class="form-control <?= form_error('pemasok') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('pemasok', $seasoning->pemasok)); ?>">
						<div class="invalid-feedback <?= form_error('pemasok') ? 'd-block' : ''; ?>">
							<?= form_error('pemasok'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Jenis Mobil</label>
						<input type="text" name="jenis_mobil"
							class="form-control <?= form_error('jenis_mobil') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('jenis_mobil', $seasoning->jenis_mobil)); ?>">
						<div class="invalid-feedback <?= form_error('jenis_mobil') ? 'd-block' : ''; ?>">
							<?= form_error('jenis_mobil'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">No. Polisi</label>
						<input type="text" name="no_polisi"
							class="form-control <?= form_error('no_polisi') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('no_polisi', $seasoning->no_polisi)); ?>">
						<div class="invalid-feedback <?= form_error('no_polisi') ? 'd-block' : ''; ?>">
							<?= form_error('no_polisi'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Identitas Pengantar</label>
						<input type="text" name="identitas_pengantar"
							class="form-control <?= form_error('identitas_pengantar') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('identitas_pengantar', $seasoning->identitas_pengantar)); ?>">
						<div class="invalid-feedback <?= form_error('identitas_pengantar') ? 'd-block' : ''; ?>">
							<?= form_error('identitas_pengantar'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">No. PO / DO</label>
						<input type="text" name="no_po"
							class="form-control <?= form_error('no_po') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('no_po', $seasoning->no_po)); ?>">
						<div class="invalid-feedback <?= form_error('no_po') ? 'd-block' : ''; ?>">
							<?= form_error('no_po'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="form-group">
					<label class="font-weight-bold d-block">Kondisi Mobil</label>
					<small class="text-danger font-italic">*Centang sesuai kondisi</small>

					<div class="table-responsive mt-2">
						<table class="table table-bordered text-center">
							<thead>
								<tr>
									<?php foreach ($keterangan as $label): ?>
										<th><?= html_escape($label); ?></th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php foreach ($keterangan as $key => $label): ?>
										<td>
											<input type="checkbox"
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
						<input type="text" name="kode_produksi"
							class="form-control <?= form_error('kode_produksi') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('kode_produksi', $seasoning->kode_produksi)); ?>">
						<div class="invalid-feedback <?= form_error('kode_produksi') ? 'd-block' : ''; ?>">
							<?= form_error('kode_produksi'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Expired Date</label>
						<input type="date" name="expired"
							class="form-control <?= form_error('expired') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('expired', $seasoning->expired)); ?>">
						<div class="invalid-feedback <?= form_error('expired') ? 'd-block' : ''; ?>">
							<?= form_error('expired'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<?php foreach (
						[
							'jumlah_barang' => 'Jumlah Barang',
							'sampel' => 'Sampel',
							'jumlah_reject' => 'Jumlah Reject'
						] as $field => $label
					): ?>
						<div class="col-sm-4">
							<label class="font-weight-bold"><?= $label; ?></label>
							<input type="text" name="<?= $field; ?>"
								class="form-control <?= form_error($field) ? 'invalid' : ''; ?>"
								value="<?= html_escape(set_value($field, $seasoning->$field)); ?>">
							<div class="invalid-feedback <?= form_error($field) ? 'd-block' : ''; ?>">
								<?= form_error($field); ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<hr>

				<div class="form-group">
					<label class="font-weight-bold d-block">Kondisi Fisik</label>
					<small class="text-danger font-italic">*Centang jika sesuai</small>

					<div class="table-responsive mt-2">
						<table class="table table-bordered text-center">
							<thead>
								<tr>
									<?php foreach ($fisik as $label): ?>
										<th><?= $label; ?></th>
									<?php endforeach; ?>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php foreach ($fisik as $field => $label): ?>
										<td>
											<input type="checkbox"
												name="<?= $field; ?>"
												value="sesuai"
												<?php
												$nilaiFisik = set_value($field, $seasoning->$field ?? '');

												if ($isAudit && trim((string) $nilaiFisik) === '') {
													$nilaiFisik = 'sesuai';
												}
												?>

												<?= seasoning_checked($nilaiFisik, 'sesuai'); ?>
												style="transform:scale(1.5);">
										</td>
									<?php endforeach; ?>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<hr>

				<label class="font-weight-bold">Spesifikasi</label>
				<div class="form-group row">
					<div class="col-sm-3">
						<label class="font-weight-bold">Kadar Air (%)</label>
						<input type="text" name="kadar_air"
							class="form-control <?= form_error('kadar_air') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('kadar_air', $seasoning->kadar_air)); ?>">
						<div class="invalid-feedback <?= form_error('kadar_air') ? 'd-block' : ''; ?>">
							<?= form_error('kadar_air'); ?>
						</div>
					</div>

					<div class="col-sm-3">
						<label class="font-weight-bold">Negara Asal Dibuat</label>
						<input type="text" name="negara_asal"
							class="form-control <?= form_error('negara_asal') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('negara_asal', $seasoning->negara_asal)); ?>">
						<div class="invalid-feedback <?= form_error('negara_asal') ? 'd-block' : ''; ?>">
							<?= form_error('negara_asal'); ?>
						</div>
					</div>


					<div class="col-sm-3">
						<label class="font-weight-bold d-block">Segel</label>
						<?php
						$nilaiSegel = set_value('segel', $seasoning->segel ?? '');

						if ($isAudit && trim((string) $nilaiSegel) === '') {
							$nilaiSegel = 'Sesuai';
						}
						?>
						<?php foreach (['Sesuai', 'Tidak Sesuai'] as $value): ?>
							<div class="form-check form-check-inline">
								<input class="form-check-input <?= form_error('segel') ? 'is-invalid' : ''; ?>"
									type="radio" name="segel"
									value="<?= $value; ?>"
									<?= seasoning_checked($nilaiSegel, $value); ?>>
								<label class="form-check-label"><?= $value; ?></label>
							</div>
						<?php endforeach; ?>
						<div class="invalid-feedback d-block"><?= form_error('segel'); ?></div>
					</div>


					<div class="col-sm-3">
						<label class="font-weight-bold d-block">Logo Halal</label>
						<?php
						$nilaiLogoHalal = set_value('logo_halal', $seasoning->logo_halal ?? '');

						if ($isAudit && trim((string) $nilaiLogoHalal) === '') {
							$nilaiLogoHalal = 'ada';
						}
						?>
						<?php foreach (['ada' => 'Ada', 'tidak ada' => 'Tidak Ada'] as $value => $label): ?>
							<div class="form-check form-check-inline">
								<input class="form-check-input <?= form_error('logo_halal') ? 'is-invalid' : ''; ?>"
									type="radio" name="logo_halal"
									value="<?= $value; ?>"
									<?= seasoning_checked($nilaiLogoHalal, $value); ?>>
								<label class="form-check-label"><?= $label; ?></label>
							</div>
						<?php endforeach; ?>
						<div class="invalid-feedback d-block"><?= form_error('logo_halal'); ?></div>
					</div>
				</div>

				<hr>

				<label class="font-weight-bold">Persyaratan Dokumen &amp; Allergen</label>
				<div class="form-group row">
					<?php
					$radioFields = [
						'sertif_halal' => [
							'label' => 'Halal',
							'options' => [
								'berlaku' => 'Berlaku',
								'tidak berlaku' => 'Tidak Berlaku'
							]
						],
						'coa' => [
							'label' => 'COA',
							'options' => [
								'ada' => 'Ada',
								'tidak ada' => 'Tidak Ada'
							]
						],
						'allergen' => [
							'label' => 'Allergen',
							'options' => [
								'allergen' => 'Allergen',
								'non allergen' => 'Non Allergen'
							]
						],
						'penerimaan' => [
							'label' => 'Penerimaan',
							'options' => [
								'ok' => 'Ok',
								'tolak' => 'Tolak'
							]
						]
					];
					?>

					<?php foreach ($radioFields as $field => $config): ?>
						<div class="col-sm-3">
							<label class="font-weight-bold d-block"><?= $config['label']; ?></label>
							<?php foreach ($config['options'] as $value => $label): ?>
								<div class="form-check form-check-inline">
									<input class="form-check-input <?= form_error($field) ? 'is-invalid' : ''; ?>"
										type="radio"
										name="<?= $field; ?>"
										value="<?= $value; ?>"
										<?php
										$nilaiRadio = set_value($field, $seasoning->$field ?? '');

										if ($isAudit && trim((string) $nilaiRadio) === '') {
											$defaultAudit = [
												'sertif_halal' => 'berlaku',
												'coa'          => 'ada',
												'allergen'     => 'non allergen',
												'penerimaan'   => 'ok'
											];

											if (isset($defaultAudit[$field])) {
												$nilaiRadio = $defaultAudit[$field];
											}
										}
										?>

										<?= seasoning_checked($nilaiRadio, $value); ?>
										<label class="form-check-label"><?= $label; ?></label>
								</div>
							<?php endforeach; ?>
							<div class="invalid-feedback d-block"><?= form_error($field); ?></div>
						</div>
					<?php endforeach; ?>
				</div>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="font-weight-bold" for="bukti_coa">Update Bukti COA</label>
						<input type="file" name="bukti_coa" id="bukti_coa"
							class="form-control no-border <?= form_error('bukti_coa') ? 'is-invalid' : ''; ?>"
							accept="image/*,application/pdf" capture="camera">

						<?php if (!empty($seasoning->bukti_coa)): ?>
							<a href="<?= base_url('uploads/seasoning/' . rawurlencode($seasoning->bukti_coa)); ?>"
								target="_blank" rel="noopener noreferrer">
								Lihat Bukti COA Sebelumnya
							</a>
						<?php endif; ?>

						<div class="invalid-feedback <?= form_error('bukti_coa') ? 'd-block' : ''; ?>">
							<?= form_error('bukti_coa'); ?>
						</div>
						<small class="text-danger font-italic">*Upload COA jika ada</small>
					</div>
				</div>

				<hr>


				<div class="form-group row">
					<div class="col-sm-6">
						<label class="font-weight-bold">Keterangan</label>
						<?php
						$keteranganLama = $seasoning->keterangan ?? '';
						$defaultKeterangan = $keteranganLama;

						if ($isAudit && trim((string) $keteranganLama) !== '') {
							$defaultKeterangan = '-';
						}

						$nilaiKeterangan = set_value('keterangan', $defaultKeterangan);
						?>
						<textarea class="form-control" name="keterangan"><?= html_escape($nilaiKeterangan); ?></textarea>
						<div class="invalid-feedback <?= form_error('keterangan') ? 'd-block' : ''; ?>">
							<?= form_error('keterangan'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="font-weight-bold">Catatan</label>
						<?php
						$catatanLama = $seasoning->catatan ?? '';
						$defaultCatatan = $catatanLama;

						if ($isAudit && trim((string) $catatanLama) !== '') {
							$defaultCatatan = '-';
						}

						$nilaiCatatan = set_value('catatan', $defaultCatatan);
						?>
						<textarea class="form-control" name="catatan"><?= html_escape($nilaiCatatan); ?></textarea>
						<div class="invalid-feedback <?= form_error('catatan') ? 'd-block' : ''; ?>">
							<?= form_error('catatan'); ?>
						</div>
					</div>
				</div>

				<button type="submit" class="btn btn-success mr-2">
					<i class="fas fa-save mr-1"></i>
					<?= $isAudit ? 'Simpan' : 'Simpan'; ?>
				</button>

				<a href="<?= base_url($baseUrl); ?>" class="btn btn-danger">
					<i class="fas fa-times mr-1"></i> Batal
				</a>

			</form>
		</div>
	</div>
</div>

<style>
	.badge-mode-audit {
		display: inline-block;
		padding: 6px 10px;
		margin-left: 6px;
		border-radius: 5px;
		background: #5a3298;
		color: #fff;
		font-size: 12px;
		vertical-align: middle;
	}

	.btn-audit {
		color: #fff;
		background: #5a3298;
		border-color: #5a3298;
	}

	.breadcrumb {
		background-color: #2E86C1;
	}

	.breadcrumb-audit {
		background-color: #2E86C1;
	}

	.breadcrumb a {
		color: #fff;
	}

	.card-audit {
		border-top: 4px solid #5a3298;
	}

	.invalid {
		border-color: #e74a3b;
	}
</style>
