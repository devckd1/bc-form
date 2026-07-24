<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Pemeriksaan Benda Mudah Pecah</h1>
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
			<div class="table-responsive">
				<form action="<?= base_url('pecahbelah/cetak') ?>" method="post" id="form_cetak_pdf">
					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<!-- <th width="30px" class="text-center">
									<i class="fas fa-print fa-lg"></i>
								</th>
								<th width="20px" class="text-center" rowspan="2">No</th>
                                <th rowspan="2">Tanggal / Shift</th>
                                <th rowspan="2">Nama Barang</th>
                                <th rowspan="2">Area / Pemilik</th>
                                <th rowspan="2">Jumlah (pcs)</th>
                                <th colspan="2" style="text-align: center;">Kondisi</th>
                                <th rowspan="2">Keterangan</th>
                                <th rowspan="2">Last Updated</th>
                                <th rowspan="2">Last Verified</th>
                                <th rowspan="2">SPV</th>
                                <th class="text-center" rowspan="2">Action</th> -->
								<th>No</th>
								<th>Tanggal / Shift</th>
								<th>Last Updated</th>
								<th>Last Verified</th>
								<th>Supervisor</th>
								<th>Action</th>
							</tr>
							<!-- <tr>
                                <th>Kondisi Awal</th>
                                <th>Kondisi Akhir</th>
                            </tr> -->
						</thead>
						<tbody>
							<?php
							$no = 1;
							foreach ($pecahbelah as $val) {
								$datetime = new DateTime($val->date);
								$tanggalFormatted = $datetime->format('d-m-Y');

								$breakable = json_decode($val->benda_pecah, true);
							?>
								<tr>
									<!--<td class="text-center"><input type="checkbox" name="checkbox[]" value="<?= $val->uuid ?>" class="select_row"></td> -->
									<td class="text-center"><?= $no; ?></td>
									<td><?= $tanggalFormatted . " / " . $val->shift; ?></td>
									<!--  <td>
                                        <ul>
                                            <?php
											if (!empty($breakable)) {
												foreach ($breakable as $equip) {
													echo '<li>' . htmlspecialchars($equip['nama_barang']) . '</li>';
												}
											}
											?>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <?php
											if (!empty($breakable)) {
												foreach ($breakable as $equip) {
													echo '<li>' . htmlspecialchars($equip['area']) . " / " . htmlspecialchars($equip['pemilik']) . '</li>';
												}
											}
											?>
                                      </ul>
                                  </td>
                                  <td>
                                    <ul>
                                        <?php
										if (!empty($breakable)) {
											foreach ($breakable as $equip) {
												echo '<li>' . htmlspecialchars($equip['jumlah']) . '</li>';
											}
										}
										?>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <?php
										if (!empty($breakable)) {
											foreach ($breakable as $equip) {
												echo '<li>' . htmlspecialchars($equip['kondisi_awal']) . '</li>';
											}
										}
										?>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <?php
										if (!empty($breakable)) {
											foreach ($breakable as $equip) {
												echo '<li>' . htmlspecialchars($equip['kondisi_akhir']) . '</li>';
											}
										}
										?>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <?php
										if (!empty($breakable)) {
											foreach ($breakable as $equip) {
												echo '<li>' . htmlspecialchars($equip['keterangan']) . '</li>';
											}
										}
										?>
                                    </ul>
                                </td> -->
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
										<a href="<?= base_url('pecahbelah/status/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
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
					<input type="hidden" name="checkbox[]" id="selected_items">
				</form>
			</div>

			<br>
			<hr>
			<div class="form-group">
				<label>Pilih Data yang akan dicetak:</label>
				<br>
				<form action="<?= base_url('pecahbelah/cetak') ?>" method="post">

					<div class="row">

						<div class="col-md-3">
							<input
								type="date"
								name="tanggal"
								class="form-control"
								required>
						</div>

						<div class="col-md-2">
							<button class="btn btn-success">
								<i class="fas fa-print"></i>
								Cetak PDF
							</button>
						</div>

					</div>

				</form>
			</div>
		</div>
	</div>
</div>
</div>

<script>
	/*document.getElementById('select_all').addEventListener('change', function() {
        var checkboxes = document.querySelectorAll('.select_row');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = this.checked;
        });
    });*/

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
		//document.getElementById('selected_items').value = selectedItems.join(',');
	});
</script>
<style>
	th {
		background-color: #f8f9fc;
	}
</style>
