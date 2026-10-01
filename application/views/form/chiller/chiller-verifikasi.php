<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Suhu Chiller</h1>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div><br>
	<?php endif ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div><br>
	<?php endif ?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('chiller/audit'); ?>"
					class="btn btn-info btn-sm">

					<i class="fas fa-clipboard-check mr-1"></i>
					Data Audit

				</a>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Pukul</th>
							<th>Chiller 1</th>
							<th>Chiller 2</th>
							<th>Chiller 3</th>
							<th>Chiller 4</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($chiller as $val):
							$tanggal = (new DateTime($val->date))->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td><?= $tanggal ?></td>
								<td><?= $val->waktu ?></td>
								<td><?= $val->chiller_1 ?></td>
								<td><?= $val->chiller_2 ?></td>
								<td><?= $val->chiller_3 ?></td>
								<td><?= $val->chiller_4 ?></td>
								<td><?= date('H:i - d m Y', strtotime($val->modified_at)) ?></td>
								<td><?= date('H:i - d m Y', strtotime($val->tgl_update_spv)) ?></td>
								<td class="text-center">
									<?php
									if ($val->status_spv == 0) {
										echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
									} elseif ($val->status_spv == 1) {
										echo '<span style="color: #28b463; font-weight: bold;">Verified</span>';
									} elseif ($val->status_spv == 2) {
										echo '<span style="color: red; font-weight: bold;">Revision</span>';
									}
									?>
								</td>
								<td class="text-center">

									<a href="<?= base_url('chiller/status/' . $val->uuid); ?>"
										class="btn btn-warning btn-icon-split btn-sm">

										<span class="text">
											Verifikasi
										</span>

									</a>

									<?php if ((int)$val->is_audit === 0): ?>

										<a href="<?= base_url('chiller/copy-to-audit/' . $val->uuid); ?>"
											class="btn btn-info btn-icon-split btn-sm"
											onclick="return confirm('Kirim data ini ke Audit?')">

											<span class="text">												
												Kirim Audit
											</span>

										</a>
									<?php endif ?>

								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>

			<br>
			<hr>
			<div class="form-group">
				<form action="" method="post" class="form-inline">
					<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
					<input type="date" name="tanggal" class="form-control mr-2" required>

					<button type="submit" formaction="<?= base_url('chiller/cetak') ?>" class="btn btn-success mr-2">
						<i class="fas fa-file-pdf fa-sm text-white-50"></i> Cetak PDF
					</button>

					<button type="submit" formaction="<?= base_url('chiller/export-excel') ?>" class="btn btn-primary">
						<i class="fas fa-file-excel fa-sm text-white-50"></i> Cetak Excel
					</button>
				</form>
			</div>

		</div>
	</div>
</div>
</div>

<style>
	th {
		background-color: #f8f9fc;
	}
</style>
