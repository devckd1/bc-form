<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'kekuatanmagnet/audit'
	: 'kekuatanmagnet';

$form_url = $is_audit
	? 'kekuatanmagnet/edit-audit/' . rawurlencode($kekuatanmagnet->uuid)
	: 'kekuatanmagnet/edit/' . rawurlencode($kekuatanmagnet->uuid);

$page_title = $is_audit
	? 'Update Audit Pemeriksaan Kekuatan Magnet Trap'
	: 'Update Pemeriksaan Kekuatan Magnet Trap';

$breadcrumb_title = $is_audit
	? 'Daftar Audit Kekuatan Magnet Trap'
	: 'Daftar Pemeriksaan Kekuatan Magnet Trap';
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		<?= html_escape($page_title); ?>

		<?php if ($is_audit): ?>
			<span class="badge-mode-audit">
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
					<?= html_escape($breadcrumb_title); ?>
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

			Halaman ini merupakan menu Edit Audit Kekuatan Magnet Trap.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<form
				class="user"
				method="post"
				action="<?= base_url($form_url); ?>">

				<div class="form-group row">

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Tanggal
						</label>

						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $kekuatanmagnet->date)); ?>"
							required>

						<div class="invalid-feedback <?= form_error('date') ? 'd-block' : ''; ?>">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Nama Alat
						</label>

						<input
							type="text"
							name="nama_alat"
							class="form-control <?= form_error('nama_alat') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('nama_alat', $kekuatanmagnet->nama_alat)); ?>"
							required>

						<div class="invalid-feedback <?= form_error('nama_alat') ? 'd-block' : ''; ?>">
							<?= form_error('nama_alat'); ?>
						</div>
					</div>

				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Nilai Pengukuran (Gauss)
						</label>

						<input
							type="text"
							name="nilai"
							class="form-control <?= form_error('nilai') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('nilai', $kekuatanmagnet->nilai)); ?>"
							required>

						<div class="invalid-feedback <?= form_error('nilai') ? 'd-block' : ''; ?>">
							<?= form_error('nilai'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Keterangan
						</label>

						<textarea
							class="form-control <?= form_error('keterangan') ? 'invalid' : ''; ?>"
							name="keterangan"
							rows="3"><?= html_escape(set_value('keterangan', $kekuatanmagnet->keterangan)); ?></textarea>

						<div class="invalid-feedback <?= form_error('keterangan') ? 'd-block' : ''; ?>">
							<?= form_error('keterangan'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Catatan
						</label>

						<textarea
							class="form-control <?= form_error('catatan') ? 'invalid' : ''; ?>"
							name="catatan"
							rows="3"><?= html_escape(set_value('catatan', $kekuatanmagnet->catatan)); ?></textarea>

						<div class="invalid-feedback <?= form_error('catatan') ? 'd-block' : ''; ?>">
							<?= form_error('catatan'); ?>
						</div>
					</div>

				</div>

				<div class="row">
					<div class="col">

						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fas fa-save"></i>
							<?= $is_audit ? 'Simpan Audit' : 'Simpan'; ?>
						</button>

						<a href="<?= base_url($back_url); ?>" class="btn btn-md btn-danger">
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

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		padding: 5px 9px;
		border-radius: 4px;
		vertical-align: middle;
	}
</style>