<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Magnet Trap</h1>
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

	<div class="card shadow mb-4">
		<div class="card-body">
			<?php if ($this->session->userdata('tipe_user') != 4): ?>
				<div class="form-group text-right">
					<a href="<?= base_url('magnettrap/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
						<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
					</a>
				</div>
			<?php endif; ?>
			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>Tahapan</th>
							<th>Jenis Kontaminasi</th>
							<th>Bukti</th>
							<th>Keterangan</th>
							<th>Supervisor</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($magnettrap as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime . " / " . $val->shift; ?></td>
								<td><?= $val->nama_produk; ?></td>
								<td><?= $val->kode_produksi; ?></td>
								<td><?= $val->tahapan; ?></td>
								<td><?= $val->kontaminasi; ?></td>
								<td>
									<?php
									$bukti = $val->bukti ?? null;

									$path1 = FCPATH . 'uploads/' . $bukti;
									$path2 = FCPATH . 'uploads/magnettrap/' . $bukti;

									if (!empty($bukti)) {
										if (file_exists($path1)) {
											$img_url = base_url('uploads/' . $bukti);
										} elseif (file_exists($path2)) {
											$img_url = base_url('uploads/magnettrap/' . $bukti);
										} else {
											$img_url = null;
										}
									} else {
										$img_url = null;
									}
									?>

									<?php if ($img_url): ?>
										<img src="<?= $img_url; ?>" alt="Bukti Temuan" style="max-width: 150px; max-height: 100px;">
									<?php else: ?>
										<p>No image available</p>
									<?php endif; ?>
								</td>
								<td><?= $val->keterangan; ?></td>
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

									<?php if ($this->session->userdata('tipe_user') == 4): ?>

										<?php if ($val->status_enginer == '1'): ?>

											<span class="btn btn-success btn-icon-split">
												<span class="icon text-white-50">
													<i class="fas fa-check"></i>
												</span>
												<span class="text">Sudah Diverifikasi</span>
											</span>

										<?php else: ?>

											<a href="<?= base_url('magnettrap/verifikasi_produksi/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split"
												onclick="return confirm('Apakah pemeriksaan ini sudah diverifikasi?');">

												<span class="icon text-white-50">
													<i class="fas fa-check"></i>
												</span>

												<span class="text">Verifikasi</span>
											</a>

										<?php endif; ?>

									<?php else: ?>

										<a href="<?= base_url('magnettrap/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split">
											<span class="text">Update</span>
										</a>

									<?php endif; ?>

									<!-- Detail tetap bisa diakses semua role -->
									<a href="<?= base_url('magnettrap/detail/' . $val->uuid); ?>"
										class="btn btn-success btn-icon-split">
										<span class="text">Detail</span>
									</a>

								</td>
							</tr>
						<?php
							$no++;
						}
						?>
					</tbody>
				</table>
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
