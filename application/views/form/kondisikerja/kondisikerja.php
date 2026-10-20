<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Data Kondisi Kerja Selama Produksi</h1>
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
				<a href="<?= base_url('kondisikerja/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('kondisikerja/audit'); ?>"
						class="btn shadow-sm"
						style="background-color: #5a3298; color: #fff;">

						<i class="fas fa-clipboard-check mr-1"></i>
						Mode Audit

					</a>
				<?php endif; ?>
			</div>
			<hr>
			<div style="display: flex; gap: 20px;">
				<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 50%; text-align: left; font-family: Times, sans-serif; font-size: 14px;">
					<thead style="background-color: #f2f2f2;">
						<tr>
							<th colspan="2" style="padding: 6px; background-color: #ADD8E6; color: gray;">Keterangan Pemeriksaan</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1. Berdebu</td>
							<td>5. Pertumbuhan Mikroorganisme (jamur, bau busuk, biofilm)</td>
						</tr>
						<tr>
							<td>2. Basah, ada genangan air</td>
							<td>6. Kontak / kontaminasi material non halal</td>
						</tr>
						<tr>
							<td>3. Sisa produksi (remah-remah roti, tepung, sisa adonan)</td>
							<td>7. Higiene karyawan tidak sesuai GMP</td>
						</tr>
						<tr>
							<td>4. Noda (karat, cat, tinta)</td>
							<td></td>
						</tr>
					</tbody>
				</table>
				<table border="1" cellpadding="6" cellspacing="0" style="border-collapse: collapse; width: 50%; text-align: center; font-family: Times, sans-serif; font-size: 14px;">
					<thead style="background-color: #f2f2f2;">
						<tr>
							<th colspan="2" style="padding: 6px; background-color: #ADD8E6; color: gray;">Simbol Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>✔️</td>
							<td>Ok, Sesuai SSOP, bersih, bebas najis / material non halal</td>
						</tr>
						<tr>
							<td>❌</td>
							<td>Tidak Ok, tidak sesuai SSOP</td>
						</tr>
						<tr>
							<td>−</td>
							<td>Tidak ada / Tidak digunakan</td>
						</tr>
					</tbody>
				</table>
			</div>
			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Area</th>
							<th>Item</th>
							<th>Kondisi</th>
							<th>Problem</th>
							<th>Tindakan Koreksi</th>
							<th>Verifikasi</th>
							<th>Supervisor</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if (empty($kondisikerja)) {
							echo ' <tr>
                            <td class="text-center">-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">-</td>
                            <td class="text-center">No data available</td>
                            </tr>';
						} else {
							$no = 1;
							foreach ($kondisikerja as $val) {
								try {
									$datetime = new DateTime($val->date);
									$formatted_date = $datetime->format('d-m-Y');
								} catch (Exception $e) {
									$formatted_date = '-';
								}
						?>
								<tr>
									<td class="text-center" rowspan="3"><?= $no++; ?></td>
									<td rowspan="3"><?= $formatted_date . " / " . $val->shift; ?></td>
									<td rowspan="3"><?= $val->area; ?></td>

									<td>Higiene Karyawan</td>
									<td><?= $val->kondisi_higiene; ?></td>
									<td><?= $val->problem_higiene; ?></td>
									<td><?= $val->tindakan_higiene; ?></td>
									<td><?= $val->verifikasi_higiene; ?></td>
									<td class="text-center" rowspan="3">
										<?php
										switch ($val->status_spv) {
											case 0:
												echo '<span class="text-muted font-weight-bold">Created</span>';
												break;
											case 1:
												echo '<span class="text-success font-weight-bold">Verified</span>';
												break;
											case 2:
												echo '<span class="text-danger font-weight-bold">Revision</span>';
												break;
										}
										?>
									</td>
									<td class="text-center" rowspan="3">
										<a href="<?= base_url('kondisikerja/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split mb-1">
											<span class="text">Edit</span>
										</a>
										<a href="<?= base_url('kondisikerja/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split mb-1">
											<span class="text">Detail</span>
										</a>
										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
											<a href="<?= base_url('kondisikerja/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>
										<?php endif; ?>
										<!-- <a href="<?= base_url('kondisikerja/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                            <span class="text">Delete</span>
                                        </a> -->
									</td>
								</tr>
								<tr>
									<td>Kebersihan Peralatan</td>
									<td><?= $val->kondisi_peralatan; ?></td>
									<td><?= $val->problem_peralatan; ?></td>
									<td><?= $val->tindakan_peralatan; ?></td>
									<td><?= $val->verifikasi_peralatan; ?></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
								</tr>
								<tr>
									<td>Kebersihan Area/Ruang</td>
									<td><?= $val->kondisi_kebersihan; ?></td>
									<td><?= $val->problem_kebersihan; ?></td>
									<td><?= $val->tindakan_kebersihan; ?></td>
									<td><?= $val->verifikasi_kebersihan; ?></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
									<td class="empty"></td>
								</tr>

						<?php
							}
						}
						?>
					</tbody>
				</table>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<!-- TOMBOL CETAK PDF PER TANGGAL -->
				<div class="form-group">
					<form action="<?= base_url('kondisikerja/cetak') ?>" method="post" class="form-inline">
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

	table.table {
		border-collapse: collapse !important;
	}

	table.table td,
	table.table th {
		border: 1px solid #dee2e6 !important;
		vertical-align: middle;
		padding: 10px;
	}

	.empty {
		display: none;
	}
</style>
<script>
	$(document).ready(function() {
		let dataExists = <?= !empty($kondisikerja) ? 'true' : 'false' ?>;
		if (dataExists) {
			$('#dataTable').DataTable({
				"ordering": false,
				"searching": true
			});
		}
	});
</script>