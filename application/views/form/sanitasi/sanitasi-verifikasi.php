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
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif ?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th class="text-center" width="20px">No</th>
							<th>Tanggal</th>
							<th>Shift</th>
							<th>Waktu</th>
							<th class="text-center">Hasil Pemeriksaan</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($sanitasi as $val):
							$tanggal = (new DateTime($val->date))->format('d-m-Y');
							$waktu = (new DateTime($val->waktu))->format('H:i');
							$result = json_decode($val->area, true);

						?>
							<tr>
								<td class="text-center"><?= $no++; ?></td>
								<td><?= $tanggal; ?></td>
								<td><?= $val->shift; ?></td>
								<td><?= $waktu; ?></td>
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

								<td><?= date('H:i - d m Y', strtotime($val->modified_at)); ?></td>
								<td><?= date('H:i - d m Y', strtotime($val->tgl_update_spv)); ?></td>

								<td class="text-center">
									<?php
									switch ($val->status_spv) {
										case 0:
											echo '<span style="color:#99a3a4;font-weight:bold;">Created</span>';
											break;
										case 1:
											echo '<span style="color:#28b463;font-weight:bold;">Verified</span>';
											break;
										case 2:
											echo '<span style="color:red;font-weight:bold;">Revision</span>';
											break;
									}
									?>
								</td>

								<td class="text-center">
									<a href="<?= base_url('sanitasi/status/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
										<span class="text">Verifikasi</span>
									</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>

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

					<div class="col-auto">
						<button type="submit"
							formaction="<?= base_url('form/pemeriksaan_gabungan/cetak') ?>"
							class="btn btn-primary">
							<i class="fas fa-file-pdf fa-sm"></i>
							Cetak Gabungan
						</button>
					</div>

				</div>

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
