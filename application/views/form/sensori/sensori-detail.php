<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit ? 'sensori/audit' : 'sensori';

$tanggal = !empty($sensori->date)
	? date('d-m-Y', strtotime($sensori->date))
	: '-';

$sensori_produk = json_decode($sensori->produk ?? '[]', true);

if (!is_array($sensori_produk)) {
	$sensori_produk = [];
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			Detail Laporan Sensori Finish Good

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
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Sensori Finish Good \ Detail'
						: 'Daftar Laporan Sensori Finish Good'; ?>
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
								LAPORAN SENSORI FINISH GOOD
							</th>
						</tr>
					</thead>

					<tbody>
						<tr>
							<td colspan="2"><b>Tanggal:</b></td>
							<td colspan="5"><?= html_escape($tanggal); ?></td>
						</tr>

						<tr>
							<td colspan="2"><b>Nama Produk</b></td>
							<td colspan="5">
								<?= html_escape($sensori->nama_produk ?? '-'); ?>
							</td>
						</tr>

						<tr class="bg-light text-center">
							<td colspan="7" class="font-weight-bold">
								Hasil Sensori Produk
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="2">Kode Produksi / BB</th>
							<th>Warna</th>
							<th>Tekstur</th>
							<th>Rasa</th>
							<th>Aroma</th>
							<th>Kenampakan</th>
						</tr>

						<?php if (!empty($sensori_produk)): ?>
							<?php foreach ($sensori_produk as $row): ?>
								<tr class="text-center">
									<td colspan="2">
										<?= html_escape($row['kode_produksi'] ?? '-'); ?>
										/
										<?= html_escape($row['best_before'] ?? '-'); ?>
									</td>

									<?php
									$atribut = [
										'warna',
										'tekstur',
										'rasa',
										'aroma',
										'kenampakan'
									];
									?>

									<?php foreach ($atribut as $field): ?>
										<?php
										$nilai = $row[$field] ?? '-';
										$nilai_normal = strtolower(trim($nilai));
										?>

										<td>
											<?php if ($nilai_normal === 'ok'): ?>
												<span class="text-success font-weight-bold">
													&#10004;
												</span>
											<?php elseif ($nilai_normal === 'tidak ok'): ?>
												<span class="text-danger font-weight-bold">
													&#10008;
												</span>
											<?php else: ?>
												<?= html_escape($nilai); ?>
											<?php endif; ?>
										</td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="7" class="text-center text-muted">
									Data sensori produk tidak tersedia.
								</td>
							</tr>
						<?php endif; ?>

						<tr>
							<td colspan="2"><b>Tindakan Koreksi</b></td>
							<td colspan="5">
								<?= !empty($sensori->tindakan)
									? nl2br(html_escape($sensori->tindakan))
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr>
							<td colspan="2"><b>Catatan</b></td>
							<td colspan="5">
								<?= !empty($sensori->catatan)
									? nl2br(html_escape($sensori->catatan))
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>

						<tr>
							<td><b>QC</b></td>
							<td colspan="6">
								<?= html_escape($sensori->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Produksi</b></td>
							<td colspan="6">
								<?= html_escape($sensori->nama_produksi ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><b>Status Supervisor</b></td>
							<td colspan="6">
								<?php if (($sensori->status_spv ?? null) == 1): ?>
									<span class="text-success font-weight-bold">
										Verified
									</span>
								<?php elseif (($sensori->status_spv ?? null) == 2): ?>
									<span class="text-danger font-weight-bold">
										Revision
									</span>
								<?php else: ?>
									<span class="text-secondary font-weight-bold">
										Created
									</span>
								<?php endif; ?>
							</td>
						</tr>

						<tr>
							<td><b>Catatan Supervisor</b></td>
							<td colspan="6">
								<?= !empty($sensori->catatan_spv)
									? nl2br(html_escape($sensori->catatan_spv))
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

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
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
