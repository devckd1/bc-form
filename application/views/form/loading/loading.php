<?php
$tipe_user = (int) $this->session->userdata('tipe_user');
$filter_tanggal = $filter_tanggal ?? '';
$filter_shift = $filter_shift ?? '';
?>

<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Pemeriksaan Loading Produk</h1>
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
			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- Filter tanggal, shift, dan verifikasi massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

					<div class="d-flex align-items-center flex-wrap mb-2">
						<form
							id="formFilterLoading"
							action="<?= base_url('loading'); ?>"
							method="get"
							class="d-flex align-items-center flex-wrap">

							<input
								type="date"
								id="filterTanggal"
								name="tanggal"
								class="form-control mr-2 mb-2"
								style="width: 170px;"
								value="<?= htmlspecialchars($filter_tanggal ?? '', ENT_QUOTES, 'UTF-8'); ?>">

							<select
								name="shift"
								id="filterShift"
								class="form-control mr-2 mb-2"
								style="width: 140px;">
								<option value="semua" <?= $filter_shift == 'semua' ? 'selected' : ''; ?>>Semua Shift</option>
								<option value="1" <?= $filter_shift == '1' ? 'selected' : ''; ?>>Shift 1</option>
								<option value="2" <?= $filter_shift == '2' ? 'selected' : ''; ?>>Shift 2</option>
								<option value="3" <?= $filter_shift == '3' ? 'selected' : ''; ?>>Shift 3</option>
							</select>

							<button
								type="button"
								class="btn btn-primary mb-2"
								id="btnVerifikasiMassal">
								<i class="fas fa-check-double mr-1"></i>
								Verifikasi Massal
							</button>
						</form>

						<a
							href="<?= base_url('loading'); ?>"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>
					</div>

				<?php endif; ?>

				<!-- Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('loading/tambah'); ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('loading/audit'); ?>"
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
				<form action="<?= base_url('loading/cetak') ?>" method="post" id="form_cetak_pdf">

					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="30px" class="text-center">
									<i class="fas fa-print fa-lg"></i>
								</th>
								<th width="20px" class="text-center">No</th>
								<th>Tanggal / Shift</th>
								<th>Start - Finish Loading</th>
								<th>Tujuan</th>
								<th style="text-align: center;">LOADING</th>
								<th>Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>

						<tbody>
							<?php
							$no = (int) ($start ?? 0) + 1;

							foreach ($loading as $val) {
								$datetime = new DateTime($val->date);
								$tanggalFormatted = $datetime->format('d-m-Y');

								$startTime = new DateTime($val->start_loading);
								$startFormatted = $startTime->format('H:i');

								$finishTime = new DateTime($val->finish_loading);
								$finishFormatted = $finishTime->format('H:i');

								$result = json_decode($val->loading, true);
							?>
								<tr>
									<td class="text-center">
										<input type="checkbox"
											name="checkbox[]"
											value="<?= $val->uuid ?>"
											class="select_row">
									</td>

									<td class="text-center"><?= $no; ?></td>

									<td><?= $tanggalFormatted . " / " . $val->shift ?></td>

									<td><?= $startFormatted . " / " . $finishFormatted; ?></td>

									<td><?= htmlspecialchars($val->tujuan) ?></td>

									<td>
										<table class="table table-sm table-bordered mb-0">
											<thead style="background-color:#2E86C1; color:gray; text-align:center;">
												<tr>
													<th width="30%">Nama Produk</th>
													<th width="20%">Kode Produksi</th>
													<th width="20%">Kode Expired</th>
													<th width="20%">Keterangan</th>
												</tr>
											</thead>

											<tbody>
												<?php if (!empty($result) && is_array($result)): ?>
													<?php foreach ($result as $row): ?>
														<tr>
															<td><?= htmlspecialchars($row['nama_produk'] ?? '-'); ?></td>
															<td style="text-align:center;"><?= htmlspecialchars($row['kode_produksi'] ?? '-'); ?></td>
															<td style="text-align:center;"><?= htmlspecialchars($row['expired'] ?? '-'); ?></td>
															<td style="text-align:center;"><?= htmlspecialchars($row['keterangan'] ?? '-'); ?></td>
														</tr>
													<?php endforeach ?>
												<?php else: ?>
													<tr>
														<td colspan="4" class="text-center">Tidak ada data</td>
													</tr>
												<?php endif ?>
											</tbody>
										</table>
									</td>

									<td class="text-center">
										<?php
										if ($val->status_spv == '1') {
											echo '<span style="color:#28b463;font-weight:bold;font-size:12px;">Verified</span>';
										} elseif ($val->status_spv == '2') {
											echo '<span style="color:red;font-weight:bold;font-size:12px;">Revision</span>';
										} elseif ($val->status_wh == '1' && $val->status_spv == '0') {
											echo '<span style="color:#28b463;font-weight:bold;font-size:12px;">Approved</span>';
										} elseif ($tipe_user == 3 && $val->status_spv == '0') {
											echo '<span style="color:#99a3a4;font-weight:bold;font-size:12px;">Created</span>';
										} elseif ($val->status_wh == '0') {
											echo '<span style="color:#f39c12;font-weight:bold;font-size:12px;">Menunggu Verifikasi Produksi</span>';
										}
										?>
									</td>

									<td class="text-center">
										<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
											<a href="<?= base_url('loading/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split">
												<span class="text">Edit</span>
											</a>
										<?php endif; ?>

										<a href="<?= base_url('loading/detail/' . $val->uuid); ?>"
											data-url="<?= base_url('loading/detail/' . $val->uuid . '?popup=1'); ?>"
											class="btn btn-success btn-icon-split btn-detail-loading">
											<span class="text">Detail</span>
										</a>

										<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_wh != '1'): ?>
											<a href="<?= base_url('loading/verifikasi_produksi/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split"
												onclick="return confirm('Apakah Anda yakin ingin menyetujui data ini?')">
												<span class="text">Setujui</span>
											</a>
										<?php endif; ?>

										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
											<?php if ($val->status_wh == '1' && $val->status_spv == '0'): ?>
												<a href="<?= base_url('loading/status/' . $val->uuid); ?>"
													class="btn btn-info btn-icon-split">
													<span class="text">Verifikasi</span>
												</a>
											<?php elseif ($val->status_wh == '1' && $val->status_spv == '2'): ?>
												<a href="<?= base_url('loading/status/' . $val->uuid); ?>"
													class="btn btn-info btn-icon-split">
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
<!-- Modal Detail Loading -->
<div class="modal fade" id="modalDetailLoading" tabindex="-1" role="dialog"
	aria-labelledby="modalDetailLoadingLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalDetailLoadingLabel">
					Detail Pemeriksaan Loading Produk
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailLoading">
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
	const cacheDetailLoading = {};

	$(document).on('click', '.btn-detail-loading', function(e) {
		e.preventDefault();

		const url = $(this).data('url');
		const $content = $('#contentDetailLoading');

		$('#modalDetailLoading').modal('show');

		if (cacheDetailLoading[url]) {
			$content.html(cacheDetailLoading[url]);
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
				cacheDetailLoading[url] = response;
				$content.html(response);
			},

			error: function() {
				$content.html(`
                <div class="alert alert-danger">
                    Gagal memuat detail Loading.
                </div>
            `);
			}
		});
	});
	$(document).ready(function() {

		// Auto-submit filter tanggal dan shift
		$('#filterTanggal, #filterShift').on('change', function() {
			$('#formFilterLoading').submit();
		});

		// Cetak PDF
		$('#form_cetak_pdf').on('submit', function(event) {
			let selectedCheckboxes = document.querySelectorAll('.select_row:checked');
			let selectedItems = [];

			selectedCheckboxes.forEach(function(checkbox) {
				selectedItems.push(checkbox.value);
			});

			if (selectedItems.length === 0) {
				event.preventDefault();
				alert("Silakan pilih data yang ingin dicetak.");
				return;
			}

			$('#selected_items').val(selectedItems.join(','));
		});

	});
</script>

<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
	<script>
		$(document).ready(function() {

			$('#btnVerifikasiMassal').on('click', function() {

				let tanggal = $('#filterTanggal').val();
				let shift = $('#filterShift').val();

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
					text: 'Data Loading tanggal ' + tanggal +
						' dengan filter shift ' + shift +
						' yang sudah diverifikasi Warehouse dan berstatus SPV Created atau Revision akan diverifikasi.',
					icon: 'question',
					showCancelButton: true,
					confirmButtonColor: '#28a745',
					cancelButtonColor: '#6c757d',
					confirmButtonText: 'Ya, Verifikasi',
					cancelButtonText: 'Batal'
				}).then((result) => {

					if (result.isConfirmed) {

						$.ajax({
							url: "<?= base_url('loading/verifikasi_massal'); ?>",
							type: "POST",
							dataType: "json",
							data: {
								tanggal: tanggal,
								shift: shift
							},
							beforeSend: function() {
								Swal.fire({
									title: 'Memproses...',
									text: 'Sedang melakukan verifikasi data Loading.',
									allowOutsideClick: false,
									didOpen: () => {
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
									}).then(() => {
										let url = "<?= base_url('loading'); ?>" +
											"?tanggal=" + encodeURIComponent(tanggal) +
											"&shift=" + encodeURIComponent(shift);

										window.location.href = url;
									});
								} else {
									Swal.fire({
										icon: 'info',
										title: 'Informasi',
										text: response.message
									});
								}
							},
							error: function() {
								Swal.fire({
									icon: 'error',
									title: 'Gagal',
									text: 'Terjadi kesalahan saat memproses verifikasi massal.'
								});
							}
						});

					}
				});
			});
		});
	</script>
<?php endif; ?>
