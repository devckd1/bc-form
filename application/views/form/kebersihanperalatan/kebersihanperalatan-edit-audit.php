<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		Update Audit Kebersihan Peralatan

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('kebersihanperalatan/audit') ?>">
					<i class="fas fa-arrow-left"></i>
					Daftar Audit Kebersihan Peralatan
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<!-- INFO AUDIT -->
	<div class="alert audit-info">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Informasi Audit:</strong>
		Data yang ditampilkan pada halaman ini merupakan data salinan
		khusus untuk keperluan Audit.
		Perubahan yang dilakukan di sini
		<strong>tidak akan mengubah data pemeriksaan aktual.</strong>

	</div>

	<div class="card shadow mb-4">

		<div class="card-header py-3 header-audit">
			<h6 class="m-0 font-weight-bold">
				<i class="fas fa-clipboard-check mr-2"></i>
				Edit Audit Kebersihan Peralatan
			</h6>
		</div>

		<div class="card-body">

			<form
				method="post"
				action="<?= base_url('kebersihanperalatan/edit-audit/' . $kebersihanperalatan->uuid); ?>"
				enctype="multipart/form-data">

				<!-- TANGGAL & SHIFT -->
				<div class="form-group row">

					<div class="col-sm-6">

						<label class="form-label font-weight-bold">
							Tanggal
						</label>

						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : '' ?>"
							value="<?= set_value(
										'date',
										$kebersihanperalatan->date
									) ?>">

						<div class="invalid-feedback">
							<?= form_error('date') ?>
						</div>

					</div>

					<div class="col-sm-6">

						<label class="form-label font-weight-bold">
							Shift
						</label>

						<select
							name="shift"
							class="form-control <?= form_error('shift') ? 'is-invalid' : '' ?>">

							<option value="">
								Pilih Shift
							</option>

							<option
								value="1"
								<?= set_select(
									'shift',
									'1',
									$kebersihanperalatan->shift == 1
								); ?>>
								Shift 1
							</option>

							<option
								value="2"
								<?= set_select(
									'shift',
									'2',
									$kebersihanperalatan->shift == 2
								); ?>>
								Shift 2
							</option>

							<option
								value="3"
								<?= set_select(
									'shift',
									'3',
									$kebersihanperalatan->shift == 3
								); ?>>
								Shift 3
							</option>

						</select>

						<div class="invalid-feedback">
							<?= form_error('shift') ?>
						</div>

					</div>

				</div>

				<hr>

				<h5 class="font-weight-bold mb-3 text-audit">
					<i class="fas fa-tools mr-2"></i>
					Detail Audit Kebersihan Peralatan
				</h5>

				<?php
				$peralatan = json_decode(
					$kebersihanperalatan->peralatan
				);
				?>

				<?php if (!empty($peralatan)): ?>

					<div class="table-responsive">

						<table class="table table-bordered table-hover">

							<thead class="thead-audit text-center">

								<tr>
									<th style="width: 20%;">
										Nama Peralatan
									</th>

									<th style="width: 15%;">
										Kondisi
									</th>

									<th style="width: 30%;">
										Problem
									</th>

									<th style="width: 30%;">
										Tindakan Koreksi
									</th>
								</tr>

							</thead>

							<tbody>

								<?php foreach ($peralatan as $i => $item): ?>

									<?php
									$kondisi = trim($item->kondisi ?? '');

									// Jika kondisi ada isinya apa pun selain kosong,
									// maka dianggap Bersih.
									$kondisi_value = ($kondisi === '') ? '' : 'Bersih';
									?>

									<tr>
										<td>
											<input type="text" name="peralatan[]" class="form-control"
												value="<?= $item->nama; ?>">
										</td>

										<td>
											<select name="kondisi[]" class="form-control">
												<option value="" <?= $kondisi_value === '' ? 'selected' : ''; ?>>
													-
												</option>

												<option value="Bersih" <?= $kondisi_value === 'Bersih' ? 'selected' : ''; ?>>
													Bersih
												</option>
											</select>
										</td>

										<td>
											<textarea
												name="problem[]"
												class="form-control"
												rows="2"><?= htmlspecialchars($item->problem ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
										</td>

										<td>
											<textarea
												name="tindakan[]"
												class="form-control"
												rows="2"><?= htmlspecialchars($item->tindakan ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
										</td>
									</tr>

								<?php endforeach; ?>

							</tbody>

						</table>

					</div>

				<?php else: ?>

					<div class="alert alert-warning">
						<i class="fas fa-exclamation-triangle mr-2"></i>
						Tidak terdapat detail peralatan pada data Audit.
					</div>

				<?php endif; ?>


				<!-- CATATAN -->
				<div class="form-group mt-4">

					<label class="form-label font-weight-bold">
						Catatan
					</label>

					<textarea
						class="form-control"
						name="catatan"
						rows="3"><?= set_value(
										'catatan',
										$kebersihanperalatan->catatan
									) ?></textarea>

					<div class="invalid-feedback <?= form_error('catatan') ? 'd-block' : '' ?>">
						<?= form_error('catatan') ?>
					</div>

				</div>


				<!-- BUTTON -->
				<div class="row mt-4">

					<div class="col">

						<button
							type="submit"
							class="btn btn-success">
							<i class="fa fa-save mr-1"></i>
							Simpan
						</button>

						<a
							href="<?= base_url('kebersihanperalatan/audit') ?>"
							class="btn btn-danger">
							<i class="fa fa-times mr-1"></i>
							Batal
						</a>

					</div>

				</div>

			</form>

		</div>
	</div>

</div>


<style>
	/* =========================
       MODE AUDIT
    ========================= */

	.badge-mode-audit {
		display: inline-block;
		margin-left: 8px;
		padding: 6px 12px;
		background-color: #5a3298;
		color: #fff;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}

	.header-audit {
		background-color: #5a3298;
		color: #fff;
	}

	.btn-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-audit:hover {
		background-color: #48277c;
		border-color: #48277c;
		color: #fff;
	}

	.border-left-audit {
		border-left: 4px solid #5a3298 !important;
	}

	.thead-audit {
		background-color: #2E86C1;
		color: #fff;
	}

	.audit-info {
		background-color: #f3e8ff;
		border-left: 4px solid #6f42c1;
		color: #4b2a75;
	}

	.table td,
	.table th {
		vertical-align: middle !important;
	}

	.table-hover tbody tr:hover {
		background-color: #f7f2fc;
	}

	.form-control:focus {
		border-color: #5a3298;
		box-shadow: 0 0 0 0.2rem rgba(90, 50, 152, 0.15);
	}

	.breadcrumb {
		background-color: #2E86C1;
	}

	.breadcrumb a {
		color: #fff;
	}
</style>
