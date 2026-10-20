<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'kebersihanmesin/audit'
	: 'kebersihanmesin';

$page_title = 'Detail Pemeriksaan Kebersihan dan Sanitasi Setelah Perbaikan Mesin';

$tanggal = !empty($kebersihanmesin->date)
	? date('d-m-Y', strtotime($kebersihanmesin->date))
	: '-';

$tanggal_perbaikan = !empty($kebersihanmesin->tgl_perbaikan)
	? date('d-m-Y', strtotime($kebersihanmesin->tgl_perbaikan))
	: '-';

$status_spv = [
	0 => '<span class="text-secondary font-weight-bold">Created</span>',
	1 => '<span class="text-success font-weight-bold">Verified</span>',
	2 => '<span class="text-danger font-weight-bold">Revision</span>'
];
?>

<div class="container-fluid">

	<h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">
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
					<?= $is_audit
						? 'Daftar Audit Kebersihan dan Sanitasi Mesin'
						: 'Daftar Pemeriksaan'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Detail
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

	<div class="card shadow mb-5">
		<div class="card-body">
			<div class="table-responsive">

				<table class="table table-bordered" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th colspan="7" class="font-weight-bold">
								PEMERIKSAAN KEBERSIHAN DAN SANITASI SETELAH PERBAIKAN MESIN
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td><b>Tanggal</b></td>
							<td colspan="6"><?= $tanggal; ?></td>
						</tr>

						<tr>
							<td><b>Shift</b></td>
							<td colspan="6">
								<?= html_escape($kebersihanmesin->shift ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Mesin / Peralatan</b></td>
							<td colspan="6">
								<?= html_escape($kebersihanmesin->mesin ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Jenis Perbaikan</b></td>
							<td colspan="6">
								<?= html_escape($kebersihanmesin->perbaikan ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Area</b></td>
							<td colspan="6">
								<?= html_escape($kebersihanmesin->area ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Tanggal Perbaikan</b></td>
							<td colspan="6"><?= $tanggal_perbaikan; ?></td>
						</tr>

						<tr class="bg-light text-center font-weight-bold">
							<td colspan="3">Kondisi</td>
							<td colspan="4">Spare Part yang Tertinggal</td>
						</tr>

						<tr class="text-center">
							<td colspan="3">
								<?= html_escape($kebersihanmesin->kondisi ?? '-'); ?>
							</td>
							<td colspan="4">
								<?= html_escape($kebersihanmesin->spare_part ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Keterangan</b></td>
							<td colspan="6">
								<?= !empty($kebersihanmesin->keterangan)
									? html_escape($kebersihanmesin->keterangan)
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center font-weight-bold">
							<td colspan="7">VERIFIKASI</td>
						</tr>

						<tr>
							<td><b>QC</b></td>
							<td colspan="6">
								<?= html_escape($kebersihanmesin->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Produksi</b></td>
							<td colspan="6">
								<?= html_escape($kebersihanmesin->nama_produksi ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Disetujui Supervisor</b></td>
							<td colspan="6">
								<?= $status_spv[$kebersihanmesin->status_spv ?? 0] ?? '-'; ?>
							</td>
						</tr>

						<tr>
							<td><b>Catatan Supervisor</b></td>
							<td colspan="6">
								<?= !empty($kebersihanmesin->catatan_spv)
									? html_escape($kebersihanmesin->catatan_spv)
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
	.breadcrumb {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb .breadcrumb-item a,
	.breadcrumb .breadcrumb-item.active {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb .breadcrumb-item a:hover {
		text-decoration: underline;
	}

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
