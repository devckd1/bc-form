<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'kekuatanmagnet/audit'
	: 'kekuatanmagnet';

$page_title = $is_audit
	? 'Detail Audit Pemeriksaan Kekuatan Magnet Trap'
	: 'Detail Pemeriksaan Kekuatan Magnet Trap';

$breadcrumb_title = $is_audit
	? 'Daftar Audit Kekuatan Magnet Trap \ Detail'
	: 'Daftar Pemeriksaan Kekuatan Magnet Trap';

$tanggal = !empty($kekuatanmagnet->date)
	? date('d-m-Y', strtotime($kekuatanmagnet->date))
	: '-';
?>

<div class="container-fluid">

	<h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">
		<?= html_escape($page_title); ?>

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
					<?= html_escape($breadcrumb_title); ?>
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
								PEMERIKSAAN KEKUATAN MAGNET TRAP
							</th>
						</tr>
					</thead>

					<tbody>

						<tr>
							<td colspan="2">
								<b>Tanggal:</b>
								<?= html_escape($tanggal); ?>
							</td>
							<td colspan="5"></td>
						</tr>

						<tr class="bg-light text-center">
							<td colspan="7" class="font-weight-bold">
								Hasil Pemeriksaan
							</td>
						</tr>

						<tr>
							<td><b>Nama Alat</b></td>
							<td colspan="6">
								<?= html_escape($kekuatanmagnet->nama_alat ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Nilai Pengukuran</b></td>
							<td colspan="6">
								<?= html_escape($kekuatanmagnet->nilai ?? '-'); ?> Gauss
							</td>
						</tr>

						<tr>
							<td><b>Keterangan</b></td>
							<td colspan="6">
								<?= !empty($kekuatanmagnet->keterangan)
									? nl2br(html_escape($kekuatanmagnet->keterangan))
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="bg-light">
							<td><b>Catatan</b></td>
							<td colspan="6">
								<?= !empty($kekuatanmagnet->catatan)
									? nl2br(html_escape($kekuatanmagnet->catatan))
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>

						<tr>
							<td><b>QC</b></td>
							<td colspan="6">
								<?= html_escape($kekuatanmagnet->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Produksi</b></td>
							<td colspan="6">
								<?= !empty($kekuatanmagnet->nama_produksi)
									? html_escape($kekuatanmagnet->nama_produksi)
									: 'Belum dikoreksi'; ?>
							</td>
						</tr>

						<tr>
							<td><b>Status Supervisor</b></td>
							<td colspan="6">
								<?php
								switch ((string) ($kekuatanmagnet->status_spv ?? '0')) {
									case '1':
										echo '<span class="text-success font-weight-bold">Verified</span>';
										break;

									case '2':
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
								<?= !empty($kekuatanmagnet->catatan_spv)
									? nl2br(html_escape($kekuatanmagnet->catatan_spv))
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
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		padding: 5px 9px;
		border-radius: 4px;
		vertical-align: middle;
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