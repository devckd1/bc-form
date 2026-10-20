<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-3 text-gray-800 font-weight-bold text-center">
		Detail Audit Peneraan Thermometer

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a
					class="text-white"
					href="<?= base_url('thermometer/audit'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Peneraan Thermometer
				</a>
			</li>
		</ol>
	</nav>

	<!-- Informasi Audit -->
	<div class="alert alert-warning shadow-sm">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Data Audit:</strong>
		Data pada halaman ini merupakan snapshot Audit dan
		<strong>tidak mengubah data Peneraan Thermometer utama.</strong>
	</div>

	<div class="card shadow mb-4 border-left-audit">

		<div class="card-body">

			<div class="table-responsive">

				<table class="table table-bordered" cellspacing="0">

					<?php
					$datetime = new DateTime($thermometer->date);
					$formattedDate = $datetime->format('d-m-Y');

					$result = json_decode(
						$thermometer->peneraan_hasil,
						true
					);

					if (!is_array($result)) {
						$result = [];
					}
					?>

					<thead class="text-center">

						<tr>
							<th
								colspan="7"
								class="font-weight-bold">
								AUDIT PENERAAN THERMOMETER
							</th>
						</tr>

					</thead>

					<tbody>

						<!-- Tanggal & Shift -->
						<tr>

							<td colspan="2">
								<b>Tanggal:</b>
								<?= $formattedDate; ?>
							</td>

							<td colspan="5">
								<b>Shift:</b>
								<?= htmlspecialchars(
									$thermometer->shift ?? '-'
								); ?>
							</td>

						</tr>

						<!-- Daftar Hasil -->
						<tr class="bg-light text-center">

							<td
								colspan="7"
								class="font-weight-bold">
								Daftar Hasil Pemeriksaan Audit
							</td>

						</tr>

						<!-- Header -->
						<tr class="table-primary text-center">

							<th>No</th>
							<th>Kode Thermometer</th>
							<th>Model</th>
							<th>Area</th>
							<th>Waktu</th>
							<th>Standar Suhu (°C)</th>
							<th>Hasil</th>

						</tr>

						<?php if (!empty($result)): ?>

							<?php $no = 1; ?>

							<?php foreach ($result as $row): ?>

								<tr class="text-center">

									<td>
										<?= $no++; ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$row['kode_thermo'] ?? '-'
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$row['model'] ?? '-'
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$row['area'] ?? '-'
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$row['pukul'] ?? '-'
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$row['standar'] ?? '-'
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$row['hasil'] ?? '-'
										); ?>
									</td>

								</tr>

							<?php endforeach; ?>

						<?php else: ?>

							<tr>

								<td
									colspan="7"
									class="text-center text-muted">
									Tidak ada data pemeriksaan.
								</td>

							</tr>

						<?php endif; ?>

						<!-- Tindakan -->
						<tr>

							<td>
								<b>Tindakan Perbaikan</b>
							</td>

							<td colspan="6">

								<?php if (
									!empty($thermometer->tindakan_perbaikan)
								): ?>

									<?= nl2br(
										htmlspecialchars(
											$thermometer->tindakan_perbaikan
										)
									); ?>

								<?php else: ?>

									Tidak ada

								<?php endif; ?>

							</td>

						</tr>

						<!-- Keterangan -->
						<tr>

							<td>
								<b>Keterangan</b>
							</td>

							<td colspan="6">

								<?php if (
									!empty($thermometer->keterangan)
								): ?>

									<?= nl2br(
										htmlspecialchars(
											$thermometer->keterangan
										)
									); ?>

								<?php else: ?>

									Tidak ada

								<?php endif; ?>

							</td>

						</tr>

					</tbody>

				</table>

			</div>

			<!-- Button -->
			<div class="mt-3">

				<a
					href="<?= base_url('thermometer/audit'); ?>"
					class="btn btn-danger mr-2">
					<i class="fas fa-arrow-left mr-1"></i>
					Kembali
				</a>

				<a
					href="<?= base_url('thermometer/edit-audit/' . $thermometer->uuid); ?>"
					class="btn btn-warning">
					<i class="fas fa-edit mr-1"></i>
					Edit Audit
				</a>

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
		margin-left: 10px;
		padding: 6px 12px;
		background-color: #5a3298;
		color: #fff;
		border-radius: 20px;
		font-size: 13px;
		font-weight: 600;
		vertical-align: middle;
	}

	.border-left-audit {
		border-left: 5px solid #5a3298 !important;
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

		.badge-mode-audit {
			display: block;
			width: fit-content;
			margin: 10px auto 0;
		}

	}
</style>
