<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Laporan Roti Gosong</h1>
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

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 3, 9])): ?>

					<div class="d-flex align-items-center flex-wrap mb-2">

						<input
							type="date"
							id="filter_tanggal"
							class="form-control mr-2 mb-2"
							style="width: 170px;"
							value="<?= htmlspecialchars($filter_tanggal ?? '', ENT_QUOTES, 'UTF-8'); ?>">

						<select
							id="filter_shift"
							class="form-control mr-2 mb-2"
							style="width: 140px;">

							<option value="">Semua Shift</option>

							<?php foreach (['1A', '1B', '1C', '2A', '2B', '2C', '3A', '3B', '3C'] as $shift): ?>
								<option value="<?= $shift; ?>"
									<?= (($filter_shift ?? '') === $shift) ? 'selected' : ''; ?>>
									Shift <?= $shift; ?>
								</option>
							<?php endforeach; ?>

						</select>

						<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

							<button
								type="button"
								class="btn btn-primary btn-verifikasi-massal mr-1 mb-2"
								data-jenis="spv">

								<i class="fas fa-check-double mr-1"></i>
								Verifikasi Massal
							</button>

						<?php endif; ?>

						<?php if (in_array($tipe_user, [0, 1, 3, 9])): ?>

							<button
								type="button"
								class="btn btn-primary btn-verifikasi-massal mb-2"
								data-jenis="produksi">

								<i class="fas fa-industry mr-1"></i>
								Verifikasi Massal
							</button>

						<?php endif; ?>

						<a
							href="<?= base_url('gosong'); ?>"
							class="btn btn-secondary ml-2 mb-2">

							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>

					</div>

				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">
					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('gosong/tambah'); ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('gosong/audit'); ?>"
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
					Hanya data yang sudah diverifikasi Produksi yang akan diproses.
				</small>
			<?php endif; ?>
			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Shift</th>
							<th>Total Berat (Kg)</th>
							<th>Inspector</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;
						foreach ($gosong as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->shift; ?></td>
								<td><?= $val->total_berat; ?></td>
								<td><?= $val->username; ?></td>
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
										<a href="<?= base_url('gosong/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split mb-1">
											<span class="text">Edit</span>
										</a>
									<?php endif; ?>

									<a href="<?= base_url('gosong/detail/' . $val->uuid); ?>"
										data-url="<?= base_url('gosong/detail/' . $val->uuid . '?popup=1'); ?>"
										class="btn btn-success btn-icon-split mb-1 btn-detail-gosong">
										<span class="text">Detail</span>
									</a>

									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('gosong/delete/' . $val->uuid); ?>"
											class="btn btn-danger btn-icon-split mb-1"
											onclick="return confirm('Yakin ingin menghapus data ini?')">
											<span class="text">Delete</span>
										</a>
									<?php endif; ?>

									<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
										<a href="<?= base_url('gosong/verifikasi_produksi/' . $val->uuid); ?>"
											class="btn btn-primary btn-icon-split mb-1"
											onclick="return confirm('Apakah Anda yakin ingin menyetujui data ini?')">
											<span class="text">Setujui</span>
										</a>
									<?php endif; ?>

									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<?php if ($val->status_produksi == '1' && $val->status_spv == '0'): ?>
											<a href="<?= base_url('gosong/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>
										<?php elseif ($val->status_produksi == '1' && $val->status_spv == '2'): ?>
											<a href="<?= base_url('gosong/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
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
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<div class="form-group">
					<form action="<?= base_url('gosong/cetak') ?>" method="post" class="form-inline">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" class="form-control mr-2" required>
						<button type="submit" class="btn btn-success">
							<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
						</button>
					</form>
				</div>
				<hr>
				<div class="form-group mt-3">
					<form action="<?= base_url('gosong/export_excel') ?>" method="post" class="form-inline">
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
<div class="modal fade" id="modalDetailGosong" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Laporan Gosong</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailGosong">
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
	var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
	tooltipTriggerList.forEach(function(tooltipTriggerEl) {
		new bootstrap.Tooltip(tooltipTriggerEl)
	})
</script>
<script>
	$(document).ready(function() {

		let cacheDetailGosong = {};

		$(document).on('click', '.btn-detail-gosong', function(e) {
			e.preventDefault();

			let url = $(this).data('url');
			let content = $('#contentDetailGosong');

			$('#modalDetailGosong').modal('show');

			if (cacheDetailGosong[url]) {
				content.html(cacheDetailGosong[url]);
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
					cacheDetailGosong[url] = response;
					content.html(response);
				},

				error: function() {
					content.html(`
                        <div class="alert alert-danger text-center">
                            Gagal memuat detail laporan Gosong.
                        </div>
                    `);
				}
			});
		});

	});
</script>
<script>
	document.getElementById('select_all').addEventListener('change', function() {
		var checkboxes = document.querySelectorAll('.select_row');
		checkboxes.forEach(function(checkbox) {
			checkbox.checked = this.checked;
		});
	});

	document.getElementById('form_cetak_pdf').addEventListener('submit', function(event) {
		var selectedCheckboxes = document.querySelectorAll('.select_row:checked');
		var selectedItems = [];

		selectedCheckboxes.forEach(function(checkbox) {
			selectedItems.push(checkbox.value);
		});
		if (selectedItems.length === 0) {
			event.preventDefault();
			alert("Silakan pilih data yang ingin dicetak.");
			return;
		}
		document.getElementById('selected_items').value = selectedItems.join(',');
	});
</script>
<script>
	$(document).ready(function() {

		const baseUrl = "<?= base_url('gosong'); ?>";
		const verifikasiUrl = "<?= base_url('gosong/verifikasi_massal'); ?>";

		// ==========================================
		// FILTER TANGGAL DAN SHIFT
		// ==========================================
		function filterGosong() {

			const tanggal = $('#filter_tanggal').val();
			const shift = $('#filter_shift').val();

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

		$('#filter_tanggal, #filter_shift').on('change', function() {
			filterGosong();
		});


		// ==========================================
		// VERIFIKASI MASSAL SPV DAN PRODUKSI
		// ==========================================
		$('.btn-verifikasi-massal').on('click', function() {

			const tombol = $(this);
			const jenis = tombol.data('jenis');

			const tanggal = $('#filter_tanggal').val();
			const shift = $('#filter_shift').val();

			const namaJenis = jenis === 'spv' ? 'SPV' : 'Produksi';

			if (!tanggal) {
				Swal.fire(
					'Perhatian',
					'Pilih tanggal terlebih dahulu.',
					'warning'
				);
				return;
			}

			const keteranganShift = shift ?
				'Shift ' + shift :
				'Semua Shift';

			const pesan = jenis === 'spv' ?
				'Data Gosong yang sudah diverifikasi Produksi pada tanggal ' +
				tanggal + ' (' + keteranganShift + ') akan diverifikasi oleh SPV.' :
				'Data Gosong yang belum diverifikasi Produksi pada tanggal ' +
				tanggal + ' (' + keteranganShift + ') akan diverifikasi oleh Produksi.';

			Swal.fire({
				title: 'Verifikasi Massal ' + namaJenis + '?',
				text: pesan,
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, Verifikasi',
				cancelButtonText: 'Batal'
			}).then(function(result) {

				if (!result.isConfirmed) {
					return;
				}

				$.ajax({

					url: verifikasiUrl,
					type: 'POST',
					dataType: 'json',

					data: {
						tanggal: tanggal,
						shift: shift || 'semua',
						jenis_verifikasi: jenis
					},

					beforeSend: function() {

						tombol.prop('disabled', true);

						tombol.html(
							'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
						);

						Swal.fire({
							title: 'Memproses...',
							text: 'Sedang melakukan verifikasi Gosong oleh ' + namaJenis + '.',
							allowOutsideClick: false,
							didOpen: function() {
								Swal.showLoading();
							}
						});
					},

					success: function(response) {

						if (response.success) {

							Swal.fire({
								title: 'Berhasil',
								text: response.message +
									' Jumlah data: ' + response.updated,
								icon: 'success'
							}).then(function() {

								const params = new URLSearchParams();

								params.set('tanggal', tanggal);

								if (shift) {
									params.set('shift', shift);
								}

								window.location.href =
									baseUrl + '?' + params.toString();
							});

						} else {

							Swal.fire(
								'Informasi',
								response.message,
								'info'
							);

							tombol.prop('disabled', false);

							tombol.html(
								jenis === 'spv' ?
								'<i class="fas fa-check-double mr-1"></i> Verifikasi SPV' :
								'<i class="fas fa-check-circle mr-1"></i> Verifikasi Produksi'
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
							jenis === 'spv' ?
							'<i class="fas fa-check-double mr-1"></i> Verifikasi SPV' :
							'<i class="fas fa-check-circle mr-1"></i> Verifikasi Produksi'
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
</style>
