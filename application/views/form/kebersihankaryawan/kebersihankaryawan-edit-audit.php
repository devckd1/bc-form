<div class="container-fluid">

	<!-- PAGE HEADING -->
	<h1 class="h3 mb-2 text-gray-800">
		Edit Audit Kebersihan Karyawan

		<span class="badge-mode-audit">
			<i class="fas fa-clipboard-check mr-1"></i>
			MODE AUDIT
		</span>
	</h1>

	<!-- BREADCRUMB -->
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">

			<li class="breadcrumb-item">
				<a href="<?= base_url('kebersihankaryawan/audit') ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					Daftar Audit Kebersihan Karyawan
				</a>
			</li>

			<li class="breadcrumb-item active text-white" aria-current="page">
				Edit
			</li>

		</ol>
	</nav>


	<!-- INFORMASI AUDIT -->
	<div class="alert audit-info">

		<i class="fas fa-info-circle mr-1"></i>

		<strong>Informasi Audit:</strong>

		Data yang ditampilkan pada halaman ini merupakan data salinan
		khusus untuk keperluan Audit.

		Perubahan yang dilakukan di sini
		<strong>tidak akan mengubah data pemeriksaan aktual.</strong>

	</div>


	<!-- FORM -->
	<div class="card shadow mb-4 card-audit">

		<div class="card-header py-3 audit-header">

			<h6 class="m-0 font-weight-bold text-white">
				<i class="fas fa-clipboard-check mr-1"></i>
				Edit Data Audit Kebersihan Karyawan
			</h6>

		</div>


		<div class="card-body">

			<form
				class="user"
				method="post"
				action="<?= base_url('kebersihankaryawan/edit-audit/' . $kebersihankaryawan->uuid); ?>">


				<!-- TANGGAL & SHIFT -->
				<div class="form-group row">

					<div class="col-sm-3">

						<label class="form-label font-weight-bold">
							Tanggal
						</label>

						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : '' ?>"
							value="<?= set_value('date', $kebersihankaryawan->date); ?>">

						<div class="invalid-feedback">
							<?= form_error('date') ?>
						</div>

					</div>


					<div class="col-sm-3">

						<label class="form-label font-weight-bold">
							Shift
						</label>

						<select
							class="form-control <?= form_error('shift') ? 'is-invalid' : '' ?>"
							name="shift">

							<option value="1"
								<?= set_select(
									'shift',
									'1',
									$kebersihankaryawan->shift == 1
								); ?>>
								1
							</option>

							<option value="2"
								<?= set_select(
									'shift',
									'2',
									$kebersihankaryawan->shift == 2
								); ?>>
								2
							</option>

							<option value="3"
								<?= set_select(
									'shift',
									'3',
									$kebersihankaryawan->shift == 3
								); ?>>
								3
							</option>

						</select>

						<div class="invalid-feedback">
							<?= form_error('shift') ?>
						</div>

					</div>

				</div>


				<!-- NAMA & BAGIAN -->
				<div class="form-group row">

					<div class="col-sm-3">

						<label class="form-label font-weight-bold">
							Nama
						</label>

						<input
							type="text"
							name="nama"
							class="form-control <?= form_error('nama') ? 'is-invalid' : '' ?>"
							value="<?= set_value('nama', $kebersihankaryawan->nama); ?>">

						<div class="invalid-feedback">
							<?= form_error('nama') ?>
						</div>

					</div>


					<?php

					$plant_uuid = $this->session->userdata('plant');

					$plant_map = [
						'651ac623-5e48-44cc-b2f6-5d622603f53c' => 'Cikande',
						'1eb341e0-1ec4-4484-ba8f-32d23352b84d' => 'Salatiga'
					];

					$plant_name = isset($plant_map[$plant_uuid])
						? $plant_map[$plant_uuid]
						: '-';

					$area_list = [];

					if ($plant_name === 'Salatiga') {

						$area_list = $this->db
							->select('area')
							->from('area_kebersihan')
							->order_by('area', 'ASC')
							->get()
							->result();
					}

					$selected_bagian = set_value(
						'bagian',
						$kebersihankaryawan->bagian ?? ''
					);

					?>


					<div class="col-sm-3">

						<label class="form-label font-weight-bold">
							Bagian
						</label>


						<?php if ($plant_name === 'Cikande'): ?>

							<input
								type="text"
								name="bagian"
								class="form-control <?= form_error('bagian') ? 'is-invalid' : '' ?>"
								value="<?= htmlspecialchars($selected_bagian); ?>">


						<?php elseif ($plant_name === 'Salatiga'): ?>

							<select
								name="bagian"
								class="form-control <?= form_error('bagian') ? 'is-invalid' : '' ?>">

								<option value="">
									-- Pilih Bagian --
								</option>

								<?php foreach ($area_list as $row): ?>

									<option
										value="<?= htmlspecialchars($row->area); ?>"
										<?= ($selected_bagian == $row->area)
											? 'selected'
											: ''; ?>>
										<?= htmlspecialchars($row->area); ?>
									</option>

								<?php endforeach; ?>

							</select>


						<?php else: ?>

							<input
								type="text"
								name="bagian"
								class="form-control"
								placeholder="Plant tidak dikenali">

						<?php endif; ?>


						<div class="invalid-feedback">
							<?= form_error('bagian') ?>
						</div>

					</div>

				</div>


				<hr>

				<label class="form-label font-weight-bold">
					Kebersihan
				</label>


				<!-- BARIS 1 -->
				<div class="form-group row">

					<!-- SERAGAM -->
					<div class="col-sm-3">

						<label class="form-label font-weight-bold">
							Seragam
						</label>

						<br>

						<?php
						$seragam = set_value(
							'seragam',
							$kebersihankaryawan->seragam ?? ''
						);
						?>

						<?php foreach (
							[
								'ok' => 'Ok',
								'tidak oke' => 'Tidak Oke',
								'tidak dipakai' => 'Tidak Dipakai'
							]
							as $val => $label
						): ?>

							<div class="form-check form-check-inline">

								<input
									class="form-check-input"
									type="radio"
									name="seragam"
									value="<?= $val ?>"
									<?= $seragam == $val ? 'checked' : '' ?>>

								<label class="form-check-label">
									<?= $label ?>
								</label>

							</div>

						<?php endforeach; ?>

						<div class="invalid-feedback">
							<?= form_error('seragam') ?>
						</div>

					</div>


					<!-- APRON -->
					<div class="col-sm-3">

						<label class="form-label font-weight-bold">
							Apron
						</label>

						<br>

						<?php
						$apron = set_value(
							'apron',
							$kebersihankaryawan->apron ?? ''
						);
						?>

						<?php foreach (
							[
								'ok' => 'Ok',
								'tidak oke' => 'Tidak Oke',
								'tidak dipakai' => 'Tidak Dipakai'
							]
							as $val => $label
						): ?>

							<div class="form-check form-check-inline">

								<input
									class="form-check-input"
									type="radio"
									name="apron"
									value="<?= $val ?>"
									<?= $apron == $val ? 'checked' : '' ?>>

								<label class="form-check-label">
									<?= $label ?>
								</label>

							</div>

						<?php endforeach; ?>

						<div class="invalid-feedback">
							<?= form_error('apron') ?>
						</div>

					</div>


					<!-- TANGAN KUKU -->
					<div class="col-sm-3">

						<label class="form-label font-weight-bold">
							Tangan dan Kuku
						</label>

						<br>

						<?php
						$tangan_kuku = set_value(
							'tangan_kuku',
							$kebersihankaryawan->tangan_kuku ?? ''
						);
						?>

						<?php foreach (
							[
								'ok' => 'Ok',
								'tidak oke' => 'Tidak Oke',
								'tidak dipakai' => 'Tidak Dipakai'
							]
							as $val => $label
						): ?>

							<div class="form-check form-check-inline">

								<input
									class="form-check-input"
									type="radio"
									name="tangan_kuku"
									value="<?= $val ?>"
									<?= $tangan_kuku == $val ? 'checked' : '' ?>>

								<label class="form-check-label">
									<?= $label ?>
								</label>

							</div>

						<?php endforeach; ?>

						<div class="invalid-feedback">
							<?= form_error('tangan_kuku') ?>
						</div>

					</div>


					<!-- KOSMETIK -->
					<div class="col-sm-3">

						<label class="form-label font-weight-bold">
							Kosmetik
						</label>

						<br>

						<?php
						$kosmetik = set_value(
							'kosmetik',
							$kebersihankaryawan->kosmetik ?? ''
						);
						?>

						<?php foreach (
							[
								'ok' => 'Ok',
								'tidak oke' => 'Tidak Oke',
								'tidak dipakai' => 'Tidak Dipakai'
							]
							as $val => $label
						): ?>

							<div class="form-check form-check-inline">

								<input
									class="form-check-input"
									type="radio"
									name="kosmetik"
									value="<?= $val ?>"
									<?= $kosmetik == $val ? 'checked' : '' ?>>

								<label class="form-check-label">
									<?= $label ?>
								</label>

							</div>

						<?php endforeach; ?>

						<div class="invalid-feedback">
							<?= form_error('kosmetik') ?>
						</div>

					</div>

				</div>


				<!-- BARIS 2 -->
				<div class="form-group row">

					<?php
					$items = [
						'perhiasan' => 'Perhiasan',
						'masker' => 'Masker',
						'topi_hairnet' => 'Topi / Hairnet',
						'sepatu' => 'Sepatu Kerja'
					];
					?>

					<?php foreach ($items as $field => $label): ?>

						<?php
						$value = set_value(
							$field,
							$kebersihankaryawan->{$field} ?? ''
						);
						?>

						<div class="col-sm-3">

							<label class="form-label font-weight-bold">
								<?= $label ?>
							</label>

							<br>

							<?php foreach (
								[
									'ok' => 'Ok',
									'tidak oke' => 'Tidak Oke',
									'tidak dipakai' => 'Tidak Dipakai'
								]
								as $val => $option_label
							): ?>

								<div class="form-check form-check-inline">

									<input
										class="form-check-input"
										type="radio"
										name="<?= $field ?>"
										value="<?= $val ?>"
										<?= $value == $val ? 'checked' : '' ?>>

									<label class="form-check-label">
										<?= $option_label ?>
									</label>

								</div>

							<?php endforeach; ?>

							<div class="invalid-feedback">
								<?= form_error($field) ?>
							</div>

						</div>

					<?php endforeach; ?>

				</div>


				<!-- TINDAKAN & CATATAN -->
				<div class="form-group row">

					<div class="col-sm-6">

						<label class="form-label font-weight-bold">
							Tindakan Koreksi
						</label>

						<textarea
							class="form-control"
							name="tindakan"
							rows="4"><?= set_value(
											'tindakan',
											$kebersihankaryawan->tindakan ?? ''
										); ?></textarea>

						<div class="invalid-feedback">
							<?= form_error('tindakan') ?>
						</div>

					</div>


					<div class="col-sm-6">

						<label class="form-label font-weight-bold">
							Catatan
						</label>

						<textarea
							class="form-control"
							name="catatan"
							rows="4"><?= set_value(
											'catatan',
											$kebersihankaryawan->catatan ?? ''
										); ?></textarea>

						<div class="invalid-feedback">
							<?= form_error('catatan') ?>
						</div>

					</div>

				</div>


				<!-- BUTTON -->
				<div class="row">

					<div class="col">

						<button
							type="submit"
							class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i>
							Simpan
						</button>

						<a
							href="<?= base_url('kebersihankaryawan/audit') ?>"
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

	.breadcrumb a {
		color: #fff !important;
	}

	.card-audit {
		border: 2px solid #5a3298 !important;
		border-radius: 8px;
	}

	.audit-header {
		background-color: #5a3298;
		border-color: #5a3298;
	}

	.audit-info {
		background-color: #f3e8ff;
		border-left: 4px solid #6f42c1;
		color: #4b2a75;
	}

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
