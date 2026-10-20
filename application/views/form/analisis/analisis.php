<div class="container-fluid">
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Permohonan Analisis Sampel Laboratorium</h1>
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

	<?php
	$tipe_user = $this->session->userdata('tipe_user');
	?>

	<div class="card shadow mb-4">
		<div class="card-body">
			<div class="form-group text-right">
				<a href="<?= base_url('analisis/tambah') ?>" class="btn btn-md btn-primary shadow-sm">
					<i class="fas fa-plus fa-sm text-white-50"></i> Tambah
				</a>
				<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
					<a href="<?= base_url('analisis/audit'); ?>"
						class="btn shadow-sm"
						style="background-color: #5a3298; color: #fff;">

						<i class="fas fa-clipboard-check mr-1"></i>
						Mode Audit

					</a>
				<?php endif; ?>
			</div>
			<hr>
			<div class="table-responsive">
				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal</th>
							<th>Tipe Sampel</th>
							<th>Penyimpanan</th>
							<th>Nama Produk</th>
							<th>Kode Produksi / Best Before</th>
							<th>Permintaan Analisis</th>
							<th>Supervisor</th>
							<th>LAB</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						function tampilkan_analisis($kode)
						{
							$map = [
								'1' => 'Moisture',
								'2' => 'Salinity',
								'3' => 'pH',
								'4' => 'Bulk Density',
								'5' => 'TPC',
								'6' => 'Enterobacter',
								'7' => 'Salmonella',
								'8' => 'Yeast and Mold',
							];
							return $map[$kode] ?? $kode;
						}

						$no = 1;
						foreach ($analisis as $val):
							$datetime = new DateTime($val->date);
							$datetime = $datetime->format('d-m-Y');
							$bb = new DateTime($val->best_before);
							$bb = $bb->format('d-m-Y');
						?>
							<tr>
								<td class="text-center"><?= $no; ?></td>
								<td><?= $datetime; ?></td>
								<td><?= htmlspecialchars($val->tipe_sampel); ?></td>
								<td><?= htmlspecialchars($val->penyimpanan); ?></td>
								<td><?= htmlspecialchars($val->nama_produk); ?></td>
								<td><?= htmlspecialchars($val->kode_produksi) . " / " . $bb; ?></td>
								<td>
									<?php
									$nilai = explode(',', $val->analisis);
									$hasil = array_map('tampilkan_analisis', array_map('trim', $nilai));
									echo htmlspecialchars(implode(', ', $hasil));
									?>
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
									<?php
									if ($val->status_lab == 0) {
										echo '<span style="color: #99a3a4; font-weight: bold;">Created</span>';
									} elseif ($val->status_lab == 1) {
										echo '<span style="color: #28b463; font-weight: bold;">Accepted</span>';
									} elseif ($val->status_lab == 2) {
										echo '<span style="color: red; font-weight: bold;">Returned</span>';
									}
									?>
								</td>
								<td class="text-center">
									<a href="<?= base_url('analisis/edit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split">
										<span class="text">Edit</span>
									</a>
									<a href="<?= base_url('analisis/analis/' . $val->uuid); ?>" class="btn btn-danger btn-icon-split">
										<span class="text">Analisis</span>
									</a>
									<button
										class="btn btn-success btn-icon-split btn-detail"
										data-uuid="<?= $val->uuid ?>">
										<span class="text">Detail</span>
									</button>
									<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
										<a href="<?= base_url('analisis/status/' . $val->uuid); ?>" class="btn btn-info btn-icon-split">
											<span class="text">Verifikasi</span>
										</a>
									<?php endif; ?>
									<!--   <a href="<?= base_url('analisis/delete/' . $val->uuid); ?>" class="btn btn-danger btn-sm me-1 rounded-circle shadow" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                   <i class="fas fa-trash fa-lg"></i>
                               </a> -->
								</td>
							</tr>
						<?php
							$no++;
						endforeach;
						?>
					</tbody>
				</table>

				<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
				<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

				<script>
					$(document).ready(function() {
						$('.btn-detail').click(function() {
							var uuid = $(this).data('uuid');
							var modal = new bootstrap.Modal(document.getElementById('detailModal'));
							$('#detailModalBody').html(`
                        <div class="text-center">
                            <div class="spinner-border" role="status"></div>
                                <span> Loading...</span>
                        </div>
                    `);
							modal.show();
							$.ajax({
								url: "<?= base_url('analisis/ajax-detail/') ?>" + uuid,
								type: "GET",
								success: function(response) {
									$('#detailModalBody').html(response);
								},
								error: function() {
									$('#detailModalBody').html('<div class="alert alert-danger">Gagal memuat detail.</div>');
								}
							});
						});
					});
				</script>

			</div>
			<?php if (in_array($tipe_user, [0, 1, 2, 9])): ?>
				<br>
				<hr>
				<div class="form-group">
					<form action="<?= base_url('analisis/cetak') ?>" method="post" class="form-inline">
						<label for="tanggal" class="mr-2 font-weight-bold">Pilih Tanggal:</label>
						<input type="date" name="tanggal" class="form-control mr-2" required>
						<button type="submit" class="btn btn-success">
							<i class="fas fa-print fa-sm text-white-50"></i> Cetak PDF
						</button>
					</form>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
</div>
<!-- Modal Detail Analisis -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="detailModalLabel">Detail Permohonan Analisis Sampel Laboratorium</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
	th {
		background-color: #f8f9fc;
	}
</style>