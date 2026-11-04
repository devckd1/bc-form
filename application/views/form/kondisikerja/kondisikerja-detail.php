<div class="container-fluid">
	<!-- Page Heading -->
	<?php if (empty($is_popup)): ?>
		<h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Detail Kondisi Kerja Selama Produksi</h1>

		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search') ?? '') : base_url('kondisikerja'); ?>">
						<i class="fas fa-arrow-left"></i> Daftar Kondisi Kerja Selama Produksi
					</a>
				</li>
			</ol>
		</nav>
	<?php endif; ?>

	<!-- Detail -->
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<?php
				$datetime = (new DateTime($kondisikerja->date))->format('d-m-Y');
				$time = (new DateTime($kondisikerja->waktu))->format('H:i');
				$nilai_keterangan = [
					'1' => 'Berdebu',
					'2' => 'Basah, ada genangan air',
					'3' => 'Sisa produksi (remah-remah roti, tepung, sisa adonan)',
					'4' => 'Kosmetik',
					'5' => 'Pertumbuhan Mikroorganisme (jamur, bau busuk, biofilm)',
					'6' => 'Kontak / kontaminasi material non halal',
					'7' => 'Higiene karyawan tidak sesuai GMP',
					'✓' => 'Ok, Sesuai SSOP, bersih, bebas najis / material non halal',
					'X' => 'Tidak Ok, tidak sesuai SSOP',
					'-' => 'Tidak ada / Tidak digunakan'
				];
				function tampilkan_kondisi($nilai, $map)
				{
					return isset($map[$nilai]) ? $map[$nilai] : $nilai;
				}
				?>
				<table class="table table-bordered">
					<thead class="text-center bg-light">
						<tr>
							<th colspan="7">KONDISI KERJA SELAMA PRODUKSI</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong>Tanggal</strong></td>
							<td><?= $datetime ?></td>
							<td><strong>Shift</strong></td>
							<td><?= $kondisikerja->shift ?></td>
							<td><strong>Pukul</strong></td>
							<td colspan="2"><?= $time ?></td>
						</tr>
						<tr>
							<td><strong>Area</strong></td>
							<td colspan="6"><?= $kondisikerja->area ?></td>
						</tr>

						<!-- Pemeriksaan -->
						<tr class="text-center bg-light font-weight-bold">
							<td>Item</td>
							<td>Kondisi</td>
							<td>Problem</td>
							<td colspan="2">Tindakan Koreksi</td>
							<td colspan="2">Verifikasi</td>
						</tr>

						<tr>
							<td>Higiene Karyawan</td>
							<td><?= tampilkan_kondisi($kondisikerja->kondisi_higiene, $nilai_keterangan); ?></td>
							<td><?= $kondisikerja->problem_higiene; ?></td>
							<td colspan="2"><?= $kondisikerja->tindakan_higiene; ?></td>
							<td colspan="2"><?= $kondisikerja->verifikasi_higiene; ?></td>
						</tr>
						<tr>
							<td>Kebersihan Peralatan</td>
							<td><?= tampilkan_kondisi($kondisikerja->kondisi_peralatan, $nilai_keterangan); ?></td>
							<td><?= $kondisikerja->problem_peralatan; ?></td>
							<td colspan="2"><?= $kondisikerja->tindakan_peralatan; ?></td>
							<td colspan="2"><?= $kondisikerja->verifikasi_peralatan; ?></td>
						</tr>
						<tr>
							<td>Kebersihan Area/Ruang</td>
							<td><?= tampilkan_kondisi($kondisikerja->kondisi_kebersihan, $nilai_keterangan); ?></td>
							<td><?= $kondisikerja->problem_kebersihan; ?></td>
							<td colspan="2"><?= $kondisikerja->tindakan_kebersihan; ?></td>
							<td colspan="2"><?= $kondisikerja->verifikasi_kebersihan; ?></td>
						</tr>

						<!-- Verifikasi -->
						<tr class="text-center table-primary font-weight-bold">
							<td colspan="7">VERIFIKASI</td>
						</tr>
						<tr>
							<td>QC</td>
							<td colspan="6"><?= $kondisikerja->username; ?></td>
						</tr>
						<tr>
							<td>Produksi</td>
							<td colspan="6"><?= $kondisikerja->nama_produksi; ?></td>
						</tr>
						<tr>
							<td>Status Supervisor</td>
							<td colspan="6">
								<?php
								if ($kondisikerja->status_spv == 0) {
									echo '<span class="badge badge-secondary">Created</span>';
								} elseif ($kondisikerja->status_spv == 1) {
									echo '<span class="badge badge-success">Verified</span>';
								} elseif ($kondisikerja->status_spv == 2) {
									echo '<span class="badge badge-danger">Revision</span>';
								}
								?>
							</td>
						</tr>
						<tr>
							<td>Catatan Supervisor</td>
							<td colspan="6"><?= !empty($kondisikerja->catatan_spv) ? $kondisikerja->catatan_spv : 'Tidak ada'; ?></td>
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

	.breadcrumb a {
		color: white;
		font-weight: bold;
		text-decoration: none;
	}

	.breadcrumb a:hover {
		text-decoration: underline;
	}

	.table td,
	.table th {
		vertical-align: middle;
		font-size: 15px;
		padding: 10px;
	}

	@media (max-width: 768px) {
		.table {
			font-size: 14px;
		}

		h1.h3 {
			font-size: 20px;
		}
	}
</style>
