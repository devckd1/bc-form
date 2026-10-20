<?php
$is_audit = isset($mode) && $mode === 'audit';
$back_url = $is_audit ? 'metal/audit' : 'metal';

$datetime = !empty($metal->date_metal)
	? date('d-m-Y', strtotime($metal->date_metal))
	: '-';

$timing = !empty($metal->time)
	? date('H:i', strtotime($metal->time))
	: '-';

function tampilkanIkonMetal($nilai)
{
	if ($nilai === null || $nilai === '') {
		return '<span style="color:gray;font-weight:bold;">-</span>';
	}

	if ($nilai === 'terdeteksi') {
		return '<span style="color:green;font-weight:bold;">&#10004;</span>';
	}

	return '<span style="color:red;font-weight:bold;">&#10006;</span>';
}

$deteksi_ng = '-';
if ((string) $metal->deteksi_ng === '1') {
	$deteksi_ng = 'Belt Conveyor Berhenti';
} elseif ((string) $metal->deteksi_ng === '2') {
	$deteksi_ng = 'Rejector';
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Detail Pemeriksaan Metal Detector

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
						? 'Daftar Audit Metal Detector / Detail'
						: 'Daftar Data Metal Detector'; ?>
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
				<table class="table table-bordered">
					<thead class="text-center">
						<tr>
							<th colspan="5" class="font-weight-bold">
								PEMERIKSAAN METAL DETECTOR
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td>Tanggal</td>
							<td colspan="4"><?= html_escape($datetime); ?></td>
						</tr>
						<tr>
							<td>Shift</td>
							<td colspan="4"><?= html_escape($metal->shift ?? '-'); ?></td>
						</tr>
						<tr>
							<td>Pukul</td>
							<td colspan="4"><?= html_escape($timing); ?></td>
						</tr>
						<tr>
							<td>Jenis Produk</td>
							<td colspan="4"><?= html_escape($metal->nama_produk ?? '-'); ?></td>
						</tr>
						<tr>
							<td>Kode Produksi</td>
							<td colspan="4"><?= html_escape($metal->kode_produksi ?? '-'); ?></td>
						</tr>
						<tr>
							<td>No. Program</td>
							<td colspan="4"><?= html_escape($metal->no_program ?? '-'); ?></td>
						</tr>
						<tr>
							<td>Deteksi NG</td>
							<td colspan="4"><?= html_escape($deteksi_ng); ?></td>
						</tr>

						<tr class="table-primary text-center">
							<td><b>STD. Spesimen</b></td>
							<td><b>Depan</b></td>
							<td><b>Tengah</b></td>
							<td><b>Belakang</b></td>
						</tr>
						<tr>
							<td><b>Fe <?= html_escape($metal->std_fe ?? '-'); ?></b></td>
							<td class="text-center"><?= tampilkanIkonMetal($metal->fe_d ?? null); ?></td>
							<td class="text-center"><?= tampilkanIkonMetal($metal->fe_t ?? null); ?></td>
							<td class="text-center"><?= tampilkanIkonMetal($metal->fe_b ?? null); ?></td>
						</tr>
						<tr>
							<td><b>Non Fe <?= html_escape($metal->std_nonfe ?? '-'); ?></b></td>
							<td class="text-center"><?= tampilkanIkonMetal($metal->nonfe_d ?? null); ?></td>
							<td class="text-center"><?= tampilkanIkonMetal($metal->nonfe_t ?? null); ?></td>
							<td class="text-center"><?= tampilkanIkonMetal($metal->nonfe_b ?? null); ?></td>
						</tr>
						<tr>
							<td><b>SUS 304 <?= html_escape($metal->std_sus304 ?? '-'); ?></b></td>
							<td class="text-center"><?= tampilkanIkonMetal($metal->sus_d ?? null); ?></td>
							<td class="text-center"><?= tampilkanIkonMetal($metal->sus_t ?? null); ?></td>
							<td class="text-center"><?= tampilkanIkonMetal($metal->sus_b ?? null); ?></td>
						</tr>

						<tr>
							<td>Keterangan</td>
							<td colspan="4"><?= nl2br(html_escape($metal->keterangan ?? '-')); ?></td>
						</tr>
						<tr>
							<td>Catatan</td>
							<td colspan="4">
								<?= !empty($metal->catatan_metal)
									? nl2br(html_escape($metal->catatan_metal))
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center">
							<td colspan="5"><b>VERIFIKASI</b></td>
						</tr>
						<tr>
							<td>QC</td>
							<td colspan="4"><?= html_escape($metal->username_1 ?? '-'); ?></td>
						</tr>
						<tr>
							<td>Produksi</td>
							<td colspan="4"><?= html_escape($metal->nama_produksi_metal ?? '-'); ?></td>
						</tr>
						<tr>
							<td>Disetujui Supervisor</td>
							<td colspan="4">
								<?php
								switch ((int) ($metal->status_spv ?? 0)) {
									case 1:
										echo '<span class="text-success font-weight-bold">Verified</span>';
										break;
									case 2:
										echo '<span class="text-danger font-weight-bold">Revision</span>';
										break;
									default:
										echo '<span class="text-secondary font-weight-bold">Created</span>';
								}
								?>
							</td>
						</tr>
						<tr>
							<td>Catatan SPV</td>
							<td colspan="4">
								<?= !empty($metal->catatan_spv)
									? nl2br(html_escape($metal->catatan_spv))
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
		vertical-align: middle;
		background: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
	}

	.breadcrumb-qc {
		background-color: #2E86C1;
	}

	.alert-audit {
		background: #f1eafb;
		border: 1px solid #5a3298;
		color: #452477;
	}

	.table {
		width: 100%;
		font-size: 15px;
		margin: 0 auto;
	}

	.table th,
	.table td {
		padding: 10px;
		border: 1px solid #dee2e6;
		white-space: normal !important;
	}

	.table td:first-child {
		font-weight: bold;
		width: 200px;
	}

	@media (max-width: 768px) {

		.table th,
		.table td {
			font-size: 14px;
		}
	}
</style>
