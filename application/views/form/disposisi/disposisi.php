<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Disposisi Produk dan Prosedur</h1>
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
				<a href="<?= base_url('disposisi/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('disposisi/audit'); ?>"
						class="btn shadow-sm"
						style="background-color: #5a3298; color: #fff;">

						<i class="fas fa-clipboard-check mr-1"></i>
						Mode Audit

					</a>
				<?php endif; ?>
			</div>
			<hr>
			<div class="table-responsive">
				<form action="<?= base_url('disposisi/cetak') ?>" method="post" id="form_cetak_pdf">
					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="30px" class="text-center">
									<i class="fas fa-print fa-lg"></i>
								</th>
								<th width="20px" class="text-center">No</th>
								<th>Tanggal</th>
								<th>Nomor</th>
								<th>Kepada</th>
								<th>Disposisi</th>
								<th>Dasar Disposisi</th>
								<th>SPV</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							foreach ($disposisi as $val) {
								$datetime = new datetime($val->date);
								$datetime = $datetime->format('d-m-Y');
							?>
								<tr>
									<td class="text-center"><input type="checkbox" name="checkbox[]" value="<?= $val->uuid ?>" class="select_row"></td>
									<td class="text-center"><?= $no; ?></td>
									<td><?= $datetime; ?></td>
									<td><?= $val->nomor; ?></td>
									<td><?= $val->kepada; ?></td>
									<td><?= $val->disposisi; ?></td>
									<td><?= $val->dasar_disposisi; ?></td>
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
										<a href="<?= base_url('disposisi/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
											<span class="text">Edit</span>
										</a>
										<a href="<?= base_url('disposisi/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split">
											<span class="text">Detail</span>
										</a>
										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
											<a href="<?= base_url('disposisi/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>
										<?php endif; ?>
										<!--   <a href="<?= base_url('disposisi/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
					<input type="hidden" name="checkbox[]" id="selected_items">
				</form>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<div class="form-group">
					<label>Pilih Data yang akan dicetak:</label>
					<br>
					<button type="submit" form="form_cetak_pdf" class="btn btn-success">
						<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
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