<?php
defined('BASEPATH') or exit('No direct script access allowed');

$isAudit = isset($mode) && $mode === 'audit';

$formUrl = $isAudit
	? base_url('produksi/edit-audit/' . rawurlencode($produksi->uuid))
	: base_url('produksi/edit/' . rawurlencode($produksi->uuid));

$listUrl = $isAudit
	? base_url('produksi/audit')
	: base_url('produksi');

$pageTitle = $isAudit
	? 'Edit Audit Proses Produksi'
	: 'Update Proses Produksi';

$breadcrumbTitle = $isAudit
	? 'Daftar Audit Proses Produksi'
	: 'Daftar Laporan Verifikasi Proses Produksi';
?>

<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">
		<?= html_escape($pageTitle); ?>

		<?php if ($isAudit): ?>
			<span class="badge-mode-audit">MODE AUDIT</span>
		<?php endif; ?>
	</h1>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= $listUrl; ?>">
					<i class="fas fa-arrow-left"></i>
					<?= html_escape($breadcrumbTitle); ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<!-- Info Audit -->
	<?php if ($isAudit): ?>
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

			<form class="user" method="post" action="<?= $formUrl; ?>">

				<!-- Tanggal dan Shift -->
				<div class="form-group row">

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Tanggal
						</label>

						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $produksi->date)); ?>">

						<div class="invalid-feedback <?= form_error('date') ? 'd-block' : ''; ?>">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Shift
						</label>

						<select
							class="form-control <?= form_error('shift') ? 'invalid' : ''; ?>"
							name="shift">
							<option value="1"
								<?= (string) set_value('shift', $produksi->shift) === '1' ? 'selected' : ''; ?>>
								1
							</option>

							<option value="2"
								<?= (string) set_value('shift', $produksi->shift) === '2' ? 'selected' : ''; ?>>
								2
							</option>

							<option value="3"
								<?= (string) set_value('shift', $produksi->shift) === '3' ? 'selected' : ''; ?>>
								3
							</option>
						</select>

						<div class="invalid-feedback <?= form_error('shift') ? 'd-block' : ''; ?>">
							<?= form_error('shift'); ?>
						</div>
					</div>

				</div>

				<!-- Nama Produk dan Kode Produksi -->
				<div class="form-group row">

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Nama Produk
						</label>

						<input
							type="text"
							name="nama_produk"
							class="form-control <?= form_error('nama_produk') ? 'invalid' : ''; ?>"
							value="<?= html_escape($produksi->nama_produk); ?>"
							readonly>

						<div class="invalid-feedback <?= form_error('nama_produk') ? 'd-block' : ''; ?>">
							<?= form_error('nama_produk'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Kode Produksi
						</label>

						<input
							type="text"
							name="kode_produksi"
							class="form-control <?= form_error('kode_produksi') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('kode_produksi', $produksi->kode_produksi)); ?>">

						<div class="invalid-feedback <?= form_error('kode_produksi') ? 'd-block' : ''; ?>">
							<?= form_error('kode_produksi'); ?>
						</div>
					</div>

				</div>

				<!-- Tombol -->
				<div class="row">
					<div class="col">

						<button type="submit" class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i>
							Simpan
						</button>

						<a href="<?= $listUrl; ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i>
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

	.breadcrumb a,
	.breadcrumb-item.active {
		color: #fff;
	}

	.badge-mode-audit {
		display: inline-block;
		padding: 5px 10px;
		margin-left: 5px;
		color: #fff;
		background-color: #5a3298;
		border-radius: 4px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}
</style>
