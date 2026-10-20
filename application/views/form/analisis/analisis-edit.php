
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'analisis/audit'
	: 'analisis';

$form_url = $is_audit
	? 'analisis/edit-audit/' . rawurlencode($analisis->uuid)
	: 'analisis/edit/' . rawurlencode($analisis->uuid);

$storage_value = set_value(
	'penyimpanan',
	isset($analisis->penyimpanan) ? $analisis->penyimpanan : ''
);

$storage_options = ['Dry'];

$is_custom_storage = !in_array($storage_value, $storage_options)
	&& $storage_value !== '';
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Update Permohonan Analisis Sampel Laboratorium

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">MODE AUDIT</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Analisis Laboratorium'
						: 'Daftar Permohonan Analisis Sampel Laboratorium'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Permohonan Analisis Sampel Laboratorium.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form class="user" method="post" action="<?= base_url($form_url); ?>">

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Tanggal</label>
						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $analisis->date)); ?>">
						<div class="invalid-feedback <?= form_error('date') ? 'd-block' : ''; ?>">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Tipe Sampel</label>
						<input
							type="text"
							name="tipe_sampel"
							class="form-control <?= form_error('tipe_sampel') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('tipe_sampel', $analisis->tipe_sampel)); ?>">
						<div class="invalid-feedback <?= form_error('tipe_sampel') ? 'd-block' : ''; ?>">
							<?= form_error('tipe_sampel'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Penyimpanan</label>

						<select
							id="penyimpanan_select"
							class="form-control <?= form_error('penyimpanan') ? 'is-invalid' : ''; ?>"
							onchange="updatePenyimpananValue()">
							<option value="">-- Pilih Storage --</option>

							<?php foreach ($storage_options as $option): ?>
								<option
									value="<?= html_escape($option); ?>"
									<?= !$is_custom_storage && $storage_value === $option ? 'selected' : ''; ?>>
									<?= html_escape($option); ?>
								</option>
							<?php endforeach; ?>

							<option value="Other" <?= $is_custom_storage ? 'selected' : ''; ?>>
								Other
							</option>
						</select>

						<div
							id="penyimpanan_lainnya_div"
							style="margin-top: 10px; display: <?= $is_custom_storage ? 'block' : 'none'; ?>;">
							<input
								type="text"
								id="penyimpanan_lainnya_input"
								class="form-control"
								placeholder="Masukkan storage lain"
								value="<?= $is_custom_storage ? html_escape($storage_value) : ''; ?>"
								oninput="updatePenyimpananValue()">
						</div>

						<input
							type="hidden"
							name="penyimpanan"
							id="penyimpanan"
							value="<?= html_escape($storage_value); ?>">

						<div class="invalid-feedback <?= form_error('penyimpanan') ? 'd-block' : ''; ?>">
							<?= form_error('penyimpanan'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Nama Produk</label>
						<input
							type="text"
							name="nama_produk"
							class="form-control <?= form_error('nama_produk') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('nama_produk', $analisis->nama_produk)); ?>">
						<div class="invalid-feedback <?= form_error('nama_produk') ? 'd-block' : ''; ?>">
							<?= form_error('nama_produk'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Production Code</label>
						<input
							type="text"
							name="kode_produksi"
							class="form-control <?= form_error('kode_produksi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('kode_produksi', $analisis->kode_produksi)); ?>">
						<div class="invalid-feedback <?= form_error('kode_produksi') ? 'd-block' : ''; ?>">
							<?= form_error('kode_produksi'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Best Before</label>
						<input
							type="date"
							name="best_before"
							class="form-control <?= form_error('best_before') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('best_before', $analisis->best_before)); ?>">
						<div class="invalid-feedback <?= form_error('best_before') ? 'd-block' : ''; ?>">
							<?= form_error('best_before'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Jumlah Sampel (g)</label>
						<input
							type="text"
							name="jumlah_sampel"
							class="form-control <?= form_error('jumlah_sampel') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jumlah_sampel', $analisis->jumlah_sampel)); ?>">
						<div class="invalid-feedback <?= form_error('jumlah_sampel') ? 'd-block' : ''; ?>">
							<?= form_error('jumlah_sampel'); ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i> Simpan
						</button>

						<a href="<?= base_url($back_url); ?>" class="btn btn-md btn-danger">
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

	.breadcrumb .breadcrumb-item a {
		color: #fff;
	}

	.badge-mode-audit {
		display: inline-block;
		padding: 6px 10px;
		border-radius: 4px;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		vertical-align: middle;
	}
</style>

<script>
	function updatePenyimpananValue() {
		const select = document.getElementById('penyimpanan_select');
		const inputDiv = document.getElementById('penyimpanan_lainnya_div');
		const inputField = document.getElementById('penyimpanan_lainnya_input');
		const hiddenField = document.getElementById('penyimpanan');

		if (select.value === 'Other') {
			inputDiv.style.display = 'block';
			hiddenField.value = inputField.value;
		} else {
			inputDiv.style.display = 'none';
			hiddenField.value = select.value;
		}
	}

	document.addEventListener('DOMContentLoaded', updatePenyimpananValue);
</script>
