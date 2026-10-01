<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<div>
			<h1 class="h3 mb-0 text-gray-800">
				Audit Pemeriksaan Chemical
			</h1>
			<p class="mb-0 text-muted">
				Data Pemeriksaan Chemical yang telah dikirim ke Audit.
			</p>
		</div>



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
			<div class="form-group text-right">
				<a href="<?= base_url('pemeriksaanchemical/verifikasi'); ?>"
					class="btn btn-secondary btn-sm">
					<i class="fas fa-arrow-left mr-1"></i>
					Kembali
				</a>
			</div>
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

										<a href="<?= base_url(
														'pemeriksaanchemical/status/' . $val->uuid
													); ?>"
											class="btn btn-info btn-sm">
											Verifikasi
										</a>
										<a href="<?= base_url('pemeriksaanchemical/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('pemeriksaanchemical/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
											<span class="text">Update</span>
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
