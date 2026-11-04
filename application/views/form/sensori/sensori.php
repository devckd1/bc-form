<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Laporan Sensori Finish Good</h1>
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
	?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- Filter tanggal dan verifikasi massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

					<div class="d-flex align-items-center flex-wrap mb-2">

						<form id="formVerifikasiMassal"
							action="<?= base_url('sensori/verifikasi_massal'); ?>"
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

						<a href="<?= base_url('sensori'); ?>"
							class="btn btn-secondary ml-2 mb-2">

							<i class="fas fa-sync-alt mr-1"></i>
							Reset

						</a>

					</div>

				<?php endif; ?>


				<!-- Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">
					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('sensori/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

						<a href="<?= base_url('sensori/audit'); ?>"
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

					Hanya data Sensori Finish Good QC yang sudah diketahui
					Produksi dan berstatus Created atau Revision pada tanggal
					terpilih yang akan diverifikasi.

				</small>

			<?php endif; ?>
			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Nama Produk</th>
							<th style="text-align: center;">Sensori Produk</th>
							<th>Tindakan Koreksi</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;
						foreach ($sensori as $val) {
							$tanggalFormatted = (new DateTime($val->date))->format('d-m-Y');
							$products = json_decode($val->produk, true);
						?>
							<tr>
								<td class="text-center"><?= $no++; ?></td>
								<td><?= $tanggalFormatted; ?></td>
								<td><?= $val->nama_produk; ?></td>
								<td>
									<table class="table table-sm table-bordered mb-0">
										<thead style="background-color: #2E86C1; color: black; text-align: center;">
											<tr>
												<th>Kode Produksi</th>
												<th>Best Before</th>
												<th>Warna</th>
												<th>Tekstur</th>
												<th>Rasa</th>
												<th>Aroma</th>
												<th>Kenampakan</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($products)): ?>
												<?php foreach ($products as $p): ?>
													<tr>
														<td><?= htmlspecialchars($p['kode_produksi'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['best_before'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['warna'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['tekstur'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['rasa'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['aroma'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['kenampakan'] ?? '-'); ?></td>
													</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="7" class="text-center">Tidak ada data sensori</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</td>

								<td><?= $val->tindakan; ?></td>

								<!-- Status Supervisor -->
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

								<!-- Aksi -->
								<td class="text-center">
									<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
										<a href="<?= base_url('sensori/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split mb-1">
											<span class="text">Edit</span>
										</a>
									<?php endif; ?>

									<a href="<?= base_url('sensori/detail/' . $val->uuid); ?>"
    data-url="<?= base_url('sensori/detail/' . $val->uuid . '?popup=1'); ?>"
    class="btn btn-success btn-icon-split mb-1 btn-detail-sensori">
    <span class="text">Detail</span>
</a>

									<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
										<a href="<?= base_url('sensori/verifikasi_produksi/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split mb-1"
											onclick="return confirm('Apakah Anda yakin ingin menyetujui data ini?')">
											<span class="text">Setujui</span>
										</a>
									<?php endif; ?>

									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<?php if ($val->status_produksi == '1' && $val->status_spv == '0'): ?>
											<a href="<?= base_url('sensori/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>
										<?php elseif ($val->status_produksi == '1' && $val->status_spv == '2'): ?>
											<a href="<?= base_url('sensori/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi Ulang</span>
											</a>
										<?php endif; ?>
									<?php endif; ?>
									<!--  <a href="<?= base_url('sensori/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <span class="text">Delete</span>
                                    </a> -->
								</td>
							</tr>
						<?php } ?>
					</tbody>

				</table>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<div class="form-group">
					<form action="<?= base_url('sensori/cetak') ?>" method="post" class="form-inline">
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
<!-- Modal Detail Sensori -->
<div class="modal fade" id="modalDetailSensori" tabindex="-1"
    role="dialog" aria-labelledby="modalDetailSensoriLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalDetailSensoriLabel">
                    Detail Laporan Sensori Finish Good
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="contentDetailSensori">
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

        const cacheDetailSensori = {};

        $(document).on('click', '.btn-detail-sensori', function(e) {
            e.preventDefault();

            const url = $(this).data('url');

            $('#modalDetailSensori').modal('show');

            if (cacheDetailSensori[url]) {
                $('#contentDetailSensori').html(cacheDetailSensori[url]);
                return;
            }

            $('#contentDetailSensori').html(`
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
                    cacheDetailSensori[url] = response;
                    $('#contentDetailSensori').html(response);
                },

                error: function() {
                    $('#contentDetailSensori').html(`
                        <div class="alert alert-danger text-center">
                            Gagal memuat detail Sensori Finish Good.
                        </div>
                    `);
                }
            });

        });

    });
</script>

<!-- <div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-2 text-gray-800">Laporan Sensori Finish Good</h1>
    </div>

    <?php if ($this->session->flashdata('success_msg')): ?>
        <div class="alert alert-success text-center">
            <i class="fas fa-check"></i>
            <?= $this->session->flashdata('success_msg') ?>
        </div><br>
    <?php endif ?>

    <?php if ($this->session->flashdata('error_msg')): ?>
        <div class="alert alert-danger text-center">
            <i class="fas fa-times"></i>
            <?= $this->session->flashdata('error_msg') ?>
        </div><br>
    <?php endif ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="form-group text-right">
                <a href="<?= base_url('sensori/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah
                </a>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Produk</th>
                            <th>Kode Produksi</th>
                            <th>Best Before</th>
                            <th>Warna</th>
                            <th>Tekstur</th>
                            <th>Rasa</th>
                            <th>Aroma</th>
                            <th>Kenampakan</th>
                            <th>Tindakan Koreksi</th>
                            <th>Produksi</th>
                            <th>Supervisor</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php
						$no = 1;
						foreach ($sensori as $val):
							$tanggalFormatted = (new DateTime($val->date))->format('d-m-Y');
							$produkList = json_decode($val->produk, true);

							if (!is_array($produkList) || empty($produkList)) {
								echo "<tr><td colspan='14' class='text-danger'>Data produk kosong atau format salah</td></tr>";
								continue;
							}

							$rowspan = count($produkList);
							foreach ($produkList as $i => $prod): ?>
                                <tr>
                                    <?php if ($i === 0): ?>
                                        <td rowspan="<?= $rowspan ?>" class="text-center"><?= $no++; ?></td>
                                        <td rowspan="<?= $rowspan ?>"><?= $tanggalFormatted; ?></td>
                                        <td rowspan="<?= $rowspan ?>"><?= htmlspecialchars($val->nama_produk); ?></td>
                                    <?php endif; ?>

                                    <td><?= htmlspecialchars($prod['kode_produksi'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($prod['best_before'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($prod['warna'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($prod['tekstur'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($prod['rasa'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($prod['aroma'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($prod['kenampakan'] ?? '-') ?></td>

                                    <?php if ($i === 0): ?>
                                        <td rowspan="<?= $rowspan ?>"><?= htmlspecialchars($val->tindakan); ?></td>
                                        <td rowspan="<?= $rowspan ?>">
                                            <?= match ($val->status_produksi) {
												0 => '<span style="color:#99a3a4;font-weight:bold;">Created</span>',
												1 => '<span style="color:#28b463;font-weight:bold;">Checked</span>',
												2 => '<span style="color:red;font-weight:bold;">Re-Check</span>',
												default => '-'
											}; ?>
                                        </td>
                                        <td rowspan="<?= $rowspan ?>">
                                            <?= match ($val->status_spv) {
												0 => '<span style="color:#99a3a4;font-weight:bold;">Created</span>',
												1 => '<span style="color:#28b463;font-weight:bold;">Verified</span>',
												2 => '<span style="color:red;font-weight:bold;">Revision</span>',
												default => '-'
											}; ?>
                                        </td>
                                        <td rowspan="<?= $rowspan ?>">
                                            <a href="<?= base_url('sensori/edit/' . $val->uuid); ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                            <a href="<?= base_url('sensori/detail/' . $val->uuid); ?>" class="btn btn-success btn-sm"><i class="fas fa-eye"></i> Detail</a>
                                            <a href="<?= base_url('sensori/delete/' . $val->uuid); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')"><i class="fas fa-trash"></i> Delete</a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach;
						endforeach; ?>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div> -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables CSS & JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
	$(document).ready(function() {
		$('#dataTable').DataTable({
			responsive: true,
			autoWidth: false
		});
	});
</script>
<script>
	$(document).ready(function() {

		// Filter tanggal otomatis
		$('#filter_tanggal').on('change', function() {

			var tanggal = $(this).val();

			if (tanggal) {
				window.location.href =
					"<?= base_url('sensori'); ?>?tanggal=" + tanggal;
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

				text: 'Data Sensori Finish Good QC yang sudah diketahui Produksi dan berstatus Created atau Revision pada tanggal tersebut akan diproses.',

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
<style>
	th {
		background-color: #f8f9fc;
	}

	td,
	th {
		text-align: center;
		vertical-align: middle;
	}

	table,
	th,
	td {
		border: 1px solid #dee2e6 !important;
	}
</style>
