<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Disposisi Produk dan Prosedur
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>
	</div>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-audit">
			<li class="breadcrumb-item">
				<a href="<?= base_url('disposisi'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Disposisi Produk dan Prosedur
				</a>
			</li>
		</ol>
	</nav>

	<!-- Informasi Audit -->
	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi Audit:</strong>
		Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan untuk keperluan pemeriksaan serta verifikasi.
		Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.
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

				<form
					action="<?= base_url('disposisi/cetak') ?>"
					method="post"
					id="form_cetak_pdf">

					<table
						class="table table-bordered"
						id="dataTable"
						width="100%"
						cellspacing="0">

						<thead>

							<tr>

								<th width="30px" class="text-center">
									<i class="fas fa-print fa-lg"></i>
								</th>

								<th width="20px" class="text-center">
									No
								</th>

								<th>Tanggal</th>
								<th>Nomor</th>
								<th>Kepada</th>
								<th>Disposisi</th>
								<th>Dasar Disposisi</th>
								<th>Last Updated</th>
								<th>Last Verified</th>
								<th>SPV</th>

								<th class="text-center">
									Action
								</th>

							</tr>

						</thead>

						<tbody>

							<?php
							$no = 1;

							foreach ($disposisi as $val):

								$datetime = new DateTime($val->date);
								$datetime = $datetime->format('d-m-Y');
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
										<?= $datetime; ?>
									</td>

									<td>
										<?= $val->nomor; ?>
									</td>

									<td>
										<?= $val->kepada; ?>
									</td>

									<td>
										<?= $val->disposisi; ?>
									</td>

									<td>
										<?= $val->dasar_disposisi; ?>
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
										<a href="<?= base_url('disposisi/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('disposisi/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
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

					<input
						type="hidden"
						name="checkbox[]"
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
	document.getElementById('form_cetak_pdf').addEventListener(
		'submit',
		function(event) {

			var selectedCheckboxes =
				document.querySelectorAll('.select_row:checked');

			var selectedItems = [];

			selectedCheckboxes.forEach(function(checkbox) {

				selectedItems.push(checkbox.value);

			});

			if (selectedItems.length === 0) {

				event.preventDefault();

				alert(
					"Silakan pilih data yang ingin dicetak."
				);

				return;
			}

			document.getElementById('selected_items').value =
				selectedItems.join(',');

		}
	);
</script>


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
