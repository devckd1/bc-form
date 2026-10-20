<?php
$mode = $mode ?? 'qc';
$isAudit = ($mode === 'audit');

$listUrl = $isAudit ? 'loading/audit' : 'loading';
$judul = $isAudit
	? 'Detail Audit Pemeriksaan Loading Produk'
	: 'Detail Pemeriksaan Loading Produk';

$datetime = !empty($loading->date)
	? (new DateTime($loading->date))->format('d-m-Y')
	: '-';

$loading_produk = json_decode($loading->loading ?? '[]', true) ?: [];
$kondisi_mobil = json_decode($loading->kondisi_mobil ?? '[]', true) ?: [];

$kondisiLabels = [
	'bersih' => 'Bersih',
	'bocor' => 'Bocor',
	'bebas_dari_hama' => 'Bebas dari Hama',
	'tidak_berdebu' => 'Tidak Berdebu',
	'tidak_ada_sampah' => 'Tidak ada Sampah',
	'kering' => 'Kering',
	'basah' => 'Basah',
	'tidak_berbau' => 'Tidak Berbau',
	'tidak_ada_produk_non_halal' => 'Tidak ada produk Non Halal',
	'tidak_ada_aktivitas_binatang' => 'Tidak ada Aktivitas Binatang'
];

$kondisiKeys = array_keys($kondisiLabels);

$set1 = [];
$set2 = [];

foreach ($kondisiKeys as $i => $key) {
	if (array_key_exists($key, $kondisi_mobil)) {
		if ($i < 5) {
			$set1[$key] = $kondisi_mobil[$key];
		} else {
			$set2[$key] = $kondisi_mobil[$key];
		}
	}
}

function tampilKondisiMobilLoading($data, $labels)
{
	$html = '<tr class="table-secondary text-center">';
	$colUsed = 0;

	foreach ($data as $key => $val) {
		$colspan = ($colUsed === 0 || $colUsed === 2) ? 2 : 1;
		$html .= '<th colspan="' . $colspan . '">'
			. htmlspecialchars($labels[$key] ?? $key, ENT_QUOTES, 'UTF-8')
			. '</th>';
		$colUsed += $colspan;
	}

	if ($colUsed < 7) {
		$html .= '<th colspan="' . (7 - $colUsed) . '"></th>';
	}

	$html .= '</tr><tr class="text-center">';

	$colUsed = 0;
	foreach ($data as $key => $val) {
		$colspan = ($colUsed === 0 || $colUsed === 2) ? 2 : 1;
		$display = ($val !== '' && $val !== null)
			? htmlspecialchars((string) $val, ENT_QUOTES, 'UTF-8')
			: '-';

		$html .= '<td colspan="' . $colspan . '">' . $display . '</td>';
		$colUsed += $colspan;
	}

	if ($colUsed < 7) {
		$html .= '<td colspan="' . (7 - $colUsed) . '"></td>';
	}

	$html .= '</tr>';

	return $html;
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			<?= $judul; ?>
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
				<a href="<?= base_url($listUrl); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $isAudit
						? 'Daftar Audit Verifikasi Loading Produk \ Detail'
						: 'Daftar Pemeriksaan Loading Produk'; ?>
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
								PEMERIKSAAN LOADING PRODUK
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td colspan="2"><b>Tanggal:</b> <?= $datetime; ?></td>
							<td colspan="5">
								<b>Shift:</b>
								<?= htmlspecialchars((string) ($loading->shift ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
							</td>
						</tr>

						<tr class="text-center">
							<td colspan="4"><b>Start Loading</b></td>
							<td colspan="3"><b>Finish Loading</b></td>
						</tr>
						<tr class="text-center">
							<td colspan="4">
								<?= htmlspecialchars((string) ($loading->start_loading ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
							</td>
							<td colspan="3">
								<?= htmlspecialchars((string) ($loading->finish_loading ?? '-'), ENT_QUOTES, 'UTF-8'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>No. Polisi</b></td>
							<td colspan="5"><?= htmlspecialchars((string) ($loading->no_pol ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Nama Supir</b></td>
							<td colspan="5"><?= htmlspecialchars((string) ($loading->nama_supir ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Ekspedisi</b></td>
							<td colspan="5"><?= htmlspecialchars((string) ($loading->ekspedisi ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Tujuan</b></td>
							<td colspan="5"><?= htmlspecialchars((string) ($loading->tujuan ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>No. Segel</b></td>
							<td colspan="5"><?= htmlspecialchars((string) ($loading->no_segel ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">KONDISI MOBIL</th>
						</tr>

						<?php if (!empty($set1)): ?>
							<?= tampilKondisiMobilLoading($set1, $kondisiLabels); ?>
						<?php endif; ?>

						<?php if (!empty($set2)): ?>
							<?= tampilKondisiMobilLoading($set2, $kondisiLabels); ?>
						<?php endif; ?>

						<tr class="table-primary text-center">
							<th colspan="7">LOADING PRODUK</th>
						</tr>
						<tr class="text-center">
							<th>No</th>
							<th>Nama Produk</th>
							<th>Kondisi Produk</th>
							<th>Kondisi Kemasan</th>
							<th>Kode Produksi</th>
							<th>Expired</th>
							<th>Keterangan</th>
						</tr>

						<?php if (!empty($loading_produk)): ?>
							<?php $no = 1; ?>
							<?php foreach ($loading_produk as $row): ?>
								<tr>
									<td><?= $no++; ?></td>
									<td><?= htmlspecialchars((string) ($row['nama_produk'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
									<td><?= htmlspecialchars((string) ($row['kondisi_produk'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
									<td><?= htmlspecialchars((string) ($row['kondisi_kemasan'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
									<td><?= htmlspecialchars((string) ($row['kode_produksi'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
									<td><?= htmlspecialchars((string) ($row['expired'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
									<td><?= htmlspecialchars((string) ($row['keterangan'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="7" class="text-center text-muted">
									Tidak ada data loading produk.
								</td>
							</tr>
						<?php endif; ?>

						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>
						<tr>
							<td colspan="2"><b>QC</b></td>
							<td colspan="5"><?= htmlspecialchars((string) ($loading->username ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Warehouse</b></td>
							<td colspan="5"><?= htmlspecialchars((string) ($loading->nama_wh ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Disetujui Supervisor</b></td>
							<td colspan="5">
								<?php
								$statusSpv = (int) ($loading->status_spv ?? 0);
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
							<td colspan="2"><b>Catatan Supervisor</b></td>
							<td colspan="5">
								<?= !empty($loading->catatan_spv)
									? htmlspecialchars($loading->catatan_spv, ENT_QUOTES, 'UTF-8')
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
	.breadcrumb {
		background-color: #2E86C1;
		padding: 10px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb-audit {
		background-color: #2E86C1;
	}

	.badge-mode-audit {
		display: inline-block;
		margin-left: 8px;
		padding: 6px 10px;
		border-radius: 5px;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		vertical-align: middle;
	}

	.card-audit {
		border-left: 4px solid #5a3298;
	}

	.table {
		width: 100%;
		font-size: 15px;
	}

	.table th,
	.table td {
		padding: 10px 12px;
		vertical-align: middle;
		word-break: break-word;
	}

	.text-center th,
	.text-center td {
		text-align: center;
	}

	.table td[colspan="2"] {
		min-width: 160px;
		max-width: 200px;
		word-break: break-word;
	}
</style>
