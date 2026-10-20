<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Checklist Inventaris Peralatan QC Bread Crumb</h1>
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

	<?php
	$tipe_user = $this->session->userdata('tipe_user');
	?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('inventaris/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('inventaris/audit'); ?>"
						class="btn shadow-sm"
						style="background-color: #5a3298; color: #fff;">

						<i class="fas fa-clipboard-check mr-1"></i>
						Mode Audit

					</a>
				<?php endif; ?>
			</div>
			<hr>
			<div class="table-responsive">
				<form action="<?= base_url('inventaris/cetak') ?>" method="post" id="form_cetak_pdf">
					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="30px" class="text-center">
									<i class="fas fa-print fa-lg"></i>
								</th>
								<!-- <th width="20px" class="text-center" rowspan="2">No</th>
                            <th rowspan="2">Tanggal / Shift</th>
                            <th rowspan="2">Nama Alat</th>
                            <th rowspan="2">Jumlah</th>
                            <th colspan="2" style="text-align: center;">Kondisi</th>
                            <th rowspan="2">Keterangan</th>
                            <th rowspan="2">Supervisor</th>
                            <th rowspan="2" class="text-center">Action</th> -->
								<th>No</th>
								<th>Tanggal / Shift</th>
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
							foreach ($inventaris as $val) {
								$datetime = new DateTime($val->date);
								$tanggalFormatted = $datetime->format('d-m-Y');

								$equipment = json_decode($val->peralatan, true);
							?>
								<tr>
									<td class="text-center"><input type="checkbox" name="checkbox[]" value="<?= $val->uuid ?>" class="select_row"></td>
									<td class="text-center"><?= $no; ?></td>
									<td><?= $tanggalFormatted . " / " . $val->shift; ?></td>
									<!-- <td>
                                    <ul>
                                        <?php
										if (!empty($equipment)) {
											foreach ($equipment as $equip) {
												echo '<li>' . htmlspecialchars($equip['nama_alat']) . '</li>';
											}
										}
										?>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <?php
										if (!empty($equipment)) {
											foreach ($equipment as $equip) {
												echo '<li>' . htmlspecialchars($equip['jumlah']) . '</li>';
											}
										}
										?>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <?php
										if (!empty($equipment)) {
											foreach ($equipment as $equip) {
												echo '<li>' . htmlspecialchars($equip['kondisi_awal']) . '</li>';
											}
										}
										?>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <?php
										if (!empty($equipment)) {
											foreach ($equipment as $equip) {
												echo '<li>' . htmlspecialchars($equip['kondisi_akhir']) . '</li>';
											}
										}
										?>
                                    </ul>
                                </td>
                                <td>
                                    <ul>
                                        <?php
										if (!empty($equipment)) {
											foreach ($equipment as $equip) {
												echo '<li>' . htmlspecialchars($equip['keterangan']) . '</li>';
											}
										}
										?>
                                    </ul>
                                </td> -->
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
										<a href="<?= base_url('inventaris/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
											<span class="text">Edit</span>
										</a>
										<a href="<?= base_url('inventaris/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('inventaris/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
											<span class="text">Delete</span>
										</a>
										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
											<a href="<?= base_url('inventaris/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
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
<style>
	th {
		background-color: #f8f9fc;
	}
</style>
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