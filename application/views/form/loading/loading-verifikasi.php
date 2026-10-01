<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Loading Produk</h1>
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
			<div class="form-group text-right">
				<a href="<?= base_url('loading/audit'); ?>"
					class="btn btn-info btn-sm">
					<i class="fas fa-clipboard-check mr-1"></i>
					Data Audit
				</a>
			</div>
			<div class="table-responsive">
				<form action="<?= base_url('loading/cetak') ?>" method="post" id="form_cetak_pdf">
					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="30px" class="text-center">
									<i class="fas fa-print fa-lg"></i>
								</th>
								<th width="20px" class="text-center">No</th>
								<th>Tanggal / Shift</th>
								<th>Start - Finish Loading</th>
								<th>Tujuan</th>
								<th style="text-align: center;">LOADING</th>
								<th>Last Updated</th>
								<th>Last Verified</th>
								<th>SPV</th>
								<th class="text-center">Action</th>
							</tr>
							<!-- <tr>
                                <th>Nama Produk</th>
                                <th>Kode Produksi</th>
                                <th>Kode Expired</th>
                                <th>Keterangan</th>
                            </tr> -->
						</thead>
						<tbody>
							<?php
							$no = 1;
							foreach ($loading as $val) {
								$datetime = new DateTime($val->date);
								$tanggalFormatted = $datetime->format('d-m-Y');

								$start = new DateTime($val->start_loading);
								$startFormatted = $start->format('H:i');
								$finish = new DateTime($val->finish_loading);
								$finishFormatted = $finish->format('H:i');

								$result = json_decode($val->loading, true);
							?>
								<tr>
									<td class="text-center"><input type="checkbox" name="checkbox[]" value="<?= $val->uuid ?>" class="select_row"></td>
									<td class="text-center"><?= $no; ?></td>
									<td><?= $tanggalFormatted . " / " . $val->shift ?></td>
									<td><?= $startFormatted . " / " . $finishFormatted; ?></td>
									<td><?= htmlspecialchars($val->tujuan) ?></td>
									<td>
										<table class="table table-sm table-bordered mb-0">
											<thead style="background-color:#2E86C1; color:gray; text-align:center;">
												<tr>
													<th width="30%">Nama Produk</th>
													<th width="20%">Kode Produksi</th>
													<th width="20%">Kode Expired</th>
													<th width="20%">Keterangan</th>
												</tr>
											</thead>
											<tbody>
												<?php if (!empty($result) && is_array($result)): ?>
													<?php foreach ($result as $row): ?>
														<tr>
															<td><?= htmlspecialchars($row['nama_produk'] ?? '-'); ?></td>
															<td style="text-align:center;"><?= htmlspecialchars($row['kode_produksi'] ?? '-'); ?></td>
															<td style="text-align:center;"><?= htmlspecialchars($row['expired'] ?? '-'); ?></td>
															<td style="text-align:center;"><?= htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
														</tr>
													<?php endforeach ?>
												<?php else: ?>
													<tr>
														<td colspan="3" class="text-center">Tidak ada data</td>
													</tr>
												<?php endif ?>
											</tbody>
										</table>
									</td>
									<td><?= date('H:i - d m Y', strtotime($val->modified_at)); ?></td>
									<td><?= date('H:i - d m Y', strtotime($val->tgl_update_spv)); ?></td>
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
										<a href="<?= base_url('loading/status/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
											<span class="text">Verifikasi</span>
										</a>

										<?php
										$CI = &get_instance();
										$CI->load->model('loading_model');

										$audit = $CI->loading_model
											->get_audit_by_source_uuid($val->uuid);
										?>

											<a href="<?= base_url('loading/copy-to-audit/' . $val->uuid); ?>"
												class="btn btn-info btn-icon-split btn-sm"
												onclick="return confirm('Kirim data ini ke Audit?');">
												<span class="text">Kirim Audit</span>
											</a>

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

			<br>
			<hr>
			<div class="form-group">
				<label>Pilih Data yang akan dicetak:</label>
				<br>
				<button type="submit" form="form_cetak_pdf" class="btn btn-success">
					<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
				</button>
			</div>
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
