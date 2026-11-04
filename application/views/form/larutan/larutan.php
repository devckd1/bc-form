<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Verifikasi Pembuatan Larutan Cleaning dan Sanitasi
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

	$filter_tanggal = $tanggal ?? '';
	$filter_shift = $shift ?? '';
	?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<!-- FILTER DAN TOMBOL -->
			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<?php if ($boleh_verifikasi): ?>
					<div class="d-flex align-items-center flex-wrap mb-2">

						<div class="input-group mr-2 mb-2" style="width: 170px;">
							<div class="input-group-prepend">
							</div>
							<input
								type="date"
								id="filterTanggal"
								class="form-control"
								value="<?= html_escape($filter_tanggal) ?>">
						</div>

						<select
							id="filterShift"
							class="form-control mr-2 mb-2"
							style="width: 140px;">
							<option value="">Semua Shift</option>
							<option value="1" <?= $filter_shift == '1' ? 'selected' : '' ?>>Shift 1</option>
							<option value="2" <?= $filter_shift == '2' ? 'selected' : '' ?>>Shift 2</option>
							<option value="3" <?= $filter_shift == '3' ? 'selected' : '' ?>>Shift 3</option>
						</select>

						<button
							type="button"
							id="btnVerifikasiMassal"
							class="btn btn-primary mb-2">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>

						<button
							type="button"
							id="resetFilterLarutan"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</button>

					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('larutan/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('larutan/audit') ?>"
							class="btn shadow-sm"
							style="background-color: #5a3298; color: #fff;">
							<i class="fas fa-clipboard-check mr-1"></i>
							Mode Audit
						</a>
					<?php endif; ?>

				</div>
			</div>

			<hr>

			<!-- TABEL -->
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th class="text-center align-middle" width="60">No</th>
							<th class="text-center align-middle">Tanggal / Shift</th>
							<th class="text-center align-middle">Status</th>
							<th class="text-center align-middle" width="320">Action</th>
						</tr>
					</thead>

					<tbody>
						<?php if (!empty($larutan)): ?>
							<?php
							$no = (int) ($start ?? 0) + 1;

							foreach ($larutan as $val):
								$datetime = new DateTime($val->date);
								$tanggalFormatted = $datetime->format('d-m-Y');
							?>
								<tr>
									<td class="text-center align-middle">
										<?= $no++; ?>
									</td>

									<td class="text-center align-middle">
										<?= $tanggalFormatted . ' / Shift ' . html_escape($val->shift); ?>
									</td>

									<td class="text-center align-middle">
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

									<td class="text-center align-middle">
										<a href="<?= base_url('larutan/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-sm">
											Edit
										</a>

										<a href="<?= base_url('larutan/detail/' . $val->uuid); ?>"
											data-url="<?= base_url('larutan/detail/' . $val->uuid . '?popup=1'); ?>"
											class="btn btn-success btn-sm btn-detail-larutan">
											Detail
										</a>

										<a href="<?= base_url('larutan/delete/' . $val->uuid); ?>"
											class="btn btn-danger btn-sm"
											onclick="return confirm('Yakin ingin menghapus data ini?')">
											Delete
										</a>

										<?php if ($boleh_verifikasi): ?>
											<a href="<?= base_url('larutan/status/' . $val->uuid); ?>"
												class="btn btn-info btn-sm">
												Verifikasi
											</a>
										<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="4" class="text-center">
									Tidak ada data Larutan.
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<!-- PAGINATION -->
			<div class="mt-3">
				<?= $pagination ?? '' ?>
			</div>

			<!-- CETAK PDF -->
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<hr>

				<div class="form-group">
					<form action="<?= base_url('larutan/cetak') ?>"
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

						<label for="shiftCetak" class="mr-2 font-weight-bold">
							Shift:
						</label>

						<select name="shift"
							id="shiftCetak"
							class="form-control mr-2"
							required>
							<option value="">-- Pilih Shift --</option>
							<option value="1">Shift 1</option>
							<option value="2">Shift 2</option>
							<option value="3">Shift 3</option>
						</select>

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

<!-- LOADING OVERLAY -->
<div id="pageLoading" class="page-loading">
	<div class="spinner-border text-primary" role="status"></div>
	<div class="mt-2 font-weight-bold">Memuat data...</div>
</div>
<!-- Modal Detail Larutan -->
<div class="modal fade" id="modalDetailLarutan" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Pembuatan Larutan Cleaning dan Sanitasi</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailLarutan">
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
		text-align: center;
		vertical-align: middle;
	}

	.page-loading {
		display: none;
		position: fixed;
		z-index: 99999;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(255, 255, 255, 0.75);
		align-items: center;
		justify-content: center;
		flex-direction: column;
	}
</style>
<script>
	// =====================================================
	// MODAL DETAIL LARUTAN
	// =====================================================
	var cacheDetailLarutan = {};

	$(document).on('click', '.btn-detail-larutan', function(e) {
		e.preventDefault();

		var url = $(this).data('url');

		$('#modalDetailLarutan').modal('show');

		if (cacheDetailLarutan[url]) {
			$('#contentDetailLarutan').html(cacheDetailLarutan[url]);
			return;
		}

		$('#contentDetailLarutan').html(`
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
				cacheDetailLarutan[url] = response;
				$('#contentDetailLarutan').html(response);
			},

			error: function() {
				$('#contentDetailLarutan').html(`
                <div class="alert alert-danger text-center">
                    Gagal memuat detail Larutan.
                </div>
            `);
			}
		});
	});
</script>
<?php if ($boleh_verifikasi): ?>
	<script>
		$(document).ready(function() {

			const baseUrl = '<?= base_url('larutan') ?>';

			// FILTER OTOMATIS KETIKA TANGGAL ATAU SHIFT BERUBAH
			function terapkanFilter() {
				const tanggal = $('#filterTanggal').val();
				const shift = $('#filterShift').val();

				let url = baseUrl;
				const params = [];

				if (tanggal) {
					params.push('tanggal=' + encodeURIComponent(tanggal));
				}

				if (shift) {
					params.push('shift=' + encodeURIComponent(shift));
				}

				if (params.length > 0) {
					url += '?' + params.join('&');
				}

				$('#pageLoading').css('display', 'flex');
				window.location.href = url;
			}

			$('#filterTanggal, #filterShift').on('change', function() {
				terapkanFilter();
			});

			// RESET FILTER
			$('#resetFilterLarutan').on('click', function() {
				$('#pageLoading').css('display', 'flex');
				window.location.href = baseUrl;
			});


			// VERIFIKASI MASSAL SPV
			$('#btnVerifikasiMassal').on('click', function() {
				const tanggal = $('#filterTanggal').val();
				const shift = $('#filterShift').val();

				if (!tanggal) {
					Swal.fire({
						icon: 'warning',
						title: 'Tanggal belum dipilih',
						text: 'Pilih tanggal terlebih dahulu.'
					});
					return;
				}

				Swal.fire({
					title: 'Verifikasi semua data?',
					text: 'Data Larutan tanggal ' + tanggal +
						(shift ? ' Shift ' + shift : ' semua shift') +
						' yang memenuhi syarat akan diverifikasi.',
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Ya, verifikasi',
					cancelButtonText: 'Batal'
				}).then((result) => {
					if (!result.isConfirmed) {
						return;
					}

					$.ajax({
						url: '<?= base_url('larutan/verifikasi_massal') ?>',
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
									let url = baseUrl + '?tanggal=' +
										encodeURIComponent(tanggal);

									if (shift) {
										url += '&shift=' + encodeURIComponent(shift);
									}

									$('#pageLoading').css('display', 'flex');
									window.location.href = url;
								}
							});
						},
						error: function() {
							Swal.fire({
								icon: 'error',
								title: 'Gagal',
								text: 'Terjadi kesalahan saat memproses verifikasi.'
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


			// LOADING SAAT PINDAH HALAMAN PAGINATION
			$('.pagination a').on('click', function() {
				$('#pageLoading').css('display', 'flex');
			});
		});
	</script>
<?php endif; ?>
