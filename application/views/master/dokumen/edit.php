<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">Edit Master Dokumen</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white"
					href="<?= base_url('master-dokumen'); ?>">
					<i class="fas fa-arrow-left"></i>
					Master Dokumen \ Edit
				</a>
			</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Form Edit Dokumen
			</h6>
		</div>

		<div class="card-body">

			<?= form_open('master-dokumen/edit/' . rawurlencode($master_dokumen->uuid)); ?>

			<div class="form-group">
				<label for="nomor_dokumen">Nomor Dokumen <span class="text-danger">*</span></label>
				<input
					type="text"
					class="form-control <?= form_error('nomor_dokumen') ? 'is-invalid' : ''; ?>"
					id="nomor_dokumen"
					name="nomor_dokumen"
					value="<?= set_value('nomor_dokumen', $master_dokumen->nomor_dokumen); ?>"
					required>
				<div class="invalid-feedback">
					<?= form_error('nomor_dokumen'); ?>
				</div>
			</div>

			<div class="form-group">
				<label for="nama_dokumen">Nama Dokumen <span class="text-danger">*</span></label>
				<textarea
					class="form-control <?= form_error('nama_dokumen') ? 'is-invalid' : ''; ?>"
					id="nama_dokumen"
					name="nama_dokumen"
					rows="3"
					required><?= set_value('nama_dokumen', $master_dokumen->nama_dokumen); ?></textarea>
				<div class="invalid-feedback">
					<?= form_error('nama_dokumen'); ?>
				</div>
			</div>

			<div class="form-group">
				<label for="tanggal_terbit">Tanggal Terbit <span class="text-danger">*</span></label>
				<input
					type="date"
					class="form-control <?= form_error('tanggal_terbit') ? 'is-invalid' : ''; ?>"
					id="tanggal_terbit"
					name="tanggal_terbit"
					value="<?= set_value('tanggal_terbit', $master_dokumen->tanggal_terbit); ?>"
					required>
				<div class="invalid-feedback">
					<?= form_error('tanggal_terbit'); ?>
				</div>
			</div>

			<div class="form-group">
				<label for="revisi">Revisi <span class="text-danger">*</span></label>
				<input
					type="number"
					class="form-control <?= form_error('revisi') ? 'is-invalid' : ''; ?>"
					id="revisi"
					name="revisi"
					min="0"
					step="1"
					value="<?= set_value('revisi', $master_dokumen->revisi); ?>"
					required>
				<div class="invalid-feedback">
					<?= form_error('revisi'); ?>
				</div>
			</div>

			<div class="form-group">
				<label for="tanggal_revisi">Tanggal Revisi</label>
				<input
					type="date"
					class="form-control <?= form_error('tanggal_revisi') ? 'is-invalid' : ''; ?>"
					id="tanggal_revisi"
					name="tanggal_revisi"
					value="<?= set_value('tanggal_revisi', $master_dokumen->tanggal_revisi); ?>">
				<div class="invalid-feedback">
					<?= form_error('tanggal_revisi'); ?>
				</div>
			</div>

			<hr>

			<button type="submit" class="btn btn-success">
				<i class="fas fa-save"></i> Simpan
			</button>

			<a href="<?= base_url('master-dokumen'); ?>" class="btn btn-danger">
				<i class="fa fa-times"></i> Batal
			</a>

			<?= form_close(); ?>

		</div>
	</div>

</div>

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}
</style>
