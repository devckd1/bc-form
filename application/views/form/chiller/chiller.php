<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Suhu Chiller</h1>
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

			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<?php
				$tipe_user = (int) $this->session->userdata('tipe_user');
				$akses_spv = in_array($tipe_user, [0, 1, 2, 9], true);
				$akses_produksi = in_array($tipe_user, [0, 1, 3, 9], true);
				?>

				<?php if ($akses_spv || $akses_produksi): ?>
					<div class="d-flex align-items-center flex-wrap mb-2">

						<form id="formVerifikasiMassalChiller"
							action="<?= base_url('chiller/verifikasi_massal'); ?>"
							method="post"
							class="d-flex align-items-center flex-wrap">

							<input type="date"
								name="tanggal"
								id="tanggal_massal_chiller"
								class="form-control mr-2 mb-2"
								style="width: 170px;"
								value="<?= html_escape($filter_tanggal ?? ''); ?>"
								required>

							<?php if ($akses_spv): ?>
								<button type="submit"
									name="jenis_verifikasi"
									value="spv"
									class="btn btn-primary mb-2 mr-1">
									<i class="fas fa-check-double mr-1"></i>
									Verifikasi Massal
								</button>
							<?php endif; ?>

							<?php if ($akses_produksi): ?>
								<button type="submit"
									name="jenis_verifikasi"
									value="produksi"
									class="btn btn-primary mb-2">
									<i class="fas fa-industry mr-1"></i>
									Verifikasi Massal
								</button>
							<?php endif; ?>

						</form>

						<button type="button"
							id="resetFilterChiller"
							class="btn btn-secondary ml-2 mb-2">
							<i class="fas fa-sync-alt mr-1"></i>
							Reset
						</button>

					</div>
				<?php endif; ?>

				<!-- KANAN: Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('chiller/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('chiller/audit'); ?>"
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
					Hanya data yang sudah diverifikasi Produksi yang akan diproses.
				</small>
			<?php endif; ?>

			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead class="text-center">
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Pukul</th>
							<th>Chiller 1</th>
							<th>Chiller 2</th>
							<th>Chiller 3</th>
							<th>Chiller 4</th>
							<th class="text-center">Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($chiller as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= $val->waktu; ?></td>
								<td><?= $val->chiller_1; ?></td>
								<td><?= $val->chiller_2; ?></td>
								<td><?= $val->chiller_3; ?></td>
								<td><?= $val->chiller_4; ?></td>
								<td class="text-center">
									<?php
									$tipe_user = (int) $this->session->userdata('tipe_user');

									if ($val->status_spv == '1') {
										echo '<span style="color: #28a745; font-weight: bold; font-size: 12px;">Verified</span>';
									} elseif ($val->status_spv == '2') {
										echo '<span style="color: red; font-weight: bold; font-size: 12px;">Revision</span>';
									} elseif ($val->status_produksi == '1' && $val->status_spv == '0') {
										echo '<span style="color: #28a745; font-weight: bold; font-size: 12px;">Approved</span>';
									} elseif ($tipe_user == 3 && $val->status_spv == '0') {
										echo '<span style="color: #99a3a4; font-weight: bold; font-size: 12px;">Created</span>';
									} elseif ($val->status_produksi == '0') {
										echo '<span style="color: #f39c12; font-weight: bold; font-size: 12px;">Menunggu Verifikasi Produksi</span>';
									}
									?>
								</td>
								<td class="text-center">
									<div class="action-buttons">

										<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
											<a href="<?= base_url('chiller/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split mb-1">
												<span class="text">Edit</span>
											</a>
										<?php endif; ?>

										<a href="<?= base_url('chiller/detail/' . $val->uuid); ?>"
											class="btn btn-success btn-icon-split mb-1 btn-detail-chiller"
											data-url="<?= base_url('chiller/detail/' . $val->uuid . '?popup=1'); ?>">
											<span class="text">Detail</span>
										</a>

										<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
											<a href="<?= base_url('chiller/verifikasi_produksi/' . $val->uuid); ?>"
												class="btn btn-primary btn-icon-split mb-1"
												onclick="return confirm('Apakah pemeriksaan ini yakin disetujui?');">
												<span class="text">Setujui</span>
											</a>
										<?php endif; ?>

										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

											<?php if (
												$val->status_produksi == '1' &&
												$val->status_spv == '0'
											): ?>
												<a href="<?= base_url('chiller/status/' . $val->uuid); ?>"
													class="btn btn-info btn-icon-split mb-1">
													<span class="text">Verifikasi</span>
												</a>

											<?php elseif (
												$val->status_produksi == '1' &&
												$val->status_spv == '2'
											): ?>
												<a href="<?= base_url('chiller/status/' . $val->uuid); ?>"
													class="btn btn-warning btn-icon-split mb-1">
													<span class="text">Verifikasi Ulang</span>
												</a>
											<?php endif; ?>

										<?php endif; ?>

									</div>
									<!-- <a href="<?= base_url('chiller/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                        <span class="text">Delete</span>
                                    </a> -->
								</td>
							</tr>
						<?php
							$no++;
						}
						?>
					</tbody>
				</table>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<div class="form-group">
					<form action="" method="post" class="form-inline">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" class="form-control mr-2" required>

						<button type="submit" formaction="<?= base_url('chiller/cetak') ?>" class="btn btn-success mr-2">
							<i class="fas fa-file-pdf fa-sm text-white-50"></i> Cetak PDF
						</button>

						<button type="submit" formaction="<?= base_url('chiller/export-excel') ?>" class="btn btn-primary">
							<i class="fas fa-file-excel fa-sm text-white-50"></i> Export Excel
						</button>
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="modalDetailChiller" tabindex="-1"
	role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title">Detail Pemeriksaan Suhu Chiller</h5>
				<button type="button" class="close" data-dismiss="modal">
					<span>&times;</span>
				</button>
			</div>

			<div class="modal-body" id="contentDetailChiller">
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

		const baseUrlChiller = "<?= base_url('chiller'); ?>";

		// Filter tanggal
		function terapkanFilterChiller() {

			const tanggal = $('#tanggal_massal_chiller').val();
			const params = new URLSearchParams();

			if (tanggal) {
				params.set('tanggal', tanggal);
			}

			const query = params.toString();

			window.location.href =
				baseUrlChiller + (query ? '?' + query : '');
		}

		$('#tanggal_massal_chiller').on('change', function() {
			terapkanFilterChiller();
		});

		// Reset filter
		$('#resetFilterChiller').on('click', function() {
			window.location.href = baseUrlChiller;
		});

		// Verifikasi massal SPV dan Produksi
		$('#formVerifikasiMassalChiller').on('submit', function(e) {

			e.preventDefault();

			const form = $(this);

			const submitter = e.originalEvent ?
				e.originalEvent.submitter :
				null;

			const jenis = submitter ?
				$(submitter).val() :
				'spv';

			const tombol = $(submitter);

			const tanggal = $('#tanggal_massal_chiller').val();

			if (!tanggal) {
				Swal.fire({
					icon: 'warning',
					title: 'Perhatian',
					text: 'Silakan pilih tanggal terlebih dahulu.'
				});
				return;
			}

			const judul = jenis === 'produksi' ?
				'Verifikasi Massal Produksi?' :
				'Verifikasi Massal SPV?';

			const keterangan = jenis === 'produksi' ?
				'Data Chiller QC dengan status Produksi Created pada tanggal tersebut akan diverifikasi.' :
				'Data Chiller QC dengan status Produksi Verified dan status SPV Created atau Revision pada tanggal tersebut akan diverifikasi.';

			const teksTombol = jenis === 'produksi' ?
				'Verifikasi Massal Produksi' :
				'Verifikasi Massal SPV';

			Swal.fire({
				title: judul,
				text: keterangan,
				icon: 'question',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Ya, Verifikasi',
				cancelButtonText: 'Batal'
			}).then(function(result) {

				if (!result.isConfirmed) {
					return;
				}

				tombol.prop('disabled', true);

				tombol.html(
					'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
				);

				let dataForm = form.serialize();

				// serialize() tidak mengambil tombol submit yang diklik
				dataForm += '&jenis_verifikasi=' +
					encodeURIComponent(jenis);

				$.ajax({
					url: form.attr('action'),
					type: 'POST',
					data: dataForm,
					dataType: 'json',

					success: function(response) {

						if (response.success && response.updated > 0) {

							Swal.fire({
								icon: 'success',
								title: 'Berhasil',
								text: response.message +
									' Jumlah data: ' +
									response.updated,
								confirmButtonText: 'OK'
							}).then(function() {
								location.reload();
							});

						} else {

							Swal.fire({
								icon: 'info',
								title: 'Informasi',
								text: response.message ||
									'Tidak ada data yang memenuhi syarat verifikasi.'
							});
						}
					},

					error: function() {

						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: 'Terjadi kesalahan saat memproses verifikasi massal.'
						});
					},

					complete: function() {

						tombol.prop('disabled', false);

						tombol.html(
							'<i class="fas fa-check-double mr-1"></i> ' +
							teksTombol
						);
					}
				});
			});
		});

	});

	// Modal Detail Chiller AJAX
	let cacheDetailChiller = {};

	$(document).on('click', '.btn-detail-chiller', function(e) {

		e.preventDefault();

		let url = $(this).data('url');

		$('#modalDetailChiller').modal('show');

		if (cacheDetailChiller[url]) {
			$('#contentDetailChiller').html(cacheDetailChiller[url]);
			return;
		}

		$('#contentDetailChiller').html(`
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
				cacheDetailChiller[url] = response;
				$('#contentDetailChiller').html(response);
			},

			error: function() {
				$('#contentDetailChiller').html(`
                <div class="alert alert-danger">
                    Gagal memuat detail pemeriksaan Chiller.
                </div>
            `);
			}
		});

	});
</script>
