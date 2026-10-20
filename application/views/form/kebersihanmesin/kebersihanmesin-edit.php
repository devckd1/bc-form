<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'kebersihanmesin/audit'
	: 'kebersihanmesin';

$form_url = $is_audit
	? 'kebersihanmesin/edit-audit/' . rawurlencode($kebersihanmesin->uuid)
	: 'kebersihanmesin/edit/' . rawurlencode($kebersihanmesin->uuid);

$page_title = $is_audit
	? 'Audit Pemeriksaan Kebersihan dan Sanitasi setelah Perbaikan Mesin'
	: 'Update Pemeriksaan Kebersihan dan Sanitasi setelah Perbaikan Mesin';

/*
|--------------------------------------------------------------------------
| Normalisasi nilai khusus tampilan Edit Audit
|--------------------------------------------------------------------------
| QC tidak diubah.
| Database dan master tidak diubah oleh kode ini.
| Nilai kosong tetap kosong.
*/
if ($is_audit) {
	$normalisasi_audit = function ($nilai, $jenis) {
		$nilai = trim((string) $nilai);

		// Jika kosong, biarkan tetap kosong.
		if ($nilai === '') {
			return '';
		}

		$nilai_lower = strtolower($nilai);

		if ($jenis === 'kondisi') {
			// Nilai positif atau OK/OKE.
			if (in_array($nilai_lower, ['bersih', 'ok', 'oke'], true)) {
				return 'Bersih';
			}

			// Normalisasi ke kondisi ideal sesuai aturan Audit.
			if ($nilai_lower === 'kotor') {
				return 'Bersih';
			}

			return $nilai;
		}

		if ($jenis === 'spare_part') {
			// Nilai positif atau OK/OKE.
			if (in_array($nilai_lower, ['ada', 'ok', 'oke'], true)) {
				return 'Ada';
			}

			// Normalisasi ke kondisi ideal sesuai aturan Audit.
			if ($nilai_lower === 'tidak ada') {
				return 'Ada';
			}

			return $nilai;
		}

		return $nilai;
	};

	// Normalisasi nilai sebelum form ditampilkan.
	// Tidak menggunakan empty($_POST), agar nilai tampilan
	// tetap konsisten ketika form dimuat kembali.
	$kebersihanmesin->kondisi = $normalisasi_audit(
		$kebersihanmesin->kondisi,
		'kondisi'
	);

	$kebersihanmesin->spare_part = $normalisasi_audit(
		$kebersihanmesin->spare_part,
		'spare_part'
	);
}
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		<?= html_escape($page_title); ?>

		<?php if ($is_audit): ?>
			<span class="badge-mode-audit">
				<i class="fas fa-clipboard-check mr-1"></i>
				MODE AUDIT
			</span>
		<?php endif; ?>
	</h1>

	<nav aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="<?= base_url($back_url); ?>">
					<i class="fas fa-arrow-left"></i>
					<?= $is_audit
						? 'Daftar Audit Kebersihan dan Sanitasi Mesin'
						: 'Daftar Pemeriksaan Kebersihan dan Sanitasi Mesin'; ?>
				</a>
			</li>
			<li class="breadcrumb-item active" aria-current="page">
				Edit
			</li>
		</ol>
	</nav>

	<?php if ($is_audit): ?>
		<div class="alert"
			style="background-color: #f3e8ff; border-left: 4px solid #6f42c1; color: #4b2a75;">

			<i class="fas fa-info-circle mr-1"></i>
			<strong>Informasi:</strong>

			Halaman ini merupakan menu Edit Audit Kebersihan dan Sanitasi Mesin.
			Perubahan pada halaman ini ditujukan untuk data Audit dan tidak
			mengubah data aktual pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form class="user"
				method="post"
				action="<?= base_url($form_url); ?>">

				<div class="form-group row">

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Tanggal
						</label>

						<input type="date"
							name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $kebersihanmesin->date)); ?>"
							required>

						<div class="invalid-feedback">
							<?= form_error('date'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Shift
						</label>

						<?php $shift_value = set_value('shift', $kebersihanmesin->shift); ?>

						<select name="shift"
							class="form-control <?= form_error('shift') ? 'is-invalid' : ''; ?>"
							required>

							<option value="1" <?= $shift_value == '1' ? 'selected' : ''; ?>>
								1
							</option>

							<option value="2" <?= $shift_value == '2' ? 'selected' : ''; ?>>
								2
							</option>

							<option value="3" <?= $shift_value == '3' ? 'selected' : ''; ?>>
								3
							</option>
						</select>

						<div class="invalid-feedback">
							<?= form_error('shift'); ?>
						</div>
					</div>

				</div>

				<div class="form-group row">

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Mesin / Peralatan
						</label>

						<input type="text"
							name="mesin"
							class="form-control <?= form_error('mesin') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('mesin', $kebersihanmesin->mesin)); ?>"
							required>

						<div class="invalid-feedback">
							<?= form_error('mesin'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Jenis Perbaikan
						</label>

						<input type="text"
							name="perbaikan"
							class="form-control <?= form_error('perbaikan') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('perbaikan', $kebersihanmesin->perbaikan)); ?>"
							required>

						<div class="invalid-feedback">
							<?= form_error('perbaikan'); ?>
						</div>
					</div>

				</div>

				<div class="form-group row">

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Area
						</label>

						<input type="text"
							name="area"
							class="form-control <?= form_error('area') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('area', $kebersihanmesin->area)); ?>">

						<div class="invalid-feedback">
							<?= form_error('area'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Tanggal Perbaikan
						</label>

						<input type="date"
							name="tgl_perbaikan"
							class="form-control <?= form_error('tgl_perbaikan') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('tgl_perbaikan', $kebersihanmesin->tgl_perbaikan)); ?>">

						<div class="invalid-feedback">
							<?= form_error('tgl_perbaikan'); ?>
						</div>
					</div>

				</div>

				<div class="form-group row">

					<div class="col-sm-6">
						<label class="form-label font-weight-bold d-block">
							Kondisi
						</label>

						<?php
						$kondisi_value = set_value(
							'kondisi',
							$kebersihanmesin->kondisi
						);
						?>

						<div class="form-check form-check-inline">
							<input
								class="form-check-input <?= form_error('kondisi') ? 'is-invalid' : ''; ?>"
								type="radio"
								name="kondisi"
								id="kondisi_bersih"
								value="Bersih"
								<?= $kondisi_value === 'Bersih' ? 'checked' : ''; ?>>

							<label class="form-check-label" for="kondisi_bersih">
								Bersih
							</label>
						</div>

						<div class="form-check form-check-inline">
							<input
								class="form-check-input <?= form_error('kondisi') ? 'is-invalid' : ''; ?>"
								type="radio"
								name="kondisi"
								id="kondisi_kotor"
								value="Kotor"
								<?= $kondisi_value === 'Kotor' ? 'checked' : ''; ?>>

							<label class="form-check-label" for="kondisi_kotor">
								Kotor
							</label>
						</div>

						<div class="text-danger small">
							<?= form_error('kondisi'); ?>
						</div>
					</div>

					<div class="col-sm-6">
						<label class="form-label font-weight-bold d-block">
							Spare Part
						</label>

						<?php
						$spare_part_value = set_value(
							'spare_part',
							$kebersihanmesin->spare_part
						);
						?>

						<div class="form-check form-check-inline">
							<input
								class="form-check-input <?= form_error('spare_part') ? 'is-invalid' : ''; ?>"
								type="radio"
								name="spare_part"
								id="spare_part_ada"
								value="Ada"
								<?= $spare_part_value === 'Ada' ? 'checked' : ''; ?>>

							<label class="form-check-label" for="spare_part_ada">
								Ada
							</label>
						</div>

						<div class="form-check form-check-inline">
							<input
								class="form-check-input <?= form_error('spare_part') ? 'is-invalid' : ''; ?>"
								type="radio"
								name="spare_part"
								id="spare_part_tidak"
								value="Tidak Ada"
								<?= $spare_part_value === 'Tidak Ada' ? 'checked' : ''; ?>>

							<label class="form-check-label" for="spare_part_tidak">
								Tidak Ada
							</label>
						</div>

						<div class="text-danger small">
							<?= form_error('spare_part'); ?>
						</div>
					</div>

				</div>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">
							Keterangan
						</label>

						<textarea
							name="keterangan"
							class="form-control <?= form_error('keterangan') ? 'is-invalid' : ''; ?>"><?= html_escape(set_value('keterangan', $kebersihanmesin->keterangan)); ?></textarea>

						<div class="invalid-feedback">
							<?= form_error('keterangan'); ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">

						<button type="submit"
							class="btn btn-md btn-success mr-2">
							<i class="fa fa-save"></i>
							Simpan
						</button>

						<a href="<?= base_url($back_url); ?>"
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

	.breadcrumb a,
	.breadcrumb-item.active {
		color: #fff;
	}

	.badge-mode-audit {
		display: inline-block;
		background: #5a3298;
		color: #fff;
		padding: 6px 10px;
		border-radius: 5px;
		font-size: 12px;
		font-weight: 700;
		vertical-align: middle;
	}
</style>
