<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Metal Detector</h1>
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
				<a href="<?= base_url('metal/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('metal/audit'); ?>"
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
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>Fe</th>
							<th>Non Fe</th>
							<th>SUS 304</th>
							<th>Supervisor</th>
							<th style="text-align:center;">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($metal as $val) {
							$datetime = new datetime($val->date_metal);
							$datetime = $datetime->format('d-m-Y');

							$timing = new DateTime($val->time);
							$timing = $timing->format('H:i');

						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $timing; ?></td>
								<td><?= $val->nama_produk; ?></td>
								<td><?= $val->kode_produksi; ?></td>
								<td class="text-center">
									<?= (
										$val->fe_d === 'terdeteksi' &&
										$val->nonfe_d === 'terdeteksi' &&
										$val->sus_d === 'terdeteksi'
									) ? '✅' : '❌' ?>
								</td>
								<td class="text-center">
									<?= (
										$val->fe_t === 'terdeteksi' &&
										$val->nonfe_t === 'terdeteksi' &&
										$val->sus_t === 'terdeteksi'
									) ? '✅' : '❌' ?>
								</td>
								<td class="text-center">
									<?= (
										$val->fe_b === 'terdeteksi' &&
										$val->nonfe_b === 'terdeteksi' &&
										$val->sus_b === 'terdeteksi'
									) ? '✅' : '❌' ?>
								</td>

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
									<a href="<?= base_url('metal/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
										<span class="text">Edit</span>
									</a>
									<a href="<?= base_url('metal/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split">
										<span class="text">Detail</span>
									</a>
									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('metal/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
											<span class="text">Verifikasi</span>
										</a>
									<?php endif; ?>
									<!--  <a href="<?= base_url('metal/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
				</form>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<div class="mb-3">
					<form action="<?= base_url('metal/cetak') ?>" method="post" class="form-inline">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" class="form-control mr-2" required>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script>
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	tooltipTriggerList.forEach(function(tooltipTriggerEl) {
		new bootstrap.Tooltip(tooltipTriggerEl)
	})
</script>

<style>
	th {
		background-color: #f8f9fc;
	}
</style>