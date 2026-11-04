<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Monitoring False Rejection</h1>
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
	$filter_tanggal = $filter_tanggal ?? '';
	$filter_shift = $filter_shift ?? '';
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
							value="<?= html_escape($filter_tanggal); ?>">

						<select
							id="filterShift"
							class="form-control mr-2 mb-2"
							style="width: 140px;">

							<option value="" <?= $filter_shift == '' ? 'selected' : ''; ?>>
								Semua Shift
							</option>

							<option value="1" <?= $filter_shift == '1' ? 'selected' : ''; ?>>
								Shift 1
							</option>

							<option value="2" <?= $filter_shift == '2' ? 'selected' : ''; ?>>
								Shift 2
							</option>

							<option value="3" <?= $filter_shift == '3' ? 'selected' : ''; ?>>
								Shift 3
							</option>

						</select>

						<button
							type="button"
							id="btnVerifikasiMassal"
							class="btn btn-primary mb-2">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>

						<a href="<?= base_url('falserejection'); ?>"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>

					</div>
				<?php endif; ?>

				<!-- KANAN: Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if ($boleh_verifikasi): ?>
						<a href="<?= base_url('falserejection/audit'); ?>"
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

					<thead class="text-center">
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal Produksi</th>
							<th>Tanggal Monitoring / Shift</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>Jenis Kontaminan</th>
							<th class="text-center">Status</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!empty($falserejection)): ?>

							<?php
							$no = (int) ($start ?? 0) + 1;

							foreach ($falserejection as $val) {

								$datetime = new DateTime($val->date_metal);
								$datetime = $datetime->format('d-m-Y');

								$datetime2 = new DateTime($val->date_false_rejection);
								$datetime2 = $datetime2->format('d-m-Y');
							?>

								<tr>

									<td class="text-center">
										<?= $no; ?>
									</td>

									<td><?= $datetime; ?></td>

									<td>
										<?= $datetime2 . ' / ' . $val->shift_monitoring; ?>
									</td>

									<td><?= $val->nama_produk; ?></td>

									<td><?= $val->kode_produksi; ?></td>

									<td><?= $val->jenis_kontaminasi; ?></td>

									<td class="text-center">
										<?php
										if ($val->status_spv_false == 0) {
											echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
										} elseif ($val->status_spv_false == 1) {
											echo '<span style="color: #28b463; font-weight: bold;">Verified</span>';
										} elseif ($val->status_spv_false == 2) {
											echo '<span style="color: red; font-weight: bold;">Revision</span>';
										}
										?>
									</td>

									<td class="text-center">

										<?php if ($boleh_verifikasi): ?>

											<a href="<?= base_url('falserejection/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split mb-1">
												<span class="text">Edit</span>
											</a>

											<a href="<?= base_url('falserejection/status/' . $val->uuid); ?>"
												class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>

										<?php endif; ?>

										<a href="<?= base_url('falserejection/detail/' . $val->uuid); ?>"
											data-url="<?= base_url('falserejection/detail/' . $val->uuid . '?popup=1'); ?>"
											class="btn btn-success btn-icon-split mb-1 btn-detail-falserejection">
											<span class="text">Detail</span>
										</a>

									</td>

								</tr>

							<?php
								$no++;
							}
							?>

						<?php else: ?>

							<tr>
								<td colspan="8" class="text-center">
									Tidak ada data False Rejection.
								</td>
							</tr>

						<?php endif; ?>

					</tbody>

				</table>

			</div>

			<?php if (isset($pagination)): ?>
				<div class="mt-3">
					<?= $pagination; ?>
				</div>
			<?php endif; ?>

			<?php if ($boleh_verifikasi): ?>

				<br>
				<hr>

				<div class="form-group">

					<form action="<?= base_url('falserejection/cetak') ?>"
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
<!-- Modal Detail False Rejection -->
<div class="modal fade" id="modalDetailFalseRejection" tabindex="-1"
	role="dialog" aria-labelledby="modalDetailFalseRejectionLabel" aria-hidden="true">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailFalseRejectionLabel">
					Detail Monitoring False Rejection
				</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailFalseRejection">
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
<script>
	$(document).ready(function() {

		const cacheDetailFalseRejection = {};

		$(document).on('click', '.btn-detail-falserejection', function(e) {
			e.preventDefault();

			const url = $(this).data('url');

			$('#modalDetailFalseRejection').modal('show');

			if (cacheDetailFalseRejection[url]) {
				$('#contentDetailFalseRejection').html(
					cacheDetailFalseRejection[url]
				);
				return;
			}

			$('#contentDetailFalseRejection').html(`
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
					cacheDetailFalseRejection[url] = response;
					$('#contentDetailFalseRejection').html(response);
				},

				error: function() {
					$('#contentDetailFalseRejection').html(`
                        <div class="alert alert-danger text-center">
                            Gagal memuat detail False Rejection.
                        </div>
                    `);
				}
			});

		});

	});
</script>
<?php if ($boleh_verifikasi): ?>

	<script>
		$(document).ready(function() {

			const baseUrl = "<?= base_url('falserejection'); ?>";

			// Filter tanggal dan shift otomatis
			function applyFilter() {

				const tanggal = $('#filterTanggal').val();
				const shift = $('#filterShift').val();

				const params = new URLSearchParams();

				if (tanggal) {
					params.set('tanggal', tanggal);
				}

				if (shift) {
					params.set('shift', shift);
				}

				const query = params.toString();

				window.location.href = baseUrl + (query ? '?' + query : '');
			}

			$('#filterTanggal, #filterShift').on('change', function() {
				applyFilter();
			});

			// Verifikasi massal
			$('#btnVerifikasiMassal').on('click', function() {

				const tanggal = $('#filterTanggal').val();
				const shift = $('#filterShift').val();

				if (!tanggal) {
					Swal.fire({
						icon: 'warning',
						title: 'Pilih Tanggal',
						text: 'Silakan pilih tanggal terlebih dahulu.'
					});

					return;
				}

				const keteranganShift = shift ?
					'Shift ' + shift :
					'Semua Shift';

				Swal.fire({
					title: 'Verifikasi Massal?',
					text: 'Data False Rejection pada tanggal ' + tanggal +
						' (' + keteranganShift + ') yang memenuhi status produksi dan berstatus SPV Created atau Revision akan diverifikasi.',
					icon: 'question',
					showCancelButton: true,
					confirmButtonColor: '#4e73df',
					cancelButtonColor: '#858796',
					confirmButtonText: 'Ya, Verifikasi',
					cancelButtonText: 'Batal'
				}).then((result) => {

					if (!result.isConfirmed) {
						return;
					}

					$.ajax({

						url: "<?= base_url('falserejection/verifikasi_massal'); ?>",
						type: "POST",
						dataType: "json",

						data: {
							tanggal: tanggal,
							shift: shift
						},

						beforeSend: function() {

							$('#btnVerifikasiMassal')
								.prop('disabled', true)
								.html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

							Swal.fire({
								title: 'Memproses...',
								text: 'Sedang melakukan verifikasi data False Rejection.',
								allowOutsideClick: false,
								didOpen: () => {
									Swal.showLoading();
								}
							});

						},

						success: function(response) {

							if (response.status) {

								Swal.fire({
									icon: 'success',
									title: 'Berhasil',
									text: response.message
								}).then(() => {

									const params = new URLSearchParams();
									params.set('tanggal', tanggal);

									if (shift) {
										params.set('shift', shift);
									}

									window.location.href =
										baseUrl + '?' + params.toString();

								});

							} else {

								Swal.fire({
									icon: 'info',
									title: 'Informasi',
									text: response.message
								});

							}

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

		});
	</script>

<?php endif; ?>
