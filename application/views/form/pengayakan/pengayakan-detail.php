<div class="container-fluid">
	<!-- Page Heading -->
	<?php if (empty($is_popup)): ?>
		<h1 class="h3 mb-3 text-gray-800 text-center font-weight-bold">Detail Pemeriksaan Pengayakan</h1>

		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search') ?? '') : base_url('pengayakan'); ?>">
						<i class="fas fa-arrow-left"></i> Daftar Pemeriksaan Pengayakan
					</a>
				</li>
			</ol>
		</nav>
	<?php endif; ?>

	<div class="card shadow rounded mb-5">
		<div class="card-body">
			<div class="table-responsive">
				<?php $datetime = (new DateTime($pengayakan->date))->format('d-m-Y'); ?>
				<table class="table table-bordered" width="100%" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th colspan="7" class="font-weight-bold table-title">PEMERIKSAAN PENGAYAKAN</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong>Tanggal</strong></td>
							<td colspan="3"><?= $datetime; ?></td>
							<td><strong>Shift</strong></td>
							<td colspan="2"><?= $pengayakan->shift; ?></td>
						</tr>
						<tr>
							<td><strong>Nama Barang</strong></td>
							<td colspan="6"><?= $pengayakan->nama_barang; ?></td>
						</tr>
						<tr>
							<td><strong>Kode Produksi</strong></td>
							<td colspan="6"><?= $pengayakan->kode_produksi; ?></td>
						</tr>
						<tr>
							<td><strong>Expired Date</strong></td>
							<td colspan="6"><?= $pengayakan->expired_date; ?></td>
						</tr>
						<tr>
							<td><strong>Jumlah Barang</strong></td>
							<td colspan="6"><?= $pengayakan->jumlah_barang; ?></td>
						</tr>

						<tr class="bg-light text-center">
							<th><strong>Kontaminasi Benda Asing</strong></th>
							<th colspan="2">Screen Mess</th>
							<th>Kerikil</th>
							<th>Benang</th>
							<th colspan="2"></th>
						</tr>
						<tr class="text-center">
							<td><strong>Jumlah</strong></td>
							<td colspan="2"><?= $pengayakan->kba_screenmess; ?></td>
							<td><?= $pengayakan->kba_kerikil; ?></td>
							<td><?= $pengayakan->kba_benang; ?></td>
							<td colspan="2"></td>
						</tr>

						<tr>
							<td><strong>Kondisi Screen Ayakan</strong></td>
							<td colspan="6"><?= $pengayakan->kondisi; ?></td>
						</tr>
						<tr>
							<td><strong>Catatan</strong></td>
							<td colspan="6"><?= !empty($pengayakan->catatan) ? $pengayakan->catatan : 'Tidak ada'; ?></td>
						</tr>

						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>
						<tr>
							<td><strong>QC</strong></td>
							<td colspan="6"><?= $pengayakan->username; ?></td>
						</tr>
						<tr>
							<td><strong>Produksi</strong></td>
							<td colspan="6"><?= $pengayakan->nama_produksi; ?></td>
						</tr>
						<tr>
							<td><strong>Status Supervisor</strong></td>
							<td colspan="6">
								<?php
								switch ($pengayakan->status_spv) {
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
							<td><strong>Catatan Supervisor</strong></td>
							<td colspan="6"><?= !empty($pengayakan->catatan_spv) ? $pengayakan->catatan_spv : 'Tidak ada'; ?></td>
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
