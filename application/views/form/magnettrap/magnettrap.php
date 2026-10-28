<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Pemeriksaan Magnet Trap</h1>
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
			<div id="pageLoading" class="page-loading" style="display: none;">
				<div class="spinner-border text-primary" role="status">
					<span class="sr-only">Loading...</span>
				</div>
				<div class="mt-2">Memuat data...</div>
			</div>
			<div class="d-flex justify-content-between align-items-center flex-wrap">

				<!-- KIRI: Filter dan Verifikasi Massal -->
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<form
						id="formVerifikasiMassal"
						action="<?= base_url('magnettrap/verifikasi_massal'); ?>"
						method="post"
						class="d-flex align-items-center flex-wrap mb-2">

						<input
							type="date"
							name="tanggal"
							id="tanggal_massal"
							class="form-control form-control-sm mr-2 mb-1"
							style="width: 155px;"
							value="<?= html_escape($filter_tanggal ?? ''); ?>"
							required>

						<select
							name="shift"
							id="shift_massal"
							class="form-control form-control-sm mr-2 mb-1"
							style="width: 135px;">
							<option value="">-- Pilih Shift --</option>
							<option value="semua" <?= ($filter_shift ?? '') === 'semua' ? 'selected' : ''; ?>>
								Semua Shift
							</option>
							<option value="1" <?= ($filter_shift ?? '') === '1' ? 'selected' : ''; ?>>
								Shift 1
							</option>
							<option value="2" <?= ($filter_shift ?? '') === '2' ? 'selected' : ''; ?>>
								Shift 2
							</option>
							<option value="3" <?= ($filter_shift ?? '') === '3' ? 'selected' : ''; ?>>
								Shift 3
							</option>
						</select>

						<button type="submit"
							class="btn btn-primary btn-sm mr-2 mb-1">
							<i class="fas fa-check-double mr-1"></i>
							Verifikasi
						</button>

						<button
							type="button"
							id="resetFilterMagnetTrap"
							class="btn btn-outline-secondary btn-sm mb-1">
							<i class="fas fa-undo mr-1"></i>
							Reset
						</button>

					</form>
				<?php endif; ?>

				<!-- KANAN: Tombol Utama -->
				<div class="d-flex align-items-center flex-wrap mb-2 ml-auto">

					<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
						<a href="<?= base_url('magnettrap/tambah') ?>"
							class="btn btn-md btn-primary shadow-sm">
							<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
						</a>
					<?php endif; ?>

					<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
						<a href="<?= base_url('magnettrap/audit'); ?>"
							class="btn shadow-sm ml-2"
							style="background-color: #5a3298; color: #fff;">
							<i class="fas fa-clipboard-check mr-1"></i>
							Mode Audit
						</a>
					<?php endif; ?>

				</div>

			</div>

			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<small class="text-muted">
					<i class="fas fa-info-circle mr-1"></i>
					Hanya data yang sudah diverifikasi Produksi/Engineering
					yang akan diproses.
				</small>
			<?php endif; ?>
			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>Tahapan</th>
							<th>Jenis Kontaminasi</th>
							<th>Bukti</th>
							<th>Keterangan</th>
							<th>Status</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = (($current_page - 1) * $per_page) + 1;
						foreach ($magnettrap as $val) {
							$datetime = new datetime($val->date);
							$datetime = $datetime->format('d-m-Y');
						?>
							<tr
								data-tanggal="<?= date('Y-m-d', strtotime($val->date)); ?>"
								data-shift="<?= htmlspecialchars((string) $val->shift, ENT_QUOTES, 'UTF-8'); ?>">
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime . " / " . $val->shift; ?></td>
								<td><?= $val->nama_produk; ?></td>
								<td><?= $val->kode_produksi; ?></td>
								<td><?= $val->tahapan; ?></td>
								<td><?= $val->kontaminasi; ?></td>
								<td>
									<?php
									$bukti = $val->bukti ?? null;

									$path1 = FCPATH . 'uploads/' . $bukti;
									$path2 = FCPATH . 'uploads/magnettrap/' . $bukti;

									if (!empty($bukti)) {
										if (file_exists($path1)) {
											$img_url = base_url('uploads/' . $bukti);
										} elseif (file_exists($path2)) {
											$img_url = base_url('uploads/magnettrap/' . $bukti);
										} else {
											$img_url = null;
										}
									} else {
										$img_url = null;
									}
									?>

									<?php if ($img_url): ?>
										<img src="<?= $img_url; ?>" alt="Bukti Temuan" style="max-width: 150px; max-height: 100px;">
									<?php else: ?>
										<p>No image available</p>
									<?php endif; ?>
								</td>
								<td><?= $val->keterangan; ?></td>
								<td>
									<?php
									$tipe_user = (int) $this->session->userdata('tipe_user');

									if ($val->status_spv == '1') {
										echo '<span style="color: #28a745; font-weight: bold; font-size: 12px;">Verified</span>';
									} elseif ($val->status_enginer == '1' && $val->status_spv == '0') {
										echo '<span style="color: #28a745; font-weight: bold; font-size: 12px;">Approved</span>';
									} elseif (in_array($tipe_user, [0, 1, 2]) && $val->status_spv == '0') {
										echo '<span style="color: #f39c12; font-weight: bold; font-size: 12px;">Menunggu Verifikasi Produksi</span>';
									} elseif ($tipe_user == 3 && $val->status_spv == '0') {
										echo '<span style="color: #007bff; font-weight: bold; font-size: 12px;">Created</span>';
									} else {
										echo '<span style="color: #f39c12; font-weight: bold; font-size: 12px;">Menunggu Verifikasi Produksi</span>';
									}
									?>
								</td>
								<td class="text-center">
									<div class="action-buttons">

										<?php if (in_array($tipe_user, [0, 1, 2, 4, 9])): ?>
											<a href="<?= base_url('magnettrap/edit/' . $val->uuid); ?>"
												class="btn btn-warning btn-icon-split">
												<span class="text">Edit</span>
											</a>
										<?php endif; ?>

										<button type="button"
											class="btn btn-success btn-icon-split btn-detail-magnettrap"
											data-url="<?= base_url('magnettrap/detail/' . $val->uuid); ?>">
											<span class="text">Detail</span>
										</button>

										<?php if (in_array($tipe_user, [0, 3, 9])): ?>

											<?php if ($val->status_enginer != '1'): ?>
												<a href="<?= base_url('magnettrap/verifikasi_produksi/' . $val->uuid); ?>"
													class="btn btn-primary btn-icon-split"
													onclick="return confirm('Apakah pemeriksaan ini yakin disetujui?');">
													<span class="text">Setujui</span>
												</a>
											<?php endif; ?>

										<?php elseif (in_array($tipe_user, [0, 1, 2, 9])): ?>

											<?php if (
												$val->status_enginer == '1' &&
												$val->status_spv == '0'
											): ?>
												<a href="<?= base_url('magnettrap/status/' . $val->uuid); ?>"
													class="btn btn-info btn-icon-split">
													<span class="text">Verifikasi</span>
												</a>

											<?php elseif (
												$val->status_enginer == '1' &&
												$val->status_spv == '2'
											): ?>
												<a href="<?= base_url('magnettrap/status/' . $val->uuid); ?>"
													class="btn btn-warning btn-icon-split">
													<span class="text">Verifikasi Ulang</span>
												</a>
											<?php endif; ?>

										<?php endif; ?>

									</div>
									<!--  <a href="<?= base_url('magnettrap/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split" onclick="return confirm('Yakin ingin menghapus data ini?')">
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
				<?php if ($total_rows > 0): ?>
					<div class="d-flex justify-content-between align-items-center flex-wrap mt-3">
						<small class="text-muted mb-2">
							Menampilkan
							<?= (($current_page - 1) * $per_page) + 1; ?>
							sampai
							<?= min($current_page * $per_page, $total_rows); ?>
							dari <?= $total_rows; ?> data
						</small>

						<nav aria-label="Pagination Magnet Trap">
							<ul class="pagination pagination-sm mb-2">
								<li class="page-item <?= $current_page <= 1 ? 'disabled' : ''; ?>">
									<a class="page-link"
										href="<?= base_url('magnettrap?page=' . ($current_page - 1)); ?>">
										Sebelumnya
									</a>
								</li>

								<?php for ($i = 1; $i <= $total_pages; $i++): ?>
									<li class="page-item <?= $i == $current_page ? 'active' : ''; ?>">
										<a class="page-link"
											href="<?= base_url('magnettrap?page=' . $i); ?>">
											<?= $i; ?>
										</a>
									</li>
								<?php endfor; ?>

								<li class="page-item <?= $current_page >= $total_pages ? 'disabled' : ''; ?>">
									<a class="page-link"
										href="<?= base_url('magnettrap?page=' . ($current_page + 1)); ?>">
										Berikutnya
									</a>
								</li>
							</ul>
						</nav>
					</div>
				<?php endif; ?>
			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<div class="form-group">
					<form action="<?= base_url('magnettrap/cetak') ?>" method="post" class="form-inline">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" class="form-control mr-2" required>

						<label for="shift" class="mr-2 font-weight-bold">Shift:</label>
						<select name="shift" class="form-control mr-2" required>
							<option value="">-- Pilih Shift --</option>
							<option value="1">Shift 1</option>
							<option value="2">Shift 2</option>
							<option value="3">Shift 3</option>
						</select>

						<button type="submit" class="btn btn-success">
							<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
						</button>
					</form>
				</div>
				<hr>
				<div class="form-group mt-3">
					<form action="<?= base_url('magnettrap/export_excel') ?>" method="post" class="form-inline">
						<label for="bulan" class="mr-2 font-weight-bold">Export Excel Bulanan:</label>
						<input type="month" name="bulan" class="form-control mr-2" required>

						<button type="submit" class="btn btn-success">
							<i class="fas fa-file-excel"></i> Export Excel
						</button>
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
<!-- Container modal, isi dibuat saat tombol Detail diklik -->
<div id="modalDetailMagnetTrapContainer"></div>
<style>
	th {
		background-color: #f8f9fc;
	}

	.action-buttons {
		display: flex;
		align-items: center;
		justify-content: flex-start;
		flex-wrap: wrap;
		gap: 6px;
		min-width: 180px;
	}

	.action-buttons .btn {
		margin: 0;
		white-space: nowrap;
	}

	.action-buttons .waiting-verification {
		display: inline-block;
		max-width: 190px;
		white-space: normal;
		line-height: 1.4;
		text-align: left;
		font-size: 12px;
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

<script>
	$(document).ready(function() {

		// =====================================================
		// 1. DATATABLES
		// =====================================================
		var table = $('#dataTable').DataTable({
			paging: false,
			searching: false,
			info: false,
			ordering: false
		});

		// =====================================================
		// 2. FILTER TANGGAL DAN SHIFT (SERVER-SIDE)
		// =====================================================
		function terapkanFilter() {
			var tanggal = $('#tanggal_massal').val();
			var shift = $('#shift_massal').val();

			var params = new URLSearchParams();

			if (tanggal) {
				params.set('tanggal', tanggal);
			}

			if (shift) {
				params.set('shift', shift);
			}

			var query = params.toString();
			var url = '<?= base_url("magnettrap"); ?>';

			$('#pageLoading').css('display', 'flex');

			window.location.href = query ? url + '?' + query : url;
		}

		$('#tanggal_massal, #shift_massal').on('change', function() {
			terapkanFilter();
		});

		$('#resetFilterMagnetTrap').on('click', function() {
			$('#tanggal_massal').val('');
			$('#shift_massal').val('');
			terapkanFilter();
		});

		// =====================================================
		// 3. VERIFIKASI MASSAL
		// =====================================================
		$('#formVerifikasiMassal').on('submit', function(e) {
			e.preventDefault();

			var tanggal = $('#tanggal_massal').val();
			var shift = $('#shift_massal').val();

			if (!tanggal || !shift) {
				alert('Pilih tanggal dan shift terlebih dahulu.');
				return;
			}

			if (!confirm(
					'Verifikasi semua data pada tanggal dan shift yang dipilih?\n' +
					'Data yang belum diverifikasi Produksi akan dilewati.'
				)) {
				return;
			}

			var form = $(this);
			var tombol = form.find('button[type="submit"]');
			var teksAwal = tombol.html();

			tombol.prop('disabled', true).html(
				'<i class="fas fa-spinner fa-spin mr-1"></i> Memproses...'
			);

			$.ajax({
				url: form.attr('action'),
				type: 'POST',
				data: form.serialize(),
				dataType: 'json',

				success: function(res) {
					if (res.success) {
						alert(
							(res.message || 'Verifikasi selesai.') +
							'\nBerhasil diverifikasi: ' + (res.updated || 0) +
							'\nBelum diverifikasi Produksi: ' +
							(res.belum_engineer || 0)
						);

						// Muat ulang halaman dengan filter aktif
						window.location.reload();
					} else {
						alert(res.message || 'Verifikasi massal gagal.');
					}
				},

				error: function(xhr) {
					console.error(xhr.responseText);

					alert(
						'Permintaan gagal. Periksa controller, route, ' +
						'atau respons server.'
					);
				},

				complete: function() {
					tombol.prop('disabled', false).html(teksAwal);
				}
			});
		});

		// =====================================================
		// 4. LOADING SAAT BERPINDAH HALAMAN
		// =====================================================
		$(document).on('click', '.pagination .page-link', function(e) {
			var href = $(this).attr('href');

			if (
				!href ||
				$(this).closest('.page-item').hasClass('disabled')
			) {
				e.preventDefault();
				return;
			}

			$('#pageLoading').css('display', 'flex');
		});



		// =====================================================
		// 5. LAZY LOAD MODAL DETAIL MAGNET TRAP
		// =====================================================

		var detailMagnetTrapCache = {};
		var detailMagnetTrapRequest = null;
		var detailMagnetTrapUrlAktif = null;

		$(document).on('click', '.btn-detail-magnettrap', function() {
			var url = $(this).data('url');
			var modalId = '#modalDetailMagnetTrap';

			// Buat modal hanya saat tombol Detail diklik
			if (!$(modalId).length) {
				$('#modalDetailMagnetTrapContainer').html(`
					<div class="modal fade"
						id="modalDetailMagnetTrap"
						tabindex="-1"
						role="dialog"
						aria-labelledby="modalDetailMagnetTrapLabel"
						aria-hidden="true">
						<div class="modal-dialog modal-xl modal-dialog-scrollable"
							role="document"
							style="max-width: 1100px;">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title font-weight-bold"
										id="modalDetailMagnetTrapLabel">
										Detail Pemeriksaan Magnet Trap
									</h5>
									<button type="button" class="close"
										data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body" id="isiDetailMagnetTrap" style="max-height: 75vh; overflow-y: auto;"></div>
							</div>
						</div>
					</div>
				`);
			}

			var $modal = $(modalId);
			var $body = $('#isiDetailMagnetTrap');

			detailMagnetTrapUrlAktif = url;
			$modal.modal('show');

			// Tampilkan cache jika detail sudah pernah dimuat
			if (Object.prototype.hasOwnProperty.call(detailMagnetTrapCache, url)) {
				$body.html(detailMagnetTrapCache[url]);
				aktifkanLazyloadDetail($body);
				return;
			}

			// Batalkan request sebelumnya jika masih berjalan
			if (detailMagnetTrapRequest) {
				detailMagnetTrapRequest.abort();
				detailMagnetTrapRequest = null;
			}

			$body.html(`
				<div class="text-center py-5">
					<div class="spinner-border text-primary" role="status">
						<span class="sr-only">Loading...</span>
					</div>
					<div class="mt-2">Memuat detail pemeriksaan...</div>
				</div>
			`);

			detailMagnetTrapRequest = $.ajax({
				url: url,
				type: 'GET',
				data: {
					popup: 1
				},
				dataType: 'html',

				success: function(response) {
					// Pastikan respons sesuai dengan data yang sedang dibuka
					if (detailMagnetTrapUrlAktif !== url) {
						return;
					}

					detailMagnetTrapCache[url] = response;
					$body.html(response);
					aktifkanLazyloadDetail($body);
				},

				error: function(xhr, status) {
					if (status === 'abort' ||
						detailMagnetTrapUrlAktif !== url) {
						return;
					}

					$body.html(`
						<div class="alert alert-danger mb-0">
							Gagal memuat detail pemeriksaan.
							<button type="button"
								class="btn btn-sm btn-outline-danger ml-2"
								id="btnCobaLagiDetail">
								Coba Lagi
							</button>
						</div>
					`);
				},

				complete: function() {
					detailMagnetTrapRequest = null;
				}
			});
		});

		// Coba ulang request detail
		$(document).on('click', '#btnCobaLagiDetail', function() {
			var url = detailMagnetTrapUrlAktif;

			$('.btn-detail-magnettrap').filter(function() {
				return $(this).data('url') === url;
			}).first().trigger('click');
		});

		// Batalkan request ketika modal ditutup
		$(document).on(
			'hide.bs.modal',
			'#modalDetailMagnetTrap',
			function() {
				detailMagnetTrapUrlAktif = null;

				if (detailMagnetTrapRequest) {
					detailMagnetTrapRequest.abort();
					detailMagnetTrapRequest = null;
				}
			}
		);

		// Lazy-load gambar bukti
		function aktifkanLazyloadDetail($container) {
			$container.find('img').each(function() {
				this.setAttribute('loading', 'lazy');
				this.setAttribute('decoding', 'async');
			});
		}

	});
</script>
