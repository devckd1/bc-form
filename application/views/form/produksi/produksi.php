<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Verifikasi Proses Produksi</h1>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i> <?= $this->session->flashdata('success_msg') ?>
		</div>
		<br>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-times"></i> <?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif; ?>

	<?php
	$tipe_user = $this->session->userdata('tipe_user');
	?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="row mb-3 align-items-center">
				<div class="col-md-6">
					<form method="get" action="<?= base_url('produksi') ?>">
						<div class="input-group" style="max-width: 300px;">
							<input type="text" name="keyword" class="form-control" placeholder="Cari data produksi..." value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>">
							<div class="input-group-append">
								<button class="btn btn-secondary" type="submit"><i class="fas fa-search"></i></button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-6 text-md-right mt-2 mt-md-0">
					<a href="<?= base_url('produksi/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
						<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
					</a>
					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('produksi/audit'); ?>"
							class="btn shadow-sm"
							style="background-color: #5a3298; color: #fff;">

							<i class="fas fa-clipboard-check mr-1"></i>
							Mode Audit

						</a>
					<?php endif; ?>
				</div>
			</div>

			<hr>

			<div class="table-responsive">
				<table class="table table-bordered table-striped" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Date / Shift</th>
							<th>Types of Product</th>
							<th>Production Code</th>
							<th class="text-center">Supervisor</th>
							<th class="text-center">Process</th>
							<th class="text-center">Packing</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($produksi)): ?>
							<?php
							$page = $this->input->get('per_page');
							$no = ($page) ? $page + 1 : 1;
							foreach ($produksi as $val):
								$datetime = new DateTime($val->date);
								$datetime = $datetime->format('d-m-Y');
								$isHalus = ($val->nama_produk === 'BREADCRUMBS MIX SINTETIS HALUS (CPI)');
							?>
								<tr>
									<td class="text-center"><?= $no++; ?></td>
									<td><?= $datetime . ' / ' . $val->shift; ?></td>
									<td><?= htmlspecialchars($val->nama_produk); ?></td>
									<td><?= htmlspecialchars($val->kode_produksi); ?></td>

									<td class="text-center">
										<?php if ($val->status_spv == 0): ?>
											<span style="color:#99a3a4;font-weight:bold;">Created</span>
										<?php elseif ($val->status_spv == 1): ?>
											<span style="color:#28b463;font-weight:bold;">Verified</span>
										<?php elseif ($val->status_spv == 2): ?>
											<span style="color:red;font-weight:bold;">Revision</span>
										<?php endif; ?>
									</td>

									<td class="text-center">
										<?php if (!$isHalus): ?>
											<a href="<?= base_url('produksi/bahan/' . $val->uuid); ?>" class="btn btn-warning btn-sm shadow" title="Raw Material">RM</a>
											<a href="<?= base_url('produksi/mixing/' . $val->uuid); ?>" class="btn btn-info btn-sm shadow" title="Mixing">Mix</a>
											<a href="<?= base_url('produksi/fermentasi/' . $val->uuid); ?>" class="btn btn-success btn-sm shadow" title="Fermentasi">Fermen</a>
											<a href="<?= base_url('produksi/baking/' . $val->uuid); ?>" class="btn btn-danger btn-sm shadow" title="Baking">Bake</a>
										<?php endif; ?>
									</td>

									<td class="text-center">
										<?php if ($isHalus): ?>
											<a href="<?= base_url('produksi/halus/' . $val->uuid); ?>" class="btn btn-primary btn-sm shadow" title="Produksi Halus">Packing</a>
										<?php else: ?>
											<a href="<?= base_url('produksi/stalling/' . $val->uuid); ?>" class="btn btn-warning btn-sm shadow" title="Stalling">Stall</a>
											<a href="<?= base_url('produksi/drying/' . $val->uuid); ?>" class="btn btn-success btn-sm shadow" title="Drying">Dry</a>
											<a href="<?= base_url('produksi/packing/' . $val->uuid); ?>" class="btn btn-danger btn-sm shadow" title="Packing">Pack</a>
										<?php endif; ?>
									</td>

									<td class="text-center">
										<a href="<?= base_url('produksi/edit/' . $val->uuid); ?>" class="btn btn-warning btn-sm shadow" title="Edit">Edit</a>
										<a href="<?= base_url('produksi/detail/' . $val->uuid); ?>" class="btn btn-success btn-sm shadow" title="Detail">Detail</a>
										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
											<a href="<?= base_url('produksi/status/' . $val->uuid); ?>" class="btn btn-info btn-sm shadow" title="Verifikasi">
												<span class="text">Verifikasi</span>
											</a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="8" class="text-center text-muted">Tidak ada data produksi.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<div class="mt-3 text-center">
				<?= isset($pagination) ? $pagination : ''; ?>
			</div>
		</div>
		<!-- Export Excel & Cetak PDF -->
		<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

			<div class="card-body">
				<div class="form-row">
					<div class="form-group col-md-2">
						<label for="tanggal"><strong>Pilih Tanggal:</strong></label>
						<input type="date" id="tanggal" class="form-control" required>
					</div>
					<div class="form-group col-md-2">
						<label for="nama_produk"><strong>Pilih Produk:</strong></label>
						<select id="nama_produk" class="form-control" required>
							<option value="">-- Pilih Produk --</option>
						</select>
					</div>

					<!-- Tombol Export Excel -->
					<div class="form-group align-self-end col-md-2">
						<form id="form-export-excel" action="<?= base_url('produksi/export_excel') ?>" method="post">
							<input type="hidden" name="tanggal" id="tanggal_excel">
							<input type="hidden" name="nama_produk" id="produk_excel">
							<button type="submit" class="btn btn-primary btn-block">
								<i class="fas fa-file-excel"></i> Export Excel
							</button>
						</form>
					</div>

					<!-- Tombol Cetak PDF -->
					<div class="form-group align-self-end col-md-2">
						<form id="form-cetak-pdf" action="<?= base_url('produksi/cetak') ?>" method="post">
							<input type="hidden" name="tanggal" id="tanggal_pdf_hidden">
							<input type="hidden" name="nama_produk" id="produk_pdf_hidden">
							<button type="submit" class="btn btn-success btn-block">
								<i class="fas fa-file-pdf"></i> Cetak PDF
							</button>
						</form>
					</div>
				</div>
			</div>
		<?php endif; ?>

	</div>
</div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
		tooltipTriggerList.forEach(function(el) {
			new bootstrap.Tooltip(el);
		});
	});
</script>

<style>
	th {
		background-color: #f8f9fc;
	}

	td,
	th {
		vertical-align: middle !important;
	}

	.btn {
		min-width: 60px;
	}

	.input-group input {
		border-radius: 5px 0 0 5px !important;
	}

	.input-group .btn {
		border-radius: 0 5px 5px 0 !important;
	}
</style>