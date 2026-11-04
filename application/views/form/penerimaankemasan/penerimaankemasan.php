<?php
$tipe_user = $this->session->userdata('tipe_user');
$filter_tanggal = $filter_tanggal ?? $tanggal ?? '';
?>

<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Pemeriksaan Penerimaan Kemasan dari Supplier</h1>
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

	<div class="card shadow mb-4">
		<div class="card-body">

			<!-- Filter dan Tombol Aksi -->
			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Filter Tanggal dan Verifikasi Massal -->
				<div class="d-flex align-items-center flex-wrap mb-2">

					<form
						id="formFilterTanggal"
						method="get"
						action="<?= base_url('penerimaankemasan') ?>"
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
							value="<?= html_escape($filter_tanggal) ?>">

						<a
							href="<?= base_url('penerimaankemasan') ?>"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>
					</form>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<button
							type="button"
							id="btnVerifikasiMassal"
							class="btn btn-primary mb-2">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>
					<?php endif; ?>

				</div>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<a href="<?= base_url('penerimaankemasan/tambah') ?>"
						class="btn btn-md btn-primary shadow-sm">
						<i class="fas fa-plus fa-sm text-white-50"></i>
						Tambah
					</a>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('penerimaankemasan/audit'); ?>"
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
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Jenis Kemasan</th>
							<th>Pemasok</th>
							<th>Kode Produksi</th>
							<th>Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;

						if (!empty($penerimaankemasan)):
							foreach ($penerimaankemasan as $val):
								$datetime = new DateTime($val->date);
								$datetime = $datetime->format('d-m-Y');
						?>
								<tr>
									<td class="text-center"><?= $no++; ?></td>
									<td><?= $datetime; ?></td>
									<td><?= html_escape($val->jenis_kemasan); ?></td>
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
										<a href="<?= base_url('penerimaankemasan/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split">
											<span class="text">Edit</span>
										</a>

										<a href="<?= base_url('penerimaankemasan/detail/' . $val->uuid); ?>"
											data-url="<?= base_url('penerimaankemasan/detail/' . $val->uuid . '?popup=1'); ?>"
											class="btn btn-success btn-icon-split btn-detail-penerimaankemasan">
											<span class="text">Detail</span>
										</a>

										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
											<a href="<?= base_url('penerimaankemasan/status/' . $val->uuid); ?>"
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
								<td colspan="7" class="text-center">
									Tidak ada data Penerimaan Kemasan.
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<div class="mt-3">
				<?= $pagination ?? '' ?>
			</div>

			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<div class="mb-3">
					<form action="<?= base_url('penerimaankemasan/cetak') ?>"
						method="post"
						class="form-inline">

						<label for="tanggalCetak" class="mr-2 font-weight-bold">
							Pilih Tanggal:
						</label>

						<input
							type="date"
							name="tanggal"
							id="tanggalCetak"
							class="form-control mr-2"
							required>

						<button type="submit" class="btn btn-success">
							<i class="fas fa-print fa-sm text-white-50"></i>
							Cetak PDF
						</button>
					</form>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>
<!-- Modal Detail Penerimaan Kemasan -->
<div class="modal fade" id="modalDetailPenerimaanKemasan" tabindex="-1" role="dialog"
	aria-labelledby="modalDetailPenerimaanKemasanLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailPenerimaanKemasanLabel">
					Detail Penerimaan Kemasan
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailPenerimaanKemasan">
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
	const cacheDetailPenerimaanKemasan = {};

	$(document).on('click', '.btn-detail-penerimaankemasan', function(e) {
		e.preventDefault();

		const url = $(this).data('url');
		const $content = $('#contentDetailPenerimaanKemasan');

		$('#modalDetailPenerimaanKemasan').modal('show');

		if (cacheDetailPenerimaanKemasan[url]) {
			$content.html(cacheDetailPenerimaanKemasan[url]);
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
				cacheDetailPenerimaanKemasan[url] = response;
				$content.html(response);
			},

			error: function() {
				$content.html(`
                <div class="alert alert-danger">
                    Gagal memuat detail Penerimaan Kemasan.
                </div>
            `);
			}
		});
	});
	$(document).ready(function() {

		// Filter tanggal otomatis
		$('#filterTanggal').on('change', function() {
			$('#formFilterTanggal').submit();
		});

		<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

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
					title: 'Verifikasi semua data?',
					text: 'Data Penerimaan Kemasan pada tanggal ' + tanggal +
						' yang memenuhi kriteria verifikasi akan diproses.',
					icon: 'question',
					showCancelButton: true,
					confirmButtonText: 'Ya, verifikasi',
					cancelButtonText: 'Batal'
				}).then((result) => {

					if (!result.isConfirmed) {
						return;
					}

					$.ajax({
						url: '<?= base_url('penerimaankemasan/verifikasi_massal') ?>',
						type: 'POST',
						dataType: 'json',
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
										'<?= base_url('penerimaankemasan') ?>?tanggal=' +
										encodeURIComponent(tanggal);
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

		<?php endif; ?>

	});
</script>
