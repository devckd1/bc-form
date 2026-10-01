<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Pembuatan Larutan Cleaning dan Sanitasi
		</h1>



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

			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('error_msg') ?>

		</div>

		<br>

	<?php endif ?>

	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('larutan/verifikasi'); ?>"
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
							<th>No</th>
							<th>Tanggal / Shift</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>Last SPV</th>
							<th>Action</th>
						</tr>

					</thead>

					<tbody>

						<?php
						$no = 1;

						foreach ($larutan as $val):

							$datetime = new DateTime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>

							<tr>

								<td class="text-center">
									<?= $no; ?>
								</td>

								<td>
									<?= $datetime . " / " . $val->shift; ?>
								</td>

								<td>
									<?= date(
										'H:i - d m Y',
										strtotime($val->modified_at)
									); ?>
								</td>

								<td>
									<?= date(
										'H:i - d m Y',
										strtotime($val->tgl_update_spv)
									); ?>
								</td>

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

								<td class="text-center">

									<a href="<?= base_url('larutan/status/' . $val->uuid); ?>"
										class="btn btn-info btn-icon-split btn-sm">

										<span class="text">
											Verifikasi
										</span>

									</a>
									<a href="<?= base_url('larutan/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
										<span class="text">Detail</span>
									</a>
									<a href="<?= base_url('larutan/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
										<span class="text">Edit</span>
									</a>


								</td>

							</tr>

						<?php
							$no++;
						endforeach;
						?>

					</tbody>

				</table>

			</div>

			<div class="form-group">
				<form action="<?= base_url('larutan/cetak-audit') ?>" method="post" class="form-inline">
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

</div>

<style>
	th {
		background-color: #f8f9fc;
	}
</style>

<script>
	$(document).ready(function() {

		$('#dataTable').DataTable({
			"ordering": false,
			"searching": true
		});

	});
</script>
