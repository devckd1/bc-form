<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Verifikasi Proses Produksi</h1>
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
							value="<?= htmlspecialchars(
										$filter_tanggal ?? '',
										ENT_QUOTES,
										'UTF-8'
									); ?>">

						<select
							id="filter_shift"
							class="form-control mr-2 mb-2"
							style="width: 140px;">

							<option value="">Semua Shift</option>

							<?php foreach (['1', '2', '3'] as $shift): ?>
								<option
									value="<?= $shift; ?>"
									<?= (($filter_shift ?? '') === $shift)
										? 'selected'
										: ''; ?>>

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
								Verifikasi SPV

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
							href="<?= base_url('proses'); ?>"
							class="btn btn-secondary ml-2 mb-2">

							<i class="fas fa-sync-alt mr-1"></i>
							Reset

						</a>

					</div>

				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('proses/tambah'); ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('proses/audit'); ?>"
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
					Verifikasi massal hanya memproses data QC dengan status SPV Created atau Revision.
				</small>
			<?php endif; ?>

			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th class="text-center">Date</th>
							<th class="text-center">Shift</th>
							<th class="text-center">Types of Product</th>
							<th class="text-center">Kode Produksi</th>
							<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
								<th class="text-center">Finish Product</th>
							<?php endif; ?>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;
						foreach ($proses as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->shift; ?></td>
								<td><?= $val->nama_produk; ?></td>
								<td class="text-center">
									<?php
									$data = json_decode($val->proses_produksi, true);

									// Get the kode_produksi array
									$kodeList = $data['dough_mixing']['kode_produksi'] ?? [];

									// Find the first non-empty kode produksi
									$firstKode = '';
									foreach ($kodeList as $kp) {
										if (!empty($kp)) {
											$firstKode = $kp;
											break;
										}
									}

									echo $firstKode ?: '-';
									?>
								</td>
								<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
									<td class="text-center">
										<a href="<?= base_url('proses/packing/' . $val->uuid); ?>" class="btn btn-primary btn-icon-split">
											<span class="text">Finish Product</span>
										</a>
									</td>
								<?php endif; ?>
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
										<a href="<?= base_url('proses/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
											<span class="text">Edit</span>
										</a>
									<?php endif; ?>

									<a href="<?= base_url('proses/detail/' . $val->uuid); ?>"
										data-url="<?= base_url('proses/detail/' . $val->uuid . '?popup=1'); ?>"
										class="btn btn-success btn-icon-split btn-detail-proses">
										<span class="text">Detail</span>
									</a>

									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('proses/delete/' . $val->uuid); ?>"
											class="btn btn-danger btn-icon-split"
											onclick="return confirm('Yakin ingin menghapus data ini?')">
											<span class="text">Delete</span>
										</a>
									<?php endif; ?>

									<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
										<a href="<?= base_url('proses/verifikasi_produksi/' . $val->uuid); ?>"
											class="btn btn-primary btn-icon-split"
											onclick="return confirm('Apakah Anda yakin ingin menyetujui data ini?')">
											<span class="text">Setujui</span>
										</a>
									<?php endif; ?>

									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<?php if ($val->status_produksi == '1' && $val->status_spv == '0'): ?>
											<a href="<?= base_url('proses/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>
										<?php elseif ($val->status_produksi == '1' && $val->status_spv == '2'): ?>
											<a href="<?= base_url('proses/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
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
				</form>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<div class="form-group">
					<label>Pilih Data yang akan dicetak:</label>
					<form action="<?= base_url('proses/cetak') ?>" method="post" id="form_cetak_pdf">
						<div class="row mb-3">
							<div class="col-md-3">
								<label for="tanggal">Tanggal</label>
								<input type="date" name="tanggal" id="tanggal" class="form-control" required>
							</div>
							<div class="col-md-6 align-self-end">
								<button type="submit" class="btn btn-success">
									<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
								</button>
								<button type="button" class="btn btn-primary" id="btn_export_excel">
									<i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
								</button>
							</div>
						</div>
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
<!-- Modal Detail Proses -->
<div class="modal fade" id="modalDetailProses" tabindex="-1" role="dialog"
	aria-labelledby="modalDetailProsesLabel" aria-hidden="true">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailProsesLabel">
					Detail Proses Produksi
				</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailProses">
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

		let cacheDetailProses = {};

		$(document).on('click', '.btn-detail-proses', function(e) {
			e.preventDefault();

			let url = $(this).data('url');

			$('#modalDetailProses').modal('show');

			if (cacheDetailProses[url]) {
				$('#contentDetailProses').html(cacheDetailProses[url]);
				return;
			}

			$('#contentDetailProses').html(`
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
					cacheDetailProses[url] = response;
					$('#contentDetailProses').html(response);
				},

				error: function() {
					$('#contentDetailProses').html(`
                        <div class="alert alert-danger text-center">
                            Gagal memuat detail proses produksi.
                        </div>
                    `);
				}
			});
		});

	});
</script>
<script>
	$(document).ready(function() {

		/*
		 * FILTER TANGGAL DAN SHIFT
		 */
		function filterProses() {

			var tanggal = $('#filter_tanggal').val();
			var shift = $('#filter_shift').val();

			var params = new URLSearchParams();

			if (tanggal) {
				params.set('tanggal', tanggal);
			}

			if (shift) {
				params.set('shift', shift);
			}

			var query = params.toString();

			window.location.href = "<?= base_url('proses'); ?>" +
				(query ? '?' + query : '');
		}

		$('#filter_tanggal, #filter_shift').on('change', function() {
			filterProses();
		});


		/*
		 * VERIFIKASI MASSAL SPV DAN PRODUKSI
		 */
		$('.btn-verifikasi-massal').on('click', function() {

			var tombol = $(this);
			var jenis = tombol.data('jenis');

			var tanggal = $('#filter_tanggal').val();
			var shift = $('#filter_shift').val();

			var namaJenis = jenis === 'spv' ?
				'SPV' :
				'Produksi';

			if (!tanggal) {
				Swal.fire(
					'Perhatian',
					'Pilih tanggal terlebih dahulu.',
					'warning'
				);
				return;
			}

			var pesan = jenis === 'spv' ?
				'Data Proses dengan status SPV Created atau Revision pada tanggal dan shift terpilih akan diverifikasi.' :
				'Data Proses yang belum diverifikasi Produksi pada tanggal dan shift terpilih akan diproses.';

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

				tombol.prop('disabled', true);

				tombol.html(
					'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
				);

				$.ajax({
					url: "<?= base_url('proses/verifikasi_massal'); ?>",
					type: 'POST',
					dataType: 'json',

					data: {
						tanggal: tanggal,
						shift: shift || 'semua',
						jenis_verifikasi: jenis
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
