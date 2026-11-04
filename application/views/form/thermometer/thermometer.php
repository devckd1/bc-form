<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Peneraan Thermometer</h1>
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
			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<div class="d-flex align-items-center flex-wrap mb-2">

						<input
							type="date"
							id="filter_tanggal"
							class="form-control mr-2 mb-2"
							style="width: 170px;"
							value="<?= htmlspecialchars($filter_tanggal ?? '', ENT_QUOTES, 'UTF-8'); ?>">

						<select
							id="filter_shift"
							class="form-control mr-2 mb-2"
							style="width: 140px;">

							<option value="">Semua Shift</option>

							<?php foreach (['1', '2', '3'] as $shift): ?>
								<option value="<?= $shift; ?>"
									<?= (($filter_shift ?? '') === $shift) ? 'selected' : ''; ?>>
									Shift <?= $shift; ?>
								</option>
							<?php endforeach; ?>

						</select>

						<button
							type="button"
							id="btnVerifikasiMassal"
							class="btn btn-primary mb-2">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>

						<a href="<?= base_url('thermometer'); ?>"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>

					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('thermometer/tambah'); ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('thermometer/audit'); ?>"
							class="btn shadow-sm"
							style="background-color: #5a3298; color: #fff;">
							<i class="fas fa-clipboard-check mr-1"></i>
							Mode Audit
						</a>
					<?php endif; ?>

				</div>
			</div>

			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<small class="text-muted d-block mb-2">
					<i class="fas fa-info-circle mr-1"></i>
					Verifikasi massal hanya memproses data QC dengan status SPV Created atau Revision.
				</small>
			<?php endif; ?>

			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Hasil Pemeriksaan</th>
							<th>Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;
						foreach ($thermometer as $val) {
							$datetime = new DateTime($val->date);
							$datetime = $datetime->format('d-m-Y');
							$result = json_decode($val->peneraan_hasil, true);
						?>
							<tr>
								<td class="text-center"><?= $no++; ?></td>
								<td><?= $datetime; ?></td>
								<td>
									<table class="table table-sm table-bordered mb-0">
										<thead class="text-center" style="background-color:#skyblue; color:darkblue;">
											<tr>
												<th width="15%">Kode Thermometer</th>
												<th width="15%">Model</th>
												<th width="15%">Area</th>
												<th width="10%">Pukul</th>
												<th width="15%">Standar Suhu (°C)</th>
												<th width="10%">Hasil</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($result) && is_array($result)): ?>
												<?php foreach ($result as $row): ?>
													<tr>
														<td><?= htmlspecialchars($row['kode_thermo'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($row['model'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($row['area'] ?? '-'); ?></td>
														<td class="text-center"><?= htmlspecialchars($row['pukul'] ?? '-'); ?></td>
														<td class="text-center"><?= htmlspecialchars($row['standar'] ?? '-'); ?>°C</td>
														<td class="text-center"><?= htmlspecialchars($row['hasil'] ?? '-'); ?></td>
													</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="6" class="text-center">Tidak ada data</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</td>
								<td class="text-center">
									<?php
									$tipe_user = (int) $this->session->userdata('tipe_user');

									if ($val->status_spv == '1') {
										echo '<span style="color:#28a745;font-weight:bold;font-size:12px;">Verified</span>';
									} elseif ($val->status_spv == '2') {
										echo '<span style="color:red;font-weight:bold;font-size:12px;">Revision</span>';
									} elseif ($val->status_produksi == '1' && $val->status_spv == '0') {
										echo '<span style="color:#28a745;font-weight:bold;font-size:12px;">Approved</span>';
									} elseif ($tipe_user == 3 && $val->status_spv == '0') {
										echo '<span style="color:#99a3a4;font-weight:bold;font-size:12px;">Created</span>';
									} elseif ($val->status_produksi == '0') {
										echo '<span style="color:#f39c12;font-weight:bold;font-size:12px;">Menunggu Verifikasi Produksi</span>';
									}
									?>
								</td>
								<td class="text-center">
									<div class="action-buttons">

										<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
											<a href="<?= base_url('thermometer/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-sm mb-1">
												Edit
											</a>
										<?php endif; ?>

										<a href="<?= base_url('thermometer/detail/' . $val->uuid); ?>"
											class="btn btn-success btn-sm mb-1 btn-detail-thermometer"
											data-url="<?= base_url('thermometer/detail/' . $val->uuid . '?popup=1'); ?>">
											Detail
										</a>

										<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
											<a href="<?= base_url('thermometer/verifikasi_produksi/' . $val->uuid); ?>"
												class="btn btn-primary btn-sm mb-1"
												onclick="return confirm('Apakah pemeriksaan ini yakin disetujui?');">
												Setujui
											</a>
										<?php endif; ?>

										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

											<?php if (
												$val->status_produksi == '1' &&
												$val->status_spv == '0'
											): ?>
												<a href="<?= base_url('thermometer/status/' . $val->uuid); ?>"
													class="btn btn-info btn-sm mb-1">
													Verifikasi
												</a>

											<?php elseif (
												$val->status_produksi == '1' &&
												$val->status_spv == '2'
											): ?>
												<a href="<?= base_url('thermometer/status/' . $val->uuid); ?>"
													class="btn btn-warning btn-sm mb-1">
													Verifikasi Ulang
												</a>
											<?php endif; ?>

										<?php endif; ?>

									</div>
									<!--  <a href="<?= base_url('thermometer/delete/' . $val->uuid); ?>" 
                             class="btn btn-danger btn-sm"
                             onclick="return confirm('Yakin ingin menghapus data ini?')">
                             Delete
                         </a> -->
								</td>
							</tr>
						<?php } ?>
					</tbody>

				</table>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<div class="form-group">
					<form action="<?= base_url('thermometer/cetak') ?>" method="post" class="form-inline">
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
<!-- MODAL DETAIL THERMOMETER -->
<div class="modal fade" id="modalDetailThermometer" tabindex="-1"
	role="dialog" aria-labelledby="modalDetailThermometerLabel" aria-hidden="true">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailThermometerLabel">
					Detail Peneraan Thermometer
				</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailThermometer">
				<div class="text-center py-4">
					<i class="fas fa-spinner fa-spin fa-2x"></i>
					<p class="mt-2">Memuat data...</p>
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
<script>
	$(document).ready(function() {

		// Filter tanggal dan shift
		function filterThermometer() {
			var tanggal = $('#filter_tanggal').val();
			var shift = $('#filter_shift').val();

			var params = new URLSearchParams();

			if (tanggal) {
				params.set('tanggal', tanggal);
			}

			if (shift) {
				params.set('shift', shift);
			}

			var query = params.toString();

			window.location.href = "<?= base_url('thermometer'); ?>" +
				(query ? '?' + query : '');
		}

		$('#filter_tanggal, #filter_shift').on('change', function() {
			filterThermometer();
		});

		// Verifikasi massal SPV
		$('#btnVerifikasiMassal').on('click', function() {

			var tanggal = $('#filter_tanggal').val();
			var shift = $('#filter_shift').val();
			var tombol = $(this);

			if (!tanggal) {
				Swal.fire(
					'Perhatian',
					'Pilih tanggal terlebih dahulu.',
					'warning'
				);
				return;
			}

			Swal.fire({
				title: 'Verifikasi Massal?',
				text: 'Data Thermometer dengan status SPV Created atau Revision pada tanggal dan shift terpilih akan diverifikasi.',
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Verifikasi',
				cancelButtonText: 'Batal'
			}).then(function(result) {

				if (!result.isConfirmed) {
					return;
				}

				tombol.prop('disabled', true);
				tombol.html(
					'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
				);

				$.ajax({
					url: "<?= base_url('thermometer/verifikasi_massal'); ?>",
					type: 'POST',
					dataType: 'json',
					data: {
						tanggal: tanggal,
						shift: shift || 'semua'
					},

					success: function(response) {

						if (response.success) {
							Swal.fire({
								title: 'Berhasil',
								text: response.message +
									' Jumlah data: ' + response.updated,
								icon: 'success'
							}).then(function() {
								window.location.reload();
							});
						} else {
							Swal.fire(
								'Informasi',
								response.message,
								'info'
							);

							tombol.prop('disabled', false);
							tombol.html(
								'<i class="fas fa-check-double mr-1"></i> Verifikasi Massal'
							);
						}
					},

					error: function() {
						Swal.fire(
							'Error',
							'Terjadi kesalahan saat memproses verifikasi massal.',
							'error'
						);

						tombol.prop('disabled', false);
						tombol.html(
							'<i class="fas fa-check-double mr-1"></i> Verifikasi Massal'
						);
					}
				});
			});
		});

	});

	// MODAL DETAIL THERMOMETER AJAX
	var cacheDetailThermometer = {};

	$(document).on('click', '.btn-detail-thermometer', function(e) {
		e.preventDefault();

		var url = $(this).data('url');

		$('#modalDetailThermometer').modal('show');

		if (cacheDetailThermometer[url]) {
			$('#contentDetailThermometer').html(cacheDetailThermometer[url]);
			return;
		}

		$('#contentDetailThermometer').html(`
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">Memuat data...</p>
        </div>
    `);

		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'html',

			success: function(response) {
				cacheDetailThermometer[url] = response;
				$('#contentDetailThermometer').html(response);
			},

			error: function() {
				$('#contentDetailThermometer').html(`
                <div class="alert alert-danger text-center">
                    Gagal memuat detail Thermometer.
                </div>
            `);
			}
		});
	});
</script>
