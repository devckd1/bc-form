<div class="container-fluid">
	<!-- Page Heading -->
	<h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Detail Pemeriksaan Magnet Trap</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search')) : base_url('magnettrap'); ?>">
					<i class="fas fa-arrow-left"></i> Daftar Pemeriksaan Magnet Trap
				</a>
			</li>
		</ol>
	</nav>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" cellspacing="0">
					<?php
					$datetime = new DateTime($magnettrap->date);
					$formattedDate = $datetime->format('d-m-Y');
					$formattedTime = (new DateTime($magnettrap->time))->format('H:i');
					?>
					<thead class="text-center">
						<tr>
							<th colspan="7" class="text-center font-weight-bold">PEMERIKSAAN MAGNET TRAP</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2"><b>Tanggal:</b> <?= $formattedDate; ?></td>
							<td><b>Shift:</b> <?= $magnettrap->shift; ?></td>
							<td colspan="4"><b>Pukul:</b> <?= $formattedTime; ?></td>
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

						<tr class="bg-light text-center">
							<td colspan="7" class="font-weight-bold">Hasil Pemeriksaan</td>
						</tr>
						<tr>
							<td><b>Tahapan</b></td>
							<td colspan="6"><?= $magnettrap->tahapan; ?></td>
						</tr>
						<tr>
							<td><b>Jenis Kontaminasi</b></td>
							<td colspan="6"><?= $magnettrap->kontaminasi; ?></td>
						</tr>
						<tr>
							<td><b>Bukti Temuan</b></td>
							<td colspan="6">
								<?php
								$bukti = $magnettrap->bukti ?? null;

								$path1 = FCPATH . 'uploads/' . $bukti;
								$path2 = FCPATH . 'uploads/magnettrap/' . $bukti;

								if (!empty($bukti)) {
									if (file_exists($path1)) {
										$img_url = base_url('uploads/' . $bukti);
									} elseif (file_exists($path2)) {
										$img_url = base_url('uploads/magnettrap/' . $bukti);
									} else {
										$img_url = null;
									}
								} else {
									$img_url = null;
								}
								?>

								<?php if ($img_url): ?>
									<img src="<?= $img_url; ?>" alt="Bukti Temuan" style="max-width: 200px; max-height: 150px;">
								<?php else: ?>
									<p>Tidak ada gambar</p>
								<?php endif; ?>

							</td>
						</tr>
						<tr>
							<td><b>Analisis Temuan</b></td>
							<td colspan="6"><?= $magnettrap->analisis; ?></td>
						</tr>
						<tr>
							<td><b>Tindakan Koreksi</b></td>
							<td colspan="6"><?= $magnettrap->tindakan; ?></td>
						</tr>
						<tr>
							<td><b>Verifikasi</b></td>
							<td colspan="6"><?= $magnettrap->verifikasi; ?></td>
						</tr>
						<tr>
							<td><b>Keterangan</b></td>
							<td colspan="6"><?= !empty($magnettrap->keterangan) ? $magnettrap->keterangan : 'Tidak ada'; ?></td>
						</tr>
						<tr>
							<td><b>Catatan</b></td>
							<td colspan="6"><?= !empty($magnettrap->catatan) ? $magnettrap->catatan : 'Tidak ada'; ?></td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>
						<tr>
							<td><b>QC</b></td>
							<td colspan="6"><?= $magnettrap->username; ?></td>
						</tr>
						<tr>
							<td><b>Engineer</b></td>
							<td colspan="6"><?= $magnettrap->nama_enginer; ?></td>
						</tr>
						<tr>
							<td><b>Status Supervisor</b></td>
							<td colspan="6">
								<?php
								switch ($magnettrap->status_spv) {
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
							<td colspan="6"><?= !empty($magnettrap->catatan_spv) ? $magnettrap->catatan_spv : 'Tidak ada'; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<!-- CSS -->
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
