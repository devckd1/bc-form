<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Pemeriksaan Suhu Ruang
		</h1>
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
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<div class="form-group text-right">
				<a href="<?= base_url('suhu/verifikasi'); ?>"
					class="btn btn-secondary btn-sm shadow-sm">

					<i class="fas fa-arrow-left mr-1"></i>
					Kembali

				</a>
			</div>

			<hr>

			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Shift</th>
							<th>Pukul</th>
							<th>Nama Produksi</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>

						<?php
						$no = 1;

						if (!empty($suhu)):
							foreach ($suhu as $val):

								$datetime = !empty($val->date)
									? new DateTime($val->date)
									: null;

								$formatted_date = $datetime
									? $datetime->format('d-m-Y')
									: '-';
						?>

								<tr>

									<td class="text-center">
										<?= $no; ?>
									</td>

									<td>
										<?= $formatted_date; ?>
									</td>

									<td>
										<?= !empty($val->shift) ? $val->shift : '-'; ?>
									</td>

									<td>
										<?= !empty($val->pukul) ? $val->pukul : '-'; ?>
									</td>

									<td>
										<?= !empty($val->nama_produksi)
											? $val->nama_produksi
											: '-'; ?>
									</td>

									<td><?= date('H:i - d m Y', strtotime($val->modified_at)); ?></td>
									<td><?= date('H:i - d m Y', strtotime($val->tgl_update_spv)); ?></td>

									<td class="text-center">
										<?php
										if ($val->status_spv == 0) {
											echo '<span style="color:#99a3a4;font-weight:bold;">Created</span>';
										} elseif ($val->status_spv == 1) {
											echo '<span style="color:#28b463;font-weight:bold;">Verified</span>';
										} elseif ($val->status_spv == 2) {
											echo '<span style="color:red;font-weight:bold;">Revision</span>';
										} else {
											echo '<span style="color:#99a3a4;font-weight:bold;">-</span>';
										}
										?>
									</td>

									<td class="text-center">
										<a href="<?= base_url('suhu/status/' . $val->uuid); ?>"
											class="btn btn-info btn-icon-split btn-sm">
											<span class="text">Verifikasi</span>
										</a>

										<a href="<?= base_url('suhu/detail/' . $val->uuid); ?>"
											class="btn btn-success btn-icon-split btn-sm">

											<span class="text">
												Detail
											</span>

										</a>

										<a href="<?= base_url('suhu/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split btn-sm">

											<span class="text">
												Update
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

			<br>

			<div class="mb-3">

				<form action="<?= base_url('suhu/cetak_audit'); ?>"
					method="post"
					class="form-inline">

					<label for="tanggal"
						class="mr-2 font-weight-bold">

						Pilih Tanggal:

					</label>

					<input
						type="date"
						name="tanggal"
						id="tanggal"
						class="form-control mr-2"
						required>

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
</style>
