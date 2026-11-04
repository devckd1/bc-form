<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Verifikasi Proses Produksi</h1>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i> <?= $this->session->flashdata('success_msg') ?>
		</div>
		<br>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-times"></i> <?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif; ?>

	<?php
	$tipe_user = $this->session->userdata('tipe_user');
	?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Pencarian, Filter, dan Verifikasi Massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

					<div class="d-flex align-items-center flex-wrap mb-2">

						<form
							id="formFilterProduksi"
							method="get"
							action="<?= base_url('produksi'); ?>"
							class="d-flex align-items-center flex-wrap">

							<!-- Pencarian -->
							<input
								type="text"
								name="keyword"
								class="form-control form-control-sm mr-2 mb-2"
								style="width: 200px;"
								placeholder="Cari produk / kode..."
								value="<?= htmlspecialchars($keyword ?? '', ENT_QUOTES, 'UTF-8'); ?>">

							<button type="submit" class="btn btn-secondary btn-sm mr-2 mb-2">
								<i class="fas fa-search mr-1"></i>
								Cari
							</button>

							<!-- Filter Tanggal -->
							<input
								type="date"
								name="tanggal"
								id="filterTanggal"
								class="form-control form-control-sm mr-2 mb-2"
								style="width: 170px;"
								value="<?= htmlspecialchars($filter_tanggal ?? '', ENT_QUOTES, 'UTF-8'); ?>">

							<!-- Filter Shift -->
							<select
								name="shift"
								id="filterShift"
								class="form-control form-control-sm mr-2 mb-2"
								style="width: 140px;">

								<option value="">Semua Shift</option>

								<?php foreach (['1', '2', '3'] as $shift): ?>
									<option
										value="<?= $shift; ?>"
										<?= (($filter_shift ?? '') === $shift) ? 'selected' : ''; ?>>
										Shift <?= $shift; ?>
									</option>
								<?php endforeach; ?>

							</select>
							<!-- Verifikasi Massal -->
							<button
								type="button"
								id="btnVerifikasiMassal"
								class="btn btn-primary mb-2">
								<i class="fas fa-check-double mr-1"></i>
								Verifikasi Massal
							</button>

							<!-- Reset -->
							<a href="<?= base_url('produksi'); ?>"
								class="btn btn-secondary ml-2 mb-2">
								<i class="fas fa-sync-alt mr-1"></i>
								Reset
							</a>

						</form>

					</div>

				<?php endif; ?>


				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="d-flex align-items-center flex-wrap ml-auto mb-2">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('produksi/tambah'); ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus mr-1"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('produksi/audit'); ?>"
							class="btn shadow-sm ml-2"
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
					Hanya data yang sudah memenuhi syarat verifikasi Produksi yang diproses.
				</small>
			<?php endif; ?>

			<hr>

			<div class="table-responsive">
				<table class="table table-bordered table-striped" width="100%" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Date / Shift</th>
							<th>Types of Product</th>
							<th>Production Code</th>
							<th class="text-center">Status</th>
							<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
								<th class="text-center">Process</th>
								<th class="text-center">Packing</th>
							<?php endif; ?>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php if (!empty($produksi)): ?>
							<?php
							$page = (int) $this->input->get('per_page');
							$no = $page + 1;
							foreach ($produksi as $val):
								$datetime = new DateTime($val->date);
								$datetime = $datetime->format('d-m-Y');
								$isHalus = ($val->nama_produk === 'BREADCRUMBS MIX SINTETIS HALUS (CPI)');
							?>
								<tr>
									<td class="text-center"><?= $no++; ?></td>
									<td><?= $datetime . ' / ' . $val->shift; ?></td>
									<td><?= htmlspecialchars($val->nama_produk); ?></td>
									<td><?= htmlspecialchars($val->kode_produksi); ?></td>

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
									<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
										<td class="text-center">
											<?php if (!$isHalus): ?>
												<a href="<?= base_url('produksi/bahan/' . $val->uuid); ?>" class="btn btn-warning btn-sm shadow" title="Raw Material">RM</a>
												<a href="<?= base_url('produksi/mixing/' . $val->uuid); ?>" class="btn btn-info btn-sm shadow" title="Mixing">Mix</a>
												<a href="<?= base_url('produksi/fermentasi/' . $val->uuid); ?>" class="btn btn-success btn-sm shadow" title="Fermentasi">Fermen</a>
												<a href="<?= base_url('produksi/baking/' . $val->uuid); ?>" class="btn btn-danger btn-sm shadow" title="Baking">Bake</a>
											<?php endif; ?>
										</td>

										<td class="text-center">
											<?php if ($isHalus): ?>
												<a href="<?= base_url('produksi/halus/' . $val->uuid); ?>" class="btn btn-primary btn-sm shadow" title="Produksi Halus">Packing</a>
											<?php else: ?>
												<a href="<?= base_url('produksi/stalling/' . $val->uuid); ?>" class="btn btn-warning btn-sm shadow" title="Stalling">Stall</a>
												<a href="<?= base_url('produksi/drying/' . $val->uuid); ?>" class="btn btn-success btn-sm shadow" title="Drying">Dry</a>
												<a href="<?= base_url('produksi/packing/' . $val->uuid); ?>" class="btn btn-danger btn-sm shadow" title="Packing">Pack</a>
											<?php endif; ?>
										</td>
									<?php endif; ?>
									<td class="text-center">
										<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
											<a href="<?= base_url('produksi/edit/' . $val->uuid); ?>" class="btn btn-warning btn-sm shadow" title="Edit">Edit</a>
										<?php endif; ?>

										<a href="<?= base_url('produksi/detail/' . $val->uuid); ?>"
											data-url="<?= base_url('produksi/detail/' . $val->uuid . '?popup=1'); ?>"
											class="btn btn-success btn-sm shadow btn-detail-produksi"
											title="Detail">
											Detail
										</a>

										<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
											<a href="<?= base_url('produksi/verifikasi_produksi/' . $val->uuid); ?>"
												class="btn btn-primary btn-sm shadow"
												title="Setujui Produksi"
												onclick="return confirm('Apakah Anda yakin ingin menyetujui data ini?')">
												Setujui
											</a>
										<?php endif; ?>

										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
											<?php if ($val->status_produksi == '1' && $val->status_spv == '0'): ?>
												<a href="<?= base_url('produksi/status/' . $val->uuid); ?>" class="btn btn-info btn-sm shadow" title="Verifikasi">
													<span class="text">Verifikasi</span>
												</a>
											<?php elseif ($val->status_produksi == '1' && $val->status_spv == '2'): ?>
												<a href="<?= base_url('produksi/status/' . $val->uuid); ?>" class="btn btn-info btn-sm shadow" title="Verifikasi Ulang">
													<span class="text">Verifikasi Ulang</span>
												</a>
											<?php endif; ?>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="8" class="text-center text-muted">Tidak ada data produksi.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<div class="mt-3 text-center">
				<?= isset($pagination) ? $pagination : ''; ?>
			</div>
		</div>
		<!-- Export Excel & Cetak PDF -->
		<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

			<div class="card-body">
				<div class="form-row">
					<div class="form-group col-md-2">
						<label for="tanggal"><strong>Pilih Tanggal:</strong></label>
						<input type="date" id="tanggal" class="form-control" required>
					</div>
					<div class="form-group col-md-2">
						<label for="nama_produk"><strong>Pilih Produk:</strong></label>
						<select id="nama_produk" class="form-control" required>
							<option value="">-- Pilih Produk --</option>
						</select>
					</div>

					<!-- Tombol Export Excel -->
					<div class="form-group align-self-end col-md-2">
						<form id="form-export-excel" action="<?= base_url('produksi/export_excel') ?>" method="post">
							<input type="hidden" name="tanggal" id="tanggal_excel">
							<input type="hidden" name="nama_produk" id="produk_excel">
							<button type="submit" class="btn btn-primary btn-block">
								<i class="fas fa-file-excel"></i> Export Excel
							</button>
						</form>
					</div>

					<!-- Tombol Cetak PDF -->
					<div class="form-group align-self-end col-md-2">
						<form id="form-cetak-pdf" action="<?= base_url('produksi/cetak') ?>" method="post">
							<input type="hidden" name="tanggal" id="tanggal_pdf_hidden">
							<input type="hidden" name="nama_produk" id="produk_pdf_hidden">
							<button type="submit" class="btn btn-success btn-block">
								<i class="fas fa-file-pdf"></i> Cetak PDF
							</button>
						</form>
					</div>
				</div>
			</div>
		<?php endif; ?>

	</div>
</div>
</div>
<!-- Modal Detail Produksi -->
<div class="modal fade" id="modalDetailProduksi" tabindex="-1" role="dialog"
	aria-labelledby="modalDetailProduksiLabel" aria-hidden="true">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailProduksiLabel">
					Detail Produksi
				</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailProduksi">
				<div class="text-center py-5">
					<i class="fas fa-spinner fa-spin fa-2x"></i>
					<p class="mt-2">Memuat detail...</p>
				</div>
			</div>

		</div>
	</div>
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<script>
	document.addEventListener('DOMContentLoaded', function() {
		var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
		tooltipTriggerList.forEach(function(el) {
			new bootstrap.Tooltip(el);
		});
	});
</script>
<script>
	$(document).ready(function() {

		let cacheDetailProduksi = {};

		$(document).on('click', '.btn-detail-produksi', function(e) {
			e.preventDefault();

			let url = $(this).data('url');

			$('#modalDetailProduksi').modal('show');

			if (cacheDetailProduksi[url]) {
				$('#contentDetailProduksi').html(cacheDetailProduksi[url]);
				return;
			}

			$('#contentDetailProduksi').html(`
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
					cacheDetailProduksi[url] = response;
					$('#contentDetailProduksi').html(response);
				},

				error: function() {
					$('#contentDetailProduksi').html(`
                        <div class="alert alert-danger text-center">
                            Gagal memuat detail produksi.
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

	td,
	th {
		vertical-align: middle !important;
	}

	.btn {
		min-width: 60px;
	}

	.input-group input {
		border-radius: 5px 0 0 5px !important;
	}

	.input-group .btn {
		border-radius: 0 5px 5px 0 !important;
	}
</style>
<script>
	$(document).ready(function() {

		$('#btnVerifikasiMassal').on('click', function() {

			var tanggal = $('input[name="tanggal"]').val();
			var shift = $('select[name="shift"]').val();
			var tombol = $(this);

			if (!tanggal) {
				Swal.fire(
					'Perhatian',
					'Pilih tanggal terlebih dahulu.',
					'warning'
				);
				return;
			}

			Swal.fire({
				title: 'Verifikasi Massal?',
				text: 'Data Produksi pada tanggal dan shift terpilih yang sudah memenuhi syarat akan diverifikasi.',
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
					url: "<?= base_url('produksi/verifikasi_massal'); ?>",
					type: 'POST',
					dataType: 'json',
					data: {
						tanggal: tanggal,
						shift: shift || 'semua'
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
							tombol.html(
								'<i class="fas fa-check-double mr-1"></i> Verifikasi Massal'
							);
						}
					},

					error: function() {
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
<script>
	$(document).ready(function() {

		// Otomatis filter ketika tanggal berubah
		$('#filterTanggal').on('change', function() {
			$('#formFilterProduksi').submit();
		});

		// Otomatis filter ketika shift berubah
		$('#filterShift').on('change', function() {
			$('#formFilterProduksi').submit();
		});

	});
</script>
