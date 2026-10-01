<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Chemical dari Supplier</h1>
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
			<div class="form-group text-right">
				<a href="<?= base_url('pemeriksaanchemical/audit'); ?>"
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
							<th>Tanggal / Shift</th>
							<th>Jenis Chemical</th>
							<th>Pemasok</th>
							<th>Kode Produksi</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($pemeriksaanchemical as $val) {
							$datetime = new DateTime($val->date);
						?>
							<tr>
								<td class="text-center"><?= $no++; ?></td>
								<td><?= $datetime->format('d-m-Y') . " / " . $val->shift; ?></td>
								<td><?= $val->jenis_chemical; ?></td>
								<td><?= $val->pemasok; ?></td>
								<td><?= $val->kode_produksi; ?></td>
								<td><?= date('H:i - d/m/Y', strtotime($val->modified_at)); ?></td>
								<td><?= date('H:i - d/m/Y', strtotime($val->tgl_update_spv)); ?></td>
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
									<a href="<?= base_url('pemeriksaanchemical/status/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
										<span class="text">Verifikasi</span>
									</a>

									<?php
									$CI = &get_instance();
									$CI->load->model('pemeriksaanchemical_model');

									$audit = $CI->pemeriksaanchemical_model
										->get_audit_by_source_uuid($val->uuid);
									?>
										<a href="<?= base_url('pemeriksaanchemical/copy-to-audit/' . $val->uuid); ?>"
											class="btn btn-info btn-icon-split btn-sm"
											onclick="return confirm('Kirim data ini ke Audit?');">
											<span class="text">Kirim Audit</span>
										</a>

								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>

			<br>
			<div class="mb-3">
				<form action="<?= base_url('pemeriksaanchemical/cetak') ?>" method="post" class="form-inline">
					<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
					<input type="date" name="tanggal" class="form-control mr-2" required>
					<button type="submit" class="btn btn-success">
						<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
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
