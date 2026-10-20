
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'pemusnahan/audit'
	: 'pemusnahan';

$form_url = $is_audit
	? 'pemusnahan/edit-audit/' . rawurlencode($pemusnahan->uuid)
	: 'pemusnahan/edit/' . rawurlencode($pemusnahan->uuid);
?>

<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			<?= $is_audit
				? 'Edit Audit Pemusnahan Barang / Produk'
				: 'Update Data Pemusnahan Barang / Produk'; ?>

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit
						? 'Daftar Audit Pemusnahan'
						: 'Daftar Data Pemusnahan Barang / Produk'; ?>
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

			Halaman ini merupakan menu Edit Audit Pemusnahan Barang.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3 <?= $is_audit ? 'header-audit' : ''; ?>">
			<h6 class="m-0 font-weight-bold <?= $is_audit ? 'text-white' : 'text-primary'; ?>">
				Form Pemusnahan Barang / Produk
			</h6>
		</div>

		<div class="card-body">
			<?= form_open_multipart($form_url, ['class' => 'user', 'method' => 'post']); ?>

			<div class="form-group row">
				<div class="col-sm-6">
					<label class="font-weight-bold">Tanggal</label>
					<input
						type="date"
						name="date"
						class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
						value="<?= html_escape(set_value('date', $pemusnahan->date ?? '')); ?>">

					<div class="invalid-feedback">
						<?= form_error('date'); ?>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-6">
					<label class="font-weight-bold">Nama Produk</label>
					<input
						type="text"
						name="nama_produk"
						class="form-control <?= form_error('nama_produk') ? 'is-invalid' : ''; ?>"
						value="<?= html_escape(set_value('nama_produk', $pemusnahan->nama_produk ?? '')); ?>">

					<div class="invalid-feedback">
						<?= form_error('nama_produk'); ?>
					</div>
				</div>

				<div class="col-sm-6">
					<label class="font-weight-bold">Kode Produksi</label>
					<input
						type="text"
						name="kode_produksi"
						class="form-control <?= form_error('kode_produksi') ? 'is-invalid' : ''; ?>"
						value="<?= html_escape(set_value('kode_produksi', $pemusnahan->kode_produksi ?? '')); ?>">

					<div class="invalid-feedback">
						<?= form_error('kode_produksi'); ?>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-6">
					<label class="font-weight-bold">Best Before</label>
					<input
						type="date"
						name="best_before"
						class="form-control <?= form_error('best_before') ? 'is-invalid' : ''; ?>"
						value="<?= html_escape(set_value('best_before', $pemusnahan->best_before ?? '')); ?>">

					<div class="invalid-feedback">
						<?= form_error('best_before'); ?>
					</div>
				</div>
			</div>

			<div class="form-group row">
				<div class="col-sm-6">
					<label class="font-weight-bold">Analisa Masalah</label>
					<textarea
						name="analisa"
						class="form-control"
						rows="5"><?= html_escape(set_value('analisa', $pemusnahan->analisa ?? '')); ?></textarea>

					<div class="text-danger small">
						<?= form_error('analisa'); ?>
					</div>
				</div>

				<div class="col-sm-6">
					<label class="font-weight-bold">Keterangan</label>
					<textarea
						name="keterangan"
						class="form-control"
						rows="5"><?= html_escape(set_value('keterangan', $pemusnahan->keterangan ?? '')); ?></textarea>

					<div class="text-danger small">
						<?= form_error('keterangan'); ?>
					</div>
				</div>
			</div>

			<hr>

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
		padding: 8px 16px;
		border-radius: .25rem;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb .breadcrumb-item a:hover {
		text-decoration: underline;
	}
</style>
