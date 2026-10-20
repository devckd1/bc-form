<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Verifikasi Pembuatan Larutan Cleaning dan Sanitasi</h1>
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

	<?php
	$tipe_user = $this->session->userdata('tipe_user');
	?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('larutan/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('larutan/audit'); ?>"
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
							<!-- <th width="20px" class="text-center" rowspan="2">No</th>
                            <th rowspan="2">Tanggal / Shift</th>
                            <th rowspan="2">Nama Bahan</th>
                            <th rowspan="2">Kadar yang Diinginkan</th>
                            <th colspan="3" style="text-align: center;">Verifikasi Formulasi</th>
                            <th rowspan="2">Produksi</th>
                            <th rowspan="2">Supervisor</th>
                            <th rowspan="2" class="text-center">Action</th> -->
							<th class="text-center align-middle" width="60">No</th>
							<th class="text-center align-middle">Tanggal / Shift</th>
							<th class="text-center align-middle">Supervisor</th>
							<th class="text-center align-middle" width="320">Action</th>
						</tr>
						<!-- <tr>
                            <th>Bahan Kimia (ml)</th>
                            <th>Air Bersih (ml)</th>
                            <th>Volume Akhir (ml)</th>
                        </tr> -->
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($larutan as $val) {
							$datetime = new DateTime($val->date);
							$tanggalFormatted = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center align-middle"><?= $no; ?></td>

								<td class="text-center align-middle">
									<?= $tanggalFormatted . " / Shift " . $val->shift; ?>
								</td>
								<!-- <td><?= $val->nama_bahan; ?></td>
                                <td><?= $val->kadar; ?></td>
                                <td><?= $val->bahan_kimia; ?></td>
                                <td><?= $val->air_bersih; ?></td>
                                <td><?= $val->volume_akhir; ?></td> -->
								<td class="text-center align-middle">
									<?php
									if ($val->status_spv == 0) {
										echo '<span style="color:#99a3a4;font-weight:bold;">Created</span>';
									} elseif ($val->status_spv == 1) {
										echo '<span style="color:#28b463;font-weight:bold;">Verified</span>';
									} elseif ($val->status_spv == 2) {
										echo '<span style="color:red;font-weight:bold;">Revision</span>';
									}
									?>
								</td>

								<td class="text-center align-middle">
									<a href="<?= base_url('larutan/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
										<span class="text">Edit</span>
									</a>

									<a href="<?= base_url('larutan/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
										<span class="text">Detail</span>
									</a>

									<a href="<?= base_url('larutan/delete/' . $val->uuid); ?>"
										class="btn btn-danger btn-icon-split btn-sm"
										onclick="return confirm('Yakin ingin menghapus data ini?')">
										<span class="text">Delete</span>
									</a>

									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('larutan/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split btn-sm">
											<span class="text">Verifikasi</span>
										</a>
									<?php endif; ?>
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
					<form action="<?= base_url('larutan/cetak') ?>" method="post" class="form-inline">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" class="form-control mr-2" required>

						<label for="shift" class="mr-2 font-weight-bold">Shift:</label>
						<select name="shift" class="form-control mr-2" required>
							<option value="">-- Pilih Shift --</option>
							<option value="1">Shift 1</option>
							<option value="2">Shift 2</option>
							<option value="3">Shift 3</option>
						</select>

						<button type="submit" class="btn btn-success">
							<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
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

	#dataTable th,
	#dataTable td {
		text-align: center;
		vertical-align: middle;
	}
</style>
