<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Laporan Roti Gosong</h1>
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
				<a href="<?= base_url('gosong/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('gosong/audit'); ?>"
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
							<th>Shift</th>
							<th>Total Berat (Kg)</th>
							<th>Inspector</th>
							<th>Supervisor</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($gosong as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->shift; ?></td>
								<td><?= $val->total_berat; ?></td>
								<td><?= $val->username; ?></td>
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
									<a href="<?= base_url('gosong/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split mb-1">
										<span class="text">Edit</span>
									</a>
									<a href="<?= base_url('gosong/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split mb-1">
										<span class="text">Detail</span>
									</a>
									<a href="<?= base_url('gosong/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
										<span class="text">Delete</span>
									</a>
									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('gosong/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
											<span class="text">Verifikasi</span>
										</a>
									<?php endif; ?>
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
					<form action="<?= base_url('gosong/cetak') ?>" method="post" class="form-inline">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" class="form-control mr-2" required>
						<button type="submit" class="btn btn-success">
							<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
						</button>
					</form>
				</div>
				<hr>
				<div class="form-group mt-3">
					<form action="<?= base_url('gosong/export_excel') ?>" method="post" class="form-inline">
						<label for="bulan" class="mr-2 font-weight-bold">Export Excel Bulanan:</label>
						<input type="month" name="bulan" class="form-control mr-2" required>

						<button type="submit" class="btn btn-primary">
							<i class="fas fa-file-excel"></i> Export Excel
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
<script>
	document.getElementById('select_all').addEventListener('change', function() {
		var checkboxes = document.querySelectorAll('.select_row');
		checkboxes.forEach(function(checkbox) {
			checkbox.checked = this.checked;
		});
	});

	document.getElementById('form_cetak_pdf').addEventListener('submit', function(event) {
		var selectedCheckboxes = document.querySelectorAll('.select_row:checked');
		var selectedItems = [];

		selectedCheckboxes.forEach(function(checkbox) {
			selectedItems.push(checkbox.value);
		});
		if (selectedItems.length === 0) {
			event.preventDefault();
			alert("Silakan pilih data yang ingin dicetak.");
			return;
		}
		document.getElementById('selected_items').value = selectedItems.join(',');
	});
</script>
<style>
	th {
		background-color: #f8f9fc;
	}
</style>