<div class="container-fluid">
	<!-- Page Heading -->
	<?php if (empty($is_popup)): ?>
		<h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">Detail Pemeriksaan Suhu Chiller</h1>

		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				<li class="breadcrumb-item">
					<a class="text-white" href="<?= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] . '?search=' . urlencode($this->input->get('search') ?? '') : base_url('chiller'); ?>">
						<i class="fas fa-arrow-left"></i> Daftar Pemeriksaan Suhu Chiller
					</a>
				</li>
			</ol>
		</nav>
	<?php endif; ?>
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" cellspacing="0">
					<?php
					$datetime = new DateTime($chiller->date);
					$formattedDate = $datetime->format('d-m-Y');
					?>
					<thead class="text-center">
						<tr>
							<th colspan="7" class="text-center font-weight-bold">PEMERIKSAAN SUHU CHILLER</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="2"><b>Tanggal:</b> <?= $formattedDate; ?></td>
							<td><b>Pukul:</b> <?= date('H:i', strtotime($chiller->waktu)); ?></td>
							<td colspan="4"></td>
						</tr>
						<tr class="bg-light text-center">
							<td colspan="7" class="font-weight-bold">Hasil Pemeriksaan</td>
						</tr>
						<tr class="table-primary text-center">
							<th colspan="3">Chiller</th>
							<th colspan="4">Suhu (°C)</th>
						</tr>
						<tr class="text-center">
							<td colspan="3"><b>Chiller No. 1</b></td>
							<td colspan="4"><?= $chiller->chiller_1; ?> °C</td>
						</tr>
						<tr class="text-center">
							<td colspan="3"><b>Chiller No. 2</b></td>
							<td colspan="4"><?= $chiller->chiller_2; ?> °C</td>
						</tr>
						<tr class="text-center">
							<td colspan="3"><b>Chiller No. 3</b></td>
							<td colspan="4"><?= $chiller->chiller_3; ?> °C</td>
						</tr>
						<tr class="text-center">
							<td colspan="3"><b>Chiller No. 4</b></td>
							<td colspan="4"><?= $chiller->chiller_4; ?> °C</td>
						</tr>
						<tr class="bg-light">
							<td><b>Catatan</b></td>
							<td colspan="6"><?= !empty($chiller->catatan) ? $chiller->catatan : 'Tidak ada'; ?></td>
						</tr>
						<tr class="table-primary text-center">
							<th colspan="7">VERIFIKASI</th>
						</tr>
						<tr>
							<td><b>QC</b></td>
							<td colspan="6"><?= $chiller->username; ?></td>
						</tr>
						<tr>
							<td><b>Produksi</b></td>
							<td colspan="6"><?= !empty($chiller->nama_produksi) ? $chiller->nama_produksi : 'Belum dikoreksi'; ?></td>
						</tr>
						<tr>
							<td><b>Status Supervisor</b></td>
							<td colspan="6">
								<?php
								switch ($chiller->status_spv) {
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
							<td colspan="6"><?= !empty($chiller->catatan_spv) ? $chiller->catatan_spv : 'Tidak ada'; ?></td>
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
