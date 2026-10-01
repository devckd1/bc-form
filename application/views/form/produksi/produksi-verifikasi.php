<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Verifikasi Proses Produksi</h1>
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

	<!-- TABEL DATA -->
	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('produksi/audit'); ?>"
					class="btn btn-info btn-sm">
					<i class="fas fa-clipboard-check mr-1"></i>
					Data Audit
				</a>
			</div>

			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th class="text-center">No</th>
							<th>Date</th>
							<th>Shift</th>
							<th>Types of Product</th>
							<th>Production Code</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php $no = 1;
						foreach ($produksi as $val): ?>
							<?php $tanggal = (new DateTime($val->date))->format('d-m-Y'); ?>
							<tr>
								<td class="text-center"><?= $no++ ?></td>
								<td><?= $tanggal ?></td>
								<td><?= $val->shift ?></td>
								<td><?= $val->nama_produk ?></td>
								<td><?= $val->kode_produksi ?></td>
								<td><?= date('H:i - d m Y', strtotime($val->modified_at)) ?></td>
								<td><?= date('H:i - d m Y', strtotime($val->tgl_update)) ?></td>
								<td class="text-center">
									<?php
									if ($val->status_spv == 0) echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
									elseif ($val->status_spv == 1) echo '<span style="color: #28b463; font-weight: bold;">Verified</span>';
									elseif ($val->status_spv == 2) echo '<span style="color: red; font-weight: bold;">Revision</span>';
									?>
								</td>
								<td class="text-center">
									<a href="<?= base_url('produksi/status/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
										<span class="text">Verifikasi</span>
									</a>

									<?php if ((int)$val->is_audit === 0): ?>

										<a href="<?= base_url('produksi/copy-to-audit/' . $val->uuid); ?>"
											class="btn btn-info btn-icon-split btn-sm">
											<span class="text">
												Kirim Audit
											</span>
										</a>

									<?php endif; ?>

								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- Export Excel & Cetak PDF -->
	<div class="card shadow mb-4">
		<div class="card-header bg-info text-white">
			<h6 class="m-0 font-weight-bold">Export / Cetak</h6>
		</div>
		<div class="card-body">
			<div class="form-row">
				<div class="form-group col-md-2">
					<label for="tanggal"><strong>Pilih Tanggal:</strong></label>
					<input type="date" id="tanggal" class="form-control" required>
				</div>
				<div class="form-group col-md-2">
					<label for="nama_produk"><strong>Pilih Produk:</strong></label>
					<select id="nama_produk" class="form-control" required>
						<option value="">-- Pilih Produk --</option>
					</select>
				</div>

				<!-- Tombol Export Excel -->
				<div class="form-group align-self-end col-md-2">
					<form action="<?= base_url('produksi/export_excel') ?>" method="post">
						<input type="hidden" name="tanggal" id="tanggal_excel">
						<input type="hidden" name="nama_produk" id="produk_excel">
						<button type="submit" class="btn btn-success btn-block">
							<i class="fas fa-file-excel"></i> Export Excel
						</button>
					</form>
				</div>

				<!-- Tombol Cetak PDF -->
				<div class="form-group align-self-end col-md-2">
					<form action="<?= base_url('produksi/cetak') ?>" method="post">
						<input type="hidden" name="tanggal" id="tanggal_pdf_hidden">
						<input type="hidden" name="nama_produk" id="produk_pdf_hidden">
						<button type="submit" class="btn btn-danger btn-block">
							<i class="fas fa-file-pdf"></i> Cetak PDF
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$(document).ready(function() {
		function loadProdukByTanggal(tanggal) {
			if (!tanggal) return;
			$.ajax({
				type: 'POST',
				url: '<?= base_url("produksi/get_produk_by_tanggal") ?>',
				data: {
					tanggal: tanggal
				},
				dataType: 'json',
				success: function(data) {
					$('#nama_produk').empty();
					$('#nama_produk').append('<option value="">-- Pilih Produk --</option>');
					$.each(data, function(i, item) {
						$('#nama_produk').append('<option value="' + item.nama_produk + '">' + item.nama_produk + '</option>');
					});
				},
				error: function() {
					alert('Gagal mengambil data produk.');
				}
			});
		}

		// Trigger saat tanggal berubah
		$('#tanggal').on('change', function() {
			var tanggal = $(this).val();
			$('#tanggal_excel').val(tanggal);
			$('#tanggal_pdf_hidden').val(tanggal);
			loadProdukByTanggal(tanggal);
		});

		// Trigger saat produk berubah
		$('#nama_produk').on('change', function() {
			var produk = $(this).val();
			$('#produk_excel').val(produk);
			$('#produk_pdf_hidden').val(produk);
		});

		// Jika halaman di-load dan sudah ada tanggal, langsung load produk
		var initialTanggal = $('#tanggal').val();
		if (initialTanggal) {
			$('#tanggal_excel').val(initialTanggal);
			$('#tanggal_pdf_hidden').val(initialTanggal);
			loadProdukByTanggal(initialTanggal);
		}
	});
</script>

<style>
	th {
		background-color: #f8f9fc;
	}
</style>
