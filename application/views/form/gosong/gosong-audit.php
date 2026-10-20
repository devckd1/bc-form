<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">
			Data Audit Roti Gosong
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>
	</div>

	<!-- Breadcrumb -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb breadcrumb-audit">
			<li class="breadcrumb-item">
				<a href="<?= base_url('gosong'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Laporan Roti Gosong
				</a>
			</li>
		</ol>
	</nav>

	<!-- Informasi Audit -->
	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi Audit:</strong>
		Data yang ditampilkan pada halaman ini merupakan data Audit dan digunakan untuk keperluan pemeriksaan serta verifikasi.
		Perubahan pada data Audit tidak akan mengubah data aktual pada menu pemeriksaan.
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
							<th>Total Berat</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!empty($gosong)): ?>

							<?php $no = 1; ?>

							<?php foreach ($gosong as $val): ?>

								<tr>

									<td>
										<?= $no++; ?>
									</td>

									<td>
										<?= !empty($val->date)
											? date('d-m-Y', strtotime($val->date))
											: '-'; ?>
									</td>

									<td>
										<?= htmlspecialchars($val->shift ?? '-'); ?>
									</td>

									<td>
										<?= isset($val->total_berat)
											? number_format(
												(float)$val->total_berat,
												3,
												',',
												'.'
											)
											: '-'; ?>
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
										<?= !empty($val->tgl_update_spv)
											? date(
												'd-m-Y H:i',
												strtotime($val->tgl_update_spv)
											)
											: '-'; ?>
									</td>

									<td>
										<?= htmlspecialchars($val->nama_spv ?? '-'); ?>
									</td>

									<td class="text-center">

										<a href="<?= base_url('gosong/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('gosong/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
											<span class="text">Edit</span>
										</a>


									</td>

								</tr>

							<?php endforeach; ?>

						<?php endif; ?>

					</tbody>

				</table>

			</div>

			<hr>
			<div class="form-group">
				<form action="<?= base_url('gosong/cetak-audit') ?>" method="post" class="form-inline">
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
