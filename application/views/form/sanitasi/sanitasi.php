<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Sanitasi</h1>
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
				<a href="<?= base_url('sanitasi/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('sanitasi/audit'); ?>"
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
							<th>Shift</th>
							<th>Waktu</th>
							<th class="text-center">Hasil Pemeriksaan</th>
							<th>Supervisor</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($sanitasi as $val) {
							$datetime = (new DateTime($val->date))->format('d-m-Y');
							$timing = (new DateTime($val->waktu))->format('H:i');

							$result = json_decode($val->area, true);
						?>
							<tr>
								<td class="text-center"><?= $no++; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->shift; ?></td>
								<td><?= $timing; ?></td>

								<td>
									<table class="table table-sm table-bordered mb-0">
										<thead style="background-color:#2E86C1; color:black; text-align:center;">
											<tr>
												<th width="30%">Area</th>
												<th width="20%">Aktual</th>
												<th width="30%">Gambar</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($result) && is_array($result)): ?>
												<?php foreach ($result as $row): ?>
													<tr>
														<td><?= htmlspecialchars($row['sub_area'] ?? '-'); ?></td>
														<td style="text-align:center;"><?= htmlspecialchars($row['aktual'] ?? '-'); ?></td>
														<td style="text-align:center;">
															<?php if (!empty($row['gambar'])): ?>
																<a href="<?= base_url('uploads/sanitasi/' . $row['gambar']); ?>" target="_blank">Lihat Gambar</a>
															<?php else: ?>
																<span class="text-muted">Tidak ada</span>
															<?php endif ?>
														</td>
													</tr>
												<?php endforeach ?>
											<?php else: ?>
												<tr>
													<td colspan="3" class="text-center">Tidak ada data</td>
												</tr>
											<?php endif ?>
										</tbody>
									</table>
								</td>
								<!-- Status SPV -->
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

								<!-- Aksi -->
								<td class="text-center">
									<a href="<?= base_url('sanitasi/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split mb-1">
										<span class="text">Edit</span>
									</a>
									<a href="<?= base_url('sanitasi/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split mb-1">
										<span class="text">Detail</span>
									</a>
									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('sanitasi/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
											<span class="text">Verifikasi</span>
										</a>
									<?php endif; ?>
									<!-- <a href="<?= base_url('sanitasi/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <span class="text">Delete</span>
                                </a>  -->
								</td>
							</tr>
						<?php } ?>
					</tbody>

				</table>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<form action="<?= base_url('sanitasi/cetak') ?>" method="post" target="_blank" class="mb-3">

					<div class="form-row align-items-end">

						<div class="col-auto">
							<label for="date" class="font-weight-bold">
								Pilih Tanggal
							</label>
							<input type="date"
								name="date"
								id="date"
								class="form-control"
								required>
						</div>

						<div class="col-auto">
							<button type="submit" class="btn btn-success">
								<i class="fas fa-print fa-sm"></i>
								Cetak PDF
							</button>
						</div>

						<?php if ($this->session->userdata('plant') != '651ac623-5e48-44cc-b2f6-5d622603f53c') : ?>
							<div class="col-auto">
								<button type="submit"
									formaction="<?= base_url('form/pemeriksaan_gabungan/cetak') ?>"
									class="btn btn-primary">
									<i class="fas fa-file-pdf fa-sm"></i>
									Cetak Gabungan
								</button>
							</div>
						<?php endif; ?>

					</div>

				</form>
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