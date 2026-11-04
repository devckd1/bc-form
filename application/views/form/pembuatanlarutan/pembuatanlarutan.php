
<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Pemeriksaan Pembuatan Larutan
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

	<?php
	$tipe_user = $this->session->userdata('tipe_user');
	?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<!-- LOADING OVERLAY -->
			<div id="pageLoading" class="page-loading" style="display: none;">
				<div class="spinner-border text-primary" role="status">
					<span class="sr-only">Loading...</span>
				</div>
				<div class="mt-2">Memuat data...</div>
			</div>

			<div class="d-flex justify-content-between align-items-center flex-wrap mb-3">

    <!-- KIRI: FILTER DAN VERIFIKASI MASSAL -->
    <?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
        <div class="d-flex align-items-center flex-wrap mb-2">

            <input
                type="date"
                id="tanggal_massal"
                class="form-control mr-2 mb-2"
                style="width: 170px;"
                value="<?= htmlspecialchars($filter_tanggal ?? '', ENT_QUOTES, 'UTF-8'); ?>">

            <button
                type="button"
                id="btnVerifikasiMassal"
                class="btn btn-primary mb-2">
                <i class="fas fa-check-double mr-1"></i>
                Verifikasi Massal
            </button>

            <button
                type="button"
                id="resetFilterPembuatanLarutan"
                class="btn btn-secondary ml-2 mb-2">
                <i class="fas fa-sync-alt mr-1"></i>
                Reset
            </button>

        </div>
    <?php endif; ?>

    <!-- KANAN: TAMBAH DAN MODE AUDIT -->
    <div class="text-right mb-2 ml-auto">

        <?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
            <a href="<?= base_url('pembuatanlarutan/tambah'); ?>"
                class="btn btn-md btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i>
                Tambah
            </a>
        <?php endif; ?>

        <?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
            <a href="<?= base_url('pembuatanlarutan/audit'); ?>"
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
        Verifikasi massal akan memproses data Pembuatan Larutan pada tanggal yang dipilih
        dengan status Created atau Revision.
    </small>
<?php endif; ?>

<hr>

			<div class="table-responsive">

				<table class="table table-bordered"
					id="dataTablePembuatanLarutan"
					width="100%"
					cellspacing="0">

					<thead>
						<tr>
							<th width="20px" class="text-center" rowspan="2">
								No
							</th>
							<th rowspan="2">Tanggal / Pukul</th>
							<th rowspan="2">Area</th>
							<th rowspan="2">Nama Chemical</th>
							<th rowspan="2">Expired</th>
							<th rowspan="2">Konsentrasi</th>
							<th colspan="2" class="text-center">
								Pengenceran
							</th>
							<th rowspan="2">Supervisor</th>
							<th rowspan="2" class="text-center">Action</th>
						</tr>
						<tr>
							<th>Larutan Beku</th>
							<th>Air</th>
						</tr>
					</thead>

					<tbody>

						<?php
						$no = (int) ($start ?? 0) + 1;

						if (!empty($pembuatanlarutan)):
							foreach ($pembuatanlarutan as $val):

								$datetime = new DateTime($val->date);
								$datetime = $datetime->format('d-m-Y');

								$bb = new DateTime($val->expired);
								$bb = $bb->format('d-m-Y');

								$timing = new DateTime($val->pukul);
								$timing = $timing->format('H:i');
						?>

								<tr>
									<td class="text-center">
										<?= $no++; ?>
									</td>

									<td>
										<?= $datetime . ' / ' . $timing; ?>
									</td>

									<td>
										<?= html_escape($val->area); ?>
									</td>

									<td>
										<?= html_escape($val->nama_chemical); ?>
									</td>

									<td>
										<?= $bb; ?>
									</td>

									<td>
										<?= html_escape($val->konsentrasi); ?>
									</td>

									<td>
										<?= html_escape($val->larutan_beku); ?>
									</td>

									<td>
										<?= html_escape($val->air); ?>
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

										<a href="<?= base_url('pembuatanlarutan/edit/' . $val->uuid); ?>"
											class="btn btn-warning btn-icon-split mb-1">
											<span class="text">Edit</span>
										</a>

										<a href="<?= base_url('pembuatanlarutan/detail/' . $val->uuid); ?>"
    data-url="<?= base_url('pembuatanlarutan/detail/' . $val->uuid . '?popup=1'); ?>"
    class="btn btn-success btn-icon-split mb-1 btn-detail-pembuatanlarutan">
    <span class="text">Detail</span>
</a>

										<a href="<?= base_url('pembuatanlarutan/delete/' . $val->uuid); ?>"
											class="btn btn-danger btn-icon-split mb-1"
											onclick="return confirm('Yakin ingin menghapus data ini?')">
											<span class="text">Delete</span>
										</a>

									</td>
								</tr>

							<?php
							endforeach;
						else:
							?>

							<tr>
								<td colspan="10" class="text-center">
									Tidak ada data Pembuatan Larutan.
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

			<div class="text-center text-muted small mb-3">
				Menampilkan <?= count($pembuatanlarutan); ?> data

				<?php if (!empty($filter_tanggal)): ?>
					untuk tanggal
					<?= date('d-m-Y', strtotime($filter_tanggal)); ?>
				<?php endif; ?>
			</div>

		</div>
	</div>
</div>
<!-- Modal Detail Pembuatan Larutan -->
<div class="modal fade" id="modalDetailPembuatanLarutan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pembuatan Larutan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body" id="contentDetailPembuatanLarutan">
                <div class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p class="mt-2">Memuat detail...</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	$(document).ready(function() {

		// =====================================================
		// 1. FILTER TANGGAL OTOMATIS
		// =====================================================
		$('#tanggal_massal').on('change', function() {

			let tanggal = $(this).val();
			let url = "<?= base_url('pembuatanlarutan'); ?>";

			if (tanggal) {
				url += '?tanggal=' + encodeURIComponent(tanggal);
			}

			$('#pageLoading').css('display', 'flex');

			window.location.href = url;
		});

		// =====================================================
		// 2. RESET FILTER
		// =====================================================
		$('#resetFilterPembuatanLarutan').on('click', function() {

			$('#pageLoading').css('display', 'flex');

			window.location.href = "<?= base_url('pembuatanlarutan'); ?>";
		});

		// =====================================================
		// 3. VERIFIKASI MASSAL
		// =====================================================
		$('#btnVerifikasiMassal').on('click', function() {

			let tanggal = $('#tanggal_massal').val();

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
				text: 'Seluruh data Pembuatan Larutan dengan status Created atau Revision pada tanggal ' + tanggal + ' akan diverifikasi.',
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

				let tombol = $('#btnVerifikasiMassal');
				let teksAwal = tombol.html();

				tombol.prop('disabled', true).html(
					'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
				);

				$.ajax({
					url: "<?= base_url('pembuatanlarutan/verifikasi_massal'); ?>",
					type: "POST",
					dataType: "json",

					data: {
						tanggal: tanggal
					},

					beforeSend: function() {
						Swal.fire({
							title: 'Memproses...',
							text: 'Sedang melakukan verifikasi Pembuatan Larutan.',
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
								text: response.message || 'Verifikasi massal berhasil.'
							}).then(() => {

								let url = "<?= base_url('pembuatanlarutan'); ?>";

								if (tanggal) {
									url += '?tanggal=' + encodeURIComponent(tanggal);
								}

								window.location.href = url;

							});

						} else {

							Swal.fire({
								icon: 'info',
								title: 'Informasi',
								text: response.message || 'Tidak ada data yang dapat diverifikasi.'
							});

						}
					},

					error: function(xhr) {

						console.error(xhr.responseText);

						Swal.fire({
							icon: 'error',
							title: 'Gagal',
							text: 'Terjadi kesalahan saat memproses verifikasi massal.'
						});

					},

					complete: function() {
						tombol.prop('disabled', false).html(teksAwal);
					}
				});

			});

		});

		// =====================================================
		// 4. LOADING SAAT PAGINATION
		// =====================================================
		$(document).on('click', '.pagination .page-link', function() {
			$('#pageLoading').css('display', 'flex');
		});

	});

	// =====================================================
// 5. MODAL DETAIL PEMBUATAN LARUTAN
// =====================================================
var cacheDetailPembuatanLarutan = {};

$(document).on('click', '.btn-detail-pembuatanlarutan', function(e) {
    e.preventDefault();

    var url = $(this).data('url');

    $('#modalDetailPembuatanLarutan').modal('show');

    if (cacheDetailPembuatanLarutan[url]) {
        $('#contentDetailPembuatanLarutan').html(
            cacheDetailPembuatanLarutan[url]
        );
        return;
    }

    $('#contentDetailPembuatanLarutan').html(`
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
            cacheDetailPembuatanLarutan[url] = response;

            $('#contentDetailPembuatanLarutan').html(response);
        },

        error: function() {
            $('#contentDetailPembuatanLarutan').html(`
                <div class="alert alert-danger text-center">
                    Gagal memuat detail Pembuatan Larutan.
                </div>
            `);
        }
    });
});
</script>

<style>
	th {
		background-color: #f8f9fc;
	}

	.page-loading {
		position: fixed;
		inset: 0;
		z-index: 9999;
		background: rgba(255, 255, 255, 0.75);
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		font-weight: 600;
	}
</style>
