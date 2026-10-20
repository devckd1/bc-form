<?php
$is_audit = isset($mode) && $mode === 'audit';
$back_url = $is_audit ? 'kontaminasi/audit' : 'kontaminasi';

$tanggal = !empty($kontaminasi->date)
	? date('d-m-Y', strtotime($kontaminasi->date))
	: '-';

$jam = !empty($kontaminasi->time)
	? date('H:i', strtotime($kontaminasi->time))
	: '-';

$bukti = $kontaminasi->bukti ?? null;
$img_url = null;
$is_pdf = false;

if (!empty($bukti)) {
	$path1 = FCPATH . 'uploads/' . $bukti;
	$path2 = FCPATH . 'uploads/kontaminasi/' . $bukti;

	if (is_file($path2)) {
		$img_url = base_url('uploads/kontaminasi/' . rawurlencode($bukti));
	} elseif (is_file($path1)) {
		$img_url = base_url('uploads/' . rawurlencode($bukti));
	}

	$is_pdf = strtolower(pathinfo($bukti, PATHINFO_EXTENSION)) === 'pdf';
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			Detail Kontaminasi Benda Asing

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb <?= $is_audit ? 'breadcrumb-qc' : 'breadcrumb-qc'; ?>">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					<?= $is_audit
						? 'Daftar Audit Kontaminasi Benda Asing / Detail'
						: 'Daftar Data Kontaminasi Benda Asing'; ?>
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
				<table class="table table-bordered">
					<thead class="text-center">
						<tr>
							<th colspan="7">KONTAMINASI BENDA ASING</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td colspan="2"><b>Tanggal:</b> <?= html_escape($tanggal); ?></td>
							<td><b>Pukul:</b> <?= html_escape($jam); ?></td>
							<td colspan="4"><b>Shift:</b> <?= html_escape($kontaminasi->shift ?? '-'); ?></td>
						</tr>

						<tr class="bg-light text-center">
							<td colspan="7"><b>Hasil Pemeriksaan</b></td>
						</tr>

						<tr>
							<td colspan="2"><b>Jenis Kontaminasi</b></td>
							<td colspan="5"><?= html_escape($kontaminasi->jenis_kontaminasi ?? '-'); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Nama Produk</b></td>
							<td colspan="5"><?= html_escape($kontaminasi->nama_produk ?? '-'); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Kode Produksi</b></td>
							<td colspan="5"><?= html_escape($kontaminasi->kode_produksi ?? '-'); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Tahapan</b></td>
							<td colspan="5"><?= nl2br(html_escape($kontaminasi->tahapan ?? '-')); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Jumlah Temuan</b></td>
							<td colspan="5"><?= html_escape($kontaminasi->jumlah_temuan ?? '-'); ?></td>
						</tr>

						<tr>
							<td colspan="2"><b>Bukti Temuan</b></td>
							<td colspan="5">
								<?php if ($img_url && $is_pdf): ?>
									<a href="<?= $img_url; ?>" target="_blank" rel="noopener">
										<i class="fas fa-file-pdf text-danger mr-1"></i>
										Lihat Dokumen Bukti (PDF)
									</a>

								<?php elseif ($img_url): ?>
									<a href="<?= $img_url; ?>" target="_blank" rel="noopener">
										<img src="<?= $img_url; ?>"
											alt="Bukti Temuan"
											style="max-width:200px;max-height:150px;object-fit:contain;">
									</a>
									<div class="mt-1">
										<small class="text-muted">Klik gambar untuk melihat ukuran penuh.</small>
									</div>

								<?php else: ?>
									<span class="text-muted">Bukti tidak tersedia.</span>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Analisis Temuan</b></td>
							<td colspan="5"><?= nl2br(html_escape($kontaminasi->analisis ?? '-')); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Tindakan Koreksi</b></td>
							<td colspan="5"><?= nl2br(html_escape($kontaminasi->tindakan ?? '-')); ?></td>
						</tr>
						<tr>
							<td colspan="2"><b>Keterangan</b></td>
							<td colspan="5">
								<?= !empty($kontaminasi->keterangan)
									? nl2br(html_escape($kontaminasi->keterangan))
									: 'Tidak ada'; ?>
							</td>
						</tr>
						<tr>
							<td colspan="2"><b>Catatan</b></td>
							<td colspan="5">
								<?= !empty($kontaminasi->catatan)
									? nl2br(html_escape($kontaminasi->catatan))
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>
						<tr>
							<td><b>QC</b></td>
							<td colspan="6"><?= html_escape($kontaminasi->username ?? '-'); ?></td>
						</tr>
						<tr>
							<td><b>Produksi</b></td>
							<td colspan="6"><?= html_escape($kontaminasi->nama_produksi ?? '-'); ?></td>
						</tr>
						<tr>
							<td><b>Status Supervisor</b></td>
							<td colspan="6">
								<?php
								switch ((int) ($kontaminasi->status_spv ?? 0)) {
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
								<?= !empty($kontaminasi->catatan_spv)
									? nl2br(html_escape($kontaminasi->catatan_spv))
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
		vertical-align: middle;
		background: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
	}

	.breadcrumb-qc {
		background-color: #2E86C1;
	}

	.alert-audit {
		background: #f1eafb;
		border: 1px solid #5a3298;
		color: #452477;
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
