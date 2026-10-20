<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<div>
			<h1 class="h3 mb-0 text-gray-800">
				Audit Pemeriksaan Chemical
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			</h1>

		</div>

	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white"
					href="<?= base_url('pemeriksaanchemical'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Chemical
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Pemeriksaan Chemical.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
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
							<th>Shift</th>
							<th>Chemical</th>
							<th>Pemasok</th>
							<th>Kode Produksi</th>
							<th>Jumlah</th>
							<th>Reject</th>
							<th>Penerimaan</th>
							<th>Status SPV</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>

						<?php if (!empty($data)): ?>

							<?php $no = 1; ?>

							<?php foreach ($data as $val): ?>

								<tr>

									<td><?= $no++; ?></td>

									<td>
										<?= date(
											'd-m-Y',
											strtotime($val->date)
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->shift
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->jenis_chemical
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->pemasok
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->kode_produksi
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->jumlah_barang
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->jumlah_reject
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->penerimaan
										); ?>
									</td>

									<td class="text-center">

										<?php if ($val->status_spv == 1): ?>

											<span class="badge badge-success">
												Verified
											</span>

										<?php elseif ($val->status_spv == 2): ?>

											<span class="badge badge-danger">
												Revision
											</span>

										<?php else: ?>

											<span class="badge badge-secondary">
												Created
											</span>

										<?php endif; ?>

									</td>

									<td class="text-center">

										<a href="<?= base_url('pemeriksaanchemical/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('pemeriksaanchemical/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
											<span class="text">Edit</span>
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
				<form action="<?= base_url('pemeriksaanchemical/cetak-audit') ?>" method="post" class="form-inline">
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
	#dataTable th {
		background-color: #f8f9fc;
	}
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

<script>
	$(document).ready(function() {

		$('#dataTable').DataTable({
			ordering: false,
			searching: true,
			pageLength: 10
		});

	});
</script>
