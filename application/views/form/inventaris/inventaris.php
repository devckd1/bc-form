<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Checklist Inventaris Peralatan QC Bread Crumb</h1>
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
	$filter_tanggal = $filter_tanggal ?? '';
	$filter_shift = $filter_shift ?? '';
	?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 3, 9])): ?>

					<div class="d-flex align-items-center flex-wrap mb-2">

						<input
							type="date"
							id="filterTanggal"
							class="form-control mr-2 mb-2"
							style="width: 170px;"
							value="<?= htmlspecialchars($filter_tanggal ?? '', ENT_QUOTES, 'UTF-8'); ?>">

						<select
							id="filterShift"
							class="form-control mr-2 mb-2"
							style="width: 140px;">

							<option value="" <?= ($filter_shift ?? '') == '' ? 'selected' : ''; ?>>
								Semua Shift
							</option>

							<option value="1" <?= ($filter_shift ?? '') == '1' ? 'selected' : ''; ?>>
								Shift 1
							</option>

							<option value="2" <?= ($filter_shift ?? '') == '2' ? 'selected' : ''; ?>>
								Shift 2
							</option>

							<option value="3" <?= ($filter_shift ?? '') == '3' ? 'selected' : ''; ?>>
								Shift 3
							</option>

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
							href="<?= base_url('inventaris'); ?>"
							class="btn btn-secondary ml-2 mb-2">

							<i class="fas fa-sync-alt mr-1"></i>
							Reset

						</a>

					</div>

				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('inventaris/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('inventaris/audit'); ?>"
							class="btn shadow-sm"
							style="background-color: #5a3298; color: #fff;">
							<i class="fas fa-clipboard-check mr-1"></i>
							Mode Audit
						</a>
					<?php endif; ?>

				</div>
			</div>

			<hr>

			<div class="table-responsive">

				<form action="<?= base_url('inventaris/cetak') ?>"
					method="post"
					id="form_cetak_pdf">

					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead class="text-center">
							<tr>
								<th width="30px" class="text-center">
									<input type="checkbox" id="select_all">
								</th>
								<th>No</th>
								<th>Tanggal / Shift</th>
								<th class="text-center">Supervisor</th>
								<th>Action</th>
							</tr>
						</thead>

						<tbody>
							<?php
							$no = (int) ($start ?? 0) + 1;

							foreach ($inventaris as $val) {
								$datetime = new DateTime($val->date);
								$tanggalFormatted = $datetime->format('d-m-Y');

								$equipment = json_decode($val->peralatan, true);
							?>
								<tr>
									<td class="text-center">
										<input type="checkbox"
											name="checkbox[]"
											value="<?= $val->uuid ?>"
											class="select_row">
									</td>

									<td class="text-center">
										<?= $no; ?>
									</td>

									<td>
										<?= $tanggalFormatted . " / " . $val->shift; ?>
									</td>

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
											<a href="<?= base_url('inventaris/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split">
												<span class="text">Edit</span>
											</a>

											<a href="<?= base_url('inventaris/delete/' . $val->uuid); ?>"
												class="btn btn-danger btn-icon-split"
												onclick="return confirm('Yakin ingin menghapus data ini?')">
												<span class="text">Delete</span>
											</a>
										<?php endif; ?>

										<a href="<?= base_url('inventaris/detail/' . $val->uuid); ?>"
											data-url="<?= base_url('inventaris/detail/' . $val->uuid . '?popup=1'); ?>"
											class="btn btn-success btn-icon-split btn-detail-inventaris">
											<span class="text">Detail</span>
										</a>

										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
											<a href="<?= base_url('inventaris/status/' . $val->uuid); ?>"
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

					<input type="hidden" name="checkbox[]" id="selected_items">

				</form>
			</div>

			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>

				<div class="form-group">
					<label>Pilih Data yang akan dicetak:</label>
					<br>

					<button type="submit"
						form="form_cetak_pdf"
						class="btn btn-success">
						<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
					</button>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>
<!-- Modal Detail Inventaris -->
<div class="modal fade" id="modalDetailInventaris" tabindex="-1" role="dialog"
	aria-labelledby="modalDetailInventarisLabel" aria-hidden="true">

	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailInventarisLabel">
					Detail Inventaris
				</h5>

				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailInventaris">
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

		const baseUrl = "<?= base_url('inventaris'); ?>";
		const verifikasiUrl = "<?= base_url('inventaris/verifikasi_massal'); ?>";

		// ==========================================
		// FILTER TANGGAL DAN SHIFT OTOMATIS
		// ==========================================
		function applyFilter() {

			const tanggal = $('#filterTanggal').val();
			const shift = $('#filterShift').val();

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

		$('#filterTanggal, #filterShift').on('change', function() {
			applyFilter();
		});


		// ==========================================
		// PILIH SEMUA CHECKBOX
		// ==========================================
		$('#select_all').on('change', function() {
			$('.select_row').prop('checked', this.checked);
		});

		$('.select_row').on('change', function() {

			const total = $('.select_row').length;
			const checked = $('.select_row:checked').length;

			$('#select_all').prop(
				'checked',
				total > 0 && total === checked
			);
		});


		// ==========================================
		// CETAK PDF
		// ==========================================
		$('#form_cetak_pdf').on('submit', function(event) {

			const selectedCheckboxes = $('.select_row:checked');
			const selectedItems = [];

			selectedCheckboxes.each(function() {
				selectedItems.push(this.value);
			});

			if (selectedItems.length === 0) {
				event.preventDefault();
				alert('Silakan pilih data yang ingin dicetak.');
				return;
			}

			$('#selected_items').val(selectedItems.join(','));
		});


		// ==========================================
		// VERIFIKASI MASSAL SPV DAN PRODUKSI
		// ==========================================
		$('.btn-verifikasi-massal').on('click', function() {

			const tombol = $(this);
			const jenis = tombol.data('jenis');

			const tanggal = $('#filterTanggal').val();
			const shift = $('#filterShift').val();

			const namaJenis = jenis === 'spv' ? 'SPV' : 'Produksi';

			// Validasi tanggal
			if (!tanggal) {
				Swal.fire({
					icon: 'warning',
					title: 'Pilih Tanggal',
					text: 'Silakan pilih tanggal terlebih dahulu.'
				});
				return;
			}

			const keteranganShift = shift ?
				'Shift ' + shift :
				'Semua Shift';

			// Pesan konfirmasi
			const pesan = jenis === 'spv' ?
				'Data Inventaris dengan status SPV Created atau Revision pada tanggal ' +
				tanggal + ' (' + keteranganShift + ') akan diverifikasi.' :
				'Data Inventaris yang belum diverifikasi Produksi pada tanggal ' +
				tanggal + ' (' + keteranganShift + ') akan diverifikasi.';

			Swal.fire({
				title: 'Verifikasi Massal ' + namaJenis + '?',
				text: pesan,
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#4e73df',
				cancelButtonColor: '#858796',
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

						tombol.prop('disabled', true).html(
							'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
						);

						Swal.fire({
							title: 'Memproses...',
							text: 'Sedang melakukan verifikasi Inventaris oleh ' +
								namaJenis + '.',
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
								text: response.message
							}).then(function() {

								// Pertahankan filter setelah verifikasi
								const params = new URLSearchParams();

								params.set('tanggal', tanggal);

								if (shift) {
									params.set('shift', shift);
								}

								window.location.href =
									baseUrl + '?' + params.toString();
							});

						} else {

							Swal.fire({
								icon: 'info',
								title: 'Informasi',
								text: response.message
							});

							tombol.prop('disabled', false).html(
								jenis === 'spv' ?
								'<i class="fas fa-check-double mr-1"></i> Verifikasi SPV' :
								'<i class="fas fa-check-circle mr-1"></i> Verifikasi Produksi'
							);
						}
					},

					error: function(xhr) {

						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: 'Terjadi kesalahan saat memproses verifikasi massal.'
						});

						tombol.prop('disabled', false).html(
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
<script>
	$(document).ready(function() {

		let cacheDetailInventaris = {};

		$(document).on('click', '.btn-detail-inventaris', function(e) {
			e.preventDefault();

			let url = $(this).data('url');

			$('#modalDetailInventaris').modal('show');

			if (cacheDetailInventaris[url]) {
				$('#contentDetailInventaris').html(cacheDetailInventaris[url]);
				return;
			}

			$('#contentDetailInventaris').html(`
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
					cacheDetailInventaris[url] = response;
					$('#contentDetailInventaris').html(response);
				},

				error: function() {
					$('#contentDetailInventaris').html(`
                        <div class="alert alert-danger text-center">
                            Gagal memuat detail inventaris.
                        </div>
                    `);
				}
			});
		});

	});
</script>
