<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Kebersihan Peralatan

			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>


	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white"
					href="<?= base_url('kebersihanperalatan'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Kebersihan Peralatan
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Kebersihan Peralatan.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
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

	<div class="card shadow mb-4 card-audit">

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
										<a href="<?= base_url('kebersihanperalatan/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('kebersihanperalatan/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
											<span class="text">Edit</span>
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

		<?php if (!empty($kebersihanperalatan)): ?>

			$('#dataTable').DataTable({
				"ordering": false,
				"searching": true
			});

		<?php endif; ?>

	});
</script>
