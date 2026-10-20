<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'pecahbelah/audit'
	: 'pecahbelah';

$datetime = new DateTime($pecahbelah->date);
$formattedDate = $datetime->format('d-m-Y');

$breakable = json_decode($pecahbelah->benda_pecah, true);
if (!is_array($breakable)) {
	$breakable = [];
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			Detail Pemeriksaan Pecah Belah
			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>

	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white" href="<?= base_url($back_url) ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Pemeriksaan Pecah Belah
				</a>
			</li>
			<li class="breadcrumb-item active text-white" aria-current="page">
				<?= $is_audit ? 'Detail Audit' : 'Detail' ?>
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
							<th colspan="8" class="text-center font-weight-bold">
								PEMERIKSAAN BENDA MUDAH PECAH
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td colspan="2">
								<b>Tanggal:</b> <?= htmlspecialchars($formattedDate, ENT_QUOTES, 'UTF-8') ?>
							</td>
							<td>
								<b>Shift:</b> <?= htmlspecialchars((string) $pecahbelah->shift, ENT_QUOTES, 'UTF-8') ?>
							</td>
							<td colspan="5"></td>
						</tr>

						<tr class="bg-light text-center">
							<td colspan="8" class="font-weight-bold">
								Daftar Alat
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th>No</th>
							<th>Nama Alat</th>
							<th>Area</th>
							<th>Pemilik</th>
							<th>Jumlah</th>
							<th>Awal Shift</th>
							<th>Akhir Shift</th>
							<th>Keterangan</th>
						</tr>

						<?php $no = 1;
						foreach ($breakable as $row): ?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td><?= htmlspecialchars($row['nama_barang'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($row['area'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($row['pemilik'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars((string) ($row['jumlah'] ?? ''), ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($row['kondisi_awal'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($row['kondisi_akhir'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
								<td><?= htmlspecialchars($row['keterangan'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
							</tr>
						<?php endforeach; ?>

						<tr class="table-primary text-center">
							<th colspan="8">VERIFIKASI</th>
						</tr>

						<tr>
							<td colspan="2"><b>QC Awal Shift</b></td>
							<td colspan="6">
								<?= htmlspecialchars($pecahbelah->username ?? '', ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>QC Akhir Shift</b></td>
							<td colspan="6">
								<?= htmlspecialchars($pecahbelah->qc_update ?? '', ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Produksi</b></td>
							<td colspan="6">
								<?= htmlspecialchars($pecahbelah->nama_produksi ?? '', ENT_QUOTES, 'UTF-8') ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Disetujui Supervisor</b></td>
							<td colspan="6">
								<?php
								switch ((int) ($pecahbelah->status_spv ?? 0)) {
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
							<td colspan="6">
								<?= !empty($pecahbelah->catatan_spv)
									? htmlspecialchars($pecahbelah->catatan_spv, ENT_QUOTES, 'UTF-8')
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

	.badge-mode-audit {
		display: inline-block;
		padding: 7px 12px;
		border-radius: 5px;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		font-weight: bold;
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
