<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'reagen/detail-audit/' . date('n', strtotime($reagen->date)) . '/' . date('Y', strtotime($reagen->date))
	: 'reagen/detail/' . date('n', strtotime($reagen->date)) . '/' . date('Y', strtotime($reagen->date));

$form_url = $is_audit
	? 'reagen/edit-audit/' . rawurlencode($reagen->uuid)
	: 'reagen/edit/' . rawurlencode($reagen->uuid);
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Edit <?= $is_audit ? 'Audit ' : '' ?>Verifikasi Penggunaan Reagen Klorin

			<?php if ($is_audit): ?>
				<span class="badge ml-2"
					style="background-color:#5a3298;color:#fff;font-size:12px;">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url) ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Reagen'
						: 'Daftar Verifikasi Penggunaan Reagen Klorin' ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				<?= $is_audit ? 'Edit' : 'Edit' ?>
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>
			<strong>Informasi:</strong>
			Halaman ini merupakan menu Audit Pemeriksaan Release Packing.
			Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
			Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form method="post" action="<?= base_url($form_url) ?>">

				<?php if (!$is_audit): ?>
					<input type="hidden" name="redirect_bulan"
						value="<?= date('n', strtotime($reagen->date)) ?>">
					<input type="hidden" name="redirect_tahun"
						value="<?= date('Y', strtotime($reagen->date)) ?>">
				<?php endif; ?>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Tanggal</label>
						<input type="date" name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : '' ?>"
							value="<?= set_value('date', $reagen->date) ?>">

						<div class="invalid-feedback">
							<?= form_error('date') ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Nama Larutan</label>
						<p class="form-control-plaintext font-weight-bold">
							Reagen C1₂–1
						</p>
						<input type="hidden" name="nama_larutan"
							value="<?= html_escape(set_value('nama_larutan', $reagen->nama_larutan ?? 'Reagen C1₂–1')) ?>">

						<div class="text-danger">
							<?= form_error('nama_larutan') ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">No. Lot</label>
						<input type="text" name="no_lot"
							class="form-control <?= form_error('no_lot') ? 'is-invalid' : '' ?>"
							value="<?= html_escape(set_value('no_lot', $reagen->no_lot)) ?>">

						<div class="invalid-feedback">
							<?= form_error('no_lot') ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Best Before</label>
						<input type="date" name="best_before"
							class="form-control <?= form_error('best_before') ? 'is-invalid' : '' ?>"
							value="<?= set_value('best_before', $reagen->best_before) ?>">

						<div class="invalid-feedback">
							<?= form_error('best_before') ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Tanggal Buka Botol</label>
						<input type="date" name="tgl_buka_botol"
							class="form-control <?= form_error('tgl_buka_botol') ? 'is-invalid' : '' ?>"
							value="<?= set_value('tgl_buka_botol', $reagen->tgl_buka_botol) ?>">

						<div class="invalid-feedback">
							<?= form_error('tgl_buka_botol') ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Volume Penggunaan Larutan (mL)
						</label>
						<input type="text" name="volume_penggunaan"
							class="form-control <?= form_error('volume_penggunaan') ? 'is-invalid' : '' ?>"
							value="<?= html_escape(set_value('volume_penggunaan', $reagen->volume_penggunaan)) ?>">

						<div class="invalid-feedback">
							<?= form_error('volume_penggunaan') ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Volume Akhir Larutan (mL)
						</label>
						<input type="text" name="volume_akhir"
							class="form-control <?= form_error('volume_akhir') ? 'is-invalid' : '' ?>"
							value="<?= html_escape(set_value('volume_akhir', $reagen->volume_akhir)) ?>">

						<div class="invalid-feedback">
							<?= form_error('volume_akhir') ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Catatan (Optional)
						</label>
						<textarea class="form-control"
							name="catatan"><?= html_escape(set_value('catatan', $reagen->catatan)) ?></textarea>

						<div class="text-danger">
							<?= form_error('catatan') ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fas fa-save"></i>
							<?= $is_audit ? 'Simpan' : 'Simpan' ?>
						</button>

						<a href="<?= base_url($back_url) ?>"
							class="btn btn-md btn-danger">
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
	.breadcrumb {
		background-color: #2E86C1;
	}

	.breadcrumb a {
		color: #fff;
	}

	.breadcrumb-item.active {
		color: #f8f9fa;
	}
</style>
