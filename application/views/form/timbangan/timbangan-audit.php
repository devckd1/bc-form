<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Pemeriksaan Timbangan
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white"
					href="<?= base_url('timbangan'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Suhu Ruang
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Thermometer.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div>
		<br>
	<?php endif ?>


	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif ?>


	<div class="card shadow mb-4 card-audit">
		<div class="card-body">
			<div class="table-responsive">

				<table class="table table-bordered"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>
						<tr>
							<th class="text-center" width="20px">
								No
							</th>

							<th>
								Tanggal / Shift
							</th>

							<th>
								Hasil Pemeriksaan
							</th>

							<th>
								Last Updated
							</th>

							<th>
								Last Verified
							</th>

							<th>
								SPV
							</th>

							<th class="text-center">
								Action
							</th>
						</tr>
					</thead>


					<tbody>

						<?php
						$no = 1;

						foreach ($timbangan as $val) {

							$tanggal = (new DateTime($val->date))
								->format('d-m-Y');

							$result = json_decode(
								$val->peneraan_hasil,
								true
							);
						?>

							<tr>

								<!-- NO -->
								<td class="text-center">
									<?= $no++; ?>
								</td>


								<!-- TANGGAL / SHIFT -->
								<td>
									<?= $tanggal . " / " . $val->shift; ?>
								</td>


								<!-- HASIL PEMERIKSAAN -->
								<td>

									<table class="table table-sm table-bordered mb-0">

										<thead
											class="text-center"
											style="background-color:skyblue; color:darkblue;">

											<tr>

												<th width="15%">
													Kode Timbangan
												</th>

												<th width="15%">
													Kapasitas
												</th>

												<th width="15%">
													Model
												</th>

												<th width="15%">
													Lokasi
												</th>

												<th width="10%">
													Pukul
												</th>

												<th width="15%">
													Peneraan Standar
												</th>

												<th width="10%">
													Hasil
												</th>

											</tr>

										</thead>


										<tbody>

											<?php if (!empty($result) && is_array($result)): ?>

												<?php foreach ($result as $row): ?>

													<tr>

														<td>
															<?= htmlspecialchars(
																$row['kode_timbangan'] ?? '-'
															); ?>
														</td>

														<td class="text-center">
															<?= htmlspecialchars(
																$row['kapasitas'] ?? '-'
															); ?>
														</td>

														<td class="text-center">
															<?= htmlspecialchars(
																$row['model'] ?? '-'
															); ?>
														</td>

														<td class="text-center">
															<?= htmlspecialchars(
																$row['lokasi'] ?? '-'
															); ?>
														</td>

														<td class="text-center">
															<?= htmlspecialchars(
																$row['pukul'] ?? '-'
															); ?>
														</td>

														<td class="text-center">
															<?= htmlspecialchars(
																$row['peneraan_standar'] ?? '-'
															); ?>
														</td>

														<td class="text-center">
															<?= htmlspecialchars(
																$row['hasil'] ?? '-'
															); ?>
														</td>

													</tr>

												<?php endforeach; ?>

											<?php else: ?>

												<tr>

													<td colspan="7"
														class="text-center">

														Tidak ada data

													</td>

												</tr>

											<?php endif; ?>

										</tbody>

									</table>

								</td>


								<!-- LAST UPDATED -->
								<td>

									<?php if (!empty($val->modified_at)): ?>

										<?= date(
											'H:i - d m Y',
											strtotime($val->modified_at)
										); ?>

									<?php else: ?>

										-

									<?php endif; ?>

								</td>


								<!-- LAST VERIFIED -->
								<td>

									<?php if (!empty($val->tgl_update_spv)): ?>

										<?= date(
											'H:i - d m Y',
											strtotime($val->tgl_update_spv)
										); ?>

									<?php else: ?>

										-

									<?php endif; ?>

								</td>


								<!-- STATUS SPV -->
								<td class="text-center">

									<?php

									if ($val->status_spv == 0) {

										echo '<span style="color: #99a3a4; font-weight: bold;">
												Created
											</span>';
									} elseif ($val->status_spv == 1) {

										echo '<span style="color: #28b463; font-weight: bold;">
												Verified
											</span>';
									} elseif ($val->status_spv == 2) {

										echo '<span style="color: red; font-weight: bold;">
												Revision
											</span>';
									}

									?>

								</td>


								<!-- ACTION -->
								<td class="text-center">

									<a href="<?= base_url('timbangan/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
										<span class="text">Detail</span>
									</a>
									<a href="<?= base_url('timbangan/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
										<span class="text">Edit</span>
									</a>


								</td>

							</tr>

						<?php } ?>

					</tbody>

				</table>

			</div>

			<div class="form-group">
				<form action="<?= base_url('timbangan/cetak-audit') ?>" method="post" class="form-inline">
					<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
					<input type="date" name="tanggal" class="form-control mr-2" required>

					<label for="shift" class="mr-2 font-weight-bold">Shift:</label>
					<select name="shift" class="form-control mr-2" required>
						<option value="">-- Pilih Shift --</option>
						<option value="1">Shift 1</option>
						<option value="2">Shift 2</option>
						<option value="3">Shift 3</option>
					</select>

					<button type="submit" class="btn btn-success">
						<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
					</button>
				</form>
			</div>

		</div>
	</div>

</div>


<style>
	th {
		background-color: #f8f9fc;
	}

	.breadcrumb {
		background-color: #2E86C1;
	}

	th {
		background-color: #f8f9fc;
	}

	.breadcrumb a {
		color: #fff !important;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	/* Label Mode Audit */
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 6px 12px;
		border-radius: 6px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
		box-shadow: 0 2px 5px rgba(90, 50, 152, 0.25);
	}
</style>
