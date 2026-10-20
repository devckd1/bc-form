
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$mode = $mode ?? 'qc';
$isAudit = $mode === 'audit';

$baseUrl = $isAudit
	? 'pemeriksaanchemical/audit'
	: 'pemeriksaanchemical';

$editUrl = $isAudit
	? 'pemeriksaanchemical/edit-audit/' . $pemeriksaanchemical->uuid
	: 'pemeriksaanchemical/edit/' . $pemeriksaanchemical->uuid;

$formatTanggal = function ($tanggal) {
	if (empty($tanggal) || $tanggal === '0000-00-00') {
		return '-';
	}

	try {
		return (new DateTime($tanggal))->format('d-m-Y');
	} catch (Exception $e) {
		return '-';
	}
};

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
if (!empty($pemeriksaanchemical->kondisi_mobil)) {
	$mobilList = is_array($pemeriksaanchemical->kondisi_mobil)
		? $pemeriksaanchemical->kondisi_mobil
		: explode(',', $pemeriksaanchemical->kondisi_mobil);
}

$escape = function ($value) {
	return html_escape((string) ($value ?? ''));
};

if (!function_exists('icon_check_chemical')) {
	function icon_check_chemical($value, $expected)
	{
		if ($value === null || $value === '') {
			return '−';
		}

		return strtolower(trim((string) $value)) === strtolower(trim((string) $expected))
			? '<span class="text-success font-weight-bold">✔</span>'
			: '<span class="text-danger font-weight-bold">✘</span>';
	}
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			Detail Pemeriksaan Chemical dari Supplier

			<?php if ($isAudit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb <?= $isAudit ? 'breadcrumb-audit' : ''; ?>">
			<li class="breadcrumb-item">
				<a href="<?= base_url($baseUrl); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $isAudit
						? 'Daftar Audit Pemeriksaan Chemical dari Supplier \ Detail'
						: 'Daftar Pemeriksaan Chemical dari Supplier'; ?>
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

	<div class="card shadow mb-4 <?= $isAudit ? 'card-audit' : ''; ?>">
		<div class="card-body">
			<div class="table-responsive">

				<table class="table table-bordered">
					<thead class="text-center">
						<tr>
							<th colspan="7" class="font-weight-bold">
								PEMERIKSAAN CHEMICAL DARI SUPPLIER
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td colspan="2">
								<b>Tanggal:</b>
								<?= $formatTanggal($pemeriksaanchemical->date); ?>
							</td>
							<td colspan="5">
								<b>Shift:</b>
								<?= $escape($pemeriksaanchemical->shift); ?>
							</td>
						</tr>

						<tr>
							<td><b>Jenis Chemical</b></td>
							<td colspan="6"><?= $escape($pemeriksaanchemical->jenis_chemical); ?></td>
						</tr>
						<tr>
							<td><b>Pemasok</b></td>
							<td colspan="6"><?= $escape($pemeriksaanchemical->pemasok); ?></td>
						</tr>
						<tr>
							<td><b>Jenis Mobil</b></td>
							<td colspan="6"><?= $escape($pemeriksaanchemical->jenis_mobil); ?></td>
						</tr>
						<tr>
							<td><b>No. Polisi</b></td>
							<td colspan="6"><?= $escape($pemeriksaanchemical->no_polisi); ?></td>
						</tr>
						<tr>
							<td><b>Identitas Pengantar</b></td>
							<td colspan="6"><?= $escape($pemeriksaanchemical->identitas_pengantar); ?></td>
						</tr>
						<tr>
							<td><b>No. PO / DO</b></td>
							<td colspan="6"><?= $escape($pemeriksaanchemical->no_po); ?></td>
						</tr>

						<tr class="table-primary text-center font-weight-bold">
							<th colspan="7">KONDISI MOBIL</th>
						</tr>
						<tr>
							<td colspan="7">
								<?php if (!empty($mobilList)): ?>
									<ul class="mb-0">
										<?php foreach ($mobilList as $m): ?>
											<li>
												<?= $escape(
													$keteranganMobil[(int) trim($m)] ?? 'Tidak diketahui'
												); ?>
											</li>
										<?php endforeach; ?>
									</ul>
								<?php else: ?>
									Tidak ada data
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<td><b>Kode Produksi</b></td>
							<td colspan="6"><?= $escape($pemeriksaanchemical->kode_produksi); ?></td>
						</tr>
						<tr>
							<td><b>Expired Date</b></td>
							<td colspan="6"><?= $formatTanggal($pemeriksaanchemical->expired); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Jumlah Barang</b></td>
							<td colspan="2"><b>Sampel (pcs)</b></td>
							<td colspan="3"><b>Jumlah Reject</b></td>
						</tr>
						<tr>
							<td colspan="2"><?= $escape($pemeriksaanchemical->jumlah_barang); ?></td>
							<td colspan="2"><?= $escape($pemeriksaanchemical->sampel); ?></td>
							<td colspan="3"><?= $escape($pemeriksaanchemical->jumlah_reject); ?></td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="3">KONDISI FISIK</th>
							<th colspan="4">HALAL BERLAKU</th>
						</tr>
						<tr class="text-center">
							<td><b>Kemasan</b></td>
							<td><b>Warna</b></td>
							<td><b>pH</b></td>
							<td colspan="4"><b>Berlaku</b></td>
						</tr>
						<tr class="text-center">
							<td><?= icon_check_chemical($pemeriksaanchemical->kemasan, 'sesuai'); ?></td>
							<td><?= icon_check_chemical($pemeriksaanchemical->warna, 'sesuai'); ?></td>
							<td><?= $escape($pemeriksaanchemical->ph); ?></td>
							<td colspan="4">
								<?= icon_check_chemical($pemeriksaanchemical->halal_berlaku, 'berlaku'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="3"><b>Segel</b></td>
							<td colspan="4">
								<?= $escape($pemeriksaanchemical->segel ?: '−'); ?>
							</td>
						</tr>
						<tr>
							<td colspan="3"><b>Penerimaan</b></td>
							<td colspan="4">
								<?= icon_check_chemical(
									$pemeriksaanchemical->penerimaan === 'ok' ? 'sesuai' : ($pemeriksaanchemical->penerimaan === 'tolak' ? 'tidak sesuai' : ''),
									'sesuai'
								); ?>
								<?= $escape($pemeriksaanchemical->penerimaan); ?>
							</td>
						</tr>
						<tr>
							<td colspan="3"><b>COA</b></td>
							<td colspan="2">
								<?= icon_check_chemical(
									$pemeriksaanchemical->coa === 'ada' ? 'sesuai' : ($pemeriksaanchemical->coa === 'tidak ada' ? 'tidak sesuai' : ''),
									'sesuai'
								); ?>
								<?= $escape($pemeriksaanchemical->coa); ?>
							</td>
							<td colspan="2">
								<?php if (!empty($pemeriksaanchemical->bukti_coa)): ?>
									<a href="<?= base_url(
													'uploads/penerimaan_chemical/' .
														rawurlencode($pemeriksaanchemical->bukti_coa)
												); ?>" target="_blank" rel="noopener noreferrer">
										<i class="fas fa-file-alt mr-1"></i> Link COA
									</a>
								<?php else: ?>
									−
								<?php endif; ?>
							</td>
						</tr>
						<tr>
							<td colspan="3"><b>Keterangan</b></td>
							<td colspan="4">
								<?= $escape($pemeriksaanchemical->keterangan ?: '−'); ?>
							</td>
						</tr>
						<tr>
							<td colspan="3"><b>Catatan</b></td>
							<td colspan="4">
								<?= $escape($pemeriksaanchemical->catatan ?: '−'); ?>
							</td>
						</tr>

						<tr class="table-secondary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>
						<tr>
							<td><b>QC</b></td>
							<td colspan="6"><?= $escape($pemeriksaanchemical->username); ?></td>
						</tr>
						<tr>
							<td><b>Disetujui Supervisor</b></td>
							<td colspan="6">
								<?php
								if ($pemeriksaanchemical->status_spv == 0) {
									echo '<span class="text-secondary font-weight-bold">Created</span>';
								} elseif ($pemeriksaanchemical->status_spv == 1) {
									echo '<span class="text-success font-weight-bold">Verified</span>';
								} elseif ($pemeriksaanchemical->status_spv == 2) {
									echo '<span class="text-danger font-weight-bold">Revision</span>';
								} else {
									echo '−';
								}
								?>
							</td>
						</tr>
						<tr>
							<td><b>Catatan Supervisor</b></td>
							<td colspan="6">
								<?= $escape($pemeriksaanchemical->catatan_spv ?: 'Tidak ada'); ?>
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
		margin-left: 6px;
		border-radius: 5px;
		background: #5a3298;
		color: #fff;
		font-size: 12px;
		vertical-align: middle;
	}

	.btn-audit {
		color: #fff;
		background: #5a3298;
		border-color: #5a3298;
	}

	.btn-audit:hover {
		color: #fff;
		background: #452477;
		border-color: #452477;
	}

	.breadcrumb {
		background-color: #2E86C1;
		padding: 10px 16px;
		border-radius: .25rem;
	}

	.breadcrumb-audit {
		background-color: #2E86C1;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.card-audit {
		border-top: 4px solid #5a3298;
	}

	.table {
		font-size: 15px;
		width: 100%;
		border-collapse: collapse;
		table-layout: fixed;
	}

	.table th,
	.table td {
		padding: 10px 12px;
		vertical-align: top;
		word-wrap: break-word;
		overflow-wrap: break-word;
		border: 1px solid #dee2e6;
	}

	.table th {
		text-align: center;
	}

	.table-primary {
		background-color: #cce5ff;
	}

	.table-secondary {
		background-color: #e2e3e5;
	}

	.table td ul {
		padding-left: 20px;
		margin: 0;
	}

	.table td ul li {
		margin: 0;
		line-height: 1.4;
	}
</style>
