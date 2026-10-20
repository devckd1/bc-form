<?php
$is_audit = isset($mode) && $mode === 'audit';
$back_url = $is_audit ? 'pemusnahan/audit' : 'pemusnahan';

$formattedDate = !empty($pemusnahan->date)
	? date('d-m-Y', strtotime($pemusnahan->date))
	: '-';

$bestBefore = !empty($pemusnahan->best_before)
	? date('d-m-Y', strtotime($pemusnahan->best_before))
	: '-';
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			Detail Data Pemusnahan Barang

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb <?= $is_audit ? 'breadcrumb-qc' : 'breadcrumb-qc'; ?>">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit
						? 'Daftar Audit Pemusnahan Barang / Detail'
						: 'Daftar Data Pemusnahan Barang / Produk'; ?>
				</a>
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>
			<strong>Informasi Audit:</strong>
			Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan untuk keperluan pemeriksaan serta verifikasi.
			Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-header py-3 <?= $is_audit ? 'header-audit' : ''; ?>">
			<h6 class="m-0 font-weight-bold <?= $is_audit ? 'text-white' : 'text-primary'; ?>">
				DATA PEMUSNAHAN BARANG / PRODUK
			</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" cellspacing="0">
					<tbody>
						<tr>
							<th width="25%">Tanggal</th>
							<td><?= html_escape($formattedDate); ?></td>
						</tr>
						<tr>
							<th>Nama Produk</th>
							<td><?= html_escape($pemusnahan->nama_produk ?? '-'); ?></td>
						</tr>
						<tr>
							<th>Kode Produksi</th>
							<td><?= html_escape($pemusnahan->kode_produksi ?? '-'); ?></td>
						</tr>
						<tr>
							<th>Best Before</th>
							<td><?= html_escape($bestBefore); ?></td>
						</tr>
						<tr>
							<th>Analisa Masalah</th>
							<td><?= nl2br(html_escape($pemusnahan->analisa ?? '-')); ?></td>
						</tr>
						<tr>
							<th>Keterangan</th>
							<td><?= nl2br(html_escape($pemusnahan->keterangan ?? '-')); ?></td>
						</tr>

						<tr class="<?= $is_audit ? 'header-audit' : 'table-primary'; ?>">
							<th colspan="2" class="text-center <?= $is_audit ? 'text-white' : ''; ?>">
								VERIFIKASI
							</th>
						</tr>
						<tr>
							<th>QC</th>
							<td><?= html_escape($pemusnahan->username ?? '-'); ?></td>
						</tr>
						<tr>
							<th>Disetujui Supervisor</th>
							<td>
								<?php
								$status_spv = $pemusnahan->status_spv ?? null;

								if ($status_spv == 0) {
									echo '<span class="text-secondary font-weight-bold">Created</span>';
								} elseif ($status_spv == 1) {
									echo '<span class="text-success font-weight-bold">Verified</span>';
								} elseif ($status_spv == 2) {
									echo '<span class="text-danger font-weight-bold">Revision</span>';
								} else {
									echo '-';
								}
								?>
							</td>
						</tr>
						<tr>
							<th>Catatan Supervisor</th>
							<td>
								<?= !empty($pemusnahan->catatan_spv)
									? nl2br(html_escape($pemusnahan->catatan_spv))
									: 'Tidak ada'; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<style>
	.badge-mode-audit {
		display: inline-block;
		background: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 4px;
		font-size: 12px;
		vertical-align: middle;
		letter-spacing: .5px;
	}

	.breadcrumb {
		padding: 10px 16px;
		border-radius: 4px;
	}

	.breadcrumb-qc {
		background-color: #2E86C1;
	}

	.breadcrumb-audit,
	.header-audit {
		background-color: #5a3298;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.border-left-audit {
		border-left: 5px solid #5a3298;
	}

	.table {
		width: 100%;
		font-size: 15px;
	}

	.table td,
	.table th {
		padding: 10px 12px;
		vertical-align: middle;
		word-break: break-word;
	}

	@media (max-width: 768px) {

		.table td,
		.table th {
			font-size: 14px;
			padding: 8px;
		}

		h1.h3 {
			font-size: 20px;
		}
	}
</style>
