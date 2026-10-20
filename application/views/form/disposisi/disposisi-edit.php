<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit ? 'disposisi/audit' : 'disposisi';

$form_url = $is_audit
	? 'disposisi/edit-audit/' . rawurlencode($disposisi->uuid)
	: 'disposisi/edit/' . rawurlencode($disposisi->uuid);

$options = ['Produk', 'Prosedur', 'Material'];
$selected = set_value(
	'disposisi',
	isset($disposisi->disposisi) ? $disposisi->disposisi : ''
);
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			<?= $is_audit
				? 'Edit Audit Disposisi Produk dan Prosedur'
				: 'Update Disposisi Produk dan Prosedur'; ?>

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit
						? 'Daftar Audit Disposisi'
						: 'Daftar Disposisi Produk dan Prosedur'; ?>
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

			Halaman ini merupakan menu Edit Audit Disposisi Produk dan Prosedur.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3 <?= $is_audit ? 'header-audit' : ''; ?>">
			<h6 class="m-0 font-weight-bold <?= $is_audit ? 'text-white' : 'text-primary'; ?>">
				Form Disposisi Produk dan Prosedur
			</h6>
		</div>

		<div class="card-body">
			<?= form_open($form_url, ['class' => 'user', 'method' => 'post']); ?>

			<div class="form-group row">
				<div class="col-sm-3">
					<label class="font-weight-bold">Tanggal</label>
					<input type="date" name="date"
						class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
						value="<?= html_escape(set_value('date', $disposisi->date ?? '')); ?>">
					<div class="invalid-feedback">
						<?= form_error('date'); ?>
					</div>
				</div>

				<div class="col-sm-3">
					<label class="font-weight-bold">Nomor</label>
					<input type="text" name="nomor"
						class="form-control <?= form_error('nomor') ? 'is-invalid' : ''; ?>"
						value="<?= html_escape(set_value('nomor', $disposisi->nomor ?? '')); ?>">
					<div class="invalid-feedback">
						<?= form_error('nomor'); ?>
					</div>
				</div>

				<div class="col-sm-6">
					<label class="font-weight-bold">Kepada</label>
					<input type="text" name="kepada"
						class="form-control <?= form_error('kepada') ? 'is-invalid' : ''; ?>"
						value="<?= html_escape(set_value('kepada', $disposisi->kepada ?? '')); ?>">
					<div class="invalid-feedback">
						<?= form_error('kepada'); ?>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-6">
					<label class="font-weight-bold">Disposisi</label><br>

					<?php foreach ($options as $option): ?>
						<div class="form-check form-check-inline">
							<input
								class="form-check-input <?= form_error('disposisi') ? 'is-invalid' : ''; ?>"
								type="radio"
								name="disposisi"
								id="disposisi_<?= strtolower($option); ?>"
								value="<?= $option; ?>"
								<?= $selected === $option ? 'checked' : ''; ?>>

							<label class="form-check-label"
								for="disposisi_<?= strtolower($option); ?>">
								<?= $option; ?>
							</label>
						</div>
					<?php endforeach; ?>

					<div class="text-danger small">
						<?= form_error('disposisi'); ?>
					</div>
				</div>
			</div>

			<hr>

			<div class="row form-group">
				<div class="col-sm-6">
					<label class="font-weight-bold">Dasar Disposisi</label>
					<textarea name="dasar_disposisi"
						class="form-control"
						style="height:150px;"><?= html_escape(set_value('dasar_disposisi', $disposisi->dasar_disposisi ?? '')); ?></textarea>
					<div class="text-danger small">
						<?= form_error('dasar_disposisi'); ?>
					</div>
				</div>

				<div class="col-sm-6">
					<label class="font-weight-bold">Uraian Disposisi</label>
					<textarea name="uraian_disposisi"
						class="form-control"
						style="height:150px;"><?= html_escape(set_value('uraian_disposisi', $disposisi->uraian_disposisi ?? '')); ?></textarea>
					<div class="text-danger small">
						<?= form_error('uraian_disposisi'); ?>
					</div>
				</div>
			</div>

			<div class="row form-group">
				<div class="col-sm-6">
					<label class="font-weight-bold">Catatan</label>
					<textarea name="catatan"
						class="form-control"
						style="height:150px;"><?= html_escape(set_value('catatan', $disposisi->catatan ?? '')); ?></textarea>
					<div class="text-danger small">
						<?= form_error('catatan'); ?>
					</div>
				</div>
			</div>

			<hr>

			<div class="form-group row">
				<div class="col-sm-6">
					<label class="font-weight-bold">CC</label>
					<input type="text" name="cc"
						class="form-control <?= form_error('cc') ? 'is-invalid' : ''; ?>"
						value="<?= html_escape(set_value('cc', $disposisi->cc ?? '')); ?>">
					<div class="invalid-feedback">
						<?= form_error('cc'); ?>
					</div>
				</div>
			</div>

			<div class="mt-3">
				<button type="submit" class="btn btn-success mr-2">
					<i class="fas fa-save mr-1"></i> Simpan
				</button>

				<a href="<?= base_url($back_url); ?>" class="btn btn-danger">
					<i class="fas fa-times mr-1"></i> Batal
				</a>
			</div>

			<?= form_close(); ?>
		</div>
	</div>
</div>

<style>
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

	.header-audit {
		background-color: #5a3298;
	}

	.breadcrumb {
		background-color: <?= $is_audit ? '#2E86C1' : '#2E86C1'; ?>;
		border-radius: .25rem;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}
</style>
