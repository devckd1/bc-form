<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Peneraan Thermometer
		</h1>



	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>

		<div class="alert alert-success text-center">

			<i class="fas fa-check"></i>

			<?= $this->session->flashdata('success_msg') ?>

		</div>

	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>

		<div class="alert alert-danger text-center">

			<i class="fas fa-exclamation-triangle"></i>

			<?= $this->session->flashdata('error_msg') ?>

		</div>

	<?php endif; ?>

	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('thermometer/verifikasi'); ?>"
					class="btn btn-secondary btn-sm">

					<i class="fas fa-arrow-left mr-1"></i>
					Kembali

				</a>
			</div>
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
								Tanggal
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

						foreach ($thermometer as $val):

							$tanggal = !empty($val->date)
								? (new DateTime($val->date))->format('d-m-Y')
								: '-';

							$result = json_decode(
								$val->peneraan_hasil,
								true
							);

						?>

							<tr>

								<td class="text-center">
									<?= $no++; ?>
								</td>

								<td>
									<?= $tanggal; ?>
								</td>

								<td>

									<table class="table table-sm table-bordered mb-0">

										<thead class="text-center">

											<tr>

												<th width="15%">
													Kode Thermometer
												</th>

												<th width="15%">
													Model
												</th>

												<th width="15%">
													Area
												</th>

												<th width="10%">
													Pukul
												</th>

												<th width="15%">
													Standar Suhu (°C)
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

														<td class="text-center">
															<?= htmlspecialchars(
																$row['pukul'] ?? '-'
															); ?>
														</td>

														<td class="text-center">
															<?= htmlspecialchars(
																$row['standar'] ?? '-'
															); ?>°C
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

													<td colspan="6"
														class="text-center">

														Tidak ada data

													</td>

												</tr>

											<?php endif; ?>

										</tbody>

									</table>

								</td>

								<td>
									<?= !empty($val->modified_at)
										? date(
											'H:i - d m Y',
											strtotime($val->modified_at)
										)
										: '-'; ?>
								</td>

								<td>
									<?= !empty($val->tgl_update_spv)
										? date(
											'H:i - d m Y',
											strtotime($val->tgl_update_spv)
										)
										: '-'; ?>
								</td>

								<td class="text-center">

									<?php if ($val->status_spv == 0): ?>

										<span style="color:#99a3a4;font-weight:bold;">
											Created
										</span>

									<?php elseif ($val->status_spv == 1): ?>

										<span style="color:#28b463;font-weight:bold;">
											Verified
										</span>

									<?php elseif ($val->status_spv == 2): ?>

										<span style="color:red;font-weight:bold;">
											Revision
										</span>

									<?php endif; ?>

								</td>

								<td class="text-center">

									<a href="<?= base_url('thermometer/status/' . $val->uuid); ?>"
										class="btn btn-info btn-icon-split btn-sm">

										<span class="text">
											Verifikasi
										</span>

									</a>
									<a href="<?= base_url('thermometer/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
										<span class="text">Detail</span>
									</a>
									<a href="<?= base_url('thermometer/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
										<span class="text">Update</span>
									</a>

								</td>

							</tr>

						<?php endforeach; ?>

					</tbody>

				</table>

			</div>

			<div class="form-group">
				<form action="<?= base_url('thermometer/cetak-audit') ?>" method="post" class="form-inline">
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
</style>
