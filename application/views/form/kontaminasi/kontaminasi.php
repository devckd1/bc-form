<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Kontaminasi Benda Asing</h1>
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
							action="<?= base_url('kontaminasi/verifikasi_massal'); ?>"
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

						<a href="<?= base_url('kontaminasi'); ?>"
							class="btn btn-secondary ml-2 mb-2">

							<i class="fas fa-sync-alt mr-1"></i>
							Reset

						</a>

					</div>

				<?php endif; ?>


				<!-- Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">
					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('kontaminasi/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

						<a href="<?= base_url('kontaminasi/audit'); ?>"
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

					Hanya data Kontaminasi yang sudah diverifikasi Produksi
					pada tanggal terpilih yang akan diproses.

				</small>

			<?php endif; ?>

			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Jenis Kontaminasi</th>
							<th>Bukti</th>
							<th>Produk</th>
							<th>Kode Produksi</th>
							<th>Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;
						foreach ($kontaminasi as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->jenis_kontaminasi; ?></td>
								<td>
									<?php
									$bukti = $val->bukti ?? null;

									$path1 = FCPATH . 'uploads/' . $bukti;
									$path2 = FCPATH . 'uploads/kontaminasi/' . $bukti;

									if (!empty($bukti)) {
										if (file_exists($path1)) {
											$img_url = base_url('uploads/' . $bukti);
										} elseif (file_exists($path2)) {
											$img_url = base_url('uploads/kontaminasi/' . $bukti);
										} else {
											$img_url = null;
										}
									} else {
										$img_url = null;
									}
									?>

									<?php if ($img_url): ?>
										<img src="<?= $img_url; ?>" alt="Bukti Temuan" style="max-width: 200px; max-height: 150px;">
									<?php else: ?>
										<p>Tidak ada gambar</p>
									<?php endif; ?>

								</td>
								<td><?= $val->nama_produk; ?></td>
								<td><?= $val->kode_produksi; ?></td>
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
										<a href="<?= base_url('kontaminasi/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split mb-1">
											<span class="text">Edit</span>
										</a>
									<?php endif; ?>

									<a href="<?= base_url('kontaminasi/detail/' . $val->uuid); ?>"
    data-url="<?= base_url('kontaminasi/detail/' . $val->uuid . '?popup=1'); ?>"
    class="btn btn-success btn-icon-split mb-1 btn-detail-kontaminasi">
    <span class="text">Detail</span>
</a>

									<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
										<a href="<?= base_url('kontaminasi/verifikasi_produksi/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split mb-1"
											onclick="return confirm('Apakah Anda yakin ingin menyetujui data ini?')">
											<span class="text">Setujui</span>
										</a>
									<?php endif; ?>

									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<?php if ($val->status_produksi == '1' && $val->status_spv == '0'): ?>
											<a href="<?= base_url('kontaminasi/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>
										<?php elseif ($val->status_produksi == '1' && $val->status_spv == '2'): ?>
											<a href="<?= base_url('kontaminasi/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi Ulang</span>
											</a>
										<?php endif; ?>
									<?php endif; ?>
									<!-- <a href="<?= base_url('kontaminasi/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
					<form action="<?= base_url('kontaminasi/cetak') ?>" method="post" class="form-inline">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" class="form-control mr-2" required>
						<button type="submit" class="btn btn-success">
							<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
						</button>
					</form>
				</div>

				<hr>

				<div class="form-group mt-3">
					<form action="<?= base_url('kontaminasi/export_excel') ?>" method="post" class="form-inline">
						<label for="bulan" class="mr-2 font-weight-bold">Export Excel Bulanan:</label>
						<input type="month" name="bulan" class="form-control mr-2" required>

						<button type="submit" class="btn btn-primary">
							<i class="fas fa-file-excel"></i> Export Excel
						</button>
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
<!-- Modal Detail Kontaminasi -->
<div class="modal fade" id="modalDetailKontaminasi" tabindex="-1"
    role="dialog" aria-labelledby="modalDetailKontaminasiLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailKontaminasiLabel">
                    Detail Kontaminasi Benda Asing
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="contentDetailKontaminasi">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Memuat data...</p>
                </div>
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
<script>
    $(document).ready(function() {

        const cacheDetailKontaminasi = {};

        $(document).on('click', '.btn-detail-kontaminasi', function(e) {
            e.preventDefault();

            const url = $(this).data('url');

            $('#modalDetailKontaminasi').modal('show');

            if (cacheDetailKontaminasi[url]) {
                $('#contentDetailKontaminasi').html(
                    cacheDetailKontaminasi[url]
                );
                return;
            }

            $('#contentDetailKontaminasi').html(`
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
                    cacheDetailKontaminasi[url] = response;
                    $('#contentDetailKontaminasi').html(response);
                },

                error: function() {
                    $('#contentDetailKontaminasi').html(`
                        <div class="alert alert-danger text-center">
                            Gagal memuat detail Kontaminasi.
                        </div>
                    `);
                }
            });

        });

    });
</script>
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
					"<?= base_url('kontaminasi'); ?>?tanggal=" + tanggal;
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

				text: 'Data Kontaminasi yang sudah diverifikasi Produksi pada tanggal tersebut akan diproses.',

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
</script>
