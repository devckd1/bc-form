<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Kebersihan Peralatan
		</h1>


	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check mr-1"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div>
		<br>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-exclamation-circle mr-1"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif; ?>

	<div class="card shadow mb-4">

		<div class="card-header py-3">
			<div class="form-group text-right">
				<a href="<?= base_url('kebersihanperalatan/verifikasi'); ?>"
					class="btn btn-secondary btn-sm">
					<i class="fas fa-arrow-left mr-1"></i>
					Kembali
				</a>
			</div>
		</div>

		<div class="card-body">

			<div class="table-responsive">

				<table class="table table-bordered" id="dataTable"
					width="100%" cellspacing="0">

					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!empty($kebersihanperalatan)): ?>

							<?php
							$no = 1;
							foreach ($kebersihanperalatan as $val):

								$tanggal = '-';

								if (!empty($val->date)) {
									$datetime = new DateTime($val->date);
									$tanggal = $datetime->format('d-m-Y');
								}
							?>

								<tr>

									<td class="text-center">
										<?= $no; ?>
									</td>

									<td>
										<?= $tanggal . ' / ' . $val->shift; ?>
									</td>

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

										<a href="<?= base_url('kebersihanperalatan/status/' . $val->uuid); ?>"
											class="btn btn-info btn-icon-split btn-sm">

											<span class="text">
												Verifikasi
											</span>

										</a>
										<a href="<?= base_url('kebersihanperalatan/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('kebersihanperalatan/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
											<span class="text">Update</span>
										</a>

									</td>

								</tr>

							<?php
								$no++;
							endforeach;
							?>

						<?php else: ?>

							<tr>
								<td colspan="6" class="text-center py-4">
									<i class="fas fa-clipboard-check fa-2x text-gray-400 mb-2"></i>
									<br>
									<span class="text-muted">
										Belum ada data Kebersihan Peralatan yang dikirim ke Audit.
									</span>
								</td>
							</tr>

						<?php endif; ?>

					</tbody>

				</table>

			</div>

			<br>
			<hr>

			<div class="form-group">

				<form action="<?= base_url('kebersihanperalatan/cetak-audit') ?>"
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
</style>

<script>
	$(document).ready(function() {

		<?php if (!empty($kebersihanperalatan)): ?>

			$('#dataTable').DataTable({
				"ordering": false,
				"searching": true
			});

		<?php endif; ?>

	});
</script>
