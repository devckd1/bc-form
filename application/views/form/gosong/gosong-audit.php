<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">
			Data Audit Roti Gosong
		</h1>
	</div>

	<div class="card shadow mb-4">

		<div class="card-body">
			<div class="form-group text-right">

				<a href="<?= base_url('gosong/verifikasi'); ?>"
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

										<a href="<?= base_url('gosong/status/' . $val->uuid); ?>"
											class="btn btn-info btn-sm">
											Verifikasi
										</a>
										<a href="<?= base_url('gosong/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('gosong/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
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
