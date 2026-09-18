<?php
$data['page_title'] = 'Akses Plant';
$data['active_nav'] = 'akses_plant';

$this->load->view('partials/head', $data);
?>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">

<!-- Begin Page Content -->
<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800"></h1>

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Akses Plant User</h1>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div>
		<br>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-exclamation-circle"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif; ?>


	<!-- Pilih User -->
	<div class="card shadow mb-4">

		<div class="form-group" id="cpi">
			<div class="card-body">

				<form method="GET" action="<?= base_url('akses-plant'); ?>">

					<div class="row">

						<div class="col-md-8">

							<label for="user">
								<strong>Pilih User</strong>
							</label>

							<select name="user"
								id="user"
								class="form-control">

								<option value=""></option>

								<?php foreach ($users as $user): ?>

									<option value="<?= $user->uuid; ?>"
										<?= ($selected_user == $user->uuid) ? 'selected' : ''; ?>>

										<?= htmlspecialchars($user->nama); ?>
										- <?= htmlspecialchars($user->username); ?>

									</option>

								<?php endforeach; ?>

							</select>

						</div>

					</div>

				</form>

			</div>
		</div>

	</div>


	<?php if ($selected_user_data): ?>

		<!-- Informasi User -->
		<div class="card shadow mb-4">

			<div class="form-group" id="cpi">
				<div class="card-body">

					<div class="row">

						<div class="col-md-4">
							<strong>Nama User</strong>
							<p class="text-gray-800 mb-0">
								<?= htmlspecialchars($selected_user_data->nama); ?>
							</p>
						</div>

						<div class="col-md-4">
							<strong>Username</strong>
							<p class="text-gray-800 mb-0">
								<?= htmlspecialchars($selected_user_data->username); ?>
							</p>
						</div>

						<div class="col-md-4">
							<strong>Plant Utama</strong>
							<p class="text-gray-800 mb-0">
								<i class="fas fa-building text-primary"></i>
								<?= htmlspecialchars($selected_user_data->nama_plant); ?>
							</p>
						</div>

					</div>

				</div>
			</div>

		</div>


		<!-- Akses Plant -->
		<div class="card shadow mb-4">

			<div class="form-group" id="cpi">

				<div class="card-body">

					<div class="d-sm-flex align-items-center justify-content-between mb-3">

						<h5 class="text-primary font-weight-bold mb-0">
							Akses Plant
						</h5>

						<span class="badge badge-primary">
							<?= count($plants); ?> Plant
						</span>

					</div>

					<form method="POST"
						action="<?= base_url('akses-plant/save'); ?>">

						<input type="hidden"
							name="user_uuid"
							value="<?= $selected_user_data->uuid; ?>">

						<div class="table-responsive">

							<table class="table table-bordered"
								width="100%"
								cellspacing="0">

								<thead>
									<tr>
										<th width="10%">No</th>
										<th>Plant</th>
										<th width="20%" class="text-center">
											Akses
										</th>
									</tr>
								</thead>

								<tbody>

									<?php
									$no = 1;

									foreach ($plants as $plant):

										// Plant utama tidak dimasukkan
										// sebagai akses tambahan
										if ($plant->uuid == $selected_user_data->plant) {
											continue;
										}

										$checked = in_array(
											$plant->uuid,
											$user_access
										);
									?>

										<tr>

											<td>
												<?= $no; ?>
											</td>

											<td>
												<i class="fas fa-building text-primary mr-2"></i>
												<?= htmlspecialchars($plant->plant); ?>
											</td>

											<td class="text-center">

												<div class="custom-control custom-checkbox">

													<input type="checkbox"
														class="custom-control-input plant-checkbox"
														id="plant_<?= $plant->uuid; ?>"
														name="plant_uuid[]"
														value="<?= $plant->uuid; ?>"
														<?= $checked ? 'checked' : ''; ?>>

													<label class="custom-control-label"
														for="plant_<?= $plant->uuid; ?>">
														Akses
													</label>

												</div>

											</td>

										</tr>

									<?php
										$no++;
									endforeach;
									?>

								</tbody>

							</table>

						</div>

						<div class="text-right mt-3">

							<button type="submit"
								class="btn btn-primary shadow-sm">

								<i class="fas fa-save fa-sm text-white-50"></i>
								Simpan

							</button>

						</div>

					</form>

				</div>

			</div>

		</div>

	<?php else: ?>

		<!-- Belum pilih user -->
		<div class="card shadow mb-4">

			<div class="form-group" id="cpi">

				<div class="card-body text-center">

					<i class="fas fa-users fa-3x text-gray-300 mb-3"></i>

					<h5 class="text-gray-600">
						Belum Ada User Dipilih
					</h5>

					<p class="text-muted mb-0">
						Silakan pilih user terlebih dahulu
						untuk mengatur akses plant.
					</p>

				</div>

			</div>

		</div>

	<?php endif; ?>

</div>
<!-- /.container-fluid -->


<?php $this->load->view('partials/footer'); ?>


<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
	$(document).ready(function() {

		$('#user').select2({
			placeholder: '-- Cari Nama atau Username --',
			allowClear: true,
			width: '100%'
		});

		$('#user').on('change', function() {

			if ($(this).val()) {
				$(this).closest('form').submit();
			}

		});

	});
</script>
