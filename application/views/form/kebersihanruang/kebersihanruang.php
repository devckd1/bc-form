<div class="container-fluid">

	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Kebersihan Ruang Produksi</h1>
	</div>

	<!-- Flash Messages -->
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
	?>

	<!-- Card -->
	<div class="card shadow mb-4">
		<div class="card-body">

			<!-- Button Tambah -->

			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

				<!-- Filter dan Verifikasi Massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

					<div class="d-flex align-items-center flex-wrap mb-2">

						<form id="formVerifikasiMassal"
							action="<?= base_url('kebersihanruang/verifikasi_massal'); ?>"
							method="post"
							class="d-flex align-items-center flex-wrap">

							<input type="date"
								id="filter_tanggal"
								name="tanggal"
								class="form-control mr-2 mb-2"
								style="width: 170px;"
								value="<?= htmlspecialchars($filter_tanggal ?? '', ENT_QUOTES, 'UTF-8'); ?>"
								required>

							<button type="submit"
								id="btnVerifikasiMassal"
								class="btn btn-primary mb-2">

								<i class="fas fa-check-double mr-1"></i>
								Verifikasi Massal

							</button>

						</form>

						<a href="<?= base_url('kebersihanruang'); ?>"
							class="btn btn-secondary ml-2 mb-2">

							<i class="fas fa-sync-alt mr-1"></i>
							Reset

						</a>

					</div>

				<?php endif; ?>


				<!-- Tombol Tambah dan Mode Audit -->
				<div class="text-right mb-2">

					<a href="<?= base_url('kebersihanruang/tambah') ?>"
						class="btn btn-md btn-primary shadow-sm">

						<i class="fas fa-plus fa-sm text-white-50"></i>
						Tambah

					</a>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

						<a href="<?= base_url('kebersihanruang/audit'); ?>"
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

					Hanya data yang sudah diverifikasi Produksi
					pada tanggal terpilih yang akan diproses.

				</small>

			<?php endif; ?>

			<hr>

			<!-- Table -->
			<div class="table-responsive">
				<table class="table table-bordered"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Lokasi</th>
							<th class="text-center">Hasil Pemeriksaan</th>
							<th>Supervisor</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>

						<?php
						$no = (int) ($start ?? 0) + 1;

						foreach ($kebersihanruang as $val):

							$tanggalFormatted = (new DateTime($val->date))
								->format('d-m-Y');
						?>

							<tr>

								<td class="text-center">
									<?= $no++; ?>
								</td>

								<td>
									<?= $tanggalFormatted . ' / ' . $val->shift; ?>
								</td>

								<td>
									<?= htmlspecialchars($val->lokasi); ?>
								</td>

								<td class="text-center">

									<?php if (!empty($val->detail)): ?>

										<button
											class="btn btn-info btn-sm view-detail"
											type="button"
											data-detail='<?= htmlspecialchars($val->detail, ENT_QUOTES, "UTF-8") ?>'
											data-foto1="<?= $val->foto1 ?? '' ?>"
											data-foto2="<?= $val->foto2 ?? '' ?>">
											View
										</button>

									<?php else: ?>

										-

									<?php endif; ?>

								</td>

								<td class="text-center">
									<?php
									$tipe_user = (int) $this->session->userdata('tipe_user');

									if ($val->status_spv == '1') {
										echo '<span style="color:#28a745;font-weight:bold;font-size:12px;">Verified</span>';
									} elseif ($val->status_spv == '2') {
										echo '<span style="color:red;font-weight:bold;font-size:12px;">Revision</span>';
									} elseif ($val->status_produksi == '1' && $val->status_spv == '0') {
										echo '<span style="color:#28a745;font-weight:bold;font-size:12px;">Approved</span>';
									} elseif ($tipe_user == 3 && $val->status_spv == '0') {
										echo '<span style="color:#99a3a4;font-weight:bold;font-size:12px;">Created</span>';
									} elseif ($val->status_produksi == '0') {
										echo '<span style="color:#f39c12;font-weight:bold;font-size:12px;">Menunggu Verifikasi Produksi</span>';
									}
									?>
								</td>
								<td class="text-center">
									<div class="action-buttons">

										<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
											<a href="<?= base_url('kebersihanruang/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split mb-1">
												<span class="text">Edit</span>
											</a>
										<?php endif; ?>

										<a href="<?= base_url('kebersihanruang/detail/' . $val->uuid); ?>"
											class="btn btn-success btn-icon-split mb-1">
											<span class="text">Detail</span>
										</a>

										<?php if (in_array($tipe_user, [0, 3, 9]) && $val->status_produksi != '1'): ?>
											<a href="<?= base_url('kebersihanruang/verifikasi_produksi/' . $val->uuid); ?>"
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
												<a href="<?= base_url('kebersihanruang/status/' . $val->uuid); ?>"
													class="btn btn-info btn-icon-split mb-1">
													<span class="text">Verifikasi</span>
												</a>

											<?php elseif (
												$val->status_produksi == '1' &&
												$val->status_spv == '2'
											): ?>
												<a href="<?= base_url('kebersihanruang/status/' . $val->uuid); ?>"
													class="btn btn-warning btn-icon-split mb-1">
													<span class="text">Verifikasi Ulang</span>
												</a>
											<?php endif; ?>

										<?php endif; ?>

									</div>
								</td>

							</tr>

						<?php endforeach; ?>

					</tbody>

				</table>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

				<br>
				<hr>
				<!-- Form Cetak PDF -->
				<div class="form-group">
					<form method="post" class="form-inline" id="formExport">

						<label for="tanggal" class="mr-2 font-weight-bold">
							Pilih Tanggal:
						</label>

						<input type="date" name="tanggal" class="form-control mr-2" required>

						<label for="shift" class="mr-2 font-weight-bold">
							Shift:
						</label>

						<select name="shift" class="form-control mr-2" required>
							<option value="">-- Pilih Shift --</option>
							<option value="1">Shift 1</option>
							<option value="2">Shift 2</option>
							<option value="3">Shift 3</option>
						</select>

						<button type="submit"
							formaction="<?= base_url('kebersihanruang/cetak') ?>"
							class="btn btn-success mr-2">
							<i class="fas fa-print fa-sm text-white-50"></i>
							Cetak PDF
						</button>

						<button type="submit"
							formaction="<?= base_url('kebersihanruang/export_excel') ?>"
							class="btn btn-primary">
							<i class="fas fa-file-excel"></i>
							Export Excel
						</button>

					</form>
				</div>
			<?php endif; ?>

		</div>
	</div>
</div>
</div>

<style>
	th {
		background-color: #f8f9fc;
	}
</style>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
	$(document).ready(function() {

		// destroy kalau sudah pernah init
		if ($.fn.DataTable.isDataTable('#dataTable')) {
			$('#dataTable').DataTable().destroy();
		}

		var kondisiMap = {
			'0': 'Bersih',
			'1': 'Berdebu',
			'2': 'Basah',
			'3': 'Pecah/Retak',
			'4': 'Sisa produksi (terigu/produk)',
			'5': 'Noda (tinta, karat)',
			'6': 'Pertumbuhan mikroorganisme (jamur/bau busuk)'
		};

		var table = $('#dataTable').DataTable({
			pageLength: 10,
			lengthMenu: [
				[10, 25, 50, 100],
				[10, 25, 50, 100]
			],
			ordering: false
		});

		$('#dataTable tbody').on('click', '.view-detail', function() {

			var tr = $(this).closest('tr');
			var row = table.row(tr);
			var foto1 = $(this).data('foto1');
			var foto2 = $(this).data('foto2');
			console.log(baseUrl);
			console.log(foto1);
			console.log(baseUrl + foto1);
			if (row.child.isShown()) {
				row.child.hide();
				tr.removeClass('shown');
				$(this).removeClass('btn-secondary').addClass('btn-info').text('View');
			} else {

				var details = JSON.parse($(this).attr('data-detail'));
				var baseUrl = "<?= base_url('uploads/kebersihan/') ?>";

				// Build foto HTML
				var fotoHtml = '';
				if (foto1) {
					fotoHtml += `
        <img 
            src="${baseUrl}${foto1}" 
            style="max-width:120px;margin:2px;border:1px solid #ddd;"
            onerror="this.style.display='none'; console.log(this.src)"
        />
                    `;
				}

				if (foto2) {
					fotoHtml += `
        <img 
            src="${baseUrl}${foto2}" 
            style="max-width:120px;margin:2px;border:1px solid #ddd;"
            onerror="this.style.display='none'; console.log(this.src)"
        />
                    `;
				}
				if (!foto1 && !foto2) fotoHtml = '-';

				var html = `
        <table class="table table-sm table-bordered mb-0">
            <thead style="background:#2E86C1;color:black;text-align:center;">
                <tr>
                    <th>No</th>
                    <th>Bagian</th>
                    <th>Kondisi</th>
                    <th>Problem</th>
                    <th>Tindakan</th>
                    <th>Verifikasi Ulang</th>
                </tr>
            </thead>
            <tbody>
                `;

				details.forEach(function(item, index) {
					html += `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>${item.bagian ?? '-'}</td>
                <td class="text-center">${kondisiMap[item.kondisi] ?? item.kondisi}</td>
                <td>${item.problem ?? '-'}</td>
                <td>${item.tindakan ?? '-'}</td>
                <td>${item.verifikasi ?? '-'}</td>
            </tr>
                    `;
				});

				html += `
            </tbody>
        </table>
        <!-- Foto di bawah tabel detail -->
        <div class="mt-2 mb-2" style="text-align:center;">
            <strong>Foto:</strong><br>
            ${fotoHtml}
        </div>
                `;

				row.child(html).show();
				tr.addClass('shown');
				$(this).removeClass('btn-info').addClass('btn-secondary').text('Hide');
			}
		});

	});
</script>

<script>
	$(document).ready(function() {

		// Filter tanggal otomatis
		$('#filter_tanggal').on('change', function() {

			var tanggal = $(this).val();

			if (tanggal) {
				window.location.href =
					"<?= base_url('kebersihanruang'); ?>?tanggal=" + tanggal;
			}

		});


		// Verifikasi massal
		$('#formVerifikasiMassal').on('submit', function(e) {

			e.preventDefault();

			var form = $(this);
			var tombol = $('#btnVerifikasiMassal');

			if (!form.find('[name="tanggal"]').val()) {

				Swal.fire(
					'Perhatian',
					'Silakan pilih tanggal terlebih dahulu.',
					'warning'
				);

				return;
			}

			Swal.fire({

				title: 'Verifikasi Massal?',

				text: 'Data Kebersihan Ruang yang sudah diverifikasi Produksi pada tanggal tersebut akan diproses.',

				icon: 'question',

				showCancelButton: true,

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

				$.ajax({

					url: form.attr('action'),

					type: 'POST',

					data: form.serialize(),

					dataType: 'json',

					success: function(response) {

						if (response.success) {

							Swal.fire({

								title: 'Berhasil',

								text: response.message +
									' Jumlah data: ' +
									response.updated,

								icon: 'success'

							}).then(function() {

								window.location.reload();

							});

						} else {

							Swal.fire(
								'Informasi',
								response.message,
								'info'
							);

							tombol.prop('disabled', false);

							tombol.html(
								'<i class="fas fa-check-double mr-1"></i> Verifikasi Massal'
							);

						}

					},

					error: function(xhr) {

						Swal.fire(
							'Error',
							'Terjadi kesalahan saat memproses verifikasi massal.',
							'error'
						);

						tombol.prop('disabled', false);

						tombol.html(
							'<i class="fas fa-check-double mr-1"></i> Verifikasi Massal'
						);

					}

				});

			});

		});

	});
</script>
