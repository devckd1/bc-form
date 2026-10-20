<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-3 text-gray-800 font-weight-bold">
		Detail Audit Pemeriksaan Pengayakan

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white" href="<?= base_url('pengayakan/audit'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Pengayakan \ Detail
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

	<div class="card shadow rounded mb-5 card-audit">
		<div class="card-body">

			<div class="table-responsive">

				<?php
				$datetime = !empty($pengayakan->date)
					? (new DateTime($pengayakan->date))->format('d-m-Y')
					: '-';
				?>

				<table class="table table-bordered" width="100%" cellspacing="0">

					<thead class="text-center">
						<tr>
							<th colspan="7" class="font-weight-bold table-title">
								PEMERIKSAAN PENGAYAKAN
							</th>
						</tr>
					</thead>

					<tbody>

						<!-- Informasi Pemeriksaan -->
						<tr>
							<td><strong>Tanggal</strong></td>
							<td colspan="3"><?= $datetime; ?></td>

							<td><strong>Shift</strong></td>
							<td colspan="2"><?= !empty($pengayakan->shift) ? $pengayakan->shift : '-'; ?></td>
						</tr>

						<tr>
							<td><strong>Nama Barang</strong></td>
							<td colspan="6">
								<?= !empty($pengayakan->nama_barang) ? $pengayakan->nama_barang : '-'; ?>
							</td>
						</tr>

						<tr>
							<td><strong>Kode Produksi</strong></td>
							<td colspan="6">
								<?= !empty($pengayakan->kode_produksi) ? $pengayakan->kode_produksi : '-'; ?>
							</td>
						</tr>

						<tr>
							<td><strong>Expired Date</strong></td>
							<td colspan="6">
								<?= !empty($pengayakan->expired_date) ? $pengayakan->expired_date : '-'; ?>
							</td>
						</tr>

						<tr>
							<td><strong>Jumlah Barang</strong></td>
							<td colspan="6">
								<?= !empty($pengayakan->jumlah_barang) ? $pengayakan->jumlah_barang : '-'; ?>
							</td>
						</tr>


						<!-- Kontaminasi -->
						<tr class="bg-light text-center">
							<th><strong>Kontaminasi Benda Asing</strong></th>
							<th colspan="2">Screen Mess</th>
							<th>Kerikil</th>
							<th>Benang</th>
							<th colspan="2"></th>
						</tr>

						<tr class="text-center">
							<td><strong>Jumlah</strong></td>

							<td colspan="2">
								<?= !empty($pengayakan->kba_screenmess)
									? $pengayakan->kba_screenmess
									: '-'; ?>
							</td>

							<td>
								<?= !empty($pengayakan->kba_kerikil)
									? $pengayakan->kba_kerikil
									: '-'; ?>
							</td>

							<td>
								<?= !empty($pengayakan->kba_benang)
									? $pengayakan->kba_benang
									: '-'; ?>
							</td>

							<td colspan="2"></td>
						</tr>


						<!-- Kondisi Screen -->
						<tr>
							<td><strong>Kondisi Screen Ayakan</strong></td>
							<td colspan="6">
								<?= !empty($pengayakan->kondisi)
									? $pengayakan->kondisi
									: '-'; ?>
							</td>
						</tr>


						<!-- Catatan -->
						<tr>
							<td><strong>Catatan</strong></td>
							<td colspan="6">
								<?= !empty($pengayakan->catatan)
									? $pengayakan->catatan
									: 'Tidak ada'; ?>
							</td>
						</tr>


						<!-- Verifikasi -->
						<tr class="text-center">
							<th colspan="7">
								VERIFIKASI AUDIT
							</th>
						</tr>

						<tr>
							<td><strong>QC</strong></td>
							<td colspan="6">
								<?= !empty($pengayakan->username)
									? $pengayakan->username
									: '-'; ?>
							</td>
						</tr>

						<tr>
							<td><strong>Produksi</strong></td>
							<td colspan="6">
								<?= !empty($pengayakan->nama_produksi)
									? $pengayakan->nama_produksi
									: '-'; ?>
							</td>
						</tr>

						<tr>
							<td><strong>Status Supervisor</strong></td>
							<td colspan="6">

								<?php
								switch ($pengayakan->status_spv) {
									case 1:
										echo '<span class="text-success font-weight-bold">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Verified
                                              </span>';
										break;

									case 2:
										echo '<span class="text-danger font-weight-bold">
                                                <i class="fas fa-exclamation-circle mr-1"></i>
                                                Revision
                                              </span>';
										break;

									default:
										echo '<span class="text-secondary font-weight-bold">
                                                <i class="fas fa-clock mr-1"></i>
                                                Created
                                              </span>';
										break;
								}
								?>

							</td>
						</tr>

						<tr>
							<td><strong>Catatan Supervisor</strong></td>
							<td colspan="6">
								<?= !empty($pengayakan->catatan_spv)
									? $pengayakan->catatan_spv
									: 'Tidak ada'; ?>
							</td>
						</tr>

					</tbody>
				</table>

			</div>

		</div>
	</div>

</div>


<!-- CSS -->
<style>
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 12px;
		padding: 6px 10px;
		border-radius: 20px;
		vertical-align: middle;
		margin-left: 8px;
		letter-spacing: 0.3px;
	}

	.breadcrumb {
		background-color: #2E86C1;
		padding: 10px 16px;
		border-radius: 0.35rem;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb .breadcrumb-item a:hover {
		text-decoration: underline;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	.table {
		font-size: 15px;
		background-color: #fff;
	}

	.table th,
	.table td {
		padding: 10px 14px;
		vertical-align: middle;
		word-break: break-word;
		white-space: normal;
	}

	.table-title {
		background-color: #f8f9fa;
		font-size: 17px;
		font-weight: bold;
	}

	.badge-audit {
		background-color: #5a3298;
		color: #fff;
		font-size: 11px;
		padding: 5px 9px;
		border-radius: 12px;
	}

	.table-audit {
		background-color: #5a3298 !important;
		color: #fff;
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

		.badge-mode-audit {
			display: block;
			width: fit-content;
			margin: 8px auto 0;
		}

	}
</style>
