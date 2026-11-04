<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Metal Detector</h1>
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
	$tanggal = $tanggal ?? '';
	$boleh_verifikasi = in_array($tipe_user, [0, 1, 2, 9]);
	?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<?php if ($boleh_verifikasi): ?>
					<div class="d-flex align-items-center flex-wrap mb-2">

						<input
							type="date"
							id="filterTanggal"
							class="form-control mr-2 mb-2"
							style="width: 170px;"
							value="<?= html_escape($tanggal); ?>">

						<button
							type="button"
							id="btnVerifikasiMassal"
							class="btn btn-primary mb-2">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>

						<a href="<?= base_url('metal'); ?>"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>

					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('metal/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if ($boleh_verifikasi): ?>
						<a href="<?= base_url('metal/audit'); ?>"
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
							<th>Pukul</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>Fe</th>
							<th>Non Fe</th>
							<th>SUS 304</th>
							<th class="text-center">Status</th>
							<th style="text-align:center;">Action</th>
						</tr>
					</thead>

					<tbody>

						<?php
						$no = (int) ($start ?? 0) + 1;

						foreach ($metal as $val) {

							$datetime = new DateTime($val->date_metal);
							$datetime = $datetime->format('d-m-Y');

							$timing = new DateTime($val->time);
							$timing = $timing->format('H:i');
						?>

							<tr>

								<td class="text-center">
									<?= $no; ?>
								</td>

								<td><?= $datetime; ?></td>

								<td><?= $timing; ?></td>

								<td><?= $val->nama_produk; ?></td>

								<td><?= $val->kode_produksi; ?></td>

								<td class="text-center">
									<?= (
										$val->fe_d === 'terdeteksi' &&
										$val->nonfe_d === 'terdeteksi' &&
										$val->sus_d === 'terdeteksi'
									) ? '✅' : '❌' ?>
								</td>

								<td class="text-center">
									<?= (
										$val->fe_t === 'terdeteksi' &&
										$val->nonfe_t === 'terdeteksi' &&
										$val->sus_t === 'terdeteksi'
									) ? '✅' : '❌' ?>
								</td>

								<td class="text-center">
									<?= (
										$val->fe_b === 'terdeteksi' &&
										$val->nonfe_b === 'terdeteksi' &&
										$val->sus_b === 'terdeteksi'
									) ? '✅' : '❌' ?>
								</td>

								<td class="text-center">
									<?php
									if ($val->status_spv == '1') {
										echo '<span style="color:#28b463;font-weight:bold;font-size:12px;">Verified</span>';
									} elseif ($val->status_spv == '2') {
										echo '<span style="color:red;font-weight:bold;font-size:12px;">Revision</span>';
									} elseif ($val->status_produksi == '1' && $val->status_spv == '0') {
										echo '<span style="color:#28b463;font-weight:bold;font-size:12px;">Approved</span>';
									} elseif ((int) $tipe_user == 3 && $val->status_spv == '0') {
										echo '<span style="color:#99a3a4;font-weight:bold;font-size:12px;">Created</span>';
									} elseif ($val->status_produksi == '0') {
										echo '<span style="color:#f39c12;font-weight:bold;font-size:12px;">Menunggu Verifikasi Produksi</span>';
									}
									?>
								</td>

								<td class="text-center">

									<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
										<a href="<?= base_url('metal/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split">
											<span class="text">Edit</span>
										</a>
									<?php endif; ?>

									<a href="<?= base_url('metal/detail/' . $val->uuid); ?>"
										data-url="<?= base_url('metal/detail/' . $val->uuid . '?popup=1'); ?>"
										class="btn btn-success btn-icon-split btn-detail-metal">
										<span class="text">Detail</span>
									</a>

									<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
										<a href="<?= base_url('metal/verifikasi_produksi/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split"
											onclick="return confirm('Apakah Anda yakin ingin menyetujui data ini?')">
											<span class="text">Setujui</span>
										</a>
									<?php endif; ?>

									<?php if ($boleh_verifikasi): ?>

										<?php if ($val->status_produksi == '1' && $val->status_spv == '0'): ?>
											<a href="<?= base_url('metal/status/' . $val->uuid); ?>"
												class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>

										<?php elseif ($val->status_produksi == '1' && $val->status_spv == '2'): ?>
											<a href="<?= base_url('metal/status/' . $val->uuid); ?>"
												class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi Ulang</span>
											</a>
										<?php endif; ?>

									<?php endif; ?>

								</td>

							</tr>

						<?php
							$no++;
						}
						?>

					</tbody>

				</table>

			</div>

			<?php if ($boleh_verifikasi): ?>

				<br>

				<div class="mb-3">

					<form action="<?= base_url('metal/cetak') ?>"
						method="post"
						class="form-inline">

						<label for="tanggalCetak" class="mr-2 font-weight-bold">
							Pilih Tanggal:
						</label>

						<input type="date"
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
<!-- Modal Detail Metal -->
<div class="modal fade" id="modalDetailMetal" tabindex="-1" role="dialog"
	aria-labelledby="modalDetailMetalLabel" aria-hidden="true">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailMetalLabel">
					Detail Pemeriksaan Metal Detector
				</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailMetal">
				<div class="text-center py-5">
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

<?php if ($boleh_verifikasi): ?>

	<script>
		$(document).ready(function() {

			const baseUrl = "<?= base_url('metal'); ?>";

			// Filter tanggal otomatis
			$('#filterTanggal').on('change', function() {

				const tanggal = $(this).val();

				if (tanggal) {
					window.location.href = baseUrl + '?tanggal=' + encodeURIComponent(tanggal);
				} else {
					window.location.href = baseUrl;
				}

			});

			// Verifikasi massal
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
					text: 'Semua data Metal Detector yang memenuhi syarat pada tanggal ' +
						tanggal + ' akan diverifikasi.',
					icon: 'question',
					showCancelButton: true,
					confirmButtonColor: '#4e73df',
					cancelButtonColor: '#858796',
					confirmButtonText: 'Ya, verifikasi',
					cancelButtonText: 'Batal'
				}).then((result) => {

					if (!result.isConfirmed) {
						return;
					}

					$.ajax({
						url: '<?= base_url('metal/verifikasi_massal') ?>',
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

							if (response.status) {

								Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: response.message
								}).then(() => {
									window.location.href = baseUrl +
										'?tanggal=' + encodeURIComponent(tanggal);
								});

							} else {

								Swal.fire({
									icon: 'info',
									title: 'Informasi',
									text: response.message
								});

							}

						},

						error: function(xhr) {

							Swal.fire({
								icon: 'error',
								title: 'Gagal',
								text: xhr.responseJSON?.message ||
									'Terjadi kesalahan saat melakukan verifikasi.'
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

		});
	</script>

<?php endif; ?>

<script>
	$(document).ready(function() {

		const cacheDetailMetal = {};

		$(document).on('click', '.btn-detail-metal', function(e) {
			e.preventDefault();

			const url = $(this).data('url');

			$('#modalDetailMetal').modal('show');

			if (cacheDetailMetal[url]) {
				$('#contentDetailMetal').html(cacheDetailMetal[url]);
				return;
			}

			$('#contentDetailMetal').html(`
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Memuat data...</p>
                </div>
            `);

			$.ajax({
				url: url,
				type: 'GET',
				dataType: 'html',

				success: function(response) {
					cacheDetailMetal[url] = response;
					$('#contentDetailMetal').html(response);
				},

				error: function() {
					$('#contentDetailMetal').html(`
                        <div class="alert alert-danger text-center">
                            Gagal memuat detail Metal Detector.
                        </div>
                    `);
				}
			});
		});

	});
</script>
