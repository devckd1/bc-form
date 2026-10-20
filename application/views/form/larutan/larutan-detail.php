<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'larutan/audit'
	: 'larutan';

$back_label = $is_audit
	? 'Daftar Audit Verifikasi Larutan \ Detail'
	: 'Daftar Verifikasi Pembuatan Larutan Cleaning dan Sanitasi';

$datetime = !empty($larutan->date)
	? date('d-m-Y', strtotime($larutan->date))
	: '-';

$bagian_list = json_decode($larutan->nama_bahan ?? '[]', true);

if (!is_array($bagian_list)) {
	$bagian_list = [];
}
?>

<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			Detail Verifikasi Pembuatan Larutan Cleaning dan Sanitasi
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
					<?= html_escape($back_label); ?>
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

	<div class="card shadow mb-5 rounded">
		<div class="card-body">

			<div class="table-responsive">
				<table class="table table-bordered" cellspacing="0" width="100%">

					<thead class="text-center">
						<tr>
							<th colspan="8" class="font-weight-bold">
								PEMBUATAN LARUTAN CLEANING DAN SANITASI
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td><strong>Tanggal</strong></td>
							<td colspan="3">
								<?= html_escape($datetime); ?>
							</td>

							<td><strong>Shift</strong></td>
							<td colspan="3">
								<?= html_escape($larutan->shift ?? '-'); ?>
							</td>
						</tr>

						<tr class="bg-light text-center">
							<th>Nama Bahan</th>
							<th>Kadar</th>
							<th>Kimia (ml)</th>
							<th>Air (ml)</th>
							<th>Volume</th>
							<th>Kebutuhan</th>
							<th>Keterangan</th>
							<th>Tindakan & Verifikasi</th>
						</tr>

						<?php if (!empty($bagian_list)): ?>
							<?php foreach ($bagian_list as $row): ?>
								<tr class="text-center">
									<td>
										<?= html_escape($row['bahan'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['kadar'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['bahan_kimia'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['air_bersih'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['volume_akhir'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['kebutuhan'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['keterangan'] ?? '-'); ?>
									</td>
									<td class="text-left">
										<?php if (!empty($row['tindakan'])): ?>
											<strong>T:</strong>
											<?= html_escape($row['tindakan']); ?>
											<br>
										<?php endif; ?>

										<?php if (!empty($row['verifikasi'])): ?>
											<strong>V:</strong>
											<?= html_escape($row['verifikasi']); ?>
										<?php endif; ?>

										<?php if (
											empty($row['tindakan']) &&
											empty($row['verifikasi'])
										): ?>
											-
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="8" class="text-center text-muted">
									Data bahan larutan tidak tersedia.
								</td>
							</tr>
						<?php endif; ?>

						<tr>
							<td><strong>Catatan</strong></td>
							<td colspan="7">
								<?= !empty($larutan->catatan)
									? nl2br(html_escape($larutan->catatan))
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="8">VERIFIKASI</th>
						</tr>

						<tr>
							<td><strong>QC</strong></td>
							<td colspan="7">
								<?= html_escape($larutan->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><strong>Produksi</strong></td>
							<td colspan="7">
								<?= html_escape($larutan->nama_produksi ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><strong>Status Supervisor</strong></td>
							<td colspan="7">
								<?php
								switch ((string) ($larutan->status_spv ?? '0')) {
									case '1':
										echo '<span class="text-success font-weight-bold">Verified</span>';
										break;
									case '2':
										echo '<span class="text-danger font-weight-bold">Revision</span>';
										break;
									default:
										echo '<span class="text-secondary font-weight-bold">Created</span>';
										break;
								}
								?>
							</td>
						</tr>

						<tr>
							<td><strong>Catatan Supervisor</strong></td>
							<td colspan="7">
								<?= !empty($larutan->catatan_spv)
									? nl2br(html_escape($larutan->catatan_spv))
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
		background-color: #5a3298;
		color: #fff;
		padding: 7px 12px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		white-space: nowrap;
	}

	.breadcrumb {
		background-color: #2E86C1;
		padding: 10px 16px;
		border-radius: 0.35rem;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb .breadcrumb-item a:hover {
		text-decoration: underline;
	}

	.table {
		font-size: 15px;
		background-color: #fff;
	}

	.table th,
	.table td {
		padding: 10px 14px;
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
