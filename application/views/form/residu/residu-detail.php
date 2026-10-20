<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'residu/audit'
	: 'residu';

$back_label = $is_audit
	? 'Daftar Audit Verifikasi Residu Klorin \ Detail'
	: 'Daftar Verifikasi Residu Klorin';

$datetime = !empty($residu->date)
	? date('d-m-Y', strtotime($residu->date))
	: '-';
?>

<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			Detail Verifikasi Residu Klorin
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

	<!-- Detail Table -->
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">

				<table class="table table-bordered" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th colspan="7" class="font-weight-bold">
								VERIFIKASI RESIDU KLORIN
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td><strong>Tanggal</strong></td>
							<td colspan="6">
								<?= html_escape($datetime); ?>
							</td>
						</tr>

						<tr>
							<td><strong>Area</strong></td>
							<td colspan="6">
								<?= html_escape($residu->area ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><strong>Titik Sampling</strong></td>
							<td colspan="6">
								<?= html_escape($residu->titik_sampling ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><strong>Standar</strong></td>
							<td colspan="6">
								<?= html_escape($residu->standar ?? '-'); ?> PPM
							</td>
						</tr>

						<tr>
							<td><strong>Hasil Pemeriksaan</strong></td>
							<td colspan="6">
								<?= html_escape($residu->hasil_pemeriksaan ?? '-'); ?> PPM
							</td>
						</tr>

						<tr>
							<td><strong>Keterangan</strong></td>
							<td colspan="6">
								<?= html_escape($residu->keterangan ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><strong>Tindakan Koreksi</strong></td>
							<td colspan="6">
								<?= html_escape($residu->tindakan ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><strong>Verifikasi</strong></td>
							<td colspan="6">
								<?= html_escape($residu->verifikasi ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><strong>Catatan</strong></td>
							<td colspan="6">
								<?= !empty($residu->catatan)
									? html_escape($residu->catatan)
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>

						<tr>
							<td><strong>QC</strong></td>
							<td colspan="6">
								<?= html_escape($residu->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><strong>Status</strong></td>
							<td colspan="6">
								<?php
								if ((string) ($residu->status_spv ?? '') === '0') {
									echo '<span class="text-secondary font-weight-bold">Created</span>';
								} elseif ((string) ($residu->status_spv ?? '') === '1') {
									echo '<span class="text-success font-weight-bold">Verified</span>';
								} elseif ((string) ($residu->status_spv ?? '') === '2') {
									echo '<span class="text-danger font-weight-bold">Revision</span>';
								} else {
									echo '<span class="text-muted">-</span>';
								}
								?>
							</td>
						</tr>

						<tr>
							<td><strong>Catatan Supervisor</strong></td>
							<td colspan="6">
								<?= !empty($residu->catatan_spv)
									? html_escape($residu->catatan_spv)
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
		padding: 6px 12px;
		border-radius: 6px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
		box-shadow: 0 2px 5px rgba(90, 50, 152, 0.25);
	}

	.breadcrumb {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb .breadcrumb-item a:hover {
		text-decoration: underline;
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
