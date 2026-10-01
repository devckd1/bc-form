<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<div>
			<h1 class="h3 mb-0 text-gray-800">
				Audit Analisis Lab
			</h1>

			<p class="mb-0 text-muted">
				Data Analisis Lab yang telah dikirim ke Audit.
			</p>
		</div>

		<a href="<?= base_url('analisis/verifikasi'); ?>"
			class="btn btn-secondary btn-sm">
			<i class="fas fa-arrow-left mr-1"></i>
			Kembali
		</a>

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

		<div class="card-header py-3">
			<h6 class="m-0 font-weight-bold text-primary">
				Data Audit Analisis Lab
			</h6>
		</div>

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
							<th>Status Produksi</th>
							<th>Status Lab</th>
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
										<?= !empty($val->date)
											? date('d-m-Y', strtotime($val->date))
											: '-'; ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->tipe_sampel ?? '-'
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->penyimpanan ?? '-'
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->nama_produk ?? '-'
										); ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->kode_produksi ?? '-'
										); ?>
									</td>

									<td>
										<?= !empty($val->best_before)
											? date(
												'd-m-Y',
												strtotime($val->best_before)
											)
											: '-'; ?>
									</td>

									<td>
										<?= htmlspecialchars(
											$val->jumlah_sampel ?? '-'
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

										<?php if ($val->status_produksi == 1): ?>

											<span class="badge badge-success">
												Diketahui
											</span>

										<?php elseif ($val->status_produksi == 2): ?>

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

										<?php if ($val->status_lab == 1): ?>

											<span class="badge badge-success">
												Diterima
											</span>

										<?php elseif ($val->status_lab == 2): ?>

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

										<a href="<?= base_url(
														'analisis/status/' . $val->uuid
													); ?>"
											class="btn btn-warning btn-sm">
											Verifikasi
										</a>

										<a href="<?= base_url('analisis/edit/' . $val->uuid); ?>" class="btn btn-warning btn-sm me-1 rounded-circle shadow" data-bs-toggle="tooltip" data-bs-placement="top" title="Update">
											<i class="fas fa-edit fa-lg"></i>
										</a>
										<button
											class="btn btn-success btn-sm me-1 rounded-circle shadow btn-detail"
											data-uuid="<?= $val->uuid ?>"
											data-bs-toggle="tooltip" data-bs-placement="top" title="Detail">
											<i class="fas fa-info-circle fa-lg"></i>
										</button>

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
				<form action="<?= base_url('analisis/cetak-audit') ?>" method="post" class="form-inline">
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
