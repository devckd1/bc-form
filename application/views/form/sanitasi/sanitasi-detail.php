<div class="container-fluid">
	<!-- Page Heading -->
	<?php if (empty($is_popup)): ?>
		<h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Detail Pemeriksaan Sanitasi</h1>

		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search') ?? '') : base_url('sanitasi'); ?>">
						<i class="fas fa-arrow-left"></i> Daftar Pemeriksaan Sanitasi
					</a>
				</li>
			</ol>
		</nav>
	<?php endif; ?>
	<!-- Detail Pemeriksaan -->
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<?php $datetime = (new DateTime($sanitasi->date))->format('d-m-Y'); ?>
				<table class="table table-bordered">
					<thead class="text-center">
						<tr>
							<th colspan="7" class="bg-light font-weight-bold">PEMERIKSAAN SANITASI</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2"><strong>Tanggal</strong></td>
							<td colspan="5"><?= $datetime; ?></td>
						</tr>
						<tr>
							<td colspan="2"><strong>Shift</strong></td>
							<td colspan="5"><?= $sanitasi->shift; ?></td>
						</tr>
						<tr>
							<td colspan="2"><strong>Pukul</strong></td>
							<td colspan="5"><?= date('H:i', strtotime($sanitasi->waktu)); ?></td>
						</tr>

						<!-- Area Pemeriksaan -->
						<tr class="text-center bg-light">
							<th colspan="7">DAFTAR HASIL PEMERIKSAAN</th>
						</tr>
						<tr class="text-center">
							<th>Area</th>
							<th>Standar (ppm)</th>
							<th>Aktual</th>
							<th>Bukti</th>
							<th>Suhu Air</th>
							<th>Keterangan</th>
							<th>Tindakan</th>
						</tr>
						<?php
						$result = json_decode($sanitasi->area, true) ?? [];
						foreach ($result as $row): ?>
							<tr>
								<td><?= htmlspecialchars($row['sub_area']) ?></td>
								<td><?= htmlspecialchars($row['standar']) ?></td>
								<td><?= htmlspecialchars($row['aktual']) ?></td>
								<td class="text-center">
									<?php if (!empty($row['gambar'])): ?>
										<img src="<?= base_url('uploads/sanitasi/' . $row['gambar']) ?>" alt="Bukti" style="width: 100px; height: auto;">
									<?php else: ?>
										<span class="text-muted">Tidak ada</span>
									<?php endif; ?>
								</td>
								<td><?= htmlspecialchars($row['suhu_air']) ?></td>
								<td><?= htmlspecialchars($row['keterangan']) ?></td>
								<td><?= htmlspecialchars($row['tindakan']) ?></td>
							</tr>
						<?php endforeach; ?>

						<!-- Catatan -->
						<tr class="bg-light">
							<td><strong>Catatan</strong></td>
							<td colspan="6"><?= !empty($sanitasi->catatan) ? $sanitasi->catatan : 'Tidak ada'; ?></td>
						</tr>

						<!-- Verifikasi -->
						<tr class="text-center table-primary">
							<th colspan="7">VERIFIKASI</th>
						</tr>
						<tr>
							<td><strong>QC</strong></td>
							<td colspan="6"><?= $sanitasi->username; ?></td>
						</tr>
						<tr>
							<td><strong>Produksi</strong></td>
							<td colspan="6"><?= !empty($sanitasi->nama_produksi) ? $sanitasi->nama_produksi : 'Belum dikoreksi'; ?></td>
						</tr>
						<tr>
							<td><strong>Status Supervisor</strong></td>
							<td colspan="6">
								<?php
								if ($sanitasi->status_spv == 0) {
									echo '<span class="badge badge-secondary">Created</span>';
								} elseif ($sanitasi->status_spv == 1) {
									echo '<span class="badge badge-success">Verified</span>';
								} elseif ($sanitasi->status_spv == 2) {
									echo '<span class="badge badge-danger">Revision</span>';
								}
								?>
							</td>
						</tr>
						<tr>
							<td><strong>Catatan Supervisor</strong></td>
							<td colspan="6"><?= !empty($sanitasi->catatan_spv) ? $sanitasi->catatan_spv : 'Tidak ada'; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<!-- Style -->
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

		.table img {
			width: 80px;
		}

		h1.h3 {
			font-size: 20px;
		}
	}
</style>
