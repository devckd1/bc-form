<?php
$tipe_user = (int) $this->session->userdata('tipe_user');
$filter_tanggal = $filter_tanggal ?? '';
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Pemeriksaan Seasoning dari Pemasok</h1>
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

	<div class="card shadow mb-4">
		<div class="card-body">

			<!-- Filter dan Tombol Aksi -->
			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Filter Tanggal dan Verifikasi Massal -->
				<div class="d-flex align-items-center flex-wrap mb-2">

					<form
						id="formFilterTanggal"
						method="get"
						action="<?= base_url('seasoning'); ?>"
						class="form-inline mr-2 mb-2">

						<input
							type="date"
							name="tanggal"
							id="filterTanggal"
							class="form-control mr-2 mb-2"
							style="width: 170px;"
							value="<?= html_escape($filter_tanggal); ?>">

						<a
							href="<?= base_url('seasoning'); ?>"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>

					</form>

					<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>
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

					<a href="<?= base_url('seasoning/tambah') ?>"
						class="btn btn-md btn-primary shadow-sm">
						<i class="fas fa-plus fa-sm text-white-50"></i>
						Tambah
					</a>

					<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>
						<a href="<?= base_url('seasoning/audit'); ?>"
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
				<table class="table table-bordered" id="dataTableSeasoning" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Jenis Seasoning</th>
							<th>Spesifikasi</th>
							<th>Pemasok</th>
							<th>Kode Produksi</th>
							<th>Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;

						if (!empty($seasoning)):
							foreach ($seasoning as $val):
								$datetime = new DateTime($val->date);
						?>
								<tr>
									<td class="text-center"><?= $no++; ?></td>
									<td><?= $datetime->format('d-m-Y'); ?></td>
									<td><?= html_escape($val->jenis_seasoning); ?></td>
									<td><?= html_escape($val->spesifikasi); ?></td>
									<td><?= html_escape($val->pemasok); ?></td>
									<td><?= html_escape($val->kode_produksi); ?></td>

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
										<a href="<?= base_url('seasoning/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split">
											<span class="text">Edit</span>
										</a>

										<a href="<?= base_url('seasoning/detail/' . $val->uuid); ?>"
											data-url="<?= base_url('seasoning/detail/' . $val->uuid . '?popup=1'); ?>"
											class="btn btn-success btn-icon-split btn-detail-seasoning">
											<span class="text">Detail</span>
										</a>

										<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>
											<a href="<?= base_url('seasoning/status/' . $val->uuid); ?>"
												class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>
										<?php endif; ?>

										<!-- Tombol Delete asli tetap dikomentari -->
									</td>
								</tr>
							<?php
							endforeach;
						else:
							?>
							<tr>
								<td colspan="8" class="text-center">
									Tidak ada data Seasoning.
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<div class="mt-3">
				<?= $pagination ?? ''; ?>
			</div>

			<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>
				<br>
				<div class="mb-3">
					<form action="<?= base_url('seasoning/cetak') ?>"
						method="post"
						class="form-inline">

						<label for="tanggalCetak" class="mr-2 font-weight-bold">
							Pilih Tanggal:
						</label>

						<input
							type="date"
							name="tanggal"
							id="tanggalCetak"
							class="form-control mr-2 mb-2"
							required>

						<button type="submit" class="btn btn-success mb-2">
							<i class="fas fa-print fa-sm text-white-50"></i>
							Cetak PDF
						</button>
					</form>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>
<!-- Modal Detail Seasoning -->
<div class="modal fade" id="modalDetailSeasoning" tabindex="-1" role="dialog"
	aria-labelledby="modalDetailSeasoningLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailSeasoningLabel">
					Detail Pemeriksaan Seasoning
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailSeasoning">
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
	const cacheDetailSeasoning = {};

	$(document).on('click', '.btn-detail-seasoning', function(e) {
		e.preventDefault();

		const url = $(this).data('url');
		const $content = $('#contentDetailSeasoning');

		$('#modalDetailSeasoning').modal('show');

		if (cacheDetailSeasoning[url]) {
			$content.html(cacheDetailSeasoning[url]);
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
				cacheDetailSeasoning[url] = response;
				$content.html(response);
			},

			error: function() {
				$content.html(`
                <div class="alert alert-danger">
                    Gagal memuat detail Seasoning.
                </div>
            `);
			}
		});
	});
	$(document).ready(function() {

		// Filter tanggal otomatis pada halaman index Seasoning
		$('#filterTanggal').on('change', function() {
			$('#formFilterTanggal').submit();
		});

		// Pagination tetap dikelola CodeIgniter.
		// DataTables hanya menangani pencarian dan pengurutan.
		if ($.fn.DataTable) {
			$('#dataTableSeasoning').DataTable({
				ordering: true,
				searching: true,
				paging: false,
				info: false,
				lengthChange: false
			});
		}

		<?php if (in_array($tipe_user, [0, 1, 2, 9], true)): ?>

			// Verifikasi Massal SPV
			$('#btnVerifikasiMassal').on('click', function() {

				const tanggal = $('#filterTanggal').val();

				if (!tanggal) {
					Swal.fire({
						icon: 'warning',
						title: 'Tanggal belum dipilih',
						text: 'Silakan pilih tanggal terlebih dahulu.'
					});
					return;
				}

				Swal.fire({
					title: 'Verifikasi Massal?',
					text: 'Data Seasoning berstatus Created atau Revision pada tanggal ' +
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

					$.ajax({
						url: "<?= base_url('seasoning/verifikasi_massal'); ?>",
						type: "POST",
						dataType: "json",

						data: {
							tanggal: tanggal
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
										"<?= base_url('seasoning'); ?>?tanggal=" +
										encodeURIComponent(tanggal);
								}

							});
						},

						error: function(xhr) {
							Swal.fire({
								icon: 'error',
								title: 'Gagal',
								text: xhr.responseJSON?.message ||
									'Terjadi kesalahan saat melakukan verifikasi massal.'
							});
						},

						complete: function() {
							$('#btnVerifikasiMassal')
								.prop('disabled', false)
								.html('<i class="fas fa-check-double"></i> Verifikasi Massal');
						}
					});

				});

			});

		<?php endif; ?>

	});
</script>
