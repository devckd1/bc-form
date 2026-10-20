<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$form_url = $is_audit
	? base_url('produksi/drying-audit/' . rawurlencode($produksi->uuid))
	: base_url('produksi/drying/' . rawurlencode($produksi->uuid));

$back_url = $is_audit
	? base_url('produksi/audit')
	: base_url('produksi');
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		Proses Drying
		<?php if ($is_audit): ?>
			<span class="badge-mode-audit">MODE AUDIT</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= $back_url; ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Verifikasi Proses Produksi'
						: 'Daftar Laporan Verifikasi Proses Produksi'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				<?= $is_audit ? 'Edit' : 'Edit'; ?>
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert"
			style="background-color:#f3e8ff;border-left:4px solid #6f42c1;color:#4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>
			<strong>Informasi:</strong>
			Halaman ini merupakan menu Edit Proses Produksi.
			Perubahan data pada halaman ini hanya berlaku pada data Audit
			dan tidak akan mengubah data aktual pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form class="user" method="post" action="<?= $form_url; ?>">

				<label class="form-label font-weight-bold">
					Produk: <?= html_escape($produksi->nama_produk); ?>
				</label>
				<br>

				<label class="form-label font-weight-bold">
					Kode Produksi: <?= html_escape($produksi->kode_produksi); ?>
				</label>

				<hr>

				<label class="form-label font-weight-bold">DRYING</label>

				<div class="form-group row">

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Suhu (°C)</label>
						<input type="text"
							name="dry_suhu"
							class="form-control <?= form_error('dry_suhu') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('dry_suhu', $produksi->dry_suhu)); ?>">

						<div class="invalid-feedback <?= form_error('dry_suhu') ? 'd-block' : ''; ?>">
							<?= form_error('dry_suhu'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Speed Rotasi (4-6 RPM)
						</label>
						<?php
						$dry_rotasi = $produksi->dry_rotasi ?? '';

						if (
							$is_audit &&
							trim((string) $dry_rotasi) !== ''
						) {
							if (
								!is_numeric($dry_rotasi) ||
								(float) $dry_rotasi < 4 ||
								(float) $dry_rotasi > 6
							) {
								$dry_rotasi = '5';
							}
						}
						?>

						<input type="text"
							name="dry_rotasi"
							class="form-control <?= form_error('dry_rotasi') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('dry_rotasi', $dry_rotasi)); ?>">

						<div class="invalid-feedback <?= form_error('dry_rotasi') ? 'd-block' : ''; ?>">
							<?= form_error('dry_rotasi'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Kadar Air 4-8 (%)
						</label>
						<?php
						$dry_kadar_air = $produksi->dry_kadar_air ?? '';

						if (
							$is_audit &&
							trim((string) $dry_kadar_air) !== ''
						) {
							if (
								!is_numeric($dry_kadar_air) ||
								(float) $dry_kadar_air < 4 ||
								(float) $dry_kadar_air > 8
							) {
								$dry_kadar_air = '6';
							}
						}
						?>

						<input type="text"
							name="dry_kadar_air"
							class="form-control <?= form_error('dry_kadar_air') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('dry_kadar_air', $dry_kadar_air)); ?>">

						<div class="invalid-feedback <?= form_error('dry_kadar_air') ? 'd-block' : ''; ?>">
							<?= form_error('dry_kadar_air'); ?>
						</div>
					</div>

				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>

						<a href="<?= $back_url; ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>

			</form>

		</div>
	</div>
</div>

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		padding: 6px 9px;
		border-radius: 4px;
		vertical-align: middle;
	}
</style>
