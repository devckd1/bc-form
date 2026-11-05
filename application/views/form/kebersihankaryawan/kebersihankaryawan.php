<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Kebersihan Karyawan</h1>
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

				$akses_spv = in_array($tipe_user, [0, 1, 2, 9], true);
				$akses_produksi = in_array($tipe_user, [0, 1, 3, 9], true);
				?>

				<?php if ($akses_spv || $akses_produksi): ?>
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

						<?php if ($akses_spv): ?>
							<button type="button"
								class="btn btn-primary mr-1 mb-2 btnVerifikasiMassal"
								data-jenis="spv">

								<i class="fas fa-check-double mr-1"></i>
								Verifikasi Massal
							</button>
						<?php endif; ?>

						<?php if ($akses_produksi): ?>
							<button type="button"
								class="btn btn-primary mr-1 mb-2 btnVerifikasiMassal"
								data-jenis="produksi">

								<i class="fas fa-industry mr-1"></i>
								Verifikasi Massal
							</button>
						<?php endif; ?>

						<a href="<?= base_url('kebersihankaryawan'); ?>"
							class="btn btn-secondary mb-2">

							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>

					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('kebersihankaryawan/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('kebersihankaryawan/audit'); ?>"
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
				<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 40%; text-align: left; font-family: Arial, sans-serif; font-size: 14px;">
					<thead style="background-color: #f2f2f2;">
						<tr>
							<th colspan="2" style="padding: 10px; background-color: #ADD8E6; color: gray;">Keterangan Pemeriksaan</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1. Seragam</td>
							<td>5. Perhiasan</td>
						</tr>
						<tr>
							<td>2. Apron</td>
							<td>6. Masker</td>
						</tr>
						<tr>
							<td>3. Tangan dan Kuku</td>
							<td>7. Topi / Hairnet</td>
						</tr>
						<tr>
							<td>4. Kosmetik</td>
							<td>8. Sepatu Kerja</td>
						</tr>
					</tbody>
				</table>
				<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; width: 30%; text-align: center; font-family: Arial, sans-serif; font-size: 14px;">
					<thead style="background-color: #f2f2f2;">
						<tr>
							<th colspan="2" style="padding: 10px; background-color: #ADD8E6; color: gray;">Simbol Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>✔️</td>
							<td>Ok</td>
						</tr>
						<tr>
							<td>❌</td>
							<td>Tidak Ok</td>
						</tr>
						<tr>
							<td>−</td>
							<td>Tidak ada / Tidak digunakan</td>
						</tr>
					</tbody>
				</table>
			</div>

			<br>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th width="20px" class="text-center" rowspan="2">No</th>
							<th rowspan="2">Tanggal</th>
							<th rowspan="2">Nama</th>
							<th rowspan="2">Bagian</th>
							<th colspan="8" class="text-center">Kebersihan</th>
							<th rowspan="2">Tindakan Koreksi</th>
							<th rowspan="2" class="text-center">Status</th>
							<th rowspan="2" class="text-center">Action</th>
						</tr>
						<tr>
							<th>1</th>
							<th>2</th>
							<th>3</th>
							<th>4</th>
							<th>5</th>
							<th>6</th>
							<th>7</th>
							<th>8</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;
						foreach ($kebersihankaryawan as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->nama; ?></td>
								<td><?= $val->bagian; ?></td>
								<td class="text-center">
									<?= ($val->seragam == 'ok') ? '✔️' : (($val->seragam == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->apron == 'ok') ? '✔️' : (($val->apron == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->tangan_kuku == 'ok') ? '✔️' : (($val->tangan_kuku == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->kosmetik == 'ok') ? '✔️' : (($val->kosmetik == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->perhiasan == 'ok') ? '✔️' : (($val->perhiasan == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->masker == 'ok') ? '✔️' : (($val->masker == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->topi_hairnet == 'ok') ? '✔️' : (($val->topi_hairnet == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td class="text-center">
									<?= ($val->sepatu == 'ok') ? '✔️' : (($val->sepatu == 'tidak oke') ? '❌' : '−'); ?>
								</td>
								<td><?= $val->tindakan; ?></td>
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
								<td class="text-center">
									<div class="action-buttons">

										<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
											<a href="<?= base_url('kebersihankaryawan/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split mb-1">
												<span class="text">Edit</span>
											</a>
										<?php endif; ?>

										<a href="<?= base_url('kebersihankaryawan/detail/' . $val->uuid); ?>"
											data-url="<?= base_url('kebersihankaryawan/detail/' . $val->uuid . '?popup=1'); ?>"
											class="btn btn-success btn-icon-split mb-1 btn-detail-kebersihankaryawan">
											<span class="text">Detail</span>
										</a>

										<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
											<a href="<?= base_url('kebersihankaryawan/verifikasi_produksi/' . $val->uuid); ?>"
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
												<a href="<?= base_url('kebersihankaryawan/status/' . $val->uuid); ?>"
													class="btn btn-info btn-icon-split mb-1">
													<span class="text">Verifikasi</span>
												</a>

											<?php elseif (
												$val->status_produksi == '1' &&
												$val->status_spv == '2'
											): ?>
												<a href="<?= base_url('kebersihankaryawan/status/' . $val->uuid); ?>"
													class="btn btn-warning btn-icon-split mb-1">
													<span class="text">Verifikasi Ulang</span>
												</a>
											<?php endif; ?>

										<?php endif; ?>

									</div>

									<!-- <a href="<?= base_url('kebersihankaryawan/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<div class="form-group">
					<form action="<?= base_url('kebersihankaryawan/cetak') ?>" method="post" class="form-inline">

						<label class="mr-2 font-weight-bold">
							Pilih Tanggal:
						</label>

						<input type="date"
							name="tanggal"
							class="form-control mr-2"
							required>

						<button type="submit" class="btn btn-success mr-2">
							<i class="fas fa-print fa-sm text-white-50"></i>
							Cetak PDF
						</button>

						<?php if ($this->session->userdata('plant') != '651ac623-5e48-44cc-b2f6-5d622603f53c') : ?>
							<button type="submit"
								formaction="<?= base_url('form/pemeriksaan_gabungan/cetak') ?>"
								class="btn btn-primary">
								<i class="fas fa-file-pdf fa-sm text-white-50"></i>
								Cetak Gabungan
							</button>
						<?php endif; ?>

					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="modalDetailKebersihanKaryawan" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Kebersihan Karyawan</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>
			<div class="modal-body" id="contentDetailKebersihanKaryawan">
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
		function filterKebersihanKaryawan() {

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

			window.location.href = "<?= base_url('kebersihankaryawan'); ?>" +
				(query ? '?' + query : '');
		}

		$('#filter_tanggal, #filter_shift').on('change', function() {
			filterKebersihanKaryawan();
		});


		// Verifikasi massal SPV dan Produksi
		$('.btnVerifikasiMassal').on('click', function() {

			var tombol = $(this);
			var jenis = tombol.data('jenis');

			var tanggal = $('#filter_tanggal').val();
			var shift = $('#filter_shift').val();

			var namaJenis = jenis === 'spv' ? 'SPV' : 'Produksi';

			if (!tanggal) {
				Swal.fire(
					'Perhatian',
					'Pilih tanggal terlebih dahulu.',
					'warning'
				);
				return;
			}

			var pesan = jenis === 'spv' ?
				'Data Kebersihan Karyawan yang sudah diverifikasi Produksi pada tanggal dan shift terpilih akan diverifikasi oleh SPV.' :
				'Data Kebersihan Karyawan dengan status Produksi Created pada tanggal dan shift terpilih akan diverifikasi oleh Produksi.';

			Swal.fire({
				title: 'Verifikasi Massal ' + namaJenis + '?',
				text: pesan,
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Verifikasi',
				cancelButtonText: 'Batal'
			}).then(function(result) {

				if (!result.isConfirmed) {
					return;
				}

				var tombolAwal = tombol.html();

				tombol.prop('disabled', true);
				tombol.html(
					'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
				);

				$.ajax({
					url: "<?= base_url('kebersihankaryawan/verifikasi_massal'); ?>",
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
							tombol.html(tombolAwal);
						}
					},

					error: function(xhr) {

						var pesanError = 'Terjadi kesalahan saat memproses verifikasi massal.';

						if (xhr.responseJSON && xhr.responseJSON.message) {
							pesanError = xhr.responseJSON.message;
						}

						Swal.fire(
							'Error',
							pesanError,
							'error'
						);

						tombol.prop('disabled', false);
						tombol.html(tombolAwal);
					}
				});

			});
		});

	});

	// Modal Detail AJAX Kebersihan Karyawan
	var cacheDetailKebersihanKaryawan = {};

	$(document).on('click', '.btn-detail-kebersihankaryawan', function(e) {
		e.preventDefault();

		var url = $(this).data('url');
		var modal = $('#modalDetailKebersihanKaryawan');
		var content = $('#contentDetailKebersihanKaryawan');

		modal.modal('show');

		if (cacheDetailKebersihanKaryawan[url]) {
			content.html(cacheDetailKebersihanKaryawan[url]);
			return;
		}

		content.html(`
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
				cacheDetailKebersihanKaryawan[url] = response;
				content.html(response);
			},
			error: function() {
				content.html(`
                <div class="alert alert-danger text-center">
                    Gagal memuat detail Kebersihan Karyawan.
                </div>
            `);
			}
		});
	});
</script>
