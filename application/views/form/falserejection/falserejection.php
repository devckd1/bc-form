<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Monitoring False Rejection</h1>
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
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('falserejection/audit'); ?>"
						class="btn shadow-sm"
						style="background-color: #5a3298; color: #fff;">

						<i class="fas fa-clipboard-check mr-1"></i>
						Mode Audit

					</a>
				<?php endif; ?>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal Produksi</th>
							<th>Tanggal Monitoring/Shift</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>Jenis kontaminan</th>
							<th>Supervisor</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($falserejection as $val) {
							$datetime = new datetime($val->date_metal);
							$datetime = $datetime->format('d-m-Y');
							$datetime2 = new datetime($val->date_false_rejection);
							$datetime2 = $datetime2->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $datetime2 . ' / ' . $val->shift_monitoring; ?></td>
								<td><?= $val->nama_produk; ?></td>
								<td><?= $val->kode_produksi; ?></td>
								<td><?= $val->jenis_kontaminasi; ?></td>
								<td class="text-center">
									<?php
									if ($val->status_spv_false == 0) {
										echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
									} elseif ($val->status_spv_false == 1) {
										echo '<span style="color: #28b463; font-weight: bold;">Verified</span>';
									} elseif ($val->status_spv_false == 2) {
										echo '<span style="color: red; font-weight: bold;">Revision</span>';
									}
									?>
								</td>
								<td class="text-center">
									<a href="<?= base_url('falserejection/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split mb-1">
										<span class="text">Edit</span>
									</a>
									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('falserejection/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
											<span class="text">Verifikasi</span>
										</a>
									<?php endif; ?>
									<!--  <a href="<?= base_url('falserejection/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split mb-1">
                                    <span class="text">Detail</span>
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
				<hr>
				<div class="form-group">
					<form action="<?= base_url('falserejection/cetak') ?>" method="post" class="form-inline">
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