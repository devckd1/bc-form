
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'analisis/audit'
	: 'analisis';

$datetime = new DateTime($analisis->date);
$datetime = $datetime->format('d-m-Y');

$bb = new DateTime($analisis->best_before);
$bb = $bb->format('d-m-Y');
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		Detail Permohonan Analisis Sampel Laboratorium

		<?php if ($is_audit): ?>
			<span class="badge-mode-audit">MODE AUDIT</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Analisis Laboratorium'
						: 'Daftar Permohonan Analisis Sampel Laboratorium'; ?>
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
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th class="text-center" colspan="6">
								ANALISIS SAMPLE REPORT
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td colspan="6">
								<strong>Tanggal:</strong>
								<?= html_escape($datetime); ?>
							</td>
						</tr>

						<tr>
							<td>Tipe Sampel</td>
							<td colspan="5"><?= html_escape($analisis->tipe_sampel); ?></td>
						</tr>

						<tr>
							<td>Penyimpanan</td>
							<td colspan="5"><?= html_escape($analisis->penyimpanan); ?></td>
						</tr>

						<tr>
							<td>Nama Produk</td>
							<td colspan="5"><?= html_escape($analisis->nama_produk); ?></td>
						</tr>

						<tr>
							<td>Kode Produksi</td>
							<td colspan="5"><?= html_escape($analisis->kode_produksi); ?></td>
						</tr>

						<tr>
							<td>Best Before</td>
							<td colspan="5"><?= html_escape($bb); ?></td>
						</tr>

						<tr>
							<td>Jumlah Sampel (g)</td>
							<td colspan="5"><?= html_escape($analisis->jumlah_sampel); ?></td>
						</tr>

						<tr>
							<td>Permintaan Analisis</td>
							<td colspan="5">
								<?= html_escape($analisis->analisis ?? ''); ?>
							</td>
						</tr>

						<tr>
							<td>Catatan</td>
							<td colspan="5">
								<?= !empty($analisis->catatan)
									? html_escape($analisis->catatan)
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr>
							<th class="text-center" colspan="6">
								<?= $is_audit ? 'DATA SNAPSHOT AUDIT' : 'VERIFIKASI'; ?>
							</th>
						</tr>

						<tr>
							<td>QC</td>
							<td colspan="5"><?= html_escape($analisis->username); ?></td>
						</tr>

						<tr>
							<td>Produksi</td>
							<td colspan="5">
								<?= html_escape($analisis->nama_produksi ?? '-'); ?>
							</td>
						</tr>

						<?php if (!$is_audit): ?>
							<tr>
								<td>LAB</td>
								<td colspan="5">
									<?= html_escape($analisis->nama_lab ?? '-'); ?>
								</td>
							</tr>

							<tr>
								<td>Diterima Lab</td>
								<td colspan="5">
									<?php
									if ($analisis->status_lab == 0) {
										echo '<span class="text-secondary font-weight-bold">Created</span>';
									} elseif ($analisis->status_lab == 1) {
										echo '<span class="text-success font-weight-bold">Accepted</span>';
									} elseif ($analisis->status_lab == 2) {
										echo '<span class="text-danger font-weight-bold">Returned</span>';
									}
									?>
								</td>
							</tr>

							<tr>
								<td>Catatan Produksi</td>
								<td colspan="5">
									<?= !empty($analisis->catatan_produksi)
										? html_escape($analisis->catatan_produksi)
										: 'Tidak ada'; ?>
								</td>
							</tr>

							<tr>
								<td>Disetujui Supervisor</td>
								<td colspan="5">
									<?php
									if ($analisis->status_spv == 0) {
										echo '<span class="text-secondary font-weight-bold">Created</span>';
									} elseif ($analisis->status_spv == 1) {
										echo '<span class="text-success font-weight-bold">Verified</span>';
									} elseif ($analisis->status_spv == 2) {
										echo '<span class="text-danger font-weight-bold">Revision</span>';
									}
									?>
								</td>
							</tr>

							<tr>
								<td>Catatan Supervisor</td>
								<td colspan="5">
									<?= !empty($analisis->catatan_spv)
										? html_escape($analisis->catatan_spv)
										: 'Tidak ada'; ?>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
	}

	.badge-mode-audit {
		display: inline-block;
		padding: 6px 10px;
		border-radius: 4px;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		vertical-align: middle;
	}

	.table {
		width: 100%;
		font-size: 16px;
		margin: 0 auto;
	}

	.table,
	.table th,
	.table td {
		border: none;
	}

	.table th,
	.table td {
		padding: 6px 8px;
		text-align: left;
		border-bottom: 1px solid #ddd;
		word-wrap: break-word;
		white-space: normal !important;
	}
</style>
