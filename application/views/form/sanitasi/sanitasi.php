<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Sanitasi</h1>
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

				<?php
				$tipe_user = (int) $this->session->userdata('tipe_user');
				?>
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

					<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>
						<button
							type="button"
							class="btn btn-primary mr-1 mb-2 btnVerifikasiMassal"
							data-jenis="spv">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 3, 9], true)): ?>
						<button
							type="button"
							class="btn btn-primary mb-2 btnVerifikasiMassal"
							data-jenis="produksi">
							<i class="fas fa-industry mr-1"></i>
							Verifikasi Massal
						</button>
					<?php endif; ?>

					<a href="<?= base_url('sanitasi'); ?>"
						class="btn btn-secondary ml-2 mb-2">

						<i class="fas fa-sync-alt mr-1"></i>
						Reset
					</a>

				</div>

				<!-- Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('sanitasi/tambah'); ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('sanitasi/audit'); ?>"
							class="btn shadow-sm"
							style="background-color: #5a3298; color: #fff;">
							<i class="fas fa-clipboard-check mr-1"></i>
							Mode Audit
						</a>
					<?php endif; ?>

				</div>
			</div>

			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Shift</th>
							<th>Waktu</th>
							<th class="text-center">Hasil Pemeriksaan</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;
						foreach ($sanitasi as $val) {
							$datetime = (new DateTime($val->date))->format('d-m-Y');
							$timing = (new DateTime($val->waktu))->format('H:i');

							$result = json_decode($val->area, true);
						?>
							<tr>
								<td class="text-center"><?= $no++; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->shift; ?></td>
								<td><?= $timing; ?></td>

								<td>
									<table class="table table-sm table-bordered mb-0">
										<thead style="background-color:#2E86C1; color:black; text-align:center;">
											<tr>
												<th width="30%">Area</th>
												<th width="20%">Aktual</th>
												<th width="30%">Gambar</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($result) && is_array($result)): ?>
												<?php foreach ($result as $row): ?>
													<tr>
														<td><?= htmlspecialchars($row['sub_area'] ?? '-'); ?></td>
														<td style="text-align:center;"><?= htmlspecialchars($row['aktual'] ?? '-'); ?></td>
														<td style="text-align:center;">
															<?php if (!empty($row['gambar'])): ?>
																<a href="<?= base_url('uploads/sanitasi/' . $row['gambar']); ?>" target="_blank">Lihat Gambar</a>
															<?php else: ?>
																<span class="text-muted">Tidak ada</span>
															<?php endif ?>
														</td>
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
								<!-- Status SPV -->
								<td class="text-center">
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

								<!-- Aksi -->
								<td class="text-center">
									<div class="action-buttons">

										<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
											<a href="<?= base_url('sanitasi/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split mb-1">
												<span class="text">Edit</span>
											</a>
										<?php endif; ?>

										<a href="<?= base_url('sanitasi/detail/' . $val->uuid); ?>"
											class="btn btn-success btn-icon-split mb-1 btn-detail-sanitasi"
											data-url="<?= base_url('sanitasi/detail/' . $val->uuid . '?popup=1'); ?>">
											<span class="text">Detail</span>
										</a>

										<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
											<a href="<?= base_url('sanitasi/verifikasi_produksi/' . $val->uuid); ?>"
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
												<a href="<?= base_url('sanitasi/status/' . $val->uuid); ?>"
													class="btn btn-info btn-icon-split mb-1">
													<span class="text">Verifikasi</span>
												</a>

											<?php elseif (
												$val->status_produksi == '1' &&
												$val->status_spv == '2'
											): ?>
												<a href="<?= base_url('sanitasi/status/' . $val->uuid); ?>"
													class="btn btn-warning btn-icon-split mb-1">
													<span class="text">Verifikasi Ulang</span>
												</a>
											<?php endif; ?>

										<?php endif; ?>

									</div>
									<!-- <a href="<?= base_url('sanitasi/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <span class="text">Delete</span>
                                </a>  -->
								</td>
							</tr>
						<?php } ?>
					</tbody>

				</table>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<form action="<?= base_url('sanitasi/cetak') ?>" method="post" target="_blank" class="mb-3">

					<div class="form-row align-items-end">

						<div class="col-auto">
							<label for="date" class="font-weight-bold">
								Pilih Tanggal
							</label>
							<input type="date"
								name="date"
								id="date"
								class="form-control"
								required>
						</div>

						<div class="col-auto">
							<button type="submit" class="btn btn-success">
								<i class="fas fa-print fa-sm"></i>
								Cetak PDF
							</button>
						</div>

						<?php if ($this->session->userdata('plant') != '651ac623-5e48-44cc-b2f6-5d622603f53c') : ?>
							<div class="col-auto">
								<button type="submit"
									formaction="<?= base_url('form/pemeriksaan_gabungan/cetak') ?>"
									class="btn btn-primary">
									<i class="fas fa-file-pdf fa-sm"></i>
									Cetak Gabungan
								</button>
							</div>
						<?php endif; ?>

					</div>

				</form>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="modalDetailSanitasi" tabindex="-1"
	role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title">Detail Pemeriksaan Sanitasi</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailSanitasi">
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
</style>
<script>
	$(document).ready(function() {

		// Filter tanggal dan shift
		function filterSanitasi() {

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

			window.location.href =
				"<?= base_url('sanitasi'); ?>" +
				(query ? '?' + query : '');
		}

		$('#filter_tanggal, #filter_shift').on('change', function() {
			filterSanitasi();
		});


		// Verifikasi massal SPV dan Produksi
		$('.btnVerifikasiMassal').on('click', function() {

			var tombol = $(this);
			var jenis = tombol.data('jenis');

			var tanggal = $('#filter_tanggal').val();
			var shift = $('#filter_shift').val();

			if (!tanggal) {
				Swal.fire(
					'Perhatian',
					'Pilih tanggal terlebih dahulu.',
					'warning'
				);
				return;
			}

			var namaJenis = jenis === 'produksi' ?
				'Produksi' :
				'SPV';

			var keterangan = jenis === 'produksi' ?
				'Data Sanitasi dengan status Produksi Created pada tanggal dan shift terpilih akan diproses.' :
				'Data Sanitasi dengan status SPV Created atau Revision pada tanggal dan shift terpilih akan diverifikasi.';

			Swal.fire({

				title: 'Verifikasi Massal ' + namaJenis + '?',

				text: keterangan,

				icon: 'question',

				showCancelButton: true,

				confirmButtonText: 'Ya, Verifikasi',

				cancelButtonText: 'Batal'

			}).then(function(result) {

				if (!result.isConfirmed) {
					return;
				}

				var teksTombol = jenis === 'produksi' ?
					'Verifikasi Massal Produksi' :
					'Verifikasi Massal SPV';

				tombol.prop('disabled', true);

				tombol.html(
					'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
				);

				$.ajax({

					url: "<?= base_url('sanitasi/verifikasi_massal'); ?>",

					type: 'POST',

					dataType: 'json',

					data: {
						tanggal: tanggal,
						shift: shift || 'semua',
						jenis_verifikasi: jenis
					},

					success: function(response) {

						if (response.success) {

							Swal.fire({

								title: 'Berhasil',

								text: response.message +
									' Jumlah data: ' +
									response.updated,

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
								'<i class="fas fa-check-double mr-1"></i> ' +
								teksTombol
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
							'<i class="fas fa-check-double mr-1"></i> ' +
							teksTombol
						);
					}
				});

			});

		});

	});

	// Modal Detail Sanitasi AJAX
	let cacheDetailSanitasi = {};

	$(document).on('click', '.btn-detail-sanitasi', function(e) {

		e.preventDefault();

		let url = $(this).data('url');

		$('#modalDetailSanitasi').modal('show');

		if (cacheDetailSanitasi[url]) {
			$('#contentDetailSanitasi').html(cacheDetailSanitasi[url]);
			return;
		}

		$('#contentDetailSanitasi').html(`
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
				cacheDetailSanitasi[url] = response;
				$('#contentDetailSanitasi').html(response);
			},

			error: function() {
				$('#contentDetailSanitasi').html(`
                <div class="alert alert-danger">
                    Gagal memuat detail pemeriksaan Sanitasi.
                </div>
            `);
			}
		});

	});
</script>
