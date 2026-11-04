<?php
$tipe_user = $this->session->userdata('tipe_user');
$boleh_verifikasi = in_array($tipe_user, [0, 1, 2, 9]);

$filter_tanggal = $filter_tanggal ?? $tanggal ?? '';
$filter_shift = $filter_shift ?? $shift ?? '';
?>

<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Data Pemeriksaan Proses Pengemasan</h1>
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
			<i class="fas fa-exclamation-circle"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif ?>

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
							value="<?= html_escape($filter_tanggal) ?>">

						<select
							id="filterShift"
							class="form-control mr-2 mb-2"
							style="width: 140px;">
							<option value="" <?= $filter_shift == '' ? 'selected' : '' ?>>
								Semua Shift
							</option>
							<option value="1" <?= $filter_shift == '1' ? 'selected' : '' ?>>
								Shift 1
							</option>
							<option value="2" <?= $filter_shift == '2' ? 'selected' : '' ?>>
								Shift 2
							</option>
							<option value="3" <?= $filter_shift == '3' ? 'selected' : '' ?>>
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

						<a
							href="<?= base_url('pengemasan') ?>"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>

					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah -->
				<div class="text-right mb-2 ml-auto">
					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('pengemasan/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>
				</div>

			</div>

			<hr>

			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Pukul</th>
							<th>Nama Produk</th>
							<th>Kode Produksi / Exp Date</th>
							<th>Kadar Air</th>
							<th>Kondisi Produk</th>
							<th>Kondisi Seal</th>
							<th>Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;

						foreach ($pengemasan as $val):
							$datetime = new DateTime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime . " / " . $val->shift; ?></td>
								<td><?= date('H:i', strtotime($val->waktu)); ?></td>
								<td><?= $val->nama_produk; ?></td>
								<td><?= $val->kode_produksi . " / " . $val->best_before; ?></td>
								<td><?= $val->kadar_air; ?></td>
								<td><?= $val->kondisi_produk; ?></td>
								<td><?= $val->kondisi_seal; ?></td>
								<td class="text-center">
									<?php
									if ($val->status_spv == 0) {
										echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
									} elseif ($val->status_spv == 1) {
										echo '<span style="color: #28b463; font-weight: bold;">Verified</span>';
									} elseif ($val->status_spv == 2) {
										echo '<span style="color: red; font-weight: bold;">Revision</span>';
									}
									?>
								</td>
								<td class="text-center">
									<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
										<a href="<?= base_url('pengemasan/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split">
											<span class="text">Update</span>
										</a>
									<?php endif; ?>

									<a href="<?= base_url('pengemasan/detail/' . $val->uuid); ?>"
										data-url="<?= base_url('pengemasan/detail/' . $val->uuid . '?popup=1'); ?>"
										class="btn btn-success btn-icon-split btn-detail-pengemasan">
										<span class="text">Detail</span>
									</a>
								</td>
							</tr>
						<?php
							$no++;
						endforeach;
						?>
					</tbody>
				</table>
			</div>

		</div>
	</div>
</div>
<div class="modal fade" id="modalDetailPengemasan" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Pemeriksaan Pengemasan</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="contentDetailPengemasan">
				<div class="text-center py-5">
					<i class="fas fa-spinner fa-spin"></i> Memuat data...
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
		const cacheDetailPengemasan = {};

		$(document).on('click', '.btn-detail-pengemasan', function(e) {
			e.preventDefault();

			const url = $(this).data('url');
			const $content = $('#contentDetailPengemasan');

			$('#modalDetailPengemasan').modal('show');

			if (cacheDetailPengemasan[url]) {
				$content.html(cacheDetailPengemasan[url]);
				return;
			}

			$content.html(`
				<div class="text-center py-5">
					<i class="fas fa-spinner fa-spin"></i> Memuat data...
				</div>
			`);

			$.ajax({
				url: url,
				type: 'GET',
				dataType: 'html',
				success: function(response) {
					cacheDetailPengemasan[url] = response;
					$content.html(response);
				},
				error: function() {
					$content.html(`
						<div class="alert alert-danger mb-0">
							Gagal memuat detail pengemasan. Silakan coba lagi.
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

			// Filter otomatis berdasarkan tanggal dan shift
			function terapkanFilter() {
				const tanggal = $('#filterTanggal').val();
				const shift = $('#filterShift').val();

				let url = '<?= base_url('pengemasan') ?>';
				let params = [];

				if (tanggal) {
					params.push('tanggal=' + encodeURIComponent(tanggal));
				}

				if (shift) {
					params.push('shift=' + encodeURIComponent(shift));
				}

				if (params.length > 0) {
					url += '?' + params.join('&');
				}

				window.location.href = url;
			}

			$('#filterTanggal, #filterShift').on('change', function() {
				terapkanFilter();
			});

			// Verifikasi massal
			$('#btnVerifikasiMassal').on('click', function() {

				const tanggal = $('#filterTanggal').val();
				const shift = $('#filterShift').val();

				if (!tanggal) {
					Swal.fire({
						icon: 'warning',
						title: 'Tanggal belum dipilih',
						text: 'Silakan pilih tanggal terlebih dahulu.'
					});
					return;
				}

				let keteranganShift = shift ? 'Shift ' + shift : 'Semua Shift';

				Swal.fire({
					title: 'Verifikasi semua data?',
					text: 'Data Pengemasan tanggal ' + tanggal + ' (' + keteranganShift + ') yang memenuhi syarat akan diverifikasi.',
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Ya, verifikasi',
					cancelButtonText: 'Batal'
				}).then((result) => {

					if (!result.isConfirmed) {
						return;
					}

					$.ajax({
						url: '<?= base_url('pengemasan/verifikasi_massal') ?>',
						type: 'POST',
						dataType: 'json',
						data: {
							tanggal: tanggal,
							shift: shift
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
									let url = '<?= base_url('pengemasan') ?>';
									let params = [];

									if (tanggal) {
										params.push('tanggal=' + encodeURIComponent(tanggal));
									}

									if (shift) {
										params.push('shift=' + encodeURIComponent(shift));
									}

									if (params.length > 0) {
										url += '?' + params.join('&');
									}

									window.location.href = url;
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
								.html('<i class="fas fa-check-double"></i> Verifikasi');
						}
					});

				});
			});
		});
	</script>
<?php endif; ?>
