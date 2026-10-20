<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Suhu Chiller</h1>
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
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif ?>
	<?php
	$tipe_user = $this->session->userdata('tipe_user');
	?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('chiller/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('chiller/audit'); ?>"
						class="btn shadow-sm"
						style="background-color: #5a3298; color: #fff;">

						<i class="fas fa-clipboard-check mr-1"></i>
						Mode Audit

					</a>
				<?php endif; ?>
			</div>
			<hr>
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
							<th>Supervisor</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($chiller as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->waktu; ?></td>
								<td><?= $val->chiller_1; ?></td>
								<td><?= $val->chiller_2; ?></td>
								<td><?= $val->chiller_3; ?></td>
								<td><?= $val->chiller_4; ?></td>
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
									<a href="<?= base_url('chiller/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split mb-1">
										<span class="text">Edit</span>
									</a>
									<a href="<?= base_url('chiller/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split mb-1">
										<span class="text">Detail</span>
									</a>
									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('chiller/status/' . $val->uuid) ?>" class="btn btn-info btn-icon-split">
											<span class="text">Verifikasi</span>
										</a>
									<?php endif; ?>
									<!-- <a href="<?= base_url('chiller/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <span class="text">Delete</span>
                                    </a> -->
								</td>
							</tr>
						<?php
							$no++;
						}
						?>
					</tbody>
				</table>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
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
							<i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
						</button>
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>

<style>
	th {
		background-color: #f8f9fc;
	}
</style>