
<?php
$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'thermometer/audit'
	: 'thermometer';

$form_url = $is_audit
	? 'thermometer/edit-audit/' . rawurlencode($thermometer->uuid)
	: 'thermometer/edit/' . rawurlencode($thermometer->uuid);

$thermometer_data = json_decode($thermometer->peneraan_hasil, true);

if (!is_array($thermometer_data)) {
	$thermometer_data = [];
}
?>

<div class="container-fluid">

	<div class="d-sm-flex align-items-center justify-content-between mb-3">
		<h1 class="h3 mb-2 text-gray-800">
			<?= $is_audit ? 'Edit Audit Peneraan Thermometer' : 'Update Peneraan Thermometer'; ?>
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
					<?= $is_audit ? 'Daftar Audit Peneraan Thermometer' : 'Daftar Peneraan Thermometer'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">Edit</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert" style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">
			<i class="fas fa-info-circle mr-1"></i>
			<strong>Informasi:</strong>
			Halaman ini merupakan menu Edit Audit Peneraan Thermometer.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<div style="font-size:16px;">
				<strong>Keterangan:</strong>
				<table>
					<tr>
						<td style="width:20px;">-</td>
						<td>Tera thermometer dilakukan setiap awal produksi</td>
					</tr>
					<tr>
						<td>-</td>
						<td>Thermometer ditera dengan dimasukkan sensor di es (0°)</td>
					</tr>
					<tr>
						<td>-</td>
						<td>Jika ada selisih angka display suhu dengan suhu standar es, beri keterangan (+) atau (-) angka selisih (faktor koreksi)</td>
					</tr>
					<tr>
						<td>-</td>
						<td>Jika faktor koreksi &lt; 0,5 °C, thermometer perlu diperbaiki</td>
					</tr>
				</table>
			</div>

			<hr>

			<?php if (empty($thermometer_data)): ?>
				<div class="alert alert-danger">
					Data hasil peneraan tidak valid atau kosong.
				</div>
			<?php endif; ?>

			<form class="user" method="post" action="<?= base_url($form_url); ?>">

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Tanggal</label>
						<input
							type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $thermometer->date)); ?>">

						<div class="invalid-feedback <?= form_error('date') ? 'd-block' : ''; ?>">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Shift</label>
						<select
							class="form-control <?= form_error('shift') ? 'invalid' : ''; ?>"
							name="shift">

							<?php foreach ([1, 2, 3] as $shift): ?>
								<option
									value="<?= $shift; ?>"
									<?= (string) set_value('shift', $thermometer->shift) === (string) $shift ? 'selected' : ''; ?>>
									<?= $shift; ?>
								</option>
							<?php endforeach; ?>
						</select>

						<div class="invalid-feedback <?= form_error('shift') ? 'd-block' : ''; ?>">
							<?= form_error('shift'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="form-area" id="form-thermometer-wrapper">
					<label class="form-label font-weight-bold mb-2">
						Hasil Pemeriksaan
					</label>

					<?php foreach ($thermometer_data as $i => $detail): ?>
						<?php
						$kode_thermo = isset($detail['kode_thermo']) ? $detail['kode_thermo'] : '';
						$model = isset($detail['model']) ? $detail['model'] : '';
						$area = isset($detail['area']) ? $detail['area'] : '';
						$pukul = isset($detail['pukul']) ? $detail['pukul'] : '';
						$standar = isset($detail['standar']) ? $detail['standar'] : '';
						$hasil = isset($detail['hasil']) ? $detail['hasil'] : '';

						$kode_ada_di_master = false;
						$standar_master = '';
						$hasil_master = '';

						if (!empty($list_thermo)) {
							foreach ($list_thermo as $lt) {
								if ((string) $lt->kode_thermometer === (string) $kode_thermo) {
									$kode_ada_di_master = true;
									$standar_master = $lt->standar ?? '';
									$hasil_master = $lt->hasil ?? '';
									$model_master = $lt->model ?? $model;
									$area_master = $lt->area ?? $area;
									break;
								}
							}
						}

						// Mode Audit mengambil standar dan hasil dari master.
						// Mode QC tetap menggunakan data tersimpan.
						if ($is_audit && $kode_ada_di_master) {
							$standar = $standar_master;
							$hasil = $hasil_master;
							$model = $model_master;
							$area = $area_master;
						}
						?>

						<div class="thermometer-group border p-3 mb-3 rounded bg-light"
							data-index="<?= $i; ?>">

							<div class="form-row d-flex flex-wrap align-items-end">

								<div class="col-auto mb-2">
									<label class="small mb-1">Kode Thermometer</label>

									<select name="kode_thermo[]" class="form-control form-control-sm kode-thermo">
										<option value="">Pilih Kode Thermometer</option>

										<?php if ($kode_thermo !== '' && !$kode_ada_di_master): ?>
											<option
												value="<?= html_escape($kode_thermo); ?>"
												data-model="<?= html_escape($model); ?>"
												data-area="<?= html_escape($area); ?>"
												data-standar="<?= html_escape($standar); ?>"
												data-hasil="<?= html_escape($hasil); ?>"
												selected>
												<?= html_escape($kode_thermo); ?> (snapshot)
											</option>
										<?php endif; ?>

										<?php if (!empty($list_thermo)): ?>
											<?php foreach ($list_thermo as $lt): ?>
												<option
													value="<?= html_escape($lt->kode_thermometer); ?>"
													data-model="<?= html_escape($lt->model ?? ''); ?>"
													data-area="<?= html_escape($lt->area ?? ''); ?>"
													data-standar="<?= html_escape($lt->standar ?? ''); ?>"
													data-hasil="<?= html_escape($lt->hasil ?? ''); ?>"
													<?= (string) $kode_thermo === (string) $lt->kode_thermometer ? 'selected' : ''; ?>>
													<?= html_escape($lt->kode_thermometer); ?>
												</option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
								</div>

								<div class="col-auto mb-2">
									<label class="small mb-1">Model</label>
									<input
										type="text"
										name="model[]"
										class="form-control form-control-sm model"
										value="<?= html_escape($model); ?>"
										readonly>
								</div>

								<div class="col-auto mb-2">
									<label class="small mb-1">Area</label>
									<input
										type="text"
										name="area[]"
										class="form-control form-control-sm area"
										value="<?= html_escape($area); ?>">
								</div>

								<div class="col-auto mb-2">
									<label class="small mb-1">Pukul</label>
									<input
										type="time"
										name="pukul[]"
										class="form-control form-control-sm"
										value="<?= html_escape($pukul); ?>">
								</div>

								<div class="col-auto mb-2">
									<label class="small mb-1">Standar Suhu (°C)</label>
									<select name="standar[]" class="form-control form-control-sm standar">
										<option value="0" <?= (string) $standar === '0' ? 'selected' : ''; ?>>
											0°C
										</option>
										<option value="100" <?= (string) $standar === '100' ? 'selected' : ''; ?>>
											100°C
										</option>

										<?php if ($standar !== '' && !in_array((string) $standar, ['0', '100'], true)): ?>
											<option value="<?= html_escape($standar); ?>" selected>
												<?= html_escape($standar); ?>°C
											</option>
										<?php endif; ?>
									</select>
								</div>

								<div class="col-auto mb-2">
									<label class="small mb-1">Hasil</label>
									<input
										type="text"
										name="hasil[]"
										class="form-control form-control-sm hasil"
										value="<?= html_escape($hasil); ?>">
								</div>

								<div class="col-auto mb-2">
									<button type="button" class="btn btn-danger btn-sm btn-remove">
										Hapus
									</button>
								</div>

							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<button type="button" class="btn btn-primary mt-2" id="add-thermometer">
					+ Tambah Pemeriksaan
				</button>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Tindakan Perbaikan</label>
						<textarea
							class="form-control"
							name="tindakan_perbaikan"><?= html_escape(set_value('tindakan_perbaikan', $thermometer->tindakan_perbaikan)); ?></textarea>

						<div class="invalid-feedback <?= form_error('tindakan_perbaikan') ? 'd-block' : ''; ?>">
							<?= form_error('tindakan_perbaikan'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Keterangan</label>
						<textarea
							class="form-control"
							name="keterangan"><?= html_escape(set_value('keterangan', $thermometer->keterangan)); ?></textarea>

						<div class="invalid-feedback <?= form_error('keterangan') ? 'd-block' : ''; ?>">
							<?= form_error('keterangan'); ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-md <?= $is_audit ? 'btn-success' : 'btn-success'; ?> mr-2">
							<i class="fa fa-save"></i>
							<?= $is_audit ? 'Simpan' : 'Simpan'; ?>
						</button>

						<a href="<?= base_url($back_url); ?>" class="btn btn-md btn-danger">
							<i class="fa fa-times"></i> Batal
						</a>
					</div>
				</div>

			</form>
		</div>
	</div>
</div>

<style>
	.breadcrumb {
		background-color: <?= $is_audit ? '#2E86C1' : '#2E86C1'; ?>;
	}

	.breadcrumb a {
		color: #fff;
	}

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 8px 14px;
		border-radius: 6px;
		font-size: 13px;
		font-weight: 700;
		white-space: nowrap;
	}

	.btn-audit {
		background-color: #5a3298;
		border-color: #5a3298;
		color: #fff;
	}

	.btn-audit:hover {
		background-color: #452477;
		border-color: #452477;
		color: #fff;
	}

	.thermometer-group {
		overflow-x: auto;
	}
</style>

<script>
	$(document).ready(function() {

		$('#add-thermometer').on('click', function() {
			let firstGroup = $('.thermometer-group').first();

			if (!firstGroup.length) {
				alert('Tidak ada baris pemeriksaan untuk disalin.');
				return;
			}

			let newGroup = firstGroup.clone();

			newGroup.find('input').val('');
			newGroup.find('select').val('');

			newGroup.find('.model').val('');
			newGroup.find('.area').val('');

			$('#form-thermometer-wrapper').append(newGroup);
		});

		$(document).on('click', '.btn-remove', function() {
			if ($('.thermometer-group').length > 1) {
				$(this).closest('.thermometer-group').remove();
			} else {
				alert('Minimal satu baris harus ada.');
			}
		});

		$(document).on('change', '.kode-thermo', function() {
			let selected = $(this).find('option:selected');
			let parent = $(this).closest('.thermometer-group');

			parent.find('.model').val(selected.attr('data-model') || '');
			parent.find('.area').val(selected.attr('data-area') || '');

			<?php if ($is_audit): ?>
				let standarMaster = selected.attr('data-standar') || '';
				let hasilMaster = selected.attr('data-hasil') || '';

				// Tetap gunakan dropdown standar 0 atau 100.
				if (standarMaster !== '') {
					parent.find('.standar').val(standarMaster);
				}

				parent.find('.hasil').val(hasilMaster);
			<?php endif; ?>
		});

	});
</script>
