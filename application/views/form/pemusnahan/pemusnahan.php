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
	?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

				<div class="mb-2">

					<a href="<?= base_url('pemusnahan/tambah') ?>"
						class="btn btn-md btn-primary shadow-sm">
						<i class="fas fa-plus fa-sm"></i>
						Tambah
					</a>

					<?php if ($boleh_verifikasi): ?>
						<a href="<?= base_url('pemusnahan/audit') ?>"
							class="btn shadow-sm"
							style="background-color:#5a3298;color:#fff;">
							<i class="fas fa-clipboard-check mr-1"></i>
							Mode Audit
						</a>
					<?php endif; ?>

				</div>

				<form method="get"
					action="<?= base_url('pemusnahan') ?>"
					class="form-inline mb-2">

					<label for="filterTanggal" class="mr-2 font-weight-bold">
						Filter Tanggal
					</label>

					<input
						type="date"
						name="tanggal"
						id="filterTanggal"
						class="form-control mr-2"
						value="<?= html_escape($tanggal ?? '') ?>">

					<button type="submit" class="btn btn-info mr-2">
						<i class="fas fa-search"></i>
						Filter
					</button>

					<a href="<?= base_url('pemusnahan') ?>"
						class="btn btn-secondary">
						Reset
					</a>

				</form>

			</div>

			<?php if ($boleh_verifikasi): ?>

				<div class="alert alert-light border d-flex flex-wrap justify-content-between align-items-center">

					<div class="mb-2 mb-md-0">
						<strong>Verifikasi SPV Massal</strong>

						<div class="small text-muted">
							Verifikasi semua data Pemusnahan pada tanggal terpilih
							yang belum diverifikasi atau berstatus revisi.
						</div>
					</div>

					<button
						type="button"
						id="btnVerifikasiMassal"
						class="btn btn-success"
						<?= empty($tanggal) ? 'disabled' : '' ?>>
						<i class="fas fa-check-double"></i>
						Verifikasi Semua
					</button>

				</div>

			<?php endif; ?>

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
							<th>Supervisor</th>
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

										<a href="<?= base_url('pemusnahan/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-sm">
											Edit
										</a>

										<a href="<?= base_url('pemusnahan/detail/' . $val->uuid); ?>"
											class="btn btn-success btn-sm">
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

						<input
							type="date"
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

<style>
	#dataTable th {
		background-color: #f8f9fc;
	}

	#dataTable th,
	#dataTable td {
		vertical-align: middle;
	}
</style>

<?php if ($boleh_verifikasi): ?>

	<script>
		$(document).ready(function() {

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
					text: 'Semua data Pemusnahan yang memenuhi syarat pada tanggal ' + tanggal + ' akan diverifikasi.',
					icon: 'question',
					showCancelButton: true,
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

							Swal.fire({
								icon: response.status ? 'success' : 'info',
								title: response.status ? 'Berhasil' : 'Informasi',
								text: response.message
							}).then(() => {

								if (response.status) {
									window.location.reload();
								}

							});

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
								.html('<i class="fas fa-check-double"></i> Verifikasi Semua');

						}
					});

				});

			});

		});
	</script>

<?php endif; ?>
