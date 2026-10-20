<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Suhu Ruang</h1>
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
				<a href="<?= base_url('suhu/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('suhu/audit'); ?>"
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
							<th>Tanggal/ Shift</th>
							<th>Pukul</th><!-- 
                            <th>Lokasi</th>
                            <th>Suhu / RH</th> -->
							<!-- <th>Produksi</th> -->
							<th>Supervisor</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($suhu as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime . " / " . $val->shift; ?></td>
								<td><?= date('H:i', strtotime($val->pukul)); ?></td><!-- 
                                <td><?= $val->lokasi; ?></td>
                                <td><?= $val->suhu . " / " . $val->rh; ?></td> -->
								<!-- <td class="text-center">
                                    <?php
									if ($val->status_produksi == 0) {
										echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
									} elseif ($val->status_produksi == 1) {
										echo '<span style="color: #28b463; font-weight: bold;">Checked</span>';
									} elseif ($val->status_produksi == 2) {
										echo '<span style="color: red; font-weight: bold;">Re-Check</span>';
									}
									?>
                                </td> -->
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
									<a href="<?= base_url('suhu/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split mb-1">
										<span class="text">Edit</span>
									</a>
									<a href="<?= base_url('suhu/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split mb-1">
										<span class="text">Detail</span>
									</a>
									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('suhu/status/' . $val->uuid); ?>"
											class="btn btn-info btn-icon-split">
											<span class="text">Verifikasi</span>
										</a>
									<?php endif; ?>
									<!--  <a href="<?= base_url('suhu/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
				<hr>
				<?php
				$plant_uuid = $this->session->userdata('plant');
				?>

				<form action="<?= base_url('suhu/cetak') ?>" method="post" target="_blank" class="form-inline mb-3">
					<div class="form-group mr-3">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" id="tanggal" class="form-control" required>
					</div>

					<!-- PDF (SEMUA PLANT BOLEH) -->
					<button type="submit" class="btn btn-success mr-2">
						<i class="fas fa-print"></i> Cetak PDF
					</button>

					<!-- EXCEL CIKANDE -->
					<?php if ($plant_uuid === '651ac623-5e48-44cc-b2f6-5d622603f53c'): ?>
						<button type="submit"
							formaction="<?= base_url('suhu/export-excel') ?>"
							class="btn btn-primary mr-2">
							<i class="fas fa-file-excel"></i> Export Excel
						</button>
					<?php endif; ?>

					<!-- EXCEL SALATIGA -->
					<?php if ($plant_uuid === '1eb341e0-1ec4-4484-ba8f-32d23352b84d'): ?>
						<button type="submit"
							formaction="<?= base_url('suhu/export-excel-salatiga') ?>"
							class="btn btn-primary">
							<i class="fas fa-file-excel"></i> Export Excel
						</button>
					<?php endif; ?>
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