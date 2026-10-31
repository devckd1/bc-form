<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Peneraan Thermometer</h1>
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
				<a href="<?= base_url('thermometer/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('thermometer/audit'); ?>"
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
							<th>Hasil Pemeriksaan</th>
							<th>Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($thermometer as $val) {
							$datetime = new DateTime($val->date);
							$datetime = $datetime->format('d-m-Y');
							$result = json_decode($val->peneraan_hasil, true);
						?>
							<tr>
								<td class="text-center"><?= $no++; ?></td>
								<td><?= $datetime; ?></td>
								<td>
									<table class="table table-sm table-bordered mb-0">
										<thead class="text-center" style="background-color:#skyblue; color:darkblue;">
											<tr>
												<th width="15%">Kode Thermometer</th>
												<th width="15%">Model</th>
												<th width="15%">Area</th>
												<th width="10%">Pukul</th>
												<th width="15%">Standar Suhu (°C)</th>
												<th width="10%">Hasil</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($result) && is_array($result)): ?>
												<?php foreach ($result as $row): ?>
													<tr>
														<td><?= htmlspecialchars($row['kode_thermo'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($row['model'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($row['area'] ?? '-'); ?></td>
														<td class="text-center"><?= htmlspecialchars($row['pukul'] ?? '-'); ?></td>
														<td class="text-center"><?= htmlspecialchars($row['standar'] ?? '-'); ?>°C</td>
														<td class="text-center"><?= htmlspecialchars($row['hasil'] ?? '-'); ?></td>
													</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="6" class="text-center">Tidak ada data</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</td>
								<td class="text-center">
									<?php
									$tipe_user = (int) $this->session->userdata('tipe_user');

									if ($val->status_spv == '1') {
										echo '<span style="color:#28a745;font-weight:bold;font-size:12px;">Verified</span>';
									} elseif ($val->status_spv == '2') {
										echo '<span style="color:red;font-weight:bold;font-size:12px;">Revision</span>';
									} elseif ($val->status_produksi == '1' && $val->status_spv == '0') {
										echo '<span style="color:#28a745;font-weight:bold;font-size:12px;">Approved</span>';
									} elseif ($tipe_user == 3 && $val->status_spv == '0') {
										echo '<span style="color:#99a3a4;font-weight:bold;font-size:12px;">Created</span>';
									} elseif ($val->status_produksi == '0') {
										echo '<span style="color:#f39c12;font-weight:bold;font-size:12px;">Menunggu Verifikasi Produksi</span>';
									}
									?>
								</td>
								<td class="text-center">
									<div class="action-buttons">

										<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
											<a href="<?= base_url('thermometer/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-sm mb-1">
												Edit
											</a>
										<?php endif; ?>

										<a href="<?= base_url('thermometer/detail/' . $val->uuid); ?>"
											class="btn btn-success btn-sm mb-1">
											Detail
										</a>

										<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
											<a href="<?= base_url('thermometer/verifikasi_produksi/' . $val->uuid); ?>"
												class="btn btn-primary btn-sm mb-1"
												onclick="return confirm('Apakah pemeriksaan ini yakin disetujui?');">
												Setujui
											</a>
										<?php endif; ?>

										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

											<?php if (
												$val->status_produksi == '1' &&
												$val->status_spv == '0'
											): ?>
												<a href="<?= base_url('thermometer/status/' . $val->uuid); ?>"
													class="btn btn-info btn-sm mb-1">
													Verifikasi
												</a>

											<?php elseif (
												$val->status_produksi == '1' &&
												$val->status_spv == '2'
											): ?>
												<a href="<?= base_url('thermometer/status/' . $val->uuid); ?>"
													class="btn btn-warning btn-sm mb-1">
													Verifikasi Ulang
												</a>
											<?php endif; ?>

										<?php endif; ?>

									</div>
									<!--  <a href="<?= base_url('thermometer/delete/' . $val->uuid); ?>" 
                             class="btn btn-danger btn-sm"
                             onclick="return confirm('Yakin ingin menghapus data ini?')">
                             Delete
                         </a> -->
								</td>
							</tr>
						<?php } ?>
					</tbody>

				</table>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<div class="form-group">
					<form action="<?= base_url('thermometer/cetak') ?>" method="post" class="form-inline">
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
</style>
