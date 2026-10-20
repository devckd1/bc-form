<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-2 text-gray-800">
			Daftar Audit Sanitasi
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
					href="<?= base_url('sanitasi'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Pemeriksaan Sanitasi
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit Pemeriksaan Sanitasi.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
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
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>
		<br>
	<?php endif ?>

	<div class="card shadow mb-4 card-audit">

		<div class="card-body">

			<div class="table-responsive">

				<table class="table table-bordered"
					id="dataTable"
					width="100%"
					cellspacing="0">

					<thead>
						<tr>

							<th class="text-center" width="20px">
								No
							</th>

							<th>
								Tanggal
							</th>

							<th>
								Shift
							</th>

							<th>
								Waktu
							</th>

							<th class="text-center">
								Hasil Pemeriksaan
							</th>

							<th>
								Last Updated
							</th>

							<th>
								SPV
							</th>

							<th class="text-center">
								Action
							</th>

						</tr>
					</thead>

					<tbody>

						<?php
						$no = 1;

						if (!empty($sanitasi)):
							foreach ($sanitasi as $val):

								$tanggal = !empty($val->date)
									? (new DateTime($val->date))->format('d-m-Y')
									: '-';

								$waktu = !empty($val->waktu)
									? (new DateTime($val->waktu))->format('H:i')
									: '-';

								$result = json_decode($val->area, true);
						?>

								<tr>

									<td class="text-center">
										<?= $no++; ?>
									</td>

									<td>
										<?= $tanggal; ?>
									</td>

									<td>
										<?= htmlspecialchars($val->shift ?? '-'); ?>
									</td>

									<td>
										<?= $waktu; ?>
									</td>

									<td>

										<table class="table table-sm table-bordered mb-0">

											<thead style="background-color:#2E86C1; color:black; text-align:center;">

												<tr>

													<th width="25%">
														Area
													</th>

													<th width="15%">
														Aktual
													</th>

													<th width="15%">
														Gambar
													</th>

												</tr>

											</thead>

											<tbody>

												<?php if (!empty($result) && is_array($result)): ?>

													<?php foreach ($result as $row): ?>

														<tr>

															<td>
																<?= htmlspecialchars($row['sub_area'] ?? '-'); ?>
															</td>

															<td class="text-center">
																<?= htmlspecialchars($row['aktual'] ?? '-'); ?>
															</td>

															<td class="text-center">

																<?php if (!empty($row['gambar'])): ?>

																	<a href="<?= base_url('uploads/sanitasi/' . $row['gambar']); ?>"
																		target="_blank"
																		class="btn btn-info btn-sm">

																		<i class="fas fa-image mr-1"></i>
																		Lihat

																	</a>

																<?php else: ?>

																	<span class="text-muted">
																		Tidak ada
																	</span>

																<?php endif; ?>

															</td>

														</tr>

													<?php endforeach; ?>

												<?php else: ?>

													<tr>

														<td colspan="6"
															class="text-center">

															Tidak ada data

														</td>

													</tr>

												<?php endif; ?>

											</tbody>

										</table>

									</td>

									<td>
										<?= !empty($val->modified_at)
											? date('H:i - d m Y', strtotime($val->modified_at))
											: '-'; ?>
									</td>

									<td class="text-center">
										<?php
										switch ($val->status_spv) {
											case 0:
												echo '<span style="color:#99a3a4;font-weight:bold;">Created</span>';
												break;
											case 1:
												echo '<span style="color:#28b463;font-weight:bold;">Verified</span>';
												break;
											case 2:
												echo '<span style="color:red;font-weight:bold;">Revision</span>';
												break;
										}
										?>
									</td>

									<td class="text-center">
										<a href="<?= base_url('sanitasi/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('sanitasi/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
											<span class="text">Edit</span>
										</a>

									</td>

								</tr>

						<?php
							endforeach;
						endif;
						?>

					</tbody>

				</table>

			</div>

			<br>
			<hr>

			<form action="<?= base_url('sanitasi/cetak-audit') ?>"
				method="post"
				target="_blank"
				class="mb-3">

				<div class="form-row align-items-end">

					<div class="col-auto">

						<label for="date" class="font-weight-bold">
							Pilih Tanggal
						</label>

						<input type="date"
							name="date"
							id="date"
							class="form-control"
							required>

					</div>

					<div class="col-auto">

						<button type="submit"
							class="btn btn-success">

							<i class="fas fa-print fa-sm"></i>
							Cetak PDF

						</button>

					</div>

				</div>

			</form>

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
