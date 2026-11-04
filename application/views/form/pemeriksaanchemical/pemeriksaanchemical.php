<?php
$tipe_user = (int) $this->session->userdata('tipe_user');
$filter_tanggal = $filter_tanggal ?? '';
$filter_shift = $filter_shift ?? '';
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Pemeriksaan Chemical dari Supplier
		</h1>
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg'); ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg'); ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<!-- Filter dan Tombol Aksi -->
			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
				<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>
					<!-- KIRI: Filter Tanggal, Shift, dan Verifikasi Massal -->
					<div class="d-flex align-items-center flex-wrap mb-2">

						<form
							id="formFilter"
							method="get"
							action="<?= base_url('pemeriksaanchemical'); ?>"
							class="form-inline mr-2 mb-2">

							<label for="filterTanggal" class="mr-2 font-weight-bold">
								Tanggal:
							</label>

							<input
								type="date"
								name="tanggal"
								id="filterTanggal"
								class="form-control mr-2 mb-2"
								style="width: 170px;"
								value="<?= html_escape($filter_tanggal); ?>">

							<select
								name="shift"
								id="filterShift"
								class="form-control mr-2 mb-2"
								style="width: 140px;">

								<option value="">Semua Shift</option>
								<option value="1" <?= $filter_shift == '1' ? 'selected' : ''; ?>>
									Shift 1
								</option>
								<option value="2" <?= $filter_shift == '2' ? 'selected' : ''; ?>>
									Shift 2
								</option>
								<option value="3" <?= $filter_shift == '3' ? 'selected' : ''; ?>>
									Shift 3
								</option>

							</select>

							<a
								href="<?= base_url('pemeriksaanchemical'); ?>"
								class="btn btn-secondary ml-2 mb-2">
								<i class="fas fa-sync-alt mr-1"></i>
								Reset
							</a>

						</form>


						<button
							type="button"
							class="btn btn-primary mb-2"
							id="btnVerifikasiMassal">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>
					<?php endif; ?>

					</div>

					<!-- KANAN: Tambah dan Mode Audit -->
					<div class="text-right mb-2 ml-auto">

						<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
							<a href="<?= base_url('pemeriksaanchemical/tambah'); ?>"
								class="btn btn-md btn-primary shadow-sm">
								<i class="fas fa-plus fa-sm text-white-50"></i>
								Tambah
							</a>
						<?php endif; ?>

						<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>
							<a href="<?= base_url('pemeriksaanchemical/audit'); ?>"
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

				<table class="table table-bordered"
					id="dataTablePemeriksaanChemical"
					width="100%"
					cellspacing="0">

					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Jenis Chemical</th>
							<th>Pemasok</th>
							<th>Kode Produksi</th>
							<th>Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>

						<?php
						$no = (int) ($start ?? 0) + 1;

						if (!empty($pemeriksaanchemical)):
							foreach ($pemeriksaanchemical as $val):

								$datetime = new DateTime($val->date);
						?>

								<tr>
									<td class="text-center">
										<?= $no++; ?>
									</td>

									<td>
										<?= $datetime->format('d-m-Y') . ' / ' . html_escape($val->shift); ?>
									</td>

									<td>
										<?= html_escape($val->jenis_chemical); ?>
									</td>

									<td>
										<?= html_escape($val->pemasok); ?>
									</td>

									<td>
										<?= html_escape($val->kode_produksi); ?>
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

									<?php if (in_array($tipe_user, [0, 1, 2, 4, 9], true)): ?>
										<a href="<?= base_url('pemeriksaanchemical/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-sm mb-1">
											Edit
										</a>
										<?php endif; ?>

										<a href="<?= base_url('pemeriksaanchemical/detail/' . $val->uuid); ?>"
											data-url="<?= base_url('pemeriksaanchemical/detail/' . $val->uuid . '?popup=1'); ?>"
											class="btn btn-success btn-sm mb-1 btn-detail-pemeriksaanchemical">
											Detail
										</a>

										<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>
											<a href="<?= base_url('pemeriksaanchemical/status/' . $val->uuid); ?>"
												class="btn btn-info btn-sm mb-1">
												Verifikasi
											</a>
										<?php endif; ?>

									</td>
								</tr>

							<?php
							endforeach;
						else:
							?>

							<tr>
								<td colspan="7" class="text-center">
									Tidak ada data Pemeriksaan Chemical.
								</td>
							</tr>

						<?php endif; ?>

					</tbody>

				</table>

			</div>

			<?php if (!empty($pagination)): ?>
				<div class="mt-3">
					<?= $pagination; ?>
				</div>
			<?php endif; ?>

			<div class="text-muted small text-center mb-3">
				Menampilkan <?= count($pemeriksaanchemical); ?> data

				<?php if (!empty($filter_tanggal)): ?>
					untuk tanggal
					<?= date('d-m-Y', strtotime($filter_tanggal)); ?>
				<?php endif; ?>

				<?php if ($filter_shift !== ''): ?>
					- Shift <?= html_escape($filter_shift); ?>
				<?php endif; ?>
			</div>

			<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>

				<hr>

				<div class="mb-3">
					<form action="<?= base_url('pemeriksaanchemical/cetak'); ?>"
						method="post"
						class="form-inline">

						<label for="tanggalCetak" class="mr-2 font-weight-bold">
							Pilih Tanggal Cetak PDF:
						</label>

						<input type="date"
							id="tanggalCetak"
							name="tanggal"
							class="form-control mr-2 mb-2"
							required>

						<button type="submit"
							class="btn btn-success mb-2">
							<i class="fas fa-print"></i>
							Cetak PDF
						</button>

					</form>
				</div>

			<?php endif; ?>

		</div>
	</div>
</div>
<!-- Modal Detail Pemeriksaan Chemical -->
<div class="modal fade" id="modalDetailPemeriksaanChemical" tabindex="-1" role="dialog"
	aria-labelledby="modalDetailPemeriksaanChemicalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailPemeriksaanChemicalLabel">
					Detail Pemeriksaan Chemical
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailPemeriksaanChemical">
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
	// Detail AJAX
	const cacheDetailPemeriksaanChemical = {};

	$(document).on('click', '.btn-detail-pemeriksaanchemical', function(e) {
		e.preventDefault();

		const url = $(this).data('url');
		const $content = $('#contentDetailPemeriksaanChemical');

		$('#modalDetailPemeriksaanChemical').modal('show');

		if (cacheDetailPemeriksaanChemical[url]) {
			$content.html(cacheDetailPemeriksaanChemical[url]);
			return;
		}

		$content.html(`
        <div class="text-center py-4">
            <div class="spinner-border" role="status"></div>
            <span> Memuat data...</span>
        </div>
    `);

		$.ajax({
			url: url,
			type: 'GET',
			dataType: 'html',

			success: function(response) {
				cacheDetailPemeriksaanChemical[url] = response;
				$content.html(response);
			},

			error: function() {
				$content.html(`
                <div class="alert alert-danger">
                    Gagal memuat detail Pemeriksaan Chemical.
                </div>
            `);
			}
		});
	});
	$(document).ready(function() {

		// Filter otomatis ketika tanggal atau shift berubah
		$('#filterTanggal, #filterShift').on('change', function() {
			$('#formFilter').submit();
		});

		<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>

			// Verifikasi Massal SPV
			$('#btnVerifikasiMassal').on('click', function() {

				const tanggal = $('#filterTanggal').val();
				const shift = $('#filterShift').val();

				if (!tanggal) {
					Swal.fire({
						icon: 'warning',
						title: 'Tanggal belum dipilih',
						text: 'Silakan pilih tanggal pemeriksaan terlebih dahulu.'
					});
					return;
				}

				Swal.fire({
					title: 'Verifikasi Massal?',
					text: 'Data Pemeriksaan Chemical pada tanggal ' + tanggal +
						(shift ? ' Shift ' + shift : ' semua shift') +
						' yang memenuhi kriteria verifikasi akan diproses.',
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

					$.ajax({
						url: "<?= base_url('pemeriksaanchemical/verifikasi_massal'); ?>",
						type: "POST",
						dataType: "json",

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
									window.location.href =
										"<?= base_url('pemeriksaanchemical'); ?>?tanggal=" +
										encodeURIComponent(tanggal) +
										'&shift=' + encodeURIComponent(shift);
								}

							});
						},

						error: function(xhr) {

							Swal.fire({
								icon: 'error',
								title: 'Gagal',
								text: xhr.responseJSON?.message ||
									'Terjadi kesalahan saat memproses verifikasi massal.'
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

		<?php endif; ?>

	});
</script>
