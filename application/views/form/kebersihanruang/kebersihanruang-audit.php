<div class="container-fluid">
	<!-- Page Heading -->
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">Daftar Audit Kebersihan Ruang Produksi
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a class="text-white"
					href="<?= base_url('kebersihanruang'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Chiller
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Kebersihan Ruang.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<!-- Flash Messages -->
	<?php if ($this->session->flashdata('success_msg')): ?>
		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div><br>
	<?php endif ?>

	<?php if ($this->session->flashdata('error_msg')): ?>
		<div class="alert alert-danger text-center">
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div><br>
	<?php endif ?>

	<div class="card shadow mb-4 card-audit">
		<div class="card-body">

			<div class="table-responsive">

				<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">

					<thead>
						<tr>
							<th width="20px" class="text-center">No</th>
							<th>Tanggal / Shift</th>
							<th>Lokasi</th>
							<th class="text-center">Hasil Pemeriksaan</th>
							<th>Last Updated</th>
							<th>Last Verified</th>
							<th>SPV</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>

					<tbody>

						<?php
						$no = 1;

						foreach ($kebersihanruang as $val):

							$tanggal = (new DateTime($val->date))->format('d-m-Y');

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

							<tr>

								<td class="text-center">
									<?= $no++ ?>
								</td>

								<td>
									<?= $tanggal . " / " . $val->shift ?>
								</td>

								<td>
									<?= htmlspecialchars($val->lokasi) ?>
								</td>

								<td class="text-center">

									<?php if (!empty($details) && is_array($details)): ?>

										<button class="btn btn-info btn-sm view-detail" type="button">
											View
										</button>

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

															<td class="text-center">
																<?= $i + 1 ?>
															</td>

															<td>
																<?= htmlspecialchars($row['bagian']) ?>
															</td>

															<td class="text-center">
																<?= $kondisiMap[$row['kondisi']] ?? htmlspecialchars($row['kondisi']) ?>
															</td>

															<td>
																<?= !empty($row['problem'])
																	? htmlspecialchars($row['problem'])
																	: '-' ?>
															</td>

															<td>
																<?= !empty($row['tindakan'])
																	? htmlspecialchars($row['tindakan'])
																	: '-' ?>
															</td>

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

								<td>
									<?= date('H:i - d m Y', strtotime($val->modified_at)) ?>
								</td>

								<td>
									<?= date('H:i - d m Y', strtotime($val->tgl_update_spv)) ?>
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

									<a href="<?= base_url('kebersihanruang/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
										<span class="text">Detail</span>
									</a>
									<a href="<?= base_url('kebersihanruang/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
										<span class="text">Edit</span>
									</a>

								</td>

							</tr>

						<?php endforeach ?>

					</tbody>

				</table>

			</div>

			<br>
			<hr>

			<!-- Form Cetak PDF -->
			<div class="form-group">

				<form action="<?= base_url('kebersihanruang/cetak-audit') ?>"
					method="post"
					class="form-inline">

					<label for="tanggal" class="mr-2 font-weight-bold">
						Pilih Tanggal:
					</label>

					<input type="date"
						name="tanggal"
						class="form-control mr-2"
						required>

					<label for="shift" class="mr-2 font-weight-bold">
						Shift:
					</label>

					<select name="shift"
						class="form-control mr-2"
						required>

						<option value="">
							-- Pilih Shift --
						</option>

						<option value="1">
							Shift 1
						</option>

						<option value="2">
							Shift 2
						</option>

						<option value="3">
							Shift 3
						</option>

					</select>

					<button type="submit"
						class="btn btn-success">

						<i class="fas fa-print fa-sm text-white-50"></i>
						Cetak PDF

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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
	$(document).ready(function() {

		var table = $('#dataTable').DataTable({
			"ordering": false,
			"searching": true
		});

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
