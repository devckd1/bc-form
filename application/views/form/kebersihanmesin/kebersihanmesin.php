<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Kebersihan dan Sanitasi Setelah Perbaikan Mesin</h1>
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
							name="tanggal"
							class="form-control mr-2 mb-2"
							style="width: 170px;"
							value="<?= htmlspecialchars($filter_tanggal ?? '', ENT_QUOTES, 'UTF-8'); ?>"
							required>

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

						<a href="<?= base_url('kebersihanmesin'); ?>"
							class="btn btn-secondary mb-2">

							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>

					</div>

				<?php endif; ?>


				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">
					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('kebersihanmesin/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('kebersihanmesin/audit'); ?>"
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

					Hanya data yang sudah diverifikasi Produksi
					pada tanggal terpilih yang akan diproses.

				</small>

			<?php endif; ?>

			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Mesin / Peralatan</th>
							<th>Jenis Perbaikan</th>
							<th>Area</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;
						foreach ($kebersihanmesin as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime . " / " . $val->shift; ?></td>
								<td><?= $val->mesin; ?></td>
								<td><?= $val->perbaikan; ?></td>
								<td><?= $val->area; ?></td>
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
											<a href="<?= base_url('kebersihanmesin/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split mb-1">
												<span class="text">Edit</span>
											</a>
										<?php endif; ?>

										<a href="<?= base_url('kebersihanmesin/detail/' . $val->uuid); ?>"
											data-url="<?= base_url('kebersihanmesin/detail/' . $val->uuid . '?popup=1'); ?>"
											class="btn btn-success btn-icon-split mb-1 btn-detail-kebersihanmesin">
											<span class="text">Detail</span>
										</a>

										<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
											<a href="<?= base_url('kebersihanmesin/verifikasi_produksi/' . $val->uuid); ?>"
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
												<a href="<?= base_url('kebersihanmesin/status/' . $val->uuid); ?>"
													class="btn btn-info btn-icon-split mb-1">
													<span class="text">Verifikasi</span>
												</a>

											<?php elseif (
												$val->status_produksi == '1' &&
												$val->status_spv == '2'
											): ?>
												<a href="<?= base_url('kebersihanmesin/status/' . $val->uuid); ?>"
													class="btn btn-warning btn-icon-split mb-1">
													<span class="text">Verifikasi Ulang</span>
												</a>
											<?php endif; ?>

										<?php endif; ?>

									</div>
									<!-- <a href="<?= base_url('kebersihanmesin/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
					<form action="<?= base_url('kebersihanmesin/cetak') ?>" method="post" class="form-inline">
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
<!-- Modal Detail Kebersihan Mesin -->
<div class="modal fade" id="modalDetailKebersihanMesin" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Kebersihan Mesin</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailKebersihanMesin">
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

		// Filter tanggal otomatis
		$('#filter_tanggal').on('change', function() {

			var tanggal = $(this).val();

			if (tanggal) {
				window.location.href =
					"<?= base_url('kebersihanmesin'); ?>?tanggal=" +
					encodeURIComponent(tanggal);
			}
		});


		// Verifikasi massal SPV dan Produksi
		$('.btnVerifikasiMassal').on('click', function() {

			var tombol = $(this);
			var jenis = tombol.data('jenis');
			var tanggal = $('#filter_tanggal').val();

			var namaJenis = jenis === 'spv' ? 'SPV' : 'Produksi';

			if (!tanggal) {
				Swal.fire(
					'Perhatian',
					'Silakan pilih tanggal terlebih dahulu.',
					'warning'
				);
				return;
			}

			var pesan = jenis === 'spv' ?
				'Data Kebersihan Mesin yang sudah diverifikasi Produksi pada tanggal tersebut akan diproses oleh SPV.' :
				'Data Kebersihan Mesin dengan status Produksi Created pada tanggal tersebut akan diverifikasi oleh Produksi.';

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
					url: "<?= base_url('kebersihanmesin/verifikasi_massal'); ?>",
					type: 'POST',
					dataType: 'json',

					data: {
						tanggal: tanggal,
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

						var pesanError =
							'Terjadi kesalahan saat memproses verifikasi massal.';

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

	// Modal Detail Kebersihan Mesin
	var cacheDetailKebersihanMesin = {};

	$(document).on('click', '.btn-detail-kebersihanmesin', function(e) {
		e.preventDefault();

		var url = $(this).data('url');

		$('#modalDetailKebersihanMesin').modal('show');

		if (cacheDetailKebersihanMesin[url]) {
			$('#contentDetailKebersihanMesin').html(
				cacheDetailKebersihanMesin[url]
			);
			return;
		}

		$('#contentDetailKebersihanMesin').html(`
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
				cacheDetailKebersihanMesin[url] = response;

				$('#contentDetailKebersihanMesin').html(response);
			},

			error: function() {
				$('#contentDetailKebersihanMesin').html(`
                <div class="alert alert-danger text-center">
                    Gagal memuat detail Kebersihan Mesin.
                </div>
            `);
			}
		});
	});
</script>
