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
			<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

				<div class="mb-2">
					<a href="<?= base_url('metal/tambah') ?>"
						class="btn btn-md btn-primary shadow-sm">
						<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
					</a>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('metal/audit'); ?>"
							class="btn shadow-sm"
							style="background-color: #5a3298; color: #fff;">
							<i class="fas fa-clipboard-check mr-1"></i>
							Mode Audit
						</a>
					<?php endif; ?>
				</div>

				<form method="get"
					action="<?= base_url('metal') ?>"
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
						<i class="fas fa-search"></i> Filter
					</button>

					<a href="<?= base_url('metal') ?>" class="btn btn-secondary">
						Reset
					</a>
				</form>

			</div>

			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<div class="alert alert-light border d-flex flex-wrap justify-content-between align-items-center">
					<div class="mb-2 mb-md-0">
						<strong>Verifikasi SPV Massal</strong>
						<div class="small text-muted">
							Verifikasi seluruh pemeriksaan Metal Detector pada tanggal terpilih
							yang sudah disetujui bagian produksi.
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
							<th>Status</th>
							<th style="text-align:center;">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;
						foreach ($metal as $val) {
							$datetime = new datetime($val->date_metal);
							$datetime = $datetime->format('d-m-Y');

							$timing = new DateTime($val->time);
							$timing = $timing->format('H:i');

						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
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
									$tipe_user = (int) $this->session->userdata('tipe_user');

									if ($val->status_spv == '1') {
										echo '<span style="color:#28b463;font-weight:bold;font-size:12px;">Verified</span>';
									} elseif ($val->status_spv == '2') {
										echo '<span style="color:red;font-weight:bold;font-size:12px;">Revision</span>';
									} elseif ($val->status_produksi == '1' && $val->status_spv == '0') {
										echo '<span style="color:#28b463;font-weight:bold;font-size:12px;">Approved</span>';
									} elseif ($tipe_user == 3 && $val->status_spv == '0') {
										echo '<span style="color:#99a3a4;font-weight:bold;font-size:12px;">Created</span>';
									} elseif ($val->status_produksi == '0') {
										echo '<span style="color:#f39c12;font-weight:bold;font-size:12px;">Menunggu Verifikasi Produksi</span>';
									}
									?>
								</td>
								<td class="text-center">
									<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
										<a href="<?= base_url('metal/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
											<span class="text">Edit</span>
										</a>
									<?php endif; ?>

									<a href="<?= base_url('metal/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split">
										<span class="text">Detail</span>
									</a>

									<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
										<a href="<?= base_url('metal/verifikasi_produksi/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split"
											onclick="return confirm('Apakah Anda yakin ingin menyetujui data ini?')">
											<span class="text">Setujui</span>
										</a>
									<?php endif; ?>

									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<?php if ($val->status_produksi == '1' && $val->status_spv == '0'): ?>
											<a href="<?= base_url('metal/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>
										<?php elseif ($val->status_produksi == '1' && $val->status_spv == '2'): ?>
											<a href="<?= base_url('metal/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi Ulang</span>
											</a>
										<?php endif; ?>
									<?php endif; ?>
									<!--  <a href="<?= base_url('metal/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
				</form>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<div class="mb-3">
					<form action="<?= base_url('metal/cetak') ?>" method="post" class="form-inline">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" class="form-control mr-2" required>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<script>
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	tooltipTriggerList.forEach(function(tooltipTriggerEl) {
		new bootstrap.Tooltip(tooltipTriggerEl)
	})
</script>

<style>
	th {
		background-color: #f8f9fc;
	}
</style>
<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
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
					text: 'Semua data Metal Detector yang memenuhi syarat pada tanggal ' + tanggal + ' akan diverifikasi.',
					icon: 'question',
					showCancelButton: true,
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
