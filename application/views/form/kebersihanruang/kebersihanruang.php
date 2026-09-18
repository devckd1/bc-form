<div class="container-fluid">
	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Kebersihan Ruang Produksi</h1>
	</div>

	<!-- Flash Messages -->
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

	<!-- Card -->
	<div class="card shadow mb-4">
		<div class="card-body">

			<!-- Button Tambah -->
			<div class="form-group text-right">
				<a href="<?= base_url('kebersihanruang/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
			</div>

			<hr>

			<!-- Table -->
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Lokasi</th>
							<th class="text-center">Hasil Pemeriksaan</th>
							<th>Supervisor</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						foreach ($kebersihanruang as $val) {
							$tanggalFormatted = (new DateTime($val->date))->format('d-m-Y');
							$details = json_decode($val->detail, true);
							$kondisiMap = [
								'0' => 'Bersih',
								'1' => 'Berdebu',
								'2' => 'Basah',
								'3' => 'Pecah/Retak',
								'4' => 'Sisa produksi (terigu/produk)',
								'5' => 'Noda (tinta, karat)',
								'6' => 'Pertumbuhan mikroorganisme (jamur/bau busuk)',
							];
						?>
							<!-- Row Utama -->
							<tr>
								<td class="text-center"><?= $no++; ?></td>
								<td><?= $tanggalFormatted . " / " . $val->shift; ?></td>
								<td><?= htmlspecialchars($val->lokasi); ?></td>
								<td class="text-center">
									<?php if (!empty($details) && is_array($details)): ?>
										<button class="btn btn-info btn-sm view-detail" type="button">
											View
										</button>
										<!-- Hidden child row content -->
										<div class="d-none child-row">
											<table class="table table-sm table-bordered mb-0">
												<thead style="background-color:#2E86C1; color:black; text-align:center;">
													<tr>
														<th>No</th>
														<th>Bagian</th>
														<th>Kondisi</th>
														<th>Problem</th>
														<th>Tindakan</th>
														<th>Verifikasi Ulang</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($details as $i => $row): ?>
														<tr>
															<td class="text-center"><?= $i + 1; ?></td>
															<td><?= htmlspecialchars($row['bagian']); ?></td>
															<td class="text-center"><?= $kondisiMap[$row['kondisi']] ?? htmlspecialchars($row['kondisi']); ?></td>
															<td><?= !empty($row['problem']) ? htmlspecialchars($row['problem']) : '-'; ?></td>
															<td><?= !empty($row['tindakan']) ? htmlspecialchars($row['tindakan']) : '-'; ?></td>

															<td class="text-center">
																<?php
																$verifikasi = $row['verifikasi'] ?? null;

																if ($verifikasi == 'bersih') {
																	echo '<span style="color:green; font-weight:bold;">✔ Bersih</span>';
																} elseif ($verifikasi == 'kotor') {
																	echo '<span style="color:red; font-weight:bold;">✖ Kotor</span>';
																} else {
																	echo '-';
																}
																?>
															</td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									<?php else: ?>
										-
									<?php endif; ?>
								</td>
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
									<a href="<?= base_url('kebersihanruang/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split mb-1">
										<span class="text">Update</span>
									</a>
									<a href="<?= base_url('kebersihanruang/detail/' . $val->uuid); ?>" class="btn btn-success btn-icon-split mb-1">
										<span class="text">Detail</span>
									</a>
									<!--  <a href="<?= base_url('kebersihanruang/delete/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split mb-1" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    <span class="text">Delete</span>
                                </a> -->
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<style>
	th {
		background-color: #f8f9fc;
	}
</style>

<!-- JS Bootstrap & DataTables -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
	$(document).ready(function() {
		var table = $('#dataTable').DataTable();

		// Toggle child row
		$('#dataTable tbody').on('click', 'button.view-detail', function() {
			var tr = $(this).closest('tr');
			var row = table.row(tr);

			if (row.child.isShown()) {
				row.child.hide();
				tr.removeClass('shown');
				$(this).text('View');
			} else {
				var childContent = tr.find('.child-row').html();
				row.child(childContent).show();
				tr.addClass('shown');
				$(this).text('Hide');
			}
		});
	});
</script>
