<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Pemeriksaan Kekuatan Magnet Trap</h1>
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
	?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<!-- FILTER DAN TOMBOL -->
			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<?php if ($boleh_verifikasi): ?>
					<div class="d-flex align-items-center flex-wrap mb-2">

						<input
							type="date"
							id="filterTanggal"
							class="form-control mr-2 mb-2"
							style="width: 170px;"
							value="<?= html_escape($filter_tanggal) ?>">

						<button
							type="button"
							id="btnVerifikasiMassal"
							class="btn btn-primary mb-2">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>

						<button
							type="button"
							id="resetFilterKekuatanMagnet"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</button>

					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('kekuatanmagnet/tambah'); ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if ($boleh_verifikasi): ?>
						<a href="<?= base_url('kekuatanmagnet/audit'); ?>"
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
							<th>Tanggal</th>
							<th>Nama Alat</th>
							<th>Nilai Pengukuran</th>
							<th>Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;

						if (!empty($kekuatanmagnet)):
							foreach ($kekuatanmagnet as $val):
								$datetime = new DateTime($val->date);
								$datetime = $datetime->format('d-m-Y');
						?>
								<tr>
									<td class="text-center"><?= $no; ?></td>
									<td><?= $datetime; ?></td>
									<td><?= $val->nama_alat; ?></td>
									<td><?= $val->nilai . " Gauss "; ?></td>

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
												<a href="<?= base_url('kekuatanmagnet/edit/' . $val->uuid); ?>"
													class="btn btn-warning btn-icon-split mb-1">
													<span class="text">Edit</span>
												</a>
											<?php endif; ?>

											<a href="<?= base_url('kekuatanmagnet/detail/' . $val->uuid); ?>"
												class="btn btn-success btn-icon-split mb-1 btn-detail-kekuatanmagnet"
												data-url="<?= base_url('kekuatanmagnet/detail/' . $val->uuid . '?popup=1'); ?>">
												<span class="text">Detail</span>
											</a>

											<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
												<a href="<?= base_url('kekuatanmagnet/verifikasi_produksi/' . $val->uuid); ?>"
													class="btn btn-primary btn-icon-split mb-1"
													onclick="return confirm('Apakah pemeriksaan ini yakin disetujui?');">
													<span class="text">Setujui</span>
												</a>
											<?php endif; ?>

											<?php if ($boleh_verifikasi): ?>

												<?php if (
													$val->status_produksi == '1' &&
													$val->status_spv == '0'
												): ?>
													<a href="<?= base_url('kekuatanmagnet/status/' . $val->uuid); ?>"
														class="btn btn-info btn-icon-split mb-1">
														<span class="text">Verifikasi</span>
													</a>

												<?php elseif (
													$val->status_produksi == '1' &&
													$val->status_spv == '2'
												): ?>
													<a href="<?= base_url('kekuatanmagnet/status/' . $val->uuid); ?>"
														class="btn btn-warning btn-icon-split mb-1">
														<span class="text">Verifikasi Ulang</span>
													</a>
												<?php endif; ?>

											<?php endif; ?>

										</div>
									</td>
								</tr>
							<?php
								$no++;
							endforeach;
						else:
							?>
							<tr>
								<td colspan="6" class="text-center">
									Tidak ada data Kekuatan Magnet.
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
					<form action="<?= base_url('kekuatanmagnet/cetak') ?>"
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

<!-- LOADING OVERLAY -->
<div id="pageLoading" class="page-loading">
	<div class="spinner-border text-primary" role="status"></div>
	<div class="mt-2 font-weight-bold">Memuat data...</div>
</div>
<!-- MODAL DETAIL KEKUATAN MAGNET -->
<div class="modal fade" id="modalDetailKekuatanMagnet" tabindex="-1"
	role="dialog" aria-labelledby="modalDetailKekuatanMagnetLabel" aria-hidden="true">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailKekuatanMagnetLabel">
					Detail Pemeriksaan Kekuatan Magnet
				</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailKekuatanMagnet">
				<div class="text-center py-4">
					<i class="fas fa-spinner fa-spin fa-2x"></i>
					<p class="mt-2">Memuat data...</p>
				</div>
			</div>

		</div>
	</div>
</div>
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

<?php if ($boleh_verifikasi): ?>
	<script>
		$(document).ready(function() {

			const baseUrl = '<?= base_url('kekuatanmagnet') ?>';

			// FILTER OTOMATIS SAAT TANGGAL BERUBAH
			$('#filterTanggal').on('change', function() {
				const tanggal = $(this).val();

				let url = baseUrl;

				if (tanggal) {
					url += '?tanggal=' + encodeURIComponent(tanggal);
				}

				$('#pageLoading').css('display', 'flex');
				window.location.href = url;
			});

			// RESET FILTER
			$('#resetFilterKekuatanMagnet').on('click', function() {
				$('#pageLoading').css('display', 'flex');
				window.location.href = baseUrl;
			});

			// VERIFIKASI MASSAL SPV
			$('#btnVerifikasiMassal').on('click', function() {

				const tanggal = $('#filterTanggal').val();

				if (!tanggal) {
					Swal.fire({
						icon: 'warning',
						title: 'Tanggal belum dipilih',
						text: 'Silakan pilih tanggal terlebih dahulu.'
					});
					return;
				}

				Swal.fire({
					title: 'Verifikasi semua data?',
					text: 'Data Kekuatan Magnet pada tanggal ' + tanggal +
						' dengan status produksi sudah disetujui dan status SPV Created atau Revision akan diverifikasi.',
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Ya, verifikasi',
					cancelButtonText: 'Batal'
				}).then((result) => {

					if (!result.isConfirmed) {
						return;
					}

					$.ajax({
						url: '<?= base_url('kekuatanmagnet/verifikasi_massal'); ?>',
						type: 'POST',
						dataType: 'json',
						data: {
							tanggal: tanggal
						},
						beforeSend: function() {
							$('#btnVerifikasiMassal')
								.prop('disabled', true)
								.html('<i class="fas fa-spinner fa-spin"></i> Memproses...');
						},
						success: function(response) {
							Swal.fire({
								icon: response.status ? 'success' : 'info',
								title: response.status ? 'Berhasil' : 'Informasi',
								text: response.message
							}).then(() => {
								if (response.status) {
									$('#pageLoading').css('display', 'flex');
									window.location.href = baseUrl +
										'?tanggal=' + encodeURIComponent(tanggal);
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
							$('#btnVerifikasiMassal')
								.prop('disabled', false)
								.html('<i class="fas fa-check-double"></i> Verifikasi');
						}
					});
				});
			});

			// LOADING SAAT PINDAH HALAMAN PAGINATION
			$('.pagination a').on('click', function() {
				$('#pageLoading').css('display', 'flex');
			});

		});

		// MODAL DETAIL KEKUATAN MAGNET AJAX
		var cacheDetailKekuatanMagnet = {};

		$(document).on('click', '.btn-detail-kekuatanmagnet', function(e) {
			e.preventDefault();

			var url = $(this).data('url');

			$('#modalDetailKekuatanMagnet').modal('show');

			if (cacheDetailKekuatanMagnet[url]) {
				$('#contentDetailKekuatanMagnet').html(
					cacheDetailKekuatanMagnet[url]
				);
				return;
			}

			$('#contentDetailKekuatanMagnet').html(`
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
					cacheDetailKekuatanMagnet[url] = response;
					$('#contentDetailKekuatanMagnet').html(response);
				},

				error: function() {
					$('#contentDetailKekuatanMagnet').html(`
                <div class="alert alert-danger text-center">
                    Gagal memuat detail Kekuatan Magnet.
                </div>
            `);
				}
			});
		});
	</script>
<?php endif; ?>
