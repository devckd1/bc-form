<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Data Audit Proses Produksi
		</h1>

		<a href="<?= base_url('proses/verifikasi'); ?>"
			class="btn btn-secondary btn-sm">

			<i class="fas fa-arrow-left mr-1"></i>
			Kembali

		</a>

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

			<div class="table-responsive">

				<table class="table table-bordered"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>

						<tr>

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
								Nama Produk
							</th>

							<th>
								Catatan
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

						if (!empty($proses)):

							foreach ($proses as $val):

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
										<?= $no; ?>
									</td>

									<td>
										<?= $tanggal; ?>
									</td>

									<td>
										<?= !empty($val->shift)
											? $val->shift
											: '-'; ?>
									</td>

									<td>
										<?= !empty($val->nama_produk)
											? $val->nama_produk
											: '-'; ?>
									</td>

									<td>
										<?= !empty($val->catatan)
											? $val->catatan
											: '-'; ?>
									</td>

									<td>
										<?= !empty($val->modified_at)
											? date(
												'H:i - d m Y',
												strtotime($val->modified_at)
											)
											: '-'; ?>
									</td>

									<td>
										<?= !empty($val->tgl_update)
											? date(
												'H:i - d m Y',
												strtotime($val->tgl_update)
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

										<a href="<?= base_url('proses/status/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split btn-sm">

											<span class="text">
												Verifikasi
											</span>

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

			</div>

		</div>

	</div>

</div>


<style>
	th {
		background-color: #f8f9fc;
	}
</style>
