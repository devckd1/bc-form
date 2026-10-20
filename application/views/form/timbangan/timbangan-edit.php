<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit ? 'timbangan/audit' : 'timbangan';

$form_url = $is_audit
	? 'timbangan/edit-audit/' . rawurlencode($timbangan->uuid)
	: 'timbangan/edit/' . rawurlencode($timbangan->uuid);

$judul = $is_audit
	? 'Update Audit Pemeriksaan Timbangan'
	: 'Update Pemeriksaan Timbangan';

$timbangan_data = json_decode($timbangan->peneraan_hasil, true);

if (!is_array($timbangan_data)) {
	$timbangan_data = [];
}

if (empty($timbangan_data)) {
	$timbangan_data = [[
		'kode_timbangan' => '',
		'kapasitas' => '',
		'model' => '',
		'lokasi' => '',
		'peneraan_standar' => '',
		'pukul' => '',
		'hasil' => ''
	]];
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800 font-weight-bold">
			<?= html_escape($judul); ?>

			<?php if ($is_audit): ?>
				<span class="badge-mode-audit">
					<i class="fas fa-clipboard-check mr-1"></i>
					MODE AUDIT
				</span>
			<?php endif; ?>
		</h1>
	</div>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Pemeriksaan Timbangan'
						: 'Daftar Pemeriksaan Timbangan'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>

			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Pemeriksaan Timbangan.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form
				class="user"
				method="post"
				action="<?= base_url($form_url); ?>"
				enctype="multipart/form-data">

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Tanggal</label>

						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $timbangan->date)); ?>"
							required>

						<div class="invalid-feedback">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Shift</label>

						<select
							class="form-control <?= form_error('shift') ? 'is-invalid' : ''; ?>"
							name="shift"
							required>
							<option value="">Pilih Shift</option>

							<?php foreach ([1, 2, 3] as $shift): ?>
								<option
									value="<?= $shift; ?>"
									<?= (string) set_value('shift', $timbangan->shift) === (string) $shift
										? 'selected'
										: ''; ?>>
									<?= $shift; ?>
								</option>
							<?php endforeach; ?>
						</select>

						<div class="invalid-feedback">
							<?= form_error('shift'); ?>
						</div>
					</div>
				</div>


				<div class="form-area" id="form-timbangan-wrapper">
					<label class="form-label font-weight-bold">
						Hasil Pemeriksaan
					</label>

					<?php foreach ($timbangan_data as $i => $detail): ?>
						<?php
						$kode_timbangan = $detail['kode_timbangan'] ?? '';
						$kapasitas = $detail['kapasitas'] ?? '';
						$model = $detail['model'] ?? '';
						$lokasi = $detail['lokasi'] ?? '';
						$peneraan_standar = $detail['peneraan_standar'] ?? '';
						$pukul = $detail['pukul'] ?? '';
						$hasil = $detail['hasil'] ?? '';

						$kode_ada_di_master = false;
						$standar_master = '';

						if (!empty($list_timbangan)) {
							foreach ($list_timbangan as $lt) {
								if ((string) $lt->kode_timbangan === (string) $kode_timbangan) {
									$kode_ada_di_master = true;
									$standar_master = $lt->standar ?? '';
									break;
								}
							}
						}

						$standar_tampil = ($is_audit && $standar_master !== '')
							? $standar_master
							: $peneraan_standar;
						?>

						<div class="timbangan-group border p-3 mb-3 rounded bg-light">
							<div class="form-group row align-items-end">

								<div class="col-sm-2">
									<label>Kode Timbangan</label>
									<select name="kode_timbangan[]" class="form-control kode-timbangan">
										<option value="">Pilih Kode</option>

										<?php if (!empty($kode_timbangan) && !$kode_ada_di_master): ?>
											<option
												value="<?= html_escape($kode_timbangan); ?>"
												data-kapasitas="<?= html_escape($kapasitas); ?>"
												data-model="<?= html_escape($model); ?>"
												data-lokasi="<?= html_escape($lokasi); ?>"
												data-standar="<?= html_escape($peneraan_standar); ?>"
												selected>
												<?= html_escape($kode_timbangan); ?> (Data lama)
											</option>
										<?php endif; ?>

										<?php if (!empty($list_timbangan)): ?>
											<?php foreach ($list_timbangan as $lt): ?>
												<option
													value="<?= html_escape($lt->kode_timbangan); ?>"
													data-kapasitas="<?= html_escape($lt->kapasitas); ?>"
													data-model="<?= html_escape($lt->model); ?>"
													data-lokasi="<?= html_escape($lt->lokasi); ?>"
													data-standar="<?= html_escape($lt->standar ?? ''); ?>"
													<?= (string) $kode_timbangan === (string) $lt->kode_timbangan
														? 'selected'
														: ''; ?>>
													<?= html_escape($lt->kode_timbangan); ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>

								<div class="col-sm-1">
									<label>Kapasitas</label>
									<input type="text" name="kapasitas[]" class="form-control"
										value="<?= html_escape($kapasitas); ?>" readonly>
								</div>

								<div class="col-sm-2">
									<label>Model</label>
									<input type="text" name="model[]" class="form-control"
										value="<?= html_escape($model); ?>" readonly>
								</div>

								<div class="col-sm-2">
									<label>Lokasi</label>
									<input type="text" name="lokasi[]" class="form-control"
										value="<?= html_escape($lokasi); ?>">
								</div>

								<div class="col-sm-1">
									<label>Standar (g)</label>
									<input
										type="text"
										name="peneraan_standar[]"
										class="form-control"
										value="<?= html_escape($standar_tampil); ?>"
										<?= $is_audit ? '' : ''; ?>>
								</div>

								<div class="col-sm-2">
									<label>Pukul</label>
									<input type="time" name="pukul[]" class="form-control"
										value="<?= html_escape($pukul); ?>">
								</div>

								<div class="col-sm-1">
									<label>Hasil</label>
									<input type="text" name="hasil[]" class="form-control"
										value="<?= html_escape($hasil); ?>">
								</div>

								<div class="col-sm-1 text-center">
									<button type="button" class="btn btn-danger btn-remove mt-4">
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
					id="add-timbangan">
					+ Tambah Pemeriksaan
				</button>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Keterangan
						</label>

						<textarea
							class="form-control"
							name="keterangan"><?= html_escape(set_value('keterangan', $timbangan->keterangan ?? '')); ?></textarea>

						<div class="invalid-feedback">
							<?= form_error('keterangan'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Catatan
						</label>

						<textarea
							class="form-control"
							name="catatan"><?= html_escape(set_value('catatan', $timbangan->catatan ?? '')); ?></textarea>

						<div class="invalid-feedback">
							<?= form_error('catatan'); ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-success mr-2">
							<i class="fas fa-save"></i> Simpan
						</button>

						<a
							href="<?= base_url($back_url); ?>"
							class="btn btn-danger">
							<i class="fas fa-times"></i> Batal
						</a>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<style>
	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 6px 12px;
		border-radius: 5px;
		font-size: 13px;
		font-weight: 700;
		vertical-align: middle;
	}

	.breadcrumb {
		background-color: <?= $is_audit ? '#2E86C1' : '#2E86C1'; ?>;
	}

	.breadcrumb .breadcrumb-item a {
		color: #fff;
		font-weight: 500;
	}

	.timbangan-group {
		border-left: 4px solid <?= $is_audit ? '#5a3298' : '#2E86C1'; ?> !important;
	}

	@media (max-width: 768px) {
		.badge-mode-audit {
			margin-top: 8px;
		}
	}
</style>

<script>
	$(document).ready(function() {

		// Tambah baris pemeriksaan
		$('#add-timbangan').on('click', function() {
			let newGroup = $('.timbangan-group').first().clone();

			newGroup.find('input').val('');
			newGroup.find('select').val('');

			$('#form-timbangan-wrapper').append(newGroup);
		});

		// Hapus baris pemeriksaan
		$(document).on('click', '.btn-remove', function() {
			if ($('.timbangan-group').length > 1) {
				$(this).closest('.timbangan-group').remove();
			} else {
				alert('Minimal satu baris pemeriksaan harus ada.');
			}
		});

		// Isi otomatis dari master timbangan
		$(document).on('change', '.kode-timbangan', function() {
			let selected = $(this).find(':selected');
			let parent = $(this).closest('.timbangan-group');

			let kapasitas = selected.attr('data-kapasitas') || '';
			let model = selected.attr('data-model') || '';
			let lokasi = selected.attr('data-lokasi') || '';
			let standar = selected.attr('data-standar') || '';

			parent.find('input[name="kapasitas[]"]').val(kapasitas);
			parent.find('input[name="model[]"]').val(model);
			parent.find('input[name="lokasi[]"]').val(lokasi);

			if (<?= $is_audit ? 'true' : 'false'; ?>) {
				parent.find('input[name="peneraan_standar[]"]').val(standar);
			}
		});

	});
</script>
