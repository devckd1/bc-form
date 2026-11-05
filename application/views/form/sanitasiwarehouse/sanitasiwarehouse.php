<?php
$tipe_user = (int) $this->session->userdata('tipe_user');
$boleh_verifikasi = in_array($tipe_user, [0, 1, 2, 9]);

$filter_tanggal = $filter_tanggal ?? $tanggal ?? '';
?>

<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Pemeriksaan Sanitasi Warehouse</h1>
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

				<?php
				$tipe_user = (int) $this->session->userdata('tipe_user');

				$boleh_verifikasi_spv = in_array($tipe_user, [0, 1, 2, 9], true);
				$boleh_verifikasi_produksi = in_array($tipe_user, [0, 1, 3, 9], true);
				?>

				<?php if ($boleh_verifikasi_spv || $boleh_verifikasi_produksi): ?>
					<div class="d-flex align-items-center flex-wrap mb-2">

						<input
							type="date"
							id="filterTanggal"
							class="form-control mr-2 mb-2"
							style="width: 170px;"
							value="<?= html_escape($filter_tanggal ?? '') ?>">

						<?php if ($boleh_verifikasi_spv): ?>
							<button
								type="button"
								class="btn btn-primary mr-1 mb-2 btnVerifikasiMassal"
								data-jenis="spv">
								<i class="fas fa-check-double mr-1"></i>
								Verifikasi Massal SPV
							</button>
						<?php endif; ?>

						<?php if ($boleh_verifikasi_produksi): ?>
							<button
								type="button"
								class="btn btn-primary mr-1 mb-2 btnVerifikasiMassal"
								data-jenis="produksi">
								<i class="fas fa-industry mr-1"></i>
								Verifikasi Massal
							</button>
						<?php endif; ?>

						<a
							href="<?= base_url('sanitasiwarehouse') ?>"
							class="btn btn-secondary mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>

					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('sanitasiwarehouse/tambah'); ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if ($boleh_verifikasi): ?>
						<a href="<?= base_url('sanitasiwarehouse/audit'); ?>"
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
							<th>Area</th>
							<th>Hasil Pemeriksaan</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;

						foreach ($sanitasiwarehouse as $val):
							$datetime = new DateTime($val->date);
							$tanggalFormatted = $datetime->format('d-m-Y');

							$result = json_decode($val->detail, true);

							$kondisiMap = [
								'0' => 'Bersih',
								'1' => 'Berdebu',
								'2' => 'Basah',
								'3' => 'Sampah (sisa lakban, kertas, remah produk/bahan baku, plastik, kardus bekas)',
								'4' => 'Pertumbuhan mikroorganisme (jamur dan bau busuk)',
								'5' => 'Pallet rusak/pecah',
								'6' => 'Terdapat aktifitas binatang (tikus, kecoa, lalat, ulat, belatung)',
								'7' => 'Sarang laba-laba',
							];
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $tanggalFormatted; ?></td>
								<td><?= htmlspecialchars($val->area); ?></td>
								<td>
									<table class="table table-sm table-bordered mb-0">
										<thead style="background-color:#2E86C1; color:gray; text-align:center;">
											<tr>
												<th width="30%">Titik Pemeriksaan</th>
												<th width="20%">Kondisi</th>
												<th width="20%">Masalah</th>
												<th width="30%">Tindakan Koreksi</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($result) && is_array($result)): ?>
												<?php foreach ($result as $row): ?>
													<tr>
														<td><?= htmlspecialchars($row['bagian'] ?? '-'); ?></td>
														<td style="text-align:center;">
															<?= $kondisiMap[$row['kondisi'] ?? ''] ?? htmlspecialchars($row['kondisi'] ?? '-'); ?>
														</td>
														<td style="text-align:center;">
															<?= htmlspecialchars($row['problem'] ?? '-'); ?>
														</td>
														<td style="text-align:center;">
															<?= htmlspecialchars($row['tindakan'] ?? '-'); ?>
														</td>
													</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="4" class="text-center">Tidak ada data</td>
												</tr>
											<?php endif; ?>
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
										<a href="<?= base_url('sanitasiwarehouse/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split">
											<span class="text">Edit</span>
										</a>
									<?php endif; ?>

									<a href="<?= base_url('sanitasiwarehouse/detail/' . $val->uuid); ?>"
										data-url="<?= base_url('sanitasiwarehouse/detail/' . $val->uuid . '?popup=1'); ?>"
										class="btn btn-success btn-icon-split btn-detail-sanitasiwarehouse">
										<span class="text">Detail</span>
									</a>

									<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_wh != '1'): ?>
										<a href="<?= base_url('sanitasiwarehouse/verifikasi_produksi/' . $val->uuid); ?>"
											class="btn btn-primary btn-icon-split"
											onclick="return confirm('Apakah Anda yakin ingin menyetujui data ini?')">
											<span class="text">Setujui</span>
										</a>
									<?php endif; ?>

									<?php if ($boleh_verifikasi): ?>
										<?php if ($val->status_wh == '1' && $val->status_spv == '0'): ?>
											<a href="<?= base_url('sanitasiwarehouse/status/' . $val->uuid); ?>"
												class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>
										<?php elseif ($val->status_wh == '1' && $val->status_spv == '2'): ?>
											<a href="<?= base_url('sanitasiwarehouse/status/' . $val->uuid); ?>"
												class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi Ulang</span>
											</a>
										<?php endif; ?>
									<?php endif; ?>
								</td>
							</tr>
						<?php
							$no++;
						endforeach;
						?>
					</tbody>
				</table>
			</div>

			<?php if ($boleh_verifikasi): ?>
				<br>
				<div class="mb-3">
					<form action="<?= base_url('sanitasiwarehouse/cetak') ?>"
						method="post"
						class="form-inline">
						<label for="tanggalCetak" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input
							type="date"
							name="tanggal"
							id="tanggalCetak"
							class="form-control mr-2"
							required>
						<button type="submit" class="btn btn-success">
							<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
						</button>
					</form>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>
<div class="modal fade" id="modalDetailSanitasiWarehouse" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Detail Sanitasi Warehouse</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="contentDetailSanitasiWarehouse">
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

<?php if ($boleh_verifikasi): ?>
	<script>
		$(document).ready(function() {

			// Filter tanggal
			$('#filterTanggal').on('change', function() {
				const tanggal = $(this).val();
				let url = '<?= base_url('sanitasiwarehouse') ?>';

				if (tanggal) {
					url += '?tanggal=' + encodeURIComponent(tanggal);
				}

				window.location.href = url;
			});

			// Verifikasi massal SPV dan Produksi
			$('.btnVerifikasiMassal').on('click', function() {

				const tombol = $(this);
				const jenis = tombol.data('jenis');
				const tanggal = $('#filterTanggal').val();

				const namaJenis = jenis === 'spv' ? 'SPV' : 'Produksi';

				if (!tanggal) {
					Swal.fire({
						icon: 'warning',
						title: 'Tanggal belum dipilih',
						text: 'Silakan pilih tanggal terlebih dahulu.'
					});
					return;
				}

				const keterangan = jenis === 'spv' ?
					'Data Sanitasi Warehouse pada tanggal ' + tanggal +
					' yang memenuhi status Warehouse dan berstatus SPV Created atau Revision akan diverifikasi.' :
					'Data Sanitasi Warehouse berstatus Produksi Created pada tanggal ' +
					tanggal + ' akan diverifikasi.';

				Swal.fire({
					title: 'Verifikasi Massal ' + namaJenis + '?',
					text: keterangan,
					icon: 'question',
					showCancelButton: true,
					confirmButtonColor: jenis === 'spv' ? '#007bff' : '#28a745',
					cancelButtonColor: '#6c757d',
					confirmButtonText: 'Ya, Verifikasi',
					cancelButtonText: 'Batal'
				}).then(function(result) {

					if (!result.isConfirmed) {
						return;
					}

					const teksAwal = tombol.html();

					$.ajax({
						url: '<?= base_url('sanitasiwarehouse/verifikasi_massal'); ?>',
						type: 'POST',
						dataType: 'json',
						data: {
							tanggal: tanggal,
							jenis_verifikasi: jenis
						},

						beforeSend: function() {
							tombol
								.prop('disabled', true)
								.html(
									'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
								);

							Swal.fire({
								title: 'Memproses...',
								text: 'Sedang melakukan verifikasi Sanitasi Warehouse oleh ' + namaJenis + '.',
								allowOutsideClick: false,
								didOpen: function() {
									Swal.showLoading();
								}
							});
						},

						success: function(response) {
							Swal.fire({
								icon: response.status ? 'success' : 'info',
								title: response.status ? 'Berhasil' : 'Informasi',
								text: response.message
							}).then(function() {

								if (response.status) {
									let url = '<?= base_url('sanitasiwarehouse') ?>';

									if (tanggal) {
										url += '?tanggal=' + encodeURIComponent(tanggal);
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
									'Terjadi kesalahan saat memproses verifikasi massal.'
							});
						},

						complete: function() {
							tombol
								.prop('disabled', false)
								.html(teksAwal);
						}
					});
				});
			});
		});
	</script>
<?php endif; ?>

<script>
	$(document).ready(function() {
		const cacheDetailSanitasiWarehouse = {};

		$(document).on('click', '.btn-detail-sanitasiwarehouse', function(e) {
			e.preventDefault();

			const url = $(this).data('url');
			const $content = $('#contentDetailSanitasiWarehouse');

			$('#modalDetailSanitasiWarehouse').modal('show');

			if (cacheDetailSanitasiWarehouse[url]) {
				$content.html(cacheDetailSanitasiWarehouse[url]);
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
					cacheDetailSanitasiWarehouse[url] = response;
					$content.html(response);
				},
				error: function() {
					$content.html(`
						<div class="alert alert-danger mb-0">
							Gagal memuat detail Sanitasi Warehouse. Silakan coba lagi.
						</div>
					`);
				}
			});
		});
	});
</script>
