
<?php
$t = $timbangan;
$detail = $peneraan_hasil ?? [];

$datetime = new DateTime($t->date);
$formattedDate = $datetime->format('d-m-Y');
?>

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	.breadcrumb a {
		color: #fff !important;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 7px 14px;
		margin-left: 8px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 700;
		letter-spacing: .5px;
		vertical-align: middle;
		box-shadow: 0 3px 8px rgba(90, 50, 152, .35);
	}

	.audit-info {
		background-color: #f3e8ff;
		border-left: 4px solid #6f42c1;
		color: #4b2a75;
		padding: 12px 15px;
		border-radius: 4px;
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
			font-size: 13px;
			padding: 7px;
		}

		h1.h3 {
			font-size: 20px;
		}
	}
</style>

<div class="container-fluid">

	<h1 class="h3 mb-3 text-gray-800 font-weight-bold">
		Detail Audit Pemeriksaan Timbangan
		<span class="badge-mode-audit">MODE AUDIT</span>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= site_url('timbangan/audit'); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					Daftar Audit Timbangan
				</a>
			</li>
			<li class="breadcrumb-item active text-white">
				Detail Audit
			</li>
		</ol>
	</nav>

	<div class="audit-info mb-4">
		<strong>Informasi Audit:</strong>
		Data pada halaman ini merupakan salinan audit.
		Perubahan maupun pemeriksaan pada data ini tidak mengubah
		data QC aktual.
	</div>

	<div class="card card-audit shadow mb-4">
		<div class="card-body">

			<div class="table-responsive">
				<table class="table table-bordered" cellspacing="0">

					<thead class="text-center">
						<tr>
							<th colspan="7" class="font-weight-bold">
								PEMERIKSAAN TIMBANGAN
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td colspan="2">
								<b>Tanggal:</b>
								<?= html_escape($formattedDate); ?>
							</td>
							<td colspan="5">
								<b>Shift:</b>
								<?= html_escape($t->shift ?? '-'); ?>
							</td>
						</tr>

						<tr class="bg-light text-center">
							<td colspan="7" class="font-weight-bold">
								Daftar Hasil Pemeriksaan
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th>Kode Timbangan</th>
							<th>Kapasitas</th>
							<th>Model</th>
							<th>Lokasi</th>
							<th>Waktu</th>
							<th>Peneraan Standar</th>
							<th>Hasil</th>
						</tr>

						<?php if (!empty($detail)): ?>
							<?php foreach ($detail as $row): ?>
								<tr class="text-center">
									<td>
										<?= html_escape($row['kode_timbangan'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['kapasitas'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['model'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['lokasi'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['pukul'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['peneraan_standar'] ?? '-'); ?>
									</td>
									<td>
										<?= html_escape($row['hasil'] ?? '-'); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="7" class="text-center text-muted">
									Tidak ada detail pemeriksaan.
								</td>
							</tr>
						<?php endif; ?>

						<tr class="bg-light text-center">
							<td colspan="7" class="font-weight-bold">
								INFORMASI TAMBAHAN
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Keterangan</b></td>
							<td colspan="5">
								<?= nl2br(html_escape($t->keterangan ?? '-')); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Catatan</b></td>
							<td colspan="5">
								<?= nl2br(html_escape($t->catatan ?? '-')); ?>
							</td>
						</tr>

						<tr class="bg-light text-center">
							<td colspan="7" class="font-weight-bold">
								VERIFIKASI
							</td>
						</tr>

						<tr>
							<td><b>QC</b></td>
							<td colspan="6">
								<?= html_escape($t->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Disetujui Supervisor</b></td>
							<td colspan="6">
								<?php
								if ((string) ($t->status_spv ?? '') === '0') {
									echo '<span class="text-secondary font-weight-bold">Created</span>';
								} elseif ((string) ($t->status_spv ?? '') === '1') {
									echo '<span class="text-success font-weight-bold">Verified</span>';
								} elseif ((string) ($t->status_spv ?? '') === '2') {
									echo '<span class="text-danger font-weight-bold">Revision</span>';
								} else {
									echo '-';
								}
								?>
							</td>
						</tr>

						<tr>
							<td><b>Catatan Supervisor</b></td>
							<td colspan="6">
								<?= nl2br(html_escape($t->catatan_spv ?? 'Tidak ada')); ?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
