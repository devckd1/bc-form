<?php
$tipe_user = (int) $this->session->userdata('tipe_user');
$boleh_verifikasi = in_array($tipe_user, [0, 1, 2, 9]);

$filter_tanggal = $filter_tanggal ?? $tanggal ?? '';

function tampilkan_analisis($kode)
{
	$map = [
		'1' => 'Moisture',
		'2' => 'Salinity',
		'3' => 'pH',
		'4' => 'Bulk Density',
		'5' => 'TPC',
		'6' => 'Enterobacter',
		'7' => 'Salmonella',
		'8' => 'Yeast and Mold',
	];

	return $map[$kode] ?? $kode;
}
?>

<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Permohonan Analisis Sampel Laboratorium</h1>
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

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<?php if ($boleh_verifikasi): ?>
					<div class="d-flex align-items-center flex-wrap mb-2">

						<input
							type="date"
							id="filterTanggal"
							class="form-control mr-2 mb-2"
							style="width: 170px;"
							value="<?= html_escape($filter_tanggal) ?>">

						<button
							type="button"
							id="btnVerifikasiMassal"
							class="btn btn-primary mb-2">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi Massal
						</button>

						<a
							href="<?= base_url('analisis') ?>"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</a>

					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('analisis/tambah'); ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if ($boleh_verifikasi): ?>
						<a href="<?= base_url('analisis/audit'); ?>"
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
					<thead class="text-center">
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Tipe Sampel</th>
							<th>Penyimpanan</th>
							<th>Nama Produk</th>
							<th>Kode Produksi / Best Before</th>
							<th>Permintaan Analisis</th>
							<th class="text-center">Status</th>
							<th>LAB</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (int) ($start ?? 0) + 1;

						foreach ($analisis as $val):
							$datetime = new DateTime($val->date);
							$tanggalFormatted = $datetime->format('d-m-Y');

							$bb = new DateTime($val->best_before);
							$bestBeforeFormatted = $bb->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $tanggalFormatted; ?></td>
								<td><?= htmlspecialchars($val->tipe_sampel); ?></td>
								<td><?= htmlspecialchars($val->penyimpanan); ?></td>
								<td><?= htmlspecialchars($val->nama_produk); ?></td>
								<td>
									<?= htmlspecialchars($val->kode_produksi) . " / " . $bestBeforeFormatted; ?>
								</td>
								<td>
									<?php
									$nilai = explode(',', $val->analisis);
									$hasil = array_map(
										'tampilkan_analisis',
										array_map('trim', $nilai)
									);

									echo htmlspecialchars(implode(', ', $hasil));
									?>
								</td>

								<td class="text-center">
									<?php
									if ($val->status_spv == '1') {
										echo '<span style="color:#28b463;font-weight:bold;font-size:12px;">Verified</span>';
									} elseif ($val->status_spv == '2') {
										echo '<span style="color:red;font-weight:bold;font-size:12px;">Revision</span>';
									} elseif ($val->status_lab == '1' && $val->status_spv == '0') {
										echo '<span style="color:#28b463;font-weight:bold;font-size:12px;">Approved</span>';
									} elseif ($tipe_user == 3 && $val->status_spv == '0') {
										echo '<span style="color:#99a3a4;font-weight:bold;font-size:12px;">Created</span>';
									} elseif ($val->status_lab == '0') {
										echo '<span style="color:#f39c12;font-weight:bold;font-size:12px;">Menunggu Verifikasi Produksi</span>';
									}
									?>
								</td>

								<td class="text-center">
									<?php
									if ($val->status_lab == 0) {
										echo '<span style="color:#99a3a4;font-weight:bold;">Created</span>';
									} elseif ($val->status_lab == 1) {
										echo '<span style="color:#28b463;font-weight:bold;">Accepted</span>';
									} elseif ($val->status_lab == 2) {
										echo '<span style="color:red;font-weight:bold;">Returned</span>';
									}
									?>
								</td>

								<td class="text-center">
									<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
										<a href="<?= base_url('analisis/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split">
											<span class="text">Edit</span>
										</a>

										<a href="<?= base_url('analisis/analis/' . $val->uuid); ?>"
											class="btn btn-danger btn-icon-split">
											<span class="text">Analisis</span>
										</a>
									<?php endif; ?>

									<a href="<?= base_url('analisis/detail/' . $val->uuid); ?>"
										data-url="<?= base_url('analisis/detail/' . $val->uuid . '?popup=1'); ?>"
										class="btn btn-success btn-icon-split btn-detail-analisis">
										<span class="text">Detail</span>
									</a>

									<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_lab != '1'): ?>
										<a href="<?= base_url('analisis/verifikasi_produksi/' . $val->uuid); ?>"
											class="btn btn-primary btn-icon-split"
											onclick="return confirm('Apakah Anda yakin ingin menyetujui data ini?')">
											<span class="text">Setujui</span>
										</a>
									<?php endif; ?>

									<?php if ($boleh_verifikasi): ?>
										<?php if ($val->status_lab == '1' && $val->status_spv == '0'): ?>
											<a href="<?= base_url('analisis/status/' . $val->uuid); ?>"
												class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>
										<?php elseif ($val->status_lab == '1' && $val->status_spv == '2'): ?>
											<a href="<?= base_url('analisis/status/' . $val->uuid); ?>"
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

			<!-- Cetak PDF -->
			<?php if ($boleh_verifikasi): ?>
				<br>
				<hr>
				<div class="form-group">
					<form action="<?= base_url('analisis/cetak') ?>"
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
							<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
						</button>
					</form>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>

<!-- Modal Detail Analisis -->
<div class="modal fade" id="detailModal" tabindex="-1"
	aria-labelledby="detailModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="detailModalLabel">
					Detail Permohonan Analisis Sampel Laboratorium
				</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" id="detailModalBody">
				<div class="text-center">
					<div class="spinner-border" role="status"></div>
					<span> Loading...</span>
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

		// Filter otomatis berdasarkan tanggal
		$('#filterTanggal').on('change', function() {
			const tanggal = $(this).val();
			let url = '<?= base_url('analisis') ?>';

			if (tanggal) {
				url += '?tanggal=' + encodeURIComponent(tanggal);
			}

			window.location.href = url;
		});

		// Detail AJAX
		// Detail AJAX
		const cacheDetailAnalisis = {};

		$(document).on('click', '.btn-detail-analisis', function(e) {
			e.preventDefault();

			const url = $(this).data('url');
			const $content = $('#detailModalBody');

			$('#detailModal').modal('show');

			if (cacheDetailAnalisis[url]) {
				$content.html(cacheDetailAnalisis[url]);
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
					cacheDetailAnalisis[url] = response;
					$content.html(response);
				},
				error: function() {
					$content.html(
						'<div class="alert alert-danger">Gagal memuat detail Analisis.</div>'
					);
				}
			});
		});

		<?php if ($boleh_verifikasi): ?>

			// Verifikasi massal
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
					text: 'Data Analisis pada tanggal ' + tanggal +
						' yang sudah Accepted oleh LAB dan berstatus SPV Created atau Revision akan diverifikasi.',
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
						url: '<?= base_url('analisis/verifikasi_massal'); ?>',
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
										'<?= base_url('analisis') ?>?tanggal=' +
										encodeURIComponent(tanggal);
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
