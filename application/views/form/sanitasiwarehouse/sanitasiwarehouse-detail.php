<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'sanitasiwarehouse/audit'
	: 'sanitasiwarehouse';

$details = json_decode($sanitasiwarehouse->detail ?? '[]', true);

if (!is_array($details)) {
	$details = [];
}

$formattedDate = '-';

if (!empty($sanitasiwarehouse->date)) {
	$datetime = new DateTime($sanitasiwarehouse->date);
	$formattedDate = $datetime->format('d-m-Y');
}

$kondisiMap = [
	'0' => 'Bersih',
	'1' => 'Berdebu',
	'2' => 'Basah',
	'3' => 'Sampah (sisa lakban, kertas, remah produk/bahan baku, plastik, kardus bekas)',
	'4' => 'Pertumbuhan mikroorganisme (jamur dan bau busuk)',
	'5' => 'Pallet rusak/pecah',
	'6' => 'Terdapat aktifitas binatang (tikus, kecoa, lalat, ulat, belatung)',
	'7' => 'Sarang laba-laba',
];
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			<?= $is_audit
				? 'Detail Audit Sanitasi Warehouse'
				: 'Detail Pemeriksaan Sanitasi Warehouse'; ?>

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-custom">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit
						? 'Daftar Audit Sanitasi Warehouse \ Detail'
						: 'Daftar Pemeriksaan Sanitasi Warehouse'; ?>
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
				PEMERIKSAAN SANITASI WAREHOUSE
			</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" cellspacing="0">

					<thead class="text-center">
						<tr>
							<th colspan="7" class="font-weight-bold">
								PEMERIKSAAN SANITASI WAREHOUSE
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
								<b>Area:</b>
								<?= html_escape($sanitasiwarehouse->area ?? '-'); ?>
							</td>
						</tr>

						<tr class="bg-light text-center font-weight-bold">
							<td style="width:5%;">No</td>
							<td colspan="2" style="width:30%;">Titik Pemeriksaan</td>
							<td style="width:20%;">Kondisi</td>
							<td style="width:15%;">Masalah</td>
							<td style="width:15%;">Tindakan</td>
							<td style="width:15%;"></td>
						</tr>

						<?php if (!empty($details)): ?>
							<?php foreach ($details as $i => $row): ?>
								<?php
								$kondisi = (string) ($row['kondisi'] ?? '');
								$namaKondisi = $kondisiMap[$kondisi]
									?? $kondisi;
								?>

								<tr class="text-center">
									<td><?= $i + 1; ?></td>

									<td colspan="2" class="text-left">
										<?= html_escape($row['bagian'] ?? '-'); ?>
									</td>

									<td>
										<?= html_escape($namaKondisi); ?>
									</td>

									<td class="text-left">
										<?= html_escape(
											!empty($row['problem'])
												? $row['problem']
												: '-'
										); ?>
									</td>

									<td class="text-left">
										<?= html_escape(
											!empty($row['tindakan'])
												? $row['tindakan']
												: '-'
										); ?>
									</td>

									<td></td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="7" class="text-center">
									Tidak ada data detail
								</td>
							</tr>
						<?php endif; ?>

						<tr class="table-primary text-center font-weight-bold">
							<td colspan="7">VERIFIKASI</td>
						</tr>

						<tr>
							<td colspan="2"><b>QC</b></td>
							<td colspan="5">
								<?= html_escape($sanitasiwarehouse->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Warehouse</b></td>
							<td colspan="5">
								<?= html_escape($sanitasiwarehouse->nama_wh ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Disetujui Supervisor</b></td>
							<td colspan="5">
								<?php
								switch ((int) ($sanitasiwarehouse->status_spv ?? 0)) {
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
							<td colspan="2"><b>Catatan Supervisor</b></td>
							<td colspan="5">
								<?= html_escape(
									!empty($sanitasiwarehouse->catatan_spv)
										? $sanitasiwarehouse->catatan_spv
										: 'Tidak ada'
								); ?>
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
		vertical-align: middle;
	}

	.header-audit {
		background-color: #5a3298;
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

	.breadcrumb-custom {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb-custom .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb-custom .breadcrumb-item a:hover {
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
	}
</style>
