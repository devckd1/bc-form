<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="container-fluid">

	<!-- PAGE HEADING -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Proses Produksi
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>

	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white"
					href="<?= base_url('produksi'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Pemeriksaan .
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<!-- FLASH MESSAGE -->
	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			<?= html_escape($this->session->flashdata('success_msg')); ?>
			<button type="button" class="close" data-dismiss="alert">
				<span>&times;</span>
			</button>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<?= html_escape($this->session->flashdata('error_msg')); ?>
			<button type="button" class="close" data-dismiss="alert">
				<span>&times;</span>
			</button>
		</div>
	<?php endif; ?>

	<!-- TABEL AUDIT -->
	<div class="card shadow mb-4">
		<div class="card-body">

			<div class="table-responsive">
				<table class="table table-bordered table-striped" id="dataTable"
					width="100%" cellspacing="0">

					<thead>
						<tr>
							<th class="text-center font-weight-bold" width="40">No</th>
							<th class="font-weight-bold">Date / Shift</th>
							<th class="font-weight-bold">Types of Product</th>
							<th class="font-weight-bold">Production Code</th>
							<th class="text-center font-weight-bold">Supervisor</th>
							<th class="text-center font-weight-bold">Process</th>
							<th class="text-center font-weight-bold">Packing</th>
							<th class="text-center font-weight-bold">Action</th>
						</tr>
					</thead>

					<tbody>
						<?php if (!empty($produksi)): ?>
							<?php $no = 1; ?>

							<?php foreach ($produksi as $val): ?>
								<?php
								$tanggal = !empty($val->date)
									? date('d-m-Y', strtotime($val->date))
									: '-';

								$shift = $val->shift ?? '-';
								$namaProduk = $val->nama_produk ?? '-';
								$kodeProduksi = $val->kode_produksi ?? '-';
								$uuid = rawurlencode($val->uuid);

								$isHalus = (
									$namaProduk ===
									'BREADCRUMBS MIX SINTETIS HALUS (CPI)'
								);

								$statusSpv = (int) ($val->status_spv ?? 0);
								?>

								<tr>
									<!-- NO -->
									<td class="text-center">
										<?= $no++; ?>
									</td>

									<!-- DATE / SHIFT -->
									<td>
										<?= html_escape($tanggal); ?>
										/
										<?= html_escape($shift); ?>
									</td>

									<!-- PRODUCT -->
									<td>
										<?= html_escape($namaProduk); ?>
									</td>

									<!-- PRODUCTION CODE -->
									<td>
										<?= html_escape($kodeProduksi); ?>
									</td>

									<!-- STATUS SUPERVISOR -->
									<td class="text-center">
										<?php if ($statusSpv === 0): ?>
											<span style="color:#99a3a4;font-weight:bold;">
												Created
											</span>
										<?php elseif ($statusSpv === 1): ?>
											<span style="color:#28b463;font-weight:bold;">
												Verified
											</span>
										<?php elseif ($statusSpv === 2): ?>
											<span style="color:red;font-weight:bold;">
												Revision
											</span>
										<?php else: ?>
											<span class="text-muted">-</span>
										<?php endif; ?>
									</td>

									<!-- PROCESS -->
									<td class="text-center text-nowrap">
										<?php if (!$isHalus): ?>

											<a href="<?= base_url('produksi/bahan-audit/' . $uuid); ?>"
												class="btn btn-warning btn-sm shadow mb-1"
												title="Raw Material">
												RM
											</a>

											<a href="<?= base_url('produksi/mixing-audit/' . $uuid); ?>"
												class="btn btn-info btn-sm shadow mb-1"
												title="Mixing">
												Mix
											</a>

											<a href="<?= base_url('produksi/fermentasi-audit/' . $uuid); ?>"
												class="btn btn-success btn-sm shadow mb-1"
												title="Fermentasi">
												Fermen
											</a>

											<a href="<?= base_url('produksi/baking-audit/' . $uuid); ?>"
												class="btn btn-danger btn-sm shadow mb-1"
												title="Baking">
												Bake
											</a>

										<?php else: ?>
											<span class="text-muted">-</span>
										<?php endif; ?>
									</td>

									<!-- PACKING -->
									<td class="text-center text-nowrap">
										<?php if ($isHalus): ?>

											<a href="<?= base_url('produksi/halus-audit/' . $uuid); ?>"
												class="btn btn-primary btn-sm shadow mb-1"
												title="Packing Halus">
												Packing
											</a>

										<?php else: ?>

											<a href="<?= base_url('produksi/stalling-audit/' . $uuid); ?>"
												class="btn btn-warning btn-sm shadow mb-1"
												title="Stalling">
												Stall
											</a>

											<a href="<?= base_url('produksi/drying-audit/' . $uuid); ?>"
												class="btn btn-success btn-sm shadow mb-1"
												title="Drying">
												Dry
											</a>

											<a href="<?= base_url('produksi/packing-audit/' . $uuid); ?>"
												class="btn btn-danger btn-sm shadow mb-1"
												title="Packing">
												Pack
											</a>

										<?php endif; ?>
									</td>

									<!-- ACTION -->
									<td class="text-center">
										<a href="<?= base_url('produksi/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-sm shadow" title="Detail">Detail</a>
										<a href="<?= base_url('produksi/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-sm shadow" title="Edit">Edit</a>
									</td>
								</tr>

							<?php endforeach; ?>

						<?php else: ?>
							<tr>
								<td colspan="8" class="text-center">
									Belum ada data Audit Produksi.
								</td>
							</tr>
						<?php endif; ?>
					</tbody>

				</table>
			</div>

			<!-- CETAK PDF -->
			<div class="mt-4">
				<form action="<?= base_url('produksi/cetak-audit'); ?>"
					method="post"
					class="form-inline">

					<div class="form-group mr-2 mb-2">
						<label for="tanggal_pdf" class="mr-2">
							<strong>Pilih Tanggal:</strong>
						</label>

						<input type="date"
							id="tanggal_pdf"
							name="tanggal"
							class="form-control"
							required>
					</div>

					<button type="submit" class="btn btn-success mb-2">
						<i class="fas fa-file-pdf mr-1"></i>
						Cetak PDF
					</button>
				</form>
			</div>

		</div>
	</div>

</div>

<style>
	#dataTable thead th {
		font-weight: 700 !important;
		vertical-align: middle;
	}

	#dataTable tbody td {
		vertical-align: middle;
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

	th {
		background-color: #f8f9fc;
	}

	.breadcrumb {
		background-color: #2E86C1;
	}

	th {
		background-color: #f8f9fc;
	}

	.breadcrumb a {
		color: #fff !important;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	/* Label Mode Audit */
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 6px 12px;
		border-radius: 6px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
		box-shadow: 0 2px 5px rgba(90, 50, 152, 0.25);
	}
</style>
