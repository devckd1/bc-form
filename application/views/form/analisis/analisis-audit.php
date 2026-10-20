<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<div>
			<h1 class="h3 mb-0 text-gray-800">
				Audit Analisis Lab
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			</h1>
		</div>
	</div>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-audit">
			<li class="breadcrumb-item">
				<a href="<?= base_url('analisis'); ?>">
					<i class="fas fa-arrow-left"></i>
					Kembali ke Daftar Analisis Lab
				</a>
			</li>
		</ol>
	</nav>

	<!-- Informasi Audit -->
	<div class="alert"
		style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi Audit:</strong>
		Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan
		untuk keperluan pemeriksaan serta verifikasi. Perubahan pada data Audit
		tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success">
			<?= $this->session->flashdata('success_msg'); ?>
		</div>
	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger">
			<?= $this->session->flashdata('error_msg'); ?>
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<div class="table-responsive">
				<table class="table table-bordered table-hover"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Tipe Sampel</th>
							<th>Penyimpanan</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>Best Before</th>
							<th>Jumlah Sampel</th>
							<th>Status SPV</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>
						<?php if (!empty($data)): ?>
							<?php $no = 1; ?>

							<?php foreach ($data as $val): ?>
								<tr>
									<td><?= $no++; ?></td>

									<td>
										<?= !empty($val->date)
											? date('d-m-Y', strtotime($val->date))
											: '-'; ?>
									</td>

									<td>
										<?= htmlspecialchars($val->tipe_sampel ?? '-'); ?>
									</td>

									<td>
										<?= htmlspecialchars($val->penyimpanan ?? '-'); ?>
									</td>

									<td>
										<?= htmlspecialchars($val->nama_produk ?? '-'); ?>
									</td>

									<td>
										<?= htmlspecialchars($val->kode_produksi ?? '-'); ?>
									</td>

									<td>
										<?= !empty($val->best_before)
											? date('d-m-Y', strtotime($val->best_before))
											: '-'; ?>
									</td>

									<td>
										<?= htmlspecialchars($val->jumlah_sampel ?? '-'); ?>
									</td>

									<td class="text-center">
										<?php if ($val->status_spv == 1): ?>
											<span class="badge badge-success">Verified</span>
										<?php elseif ($val->status_spv == 2): ?>
											<span class="badge badge-danger">Revision</span>
										<?php else: ?>
											<span class="badge badge-secondary">Created</span>
										<?php endif; ?>
									</td>

									<td class="text-center text-nowrap">
										<a href="<?= base_url('analisis/edit-audit/' . rawurlencode($val->uuid)); ?>"
											class="btn btn-warning btn-icon-split mb-1">
											<span class="text">Edit</span>
										</a>

										<button
											type="button"
											class="btn btn-success btn-icon-split btn-detail-audit mb-1"
											data-uuid="<?= htmlspecialchars($val->uuid, ENT_QUOTES, 'UTF-8'); ?>">
											<span class="text">Detail</span>
										</button>

										<a href="<?= base_url('analisis/analis-audit/' . rawurlencode($val->uuid)); ?>"
											class="btn btn-danger btn-icon-split mb-1">
											<span class="text">Analisis</span>
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>
			</div>

			<br>
			<hr>

			<div class="form-group">
				<form action="<?= base_url('analisis/cetak-audit'); ?>"
					method="post"
					class="form-inline">

					<label for="tanggal" class="mr-2 font-weight-bold">
						Pilih Tanggal:
					</label>

					<input type="date"
						name="tanggal"
						id="tanggal"
						class="form-control mr-2"
						required>

					<button type="submit" class="btn btn-success">
						<i class="fas fa-print fa-sm text-white-50"></i>
						Cetak PDF
					</button>
				</form>
			</div>

		</div>
	</div>
</div>

<!-- Modal Detail Audit -->
<div class="modal fade"
	id="detailModal"
	tabindex="-1"
	aria-labelledby="detailModalLabel"
	aria-hidden="true">

	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content">

			<div class="modal-header">
				<h5 class="modal-title" id="detailModalLabel">
					Detail Permohonan Analisis Sampel Laboratorium
					<span class="badge-mode-audit">
						<i class="fas fa-clipboard-check mr-1"></i>
						MODE AUDIT
					</span>
				</h5>

				<button type="button"
					class="btn-close"
					data-bs-dismiss="modal"
					aria-label="Close"></button>
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
	#dataTable th,
	th {
		background-color: #f8f9fc;
	}

	.breadcrumb-audit {
		background-color: #2E86C1;
	}

	.breadcrumb-audit a {
		color: #fff !important;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 6px 12px;
		border-radius: 6px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
		box-shadow: 0 2px 5px rgba(90, 50, 152, 0.25);
	}

	#detailModal .modal-header {
		align-items: center;
	}
</style>

<script>
	$(document).ready(function() {

		// DataTable
		if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#dataTable')) {
			$('#dataTable').DataTable({
				ordering: false,
				searching: true,
				pageLength: 10
			});
		}

		// Detail Audit
		$('#dataTable').on('click', '.btn-detail-audit', function() {
			var uuid = $(this).data('uuid');
			var modalElement = document.getElementById('detailModal');
			var bodyElement = document.getElementById('detailModalBody');

			if (!modalElement || !bodyElement) {
				console.error('Struktur modal Detail Audit tidak ditemukan.');
				return;
			}

			$('#detailModalBody').html(
				'<div class="text-center py-3">' +
				'<div class="spinner-border" role="status"></div>' +
				'<span> Loading...</span>' +
				'</div>'
			);

			// Gunakan instance Bootstrap yang tersedia jika ada.
			var modal = null;

			if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
				if (typeof bootstrap.Modal.getInstance === 'function') {
					modal = bootstrap.Modal.getInstance(modalElement);
				}

				if (!modal) {
					modal = new bootstrap.Modal(modalElement);
				}

				modal.show();
			} else {
				console.error('Bootstrap Modal tidak tersedia. Periksa template utama.');
				return;
			}

			$.ajax({
				url: "<?= base_url('analisis/ajax-detail-audit/'); ?>" +
					encodeURIComponent(uuid),
				type: 'GET',
				dataType: 'html',

				success: function(response) {
					$('#detailModalBody').html(response);
				},

				error: function(xhr) {
					console.error('Gagal memuat Detail Audit:', xhr.status);

					$('#detailModalBody').html(
						'<div class="alert alert-danger">' +
						'Gagal memuat detail Audit. Silakan coba kembali.' +
						'</div>'
					);
				}
			});
		});

	});
</script>
