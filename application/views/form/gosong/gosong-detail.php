
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'gosong/audit'
	: 'gosong';

$datetime = (new DateTime($gosong->date))->format('d-m-Y');
?>

<div class="container-fluid">
	<h1 class="h3 mb-3 text-gray-800 font-weight-bold">
		Detail Laporan Roti Gosong

		<?php if ($is_audit): ?>
			<span class="badge badge-mode-audit">MODE AUDIT</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white" href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit ? 'Daftar Audit Roti Gosong \ Detail' : 'Daftar Pemeriksaan Roti Gosong'; ?>
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

	<div class="card shadow rounded mb-5">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th colspan="7" class="font-weight-bold table-title">
								LAPORAN ROTI GOSONG
							</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong>Tanggal</strong></td>
							<td colspan="3"><?= html_escape($datetime); ?></td>
							<td><strong>Shift</strong></td>
							<td colspan="2"><?= html_escape($gosong->shift); ?></td>
						</tr>

						<tr>
							<td><strong>Total Berat (Kg)</strong></td>
							<td colspan="6"><?= html_escape($gosong->total_berat); ?></td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">
								<?= $is_audit ? 'DATA SNAPSHOT AUDIT' : 'VERIFIKASI'; ?>
							</th>
						</tr>

						<tr>
							<td><strong>QC</strong></td>
							<td colspan="6"><?= html_escape($gosong->username); ?></td>
						</tr>

						<tr>
							<td><strong>Produksi</strong></td>
							<td colspan="6"><?= html_escape($gosong->nama_produksi ?? '-'); ?></td>
						</tr>

						<?php if (!$is_audit): ?>
							<tr>
								<td><strong>Status Supervisor</strong></td>
								<td colspan="6">
									<?php
									switch ($gosong->status_spv) {
										case 1:
											echo '<span class="text-success font-weight-bold">Verified</span>';
											break;
										case 2:
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
								<td colspan="6">
									<?= !empty($gosong->catatan_spv)
										? html_escape($gosong->catatan_spv)
										: 'Tidak ada'; ?>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<style>
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

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		padding: 6px 10px;
		border-radius: 4px;
		vertical-align: middle;
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
		white-space: normal;
	}

	.table-title {
		background-color: #f8f9fa;
		font-size: 17px;
		font-weight: bold;
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
