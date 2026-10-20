<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit False Rejection
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>
	</div>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-audit">
			<li class="breadcrumb-item">
				<a href="<?= base_url('falserejection'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Laporan false rejection
				</a>
			</li>
		</ol>
	</nav>

	<!-- Informasi Audit -->
	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi Audit:</strong>
		Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan untuk keperluan pemeriksaan serta verifikasi.
		Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<div class="card shadow mb-4 card-audit">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th>No.</th>
							<th>Tanggal Produksi</th>
							<th>Tanggal Monitoring</th>
							<th>Shift</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>No. Mesin</th>
							<th>Jenis Kontaminasi</th>
							<th>Status SPV</th>
							<th>Aksi</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($falserejection)) : ?>
							<?php $no = 1; ?>
							<?php foreach ($falserejection as $row) : ?>
								<tr>
									<td class="text-center"><?= $no++; ?></td>

									<td>
										<?php
										$tanggal_produksi = $row->date_metal ?? '';
										echo !empty($tanggal_produksi) && $tanggal_produksi !== '0000-00-00'
											? html_escape(date('d-m-Y', strtotime($tanggal_produksi)))
											: '-';
										?>
									</td>

									<td>
										<?php
										$tanggal_monitoring = $row->date_false_rejection ?? '';
										echo !empty($tanggal_monitoring) && $tanggal_monitoring !== '0000-00-00'
											? html_escape(date('d-m-Y', strtotime($tanggal_monitoring)))
											: '-';
										?>
									</td>

									<td class="text-center">
										<?= !empty($row->shift_monitoring) ? html_escape($row->shift_monitoring) : '-'; ?>
									</td>

									<td>
										<?= !empty($row->nama_produk) ? html_escape($row->nama_produk) : '-'; ?>
									</td>

									<td>
										<?= !empty($row->kode_produksi) ? html_escape($row->kode_produksi) : '-'; ?>
									</td>

									<td>
										<?= !empty($row->no_mesin) ? html_escape($row->no_mesin) : '-'; ?>
									</td>

									<td>
										<?= !empty($row->jenis_kontaminasi) ? html_escape($row->jenis_kontaminasi) : '-'; ?>
									</td>

									<td class="text-center">
										<?php if ((string) ($row->status_spv_false ?? '') === '0') : ?>
											<span class="badge badge-secondary">Created</span>
										<?php elseif ((string) ($row->status_spv_false ?? '') === '1') : ?>
											<span class="badge badge-success">Verified</span>
										<?php elseif ((string) ($row->status_spv_false ?? '') === '2') : ?>
											<span class="badge badge-danger">Revision</span>
										<?php else : ?>
											<span class="badge badge-warning">Belum Diverifikasi</span>
										<?php endif; ?>
									</td>

									<td class="text-center" style="min-width: 160px;">
										<a href="<?= base_url('falserejection/detail-audit/' . $row->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('falserejection/edit-audit/' . $row->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
											<span class="text">Edit</span>
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr>
								<td colspan="10" class="text-center text-muted">
									<i class="fas fa-inbox mr-1"></i>
									Belum ada data Audit False Rejection.
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
			<br>
			<hr>
			<div class="form-group">
				<form action="<?= base_url('falserejection/cetak-audit') ?>" method="post" class="form-inline">
					<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
					<input type="date" name="tanggal" class="form-control mr-2" required>
					<button type="submit" class="btn btn-success">
						<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
					</button>
				</form>
			</div>
		</div>
	</div>
</div>

<style>
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		font-weight: 700;
		padding: 6px 10px;
		border-radius: 5px;
		vertical-align: middle;
		margin-left: 5px;
	}

	.btn-edit-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-edit-audit:hover {
		background-color: #452577;
		border-color: #452577;
		color: #fff;
	}

	.breadcrumb {
		background-color: #2E86C1;
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

	.card-audit {
		border: 1px solid #e3d7f5 !important;
		border-radius: 8px;
	}

	#dataTable th,
	#dataTable td {
		vertical-align: middle;
	}

	#dataTable thead th {
		background-color: #f8f9fc;
		white-space: nowrap;
	}
</style>