<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Verifikasi Residu Klorin</h1>
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

			<div id="pageLoading" class="page-loading" style="display: none;">
				<div class="spinner-border text-primary" role="status">
					<span class="sr-only">Loading...</span>
				</div>
				<div class="mt-2">Memuat data...</div>
			</div>

			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<div class="d-flex align-items-center flex-wrap mb-2">

						<input
							type="date"
							name="tanggal"
							id="tanggal_massal"
							class="form-control mr-2 mb-2"
							style="width: 170px;"
							value="<?= html_escape($filter_tanggal ?? ''); ?>"
							required>

						<button
							type="submit"
							form="formVerifikasiMassal"
							class="btn btn-primary mb-2">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>

						<button
							type="button"
							id="resetFilterResidu"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</button>

					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('residu/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('residu/audit'); ?>"
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
					Verifikasi massal akan memproses data Residu pada tanggal yang dipilih
					dengan status Created atau Revision.
				</small>
			<?php endif; ?>

			<hr>

			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Area</th>
							<th>Titik Sampling</th>
							<th>Hasil Pemeriksaan</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;

						foreach ($residu as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->area; ?></td>
								<td><?= $val->titik_sampling; ?></td>
								<td><?= $val->hasil_pemeriksaan; ?></td>

								<td class="text-center">
									<?php
									$tipe_user = (int) $this->session->userdata('tipe_user');

									if ($val->status_spv == '1') {
										echo '<span style="color:#28b463;font-weight:bold;font-size:12px;">Verified</span>';
									} elseif ($val->status_spv == '2') {
										echo '<span style="color:red;font-weight:bold;font-size:12px;">Revision</span>';
									} elseif ($tipe_user == 3 && $val->status_spv == '0') {
										echo '<span style="color:#99a3a4;font-weight:bold;font-size:12px;">Created</span>';
									}
									?>
								</td>

								<td class="text-center">
									<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
										<a href="<?= base_url('residu/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split">
											<span class="text">Update</span>
										</a>
									<?php endif; ?>

									<a href="<?= base_url('residu/detail/' . $val->uuid); ?>"
										data-url="<?= base_url('residu/detail/' . $val->uuid . '?popup=1'); ?>"
										class="btn btn-success btn-icon-split btn-detail-residu">
										<span class="text">Detail</span>
									</a>

									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('residu/status/' . $val->uuid); ?>"
											class="btn btn-info btn-icon-split">
											<span class="text">Verifikasi</span>
										</a>
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

		</div>
	</div>
</div>
<!-- Modal Detail Residu -->
<div class="modal fade" id="modalDetailResidu" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Verifikasi Residu Klorin</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailResidu">
				<div class="text-center py-5">
					<i class="fas fa-spinner fa-spin fa-2x"></i>
					<p class="mt-2">Memuat detail...</p>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {

		// =====================================================
		// 1. DATATABLES
		// =====================================================
		if ($.fn.DataTable) {
			$('#dataTable').DataTable({
				paging: false,
				searching: true,
				info: false,
				ordering: true,
				lengthChange: false
			});
		}

		// =====================================================
		// 2. FILTER TANGGAL (TETAP DI HALAMAN UTAMA RESIDU)
		// =====================================================
		function terapkanFilter() {
			var tanggal = $('#tanggal_massal').val();

			var url = '<?= base_url("residu"); ?>';

			if (tanggal) {
				url += '?tanggal=' + encodeURIComponent(tanggal);
			}

			$('#pageLoading').css('display', 'flex');

			window.location.href = url;
		}

		$('#tanggal_massal').on('change', function() {
			terapkanFilter();
		});

		// =====================================================
		// 3. RESET FILTER
		// =====================================================
		$('#resetFilterResidu').on('click', function() {
			$('#tanggal_massal').val('');

			$('#pageLoading').css('display', 'flex');

			window.location.href = '<?= base_url("residu"); ?>';
		});

		// =====================================================
		// 4. VERIFIKASI MASSAL
		// =====================================================
		$('#formVerifikasiMassal').on('submit', function(e) {
			e.preventDefault();

			var tanggal = $('#tanggal_massal').val();

			if (!tanggal) {
				Swal.fire({
					icon: 'warning',
					title: 'Pilih Tanggal',
					text: 'Silakan pilih tanggal terlebih dahulu.'
				});
				return;
			}

			Swal.fire({
				title: 'Verifikasi Massal?',
				text: 'Semua data Residu berstatus Created atau Revision pada tanggal ' +
					tanggal + ' akan diverifikasi.',
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#28a745',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Ya, Verifikasi',
				cancelButtonText: 'Batal'
			}).then((result) => {

				if (!result.isConfirmed) {
					return;
				}

				var form = $(this);
				var tombol = form.find('button[type="submit"]');
				var teksAwal = tombol.html();

				tombol.prop('disabled', true).html(
					'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
				);

				$.ajax({
					url: form.attr('action'),
					type: 'POST',
					data: {
						tanggal: tanggal
					},
					dataType: 'json',

					beforeSend: function() {
						Swal.fire({
							title: 'Memproses...',
							text: 'Sedang melakukan verifikasi Residu.',
							allowOutsideClick: false,
							didOpen: function() {
								Swal.showLoading();
							}
						});
					},

					success: function(response) {

						if (response.status) {
							Swal.fire({
								icon: 'success',
								title: 'Berhasil',
								text: response.message || 'Verifikasi massal berhasil.'
							}).then(() => {

								var url = '<?= base_url("residu"); ?>';

								if (tanggal) {
									url += '?tanggal=' + encodeURIComponent(tanggal);
								}

								window.location.href = url;
							});

						} else {
							Swal.fire({
								icon: 'info',
								title: 'Informasi',
								text: response.message || 'Tidak ada data yang dapat diverifikasi.'
							});
						}
					},

					error: function(xhr) {
						console.error(xhr.responseText);

						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: 'Terjadi kesalahan saat melakukan verifikasi massal.'
						});
					},

					complete: function() {
						tombol.prop('disabled', false).html(teksAwal);
					}
				});
			});
		});

		// =====================================================
		// 5. LOADING SAAT BERPINDAH HALAMAN
		// =====================================================
		$(document).on('click', '.pagination .page-link', function() {
			$('#pageLoading').css('display', 'flex');
		});

	});

	// =====================================================
	// 6. MODAL DETAIL RESIDU
	// =====================================================
	var cacheDetailResidu = {};

	$(document).on('click', '.btn-detail-residu', function(e) {
		e.preventDefault();

		var url = $(this).data('url');

		$('#modalDetailResidu').modal('show');

		if (cacheDetailResidu[url]) {
			$('#contentDetailResidu').html(cacheDetailResidu[url]);
			return;
		}

		$('#contentDetailResidu').html(`
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
				cacheDetailResidu[url] = response;
				$('#contentDetailResidu').html(response);
			},

			error: function() {
				$('#contentDetailResidu').html(`
                <div class="alert alert-danger text-center">
                    Gagal memuat detail Residu.
                </div>
            `);
			}
		});
	});
</script>

<style>
	.page-loading {
		position: fixed;
		inset: 0;
		z-index: 9999;
		background: rgba(255, 255, 255, 0.75);
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		font-weight: 600;
	}
</style>
