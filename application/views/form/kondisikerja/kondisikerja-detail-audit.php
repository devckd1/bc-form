<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-3 text-gray-800 font-weight-bold">
		Detail Audit Kondisi Kerja Selama Produksi

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-audit">
			<li class="breadcrumb-item">
				<a href="<?= base_url('kondisikerja/audit') ?>">
					<i class="fas fa-arrow-left"></i> Daftar Audit Kondisi Kerja
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Detail
			</li>
		</ol>
	</nav>

	<!-- Informasi Audit -->
	<div class="alert alert-audit">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan detail data Audit Kondisi Kerja.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<!-- Detail -->
	<div class="card shadow mb-4 card-audit">

		<div class="card-body">

			<div class="table-responsive">

				<?php

				$datetime = !empty($kondisikerja->date)
					? (new DateTime($kondisikerja->date))->format('d-m-Y')
					: '-';

				$time = !empty($kondisikerja->waktu)
					? (new DateTime($kondisikerja->waktu))->format('H:i')
					: '-';

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

				function tampilkan_kondisi_audit($nilai, $map)
				{
					return isset($map[$nilai]) ? $map[$nilai] : $nilai;
				}

				?>

				<table class="table table-bordered">

					<thead class="text-center bg-light">
						<tr>
							<th colspan="7">
								KONDISI KERJA SELAMA PRODUKSI
							</th>
						</tr>
					</thead>

					<tbody>

						<!-- Informasi Pemeriksaan -->

						<tr>

							<td>
								<strong>Tanggal</strong>
							</td>

							<td>
								<?= htmlspecialchars($datetime); ?>
							</td>

							<td>
								<strong>Shift</strong>
							</td>

							<td>
								<?= htmlspecialchars($kondisikerja->shift ?? '-'); ?>
							</td>

							<td>
								<strong>Pukul</strong>
							</td>

							<td colspan="2">
								<?= htmlspecialchars($time); ?>
							</td>

						</tr>


						<tr>

							<td>
								<strong>Area</strong>
							</td>

							<td colspan="6">
								<?= htmlspecialchars($kondisikerja->area ?? '-'); ?>
							</td>

						</tr>


						<!-- Pemeriksaan -->

						<tr class="text-center bg-light font-weight-bold">

							<td>
								Item
							</td>

							<td>
								Kondisi
							</td>

							<td>
								Problem
							</td>

							<td colspan="2">
								Tindakan Koreksi
							</td>

							<td colspan="2">
								Verifikasi
							</td>

						</tr>


						<!-- Higiene Karyawan -->

						<tr>

							<td>
								Higiene Karyawan
							</td>

							<td>
								<?= tampilkan_kondisi_audit(
									$kondisikerja->kondisi_higiene ?? '',
									$nilai_keterangan
								); ?>
							</td>

							<td>
								<?= htmlspecialchars($kondisikerja->problem_higiene ?? '-'); ?>
							</td>

							<td colspan="2">
								<?= htmlspecialchars($kondisikerja->tindakan_higiene ?? '-'); ?>
							</td>

							<td colspan="2">
								<?= htmlspecialchars($kondisikerja->verifikasi_higiene ?? '-'); ?>
							</td>

						</tr>


						<!-- Kebersihan Peralatan -->

						<tr>

							<td>
								Kebersihan Peralatan
							</td>

							<td>
								<?= tampilkan_kondisi_audit(
									$kondisikerja->kondisi_peralatan ?? '',
									$nilai_keterangan
								); ?>
							</td>

							<td>
								<?= htmlspecialchars($kondisikerja->problem_peralatan ?? '-'); ?>
							</td>

							<td colspan="2">
								<?= htmlspecialchars($kondisikerja->tindakan_peralatan ?? '-'); ?>
							</td>

							<td colspan="2">
								<?= htmlspecialchars($kondisikerja->verifikasi_peralatan ?? '-'); ?>
							</td>

						</tr>


						<!-- Kebersihan Area -->

						<tr>

							<td>
								Kebersihan Area/Ruang
							</td>

							<td>
								<?= tampilkan_kondisi_audit(
									$kondisikerja->kondisi_kebersihan ?? '',
									$nilai_keterangan
								); ?>
							</td>

							<td>
								<?= htmlspecialchars($kondisikerja->problem_kebersihan ?? '-'); ?>
							</td>

							<td colspan="2">
								<?= htmlspecialchars($kondisikerja->tindakan_kebersihan ?? '-'); ?>
							</td>

							<td colspan="2">
								<?= htmlspecialchars($kondisikerja->verifikasi_kebersihan ?? '-'); ?>
							</td>

						</tr>


						<!-- Verifikasi -->

						<tr class="text-center bg-light">

							<td colspan="7">
								VERIFIKASI
							</td>

						</tr>


						<tr>

							<td>
								QC
							</td>

							<td colspan="6">
								<?= htmlspecialchars($kondisikerja->username ?? '-'); ?>
							</td>

						</tr>


						<tr>

							<td>
								Produksi
							</td>

							<td colspan="6">
								<?= htmlspecialchars($kondisikerja->nama_produksi ?? '-'); ?>
							</td>

						</tr>


						<tr>

							<td>
								Status Supervisor
							</td>

							<td colspan="6">

								<?php

								if ($kondisikerja->status_spv == 0) {

									echo '<span class="badge badge-secondary">
                                            Created
                                          </span>';
								} elseif ($kondisikerja->status_spv == 1) {

									echo '<span class="badge badge-success">
                                            Verified
                                          </span>';
								} elseif ($kondisikerja->status_spv == 2) {

									echo '<span class="badge badge-danger">
                                            Revision
                                          </span>';
								} else {

									echo '<span class="badge badge-secondary">
                                            -
                                          </span>';
								}

								?>

							</td>

						</tr>


						<tr>

							<td>
								Catatan Supervisor
							</td>

							<td colspan="6">
								<?= !empty($kondisikerja->catatan_spv)
									? htmlspecialchars($kondisikerja->catatan_spv)
									: 'Tidak ada';
								?>
							</td>

						</tr>

					</tbody>

				</table>

			</div>

		</div>

	</div>

</div>


<style>
	/* Breadcrumb Audit */

	.breadcrumb-audit {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb-audit a {
		color: #fff !important;
		text-decoration: none;
	}

	.breadcrumb-audit a:hover {
		text-decoration: underline;
	}


	/* Mode Audit */

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 6px 12px;
		border-radius: 6px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
		margin-left: 6px;
		box-shadow: 0 2px 5px rgba(90, 50, 152, 0.25);
	}


	/* Card Audit */

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}


	/* Alert Audit */

	.alert-audit {
		background-color: #f3e8ff;
		border-left: 4px solid #6f42c1;
		color: #4b2a75;
	}


	/* Header Table */

	table.table {
		border-collapse: collapse !important;
	}

	table.table td,
	table.table th {
		border: 1px solid #dee2e6 !important;
		vertical-align: middle;
		padding: 10px;
	}


	/* Header Verifikasi */

	.verification-audit {
		background-color: #f3e8ff;
		color: #4b2a75;
		font-weight: bold;
	}


	@media (max-width: 768px) {

		.table {
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
</style>
