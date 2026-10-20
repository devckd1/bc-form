<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'produksi/audit'
	: 'produksi';

$form_url = $is_audit
	? 'produksi/fermentasi-audit/' . rawurlencode($produksi->uuid)
	: 'produksi/fermentasi/' . rawurlencode($produksi->uuid);
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		Proses Fermentasi
		<?php if ($is_audit): ?>
			<span class="badge ml-2"
				style="background:#5a3298;color:#fff;font-size:12px;">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Verifikasi Proses Fermentasi'
						: 'Daftar Laporan Verifikasi Proses Produksi'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<!-- Info Audit -->
	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Proses Produksi.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form class="user" method="post"
				action="<?= base_url($form_url); ?>">

				<label class="form-label font-weight-bold">
					Produk: <?= html_escape($produksi->nama_produk); ?>
				</label>
				<br>

				<label class="form-label font-weight-bold">
					Kode Produksi: <?= html_escape($produksi->kode_produksi); ?>
				</label>

				<hr>

				<label class="form-label font-weight-bold">
					FERMENTASI
				</label>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Suhu (34 - 36°C)
						</label>

						<?php
						$fermen_suhu = $produksi->fermen_suhu ?? '';

						if ($is_audit && trim((string) $fermen_suhu) !== '') {
							if (
								!is_numeric($fermen_suhu) ||
								(float) $fermen_suhu < 34 ||
								(float) $fermen_suhu > 36
							) {
								$fermen_suhu = '35';
							}
						}
						?>

						<input type="text"
							name="fermen_suhu"
							class="form-control <?= form_error('fermen_suhu') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('fermen_suhu', $fermen_suhu)); ?>">

						<div class="invalid-feedback">
							<?= form_error('fermen_suhu'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							RH (78 - 82%)
						</label>

						<?php
						$fermen_rh = $produksi->fermen_rh ?? '';

						if ($is_audit && trim((string) $fermen_rh) !== '') {
							if (
								!is_numeric($fermen_rh) ||
								(float) $fermen_rh < 78 ||
								(float) $fermen_rh > 82
							) {
								$fermen_rh = '80';
							}
						}
						?>

						<input type="text"
							name="fermen_rh"
							class="form-control <?= form_error('fermen_rh') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('fermen_rh', $fermen_rh)); ?>">

						<div class="invalid-feedback">
							<?= form_error('fermen_rh'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Jam Mulai
						</label>
						<input type="time"
							name="fermen_jam_mulai"
							class="form-control <?= form_error('fermen_jam_mulai') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('fermen_jam_mulai', $produksi->fermen_jam_mulai)); ?>">

						<div class="invalid-feedback">
							<?= form_error('fermen_jam_mulai'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Jam Selesai
						</label>
						<input type="time"
							name="fermen_jam_selesai"
							class="form-control <?= form_error('fermen_jam_selesai') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('fermen_jam_selesai', $produksi->fermen_jam_selesai)); ?>">

						<div class="invalid-feedback">
							<?= form_error('fermen_jam_selesai'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Lama Proses (60 - 70 Menit)
						</label>

						<?php
						$fermen_lama_proses = $produksi->fermen_lama_proses ?? '';

						if ($is_audit && trim((string) $fermen_lama_proses) !== '') {
							if (
								!is_numeric($fermen_lama_proses) ||
								(float) $fermen_lama_proses < 60 ||
								(float) $fermen_lama_proses > 70
							) {
								$fermen_lama_proses = '65';
							}
						}
						?>

						<input type="text"
							name="fermen_lama_proses"
							class="form-control <?= form_error('fermen_lama_proses') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('fermen_lama_proses', $fermen_lama_proses)); ?>">

						<div class="invalid-feedback">
							<?= form_error('fermen_lama_proses'); ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>

						<a href="<?= base_url($back_url); ?>"
							class="btn btn-md btn-danger">
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
</style>
