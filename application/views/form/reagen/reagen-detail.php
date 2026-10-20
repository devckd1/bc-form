<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'reagen/audit'
	: 'reagen';

$nama_bulan = [
	1  => 'Januari',
	2  => 'Februari',
	3  => 'Maret',
	4  => 'April',
	5  => 'Mei',
	6  => 'Juni',
	7  => 'Juli',
	8  => 'Agustus',
	9  => 'September',
	10 => 'Oktober',
	11 => 'November',
	12 => 'Desember'
];
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			Detail <?= $is_audit ? 'Audit ' : '' ?>Reagen
			<?= $nama_bulan[(int) $bulan] ?? $bulan ?>
			<?= html_escape($tahun) ?>

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
				<a class="text-white"
					href="<?= base_url('reagen/audit'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Release Packing \ Detail
				</a>
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

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check mr-1"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-exclamation-circle mr-1"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Daftar <?= $is_audit ? 'Audit ' : '' ?>Penggunaan Reagen Klorin
			</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead class="text-center thead-light">
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Nama Larutan</th>
							<th>No Lot</th>
							<th>Best Before</th>
							<th>Status SPV</th>
							<th>Aksi</th>
						</tr>
					</thead>

					<tbody>
						<?php if (empty($reagen)): ?>
							<tr>
								<td colspan="7" class="text-center text-muted">
									Tidak ada data
								</td>
							</tr>
						<?php else: ?>
							<?php $no = 1; ?>

							<?php foreach ($reagen as $row): ?>
								<tr>
									<td class="text-center">
										<?= $no++ ?>
									</td>

									<td>
										<?= !empty($row->date)
											? date('d-m-Y', strtotime($row->date))
											: '-' ?>
									</td>

									<td>
										<?= html_escape($row->nama_larutan ?? '-') ?>
									</td>

									<td>
										<?= html_escape($row->no_lot ?? '-') ?>
									</td>

									<td>
										<?= !empty($row->best_before)
											? date('d-m-Y', strtotime($row->best_before))
											: '-' ?>
									</td>

									<td class="text-center">
										<?php if ((int) $row->status_spv === 0): ?>
											<span class="text-secondary font-weight-bold">
												Created
											</span>
										<?php elseif ((int) $row->status_spv === 1): ?>
											<span class="text-success font-weight-bold">
												Verified
											</span>
										<?php else: ?>
											<span class="text-danger font-weight-bold">
												Revision
											</span>
										<?php endif; ?>
									</td>

									<td class="text-center">
										<?php if ($is_audit): ?>
											<a href="<?= base_url('reagen/edit-audit/' . rawurlencode($row->uuid)) ?>"
												class="btn btn-warning btn-sm">
												Edit
											</a>
										<?php else: ?>
											<a href="<?= base_url('reagen/edit/' . rawurlencode($row->uuid)) ?>"
												class="btn btn-warning btn-sm">
												Edit
											</a>

											<a href="<?= base_url('reagen/delete/' . rawurlencode($row->uuid)) ?>"
												class="btn btn-danger btn-sm"
												onclick="return confirm('Yakin hapus data?')">
												Hapus
											</a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<style>
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
