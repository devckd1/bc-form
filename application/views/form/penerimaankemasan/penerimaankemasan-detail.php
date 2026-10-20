<?php
$isAudit = isset($mode) && $mode === 'audit';

$backUrl = $isAudit
	? base_url('penerimaankemasan/audit')
	: base_url('penerimaankemasan');

$formattedDate = !empty($penerimaankemasan->date)
	? date('d-m-Y', strtotime($penerimaankemasan->date))
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

function icon_check_penerimaan($value)
{
	if ($value === 'sesuai') {
		return '<span class="text-success font-weight-bold">&#10004;</span>';
	}

	if ($value === 'tidak sesuai') {
		return '<span class="text-danger font-weight-bold">&#10006;</span>';
	}

	return '&minus;';
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			Detail Pemeriksaan Penerimaan Kemasan dari Supplier

			<?php if ($isAudit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb <?= $isAudit ? 'breadcrumb-qc' : 'breadcrumb-qc'; ?>">
			<li class="breadcrumb-item">
				<a href="<?= $backUrl; ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $isAudit ? 'Daftar Audit \ Detail' : 'Daftar Pemeriksaan'; ?>
				</a>
			</li>
		</ol>
	</nav>

	<?php if ($isAudit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>
			<strong>Informasi Audit:</strong>
			Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan untuk keperluan pemeriksaan serta verifikasi.
			Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body table-responsive">

			<table class="table table-bordered" cellspacing="0">
				<thead class="text-center">
					<tr>
						<th colspan="9" class="font-weight-bold">
							PEMERIKSAAN PENERIMAAN KEMASAN DARI SUPPLIER
						</th>
					</tr>
				</thead>

				<tbody>
					<?php
					$infoUmum = [
						'Tanggal' => $formattedDate,
						'Shift' => $penerimaankemasan->shift ?? '-',
						'Jenis Kemasan' => $penerimaankemasan->jenis_kemasan ?? '-',
						'Pemasok' => $penerimaankemasan->pemasok ?? '-',
						'Jenis Mobil' => $penerimaankemasan->jenis_mobil ?? '-',
						'No. Polisi' => $penerimaankemasan->no_polisi ?? '-',
						'Identitas Pengantar' => $penerimaankemasan->identitas_pengantar ?? '-',
						'No. PO / DO' => $penerimaankemasan->no_po ?? '-',
						'Kode Produksi' => $penerimaankemasan->kode_produksi ?? '-'
					];

					foreach ($infoUmum as $label => $value):
					?>
						<tr>
							<td colspan="3"><b><?= html_escape($label); ?></b></td>
							<td colspan="6">
								<?= html_escape((string) ($value ?: '-')); ?>
							</td>
						</tr>
					<?php endforeach; ?>

					<tr class="section-heading text-center font-weight-bold">
						<th colspan="9">KONDISI MOBIL</th>
					</tr>

					<tr>
						<td colspan="9">
							<?php
							$mobil = $penerimaankemasan->kondisi_mobil ?? '';

							if (is_array($mobil)) {
								$mobilList = $mobil;
							} elseif ($mobil !== '') {
								$mobilList = explode(',', $mobil);
							} else {
								$mobilList = [];
							}

							if (!empty($mobilList)):
							?>
								<ul class="mb-0">
									<?php foreach ($mobilList as $m): ?>
										<li>
											<?= html_escape(
												$keteranganMobil[trim((string) $m)]
													?? 'Tidak diketahui'
											); ?>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php else: ?>
								Tidak ada data
							<?php endif; ?>
						</td>
					</tr>

					<tr class="bg-light text-center font-weight-bold">
						<td colspan="3">Jumlah Datang</td>
						<td colspan="3">Sampel (pcs)</td>
						<td colspan="3">Jumlah Reject</td>
					</tr>

					<tr class="text-center">
						<td colspan="3">
							<?= html_escape((string) ($penerimaankemasan->jumlah_datang ?? '-')); ?>
						</td>
						<td colspan="3">
							<?= html_escape((string) ($penerimaankemasan->sampel ?? '-')); ?>
						</td>
						<td colspan="3">
							<?= html_escape((string) ($penerimaankemasan->jumlah_reject ?? '-')); ?>
						</td>
					</tr>

					<tr class="section-heading text-center font-weight-bold">
						<th colspan="9">KONDISI FISIK</th>
					</tr>

					<tr class="text-center">
						<?php
						$fisik = [
							'Warna',
							'Panjang',
							'Diameter',
							'Lebar',
							'Tinggi',
							'Berat',
							'Delaminasi',
							'Bau',
							'Desain'
						];
						foreach ($fisik as $f):
						?>
							<th class="vertical"><?= html_escape($f); ?></th>
						<?php endforeach; ?>
					</tr>

					<tr class="text-center">
						<?php
						$cek = [
							'warna',
							'panjang',
							'diameter',
							'lebar',
							'tinggi',
							'berat',
							'delaminasi',
							'bau',
							'desain'
						];
						foreach ($cek as $c):
						?>
							<td>
								<?= icon_check_penerimaan(
									$penerimaankemasan->$c ?? ''
								); ?>
							</td>
						<?php endforeach; ?>
					</tr>

					<tr>
						<td colspan="3"><b>Segel</b></td>
						<td colspan="6">
							<?= html_escape((string) ($penerimaankemasan->segel ?: '-')); ?>
						</td>
					</tr>

					<tr>
						<td colspan="3"><b>Penerimaan</b></td>
						<td colspan="6">
							<?php
							$penerimaan = $penerimaankemasan->penerimaan ?? '';

							echo icon_check_penerimaan(
								$penerimaan === 'ok'
									? 'sesuai'
									: ($penerimaan === 'tolak' ? 'tidak sesuai' : '')
							);

							if ($penerimaan === 'ok') {
								echo ' <span class="text-success">Diterima</span>';
							} elseif ($penerimaan === 'tolak') {
								echo ' <span class="text-danger">Ditolak</span>';
							}
							?>
						</td>
					</tr>

					<tr>
						<td colspan="3"><b>COA</b></td>
						<td colspan="3">
							<?php
							$coa = $penerimaankemasan->coa ?? '';

							echo icon_check_penerimaan(
								$coa === 'ada'
									? 'sesuai'
									: ($coa === 'tidak ada' ? 'tidak sesuai' : '')
							);
							?>
							<?= html_escape((string) ($coa ?: '-')); ?>
						</td>
						<td colspan="3">
							<?php if (!empty($penerimaankemasan->bukti_coa)): ?>
								<a
									href="<?= base_url(
												'uploads/penerimaan_kemasan/'
													. rawurlencode(basename($penerimaankemasan->bukti_coa))
											); ?>"
									target="_blank"
									rel="noopener noreferrer">
									<i class="fas fa-file-alt mr-1"></i> Link COA
								</a>
							<?php else: ?>
								&minus;
							<?php endif; ?>
						</td>
					</tr>

					<tr>
						<td colspan="3"><b>Keterangan</b></td>
						<td colspan="6">
							<?= nl2br(html_escape(
								(string) ($penerimaankemasan->keterangan ?: '-')
							)); ?>
						</td>
					</tr>

					<tr class="section-heading text-center font-weight-bold">
						<th colspan="9">VERIFIKASI</th>
					</tr>

					<tr>
						<td colspan="3"><b>QC</b></td>
						<td colspan="6">
							<?= html_escape((string) ($penerimaankemasan->username ?? '-')); ?>
						</td>
					</tr>

					<tr>
						<td colspan="3"><b>Status Supervisor</b></td>
						<td colspan="6">
							<?php
							$statusSpv = (int) ($penerimaankemasan->status_spv ?? 0);

							if ($statusSpv === 1) {
								echo '<span class="text-success font-weight-bold">Verified</span>';
							} elseif ($statusSpv === 2) {
								echo '<span class="text-danger font-weight-bold">Revision</span>';
							} else {
								echo '<span class="text-secondary font-weight-bold">Created</span>';
							}
							?>
						</td>
					</tr>

					<tr>
						<td colspan="3"><b>Catatan Supervisor</b></td>
						<td colspan="6">
							<?= nl2br(html_escape(
								(string) ($penerimaankemasan->catatan_spv ?: '-')
							)); ?>
						</td>
					</tr>
				</tbody>
			</table>

		</div>
	</div>
</div>

<style>
	.breadcrumb {
		padding: 8px 16px;
		border-radius: .25rem;
	}

	.breadcrumb-qc {
		background-color: #2E86C1;
	}

	.breadcrumb-audit {
		background-color: #5a3298;
	}

	.breadcrumb a {
		color: #fff;
		font-weight: 500;
		text-decoration: none;
	}

	.breadcrumb a:hover {
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

	.table th.vertical {
		white-space: nowrap;
		text-align: center;
		vertical-align: middle;
		padding: 8px 4px;
	}

	.section-heading {
		background-color: <?= $isAudit ? '#5a3298' : '#cfe2ff'; ?>;
		color: <?= $isAudit ? '#fff' : '#212529'; ?>;
	}

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

	.btn-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-audit:hover {
		background-color: #452575;
		border-color: #452575;
		color: #fff;
	}

	@media (max-width: 768px) {
		.table th.vertical {
			writing-mode: vertical-rl;
			transform: rotate(180deg);
			padding: 4px 2px;
			font-size: 12px;
		}
	}
</style>
