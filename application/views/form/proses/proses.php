<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Verifikasi Proses Produksi</h1>
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
				<a href="<?= base_url('proses/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('proses/audit'); ?>"
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
							<th class="text-center">Date</th>
							<th class="text-center">Shift</th>
							<th class="text-center">Types of Product</th>
							<th class="text-center">Kode Produksi</th>
							<th class="text-center">Finish Product</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($proses as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->shift; ?></td>
								<td><?= $val->nama_produk; ?></td>
								<td class="text-center">
									<?php
									$data = json_decode($val->proses_produksi, true);

									// Get the kode_produksi array
									$kodeList = $data['dough_mixing']['kode_produksi'] ?? [];

									// Find the first non-empty kode produksi
									$firstKode = '';
									foreach ($kodeList as $kp) {
										if (!empty($kp)) {
											$firstKode = $kp;
											break;
										}
									}

									echo $firstKode ?: '-';
									?>
								</td>
								<td class="text-center">
									<a href="<?= base_url('proses/packing/' . $val->uuid); ?>" class="btn btn-primary btn-icon-split">
										<span class="text">Finish Product</span>
									</a>
								</td>
								<td class="text-center">
									<a href="<?= base_url('proses/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
										<span class="text">Edit</span>
									</a>
									<a href="<?= base_url('proses/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split">
										<span class="text">Detail</span>
									</a>
									<a href="<?= base_url('proses/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
										<span class="text">Delete</span>
									</a>
									<a href="<?= base_url('proses/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
										<span class="text">Verifikasi</span>
									</a>
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
					<label>Pilih Data yang akan dicetak:</label>
					<form action="<?= base_url('proses/cetak') ?>" method="post" id="form_cetak_pdf">
						<div class="row mb-3">
							<div class="col-md-3">
								<label for="tanggal">Tanggal</label>
								<input type="date" name="tanggal" id="tanggal" class="form-control" required>
							</div>
							<div class="col-md-6 align-self-end">
								<button type="submit" class="btn btn-success">
									<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
								</button>
								<button type="button" class="btn btn-primary" id="btn_export_excel">
									<i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
								</button>
							</div>
						</div>
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