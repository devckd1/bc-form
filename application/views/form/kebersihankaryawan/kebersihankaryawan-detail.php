<div class="container-fluid">
	<!-- Heading -->
	<?php if (empty($is_popup)): ?>
		<h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Detail Kebersihan Karyawan</h1>

		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search') ?? '') : base_url('kebersihankaryawan'); ?>">
						<i class="fas fa-arrow-left"></i> Daftar Kebersihan Karyawan
					</a>
				</li>
			</ol>
		</nav>
	<?php endif; ?>

	<!-- Card Detail -->
	<div class="card shadow mb-4">
		<div class="card-body">
			<!-- Wrapper agar tabel tidak menyebabkan scroll horizontal -->
			<div class="table-scroll">
				<!-- Keterangan dan Simbol -->
				<div class="row mb-4">
					<div class="col-md-6">
						<table class="table table-bordered table-sm">
							<thead class="thead-light text-center">
								<tr>
									<th colspan="2">Keterangan Pemeriksaan</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1. Seragam</td>
									<td>5. Perhiasan</td>
								</tr>
								<tr>
									<td>2. Apron</td>
									<td>6. Masker</td>
								</tr>
								<tr>
									<td>3. Tangan dan Kuku</td>
									<td>7. Topi / Hairnet</td>
								</tr>
								<tr>
									<td>4. Kosmetik</td>
									<td>8. Sepatu Kerja</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-md-6">
						<table class="table table-bordered table-sm text-center">
							<thead class="thead-light">
								<tr>
									<th colspan="2">Simbol Keterangan</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>✔️</td>
									<td>Ok</td>
								</tr>
								<tr>
									<td>❌</td>
									<td>Tidak Ok</td>
								</tr>
								<tr>
									<td>−</td>
									<td>Tidak Ada / Tidak Digunakan</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<!-- Tabel Pemeriksaan -->
				<?php $formattedDate = (new DateTime($kebersihankaryawan->date))->format('d-m-Y'); ?>
				<table class="table table-bordered">
					<thead class="text-center bg-light font-weight-bold">
						<tr>
							<th colspan="9">PEMERIKSAAN KEBERSIHAN KARYAWAN</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong>Tanggal</strong></td>
							<td colspan="8"><?= $formattedDate; ?></td>
						</tr>
						<tr>
							<td><strong>Shift</strong></td>
							<td colspan="8"><?= $kebersihankaryawan->shift; ?></td>
						</tr>
						<tr>
							<td><strong>Nama Karyawan</strong></td>
							<td colspan="8"><?= $kebersihankaryawan->nama; ?></td>
						</tr>
						<tr>
							<td><strong>Bagian</strong></td>
							<td colspan="8"><?= $kebersihankaryawan->bagian; ?></td>
						</tr>

						<!-- Hasil Pemeriksaan -->
						<tr class="text-center table-primary font-weight-bold">
							<td>Keterangan</td>
							<?php for ($i = 1; $i <= 8; $i++): ?>
								<td><?= $i ?></td>
							<?php endfor; ?>
						</tr>
						<tr class="text-center">
							<td><strong>Hasil</strong></td>
							<td><?= simbol($kebersihankaryawan->seragam); ?></td>
							<td><?= simbol($kebersihankaryawan->apron); ?></td>
							<td><?= simbol($kebersihankaryawan->tangan_kuku); ?></td>
							<td><?= simbol($kebersihankaryawan->kosmetik); ?></td>
							<td><?= simbol($kebersihankaryawan->perhiasan); ?></td>
							<td><?= simbol($kebersihankaryawan->masker); ?></td>
							<td><?= simbol($kebersihankaryawan->topi_hairnet); ?></td>
							<td><?= simbol($kebersihankaryawan->sepatu); ?></td>
						</tr>
						<tr>
							<td><strong>Tindakan Koreksi</strong></td>
							<td colspan="8"><?= $kebersihankaryawan->tindakan; ?></td>
						</tr>
						<tr>
							<td><strong>Catatan</strong></td>
							<td colspan="8"><?= !empty($kebersihankaryawan->catatan) ? $kebersihankaryawan->catatan : 'Tidak ada'; ?></td>
						</tr>

						<!-- Verifikasi -->
						<tr class="text-center bg-light font-weight-bold">
							<td colspan="9">VERIFIKASI</td>
						</tr>
						<tr>
							<td><strong>QC</strong></td>
							<td colspan="8"><?= $kebersihankaryawan->username; ?></td>
						</tr>
						<tr>
							<td><strong>Produksi</strong></td>
							<td colspan="8"><?= $kebersihankaryawan->nama_produksi ?? 'Belum dikoreksi'; ?></td>
						</tr>
						<tr>
							<td><strong>Status Supervisor</strong></td>
							<td colspan="8"><?= status_label($kebersihankaryawan->status_spv); ?></td>
						</tr>
						<tr>
							<td><strong>Catatan Supervisor</strong></td>
							<td colspan="8"><?= !empty($kebersihankaryawan->catatan_spv) ? $kebersihankaryawan->catatan_spv : 'Tidak ada'; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
</div>

<!-- CSS tambahan -->
<style>
	/* Breadcrumb */
	.breadcrumb {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
		margin-bottom: 1rem;
	}

	.breadcrumb a {
		color: white;
		font-weight: bold;
		text-decoration: none;
	}

	.breadcrumb a:hover {
		text-decoration: underline;
	}

	/* Tabel Responsif dan Scroll */
	.table-responsive {
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
	}

	.table {
		width: 100% !important;
		table-layout: auto;
		border-collapse: collapse;
	}

	.table td,
	.table th {
		vertical-align: middle;
		font-size: 15px;
		padding: 10px;
		white-space: nowrap;
		/* Cegah kolom terlalu lebar */
	}

	.table td[colspan="8"],
	.table td[colspan="9"] {
		white-space: normal !important;
		/* Tapi izinkan cell colspan meluas */
	}

	/* Fix agar tidak overflow */
	html,
	body {
		overflow-x: hidden !important;
	}

	.container-fluid {
		width: 100%;
		max-width: 100%;
		overflow-x: hidden;
		padding-left: 15px;
		padding-right: 15px;
	}

	/* Tambahan opsional untuk elemen yang mungkin membuat overflow */
	* {
		box-sizing: border-box;
	}

	/* Responsif untuk mobile */
	@media (max-width: 768px) {

		.table td,
		.table th {
			font-size: 14px;
		}

		h1.h3 {
			font-size: 20px;
		}
	}

	/* Optional: Hilangkan scrollbar horizontal dari body yang tersisa */
	::-webkit-scrollbar {
		height: 0px;
		background: transparent;
	}
</style>


<?php
// Fungsi Helper
function simbol($val)
{
	return $val == 'ok' ? '✔️' : ($val == 'tidak oke' ? '❌' : '−');
}

function status_label($status)
{
	if ($status == 1) return '<span class="badge badge-success">Verified</span>';
	if ($status == 2) return '<span class="badge badge-danger">Revision</span>';
	return '<span class="badge badge-secondary">Created</span>';
}
?>
