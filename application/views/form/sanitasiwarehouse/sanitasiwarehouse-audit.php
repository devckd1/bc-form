<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Sanitasi Warehouse
		</h1>



	</div>


	<?php if ($this->session->flashdata('success_msg')): ?>

		<div class="alert alert-success text-center">

			<i class="fas fa-check mr-1"></i>

			<?= $this->session->flashdata('success_msg'); ?>

		</div>

	<?php endif; ?>


	<?php if ($this->session->flashdata('error_msg')): ?>

		<div class="alert alert-danger text-center">

			<i class="fas fa-times mr-1"></i>

			<?= $this->session->flashdata('error_msg'); ?>

		</div>

	<?php endif; ?>


	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('sanitasiwarehouse/verifikasi'); ?>"
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
							<th>Tanggal</th>
							<th>Area</th>
							<th>Detail</th>
							<th>Warehouse</th>
							<th>Status SPV</th>
							<th>Action</th>
						</tr>

					</thead>

					<tbody>

						<?php $no = 1; ?>

						<?php foreach ($data as $val): ?>

							<tr>

								<td class="text-center">
									<?= $no; ?>
								</td>

								<td>
									<?= !empty($val->date)
										? date('d-m-Y', strtotime($val->date))
										: '-'; ?>
								</td>

								<td>
									<?= htmlspecialchars(
										$val->area ?? '-'
									); ?>
								</td>

								<td>
									<?php
									$detail = json_decode(
										$val->detail,
										true
									);
									?>

									<?php if (!empty($detail)): ?>

										<?php foreach ($detail as $item): ?>

											<div class="mb-1">
												<strong>
													<?= htmlspecialchars(
														$item['bagian'] ?? '-'
													); ?>
												</strong>

												:
												<?= htmlspecialchars(
													$item['kondisi'] ?? '-'
												); ?>
											</div>

										<?php endforeach; ?>

									<?php else: ?>

										-

									<?php endif; ?>
								</td>

								<td>
									<?= htmlspecialchars(
										$val->nama_wh ?? '-'
									); ?>
								</td>

								<td class="text-center">

									<?php if ((int)$val->status_spv === 0): ?>

										<span style="color:#99a3a4;font-weight:bold;">
											Created
										</span>

									<?php elseif ((int)$val->status_spv === 1): ?>

										<span style="color:#28b463;font-weight:bold;">
											Verified
										</span>

									<?php elseif ((int)$val->status_spv === 2): ?>

										<span style="color:red;font-weight:bold;">
											Revision
										</span>

									<?php else: ?>

										<span style="color:#99a3a4;font-weight:bold;">
											-
										</span>

									<?php endif; ?>

								</td>

								<td class="text-center">

									<a href="<?= base_url(
													'sanitasiwarehouse/status/' . $val->uuid
												); ?>"
										class="btn btn-info btn-icon-split btn-sm">

										<span class="text">
											Verifikasi
										</span>

									</a>
									<a href="<?= base_url('sanitasiwarehouse/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
										<span class="text">Detail</span>
									</a>
									<a href="<?= base_url('sanitasiwarehouse/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
										<span class="text">Update</span>
									</a>

								</td>

							</tr>

							<?php $no++; ?>

						<?php endforeach; ?>

					</tbody>

				</table>

			</div>

			<br>
			<div class="mb-3">
				<form action="<?= base_url('sanitasiwarehouse/cetak-audit') ?>" method="post" class="form-inline">
					<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
					<input type="date" name="tanggal" class="form-control mr-2" required>
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


<script>
	$(document).ready(function() {

		$('#dataTable').DataTable({
			"ordering": false,
			"searching": true
		});

	});
</script>
