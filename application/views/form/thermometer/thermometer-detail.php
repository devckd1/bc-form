<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'thermometer/audit'
	: 'thermometer';

$judul = $is_audit
	? 'Detail Audit Peneraan Thermometer'
	: 'Detail Peneraan Thermometer';

$datetime = new DateTime($thermometer->date);
$formattedDate = $datetime->format('d-m-Y');

$result = json_decode($thermometer->peneraan_hasil, true);
if (!is_array($result)) {
	$result = [];
}
?>

<div class="container-fluid">

	<h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">
		<?= $judul; ?>

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
						? 'Daftar Audit Peneraan Thermometer \ Detail'
						: 'Daftar Peneraan Thermometer'; ?>
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
							<th colspan="7" class="font-weight-bold">
								PENERAAN THERMOMETER
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td colspan="2">
								<b>Tanggal:</b> <?= html_escape($formattedDate); ?>
							</td>
							<td colspan="5">
								<b>Shift:</b> <?= html_escape($thermometer->shift ?? '-'); ?>
							</td>
						</tr>

						<tr class="bg-light text-center">
							<td colspan="7" class="font-weight-bold">
								Daftar Hasil Pemeriksaan
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th>No</th>
							<th>Kode Thermometer</th>
							<th>Model</th>
							<th>Area</th>
							<th>Waktu</th>
							<th>Standar Suhu (°C)</th>
							<th>Hasil</th>
						</tr>

						<?php if (!empty($result)): ?>
							<?php $no = 1; ?>
							<?php foreach ($result as $row): ?>
								<tr class="text-center">
									<td><?= $no++; ?></td>
									<td><?= html_escape($row['kode_thermo'] ?? '-'); ?></td>
									<td><?= html_escape($row['model'] ?? '-'); ?></td>
									<td><?= html_escape($row['area'] ?? '-'); ?></td>
									<td><?= html_escape($row['pukul'] ?? '-'); ?></td>
									<td><?= html_escape($row['standar'] ?? '-'); ?></td>
									<td><?= html_escape($row['hasil'] ?? '-'); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="7" class="text-center text-muted">
									Belum ada hasil pemeriksaan.
								</td>
							</tr>
						<?php endif; ?>

						<tr>
							<td><b>Tindakan Perbaikan</b></td>
							<td colspan="6">
								<?= !empty($thermometer->tindakan_perbaikan)
									? nl2br(html_escape($thermometer->tindakan_perbaikan))
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr>
							<td><b>Keterangan</b></td>
							<td colspan="6">
								<?= !empty($thermometer->keterangan)
									? nl2br(html_escape($thermometer->keterangan))
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>

						<tr>
							<td><b>QC</b></td>
							<td colspan="6">
								<?= html_escape($thermometer->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Produksi</b></td>
							<td colspan="6">
								<?= html_escape($thermometer->nama_produksi ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Status Supervisor</b></td>
							<td colspan="6">
								<?php
								switch ((int) ($thermometer->status_spv ?? 0)) {
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
							<td><b>Catatan Supervisor</b></td>
							<td colspan="6">
								<?= !empty($thermometer->catatan_spv)
									? nl2br(html_escape($thermometer->catatan_spv))
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
		border-radius: 5px;
		font-size: 13px;
		font-weight: 700;
		vertical-align: middle;
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

		.badge-mode-audit {
			margin-top: 8px;
		}
	}
</style>
