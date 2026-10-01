<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<div>
			<h1 class="h3 mb-0 text-gray-800">
				Audit Loading
			</h1>

			<p class="mb-0 text-muted">
				Data Loading yang telah dikirim ke Audit.
			</p>
		</div>

		<a href="<?= base_url('loading/verifikasi'); ?>"
			class="btn btn-secondary btn-sm">
			<i class="fas fa-arrow-left mr-1"></i>
			Kembali
		</a>

	</div>


	<?php if ($this->session->flashdata('success_msg')): ?>

		<div class="alert alert-success">
			<?= $this->session->flashdata('success_msg'); ?>
		</div>

	<?php endif; ?>


	<?php if ($this->session->flashdata('error_msg')): ?>

		<div class="alert alert-danger">
			<?= $this->session->flashdata('error_msg'); ?>
		</div>

	<?php endif; ?>


	<div class="card shadow mb-4">

		<div class="card-header py-3">

			<h6 class="m-0 font-weight-bold text-primary">
				Data Audit Loading
			</h6>

		</div>


		<div class="card-body">

			<div class="table-responsive">

				<form action="<?= base_url('loading/cetak') ?>"
					method="post"
					id="form_cetak_pdf">

					<table class="table table-bordered table-hover"
						id="dataTable"
						width="100%"
						cellspacing="0">

						<thead>

							<tr>

								<th width="30px" class="text-center">
									<i class="fas fa-print fa-lg"></i>
								</th>

								<th>No</th>

								<th>Tanggal</th>

								<th>Shift</th>

								<th>No. Polisi</th>

								<th>Driver</th>

								<th>Ekspedisi</th>

								<th>Tujuan</th>

								<th>Start</th>

								<th>Finish</th>

								<th>No. Segel</th>

								<th>Status SPV</th>

								<th>Status WH</th>

								<th>Action</th>

							</tr>

						</thead>


						<tbody>

							<?php if (!empty($data)): ?>

								<?php $no = 1; ?>

								<?php foreach ($data as $val): ?>

									<tr>

										<td class="text-center">

											<input
												type="checkbox"
												name="checkbox[]"
												value="<?= $val->uuid ?>"
												class="select_row">

										</td>


										<td>
											<?= $no++; ?>
										</td>


										<td>

											<?= !empty($val->date)
												? date('d-m-Y', strtotime($val->date))
												: '-'; ?>

										</td>


										<td>

											<?= htmlspecialchars(
												$val->shift ?? '-'
											); ?>

										</td>


										<td>

											<?= htmlspecialchars(
												$val->no_pol ?? '-'
											); ?>

										</td>


										<td>

											<?= htmlspecialchars(
												$val->nama_supir ?? '-'
											); ?>

										</td>


										<td>

											<?= htmlspecialchars(
												$val->ekspedisi ?? '-'
											); ?>

										</td>


										<td>

											<?= htmlspecialchars(
												$val->tujuan ?? '-'
											); ?>

										</td>


										<td>

											<?= htmlspecialchars(
												$val->start_loading ?? '-'
											); ?>

										</td>


										<td>

											<?= htmlspecialchars(
												$val->finish_loading ?? '-'
											); ?>

										</td>


										<td>

											<?= htmlspecialchars(
												$val->no_segel ?? '-'
											); ?>

										</td>


										<td class="text-center">

											<?php if ($val->status_spv == 1): ?>

												<span class="badge badge-success">
													Verified
												</span>

											<?php elseif ($val->status_spv == 2): ?>

												<span class="badge badge-danger">
													Revision
												</span>

											<?php else: ?>

												<span class="badge badge-secondary">
													Created
												</span>

											<?php endif; ?>

										</td>


										<td class="text-center">

											<?php if ($val->status_wh == 1): ?>

												<span class="badge badge-success">
													Diketahui
												</span>

											<?php elseif ($val->status_wh == 2): ?>

												<span class="badge badge-danger">
													Revision
												</span>

											<?php else: ?>

												<span class="badge badge-secondary">
													Created
												</span>

											<?php endif; ?>

										</td>


										<td class="text-center">

											<a href="<?= base_url(
															'loading/status/' . $val->uuid
														); ?>"
												class="btn btn-info btn-sm">

												Verifikasi

											</a>
											<a href="<?= base_url('loading/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
												<span class="text">Detail</span>
											</a>
											<a href="<?= base_url('loading/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
												<span class="text">Update</span>
											</a>


										</td>

									</tr>

								<?php endforeach; ?>

							<?php endif; ?>

						</tbody>

					</table>


					<input
						type="hidden"
						name="selected_items"
						id="selected_items">

				</form>

			</div>


			<br>

			<hr>


			<div class="form-group">

				<label>
					Pilih Data yang akan dicetak:
				</label>

				<br>

				<button
					type="submit"
					form="form_cetak_pdf"
					class="btn btn-success">

					<i class="fas fa-print fa-sm text-white-50"></i>
					Cetak PDF

				</button>

			</div>

		</div>

	</div>

</div>


<style>
	#dataTable th {
		background-color: #f8f9fc;
	}
</style>


<script>
	$(document).ready(function() {

		$('#dataTable').DataTable({
			ordering: false,
			searching: true,
			pageLength: 10
		});


		$('#form_cetak_pdf').on('submit', function(event) {

			var selectedItems = [];


			$('.select_row:checked').each(function() {

				selectedItems.push($(this).val());

			});


			if (selectedItems.length === 0) {

				event.preventDefault();

				alert("Silakan pilih data yang ingin dicetak.");

				return false;

			}


			$('#selected_items').val(
				selectedItems.join(',')
			);

		});

	});
</script>
