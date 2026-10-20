<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? base_url('verifikasimagnet/audit')
	: base_url('verifikasimagnet');

$page_title = $is_audit
	? 'Detail Audit Verifikasi Magnet Trap'
	: 'Detail Verifikasi Magnet Trap';

$datetime = new DateTime($verifikasimagnet->date);
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-3 text-gray-800 font-weight-bold">
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
				<a href="<?= $back_url; ?>" class="text-white">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit
						? 'Daftar Audit Verifikasi Magnet Trap \ Detail'
						: 'Daftar Verifikasi Magnet Trap'; ?>
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
							<th colspan="7" class="text-center font-weight-bold">
								VERIFIKASI MAGNET TRAP
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td colspan="2">
								<b>Tanggal:</b>
								<?= $datetime->format('d-m-Y'); ?>
							</td>
							<td colspan="5"></td>
						</tr>

						<tr class="bg-light text-center">
							<td colspan="7" class="font-weight-bold">
								Hasil Pemeriksaan
							</td>
						</tr>

						<tr>
							<td><b>Shift</b></td>
							<td colspan="6">
								<?= html_escape($verifikasimagnet->shift); ?>
							</td>
						</tr>

						<tr>
							<td><b>Nama Produk</b></td>
							<td colspan="6">
								<?= html_escape($verifikasimagnet->nama_produk); ?>
							</td>
						</tr>

						<tr>
							<td><b>Kode Produksi</b></td>
							<td colspan="6">
								<?= html_escape($verifikasimagnet->kode_produksi); ?>
							</td>
						</tr>

						<tr>
							<td><b>Jumlah Temuan</b></td>
							<td colspan="6">
								<?= html_escape($verifikasimagnet->jumlah_temuan); ?>
							</td>
						</tr>

						<tr>
							<td><b>Bukti Temuan</b></td>
							<td colspan="6" class="text-center">
								<?php if (!empty($verifikasimagnet->bukti_temuan)): ?>
									<?php
									$file = $verifikasimagnet->bukti_temuan;
									$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
									?>

									<?php if (in_array($ext, ['jpg', 'jpeg', 'png'])): ?>
										<a href="<?= base_url('uploads/bukti_temuan/' . rawurlencode($file)); ?>"
											target="_blank" rel="noopener noreferrer">
											<img
												src="<?= base_url('uploads/bukti_temuan/' . rawurlencode($file)); ?>"
												alt="Bukti Temuan"
												style="max-width:150px;max-height:150px;border-radius:8px;object-fit:cover;">
										</a>
									<?php else: ?>
										<a href="<?= base_url('uploads/bukti_temuan/' . rawurlencode($file)); ?>"
											target="_blank" rel="noopener noreferrer">
											<i class="fas fa-file-pdf mr-1"></i>
											Lihat Bukti Temuan
										</a>
									<?php endif; ?>
								<?php else: ?>
									<span class="text-muted">-</span>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<td><b>Keterangan</b></td>
							<td colspan="6">
								<?= !empty($verifikasimagnet->keterangan)
									? nl2br(html_escape($verifikasimagnet->keterangan))
									: '-'; ?>
							</td>
						</tr>

						<tr class="bg-light">
							<td><b>Catatan</b></td>
							<td colspan="6">
								<?= !empty($verifikasimagnet->catatan)
									? nl2br(html_escape($verifikasimagnet->catatan))
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>

						<tr>
							<td><b>QC</b></td>
							<td colspan="6">
								<?= html_escape($verifikasimagnet->username); ?>
							</td>
						</tr>

						<tr>
							<td><b>Produksi</b></td>
							<td colspan="6">
								<?= !empty($verifikasimagnet->nama_produksi)
									? html_escape($verifikasimagnet->nama_produksi)
									: 'Belum dikoreksi'; ?>
							</td>
						</tr>

						<tr>
							<td><b>Status Supervisor</b></td>
							<td colspan="6">
								<?php
								switch ((int) $verifikasimagnet->status_spv) {
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
							<td><b>Catatan Supervisor</b></td>
							<td colspan="6">
								<?= !empty($verifikasimagnet->catatan_spv)
									? nl2br(html_escape($verifikasimagnet->catatan_spv))
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
		padding: 8px 14px;
		border-radius: 6px;
		font-size: 13px;
		font-weight: 700;
		white-space: nowrap;
	}

	.btn-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-audit:hover {
		background-color: #452477;
		border-color: #452477;
		color: #fff;
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
