<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'magnettrap/audit'
	: 'magnettrap';

$datetime = !empty($magnettrap->date)
	? date('d-m-Y', strtotime($magnettrap->date))
	: '-';

$formattedTime = !empty($magnettrap->time)
	? date('H:i', strtotime($magnettrap->time))
	: '-';

$bukti = $magnettrap->bukti ?? null;
$img_url = null;
$is_pdf = false;

if (!empty($bukti)) {
	$path1 = FCPATH . 'uploads/' . $bukti;
	$path2 = FCPATH . 'uploads/magnettrap/' . $bukti;

	if (file_exists($path1)) {
		$img_url = base_url('uploads/' . $bukti);
		$file_path = $path1;
	} elseif (file_exists($path2)) {
		$img_url = base_url('uploads/magnettrap/' . $bukti);
		$file_path = $path2;
	}

	if (!empty($img_url)) {
		$is_pdf = strtolower(pathinfo($file_path, PATHINFO_EXTENSION)) === 'pdf';
	}
}
?>

<div class="container-fluid">

	<?php if (empty($is_popup)): ?>
		<div class="d-sm-flex align-items-center justify-content-between mb-3">
			<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
				<?= $is_audit ? 'Detail Audit Pemeriksaan Magnet Trap' : 'Detail Pemeriksaan Magnet Trap'; ?>
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
					<a href="<?= base_url($back_url); ?>">
						<i class="fas fa-arrow-left mr-1"></i>
						<?= $is_audit ? 'Daftar Audit Magnet Trap' : 'Daftar Pemeriksaan Magnet Trap'; ?>
					</a>
				</li>
				<li class="breadcrumb-item active" aria-current="page">
					Detail
				</li>
			</ol>
		</nav>
	<?php endif; ?>

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
							<th colspan="7" class="table-primary font-weight-bold">
								PEMERIKSAAN MAGNET TRAP
								<?= $is_audit ? ' - AUDIT' : ''; ?>
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td colspan="2">
								<b>Tanggal:</b>
								<?= html_escape($datetime); ?>
							</td>
							<td>
								<b>Shift:</b>
								<?= html_escape($magnettrap->shift ?? '-'); ?>
							</td>
							<td colspan="4">
								<b>Pukul:</b>
								<?= html_escape($formattedTime); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<b>Nama Produk:</b>
								<?= !empty($magnettrap->nama_produk) ? $magnettrap->nama_produk : '-'; ?>
							</td>
							<td colspan="5">
								<b>Kode Produksi:</b>
								<?= !empty($magnettrap->kode_produksi) ? $magnettrap->kode_produksi : '-'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center">
							<td colspan="7" class="font-weight-bold">
								Hasil Pemeriksaan
							</td>
						</tr>

						<tr>
							<td><b>Tahapan</b></td>
							<td colspan="6">
								<?= html_escape($magnettrap->tahapan ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Jenis Kontaminasi</b></td>
							<td colspan="6">
								<?= html_escape($magnettrap->kontaminasi ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Bukti Temuan</b></td>
							<td colspan="6">
								<?php if ($img_url && $is_pdf): ?>
									<a href="<?= html_escape($img_url); ?>"
										target="_blank"
										rel="noopener noreferrer"
										class="btn btn-danger btn-sm">
										<i class="fas fa-file-pdf mr-1"></i>
										Lihat Bukti PDF
									</a>
								<?php elseif ($img_url): ?>
									<a href="<?= html_escape($img_url); ?>"
										target="_blank"
										rel="noopener noreferrer">
										<img src="<?= html_escape($img_url); ?>"
											alt="Bukti Temuan"
											loading="lazy"
											decoding="async"
											style="max-width:200px; max-height:150px;">
									</a>
								<?php else: ?>
									<span class="text-muted">Tidak ada bukti</span>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<td><b>Analisis Temuan</b></td>
							<td colspan="6">
								<?= html_escape($magnettrap->analisis ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Tindakan Koreksi</b></td>
							<td colspan="6">
								<?= html_escape($magnettrap->tindakan ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Verifikasi</b></td>
							<td colspan="6">
								<?= html_escape($magnettrap->verifikasi ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Keterangan</b></td>
							<td colspan="6">
								<?= !empty($magnettrap->keterangan)
									? html_escape($magnettrap->keterangan)
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr>
							<td><b>Catatan</b></td>
							<td colspan="6">
								<?= !empty($magnettrap->catatan)
									? html_escape($magnettrap->catatan)
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>

						<tr>
							<td><b>QC</b></td>
							<td colspan="6">
								<?= html_escape($magnettrap->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Engineer</b></td>
							<td colspan="6">
								<?= html_escape($magnettrap->nama_enginer ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Status Supervisor</b></td>
							<td colspan="6">
								<?php
								$tipe_user = (int) $this->session->userdata('tipe_user');
								$status_spv = (int) ($magnettrap->status_spv ?? 0);
								$status_enginer = (int) ($magnettrap->status_enginer ?? 0);

								if ($status_spv === 1) {
									echo '<span class="text-success font-weight-bold">Verified</span>';
								} elseif ($status_spv === 2) {
									echo '<span class="text-danger font-weight-bold">Revision</span>';
								} elseif ($status_enginer !== 1 && in_array($tipe_user, [0, 1, 2])) {
									echo '<span style="color: #f39c12; font-weight: bold; font-size: 12px;">Menunggu Verifikasi Produksi</span>';
								} elseif ($status_enginer === 1 && $status_spv === 0) {
									echo '-';
								} else {
									echo '<span class="text-secondary font-weight-bold">Created</span>';
								}
								?>
							</td>
						</tr>

						<tr>
							<td><b>Catatan Supervisor</b></td>
							<td colspan="6">
								<?= !empty($magnettrap->catatan_spv)
									? html_escape($magnettrap->catatan_spv)
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

	.breadcrumb .breadcrumb-item.active {
		color: #fff;
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
		font-weight: 600;
		padding: 6px 10px;
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
