<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Suhu Ruang</h1>
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

				<!-- Filter tanggal dan verifikasi massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

					<div class="d-flex align-items-center flex-wrap mb-2">
						<form id="formVerifikasiMassal"
							action="<?= base_url('suhu/verifikasi_massal'); ?>"
							method="post"
							class="d-flex align-items-center flex-wrap">
							<input type="date"
								id="filter_tanggal"
								name="tanggal"
								class="form-control mr-2 mb-2"
								style="width: 170px;"
								value="<?= htmlspecialchars($filter_tanggal ?? '', ENT_QUOTES, 'UTF-8'); ?>"
								required>
							<button type="submit"
								id="btnVerifikasiMassal"
								class="btn btn-primary mb-2">
								<i class="fas fa-check-double mr-1"></i>
								Verifikasi Massal
							</button>
						</form>
						<a href="<?= base_url('suhu'); ?>"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>
					</div>

				<?php endif; ?>

				<!-- Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">
					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('suhu/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>
					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('suhu/audit'); ?>"
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

					Hanya data Suhu Ruang QC dengan status Produksi
					Checked dan status SPV Created atau Revision pada
					tanggal terpilih yang akan diproses.

				</small>

			<?php endif; ?>

			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th class="text-center">Tanggal/ Shift</th>
							<th class="text-center">Pukul</th><!-- 
                            <th>Lokasi</th>
                            <th>Suhu / RH</th> -->
							<!-- <th>Produksi</th> -->
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;
						foreach ($suhu as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime . " / " . $val->shift; ?></td>
								<td><?= date('H:i', strtotime($val->pukul)); ?></td><!-- 
                                <td><?= $val->lokasi; ?></td>
                                <td><?= $val->suhu . " / " . $val->rh; ?></td> -->
								<!-- <td class="text-center">
                                    <?php
									if ($val->status_produksi == 0) {
										echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
									} elseif ($val->status_produksi == 1) {
										echo '<span style="color: #28b463; font-weight: bold;">Checked</span>';
									} elseif ($val->status_produksi == 2) {
										echo '<span style="color: red; font-weight: bold;">Re-Check</span>';
									}
									?>
                                </td> -->
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
											<a href="<?= base_url('suhu/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split mb-1">
												<span class="text">Edit</span>
											</a>
										<?php endif; ?>

										<a href="<?= base_url('suhu/detail/' . $val->uuid); ?>"
											class="btn btn-success btn-icon-split mb-1 btn-detail-suhu"
											data-url="<?= base_url('suhu/detail/' . $val->uuid . '?popup=1'); ?>">
											<span class="text">Detail</span>
										</a>

										<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
											<a href="<?= base_url('suhu/verifikasi_produksi/' . $val->uuid); ?>"
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
												<a href="<?= base_url('suhu/status/' . $val->uuid); ?>"
													class="btn btn-info btn-icon-split mb-1">
													<span class="text">Verifikasi</span>
												</a>

											<?php elseif (
												$val->status_produksi == '1' &&
												$val->status_spv == '2'
											): ?>
												<a href="<?= base_url('suhu/status/' . $val->uuid); ?>"
													class="btn btn-warning btn-icon-split mb-1">
													<span class="text">Verifikasi Ulang</span>
												</a>
											<?php endif; ?>

										<?php endif; ?>

									</div>
									<!--  <a href="<?= base_url('suhu/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
				<hr>
				<?php
				$plant_uuid = $this->session->userdata('plant');
				?>

				<form action="<?= base_url('suhu/cetak') ?>" method="post" target="_blank" class="form-inline mb-3">
					<div class="form-group mr-3">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" id="tanggal" class="form-control" required>
					</div>

					<!-- PDF (SEMUA PLANT BOLEH) -->
					<button type="submit" class="btn btn-success mr-2">
						<i class="fas fa-print"></i> Cetak PDF
					</button>

					<!-- EXCEL CIKANDE -->
					<?php if ($plant_uuid === '651ac623-5e48-44cc-b2f6-5d622603f53c'): ?>
						<button type="submit"
							formaction="<?= base_url('suhu/export-excel') ?>"
							class="btn btn-primary mr-2">
							<i class="fas fa-file-excel"></i> Export Excel
						</button>
					<?php endif; ?>

					<!-- EXCEL SALATIGA -->
					<?php if ($plant_uuid === '1eb341e0-1ec4-4484-ba8f-32d23352b84d'): ?>
						<button type="submit"
							formaction="<?= base_url('suhu/export-excel-salatiga') ?>"
							class="btn btn-primary">
							<i class="fas fa-file-excel"></i> Export Excel
						</button>
					<?php endif; ?>
				</form>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="modalDetailSuhu" tabindex="-1"
	role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title">Detail Pemeriksaan Suhu Ruang</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailSuhu">
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
					"<?= base_url('suhu'); ?>?tanggal=" + tanggal;
			}

		});


		// Verifikasi massal SPV
		$('#formVerifikasiMassal').on('submit', function(e) {

			e.preventDefault();

			var form = $(this);
			var tombol = $('#btnVerifikasiMassal');

			if (!form.find('[name="tanggal"]').val()) {

				Swal.fire(
					'Perhatian',
					'Silakan pilih tanggal terlebih dahulu.',
					'warning'
				);

				return;
			}

			Swal.fire({

				title: 'Verifikasi Massal?',

				text: 'Data Suhu Ruang QC dengan status Produksi Checked dan status SPV Created atau Revision pada tanggal tersebut akan diproses.',

				icon: 'question',

				showCancelButton: true,

				confirmButtonText: 'Ya, Verifikasi',

				cancelButtonText: 'Batal'

			}).then(function(result) {

				if (!result.isConfirmed) {
					return;
				}

				tombol.prop('disabled', true);

				tombol.html(
					'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
				);

				$.ajax({

					url: form.attr('action'),

					type: 'POST',

					data: form.serialize(),

					dataType: 'json',

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
								'<i class="fas fa-check-double mr-1"></i> Verifikasi Massal'
							);

						}

					},

					error: function(xhr) {

						Swal.fire(
							'Error',
							'Terjadi kesalahan saat memproses verifikasi massal.',
							'error'
						);

						tombol.prop('disabled', false);

						tombol.html(
							'<i class="fas fa-check-double mr-1"></i> Verifikasi Massal'
						);

					}

				});

			});

		});

	});

	// Modal Detail Suhu AJAX
	let cacheDetailSuhu = {};

	$(document).on('click', '.btn-detail-suhu', function(e) {

		e.preventDefault();

		let url = $(this).data('url');

		$('#modalDetailSuhu').modal('show');

		if (cacheDetailSuhu[url]) {
			$('#contentDetailSuhu').html(cacheDetailSuhu[url]);
			return;
		}

		$('#contentDetailSuhu').html(`
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
				cacheDetailSuhu[url] = response;
				$('#contentDetailSuhu').html(response);
			},

			error: function() {
				$('#contentDetailSuhu').html(`
                <div class="alert alert-danger">
                    Gagal memuat detail pemeriksaan suhu.
                </div>
            `);
			}
		});

	});
</script>
