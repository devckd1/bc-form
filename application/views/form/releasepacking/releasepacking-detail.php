<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? base_url('releasepacking/audit')
	: base_url('releasepacking');

$page_title = $is_audit
	? 'Detail Audit Release Packing'
	: 'Detail Data Release Packing';

$formattedDate = '-';

if (!empty($releasepacking->date)) {
	$datetime = new DateTime($releasepacking->date);
	$formattedDate = $datetime->format('d-m-Y');
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			<?= $page_title; ?>

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
				<a href="<?= $back_url; ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit
						? 'Daftar Audit Release Packing \ Detail'
						: 'Daftar Data Release Packing'; ?>
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
				DATA RELEASE PACKING
			</h6>
		</div>

		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" cellspacing="0">

					<thead class="text-center">
						<tr>
							<th colspan="7">DATA RELEASE PACKING</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td><b>Tanggal</b></td>
							<td colspan="6">
								<?= html_escape($formattedDate); ?>
							</td>
						</tr>

						<tr>
							<td>Nama Produk</td>
							<td colspan="6">
								<?= html_escape($releasepacking->nama_produk ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td>Kode Produksi</td>
							<td colspan="6">
								<?= html_escape($releasepacking->kode_produksi ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td>Best Before</td>
							<td colspan="6">
								<?= html_escape($releasepacking->best_before ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td>Jumlah</td>
							<td colspan="6">
								<?= html_escape($releasepacking->jumlah ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td>Keterangan</td>
							<td colspan="6">
								<?= html_escape($releasepacking->keterangan ?? '-'); ?>
							</td>
						</tr>

						<tr class="text-center table-primary">
							<th colspan="7">VERIFIKASI</th>
						</tr>

						<tr>
							<td>QC</td>
							<td colspan="6">
								<?= html_escape($releasepacking->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td>Disetujui Supervisor</td>
							<td colspan="6">
								<?php
								switch ((int) ($releasepacking->status_spv ?? 0)) {
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
							<td>Catatan Supervisor</td>
							<td colspan="6">
								<?= html_escape(
									!empty($releasepacking->catatan_spv)
										? $releasepacking->catatan_spv
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
