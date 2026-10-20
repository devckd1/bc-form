<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'produksi/audit'
	: 'produksi';

$form_url = $is_audit
	? 'produksi/baking-audit/' . rawurlencode($produksi->uuid)
	: 'produksi/baking/' . rawurlencode($produksi->uuid);
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		Proses Electric Baking

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
						? 'Daftar Audit Verifikasi Electric Baking'
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
					ELECTRIC BAKING
				</label>

				<div class="form-group row">

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Suhu Produk (80 - 97°C)
						</label>
						<?php
						$electric_baking_suhu = $produksi->electric_baking_suhu ?? '';

						if (
							$is_audit &&
							trim((string) $electric_baking_suhu) !== ''
						) {
							if (
								!is_numeric($electric_baking_suhu) ||
								(float) $electric_baking_suhu < 80 ||
								(float) $electric_baking_suhu > 97
							) {
								$electric_baking_suhu = '88.5';
							}
						}
						?>

						<input type="text"
							name="electric_baking_suhu"
							class="form-control <?= form_error('electric_baking_suhu') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('electric_baking_suhu', $electric_baking_suhu)); ?>">

						<div class="invalid-feedback">
							<?= form_error('electric_baking_suhu'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							No. Mesin
						</label>
						<input type="text"
							name="electric_baking_mesin"
							class="form-control <?= form_error('electric_baking_mesin') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('electric_baking_mesin', $produksi->electric_baking_mesin)); ?>">

						<div class="invalid-feedback">
							<?= form_error('electric_baking_mesin'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Expand Roti (%)
						</label>
						<input type="text"
							name="electric_baking_expand"
							class="form-control <?= form_error('electric_baking_expand') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('electric_baking_expand', $produksi->electric_baking_expand)); ?>">

						<div class="invalid-feedback">
							<?= form_error('electric_baking_expand'); ?>
						</div>
					</div>

				</div>

				<hr>

				<label class="form-label font-weight-bold">SENSORI</label>

				<div class="form-group row">

					<?php
					$sensori_fields = [
						'sens_kematangan' => 'Kematangan',
						'sens_rasa'       => 'Rasa',
						'sens_aroma'      => 'Aroma',
						'sens_tekstur'    => 'Tekstur',
						'sens_warna'      => 'Warna',
					];
					?>

					<?php foreach ($sensori_fields as $field => $label): ?>
						<?php

						$current_value = set_value(
							$field,
							isset($produksi->$field) ? $produksi->$field : ''
						);

						if ($is_audit && $current_value !== '') {
							if ($current_value === 'tidak') {
								$current_value = 'oke';
							}
						}
						?>

						<div class="col-sm-2">
							<label class="form-label font-weight-bold mb-2">
								<?= $label; ?>
							</label>

							<?php foreach (['oke' => 'Oke', 'tidak' => 'Tidak'] as $value => $text): ?>
								<div class="form-check">
									<input type="radio"
										name="<?= $field; ?>"
										value="<?= $value; ?>"
										class="form-check-input <?= form_error($field) ? 'is-invalid' : ''; ?>"
										<?= $current_value === $value ? 'checked' : ''; ?>>

									<label class="form-check-label">
										<?= $text; ?>
									</label>
								</div>
							<?php endforeach; ?>

							<div class="invalid-feedback <?= form_error($field) ? 'd-block' : ''; ?>">
								<?= form_error($field); ?>
							</div>
						</div>

					<?php endforeach; ?>

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
