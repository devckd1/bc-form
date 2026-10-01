<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Proses Produksi
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
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>

		<br>

	<?php endif ?>


	<!-- TABEL DATA AUDIT -->
	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('produksi/verifikasi'); ?>"
					class="btn btn-secondary btn-sm">
					<i class="fas fa-arrow-left mr-1"></i>
					Kembali
				</a>
			</div>
			<div class="table-responsive">

				<table
					class="table table-bordered"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>

						<tr>
							<th class="text-center">No</th>
							<th>Date</th>
							<th>Shift</th>
							<th>Types of Product</th>
							<th>Production Code</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th class="text-center">Action</th>
						</tr>

					</thead>

					<tbody>

						<?php $no = 1; ?>

						<?php foreach ($produksi as $val): ?>

							<?php
							$tanggal = (new DateTime($val->date))->format('d-m-Y');
							?>

							<tr>

								<td class="text-center">
									<?= $no++ ?>
								</td>

								<td>
									<?= $tanggal ?>
								</td>

								<td>
									<?= $val->shift ?>
								</td>

								<td>
									<?= $val->nama_produk ?>
								</td>

								<td>
									<?= $val->kode_produksi ?>
								</td>

								<td>
									<?= date(
										'H:i - d m Y',
										strtotime($val->modified_at)
									) ?>
								</td>

								<td>
									<?= date(
										'H:i - d m Y',
										strtotime($val->tgl_update)
									) ?>
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

									<a
										href="<?= base_url('produksi/status/' . $val->uuid); ?>"
										class="btn btn-info btn-icon-split btn-sm">
										<span class="text">
											Verifikasi
										</span>
									</a>
									<a href="<?= base_url('produksi/detail/' . $val->uuid); ?>" class="btn btn-success btn-sm shadow" title="Detail">Detail</a>
									<a href="<?= base_url('produksi/edit/' . $val->uuid); ?>" class="btn btn-warning btn-sm shadow" title="Edit">Update</a>


								</td>

							</tr>

						<?php endforeach ?>

					</tbody>

				</table>

			</div>

			<!-- Export Excel & Cetak PDF -->

				<div class="card-body">
					<div class="form-row">

						<div class="form-group col-md-2">
							<label for="tanggal">
								<strong>Pilih Tanggal:</strong>
							</label>

							<input
								type="date"
								id="tanggal"
								class="form-control"
								required>
						</div>

						<!-- Tombol Cetak PDF -->
						<div class="form-group align-self-end col-md-2">

							<form
								action="<?= base_url('produksi/cetak-audit') ?>"
								method="post"
								onsubmit="return setTanggalPdf();">

								<input
									type="hidden"
									name="tanggal"
									id="tanggal_pdf_hidden">

								<button
									type="submit"
									class="btn btn-danger btn-block">
									<i class="fas fa-file-pdf"></i>
									Cetak PDF
								</button>

							</form>

						</div>

					</div>
				</div>


			<script>
				function setTanggalPdf() {
					const tanggal = document.getElementById('tanggal').value;

					if (!tanggal) {
						Swal.fire({
							icon: 'warning',
							title: 'Tanggal Belum Dipilih',
							text: 'Silakan pilih tanggal terlebih dahulu.'
						});

						return false;
					}

					document.getElementById('tanggal_pdf_hidden').value = tanggal;

					return true;
				}
			</script>

		</div>

	</div>

</div>


<style>
	th {
		background-color: #f8f9fc;
	}
</style>
