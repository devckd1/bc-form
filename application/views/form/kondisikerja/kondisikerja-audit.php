<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Kondisi Kerja Selama Produksi
		</h1>

		<a href="<?= base_url('kondisikerja/verifikasi'); ?>"
			class="btn btn-secondary btn-sm shadow-sm">

			<i class="fas fa-arrow-left mr-1"></i>
			Kembali

		</a>

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


	<div class="card shadow mb-4">

		<div class="card-body">

			<div class="table-responsive">

				<table class="table table-bordered"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>

						<tr>

							<th width="20px" class="text-center">
								No
							</th>

							<th>
								Tanggal / Shift
							</th>

							<th>
								Area
							</th>

							<th>
								Item
							</th>

							<th>
								Kondisi
							</th>

							<th>
								Problem
							</th>

							<th>
								Tindakan Koreksi
							</th>

							<th>
								Verifikasi
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

						if (empty($kondisikerja)) {

							echo '
							<tr>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td>-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">-</td>
								<td class="text-center">No data available</td>
							</tr>';
						} else {

							$no = 1;

							foreach ($kondisikerja as $val) {

								try {

									$datetime = new DateTime($val->date);
									$formatted_date = $datetime->format('d-m-Y');
								} catch (Exception $e) {

									$formatted_date = '-';
								}

						?>

								<!-- BARIS 1 : HIGIENE KARYAWAN -->

								<tr>

									<td class="text-center" rowspan="3">
										<?= $no++; ?>
									</td>

									<td rowspan="3">
										<?= $formatted_date . " / " . $val->shift; ?>
									</td>

									<td rowspan="3">
										<?= $val->area; ?>
									</td>

									<td>
										Higiene Karyawan
									</td>

									<td>
										<?= $val->kondisi_higiene; ?>
									</td>

									<td>
										<?= $val->problem_higiene; ?>
									</td>

									<td>
										<?= $val->tindakan_higiene; ?>
									</td>

									<td>
										<?= $val->verifikasi_higiene; ?>
									</td>

									<td rowspan="3">

										<?= !empty($val->modified_at)
											? date(
												'H:i - d m Y',
												strtotime($val->modified_at)
											)
											: '-';
										?>

									</td>

									<td rowspan="3">

										<?= !empty($val->tgl_update_spv)
											? date(
												'H:i - d m Y',
												strtotime($val->tgl_update_spv)
											)
											: '-';
										?>

									</td>

									<td class="text-center" rowspan="3">

										<?php

										switch ($val->status_spv) {

											case 0:

												echo '<span class="text-muted font-weight-bold">
														Created
													</span>';

												break;

											case 1:

												echo '<span class="text-success font-weight-bold">
														Verified
													</span>';

												break;

											case 2:

												echo '<span class="text-danger font-weight-bold">
														Revision
													</span>';

												break;
										}

										?>

									</td>


									<td class="text-center" rowspan="3">

										<a href="<?= base_url('kondisikerja/status/' . $val->uuid); ?>"
											class="btn btn-info btn-icon-split btn-sm">
											<span class="text">
												Verifikasi
											</span>
										</a>										
										
										<a href="<?= base_url('kondisikerja/detail/' . $val->uuid); ?>"
											class="btn btn-success btn-icon-split btn-sm">
											<span class="text">
												Detail
											</span>
										</a>

										<a href="<?= base_url('kondisikerja/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split btn-sm">
											<span class="text">
												Update
											</span>
										</a>

									</td>

								</tr>


								<!-- BARIS 2 : KEBERSIHAN PERALATAN -->

								<tr>

									<td>
										Kebersihan Peralatan
									</td>

									<td>
										<?= $val->kondisi_peralatan; ?>
									</td>

									<td>
										<?= $val->problem_peralatan; ?>
									</td>

									<td>
										<?= $val->tindakan_peralatan; ?>
									</td>

									<td>
										<?= $val->verifikasi_peralatan; ?>
									</td>

								</tr>


								<!-- BARIS 3 : KEBERSIHAN AREA / RUANG -->

								<tr>

									<td>
										Kebersihan Area/Ruang
									</td>

									<td>
										<?= $val->kondisi_kebersihan; ?>
									</td>

									<td>
										<?= $val->problem_kebersihan; ?>
									</td>

									<td>
										<?= $val->tindakan_kebersihan; ?>
									</td>

									<td>
										<?= $val->verifikasi_kebersihan; ?>
									</td>

								</tr>


						<?php

							}
						}

						?>

					</tbody>

				</table>

			</div>


			<br>

			<hr>


			<!-- TOMBOL CETAK PDF -->

			<div class="form-group">

				<form action="<?= base_url('kondisikerja/cetak-audit') ?>" 
					method="post"
					class="form-inline">

					<label for="tanggal"
						class="mr-2 font-weight-bold">

						Pilih Tanggal:

					</label>


					<input type="date"
						name="tanggal"
						class="form-control mr-2"
						required>


					<label for="shift"
						class="mr-2 font-weight-bold">

						Shift:

					</label>


					<select name="shift"
						class="form-control mr-2"
						required>

						<option value="">
							-- Pilih Shift --
						</option>

						<option value="1">
							Shift 1
						</option>

						<option value="2">
							Shift 2
						</option>

						<option value="3">
							Shift 3
						</option>

					</select>


					<button type="submit"
						class="btn btn-success">

						<i class="fas fa-print fa-sm text-white-50"></i>

						Cetak PDF

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

	table.table {
		border-collapse: collapse !important;
	}

	table.table td,
	table.table th {
		border: 1px solid #dee2e6 !important;
		vertical-align: middle;
		padding: 10px;
	}
</style>


<script>
	$(document).ready(function() {

		// Hilangkan popup/warning DataTables
		$.fn.dataTable.ext.errMode = 'none';

		let dataExists = <?= !empty($kondisikerja) ? 'true' : 'false' ?>;

		if (dataExists) {

			$('#dataTable').DataTable({
				"ordering": false,
				"searching": true
			});

		}

	});
</script>
