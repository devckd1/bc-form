<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Sensori Finish Good
		</h1>

		<a href="<?= base_url('sensori/verifikasi'); ?>"
			class="btn btn-secondary btn-sm">

			<i class="fas fa-arrow-left mr-1"></i>
			Kembali

		</a>

	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>

		<div class="alert alert-success text-center">
			<i class="fas fa-check mr-1"></i>
			<?= $this->session->flashdata('success_msg'); ?>
		</div>

		<br>

	<?php endif; ?>

	<?php if ($this->session->flashdata('error_msg')): ?>

		<div class="alert alert-danger text-center">
			<i class="fas fa-times mr-1"></i>
			<?= $this->session->flashdata('error_msg'); ?>
		</div>

		<br>

	<?php endif; ?>

	<div class="card shadow mb-4">

		<div class="card-body">

			<div class="table-responsive">

				<table class="table table-bordered"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Nama Produk</th>
							<th>Produk / Kode Produksi</th>
							<th>Tindakan</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th>Action</th>
						</tr>
					</thead>

					<tbody>

						<?php
						$no = 1;

						foreach ($data as $val):

							$produk = json_decode($val->produk);

							$kode_produksi = [];

							if ($produk && is_array($produk)) {
								foreach ($produk as $item) {
									if (!empty($item->kode_produksi)) {
										$kode_produksi[] = $item->kode_produksi;
									}
								}
							}
						?>

							<tr>

								<td class="text-center">
									<?= $no; ?>
								</td>

								<td>
									<?= !empty($val->date)
										? date('d-m-Y', strtotime($val->date))
										: '-'; ?>
								</td>

								<td>
									<?= htmlspecialchars(
										$val->nama_produk ?? '-'
									); ?>
								</td>

								<td>
									<?= !empty($kode_produksi)
										? htmlspecialchars(
											implode(', ', $kode_produksi)
										)
										: '-'; ?>
								</td>

								<td>
									<?= htmlspecialchars(
										$val->tindakan ?? '-'
									); ?>
								</td>

								<td>
									<?= !empty($val->modified_at)
										? date(
											'H:i - d-m-Y',
											strtotime($val->modified_at)
										)
										: '-'; ?>
								</td>

								<td>
									<?= !empty($val->tgl_update_spv)
										? date(
											'H:i - d-m-Y',
											strtotime($val->tgl_update_spv)
										)
										: '-'; ?>
								</td>

								<td class="text-center">

									<?php if ((int)$val->status_spv === 0): ?>

										<span style="color:#99a3a4;font-weight:bold;">
											Created
										</span>

									<?php elseif ((int)$val->status_spv === 1): ?>

										<span style="color:#28b463;font-weight:bold;">
											Verified
										</span>

									<?php elseif ((int)$val->status_spv === 2): ?>

										<span style="color:red;font-weight:bold;">
											Revision
										</span>

									<?php else: ?>

										<span style="color:#99a3a4;font-weight:bold;">
											-
										</span>

									<?php endif; ?>

								</td>

								<td class="text-center">

									<a href="<?= base_url(
													'sensori/status/' . $val->uuid
												); ?>"
										class="btn btn-warning btn-icon-split">

										<span class="text">
											Verifikasi
										</span>

									</a>

								</td>

							</tr>

						<?php
							$no++;
						endforeach;
						?>

					</tbody>

				</table>

			</div>

			<hr>
			<div class="form-group">
				<form action="<?= base_url('sensori/cetak-audit') ?>" method="post" class="form-inline">
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
</style>

<script>
	$(document).ready(function() {

		$('#dataTable').DataTable({
			"ordering": false,
			"searching": true
		});

	});
</script>
