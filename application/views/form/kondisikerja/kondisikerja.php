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
			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<div class="d-flex align-items-center flex-wrap mb-2">

						<input type="date"
							id="filter_tanggal"
							class="form-control mr-2 mb-2"
							style="width: 170px;"
							value="<?= htmlspecialchars($filter_tanggal ?? '', ENT_QUOTES, 'UTF-8'); ?>">

						<select id="filter_shift"
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

						<button type="button"
							id="btnVerifikasiMassal"
							class="btn btn-primary mb-2">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>

						<a href="<?= base_url('kondisikerja'); ?>"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>
					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('kondisikerja/tambah'); ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('kondisikerja/audit'); ?>"
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
					Hanya data yang sudah diverifikasi Produksi yang akan diproses.
				</small>
			<?php endif; ?>

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
							<th class="text-center">Status</th>
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
							$no = (int) ($start ?? 0) + 1;
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
										$tipe_user = (int) $this->session->userdata('tipe_user');

										if ($val->status_spv == '1') {
											echo '<span style="color: #28a745; font-weight: bold; font-size: 12px;">Verified</span>';
										} elseif ($val->status_spv == '2') {
											echo '<span style="color: red; font-weight: bold; font-size: 12px;">Revision</span>';
										} elseif ($val->status_produksi == '1' && $val->status_spv == '0') {
											echo '<span style="color: #28a745; font-weight: bold; font-size: 12px;">Approved</span>';
										} elseif ($tipe_user == 3 && $val->status_spv == '0') {
											echo '<span style="color: #99a3a4; font-weight: bold; font-size: 12px;">Created</span>';
										} elseif ($val->status_produksi == '0') {
											echo '<span style="color: #f39c12; font-weight: bold; font-size: 12px;">Menunggu Verifikasi Produksi</span>';
										}
										?>
									</td>
									<td class="text-center" rowspan="3">
										<div class="action-buttons">

											<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
												<a href="<?= base_url('kondisikerja/edit/' . $val->uuid); ?>"
													class="btn btn-warning btn-icon-split mb-1">
													<span class="text">Edit</span>
												</a>
											<?php endif; ?>

											<a href="<?= base_url('kondisikerja/detail/' . $val->uuid); ?>"
												class="btn btn-success btn-icon-split mb-1 btn-detail-kondisikerja"
												data-url="<?= base_url('kondisikerja/detail/' . $val->uuid . '?popup=1'); ?>">
												<span class="text">Detail</span>
											</a>

											<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
												<a href="<?= base_url('kondisikerja/verifikasi_produksi/' . $val->uuid); ?>"
													class="btn btn-primary btn-icon-split mb-1"
													onclick="return confirm('Apakah pemeriksaan ini yakin disetujui?');">
													<span class="text">Setujui</span>
												</a>
											<?php endif; ?>

											<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

												<?php if (
													$val->status_produksi == '1' &&
													$val->status_spv == '0'
												): ?>
													<a href="<?= base_url('kondisikerja/status/' . $val->uuid); ?>"
														class="btn btn-info btn-icon-split mb-1">
														<span class="text">Verifikasi</span>
													</a>

												<?php elseif (
													$val->status_produksi == '1' &&
													$val->status_spv == '2'
												): ?>
													<a href="<?= base_url('kondisikerja/status/' . $val->uuid); ?>"
														class="btn btn-warning btn-icon-split mb-1">
														<span class="text">Verifikasi Ulang</span>
													</a>
												<?php endif; ?>

											<?php endif; ?>

										</div>
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
<div class="modal fade" id="modalDetailKondisiKerja" tabindex="-1"
	role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title">Detail Pemeriksaan Kondisi Kerja</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailKondisiKerja">
				<div class="text-center py-5">
					<i class="fas fa-spinner fa-spin fa-2x"></i>
					<p class="mt-2">Memuat detail...</p>
				</div>
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
<script>
	$(document).ready(function() {

		// Filter tanggal dan shift
		function filterKondisiKerja() {
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

			window.location.href = "<?= base_url('kondisikerja'); ?>" +
				(query ? '?' + query : '');
		}

		$('#filter_tanggal, #filter_shift').on('change', function() {
			filterKondisiKerja();
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
				text: 'Data Kondisi Kerja yang sudah diverifikasi Produksi pada tanggal dan shift terpilih akan diproses.',
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
					url: "<?= base_url('kondisikerja/verifikasi_massal'); ?>",
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

	// Modal Detail Kondisi Kerja AJAX
	let cacheDetailKondisiKerja = {};

	$(document).on('click', '.btn-detail-kondisikerja', function(e) {

		e.preventDefault();

		let url = $(this).data('url');

		$('#modalDetailKondisiKerja').modal('show');

		if (cacheDetailKondisiKerja[url]) {
			$('#contentDetailKondisiKerja').html(
				cacheDetailKondisiKerja[url]
			);
			return;
		}

		$('#contentDetailKondisiKerja').html(`
        <div class="text-center py-5">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p class="mt-2">Memuat detail...</p>
        </div>
    `);

		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'html',

			success: function(response) {
				cacheDetailKondisiKerja[url] = response;
				$('#contentDetailKondisiKerja').html(response);
			},

			error: function() {
				$('#contentDetailKondisiKerja').html(`
                <div class="alert alert-danger">
                    Gagal memuat detail pemeriksaan Kondisi Kerja.
                </div>
            `);
			}
		});

	});
</script>
