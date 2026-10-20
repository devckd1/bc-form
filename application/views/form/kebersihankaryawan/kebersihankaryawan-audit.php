<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-4">

		<div>
			<h1 class="h3 mb-2 text-gray-800">
				Daftar Audit Kebersihan Karyawan

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
					href="<?= base_url('kebersihankaryawan'); ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit kebersihan karyawan
				</a>
			</li>
		</ol>
	</nav>

	<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi:</strong>
		Halaman ini merupakan menu Audit kebersihan karyawan.
		Data yang ditampilkan merupakan salinan data untuk keperluan Audit.
		Perubahan atau pembaruan data pada halaman ini tidak akan mengubah data aktual pada menu pemeriksaan.
	</div>

	<?php if ($this->session->flashdata('success_msg')): ?>

		<div class="alert alert-success text-center">
			<i class="fas fa-check"></i>
			<?= $this->session->flashdata('success_msg') ?>
		</div>

	<?php endif ?>

	<?php if ($this->session->flashdata('error_msg')): ?>

		<div class="alert alert-danger text-center">
			<i class="fas fa-times"></i>
			<?= $this->session->flashdata('error_msg') ?>
		</div>

	<?php endif ?>


	<div class="card shadow mb-4">

		<div class="card-body">

			<!-- KETERANGAN -->
			<div style="display: flex; gap: 20px; margin-bottom: 30px;">

				<table border="1"
					cellpadding="8"
					cellspacing="0"
					style="border-collapse: collapse;
                           width: 40%;
                           text-align: left;
                           font-family: Arial, sans-serif;
                           font-size: 14px;">

					<thead style="background-color: #f2f2f2;">

						<tr>
							<th colspan="2"
								style="padding: 10px;
                                       background-color: #ADD8E6;
                                       color: gray;">
								Keterangan Pemeriksaan
							</th>
						</tr>

					</thead>

					<tbody>

						<tr>
							<td>1. Seragam</td>
							<td>5. Perhiasan</td>
						</tr>

						<tr>
							<td>2. Apron</td>
							<td>6. Masker</td>
						</tr>

						<tr>
							<td>3. Tangan dan Kuku</td>
							<td>7. Topi / Hairnet</td>
						</tr>

						<tr>
							<td>4. Kosmetik</td>
							<td>8. Sepatu Kerja</td>
						</tr>

					</tbody>

				</table>


				<table border="1"
					cellpadding="8"
					cellspacing="0"
					style="border-collapse: collapse;
                           width: 30%;
                           text-align: center;
                           font-family: Arial, sans-serif;
                           font-size: 14px;">

					<thead style="background-color: #f2f2f2;">

						<tr>
							<th colspan="2"
								style="padding: 10px;
                                       background-color: #ADD8E6;
                                       color: gray;">
								Simbol Keterangan
							</th>
						</tr>

					</thead>

					<tbody>

						<tr>
							<td>✔️</td>
							<td>Ok</td>
						</tr>

						<tr>
							<td>❌</td>
							<td>Tidak Ok</td>
						</tr>

						<tr>
							<td>−</td>
							<td>Tidak ada / Tidak digunakan</td>
						</tr>

					</tbody>

				</table>

			</div>


			<!-- DATA AUDIT -->
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
								Nama
							</th>

							<th>
								Bagian
							</th>

							<th>
								1
							</th>

							<th>
								2
							</th>

							<th>
								3
							</th>

							<th>
								4
							</th>

							<th>
								5
							</th>

							<th>
								6
							</th>

							<th>
								7
							</th>

							<th>
								8
							</th>

							<th>
								Tindakan Koreksi
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

						<?php if (!empty($kebersihankaryawan)): ?>

							<?php
							$no = 1;

							foreach ($kebersihankaryawan as $val):

								$tanggal = !empty($val->date)
									? date('d-m-Y', strtotime($val->date))
									: '-';

								$last_updated = !empty($val->modified_at)
									? date('H:i - d m Y', strtotime($val->modified_at))
									: '-';

								$last_verified = !empty($val->tgl_update_spv)
									? date('H:i - d m Y', strtotime($val->tgl_update_spv))
									: '-';
							?>

								<tr>

									<td class="text-center">
										<?= $no++ ?>
									</td>

									<td>
										<?= $tanggal ?>
									</td>

									<td>
										<?= $val->nama ?>
									</td>

									<td>
										<?= $val->bagian ?>
									</td>


									<td class="text-center">
										<?= ($val->seragam == 'ok')
											? '✔️'
											: (($val->seragam == 'tidak oke') ? '❌' : '−') ?>
									</td>

									<td class="text-center">
										<?= ($val->apron == 'ok')
											? '✔️'
											: (($val->apron == 'tidak oke') ? '❌' : '−') ?>
									</td>

									<td class="text-center">
										<?= ($val->tangan_kuku == 'ok')
											? '✔️'
											: (($val->tangan_kuku == 'tidak oke') ? '❌' : '−') ?>
									</td>

									<td class="text-center">
										<?= ($val->kosmetik == 'ok')
											? '✔️'
											: (($val->kosmetik == 'tidak oke') ? '❌' : '−') ?>
									</td>

									<td class="text-center">
										<?= ($val->perhiasan == 'ok')
											? '✔️'
											: (($val->perhiasan == 'tidak oke') ? '❌' : '−') ?>
									</td>

									<td class="text-center">
										<?= ($val->masker == 'ok')
											? '✔️'
											: (($val->masker == 'tidak oke') ? '❌' : '−') ?>
									</td>

									<td class="text-center">
										<?= ($val->topi_hairnet == 'ok')
											? '✔️'
											: (($val->topi_hairnet == 'tidak oke') ? '❌' : '−') ?>
									</td>

									<td class="text-center">
										<?= ($val->sepatu == 'ok')
											? '✔️'
											: (($val->sepatu == 'tidak oke') ? '❌' : '−') ?>
									</td>


									<td>
										<?= !empty($val->tindakan)
											? $val->tindakan
											: '-' ?>
									</td>

									<td class="text-center">

										<?php

										if ($val->status_spv == 0) {

											echo '<span style="color: #99a3a4; font-weight: bold;">
                                                    Created
                                                  </span>';
										} elseif ($val->status_spv == 1) {

											echo '<span style="color: #28b463; font-weight: bold;">
                                                    Verified
                                                  </span>';
										} elseif ($val->status_spv == 2) {

											echo '<span style="color: red; font-weight: bold;">
                                                    Revision
                                                  </span>';
										} else {

											echo '-';
										}

										?>

									</td>


									<td class="text-center">

										<a href="<?= base_url('kebersihankaryawan/detail-audit/' . $val->uuid); ?>" class="btn btn-success btn-icon-split btn-sm">
											<span class="text">Detail</span>
										</a>
										<a href="<?= base_url('kebersihankaryawan/edit-audit/' . $val->uuid); ?>" class="btn btn-warning btn-icon-split btn-sm">
											<span class="text">Edit</span>
										</a>

									</td>

								</tr>

							<?php endforeach; ?>

						<?php else: ?>

							<tr>

								<td colspan="17"
									class="text-center text-muted">

									<i class="fas fa-info-circle mr-1"></i>
									Belum ada data Audit Kebersihan Karyawan.

								</td>

							</tr>

						<?php endif; ?>

					</tbody>

				</table>

			</div>


			<br>

			<hr>


			<!-- CETAK -->
			<div class="form-group">

				<form action="<?= base_url('kebersihankaryawan/cetak-audit'); ?>"
					method="post"
					class="form-inline">

					<label class="mr-2 font-weight-bold">
						Pilih Tanggal:
					</label>

					<input type="date"
						name="tanggal"
						class="form-control mr-2"
						required>

					<button type="submit"
						class="btn btn-success mr-2">

						<i class="fas fa-print fa-sm text-white-50"></i>
						Cetak PDF

					</button>


					<?php if (
						$this->session->userdata('plant')
						!= '651ac623-5e48-44cc-b2f6-5d622603f53c'
					) : ?>

						<button type="submit"
							formaction="<?= base_url('form/pemeriksaan_gabungan/cetak'); ?>"
							class="btn btn-primary">

							<i class="fas fa-file-pdf fa-sm text-white-50"></i>
							Cetak Gabungan

						</button>

					<?php endif; ?>

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

	table.table {
		border-collapse: collapse !important;
	}

	table.table td,
	table.table th {
		border: 1px solid #dee2e6 !important;
		vertical-align: middle;
		padding: 10px;
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
