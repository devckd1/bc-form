<div class="container-fluid">

	<!-- Page Heading -->
	<h1 class="h3 mb-2 text-gray-800">
		Edit Audit Peneraan Thermometer

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url('thermometer/audit') ?>" class="text-white">
					<i class="fas fa-arrow-left mr-1"></i>
					Daftar Audit Peneraan Thermometer
				</a>
			</li>
			<li class="breadcrumb-item active text-white" aria-current="page">
				Edit Audit
			</li>
		</ol>
	</nav>

	<div class="alert alert-warning shadow-sm">
		<i class="fas fa-info-circle mr-1"></i>
		<strong>Data Audit:</strong>
		Perubahan pada halaman ini hanya mengubah data Audit dan
		<strong>tidak mengubah data Peneraan Thermometer utama.</strong>
	</div>

	<div class="card shadow mb-4 border-left-audit">
		<div class="card-body">

			<div style="font-size: 16px;">
				<strong>Keterangan:</strong><br>

				<table>
					<tr>
						<td style="width: 20px;">-</td>
						<td>Tera thermometer dilakukan setiap awal produksi</td>
					</tr>

					<tr>
						<td>-</td>
						<td>Thermometer ditera dengan dimasukkan sensor di es (0°)</td>
					</tr>

					<tr>
						<td>-</td>
						<td>
							Jika ada selisih angka display suhu dengan suhu standar es,
							beri keterangan (+) atau (-) angka selisih (faktor koreksi)
						</td>
					</tr>

					<tr>
						<td>-</td>
						<td>
							Jika faktor koreksi &lt; 0,5 °C, thermometer perlu diperbaiki
						</td>
					</tr>
				</table>
			</div>

			<hr>

			<form
				class="user"
				method="post"
				action="<?= base_url('thermometer/edit-audit/' . $thermometer->uuid); ?>">

				<!-- Tanggal & Shift -->
				<div class="form-group row">

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Tanggal
						</label>

						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'invalid' : '' ?>"
							value="<?= htmlspecialchars($thermometer->date ?? '', ENT_QUOTES); ?>">

						<div class="invalid-feedback <?= !empty(form_error('date')) ? 'd-block' : '' ?>">
							<?= form_error('date') ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Shift
						</label>

						<select
							class="form-control <?= form_error('shift') ? 'invalid' : '' ?>"
							name="shift">
							<option value="1"
								<?= $thermometer->shift == 1 ? 'selected' : ''; ?>>
								1
							</option>

							<option value="2"
								<?= $thermometer->shift == 2 ? 'selected' : ''; ?>>
								2
							</option>

							<option value="3"
								<?= $thermometer->shift == 3 ? 'selected' : ''; ?>>
								3
							</option>
						</select>

						<div class="invalid-feedback <?= !empty(form_error('shift')) ? 'd-block' : '' ?>">
							<?= form_error('shift') ?>
						</div>
					</div>

				</div>

				<hr>

				<!-- Hasil Pemeriksaan -->
				<div class="form-area" id="form-thermometer-wrapper">

					<label class="form-label font-weight-bold mb-2">
						Hasil Pemeriksaan
					</label>

					<?php
					$thermometer_data = json_decode(
						$thermometer->peneraan_hasil,
						true
					);

					if (!is_array($thermometer_data)) {
						echo "<div class='alert alert-danger'>
                                Data peneraan tidak valid atau kosong.
                              </div>";

						$thermometer_data = [];
					}
					?>

					<?php foreach ($thermometer_data as $i => $detail): ?>

						<?php
						$kode_thermo = isset($detail['kode_thermo'])
							? $detail['kode_thermo']
							: '';

						$model = isset($detail['model'])
							? $detail['model']
							: '';

						$area = isset($detail['area'])
							? $detail['area']
							: '';

						$pukul = isset($detail['pukul'])
							? $detail['pukul']
							: '';

						$standar = isset($detail['standar'])
							? $detail['standar']
							: '';

						$hasil = isset($detail['hasil'])
							? $detail['hasil']
							: '';
						?>

						<div class="thermometer-group border p-3 mb-3 rounded bg-light"
							data-index="<?= $i ?>">

							<div class="form-row d-flex flex-wrap align-items-end">

								<!-- Kode Thermometer -->
								<div class="col-auto mb-2">
									<label class="small mb-1">
										Kode Thermometer
									</label>

									<select
										name="kode_thermo[]"
										class="form-control form-control-sm kode-thermo">
										<option value="" disabled>
											Pilih Kode Thermometer
										</option>

										<?php foreach ($list_thermo as $lt): ?>

											<option
												value="<?= $lt->kode_thermometer ?>"
												data-model="<?= htmlspecialchars($lt->model, ENT_QUOTES) ?>"
												data-area="<?= htmlspecialchars($lt->area, ENT_QUOTES) ?>"
												<?= ($kode_thermo == $lt->kode_thermometer)
													? 'selected'
													: '' ?>>
												<?= $lt->kode_thermometer ?>
											</option>

										<?php endforeach; ?>

									</select>
								</div>

								<!-- Model -->
								<div class="col-auto mb-2">
									<label class="small mb-1">
										Model
									</label>

									<input
										type="text"
										name="model[]"
										class="form-control form-control-sm model"
										value="<?= htmlspecialchars($model, ENT_QUOTES); ?>"
										readonly>
								</div>

								<!-- Area -->
								<div class="col-auto mb-2">
									<label class="small mb-1">
										Area
									</label>

									<input
										type="text"
										name="area[]"
										class="form-control form-control-sm area"
										value="<?= htmlspecialchars($area, ENT_QUOTES); ?>">
								</div>

								<!-- Pukul -->
								<div class="col-auto mb-2">
									<label class="small mb-1">
										Pukul
									</label>

									<input
										type="time"
										name="pukul[]"
										class="form-control form-control-sm"
										value="<?= htmlspecialchars($pukul, ENT_QUOTES); ?>">
								</div>

								<!-- Standar -->
								<div class="col-auto mb-2">
									<label class="small mb-1">
										Standar Suhu (°C)
									</label>

									<select
										name="standar[]"
										class="form-control form-control-sm">
										<option value="0"
											<?= ($standar == '0') ? 'selected' : '' ?>>
											0°C
										</option>

										<option value="100"
											<?= ($standar == '100') ? 'selected' : '' ?>>
											100°C
										</option>

										<?php if (
											$standar != '0' &&
											$standar != '100' &&
											$standar != ''
										): ?>

											<option
												value="<?= htmlspecialchars($standar, ENT_QUOTES); ?>"
												selected>
												<?= htmlspecialchars($standar, ENT_QUOTES); ?>°C
											</option>

										<?php endif; ?>

									</select>
								</div>

								<!-- Hasil -->
								<div class="col-auto mb-2">
									<label class="small mb-1">
										Hasil
									</label>

									<input
										type="text"
										name="hasil[]"
										class="form-control form-control-sm"
										value="<?= htmlspecialchars($hasil, ENT_QUOTES); ?>">
								</div>

								<!-- Hapus -->
								<div class="col-auto mb-2">
									<button
										type="button"
										class="btn btn-danger btn-sm mt-3 btn-remove">
										Hapus
									</button>
								</div>

							</div>
						</div>

					<?php endforeach; ?>

				</div>

				<button
					type="button"
					class="btn btn-primary mt-2"
					id="add-thermometer">
					+ Tambah Pemeriksaan
				</button>

				<hr>

				<!-- Tindakan & Keterangan -->
				<div class="form-group row">

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Tindakan Perbaikan
						</label>

						<textarea
							class="form-control"
							name="tindakan_perbaikan"><?= htmlspecialchars($thermometer->tindakan_perbaikan ?? '', ENT_QUOTES); ?></textarea>

						<div class="invalid-feedback <?= !empty(form_error('tindakan_perbaikan')) ? 'd-block' : '' ?>">
							<?= form_error('tindakan_perbaikan') ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Keterangan
						</label>

						<textarea
							class="form-control"
							name="keterangan"><?= htmlspecialchars($thermometer->keterangan ?? '', ENT_QUOTES); ?></textarea>

						<div class="invalid-feedback <?= !empty(form_error('keterangan')) ? 'd-block' : '' ?>">
							<?= form_error('keterangan') ?>
						</div>
					</div>

				</div>

				<!-- Button -->
				<div class="row">
					<div class="col">

						<button
							type="submit"
							class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i>
							Simpan Audit
						</button>

						<a
							href="<?= base_url('thermometer/audit') ?>"
							class="btn btn-md btn-danger">
							<i class="fa fa-times"></i>
							Batal
						</a>

					</div>
				</div>

			</form>

		</div>
	</div>

</div>

<style>
	.breadcrumb {
		background-color: #2E86C1;
	}

	.badge-mode-audit {
		display: inline-block;
		margin-left: 10px;
		padding: 6px 12px;
		background-color: #5a3298;
		color: #fff;
		border-radius: 20px;
		font-size: 13px;
		font-weight: 600;
		vertical-align: middle;
	}

	.border-left-audit {
		border-left: 5px solid #5a3298 !important;
	}

	.thermometer-group {
		border-color: #ddd !important;
	}
</style>

<script>
	$(document).ready(function() {

		// =============================
		// TAMBAH BARIS
		// =============================
		$('#add-thermometer').click(function() {

			let newGroup = $('.thermometer-group').first().clone();

			newGroup.find(
				'input[type="text"], input[type="number"], input[type="time"]'
			).val('');

			newGroup.find('select').val('');

			newGroup.find('.model').val('');
			newGroup.find('.area').val('');

			$('#form-thermometer-wrapper').append(newGroup);
		});


		// =============================
		// HAPUS BARIS
		// =============================
		$(document).on('click', '.btn-remove', function() {

			if ($('.thermometer-group').length > 1) {

				$(this)
					.closest('.thermometer-group')
					.remove();

			} else {

				alert('Minimal satu baris harus ada.');

			}

		});


		// =============================
		// AUTO FILL MODEL & AREA
		// =============================
		$(document).on('change', '.kode-thermo', function() {

			let selected = $(this).find('option:selected');

			let model = selected.attr('data-model');
			let area = selected.attr('data-area');

			let parent = $(this).closest('.thermometer-group');

			parent.find('.model').val(
				model ? model : ''
			);

			parent.find('.area').val(
				area ? area : ''
			);

		});

	});
</script>
