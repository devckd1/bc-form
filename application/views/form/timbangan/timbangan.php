<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Pemeriksaan Timbangan</h1>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div>
	<?php endif ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
	<?php endif ?>

	<?php
	$tipe_user = (int) $this->session->userdata('tipe_user');
	$boleh_verifikasi = in_array($tipe_user, [0, 1, 2, 9]);

	$filter_tanggal = $filter_tanggal ?? '';
	$filter_shift = $filter_shift ?? ($shift ?? '');
	?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<!-- FILTER DAN TOMBOL -->
			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<div class="d-flex align-items-center flex-wrap mb-2">

					<input
						type="date"
						id="filterTanggal"
						class="form-control mr-2 mb-2"
						style="width: 170px;"
						value="<?= html_escape($filter_tanggal ?? '') ?>">

					<select
						id="filterShift"
						class="form-control mr-2 mb-2"
						style="width: 140px;">

						<option value="">Semua Shift</option>

						<option value="1" <?= ($filter_shift ?? '') == '1' ? 'selected' : '' ?>>
							Shift 1
						</option>

						<option value="2" <?= ($filter_shift ?? '') == '2' ? 'selected' : '' ?>>
							Shift 2
						</option>

						<option value="3" <?= ($filter_shift ?? '') == '3' ? 'selected' : '' ?>>
							Shift 3
						</option>

					</select>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<button
							type="button"
							class="btn btn-primary btn-verifikasi-massal mr-1 mb-2"
							data-jenis="spv">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 3, 9])): ?>
						<button
							type="button"
							class="btn btn-primary btn-verifikasi-massal mb-2"
							data-jenis="produksi">
							<i class="fas fa-industry mr-1"></i>
							Verifikasi Massal
						</button>
					<?php endif; ?>

					<button
						type="button"
						id="resetFilterTimbangan"
						class="btn btn-secondary ml-2 mb-2">
						<i class="fas fa-sync-alt mr-1"></i>
						Reset
					</button>

				</div>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('timbangan/tambah'); ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if ($boleh_verifikasi): ?>
						<a href="<?= base_url('timbangan/audit'); ?>"
							class="btn shadow-sm"
							style="background-color: #5a3298; color: #fff;">
							<i class="fas fa-clipboard-check mr-1"></i>
							Mode Audit
						</a>
					<?php endif; ?>

				</div>
			</div>

			<hr>

			<!-- TABEL -->
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Hasil Pemeriksaan</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>
						<?php if (!empty($timbangan)): ?>
							<?php
							$no = (int) ($start ?? 0) + 1;

							foreach ($timbangan as $val):
								$datetime = new DateTime($val->date);
								$datetime = $datetime->format('d-m-Y');
								$result = json_decode($val->peneraan_hasil, true);
							?>
								<tr>
									<td class="text-center"><?= $no++; ?></td>

									<td>
										<?= $datetime . " / Shift " . html_escape($val->shift); ?>
									</td>

									<td>
										<table class="table table-sm table-bordered mb-0">
											<thead class="text-center" style="background-color:skyblue; color:darkblue;">
												<tr>
													<th width="15%">Kode Timbangan</th>
													<th width="15%">Kapasitas</th>
													<th width="15%">Model</th>
													<th width="15%">Lokasi</th>
													<th width="10%">Pukul</th>
													<th width="15%">Peneraan Standar</th>
													<th width="10%">Hasil</th>
												</tr>
											</thead>

											<tbody>
												<?php if (!empty($result) && is_array($result)): ?>
													<?php foreach ($result as $row): ?>
														<tr>
															<td><?= html_escape($row['kode_timbangan'] ?? '-'); ?></td>
															<td class="text-center"><?= html_escape($row['kapasitas'] ?? '-'); ?></td>
															<td class="text-center"><?= html_escape($row['model'] ?? '-'); ?></td>
															<td class="text-center"><?= html_escape($row['lokasi'] ?? '-'); ?></td>
															<td class="text-center"><?= html_escape($row['pukul'] ?? '-'); ?></td>
															<td class="text-center"><?= html_escape($row['peneraan_standar'] ?? '-'); ?></td>
															<td class="text-center"><?= html_escape($row['hasil'] ?? '-'); ?></td>
														</tr>
													<?php endforeach; ?>
												<?php else: ?>
													<tr>
														<td colspan="7" class="text-center">Tidak ada data</td>
													</tr>
												<?php endif; ?>
											</tbody>
										</table>
									</td>

									<td class="text-center">
										<?php
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
												<a href="<?= base_url('timbangan/edit/' . $val->uuid); ?>"
													class="btn btn-warning btn-sm mb-1">
													Edit
												</a>
											<?php endif; ?>

											<a href="<?= base_url('timbangan/detail/' . $val->uuid); ?>"
												class="btn btn-success btn-sm mb-1 btn-detail-timbangan"
												data-url="<?= base_url('timbangan/detail/' . $val->uuid . '?popup=1'); ?>">
												Detail
											</a>

											<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
												<a href="<?= base_url('timbangan/verifikasi_produksi/' . $val->uuid); ?>"
													class="btn btn-primary btn-sm mb-1"
													onclick="return confirm('Apakah pemeriksaan ini yakin disetujui?');">
													Setujui
												</a>
											<?php endif; ?>

											<?php if ($boleh_verifikasi): ?>

												<?php if (
													$val->status_produksi == '1' &&
													$val->status_spv == '0'
												): ?>
													<a href="<?= base_url('timbangan/status/' . $val->uuid); ?>"
														class="btn btn-info btn-sm mb-1">
														Verifikasi
													</a>

												<?php elseif (
													$val->status_produksi == '1' &&
													$val->status_spv == '2'
												): ?>
													<a href="<?= base_url('timbangan/status/' . $val->uuid); ?>"
														class="btn btn-warning btn-sm mb-1">
														Verifikasi Ulang
													</a>
												<?php endif; ?>

											<?php endif; ?>

										</div>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="5" class="text-center">
									Tidak ada data Timbangan.
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<!-- CETAK PDF -->
			<?php if ($boleh_verifikasi): ?>
				<br>
				<hr>

				<div class="form-group">
					<form action="<?= base_url('timbangan/cetak') ?>"
						method="post"
						class="form-inline">

						<label for="tanggalCetak" class="mr-2 font-weight-bold">
							Pilih Tanggal:
						</label>

						<input
							type="date"
							id="tanggalCetak"
							name="tanggal"
							class="form-control mr-2"
							required>

						<label for="shiftCetak" class="mr-2 font-weight-bold">
							Shift:
						</label>

						<select name="shift"
							id="shiftCetak"
							class="form-control mr-2"
							required>
							<option value="">-- Pilih Shift --</option>
							<option value="1">Shift 1</option>
							<option value="2">Shift 2</option>
							<option value="3">Shift 3</option>
						</select>

						<button type="submit" class="btn btn-success">
							<i class="fas fa-print fa-sm text-white-50"></i>
							Cetak PDF
						</button>
					</form>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>
<!-- MODAL DETAIL TIMBANGAN -->
<div class="modal fade" id="modalDetailTimbangan" tabindex="-1"
	role="dialog" aria-labelledby="modalDetailTimbanganLabel" aria-hidden="true">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailTimbanganLabel">
					Detail Pemeriksaan Timbangan
				</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailTimbangan">
				<div class="text-center py-4">
					<i class="fas fa-spinner fa-spin fa-2x"></i>
					<p class="mt-2">Memuat data...</p>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- LOADING OVERLAY -->
<div id="pageLoading" class="page-loading">
	<div class="spinner-border text-primary" role="status"></div>
	<div class="mt-2 font-weight-bold">Memuat data...</div>
</div>
<script>
	// MODAL DETAIL TIMBANGAN AJAX
	var cacheDetailTimbangan = {};

	$(document).on('click', '.btn-detail-timbangan', function(e) {
		e.preventDefault();

		var url = $(this).data('url');

		$('#modalDetailTimbangan').modal('show');

		if (cacheDetailTimbangan[url]) {
			$('#contentDetailTimbangan').html(cacheDetailTimbangan[url]);
			return;
		}

		$('#contentDetailTimbangan').html(`
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
				cacheDetailTimbangan[url] = response;
				$('#contentDetailTimbangan').html(response);
			},

			error: function() {
				$('#contentDetailTimbangan').html(`
                    <div class="alert alert-danger text-center">
                        Gagal memuat detail Timbangan.
                    </div>
                `);
			}
		});
	});
</script>
<style>
	#dataTable th {
		background-color: #f8f9fc;
	}

	#dataTable th,
	#dataTable td {
		vertical-align: middle;
	}

	.page-loading {
		display: none;
		position: fixed;
		z-index: 99999;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(255, 255, 255, 0.75);
		align-items: center;
		justify-content: center;
		flex-direction: column;
	}
</style>

	<script>
		$(document).ready(function() {

			const baseUrl = '<?= base_url('timbangan') ?>';

			// FILTER TANGGAL DAN SHIFT
			function terapkanFilter() {
				const tanggal = $('#filterTanggal').val();
				const shift = $('#filterShift').val();

				let url = baseUrl;
				const params = [];

				if (tanggal) {
					params.push('tanggal=' + encodeURIComponent(tanggal));
				}

				if (shift) {
					params.push('shift=' + encodeURIComponent(shift));
				}

				if (params.length > 0) {
					url += '?' + params.join('&');
				}

				$('#pageLoading').css('display', 'flex');
				window.location.href = url;
			}

			$('#filterTanggal, #filterShift').on('change', function() {
				terapkanFilter();
			});

			// RESET FILTER
			$('#resetFilterTimbangan').on('click', function() {
				$('#pageLoading').css('display', 'flex');
				window.location.href = baseUrl;
			});

			// VERIFIKASI MASSAL SPV DAN PRODUKSI
			$('.btn-verifikasi-massal').on('click', function() {

				const tanggal = $('#filterTanggal').val();
				const shift = $('#filterShift').val();
				const jenis = $(this).data('jenis');
				const tombol = $(this);

				const namaJenis = jenis === 'spv' ? 'SPV' : 'Produksi';
				const htmlAwal = tombol.html();

				if (!tanggal) {
					Swal.fire({
						icon: 'warning',
						title: 'Tanggal belum dipilih',
						text: 'Silakan pilih tanggal terlebih dahulu.'
					});
					return;
				}

				Swal.fire({
					title: 'Verifikasi Massal ' + namaJenis + '?',
					text: 'Data Timbangan tanggal ' + tanggal +
						(shift ? ' Shift ' + shift : ' semua shift') +
						' yang memenuhi syarat akan diverifikasi oleh ' + namaJenis + '.',
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Ya, verifikasi',
					cancelButtonText: 'Batal'
				}).then((result) => {

					if (!result.isConfirmed) {
						return;
					}

					$.ajax({
						url: '<?= base_url('timbangan/verifikasi_massal'); ?>',
						type: 'POST',
						dataType: 'json',

						data: {
							tanggal: tanggal,
							shift: shift || 'semua',
							jenis_verifikasi: jenis
						},

						beforeSend: function() {
							$('.btn-verifikasi-massal').prop('disabled', true);

							tombol.html(
								'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
							);
						},

						success: function(response) {

							Swal.fire({
								icon: response.status ? 'success' : 'info',
								title: response.status ? 'Berhasil' : 'Informasi',
								text: response.message
							}).then(() => {

								if (response.status) {
									let url = baseUrl + '?tanggal=' +
										encodeURIComponent(tanggal);

									if (shift) {
										url += '&shift=' + encodeURIComponent(shift);
									}

									$('#pageLoading').css('display', 'flex');
									window.location.href = url;
								}
							});
						},

						error: function() {
							Swal.fire({
								icon: 'error',
								title: 'Gagal',
								text: 'Terjadi kesalahan saat memproses verifikasi massal.'
							});
						},

						complete: function() {
							tombol
								.prop('disabled', false)
								.html(htmlAwal);

							$('.btn-verifikasi-massal').prop('disabled', false);
						}
					});
				});
			});

			// LOADING SAAT PINDAH HALAMAN PAGINATION
			$('.pagination a').on('click', function() {
				$('#pageLoading').css('display', 'flex');
			});

		});
	</script>
