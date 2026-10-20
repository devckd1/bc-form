<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Data Audit Pemusnahan Barang / Produk
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
				<a href="<?= base_url('pemusnahan'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemusnahan Barang
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

				<table
					class="table table-bordered"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>

						<tr>

							<th width="20px" class="text-center">
								No
							</th>

							<th>Tanggal</th>

							<th>Nama Produk</th>

							<th>Kode Produksi</th>

							<th>Best Before</th>

							<th>Analisa Masalah</th>

							<th>Last Updated</th>

							<th>Last Verified</th>

							<th class="text-center">
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

						foreach ($pemusnahan as $val) {

							$datetime = new datetime($val->date);
							$tanggal = $datetime->format('d-m-Y');

							$bb = new datetime($val->best_before);
							$best_before = $bb->format('d-m-Y');
						?>

							<tr>

								<td class="text-center">
									<?= $no++; ?>
								</td>

								<td>
									<?= $tanggal; ?>
								</td>

								<td>
									<?= $val->nama_produk; ?>
								</td>

								<td>
									<?= $val->kode_produksi; ?>
								</td>

								<td>
									<?= $best_before; ?>
								</td>

								<td>
									<?= $val->analisa; ?>
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

									<a href="<?= base_url('pemusnahan/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
										<span class="text">Detail</span>
									</a>
									<a href="<?= base_url('pemusnahan/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
										<span class="text">Edit</span>
									</a>


								</td>

							</tr>

						<?php } ?>

					</tbody>

				</table>

			</div>

			<div class="mb-3">
				<form action="<?= base_url('pemusnahan/cetak-audit') ?>" method="post" class="form-inline">
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
