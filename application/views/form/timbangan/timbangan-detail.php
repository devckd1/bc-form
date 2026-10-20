<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'timbangan/audit'
	: 'timbangan';

$judul = $is_audit
	? 'Detail Audit Pemeriksaan Timbangan'
	: 'Detail Pemeriksaan Timbangan';

$datetime = new DateTime($timbangan->date);
$formattedDate = $datetime->format('d-m-Y');

$result = json_decode($timbangan->peneraan_hasil, true);
if (!is_array($result)) {
	$result = [];
}
?>

<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			<?= $judul; ?>

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
					<?= $is_audit
						? 'Daftar Audit Verifikasi Timbangan \ Detail'
						: 'Daftar Pemeriksaan Timbangan'; ?>
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

	<!-- Card Detail -->
	<div class="card shadow mb-4">
		<div class="card-header py-3 <?= $is_audit ? 'header-audit' : ''; ?>">
			<h6 class="m-0 font-weight-bold <?= $is_audit ? 'text-white' : 'text-primary'; ?>">
				<i class="fas fa-weight mr-1"></i>
				PEMERIKSAAN TIMBANGAN
			</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" cellspacing="0">
					<tbody>
						<tr>
							<td colspan="2">
								<b>Tanggal:</b>
								<?= htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8'); ?>
							</td>
							<td colspan="5">
								<b>Shift:</b>
								<?= htmlspecialchars((string) $timbangan->shift, ENT_QUOTES, 'UTF-8'); ?>
							</td>
						</tr>

						<tr class="bg-light text-center">
							<th colspan="7">Daftar Hasil Pemeriksaan</th>
						</tr>

						<tr class="table-primary text-center">
							<th>Kode Timbangan</th>
							<th>Kapasitas</th>
							<th>Model</th>
							<th>Lokasi</th>
							<th>Waktu</th>
							<th>Standar Peneraan</th>
							<th>Hasil</th>
						</tr>

						<?php if (!empty($result)): ?>
							<?php foreach ($result as $row): ?>
								<tr class="text-center">
									<td>
										<?= htmlspecialchars((string) ($row['kode_timbangan'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
									</td>
									<td>
										<?= htmlspecialchars((string) ($row['kapasitas'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
									</td>
									<td>
										<?= htmlspecialchars((string) ($row['model'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
									</td>
									<td>
										<?= htmlspecialchars((string) ($row['lokasi'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
									</td>
									<td>
										<?= htmlspecialchars((string) ($row['pukul'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
									</td>
									<td>
										<?= htmlspecialchars((string) ($row['peneraan_standar'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
									</td>
									<td>
										<?= htmlspecialchars((string) ($row['hasil'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="7" class="text-center text-muted">
									Belum ada detail hasil pemeriksaan.
								</td>
							</tr>
						<?php endif; ?>

						<tr class="bg-light text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>

						<tr>
							<td><b>QC</b></td>
							<td colspan="6">
								<?= htmlspecialchars((string) ($timbangan->username ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Disetujui Supervisor</b></td>
							<td colspan="6">
								<?php
								$status_spv = $timbangan->status_spv ?? null;

								if ((string) $status_spv === '0') {
									echo '<span class="text-secondary font-weight-bold">Created</span>';
								} elseif ((string) $status_spv === '1') {
									echo '<span class="text-success font-weight-bold">Verified</span>';
								} elseif ((string) $status_spv === '2') {
									echo '<span class="text-danger font-weight-bold">Revision</span>';
								} else {
									echo '<span class="text-muted">-</span>';
								}
								?>
							</td>
						</tr>

						<tr>
							<td><b>Catatan Supervisor</b></td>
							<td colspan="6">
								<?= htmlspecialchars((string) ($timbangan->catatan_spv ?: 'Tidak ada'), ENT_QUOTES, 'UTF-8'); ?>
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
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}

	.header-audit {
		background-color: #5a3298;
		color: #fff;
	}

	.breadcrumb {
		background-color: <?= $is_audit ? '#2E86C1' : '#2E86C1'; ?>;
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
