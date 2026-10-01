<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Laporan Sensori Finish Good</h1>
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

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('sensori/audit'); ?>"
					class="btn btn-info btn-sm">
					<i class="fas fa-clipboard-check mr-1"></i>
					Data Audit
				</a>
			</div>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Nama Produk</th>
							<th class="text-center">Sensori Produk</th>
							<th>Tindakan Koreksi</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($sensori as $val) {
							$datetime = new DateTime($val->date);
							$tanggalFormatted = $datetime->format('d-m-Y');
							$products = json_decode($val->produk, true);
						?>
							<tr>
								<td class="text-center"><?= $no++; ?></td>
								<td><?= $tanggalFormatted; ?></td>
								<td><?= $val->nama_produk; ?></td>
								<td>
									<table class="table table-sm table-bordered mb-0">
										<thead style="background-color: #2E86C1; color: black; text-align: center;">
											<tr>
												<th>Kode Produksi</th>
												<th>Best Before</th>
												<th>Warna</th>
												<th>Tekstur</th>
												<th>Rasa</th>
												<th>Aroma</th>
												<th>Kenampakan</th>
											</tr>
										</thead>
										<tbody>
											<?php if (!empty($products)): ?>
												<?php foreach ($products as $p): ?>
													<tr>
														<td><?= htmlspecialchars($p['kode_produksi'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['best_before'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['warna'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['tekstur'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['rasa'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['aroma'] ?? '-'); ?></td>
														<td><?= htmlspecialchars($p['kenampakan'] ?? '-'); ?></td>
													</tr>
												<?php endforeach; ?>
											<?php else: ?>
												<tr>
													<td colspan="7" class="text-center">Tidak ada data sensori</td>
												</tr>
											<?php endif; ?>
										</tbody>
									</table>
								</td>
								<td><?= $val->tindakan; ?></td>
								<td><?= date('H:i - d m Y', strtotime($val->modified_at)); ?></td>
								<td><?= date('H:i - d m Y', strtotime($val->tgl_update_spv)); ?></td>
								<td class="text-center">
									<?php
									if ($val->status_spv == 0) {
										echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
									} elseif ($val->status_spv == 1) {
										echo '<span style="color: #28b463; font-weight: bold;">Verified</span>';
									} elseif ($val->status_spv == 2) {
										echo '<span style="color: red; font-weight: bold;">Revision</span>';
									}
									?>
								</td>
								<td class="text-center">
									<a href="<?= base_url('sensori/status/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
										<span class="text">Verifikasi</span>
									</a>

									<?php
									$CI = &get_instance();
									$CI->load->model('sensori_model');

									$audit = $CI->sensori_model
										->get_audit_by_source_uuid($val->uuid);
									?>

									<?php if ($audit): ?>

										<a href="<?= base_url('sensori/audit'); ?>"
											class="btn btn-success btn-icon-split">
											<span class="text">Sudah Diaudit</span>
										</a>

									<?php else: ?>

										<a href="<?= base_url('sensori/copy-to-audit/' . $val->uuid); ?>"
											class="btn btn-info btn-icon-split"
											onclick="return confirm('Kirim data ini ke Audit?');">
											<span class="text">Kirim Audit</span>
										</a>

									<?php endif; ?>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>

			<br>
			<hr>
			<div class="form-group">
				<form action="<?= base_url('sensori/cetak') ?>" method="post" class="form-inline">
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
</div>

<style>
	th {
		background-color: #f8f9fc;
	}
</style>
