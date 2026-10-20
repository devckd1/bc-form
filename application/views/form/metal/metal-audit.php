<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">
			Data Audit Metal Detector
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>
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

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white"
					href="<?= base_url('metal'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Metal
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Pemeriksaan Metal.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

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
							<th>Shift</th>
							<th>Waktu</th>
							<th>Nama Produk</th>
							<th>Kode Produksi</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!empty($metal)): ?>

							<?php $no = 1; ?>

							<?php foreach ($metal as $val): ?>

								<?php
								$standar =
									'FE: ' . ($val->std_fe ?? '-') .
									'<br>Non-FE: ' . ($val->std_nonfe ?? '-') .
									'<br>SUS 304: ' . ($val->std_sus304 ?? '-');

								$hasil_deteksi =
									'FE Depan: ' . ($val->fe_d ?? '-') .
									'<br>Non-FE Depan: ' . ($val->nonfe_d ?? '-') .
									'<br>SUS Depan: ' . ($val->sus_d ?? '-') .
									'<br><br>' .
									'FE Tengah: ' . ($val->fe_t ?? '-') .
									'<br>Non-FE Tengah: ' . ($val->nonfe_t ?? '-') .
									'<br>SUS Tengah: ' . ($val->sus_t ?? '-') .
									'<br><br>' .
									'FE Belakang: ' . ($val->fe_b ?? '-') .
									'<br>Non-FE Belakang: ' . ($val->nonfe_b ?? '-') .
									'<br>SUS Belakang: ' . ($val->sus_b ?? '-');
								?>

								<tr>

									<td>
										<?= $no++; ?>
									</td>

									<td>
										<?= !empty($val->date_metal)
											? date('d-m-Y', strtotime($val->date_metal))
											: '-'; ?>
									</td>

									<td>
										<?= htmlspecialchars($val->shift ?? '-'); ?>
									</td>

									<td>
										<?= htmlspecialchars($val->time ?? '-'); ?>
									</td>

									<td>
										<?= htmlspecialchars($val->nama_produk ?? '-'); ?>
									</td>

									<td>
										<?= htmlspecialchars($val->kode_produksi ?? '-'); ?>
									</td>

									<td>
										<?= !empty($val->modified_at)
											? date(
												'd-m-Y H:i',
												strtotime($val->modified_at)
											)
											: '-'; ?>
									</td>

									<td>
										<?= !empty($val->tgl_update_spv_metal)
											? date(
												'd-m-Y H:i',
												strtotime($val->tgl_update_spv_metal)
											)
											: '-'; ?>
									</td>

									<td>
										<?= htmlspecialchars($val->nama_spv_metal ?? '-'); ?>
									</td>


									<td class="text-center">

										<a href="<?= base_url('metal/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
											<span class="text">Edit</span>
										</a>
										<a href="<?= base_url('metal/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split">
											<span class="text">Detail</span>
										</a>

									</td>

								</tr>

							<?php endforeach; ?>

						<?php endif; ?>

					</tbody>

				</table>

			</div>

			<br>
			<div class="mb-3">
				<form action="<?= base_url('metal/cetak-audit') ?>" method="post" class="form-inline">
					<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
					<input type="date" name="tanggal" class="form-control mr-2" required>
					<button type="submit" class="btn btn-success">
						<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
					</button>
				</form>
			</div>

		</div>

	</div>

</div>

<style>
	th {
		background-color: #f8f9fc;
	}

	.breadcrumb {
		background-color: #2E86C1;
	}
	th {
		background-color: #f8f9fc;
	}
	.breadcrumb a {
		color: #fff !important;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	/* Label Mode Audit */
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
</style>
