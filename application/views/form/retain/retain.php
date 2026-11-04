<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Retain Sample Report</h1>
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

				<!-- Filter tanggal dan verifikasi massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

					<div class="d-flex align-items-center flex-wrap mb-2">

						<form id="formVerifikasiMassal"
							action="<?= base_url('retain/verifikasi_massal'); ?>"
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

						<a href="<?= base_url('retain'); ?>"
							class="btn btn-secondary ml-2 mb-2">

							<i class="fas fa-sync-alt mr-1"></i>
							Reset

						</a>

					</div>

				<?php endif; ?>


				<!-- Tambah dan Mode Audit -->
				<div class="text-right mb-2 ml-auto">
					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('retain/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i>
							Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>

						<a href="<?= base_url('retain/audit'); ?>"
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

					Verifikasi massal akan memproses data Retain QC
					dengan status Created atau Revision pada tanggal terpilih.

				</small>

			<?php endif; ?>
			<hr>
			<div class="table-responsive">
				<form action="<?= base_url('retain/cetak') ?>" method="post" id="form_cetak_pdf">
					<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
						<thead>
							<tr>
								<th width="30px" class="text-center">
									<i class="fas fa-print fa-lg"></i>
								</th>
								<th width="20px" class="text-center">No</th>
								<th>Tanggal</th>
								<th>Plant</th>
								<th>Sample Type</th>
								<!-- <th>Nama Produk</th>
                            <th>Kode Produksi / Best Before</th> -->
								<th>Status</th>
								<th class="text-center">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = (int) ($start ?? 0) + 1;
							foreach ($retain as $val) {
								$datetime = new datetime($val->date);
								$datetime = $datetime->format('d-m-Y');
								$bb = new datetime($val->best_before);
								$bb = $bb->format('d-m-Y');
							?>
								<tr>
									<td class="text-center"><input type="checkbox" name="checkbox[]" value="<?= $val->uuid ?>" class="select_row"></td>
									<td class="text-center"><?= $no; ?></td>
									<td><?= $datetime; ?></td>
									<td><?= $val->nama_plant; ?></td>
									<td><?= $val->sample_type; ?></td>
									<!-- <td><?= $val->deskripsi; ?></td>
                                <td><?= $val->kode_produksi . " / " . $bb; ?></td> -->
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
											<a href="<?= base_url('retain/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
												<span class="text">Edit</span>
											</a>
										<?php endif; ?>
										<a href="<?= base_url('retain/detail/' . $val->uuid); ?>"
    data-url="<?= base_url('retain/detail/' . $val->uuid . '?popup=1'); ?>"
    class="btn btn-success btn-icon-split btn-detail-retain">
    <span class="text">Detail</span>
</a>
										<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
											<a href="<?= base_url('retain/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
												<span class="text">Verifikasi</span>
											</a>
										<?php endif; ?>
										<!--  <a href="<?= base_url('retain/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
					<input type="hidden" name="checkbox[]" id="selected_items">
				</form>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<div class="form-group">
					<label>Pilih Data yang akan dicetak:</label>
					<br>
					<button type="submit" form="form_cetak_pdf" class="btn btn-success">
						<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
					</button>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="modalDetailRetain" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Retain Sample</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="contentDetailRetain">
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

        let cacheDetailRetain = {};

        $(document).on('click', '.btn-detail-retain', function(e) {
            e.preventDefault();

            let url = $(this).data('url');
            let content = $('#contentDetailRetain');

            $('#modalDetailRetain').modal('show');

            if (cacheDetailRetain[url]) {
                content.html(cacheDetailRetain[url]);
                return;
            }

            content.html(`
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
                    cacheDetailRetain[url] = response;
                    content.html(response);
                },

                error: function() {
                    content.html(`
                        <div class="alert alert-danger text-center">
                            Gagal memuat detail Retain.
                        </div>
                    `);
                }
            });
        });

    });
</script>
<script>
	document.getElementById('select_all').addEventListener('change', function() {
		var checkboxes = document.querySelectorAll('.select_row');
		checkboxes.forEach(function(checkbox) {
			checkbox.checked = this.checked;
		});
	});

	document.getElementById('form_cetak_pdf').addEventListener('submit', function(event) {
		var selectedCheckboxes = document.querySelectorAll('.select_row:checked');
		var selectedItems = [];

		selectedCheckboxes.forEach(function(checkbox) {
			selectedItems.push(checkbox.value);
		});
		if (selectedItems.length === 0) {
			event.preventDefault();
			alert("Silakan pilih data yang ingin dicetak.");
			return;
		}
		document.getElementById('selected_items').value = selectedItems.join(',');
	});
</script>
<script>
	$(document).ready(function() {

		// Filter tanggal otomatis
		$('#filter_tanggal').on('change', function() {

			var tanggal = $(this).val();

			if (tanggal) {
				window.location.href =
					"<?= base_url('retain'); ?>?tanggal=" + tanggal;
			}

		});


		// Verifikasi massal SPV
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

				text: 'Data Retain QC dengan status Created atau Revision pada tanggal tersebut akan diverifikasi.',

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
