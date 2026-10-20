<?php
defined('BASEPATH') or exit('No direct script access allowed');

$mode = $mode ?? 'qc';
$isAudit = ($mode === 'audit');

$listUrl = $isAudit
	? base_url('seasoning/audit')
	: base_url('seasoning');

$detailTitle = 'Detail Pemeriksaan Seasoning dari Pemasok';

function icon_check($val, $yes = 'sesuai')
{
	if ($val === $yes) {
		return '<span class="text-success font-weight-bold">&#10004;</span>';
	}

	if ($val === 'tidak sesuai') {
		return '<span class="text-danger font-weight-bold">&#10008;</span>';
	}

	return '<span class="text-muted">&minus;</span>';
}

function seasoning_escape($value)
{
	return html_escape($value ?? '');
}
?>

<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			<?= $detailTitle; ?>

			<?php if ($isAudit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb <?= $isAudit ? 'breadcrumb-audit' : ''; ?>">
			<li class="breadcrumb-item">
				<a href="<?= $listUrl; ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $isAudit
						? 'Daftar Audit Pemeriksaan Seasoning dari Pemasok \ Detail'
						: 'Daftar Pemeriksaan Seasoning dari Pemasok'; ?>
				</a>
			</li>
		</ol>
	</nav>

	<?php if ($isAudit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Pemeriksaan Seasoning.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<!-- Detail Card -->
	<div class="card shadow mb-4 <?= $isAudit ? 'card-audit' : ''; ?>">
		<div class="card-body">

			<?php
			$datetime = !empty($seasoning->date)
				? date('d-m-Y', strtotime($seasoning->date))
				: '-';

			$exp = !empty($seasoning->expired)
				? date('d-m-Y', strtotime($seasoning->expired))
				: '-';

			$keteranganMobil = [
				1 => 'Bersih',
				2 => 'Kotor',
				3 => 'Bau',
				4 => 'Bocor',
				5 => 'Basah',
				6 => 'Kering',
				7 => 'Bebas Hama'
			];

			$mobilList = [];

			if (!empty($seasoning->kondisi_mobil)) {
				$mobilList = is_array($seasoning->kondisi_mobil)
					? $seasoning->kondisi_mobil
					: explode(',', $seasoning->kondisi_mobil);
			}
			?>

			<div class="table-responsive">
				<table class="table table-bordered table-detail-seasoning">

					<thead class="text-center">
						<tr>
							<th colspan="7" class="font-weight-bold">
								PEMERIKSAAN SEASONING DARI PEMASOK
							</th>
						</tr>
					</thead>

					<tbody>

						<tr>
							<td colspan="7">
								<b>Tanggal:</b> <?= $datetime; ?>
							</td>
						</tr>

						<tr>
							<td><b>Jenis Seasoning</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->jenis_seasoning); ?></td>
						</tr>

						<tr>
							<td><b>Spesifikasi</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->spesifikasi); ?></td>
						</tr>

						<tr>
							<td><b>Pemasok</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->pemasok); ?></td>
						</tr>

						<tr>
							<td><b>Jenis Mobil</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->jenis_mobil); ?></td>
						</tr>

						<tr>
							<td><b>No. Polisi</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->no_polisi); ?></td>
						</tr>

						<tr>
							<td><b>Identitas Pengantar</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->identitas_pengantar); ?></td>
						</tr>

						<tr>
							<td><b>No. PO / DO</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->no_po); ?></td>
						</tr>

						<!-- Kondisi Mobil -->
						<tr class="section-heading text-center font-weight-bold">
							<th colspan="7">KONDISI MOBIL</th>
						</tr>

						<tr>
							<td colspan="7">
								<?php if (!empty($mobilList)): ?>
									<ul class="mb-0">
										<?php foreach ($mobilList as $m): ?>
											<?php
											$key = trim((string) $m);
											$label = $keteranganMobil[$key] ?? 'Tidak diketahui';
											?>
											<li><?= seasoning_escape($label); ?></li>
										<?php endforeach; ?>
									</ul>
								<?php else: ?>
									<span class="text-muted">Tidak ada data</span>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<td><b>Kode Produksi</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->kode_produksi); ?></td>
						</tr>

						<tr>
							<td><b>Expired Date</b></td>
							<td colspan="6"><?= $exp; ?></td>
						</tr>

						<tr>
							<td><b>Jumlah Barang</b></td>
							<td><b>Sampel (pcs)</b></td>
							<td colspan="5"><b>Jumlah Reject</b></td>
						</tr>

						<tr>
							<td><?= seasoning_escape($seasoning->jumlah_barang); ?></td>
							<td><?= seasoning_escape($seasoning->sampel); ?></td>
							<td colspan="5"><?= seasoning_escape($seasoning->jumlah_reject); ?></td>
						</tr>

						<!-- Kondisi Fisik -->
						<tr class="section-heading text-center">
							<th colspan="7">KONDISI FISIK</th>
						</tr>

						<tr class="text-center">
							<td><b>Kemasan</b></td>
							<td><b>Warna</b></td>
							<td><b>Kotoran</b></td>
							<td colspan="4"><b>Aroma</b></td>
						</tr>

						<tr class="text-center">
							<td><?= icon_check($seasoning->kemasan ?? '', 'sesuai'); ?></td>
							<td><?= icon_check($seasoning->warna ?? '', 'sesuai'); ?></td>
							<td><?= icon_check($seasoning->kotoran ?? '', 'sesuai'); ?></td>
							<td colspan="4"><?= icon_check($seasoning->aroma ?? '', 'sesuai'); ?></td>
						</tr>

						<tr>
							<td><b>Kadar Air</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->kadar_air); ?></td>
						</tr>

						<tr>
							<td><b>Negara Asal Dibuat</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->negara_asal); ?></td>
						</tr>

						<tr>
							<td><b>Segel</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->segel); ?></td>
						</tr>

						<tr>
							<td><b>Penerimaan</b></td>
							<td colspan="6"><?= icon_check($seasoning->penerimaan ?? '', 'ok'); ?></td>
						</tr>

						<!-- Persyaratan Dokumen -->
						<tr class="section-heading text-center">
							<th colspan="7">PERSYARATAN DOKUMEN</th>
						</tr>

						<tr class="text-center">
							<td><b>Logo Halal</b></td>
							<td><b>Halal</b></td>
							<td colspan="2"><b>COA</b></td>
							<td colspan="2"><b>Allergen</b></td>
						</tr>

						<tr class="text-center">
							<td><?= seasoning_escape($seasoning->logo_halal); ?></td>
							<td><?= seasoning_escape($seasoning->sertif_halal); ?></td>
							<td colspan="2"><?= seasoning_escape($seasoning->coa); ?></td>
							<td colspan="2"><?= seasoning_escape($seasoning->allergen); ?></td>
						</tr>

						<!-- Bukti COA -->
						<tr>
							<td><b>Bukti COA</b></td>
							<td colspan="6">
								<?php if (!empty($seasoning->bukti_coa)): ?>
									<a
										href="<?= base_url('uploads/seasoning/' . rawurlencode(basename($seasoning->bukti_coa))); ?>"
										target="_blank"
										rel="noopener noreferrer">
										<i class="fas fa-file-alt mr-1"></i>
										Lihat File COA
									</a>
								<?php else: ?>
									<span class="text-muted">Tidak ada file</span>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<td><b>Keterangan</b></td>
							<td colspan="6">
								<?= !empty($seasoning->keterangan)
									? nl2br(seasoning_escape($seasoning->keterangan))
									: '<span class="text-muted">Tidak ada</span>'; ?>
							</td>
						</tr>

						<tr>
							<td><b>Catatan</b></td>
							<td colspan="6">
								<?= !empty($seasoning->catatan)
									? nl2br(seasoning_escape($seasoning->catatan))
									: '<span class="text-muted">Tidak ada</span>'; ?>
							</td>
						</tr>

						<!-- Verifikasi -->
						<tr class="section-heading text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>

						<tr>
							<td><b>QC</b></td>
							<td colspan="6"><?= seasoning_escape($seasoning->username ?? '-'); ?></td>
						</tr>

						<tr>
							<td><b>Disetujui Supervisor</b></td>
							<td colspan="6">
								<?php
								$statusSpv = $seasoning->status_spv ?? null;

								if ((string) $statusSpv === '0') {
									echo '<span class="text-secondary font-weight-bold">Created</span>';
								} elseif ((string) $statusSpv === '1') {
									echo '<span class="text-success font-weight-bold">Verified</span>';
								} elseif ((string) $statusSpv === '2') {
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
								<?= !empty($seasoning->catatan_spv)
									? nl2br(seasoning_escape($seasoning->catatan_spv))
									: '<span class="text-muted">Tidak ada</span>'; ?>
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
		padding: 6px 10px;
		margin-left: 8px;
		border-radius: 5px;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}

	.breadcrumb {
		background-color: #2E86C1;
		padding: 10px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb-audit {
		background-color: #2E86C1;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
		text-decoration: none;
	}

	.card-audit {
		border-left: 4px solid #5a3298;
	}

	.table-detail-seasoning {
		width: 100%;
		font-size: 15px;
	}

	.table-detail-seasoning th,
	.table-detail-seasoning td {
		padding: 10px 12px;
		vertical-align: middle;
		word-break: break-word;
	}

	.table-detail-seasoning .section-heading th {
		background-color: #dbeafe;
		color: #1f2937;
	}

	.breadcrumb-audit+.card-audit .section-heading th {
		background-color: #eee6f8;
	}

	.table-detail-seasoning .text-center th,
	.table-detail-seasoning .text-center td {
		text-align: center;
	}

	@media (max-width: 768px) {
		.table-detail-seasoning {
			font-size: 13px;
		}

		.table-detail-seasoning th,
		.table-detail-seasoning td {
			padding: 7px;
		}

		.badge-mode-audit {
			margin: 6px 0 0;
		}
	}
</style>
