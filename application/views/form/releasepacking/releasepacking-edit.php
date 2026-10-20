
<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit ? 'releasepacking/audit' : 'releasepacking';

$form_action = $is_audit
	? 'releasepacking/edit-audit/' . $releasepacking->uuid
	: 'releasepacking/edit/' . $releasepacking->uuid;
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			<?= $is_audit ? 'Edit Audit Data Release Packing' : 'Update Data Release Packing'; ?>

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
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Release Packing'
						: 'Daftar Data Release Packing'; ?>
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

			Halaman ini merupakan menu Edit Audit Release Packing.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form
				class="user"
				method="post"
				action="<?= base_url($form_action); ?>"
				enctype="multipart/form-data">

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Tanggal</label>
						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $releasepacking->date ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('date'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Nama Produk</label>
						<input
							type="text"
							name="nama_produk"
							class="form-control <?= form_error('nama_produk') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('nama_produk', $releasepacking->nama_produk ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('nama_produk'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Kode Produksi</label>
						<input
							type="text"
							name="kode_produksi"
							class="form-control <?= form_error('kode_produksi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('kode_produksi', $releasepacking->kode_produksi ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('kode_produksi'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Best Before</label>
						<input
							type="date"
							name="best_before"
							class="form-control <?= form_error('best_before') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('best_before', $releasepacking->best_before ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('best_before'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Jumlah</label>
						<input
							type="text"
							name="jumlah"
							class="form-control <?= form_error('jumlah') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('jumlah', $releasepacking->jumlah ?? '')); ?>">
						<div class="invalid-feedback">
							<?= form_error('jumlah'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Keterangan</label>
						<textarea
							class="form-control <?= form_error('keterangan') ? 'is-invalid' : ''; ?>"
							name="keterangan"
							rows="3"><?= html_escape(set_value('keterangan', $releasepacking->keterangan ?? '')); ?></textarea>
						<div class="invalid-feedback">
							<?= form_error('keterangan'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fas fa-save"></i> Simpan
						</button>

						<a href="<?= base_url($back_url); ?>" class="btn btn-md btn-danger">
							<i class="fas fa-times"></i> Batal
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
		padding: 6px 10px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}
</style>
