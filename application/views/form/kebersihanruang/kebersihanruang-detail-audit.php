<div class="container-fluid">

	<!-- Heading -->
	<h1 class="h3 mb-3 text-gray-800 font-weight-bold">
		Detail Audit Kebersihan Ruang Produksi

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-audit">
			<li class="breadcrumb-item">
				<a
					class="text-white"
					href="<?= base_url('kebersihanruang/audit'); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					Daftar Audit Kebersihan Ruang Produksi \ Detail
				</a>
			</li>
		</ol>
	</nav>

	<!-- Informasi Audit -->
	<div class="alert alert-audit mb-4">
		<i class="fas fa-info-circle mr-2"></i>
		Data yang ditampilkan merupakan <strong>snapshot Audit</strong>
		dari data Kebersihan Ruang Produksi saat pemeriksaan dibuat.
		Perubahan pada data Audit tidak mengubah data pemeriksaan utama.
	</div>

	<!-- Card -->
	<div class="card shadow mb-4">

		<div class="card-header card-header-audit">
			<h6 class="m-0 font-weight-bold text-white">
				<i class="fas fa-clipboard-check mr-2"></i>
				DETAIL AUDIT KEBERSIHAN RUANG PRODUKSI
			</h6>
		</div>

		<div class="card-body">

			<div class="table-responsive">

				<?php
				$tanggal = (new DateTime(
					$kebersihanruang->date
				))->format('d-m-Y');

				$details = json_decode(
					$kebersihanruang->detail,
					true
				);

				$kondisiMap = [
					'0' => 'Bersih',
					'1' => 'Berdebu',
					'2' => 'Basah',
					'3' => 'Pecah/Retak',
					'4' => 'Sisa produksi (terigu/produk)',
					'5' => 'Noda (tinta, karat)',
					'6' => 'Pertumbuhan mikroorganisme (jamur/bau busuk)',
				];
				?>

				<table class="table table-bordered">

					<thead class="text-center">
						<tr>
							<th colspan="6">
								KEBERSIHAN RUANG PRODUKSI
							</th>
						</tr>
					</thead>

					<tbody>

						<!-- Informasi -->
						<tr>
							<td colspan="2">
								<strong>Tanggal</strong>
							</td>
							<td colspan="4">
								<?= htmlspecialchars($tanggal); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<strong>Shift</strong>
							</td>
							<td colspan="4">
								<?= htmlspecialchars($kebersihanruang->shift); ?>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<strong>Lokasi</strong>
							</td>
							<td colspan="4">
								<?= htmlspecialchars($kebersihanruang->lokasi); ?>
							</td>
						</tr>

						<!-- Detail -->
						<tr class="">
							<th colspan="6">
								DETAIL PEMERIKSAAN AUDIT
							</th>
						</tr>

						<tr class="text-center table-header-audit">
							<th>No</th>
							<th colspan="2">Bagian</th>
							<th>Kondisi</th>
							<th>Problem</th>
							<th>Tindakan</th>
						</tr>

						<?php if (!empty($details) && is_array($details)) : ?>

							<?php foreach ($details as $i => $row) : ?>

								<?php
								$kondisi = $row['kondisi'] ?? '';

								if ($kondisi === '') {
									$kondisi_text = '-';
								} else {
									$kondisi_text =
										$kondisiMap[$kondisi]
										?? $kondisi;
								}
								?>

								<tr>

									<td class="text-center">
										<?= $i + 1; ?>
									</td>

									<td colspan="2">
										<?= htmlspecialchars(
											$row['bagian'] ?? ''
										); ?>
									</td>

									<td class="text-center">
										<?php if ($kondisi === '') : ?>

											-

										<?php elseif ($kondisi === '0') : ?>

											<span class="text-success font-weight-bold">
												Bersih
											</span>

										<?php else : ?>

											<span class="text-danger font-weight-bold">
												<?= htmlspecialchars(
													$kondisi_text
												); ?>
											</span>

										<?php endif; ?>
									</td>

									<!-- Problem -->
									<td>
										<?= !empty($row['problem'])
											? htmlspecialchars($row['problem'])
											: '-'; ?>
									</td>

									<!-- Tindakan -->
									<td>
										<?= !empty($row['tindakan'])
											? htmlspecialchars($row['tindakan'])
											: '-'; ?>
									</td>

								</tr>

							<?php endforeach; ?>

						<?php else : ?>

							<tr>
								<td colspan="6" class="text-center text-muted">
									Tidak ada data detail
								</td>
							</tr>

						<?php endif; ?>


						<!-- Verifikasi -->
						<tr class="">
							<th colspan="6">
								VERIFIKASI
							</th>
						</tr>

						<tr>
							<td colspan="2">
								<strong>QC</strong>
							</td>

							<td colspan="4">
								<?= !empty($kebersihanruang->username)
									? htmlspecialchars(
										$kebersihanruang->username
									)
									: '-'; ?>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<strong>Produksi</strong>
							</td>

							<td colspan="4">
								<?= !empty($kebersihanruang->nama_produksi)
									? htmlspecialchars(
										$kebersihanruang->nama_produksi
									)
									: '-'; ?>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<strong>Disetujui Supervisor</strong>
							</td>

							<td colspan="4">
								<?php
								$status = $kebersihanruang->status_spv ?? 0;

								if ($status == 1) {
									echo '<span class="text-success font-weight-bold">Verified</span>';
								} elseif ($status == 2) {
									echo '<span class="text-danger font-weight-bold">Revision</span>';
								} else {
									echo '<span class="text-secondary font-weight-bold">Created</span>';
								}
								?>
							</td>
						</tr>

						<tr>
							<td colspan="2">
								<strong>Catatan Supervisor</strong>
							</td>

							<td colspan="4">
								<?= !empty($kebersihanruang->catatan_spv)
									? htmlspecialchars(
										$kebersihanruang->catatan_spv
									)
									: 'Tidak ada'; ?>
							</td>
						</tr>

					</tbody>

				</table>

			</div>

			<!-- Foto Audit -->
			<?php if (
				!empty($kebersihanruang->foto1) ||
				!empty($kebersihanruang->foto2)
			) : ?>

				<hr>

				<h5 class="font-weight-bold mt-4 mb-3">
					<i class="fas fa-camera mr-2"></i>
					Foto Bukti Audit
				</h5>

				<div class="row">

					<?php if (!empty($kebersihanruang->foto1)) : ?>

						<div class="col-md-6 mb-3">
							<div class="card shadow-sm">
								<div class="card-header text-center font-weight-bold">
									Foto 1
								</div>

								<div class="card-body text-center">
									<img
										src="<?= base_url(
													'uploads/kebersihan/' .
														$kebersihanruang->foto1
												); ?>"
										class="img-fluid rounded"
										style="max-height:400px;"
										alt="Foto Kebersihan Ruang 1">
								</div>
							</div>
						</div>

					<?php endif; ?>


					<?php if (!empty($kebersihanruang->foto2)) : ?>

						<div class="col-md-6 mb-3">
							<div class="card shadow-sm">
								<div class="card-header text-center font-weight-bold">
									Foto 2
								</div>

								<div class="card-body text-center">
									<img
										src="<?= base_url(
													'uploads/kebersihan/' .
														$kebersihanruang->foto2
												); ?>"
										class="img-fluid rounded"
										style="max-height:400px;"
										alt="Foto Kebersihan Ruang 2">
								</div>
							</div>
						</div>

					<?php endif; ?>

				</div>

			<?php endif; ?>

		</div>
	</div>

</div>


<style>
	.breadcrumb-audit {
		background-color: #2E86C1;
		padding: 8px 16px;
		border-radius: 0.25rem;
	}

	.breadcrumb-audit .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.breadcrumb-audit .breadcrumb-item a:hover {
		text-decoration: underline;
	}

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		font-size: 13px;
		padding: 6px 10px;
		border-radius: 5px;
		vertical-align: middle;
		margin-left: 8px;
	}

	.alert-audit {
		background-color: #f0e9f8;
		border-left: 5px solid #5a3298;
		color: #43266f;
	}

	.card-header-audit {
		background-color: #5a3298;
		border-color: #5a3298;
	}

	.section-audit {
		background-color: #f0e9f8;
		color: #2E86C1;
		text-align: center;
	}

	.table-header-audit {
		background-color: #2E86C1;
		color: #fff;
	}

	.btn-audit {
		background-color: #fff;
		border-color: #2E86C1;
		color: #fff;
	}

	.btn-audit:hover {
		background-color: #fff;
		border-color: #2E86C1;
		color: #fff;
	}

	.table {
		font-size: 16px;
		border-collapse: collapse;
	}

	.table th,
	.table td {
		padding: 8px 10px;
		word-wrap: break-word;
		white-space: normal !important;
		vertical-align: middle;
	}

	.table th:first-child,
	.table td:first-child {
		width: 50px;
		max-width: 50px;
		text-align: center;
	}

	@media (max-width: 768px) {
		.table {
			font-size: 14px;
		}

		.badge-mode-audit {
			display: block;
			width: fit-content;
			margin-left: 0;
			margin-top: 8px;
		}
	}
</style>
