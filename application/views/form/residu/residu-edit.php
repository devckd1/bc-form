<?php
defined('BASEPATH') or exit('No direct script access allowed');

$is_audit = isset($mode) && $mode === 'audit';

$back_url = $is_audit
	? 'residu/audit'
	: 'residu';

$form_url = $is_audit
	? 'residu/edit-audit/' . rawurlencode($residu->uuid)
	: 'residu/edit/' . rawurlencode($residu->uuid);

/*
|--------------------------------------------------------------------------
| Normalisasi khusus tampilan Edit Audit Residu Klorin
|--------------------------------------------------------------------------
| Standar: 0.1 - 5 PPM
| Nilai tengah: 2.55 PPM
| Tidak mengubah database secara langsung.
*/

if ($is_audit) {
	$hasil_asli = trim((string) $residu->hasil_pemeriksaan);

	if ($hasil_asli !== '' && is_numeric($hasil_asli)) {
		$hasil_angka = (float) $hasil_asli;

		if ($hasil_angka < 0.1 || $hasil_angka > 5) {
			$residu->hasil_pemeriksaan = '2.55';
		}
	}

	$hasil_tampil = trim((string) $residu->hasil_pemeriksaan);
	$keterangan_asli = trim((string) $residu->keterangan);

	if ($keterangan_asli !== '') {
		$keterangan_lower = strtolower($keterangan_asli);

		if (in_array($keterangan_lower, ['ok', 'oke'], true)) {
			$residu->keterangan = 'Ok';
		} elseif (is_numeric($hasil_tampil)) {
			$hasil_angka = (float) $hasil_tampil;

			$residu->keterangan = (
				$hasil_angka >= 0.1 && $hasil_angka <= 5
			) ? 'Ok' : 'Tidak Ok';
		}
	}

	// Kosongkan isian catatan dan tindak lanjut pada tampilan Audit.
	$residu->tindakan = '';
	$residu->verifikasi = '';
	$residu->catatan = '';
}
?>

<div class="container-fluid">

	<h1 class="h3 mb-2 text-gray-800">
		<?= $is_audit
			? 'Edit Audit Verifikasi Residu Klorin'
			: 'Edit Verifikasi Residu Klorin'; ?>

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
						? 'Daftar Audit Residu Klorin'
						: 'Daftar Verifikasi Residu Klorin'; ?>
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
			Halaman ini merupakan menu Edit Audit Residu Klorin.
			Perubahan data pada halaman ini tidak akan mengubah data aktual
			pada menu pemeriksaan.
		</div>
	<?php endif; ?>

	<div class="card shadow mb-4">
		<div class="card-body">

			<form class="user" method="post"
				action="<?= base_url($form_url); ?>">

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Tanggal</label>
						<input type="date" name="date"
							class="form-control <?= form_error('date') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('date', $residu->date)); ?>"
							required>
						<div class="invalid-feedback">
							<?= form_error('date'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Area</label>
						<input type="text" name="area"
							class="form-control <?= form_error('area') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('area', $residu->area)); ?>"
							required>
						<div class="invalid-feedback">
							<?= form_error('area'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Titik Sampling</label>
						<input type="text" name="titik_sampling"
							class="form-control <?= form_error('titik_sampling') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('titik_sampling', $residu->titik_sampling)); ?>"
							required>
						<div class="invalid-feedback">
							<?= form_error('titik_sampling'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-2">
						<label class="form-label font-weight-bold">Standar (PPM)</label>
						<p class="form-control-plaintext font-weight-bold">0.1 - 5</p>
						<input type="hidden" name="standar"
							value="<?= html_escape(set_value('standar', $residu->standar)); ?>">
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">
							Hasil Pemeriksaan (PPM)
						</label>
						<input type="text" name="hasil_pemeriksaan"
							class="form-control <?= form_error('hasil_pemeriksaan') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('hasil_pemeriksaan', $residu->hasil_pemeriksaan)); ?>"
							required>
						<div class="invalid-feedback">
							<?= form_error('hasil_pemeriksaan'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Keterangan</label>

						<?php
						$keterangan_value = set_value(
							'keterangan',
							$residu->keterangan
						);
						?>

						<div class="form-check">
							<input class="form-check-input <?= form_error('keterangan') ? 'is-invalid' : ''; ?>"
								type="radio" name="keterangan"
								id="keterangan_ok" value="Ok"
								<?= $keterangan_value === 'Ok' ? 'checked' : ''; ?>>
							<label class="form-check-label" for="keterangan_ok">
								Ok
							</label>
						</div>

						<div class="form-check">
							<input class="form-check-input <?= form_error('keterangan') ? 'is-invalid' : ''; ?>"
								type="radio" name="keterangan"
								id="keterangan_tidak_ok" value="Tidak Ok"
								<?= $keterangan_value === 'Tidak Ok' ? 'checked' : ''; ?>>
							<label class="form-check-label" for="keterangan_tidak_ok">
								Tidak Ok
							</label>
						</div>

						<div class="text-danger small">
							<?= form_error('keterangan'); ?>
						</div>
					</div>
				</div>

				<div class="form-group row">
					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Tindakan Koreksi</label>
						<input type="text" name="tindakan"
							class="form-control <?= form_error('tindakan') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('tindakan', $residu->tindakan)); ?>">
						<div class="invalid-feedback">
							<?= form_error('tindakan'); ?>
						</div>
					</div>

					<div class="col-sm-4">
						<label class="form-label font-weight-bold">Verifikasi</label>
						<input type="text" name="verifikasi"
							class="form-control <?= form_error('verifikasi') ? 'is-invalid' : ''; ?>"
							value="<?= html_escape(set_value('verifikasi', $residu->verifikasi)); ?>">
						<div class="invalid-feedback">
							<?= form_error('verifikasi'); ?>
						</div>
					</div>
				</div>

				<hr>

				<div class="form-group row">
					<div class="col-sm-6">
						<label class="form-label font-weight-bold">Catatan</label>
						<textarea name="catatan"
							class="form-control <?= form_error('catatan') ? 'is-invalid' : ''; ?>"><?= html_escape(set_value('catatan', $residu->catatan)); ?></textarea>
						<div class="invalid-feedback">
							<?= form_error('catatan'); ?>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col">
						<button type="submit" class="btn btn-success">
							<i class="fa fa-save"></i> Simpan
						</button>

						<a href="<?= base_url($back_url); ?>"
							class="btn btn-danger">
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
		background-color: #2E86C1;
	}

	.breadcrumb a,
	.breadcrumb-item.active {
		color: #fff;
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
