
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'disposisi/audit'
	: 'disposisi';

$judul = $is_audit
	? 'Detail Audit Disposisi Produk dan Prosedur'
	: 'Detail Disposisi Produk dan Prosedur';

$datetime = new DateTime($disposisi->date);
$formattedDate = $datetime->format('d-m-Y');
?>

<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			<?= $judul; ?>

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit
						? 'Daftar Audit Disposisi'
						: 'Daftar Disposisi Produk dan Prosedur'; ?>
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

	<!-- Card Detail -->
	<div class="card shadow mb-4">

		<div class="card-header py-3 <?= $is_audit ? 'header-audit' : ''; ?>">
			<h6 class="m-0 font-weight-bold <?= $is_audit ? 'text-white' : 'text-primary'; ?>">
				DISPOSISI PRODUK DAN PROSEDUR
			</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th colspan="6">DISPOSISI PRODUK DAN PROSEDUR</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td><b>Tanggal</b></td>
							<td colspan="5">
								<?= html_escape($formattedDate); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Nomor</b></td>
							<td colspan="4">
								<?= html_escape($disposisi->nomor ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Kepada</b></td>
							<td colspan="4">
								<?= html_escape($disposisi->kepada ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Disposisi</b></td>
							<td colspan="4">
								<?= html_escape($disposisi->disposisi ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Dasar Disposisi</b></td>
							<td colspan="4">
								<?= html_escape(!empty($disposisi->dasar_disposisi)
									? $disposisi->dasar_disposisi
									: 'Tidak ada'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Uraian Disposisi</b></td>
							<td colspan="4">
								<?= html_escape(!empty($disposisi->uraian_disposisi)
									? $disposisi->uraian_disposisi
									: 'Tidak ada'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Catatan</b></td>
							<td colspan="4">
								<?= html_escape(!empty($disposisi->catatan)
									? $disposisi->catatan
									: 'Tidak ada'); ?>
							</td>
						</tr>

						<tr class="<?= $is_audit ? 'header-audit text-white' : 'table-primary'; ?> text-center">
							<th colspan="6">VERIFIKASI</th>
						</tr>

						<tr>
							<td colspan="2"><b>QC</b></td>
							<td colspan="4">
								<?= html_escape($disposisi->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Produksi</b></td>
							<td colspan="4">
								<?= html_escape($disposisi->nama_produksi ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Disetujui Supervisor</b></td>
							<td colspan="4">
								<?php
								switch ((string) ($disposisi->status_spv ?? '0')) {
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
							<td colspan="2"><b>Catatan Supervisor</b></td>
							<td colspan="4">
								<?= html_escape(!empty($disposisi->catatan_spv)
									? $disposisi->catatan_spv
									: 'Tidak ada'); ?>
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
		background: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}

	.header-audit {
		background-color: #5a3298;
	}

	.breadcrumb {
		background-color: <?= $is_audit ? '#2E86C1' : '#2E86C1'; ?>;
		padding: 8px 16px;
		border-radius: .25rem;
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
	}
</style>
