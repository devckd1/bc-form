<?php
$is_audit = isset($mode) && $mode === 'audit';
$back_url = $is_audit ? 'retain/audit' : 'retain';

$tanggal = !empty($retain->date)
	? date('d-m-Y', strtotime($retain->date))
	: '-';

$description_data = json_decode($retain->description ?? '[]', true);
if (!is_array($description_data)) {
	$description_data = [];
}

$plant_map = [
	'651ac623-5e48-44cc-b2f6-5d622603f53c' => 'Bread Crumb Cikande',
	'1eb341e0-1ec4-4484-ba8f-32d23352b84d' => 'Bread Crumb Salatiga'
];

$plant_name = $plant_map[$retain->plant] ?? ($retain->nama_plant ?? $retain->plant ?? '-');

$status_spv = $retain->status_spv ?? null;
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Detail Retain Sample Report

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
						? 'Daftar Audit Retain Sample Report \ Detail'
						: 'Daftar Retain Sample Report'; ?>
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
				RETAIN SAMPLE REPORT
			</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-sm">
					<thead class="<?= $is_audit ? 'thead-audit' : 'thead-light'; ?>">
						<tr>
							<th class="text-center" colspan="6">
								RETAIN SAMPLE REPORT
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td style="width: 20%;"><strong>Tanggal</strong></td>
							<td colspan="5"><?= html_escape($tanggal); ?></td>
						</tr>
						<tr>
							<td><strong>Plant</strong></td>
							<td colspan="5"><?= html_escape($plant_name); ?></td>
						</tr>
						<tr>
							<td><strong>Sample Type</strong></td>
							<td colspan="5"><?= html_escape($retain->sample_type ?? '-'); ?></td>
						</tr>
						<tr>
							<td><strong>Sample Storage</strong></td>
							<td colspan="5"><?= html_escape($retain->sample_storage ?? '-'); ?></td>
						</tr>

						<tr class="<?= $is_audit ? 'header-audit text-white' : 'table-secondary'; ?>">
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>Best Before</th>
							<th>Quantity (g)</th>
							<th>Remarks</th>
							<th></th>
						</tr>

						<?php if (!empty($description_data)): ?>
							<?php foreach ($description_data as $item): ?>
								<?php
								$best_before = !empty($item['best_before'])
									? date('d-m-Y', strtotime($item['best_before']))
									: '-';
								?>
								<tr>
									<td><?= html_escape($item['nama_produk'] ?? '-'); ?></td>
									<td><?= html_escape($item['kode_produksi'] ?? '-'); ?></td>
									<td><?= html_escape($best_before); ?></td>
									<td><?= html_escape($item['quantity'] ?? '-'); ?></td>
									<td><?= html_escape($item['remarks'] ?? '-'); ?></td>
									<td></td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="6" class="text-center text-muted">
									Tidak ada data deskripsi.
								</td>
							</tr>
						<?php endif; ?>

						<tr>
							<td><strong>Catatan</strong></td>
							<td colspan="5">
								<?= !empty($retain->catatan)
									? nl2br(html_escape($retain->catatan))
									: '<em>Tidak ada</em>'; ?>
							</td>
						</tr>

						<tr class="<?= $is_audit ? 'header-audit text-white' : 'table-light'; ?>">
							<th class="text-center" colspan="6">VERIFIKASI</th>
						</tr>
						<tr>
							<td><strong>QC</strong></td>
							<td colspan="5">
								<?= html_escape($retain->username ?? '-'); ?>
							</td>
						</tr>
						<tr>
							<td><strong>Status Supervisor</strong></td>
							<td colspan="5">
								<?php
								if ((string) $status_spv === '0') {
									echo '<span class="badge badge-secondary">Created</span>';
								} elseif ((string) $status_spv === '1') {
									echo '<span class="badge badge-success">Verified</span>';
								} elseif ((string) $status_spv === '2') {
									echo '<span class="badge badge-danger">Revision</span>';
								} else {
									echo '-';
								}
								?>
							</td>
						</tr>
						<tr>
							<td><strong>Catatan Supervisor</strong></td>
							<td colspan="5">
								<?= !empty($retain->catatan_spv)
									? nl2br(html_escape($retain->catatan_spv))
									: '<em>Tidak ada</em>'; ?>
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
	.header-audit,
	.thead-audit {
		background-color: #5a3298;
		color: #fff;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		text-decoration: none;
		font-weight: 500;
	}

	.border-left-audit {
		border-left: 5px solid #5a3298;
	}

	.table th,
	.table td {
		vertical-align: middle;
	}

	.badge {
		font-size: 90%;
	}

	.table-responsive {
		overflow-x: auto;
	}

	@media (max-width: 768px) {
		h1.h3 {
			font-size: 20px;
		}
	}
</style>
