<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'inventaris/audit'
	: 'inventaris';

$back_label = $is_audit
	? 'Daftar Audit Verifikasi Inventaris \ Detail'
	: 'Daftar Checklist Inventaris Peralatan QC Bread Crumb';

$datetime = !empty($inventaris->date)
	? date('d-m-Y', strtotime($inventaris->date))
	: '-';

$equipment = json_decode($inventaris->peralatan ?? '[]', true);

if (!is_array($equipment)) {
	$equipment = [];
}
?>

<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			Detail Checklist Inventaris Peralatan QC Bread Crumb

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

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">

				<table class="table table-bordered" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th colspan="7" class="text-center font-weight-bold">
								CHECKLIST INVENTARIS PERALATAN QC BREAD CRUMB
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td colspan="2">
								<b>Tanggal:</b>
								<?= html_escape($datetime); ?>
							</td>
							<td>
								<b>Shift:</b>
								<?= html_escape($inventaris->shift ?? '-'); ?>
							</td>
							<td colspan="4"></td>
						</tr>

						<tr class="bg-light text-center">
							<td colspan="7" class="font-weight-bold">Daftar Alat</td>
						</tr>

						<tr class="table-primary text-center">
							<th>No</th>
							<th>Nama Alat</th>
							<th>Jumlah</th>
							<th>Awal Shift</th>
							<th>Akhir Shift</th>
							<th colspan="2">Keterangan</th>
						</tr>

						<?php if (!empty($equipment)): ?>
							<?php $no = 1;
							foreach ($equipment as $row): ?>
								<tr>
									<td class="text-center"><?= $no++ ?></td>
									<td><?= html_escape($row['nama_alat'] ?? '-'); ?></td>
									<td><?= html_escape($row['jumlah'] ?? '-'); ?></td>
									<td><?= html_escape($row['kondisi_awal'] ?? '-'); ?></td>
									<td><?= html_escape($row['kondisi_akhir'] ?? '-'); ?></td>
									<td colspan="2"><?= html_escape($row['keterangan'] ?? '-'); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="7" class="text-center text-muted">
									Data peralatan tidak tersedia.
								</td>
							</tr>
						<?php endif; ?>

						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>

						<tr>
							<td colspan="2"><b>QC Awal Shift</b></td>
							<td colspan="5">
								<?= html_escape($inventaris->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>QC Akhir Shift</b></td>
							<td colspan="5">
								<?= html_escape($inventaris->qc_update ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Status Supervisor</b></td>
							<td colspan="5">
								<?php
								switch ((string) ($inventaris->status_spv ?? '0')) {
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
							<td colspan="2"><b>Catatan Supervisor</b></td>
							<td colspan="5">
								<?= !empty($inventaris->catatan_spv)
									? html_escape($inventaris->catatan_spv)
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
		width: 100%;
		font-size: 15px;
		background-color: #fff;
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
