
<?php
defined('BASEPATH') or exit('No direct script access allowed');

$tanggal = $falserejection->date_false_rejection ?? '';
if (empty($tanggal) || $tanggal === '0000-00-00') {
	$formatted_date = '-';
} else {
	$timestamp = strtotime($tanggal);
	$formatted_date = $timestamp ? date('d-m-Y', $timestamp) : '-';
}

$status_spv = (string) ($falserejection->status_spv_false ?? '0');
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Detail Audit False Rejection
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-audit">
			<li class="breadcrumb-item">
				<a href="<?= base_url('falserejection/audit'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit False Rejection \ Detail
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert alert-audit">
		<i class="fas fa-info-circle mr-1"></i>
		Detail ini merupakan snapshot Audit. Data QC utama tidak diubah melalui halaman ini.
	</div>

	<div class="card shadow mb-4 card-audit">
		<div class="card-body">
			<div class="table-responsive">

				<table class="table table-bordered">
					<thead class="text-center">
						<tr>
							<th colspan="2" class="font-weight-bold">
								DETAIL AUDIT FALSE REJECTION
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td>Tanggal Monitoring</td>
							<td><b><?= html_escape($formatted_date); ?></b></td>
						</tr>

						<tr>
							<td>Shift</td>
							<td><?= html_escape($falserejection->shift_monitoring ?? '-'); ?></td>
						</tr>

						<tr>
							<td>Mesin</td>
							<td><?= html_escape($falserejection->no_mesin ?? '-'); ?></td>
						</tr>

						<tr>
							<td>Nama Produk</td>
							<td><?= html_escape($falserejection->nama_produk ?? '-'); ?></td>
						</tr>

						<tr>
							<td>Kode Produksi</td>
							<td><?= html_escape($falserejection->kode_produksi ?? '-'); ?></td>
						</tr>

						<tr>
							<td>Jumlah Pack/Bag Tidak Lolos</td>
							<td><?= html_escape($falserejection->jumlah_tidak_lolos ?? '0'); ?></td>
						</tr>

						<tr>
							<td>Jumlah dengan Kontaminasi</td>
							<td><?= html_escape($falserejection->jumlah_kontaminasi ?? '0'); ?></td>
						</tr>

						<tr>
							<td>Jenis Kontaminasi</td>
							<td><?= html_escape($falserejection->jenis_kontaminasi ?? '-'); ?></td>
						</tr>

						<tr>
							<td>Posisi Kontaminasi</td>
							<td><?= html_escape($falserejection->posisi_kontaminasi ?? '-'); ?></td>
						</tr>

						<tr>
							<td>False Rejection</td>
							<td><?= html_escape($falserejection->false_rejection ?? '0'); ?></td>
						</tr>

						<tr>
							<td>Catatan Monitoring</td>
							<td>
								<?= !empty($falserejection->catatan)
									? html_escape($falserejection->catatan)
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center text-uppercase">
							<td colspan="2"><b>Verifikasi</b></td>
						</tr>

						<tr>
							<td>QC</td>
							<td><?= html_escape($falserejection->username_2 ?? '-'); ?></td>
						</tr>

						<tr>
							<td>Produksi</td>
							<td>
								<?= !empty($falserejection->nama_produksi_false)
									? html_escape($falserejection->nama_produksi_false)
									: 'Belum dikoreksi'; ?>
							</td>
						</tr>

						<tr>
							<td>Status Supervisor</td>
							<td>
								<?php if ($status_spv === '1') : ?>
									<span class="badge badge-success">Verified</span>
								<?php elseif ($status_spv === '2') : ?>
									<span class="badge badge-danger">Revision</span>
								<?php else : ?>
									<span class="badge badge-secondary">Created</span>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<td>Catatan Supervisor</td>
							<td>
								<?= !empty($falserejection->catatan_spv_false)
									? html_escape($falserejection->catatan_spv_false)
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
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		font-weight: 700;
		padding: 6px 10px;
		border-radius: 5px;
		vertical-align: middle;
		margin-left: 5px;
	}

	.breadcrumb-audit {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb-audit a {
		color: #fff;
		font-weight: 500;
	}

	.alert-audit {
		background-color: #f3e8ff;
		border-left: 4px solid #6f42c1;
		color: #4b2a75;
	}

	.card-audit {
		border: 1px solid #e3d7f5;
		border-radius: 8px;
	}

	.btn-edit-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-edit-audit:hover {
		background-color: #452577;
		border-color: #452577;
		color: #fff;
	}

	.table {
		width: 100%;
		font-size: 15px;
	}

	.table th,
	.table td {
		padding: 10px;
		vertical-align: middle;
		white-space: normal;
	}

	.table td:first-child {
		font-weight: bold;
		width: 220px;
	}
</style>