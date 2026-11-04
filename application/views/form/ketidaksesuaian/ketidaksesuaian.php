<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Data Ketidaksesuaian Produk</h1>
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
				<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
					<a href="<?= base_url('ketidaksesuaian/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
						<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
					</a>
				<?php endif; ?>
			</div>
			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Pukul / Shift</th>
							<th>Nama Produk</th>
							<th>Ketidaksesuaian</th>
							<th>Jumlah</th>
							<th>Penyebab / Kategori Bahaya</th>
							<th>Supervisor</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($ketidaksesuaian as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime . " / " . date('H:i', strtotime($val->waktu)) . " / " . $val->shift; ?></td>
								<td><?= $val->nama_produk; ?></td>
								<td><?= $val->ketidaksesuaian; ?></td>
								<td><?= $val->jumlah; ?></td>
								<td><?= $val->penyebab; ?></td>
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
									<a href="<?= base_url('ketidaksesuaian/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
										<span class="text">Update</span>
									</a>
									<a href="<?= base_url('ketidaksesuaian/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split">
										<span class="text">Detail</span>
									</a>
									<!-- <a href="<?= base_url('ketidaksesuaian/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
		</div>
	</div>
</div>
</div>
<style>
	th {
		background-color: #f8f9fc;
	}
</style>