<div class="container-fluid">

	<!-- Heading -->
	<h1 class="h3 mb-3 text-gray-800 font-weight-bold">
		Detail Audit Kebersihan Karyawan
		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-audit">
			<li class="breadcrumb-item">
				<a href="<?= base_url('kebersihankaryawan/audit'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Kebersihan Karyawan \ Detail
				</a>
			</li>
		</ol>
	</nav>

	<!-- Informasi Audit -->
	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi Audit:</strong>
		Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan untuk keperluan pemeriksaan serta verifikasi.
		Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<!-- Card Detail -->
	<div class="card shadow mb-4">
		<div class="card-header py-3 audit-header">
			<h6 class="m-0 font-weight-bold text-white">
				<i class="fas fa-user-check mr-1"></i>
				Detail Pemeriksaan Kebersihan Karyawan
			</h6>
		</div>

		<div class="card-body">

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

				<?php
				$formattedDate = !empty($kebersihankaryawan->date)
					? (new DateTime($kebersihankaryawan->date))->format('d-m-Y')
					: '-';
				?>

				<!-- Tabel Pemeriksaan -->
				<table class="table table-bordered">

					<thead class="text-center font-weight-bold thead-light">
						<tr>
							<th colspan="9">
								PEMERIKSAAN KEBERSIHAN KARYAWAN
							</th>
						</tr>
					</thead>

					<tbody>

						<tr>
							<td><strong>Tanggal</strong></td>
							<td colspan="8">
								<?= $formattedDate; ?>
							</td>
						</tr>

						<tr>
							<td><strong>Shift</strong></td>
							<td colspan="8">
								<?= htmlspecialchars($kebersihankaryawan->shift ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><strong>Nama Karyawan</strong></td>
							<td colspan="8">
								<?= htmlspecialchars($kebersihankaryawan->nama ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td><strong>Bagian</strong></td>
							<td colspan="8">
								<?= htmlspecialchars($kebersihankaryawan->bagian ?? '-'); ?>
							</td>
						</tr>

						<!-- Hasil Pemeriksaan -->
						<tr class="text-center thead-light font-weight-bold">
							<td>Keterangan</td>

							<?php for ($i = 1; $i <= 8; $i++): ?>
								<td><?= $i; ?></td>
							<?php endfor; ?>
						</tr>

						<tr class="text-center">
							<td>
								<strong>Hasil</strong>
							</td>

							<td>
								<?= simbol($kebersihankaryawan->seragam); ?>
							</td>

							<td>
								<?= simbol($kebersihankaryawan->apron); ?>
							</td>

							<td>
								<?= simbol($kebersihankaryawan->tangan_kuku); ?>
							</td>

							<td>
								<?= simbol($kebersihankaryawan->kosmetik); ?>
							</td>

							<td>
								<?= simbol($kebersihankaryawan->perhiasan); ?>
							</td>

							<td>
								<?= simbol($kebersihankaryawan->masker); ?>
							</td>

							<td>
								<?= simbol($kebersihankaryawan->topi_hairnet); ?>
							</td>

							<td>
								<?= simbol($kebersihankaryawan->sepatu); ?>
							</td>
						</tr>

						<tr>
							<td>
								<strong>Tindakan Koreksi</strong>
							</td>

							<td colspan="8">
								<?= !empty($kebersihankaryawan->tindakan)
									? htmlspecialchars($kebersihankaryawan->tindakan)
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<tr>
							<td>
								<strong>Catatan</strong>
							</td>

							<td colspan="8">
								<?= !empty($kebersihankaryawan->catatan)
									? htmlspecialchars($kebersihankaryawan->catatan)
									: 'Tidak ada'; ?>
							</td>
						</tr>

						<!-- Verifikasi -->
						<tr class="text-center font-weight-bold thead-light">
							<td colspan="9">
								VERIFIKASI
							</td>
						</tr>

						<tr>
							<td>
								<strong>QC</strong>
							</td>

							<td colspan="8">
								<?= htmlspecialchars($kebersihankaryawan->username ?? '-'); ?>
							</td>
						</tr>

						<tr>
							<td>
								<strong>Produksi</strong>
							</td>

							<td colspan="8">
								<?= !empty($kebersihankaryawan->nama_produksi)
									? htmlspecialchars($kebersihankaryawan->nama_produksi)
									: 'Belum dikoreksi'; ?>
							</td>
						</tr>

						<tr>
							<td>
								<strong>Status Supervisor</strong>
							</td>

							<td colspan="8">
								<?= status_label($kebersihankaryawan->status_spv); ?>
							</td>
						</tr>

						<tr>
							<td>
								<strong>Catatan Supervisor</strong>
							</td>

							<td colspan="8">
								<?= !empty($kebersihankaryawan->catatan_spv)
									? htmlspecialchars($kebersihankaryawan->catatan_spv)
									: 'Tidak ada'; ?>
							</td>
						</tr>

					</tbody>
				</table>

			</div>
		</div>
	</div>

</div>


<!-- CSS Audit -->
<style>
	.breadcrumb-audit {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
		margin-bottom: 1rem;
	}

	.breadcrumb-audit a {
		color: white;
		text-decoration: none;
	}

	.breadcrumb-audit a:hover {
		text-decoration: underline;
	}

	/* Badge MODE AUDIT */
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		font-weight: 700;
		padding: 6px 10px;
		border-radius: 20px;
		vertical-align: middle;
		margin-left: 8px;
		letter-spacing: 0.3px;
	}

	/* Header card audit */
	.audit-header {
		background-color: #5a3298;
		border-left: 5px solid #3f216d;
	}

	/* Header tabel utama */
	.audit-table-header {
		background-color: #5a3298;
		color: white;
	}

	/* Header hasil pemeriksaan */
	.audit-section-header {
		background-color: #eee5fa;
		color: #5a3298;
	}

	/* Header verifikasi */
	.audit-verifikasi-header {
		background-color: #5a3298;
		color: white;
	}

	/* Tabel */
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
	}

	.table td[colspan="8"],
	.table td[colspan="9"] {
		white-space: normal !important;
	}

	/* Wrapper */
	.table-scroll {
		width: 100%;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
	}

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

	* {
		box-sizing: border-box;
	}

	@media (max-width: 768px) {

		.table td,
		.table th {
			font-size: 14px;
		}

		h1.h3 {
			font-size: 20px;
		}

		.badge-mode-audit {
			display: block;
			width: fit-content;
			margin: 8px auto 0;
		}
	}

	::-webkit-scrollbar {
		height: 0px;
		background: transparent;
	}
</style>


<?php

function simbol($val)
{
	return $val == 'ok'
		? '✔️'
		: ($val == 'tidak oke' ? '❌' : '−');
}

function status_label($status)
{
	if ($status == 1) {
		return '<span class="badge badge-success">Verified</span>';
	}

	if ($status == 2) {
		return '<span class="badge badge-danger">Revision</span>';
	}

	return '<span class="badge badge-secondary">Created</span>';
}

?>
