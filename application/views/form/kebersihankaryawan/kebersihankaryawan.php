<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Kebersihan Karyawan</h1>
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
				<a href="<?= base_url('kebersihankaryawan/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('kebersihankaryawan/audit'); ?>"
						class="btn shadow-sm"
						style="background-color: #5a3298; color: #fff;">

						<i class="fas fa-clipboard-check mr-1"></i>
						Mode Audit

					</a>
				<?php endif; ?>
			</div>
			<hr>
			<div style="display: flex; gap: 20px;">
				<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 40%; text-align: left; font-family: Arial, sans-serif; font-size: 14px;">
					<thead style="background-color: #f2f2f2;">
						<tr>
							<th colspan="2" style="padding: 10px; background-color: #ADD8E6; color: gray;">Keterangan Pemeriksaan</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1. Seragam</td>
							<td>5. Perhiasan</td>
						</tr>
						<tr>
							<td>2. Apron</td>
							<td>6. Masker</td>
						</tr>
						<tr>
							<td>3. Tangan dan Kuku</td>
							<td>7. Topi / Hairnet</td>
						</tr>
						<tr>
							<td>4. Kosmetik</td>
							<td>8. Sepatu Kerja</td>
						</tr>
					</tbody>
				</table>
				<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 30%; text-align: center; font-family: Arial, sans-serif; font-size: 14px;">
					<thead style="background-color: #f2f2f2;">
						<tr>
							<th colspan="2" style="padding: 10px; background-color: #ADD8E6; color: gray;">Simbol Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>✔️</td>
							<td>Ok</td>
						</tr>
						<tr>
							<td>❌</td>
							<td>Tidak Ok</td>
						</tr>
						<tr>
							<td>−</td>
							<td>Tidak ada / Tidak digunakan</td>
						</tr>
					</tbody>
				</table>
			</div>

			<br>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center" rowspan="2">No</th>
							<th rowspan="2">Tanggal</th>
							<th rowspan="2">Nama</th>
							<th rowspan="2">Bagian</th>
							<th colspan="8" class="text-center">Kebersihan</th>
							<th rowspan="2">Tindakan Koreksi</th>
							<th rowspan="2">Supervisor</th>
							<th rowspan="2" class="text-center">Action</th>
						</tr>
						<tr>
							<th>1</th>
							<th>2</th>
							<th>3</th>
							<th>4</th>
							<th>5</th>
							<th>6</th>
							<th>7</th>
							<th>8</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($kebersihankaryawan as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->nama; ?></td>
								<td><?= $val->bagian; ?></td>
								<td class="text-center">
									<?= ($val->seragam == 'ok') ? '✔️' : (($val->seragam == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->apron == 'ok') ? '✔️' : (($val->apron == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->tangan_kuku == 'ok') ? '✔️' : (($val->tangan_kuku == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->kosmetik == 'ok') ? '✔️' : (($val->kosmetik == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->perhiasan == 'ok') ? '✔️' : (($val->perhiasan == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->masker == 'ok') ? '✔️' : (($val->masker == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->topi_hairnet == 'ok') ? '✔️' : (($val->topi_hairnet == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->sepatu == 'ok') ? '✔️' : (($val->sepatu == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td><?= $val->tindakan; ?></td>
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
									<a href="<?= base_url('kebersihankaryawan/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
										<span class="text">Edit</span>
									</a>
									<a href="<?= base_url('kebersihankaryawan/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split">
										<span class="text">Detail</span>
									</a>
									<?php if (in_array($tipe_user, [0, 1, 2, 9 ])): ?>
										<a href="<?= base_url('kebersihankaryawan/status/' . $val->uuid) ?>" class="btn btn-info btn-icon-split">
											<span class="text">Verifikasi</span>
										</a>
									<?php endif; ?>

									<!-- <a href="<?= base_url('kebersihankaryawan/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
				<br>
				<hr>
				<div class="form-group">
					<form action="<?= base_url('kebersihankaryawan/cetak') ?>" method="post" class="form-inline">

						<label class="mr-2 font-weight-bold">
							Pilih Tanggal:
						</label>

						<input type="date"
							name="tanggal"
							class="form-control mr-2"
							required>

						<button type="submit" class="btn btn-success mr-2">
							<i class="fas fa-print fa-sm text-white-50"></i>
							Cetak PDF
						</button>

						<?php if ($this->session->userdata('plant') != '651ac623-5e48-44cc-b2f6-5d622603f53c') : ?>
							<button type="submit"
								formaction="<?= base_url('form/pemeriksaan_gabungan/cetak') ?>"
								class="btn btn-primary">
								<i class="fas fa-file-pdf fa-sm text-white-50"></i>
								Cetak Gabungan
							</button>
						<?php endif; ?>

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