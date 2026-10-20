<?php
$t = $timbangan;
$timbangan_data = $peneraan_hasil ?? [];

if (!is_array($timbangan_data)) {
	$timbangan_data = [];
}
?>

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

	.badge-mode-audit {
		display: inline-block;
		background-color: #5a3298;
		color: #fff;
		padding: 7px 14px;
		margin-left: 8px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: 700;
		letter-spacing: .5px;
		vertical-align: middle;
		box-shadow: 0 3px 8px rgba(90, 50, 152, .35);
	}

	.audit-info {
		background-color: #f3e8ff;
		border-left: 4px solid #6f42c1;
		color: #4b2a75;
		padding: 12px 15px;
		border-radius: 4px;
	}
</style>

<div class="container-fluid">

	<h1 class="h3 mb-3 text-gray-800">
		Edit Audit Pemeriksaan Timbangan
		<span class="badge-mode-audit">MODE AUDIT</span>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= site_url('timbangan/audit'); ?>">
					<i class="fas fa-arrow-left mr-1"></i>
					Daftar Audit Timbangan
				</a>
			</li>
			<li class="breadcrumb-item active text-white">
				Edit Audit
			</li>
		</ol>
	</nav>

	<div class="audit-info mb-4">
		<strong>Mode Audit:</strong>
		Perubahan di halaman ini hanya memengaruhi data audit,
		bukan data QC aktual atau data master Timbangan.
	</div>

	<?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>

	<div class="card card-audit shadow mb-4">
		<div class="card-body">

			<form method="post"
				action="<?= site_url('timbangan/edit-audit/' . rawurlencode($t->uuid)); ?>">

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="font-weight-bold">Tanggal</label>
						<input type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							required
							value="<?= html_escape(set_value('date', $t->date)); ?>">
						<div class="invalid-feedback">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="font-weight-bold">Shift</label>
						<select name="shift"
							class="form-control <?= form_error('shift') ? 'is-invalid' : ''; ?>"
							required>
							<option value="">Pilih Shift</option>
							<?php for ($s = 1; $s <= 3; $s++): ?>
								<option value="<?= $s; ?>"
									<?= (string) set_value('shift', $t->shift) === (string) $s ? 'selected' : ''; ?>>
									<?= $s; ?>
								</option>
							<?php endfor; ?>
						</select>
						<div class="invalid-feedback">
							<?= form_error('shift'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="font-weight-bold">Petugas QC</label>
						<input type="text"
							class="form-control"
							readonly
							value="<?= html_escape($t->username ?? '-'); ?>">
					</div>
				</div>

				<div id="form-timbangan-wrapper">
					<label class="font-weight-bold">Hasil Pemeriksaan</label>

					<?php if (empty($timbangan_data)): ?>
						<div class="alert alert-warning">
							Tidak ada detail pemeriksaan. Tambahkan baris pemeriksaan di bawah.
						</div>
					<?php endif; ?>

					<?php foreach ($timbangan_data as $i => $detail): ?>
						<div class="timbangan-group border p-3 mb-3 rounded bg-light">

							<div class="form-group row align-items-end">

								<div class="col-md-2 mb-2">
									<label>Kode Timbangan</label>
									<select name="kode_timbangan[]" class="form-control kode-timbangan">
										<option value="">Pilih Kode</option>
										<?php foreach ($list_timbangan as $lt): ?>
											<option value="<?= html_escape($lt->kode_timbangan); ?>"
												data-kapasitas="<?= html_escape($lt->kapasitas); ?>"
												data-model="<?= html_escape($lt->model); ?>"
												data-lokasi="<?= html_escape($lt->lokasi); ?>"
												<?= ($detail['kode_timbangan'] ?? '') == $lt->kode_timbangan ? 'selected' : ''; ?>>
												<?= html_escape($lt->kode_timbangan); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>

								<div class="col-md-1 mb-2">
									<label>Kapasitas</label>
									<input type="text"
										name="kapasitas[]"
										class="form-control"
										readonly
										value="<?= html_escape($detail['kapasitas'] ?? ''); ?>">
								</div>

								<div class="col-md-2 mb-2">
									<label>Model</label>
									<input type="text"
										name="model[]"
										class="form-control"
										readonly
										value="<?= html_escape($detail['model'] ?? ''); ?>">
								</div>

								<div class="col-md-2 mb-2">
									<label>Lokasi</label>
									<input type="text"
										name="lokasi[]"
										class="form-control"
										value="<?= html_escape($detail['lokasi'] ?? ''); ?>">
								</div>

								<div class="col-md-1 mb-2">
									<label>Standar (g)</label>
									<input type="text"
										name="peneraan_standar[]"
										class="form-control"
										value="<?= html_escape($detail['peneraan_standar'] ?? ''); ?>">
								</div>

								<div class="col-md-2 mb-2">
									<label>Pukul</label>
									<input type="time"
										name="pukul[]"
										class="form-control"
										value="<?= html_escape($detail['pukul'] ?? ''); ?>">
								</div>

								<div class="col-md-1 mb-2">
									<label>Hasil</label>
									<input type="text"
										name="hasil[]"
										class="form-control"
										value="<?= html_escape($detail['hasil'] ?? ''); ?>">
								</div>

								<div class="col-md-1 mb-2 text-center">
									<button type="button"
										class="btn btn-danger btn-remove">
										Hapus
									</button>
								</div>

							</div>
						</div>
					<?php endforeach; ?>
				</div>

				<button type="button"
					class="btn btn-outline-primary mt-2"
					id="add-timbangan">
					<i class="fas fa-plus mr-1"></i> Tambah Pemeriksaan
				</button>

				<hr>

				<div class="form-group row">
					<div class="col-md-6">
						<label class="font-weight-bold">Keterangan</label>
						<textarea name="keterangan"
							class="form-control"
							rows="3"><?= html_escape(set_value('keterangan', $t->keterangan ?? '')); ?></textarea>
					</div>

					<div class="col-md-6">
						<label class="font-weight-bold">Catatan</label>
						<textarea name="catatan"
							class="form-control"
							rows="3"><?= html_escape(set_value('catatan', $t->catatan ?? '')); ?></textarea>
					</div>
				</div>

				<div class="d-flex justify-content-between">
					<a href="<?= site_url('timbangan/audit'); ?>"
						class="btn btn-secondary">
						<i class="fas fa-arrow-left mr-1"></i> Kembali
					</a>

					<button type="submit" class="btn btn-primary">
						<i class="fas fa-save mr-1"></i> Simpan Audit
					</button>
				</div>

			</form>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {

		$('#add-timbangan').on('click', function() {
			let first = $('.timbangan-group').first();

			if (!first.length) {
				alert('Tidak ada baris acuan. Muat ulang halaman atau tambahkan detail awal.');
				return;
			}

			let newGroup = first.clone();

			newGroup.find('input').val('');
			newGroup.find('select').val('');

			$('#form-timbangan-wrapper').append(newGroup);
		});

		$(document).on('click', '.btn-remove', function() {
			if ($('.timbangan-group').length > 1) {
				$(this).closest('.timbangan-group').remove();
			} else {
				alert('Minimal satu baris pemeriksaan harus tersedia.');
			}
		});

		$(document).on('change', '.kode-timbangan', function() {
			let selected = $(this).find(':selected');
			let parent = $(this).closest('.timbangan-group');

			parent.find('input[name="kapasitas[]"]').val(
				selected.data('kapasitas') || ''
			);

			parent.find('input[name="model[]"]').val(
				selected.data('model') || ''
			);

			parent.find('input[name="lokasi[]"]').val(
				selected.data('lokasi') || ''
			);
		});

	});
</script>
