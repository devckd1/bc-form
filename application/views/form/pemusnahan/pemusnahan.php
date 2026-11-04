<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Data Pemusnahan Barang / Produk
		</h1>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
	<?php endif; ?>

	<?php
	$tipe_user = $this->session->userdata('tipe_user');
	$boleh_verifikasi = in_array($tipe_user, [0, 1, 2, 9]);
	$tanggal = $tanggal ?? '';
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

						<a href="<?= base_url('pemusnahan'); ?>"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>

					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('pemusnahan/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if ($boleh_verifikasi): ?>
						<a href="<?= base_url('pemusnahan/audit') ?>"
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
							<th width="50" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>Best Before</th>
							<th>Analisa Masalah</th>
							<th>Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!empty($pemusnahan)): ?>

							<?php
							$no = (int) ($start ?? 0) + 1;

							foreach ($pemusnahan as $val):

								$datetime = new DateTime($val->date);
								$tanggal_formatted = $datetime->format('d-m-Y');

								$bb = new DateTime($val->best_before);
								$best_before = $bb->format('d-m-Y');
							?>

								<tr>

									<td class="text-center">
										<?= $no++; ?>
									</td>

									<td>
										<?= $tanggal_formatted; ?>
									</td>

									<td>
										<?= html_escape($val->nama_produk); ?>
									</td>

									<td>
										<?= html_escape($val->kode_produksi); ?>
									</td>

									<td>
										<?= $best_before; ?>
									</td>

									<td>
										<?= html_escape($val->analisa); ?>
									</td>

									<td class="text-center">

										<?php if ($val->status_spv == 0): ?>
											<span style="color:#99a3a4;font-weight:bold;">
												Created
											</span>

										<?php elseif ($val->status_spv == 1): ?>
											<span style="color:#28b463;font-weight:bold;">
												Verified
											</span>

										<?php elseif ($val->status_spv == 2): ?>
											<span style="color:red;font-weight:bold;">
												Revision
											</span>
										<?php endif; ?>

									</td>

									<td class="text-center">

										<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
											<a href="<?= base_url('pemusnahan/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-sm">
												Edit
											</a>
										<?php endif; ?>

										<a href="<?= base_url('pemusnahan/detail/' . $val->uuid); ?>"
											data-url="<?= base_url('pemusnahan/detail/' . $val->uuid . '?popup=1'); ?>"
											class="btn btn-success btn-sm btn-detail-pemusnahan">
											Detail
										</a>

										<?php if ($boleh_verifikasi): ?>
											<a href="<?= base_url('pemusnahan/status/' . $val->uuid); ?>"
												class="btn btn-info btn-sm">
												Verifikasi
											</a>
										<?php endif; ?>

									</td>

								</tr>

							<?php endforeach; ?>

						<?php else: ?>

							<tr>
								<td colspan="8" class="text-center">
									Tidak ada data Pemusnahan.
								</td>
							</tr>

						<?php endif; ?>

					</tbody>

				</table>

			</div>

			<div class="mt-3">
				<?= $pagination ?? '' ?>
			</div>

			<?php if ($boleh_verifikasi): ?>

				<hr>

				<div class="mb-3">

					<form action="<?= base_url('pemusnahan/cetak') ?>"
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
							<i class="fas fa-print fa-sm"></i>
							Cetak PDF
						</button>

					</form>

				</div>

			<?php endif; ?>

		</div>
	</div>

</div>
<div class="modal fade" id="modalDetailPemusnahan" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Pemusnahan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailPemusnahan">
				<div class="text-center py-5">
					<i class="fas fa-spinner fa-spin fa-2x"></i>
					<p class="mt-2">Memuat detail...</p>
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
</style>
<script>
	$(document).ready(function() {

		let cacheDetailPemusnahan = {};

		$(document).on('click', '.btn-detail-pemusnahan', function(e) {
			e.preventDefault();

			let url = $(this).data('url');
			let content = $('#contentDetailPemusnahan');

			$('#modalDetailPemusnahan').modal('show');

			if (cacheDetailPemusnahan[url]) {
				content.html(cacheDetailPemusnahan[url]);
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
					cacheDetailPemusnahan[url] = response;
					content.html(response);
				},

				error: function() {
					content.html(`
                        <div class="alert alert-danger text-center">
                            Gagal memuat detail pemusnahan.
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

			const baseUrl = "<?= base_url('pemusnahan'); ?>";

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
					text: 'Semua data Pemusnahan yang memenuhi syarat pada tanggal ' +
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
						url: '<?= base_url('pemusnahan/verifikasi_massal') ?>',
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
