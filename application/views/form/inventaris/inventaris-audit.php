<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Data Audit Inventaris
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

			<i class="fas fa-exclamation-circle mr-1"></i>

			<?= $this->session->flashdata('error_msg'); ?>

		</div>

	<?php endif; ?>


	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('inventaris/verifikasi'); ?>"
					class="btn btn-secondary btn-sm">

					<i class="fas fa-arrow-left mr-1"></i>
					Kembali

				</a>
			</div>
			<div class="table-responsive">

				<form action="<?= base_url('inventaris/cetak') ?>"
					method="post"
					id="form_cetak_pdf">

					<table class="table table-bordered"
						id="dataTable"
						width="100%"
						cellspacing="0">

						<thead>

							<tr>

								<th width="30px" class="text-center">
									<i class="fas fa-print fa-lg"></i>
								</th>

								<th width="30px" class="text-center">
									No
								</th>

								<th>
									Tanggal
								</th>

								<th>
									Shift
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

							if (!empty($inventaris)):

								foreach ($inventaris as $val):

									$tanggal = '-';

									if (!empty($val->date)) {

										$tanggal = date(
											'd-m-Y',
											strtotime($val->date)
										);
									}

							?>

									<tr>

										<td class="text-center">

											<input
												type="checkbox"
												name="checkbox[]"
												value="<?= $val->uuid ?>"
												class="select_row">

										</td>


										<td class="text-center">

											<?= $no; ?>

										</td>


										<td>

											<?= $tanggal; ?>

										</td>


										<td>

											<?= !empty($val->shift)
												? htmlspecialchars($val->shift)
												: '-'; ?>

										</td>


										<td>

											<?= !empty($val->modified_at)

												? date(
													'H:i - d m Y',
													strtotime($val->modified_at)
												)

												: (

													!empty($val->created_at)

													? date(
														'H:i - d m Y',
														strtotime($val->created_at)
													)

													: '-'

												); ?>

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

											<?php

											if ($val->status_spv == 0) {

												echo '<span style="color:#99a3a4;font-weight:bold;">
														Created
													</span>';
											} elseif ($val->status_spv == 1) {

												echo '<span style="color:#28b463;font-weight:bold;">
														Verified
													</span>';
											} elseif ($val->status_spv == 2) {

												echo '<span style="color:red;font-weight:bold;">
														Revision
													</span>';
											} else {

												echo '<span style="color:#99a3a4;font-weight:bold;">
														-
													</span>';
											}

											?>

										</td>


										<td class="text-center">

											<a href="<?= base_url(
															'inventaris/status/' . $val->uuid
														); ?>"
												class="btn btn-info btn-icon-split btn-sm">

												<span class="text">
													Verifikasi
												</span>

											</a>
											<a href="<?= base_url('inventaris/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
												<span class="text">Detail</span>
											</a>
											<a href="<?= base_url('inventaris/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
												<span class="text">Edit</span>
											</a>


										</td>

									</tr>

							<?php

									$no++;

								endforeach;

							endif;

							?>

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


<style>
	th {
		background-color: #f8f9fc;
	}
</style>
