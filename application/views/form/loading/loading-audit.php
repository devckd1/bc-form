<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<div>
			<h1 class="h3 mb-0 text-gray-800">
				Audit Loading
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			</h1>
		</div>

	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white"
					href="<?= base_url('loading'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Loading
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Pemeriksaan Loading.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
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
											<a href="<?= base_url('loading/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
												<span class="text">Detail</span>
											</a>
											<a href="<?= base_url('loading/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
												<span class="text">Edit</span>
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
